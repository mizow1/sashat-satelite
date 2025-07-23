<?php

class SS3{
	private static $exec_enc = '';
	private static $s3_bucket_list = array();
	private static $sh_file_path = BACKGROUND_DIR.'cron/s3_sync_upload.sh';
	private static $ct_sh_file_path = BACKGROUND_DIR.'cron/s3_sync_upload_ct.sh';
	private static $sh_log_path = LOG_DIR.'cron/s3_sync_upload.sh.log';

	public function __construct(){
		throw new RuntimeException('このクラスはインスタンス化禁止です');
	}

	public static function init(){
		// AWSに設定されているEnvironmentタグ内容を取得
		// 空の場合、AWS環境ではないとし、S3のインスタンスを作成せずに終了。
		self::$exec_enc = SAWS::getAwsEnv();
		if(empty(self::$exec_enc)){
			return false;
		}

		$s3_access_key = Settings::getById('s3_access_key');
		$s3_sercret_access_key = Settings::getById('s3_sercret_access_key');

		if(empty($s3_access_key) || empty($s3_sercret_access_key)){
			ErrorLog::write('[Error] S3 setting empty.');
			return false;
		}

		// バケットのマスター取得
		$s3_bucket_json = Settings::getById('s3_bucket_json');
		if(empty($s3_bucket_json)){
			ErrorLog::write('[Error] S3 bucket setting empty.');
			return false;
		}
		$s3_bucket_list = @json_decode($s3_bucket_json, true);
		if(empty($s3_bucket_list)){
			ErrorLog::write('[Error] S3 bucket setting irregular.');
			return false;
		}

		self::$s3_bucket_list = self::formatBucket($s3_bucket_list);

		require_once(LIB_DIR.'local/aws/StaticCloudFrontClass.php');
		SCF::init();

		return true;
	}

	/**
	 * S3間でのコピー
	 *
	 * @param string $source_path コピー元フルパス
	 * @param string $dest_path コピー先フルパス（省略の場合は「コピー元フルパス」を使用する）
	 * @param string $content_type コンテントタイプ
	 * @param boolean $cloudfront_cache_clear_flag Cloudfrontのキャッシュクリア実行フラグ
	 * @return boolean
	 */
	public static function copyS3ToS3($source_path='', $dest_path='', $content_type='', $cloudfront_cache_clear_flag=false){
		if(empty($source_path)){
			return false;
		}

		if(empty($dest_path)){
			$dest_path = $source_path;
		}

		if(strpos($source_path, DOCUMENT_ROOT) === false){
			ErrorLog::write('[Error] Not document root file:'.$source_path);
			return false;
		}
		if(strpos($dest_path, DOCUMENT_ROOT) === false){
			ErrorLog::write('[Error] Not document root file:'.$dest_path);
			return false;
		}

		$source_target_bucket = self::getS3BucketNameAndPath($source_path);
		$dest_target_bucket = self::getS3BucketNameAndPath($dest_path);

		if(empty($source_target_bucket['bucket'])){
			ErrorLog::write('[Error] no bucket setting. file:'.$source_path);
			return false;
		}
		if(empty($dest_target_bucket['bucket'])){
			ErrorLog::write('[Error] no bucket setting. file:'.$dest_path);
			return false;
		}

		$output = array();
		$ret_val = 0;
		$cmd = 'aws s3 cp --no-guess-mime-type';
		if(!empty($content_type)){
			$cmd .= ' --content-type "'.$content_type.'"';
		}
		$cmd .= ' --metadata-directive "REPLACE" --recursive s3://'.$source_target_bucket['bucket'].'/'.$source_target_bucket['path'].' s3://'.$dest_target_bucket['bucket'].'/'.$dest_target_bucket['path'].' 1>>'.LOG_DIR.'cron/copy_s3_to_s3.log 2>&1';

		exec($cmd, $output, $ret_val);
		if($ret_val != 0){
			ErrorLog::write('[Error] code:'.$ret_val.' command:'.$cmd);
			return false;
		}

		if(!empty($content_type)){
			if($cloudfront_cache_clear_flag){
				// cloudfrontのキャッシュクリア
				SCF::CacheClear($dest_path);
			}
		}

		return true;
	}

	/**
	 * 【個別版】ファイルおよびディレクトリの転送（削除含む）
	 *
	 * 転送or削除を自動判別しS3に対して実行する。
	 * 転送ソースがファイルの場合：
	 *   実態ファイルが存在すれば転送、
	 *   実態ファイルが存在しなければ削除
	 * 転送ソースがディレクトリの場合：
	 *   実態ディレクトリが存在すればディレクトリごと転送、
	 *   実態ディレクトリが存在しない場合はディレクトリごと削除。
	 *
	 * @param string $source_path 転送ソース元フルパス
	 * @param string $bucket_pair_path バケットと対になるフルパス（省略の場合は「転送ソース元」を使用する）
	 * @param boolean $background_flag S3への転送処理をバックグラウンドに回すフラグ
	 * @param boolean $src_del_flag 転送後、転送ソース元ファイル削除フラグ
	 * @param string $controller_name ソース元機能名
	 * @param boolean $cloudfront_cache_clear_flag Cloudfrontのキャッシュクリア実行フラグ
	 * @return boolean
	 **/
	public static function syncUpload($source_path='', $bucket_pair_path='', $background_flag=false, $src_del_flag=false, $controller_name="", $cloudfront_cache_clear_flag=false){
		if(empty($source_path)){
			return false;
		}

		if(empty($bucket_pair_path)){
			$bucket_pair_path = $source_path;
		}

		$target_bucket = self::getS3BucketNameAndPath($bucket_pair_path);

		// バケットが特定できなかったので終了
		if(empty($target_bucket['bucket'])){
			ErrorLog::write('[Error] no bucket setting. file:'.$bucket_pair_path);
			return false;
		}

		// ドキュメントルート以下のファイルではなく、bucketの対応先も存在しなかった場合同期を行わずに終了
		if(
			strpos($bucket_pair_path, DOCUMENT_ROOT) === false &&
			!empty($target_bucket['default'])
		){
			ErrorLog::write('[Error] Not document root file. And no bucket setting. file:'.$bucket_pair_path);
			return false;
		}

		if(
			file_exists($source_path) &&
			is_file($source_path) &&
			empty(self::sizeCheck($source_path))
		){
			return false;
		}

		$src_del_flag = empty($src_del_flag) ? 0 : 1;
		$output = array();
		$ret_val = 0;

		if(!is_dir($source_path)){
			$source_path_dir = dirname($source_path);
		}else{
			$source_path_dir = $source_path;
		}
		if(mb_substr($source_path_dir, -1) != '/'){
			$source_path_dir = $source_path_dir.'/';
		}

		// ファイル管理からの転送の場合、ContentType設定を反映させる
		if($controller_name == "files"){
			require_once(MODEL.'administrator/files_config/FilesConfigModelClass.php');
			$objFilesConfigModel = new FilesConfigModel();
			$content_type_config = $objFilesConfigModel->getData('config');

			if(!empty($content_type_config)){
				$content_type = '';
				foreach($content_type_config['target_dir'] as $key=>$val){
					$content_type_config_dir = str_replace(array('https://'.CMS_TOP_DOMAIN.'/admin/files/', 'http://'.CMS_TOP_DOMAIN.'/admin/files/'),DOCUMENT_ROOT, $val);
					$content_type_config_dir = str_replace('/static/','/', $content_type_config_dir);
					if(mb_substr($content_type_config_dir, -1) != '/'){
						$content_type_config_dir = $content_type_config_dir.'/';
					}

					if($source_path_dir == $content_type_config_dir){
						$content_type = $content_type_config['content_type'][$key];
						break;
					}
				}
			}
		}

		if(empty($content_type)){
			if($background_flag){
				// 転送対象が大容量になる場合用。
				// バックグラウンドに回して、タイムアウトを防止。
				exec("nohup /bin/bash ".self::$sh_file_path.' '.$source_path.' s3://'.$target_bucket['bucket'].'/'.$target_bucket['path'].' '.$src_del_flag.' 1>>'.self::$sh_log_path.' 2>&1 & ', $output, $ret_val);
			}else{
				exec("/bin/bash ".self::$sh_file_path.' '.$source_path.' s3://'.$target_bucket['bucket'].'/'.$target_bucket['path'].' '.$src_del_flag.' 1>>'.self::$sh_log_path.' 2>&1', $output, $ret_val);
			}
		}else{
			if($background_flag){
				// 転送対象が大容量になる場合用。
				// バックグラウンドに回して、タイムアウトを防止。
				exec("nohup /bin/bash ".self::$ct_sh_file_path.' '.$source_path.' s3://'.$target_bucket['bucket'].'/'.$target_bucket['path'].' '.$content_type.' '.$src_del_flag.' 1>>'.self::$sh_log_path.' 2>&1 & ', $output, $ret_val);
			}else{
				exec("/bin/bash ".self::$ct_sh_file_path.' '.$source_path.' s3://'.$target_bucket['bucket'].'/'.$target_bucket['path'].' '.$content_type.' '.$src_del_flag.' 1>>'.self::$sh_log_path.' 2>&1', $output, $ret_val);
			}

			if($cloudfront_cache_clear_flag){
				// cloudfrontのキャッシュクリア
				SCF::CacheClear($source_path);
			}
		}

		return empty($ret_val);

	}

	/**
	 * 【一括版】ファイルおよびディレクトリの転送（削除含む）
	 *
	 * 転送or削除を自動判別しS3に対して実行する。
	 * 転送ソースがファイルの場合：
	 *   実態ファイルが存在すれば転送、
	 *   実態ファイルが存在しなければ削除
	 * 転送ソースがディレクトリの場合：
	 *   実態ディレクトリが存在すればディレクトリごと転送、
	 *   実態ディレクトリが存在しない場合はディレクトリごと削除。
	 *
	 * @param array $sync_file_path_list 転送ソースフルパス一覧
	 * @param boolean $background_flag S3への転送処理をバックグラウンドに回すフラグ
	 * @param boolean $src_del_flag 転送後、転送ソース元ファイル削除フラグ
	 * @param boolean $cloudfront_cache_clear_flag Cloudfrontのキャッシュクリア実行フラグ
	 * @return boolean
	 **/
	// 一括版
	public static function syncBunchUpload($sync_file_path_list=array(), $background_flag=false, $src_del_flag=false, $cloudfront_cache_clear_flag=false){
		if(empty($sync_file_path_list)){
			return false;
		}

		$sync_success_flag = true;
		$bunch_sync_file_path_list = array();
		foreach($sync_file_path_list as $sync_file_path_key=>$sync_file_path){
			// 転送ソース元フルパス
			$source_path =  empty($sync_file_path['src']) ?  '' : $sync_file_path['src'];
			// S3のバケットと対になるフルパス（省略の場合は「転送ソース元」を使用する）
			$bucket_pair_path = empty($sync_file_path['dest']) ?  $source_path : $sync_file_path['dest'];

			if(empty($source_path)){
				ErrorLog::write('[Error] No source file name.');
				$sync_success_flag = false;
				continue;
			}
			if(empty($source_path) || !is_string($source_path)){
				ErrorLog::write('[Error] Source file name is not a string.');
				$sync_success_flag = false;
				continue;
			}

			$target_bucket = self::getS3BucketNameAndPath($bucket_pair_path);

			// S3のバケットが特定できなかったのでスキップ
			if(empty($target_bucket['bucket'])){
				ErrorLog::write('[Error] no bucket setting. file:'.$bucket_pair_path);
				$sync_success_flag = false;
				continue;
			}

			// ドキュメントルート以下のファイルではなく、bucketの対応先も存在しなかった場合同期を行わずにスキップ
			if(
				strpos($bucket_pair_path, DOCUMENT_ROOT) === false &&
				!empty($target_bucket['default'])
			){
				ErrorLog::write('[Error] Not document root file. And no bucket setting. file:'.$bucket_pair_path);
				$sync_success_flag = false;
				continue;
			}

			// 転送対象ファイルが1ギガ以上。エラーはsizeCheck内で出力。
			if(
				file_exists($source_path) &&
				is_file($source_path) &&
				empty(self::sizeCheck($source_path))
			){
				$sync_success_flag = false;
				continue;
			}

			$bunch_sync_file_path_list[] = array(
				'src' => $source_path,
				's3_path' => 's3://'.$target_bucket['bucket'].'/'.$target_bucket['path'],
			);
		}

		if(empty($bunch_sync_file_path_list)){
			return false;
		}


		$src_del_flag = empty($src_del_flag) ? 0 : 1;

		// バックグラウンドに回して、すぐに復帰する場合（個別版と挙動＆スピード変わらない）
		if($background_flag){
			foreach($bunch_sync_file_path_list as $path){
				$output = array();
				$ret_val = 0;

				exec("nohup /bin/bash ".self::$sh_file_path.' '.$path['src'].' '.$path['s3_path'].' '.$src_del_flag.' 1>>'.self::$sh_log_path.' 2>&1 &', $output, $ret_val);
				if($ret_val != 0){
					$sync_success_flag = false;
				}

				//if($cloudfront_cache_clear_flag){
				//	// cloudfrontのキャッシュクリア
				//	SCF::CacheClear($path['src']);
				//}

			}

			return $sync_success_flag;
		}

		// バックグラウンドに回さず、転送が終わるのを待つ場合
		$chunk_sync_file_path_list = array_chunk($bunch_sync_file_path_list, S3_UPLOAD_BUNCH_FILE_NUM);


		foreach($chunk_sync_file_path_list as $chunk){
			$full_command = '';
			foreach($chunk as $path){
				$command = '$(/bin/bash -x '.self::$sh_file_path.' '.$path['src'].' '.$path['s3_path'].' '.$src_del_flag.') 1>>'.self::$sh_log_path.' 2>&1 & ';
				$full_command .= $command;
			}

			$full_command = '/bin/bash -x '.$full_command.'wait';
			$output = array();
			$ret_val = 0;
			exec($full_command, $output, $ret_val);
			if($ret_val != 0){
				$sync_success_flag = false;
			}

			//if($cloudfront_cache_clear_flag){
			//	foreach($chunk as $path){
			//		// cloudfrontのキャッシュクリア
			//		SCF::CacheClear($path['src']);
			//	}
			//}
		}

		return $sync_success_flag;

	}



	private static function sizeCheck($source_file){
		$source_file_size = filesize($source_file);
		$byte = 1073741824; // 1ギガ

		// 1ギガ以上のファイルは制限する
		if($source_file_size > $byte){
			// TODO:CMS画面にエラーを出すとかzabbixに警報出すとか
			ErrorLog::write('[Error] The File size is larger than 1GB. S3 transfer not possible. file:'.$source_file);
			return false;
		}

		return true;
	}

	public static function syncDownload($local_path='', $bucket_pair_path='', $background_flag=false){
		if(empty($local_path)){
			return false;
		}

		if(empty($bucket_pair_path)){
			$bucket_pair_path = $local_path;
		}

		$target_bucket = self::getS3BucketNameAndPath($bucket_pair_path);

		// バケットが特定できなかったので終了
		if(empty($target_bucket['bucket'])){
			ErrorLog::write('[Error] no bucket setting. file:'.$bucket_pair_path);
			return false;
		}

		// ドキュメントルート以下のファイルではなく、bucketの対応先も存在しなかった場合同期を行わずに終了
		if(
			strpos($bucket_pair_path, DOCUMENT_ROOT) === false &&
			!empty($target_bucket['default'])
		){
			ErrorLog::write('[Error] Not document root file. And no bucket setting. file:'.$bucket_pair_path);
			return false;
		}

		if($background_flag){
			// 転送対象が大容量になる場合用。
			// バックグラウンドに回して、タイムアウトを防止。
			exec("nohup /bin/bash ".BACKGROUND_DIR.'cron/s3_sync_download.sh s3://'.$target_bucket['bucket'].'/'.$target_bucket['path'].' '.$local_path.' &');
		}else{
			exec("/bin/bash ".BACKGROUND_DIR.'cron/s3_sync_download.sh s3://'.$target_bucket['bucket'].'/'.$target_bucket['path'].' '.$local_path);
		}

		return true;
	}

	private static function formatBucket($s3_bucket_list=array()){
		if(empty($s3_bucket_list)){
			return array();
		}

		$bucket_replace = array(
			'/size1/' => '/'.Settings::getById('article_image_size_name1').'/',
			'/size2/' => '/'.Settings::getById('article_image_size_name2').'/',
			'/size3/' => '/'.Settings::getById('article_image_size_name3').'/',
		);

		foreach($s3_bucket_list as $s3_bucket_key=>$s3_bucket){
			foreach($bucket_replace as $search=>$replace){
				if(strpos($s3_bucket['PathPattern'], $search) !== false){
					$replace_PathPattern = str_replace($search, $replace, $s3_bucket['PathPattern']);
					unset($s3_bucket_list[$s3_bucket_key]);
					$s3_bucket_list[$s3_bucket_key] = array(
						'PathPattern' => $replace_PathPattern,
						'TargetOriginId' => $s3_bucket['TargetOriginId'],
					);
				}
			}
		}

		ksort($s3_bucket_list);

		return $s3_bucket_list;
	}

	private static function getS3BucketNameAndPath($source_file){
		foreach(self::$s3_bucket_list as $bucket_key=>$bucket){
			if(mb_substr($bucket['PathPattern'], 0, 1) == '/'){
				self::$s3_bucket_list[$bucket_key]['full_path'] = DOCUMENT_ROOT.ltrim($bucket['PathPattern'], '/');
			}else{
				self::$s3_bucket_list[$bucket_key]['full_path'] = DOCUMENT_ROOT.$bucket['PathPattern'];
			}

			if(mb_substr($bucket['PathPattern'], -1) == '*'){
				self::$s3_bucket_list[$bucket_key]['full_path'] = rtrim(self::$s3_bucket_list[$bucket_key]['full_path'], '*');
			}

			if(mb_substr($bucket['TargetOriginId'], 0, 3) == 'S3-'){
				self::$s3_bucket_list[$bucket_key]['TargetOriginId'] = ltrim($bucket['TargetOriginId'], 'S3-');
			}
		}

		$target_bucket = '';
		$path = '';
		$default = false;

		foreach(self::$s3_bucket_list as $bucket_key2=>$bucket2){
			if(strpos($source_file, $bucket2['full_path']) !== false){
				$target_bucket = $bucket2['TargetOriginId'];
				$path = str_replace(DOCUMENT_ROOT, '', $source_file);
				$default = !empty($bucket2['OW_SYSTEM_DEFAULT']);
				break;
			}
		}

		return array(
			'bucket' => $target_bucket,
			'path' => $path,
			'default' => $default,
		);
	}

}

<?php
/**
 * このクラスではファイル同期処理を行います
 *
 * background/cron/rsync_del_now.shを利用してCMSサーバーから公開サーバーへデータを転送します。
 * テスト環境、検証環境、本番環境毎に処理を分けて実行します。
 *
 * @access static
 * @param string $rsync_command コマンド最初
 * @param string $command_end コマンドの最後
 * @param array $target 転送先
 */
class FileSync{
	private static $rsync_command = '/bin/bash '.BACKGROUND_DIR.'cron/rsync_del_now.sh ';
	private static $command_end = ' 1>&2 &';
	private static $background_flag = true;
	private static $target = array(
		SERVER_ENV_TEST			=> array(),
		SERVER_ENV_STAGE		=> array(
		),
		SERVER_ENV_PRODUCTION	=> array(
			'ALL',
/*
			'STA',
			'WEB',
			'CMS',
*/
		),
	);
	public function __construct(){
		throw new RuntimeException('このクラスはインスタンス化禁止です');
	}



	/**
 	* 同期処理関数
 	*
 	* @access public
 	* @param string $src_file_path 同期元
 	* @param string $dist_file_path 同期先（省略可）
	* @param boolean $background_flag バックグラウンドに処理を回すフラグ
	* @param boolean $src_del_flag 元ファイル削除フラグ
	* @param string $controller_name ソース元機能名
	* @param boolean $cloudfront_cache_clear_flag Cloudfrontのキャッシュクリア実行フラグ
 	* @return boolean
 	*/
	public static function runFileSync($src_file_path, $dist_file_path='',$background_flag=true, $src_del_flag=false, $controller_name="", $cloudfront_cache_clear_flag=false){
		if(empty($src_file_path) || !is_string($src_file_path)){
			return false;
		}
		if(!empty($dist_file_path) && !is_string($dist_file_path)){
			return false;
		}

		$dist_file_path = empty($dist_file_path) ? $src_file_path : $dist_file_path;

		// AWS環境の場合、S3にファイル転送（ファイルの実態が存在しない場合は削除になる）
		if(!empty(SAWS::getAwsEnv())){
			return SS3::syncUpload($src_file_path, $dist_file_path, $background_flag, $src_del_flag, $controller_name, $cloudfront_cache_clear_flag);

		// AWS環境ではない場合、rsync_del_nowで各サーバーに転送（ファイルの実態が存在しない場合は削除になる）
		}else{
			if(!FILE_SYNC_MODE){
				return true;
			}
			if(empty($src_file_path)){
				return true;
			}
			self::$background_flag=$background_flag;
			switch ($_SERVER[SERVER_ENV_NAME]) {
				case SERVER_ENV_TEST:
					break;
				case SERVER_ENV_STAGE:
					self::startSync($src_file_path,$dist_file_path,SERVER_ENV_STAGE);
					break;

				case SERVER_ENV_PRODUCTION:
					self::startSync($src_file_path,$dist_file_path,SERVER_ENV_PRODUCTION);
					break;

				default:
					break;
			}
			return true;
		}
	}

	public static function runFileSyncBunch($sync_file_path_list, $background_flag=true, $src_del_flag=false){

		if(empty($sync_file_path_list)){
			return false;
		}

		$sync_success_flag = true;

		// AWS環境の場合、S3にファイル転送（ファイルの実態が存在しない場合は削除になる）
		if(!empty(SAWS::getAwsEnv())){
			return SS3::syncBunchUpload($sync_file_path_list, $background_flag, $src_del_flag);

		// AWS環境ではない場合、rsync_del_nowで各サーバーに転送（ファイルの実態が存在しない場合は削除になる）
		}else{
			foreach($sync_file_path_list as $key=>$val){
				$src_file_path = empty($val['src']) ? '' : $val['src'];
				$dist_file_path = empty($val['dest']) ? '' : $val['dest'];

				// rsync_del_now用のbunch処理未実装なので、1ファイルずつ転送する
				if(!self::runFileSync($src_file_path, $dist_file_path, $background_flag, $src_del_flag)){
					// 1つでも転送に失敗したらfalseを返す
					$sync_success_flag = false;
				}
			}

		}

		return $sync_success_flag;
	}

/**
 * 処理実行関数
 *
 * @access private
 * @param string $src_file_path 同期元
 * @param string $dist_file_path 同期先（省略可）
 * @param string $env 実行環境（省略可）
 * @retun boolean true
 */
	private function startSync($src_file_path,$dist_file_path,$env){
		if(empty(self::$target[$env])){
			return true;
		}
		$command = '';
		foreach(self::$target[$env] as $key => $val){
			$command = self::$rsync_command.$val.' '.$src_file_path.' '.$dist_file_path.' '.((self::$background_flag) ? self::$command_end : '');
			shell_exec($command);
//			var_dump($command);
		}
		return false;
	}
}

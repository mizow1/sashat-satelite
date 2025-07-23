<?php

class SAWS{

	private static $environment = '';
	private static $site = '';
	private static $instance_id = 'no_id';

	public function __construct(){
		throw new RuntimeException('このクラスはインスタンス化禁止です');
	}

	public static function init(){
		// 実行環境判断用ファイルが存在しない場合に作成する(InstanceId の取得がなければ取得する)
		exec('. ~/.bashrc;/bin/bash '.BACKGROUND_DIR.'cron/exec_enc_file_create.sh 2>&1', $output, $retval);
		if($retval!=0){
			error_log('['.date('Y-m-d H:i:s').'] :'.serialize($output)."\n",3,TMP_DIR.'log/cron/exec_enc_file_create.log');
		}

		if(
			!file_exists(EXEC_ENC_FILE_PATH) ||
			!filesize(EXEC_ENC_FILE_PATH)
		){
			return;
		}

		$exec_enc_json = file_get_contents(EXEC_ENC_FILE_PATH);
		$exec_enc_array = @json_decode($exec_enc_json, true);
		if(empty($exec_enc_array)){
			return;
		}

		foreach($exec_enc_array as $val){
			if(!empty($val['InstanceId'])){
				self::$instance_id = $val['InstanceId'];
			}
			if(empty($val['Key'])){
				continue;
			}
			if($val['Key'] == 'Environment'){
				self::$environment = $val['Value'];
			}
			if($val['Key'] == 'Site'){
				self::$site = $val['Value'];
			}
		}
	}

	/**
	 * AWSの環境取得
	 *
	 * AWSに設定されているEnvironmentタグ内容を返す
	 * 例：production or stage
	 *
	 * @return string self::$environment
	 */
	public static function getAwsEnv(){
		return self::$environment;
	}

	/**
	 * AWSの環境取得（サイト）
	 *
	 * AWSに設定されているSiteタグ内容を返す
	 * 例：chunichi or tokyo
	 *
	 * @return string self::$site
	 */
	public static function getAwsSite(){
		return self::$site;
	}

	/**
	 * AWSの環境取得（InstanceId）
	 *
	 * AWSに設定されているInstanceIdタグ内容を返す
	 * 例：i-00d9e8324d295bff2 or no_id
	 *
	 * @return string self::$instance_id
	 */
	public static function getAwsInstanceId(){
		return self::$instance_id;
	}

	/**
	 * AWS環境判定ファイルの削除
	 */
	public static function deleteAwsEnvFile(){
		exec('/bin/bash '.BACKGROUND_DIR.'cron/exec_enc_file_init.sh');
	}
}

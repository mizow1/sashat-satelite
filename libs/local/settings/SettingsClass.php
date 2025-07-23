<?php
class Settings{
	const SETTINGS_FILE = GITIGNORE.'settings/data.php';
	private static $arr_data = array();

	public static function init(){
		if(!is_array(self::$arr_data)){
			self::$arr_data = array();
		}
		if(
			!is_file(self::SETTINGS_FILE) || 
			!filesize(self::SETTINGS_FILE)
		){
			require_once(LIB_DIR.'local/db/StaticDataAccessObjectClass.php');
			require_once(MODEL.'administrator/settings/SettingsModelClass.php');
			SDAO::init();
			$objSetting = new SettingsModel();
			self::$arr_data = $objSetting->getAll();

			exec('nohup /bin/bash '.BACKGROUND_DIR.'cron/create_settings_php.sh '.$_SERVER["PANDA_ENV"].' > '.TMP_DIR.'log/settings/nohup.log 2>&1 &');

		}else{
			self::$arr_data = require(self::SETTINGS_FILE);
		}
	}

	public static function getById($str_id = ''){
		if(
			empty($str_id) || 
			!array_key_exists($str_id,self::$arr_data)
		){
			return '';
		}

		return self::$arr_data[$str_id];
	}

	public static function getAll(){
		return self::$arr_data;
	}
}

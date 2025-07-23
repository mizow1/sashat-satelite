<?php
class SS{
	protected static $str_directory;
	protected static $arr_status;

	public static function init($str_directory){
		self::$str_directory = $str_directory;
		self::$arr_status = parse_ini_file(self::$str_directory);
	}

	public static function getAll(){
		return self::$arr_status;
	}

	public static function getMessage($num_code){
		if(!isset(self::$arr_status[$num_code])){
			return '';
		}

		return self::$arr_status[$num_code];
	}

	public static function getListByOutput($str_route){
		$arr_status_of_output = parse_ini_file(self::$str_directory, true);
		if(!isset($arr_status_of_output[$str_route])){
			return array();
		}

		return $arr_status_of_output[$str_route];
	}
}

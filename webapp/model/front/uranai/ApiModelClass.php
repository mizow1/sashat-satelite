<?php
class ApiModel{

	// $_SESSION添字(この名前で配列を作成する)
	const SESSION_NAME = 'regist';

	// 登録STEP
	const PHASE_AREA = 'area';
	const PHASE_PURCHASE = 'purchase';
	const PHASE_MAIN = 'main';
	const PHASE_CONFIRM = 'confirm';
	const PHASE_FINISH = 'finish';

	// 扱うデータ
	private $input_data;

	// 現在の登録STEP
	private $phase;

	// validation error
	private $errors;

	public function __construct($input_data = array()){
		// $_SESSIONに器がなければ作っとく
		if (!isset($_SESSION[self::SESSION_NAME])) {
			$_SESSION[self::SESSION_NAME] = array();
		}
	}

	public function getApi($url,$params = []){
		if(!empty($params)){
			$api_json = file_get_contents($url.http_build_query($params));
		}else{
			$api_json = file_get_contents($url);
		}
		$return_data = json_decode($api_json,true);
		return $return_data ;
	}
}

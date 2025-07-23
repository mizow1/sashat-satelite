<?php
require_once(MODEL.'front/controller/AbstractControllerClass.php');
require_once(MODEL.'front/uranai/ApiModelClass.php');

class DailyAction extends AbstractController{
	function __construct($controller='',$action='',&$session_data=array(),$device=''){
		$this->init($controller,$action,$session_data,$device);
	}
	function Execute($get_data=array(),&$session_data=array()){
		$disp_array = array();
		$api = new ApiModel();
		$url = 'https://www.goodfortune.jp/api/daily?mode=daily&tgt_date='.date('Y-m-d');
		$api_data = array_shift($api->getApi($url));
		
		
		
		$disp_array['api_data'] = $api_data;
		
		$this->display($disp_array, 'daily');
	}
	function PostExecute($get_data=array(),$post_data=array(),$file_data=array(),&$session_data=array()){

	}
}

<?php
require_once(MODEL.'front/controller/AbstractControllerClass.php');
require_once(MODEL.'front/uranai/ApiModelClass.php');

class Daily_detailAction extends AbstractController{
	function __construct($controller='',$action='',&$session_data=array(),$device=''){
		$this->init($controller,$action,$session_data,$device);
	}
	function Execute($get_data=array(),&$session_data=array()){
		$disp_array = array();
		$api = new ApiModel();
		$seiza_list = array('','aries','taurus','gemini','cancer','leo','virgo','libra','scorpio','sagittarius','capricornus','aquarius','pisces');
		// $url = 'https://www.goodfortune.jp/api/daily?mode=daily_detail&tgt_date='.date('Y-m-d').'&tgt_seiza='.$seiza_list[$_GET['tgt']];
		$url = 'https://www.goodfortune.jp/api/daily?mode=daily_detail&tgt_date='.date('Y-m-d').'&tgt_seiza='.$_GET['tgt'];
		$api_data = $api->getApi($url);
		$disp_array['api_data'] = $api_data;
		
		$this->display($disp_array, 'daily_detail');
	}
	function PostExecute($get_data=array(),$post_data=array(),$file_data=array(),&$session_data=array()){

	}
}

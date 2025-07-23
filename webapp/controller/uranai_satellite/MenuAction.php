<?php
require_once(MODEL.'front/controller/AbstractControllerClass.php');
require_once(MODEL.'front/uranai/ApiModelClass.php');
class MenuAction extends AbstractController{
	function __construct($controller='',$action='',&$session_data=array(),$device=''){
		$this->init($controller,$action,$session_data,$device);
	}
	function Execute($get_data=array(),&$session_data=array()){

		$disp_array = array();

		$api = new ApiModel();
		$url = API_MENU.$get_data['menu'];
		$disp_array = $api->getApi($url);
		
		$this->display($disp_array, 'entry');
	}
	function PostExecute($get_data=array(),$post_data=array(),$file_data=array(),&$session_data=array()){


		$api = new ApiModel();
		$url = API_RESULT_NC.$get_data['menu'];
		$api_param = rawurlencode(base64_encode(json_encode($post_data)));
		$url .= '&api_param='.$api_param;

		$disp_array = $api->getApi($url);
		
		//一部無料 or 完全無料
		if(($disp_array["contents_data"]["nc_flag"] && !empty($post_data['nc']) ) || $disp_array["contents_data"]["price_notax"] == 1){
			if($disp_array['contents_data']['price_notax'] > 1){
				$this->display($disp_array, 'nc_result');
			}else{
				$this->display($disp_array, 'result_free');
			}
			return;
		}
		
		//購入画面へリダイレクト
		$query_string = $disp_array['query_string'];
		$url = API_DOMAIN."credit/cid".$get_data["menu"]."/chk.php?".$query_string;
		header("Location: $url\n\n");


	}
}


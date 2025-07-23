<?php
require_once(MODEL.'front/controller/AbstractControllerClass.php');
class LawAction extends AbstractController{
	function __construct($controller='',$action='',&$session_data=array(),$device=''){
		$this->init($controller,$action,$session_data,$device);
	}
	function Execute($get_data=array(),&$session_data=array()){

		$disp_array = array();

		$this->display($disp_array, 'law');
	}
	function PostExecute($get_data=array(),$post_data=array(),$file_data=array(),&$session_data=array()){

	}
}

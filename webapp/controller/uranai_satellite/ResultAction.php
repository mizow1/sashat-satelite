<?php
require_once(MODEL.'front/controller/AbstractController.php');
class ResultAction extends AbstractController{
	function __construct($controller='',$action='',&$session_data=array(),$template=''){

	}
	function Execute($get_data=array(),&$session_data=array()){


		$disp_array = array(
		);

		$this->display($disp_array, 'nc_result');
	}
	function PostExecute($get_data=array(),$post_data=array(),$file_data=array(),&$session_data=array()){

	}
}

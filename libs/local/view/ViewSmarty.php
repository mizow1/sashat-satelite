<?php
require_once(SMARTY_DIR.'Smarty.class.php');

class ViewSmarty extends Smarty{
	private $operation_layer_id = 0;
	private $operator_name = '';
	private $static_server = '';
	private $search_words = array(); //置換対象文字列
	private $replace_words = array(); //置換文字列
	private $preview_replace_words = array();
	private $site_domain = '';
	public function __construct(){
		parent::__construct();
		$this->template_dir = HTML_TEMPLATE_DIR;
		$this->compile_dir = HTML_COMPILE_DIR;
		$this->cache_dir = HTML_CACHE_DIR;
		$this->caching = HTML_CACHING_FLG;
		$this->left_delimiter = '<!--{';
		$this->right_delimiter = '}-->';
	}

	public function encDisplay($tpl,$encode=''){
		echo $this->encFetch($tpl,$encode);
	}

	public function encFetch($tpl,$encode=''){
//		parent::clearCompiledTemplate($tpl,$this->compile_id,SMARTY_LIFETIME);
		$body = parent::fetch($tpl,$this->cache_id,$this->compile_id);
		if (!empty($encode)) {
			$body = mb_convert_encoding($body,$encode,'auto');
		}
		return $body;
	}

	public function changeTemplate($template_dir){
		$this->template_dir = HTML_TEMPLATE_DIR.$template_dir;
	}

	public function changeCompileDir($compile_dir){
		$this->compile_dir = HTML_COMPILE_DIR.$compile_dir;
	}

	public function setOperationLayer($operation_layer_id){
		$this->operation_layer_id = $operation_layer_id;
	}

	public function getOperationLayer(){
		return $this->operation_layer_id;
	}

	public function setOperatorName($operator_name){
		$this->operator_name = $operator_name;
	}

	public function getOperatorName(){
		return $this->operator_name;
	}

	public function replace($body = ""){
		return $this->replaceWords($body);
	}



}

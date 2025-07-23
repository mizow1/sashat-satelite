<?php
require_once(SMARTY_DIR.'Smarty.class.php');

class SqlTemplate extends Smarty{
	public function __construct(){
		parent::__construct();

		$this->template_dir = SQL_TEMPLATE_DIR;
		$this->compile_dir = SQL_COMPILE_DIR;
		$this->cache_dir = SQL_CACHE_DIR;
		$this->caching = SQL_CACHING_FLG;
		$this->left_delimiter = '<!--{';
		$this->right_delimiter = '}-->';
		$this->setCompileId();
	}

	public function getQuery($tpl,$templateDat=array(),$encode=''){
		$this->assign($templateDat);
		$query = $this->fetch($tpl);
		if (!empty($encode)) {
			$query = mb_convert_encoding($query,$encode,'auto');
		}
		return $query;
	}

	public function changeTemplate($template_dir){
		$this->template_dir = TEMPLATE_DIR.$template_dir;
	}

	public function setCompileId($compile_id='default'){
		$this->compile_id = $compile_id;
	}

	public function setCachFlag($flag){
		$this->caching = $flag;
	}

}

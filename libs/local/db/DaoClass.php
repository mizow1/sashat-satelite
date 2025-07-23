<?php
require_once(LIB_DIR.'local/db/MysqlDaoClass.php');
require_once(LIB_DIR.'local/db/SqlTemplateClass.php');

class Dao extends MysqlDao{
	private $sql_view;

	public function __construct($dsn, $id, $pass){
		parent::__construct($dsn, $id, $pass);
		$this->sql_view = new SqlTemplate();
	}

	public function queryCollection($template_name, $wDat=array(), $templateDat=array(), $no_ret_flag=false){
		if (empty($template_name)) {
			return false;
		}
		$format = $this->sql_view->getQuery($template_name.SQL_TEMPLATE_EXTENSION, $templateDat);
		return $this->sql($format, $wDat, $no_ret_flag);
	}

}

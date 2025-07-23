<?php
require_once(LIB_DIR.'local/db/DaoClass.php');

class SDAO {
	public static $db;

	public static function init($readonly = false){
		if ($readonly) {
			self::$db = new Dao(READ_ONLY_DB, READ_ONLY_DB_USER, READ_ONLY_DB_PASSWORD);
		} else {
			self::$db = new Dao(CN1_DB, CN1_DB_USER, CN1_DB_PASSWORD);
		}
	}

	public static function begin(){
		self::$db->begin();
	}

	public static function commit(){
		self::$db->commit();
	}

	public static function rollback(){
		self::$db->rollback();
	}

	public static function getTransactionStatus(){
		return self::$db->getTransactionStatus();
	}

	public static function getRowCount(){
		return self::$db->getRowCount();
	}

	public static function setSqlExecuteCountViewFlag($view_flag = true){
		self::$db->setSqlExecuteCountViewFlag($view_flag);
	}

	public static function getSqlExecuteCountViewFlag(){
		return self::$db->getSqlExecuteCountViewFlag();
	}

	public static function getSqlExecuteCount(){
		return self::$db->getSqlExecuteCount();
	}

	public static function getLastInsertId(){
		return self::$db->getLastInsertId();
	}

	public static function setDebugMode($mode = true){
		self::$db->setDebugMode($mode);
	}

	public static function getSqlExecuteTimeList(){
		return self::$db->getSqlExecuteTimeList();
	}

	public static function getSqlExecuteTotalTime(){
		return self::$db->getSqlExecuteTotalTime();
	}

	public static function getDataList($tableName='',$wDat=array(),$sub=array(),$format=''){
		return self::$db->getDataList($tableName,$wDat,$sub,$format);
	}

	public static function sql($format='',$wDat=array(), $no_ret_flag=false){
		return self::$db->sql($format,$wDat,$no_ret_flag);
	}

	public static function queryCollection($template_name='',$wDat=array(),$templateDat=array(), $no_ret_flag=false){
		return self::$db->queryCollection($template_name,$wDat,$templateDat, $no_ret_flag);
	}

	public static function insert($tableName='',$incData=array()){
		return self::$db->insert($tableName,$incData);
	}

	public static function update($tableName='',$modifyData=array(),$wDat=array(),$format=''){
		return self::$db->update($tableName,$modifyData,$wDat,$format);
	}

	public static function delete($tableName='',$wDat=array(),$format=''){
		return self::$db->delete($tableName,$wDat,$format);
	}

	public static function execute($format='',$wDat=array()){
		return self::$db->sql($format,$wDat,true);
	}

}

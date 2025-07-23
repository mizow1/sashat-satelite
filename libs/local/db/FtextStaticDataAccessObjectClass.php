<?php
require_once(LIB_DIR.'local/db/DaoClass.php');

class FSDAO {
	public static $db;

	public static function init($readonly = false){
		try {
			if ($readonly) {
				self::$db = new Dao(READ_ONLY_DB_FTEXT, READ_ONLY_DB_USER_FTEXT, READ_ONLY_DB_PASSWORD_FTEXT);
			} else {
				self::$db = new Dao(CN1_DB_FTEXT, CN1_DB_USER_FTEXT, CN1_DB_PASSWORD_FTEXT);
			}
		} catch(exception $e) {
			error_log($e->getMessage());
			/******************/
			// TODO
			// WEB画面が「応答なし」にならないための一時対応
			// 接続できなかったときCMS側では各処理に障害メッセージ表示も対応が必要
			/******************/
		}
	}

	public static function begin(){
		if(empty(self::$db)){
			return false;
		}
		self::$db->begin();
	}

	public static function commit(){
		if(empty(self::$db)){
			return false;
		}
		self::$db->commit();
	}

	public static function rollback(){
		if(empty(self::$db)){
			return false;
		}
		self::$db->rollback();
	}

	public static function getTransactionStatus(){
		if(empty(self::$db)){
			return false;
		}
		return self::$db->getTransactionStatus();
	}

	public static function getRowCount(){
		if(empty(self::$db)){
			return 0;
		}
		return self::$db->getRowCount();
	}

	public static function getLastInsertId(){
		if(empty(self::$db)){
			return 0;
		}
		return self::$db->getLastInsertId();
	}

	public static function setDebugMode($mode = true){
		if(empty(self::$db)){
			return ;
		}
		self::$db->setDebugMode($mode);
	}

	public static function getDataList($tableName='',$wDat=array(),$sub=array(),$format=''){
		if(empty(self::$db)){
			return array();
		}
		return self::$db->getDataList($tableName,$wDat,$sub,$format);
	}

	public static function sql($format='',$wDat=array(), $no_ret_flag=false){
		if(empty(self::$db)){
			return array();
		}
		return self::$db->sql($format,$wDat,$no_ret_flag);
	}

	public static function queryCollection($template_name='',$wDat=array(),$templateDat=array(), $no_ret_flag=false){
		if(empty(self::$db)){
			return array();
		}
		return self::$db->queryCollection($template_name,$wDat,$templateDat, $no_ret_flag);
	}

	public static function insert($tableName='',$incData){
		if(empty(self::$db)){
			return false;
		}
		return self::$db->insert($tableName,$incData);
	}

	public static function update($tableName='',$modifyData=array(),$wDat=array(),$format=''){
		if(empty(self::$db)){
			return false;
		}
		return self::$db->update($tableName,$modifyData,$wDat,$format);
	}

	public static function delete($tableName='',$wDat=array(),$format=''){
		if(empty(self::$db)){
			return false;
		}
		return self::$db->delete($tableName,$wDat,$format);
	}

	public static function execute($format='',$wDat=array()){
		if(empty(self::$db)){
			return array();
		}
		return self::$db->sql($format,$wDat,true);
	}

	public static function close(){
		self::$db->close();
	}

}

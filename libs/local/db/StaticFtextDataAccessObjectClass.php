<?php
/*
DAO::getDataList('table name')

*/
require_once(LIB_DIR.'local/db/DaoClass.php');
class SFDAO {
	public static $db;
	public static function init($readonly = false){
		if ($readonly) {
			self::$db = new Dao(READ_ONLY_FTEXT_DB, READ_ONLY_FTEXT_DB_USER, READ_ONLY_FTEXT_DB_PASSWORD);
		} else {
			self::$db = new Dao(FTEXT_DB, FTEXT_DB_USER, FTEXT_DB_PASSWORD);
		}
 	}

	public static function setDebugMode($mode = 1){
		self::$db->setDebugMode($mode);
	}
	public static function getTransactionStatus(){
		return self::$db->getTransactionStatus();
	}

	public static function getDataList($tableName="",$wDat="",$sub="",$format=""){
		return self::$db->getDataList($tableName,$wDat,$sub,$format);
	}

	public static function sql($format="",$wDat=""){
		return self::$db-> sql($format,$wDat);
	}
	public static function execute($format="",$wDat=""){
		return self::$db-> sql($format,$wDat,true);
	}
	public static function queryCollection($template_name="",$wDat="",$templateDat=array()){
		return self::$db->queryCollection($template_name,$wDat,$templateDat);
	}
	public static function insert($tableName="",$incData){
		return self::$db->insert($tableName,$incData);
	}

	public static function update($tableName="",$modifyData="",$wDat="",$format=""){
		return self::$db->update($tableName,$modifyData,$wDat,$format);
	}

	public static function replace($tableName="",$modifyData="",$wDat="",$format=""){
		return self::$db->replace($tableName,$modifyData,$wDat,$format);
	}

	public static function delete($tableName="",$wDat="",$format=""){
		return self::$db->delete($tableName,$wDat,$format);
	}
	public static function begin(){
		self::$db-> begin();
	}
	public static function commit(){
		self::$db-> commit();
	}
	public static function rollback(){
		self::$db->rollback();
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

	public static function getSqlExecuteTimeList(){
		return self::$db->getSqlExecuteTimeList();
	}

	public static function getSqlExecuteTotalTime(){
		return self::$db->getSqlExecuteTotalTime();
	}

	public static function getLastInsertId(){
		return self::$db->getLastInsertId();
	}
	//フィールド指定バージョン
	public static function getDataListFS($tableName="",$wDat="",$sub="",$format=""){
		$result = array();
		$where = "";
		$orderBy = "";
		$limit = "";
		if(!empty($wDat)){
			if(empty($format)){
				$tmp = array();
				foreach($wDat as $key=>$val){
					$tmp[$key]="$key = :$key";
				}
				$where = implode(" and ",$tmp);
			}else{
				$where = $format;
			}
			$where = "where $where";
		}
		if(!empty($sub)){
			if(!empty($sub["sort"])){
				$orderBy = "order by ".$sub["sort"];
			}
			if(!empty($sub["limit"])){
				$limit = "limit ".$sub["limit"];
			}
		}
		$field = empty($sub["field"]) ? "*" : $sub["field"];

		$sth = $this->db->prepare("select $field from $tableName $where $orderBy $limit");
		if(!empty($wDat)){
			reset($wDat);
			foreach($wDat as $key=>$val){
				$value = is_array($val) ? (empty($val["value"]) ? (is_numeric($val["value"]) ? $val["value"]:(is_bool($val["value"]) ? $val["value"]:null)):$val["value"])
										: (empty($val)          ? (is_numeric($val)          ? $val         :(is_bool($val)          ? $val         :null))         :$val);

				$type = is_null($value) ? $this->data_type["null"] : (empty($this->data_type[$val["type"]]) ? $this->data_type["str"]: $this->data_type[$val["type"]]);
				$sth->bindValue(':'.$key,mb_convert_encoding($value,$this->encode,ENCODE_SYSTEM),$type);

			}
		}
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$this->rowCount = $sth->rowCount();
/*
*		debug mode
*/
		if($this->debugMode) $sth->debugDumpParams();

		if($sth->rowCount()){
			while($rows = $sth->fetch()){
				foreach($rows as $key=>$val){
					$rows[$key]=mb_convert_encoding($val,ENCODE_SYSTEM,$this->encode);
				}
				$result[$rows["id"]]=$rows;
			}
		}

		$sth = null;
		return $result;
	}

	public function keySetSql($format="",$wDat="",$key_field="id"){
		$result = array();
		$sth = $this->db->prepare($format);
		if(!empty($wDat)){
			reset($wDat);
			foreach($wDat as $key=>$val){
				$value = is_array($val) ? (empty($val["value"]) ? (is_numeric($val["value"]) ? $val["value"]:(is_bool($val["value"]) ? $val["value"]:null)):$val["value"])
										: (empty($val)          ? (is_numeric($val)          ? $val         :(is_bool($val)          ? $val         :null))         :$val);

				$type = is_null($value) ? $this->data_type["null"] : (empty($this->data_type[$val["type"]]) ? $this->data_type["str"]: $this->data_type[$val["type"]]);
				$sth->bindValue(':'.$key,mb_convert_encoding($value,$this->encode,ENCODE_SYSTEM),$type);
			}
		}
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$this->rowCount = $sth->rowCount();

/*
*		debug mode
*/
		if($this->debugMode) $sth->debugDumpParams();

		if($sth->rowCount()){
			while($rows = $sth->fetch()){
				foreach($rows as $key=>$val){
					$rows[$key]=mb_convert_encoding($val,ENCODE_SYSTEM,$this->encode);
				}
				if(!empty($rows[$key_field])){
					$result[$rows[$key_field]]=$rows;
				}else{
					$result[]=$rows;
				}
			}
		}
		$sth = null;
		return $result;
	}

}

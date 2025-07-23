<?php
class PgDao{
	private $debugMode = 0;
	private $transactionStatus = false;
	private $db;
	private $data_type = array();
	/**
	 * __construct
	 *
	 * @param $dsn
	 * @param $database_encode
	 * @return void
	 */
	public function __construct($dsn="",$databaseEncode="utf-8",$id="",$pass=""){
		empty($id) ? $this->db = new PDO($dsn):$this->db = new PDO($dsn,$id,$pass);
		$this->encode = $databaseEncode;
		$this->data_type["bool"]= PDO::PARAM_BOOL;
		$this->data_type["null"]= PDO::PARAM_NULL;
		$this->data_type["int"]	= PDO::PARAM_INT;
		$this->data_type["str"]	= PDO::PARAM_STR;
		$this->db->setAttribute( PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	public function setDebugMode($mode = true){
		$this->debugMode = $mode;
	}
	public function getTransactionStatus(){
		return $this->transactionStatus;
	}
	/**
	 * getDataList
	 *
	 * @param $tableName
	 * @param $wDat
	 * @param $sort
	 * @param $limit
	 * @return void
	 */
	public function getDataList($tableName="",$wDat="",$sub="",$format=""){
		$result = array();
		$where = "";

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
		$orderBy = empty($sub["sort"])  ? "":"order by ".$sub["sort"];
		$limit   = empty($sub["limit"]) ? "":"limit ".$sub["limit"];
		$sth = $this->db->prepare("select * from $tableName $where $orderBy $limit");
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
	/**
	 * sql
	 *
	 * @param $format
	 * @param $wDat
	 * @return void
	 */
	public function sql($format="",$wDat=""){
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
				if(!empty($rows['id'])){
					$result[$rows["id"]]=$rows;
				}else{
					$result[]=$rows;
				}
			}
		}
		$sth = null;
		return $result;
	}
	/**
	 * insert
	 *
	 * @param $tableName
	 * @param $post_data
	 * @return void
	 */
	public function insert($tableName="",$incData){
		if(!empty($incData)){
			$tmp1 =array();
			$tmp2 =array();
			foreach($incData as $key=>$val){
				$tmp1[$key]   = $key;
				$tmp2[$key] = ":".$key;
			}
			$keys   = implode(",",$tmp1);
			$format = implode(",",$tmp2);
			$sth = $this->db->prepare("insert into $tableName ($keys) values ($format)");
			reset($incData);
			foreach($incData as $key=>$val){
				$value = is_array($val) ? (empty($val["value"]) ? (is_numeric($val["value"]) ? $val["value"]:(is_bool($val["value"]) ? $val["value"]:null)):$val["value"])
										: (empty($val)          ? (is_numeric($val)          ? $val         :(is_bool($val)          ? $val         :null))         :$val);

				$type = is_null($value) ? $this->data_type["null"] : (empty($this->data_type[$val["type"]]) ? $this->data_type["str"]: $this->data_type[$val["type"]]);
				$sth->bindValue(':'.$key,mb_convert_encoding($value,$this->encode,ENCODE_SYSTEM),$type);
			}
			$sth->execute();
			$this->rowCount = $sth->rowCount();
/*
*		debug mode
*/
		if($this->debugMode) $sth->debugDumpParams();

		$sth = null;

		}
	}
	/**
	 * update
	 *
	 * @param $tableName
	 * @param $modifyData
	 * @param $wDat
	 * @param $format
	 * @return void
	 */
	public function update($tableName="",$modifyData="",$wDat="",$format=""){
		$result = array();
		$where = "";
		if(!empty($wDat)){
			if(empty($format)){
				$tmp = array();
				foreach($wDat as $key=>$val){
					$tmp[$key]="$key = :_$key";
				}
				$where = implode(" and ",$tmp);
			}else{
				$where = $format;
			}
			$where = "where $where";
		}
		if(!empty($modifyData)){
			$tmp = array();
			foreach($modifyData as $key=>$val){
				$tmp[$key]="$key = :$key";
			}
			$setData = implode(",",$tmp);
			$sth = $this->db->prepare("update $tableName set $setData $where");
//echo "update $tableName set $setData $where";
//var_dump($wDat);
			reset($wDat);
			foreach($wDat as $key=>$val){
				$value = is_array($val) ? (empty($val["value"]) ? (is_numeric($val["value"]) ? $val["value"]:(is_bool($val["value"]) ? $val["value"]:null)):$val["value"])
										: (empty($val)          ? (is_numeric($val)          ? $val         :(is_bool($val)          ? $val         :null))         :$val);

				$type = is_null($value) ? $this->data_type["null"] : (empty($this->data_type[$val["type"]]) ? $this->data_type["str"]: $this->data_type[$val["type"]]);
				$sth->bindValue(':_'.$key,mb_convert_encoding($value,$this->encode,ENCODE_SYSTEM),$type);

				//$sth->bindValue(':_'.$key,mb_convert_encoding($val,$this->encode,ENCODE_SYSTEM),PDO::PARAM_STR);
			}
			reset($modifyData);
			foreach($modifyData as $key=>$val){
				$value = is_array($val) ? (empty($val["value"]) ? (is_numeric($val["value"]) ? $val["value"]:(is_bool($val["value"]) ? $val["value"]:null)):$val["value"])
										: (empty($val)          ? (is_numeric($val)          ? $val         :(is_bool($val)          ? $val         :null))         :$val);

				$type = is_null($value) ? $this->data_type["null"] : (empty($this->data_type[$val["type"]]) ? $this->data_type["str"]: $this->data_type[$val["type"]]);
				$sth->bindValue(':'.$key,mb_convert_encoding($value,$this->encode,ENCODE_SYSTEM),$type);
			}
			$sth->execute();
			$this->rowCount = $sth->rowCount();
/*
*		debug mode
*/
			if($this->debugMode) $sth->debugDumpParams();
			$sth = null;
		}
	}
	/**
	 * delete
	 *
	 * @param $tableName
	 * @param $wDat
	 * @param $format
	 * @return void
	 */
	public function delete($tableName="",$wDat="",$format=""){
		$result = array();
		$where = "";
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
		$sth = $this->db->prepare("delete from $tableName $where");
		reset($wDat);
		foreach($wDat as $key=>$val){
				$value = is_array($val) ? (empty($val["value"]) ? (is_numeric($val["value"]) ? $val["value"]:(is_bool($val["value"]) ? $val["value"]:null)):$val["value"])
										: (empty($val)          ? (is_numeric($val)          ? $val         :(is_bool($val)          ? $val         :null))         :$val);

				$type = is_null($value) ? $this->data_type["null"] : (empty($this->data_type[$val["type"]]) ? $this->data_type["str"]: $this->data_type[$val["type"]]);
				$sth->bindValue(':'.$key,mb_convert_encoding($value,$this->encode,ENCODE_SYSTEM),$type);
		}
		$sth->execute();
		$this->rowCount = $sth->rowCount();
/*
*		debug mode
*/
		if($this->debugMode) $sth->debugDumpParams();
		$sth = null;
	}


	public function begin(){
		if($this->transactionStatus === true){
			return 1;
		}else{
			$this->db->beginTransaction();
			$this->transactionStatus = true;
			return 0;
		}
	}
	public function commit(){
		if($this->transactionStatus === true){
			$this->db->commit();
			$this->transactionStatus = false;
			return 0;
		}else{
			return 1;
		}
	}
	public function rollback(){
		if($this->transactionStatus === true){
			$this->db->rollBack();
			$this->transactionStatus = false;
			return 0;
		}else{
			return 1;
		}
	}
	function pg_parse($val=""){
		if(!get_magic_quotes_gpc()){
			return pg_escape_string($val);
		}else{
			return pg_escape_string($val);
		//	return $val;
		}
	}
	function __destruct(){
		$this->rollback();
	}
	public function getRowCount(){
		return $this->rowCount;
	}
}
?>

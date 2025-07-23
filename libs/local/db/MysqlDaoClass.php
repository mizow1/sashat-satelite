<?php

require_once(LIB_DIR."local/ChromePhp.php");

if(empty($GLOBALS['sql_execute_count'])){
	$GLOBALS['sql_execute_count'] = 0;
}
if(empty($GLOBALS['sql_execute_time_list'])){
	$GLOBALS['sql_execute_time_list'] = array();
}
if(empty($GLOBALS['sql_execute_total_time'])){
	$GLOBALS['sql_execute_total_time'] = 0;
}

class MysqlDao{
	private $db;
	private $data_type = array();
	private $transactionStatus = false;
	private $row_count = 0;
	private $last_insert_id = 0;
	private $debug_mode = 0;
	private $sql_execute_count = 0;
	private $sql_execute_count_view_flag = false;
	private $sql_execute_time_list = array();
	private $sql_execute_total_time = 0;

	public function __construct($dsn, $id, $pass){

		$this->db = new PDO($dsn, $id, $pass);
		$this->db->query('SET NAMES utf8');
		$this->data_type['bool'] = PDO::PARAM_BOOL;
		$this->data_type['null'] = PDO::PARAM_NULL;
		$this->data_type['int'] = PDO::PARAM_INT;
		$this->data_type['str'] = PDO::PARAM_STR;
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

		$this->sql_execute_count = $GLOBALS['sql_execute_count'];
		$this->sql_execute_count_view_flag = false;
		$this->sql_execute_time_list = $GLOBALS['sql_execute_time_list'];
		$this->sql_execute_total_time = $GLOBALS['sql_execute_total_time'];
	}

	public function __destruct(){
		$this->rollback();
	}

	public function begin(){
		if($this->transactionStatus === true){
			return true;
		}else{
			$this->db->beginTransaction();
			$this->transactionStatus = true;
			return false;
		}
	}

	public function commit(){
		if($this->transactionStatus === true){
			try{
				$this->db->commit();
			}catch(Exception $e){
				// 何もしない
			}

			$this->transactionStatus = false;
			return false;
		}else{
			return true;
		}
	}

	public function rollback(){
		if($this->transactionStatus === true){
			try{
				$this->db->rollBack();
			}catch(Exception $e){
				// 何もしない
			}

			$this->transactionStatus = false;
			return false;
		}else{
			return true;
		}
	}

	public function getTransactionStatus(){
		return $this->transactionStatus;
	}

	public function getRowCount(){
		return $this->row_count;
	}

	public function setSqlExecuteCountViewFlag($view_flag = true){
		$this->sql_execute_count_view_flag = $view_flag;
	}

	public function getSqlExecuteCountViewFlag(){
		return $this->sql_execute_count_view_flag;
	}

	public function getSqlExecuteCount(){
		return $this->sql_execute_count;
	}

	public function getLastInsertId(){
		return $this->last_insert_id;
	}

	public function setDebugMode($mode = true){
		$this->debug_mode = $mode;
	}

	private function getPrepareWhereString($wDat){
		$tmp = array();
		foreach($wDat as $key => $val){
			$value = isset($val['value']) ? $val['value'] : $val;
			if (is_null($value)) {
				$operator = ' IS ';
			} else {
				$operator = ' = ';
			}
			$tmp[$key] = $key.$operator.':'.$key;
		}
		return implode(' AND ', $tmp);
	}

	private function getPrepareExecuteString($list){
		$tmp = array();
		foreach($list as $key => $val){
			$tmp[$key] = $key.' = :_'.$key;
		}
		return implode(',', $tmp);
	}

	private function getBindValue($val){
		return isset($val['value']) ? $val['value'] : $val;
	}

	private function getBindValueType($val){
		$value = $this->getBindValue($val);
		$type = $this->data_type['str'];
		if (isset($val['type']) && !empty($this->data_type[$val['type']])) {
			$type = $this->data_type[$val['type']];
		} elseif (is_null($value)) {
			$type = $this->data_type['null'];
		} elseif (is_int($value)) {
			$type = $this->data_type['int'];
		} elseif (is_bool($value)) {
			$type = $this->data_type['bool'];
		}
		return $type;
	}

	private function getResult($stmt){
		if(!$stmt->rowCount()){
			return array();
		}

		$result = array();
		while($row = $stmt->fetch()){
			if(!empty($row['id'])){
				$result[$row['id']] = $row;
			}else{
				$result[] = $row;
			}
		}
		return $result;
	}

	public function setSqlExecuteTime($start_time,$end_time,$excute_function_name=''){
		if(empty($start_time) || empty($end_time)){
			return;
		}
		$execute_time = $end_time - $start_time;
		$this->sql_execute_time_list[$excute_function_name][] = $execute_time;
		$this->sql_execute_total_time += $execute_time;

		$GLOBALS['sql_execute_time_list'] = $this->sql_execute_time_list;
		$GLOBALS['sql_execute_total_time'] = $this->sql_execute_total_time;
	}

	public function getSqlExecuteTimeList(){
		return $this->sql_execute_time_list;
	}

	public function getSqlExecuteTotalTime(){
		return round($this->sql_execute_total_time,3);
	}

	public function getDataList($tableName='', $wDat=array(), $sub=array(), $format=''){
		$this->sql_execute_count++;
		$GLOBALS['sql_execute_count'] = $this->sql_execute_count;

		$sql_execute_start_time = microtime(true);
		$where = '';
		if(!empty($wDat)){
			if(
				is_array($wDat) &&
				count($wDat) == 1 &&
				array_key_exists(1, $wDat) &&
				$wDat[1] == 1
			){
				// PHP8 対応
			}else{
				if(!empty($format)){
					$where = $format;
				}else{
					$where = $this->getPrepareWhereString($wDat);
				}
				$where = 'WHERE '.$where;
			}
		}
		$orderBy = empty($sub['sort']) ? '' : 'ORDER BY '.$sub['sort'];
		$limit = empty($sub['limit']) ? '' : 'LIMIT '.$sub['limit'];
		$field = empty($sub['field']) ? '*' : $sub['field'];
		$stmt = $this->db->prepare('SELECT '.$field.' FROM '.$tableName.' '.$where.' '.$orderBy.' '.$limit);
		if(!empty($wDat)){
			if(
				is_array($wDat) &&
				count($wDat) == 1 &&
				array_key_exists(1, $wDat) &&
				$wDat[1] == 1
			){
				// PHP8 対応
				// $wDat = array(1=>1) 設定時、それをbindValueすると下記エラー発生するので回避するように修正
				// PDOException: SQLSTATE[HY093]: Invalid parameter number: number of bound variables does not match number of tokens
			}else{
				reset($wDat);
				foreach($wDat as $key => $val){
					$value = $this->getBindValue($val);
					$type = $this->getBindValueType($val);
					$stmt->bindValue(':'.$key, $value, $type);
				}
			}

		}

		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute();
		$this->row_count = $stmt->rowCount();
		if($this->debug_mode){
			$stmt->debugDumpParams();
		}
		$sql_execute_end_time = microtime(true);
		// SDAO呼び出しもとから呼び出し元関数名を渡すようにする(setSqlExecuteTime:'get_data_list'引数部分)
		$this->setSqlExecuteTime($sql_execute_start_time,$sql_execute_end_time,'get_data_list');
		return $this->getResult($stmt);
	}

	public function sql($format='', $wDat=array(), $no_ret_flag=false){
		$this->sql_execute_count++;
		$GLOBALS['sql_execute_count'] = $this->sql_execute_count;

		$sql_execute_start_time = microtime(true);
		$stmt = $this->db->prepare($format);

		// PHP8 対応
		// bindValueによる下記エラーが発生するので回避するように修正
		// PDOException: SQLSTATE[HY093]: Invalid parameter number: number of bound variables does not match number of tokens
		$bindvalue_target = array();
		if(!empty($wDat)){
			foreach($wDat as $key => $val){
				if(strpos($format, ':'.$key) !== false){
					$bindvalue_target[$key] = $val;
				}
			}
		}
		if(!empty($wDat)){
			if(
				is_array($wDat) &&
				count($wDat) == 1 &&
				array_key_exists(1, $wDat) &&
				$wDat[1] == 1
			){
				// PHP8 対応
				// $wDat = array(1=>1) 設定時、それをbindValueすると下記エラー発生するので回避するように修正
				// PDOException: SQLSTATE[HY093]: Invalid parameter number: number of bound variables does not match number of tokens
			}else{
				foreach($bindvalue_target as $key => $val){
					$value = $this->getBindValue($val);
					$type = $this->getBindValueType($val);
					$stmt->bindValue(':'.$key, $value, $type);
				}
			}
		}
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute();
		$this->row_count = $stmt->rowCount();
		if($this->debug_mode){
			$stmt->debugDumpParams();
		}
		if($no_ret_flag){
			return true;
		}
		$sql_execute_end_time = microtime(true);
		// SDAO呼び出しもとから呼び出し元関数名を渡すようにする(setSqlExecuteTime:'sql'引数部分)
		$this->setSqlExecuteTime($sql_execute_start_time,$sql_execute_end_time,'sql');
		return $this->getResult($stmt);
	}

	public function insert($tableName='',$incData=array()){

		if (empty($incData)) {
			return false;
		}

		$this->sql_execute_count++;
		$GLOBALS['sql_execute_count'] = $this->sql_execute_count;

		$sql_execute_start_time = microtime(true);
		foreach($incData as $key => $val){
			$tmp1[$key] = $key;
			$tmp2[$key] = ':_'.$key;
		}

		$keys   = implode('`,`', $tmp1);
		$format = implode(',', $tmp2);
		$stmt   = $this->db->prepare('INSERT INTO '.$tableName.' (`'.$keys.'`) VALUES ('.$format.')');

		reset($incData);

		foreach($incData as $key => $val){

			$value = $this->getBindValue($val);
			$type  = $this->getBindValueType($val);

			$stmt->bindValue(':_'.$key, $value, $type);
		}

		$result               = $stmt->execute();
		$this->row_count      = $stmt->rowCount();
		$this->last_insert_id = $this->db->lastInsertId();

		if($this->debug_mode){
			$stmt->debugDumpParams();
		}
		$sql_execute_end_time = microtime(true);
		// SDAO呼び出しもとから呼び出し元関数名を渡すようにする(setSqlExecuteTime:'insert'引数部分)
		$this->setSqlExecuteTime($sql_execute_start_time,$sql_execute_end_time,'insert');
		return $result;
	}

	public function update($tableName='',$modifyData=array(),$wDat=array(),$format=''){
		if (empty($modifyData)) {
			return false;
		}

		$this->sql_execute_count++;
		$GLOBALS['sql_execute_count'] = $this->sql_execute_count;

		$sql_execute_start_time = microtime(true);
		$where = '';
		if(!empty($wDat)){
			if(!empty($format)){
				$where = $format;
			}else{
				$where = $this->getPrepareWhereString($wDat);
			}
			$where = 'WHERE '.$where;
		}
		$setData = $this->getPrepareExecuteString($modifyData);
		$stmt = $this->db->prepare('UPDATE '.$tableName.' SET '.$setData.' '.$where);
		reset($modifyData);
		foreach($modifyData as $key => $val){
			$value = $this->getBindValue($val);
			$type = $this->getBindValueType($val);
			$stmt->bindValue(':_'.$key, $value, $type);
		}
		if(!empty($wDat)){
			reset($wDat);
			foreach($wDat as $key => $val){
				$value = $this->getBindValue($val);
				$type = $this->getBindValueType($val);
				$stmt->bindValue(':'.$key, $value, $type);
			}
		}
		$result = $stmt->execute();
		$this->row_count = $stmt->rowCount();
		if($this->debug_mode){
			$stmt->debugDumpParams();
		}
		$sql_execute_end_time = microtime(true);
		// SDAO呼び出しもとから呼び出し元関数名を渡すようにする(setSqlExecuteTime:'update'引数部分)
		$this->setSqlExecuteTime($sql_execute_start_time,$sql_execute_end_time,'update');
		return $result;
	}

	public function delete($tableName='',$wDat=array(),$format=''){
		$this->sql_execute_count++;
		$GLOBALS['sql_execute_count'] = $this->sql_execute_count;

		$sql_execute_start_time = microtime(true);
		$where = '';
		if(!empty($wDat)){
			if(!empty($format)){
				$where = $format;
			}else{
				$where = $this->getPrepareWhereString($wDat);
			}
			$where = 'WHERE '.$where;
		}
		$stmt = $this->db->prepare('DELETE FROM '.$tableName.' '.$where);
		if(!empty($wDat)){
			reset($wDat);
			foreach($wDat as $key=>$val){
				$value = $this->getBindValue($val);
				$type = $this->getBindValueType($val);
				$stmt->bindValue(':'.$key, $value, $type);
			}
		}
		$result = $stmt->execute();
		$this->row_count = $stmt->rowCount();
		if($this->debug_mode){
			$stmt->debugDumpParams();
		}
		$sql_execute_end_time = microtime(true);
		// SDAO呼び出しもとから呼び出し元関数名を渡すようにする(setSqlExecuteTime:'delete'引数部分)
		$this->setSqlExecuteTime($sql_execute_start_time,$sql_execute_end_time,'delete');
		return $result;
	}

	// 接続閉鎖
	public function close(){
		$this->db = null;
	}

}

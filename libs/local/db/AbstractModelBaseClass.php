<?php
abstract class AbstractModelBase{
	private $_max_rec = 0;
	private $_page_max_rec = 0;
	private $scope = '';

	public function __construct($page_max_rec='100', $ftext_flag=false){
		$this->_page_max_rec = $page_max_rec;
		$this->scope = empty($ftext_flag) ? 'SDAO' : 'FSDAO';
	}
	
	public function setPageMaxRec($page_max_rec){
		$this->_page_max_rec = $page_max_rec;
	}
	public function getDataListPage($tableName='',$wDat=array(),$sub=array(),$format='',$page='1'){
		if($tmp = SDAO::getDataList($tableName,$wDat,array('field'=>'count(id) as cnt'),$format)){
			foreach($tmp as $item){
				$this->_max_rec = $item['cnt'];
			}
		}

		$result = array();
		try{
			$offset = ($page - 1) * $this->_page_max_rec;
			$sub['limit'] = $offset.','.$this->_page_max_rec;
			if($list = SDAO::getDataList($tableName,$wDat,$sub,$format)){
				$result['status'] = $this->createPageStatus($page);
				$result['list'] = $list;
			}else{
				$result['status'] = array(
					'max_rec' => $this->_max_rec,
					'page_no' => $page,
					'max_page' => 1,
					'next_page' => 1,
					'prev_page' => 0,
				);
				$result['list'] = array();
			}
		}catch(Exception $e){
			error_log($e);
			$result = array(
				'list' => array(),
				'status' => array(),
			);
		}

		return $result;
	}

	public function queryCollectionPage($template_name='',$wDat='',$templateDat=array(),$page='1'){
		if($tmp = $this->scope::queryCollection($template_name,$wDat,array_merge($templateDat,array('count'=>true)))){
			foreach($tmp as $item){
				$this->_max_rec = $item['cnt'];
			}
		}

		$result = array();
		try{
			$offset = ($page - 1) * $this->_page_max_rec;
			if($list = $this->scope::queryCollection($template_name,$wDat,array_merge($templateDat,array('count'=>false,'limit'=>$offset.','.$this->_page_max_rec)))){
				$result['status'] = $this->createPageStatus($page);
				$result['list'] = $list;
			}else{
				$result['status'] = array(
					'max_rec' => $this->_max_rec,
					'page_no' => $page,
					'max_page' => 1,
					'next_page' => 1,
					'prev_page' => 0,
				);
				$result['list'] = array();
			}
		}catch(Exception $e){
			error_log($e);
			$result = array(
				'list' => array(),
				'status' => array(),
			);
		}

		return $result;
	}

	private function createPageStatus($page){
		$page = empty($page) ? '1':$page;
		$max_page = floor($this->_max_rec / $this->_page_max_rec);
		$tmp = $this->_max_rec % $this->_page_max_rec;
		if(!empty($tmp)){
			$max_page++;
		}

		$next_page = $max_page == $page ? 0 : $page + 1;
		$prev_page = $page - 1;

		return array(
			'max_rec' => $this->_max_rec,
			'page_no' => $page,
			'max_page' => $max_page,
			'next_page' => $next_page,
			'prev_page' => $prev_page,
		);
	}

	public function queryCollectionFtextPage($template_name="",$wDat="",$templateDat=array(),$page="1"){
		if($tmp = SFDAO::queryCollection($template_name,$wDat,array_merge($templateDat,array('count'=>true))) ){
			foreach($tmp as $item);
			$this->_max_rec = $item["cnt"];
		}


		$result = array();
		try{
			$offset = ($page - 1) * $this->_page_max_rec;
			if($list = SFDAO::queryCollection($template_name,$wDat,array_merge($templateDat,array('count'=>false,'limit'=>$offset.",".$this->_page_max_rec)))){
				$result["status"] = $this->createPageStatus($page);
				$result["list"] = $list;
			}else{
				$result["status"] = array("max_rec"=>$this->_max_rec,
										"page_no"=>$page,
										"max_page"=>1,
										"next_page"=>1,
										"prev_page"=>0);
				$result["list"] = array();
			}

		}catch(exception $e){
			error_log(serialize($e));
		}
		return $result;
	}
}

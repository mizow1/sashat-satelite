<?php

require_once(LIB_DIR.'local/aws/bin/vendor/autoload.php');

class SOPS{
	private static $objClient;

	public function __construct(){
		throw new RuntimeException('このクラスはインスタンス化禁止です');
	}

	public static function init($page_max_rec='100'){
		if($_SERVER['PANDA_ENV'] == 'test'){
			error_log('Cannot use opensearch in test environment.');
			return;
		}

		self::$objClient = (new \OpenSearch\ClientBuilder())
			->setHosts([OPENSEARCH_HOST])
			->setBasicAuthentication(OPENSEARCH_AUTH_USER, OPENSEARCH_AUTH_PASS) // For testing only. Don't store credentials in code.
			->setSSLVerification(false) // For testing only. Use certificate for validation
			->build();
	}

	public static function info(){
		return self::$objClient->info();
	}

	public static function ping()
	{
		try {
			$isAvailable = self::$objClient->ping();
		} catch (Exception $e) {
			error_log($e);
			$isAvailable = false;
		}

		return $isAvailable;
	}

	/**
	* SQLでのinsertに該当
	* body 例）
	*	'article_id' => '671116',
	*	'search_text' => "空知管内の感染者５週連続増加　前週比１．３倍に\n\n　道が１８日に公表した先週１週間（１０～１６日）の自治体別の新型コロナウイルス感染者数によると、空知管内全体では前週（３～９日）比１・３倍の７１１人となった。",
	*	'viewer_user_type' => '31',
	*	'open_start_date' => "2022-04-19T05:00:00Z",
	*
	**/
	public static function create($index_name='', $id='', $body=array()){
		if(empty($index_name) || empty($id)){
			return false;
		}

		self::$objClient->create(array(
			'index' => $index_name,
			'id' => $id,
			'body' => $body,
		));

		return true;
	}

	/**
	* 存在確認
	**/
	public static function exists($index_name='', $id=''){
		if(empty($index_name) || empty($id)){
			return false;
		}

		return self::$objClient->exists(array(
			'index' => $index_name,
			'id' => $id,
		));
	}

	/**
	* match 例）
	* 	array(
	*		array("search_text"=>"マスク"),
	*		array("search_text"=>"イーロン"),
	*	)
	*
	**/
	public static function count($index_name='', $match=array()){
		if(empty($index_name) || empty($match)){
			return 0;
		}

		$must_match = array();
		foreach($match as $key=>$val){
			$must_match[] = array("match_phrase" => $val);
		}

		$result = self::$objClient->count(
			array(
				'index' => $index_name,
				'body' => array(
					"query" => array(
						"bool" => array(
							"must" => $must_match,
							"filter" => $must_match
						)
					)
				)
			)
		);

		return $result['count'];
	}

	/**
	* match 例）
	* 	array(
	*		array("search_text"=>"マスク"),
	*		array("search_text"=>"イーロン"),
	*	)
	*
	* $source 例）
	* 	array("article_id","open_start_date")
	*
	* $sort 例）
	*	array(
	*		"open_start_date" => "desc",
	*		"_id" => "desc"
	*	)
	**/
	public static function searchList($index_name='', $match=array(), $source=array(), $sort=array(), $size=100, $from=0){
		if(empty($index_name) || empty($match)){
			return array();
		}

		$must_match = array();
		foreach($match as $mval){
			$must_match[] = array("match_phrase" => $mval);
		}

		if(!empty($sort)){
			foreach($sort as $skey=>$sval){
				$sort[$skey] = array( "order" => $sval);
			}
		}

		$result = self::$objClient->search(
			array(
				'index' => $index_name,
				'body' => array(
					"query" => array(
						"bool" => array(
							"must" => $must_match,
							"filter" => $must_match
						)
					),
					"_source" => $source,
					"sort" => array($sort),
					"from" => $from,
					"size" => $size,
				)
			)
		);

		if(empty($result['hits']) || empty($result['hits']['hits'])){
			return array();
		}

		return $result['hits']['hits'];
	}

	/**
	* match 例）
	* 	array(
	*		array("search_text"=>"マスク"),
	*		array("search_text"=>"イーロン"),
	*	)
	*
	* $source 例）
	* 	array("article_id","open_start_date")
	*
	* $sort 例）
	*	array(
	*		"open_start_date" => "desc",
	*		"_id" => "desc"
	*	)
	**/
	public static function searchListPage($index_name='', $match=array(), $source=array(), $sort=array(), $size=100, $page=1){
		if(empty($index_name) || empty($match)){
			return array();
		}

		$max_rec = self::count($index_name, $match);

		$must_match = array();
		foreach($match as $mval){
			$must_match[] = array("match_phrase" => $mval);
		}

		if(!empty($sort)){
			foreach($sort as $skey=>$sval){
				$sort[$skey] = array( "order" => $sval);
			}
		}

		$offset = ($page - 1) * $size;
		$result = self::$objClient->search(
			array(
				'index' => $index_name,
				'body' => array(
					"query" => array(
						"bool" => array(
							"must" => $must_match,
							"filter" => $must_match
						)
					),
					"_source" => $source,
					"sort" => array($sort),
					"from" => $offset,
					"size" => $size,
				)
			)
		);

		$result['status'] = array(
			'max_rec' => $max_rec,
			'page_no' => $page,
			'max_page' => 1,
			'next_page' => 1,
			'prev_page' => 0,
		);
		$result['list'] = array();

		if(empty($result['hits']) || empty($result['hits']['hits'])){
			return $result;
		}

		$result['status'] = self::createPageStatus($page, $max_rec, $size);
		$hits = $result['hits']['hits'];
		$result['list'] = array();
		foreach($hits as $hit){
			$result['list'][$hit['_id']] = $hit['_source'];
		}

		return $result;
	}

	private static function createPageStatus($page, $max_rec, $size){
		$page = empty($page) ? '1':$page;
		$max_page = floor($max_rec / $size);
		$tmp = $max_rec % $size;
		if(!empty($tmp)){
			$max_page++;
		}

		$next_page = $max_page == $page ? 0 : $page + 1;
		$prev_page = $page - 1;

		return array(
			'max_rec' => $max_rec,
			'page_no' => $page,
			'max_page' => $max_page,
			'next_page' => $next_page,
			'prev_page' => $prev_page,
		);
	}

	// id 指定での削除
	public static function delete($index_name='', $id=''){
		if(empty($index_name) || empty($id)){
			return false;
		}
		self::$objClient->delete([
			'index' => $index_name,
			'id' => $id
		]);

		return true;
	}

	// indexごと削除
	public static function deleteIndex($index_name=''){
		if(empty($index_name)){
			return false;
		}
		self::$objClient->indices()->delete([
			'index' => $index_name
		]);

		return true;
	}

	public static function update($index_name='', $id='', $body=array()){
		if(empty($index_name) || empty($id)){
			return false;
		}

		self::$objClient->update(array(
			'index' => $index_name,
			'id' => $id,
			'body' => $body,
		));

		return true;
	}
}

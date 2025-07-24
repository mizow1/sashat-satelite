<?php
require_once(MODEL.'front/controller/AbstractControllerClass.php');
require_once(MODEL.'front/uranai/ApiModelClass.php');
class TopAction extends AbstractController{
	function __construct($controller='',$action='',&$session_data=array(),$device=''){
		$this->init($controller,$action,$session_data,$device);
	}
	function Execute($get_data=array(),&$session_data=array()){


		$disp_array = array();

		$api = new ApiModel();
		$disp_array = $api->getApi(API_TOP);
		
		// コラム最新4件を取得
		$latest_columns = $this->getLatestColumns(4);
		$disp_array['latest_columns'] = $latest_columns;
		
		// デバッグ用：コラムデータが取得できているか確認
		if(!empty($GLOBALS['debug_flag'])){
			error_log("@@@ TopAction latest_columns count: " . count($latest_columns));
			error_log("@@@ TopAction disp_array keys: " . implode(', ', array_keys($disp_array)));
		}
		
		$this->display($disp_array, 'preview_rakuten');
	}
	
	private function getLatestColumns($limit = 4){
		$articles = array();
		$csv_file = dirname(dirname(dirname(dirname(__FILE__)))) . '/column.csv';
		
		// デバッグ用：CSVファイルパスとファイル存在確認
		if(!empty($GLOBALS['debug_flag'])){
			error_log("@@@ CSV file path: " . $csv_file);
			error_log("@@@ CSV file exists: " . (file_exists($csv_file) ? 'true' : 'false'));
		}
		
		if (!file_exists($csv_file)) {
			return $articles;
		}
		
		$handle = fopen($csv_file, 'r');
		if ($handle === false) {
			return $articles;
		}
		
		// ヘッダー行をスキップ
		$header = fgetcsv($handle);
		
		$now = date('Y-m-d H:i:s');
		
		while (($data = fgetcsv($handle)) !== false) {
			if (count($data) >= 7) {
				$post_date = $data[5];
				
				// 公開日が空欄または未来の記事は除外
				if (empty($post_date) || $post_date > $now) {
					continue;
				}
				
				$articles[] = array(
					'id' => $data[0],
					'title' => $data[1],
					'seo_keywords' => $data[2],
					'summary' => $data[3],
					'content' => $data[4],
					'post_date' => $data[5],
					'created_date' => $data[6],
					'formatted_post_date' => $this->formatPostDate($data[5])
				);
			}
		}
		
		fclose($handle);
		
		// 投稿日時でソート（新しい順）
		usort($articles, function($a, $b) {
			return strcmp($b['post_date'], $a['post_date']);
		});
		
		$result = array_slice($articles, 0, $limit);
		
		// デバッグ用：取得した記事数を確認
		if(!empty($GLOBALS['debug_flag'])){
			error_log("@@@ Total articles found: " . count($articles));
			error_log("@@@ Returned articles count: " . count($result));
		}
		
		return $result;
	}
	
	private function formatPostDate($post_date){
		if (empty($post_date)) {
			return '';
		}
		
		$timestamp = strtotime($post_date);
		if ($timestamp === false) {
			return '';
		}
		
		return date('Y年n月j日', $timestamp);
	}
	function PostExecute($get_data=array(),$post_data=array(),$file_data=array(),&$session_data=array()){

	}
}

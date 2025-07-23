<?php
require_once(MODEL.'front/controller/AbstractControllerClass.php');
require_once(MODEL.'front/uranai/ApiModelClass.php');
require_once(dirname(dirname(dirname(__FILE__))).'/lib/ColumnApiClient.php');

class ColumnAction extends AbstractController{
	private $apiClient;
	
	function __construct($controller='',$action='',&$session_data=array(),$device=''){
		$this->init($controller,$action,$session_data,$device);
		
		// API クライアントを初期化
		$this->apiClient = new ColumnApiClient([
			'api_endpoint' => 'https://uranai.flier.jp/uranai_common/api/v1',
			'site_id' => 'satellite_site',
			'cache_expiry' => 3600
		]);
	}
	
	function Execute($get_data=array(),&$session_data=array()){
		$disp_array = array();
		
		// デバッグ情報を追加
		error_log("ColumnAction::Execute called");
		
		// ページネーション処理
		$page = isset($get_data['page']) ? (int)$get_data['page'] : 1;
		$per_page = 50;
		
		try {
			// 外部APIからコラム一覧を取得
			$apiData = $this->apiClient->getColumnsList($page, $per_page);
			
			// APIレスポンスから必要なデータを抽出
			$articles = $apiData['columns'];
			$total_articles = $apiData['total'];
			$total_pages = ceil($total_articles / $per_page);
			
			// 各記事の公開日をフォーマット
			foreach ($articles as &$article) {
				$article['formatted_post_date'] = $this->formatPostDate($article['post_date']);
			}
			
			error_log("API data loaded, articles count: " . count($articles));
			
		} catch (Exception $e) {
			error_log("API request failed, using fallback: " . $e->getMessage());
			
			// フォールバック: ローカルデータを使用
			$fallbackData = $this->apiClient->getFallbackData('list', [
				'page' => $page,
				'limit' => $per_page
			]);
			
			if ($fallbackData) {
				$articles = $fallbackData['columns'];
				$total_articles = $fallbackData['total'];
				$total_pages = ceil($total_articles / $per_page);
				
				// 各記事の公開日をフォーマット
				foreach ($articles as &$article) {
					$article['formatted_post_date'] = $this->formatPostDate($article['post_date']);
				}
			} else {
				$articles = [];
				$total_articles = 0;
				$total_pages = 0;
			}
		}
		
		$disp_array['articles'] = $articles;
		$disp_array['current_page'] = $page;
		$disp_array['total_pages'] = $total_pages;
		$disp_array['total_articles'] = $total_articles;
		
		error_log("Template data prepared, calling display");
		$this->display($disp_array, 'column_list');
	}
	
	function PostExecute($get_data=array(),$post_data=array(),$file_data=array(),&$session_data=array()){
		// POST処理が必要な場合はここに実装
	}
	
	// loadArticles メソッドは不要になったため削除
	// 外部APIまたはフォールバック処理で代替
	
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
}
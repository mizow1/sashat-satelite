<?php
require_once(MODEL.'front/controller/AbstractControllerClass.php');
require_once(MODEL.'front/uranai/ApiModelClass.php');
require_once(dirname(dirname(dirname(__FILE__))).'/lib/ColumnApiClient.php');

class Column_detailAction extends AbstractController{
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
		
		// 記事IDを取得
		$article_id = isset($get_data['id']) ? $get_data['id'] : '';
		
		if (empty($article_id)) {
			throw new Exception('404');
		}
		
		try {
			// 外部APIからコラム詳細を取得
			$apiData = $this->apiClient->getColumnDetail($article_id);
			
			if (!$apiData) {
				throw new Exception('404');
			}
			
			// APIレスポンスから記事データを構築
			$article = [
				'id' => $apiData['id'],
				'title' => $apiData['title'],
				'seo_keywords' => $apiData['seo_keywords'],
				'summary' => $apiData['summary'],
				'content' => $apiData['content'],
				'content_html' => $apiData['content'], // APIから既にHTMLで返される
				'post_date' => $apiData['post_date'],
				'formatted_post_date' => $this->formatPostDate($apiData['post_date'])
			];
			
			// 関連記事もAPIから取得
			$related_articles = $apiData['related_columns'] ?? [];
			
		} catch (Exception $e) {
			error_log("API request failed, using fallback: " . $e->getMessage());
			
			// フォールバック: ローカルデータを使用
			$fallbackData = $this->apiClient->getFallbackData('detail', [
				'column_id' => $article_id
			]);
			
			if (!$fallbackData) {
				throw new Exception('404');
			}
			
			$article = [
				'id' => $fallbackData['id'],
				'title' => $fallbackData['title'],
				'seo_keywords' => $fallbackData['seo_keywords'],
				'summary' => $fallbackData['summary'],
				'content' => $fallbackData['content'],
				'content_html' => $fallbackData['content'],
				'post_date' => $fallbackData['post_date'],
				'formatted_post_date' => $this->formatPostDate($fallbackData['post_date'])
			];
			
			$related_articles = $fallbackData['related_columns'] ?? [];
		}
		
		$disp_array['article'] = $article;
		$disp_array['related_articles'] = $related_articles;
		
		$this->display($disp_array, 'column_detail');
	}
	
	function PostExecute($get_data=array(),$post_data=array(),$file_data=array(),&$session_data=array()){
		// POST処理が必要な場合はここに実装
	}
	
	// 以下のメソッドは外部API化により不要になったため削除
	// - loadArticles: APIクライアントが代替
	// - findArticleById: APIエンドポイントが代替
	// - markdownToHtml: 中央サーバーで処理
	// - getRelatedArticles: 中央サーバーで処理
	
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
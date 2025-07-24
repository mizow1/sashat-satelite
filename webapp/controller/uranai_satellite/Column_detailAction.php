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
				'content_html' => $this->convertMarkdownToHtml($apiData['content']), // マークダウンをHTMLに変換
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
				'content_html' => $this->convertMarkdownToHtml($fallbackData['content']),
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
	
	/**
	 * マークダウンをHTMLに変換
	 */
	private function convertMarkdownToHtml($markdown){
		if (empty($markdown)) {
			return '';
		}
		
		$html = $markdown;
		
		// リンク記法 [text](url)
		$html = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" target="_blank" rel="noopener">$1</a>', $html);
		
		// 太字 **text**
		$html = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $html);
		
		// 斜体 *text*
		$html = preg_replace('/\*([^*]+)\*/', '<em>$1</em>', $html);
		
		// 見出し
		$html = preg_replace('/^#### (.+)$/m', '<h5>$1</h5>', $html);
		$html = preg_replace('/^### (.+)$/m', '<h4>$1</h4>', $html);
		$html = preg_replace('/^## (.+)$/m', '<h3>$1</h3>', $html);
		$html = preg_replace('/^# (.+)$/m', '<h2>$1</h2>', $html);
		
		// 箇条書き（- または * で始まる行）
		$html = preg_replace('/^[\-\*] (.+)$/m', '<li>$1</li>', $html);
		
		// 連続するliタグをulで囲む
		$html = preg_replace('/(<li>.+<\/li>)/s', '<ul>$1</ul>', $html);
		$html = preg_replace('/(<\/ul>)\s*(<ul>)/', '', $html); // 連続するul要素を統合
		
		// 番号付きリスト（1. で始まる行）
		$html = preg_replace('/^\d+\. (.+)$/m', '<li>$1</li>', $html);
		
		// コードブロック ```は除去（内容のみ残す）
		$html = preg_replace('/```([^`]+)```/s', '$1', $html);
		
		// インラインコード `code`は除去（内容のみ残す）
		$html = preg_replace('/`([^`]+)`/', '$1', $html);
		
		// 水平線 ---
		$html = preg_replace('/^---+$/m', '<hr>', $html);
		
		// 段落（空行で区切られたテキストをpタグで囲む）
		$paragraphs = preg_split('/\n\s*\n/', $html);
		$processedParagraphs = [];
		
		foreach ($paragraphs as $paragraph) {
			$paragraph = trim($paragraph);
			if (!empty($paragraph)) {
				// 既にHTMLタグで始まっている場合はそのまま
				if (preg_match('/^<(h[2-6]|ul|ol|pre|hr|div|blockquote)/', $paragraph)) {
					$processedParagraphs[] = $paragraph;
				} else {
					// 改行をbrタグに変換してpタグで囲む
					$paragraph = nl2br($paragraph);
					$processedParagraphs[] = '<p>' . $paragraph . '</p>';
				}
			}
		}
		
		$html = implode("\n\n", $processedParagraphs);
		
		// pre、code要素、「markdown」文字列を除去
		$html = $this->removeUnwantedElements($html);
		
		return $html;
	}
	
	/**
	 * 不要な要素を除去
	 */
	private function removeUnwantedElements($html){
		if (empty($html)) {
			return '';
		}
		
		// pre要素を除去（開始タグから終了タグまで）
		$html = preg_replace('/<pre[^>]*>.*?<\/pre>/is', '', $html);
		
		// code要素を除去（開始タグから終了タグまで）
		$html = preg_replace('/<code[^>]*>.*?<\/code>/is', '', $html);
		
		// 「markdown」文字列を除去（大文字小文字区別なし）
		$html = preg_replace('/markdown/i', '', $html);
		
		// 改行の正規化処理
		$html = $this->normalizeLineBreaks($html);
		
		// 余分な空白を整理（改行は除く）
		$html = preg_replace('/[ \t]+/', ' ', $html);
		$html = preg_replace('/\s*<\/p>\s*<p>\s*/', '</p><p>', $html);
		$html = trim($html);
		
		return $html;
	}
	
	/**
	 * 改行の正規化処理
	 */
	private function normalizeLineBreaks($html){
		if (empty($html)) {
			return '';
		}
		
		// Windows改行(\r\n)をUnix改行(\n)に統一
		$html = str_replace("\r\n", "\n", $html);
		$html = str_replace("\r", "\n", $html);
		
		// <br>タグと改行コードの混在パターンを正規化
		// <br>\n, <br/>\n, <br />\n などを統一
		$html = preg_replace('/<br\s*\/?>\s*\n/i', '<br>', $html);
		
		// 連続する<br>タグを1つにまとめる
		$html = preg_replace('/(<br\s*\/?>[\s\n]*)+/i', '<br>', $html);
		
		// 連続する改行コード(\n)を1つにまとめる
		$html = preg_replace('/\n+/', "\n", $html);
		
		// <br>タグの前後の余分な空白を除去
		$html = preg_replace('/\s*<br\s*\/?>\s*/i', '<br>', $html);
		
		return $html;
	}
}
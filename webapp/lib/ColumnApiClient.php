<?php
/**
 * コラムAPI クライアントクラス
 * 中央サーバーとの通信を処理
 */

class ColumnApiClient
{
    private $apiEndpoint;
    private $siteId;
    private $apiKey;
    private $cacheExpiry;
    
    public function __construct($config = [])
    {
        $this->apiEndpoint = $config['api_endpoint'] ?? 'https://uranai.flier.jp/uranai_common/api/v1';
        $this->siteId = $config['site_id'] ?? 'default_site';
        $this->apiKey = $config['api_key'] ?? '';
        $this->cacheExpiry = $config['cache_expiry'] ?? 3600;
    }
    
    /**
     * コラム一覧を取得
     */
    public function getColumnsList($page = 1, $limit = 50, $sort = 'post_date_desc')
    {
        $url = $this->apiEndpoint . '/columns';
        $params = [
            'site_id' => $this->siteId,
            'page' => $page,
            'limit' => $limit,
            'sort' => $sort
        ];
        
        return $this->makeRequest($url, $params);
    }
    
    /**
     * コラム詳細を取得
     */
    public function getColumnDetail($columnId)
    {
        $url = $this->apiEndpoint . '/columns/' . $columnId;
        $params = [
            'site_id' => $this->siteId
        ];
        
        return $this->makeRequest($url, $params);
    }
    
    /**
     * 最新コラムを取得
     */
    public function getLatestColumns($limit = 4)
    {
        $url = $this->apiEndpoint . '/columns/latest';
        $params = [
            'site_id' => $this->siteId,
            'limit' => $limit
        ];
        
        return $this->makeRequest($url, $params);
    }
    
    /**
     * APIリクエストを実行
     */
    private function makeRequest($url, $params = [])
    {
        // URLにパラメータを追加
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        // cURLでリクエスト実行
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'User-Agent: ColumnApiClient/1.0'
            ]
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception("cURL Error: " . $error);
        }
        
        if ($httpCode !== 200) {
            throw new Exception("HTTP Error: " . $httpCode);
        }
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON Decode Error: " . json_last_error_msg());
        }
        
        if ($data['status'] !== 'success') {
            throw new Exception("API Error: " . ($data['error']['message'] ?? 'Unknown error'));
        }
        
        return $data['data'];
    }
    
    /**
     * フォールバック用のローカルデータ読み込み
     * APIが利用できない場合の代替処理
     */
    public function getFallbackData($type, $params = [])
    {
        switch ($type) {
            case 'list':
                return $this->loadLocalColumnsList($params);
            case 'detail':
                return $this->loadLocalColumnDetail($params);
            case 'latest':
                return $this->loadLocalLatestColumns($params);
            default:
                return null;
        }
    }
    
    /**
     * ローカルCSVからコラム一覧を読み込み（フォールバック用）
     */
    private function loadLocalColumnsList($params)
    {
        $articles = $this->loadArticlesFromCsv();
        
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 50;
        
        $total = count($articles);
        $offset = ($page - 1) * $limit;
        $pagedArticles = array_slice($articles, $offset, $limit);
        
        $columns = [];
        foreach ($pagedArticles as $article) {
            $columns[] = [
                'id' => $article['id'],
                'title' => $article['title'],
                'seo_keywords' => $article['seo_keywords'],
                'summary' => $article['summary'],
                'post_date' => $article['post_date'],
                'url' => "/column/{$article['id']}/"
            ];
        }
        
        return [
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'columns' => $columns
        ];
    }
    
    /**
     * ローカルCSVからコラム詳細を読み込み（フォールバック用）
     */
    private function loadLocalColumnDetail($params)
    {
        $articles = $this->loadArticlesFromCsv();
        $columnId = $params['column_id'] ?? '';
        
        $targetArticle = null;
        foreach ($articles as $article) {
            if ($article['id'] == $columnId) {
                $targetArticle = $article;
                break;
            }
        }
        
        if (!$targetArticle) {
            return null;
        }
        
        // マークダウンからHTMLに変換（簡単な処理）
        $htmlContent = $this->simpleMarkdownToHtml($targetArticle['content']);
        
        // 関連記事
        $relatedColumns = [];
        $count = 0;
        foreach ($articles as $article) {
            if ($article['id'] != $columnId && $count < 5) {
                $relatedColumns[] = [
                    'id' => $article['id'],
                    'title' => $article['title'],
                    'url' => "/column/{$article['id']}/"
                ];
                $count++;
            }
        }
        
        return [
            'id' => $targetArticle['id'],
            'title' => $targetArticle['title'],
            'seo_keywords' => $targetArticle['seo_keywords'],
            'summary' => $targetArticle['summary'],
            'content' => $htmlContent,
            'post_date' => $targetArticle['post_date'],
            'related_columns' => $relatedColumns
        ];
    }
    
    /**
     * ローカルCSVから最新コラムを読み込み（フォールバック用）
     */
    private function loadLocalLatestColumns($params)
    {
        $articles = $this->loadArticlesFromCsv();
        $limit = $params['limit'] ?? 4;
        
        $latestArticles = array_slice($articles, 0, $limit);
        
        $columns = [];
        foreach ($latestArticles as $article) {
            $columns[] = [
                'id' => $article['id'],
                'title' => $article['title'],
                'summary' => $article['summary'],
                'post_date' => $article['post_date'],
                'url' => "/column/{$article['id']}/"
            ];
        }
        
        return [
            'columns' => $columns
        ];
    }
    
    /**
     * CSVファイルからコラムデータを読み込み
     */
    private function loadArticlesFromCsv()
    {
        $csvFile = dirname(dirname(dirname(__FILE__))) . '/column.csv';
        
        if (!file_exists($csvFile)) {
            return [];
        }
        
        $articles = [];
        $handle = fopen($csvFile, 'r');
        
        if ($handle === false) {
            return [];
        }
        
        // ヘッダー行をスキップ
        fgetcsv($handle);
        
        $now = date('Y-m-d H:i:s');
        
        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) >= 7) {
                $postDate = $data[5];
                
                // 公開日が空欄または未来の記事は除外
                if (empty($postDate) || $postDate > $now) {
                    continue;
                }
                
                $articles[] = [
                    'id' => $data[0],
                    'title' => $data[1],
                    'seo_keywords' => $data[2],
                    'summary' => $data[3],
                    'content' => $data[4],
                    'post_date' => $data[5],
                    'created_date' => $data[6]
                ];
            }
        }
        
        fclose($handle);
        
        // 投稿日時でソート（新しい順）
        usort($articles, function($a, $b) {
            return strcmp($b['post_date'], $a['post_date']);
        });
        
        return $articles;
    }
    
    /**
     * 簡単なマークダウンからHTML変換
     */
    private function simpleMarkdownToHtml($markdown)
    {
        $html = $markdown;
        
        // 見出し
        $html = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $html);
        $html = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $html);
        
        // 改行をbrタグに変換
        $html = nl2br($html);
        
        return $html;
    }
}
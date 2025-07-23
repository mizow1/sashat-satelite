<?php
require_once(LIB_DIR.'Google/autoload.php');

class Google_Analytics{
	// 中日本番
	private $config = array(
		// メールアドレス
		'service_account_name' => 'ranking2020@ranking2020-270908.iam.gserviceaccount.com',

		// 承認スコープ
		'scopes' => array(
			'https://www.googleapis.com/auth/analytics'
		),

		// 秘密鍵
		'key_file_location' => '/key/ranking2020-1e14d8650afd.p12',

		// ビューID
		'view_id' => array(
			'chunichi' => array(
				'202603117', // 中日新聞
				'202579528', // 中日スポーツ
				'202600376', // 北陸中日新聞
				'202605416', // 中日新聞しずおか
				'202615722', // 日刊県民福井
			),
			'tokyo' => array(
				'202576493', // 東京新聞ウェブ全体
			),
			'default' => array(),
		),
	);

	private $view_id_type = '';

	private $dimensions_master = array(
		'article_id' => 'ga:dimension1',
		'category_type_id' => 'ga:dimension2',
		'category_id' => 'ga:dimension3',
	);

	// analyticsクライアントインスタンス
	private $analytics = null;

	public function __construct($view_id_type='default'){
		$client = new Google_Client();
		if (isset($_SESSION['google']['service_token'])) {
			$client->setAccessToken($_SESSION['google']['service_token']);
		}

		$key = file_get_contents(__DIR__.$this->config['key_file_location']);
		$credentials = new Google_Auth_AssertionCredentials($this->config['service_account_name'], $this->config['scopes'], $key);
		$client->setAssertionCredentials($credentials);
		if ($client->getAuth()->isAccessTokenExpired()) {
			$client->getAuth()->refreshTokenWithAssertion($credentials);
		}
		$_SESSION['google']['service_token'] = $client->getAccessToken();

		$this->analytics = new Google_Service_Analytics($client);

		$this->view_id_type = $view_id_type;
	}

	public function getPvRanking($startDate, $endDate, $max){
		if (is_null($this->analytics)) {
			return;
		}

		// ページタイトルでPV数を集計する
		$ids = 'ga:'.$this->config['view_id'];
		$metrics = 'ga:pageviews';
		$optParams = array(
			'dimensions' => 'ga:pageTitle',
			'sort' => '-ga:pageviews',
			'max-results' => $max,
		);

		$result = $this->analytics->data_ga->get($ids, $startDate, $endDate, $metrics, $optParams);
		$result = $this->toArray($result);
		if (empty($result)) {
			return array();
		}

		$articles = array();
		foreach ($result as $key => $value) {
			$articles[] = array(
				'path' => $this->getPagePath($ids, $startDate, $endDate, $metrics, $value['ga:pageTitle']),
				'pv' => $value['ga:pageviews'],
				'rank' => $key + 1,		// 添字は0開始なので+1する
			);
		}

		return $articles;
	}

	// 同一ページタイトルのURLを全て取得し、先頭1件を返す
	private function getPagePath($ids, $startDate, $endDate, $metrics, $title){
		$optParams = array(
			'dimensions' => 'ga:pagePath',
			'filters' => 'ga:pageTitle=='.$title,
		);
		$pages = $this->analytics->data_ga->get($ids, $startDate, $endDate, $metrics, $optParams);
		$pages = $this->toArray($pages);
		if (empty($pages)) {
			return '';
		}
		$page = reset($pages);
		return $page['ga:pagePath'];
	}

	// カラム名をキーとする連想配列に変換
	private function toArray($gaData){
		if (empty($gaData) || is_null($gaData->rows)) {
			return array();
		}

		$list = array();
		foreach ($gaData->rows as $row) {
			$data = array();
			foreach ($gaData->columnHeaders as $key => $value) {
				// columnHeadersを使って連想配列のキーをカラム名にする
				$data[$value->name] = $row[$key];
			}
			$list[] = $data;
		}
		return $list;
	}

	public function getPvRankingCustomByArticleId($startDate, $endDate, $max, $start_index=1, $article_id=0){
		if (is_null($this->analytics) || empty($article_id)) {
			return array();
		}

		$tmp_list = array();

		// view_idごとにpv数集計取得
		foreach($this->config['view_id'][$this->view_id_type] as $view_id){
			$result = array();

			// idでPV数を集計する
			$ids = 'ga:'.$view_id;
			$metrics = 'ga:pageviews';

			$optParams = array(
				'dimensions' => $this->dimensions_master['article_id'],
				'sort' => '-ga:pageviews',
				'start-index' => $start_index,
				'max-results' => $max,
				'filters' => $this->dimensions_master['article_id'].'=='.$article_id,
			);

			$result = $this->analytics->data_ga->get($ids, $startDate, $endDate, $metrics, $optParams);
			if (empty($result)) {
				continue;
			}

			$result = $this->toArray($result);
			if (empty($result)) {
				continue;
			}

			$tmp_list[$view_id] = array();

			foreach ($result as $key => $value) {
				$tmp_list[$view_id][] = array(
					'article_id' => $value['ga:dimension1'],
					'pv' => $value['ga:pageviews'],
					'rank' => $key + 1,		// 添字は0開始なので+1する
				);
			}
		}

		if(empty($tmp_list)){
			return array();
		}

		$return_list = array(); // 返却用配列

		// 同article_id分を統合する
		foreach($tmp_list as $article_list){
			foreach($article_list as $article_list_key=>$article){
				if(array_key_exists($article['article_id'], $return_list)){
					$return_list[$article['article_id']]['pv'] = (int)$return_list[$article['article_id']]['pv'] + (int)$article['pv'];
					continue;
				}
				$return_list[$article['article_id']] = $article;
			}
		}

		// pv数でソート
		foreach($return_list as $ret_val){
			$pv_array[] = $ret_val['pv'];
			$article_id_array[] = $ret_val['article_id'];
		}
		array_multisort($pv_array, SORT_DESC, SORT_NUMERIC, $article_id_array, SORT_DESC, SORT_NUMERIC, $return_list);

		// rank値の修正
		$rank = 0;
		foreach($return_list as $ret_key=>$ret_val){
			$rank++;
			$return_list[$ret_key]['rank'] = $rank;
		}

		return $return_list;
	}

	public function getPvRankingCustom($startDate, $endDate, $max, $uniq_dimensions=array(), $start_index=1, $category_type_id=0, $category_id_list=array(), $ignore_category_type_id_list=array(), $ignore_category_id_list=array()){
		if (is_null($this->analytics)) {
			return;
		}

		$dimentions_array = array();
		if(!empty($uniq_dimensions)){
			foreach($uniq_dimensions as $val){
				if(array_key_exists($val, $this->dimensions_master)){
					$dimentions_array[] = $this->dimensions_master[$val];
				}
			}
		}

		$optParams_dimentions = implode(',', $dimentions_array);


		$tmp_list = array();

		// view_idごとにpv数集計取得
		foreach($this->config['view_id'][$this->view_id_type] as $view_id){
			$result = array();

			// idでPV数を集計する
			$ids = 'ga:'.$view_id;
			$metrics = 'ga:pageviews';

			$optParams = array(
				'dimensions' => $optParams_dimentions,
	//			'dimensions' => 'ga:pagePath',
				'sort' => '-ga:pageviews',
				'start-index' => $start_index,
				'max-results' => $max,
			);

			// filtersを中身が空で設定するとanalyticsからの取得でエラーとなるので、必要な場合のみ設定する
			if(
				!empty($category_type_id) ||
				!empty($category_id_list) ||
				!empty($ignore_category_type_id_list) ||
				!empty($ignore_category_id_list)
			){
				$optParams['filters'] = $this->createAnalyticsCustomFilters($category_type_id, $category_id_list, $ignore_category_type_id_list, $ignore_category_id_list);
			}

			$result = $this->analytics->data_ga->get($ids, $startDate, $endDate, $metrics, $optParams);
			if (empty($result)) {
				continue;
			}

			$result = $this->toArray($result);
			if (empty($result)) {
				continue;
			}

			$tmp_list[$view_id] = array();
			foreach ($result as $key => $value) {
				$tmp_list[$view_id][] = array(
					'article_id' => $value['ga:dimension1'],
					'pv' => $value['ga:pageviews'],
					'category_type_id' => empty($value['ga:dimension2']) ? 0 : $value['ga:dimension2'],
					'category_id' => empty($value['ga:dimension3']) ? 0 : $value['ga:dimension3'],
					'rank' => $key + 1,		// 添字は0開始なので+1する
				);
			}
		}

		if(empty($tmp_list)){
			return array();
		}

		$return_list = array(); // 返却用配列
		foreach($tmp_list as $article_list){
			foreach($article_list as $article_list_key=>$article){
				$key = $article['article_id'].'_'.$article['category_type_id'].'_'.$article['category_id'];

				// 返却用配列内にすでに存在している記事（一意条件：カテゴリタイプidとカテゴリid）は削除する
				if(array_key_exists($key, $return_list)){
					unset($article_list[$article_list_key]);
					continue;
				}

				$return_list[$key] = $article;
			}
		}

		// ソート
		foreach($return_list as $ret_val){
			$pv_array[] = $ret_val['pv'];
			$article_id_array[] = $ret_val['article_id'];
		}
		array_multisort($pv_array, SORT_DESC, SORT_NUMERIC, $article_id_array, SORT_DESC, SORT_NUMERIC, $return_list);

		// rank値の修正
		$rank = 0;
		foreach($return_list as $ret_key=>$ret_val){
			$rank++;
			$return_list[$ret_key]['rank'] = $rank;
		}

		return $return_list;
	}

	/**
	 * グーグルアナリティクスからデータ取得の条件に使用する filters の内容を作成
	 *
	 * 例）ga:dimension2==1,ga:dimension3==2;ga:dimension3!=3;ga:dimension3!=4;ga:dimension1!=100
	 *
	 * @param int $category_type_id
	 * @param array $category_id_list
	 * @param array $ignore_category_type_id_list
	 * @param array $ignore_category_id_list
	 * @return string $filters
	 */
	private function createAnalyticsCustomFilters($category_type_id=0, $category_id_list=array(), $ignore_category_type_id_list=array(), $ignore_category_id_list=array()){
		if(
			empty($category_type_id) &&
			empty($category_id_list) &&
			empty($ignore_category_type_id_list) &&
			empty($ignore_category_id_list)
		){
			return '';
		}

		// 取得対象カテゴリまたは除外カテゴリが存在すれば filters を使用するので初期化
		$filters = '';

		// 取得条件の大カテゴリidおよび中・小カテゴリidをor区切り（,）でつなげる
		// and:「;」、 or:「,」
		if(!empty($category_type_id)){
			$filters .= 'ga:dimension2=='.$category_type_id;
		}elseif(!empty($category_id_list)){
			foreach($category_id_list as $key=>$category_id){
				$category_id_list[$key] = 'ga:dimension3=='.$category_id;
			}

			if(!empty($filters)){
				$filters .= ',';
			}
			$filters .= implode(',', $category_id_list);
		}

		// 除外条件の大カテゴリidおよび中・小カテゴリidをand区切り（;）でつなげる
		// and:「;」、 or:「,」
		if(!empty($ignore_category_type_id_list)){
			foreach($ignore_category_type_id_list as $key=>$category_type_id){
				$ignore_category_type_id_list[$key] = 'ga:dimension2!='.$category_type_id;
			}
			if(!empty($filters)){
				$filters .= ';';
			}
			$filters .= implode(';', $ignore_category_type_id_list);
		}

		if(!empty($ignore_category_id_list)){
			foreach($ignore_category_id_list as $key=>$category_id){
				$ignore_category_id_list[$key] = 'ga:dimension3!='.$category_id;
			}

			if(!empty($filters)){
				$filters .= ';';
			}
			$filters .= implode(';', $ignore_category_id_list);
		}

		return $filters;
	}
}

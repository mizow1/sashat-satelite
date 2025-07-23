<?php
require_once(LIB_DIR.'hybridauth/Hybrid/Auth.php');
require_once(LIB_DIR.'local/kyodo/Api.php');
require_once(LIB_DIR.'local/kyodo/Master.php');

class KyodoAuth{
	const PROVIDER_OPEN_ID = 'OpenID';
	// 開発環境でのダミーログイン用session名
	const SESSION_NAME_DEVELOPMENT = 'development';

	private $hybrid_auth;
	private $params;
	private $top_url;
	private $login_url;

	public function __construct($device_type = TEMPLATE_DEVICE_TYPE_PC){
		if ($device_type === TEMPLATE_DEVICE_TYPE_PC) {
			$this->top_url = 'https://'.SITE_TOP_DOMAIN.'/';
			$this->login_url = 'https://'.SITE_TOP_DOMAIN.'/service/login/';
		} elseif ($device_type === TEMPLATE_DEVICE_TYPE_SP) {
			$this->top_url = 'https://'.SITE_TOP_DOMAIN.'/sp/';
			$this->login_url = 'https://'.SITE_TOP_DOMAIN.'/sp/service/login/';
		}
		if (!AUTH_ENABLE) {
			return;
		}

		if ($device_type === TEMPLATE_DEVICE_TYPE_PC) {
			$base_url = 'https://'.SITE_TOP_DOMAIN.'/service/auth/';
		} elseif ($device_type === TEMPLATE_DEVICE_TYPE_SP) {
			$base_url = 'https://'.SITE_TOP_DOMAIN.'/sp/service/auth/';
		}
		$config = array(
			'base_url' => $base_url,
			'providers' => array (
				self::PROVIDER_OPEN_ID => array (
					'enabled' => true,
				),
			),
		);
		if (AUTH_LOGGING) {
			$config = array_merge($config, array(
				'debug_mode' => true,
				'debug_file' => LOG_DIR.'hybridauth.log',
			));
		}
		$this->hybrid_auth = new Hybrid_Auth($config);

		// 認証サーバーが
		$this->params = array(
			'openid_identifier' => 'https://'.SITE_AUTH_DOMAIN.'/', // https://'.SITE_AUTH_DOMAIN.'/auth/かも
		);
	}

	// 認証が必要かどうか
	public function isRequired(){
		// botの場合は認証しない
		if (
			empty($_SERVER['HTTP_USER_AGENT']) ||
			strpos($_SERVER['HTTP_USER_AGENT'], 'Googlebot') !== false ||
			strpos($_SERVER['HTTP_USER_AGENT'], 'Mediapartners-Google') !== false ||
			strpos($_SERVER['HTTP_USER_AGENT'], 'Yahoo! Slurp') !== false ||
			strpos($_SERVER['HTTP_USER_AGENT'], 'msnbot') !== false
		) {
			return false;
		}

		return true;
	}

	public function isLoggedIn(){
		if (!AUTH_ENABLE) {
			if (!isset($_SESSION[self::SESSION_NAME_DEVELOPMENT][SESSION_LOGIN_FLAG_NAME])) {
				return false;
			}
			return $_SESSION[self::SESSION_NAME_DEVELOPMENT][SESSION_LOGIN_FLAG_NAME];
		}

		return $this->hybrid_auth->isConnectedWith(self::PROVIDER_OPEN_ID);
	}

	public function getLoginInfo(){
		if (!AUTH_ENABLE) {
			// return array(
			// 	'uid' => '0000000042',
			// 	'tkn' => '31ed1f42e49b6966785e8c15eab028f9877348ee',
			// );
			// ///////////////////////////////////
			// ishida+test101@outward.jp	password	アウトワードテスト１０１	1970/1/1	Wプラン	クレジット	クレジット
			// return array(
			// 	'uid' => '0000000543',
			// 	'tkn' => 'b87d668c9efb0e4cbc112793403397ba82a69aa8',
			// );
			// ///////////////////////////////////
			// ishida+test109@outward.jp	password	アウトワードテスト１０９	1970/1/1	新聞紙面	集金
			// return array(
			// 	'uid' => '0000000553',
			// 	'tkn' => '896024a9fb1a73f98b787f4dd71199b6f25ebd2a',
			// );
			// ///////////////////////////////////
			// ishida+test107@outward.jp	password	アウトワードテスト１０７	1970/1/1	新聞紙面	クレジット
			// return array(
			// 	'uid' => '0000000551',
			// 	'tkn' => 'bc0ad26b5ab5761c8095c33c184726d3c5646d1b',
			// );
			// ///////////////////////////////////
			// ishida+test116@outward.jp	password	アウトワードテスト１１６	1970/1/1	新聞紙面	クレジット
			// return array(
			// 	'uid' => '0000000560',
			// 	'tkn' => '828093a12352df7365921d24b54f29f3a3269f65',
			// );
			return array(
				'uid' => '0000000542',//ueno@outward.jp password
				'tkn' => '15a2a1b3fc4fbc70ccce1d4491c6fb478caf1afa',
			);
		}

		$login_info = $this->hybrid_auth->getAdapter(self::PROVIDER_OPEN_ID)->getLoginInfo();
		return array(
			'uid' => $login_info['openid_ext1_uid'],
			'tkn' => $login_info['openid_ext1_tkn'],
		);
	}

	public function getUserData(){
		if (!$this->isLoggedIn()) {
			return array();
		}
/*
		if (!AUTH_ENABLE) {
			return array(
				'id' => 'develop',								// ID
				'name' => 'アウトワード',						// 氏名
				'type' => '1',									// 会員種別(1:親会員 2:家族会員 9:法人会員)
			);
		}
*/
		try {
			$login_info = $this->getLoginInfo();
			$post_data = array(
				'uid' => $login_info['uid'],
				'tkn' => $login_info['tkn'],
			);
			$api = new KyodoApi();

			// アクセストークン認証
			$response = $api->autAccessToken($post_data);
			if (!$response->isSuccess()) {
				$this->logout();
				return array();
			}
			$member = $response->getData();
			if (empty($member['result']['uid'])) {
				$this->logout();
				return array();
			}

			// 月額課金契約一覧照会
			$arr_contract_list = $this->getListContract($login_info, $api);

			return array(
				'id' => $member['result']['uid'],	// ID
				'name' => $member['result']['unm'],	// 氏名
				'token' => $login_info['tkn'],	// アクセストークン
				'nickname' => $member['result']['nnm'],	// ニックネーム
				'is_sandigi_member' => KyodoMaster::isSandigiMember($arr_contract_list),
				'is_news_paper_cre_member' => KyodoMaster::isNewsPaperCreMember($arr_contract_list),
				'is_w_plan' => KyodoMaster::isWPlan($arr_contract_list),
				'is_single_plan' => KyodoMaster::isSinglePlan($arr_contract_list),
				'is_corp_plan' => KyodoMaster::isCorpPlan($arr_contract_list),
				'is_post_plan' => KyodoMaster::isPostPlan($arr_contract_list),
				'is_campaign_a_plan' => KyodoMaster::isCampaignAPlan($arr_contract_list),
				'is_campaign_b_plan' => KyodoMaster::isCampaignBPlan($arr_contract_list),
				'is_news_paper_cre_plan' => KyodoMaster::isNewsPaperCrePlan($arr_contract_list),
			);
		} catch(Exception $e) {
			// todo: エラー処理
			return array();
		}
	}



	// 配列の階層を深くする
	private function changeArrayLevel(&$arr_data){
		if(!empty($arr_data['sid'])){
			$arr_tmp = $arr_data;
			$arr_data = array();
			$arr_data[0] = $arr_tmp;
		}
	}

	public function login(){
		if (isset($_GET['ref'])) {
			// 共同紙面ビューアサーバー(別ドメイン)からリダイレクトされた場合のIE対応
			$ref = $_GET['ref'];
		} elseif (isset($_SERVER['HTTP_REFERER'])) {
			$ref = $_SERVER['HTTP_REFERER'];
		} else {
			$ref = $this->top_url;
		}

		if (!AUTH_ENABLE) {
			// service以下のsession(secure属性あり)にログイン情報を書き込み
			$_SESSION[self::SESSION_NAME_DEVELOPMENT][SESSION_LOGIN_FLAG_NAME] = true;
			$this->changeSession();
			header('Location: '.$ref);
			return;
		}

		$this->params = array_merge($this->params, array(
			// 認証後のリダイレクト先を設定
			'hauth_return_to' => $ref,
		));

		try{
			$open_id = $this->hybrid_auth->authenticate(self::PROVIDER_OPEN_ID, $this->params);
			// これ以降は既にログインしていた場合しか通らない(Hybrid_Auth::authenticateはログイン処理でリダイレクトする為)

			// http側がログインしていない場合(cookieが削除されている場合など)はhttps側もログアウトして再度ログインする
			$this->changeSession();
			$is_logged_in = $this->isLoggedIn();
			$this->changeSession();
			if (!$is_logged_in) {
				$this->logout();
				header('Location: '.$this->login_url.'?ref='.$ref);
				return;
			}

			// 認証サーバーにログイン情報がない場合に作成する為に認証APIを叩いておく
			$login_info = $this->getLoginInfo();
			$post_data = array(
				'uid' => $login_info['uid'],
				'tkn' => $login_info['tkn'],
			);
			$api = new KyodoApi();
			$response = $api->autAccessToken($post_data);
			// 手動でリダイレクト
			header('Location: '.$ref);
			return;
		}catch(Exception $e){
			// todo: エラーページ表示
			return;
		}
	}

/*
	public function kyodo_logout(){
		$str_return_to = $this->boo_passport_flag ? 'https://'.$this->str_top_domain.'/logout?return=front' :
								'https://'.$this->str_top_domain.'/service/logout?return=front';
		header('location: '.$this->params['openid_identifier'].'logout?return_to='.$str_return_to);
		return ;
	}
*/

	public function logout(){
		$ref = isset($_GET['ref']) ? $_GET['ref'] : $this->top_url;
		if (!AUTH_ENABLE) {
			// 通常のsession(secure属性なし)にログイン情報を書き込み
			$_SESSION[self::SESSION_NAME_DEVELOPMENT][SESSION_LOGIN_FLAG_NAME] = false;
			$this->changeSession();
//			$this->sessionEnd();
		}
		if (!$this->isLoggedIn()) {
			$this->sessionEnd();
			header('Location: '.$ref);
			return;
		}

		try {
			$login_info = $this->getLoginInfo();
			$post_data = array(
				'uid' => $login_info['uid'],
				'tkn' => $login_info['tkn'],
			);
			$api = new KyodoApi();
			$response = $api->endAccess($post_data);
			$this->sessionEnd();
			header('Location: '.$ref);
		} catch(Exception $e) {
			// todo: エラー処理
			$this->sessionEnd();
			header('Location: '.$ref);
		}
	}

	public function immedate(){

		if (isset($_GET['ref'])) {
			// 共同紙面ビューアサーバー(別ドメイン)からリダイレクトされた場合のIE対応
			$ref = $_GET['ref'];
		} elseif (isset($_SERVER['HTTP_REFERER'])) {
			$ref = $_SERVER['HTTP_REFERER'];
		} else {
			$ref = $this->top_url;
		}

		if (!AUTH_ENABLE) {
			// service以下のsession(secure属性あり)にログイン情報を書き込み
			$_SESSION[self::SESSION_NAME_DEVELOPMENT][SESSION_LOGIN_FLAG_NAME] = true;
//			$this->changeSession();
			// 通常のsession(secure属性なし)にログイン情報を書き込み
//			$_SESSION[self::SESSION_NAME_DEVELOPMENT][SESSION_LOGIN_FLAG_NAME] = true;
//			$this->changeSession();
			header('Location: '.$ref);
			return;
		}

		$this->params = array_merge($this->params, array(
			// 認証後のリダイレクト先を設定
			'hauth_return_to' => $ref,
			'open_id_mode' => 'immedate',
			'service_site' => !empty($_SESSION['service_site']) ? $_SESSION['service_site'] : '/',
		));

		try{

			$open_id = $this->hybrid_auth->authenticate(self::PROVIDER_OPEN_ID, $this->params);

			// これ以降は既にログインしていた場合しか通らない(Hybrid_Auth::authenticateはログイン処理でリダイレクトする為)

			// http側がログインしていない場合(cookieが削除されている場合など)はhttps側もログアウトして再度ログインする
			$this->changeSession();
			$is_logged_in = $this->isLoggedIn();
			$this->changeSession();
			if (!$is_logged_in) {
				$this->logout();
				header('Location: '.$this->login_url.'?ref='.$ref);
				return;
			}

			// 認証サーバーにログイン情報がない場合に作成する為に認証APIを叩いておく
			$login_info = $this->getLoginInfo();
			$post_data = array(
				'uid' => $login_info['uid'],
				'tkn' => $login_info['tkn'],
			);
			$api = new KyodoApi();
			$response = $api->autAccessToken($post_data);
			// 手動でリダイレクト
			header('Location: '.$ref);
			return;
		}catch(Exception $e){
			// todo: エラーページ表示
			return;
		}
	}

	private function sessionEnd(){
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 86400,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
		session_destroy();
		session_write_close();
		$this->changeSession();
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 86400,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
		session_destroy();
		session_write_close();
	}

	// 通常のsessionとservice以下のsessionを切り替える
	public function changeSession($new_id = ''){
		// PC(http)→PC(https)
		if (session_name() == '_panda_sess') {
			require_once(MODEL.'front/session/FrontSecureSessionClass.php');
			new FrontSecureSession();
			$new_session_name = '_panda_secure_sess';

		// PC(https)→PC(http)
		} elseif (session_name() == '_panda_secure_sess') {
			require_once(MODEL.'front/session/FrontSessionClass.php');
			new FrontSession();
			$new_session_name = '_panda_sess';

/*
		// SP(http)→SP(https)
		} elseif (session_name() == '_panda_sp_sess') {
			require_once(MODEL.'front/session/FrontSpSecureSessionClass.php');
			new FrontSpSecureSession();
			$new_session_name = '_panda_sp_secure_sess';

		// SP(https)→SP(http)
		} elseif (session_name() == '_panda_sp_secure_sess') {
			require_once(MODEL.'front/session/FrontSpSessionClass.php');
			new FrontSpSession();
			$new_session_name = '_panda_sp_sess';

		}
*/
		}
		if (!empty($new_id)){
			// session_idの指定がある場合はそれを使う
			session_id($new_id);
		} elseif (!empty($_COOKIE[$new_session_name])) {
			// 切り替え後のcookieが既に存在する場合はそれを使う
			session_id($_COOKIE[$new_session_name]);
		}
		session_start();
	}

	// 月額課金契約一覧照会
	private function getListContract($arr_login_info, $objApi){
		if(
			empty($arr_login_info['uid']) ||
			empty($arr_login_info['tkn'])
		){
			return array();
		}
		$arr_post_data = array(
			'uid' => $arr_login_info['uid'],
			'tkn' => $arr_login_info['tkn'],
			'smd' => 2, // 1:全ての月額課金契約（退会含む）　2:入会中の月額課金契約
		);
		// 月額課金契約一覧照会
		$objResponse = $objApi->getListContract($arr_post_data);
		$arr_response_data = $objResponse->getData();
		if(empty($arr_response_data)){
			// システムエラー
			ErrorLog::write('Error: Array response_data empty');
			return array();
		}

		if ($objResponse->isSuccess()) {
			$this->changeArrayLevel($arr_response_data['result']);
			return $arr_response_data['result'];
		}

		// 返却値24で月額課金契約情報なし　正常
		$num_code = $objResponse->getCode();
		if ($num_code == 24) {
			return array();
		}

		ErrorLog::write('Error: kyodo_api get_list_contract response_code:'.$num_code);
		return array();
	}
}

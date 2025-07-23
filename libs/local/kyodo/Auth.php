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
	private $top_domain;
	private $top_url;
	private $login_url;

	public function __construct($device_type = TEMPLATE_DEVICE_TYPE_PC,$idsite_flag = false){
		$_SESSION['auth_device'] = $device_type;
		$this->idsite_flag = $idsite_flag;
		$this->top_domain = $idsite_flag ? IDSITE_TOP_DOMAIN : SITE_TOP_DOMAIN ;
		$this->top_url = 'https://'.$this->top_domain.'/';
		$this->login_url = $idsite_flag ? 'https://'.$this->top_domain.'/login/' :
										'https://'.$this->top_domain.'/service/login/' ;

		if (!AUTH_ENABLE) {
			return;
		}
		$base_url = $idsite_flag ? 'https://'.$this->top_domain.'/auth/' :
									'https://'.$this->top_domain.'/service/auth/';

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

		switch ($_SERVER[SERVER_ENV_NAME]) {
			case SERVER_ENV_STAGE:
				$this->params = array(
					'openid_identifier' => 'https://'.SITE_AUTH_DOMAIN.'/auth/',
				);
				break;
			default :
				$this->params = array(
					'openid_identifier' => 'https://'.SITE_AUTH_DOMAIN.'/auth/',
				);
				break;
		}
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

		// 認証機能無効化
		$auth_invalid_flag = Settings::getById('auth_invalid_flag');

		// 認証機能無効化が有効になっている、かつ セッション上はログイン中の場合、認証サーバーに問い合わせに行かない
		if(!empty($auth_invalid_flag)){
			return !empty($_SESSION[SESSION_LOGIN_FLAG_NAME]);
		}

		return $this->hybrid_auth->isConnectedWith(self::PROVIDER_OPEN_ID);
	}

	public function getLoginInfo(){
		if (!AUTH_ENABLE) {
			return array(
				'uid' => '1100000115',//matsuo-k+001@outward.jp password
				'tkn' => '6301989971a8098805f48af01bfe8d230c25fa23',
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
				$_GET['ref'] = $_SERVER['REQUEST_URI'];
				$this->logout();
				return array();
			}

			$data = $response->getData();
			if (empty($data['result']['uid'])) {
				$this->logout();
				return array();
			}

			$response = $api->getMember($post_data);
			if (!$response->isSuccess()) {
				$this->logout();
				return array();
			}

			$member = $response->getData();
			if (empty($member['result']['uid'])) {
				$this->logout();
				return array();
			}
			$arr_data = $member['result'];

			$post_data['smd'] = 2;
			$response = $api->getListContract($post_data);
			if ($response->isSuccess()) {
				$arr_data['contracts'] = $response->getData()['result'];
			}
			$response_year = $api->getListContractYear($post_data);
			if ($response_year->isSuccess()) {
				$arr_data['contracts_year'] = $response_year->getData()['result'];
			}

			//法人会員の場合は法人情報も取得する
			if(SKM::isCorpMember($arr_data['ukb'])){
				$post_data['hid'] = $arr_data['hid'];
				$response = $api->getCorp($post_data);
				if($response->isSuccess()){
					$arr_data['corp'] = $response->getData()['result'];
				}
			}
			return $this->formatMemberData($arr_data);

		} catch(Exception $e) {
			// todo: エラー処理
			return array();
		}
	}

	private function formatMemberData($arr_member){

		$return_data = array(
			'id'		 => $arr_member['uid'],	// ID
			'mail_address'	 => $arr_member['mla'],	// メールアドレス
			'nickname'	 => empty($arr_member['nnm']) ? $arr_member['uns'].$arr_member['unn'] : $arr_member['nnm'],	//ニックネーム
			'name_sei'	 => $arr_member['uns'],	// かな（性）
			'name_mei'	 => $arr_member['unn'],	// かな（めい）
			'kana_sei'	 => $arr_member['uks'],	// かな（性）
			'kana_mei'	 => $arr_member['ukn'],	// かな（めい）
			'ukb'		 => $arr_member['ukb'],	// 会員区分
			'ust'		 => $arr_member['ust'],	// 会員区分
			'is_trial'	 => !empty($arr_member['pr1']),//お試し会員判定
			'trial_start_date'=>!empty($arr_member['ojd']) ? substr($arr_member['ojd'],0,4).'-'.substr($arr_member['ojd'],4,2).'-'.substr($arr_member['ojd'],6,2) : '',
			'trial_end_date' => !empty($arr_member['odd']) ? substr($arr_member['odd'],0,4).'-'.substr($arr_member['odd'],4,2).'-'.substr($arr_member['odd'],6,2) : '',
			'is_temporary'	 => empty($arr_member['pr1']) ? SKM::isTemporaryMember($arr_member['ukb']) : false,
			'is_denshi'	 => empty($arr_member['pr1']) ? SKM::isDenshiMember($arr_member['ukb']) : false,
			'is_cource'	 => empty($arr_member['pr1']) ? SKM::isCourceMember($arr_member['ukb']) : false,
			'is_campaign'	 => empty($arr_member['pr1']) ? SKM::isCampaignMember($arr_member['ukb']) : false,
			'is_corp'	 => empty($arr_member['pr1']) ? SKM::isCorpMember($arr_member['ukb']) : false,
			'is_passport'	 => empty($arr_member['pr1']) ? SKM::isPassportMember($arr_member['ukb']) : false,
			'is_parent'	 => empty($arr_member['pr1']) ? SKM::isParentMember($arr_member['ust']) : false,
			'is_child'	 => empty($arr_member['pr1']) ? SKM::isChildMember($arr_member['ust']) : false,
			'is_post'	 => $arr_member['mcd'] == '8000' ? true : false ,
			'corp_id'	 => !empty($arr_member['hid']) ? $arr_member['hid'] : '',//法人ID
			'is_corp_master' => (!empty($arr_member['hid']) && ( $arr_member['mkb'] == '1')) ,//マスターID区分
			'is_corp_multi' => false ,//複数アカウント契約判定
			'is_checked_subscribe' => !empty($arr_member['rdi']), //現読確認済み判定
		);

		// ビューアーコース（学割プラン）判定
		$return_data['is_denshi_student'] = false;
		if(
			!empty($arr_member['contracts']['cid']) && $return_data['is_cource'] != false
		){
			if($arr_member['contracts']['cid'] == KyodoMaster::COMMODITY_KEY_STUDENT){
				$return_data['is_cource'] = false;
				$return_data['is_denshi_student'] = true;
			}
		}

		//海外判定
		$return_data['is_oversea'] = false;
		//海外在住パスポート会員
		if(empty($arr_member['contracts']) && ( $arr_member['ps1'] == '999' && $arr_member['ps2'] == '9999')){
			$return_data['is_oversea'] = true;
		}elseif(!empty($arr_member['contracts']['cid'])){
			$return_data['is_oversea'] = $arr_member['contracts']['cid'] == KyodoMaster::COMMODITY_KEY_TANDOKU_OVERSEAS;
		}else{
			foreach($arr_member['contracts'] as $contract){
				if($contract['cid'] == KyodoMaster::COMMODITY_KEY_TANDOKU_OVERSEAS){
					$return_data['is_oversea'] = true;
					break;
				}
			}
		}

		// 各種契約プラン判定
		$return_data['is_digital'] = false;
		$return_data['is_dsports'] = false;
		$return_data['is_oversea'] = false;
		$digital_cid = array(
			KyodoMaster::COMMODITY_KEY_DIGITAL,
			KyodoMaster::COMMODITY_KEY_DIGITAL_YEAR,
			KyodoMaster::COMMODITY_KEY_DIGITAL_OVERSEAS,
			KyodoMaster::COMMODITY_KEY_DIGITAL_YEAR_OVERSEAS,
			KyodoMaster::COMMODITY_KEY_DIGITAL_DISCOUNT,
			KyodoMaster::COMMODITY_KEY_DIGITAL_TRIAL,
		);
		$oversea_cid = array(
			KyodoMaster::COMMODITY_KEY_TANDOKU_OVERSEAS,
			KyodoMaster::COMMODITY_KEY_TANDOKU_YEAR_OVERSEAS,
			KyodoMaster::COMMODITY_KEY_DIGITAL_OVERSEAS,
			KyodoMaster::COMMODITY_KEY_DIGITAL_YEAR_OVERSEAS,
		);

		if(empty($arr_member['contracts'])){
			if( $arr_member['ps1'] == '999' && $arr_member['ps2'] == '9999'){
				$return_data['is_oversea'] = true;
			}
			$return_data['is_dsports'] = false;
			$return_data['is_digital'] = false;
		}elseif(!empty($arr_member['contracts']['sid'])){
			$return_data['is_dsports'] = $arr_member['contracts']['sid'] == KyodoMaster::SERVICE_KEY_SPORTS_DIGITAL;
			$return_data['is_digital'] = in_array($arr_member['contracts']['cid'],$digital_cid);
			$return_data['is_oversea'] = in_array($arr_member['contracts']['cid'],$oversea_cid);;

		}else{
			foreach($arr_member['contracts'] as $contract){
				if($contract['sid'] == KyodoMaster::SERVICE_KEY_SPORTS_DIGITAL){
					$return_data['is_dsports'] = true;
					break;
				}
				if(in_array($contract['cid'],$digital_cid)){
					$return_data['is_digital'] = true;
					break;
				}
				if(in_array($contract['cid'],$oversea_cid)){
					$return_data['is_oversea'] = true;
					break;
				}
			}
		}

		if(!empty($arr_member['contracts_year']) || $return_data['is_dsports']){
			if(!empty($arr_member['contracts_year']['sid'])){
				$return_data['is_dsports'] = $arr_member['contracts_year']['sid'] == KyodoMaster::SERVICE_KEY_SPORTS_DIGITAL_YEAR;
				$return_data['is_digital'] = in_array($arr_member['contracts_year']['cid'],$digital_cid);
			}else{
				foreach($arr_member['contracts_year'] as $contract){
					if(
						$contract['sid'] == KyodoMaster::SERVICE_KEY_SPORTS_DIGITAL_YEAR
					){
						$return_data['is_dsports'] = true;
						break;
					}
					if(in_array($contract['cid'],$digital_cid)){
						$return_data['is_digital'] = true;
						break;
					}
				}
			}
		}

		if(!$return_data['is_corp']){
			$return_data['plan_type'] = $this->getPlanType($return_data);
			return $return_data;
		}

		//法人会員で複数アカウント契約している場合はtrue
		$return_data['is_corp_multi'] = $arr_member['corp']['cap'] !== '1' ;

		//法人紙面コース判定
		$return_data['is_corp_denshi'] = false;
		$return_data['is_corp_cource'] = false;
		$return_data['is_corp_digital'] = false;
		if(!empty($arr_member['contracts']['cid'])){
			$return_data['is_corp_denshi'] = $arr_member['contracts']['cid'] == KyodoMaster::COMMODITY_KEY;
			$return_data['is_corp_cource'] = in_array($arr_member['contracts']['cid'],[KyodoMaster::COMMODITY_KEY_TANDOKU,KyodoMaster::COMMODITY_KEY_TANDOKU_OVERSEAS]);
			$return_data['is_corp_digital'] = in_array($arr_member['contracts']['cid'],[KyodoMaster::COMMODITY_KEY_DIGITAL,KyodoMaster::COMMODITY_KEY_DIGITAL_OVERSEAS]);
		}else{
			foreach($arr_member['contracts'] as $contract){
				if($contract['cid'] == KyodoMaster::COMMODITY_KEY){
					$return_data['is_corp_denshi'] = true;
					break;
				}
				if(in_array($contract['cid'],[KyodoMaster::COMMODITY_KEY_TANDOKU,KyodoMaster::COMMODITY_KEY_TANDOKU_OVERSEAS])){
					$return_data['is_corp_cource'] = true;
					break;
				}
				if(in_array($contract['cid'],[KyodoMaster::COMMODITY_KEY_DIGITAL,KyodoMaster::COMMODITY_KEY_DIGITAL_OVERSEAS])){
					$return_data['is_corp_digital'] = true;
					break;
				}
			}
		}

		$return_data['is_corp_temporary'] = false ;
		if($return_data['is_corp_denshi']){
			$return_data['is_corp_temporary'] = empty($arr_member['corp']['jkb']);
		}

		//法人　どうしんDB会員判定
		$return_data['is_corp_db'] = false;
		if(!empty($arr_member['contracts']['sid'])){
			$return_data['is_corp_db'] = $arr_member['contracts']['sid'] == KyodoMaster::SERVICE_KEY_DB;
		}else{
			foreach($arr_member['contracts'] as $contract){
				if($contract['sid'] == KyodoMaster::SERVICE_KEY_DB){
					$return_data['is_corp_db'] = true;
					break;
				}
			}
		}

		//法人　デジタルプラン判定
		$return_data['plan_type'] = $this->getPlanType($return_data,true);
		return $return_data;
	}


	// 配列の階層を深くする
	private function changeArrayLevel(&$arr_data){
		if(!empty($arr_data['sid'])){
			$arr_tmp = $arr_data;
			$arr_data = array();
			$arr_data[0] = $arr_tmp;
		}
	}

	private function getPlanType($data,$corp_flag = false){
		if($corp_flag){
			switch(true){
				case $data['is_corp_denshi'] || $data['is_corp_temporary'] :
					return PLAN_TYPE_PAPER;
					break;
				case $data['is_corp_digital'] :
					return PLAN_TYPE_DIGITAL;
					break;
				case $data['is_corp_cource'] :
					return PLAN_TYPE_COURCE;
					break;
				default:
					return PLAN_TYPE_FREE;
					break;
			}
		}
		switch(true){
			case $data['is_denshi'] || $data['is_trial'] || $data['is_denshi_student'] || $data['is_temporary'] || $data['is_campaign'] :
				return PLAN_TYPE_PAPER;
				break;
			case $data['is_digital'] :
				return PLAN_TYPE_DIGITAL;
				break;
			case $data['is_cource'] :
				return PLAN_TYPE_COURCE;
				break;
			default:
				return PLAN_TYPE_FREE;
				break;
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
		// 認証機能無効化
		$auth_invalid_flag = Settings::getById('auth_invalid_flag');

		// 認証情報無効化時はAPIに問い合わせに行かせない
		if(!empty($auth_invalid_flag)){
			$_SESSION[SESSION_LOGIN_FLAG_NAME] = false;
			header('Location: '.$_SERVER['REQUEST_URI']);
			exit;
		}

		$ref = isset($_GET['ref']) ? $_GET['ref'] : $this->top_url.'service/page/logout';
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
				'adi' => '1', // 認証を識別する区分 1:アクセストークン認証　2:端末固有番号認証
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

	// 会員情報取得（共同APIマルチ接続：実行時間短縮対策の為）
	// 会員情報照会        getMemberAll
	// 会員情報照会info    getMember
	// 会員状態取得status  getMemberStatus
	// プレミアム会員登録可否確認  getPremiumRegStatus
	private function getMemberAllStatus($arr_login_info, $objApi, $member){

		if(
			empty($arr_login_info['uid']) ||
			empty($arr_login_info['tkn'])
		){
			return false;
		}

		// 共通POSTデータ
		$arr_post_data = array(
			'uid' => $arr_login_info['uid'],	// 会員を識別するＩＤ
			'tkn' => $arr_login_info['tkn'],	//  会員のアクセス認証を行う際に使用するトークン
			'adi' => '1',						// 認証を識別する区分 1:アクセストークン認証　2:端末固有番号認証
		);

		$response = $objApi->getMemberAllStatus($arr_post_data);

		// タイムアウトした場合、ログアウト
		if(empty($response)){
			header('Location: /service/logout');
			return;
		}

		// 共同API実行結果判定
		foreach ($response as $resource => $value) {
			if($resource == 'get_premium_reg_status'){
				if ($response[$resource]->isSuccess()) {
					$premiere_trial_flag = false;
				} else {
					// プレミアム会員登録不可（お試し期間中）だった場合
					if($response[$resource]->getCode() == API_ERROR_63){
						$premiere_trial_flag = true;
					}
					$premiere_trial_flag = false;
				}
				continue;
			}
			if (!$response[$resource]->isSuccess()) {
				header('Location: /service/logout');
				return;
			}
		}

		require_once(MODEL.'front/service/MyInfoModelClass.php');
		$objMyInfo = new MyInfoModel();

		$get_member_info = $response['get_member_info']->getData()['member'];

		$get_member_info['uid'] = $arr_login_info['uid'];
		$arr_member = $objMyInfo->toDisplayFormat($get_member_info);

		$get_member = $response['get_member']->getData()['member'];
		$member_all = $objMyInfo->getMailStatus($get_member);

		$status = $response['get_member_status']->getData()['result'];

		// 受信メールアドレスの状態
		$status['login_mail_stop_flag'] = $member_all['login_mail_stop_flag'];
		$status['login_mail'] = $member_all['login_mail'];
		$status['service_mail_stop_flag'] = $member_all['service_mail_stop_flag'];
		$status['sub_mail'] = $member_all['sub_mail'];
		$status['service_mail_name'] = $member_all['service_mail_name'];
		$status['service_mail_code'] = $member_all['service_mail_code'];

		$arr_member['nickname'] = $member['nnm'];
		$arr_member['service_division'] = $member['sdi'];

		// プレミアム会員登録可否確認「一般会員（プレミアムお試し）の判定」
		$status['premiere_trial_flag'] = $premiere_trial_flag;
		$status['temporary_flag'] = false;
		$status['general_flag'] = false;
		$status['premiere_flag'] = false;
		$status['trial_flag'] = false;

		switch ($member['udi']) {
			case '0':
				$status['temporary_flag'] = true;
				break;
			case '1':
				$status['general_flag'] = true;
				break;
			case '2':
				$status['premiere_flag'] = true;
				break;
			case '8':
				$status['general_flag'] = true;
				$status['trial_flag'] = true;
				break;

			default:
				break;
		}
		$arr_member['status'] = $status;
		return $arr_member;
	}
}

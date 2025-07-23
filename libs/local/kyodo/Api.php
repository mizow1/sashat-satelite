<?php
require_once(LIB_DIR.'local/kyodo/Response.php');
require_once(LIB_DIR.'local/kyodo/Socket.php');

class KyodoApi{
	// テナント情報
	const TENANT_ID = '10101';
	const TENANT_PASSWORD = '2SsU6SbUyK9IrNxO';
	const SC_AUTH_KEY = '9GeMaFQY3dJZTnM';
	private $post_data_base = array(
		'tid' => self::TENANT_ID,
		'tps' => self::TENANT_PASSWORD,
	);
//	const API_BASE_URL_STAGE	 = 'https://aws-kakin-api-hokkaido.kyodonewslab.jp/BS_API/';
	const API_BASE_URL_STAGE	 = 'https://stg-aws-kakin-api-hokkaido.kyodonewslab.jp/BS_API/';
	const API_BASE_URL_STAGE_OLD	 = 'https://stg-aws-kakin-api-hokkaido.kyodonewslab.jp/BS_API_OLD/';
	const API_BASE_URL_PRODUCTION	 = 'https://hokkaido.newsaccount.jp/BS_API/';
	const GET_LIST_POST		 = 'get_list_post';		// 郵便番号マスター一覧照会
	const REG_USER_ACCEPT_RESOURCE	 = 'reg_user_accept';		// メールアドレス受付登録
	const GET_USER_ACCEPT_RESOURCE	 = 'get_user_accept';		// メールアドレス受付照会
	const REG_MEMBER_RESOURCE	 = 'reg_member';		// 会員情報登録
	const REG_MEMBER_ALL_RESOURCE	 = 'reg_member_all';		// 新規申込登録
	const REG_MEMBER_ALL_TRIAL_RESOURCE = 'reg_member_all_trial';	// 新規申込登録(お試し)
	const REG_MEMBER_ALL_CORP_RESOURCE = 'reg_member_all_corp';	// 新規申込登録(法人)
	const REG_MEMBER_ACCOUNT_RESOURCE = 'reg_member_account';	// 新規申込登録(都度課金同時)
	const REG_MEMBER_CORP_ACCOUNT_RESOURCE = 'reg_member_corp_account';	// 新規申込登録(法人：都度課金同時)
	const AUT_ACCESS_TOKEN_RESOURCE	 = 'aut_access_token';		// アクセストークン認証
	const END_ACCESS_RESOURCE	 = 'end_access';		// アクセストークン破棄
	const GET_MEMBER_RESOURCE	 = 'get_member';		// 会員情報照会
	const UPD_MEMBER_RESOURCE	 = 'upd_member';		// 会員情報変更
	const AUT_MEMBER_RESOURCE	 = 'aut_member';		// 会員認証
	const GET_CORP			 = 'get_corp';			// 法人情報照会
	const UPD_CORP			 = 'upd_corp';			// 法人情報変更
	const UPD_ADDRESS_RESOURCE	 = 'upd_address';		// 会員住所変更
	const REG_MOVE_ADDRESS		 = 'reg_move_address';		// 引っ越し手続き情報登録
	const REG_MOVE_ADDRESS_CORP	 = 'reg_move_address_corp';	// 引っ越し手続き情報登録(法人)
	const GET_MOVE_ADDRESS		 = 'get_move_address';		// 引っ越し手続き情報取得
	const GET_MOVE_ADDRESS_CORP	 = 'get_move_address_corp';		// 引っ越し手続き情報取得(法人)
	const CAN_MOVE_ADDRESS		 = 'can_move_address';		// 引っ越し手続き情報取り消し
	const CAN_MOVE_ADDRESS_CORP	 = 'can_move_address_corp';		// 引っ越し手続き情報取り消し(法人)
	const GET_CONTRACT_RESOURCE	 = 'get_contract';		// 月額課金契約照会
	const LIST_CONTRACT_RESOURCE	 = 'get_list_contract';		// 月額課金契約一覧照会
	const REG_CONTRACT_DISJOIN_RES	 = 'reg_contract_disjoin_res';	//月額課金契約退会予定登録
	const GET_CONTRACT_DISJOIN_RES	 = 'get_contract_disjoin_res';	//月額課金契約退会予定照会
	const CAN_CONTRACT_DISJOIN_RES	 = 'can_contract_disjoin_res';	//月額課金契約退会予定取消
	const GET_CARD_INFO_RESOURCE	 = 'get_card_info';		// カード情報照会
	const CHK_MAILADDRESS_RESOURCE	 = 'chk_mailaddress';		// メールアドレス・パスワードチェック
	const REG_MEMBER_DISJOIN	 = 'reg_member_disjoin';	// 会員情報退会
	const REG_MEMBER_DISJOIN_RES_RESOURCE = 'reg_member_disjoin_res';// 会員情報退会予定登録
	const GET_MEMBER_DISJOIN_RES_RESOURCE = 'get_member_disjoin_res';// 会員情報退会予定照会
	const CAN_MEMBER_DISJOIN_RES_RESOURCE = 'can_member_disjoin_res';// 会員情報退会予定取消
	const REG_MAILADDRESS_ACCEPT_RESOURCE = 'reg_mailaddress_accept';// メールアドレス変更受付登録
	const CHG_MAILADDRESS_RESOURCE	 = 'chg_mailaddress';		// メールアドレス変更
	const REM_MAILADDRESS		 = 'rem_mailaddress';		//　追加メールアドレス解除
	const CHG_PASS_RESOURCE		 = 'chg_member_pass';		// 会員パスワード変更
	const REG_CARD_RESOURCE		 = 'reg_card';			// クレジット決済方法登録
	const REG_MAIL_MAGAZINE_RESOURCE = 'reg_mail_magazine';		// メールサービス情報登録
	const REG_PASS_FORGET_ACCEPT_RESOURCE = 'reg_pass_forget_accept';// パスワード忘れ受付登録
	const REG_PASS_FORGET_RESOURCE	 = 'reg_pass_forget';		// パスワード変更(忘れ)
	const GET_LIST_BILL_RESOURCE	 = 'get_list_bill';		// 課金情報一覧照会
	const GET_LIST_BILLDETAIL_RESOURCE = 'get_list_billdetail';	// 課金明細情報一覧照会
	const AUT_PERSON		 = 'aut_person';		// 本人確認
	const GET_LIST_SERVICE		 = 'get_list_service';		// サービスマスタ一覧照会
	const GET_SERVICE		 = 'get_service';		// サービスマスタ照会
	const GET_LIST_COMMODITY	 = 'get_list_commodity';	// 商品マスタ一覧照会
	const GET_COMMODITY		 = 'get_commodity';		// 商品マスタ照会
	const GET_LIST_CODE		 = 'get_list_code';		// コードマスタ一覧照会
	const GET_CODE			 = 'get_code';			// コードマスタ照会
	const REG_CONTRACT_JOIN		 = 'reg_contract_join';		// 有料サービス登録
	const REG_CONTRACT_JOIN_TRIAL	 = 'reg_contract_join_trial';	// サービス登録(おためし)
	const REG_CARD_CONTRACT		 = 'reg_card_contract';		// クレジット月額課金契約登録
	const CHG_CONTRACT		 = 'chg_contract';		// プラン変更
	const REG_CONTRACT_CHANGE	 = 'reg_contract_chg_res';	// プラン変更予定登録
	const GET_CONTRACT_CHANGE	 = 'get_contract_chg_res';	// プラン変更予定紹介
	const CAN_CONTRACT_CHANGE	 = 'can_contract_chg_res';	// プラン変更予定取消
	const REG_CONTRACT_DISJOIN	 = 'reg_contract_disjoin';	// 有料サービス停止
	const REG_MAIL_SERVICE		 ='reg_mail_service';		// メールサービス情報登録
	const GET_MAIL_SERVICE		 ='get_mail_service';		// メールサービス情報照会
	const REG_INQUIRY		 = 'reg_inquiry';		// お問い合わせ登録
	const REG_INQUIRY_NOMEMBER	 = 'reg_inquiry_nonmember';	// お問い合わせ登録(非会員)
	const GET_LIST_DEMAND		 = 'get_list_demand';		// 請求情報一覧照会
	const GET_ACCOUNT_DEMAND_LIST	 = 'get_account_demand_list';	// 請求情報一覧照会(都度課金)
	const GET_LIST_SERVICE_STATE_RESOURCE = 'get_list_service_state';// サービス利用状態一覧照会
	const REG_FREE_SERVICE_RESOURCE	 = 'reg_free_service';		// 無料サービス登録・停止
	const REG_READER_SERVICE_JOIN	 = 'reg_reader_service_join';	// 購読者サービス登録
	const REG_READER_SERVICE_DISJOIN = 'reg_reader_service_disjoin'; // 購読者サービス停止
	const REG_ACCOUNT		 = 'reg_account';		// 都度課金登録
	const GET_ACCOUNT		 = 'get_account';		// 都度課金照会
	const GET_LIST_ACCOUNT		 = 'get_list_account';		// 都度課金一覧照会
	const REG_PAYMENT_METHOD_CHANGE	 = 'reg_payment_method_chg_res';// 支払い方法変更予約
	const GET_PAYMENT_METHOD_CHANGE	 = 'get_payment_method_chg_res';// 支払い方法変更予約照会
	const CAN_PAYMENT_METHOD_CHANGE	 = 'can_payment_method_chg_res';// 支払い方法変更予約取消
	const REG_FAMILY_ACCEPT		 = 'reg_family_accept';		// 家族会員登録申請
	const GET_FAMILY_ACCEPT		 = 'get_family_accept';		// 家族会員登録申請照会
	const REG_FAMILY		 = 'reg_family_member';		// 家族会員登録
	const CHG_FAMILY		 = 'chg_family';		// 家族会員変更承認
	const GET_FAMILY_LIST		 = 'get_family_list';		// 家族会員情報一覧照会
	const GET_FAMILY_ACCEPT_LIST	 = 'get_family_accept_list';	// 家族会員登録申請状況一覧照会
	const DEL_FAMILY_ACCEPT		 = 'del_family_accept';		// 家族会員登録申請削除
	const DEL_FAMILY		 = 'del_family';		// 家族会員削除
	const REG_FAMILY_INDEPENDENT_RESERVE = 'reg_family_independent_reserve'; // 家族会員独立申請
	const REG_FAMILY_CHANGE_READER_RESERVE = 'reg_family_change_reader_reserve'; // 購読者会員変更申請
	const GET_FAMILY_MEMBER_DISJOIN	 = 'get_family_member_disjoin_res';//家族会員退会予定照会
	const REG_FAMILY_MEMBER_DISJOIN	 = 'reg_family_member_disjoin_res';//家族会員退会予定登録
	const CAN_FAMILY_MEMBER_DISJOIN	 = 'can_family_member_disjoin_res';//家族会員退会予定取消
	const REG_FAMILY_CONTRACT_DISJOIN= 'reg_family_contract_disjoin_res';//家族会員月額課金契約予定登録
	const GET_FAMILY_CONTRACT_DISJOIN= 'get_family_contract_disjoin_res';//家族会員月額課金契約予定照会
	const CAN_FAMILY_CONTRACT_DISJOIN= 'can_family_contract_disjoin_res';//家族会員月額課金契約予定取消
	const GET_ADDRESS_CHECK		 = 'get_address_check';//住所確認情報照会
	const REG_ADDRESS_CHECK_RESULT	 = 'reg_address_check_result';//住所確認情報結果登録

	const REG_CONTRACT_YEAR_JOIN		 = 'reg_contract_year_join';		// 有料サービス登録(年額)
	const GET_CONTRACT_YEAR_RESOURCE	 = 'get_contract_year';		// 月額課金契約照会
	const LIST_CONTRACT_YEAR_RESOURCE	 = 'get_list_contract_year';		// 年額課金契約一覧照会
	const REG_CONTRACT_YEAR_DISJOIN_RES	 = 'reg_contract_year_disjoin_res';	//年額課金契約退会予定登録
	const GET_CONTRACT_YEAR_DISJOIN_RES	 = 'get_contract_year_disjoin_res';	//年額課金契約退会予定照会
	const CAN_CONTRACT_YEAR_DISJOIN_RES	 = 'can_contract_year_disjoin_res';	//年額課金契約退会予定取消

	const GET_ATTRIBUTE					 = 'get_attribute';	//会員属性情報照会
	const UPD_ATTRIBUTE					 = 'upd_attribute';	//会員属性情報更新
	const UPD_ATTRIBUTE_SC				 = 'upd_attribute_sc';	//会員属性情報更新（現読確認用）

	private $cache_resource = array(
		self::GET_MEMBER_RESOURCE,
		self::AUT_ACCESS_TOKEN_RESOURCE,
	);
	public function __construct(){
	}

	// API叩くラッパー
	private function post($resource, $post_data){
		$socket = new KyodoSocket();
		switch ($_SERVER[SERVER_ENV_NAME]) {
			case SERVER_ENV_STAGE:
				$url = self::API_BASE_URL_STAGE.$resource;
				break;

			case SERVER_ENV_PRODUCTION:
// リリースするまでは本番サーバーでも検証鑑賞に接続するように
//				$url = self::API_BASE_URL_PRODUCTION.$resource;
				$url = self::API_BASE_URL_STAGE.$resource;
				break;

			default:
				$url = self::API_BASE_URL_STAGE.$resource;
				break;
		}
		//キャッシュ対象外APIは通常通り
		if(
			!in_array($resource,$this->cache_resource) ||
			$_SERVER['HTTP_HOST'] == IDSITE_TOP_DOMAIN
		){

			$response_body = $socket->httpsPost($url, $post_data);
			return new KyodoResponse($resource, $response_body);
		}

		//キャッシュ対象APIは通信レスポンスをセッションに保存する
		if(empty($_SESSION[API_RESPONSE_SESSION][$resource])){
			$response = $socket->httpsPost($url, $post_data);
			$objKyodoResponse = new KyodoResponse($resource, $response);
			if($objKyodoResponse->isSuccess()){
				$_SESSION[API_RESPONSE_SESSION][$resource]['limit'] = time()+600;
				$_SESSION[API_RESPONSE_SESSION][$resource]['response'] = rawurlencode($response);
			}
			return $objKyodoResponse ;
		}else{
			if(
				$_SESSION[API_RESPONSE_SESSION][$resource]['limit'] > time() &&
				!empty($_SERVER['HTTP_REFERER']) &&
				strpos($_SERVER['HTTP_REFERER'],SITE_TOP_DOMAIN) !== false
			){
				$response = rawurldecode($_SESSION[API_RESPONSE_SESSION][$resource]['response']);
				return new KyodoResponse($resource, $response);
			}else{
				$response = $socket->httpsPost($url, $post_data);
				$objKyodoResponse = new KyodoResponse($resource, $response);
				if($objKyodoResponse->isSuccess()){
					$_SESSION[API_RESPONSE_SESSION][$resource]['limit'] = time()+600;
					$_SESSION[API_RESPONSE_SESSION][$resource]['response'] = rawurlencode($response);
				}
				return $objKyodoResponse ;
			}
		}

	}

	// メールアドレス受付登録
	public function getAddressFromZip($param){
		$post_data = array_merge($this->post_data_base, array(
			'psn' => mb_strtolower($param['psn']),	// ○ 郵便番号
		));
		return $this->post(self::GET_LIST_POST, $post_data);
	}


	// メールアドレス受付登録
	public function regUserAccept($param){
		$post_data = array_merge($this->post_data_base, array(
			'mla' => mb_strtolower($param['mla']),	// ○ 会員のメールアドレス(小文字変換)
			'dmj' => $param['dmj'],	// △ 紙面購読同時申込情報

		));
		return $this->post(self::REG_USER_ACCEPT_RESOURCE, $post_data);
	}

	// メールアドレス受付照会
	public function getUserAccept($param){
		$post_data = array_merge($this->post_data_base, array(
			'urn' => $param['urn'],		// 受付番号
		));
		return $this->post(self::GET_USER_ACCEPT_RESOURCE, $post_data);
	}

	// 会員情報登録
	public function regMember($param){
		$post_data = array_merge($this->post_data_base, array(
			'adi' => $param['adi'],		// ○認証区分（ 1:会員ＩＤ認証　2:メールアドレス認証 ）
			'uid' => $param['uid'],		// △会員ID（ ※認証区分が"1"の場合、必須。）
			'uns' => $param['uns'],		// △姓（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'unn' => $param['unn'],		// △名（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'uks' => $param['uks'],		// △カナ姓
			'ukn' => $param['ukn'],		// △カナ名
			'ps1' => $param['ps1'],		// △郵便番号(親)
			'ps2' => $param['ps2'],		// △郵便番号(枝)
			'prf' => $param['prf'],		// △都道府県コード
			'ad1' => $param['ad1'],		// △住所1
			'ad2' => $param['ad2'],		// △住所2
			'ad3' => $param['ad3'],		// △住所3
			'tl1' => $param['tl1'],		// △電話番号
			'tl2' => $param['tl2'],		// △電話番号
			'tl3' => $param['tl3'],		// △電話番号
			'mla' => mb_strtolower($param['mla']),		// ○メールアドレス(小文字変換)
			'sex' => $param['sex'],		// △性別コード
			'btd' => $param['btd'],		// △生年月日
			'job' => $param['job'],		// △業種コード
			'nnm' => $param['nnm'],		// △ニックネーム
			'ups' => $param['ups'],		// ○会員パスワード
			'pr1' => $param['pr1'],		// △プロファイル1(設定なし)
			'bid' => $param['bid'],		// △ぶんぶんクラブ会員番号
			'ukb' => $param['ukb'],		// △会員区分
			'mrv' => $param['mrv'],		// △サービス案内希望
			'mlf' => $param['mlf'],		// △メルマガ配信フラグ
			'rmm' => $param['rmm'],		// △備考メモ
			'aml' => $param['aml'],		// △追加メールアドレス
		));
		return $this->post(self::REG_MEMBER_RESOURCE, $post_data);
	}

	// 新規申込登録
	public function regMemberAll($param){
		$post_data = array_merge($this->post_data_base, array(
			'adi' => $param['adi'],		// ○認証区分（ 1:会員ＩＤ認証　2:メールアドレス認証 ）
			'uid' => $param['uid'],		// △会員ID（ ※認証区分が"1"の場合、必須。）
			'uns' => $param['uns'],		// △姓（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'unn' => $param['unn'],		// △名（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'uks' => $param['uks'],		// △カナ姓
			'ukn' => $param['ukn'],		// △カナ名
			'ps1' => $param['ps1'],		// △郵便番号(親)
			'ps2' => $param['ps2'],		// △郵便番号(枝)
			'prf' => $param['prf'],		// △都道府県コード
			'ad1' => $param['ad1'],		// △住所1
			'ad2' => $param['ad2'],		// △住所2
			'ad3' => $param['ad3'],		// △住所3
			'tl1' => $param['tl1'],		// △電話番号
			'tl2' => $param['tl2'],		// △電話番号
			'tl3' => $param['tl3'],		// △電話番号
			'mla' => mb_strtolower($param['mla']),		// ○メールアドレス(小文字変換)
			'sex' => $param['sex'],		// △性別コード
			'btd' => $param['btd'],		// △生年月日
			'job' => $param['job'],		// △業種コード
			'nnm' => $param['nnm'],		// △ニックネーム
			'ups' => $param['ups'],		// ○会員パスワード
			'pr1' => $param['pr1'],		// おためし区分
			'bid' => $param['bid'],		// △ぶんぶんクラブ会員番号
			'ukb' => $param['ukb'],		// △会員区分
			'rmm' => $param['rmm'],		// △備考メモ
			'shn' => $param['shn'],		// △販売店名
			'ht1' => $param['ht1'],		// △販売店電話番号1
			'ht2' => $param['ht2'],		// △販売店電話番号2
			'ht3' => $param['ht3'],		// △販売店電話番号3
			'mrv' => $param['mrv'],		// △サービス案内希望
			'mlf' => $param['mlf'],		// △メルマガ配信フラグ
			'npn' => $param['npn'],		// △新聞購読銘柄
			'dmj' => $param['dmj'],		// △紙面同時申込情報
			'rnm' => $param['rnm'],		// △紙面同時申込情報
			'sid' => $param['sid'],		// ○サービスID
			'cid' => $param['cid'],		// ○商品ID
			'pdi' => $param['pdi'],		// ○支払い区分
			'cad' => $param['cad'],		// △キャンペーンコード
			'cpr1'	=> $param['cpr1'],	// プロファイル1
			'cpr2'	=> $param['cpr2'],	// プロファイル2
			'cpr3'	=> $param['cpr3'],	// プロファイル3
			'cpr4'	=> $param['cpr4'],	// プロファイル4
			'cpr5'	=> $param['cpr5'],	// プロファイル5
			'cpr6'	=> $param['cpr6'],	// プロファイル6
			'cpr7'	=> $param['cpr7'],	// プロファイル7
			'cpr8'	=> $param['cpr8'],	// プロファイル8
			'cpr9'	=> $param['cpr9'],	// プロファイル9
			'cpr10'	=> $param['cpr10'],	// プロファイル10
			'cno' => $param['cno'],		// △カード番号
			'cfp' => $param['cfp'],		// △カード有効期限
			'scd' => $param['scd'],		// △セキュリティコード
			'stk' => $param['stk'],		// △決済トークン
			'aml' => $param['aml'],		// △追加メールアドレス
			'fcp' => $param['fcp'],		// フロント制御情報
		));

		return $this->post(self::REG_MEMBER_ALL_RESOURCE, $post_data);
	}

	// 新規申込登録
	public function regMemberAllTrial($param){
		$post_data = array_merge($this->post_data_base, array(
			'adi' => $param['adi'],		// ○認証区分（ 1:会員ＩＤ認証　2:メールアドレス認証 ）
			'uid' => $param['uid'],		// △会員ID（ ※認証区分が"1"の場合、必須。）
			'uns' => $param['uns'],		// △姓（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'unn' => $param['unn'],		// △名（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'uks' => $param['uks'],		// △カナ姓
			'ukn' => $param['ukn'],		// △カナ名
			'ps1' => $param['ps1'],		// △郵便番号(親)
			'ps2' => $param['ps2'],		// △郵便番号(枝)
			'prf' => $param['prf'],		// △都道府県コード
			'ad1' => $param['ad1'],		// △住所1
			'ad2' => $param['ad2'],		// △住所2
			'ad3' => $param['ad3'],		// △住所3
			'tl1' => $param['tl1'],		// △電話番号
			'tl2' => $param['tl2'],		// △電話番号
			'tl3' => $param['tl3'],		// △電話番号
			'mla' => mb_strtolower($param['mla']),		// ○メールアドレス(小文字変換)
			'sex' => $param['sex'],		// △性別コード
			'btd' => $param['btd'],		// △生年月日
			'job' => $param['job'],		// △業種コード
			'nnm' => $param['nnm'],		// △ニックネーム
			'ups' => $param['ups'],		// ○会員パスワード
			'mrv' => $param['mrv'],		// △サービス案内希望
			'mlf' => $param['mlf'],		// △メルマガ配信フラグ
			'usl' => $param['usl'],		// △会員別同時ログイン数
			'rmm' => $param['rmm'],		// △備考メモ
			'aml' => $param['aml'],		// △追加メールアドレス
			'sid' => $param['sid'],		// ○サービスID
			'cid' => $param['cid'],		// ○商品ID
		));

		return $this->post(self::REG_MEMBER_ALL_TRIAL_RESOURCE, $post_data);
	}

	// 新規申込登録
	public function regMemberAllCorp($param){
		$post_data = array_merge($this->post_data_base, array(
			'adi'  => $param['adi'],		// ○認証区分（ 1:会員ＩＤ認証　2:メールアドレス認証 ）
			'uid'  => $param['uid'],		// △会員ID（ ※認証区分が"1"の場合、必須。）
			'uns'  => $param['uns'],		// △姓（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'unn'  => $param['unn'],		// △名（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'uks'  => $param['uks'],		// △カナ姓
			'ukn'  => $param['ukn'],		// △カナ名
			'mla'  => mb_strtolower($param['mla']),// ○メールアドレス(小文字変換)
			'btd'  => $param['btd'],		// △生年月日
			'ups'  => $param['ups'],		// ○パスワード
			'mrv'  => $param['mrv'],		// △サービス案内希望
			'mlf'  => $param['mlf'],		// △メルマガ配信フラグ
			'usl'  => $param['usl'],		// △会員別同時ログイン数
			'shn'  => $param['shn'],		// △販売所名
			'ht1'  => $param['ht1'],		// △販売所電話番号1
			'ht2'  => $param['ht2'],		// △販売所電話番号2
			'ht3'  => $param['ht3'],		// △販売所電話番号3
			'npn'  => $param['npn'],		// △新聞購読銘柄
			'dmj'  => $param['dmj'],		// △紙面購読申込情報
			'rnm'  => $param['rnm'],		// △新聞購読名義人
			'hid'  => $param['hid'],		// △法人ID
			'hnm'  => $param['hnm'],		// ○法人名
			'hnk'  => $param['hnk'],		// △法人名かな
			'cps1' => $param['cps1'],		// △契約先郵便番号　親
			'cps2' => $param['cps2'],		// △契約先郵便番号　枝
			'cprf' => $param['cprf'],		// △契約先都道府県コード
			'cad1' => $param['cad1'],		// △契約先住所1
			'cad2' => $param['cad2'],		// △契約先住所2
			'ctel' => $param['ctel'],		// △契約先電話番号
			'cfax' => $param['cfax'],		// △契約先FAX番号
			'cmla' => $param['cmla'],		// △契約先メールアドレス
			'chbn' => $param['chbn'],		// △契約先担当部署
			'chtn' => $param['chtn'],		// △契約先担当者名
			'dps1' => $param['dps1'],		// △請求先郵便番号　親
			'dps2' => $param['dps2'],		// △請求先郵便番号　枝
			'dprf' => $param['dprf'],		// △請求先都道府県コード
			'dad1' => $param['dad1'],		// △請求先住所1
			'dad2' => $param['dad2'],		// △請求先住所2
			'dtel' => $param['dtel'],		// △請求先電話番号
			'dfax' => $param['dfax'],		// △請求先FAX番号
			'dmla' => $param['dmla'],		// △請求先メールアドレス
			'dhbn' => $param['dhbn'],		// △請求先担当部署
			'dhtn' => $param['dhtn'],		// △請求先担当者名
			'dnc'  => $param['dnc'],		// ○住所確認書類送付先区分
			'cap'  => $param['cap'],		// △クレジットカード申し込み
			'rmm'  => $param['rmm'],		// △備考
			'urm'  => $param['urm'],		// △ユーザー備考欄
			'sid'  => $param['sid'],		// ○サービスID
			'cid'  => $param['cid'],		// ○商品ID
			'pdi'  => $param['pdi'],		// ○支払い区分
			'cad'  => $param['cad'],		// △キャンペーンコード
			'cpr1'	=> $param['cpr1'],		// 汎用コード
			'cpr2'	=> $param['cpr2'],		// 大学名/卒業年月
			'cpr3'	=> $param['cpr3'],		// プロファイル3
			'cpr4'	=> $param['cpr4'],		// プロファイル4
			'cpr5'	=> $param['cpr5'],		// プロファイル5
			'cpr6'	=> $param['cpr6'],		// プロファイル6
			'cpr7'	=> $param['cpr7'],		// プロファイル7
			'cpr8'	=> $param['cpr8'],		// プロファイル8
			'cpr9'	=> $param['cpr9'],		// プロファイル9
			'cpr10'	=> $param['cpr10'],		// プロファイル10
			'cno'  => $param['cno'],		// △カード番号
			'cfp'  => $param['cfp'],		// △カード有効期限
			'scd'  => $param['scd'],		// △セキュリティコード
			'stk'  => $param['stk'],		// △決済用トークン
			'aml'  => $param['aml'],		// △追加メールアドレス
			'fcp'  => $param['fcp'],		// フロント制御情報
		));
		return $this->post(self::REG_MEMBER_ALL_CORP_RESOURCE, $post_data);
	}

	// 新規申込登録(都度課金同時)
	public function regMemberAccount($param){
		$post_data = array_merge($this->post_data_base, array(
			'adi' => $param['adi'],		// ○認証区分（ 1:会員ＩＤ認証　2:メールアドレス認証 ）
			'uid' => $param['uid'],		// △会員ID（ ※認証区分が"1"の場合、必須。）
			'uns' => $param['uns'],		// △姓（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'unn' => $param['unn'],		// △名（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'uks' => $param['uks'],		// △カナ姓
			'ukn' => $param['ukn'],		// △カナ名
			'ps1' => $param['ps1'],		// △郵便番号(親)
			'ps2' => $param['ps2'],		// △郵便番号(枝)
			'prf' => $param['prf'],		// △都道府県コード
			'ad1' => $param['ad1'],		// △住所1
			'ad2' => $param['ad2'],		// △住所2
			'ad3' => $param['ad3'],		// △住所3
			'tl1' => $param['tl1'],		// △電話番号
			'tl2' => $param['tl2'],		// △電話番号
			'tl3' => $param['tl3'],		// △電話番号
			'mla' => mb_strtolower($param['mla']),		// ○メールアドレス(小文字変換)
			'sex' => $param['sex'],		// △性別コード
			'btd' => $param['btd'],		// △生年月日
			'job' => $param['job'],		// △業種コード
			'nnm' => $param['nnm'],		// △ニックネーム
			'ups' => $param['ups'],		// ○会員パスワード
			'mrv' => $param['mrv'],		// △サービス案内希望
			'mlf' => $param['mlf'],		// △メルマガ配信フラグ
			'usl' => $param['usl'],		// △会員別同時ログイン数
			'aml' => $param['aml'],		// △追加メールアドレス
			'cno' => $param['cno'],		// △カード番号
			'cfp' => $param['cfp'],		// △カード有効期限
			'scd' => $param['scd'],		// △セキュリティコード
			'stk' => $param['stk'],		// △決済トークン
			'sid' => $param['sid'],		// ○サービスID
		));
		foreach ($param['account_item'] as $key => $value) {
			$post_data[] = array(
				'cid' => $value['cid'],		// ○商品ID
				'cnt' => $value['cnt'],		// ○個数
				'rsd' => $value['rsd'],		// △購読開始日
			);
		}
		return $this->post(self::REG_MEMBER_ACCOUNT_RESOURCE, $post_data);
	}

	// 新規申込登録(都度課金同時)
	public function regMemberCorpAccount($param){
		$post_data = array_merge($this->post_data_base, array(
			'adi'  => $param['adi'],		// ○認証区分（ 1:会員ＩＤ認証　2:メールアドレス認証 ）
			'uid'  => $param['uid'],		// △会員ID（ ※認証区分が"1"の場合、必須。）
			'uns'  => $param['uns'],		// △姓（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'unn'  => $param['unn'],		// △名（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'uks'  => $param['uks'],		// △カナ姓
			'ukn'  => $param['ukn'],		// △カナ名
			'mla'  => mb_strtolower($param['mla']),// ○メールアドレス(小文字変換)
			'btd'  => $param['btd'],		// △生年月日
			'ups'  => $param['ups'],		// ○パスワード
			'mrv'  => $param['mrv'],		// △サービス案内希望
			'mlf'  => $param['mlf'],		// △メルマガ配信フラグ
			'usl'  => $param['usl'],		// △会員別同時ログイン数
			'aml'  => $param['aml'],		// △追加メールアドレス
			'hid'  => $param['hid'],		// △法人ID
			'hnm'  => $param['hnm'],		// ○法人名
			'hnk'  => $param['hnk'],		// △法人名かな
			'cps1' => $param['cps1'],		// △契約先郵便番号　親
			'cps2' => $param['cps2'],		// △契約先郵便番号　枝
			'cprf' => $param['cprf'],		// △契約先都道府県コード
			'cad1' => $param['cad1'],		// △契約先住所1
			'cad2' => $param['cad2'],		// △契約先住所2
			'ctel' => $param['ctel'],		// △契約先電話番号
			'cfax' => $param['cfax'],		// △契約先FAX番号
			'cmla' => $param['cmla'],		// △契約先メールアドレス
			'chbn' => $param['chbn'],		// △契約先担当部署
			'chtn' => $param['chtn'],		// △契約先担当者名
			'dps1' => $param['dps1'],		// △請求先郵便番号　親
			'dps2' => $param['dps2'],		// △請求先郵便番号　枝
			'dprf' => $param['dprf'],		// △請求先都道府県コード
			'dad1' => $param['dad1'],		// △請求先住所1
			'dad2' => $param['dad2'],		// △請求先住所2
			'dtel' => $param['dtel'],		// △請求先電話番号
			'dfax' => $param['dfax'],		// △請求先FAX番号
			'dmla' => $param['dmla'],		// △請求先メールアドレス
			'dhbn' => $param['dhbn'],		// △請求先担当部署
			'dhtn' => $param['dhtn'],		// △請求先担当者名
			'dnc'  => $param['dnc'],		// ○住所確認書類送付先区分
			'cap'  => $param['cap'],		// △クレジットカード申し込み
			'rmm'  => $param['rmm'],		// △備考
			'urm'  => $param['urm'],		// △ユーザー備考欄
			'cno'  => $param['cno'],		// △カード番号
			'cfp'  => $param['cfp'],		// △カード有効期限
			'scd'  => $param['scd'],		// △セキュリティコード
			'stk'  => $param['stk'],		// △決済用トークン
			'aml'  => $param['aml'],		// △追加メールアドレス
			'sid'  => $param['sid'],		// ○サービスID
		));
		foreach ($param['account_item'] as $key => $value) {
			$post_data[] = array(
				'cid' => $value['cid'],		// ○商品ID
				'cnt' => $value['cnt'],		// ○個数
				'rsd' => $value['rsd'],		// △購読開始日
			);
		}
		return $this->post(self::REG_MEMBER_CORP_ACCOUNT_RESOURCE, $post_data);
	}

	// アクセストークン認証
	public function autAccessToken($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
		));
		return $this->post(self::AUT_ACCESS_TOKEN_RESOURCE, $post_data);
	}

	// アクセストークン破棄
	public function endAccess($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
		));
		return $this->post(self::END_ACCESS_RESOURCE, $post_data);
	}

	// 会員情報照会
	public function getMember($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
		));
	 	return $this->post(self::GET_MEMBER_RESOURCE, $post_data);
	}

	//会員認証
	public function autMember($param){
		$post_data = array_merge($this->post_data_base, array(
			'adi' => $param['adi'],		// 認証区分
			'uid' => $param['uid'],		// メールアドレス
			'mla' => $param['mla'],		// メールアドレス
			'ups' => $param['ups'],		// パスワード
		));
		return $this->post(self::AUT_MEMBER_RESOURCE, $post_data);
	}

	//法人情報照会
	public function getCorp($param){
		$post_data = array_merge($this->post_data_base, array(
			'hid' => $param['hid'],		// 法人ID
		));
		return $this->post(self::GET_CORP, $post_data);
	}

	//法人情報変更
	public function updCorp($param){
		$post_data = array_merge($this->post_data_base, array(
			'hid'  => $param['hid'],		// △法人ID
			'hnm'  => $param['hnm'],		// ○法人名
			'hnk'  => $param['hnk'],		// △法人名かな
			'cps1' => $param['cps1'],		// △契約先郵便番号　親
			'cps2' => $param['cps2'],		// △契約先郵便番号　枝
			'cprf' => $param['cprf'],		// △契約先都道府県コード
			'cad1' => $param['cad1'],		// △契約先住所1
			'cad2' => $param['cad2'],		// △契約先住所2
			'ctel' => $param['ctel'],		// △契約先電話番号
			'cfax' => $param['cfax'],		// △契約先FAX番号
			'cmla' => $param['cmla'],		// △契約先メールアドレス
			'chbn' => $param['chbn'],		// △契約先担当部署
			'chtn' => $param['chtn'],		// △契約先担当者名
			'dps1' => $param['dps1'],		// △請求先郵便番号　親
			'dps2' => $param['dps2'],		// △請求先郵便番号　枝
			'dprf' => $param['dprf'],		// △請求先都道府県コード
			'dad1' => $param['dad1'],		// △請求先住所1
			'dad2' => $param['dad2'],		// △請求先住所2
			'dtel' => $param['dtel'],		// △請求先電話番号
			'dfax' => $param['dfax'],		// △請求先FAX番号
			'dmla' => $param['dmla'],		// △請求先メールアドレス
			'dhbn' => $param['dhbn'],		// △請求先担当部署
			'dhtn' => $param['dhtn'],		// △請求先担当者名
			'dnc'  => $param['dnc'],		// ○住所確認書類送付先区分
			'cap'  => $param['cap'],		// △クレジットカード申し込み
			'rmm'  => $param['rmm'],		// △備考
			'npn'  => $param['npn'],		// △新聞購読銘柄
			'rnm'  => $param['rnm'],		// △新聞購読名義人
		));
		return $this->post(self::UPD_CORP, $post_data);
	}

	// 会員情報変更
	public function updMember($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'uns' => $param['uns'],		// △姓（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'unn' => $param['unn'],		// △名（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'uks' => $param['uks'],		// △カナ姓
			'ukn' => $param['ukn'],		// △カナ名
			'tl1' => $param['tl1'],		// △電話番号
			'tl2' => $param['tl2'],		// △電話番号
			'tl3' => $param['tl3'],		// △電話番号
			'sex' => $param['sex'],		// △性別コード
			'btd' => $param['btd'],		// △生年月日
			'job' => $param['job'],		// △業種コード
			'nnm' => $param['nnm'],		// △ニックネーム
			'bid' => $param['bid'],		// △ぶんぶんクラブ会員番号
			'shn' => $param['shn'],		// △販売店名
			'ht1' => $param['ht1'],		// △販売店電話番号1
			'ht2' => $param['ht2'],		// △販売店電話番号2
			'ht3' => $param['ht3'],		// △販売店電話番号3
			'mrv' => $param['mrv'],		// △サービス案内メール希望
			'mlf' => $param['mlf'],		// △メルマガ配信フラグ
			'usl' => $param['usl'],		// △会員別同時ログイン数
			'npn' => $param['npn'],		// △新聞購読銘柄

		));
		return $this->post(self::UPD_MEMBER_RESOURCE, $post_data);
	}

	// 会員住所変更
	public function updAddress($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'ps1' => $param['ps1'],		// △郵便番号(親)
			'ps2' => $param['ps2'],		// △郵便番号(枝)
			'prf' => $param['prf'],		// △都道府県コード
			'ad1' => $param['ad1'],		// △住所1
			'ad2' => $param['ad2'],		// △住所2
			'ad3' => $param['ad3'],		// △住所3
			'shn' => $param['shn'],		// △販売店名
			'ht1' => $param['ht1'],		// △販売店電話番号1
			'ht2' => $param['ht2'],		// △販売店電話番号2
			'ht3' => $param['ht3'],		// △販売店電話番号3
		));
		return $this->post(self::UPD_ADDRESS_RESOURCE, $post_data);
	}

	// 引っ越し手続き情報登録
	public function regMoveAddress($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'nps1' => $param['nps1'],	// △郵便番号(親)
			'nps2' => $param['nps2'],	// △新郵便番号(枝)
			'nprf' => $param['nprf'],	// △新都道府県コード
			'nad1' => $param['nad1'],	// △新住所1
			'nad2' => $param['nad2'],	// △新住所2
			'nad3' => $param['nad3'],	// △新住所3
			'ntl1' => $param['ntl1'],	// △新電話番号1
			'ntl2' => $param['ntl2'],	// △新電話番号2
			'ntl3' => $param['ntl3'],	// △新電話番号3
			'shn' => $param['shn'],		// △販売店名
			'ht1' => $param['ht1'],		// △販売店電話番号1
			'ht2' => $param['ht2'],		// △販売店電話番号2
			'ht3' => $param['ht3'],		// △販売店電話番号3
			'mvd' => $param['mvd'],		// ○引っ越し日
		));
		return $this->post(self::REG_MOVE_ADDRESS, $post_data);
	}

	// 引っ越し手続き情報登録
	public function regMoveAddressCorp($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'hid' => $param['hid'],		// 法人ID
			'nps1' => $param['nps1'],	// △郵便番号(親)
			'nps2' => $param['nps2'],	// △新郵便番号(枝)
			'nprf' => $param['nprf'],	// △新都道府県コード
			'nad1' => $param['nad1'],	// △新住所1
			'nad2' => $param['nad2'],	// △新住所2
			'ntl' => $param['ntl'],		// △新電話番号
			'nfx' => $param['nfx'],		// △新FAX番号
			'mvd' => $param['mvd'],		// ○引っ越し日
		));
		return $this->post(self::REG_MOVE_ADDRESS_CORP, $post_data);
	}


	// 引越届情報取得
	public function getMoveAddress($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
		));
		return $this->post(self::GET_MOVE_ADDRESS, $post_data);
	}

	// 引越届情報取得
	public function getMoveAddressCorp($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'hid' => $param['hid'],		// 法人ID
		));
		return $this->post(self::GET_MOVE_ADDRESS_CORP, $post_data);
	}



	// 引越届情報取消
	public function canMoveAddress($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
		));
		return $this->post(self::CAN_MOVE_ADDRESS, $post_data);
	}

	// 引越届情報取消
	public function canMoveAddressCorp($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'hid' => $param['hid'],		// 法人ID
		));
		return $this->post(self::CAN_MOVE_ADDRESS_CORP, $post_data);
	}

	// 月額課金契約照会
	public function getContract($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'sid' => $param['sid'],		// サービスID
			'cid' => $param['cid'],		// 商品ID
		));
		return $this->post(self::GET_CONTRACT_RESOURCE, $post_data);
	}

	// 月額課金契約一覧照会
	public function getListContract($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'smd' => $param['smd'],	// 照会モード(1:全ての月額課金契約(退会含む) 2:入会中の月額課金契約)
		));
		return $this->post(self::LIST_CONTRACT_RESOURCE, $post_data);
	}


	//月額課金契約退会予定照会
	public function getContractDisjoinRes($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'sid' => $param['sid'],		// サービスID
		));
		return $this->post(self::GET_CONTRACT_DISJOIN_RES, $post_data);
	}

	//月額課金契約退会予定取消
	public function canContractDisjoinRes($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'sid' => $param['sid'],		// サービスID
		));
		return $this->post(self::CAN_CONTRACT_DISJOIN_RES, $post_data);
	}

	//月額課金契約退会予定登録
	public function regContractDisjoinRes($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'sid' => $param['sid'],		// サービスID
			'cid' => $param['cid'],		// 商品ID
			'dsd' => $param['dsd'],		//
		));
		return $this->post(self::REG_CONTRACT_DISJOIN_RES, $post_data);
	}

	// カード情報照会
	public function getCardInfo($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
		));
		return $this->post(self::GET_CARD_INFO_RESOURCE, $post_data);
	}

	// メールアドレス・パスワードチェック
	public function chkMailaddress($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'mla' => mb_strtolower($param['mla']),		// メールアドレス(小文字変換)
			'ups' => $param['ups'],		// 会員パスワード
		));
		return $this->post(self::CHK_MAILADDRESS_RESOURCE, $post_data);
	}

	// 会員情報退会予定登録
	public function regMemberDisjoinRes($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'dsd' => $param['dsd'],		// 退会予定日
		));
		return $this->post(self::REG_MEMBER_DISJOIN_RES_RESOURCE, $post_data);
	}

	// 会員情報退会予定照会
	public function getMemberDisjoinRes($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
		));
		return $this->post(self::GET_MEMBER_DISJOIN_RES_RESOURCE, $post_data);
	}

	// 会員情報退会予定取消
	public function canMemberDisjoinRes($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
		));
		return $this->post(self::CAN_MEMBER_DISJOIN_RES_RESOURCE, $post_data);
	}

	// メールアドレス変更受付登録
	public function regMailaddressAccept($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'mla' => mb_strtolower($param['mla']),		// メールアドレス(小文字変換)
			'flg' => $param['flg'],		// 追加メールフラグ　0:ログインＩＤ変更　1:追加メールアドレス登録
		));
		return $this->post(self::REG_MAILADDRESS_ACCEPT_RESOURCE, $post_data);
	}

	// 追加メールアドレス解除
	public function remMailaddress($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
		));
		return $this->post(self::REM_MAILADDRESS, $post_data);
	}



	// メールアドレス変更
	public function chgMailaddress($param){
		$post_data = array_merge($this->post_data_base, array(
			'mkb' => $param['mkb'],		// メールアドレス変更区分
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'mla' => mb_strtolower($param['mla']),		// メールアドレス(小文字変換)
			'urn' => $param['urn'],		// 受付番号
			'flg' => $param['flg'],		// 追加メールフラグ　0:ログインＩＤ変更　1:追加メールアドレス登録
		));
		return $this->post(self::CHG_MAILADDRESS_RESOURCE, $post_data);
	}

	// 会員パスワード変更
	public function chgPass($param){
		$post_data = array_merge($this->post_data_base, array(
			'pkb'     => $param['pkb'],		// パスワード変更区分
			'uid'     => $param['uid'],		// 会員ID
			'tkn'     => $param['tkn'],		// アクセストークン
			'ups_new' => $param['ups_new'],		// 新パスワード
			'urn'     => $param['urn'],		// 受付番号
		));
		return $this->post(self::CHG_PASS_RESOURCE, $post_data);
	}

	// クレジット決済方法登録
	public function regCard($param){
		$post_data = array_merge($this->post_data_base, array(
			'ckb' => $param['ckb'],		// クレジット変更区分
			'uid' => $param['uid'],		// 会員ID
			'cno' => $param['cno'],		// カード番号
			'cfp' => $param['cfp'],		// カード有効期限
			'scd' => $param['scd'],		// セキュリティコード
			'stk' => $param['stk'],		// 決済トークン
			'urn' => $param['urn'],		// 受付番号
		));
		return $this->post(self::REG_CARD_RESOURCE, $post_data);
	}

	// メールサービス情報登録
	public function regMailMagazine($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
		));
		foreach ($param['mail_service_info'] as $key => $value) {
			$post_data[] = array(
				'msa' => $value['msa'],		// 送信先メールアドレス
				'msb' => $value['msb'],		// メールマガジン種別
				'hkb' => $value['hkb'],		// メールマガジン配信区分
				'pr1' => $value['mpr1'],	// プロファイル1
				'pr2' => $value['mpr2'],	// プロファイル2
				'pr3' => $value['mpr3'],	// プロファイル3
				'pr4' => $value['mpr4'],	// プロファイル4
				'pr5' => $value['mpr5'],	// プロファイル5
				'pr6' => $value['mpr6'],	// プロファイル6
				'pr7' => $value['mpr7'],	// プロファイル7
				'pr8' => $value['mpr8'],	// プロファイル8
				'pr9' => $value['mpr9'],	// プロファイル9
				'pr10' => $value['mpr10'],	// プロファイル10
			);
		}
		return $this->post(self::REG_MAIL_MAGAZINE_RESOURCE, $post_data);
	}

	// パスワード忘れ受付登録
	public function regPassForgetAccept($param){
		$post_data = array_merge($this->post_data_base, array(
			'mla' => mb_strtolower($param['mla']),		// メールアドレス(小文字変換)
			'uns' => $param['uns'],		// 氏名(姓)
			'unn' => $param['unn'],		// 氏名(名)
			'btd' => $param['btd'],		// 生年月日
		));
		return $this->post(self::REG_PASS_FORGET_ACCEPT_RESOURCE, $post_data);
	}

	// パスワード変更(忘れ)
	public function regPassForget($param){
		$post_data = array_merge($this->post_data_base, array(
			'ups_new' => $param['ups_new'],		// 新パスワード
			'urn' => $param['urn'],		// 受付番号
		));
		return $this->post(self::REG_PASS_FORGET_RESOURCE, $post_data);
	}

	// 課金情報一覧照会
	public function getListBill($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'ayy' => $param['ayy'],		// 課金年
		));
		return $this->post(self::GET_LIST_BILL_RESOURCE, $post_data);
	}

	// 課金明細情報一覧照会
	public function getListBillDetail($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'aym' => $param['aym'],		// 課金年月
		));
		return $this->post(self::GET_LIST_BILLDETAIL_RESOURCE, $post_data);
	}
	// 本人確認API
	public function authPerson($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'ups' => $param['ups'],		// パスワード
		));
		return $this->post(self::AUT_PERSON, $post_data);
	}


	// サービスマスタ一覧照会
	public function getListService(){
		$post_data = $this->post_data_base;
		return $this->post(self::GET_LIST_SERVICE, $post_data);
	}

	// サービスマスタ照会
	public function getService($param){
		$post_data = array_merge($this->post_data_base, array(
			'sid' => $param['sid'],		// ○サービスID
		));
		return $this->post(self::GET_SERVICE, $post_data);
	}

	// 商品マスタ一覧照会
	public function getListCommodity($param){
		$post_data = array_merge($this->post_data_base, array(
			'sid' => $param['sid'],		// ○サービスID
			'gmd' => '1',				// ○照会モード (1:全商品　2:表示対象商品のみ)
		));
		return $this->post(self::GET_LIST_COMMODITY, $post_data);
	}

	// 商品マスタ照会
	public function getCommodity($param){
		$post_data = array_merge($this->post_data_base, array(
			'sid' => $param['sid'],		// ○サービスID
			'cid' => $param['cid'],		// ○商品ID
		));
		return $this->post(self::GET_COMMODITY, $post_data);
	}

	// コードマスタ一覧照会
	public function getListCode($param){
		$post_data = array_merge($this->post_data_base, array(
			'cdd' => $param['cdd'],		// ○コード種別(別紙「コード定義」にて定義されたコード種別を指定 )("FRT"を指定した場合はフロントシステムでのみ使用する情報のみを返却)
		));
		return $this->post(self::GET_LIST_CODE, $post_data);
	}

	// コードマスタ照会
	public function getCode($param){
		$post_data = array_merge($this->post_data_base, array(
			'cdd' => $param['cdd'],		// ○コード種別(別紙「コード定義」にて定義されたコード種別を指定)
			'cod' => $param['cod'],		// ○コード値(別紙「コード定義」にて定義されたコード値を指定)
		));
		return $this->post(self::GET_CODE, $post_data);
	}

	// 有料サービス登録
	public function regContractJoin($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ＩＤ
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
			'cid' => $param['cid'],		// ○ 商品ID
			'mjd' => $param['mjd'],		// ○ 商品ID
			'pdi' => $param['pdi'],		// ○ 支払い区分（ 1:クレジットカード決済　2:販売店決済　3:キャリア決済）
			'uns' => $param['uns'],		// △姓（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'unn' => $param['unn'],		// △名（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'uks' => $param['uks'],		// △カナ姓
			'ukn' => $param['ukn'],		// △カナ名
			'ps1' => $param['ps1'],		// △郵便番号(親)
			'ps2' => $param['ps2'],		// △郵便番号(枝)
			'prf' => $param['prf'],		// △都道府県コード
			'ad1' => $param['ad1'],		// △住所1
			'ad2' => $param['ad2'],		// △住所2
			'ad3' => $param['ad3'],		// △住所3
			'tl1' => $param['tl1'],		// △電話番号
			'tl2' => $param['tl2'],		// △電話番号
			'tl3' => $param['tl3'],		// △電話番号
			'cpr1'	=> $param['cpr1'],	// キャンペーンコード
			'cpr2'	=> $param['cpr2'],	// 大学名/卒業年月
			'cpr3'	=> $param['cpr3'],	// プロファイル3
			'cpr4'	=> $param['cpr4'],	// プロファイル4
			'cpr5'	=> $param['cpr5'],	// プロファイル5
			'cpr6'	=> $param['cpr6'],	// プロファイル6
			'cpr7'	=> $param['cpr7'],	// プロファイル7
			'cpr8'	=> $param['cpr8'],	// プロファイル8
			'cpr9'	=> $param['cpr9'],	// プロファイル9
			'cpr10'	=> $param['cpr10'],	// プロファイル10
			'stk'	=> $param['stk'],	// 決済要トークン
			'dmj' => $param['dmj'],		// △紙面同時申込情報
			'mcd' => $param['mcd'],		// △販売店コード
			'shn' => $param['shn'],		// △販売店名
			'ht1' => $param['ht1'],		// △販売店電話番号1
			'ht2' => $param['ht2'],		// △販売店電話番号2
			'ht3' => $param['ht3'],		// △販売店電話番号3
			'npn' => $param['npn'],		// △新聞購読銘柄
			'rnm' => $param['rnm'],		// △新聞購読銘柄
			'fcp' => $param['fcp'],		// △フロント表示制御
		));
		return $this->post(self::REG_CONTRACT_JOIN, $post_data);
	}

	// クレジット月額課金契約登録
	public function regCardContract($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ＩＤ
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'pdi' => $param['pdi'],		// ○ 支払い区分（ 1:クレジットカード決済　2:販売店決済　3:キャリア決済）
			'uns' => $param['uns'],		// △姓（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'unn' => $param['unn'],		// △名（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'uks' => $param['uks'],		// △カナ姓
			'ukn' => $param['ukn'],		// △カナ名
			'ps1' => $param['ps1'],		// △郵便番号(親)
			'ps2' => $param['ps2'],		// △郵便番号(枝)
			'prf' => $param['prf'],		// △都道府県コード
			'ad1' => $param['ad1'],		// △住所1
			'ad2' => $param['ad2'],		// △住所2
			'ad3' => $param['ad3'],		// △住所3
			'tl1' => $param['tl1'],		// △電話番号
			'tl2' => $param['tl2'],		// △電話番号
			'tl3' => $param['tl3'],		// △電話番号
			'sid' => $param['sid'],		// ○サービスID
			'cid' => $param['cid'],		// ○商品ID
			'mjd' => $param['mjd'],		// △入会月区分
			'pdi' => $param['pdi'],		// ○支払区分
			'cpr1'	=> $param['cpr1'],	// キャンペーンコード
			'cpr2'	=> $param['cpr2'],	// 大学名/卒業年月
			'cpr3'	=> $param['cpr3'],	// プロファイル3
			'cpr4'	=> $param['cpr4'],	// プロファイル4
			'cpr5'	=> $param['cpr5'],	// プロファイル5
			'cpr6'	=> $param['cpr6'],	// プロファイル6
			'cpr7'	=> $param['cpr7'],	// プロファイル7
			'cpr8'	=> $param['cpr8'],	// プロファイル8
			'cpr9'	=> $param['cpr9'],	// プロファイル9
			'cpr10'	=> $param['cpr10'],	// プロファイル10
			'cno' => $param['cno'],		// ○クレジットカード番号
			'cfp' => $param['cfp'],		// ○カード有効期限
			'scd' => $param['scd'],		// ○セキュリティコード
			'stk' => $param['stk'],		// ○決済トークン
			'dmj' => $param['dmj'],		// △紙面同時申込情報
			'mcd' => $param['mcd'],		// △販売店コード
			'shn' => $param['shn'],		// △販売店名
			'ht1' => $param['ht1'],		// △販売店電話番号1
			'ht2' => $param['ht2'],		// △販売店電話番号2
			'ht3' => $param['ht3'],		// △販売店電話番号3
			'npn' => $param['npn'],		// △新聞購読銘柄
		));
		return $this->post(self::REG_CARD_CONTRACT, $post_data);
	}

	// 有料サービス登録(おためし)
	public function regContractJoinTrial($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ＩＤ
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
			'cid' => $param['cid'],		// ○ 商品ID
			'uns' => $param['uns'],		// △姓（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'unn' => $param['unn'],		// △名（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'uks' => $param['uks'],		// △カナ姓
			'ukn' => $param['ukn'],		// △カナ名
			'ps1' => $param['ps1'],		// △郵便番号(親)
			'ps2' => $param['ps2'],		// △郵便番号(枝)
			'prf' => $param['prf'],		// △都道府県コード
			'ad1' => $param['ad1'],		// △住所1
			'ad2' => $param['ad2'],		// △住所2
			'ad3' => $param['ad3'],		// △住所3
			'tl1' => $param['tl1'],		// △電話番号
			'tl2' => $param['tl2'],		// △電話番号
			'tl3' => $param['tl3'],		// △電話番号
		));
		return $this->post(self::REG_CONTRACT_JOIN_TRIAL, $post_data);
	}



	// プラン変更
	public function chgContract($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ＩＤ
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
			'cid' => $param['cid'],		// ○ 商品ID
			'cid_new' => $param['cid'],	// ○ 変更後商品ID
			'pdi' => $param['pdi'],		// ○ 支払い区分（ 1:クレジットカード決済　2:販売店決済　3:キャリア決済）
			'cno' => $param['cno'],		// △カード番号
			'cfp' => $param['cfp'],		// △カード有効期限
			'scd' => $param['scd'],		// △セキュリティコード
			'ps1' => $param['ps1'],		// △郵便番号(親)
			'ps2' => $param['ps2'],		// △郵便番号(枝)
			'prf' => $param['prf'],		// △都道府県コード
			'ad1' => $param['ad1'],		// △住所1
			'ad2' => $param['ad2'],		// △住所2
			'ad3' => $param['ad3'],		// △住所3
			'tl1' => $param['tl1'],		// △電話番号
			'tl2' => $param['tl2'],		// △電話番号
			'tl3' => $param['tl3'],		// △電話番号
			'dmj' => $param['dmj'],		// △紙面同時申込情報
			'mcd' => $param['mcd'],		// △販売店コード
			'shn' => $param['shn'],		// △販売店名
			'ht1' => $param['ht1'],		// △販売店電話番号1
			'ht2' => $param['ht2'],		// △販売店電話番号2
			'ht3' => $param['ht3'],		// △販売店電話番号3
			'npn' => $param['npn'],		// △新聞購読銘柄
		));
		return $this->post(self::CHG_CONTRACT, $post_data);
	}

	// プラン変更予定登録
	public function regContractChg($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ＩＤ
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
			'cid' => $param['cid'],		// ○ 商品ID
			'cid_new' => $param['cid_new'],	// ○ 変更後商品ID
			'psd' => $param['psd'],		// △カード番号
			'pdi' => $param['pdi'],		// ○ 支払い区分（ 1:クレジットカード決済　2:販売店決済　3:キャリア決済 4:請求書決済 9:無課金）
			'cno' => $param['cno'],		// △カード番号
			'cfp' => $param['cfp'],		// △カード有効期限
			'scd' => $param['scd'],		// △セキュリティコード
			'ps1' => $param['ps1'],		// △郵便番号(親)
			'ps2' => $param['ps2'],		// △郵便番号(枝)
			'prf' => $param['prf'],		// △都道府県コード
			'ad1' => $param['ad1'],		// △住所1
			'ad2' => $param['ad2'],		// △住所2
			'ad3' => $param['ad3'],		// △住所3
			'tl1' => $param['tl1'],		// △電話番号
			'tl2' => $param['tl2'],		// △電話番号
			'tl3' => $param['tl3'],		// △電話番号
			'stk' => $param['stk'],		// △決済用トークン
			'trm' => $param['trm'],		// △決済モード
			'dmj' => $param['dmj'],		// △紙面同時申込情報
			'mcd' => $param['mcd'],		// △販売店コード
			'shn' => $param['shn'],		// △販売店名
			'ht1' => $param['ht1'],		// △販売店電話番号1
			'ht2' => $param['ht2'],		// △販売店電話番号2
			'ht3' => $param['ht3'],		// △販売店電話番号3
			'npn' => $param['npn'],		// △新聞購読銘柄
		));
		return $this->post(self::REG_CONTRACT_CHANGE, $post_data);
	}

	// プラン変更予定紹介
	public function getContractChg($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ＩＤ
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
		));
		return $this->post(self::GET_CONTRACT_CHANGE, $post_data);
	}

	// プラン変更予定紹介
	public function canContractChg($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ＩＤ
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
		));
		return $this->post(self::CAN_CONTRACT_CHANGE, $post_data);
	}


	// 有料サービス停止
	public function regContractDisjoin($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ＩＤ
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
			'cid' => $param['cid'],		// ○ 商品ID
		));
		return $this->post(self::REG_CONTRACT_DISJOIN, $post_data);
	}

	// メールサービス情報登録
	public function regMailService($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ＩＤ
			'tkn' => $param['tkn'],		// ○ アクセストークン
		));
		$post_data[] = array(
			'msb' => $param['msb'],		// ○ メールサービス種別
			'msa' => $param['msa'],		// △ 配信先メールアドレス
			'hkb' => $param['hkb'],		// ○ 配信区分
		);
		return $this->post(self::REG_MAIL_SERVICE, $post_data);
	}

	// メールサービス情報照会
	public function getMailService($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ＩＤ
			'tkn' => $param['tkn'],		// ○ アクセストークン
		));
		return $this->post(self::GET_MAIL_SERVICE, $post_data);
	}

	//お問い合わせ登録
	public function regInquiry($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'sid' => $param['sid'],		// サービスID
			'ctg' => $param['ctg'],		//カテゴリコード
			'sbj' => $param['sbj'],		//件名
			'qtn' => $param['qtn'],		//問い合わせ内容
		));
		return $this->post(self::REG_INQUIRY, $post_data);
	}

	//お問い合わせ登録
	public function regInquiryNoMember($param){
		$post_data = array_merge($this->post_data_base, array(
			'sid' => $param['sid'],		// サービスID
			'ctg' => $param['ctg'],		//カテゴリコード
			'sbj' => $param['sbj'],		//件名
			'qtn' => $param['qtn'],		//問い合わせ内容
			'unm' => $param['unm'],		//氏名
			'mla' => $param['mla'],		//メールアドレス
			'tel' => $param['tel'],		//電話番号
		));
		return $this->post(self::REG_INQUIRY_NOMEMBER, $post_data);
	}


	// 請求情報一覧照会
	public function getListDemand($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'dpm' => $param['dpm'],		// 過去何ヶ月分の請求情報を表示するか指定する(月数)
		));
		return $this->post(self::GET_LIST_DEMAND, $post_data);
	}

	// 請求情報一覧照会(都度課金)
	public function getAccountDemandList($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'dpm' => $param['dpm'],		// 過去何ヶ月分の請求情報を表示するか指定する(月数)
		));
		return $this->post(self::GET_ACCOUNT_DEMAND_LIST, $post_data);
	}

	// サービス利用状態一覧照会
	public function getListServiceState($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// サービスID
			'tkn' => $param['tkn'],		// カテゴリコード
			'myk' => $param['myk'],		//  0:無料　1:有料
		));
		return $this->post(self::GET_LIST_SERVICE_STATE_RESOURCE, $post_data);
	}

	// 無料サービス登録・停止
	public function regFreeService($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
			'cid' => $param['cid'],		// ○ 商品ID
			'ttk' => $param['ttk'],		// ○ 登録停止区分（0:停止　1:登録）
		));
		return $this->post(self::REG_FREE_SERVICE_RESOURCE, $post_data);
	}

	// 購読者サービス登録
	public function regReaderServiceJoin($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
			'cid' => $param['cid'],		// ○ 商品ID
		));
		return $this->post(self::REG_READER_SERVICE_JOIN, $post_data);
	}

	// 購読者サービス停止
	public function regReaderServiceDisjoin($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
			'cid' => $param['cid'],		// ○ 商品ID
		));
		return $this->post(self::REG_READER_SERVICE_DISJOIN, $post_data);
	}

	// 都度課金登録
	public function regAccount($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
			'trm' => $param['trm'],		// ○ 決済モード
			'cno' => $param['cno'],		// △ カード番号
			'cfp' => $param['cfp'],		// △ カード有効期限
			'scd' => $param['scd'],		// △ セキュリティコード
			'stk' => $param['stk'],		// △ 決済用トークン
		));
		if(!empty($param['account_item'])){
			foreach ($param['account_item'] as $key => $value) {
				$post_data[] = array(
					'cid' => $value['cid'],		// ○商品ID
					'cnt' => $value['cnt'],		// ○個数
					'rsd' => $value['rsd'],		// △購読開始日
				);
			}
		}else{
			$post_data = array_merge($post_data,array(
				'cid' => $param['cid'],		// ○ 商品ID
				'cnt' => $param['cnt'],		// ○ 個数
				'rsd' => $param['rsd'],		// ○ 購読開始日
			));
		}
		return $this->post(self::REG_ACCOUNT, $post_data);
	}

	// 都度課金照会
	public function getAccount($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
			'cid' => $param['cid'],		// ○ 商品ID
			'smd' => $param['smd'],		// ○ 照会モード（1:全ての都度課金情報　2:サービスとして有効な都度課金情報）
		));
		return $this->post(self::GET_ACCOUNT, $post_data);
	}

	// 都度課金照会
	public function getListAccount($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'aym' => $param['aym'],		// ○ 照会モード（1:全ての都度課金情報　2:サービスとして有効な都度課金情報）
		));
		return $this->post(self::GET_LIST_ACCOUNT, $post_data);
	}

	// 支払方法変更予約
	public function regPaymentMethodChange($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
			'cid' => $param['cid'],		// ○ 商品ID
			'pdi' => $param['pdi'],		// ○ 支払区分
			'pdi_new' => $param['pdi_new'],	// ○ 支払区分（変更後）
			'psd' => $param['psd'],		// △ 支払い方法変更予定日
		));
		return $this->post(self::REG_PAYMENT_METHOD_CHANGE, $post_data);
	}

	// 支払方法変更予約照会
	public function getPaymentMethodChange($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
			'cid' => $param['cid'],		// ○ 商品ID
		));
		return $this->post(self::GET_PAYMENT_METHOD_CHANGE, $post_data);
	}

	// 支払方法変更予約取消
	public function canPaymentMethodChange($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
			'cid' => $param['cid'],		// ○ 商品ID
		));
		return $this->post(self::CAN_PAYMENT_METHOD_CHANGE, $post_data);
	}


	// 家族会員登録申請
	public function regFamilyAccept($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'uns' => $param['uns'],		// ○ 氏名（姓）
			'unn' => $param['unn'],		// ○ 氏名（名）
			'uks' => $param['uks'],		// ○ かな（姓）
			'ukn' => $param['ukn'],		// ○ かな（名）
			'mla' => mb_strtolower($param['mla']),		// ○ メールアドレス(小文字変換)
		));
		return $this->post(self::REG_FAMILY_ACCEPT, $post_data);
	}

	// 家族会員登録申請照会
	public function getFamilyAccept($param){
		$post_data = array_merge($this->post_data_base, array(
			'urn' => $param['urn'],		// ○ 受付番号
			'ops' => $param['ops'],		// ○ ワンタイムパスワード
		));
		return $this->post(self::GET_FAMILY_ACCEPT, $post_data);
	}

	// 家族会員登録
	public function regFamily($param){
		$post_data = array_merge($this->post_data_base, array(
			'adi' => $param['adi'],		// ○認証区分（ 1:会員ＩＤ認証　2:メールアドレス認証 ）
			'uid' => $param['uid'],		// △会員ID（ ※認証区分が"1"の場合、必須。）
			'uns' => $param['uns'],		// △姓（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'unn' => $param['unn'],		// △名（認証区分が"2"の場合、必須。UTF-8形式にてURLエンコードし設定すること）
			'uks' => $param['uks'],		// △カナ姓
			'ukn' => $param['ukn'],		// △カナ名
			'tl1' => $param['tl1'],		// △電話番号
			'tl2' => $param['tl2'],		// △電話番号
			'tl3' => $param['tl3'],		// △電話番号
			'mla' => mb_strtolower($param['mla']),		// ○メールアドレス(小文字変換)
			'sex' => $param['sex'],		// △性別コード
			'btd' => $param['btd'],		// △生年月日
			'job' => $param['job'],		// △業種コード
			'nnm' => $param['nnm'],		// △ニックネーム
			'ups' => $param['ups'],		// ○会員パスワード
			'bid' => $param['bid'],		// △ぶんぶんクラブ会員番号
			'mrv' => $param['mrv'],		// △メール配信希望
			'mlf' => $param['mlf'],		// △メルマガ配信フラグ
			'usl' => $param['usl'],		// △会員別同時ログイン数
			'oid' => $param['oid'],		// △親会員ID
			'rmm' => $param['rmm'],		// △備考メモ
			'sid' => $param['sid'],		// ○サービスID
			'cid' => $param['cid'],		// ○商品ID
			'aml' => $param['aml'],		// △追加メールアドレス
		));
		return $this->post(self::REG_FAMILY, $post_data);
	}

	// 家族会員変更承認
	public function chgFamily($param){
		$post_data = array_merge($this->post_data_base, array(
			'urn' => $param['urn'],		// ○ 受付番号
		));
		return $this->post(self::CHG_FAMILY, $post_data);
	}

	// 家族会員情報一覧照会
	public function getFamilyList($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
		));
		return $this->post(self::GET_FAMILY_LIST, $post_data);
	}

	// 家族会員登録申請状況一覧照会
	public function getFamilyAcceptList($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
		));
		return $this->post(self::GET_FAMILY_ACCEPT_LIST, $post_data);
	}

	// 家族会員登録申請削除
	public function delFamilyAccept($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'urn' => $param['urn'],		// ○ 受付番号
		));
		return $this->post(self::DEL_FAMILY_ACCEPT, $post_data);
	}

	// 家族会員削除
	public function delFamily($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'fid' => $param['fid'],		// ○ 家族会員ID
		));
		return $this->post(self::DEL_FAMILY, $post_data);
	}

	// 家族会員独立申請
	public function regFamilyIndependentReserve($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'nid' => $param['nid'],		// ○ 対象会員ＩＤ
			'fi1' => $param['fi1'],		// △ 付随家族会員ＩＤ1
			'fi2' => $param['fi2'],		// △ 付随家族会員ＩＤ2
			'fi3' => $param['fi3'],		// △ 付随家族会員ＩＤ3
			'fi4' => $param['fi4'],		// △ 付随家族会員ＩＤ4
			'fi5' => $param['fi5'],		// △ 付随家族会員ＩＤ5
			'ps1' => $param['ps1'],		// ○ 郵便番号（親）
			'ps2' => $param['ps2'],		// ○ 郵便番号（枝）
			'prf' => $param['prf'],		// ○ 都道府県コード
			'ad1' => $param['ad1'],		// ○ 住所1
			'ad2' => $param['ad2'],		// ○ 住所2
		));
		return $this->post(self::REG_FAMILY_INDEPENDENT_RESERVE, $post_data);
	}

	// 購読者会員変更申請
	public function regFamilyChangeReaderReserve($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'nid' => $param['nid'],		// ○ 対象会員ＩＤ
		));
		return $this->post(self::REG_FAMILY_CHANGE_READER_RESERVE, $post_data);
	}

	public function getFamilyMemberDisjoin($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'kid' => $param['kid'],		// ○ 対象会員ＩＤ
		));
		return $this->post(self::GET_FAMILY_MEMBER_DISJOIN, $post_data);
	}

	public function regFamilyMemberDisjoin($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'kid' => $param['kid'],		// ○ 対象会員ＩＤ
			'dsd' => $param['dsd'],		// ○ 退会予定日
		));
		return $this->post(self::REG_FAMILY_MEMBER_DISJOIN, $post_data);
	}

	public function canFamilyMemberDisjoin($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'kid' => $param['kid'],		// ○ 対象会員ＩＤ
		));
		return $this->post(self::CAN_FAMILY_MEMBER_DISJOIN, $post_data);
	}

	public function getFamilyContractDisjoin($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'kid' => $param['kid'],		// ○ 対象会員ＩＤ
			'sid' => $param['sid'],		// ○ サービスID
		));
		return $this->post(self::GET_FAMILY_CONTRACT_DISJOIN, $post_data);
	}

	public function regFamilyContractDisjoin($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'kid' => $param['kid'],		// ○ 対象会員ＩＤ
			'sid' => $param['sid'],		// ○ サービスＩＤ
			'cid' => $param['cid'],		// ○ 商品ＩＤ
			'dsd' => $param['dsd'],		// ○ 退会予定日
		));
		return $this->post(self::REG_FAMILY_CONTRACT_DISJOIN, $post_data);
	}

	public function canFamilyContractDisjoin($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ID
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'kid' => $param['kid'],		// ○ 対象会員ＩＤ
			'sid' => $param['sid'],		// ○ サービスＩＤ
		));
		return $this->post(self::CAN_FAMILY_CONTRACT_DISJOIN, $post_data);
	}

	public function getAddressCheck($param){
		$post_data = array_merge($this->post_data_base, array(
			'urn' => $param['urn'],		// ○ 受付番号
		));
		return $this->post(self::GET_ADDRESS_CHECK, $post_data);
	}

	public function getAddressCheckResult($param){
		$post_data = array_merge($this->post_data_base, array(
			'urn' => $param['urn'],		// ○ 受付番号
		));
		return $this->post(self::REG_ADDRESS_CHECK_RESULT, $post_data);
	}

	// 有料サービス登録 (年額)
	public function regContractYearJoin($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// ○ 会員ＩＤ
			'tkn' => $param['tkn'],		// ○ アクセストークン
			'sid' => $param['sid'],		// ○ サービスID
			'cid' => $param['cid'],		// ○ 商品ID
			'pdi' => $param['pdi'],		// △ 支払い区分
			'cpr1' => $param['cpr1'],		// △ 汎用コード
			'cpr2' => $param['cpr2'],		// △ 大学名
			'cpr3' => $param['cpr3'],		// △ プロファイル3
			'cpr4' => $param['cpr4'],		// △ プロファイル4
			'cpr5' => $param['cpr5'],		// △ プロファイル5
			'cpr6' => $param['cpr6'],		// △ プロファイル6
			'cpr7' => $param['cpr7'],		// △ プロファイル7
			'cpr8' => $param['cpr8'],		// △ プロファイル8
			'cpr9' => $param['cpr9'],		// △ プロファイル9
			'cpr10' => $param['cpr10'],		// △ プロファイル10
		));
		return $this->post(self::REG_CONTRACT_YEAR_JOIN, $post_data);
	}

	// 年額課金契約照会
	public function getContractYear($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'sid' => $param['sid'],		// サービスID
			'cid' => $param['cid'],		// 商品ID
		));
		return $this->post(self::GET_CONTRACT_YEAR_RESOURCE, $post_data);
	}

	// 年額課金契約一覧照会
	public function getListContractYear($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'smd' => $param['smd'],	// 照会モード(1:全ての月額課金契約(退会含む) 2:入会中の月額課金契約)
			'fgct' => $param['fgct'], //契約照会区分  0:会員自身の契約、1:親会員の契約、2:会員自身の契約＋親会員の契約
		));
		return $this->post(self::LIST_CONTRACT_YEAR_RESOURCE, $post_data);
	}

	//年額課金契約退会予定登録
	public function regContractYearDisjoinRes($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'sid' => $param['sid'],		// サービスID
			'cid' => $param['cid'],		// 商品ID
			'dsd' => $param['dsd'],		// 退会予定日
		));
		return $this->post(self::REG_CONTRACT_YEAR_DISJOIN_RES, $post_data);
	}

	//年額課金契約退会予定照会
	public function getContractYearDisjoinRes($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'sid' => $param['sid'],		// サービスID
		));
		return $this->post(self::GET_CONTRACT_YEAR_DISJOIN_RES, $post_data);
	}

	//年額課金契約退会予定取消
	public function canContractYearDisjoinRes($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
			'sid' => $param['sid'],		// サービスID
		));
		return $this->post(self::CAN_CONTRACT_YEAR_DISJOIN_RES, $post_data);
	}

	//会員属性情報更新（現読確認用）
	public function updAttributeSc($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid'	 => $param['uid'],		// 会員ID
			'sak'	 => self::SC_AUTH_KEY,		// 現読確認用APIアクセス用認証KEY
			'mla'	 => $param['mla'],		//○メールアドレス
			'ta1'	 => $param['ta1'],		//△会員に対するテキストアディショナル1
			'ta2'	 => $param['ta2'],		//△会員に対するテキストアディショナル2
			'ta3'	 => $param['ta3'],		//△会員に対するテキストアディショナル3
			'ta4'	 => $param['ta4'],		//△会員に対するテキストアディショナル4
			'ta5'	 => $param['ta5'],		//△会員に対するテキストアディショナル5
			'ta6'	 => $param['ta6'],		//△会員に対するテキストアディショナル6
			'ta7'	 => $param['ta7'],		//△会員に対するテキストアディショナル7
			'ta8'	 => $param['ta8'],		//△会員に対するテキストアディショナル8
			'ta9'	 => $param['ta9'],		//△会員に対するテキストアディショナル9
			'ta10'	 => $param['ta10'],		//△会員に対するテキストアディショナル10
			'ta11'	 => $param['ta11'],		//△会員に対するテキストアディショナル11
			'ta12'	 => $param['ta12'],		//△会員に対するテキストアディショナル12
			'ta13'	 => $param['ta13'],		//△会員に対するテキストアディショナル13
			'ta14'	 => $param['ta14'],		//△会員に対するテキストアディショナル14
			'ta15'	 => $param['ta15'],		//△会員に対するテキストアディショナル15
			'ta16'	 => $param['ta16'],		//△会員に対するテキストアディショナル16
			'ta17'	 => $param['ta17'],		//△会員に対するテキストアディショナル17
			'ta18'	 => $param['ta18'],		//△会員に対するテキストアディショナル18
			'ta19'	 => $param['ta19'],		//△会員に対するテキストアディショナル19
			'ta20'	 => $param['ta20'],		//△会員に対するテキストアディショナル20
			'na1'	 => $param['na1'],		//△会員に対するナンバーアディショナル1
			'na2'	 => $param['na2'],		//△会員に対するナンバーアディショナル2
			'na3'	 => $param['na3'],		//△会員に対するナンバーアディショナル3
			'na4'	 => $param['na4'],		//△会員に対するナンバーアディショナル4
			'na5'	 => $param['na5'],		//△会員に対するナンバーアディショナル5
			'na6'	 => $param['na6'],		//△会員に対するナンバーアディショナル6
			'na7'	 => $param['na7'],		//△会員に対するナンバーアディショナル7
			'na8'	 => $param['na8'],		//△会員に対するナンバーアディショナル8
			'na9'	 => $param['na9'],		//△会員に対するナンバーアディショナル9
			'na10'	 => $param['na10'],		//△会員に対するナンバーアディショナル10
			'na11'	 => $param['na11'],		//△会員に対するナンバーアディショナル11
			'na12'	 => $param['na12'],		//△会員に対するナンバーアディショナル12
			'na13'	 => $param['na13'],		//△会員に対するナンバーアディショナル13
			'na14'	 => $param['na14'],		//△会員に対するナンバーアディショナル14
			'na15'	 => $param['na15'],		//△会員に対するナンバーアディショナル15
			'na16'	 => $param['na16'],		//△会員に対するナンバーアディショナル16
			'na17'	 => $param['na17'],		//△会員に対するナンバーアディショナル17
			'na18'	 => $param['na18'],		//△会員に対するナンバーアディショナル18
			'na19'	 => $param['na19'],		//△会員に対するナンバーアディショナル19
			'na20'	 => $param['na20'],		//△会員に対するナンバーアディショナル20
		));
		return $this->post(self::UPD_ATTRIBUTE_SC, $post_data);
	}

	//会員属性情報更新
	public function updAttribute($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid'	 => $param['uid'],		// 会員ID
			'tkn'	 => $param['tkn'],		// アクセストークン
			'ta1'	 => $param['ta1'],		//△会員に対するテキストアディショナル1
			'ta2'	 => $param['ta2'],		//△会員に対するテキストアディショナル2
			'ta3'	 => $param['ta3'],		//△会員に対するテキストアディショナル3
			'ta4'	 => $param['ta4'],		//△会員に対するテキストアディショナル4
			'ta5'	 => $param['ta5'],		//△会員に対するテキストアディショナル5
			'ta6'	 => $param['ta6'],		//△会員に対するテキストアディショナル6
			'ta7'	 => $param['ta7'],		//△会員に対するテキストアディショナル7
			'ta8'	 => $param['ta8'],		//△会員に対するテキストアディショナル8
			'ta9'	 => $param['ta9'],		//△会員に対するテキストアディショナル9
			'ta10'	 => $param['ta10'],		//△会員に対するテキストアディショナル10
			'ta11'	 => $param['ta11'],		//△会員に対するテキストアディショナル11
			'ta12'	 => $param['ta12'],		//△会員に対するテキストアディショナル12
			'ta13'	 => $param['ta13'],		//△会員に対するテキストアディショナル13
			'ta14'	 => $param['ta14'],		//△会員に対するテキストアディショナル14
			'ta15'	 => $param['ta15'],		//△会員に対するテキストアディショナル15
			'ta16'	 => $param['ta16'],		//△会員に対するテキストアディショナル16
			'ta17'	 => $param['ta17'],		//△会員に対するテキストアディショナル17
			'ta18'	 => $param['ta18'],		//△会員に対するテキストアディショナル18
			'ta19'	 => $param['ta19'],		//△会員に対するテキストアディショナル19
			'ta20'	 => $param['ta20'],		//△会員に対するテキストアディショナル20
			'na1'	 => $param['na1'],		//△会員に対するナンバーアディショナル1
			'na2'	 => $param['na2'],		//△会員に対するナンバーアディショナル2
			'na3'	 => $param['na3'],		//△会員に対するナンバーアディショナル3
			'na4'	 => $param['na4'],		//△会員に対するナンバーアディショナル4
			'na5'	 => $param['na5'],		//△会員に対するナンバーアディショナル5
			'na6'	 => $param['na6'],		//△会員に対するナンバーアディショナル6
			'na7'	 => $param['na7'],		//△会員に対するナンバーアディショナル7
			'na8'	 => $param['na8'],		//△会員に対するナンバーアディショナル8
			'na9'	 => $param['na9'],		//△会員に対するナンバーアディショナル9
			'na10'	 => $param['na10'],		//△会員に対するナンバーアディショナル10
			'na11'	 => $param['na11'],		//△会員に対するナンバーアディショナル11
			'na12'	 => $param['na12'],		//△会員に対するナンバーアディショナル12
			'na13'	 => $param['na13'],		//△会員に対するナンバーアディショナル13
			'na14'	 => $param['na14'],		//△会員に対するナンバーアディショナル14
			'na15'	 => $param['na15'],		//△会員に対するナンバーアディショナル15
			'na16'	 => $param['na16'],		//△会員に対するナンバーアディショナル16
			'na17'	 => $param['na17'],		//△会員に対するナンバーアディショナル17
			'na18'	 => $param['na18'],		//△会員に対するナンバーアディショナル18
			'na19'	 => $param['na19'],		//△会員に対するナンバーアディショナル19
			'na20'	 => $param['na20'],		//△会員に対するナンバーアディショナル20
		));
		return $this->post(self::UPD_ATTRIBUTE, $post_data);
	}


	public function getAttribute($param){
		$post_data = array_merge($this->post_data_base, array(
			'uid' => $param['uid'],		// 会員ID
			'tkn' => $param['tkn'],		// アクセストークン
		));
		return $this->post(self::GET_ATTRIBUTE, $post_data);
	}
}

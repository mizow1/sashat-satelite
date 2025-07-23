<?php
class KyodoMaster{
	const SERVICE_KEY = 'hd001';
	const SERVICE_KEY_SPORTS = 'hm001';
	const SERVICE_KEY_ACCOUNT = 'hde11';
	const SERVICE_KEY_ACCOUNT_PAPER = 'hep01';
	const SERVICE_KEY_PAPER_VIEWER = 'hv001';

	const SERVICE_KEY_DB = 'dba01';//どうしんDB
	const SERVICE_KEY_DB_CLIP = 'dbc01';//どうしんDB クリッピングサービス
	const SERVICE_KEY_SPORTS_DIGITAL = 'dsp01';//道新スポーツデジタル(月額)
	const SERVICE_KEY_SPORTS_DIGITAL_YEAR = 'dspy1';//道新スポーツデジタル(年額) 

	const SERVICE_KEY_YEAR = 'hd0y1';//年額商品


	const COMMODITY_KEY = 'hd001_basic';
	const COMMODITY_KEY_SPORTS = 'hm001_basic';
	const COMMODITY_KEY_ACCOUNT = 'hde11_b';
	const COMMODITY_KEY_TANDOKU = 'hd001_tandoku';
	const COMMODITY_KEY_TANDOKU_OVERSEAS = 'hd001_tandoku_overseas';
	const COMMODITY_KEY_PAPER_H03 = 'hep01_h03';
	const COMMODITY_KEY_PAPER_H06 = 'hep01_h06';
	const COMMODITY_KEY_PAPER_S03 = 'hep01_s03';
	const COMMODITY_KEY_PAPER_S06 = 'hep01_s06';
	const COMMODITY_KEY_PAPER_VIEWER = 'hv001_chiiki';
	const COMMODITY_KEY_STUDENT = 'hd001_student';

	const COMMODITY_KEY_DB_S = 'dba01_s';//どうしんDB（従量制基本プラン） 法人も共通
	const COMMODITY_KEY_DB_L = 'dba01_l';//どうしんDB（使い放題基本プラン） 法人も共通
	const COMMODITY_KEY_DB_CLIP_3 = 'dbc01_3';//どうしんDB_法人（クリッピングサービス_3メールアドレスプラン）
	const COMMODITY_KEY_DB_CLIP_5 = 'dbc01_5';//どうしんDB_法人（クリッピングサービス_5メールアドレスプラン）
	const COMMODITY_KEY_DB_CLIP_10 = 'dbc01_10';//どうしんDB_法人（クリッピングサービス_10メールアドレスプラン）

	const COMMODITY_KEY_SPORTS_DIGITAL_BASIC = 'dsp01_basic';//道新スポーツデジタル（基本プラン_月額）
	const COMMODITY_KEY_SPORTS_DIGITAL_DISCOUNT = 'dsp01_discount';//道新スポーツデジタル（値引きプラン_月額）
	const COMMODITY_KEY_SPORTS_DIGITAL_YEAR_BASIC = 'dspy1_basic';//道新スポーツデジタル（基本プラン_年額）
	const COMMODITY_KEY_SPORTS_DIGITAL_YEAR_DISCOUNT = 'dspy1_discount';//道新スポーツデジタル（値引きプラン_年額）

	const COMMODITY_KEY_DIGITAL = 'hd001_standard';//スタンダード（デジタル）プラン 月額
	const COMMODITY_KEY_DIGITAL_OVERSEAS = 'hd001_standard_overseas';//スタンダード（デジタル）プラン・海外 月額
	const COMMODITY_KEY_DIGITAL_DISCOUNT = 'hd001_young';//若年層割プラン
	const COMMODITY_KEY_DIGITAL_TRIAL = 'hd001_free';//おためしプラン

	const COMMODITY_KEY_DIGITAL_YEAR = 'hd0y1_standard';//スタンダード（デジタル）プラン 年額
	const COMMODITY_KEY_DIGITAL_YEAR_OVERSEAS = 'hd0y1_standard_overseas';//スタンダード（デジタル）プラン・海外 年額
	const COMMODITY_KEY_TANDOKU_YEAR = 'hd0y1_tandoku';//スタンダード（デジタル）プラン 年額
	const COMMODITY_KEY_TANDOKU_YEAR_OVERSEAS = 'hd0y1_tandoku_overseas';//スタンダード（デジタル）プラン・海外 年額



	const DMJ_DENSHI_ONLY = 0;
	const DMJ_DENSHI = 1;
	const DMJ_PAPER = 2;
	const DMJ_TRIAL = 3;
	const DMJ_FREE = 4;
	const DMJ_DOGAI = 5;
	const DMJ_DOGAI_NOTAX = 6;
	const DMJ_DENSHI_STUDENT = 7;
	const DMJ_CORP = 8;
	const DMJ_CORP_NOTAX = 9;

	const DMJ_CORP_DENSHI = 10;
	const DMJ_CORP_PAPER = 11;
//	const DMJ_CORP_PAPER = 12;
	const DMJ_PAPER_DOGAI_CREDIT = 13;
	const DMJ_CORP_PAPER_DOGAI_CREDIT= 14;
	const DMJ_PAPER_DOGAI_SHOP = 15;
	const DMJ_CORP_PAPER_DOGAI_SHOP = 16;
	const DMJ_PAPER_DOGAI_BANK = 17;
	const DMJ_CORP_PAPER_DOGAI_BANK = 18;

	const DMJ_DOGAI_YEAR = 20;
	const DMJ_DOGAI_YEAR_NOTAX = 21;
	const DMJ_DIGITAL = 22;
	const DMJ_CORP_DIGITAL = 23;
	const DMJ_DIGITAL_NOTAX = 24;
	const DMJ_CORP_DIGITAL_NOTAX = 25;
	const DMJ_DIGITAL_DISCOUNT = 26;
	const DMJ_DIGITAL_TRIAL = 27;
	const DMJ_DIGITAL_YEAR = 28;
	const DMJ_DIGITAL_YEAR_NOTAX = 29;
	const DMJ_SP_DIGITAL = 'H1';
	const DMJ_SP_CORP_DIGITAL = 'H2';


	// 会員区分
	const UKB_TEMPORARY = 0; //仮会員
	const UKB_READER = 1;//電子版
	const UKB_COURCE = 2; //単独
	const UKB_CAMPAIN = 3; //未使用
	const UKB_CORP = 4; //法人
	const UKB_FREE = 5;//パスポート

	//会員種別
	const UST_PARENT = 0;//親会員
	const UST_CHILD = 1;//子会員

	//  メルマガ配信フラグ mlf △ テキスト 会員の各種メールマガジン配信希望有無　
	// {0,1}を区切り文字で連結した文字列 0:受け取らない　1:受け取る
	const DEFAULT_MAIL_SERVICE_MAX_NUMBER = 50; // 初期のメルマガ数に関わらず予め50個用意


	// サービスマスタ
	private $service_list = array(
		self::SERVICE_KEY => array(
			'service_key'	 => self::SERVICE_KEY,
			'name'		 => '北海道新聞デジタル',
		),
		self::SERVICE_KEY_SPORTS => array(
			'service_key'	 => self::SERVICE_KEY_SPORTS,
			'name'		 => 'メガスポ',
		),
		self::SERVICE_KEY_ACCOUNT => array(
			'service_key'	 => self::SERVICE_KEY_ACCOUNT,
			'name'		 => '都度テスト1',
		),
		self::SERVICE_KEY_PAPER_VIEWER => array(
			'service_key'	 => self::SERVICE_KEY_PAPER_VIEWER,
			'name'		 => '紙面ビューア',
		),

	);

	// 商品マスタ
	private $commodity_list = array(
		self::COMMODITY_KEY => array(
			'cid'		 => self::COMMODITY_KEY,
			'service_key'	 => self::SERVICE_KEY,
			'name'		 => '基本プラン',
		),
		self::COMMODITY_KEY_SPORTS => array(
			'cid'		 => self::COMMODITY_KEY_SPORTS,
			'service_key'	 => self::SERVICE_KEY_SPORTS,
			'name'		 => 'メガスポ',
			'price'		 => 200,
			'price_tax'	 => 216,
			'tax'		 => 16,
		),
		self::COMMODITY_KEY_ACCOUNT => array(
			'cid'		 => self::COMMODITY_KEY_ACCOUNT,
			'service_key'	 => self::SERVICE_KEY_ACCOUNT,
			'name'		 => '都度テスト',
			'price'		 => 278,
			'price_tax'	 => 300,
			'tax'		 => 22,
		),
		self::COMMODITY_KEY_PAPER_VIEWER => array(
			'cid'		 => self::COMMODITY_KEY_PAPER_VIEWER,
			'service_key'	 => self::SERVICE_KEY_PAPER_VIEWER,
			'name'		 => '地域面',
			'price'		 => 500,
			'price_tax'	 => 550,
			'tax'		 => 50,
		),
		self::COMMODITY_KEY_TANDOKU => array(
			'cid'		 => self::COMMODITY_KEY_TANDOKU,
			'service_key'	 => self::SERVICE_KEY,
			'name'		 => 'ビューアーコース(国内)',
			'price'		 => 3738,
			'price_tax'	 => 4037,
			'tax'		 => 301,
		),
		self::COMMODITY_KEY_TANDOKU_OVERSEAS => array(
			'cid'		 => self::COMMODITY_KEY_TANDOKU_OVERSEAS,
			'service_key'	 => self::SERVICE_KEY,
			'name'		 => 'ビューアーコース(国外)',
			'price'		 => 3738,
			'price_tax'	 => 3738,
			'tax'		 => 0,
		),
	);

	// 会員種別
	private $member_type_list = array(
		'1' => '個人会員',
		'2' => '法人会員',
		'3' => '特殊会員',
		'4' => '無料会員',
	);

	// 都道府県コード
	private $prefecture_list = array(
		'01' => '北海道',
		'02' => '青森県',
		'03' => '岩手県',
		'04' => '宮城県',
		'05' => '秋田県',
		'06' => '山形県',
		'07' => '福島県',
		'08' => '茨城県',
		'09' => '栃木県',
		'10' => '群馬県',
		'11' => '埼玉県',
		'12' => '千葉県',
		'13' => '東京都',
		'14' => '神奈川県',
		'15' => '新潟県',
		'16' => '富山県',
		'17' => '石川県',
		'18' => '福井県',
		'19' => '山梨県',
		'20' => '長野県',
		'21' => '岐阜県',
		'22' => '静岡県',
		'23' => '愛知県',
		'24' => '三重県',
		'25' => '滋賀県',
		'26' => '京都府',
		'27' => '大阪府',
		'28' => '兵庫県',
		'29' => '奈良県',
		'30' => '和歌山県',
		'31' => '鳥取県',
		'32' => '島根県',
		'33' => '岡山県',
		'34' => '広島県',
		'35' => '山口県',
		'36' => '徳島県',
		'37' => '香川県',
		'38' => '愛媛県',
		'39' => '高知県',
		'40' => '福岡県',
		'41' => '佐賀県',
		'42' => '長崎県',
		'43' => '熊本県',
		'44' => '大分県',
		'45' => '宮崎県',
		'46' => '鹿児島県',
		'47' => '沖縄県',
	);

	// 性別コード
	private $gender_list = array(
		'1' => '男性',
		'2' => '女性',
		'3' => 'その他',
	);

	// 業種コード
	private $job_list = array(
		'10' => '会社員・会社役員など',
		'20' => '公務員・団体職員など',
		'21' => '会社役員・経営者',
		'22' => '会社員',
		'23' => '国家公務員',
		'24' => '地方公務員',
		'25' => '団体職員',
		'26' => '随時職・派遣社員・契約社員',
		'30' => '自営業・フリーランス',
		'31' => '農業・漁業など',
		'32' => '医師',
		'33' => '看護師・薬剤師など医療関係',
		'34' => '介護士',
		'35' => '保育士',
		'36' => '教員（小学校・中学校・高校）',
		'37' => '大学教員',
		'40' => 'パート・アルバイト',
		'50' => '専業主婦（夫）',
		'60' => '学生',
		'65' => '定年退職者',
		'70' => '無職',
		'99' => 'その他',
	);

	// 支払い方法
	private $purchase_list = array(
		'01' => 'クレジットカード払い',
		'02' => '銀行振込',
	);

	// メールマガジン種別
	private $mail_service_list = array(
		'F0020C0010' => 'メールマガジン',
	);

	// メールマガジン種別(key)
	private $mail_service_key_list = array(
		'regular_news' => 'F0020C0010',
	);

	// 会員区分一覧
	private $ukb_list = array(
		self::UKB_TEMPORARY	 => '仮登録',
		self::UKB_READER	 => '読者会員',
		self::UKB_COURCE	 => 'ビューアーコース',
		self::UKB_CAMPAIN	 => 'キャンペーン会員',
		self::UKB_CORP		 => '法人会員',
		self::UKB_FREE		 => '無料登録会員',
	);

	//会員種別一覧
	private $ust_list = array(
		self::UST_PARENT	 => '親会員',
		self::UST_CHILD		 => '家族会員',
	);


	private $payment_type_name_list = array(
		1 => 'クレジットカード決済',
		2 => '販売店決済',
		3 => 'キャリア決済',
		4 => '請求書決済',
		5 => 'マスター払い（クレジット）',
		6 => 'マスター払い（請求書）',
		9 => '無課金',
	);

	//お問い合わせ用カテゴリコード
	private $inquiry_category_member = array(
		'10050' => 'サービス購入・お支払・解約について',
		'10010' => '会員の登録・解除について',
		'10020' => '操作方法について',
		'10030' => 'ご意見・ご要望',
		'10040' => 'その他',
	);
	private $inquiry_category_nomember = array(
		'20050' => 'サービス購入・お支払・解約について',
		'20010' => '会員の登録・解除について',
		'20020' => '操作方法について',
		'20030' => 'ご意見・ご要望',
		'20040' => 'その他',
	);

	//料金種別
	private $charge_category_list = array(
		'10110' => '月額料金',
		'10210' => '一時金',
		'10310' => '都度課金',
		'10315' => '年額料金',
		'10410' => '料金調整',
		'20110' => '未収額',
	);

	// 道新独自項目群
	private $subscribe_paper = array(
		'1' => '北海道新聞(朝刊)',
//		'2' => '道新スポーツ',
	);

	private $subscribe_paper_transfer = array(
		'201'=>'北海道新聞【札幌版】(朝刊)',
		'202'=>'北海道新聞【函館版】(朝刊)',
		'203'=>'北海道新聞【旭川版】(朝刊)',
		'204'=>'北海道新聞【釧路版】(朝刊)',
		'205'=>'北海道新聞【帯広版】(朝刊)',
		'206'=>'北海道新聞【小樽版】(朝刊)',
		'207'=>'北海道新聞【室蘭版】(朝刊)',
		'208'=>'北海道新聞【苫小牧版】(朝刊)',
		'209'=>'北海道新聞【北見版】(朝刊)',
	);

	private $subscribe_paper_credit = array(
		'301'=>'北海道新聞【札幌版】(朝刊)3カ月',
		'302'=>'北海道新聞【札幌版】(朝刊)6カ月',
		'303'=>'北海道新聞【函館版】(朝刊)3カ月',
		'304'=>'北海道新聞【函館版】(朝刊)6カ月',
		'305'=>'北海道新聞【旭川版】(朝刊)3カ月',
		'306'=>'北海道新聞【旭川版】(朝刊)6カ月',
		'307'=>'北海道新聞【釧路版】(朝刊)3カ月',
		'308'=>'北海道新聞【釧路版】(朝刊)6カ月',
		'309'=>'北海道新聞【帯広版】(朝刊)3カ月',
		'310'=>'北海道新聞【帯広版】(朝刊)6カ月',
		'311'=>'北海道新聞【小樽版】(朝刊)3カ月',
		'312'=>'北海道新聞【小樽版】(朝刊)6カ月',
		'313'=>'北海道新聞【室蘭版】(朝刊)3カ月',
		'314'=>'北海道新聞【室蘭版】(朝刊)6カ月',
		'315'=>'北海道新聞【苫小牧版】(朝刊)3カ月',
		'316'=>'北海道新聞【苫小牧版】(朝刊)6カ月',
		'317'=>'北海道新聞【北見版】(朝刊)3カ月',
		'318'=>'北海道新聞【北見版】(朝刊)6カ月',
	);

	private $subscribe_sports_transfer = array(
		'401'=>'購読する',
	);

	private $subscribe_sports_credit = array(
		'501'=>'道新スポーツ3カ月',
		'502'=>'道新スポーツ6カ月',
	);



	private $subscribe_period = array(
		'1' => '6ヶ月未満',
		'2' => '6ヶ月以上',
	);

	private $already_paper = array(
		'1' => '読売新聞',
		'2' => '朝日新聞',
		'3' => '毎日新聞',
		'4' => '日本経済新聞',
		'5' => 'その他',
		'6' => '現在は購読していない',
	);

	private $already_paper_period = array(
		'1' => '3ヶ月未満',
		'2' => '6ヶ月未満',
		'3' => '1年未満',
		'4' => '1年以上',
	);
	//購読のきっかけ
	private $trigger_list = array(
		1	 => 'ニュースレターを見て',
		2	 => '道新アプリを見て',
		3	 => 'チラシを見て',
		4	 => '新聞販売所から勧められて',
		5	 => 'web広告を見て',
		6	 => 'twitterなどSNSを見て',
		7	 => '北海道新聞社からのメールを見て',
		8	 => 'オンラインイベントに参加したくて',
		9	 => 'テレビCMを見て',
		10	 => '知人に勧められて',
		11	 => 'その他',
	);


	public function __construct(){
	}

	public function getServiceId(){
		return self::SERVICE_KEY;
	}

	public function getCommodityId(){
		return self::COMMODITY_KEY;
	}

	public function getServiceList(){
		return $this->service_list;
	}

	public function getCommodityList(){
		return $this->commodity_list;
	}

	public function getCommodity($commodity_key){
		if(empty($commodity_key)){
			return array();
		}
		return $this->commodity_list[$commodity_key];
	}

	public function getPaymentTypeNameList(){
		return $this->payment_type_name_list;
	}

	public function getMemberTypeList(){
		return $this->member_type_list;
	}

	public function getPrefectureList(){
		return $this->prefecture_list;
	}

	public function getGenderList(){
		return $this->gender_list;
	}

	public function getJobList(){
		//不要な職業を除外してreturn
		$job_list = $this->job_list;
		unset($job_list[10]);
		unset($job_list[20]);
		return $job_list;
	}

	public function getJobListAll(){
		return $this->job_list;
	}

	public function getPurchaseList(){
		return $this->purchase_list;
	}


	public function getMailServiceList(){
		return $this->mail_service_list;
	}

	public function getMailServiceKeyList(){
		return $this->mail_service_key_list;
	}

	public function getTrainLineList(){
		return $this->train_line_list;
	}

	public function getTrainLineKeyList(){
		return $this->train_line_key_list;
	}

	public function getInquiryCategoryMember(){
		return $this->inquiry_category_member;
	}

	public function getInquiryCategoryNoMember(){
		return $this->inquiry_category_nomember;
	}

	public function getChargeCategoryList(){
		return $this->charge_category_list;
	}

	public function getStartSubscribeList(){
		return array(
			'year' => range(date('Y'),date('Y')+1),
			'month' => range(1, 12),
			'day' => range(1, 31),
		);
	}

	public function getSubscribePaper(){
		return $this->subscribe_paper;
	}

	public function getSubscribePaperTransfer(){
		return $this->subscribe_paper_transfer;
	}

	public function getSubscribePaperCredit(){
		return $this->subscribe_paper_credit;
	}

	public function getSubscribeSportsTransfer(){
		return $this->subscribe_sports_transfer;
	}

	public function getSubscribeSportsCredit(){
		return $this->subscribe_sports_credit;
	}


	public function getSubscribePeriod(){
		return $this->subscribe_period;
	}

	public function getAlreadyPaper(){
		return $this->already_paper;
	}

	public function getAlreadyPaperPeriod(){
		return $this->already_paper_period;
	}

	public function getTriggerList(){
		return $this->trigger_list;
	}

	public function isPassportDmj($dmj){
		return self::DMJ_FREE == $dmj;
	}

	public function isTrialDmj($dmj){
		return self::DMJ_TRIAL == $dmj;
	}

	public function isDenshiDmj($dmj){
		return self::DMJ_DENSHI_ONLY == $dmj || self::DMJ_DENSHI == $dmj ;
	}

	public function isCourceDmj($dmj){
		return self::DMJ_DOGAI == $dmj || self::DMJ_DOGAI_NOTAX == $dmj ;
	}

	public function isDenshiStudentDmj($dmj){
		return self::DMJ_DENSHI_STUDENT == $dmj;
	}

	public function isCorpDmj($dmj){
		return self::DMJ_CORP == $dmj || self::DMJ_CORP_NOTAX == $dmj ;
	}

	public function isCorpDenshiDmj($dmj){
		return self::DMJ_CORP_DENSHI == $dmj;
	}

	public function isCorpPaperDmj($dmj){
		return self::DMJ_CORP_PAPER == $dmj;
	}

	public function isDigitalDmj($dmj){
		return self::DMJ_DIGITAL == $dmj || self::DMJ_DIGITAL_NOTAX == $dmj;
	}

	public function isDigitalDiscountDmj($dmj){
		return self::DMJ_DIGITAL_DISCOUNT == $dmj;
	}

	public function isDigitalTrialDmj($dmj){
		return self::DMJ_DIGITAL_TRIAL == $dmj;
	}

	public function isDigitalYearDmj($dmj){
		return self::DMJ_DIGITAL_YEAR == $dmj || self::DMJ_DIGITAL_YEAR_NOTAX == $dmj;
	}

	public function isDogaiYearDmj($dmj){
		return self::DMJ_DOGAI_YEAR == $dmj || self::DMJ_DOGAI_YEAR_NOTAX == $dmj;
	}

	public function isSpDigitalDmj($dmj){
		return self::DMJ_SP_DIGITAL == $dmj ;
	}

	public function isCorpDigitalDmj($dmj){
		return self::DMJ_CORP_DIGITAL == $dmj || self::DMJ_CORP_DIGITAL_NOTAX == $dmj;
	}

	public function isSpCorpDigitalDmj($dmj){
		return self::DMJ_SP_CORP_DIGITAL == $dmj ;
	}


	public function isTemporaryMember($ukb){
		return self::UKB_TEMPORARY == $ukb ;
	}

	public function isDenshiMember($ukb){
		return self::UKB_READER == $ukb ;
	}

	public function isCourceMember($ukb){
		return self::UKB_COURCE == $ukb ;
	}

	public function isCampaignMember($ukb){
		return self::UKB_CAMPAIN == $ukb ;
	}

	public function isCorpMember($ukb){
		return self::UKB_CORP == $ukb ;
	}

	public function isPassportMember($ukb){
		return self::UKB_FREE == $ukb ;
	}

	public function isParentMember($ust){
		return self::UST_PARENT == $ust;
	}

	public function isChildMember($ust){
		return self::UST_CHILD == $ust;
	}

	public function getPurchaseCidList(){
		return array(
			PASSPORT_PURCHASE_CID_SPORTS => self::COMMODITY_KEY_SPORTS,
		);
	}

	public function getPaperViewerCidList(){
		return array(
			PASSPORT_PAPER_VIEWER_CID_CHIIKI => self::COMMODITY_KEY_PAPER_VIEWER,
		);
	}
}

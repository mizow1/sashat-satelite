<?php
class YmirlinkApi{
/* 新接続情報2020/02/06 */
	private $url='https://fc6058.cuenote.jp/api/fcio.cgi'; //APIのURL
	private $bid='doshin02_api'; //APIのID
	private $bpw='yD9LYxVp'; //APIのPASS
/* 新接続情報2020/02/06ここまで */
/* 旧接続情報 */
/*
	private $url='https://fc1642.cuenote.jp/api/fcio.cgi'; //APIのURL
	private $bid='doshin02_api'; //APIのID
	private $bpw='ZRQzeq6N'; //APIのPASS
*/
/* 旧接続情報ここまで */
	private $addressbook = ''; //アドレス帳初期化
	private $send_name = "自動送信メール";
	private $send_mail_address = ""; // このメールアドレスからメールを送信する
	private $send_ok_flag = 0; //送信可能フラグ 1:OK 0:NG
	private $test_string = ""; //本番環境以外の送信に付ける文字列
	private $test_ad_string = ""; //本番環境以外の送信に付ける文字列
	public function __construct(){
		switch ($_SERVER[SERVER_ENV_NAME]) {
			case SERVER_ENV_PRODUCTION: //本番サーバーの時
				$this->addressbook = '5e7843a2'; //本番アドレス帳
				$this->test_ad_string = '';
				$this->send_ok_flag = 1;
				break;
			case SERVER_ENV_STAGE: //ステージサーバーの時
				$this->addressbook = '5e703110'; //ステージアドレス帳
				$this->test_string = '[テスト送信]';
				$this->test_ad_string = '[STG]';
				$this->send_ok_flag = 1;
				break;
			default: //テストサーバーの時
				$this->addressbook = '595f2c4c'; //テストアドレス帳
				$this->test_string = '[テスト送信]';
				$this->test_ad_string = '[DEV]';
				$this->send_ok_flag = 1;
				break;
		}
	}
	//API送信
	private function connectApi($command_xml){
		if(
			empty($command_xml) ||
			$this->send_ok_flag === 0
		){
			return array();
		}
		$data = array(
			'CCC' => 'i',
			'xml' => $command_xml
		);
		$options = array(
			'http' => array(
				'ignore_errors' => true,
				'method'  => 'POST',
				'header'  => "Content-Type: text/xml\r\n"."Authorization: Basic ".base64_encode("$this->bid:$this->bpw")."\r\n",
				'content' => http_build_query($data),
			)
		);
		// APIへのPOST送信
		$contents = file_get_contents($this->url, false, stream_context_create($options));
		// レスポンスが200以外は終了
		$pos = strpos($http_response_header[0], '200');
		if ($pos === false) {
			return array();
		}
		return $contents;
	}
	//アドレス帳登録
	public function makeAdBook($adbook_name){
		$adbook_name = $this->test_ad_string.$adbook_name;
		// アドレス帳登録用のXML作成
		$command_xml = '
<forcast>
<execute id="1" command="makeAdBook">
<parameter adbookname="'.$adbook_name.'">
<column tag="flag" tagname="送信フラグ" type="text" />
</parameter>
</execute>
</forcast>
		';
		//API送信
		$contents = $this->connectApi($command_xml);
		if(empty($contents)){
			return false;
		}
		return $contents;
	}
	//アドレス帳変更
	public function changeAdBook($adbook_name,$adbook_id){
		$adbook_name = $this->test_ad_string.$adbook_name;
		// アドレス帳変更用のXML作成
		$command_xml = '
<forcast>
<execute id="1" command="changeAdBook">
<parameter adbook="'.$adbook_id.'" adbookname="'.$adbook_name.'" />
<column tag="flag" tagname="送信フラグ" type="text" />
</execute>
</forcast>
		';
		//API送信
		$contents = $this->connectApi($command_xml);
		if(empty($contents)){
			return false;
		}
		return $contents;
	}
	//アドレス帳削除
	public function deleteAdBook($adbook_id){
		// アドレス帳削除用のXML作成
		$command_xml = '
<forcast>
<execute id="1" command="deleteAdBook">
<parameter adbook="'.$adbook_id.'" />
</execute>
</forcast>
		';
		//API送信
		$contents = $this->connectApi($command_xml);
		if(empty($contents)){
			return false;
		}
		return $contents;
	}

	//メールアドレス登録
	public function regMailaddress($mail_address){
		if(
			empty($mail_address) ||
			$this->send_ok_flag === 0
		){
			return false;
		}
		// メールアドレス登録用のXML作成
		$command_xml = '
			<forcast>
				<execute id="1" command="addAddress">>
					<parameter adbook="'.$this->addressbook.'">
						<rows>
							<email>'.$mail_address.'</email>
						</rows>
					</parameter>
				</execute>
			</forcast>
		';
		//API送信
		$contents = $this->connectApi($command_xml);
		if(empty($contents)){
			return false;
		}
		return true;
	}
	//メールアドレス削除
	public function delMailaddress($mail_address){
		if(
			empty($mail_address) ||
			$this->send_ok_flag === 0
		){
			return false;
		}
		$command_xml = '
			<forcast>
				<execute id="1" command="deleteAddress">>
					<parameter adbook="'.$this->addressbook.'">
						<email>'.$mail_address.'</email>
					</parameter>
				</execute>
			</forcast>
		';
		//API送信
		$contents = $this->connectApi($command_xml);
		if(empty($contents)){
			return false;
		}
		return true;
	}
	//メール送信
	public function sendMail($subject,$body,$from_name,$from_mail_address,$search_query){
		// 件名、本文、検索条件のどれかがない時、または、送信可能フラグNGの時終了
		if(
			empty($subject) ||
			empty($body) ||
			empty($search_query) ||
			$this->send_ok_flag === 0
		){
			return ;
		}
		if(!empty($from_name)){
			$this->send_name = $from_name;
		}
		if(!empty($from_mail_address)){
			$this->send_mail_address = $from_mail_address;
		}
		if(!empty($this->test_string)){
			$subject = $this->test_string.$subject;
		}
		$command_xml = '';
		// 送信文章用のXML作成
		$command_xml = '
			<forcast>
			<execute id="1">
				<command>makeTextMail</command>
				<parameter>
					<subject>'.$subject.'</subject>
					<fromname>'.$this->send_name.'</fromname>
					<from>'.$this->send_mail_address.'</from>
					<body><![CDATA['.$body.']]></body>
				</parameter>
			</execute>
			</forcast>
		';
		//API送信
		$contents = $this->connectApi($command_xml);
		if(empty($contents)){
			return false;
		}
		$yText_data = array();
		$yText = (array)simplexml_load_string($contents); //APIからの返り値をパース
		// 結果コードが1以外なら終了
		$yText["result"] = (array)$yText["result"]; //結果のみ取得
		if($yText["result"]["statuscode"]!=1){
			return false;
		}
		$yText_data["create_response_status"] = $yText["result"]["statuscode"]; //結果コードの取得
		$yText_data["mail_id"] = $yText["result"]["@attributes"]["mailid"]; //メールIDの取得

		$send_date = date('Y/m/d H:i:s'); //送信時間の設定
		// メールIDがなければ終了
		if(empty($yText_data["mail_id"])){
			return false;
		}
		// 送信条件用のXML作成
		$command_xml = '
			<forcast>
				<execute id="1">
					<command>setDelivery</command>
					<parameter>
						<adbook>'.$this->addressbook.'</adbook>
						<delivtime>'.$send_date.'</delivtime>
						<mailid>'.$yText_data["mail_id"].'</mailid>
						<searchquery type="block" op="or">'.$search_query.'</searchquery>
					</parameter>
				</execute>
			</forcast>
		';
		//API送信
		$contents = $this->connectApi($command_xml);
		if(empty($contents)){
			return false;
		}
		$yDeliv = (array)simplexml_load_string($contents); //APIからの返り値をパース
		$yDeliv["result"] = (array)$yDeliv["result"]; //結果のみ取得
		// 結果コードが1なら正常終了
		if($yDeliv["result"]["statuscode"]==1){
			return true;
		}else{
		// 結果コードが1以外なら以上終了
			return false;
		}
	}

	public function setImportEntry($filename,$address_id){
		if(
			empty($filename) ||
			$address_id == $this->addressbook
		){
			return false;
		}
		$command_xml = '<forcast>
<execute id="1" command="setImportEntry"> <parameter>
<target>'.$filename.'</target>
<strcode>sjis</strcode>
<adbook>'.$address_id.'</adbook>
<importmode>swapping</importmode>
<duplicatemethod>ignored</duplicatemethod>
<headerstyle>noheader</headerstyle>
    </parameter>
  </execute>
</forcast>';

			//API送信
			$contents = $this->connectApi($command_xml);
			if(empty($contents)){
				return false;
			}
			$yDeliv = (array)simplexml_load_string($contents); //APIからの返り値をパース
			$yDeliv["result"] = (array)$yDeliv["result"]; //結果のみ取得
			// 結果コードが1なら正常終了
			if($yDeliv["result"]["statuscode"]!=1){
			// 結果コードが1以外なら以上終了
				return false;
			}
			$y_imp_id = $yDeliv["result"]["impid"];

			$command_xml = '<forcast>
<execute id="1" command="startImportEntry">
	<parameter impid="'.$y_imp_id.'" />
    </execute>
  </forcast>
';
		//API送信
		$contents = $this->connectApi($command_xml);
		if(empty($contents)){
			return false;
		}
		$yDeliv = (array)simplexml_load_string($contents); //APIからの返り値をパース
		$yDeliv["result"] = (array)$yDeliv["result"]; //結果のみ取得
		// 結果コードが1なら正常終了
		if($yDeliv["result"]["statuscode"]!=1){
		// 結果コードが1以外なら以上終了
			return false;
		}
		return $y_imp_id;
	}

	//メールアドレス削除
	public function getImportEntry($y_imp_id){
		if(
			empty($y_imp_id)
		){
			return false;
		}
		$command_xml = '
<forcast>
<execute id="1" command="getImportEntry">
<parameter impid="'.$y_imp_id.'" />
</execute>
</forcast>
';
		//API送信
		$contents = $this->connectApi($command_xml);
		$data = (array)simplexml_load_string($contents); //APIからの返り値をパース
		$data["result"] = (array)$data["result"]; //結果のみ取得
		$result = (array)$data["result"]['entryinfo'];
		if($result['status']!='end'){
			return array('status'=>$result['status']);
		}
		$info = (array)$data["result"]['importstat'];
		return array(
			'status'			=> $result['status'],
			'total_count'		=> $info['total'],
			'success_count'		=> $info['users'],
			'error_count'		=> $info['error'],
			'dupli_count'		=> $info['dupli'],
		);
	}

	//送信可能フラグのON
	public function setMailSendok(){
		$this->send_ok_flag = 1;
	}
	//送信可能フラグのOFF
	public function setMailSendng(){
		$this->send_ok_flag = 0;
	}
	//テスト送信テキストの設定
	public function setTestString($string){
		$this->test_string = $string;
	}
}

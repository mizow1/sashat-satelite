<?php
class KyodoResponse{
	private $resource;
	private $rcd;
	private $messages = array();
	public function __construct($resource, $response_body){
		$this->messages = parse_ini_file(CONFIG_DIR.'kyodo_api_message.ini',true);
		if (empty($this->messages[$resource])) {
			throw new RuntimeException('APIのリソースが不正です。',1);
		}
		$this->resource = $resource;
		$xml = (array)simplexml_load_string($response_body);
				
		if ($xml['rcd'] != 0 && empty($this->messages[$resource][$xml['rcd']])) {
			throw new RuntimeException('APIの返却コードが不正です。',2);
		}
		$this->rcd = (int)$xml['rcd'];
		$this->data = $this->xmlToArray($xml);
	}

	// SimpleXMLElementから配列へ変換
	private function xmlToArray($xml){
		$data = array();
		if (is_object($xml)) {
			$xml_array = get_object_vars($xml);
		} else {
			$xml_array = $xml;
		}
		foreach ($xml_array as $key => $val) {
			if (is_object($val) || is_array($val)) {
				$data[$key] = $this->xmlToArray($val);
			} else {
				// URLエンコードされているマルチバイト文字をデコード
				// application/x-www-form-urlencoded
				// RFC1866 Section-8.2.1 に準拠のため、rawurldecode から urldecode に変更
				// 送信時の半角スペース(%20)が+(%2B)で戻って来るため
				if(
					   $key == 'mla'
					|| $key == 'eml'
					|| $key == 'cmla'
					|| $key == 'dmla'
				){
					$data[$key] = rawurldecode($val);
				}else{
					$data[$key] = urldecode($val);
				}
			}
		}
		return $data;
	}

	public function isSuccess(){
		return ($this->rcd === 0);
	}

	public function getCode(){
		return $this->rcd;
	}

	public function message(){
		return $this->messages[$this->resource][$this->rcd];
	}

	public function getData(){
		return $this->data;
	}

}

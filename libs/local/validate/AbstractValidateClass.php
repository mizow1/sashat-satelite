<?php
abstract class AbstractValidateBase{
//*****************  入力値チェック関数  *******************//
	public $prefix = "check";
	//空白チェック
	public function checkNoValue($value=""){
		if(str_replace(array(' ','　'),"",$value) !== ""){
			return true;
		}else{
			return false;
		}
	}
	//文字数最大チェック*****************************************************
	public function checkMaxLength($value="",$max_count){
		if(empty($value)){
			return true;
		}

		if(mb_strlen($value,ENCODE_SYSTEM) <= $max_count){
			return true;
		}else{
			return false;
		}
	}
	//文字数最小チェック*****************************************************
	public function checkMinLength($value="",$min_count){
		if(empty($value)){
			return true;
		}

		if(mb_strlen($value,ENCODE_SYSTEM) >= $min_count){
			return true;
		}else{
			return false;
		}
	}
	//文字数範囲チェック********************************************************
	public function checkRangeLength($value="",$max_count,$min_count){
		if(empty($value)){
			return true;
		}

		if(mb_strlen($value,ENCODE_SYSTEM) <= $max_count && mb_strlen($value,ENCODE_SYSTEM) >= $min_count){
			return true;
		}else{
			return false;
		}
	}
	//数値チェック
	public function checkNum($value=""){
		if(empty($value)){
			return true;
		}

		if(preg_match('/^[0-9]+$/',$value)){
			return true;
		}else{
			return false;
		}
	}
	//数値範囲チェック*********************************************************
	public function checkNumRange($value="",$min_num=0,$max_num=0){
		if(empty($value)){
			return true;
		}

		if(preg_match('/^[0-9]+$/',$value)){
			if( $value >= $min_num && $value <= $max_num ){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	//英字チェック************************************************************
	public function checkAlpha($value=""){
		if(empty($value)){
			return true;
		}

		if(preg_match('/^[a-zA-Z]+$/',$value)){
			return true;
		}else{
			return false;
		}
	}
	//英小文字チェック
	public function checkSmallAlpha($value=""){
		if(empty($value)){
			return true;
		}

		if(preg_match('/^[a-z]+$/',$value)){
			return true;
		}else{
			return false;
		}
	}
	//英数字チェック**********************************************************
	public function checkAlphaNum($value=""){
		if(empty($value)){
			return true;
		}

		if(preg_match('/^[a-zA-Z0-9]+$/',$value)){
			return true;
		}else{
			return false;
		}
	}

	//英数字チェック（アンダーバー、ハイフン可）
	public function checkAlphaNumUnderbarHaifun($value=""){
		if(empty($value)){
			return true;
		}
		if(preg_match('/^[a-zA-Z0-9]$|^[a-zA-Z0-9][a-zA-Z0-9_-]*[a-zA-Z0-9]$/',$value)){
			return true;
		}else{
			return false;
		}
	}
	//英数字チェック（アンダーバー可）
	public function checkAlphaNumUnderbar($value=""){
		if(empty($value)){
			return true;
		}
		if(preg_match('/^[\w]+$/',$value)){
			return true;
		}else{
			return false;
		}
	}

	//小文字英数字チェック（アンダーバー可）
	public function checkSmallAlphaNumUnderbar($value=""){
		if(empty($value)){
			return true;
		}

		if(preg_match('/^[a-z_0-9]+$/',$value)){
			return true;
		}else{
			return false;
		}
	}

	//ひらがなチェック
	public function checkHira($value=""){
		if(empty($value)){
			return true;
		}
		$value = str_replace(array("　"," "),array("",""),$value);
		if (preg_match("/^[ぁ-ゞー]+$/u", $value)) {
			return true;
		}else{
			return false;
		}
	}

	//カタカナチェック
	public function checkKana($value=""){
		if(empty($value)){
			return true;
		}

		$value = str_replace(array("　"," "),array("",""),$value);
		mb_regex_encoding("UTF-8");
		if (preg_match("/^[ァ-ヶー]+$/u", $value)) {
			return true;
		}else{
			return false;
		}
	}
	//html特殊文字チェック***********************************************************************
	public function checkHtmlSpecialChars($value=""){
		if(empty($value)){
			return true;
		}
		if(preg_match("/[\"'`&>'<]/u",$value)){
			return false;
		}else{
			return true;
		}
	}

	/*
	*　マルチバイト文字のバイト数チェック スマートフォンの絵文字対策
	*/
	public function checkMultiByte3($value){
		if( is_null( $value ) ){
			return true;
		}
		$len = mb_strlen( $value );
		$replace_len = mb_strlen(preg_replace('/[\x{10000}-\x{10FFFF}]/u','',$value));
		if($len <> $replace_len){
				return false;
		}
		return true;
	}

	//改行チェック**************************************************************************
	public function checkLineBreak($value=""){
		if(empty($value)){
			return true;
		}
		if(preg_match("/(?:\n|\r|\r\n)/u",$value)){
			return false;
		}else{
			return true;
		}
	}


/**********************************************************************************************************/
	//checkDateNum 系の処理をまとめて、かつ datetime 型の確認処理
	public function checkDateNumber($value=""){

	    if(empty($value)){
	        return true;
	    }

	    $date_type = array();
	    $date      = array();
	    $time      = array();

	    if(preg_match("/^[0-9]+$/",$value) && strlen($value)==8){

	        //20180401 型の場合
	        $date = array(
	            "year"  => substr($value,0,4),
	            "month" => substr($value,4,2),
	            "day"   => substr($value,6,2)
	        );

	    }else{

	        //2018-04-01 12:34:56 型と 2018-04-01 型の振り分け
	        $date_type = explode(" ", $value);

	        if(count($date_type)==2){

	            $time = array(
	                "hour"   => substr($date_type[1],0,2),
	                "minute" => substr($date_type[1],3,2),
	                "second" => substr($date_type[1],6,2)
	            );

	        }elseif(count($date_type)==1){

	        }else{
	            return false;
	        }

	        $date = array(
	            "year"  => substr($date_type[0],0,4),
	            "month" => substr($date_type[0],5,2),
	            "day"   => substr($date_type[0],8,2)
	        );
	    }

	    //日付のチェック（この辺の比較値は要考慮）
	    //if(!preg_match("/^[0-9]+$/",$date["year"]) || $date["year"] < INFYEAR || $date["year"] > SUPYEAR){
	    if(!preg_match("/^[0-9]+$/",$date["year"]) || $date["year"] < 1000 || $date["year"] > 3000){
	        return false;
	    }

	    if(!preg_match("/^[0-9]+$/",$date["month"]) || $date["month"] < 1 || $date["month"] > 12){
	        return false;
	    }

	    if(!preg_match("/^[0-9]+$/",$date["day"]) || $date["day"] < 1 || $date["day"] > 31){
	        return false;
	    }

	    //最後にグレゴリオ暦のチェック
	    if(!checkdate($date["month"], $date["day"], $date["year"])){
	        return false;
	    }

	    //時間がある場合
	    if(
	        $time &&
	        (!preg_match('/^(0[0-9]{1}|1{1}[0-9]{1}|2{1}[0-3]{1})$/', $time["hour"]) ||
	            !preg_match('/^(0[0-9]{1}|[1-5]{1}[0-9]{1})$/', $time["minute"]) ||
	            !preg_match('/^(0[0-9]{1}|[1-5]{1}[0-9]{1})$/', $time["second"]))
	        ){
	            return false;
	    }

	    return true;
	}
/**********************************************************************************************************/

	//日付チェック　年月日一体型************************************************
	public function checkDateNum($value=""){
	    return $this->checkDateNumber($value);
//return false;
/*		if(empty($value)){
			return true;
		}

		if(is_int((int)$value)){
			$yy = (int)substr($value,0,4);
			$mm = (int)substr($value,4,2);
			$dd = (int)substr($value,6,2);
			if(checkdate($mm,$dd,$yy)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}*/
	}
	public function checkDateNum2($value=""){
	    return $this->checkDateNumber($value);
//return false;
/*		if(empty($value)){
			return true;
		}

		$yy = (int)substr($value,0,4);
		$mm = (int)substr($value,5,2);
		$dd = (int)substr($value,8,2);
		$hh = (int)substr($value,11,2);
		$ii = (int)substr($value,14,2);
		$ss = (int)substr($value,17,2);
		if(checkdate($mm,$dd,$yy) && $this->checkTime($hh,$ii)){
			return true;
		}else{
			return false;
		}*/
	}
	public function checkDateNum3($value=""){
	    return $this->checkDateNumber($value);
//return false;
/*		if(empty($value)){
			return true;
		}

		$yy = (int)substr($value,0,4);
		$mm = (int)substr($value,5,2);
		$dd = (int)substr($value,8,2);
		if(checkdate($mm,$dd,$yy)){
			return true;
		}else{
			return false;
		}*/
	}

public function checkDateNum4($value=""){
    return $this->checkDateNumber($value);
}

	//日付チェック　年月日別****************************************************
	public function checkDate(&$yy="",&$mm="",&$dd=""){
		if(checkdate($mm,$dd,$yy)){
			return true;
		}else{
			return false;
		}
	}
	//日付チェック　過去日付ならエラー*******************************************
	public function checkDateNumAgo($value=""){
		if(empty($value)){
			return true;
		}

		if($this->checkDateNum($value)){
			if(substr($value,0,4)."-".substr($value,4,2)."-".substr($value,6,2) >= date('Y-m-d')){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	//日付チェック　過去日付ならエラー(当日含む)
	public function checkDateNumAgo2($value=""){
		if(empty($value)){
			return true;
		}

		if($this->checkDateNum($value)){
			if(substr($value,0,4)."-".substr($value,4,2)."-".substr($value,6,2) > date('Y-m-d')){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	//日付チェック　未来日付ならエラー***********************************************
	public function checkDateNumFuture($value=""){
		if(empty($value)){
			return true;
		}

		if($this->checkDateNum($value)){
			if(substr($value,0,4)."-".substr($value,4,2)."-".substr($value,6,2) <= date('Y-m-d')){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	//時刻チェック****************************************************************
	public function checkTime(&$hh="",&$mm=""){
		if(strtotime($hh.":".$mm)){
			return true;
		}else{
			return false;
		}
	}
	//時刻チェック　過去時刻ならエラー**********************************************
	public function checkTimeAgo($value="",&$date=""){
		if(empty($value)){
			return true;
		}

		$target_date = is_int($date) ? substr($date,0,4)."-".substr($date,4,2)."-".substr($date,6,2) : $date;
		if($this->timeCheck($value)){
			$date_target = new DateTime("{$target_date} {$hh}:{$mm}");
			$date_now    = new DateTime("now");

			if($date_target >= $date_now){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	//時刻チェック　未来時刻ならエラー*************************************************
	public function checkTimeFuture($value="",&$date=""){
		if(empty($value)){
			return true;
		}

		$target_date = is_int($date) ? substr($date,0,4)."-".substr($date,4,2)."-".substr($date,6,2) : $date;
		if($this->timeCheck($value)){
			$date_target = new DateTime("{$target_date} {$hh}:{$mm}");
			$date_now    = new DateTime(date("H:i"));

			if($date_target <= $date_now){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	/**
	 * 日付が最小値以上かのチェック
	 */
	public function checkDateMin($value="", $min_date=""){
		if(empty($value) || empty($min_date)){
			return true;
		}

		return (strtotime($value) >= strtotime($min_date));
	}

	//郵便番号チェック*******************************************************************
	public function checkZipCode($value=""){
		if(is_null($value) || $value === ''){
			return true;
		}

		if(preg_match("/^[0-9]{7}$/", $value)){
			return true;
		}else{
			return false;
		}
	}
	//電話番号チェック*********************************************************************
	public function checkTel($value=""){
		if(is_null($value)){
			return true;
		}

		$tmp = str_replace(array("ー","?","‐"),array("-","-","-"),$value);
		$data = mb_convert_kana($tmp,"a");
		if(strpos($data,"-")){
			if(preg_match("/^\d{3}\-\d{4}\-\d{4}$/",$data) || preg_match("/^\d{3}\-\d{3}\-\d{4}$/",$data) || preg_match("/^\d{2}\-\d{4}\-\d{4}$/",$data) ||preg_match("/^\d{4}\-\d{2}\-\d{4}$/",$data)  ){
				return true;
			}else{
				return false;
			}
		}else{
			$tmp = str_split($data);
			if(count($tmp) == 10 || count($tmp) == 11){
				return true;
			}else{
				return false;
			}
		}
	}

	//メールアドレスチェック*****************************************************************
	public function checkMail($value=""){
		if(empty($value)){
			return true;
		}

		if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\.+_-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $value)){
			return true;
		}else{
			return false;
		}
	}

	//メールアドレスドメインの実存チェック
	public function checkMailDns($value=""){
		if(empty($value)){
			return true;
		}
		preg_match('/^([0-9A-Za-z._\-\+]+@)([0-9A-Za-z._\-]+$)/', $value, $match);
		if(!isset($match[2])){
			return false;
		}
		$check_domain = $match[2];
		if ( checkdnsrr($check_domain, 'MX') || checkdnsrr($check_domain, 'A') ){
			return true;
		}
		return false;
	}

	//同一チェック***************************************************************
	public function checkSame($value1="",$value2=""){
		if($this->checkNoValue($value1) && $this->checkNoValue($value2)){
			if($value1 == $value2){
				return true;
			}else{
				return false;
			}
		}
	}
	//定数チェック***************************************************************
	public function checkFixNumP($value="",$fix_array=array()){
		if(!empty($fix_array[$value])){
			return true;
		}else{
			return false;
		}
	}

	// 配列の要素数 最大値以下チェック
	public function checkArrayMaxCount($arr_value=array(),$num_max_count=0){
		return (count($arr_value) <= $num_max_count);
	}

	//使用可能文字チェック**********************************************
	public function checkUseString($value=""){

        	$value = mb_convert_encoding($value,ENCODE_SYSTEM,"auto");
        	$value = @iconv("UTF-8", "SJIS//TRANSLIT", $value);

        	$v1 = mb_convert_encoding(mb_convert_encoding($value,ENCODE_DEFAULT,'auto'),'HTML-ENTITIES','auto');
        	$v2 = mb_convert_encoding(mb_convert_encoding($value,ENCODE_SYSTEM,'auto'),'HTML-ENTITIES','auto');

        	$v1 = str_replace(array("&#65293;","&#65374;"),array("&minus;","&#12316;"),$v1);

        	if($v1 === $v2){
        		return true;
        	}else{
        		return false;
        	}
	}

	//IPアドレスチェック**********************************************
	public function checkIpAddress($value=""){
		if(filter_var($value,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)){
			return true;
		}else{
			return false;
		}
	}

	//ぶんぶんクラブID用8桁チェック
	public function checkNumRangeLength8($value){
		if(empty($value)){
			return true;
		}
		//数値チェック
		if(!$this->checkNum($value)){
			return false;
		}
		return $this->checkRangeLength($value,8,8);
	}

}

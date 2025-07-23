<?php
//ini_set("display_errors",1);
require_once(dirname(dirname(dirname(__FILE__))) . "/config/init.php");

//sp_flagを格納
$sp_flag = $_POST['sp_flag'];

$_POST["name1"] = urldecode($_POST["name1"]);
if(!empty($_POST["name2"])){
	$_POST["name2"] = urldecode($_POST["name2"]);
}

$error_message = input_check($_POST,$sp_flag);

if(!is_array($error_message)){
	echo "true";
}else{
	foreach( $error_message as $key => $val){
//		echo "・".$val."\r\n";
		$echo .= "・".$val."\r\n";
	}
//	echo unicode_decode($echo);
	echo urlencode($echo);
}

function input_check($post_data,$sp_flag){

	mb_internal_encoding("UTF-8");

	$post_data = delSpace($post_data);
	$post_data2 = sanitize($post_data);

	//存在しない日付はエラー
	if($post_data['death_yy1'] != "" || $post_data['death_mm1'] != "" || $post_data['death_dd1'] != ""){
		$day_exist_flag = checkdate($post_data['death_mm1'],$post_data['death_dd1'],$post_data['death_yy1']);
		if($day_exist_flag == false){
			$return['death_date1'] = '命日に正しい日付を入力してください。';
		}
	}


	if(mb_strlen($post_data["name1"]) > 10){
	 	$return['name1']='あなたの名前は10文字以内で入力してください。';
	 }elseif(mb_strlen($post_data2["name1"]) != mb_strlen($post_data["name1"])){
	 	$return["name1"] = 'あなたの名前に、使用できない文字が入力されています。';
	 }

	//使用しているデバイスがpcの場合
	if($sp_flag == 0){
		if(is_numeric($post_data["yy1"]) && is_numeric($post_data["dd1"]) && is_numeric($post_data["mm1"])){
			if(!checkdate($post_data["mm1"],$post_data["dd1"],$post_data["yy1"])){
				$return["date1"] = 'あなたの生年月日を選択してください。';
			}
		}else{
			$return["date1"] = 'あなたの生年月日に数値以外の文字が含まれています。';
		}
		//使用しているデバイスがspの場合
	}else{
		$birthday = explode("/",$post_data["birthday1"]);
		$yy1 = $birthday[0];
		$mm1 = $birthday[1];
		$dd1 = $birthday[2];

		if(is_numeric($yy1) && is_numeric($dd1) && is_numeric($mm1)){
			if(!checkdate($mm1,$dd1,$yy1)){
				$return["date1"] = 'あなたの生年月日を選択してください。';
			}
		}else{
			$return["date1"] = 'あなたの生年月日に数値以外の文字が含まれています。';
		}
	}

//	if(empty($post_data["hh"]) || empty($post_data["ii"])){
//		$return["time"] = 'あなたの出生時刻が未選択です。';
//	}

//	if(empty($post_data["pref"])){
//		$return["pref"] = 'あなたの出生地が未選択です。';
//	}

	if(array_key_exists('name2',$post_data)){

		if(mb_strlen($post_data["name2"]) > 10){
		 	$return['name2']='あの人の名前は10文字以内で入力してください。';
		}elseif(mb_strlen($post_data2["name2"]) != mb_strlen($post_data["name2"])){
			$return["name2"] = 'あの人の名前に、使用できない文字が入力されています。';
		}
		$post_data["name2"] = htmlspecialchars($post_data["name2"],ENT_QUOTES);

		if($sp_flag == 0){
			if(is_numeric($post_data["yy2"]) && is_numeric($post_data["dd2"]) && is_numeric($post_data["mm2"])){
				if(!checkdate($post_data["mm2"],$post_data["dd2"],$post_data["yy2"])){
					$return["date2"] = 'あの人の生年月日を選択してください。';
				}
			}else{
				$return["date2"] = 'あの人の生年月日に数値以外の文字が含まれています。';
			}
		}else{
			$birthday2 = explode("/",$post_data["birthday2"]);
			$yy2 = $birthday2[0];
			$mm2 = $birthday2[1];
			$dd2 = $birthday2[2];

			if(is_numeric($yy2) && is_numeric($dd2) && is_numeric($mm2)){
				if(!checkdate($mm2,$dd2,$yy2)){
					$return["date1"] = 'あなたの生年月日を選択してください。';
				}
			}else{
				$return["date1"] = 'あなたの生年月日に数値以外の文字が含まれています。';
			}
		}

	}
	//$return = convertEncodeArray($return,'UTF8','auto');
	return $return;
}

function checkNameKana($str){
	if(preg_match("/^(\x82[\x9f-\xf1]|\x81[\x4a\x54\x55]|"."\xa4[\xa1-\xf3]|\xa1[\xb5\xb6\xab]|"."\xe3\x81[\x81-\xbf]|\xe3\x82[\x80-\x9e])+$/", $str)){
		return true;
	}else{
		return false;
	}

}

function convertEncodeArray($array,$enc1,$enc2){
	if(is_array($array)){
		foreach($array as $k => $v){
			$array[$k]=convertEncodeArray($v,$enc1,$enc2);
		}
	}else{
		$array=mb_convert_encoding($array,$enc1,$enc2);
	}
	return $array;
}


function delSpace($a) {
	$_a = array();
	$array = array(" ","　");
	foreach($a as $key=>$value) {
		if(is_array($value)){
			$_a[$key] = sanitize($value);
		}else{
			$value = str_replace($array,"",$value);
			$_a[$key] = htmlspecialchars($value);
		}
	}
	return $_a;
}



function sanitize($a) {
	$_a = array();
	$array = array("'",":",";",'"',">","<","(",")","（","）","+",".",'\\',"¥","&","＆","%","％","$","＄","","@","＠","=","＝");
	foreach($a as $key=>$value) {
		if(is_array($value)){
			$_a[$key] = sanitize($value);
		}else{
			$value = str_replace($array,"",$value);
			$_a[$key] = htmlspecialchars($value);
		}
	}
	return $_a;
}

// UTF-8文字列をUnicodeエスケープする。ただし英数字と記号はエスケープしない。
function unicode_decode($str) {
	return preg_replace_callback("/((?:[^\x09\x0A\x0D\x20-\x7E]{3})+)/", "decode_callback", $str);
}

function decode_callback($matches) {
	$char = mb_convert_encoding($matches[1], "UTF-16", "UTF-8");
	$escaped = "";
	for ($i = 0, $l = strlen($char); $i < $l; $i += 2) {
		$escaped .=  "\u" . sprintf("%02x%02x", ord($char[$i]), ord($char[$i+1]));
	}
	return $escaped;
}

// Unicodeエスケープされた文字列をUTF-8文字列に戻す
function unicode_encode($str) {
	return preg_replace_callback("/\\\\u([0-9a-zA-Z]{4})/", "encode_callback", $str);
}


?>

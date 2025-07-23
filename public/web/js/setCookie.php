<?php

$cookie_tmp = $_POST['tmp'];
$target_count = $_POST['target_count'];

$cookie_value = '1';

if(!empty($_COOKIE[$cookie_tmp])){
  $cookie_data = $_COOKIE[$cookie_tmp];

  //取得したcookieを；で配列に分割
  $cookie_data_array = explode(';',$cookie_data);

  //cookieの作成日と現在の日時の差分を格納
  $date_diff = (strtotime(date("Y-m-d")) - strtotime($cookie_data_array[1])) / (60 * 60 * 24);

  //cookie作成日から14日以内の場合は色の値に＋１(3の場合はそのまま)　14日以上の場合は―1(色が1の場合はそのまま)　それ以外は1にする。
  if($date_diff < 14){
    $cookie_value = $cookie_data_array[0] == 3 ? $cookie_data_array[0]:$cookie_data_array[0]+1;
  }elseif($date_diff > 14){
    $cookie_value = $cookie_data_array[0] == 1 ? $cookie_data_array[0]:$cookie_data_array[0]-1;
  }else{
    $cookie_value = 1;
  }

}


//保存期間　1年
$cookie_time = time()+60*60*24*365;

//今の時間を設定
$create_time = date("Y-m-d");

$previous_life_cookie = $cookie_value.';'.$create_time.';time='.$cookie_time;
// $previous_life_1_2_cookie = 'previous_life_1_2;time='.$cookie_time;
// $previous_life_1_3_cookie = 'previous_life_1_3;time='.$cookie_time;
// echo "<pre>";
// var_dump($cookie_tmp);
// var_dump($previous_life_cookie);
// var_dump($cookie_time);
// echo "</pre>";
// exit;
setcookie($cookie_tmp,$previous_life_cookie,$cookie_time,'/');
//フラグ用のcookieを設定
// setcookie('cookie_function_flag','test','','/');
// echo "<pre>";
// var_dump($_COOKIE);
// echo "</pre>";
// setcookie('previous_life_1_1',$previous_life_1_1_cookie,time()+60*60*24*7);
?>

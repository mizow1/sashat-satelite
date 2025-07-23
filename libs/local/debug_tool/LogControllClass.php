<?php
//define('LOG_DIR',SYSTEM_ROOT.'tmp/log/');

class LogControl{

	public function __construct(){
	}

	/*
		CSV取込ログ

		開発者名　：　二瀬
		開発日付　：　2014/03/14

		$log_file_name		:	出力ログファイル（拡張子無し、自動的に処理日付与）
		$php_file_name		:	処理中ファイル名
		$line				:	処理中行数
		$note				:	備考
		（例）$LogControll->debugLog('log_test',__FILE__,__LINE__,'CSV読込終了');
		      ↑/tmp/log/log_test_{YYYYmmdd}.txt　ファイルが作成され、ファイル内に「実行日付,実行PHPファイル,実行PHPファイルの行数,使用メモリ,備考」で書き込まれます。
			  書き込み例は以下です。
			  '2014/03/10 11:56:03854',/home/juchuadmin/juchu/hisamatsu_outward/webapp/controller/afc/regist_csv/RakutenAction.php','Line:29','USED MEMORY2,100,008',s:11:"CSV読込終了";'
	*/

	public function debugLog($log_file_name="",$php_file_name="",$line="",$note=""){

		$fp = fopen(LOG_DIR.$log_file_name."_".date('Ymd').".txt","a+");

		$date       = new DateTime();
		$time       = microtime();
		$time_list  = explode(' ',$time);
		$time_micro = explode('.',$time_list[0]);
		$date_str   = $date->format('Y/m/d H:i:s').substr($time_micro[1],0,3);

		$body  = "'".$date_str."','".$php_file_name."','Line:".(empty($line) ? "-" : $line)."','";
		$body .= number_format(memory_get_usage())."','".serialize($note)."'\r\n";

		fwrite($fp,$body);
		fclose($fp);
	}

}
?>
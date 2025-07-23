<?php
class ErrorLog{
	public function __construct(){
		throw new RuntimeException('このクラスはインスタンス化禁止です');
	}
	/**
	 * Apacheのエラーログに任意文字列を出力
	 * @param  string $message 出力したい文字列(デフォルトはerror)
	 *　エラーログ出力例
	 *　[Fri Sep 11 15:40:27.873015 2015] [:error] [pid 17982] [client 10.0.2.2:51795] 
	 *　ErrorLog::write /var/www/html/sanyo_medica/webapp/model/administrator/article/ArticleModelClass.php :line 26 :message error, 
	 *　referer: https://192.168.5.146:20388/admin/?controller=freepage
	 */
	public static function write($message='error'){
		$debug_array = debug_backtrace();
		$error_message = is_array($message) ? serialize($message) : $message ;
		//このメソッドを呼び出したファイル名
		$file = $debug_array[0]['file'];
		//このメソッドを呼び出した行数
		$line = $debug_array[0]['line'];
		error_log('ErrorLog::write '.$file.' :line '.$line.' :message '.$error_message);
	}
}

<?php
class ErrorDisplay{
	public function __construct(){
		throw new RuntimeException('このクラスはインスタンス化禁止です');
	}

	public static function notFound($device){
		header('HTTP/1.1 404 Not Found');
		echo file_get_contents(DOCUMENT_ROOT.'err/err_404.html');
	}

	public static function forbidden($device){
		header('HTTP/1.1 403 Forbidden');
		echo file_get_contents(DOCUMENT_ROOT.'err/err_403.html');
	}

	public static function notAllowed($device){
		header('HTTP/1.1 405 Method Not Allowed');
		echo file_get_contents(DOCUMENT_ROOT.'err/err_405.html');
	}

	public static function internal($device){
		// Internal Server Errorでも503を返す

		header('HTTP/1.1 503 Service Unavailable');
		echo file_get_contents(DOCUMENT_ROOT.'err/err_503.html');
	}

	public static function maintenance($device){
		header('HTTP/1.1 503 Service Unavailable');
		echo file_get_contents(DOCUMENT_ROOT.'err/err_maintenance.html');
	}

}

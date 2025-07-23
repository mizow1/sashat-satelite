<?php
/**
 * [Error]エラーステータス処理クラス
 *
 * 参照渡しで処理されていたerror_status情報の操作を行う
 *
 * @access public
 */
class ErrorStatus{
	public static $status_code = 9999;
	public static $status_message = 'system error';

	public function __construct(){
		throw new RuntimeException('このクラスはインスタンス化禁止です');
	}

	/**
	 * [Error]エラーステータスコード設定関数
	 *
	 * 参照渡しで処理されていたerror_status情報のコードを設定
	 *
	 * @param string $code
	 */
	public static function setCode($code){
		if(!empty($code)){
			self::$status_code = $code;
		}
	}

	/**
	 * [Error]エラーステータスコード取得関数
	 *
	 * 参照渡しで処理されていたerror_status情報のコードを取得
	 *
	 * @return string self::$status_code;
	 */
	public static function getCode(){
		return self::$status_code;
	}

	/**
	 * [Error]エラーステータスメッセージ設定関数
	 *
	 * 参照渡しで処理されていたerror_status情報のメッセージを設定
	 *
	 * @param string $message
	 */
	public static function setMessage($message){
		if(!empty($message)){
			self::$status_message = $message;
		}
	}

	/**
	 * [Error]エラーステータスメッセージ取得関数
	 *
	 * 参照渡しで処理されていたerror_status情報のメッセージを取得
	 *
	 * @return string self::$status_message;
	 */
	public static function getMessage(){
		return self::$status_message;
	}
}

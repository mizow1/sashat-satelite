<?php

/*
 * [Exception]管理用エラーステータス例外クラス
 * 
 * コントローラ、モデル等で使用されているerror_status参照渡し処理
 * の代用として例外処理させるクラス
 * 
 * @access public
 * 
 */
class AdminErrorStatusException extends Exception {
	
	/*
	 * [construct]コンストラクタ
	 * 
	 * throw時の呼び出し関数
	 * 
	 * @access public
	 * 
	 */
	public function __construct($message='system error', $code='9999'){
		parent::__construct();
		$this->code = $code;
		$this->message = $message;
		ErrorLog::write('['.$code.'] '.$message);
	}
}

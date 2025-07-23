<?php
// 隠しコマンド用クラス Hidden Command
class HC{
	private static $develop_mode = false;

	public static function init($get_data=array()){
		if(
			!empty($get_data['develop_mode_key']) &&
			$get_data['develop_mode_key'] == DEVELOP_MODE_KEY
		){
			// 開発者モード ON
			self::$develop_mode = true;
		}
	}

	/**
	 * 隠しコマンド一覧取得
	 * 
	 * 隠しコマンドに何が存在しているかの確認用
	 * '識別子' => array(
	 * 	'exp' => 説明,
	 * 	'type' => 設定箇所（get_dataやpost_dataなど）, 
	 * 	'possible_value' => 有効な値
	 * 	'current_value' => 現在の値
	 * ),
	 */
	public static function getHiddenCommandList(){
		return array(
			'develop_mode_key' => array(
				'exp' => '開発者モード切り替え',
				'type' => 'get_data',
				'possible_value' => DEVELOP_MODE_KEY,
				'current_value' => empty($get_data['develop_mode_key']) ? '' : $get_data['develop_mode_key'],
			),
		);
	}

	public static function getDevelopMode(){
		return self::$develop_mode;
	}
}

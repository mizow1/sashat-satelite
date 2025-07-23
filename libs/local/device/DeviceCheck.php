<?php
class DeviceCheck{
	public function __construct(){
		throw new RuntimeException('このクラスはインスタンス化禁止です');
	}

	/**
	* UA取得
	* @return string
	*/
	public static function getUserAgent(){
		$userAgent = isset($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT'] : '';
		return $userAgent;
	}

	/**
	* スマホかどうか判定
	* @return bool
	*/
	public static function isSmartPhone(){
		$ua = self::getuserAgent();
		if (
			stripos($ua, 'iphone') !== false || // iphone
			stripos($ua, 'ipod') !== false || // ipod
			(stripos($ua, 'android') !== false && stripos($ua, 'mobile') !== false) || // android
			(stripos($ua, 'windows') !== false && stripos($ua, 'mobile') !== false) || // windows phone
			(stripos($ua, 'firefox') !== false && stripos($ua, 'mobile') !== false) || // firefox phone
			(stripos($ua, 'bb10') !== false && stripos($ua, 'mobile') !== false) || // blackberry 10
			(stripos($ua, 'blackberry') !== false) // blackberry
		) {
			$isSmartPhone = true;
		} else {
			$isSmartPhone = false;
		}
		return $isSmartPhone;
	}
	//デバイス判定と表示テンプレート判定を$_GETに埋め込む
	public static function setDeviceParam(){
		$device_type = 'pc';
		$template_device_type = 'pc';
		if(self::isSmartPhone()){
			$device_type = 'sp';
			$template_device_type = 'sp';
		}
		if(!empty($_COOKIE[TEMPLATE_DEVICE_TYPE])){
			$template_device_type = $_COOKIE[TEMPLATE_DEVICE_TYPE] == TEMPLATE_DEVICE_TYPE_SP ?  'sp' : 'pc';
		}
		$_GET['device_type'] = $device_type;
		$_GET['template_device_type'] = $template_device_type;
	}
	/**
	* ボット判定関数
	**/
	public static function isBot() {
		// ボットのUAに含まれる文字列
		require(GITIGNORE.'device/bots.php');
		if(empty($_SERVER['HTTP_USER_AGENT'])){
			return false;
		}
		return self::checkString($_SERVER['HTTP_USER_AGENT'],$bots);

	}
	/**
	* 初回チェック判定関数
	**/
	public static function isFirstAccess() {
		// UAに含まれる文字列
		require(GITIGNORE.'device/targets.php');
		if(empty($_SERVER['HTTP_USER_AGENT'])){
			return false;
		}
		return self::checkString($_SERVER['HTTP_USER_AGENT'],$targets);

	}
	/**
	*
	**/
	private static function checkString($ua,$bots){
		foreach( $bots as $bot ) {
			if (stripos( $ua, $bot ) !== false) {
				return true;
			}
		}
		return false;
	}
}

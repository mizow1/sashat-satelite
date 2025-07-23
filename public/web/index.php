<?php
//ini_set('display_errors', "1");

$GLOBALS['debug_flag']=0; //デバッグモード

try {
	require_once(dirname(dirname(dirname(__FILE__))).'/config/init.php');

	require_once(LIB_DIR.'local/error/ErrorLog.php');

	// デバイス判定
	require_once(LIB_DIR.'local/device/DeviceCheck.php');
	DeviceCheck::setDeviceParam();
	$device = $_GET['template_device_type'];

	require_once(LIB_DIR.'local/error/ErrorDisplay.php');

	require_once(LIB_DIR.'local/view/ViewSmarty.php');

	require_once(FRONT_CONTROLLER.'FrontController.php');
	if($device=='pc'){
		new PcFrontController();
	}else{
		new SpFrontController();
	}

	if(!empty($GLOBALS['debug_flag'])){
		error_log("@@@ ".__FILE__.":".__LINE__.":".memory_get_usage()." \n");
	}
} catch (Exception $e) {
	var_dump($e->getMessage());
	// 既にheaderが送出されている＝何らかの表示がされる(headerの送出だけの場合を除く)
	if (headers_sent()) {
		exit;
	}

	if($e->getMessage() == '404'){
		ErrorDisplay::notFound('pc');
	}else{
		ErrorDisplay::internal('pc');
	}
}

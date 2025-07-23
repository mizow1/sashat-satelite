<?php
if(!isset($_SERVER['PANDA_ENV'])){
	require_once(dirname(dirname(dirname(__FILE__))).'/config/set_pandaenv.php');
}
require_once('/var/www/html/dev/config/init.php');
require_once(LIB_DIR.'local/error/ErrorLog.php');

function last_error_print(){
	if(is_null($e = error_get_last()) === false) {
		if($e['type'] != E_NOTICE){
			ErrorLog::write($e);
		}
		
		if(
			!empty($_SERVER['REQUEST_URI']) &&
			(
				$e['type'] == E_ERROR ||
				$e['type'] == E_PARSE ||
				$e['type'] == E_CORE_ERROR ||
				$e['type'] == E_COMPILE_ERROR
			)
		){
			require_once(LIB_DIR.'local/error/ErrorDisplay.php');
			$arr_uri = explode('/', $_SERVER['REQUEST_URI']);
			if($arr_uri[1] == 'sp'){
				ErrorDisplay::internal('sp');
			}else{
				ErrorDisplay::internal('pc');
			}
		}
	}
}

register_shutdown_function('last_error_print');

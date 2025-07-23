<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 * $params
 *	date = 日付
 */

function smarty_function_date_week_jp($params, &$smarty){
	if(empty($params['date'])){
		$params['date'] = date('Y-m-d');
	}

	// タイムスタンプ
	$timestamp = strtotime($params['date']);

	$arr_week_name_list = array(
		SMARTY_WEEK_NAME_SUNDAY,
		SMARTY_WEEK_NAME_MONDAY,
		SMARTY_WEEK_NAME_TUESDAY,
		SMARTY_WEEK_NAME_WEDNESDAY,
		SMARTY_WEEK_NAME_THURSDAY,
		SMARTY_WEEK_NAME_FRIDAY,
		SMARTY_WEEK_NAME_SATURDAY,
	);

	if(isset($arr_week_name_list[date('w', $timestamp)])){
		return $arr_week_name_list[date('w', $timestamp)];
	}else{
		return '';
	}
}

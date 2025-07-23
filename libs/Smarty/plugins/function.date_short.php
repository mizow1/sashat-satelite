<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 * $params
 *	date = 日付
 *	today = 出力したいフォーマット（今日）
 *	this_year = 出力したいフォーマット（今年以内）
 *	default = 出力したいフォーマット（今年以外）
 */
function smarty_function_date_short($params, &$smarty)
{
	if(empty($params['date'])){
		return date('H:i');
	}
	$today = empty($params['today']) ? 'H:i' : $params['today'] ;
	$this_year = empty($params['this_year']) ? 'm/d H:i' : $params['this_year'] ;
	$default = empty($params['default']) ? 'Y/m/d H:i' : $params['default'] ;
	$timestamp = strtotime($params['date']);
	
	// 当日だったら
	if(date('Y-m-d') == date('Y-m-d',$timestamp)){
		return date($today,$timestamp);
	}
	
	if(date('Y') == date('Y',$timestamp)){
		return date($this_year,$timestamp);
	}
	return date($default,$timestamp);
}


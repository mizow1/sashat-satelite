<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 * $params
 *	date = 日付
 *	this_year = 出力したいフォーマット（今年以内）
 *	default = 出力したいフォーマット（今年以外）
 */

function smarty_function_date_short_jp($params, &$smarty){
	if(
		empty($params['date']) && 
		empty($params['with_time'])
	){
		return '';
	}elseif(
		empty($params['date']) && 
		!empty($params['with_time'])
	){
		return date('H時i分');
	}

	// タイムスタンプ
	$timestamp = strtotime($params['date']);

	// デフォルトフォーマット
	if(!empty($params['default'])){
		$str_format_style = empty($params['default']) ? 'Y年m月d日(w_jp) H時i分' : $params['default'] ;
	}
	// 今月
	elseif(date('m') == date('m',$timestamp)){
		$str_format_style = empty($params['this_month']) ? 'd日(w_jp) H時i分' : $params['this_month'];
	}
	// 今月以降かつ今年
	elseif(date('Y') == date('Y',$timestamp)){
		$str_format_style = empty($params['this_year']) ? 'm月d日(w_jp) H時i分' : $params['this_year'] ;
	}

	// 時間なし
	if(empty($params['with_time'])){
		$str_format_style = mb_str_replace(' H時i分', '', $str_format_style);
	}

	// 曜日設定
	if(empty($params['with_week_name'])){
		$str_format_style =  str_replace('(w_jp)', '', $str_format_style);
	}else{
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
			$str_format_style = str_replace('w_jp', $arr_week_name_list[date('w', $timestamp)], $str_format_style);
		}else{
			$str_format_style = str_replace('w_jp', '', $str_format_style);
		}
	}

	return date($str_format_style,$timestamp);
}

function mb_str_replace($search, $replace, $subject, $encoding = null){
	$tmp = mb_regex_encoding();
	mb_regex_encoding(func_num_args() > 3 ? $encoding : mb_internal_encoding());
		foreach ((array)$search as $i => $s) {
			if (!is_array($replace)) {
				$r = $replace;
			} elseif (isset($replace[$i])) {
				$r = $replace[$i];
			} else {
				$r = '';
			}
		$s = mb_ereg_replace('[.\\\\+*?\\[^$(){}|]', '\\\\0', $s);
		$subject = mb_ereg_replace($s, $r, $subject);
		}
	mb_regex_encoding($tmp);
	return $subject;
}


<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty get_custom_date modifier plugin
 * Type:     modifier<br>
 * Name:     get_custom_date<br>
 * Purpose:  指定日カスタム情報を返す
 *
 * @link   
 * @author 
 *
 * @param string  $str_date      date string(yyyymmdd)
 * @param integer $str_file      file name string
 * 
 * @return array custom date data
 */
function smarty_modifier_get_custom_date($str_date, $str_file){
	if(
		empty($str_date) || 
		!is_numeric($str_date)
	){
		return array();
	}

	// 曜日
	$str_datetime = substr($str_date, 0, 4).'-'.substr($str_date, 4, 2).'-'.substr($str_date, 6, 2);
	$objDateTime = new DateTime($str_datetime);
	$arr_week_name_list = array(
		SMARTY_WEEK_NAME_SUNDAY,
		SMARTY_WEEK_NAME_MONDAY,
		SMARTY_WEEK_NAME_TUESDAY,
		SMARTY_WEEK_NAME_WEDNESDAY,
		SMARTY_WEEK_NAME_THURSDAY,
		SMARTY_WEEK_NAME_FRIDAY,
		SMARTY_WEEK_NAME_SATURDAY,
	);
	$num_week = (int)$objDateTime->format('w');

	// カスタム情報
	// TODO:ファイル設置場所を検討
	if(
		!empty($str_file) && 
		file_exists(DOCUMENT_ROOT.'include/'.$str_file)
	){
		$str_holiday_list = file_get_contents(DOCUMENT_ROOT.'include/'.$str_file);
		if($str_holiday_list === false){
			$arr_result = array(
				'date'			=> $str_date,
				'title'			=> '',
				'title_alias'	=> '',
				'week_name'		=> $arr_week_name_list[$num_week],
				'week_num'		=> $num_week,
			);

			return $arr_result;
		}
		$arr_holiday_list = json_decode($str_holiday_list, true);
	}else{
		$str_path_static = 'https://'.SITE_STATIC_DOMAIN.'/js/'.$str_file;
		// jsふぁいるないけん、非同期でstaticからかっぱらってくる
		$js_file_path = DOCUMENT_ROOT.'js/'.$str_file;
		$str_command = '/bin/bash /var/www/html/dev/background/cron/file_get.sh '.$str_path_static.' '.$js_file_path.' > /dev/null 2>&1' ;
		$arr_output = array();
		$num_ret = 0;
		exec ( $str_command, $arr_output, $num_ret );
		if(file_exists(DOCUMENT_ROOT.'include/'.$str_file)){
			$str_holiday_list = @file_get_contents(DOCUMENT_ROOT.'include/'.$str_file);
			if(empty($str_holiday_list)){
				// ファイル取得完了まで時間がかかる場合はstaticから拝借
				$str_holiday_list = @file_get_contents($str_path_static);
				if(empty($str_holiday_list)){
					$arr_holiday_list = array();
				}else{
					$arr_holiday_list = @json_decode($str_holiday_list, true);
				}
			}else{
				$arr_holiday_list = @json_decode($str_holiday_list, true);
				if(empty($arr_holiday_list)){
					$arr_holiday_list = array();
				}
			}
		}else{
			$str_holiday_list = @file_get_contents($str_path_static);
		}
		
	}

	$arr_result = array(
		'date'			=> $str_date,
		'title'			=> '',
		'title_alias'	=> '',
		'week_name'		=> $arr_week_name_list[$num_week],
		'week_num'		=> $num_week,
	);

	if(!empty($arr_holiday_list[$str_date])){
		$arr_result = array_merge($arr_result, $arr_holiday_list[$str_date]);
	}

	return $arr_result;
}

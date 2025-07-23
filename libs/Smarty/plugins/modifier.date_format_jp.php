<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty date_format_jp modifier plugin
 * Type:     modifier<br>
 * Name:     date_format_jp<br>
 * Purpose:  format datestamps via strftime<br>
 * Input:<br>
 *          - string: input date string
 *          - format: strftime format for output
 *          - default_date: default date if $string is empty
 *
 * @link   http://www.smarty.net/manual/en/language.modifier.date.format.php date_format (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com>
 *
 * @param string $string       input date string
 * @param string $format       strftime format for output
 * @param string $default_date default date if $string is empty
 * @param string $formatter    either 'strftime' or 'auto'
 *
 * @return string |void
 * @uses   smarty_make_timestamp(), date_format()
 */
function smarty_modifier_date_format_jp($string, $format = null, $default_date = '', $formatter = 'auto')
{
	// 既存のdate_formatを拡張するため、date_format内で行っているタイムスタンプ変換をここでも行う
	require_once(SMARTY_PLUGINS_DIR . 'shared.make_timestamp.php');
	if ($string != '' && $string != '0000-00-00' && $string != '0000-00-00 00:00:00') {
		$timestamp = smarty_make_timestamp($string);
	} elseif ($default_date != '') {
		$timestamp = smarty_make_timestamp($default_date);
	} else {
		return;
	}

	// 曜日
	// TODO:constant.phpもしくはsmarty.classもしくはviewSmartyに記述（constant.phpに仮記述）
	// TODO:曜日カラー設定方法検討
	$arr_week_name_list = array(
		SMARTY_WEEK_NAME_SUNDAY,
		SMARTY_WEEK_NAME_MONDAY,
		SMARTY_WEEK_NAME_TUESDAY,
		SMARTY_WEEK_NAME_WEDNESDAY,
		SMARTY_WEEK_NAME_THURSDAY,
		SMARTY_WEEK_NAME_FRIDAY,
		SMARTY_WEEK_NAME_SATURDAY,
	);

	if (strpos($format, '%w_jp') !== false) {
		if(isset($arr_week_name_list[date('w', $timestamp)])){
			$format = str_replace('%w_jp', $arr_week_name_list[date('w', $timestamp)], $format);
		}else{
			$format = str_replace('%w_jp', '', $format);
		}
	}

	require_once(SMARTY_PLUGINS_DIR . 'modifier.date_format.php');
	return smarty_modifier_date_format($string, $format, $default_date, $formatter);
}

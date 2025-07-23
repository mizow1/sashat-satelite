<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty mb_truncate modifier plugin
 * Type:     modifier<br>
 * Name:     mb_truncate<br>
 * Purpose:  マルチバイト対応のtruncate
 *
 * @link   http://webtech-walker.com/archive/2007/04/26154112.html
 * @author 
 *
 * @param string  $string      input string
 * @param integer $length      length of truncated text
 * @param string  $etc         end string
 * @param boolean $break_words truncate at word boundary(日本語にはないほうがいい機能。設定しても無効となる。)
 * @param boolean $middle      truncate in the middle of text
 * 
 * @return string truncated string
 */
function smarty_modifier_mb_truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false){
	if ($length == 0){
		return '';
	}

	if (mb_strlen($string,"UTF-8") > $length) {
		// 区切り文字数目の文字が句読点「。」で終わる場合は$etc付けずに返す
		if($string[$length] == '。'){
			return $string;
		
		// TODO:仕様決定後処理記載
		}elseif($string[$length] == '、'){
		}

		$length -= min($length, mb_strlen($etc, "UTF-8"));

		if ($middle) {
			$string = mb_substr($string, 0, $length / 2, "UTF-8") . $etc . mb_substr($string, - $length / 2, $length, "UTF-8");
		}else{
			$string = mb_substr($string, 0, $length,"UTF-8").$etc;
		}

		return $string;
	} else {
		return $string;
	}
}

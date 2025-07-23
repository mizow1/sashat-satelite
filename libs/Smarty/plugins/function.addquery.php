<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
function smarty_function_addquery($params,&$smarty)
{
	$querystring = $_GET;

	foreach($params as $key => $val){
		$querystring[$key] = $val;
	}
	return http_build_query($querystring);

}
?>

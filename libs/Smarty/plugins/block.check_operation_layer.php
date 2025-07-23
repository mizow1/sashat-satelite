<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
function smarty_block_check_operation_layer($params, $content, &$smarty, &$repeat)
{
	$operation_layer_id = empty($params["layer"]) ? 0:$params["layer"];
	if($smarty->getOperationLayer() <= $operation_layer_id || $smarty->getOperationLayer() == OPERATION_LAYER_SYSTEM_MASTER){
		return $content;
	}else{
		return "";
	}

}
?>

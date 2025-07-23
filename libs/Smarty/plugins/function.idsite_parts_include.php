<?php
function smarty_function_idsite_parts_include($params, &$template){
	if (empty($params['name'])) {
		return '';
	}
	$file_path = HTML_TEMPLATE_DIR.'default/pc_idsite/parts/'.$params['name'].HTML_TEMPLATE_EXTENSION;
	if(!file_exists($file_path)){
		return '';
	}
	$view = new ViewSmarty();
	$disp_array = $template->getTemplateVars();
	$disp_array = array_merge($disp_array, $params);
	$view->changeTemplate('default/pc_idsite/parts/');
	$view->changeCompileDir('default/pc_idsite/parts/');
	$view->setCompileId('default_pc_idsite_parts');
	$view->assign($disp_array);
	$view->clearCompiledTemplate($params['name'].HTML_TEMPLATE_EXTENSION,'default_pc_idsite_parts',SMARTY_LIFETIME);
	return $view->fetch($params['name'].HTML_TEMPLATE_EXTENSION);
}

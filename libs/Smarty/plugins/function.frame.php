<?php
require_once(MODEL.'front/widget/WidgetModelClass.php');
require_once(LIB_DIR."local/ChromePhp.php");

function smarty_function_frame($params, &$template){
	if (empty($params['name'])) {
		return '';
	}

	if(empty($params['name'])){
		return '';
	}
	
	$name = $template_name = $params['name'];//widget 名
	
	$widget_data = new WidgetModel(array("name" => $name));
	$data_array  = $widget_data->getWidgetData();//TODO 設定取得 => file から取得する方法も作る
	$widget_type = $data_array["type"];//widget タイプ

	$view = new ViewSmarty();
	$view->changeTemplate('default/widget/'.$widget_type);
	
	$template_name = $params['name'];
	
	if (!$view->templateExists($template_name.HTML_TEMPLATE_EXTENSION)) {
		ErrorLog::write('There is no template file : ['.$str_device_name.']'.$str_template_name.HTML_TEMPLATE_EXTENSION.';', '[widget]');
		return '';
	}
	
	$view->changeCompileDir('default/frame/');
	$view->setCompileId('default_frame_');

	return $view->fetch($template_name.HTML_TEMPLATE_EXTENSION);

}

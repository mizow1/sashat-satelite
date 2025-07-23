<?php
function smarty_function_pc_widget($params, &$template){
	if (empty($params['name'])) {
		return '';
	}

	// プレビューではgetで指定日を渡す予定
	$num_unix_datetime = empty($_GET['prev_datetime']) ? strtotime('now') : strtotime($_GET['prev_datetime']);

	// プレビュー指定日時が正常ではない場合、現日時を設定
	$num_unix_datetime = empty($num_unix_datetime) ? strtotime('now') : $num_unix_datetime;

	// fromとtoのunixtime変換
	if(!empty($params['from'])){
		$num_unix_from = strtotime($params['from']);
	}
	if(!empty($params['to'])){
		$num_unix_to = strtotime($params['to']);
	}

	// fromとtoの整合性チェック
	if(
		!empty($params['from']) &&
		empty($num_unix_from)
	){
		if(!empty($_GET['prev_datetime'])){
			return $params['name'].'：fromの設定日時が正しくありません';
		}else{
			$num_unix_from = '';
		}
	}
	if(
		!empty($params['to']) &&
		empty($num_unix_to)
	){
		if(!empty($_GET['prev_datetime'])){
			return $params['name'].'：toの設定日時が正しくありません';
		}else{
			$num_unix_to = '';
		}
	}
	if(
		!empty($num_unix_from) &&
		!empty($num_unix_to) &&
		$num_unix_from > $num_unix_to
	){
		if(!empty($_GET['prev_datetime'])){
			return $params['name'].'：toはfromより未来の日時を設定してください';
		}else{
			$num_unix_from = '';
			$num_unix_to = '';
		}
	}

	if(
		!empty($num_unix_from) &&
		$num_unix_from > $num_unix_datetime
	){
		return '';
	}

	if(
		!empty($num_unix_to) &&
		$num_unix_to < $num_unix_datetime
	){
		return '';
	}

	$disp_array = $template->getTemplateVars();
	$disp_array = array_merge($disp_array, $params);
	$str_device_name = empty($disp_array['device_name']) ? 'pc' : $disp_array['device_name'];

	if($str_device_name == 'pc_service'){
		$str_device_name = 'pc';
	}elseif($str_device_name == 'sp_service'){
		$str_device_name == 'sp';
	}

	$template_name = strtolower($params['name']);
	if(!empty($params['template_name'])){
		$template_name = $params['template_name'];
	}

	$view = new ViewSmarty();
	$view->changeTemplate('default/'.$str_device_name.'/widget/');

	// テンプレートファイルの存在確認
	if (!$view->templateExists($template_name.HTML_TEMPLATE_EXTENSION)) {
		ErrorLog::write('There is no template file : ['.$str_device_name.']'.$template_name.HTML_TEMPLATE_EXTENSION);
		return '';
	}

	$template_body = file_get_contents(HTML_TEMPLATE_DIR.'default/'.$str_device_name.'/widget/'.$template_name.HTML_TEMPLATE_EXTENSION);

	$ow_system_auto_widget_file_flag = false;
	$ow_system_auto_template_id_flag = false;

	$ow_system_auto_widget_file_matches = array();
	$ow_system_auto_template_id_matches = array();
	if(preg_match('/ow_system_auto_widget_file="(.*?)"/', $template_body, $ow_system_auto_widget_file_matches)){
		$ow_system_auto_widget_file_flag = true;
		$widget_file = $ow_system_auto_widget_file_matches[1];
	}
	if(preg_match('/ow_system_auto_template_id="(.*?)"/', $template_body, $ow_system_auto_template_id_matches)){
		$ow_system_auto_template_id_flag = true;
		$disp_array['template_id'] = $ow_system_auto_template_id_matches[1];
	}

	if(
		$params['name']!='mail_body' && 
		$params['name']!='mail_list' && 
		$params['name']!='mail_topics' && 
		$params['name']!='mail_dailypaper' && 
		(!$ow_system_auto_widget_file_flag || !$ow_system_auto_template_id_flag)
	){
		require_once(MODEL.'front/template/HtmlTemplateModelClass.php');
		$PCFrontHtmlTemplate = new PCFrontHtmlTemplateModel();
		$template_data = $PCFrontHtmlTemplate->getOpenHtmlTemplateData($template_name, TEMPLATE_TYPE_PARTS);
		// 未登録のテンプレート指定
		if(empty($template_data)){
			ErrorLog::write('['.$str_device_name.']'.$template_name.HTML_TEMPLATE_EXTENSION.' no data　in DB.');
			return '';
		}
		$widget_file = $template_data['widget_file'];
		$disp_array['template_id'] = $template_data['html_template_id'];
	}
	if(
		$params['name']=='mail_body' ||
		$params['name']=='mail_list' ||
		$params['name']=='mail_topics' ||
		$params['name']=='mail_dailypaper'
	){
		$widget_file = $params['name'];
	}

	// ウィジェット全般で使用可能なオプションパラメータ htmlタグ等は使用させない
	$disp_array['widget_option01'] = '';
	$disp_array['widget_option02'] = '';
	$disp_array['widget_option03'] = '';
	if(!empty($params['widget_option01'])){
		$disp_array['widget_option01'] = htmlspecialchars(strip_tags($params['widget_option01']),ENT_QUOTES);
	}
	if(!empty($params['widget_option02'])){
		$disp_array['widget_option02'] = htmlspecialchars(strip_tags($params['widget_option02']),ENT_QUOTES);
	}
	if(!empty($params['widget_option03'])){
		$disp_array['widget_option03'] = htmlspecialchars(strip_tags($params['widget_option03']),ENT_QUOTES);
	}

	// widgetを使用しない
	if($widget_file == TEMPLATE_WIDGET_SYSTEM_DISABLE){
		$view->changeCompileDir('default/'.$str_device_name.'/widget/');
		$view->setCompileId('default_'.$str_device_name.'_widget');
		$view->assign($disp_array);

		$view->clearCompiledTemplate($template_name.HTML_TEMPLATE_EXTENSION,'default_'.$str_device_name.'_widget',SMARTY_LIFETIME);
		return $view->fetch($template_name.HTML_TEMPLATE_EXTENSION);
	}

	// widgetが未設定もしくはデフォルト指定
	if(
		$widget_file == '' ||
		$widget_file == TEMPLATE_WIDGET_SYSTEM_DEFAULT
	){
		// def_widget が指定されていればそれをデフォルトのwidgetとして使用する
		// なければ template_name = widget_name
		$widget_name = empty($params['def_widget']) ? $template_name : strtolower($params['def_widget']);
	}
	// デフォルト以外でwidgetの使用設定がされている
	else{
		$widget_name = strtolower($widget_file);
		if(mb_substr($widget_name, -6) == 'widget'){
			$widget_name = mb_substr($widget_name, 0, mb_strlen($widget_name)-6);
		}
	}

	$class_name = ucwords($widget_name).'Widget';
	$class_file = WIDGET.$str_device_name.'/'.$class_name.'.php';

	if (file_exists($class_file)) {
		require_once(SMARTY_PLUGINS_DIR.'shared.get_pc_widget_params.php');
		$params = smarty_get_pc_widget_params($widget_name, $params);
		require_once($class_file);
		$widget = new $class_name($params, $disp_array, $str_device_name);
		$disp_array['widget_data'] = $widget->getData();
	}

	$view->changeCompileDir('default/'.$str_device_name.'/widget/');
	$view->setCompileId('default_'.$str_device_name.'_widget');
	$view->assign($disp_array);

	$view->clearCompiledTemplate($template_name.HTML_TEMPLATE_EXTENSION,'default_'.$str_device_name.'_widget',SMARTY_LIFETIME);
	return $view->fetch($template_name.HTML_TEMPLATE_EXTENSION);
}

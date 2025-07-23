<?php
function smarty_get_pc_widget_params($widget, $params){
	/* 20210316 tm_html_template_parameter テーブルを使用していないので、このテーブルからレコードを取得する処理をコメントアウトしておく
	require_once(MODEL.'administrator/template/HtmlTemplateParameterModelClass.php');
	$parameter = new PcHtmlTemplateParameterModel();
	$wDat = array(
		'widget' => $widget,
	);
	$expects = $parameter->getList($wDat);
	foreach ($expects as $key => $value) {
		// 引数が空の場合は初期値を入れていく
		if (empty($params[$value['name']])) {
			$params[$value['name']] = $value['default_value'];
		}
	}
	*/
	return $params;
}

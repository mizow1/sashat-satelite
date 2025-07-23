<?php
require_once(MODEL.'front/widget/WidgetModelClass.php');
require_once(LIB_DIR.'local/view/ViewSmarty.php');

require_once(LIB_DIR."local/ChromePhp.php");//for debug

function smarty_function_widget($params){

	if(empty($params['name'])){
		return '';
	}

	$name = $params['name'];//widge 名

	//device 名取得
	$device = "pc";
	if (strpos($_SERVER["REQUEST_URI"],'/sp/') !== false){
		$device = "sp";
	}

	$widget_data = new WidgetModel(array("name" => $name));
	$data_array  = $widget_data->getWidgetData();//TODO 設定取得 => file から取得する方法も作る
	if(empty($data_array)){
		return '';
	}
	//$data_array  = $widget_data->getWidgetDataFromFile();
	//$device_name = $data_array["device"];//デバイス名

	$data_array["device"] = $device;

	$widget_type = $data_array["type"];//widget タイプ
	$class_name  = ucwords(strtolower($name)).'Widget';
	//$class_file  = WIDGET.$device_name.'/'.$class_name.'.php';//使用する class file を指定
	$class_file  = WIDGET.$widget_type.'/'.$class_name.'.php';//使用する class file を指定

	$display = "";//表示 html
	if(file_exists($class_file)){

		require_once($class_file);
		$widget  = new $class_name($data_array);//クラスｗｐインスタンス化
		$display = $widget->getData();
	}

	//ViewSmarty
	$vs      = new ViewSmarty();
	$display = $vs->replace($display);

	return $display;
}

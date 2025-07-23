<?php
abstract class AbstractController implements ControllerInterface{
	protected $controller;
	protected $action;
	protected $session_data;
	protected $device;
	protected $view;

	public function init($controller='',$action='',$session_data=array(),$device=''){
		$this->controller = $controller;
		$this->action = $action;
		$this->session_data = $session_data;
		$this->device = $device;
		$this->view = new ViewSmarty();

		$this->view->changeTemplate('index/default_'.$this->device.'/');
		$this->view->changeCompileDir('');

//		$this->view->setCompileId('default_'.$this->device.'_'.$this->controller.'_');
//		$this->view->assignGlobal('device_name', $device);
	}

	public function display($disp_array=array(),$template_name='',$encode=''){

		$template_name = empty($template_name) ? $this->action : $template_name;

		$this->view->assign($disp_array);
		
		echo $this->view->fetch($template_name.HTML_TEMPLATE_EXTENSION,$encode);
	}

	public function fetch($disp_array=array(),$template_name='',$encode=''){
		$this->view->assign($disp_array);
		return $this->view->encFetch($template_name.HTML_TEMPLATE_EXTENSION,$encode);
	}

	public function checkTemplate($template_name){
		return $this->view->templateExists($template_name.HTML_TEMPLATE_EXTENSION);
	}


	// 会員状態により特定機能が使用可能か判定
	protected function isAvailableAction($str_controller,$arr_user_data,$str_action = 'main'){
		if(empty($str_controller)){
			return false;
		}
		switch(true){
			case $str_controller == 'article' && $str_action == 'clip':
				return !empty($arr_user_data['is_sandigi_member']);
				break;
			default:
				return false;
				break;
		}
	}

	public function toMultibyteErrorFormat($input_data,$error_data){
		if(empty($error_data)){
			return array();
		}
		foreach ($error_data as $key_1 => $variable) {
			foreach ($variable as $key_2 => $value) {
				if($key_2 == 'MultiByte3'){
					// スマホの絵文字
					if(preg_match('/[\xF0-\xF7][\x80-\xBF][\x80-\xBF][\x80-\xBF]/',$input_data[$key_1],$matches_smart_phone)){
						$error_data[$key_1][$key_2] = $value.'「'.$matches_smart_phone[0].'」';
					}
				}
			}
		}
		return $error_data;
	}

}

interface ControllerInterface{
	public function Execute($get_data=array(),&$session_data=array());
	public function PostExecute($get_data=array(),$post_data=array(),$file_data=array(),&$session_data=array());
}

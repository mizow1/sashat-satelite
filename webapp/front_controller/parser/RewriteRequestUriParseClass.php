<?php
class RewriteRequestUriParse{

	private $device;

	public function __construct(){
		$request_uri = $_SERVER['REQUEST_URI'];

		if(strpos($request_uri,'?')){
			// getパラメータは削除
			$request_uri = preg_replace('/\?.*/', '', $request_uri);
		}
		if(strpos($request_uri,'/sp/') === 0){
			$this->device = 'sp';
			$request_uri = preg_replace('/^\/sp\//', '/', $request_uri);
		}else{
			$this->device = 'pc';
		}
		$string = explode('/',$request_uri);
		$uri_param_list = array();
		foreach($string as $item){
			if($item != ''){
				$uri_param_list[] = $item;
			}
		}

		$this->prepareDefault($uri_param_list);
		return;

		throw new RuntimeException(404);
	}


	private function prepareDefault($uri_param_list){


		switch(true){
			case !empty($_GET['menu']) :
				$_GET['action'] = 'Menu';
				break;
			case !empty($uri_param_list[1]) :
				$_GET['action'] = $uri_param_list[1];
				break;
			defalut:
				break;
		}
		return ;
	}

	private function preparePage($uri_param_list){
		if (empty($uri_param_list[0])) {
			return;
		}
		$_GET['controller'] = $uri_param_list[0];
		$_GET['action'] = 'main';
		if(!empty($uri_param_list[1])){
			$_GET['page'] = $uri_param_list[1];
		}
	}

}

<?php
require_once(FRONT_CONTROLLER.'parser/RewriteRequestUriParseClass.php');

abstract class FrontController{

	const DEFAULT_ACTION     = 'Top';

	function __construct($device){
		//キャッシュ対策
		header("Cache-Control: no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		
		new RewriteRequestUriParse($device);
		
		if(
			empty($_GET['action'])
			&& strpos($_SERVER['REQUEST_URI'],'/web') !== false
		){
			header('location: /');
			return;
		}
		
		$controller = '';
		$action = empty($_GET['action']) ? self::DEFAULT_ACTION : ucfirst(strtolower($_GET['action']));
		$action_class = $action.'Action';
		$class_file = CONTROLLER.'uranai_satellite/'.$action_class.'.php';

		if (!file_exists($class_file)) {
			$action       = self::DEFAULT_ACTION;
			$action_class = $action.'Action';
			$class_file   = CONTROLLER.'uranai_satellite/'.$action_class.'.php';
		}
		
		require_once($class_file);
		$instance = new $action_class($controller,$action,$sessions,$device);

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$instance->PostExecute($_GET,$_POST,$_FILES,$sessions);
		} else {

			$instance->Execute($_GET,$sessions);
		}
	}

}

class PcFrontController extends FrontController{
	public function __construct(){
		parent::__construct('pc');
	}
}
class SpFrontController extends FrontController{
	public function __construct(){
		parent::__construct('smp');
	}
}

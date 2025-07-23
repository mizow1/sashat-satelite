<?php
require_once(MODEL.'administrator/settings/SettingsModelClass.php');
class ImageSizeModel{
	private $size;

	public function __construct(){
		$this->size = $this->getSizeArray();
	}
	
	public function getSize(){
		return $this->size;
	}
	public function getSizeByTarget($target){
		foreach($this->size as $size){
			if($size['target'] == $target){
				return $size;
			}
		}
		return array();
	}
	//表画像
	private function getSizeArray(){
		//初期設定
		$objSettingsModel = new SettingsModel();
		$arr_default = $objSettingsModel->getAll();
		$arr_article_image_size_list = array();
		foreach($arr_default as $str_key => $str_value){
			if(strstr($str_key, 'article_image_size_name')){
				$str_end = substr($str_key, -1);
				$arr_article_image_size_list[$str_end]['name'] = $str_value;
				continue;
			}
			if(strstr($str_key, 'article_image_size_w')){
				$str_end = substr($str_key, -1);
				$arr_article_image_size_list[$str_end]['w'] = $str_value;
				continue;
			}
			if(strstr($str_key, 'article_image_size_h')){
				$str_end = substr($str_key, -1);
				$arr_article_image_size_list[$str_end]['h'] = $str_value;
				continue;
			}
		}

		$arr_return = array();
		foreach($arr_article_image_size_list as $arr_article_image_size){
			$arr_tmp = array(
				array(
					'w'	 => $arr_article_image_size['w'],
					'h'	 => $arr_article_image_size['h'],
					'type'	 => 0,
					'target' => $arr_article_image_size['name'],
					'change' => 'reducing',	// 短辺に合わせる場合trimming
					'back'	 => '',
				)
			);
			$arr_return = array_merge($arr_return, $arr_tmp);
		}
		return $arr_return;
	}
	public function getReducingSize($w,$h,$iw,$ih){
		$rate = 1;
		if($iw > $w && $w != 0){
			$rate = $w/$iw;
		}
		if($ih > $h && $h != 0){
			$tmp = $h/$ih;
			if($rate > $tmp){
				$rate = $tmp;
			}
		}
		$result['w'] = (int)($iw*$rate);
		$result['h'] = (int)($ih*$rate);
		return $result;
	}
	public function getTrimmingSize($w,$h,$iw,$ih){
		$rate = 1;
		if($iw > $w && $w != 0){
			$rate = $w/$iw;
		}
		if($ih > $h && $h != 0){
			$tmp = $h/$ih;
			if($rate < $tmp){
				$rate = $tmp;
			}
		}
		$result['w'] = (int)($iw*$rate);
		$result['h'] = (int)($ih*$rate);
		return $result;
	}
}
?>

<?php
require_once(LIB_DIR.'local/image/GDLibClass.php');
class ImageClass {
	public function __construct(){
	}
	
	//画像作成
	public function createImage($width=90,$height=90,$target="",$receive_file="",$target_path="",$file_name=""){
		if( empty($target) || empty($receive_file) || empty($target_path) || empty($file_name) ){
			return;
		}
		
		$GD = new GDLib($receive_file);
		if($GD->ready()){
			if( $target == "original" ){
				$GD->OutputJpg(null,null,$target_path.$file_name);
			}else{
				//縮小率取得
				$src = $GD->getImageSize();
				$rate = $this->getImageRate($width,$height,$src);
				
				$w = $src['w']*$rate;
				$h = $src['h']*$rate;
				
				$GD->ResizeImage($w,$h);
				$rsrc = $GD->getImageResorce();
				$GD2 = new GDLib(LIB_DIR.'local/image/thumb_box.png');
				$GD2->CompositionResorce($rsrc,$w,$h,(($width/2)-($w/2)),(($height/2)-($h/2)) );
				$GD2->OutputJpg($width,$height,$target_path.$file_name);
			}
		}
		//shell_exec('/bin/sh /home/webmaster/sh/image_sync.sh public_html/image/');
	}
	
	//縮小率取得
	public function getImageRate($width=0,$height=0,$src=array()){
		$rate = 1;
		if( $src['w'] > $width && $width != 0){
			$rate = $width / $src['w'];
		}
		if( $src['h'] > $height && $height != 0){
			$tmp = $height / $src['h'];
			if($rate > $tmp){
				$rate = $tmp;
			}
		}
		return $rate;
	}
}
?>

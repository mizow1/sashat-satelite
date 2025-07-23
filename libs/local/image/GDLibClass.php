<?php
/************************************************
* version:0.2
************************************************/
class GDLib{
	private $image = null;
	private $size = array();

	public function __construct($base_img_path=""){
		if(!empty($base_img_path) && file_exists($base_img_path) ){
			$this->image = $this->loadImage($base_img_path,$this->size);
			if($this->image === false){
				$this->image = null;
			}else{
				$img = imagecreatetruecolor($this->size['w'],$this->size['h']);
				//透過処理
				imagealphablending($img, false);
				imageSaveAlpha($img, true);
				$fillcolor = imagecolorallocatealpha($img, 0, 0, 0, 127);
				imagefill($img, 0, 0, $fillcolor);

				imagecopy($img,$this->image,0,0,0,0,$this->size['w'],$this->size['h']);
				$this->image = $img;
			}
		}
	}

	public function getImageResorce(){
		return $this->image;
	}

	public function getImageSize(){
		return $this->size;
	}

	public function ready(){
		return $this->image === null?false:true;
	}

	/************************************************
	* args:
	* image_list = array(
	*    array(
	*        image_path,
	*        x,
	*        y
	*    )...
	* )
	************************************************/
	public function CompositionList($image_list){
		foreach($image_list as $file){
			if(!$this->Composition($file['image_path'],$file['x'],$file['y']))return false;
		}
		return true;
	}

	public function Composition($image_path="",$x="",$y=""){
		if($this->image === null)return false;
		$size = array();
		$img = $this->loadImage($image_path,$size);
		if($img === false)return false;
		imagecopy($this->image,$img,$x,$y,0,0,$size['w'],$size['h']);
		return true;
	}

	public function CompositionResorce($img,$w="",$h="",$x="",$y=""){
		if($this->image === null)return false;
		if($img === false)return false;
		imagecopy($this->image,$img,$x,$y,0,0,$w,$h);
		return true;
	}

	public function OutputJpg($num_width="",$num_height="",$str_path=null,$num_quality=80,$boo_sharp=true){
		$arr_img = $this->Output($num_width,$num_height,$str_path);
		if($boo_sharp){
			$arr_sharpenMatrix = array(
				array(-0.0, -1.0, -0.0),
				array(-1.0, 33, -1.0),
				array(-0.0, -1.0, -0.0),
			);
			$arr_divisor = array_sum(array_map('array_sum', $arr_sharpenMatrix));
			$num_offset = 0;
			// apply the matrix
			imageconvolution($arr_img, $arr_sharpenMatrix, $arr_divisor, $num_offset);
		}
		if(empty($str_path))header("Content-Type:image/jpeg");
		imagejpeg($arr_img,$str_path,$num_quality);
		imagedestroy($arr_img);
	}
	public function OutputPng($width="",$height="",$path=null){
		$img = $this->Output($width,$height,$path);
		if(empty($path))header("Content-Type:image/png");
		imagepng($img,$path,0);
	}
	public function OutputGif($width="",$height="",$path=null){
		$img = $this->Output($width,$height,$path);
		if(empty($path))header("Content-Type:image/gif");
		imagegif($img,$path,100);
	}

	public function ResizeImage($w=1,$h=1){
		$this->image = $this->resize($this->image,$w,$h);
		$this->size['w'] = imagesx($this->image);
		$this->size['h'] = imagesy($this->image);
	}

	private function Output($width="",$height="",$path=""){
		$width = empty($width)?$this->size['w']:$width;
		$height = empty($height)?$this->size['h']:$height;
		$img = $this->resize($this->image,$width,$height);
		// if(empty($path))header("Content-Type : image/png");
		return $img;
	}

	private function resize($base_img,$width,$height){
		$img = imagecreatetruecolor($width,$height);
		//透過処理
		imagealphablending($img, false);
		imageSaveAlpha($img, true);
		$fillcolor = imagecolorallocatealpha($img, 0, 0, 0, 127);
		imagefill($img, 0, 0, $fillcolor);

		imagecopyresampled($img,$base_img,0,0,0,0,$width,$height,$this->size['w'],$this->size['h']);
		return $img;
	}

	private function loadImage($file_name,&$size=array()){
		$array = getimagesize($file_name);
		$tmp = explode('/',$array['mime']);
		$ext = array_pop($tmp);
		$img = null;
		if($ext == 'png'){
			$img = imagecreatefrompng($file_name);
		}
		elseif($ext == 'gif'){
			$img = imagecreatefromgif($file_name);
		}
		elseif($ext == 'jpg' || $ext == 'jpeg'){
			$img = imagecreatefromjpeg($file_name);
		}
		else{
			return false;
		}
		$size['w'] = $array[0];
		$size['h'] = $array[1];
		return $img;
	}
	public function CropImage($dx="",$dy="",$sx="",$sy="",$w="",$h=""){
		if($this->image === null)return false;
		$img = imagecreatetruecolor($w,$h);
		if($img === false)return false;
		imagecopy($img,$this->image,$dx,$dy,$sx,$sy,$w,$h);

		$this->image = $img;
		$this->size['w'] = imagesx($img);
		$this->size['h'] = imagesy($img);
		return true;
	}
}
?>

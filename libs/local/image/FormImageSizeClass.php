<?php
class FormImageSizeModel{
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

	private function getSizeArray(){
		return array(
			array('w'=>100,'h'=>100,'target'=>'small'),
			array('w'=>200,'h'=>200,'target'=>'large'),
		);
	}

}

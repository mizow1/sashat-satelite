<?php
class PdfClass {
	public function __construct(){
	}

	// JPG画像のPDFへの変換
	public function jpeg2pdf($arr_image_file_name, $str_pdf_file_name){
		if(
			empty($arr_image_file_name) ||
			empty($str_pdf_file_name)
		){
			return false;
		}
		$str_files = implode(' ', $arr_image_file_name);
		$str_command = '/usr/bin/convert -limit area 512mb '.$str_files.' '.$str_pdf_file_name.' 2>/dev/null';
		exec($str_command,$str_out);
		if(!empty($str_out['message'])){
			ErrorLog::write($str_out);
			return false;
		}
		return true;

	}

	// PDFからJPG画像への変換
	public function pdf2image($str_pdf_file_name,$arr_image_file_name){
		if(
			empty($arr_image_file_name) ||
			empty($str_pdf_file_name)
		){
			return false;
		}
		$str_command = '/usr/bin/convert -density 200 -colorspace RGB -resample 100 -alpha remove -geometry 450 '.$str_pdf_file_name.'[0] '.$arr_image_file_name.' 2>/dev/null';
		exec($str_command,$str_out);
		if(!empty($str_out['message'])){
			ErrorLog::write($str_out);
			return false;
		}
		return true;

	}
}

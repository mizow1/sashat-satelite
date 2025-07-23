<?php

require_once(LIB_DIR."local/ChromePhp.php");

abstract class AbstractWidget implements WidgetInterface{

	protected $params;
	protected $vars;
	protected $device;

	public function __construct($params, $vars, $device){
		$this->params = $params;
		$this->vars   = $vars;
		$this->device = $device;
		$this->vars['session_data'] = empty($this->vars['session_data']) ? array() : $this->vars['session_data'];
		$this->vars['session_data']['user'] = empty($this->vars['session_data']['user']) ? array() : $this->vars['session_data']['user'];
	}

	/**
	 * 項目（li）を整形して返す
	 *
	 * @list    string[] An array of string objects.
	 * @sup     number   Superior number of return.
	 * @file    string   Html file includes "li".
	 * @wording string   Return words when $list has no data.
	 *
	 */
	protected function setArticle($list,$sup,$file,$wording){

		// n 件のチェック
		if(!is_numeric($sup)){
			//return "ERROR!";
			return false;
		}

		$str_row_base = file_get_contents($file);
		$str_li       = "";
		$arr_rep      = array(
			"<!--{REP_URL}-->",
			"<!--{REP_TEXT}-->",
		);

		if(count($list)==0){
			return str_replace($arr_rep,array("javascript:void(0)",$wording),$str_row_base);
		}

		$num_count = 1;
		foreach($list as $key => $val){

			if($sup > 0 && $num_count > $sup){
				$num_count++;
				continue;
			}

			$data_array = array($val['url'],$val['keyword']);
			$str_li    .= str_replace($arr_rep,$data_array,$str_row_base);

			$num_count++;
		}

		return $str_li;
	}

	/**
	 * returnFileName
	 * パーツファイル名を返す
	 *
	 * @param string $file_name
	 *
	 * @return string
	 *
	 */
	protected function returnFileName($params){

		$str_dir = WEBAPP."view/default/widget/".$this->params["type"]."/";

		if(array_key_exists("file_name", $params) && file_exists($str_dir.$params["file_name"].".html")){
			return $str_dir.$params["file_name"].".html";
		}

		return WEBAPP."view/default/widget/dummy.html";
	}


	/**
	 * ベース、パーツファイルを取得する
	 *
	 * @param string $file_name File name which is base file or parts file.
	 */
	public function setHtmlFile($file_name) {

		$html = $this->returnFileName(array("file_name"=>$file_name));

		$html_file  = "";
		if(file_exists($html)){
			$html_file = file_get_contents($html);
		}

		return $html_file;
	}


	/**
	 * 複数のベース、パーツファイル配列としてを取得する
	 *
	 * @param string $file_list ファイル名を「,」区切りで連結した文字列
	 */
	public function setHtmlFileList($file_list){
		$file_list = str_replace(" ", "", $file_list);
		if(strpos($file_list,',') === false){
			return array(
				$file_list => $this->setHtmlFile($file_list),
			);
		}
		$tmp = explode(',',$file_list);
		$return = array();
		foreach($tmp as $file_name){
			$return[$file_name] = $this->setHtmlFile($file_name);
		}
		return $return;
	}




	/**
	 * マッピング表を作成する
	 *
	 * @param array $data Data acquired from the database.
	 * @return array $map Key array which is corresponding to replacement string.
	 */
	public function setMappingTable($data = array()){

		if(!is_array($data)){
			return array();
		}

		$data2 = array_shift($data);

		if(!is_array($data2)){
			return array();
		}

		$map = array();
		foreach($data2 as $key => $val){

			$cap = strtoupper($key);
			$map["<!--{REP_{$cap}}-->"] = $key;
		}

		return $map;
	}


	/**
	 * 置換文字列のマッピング
	 *
	 * @param array $data Data acquired from the database.
	 * @param array $map Mapping table.
	 * @return array $return Data array which is corresponding to replacement string.
	 */
	public function replaceMapping($data = array(), $map = array()){

		$return = array();

		if(!is_array($data) || !is_array($map)){
			return $return;
		}

		foreach($map as $key => $val){

			$return[$key] = array_key_exists($val, $data) ? $data[$val] : "";
		}

		return $return;
	}


	/**
	 * Request を分析する
	 *
	 * @param int $number Number which you need.
	 *
	 * @return string $req Result which you need.
	 */
	public function RequestAnalysis($number = 0){

		$req = explode("/", $_SERVER["REQUEST_URI"]);

		return $req[$number];
	}


	/**
	 * widget 内で pagination を作成する
	 *
	 *
	 * @param array $page_array
	 *
	 * @return string
	 *
	 */
	protected function setPagination($page_array){
		// 1ページしか存在しない場合はページネーションを表示しない
		if($page_array["max"]==1){
			return '';
		}

		$fab  = 3;//front and back
		$max  = $page_array["max"];//最終頁
		$page = $page_array["page"];//頁No.
		$pnav = "";//pagination navigation
		$prev = $page_array['prev'];//previous page
		$next = $page_array['next'];//next page
		$foot = "";//pagination navigation right side

		$uri = $page_array['uri'];

		//$arg = '?page=';
		$arg = '';
		$query = '';
		if(!empty($_SERVER['QUERY_STRING'])){
			$query = strpos($uri.$arg.$prev,'?') === false ? '?'.$_SERVER['QUERY_STRING'] : '&'.$_SERVER['QUERY_STRING'] ;
		}

		$pnav .= "<ul class='pagenation'>";

		if($page>1){
			$pnav .= "<li class='prev' style='margin-right: 5px;'>";
			$pnav .= "<a href='{$uri}{$arg}{$prev}{$query}'>&lt;&lt;&nbsp;前へ</a>";
			$pnav .= "</li>";
		}

		for($i=1;$i<=$max;$i++){

			$span_before = "";
			$span_after  = "";
			$a           = "<a href='{$uri}{$arg}{$i}{$query}' style=''>{$i}</a>";

			if($i==$page){

				$span_before = "<span>";
				$span_after  = "</span>";
				$a           = $i;

				if($i<$max){
					$foot .= "<li class='next'>";
					$foot .= "<a href='{$uri}{$arg}{$next}{$query}'>次へ&nbsp;&gt;&gt;</a>";
					$foot .= "</li>";
				}
			}

			if($i==1 || $i==$max || ($i==3 && $page==1 && $max>=3) || ($i==$max-2 && $page==$max) || ($page-1<=$i && $i<=$page+1)){
				$pnav .= "<li>{$span_before}{$a}{$span_after}</li>";
			}elseif($i==$page-2 || $i==$page+2 || ($i==4 && $page==1) || ($i==$max-3 && $page==$max)){
				$pnav .= "<li>…</li>";
			}
		}

		$pnav .= "{$foot}</ul>";

		return $pnav;
	}

	/**
	 * マッピング配列とパーツHTMLからリプレイス後htmlを作成
	 *
	 * $this->setMappingTable()からstr_replace()までのまとめ関数
	 *
	 * @author nishimura
	 * @param array $mapping_array
	 * @param string $parts_html
	 * @return string
	 */
	public function createHtmlFromMappingTable($mapping_array, $parts_html){
		$map = $this->setMappingTable(array($mapping_array));
		$replace = $this->replaceMapping($mapping_array,$map);//置換データのマッピング
		$rep_key = array_keys($replace);
		return str_replace($rep_key, $replace, $parts_html);
	}

	/**
	 * 記事データが存在しない場合に表示するhtmlを作成
	 * @param string $parts_nodata 記事データが存在しない場合に表示するhtml
	 */
	public function setNodataPartsHtml($parts_nodata = '',$error_message = '',$parts_title = ''){
		if(empty($error_message)){
			$error_message = '只今準備中です。';
		}
		if(empty($parts_nodata)) {
			$default_parts = HTML_TEMPLATE_DIR.'default/widget/etc/nodata_parts.html';
			if(!file_exists($default_parts)){
				return '';
			}
			$parts_nodata = file_get_contents($default_parts);
			if(!empty($parts_title)){
				$rep_key = array(
					'<!--{REP_TITLE}-->',
					'<!--{REP_ERROR_MESSAGE}-->',
				);
				$replace = array(
					$parts_title,
					$error_message,
				);
				$parts_nodata = str_replace($rep_key,$replace,$parts_nodata);
			}else{
				$parts_nodata = str_replace('<!--{REP_ERROR_MESSAGE}-->',$error_message,$parts_nodata);
			}
		}
		return $parts_nodata;
	}

	/*
		URL引数を使った一覧の日付絞り込みを行う際に引数のチェックを行う

	 */
	public function checkTargetDate(){
		if(empty($_GET['year'])){
			return false;
		}
		if(
			!is_int((int)$_GET['year']) ||
			strlen($_GET['year']) != 4
		){
			return false;
		}
		if(empty($_GET['month'])){
			return true;
		}
		if(
			!is_int((int)$_GET['month']) ||
			$_GET['month'] < 0 ||
			$_GET['month'] > 12
		){
			return false;
		}
		return true;
	}

}

interface WidgetInterface{
	public function getData();
}

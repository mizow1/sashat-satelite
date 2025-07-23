<?php



/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
function smarty_function_site_paginate_photograph_detail($params, &$smarty){

	$querystring = $_GET;

	$type = "";

	if(!empty($params["type"])){
		$type = "/".$params["type"];
	}

	$html = '';

	if($params["max_page"] != "1"){

		if(empty($params["type"])){

			$html  = '<ul class="pagenation"><li class="prev" style="margin-right: 5px;">';

			if(!empty($params["prev_page"])){

				$querystring["page"] = $params["prev_page"];

				$html .= '<a href="'.$type.mokahoshi_create_qauery($querystring).'">';
				$html .= "&lt;&lt;&nbsp;前へ</a>";
			}else{
				$html .= '&lt;&lt;&nbsp;前へ';
			}
			$html  = '</li>';

			$querystring["page"] = "1";

			$start = ($params["page"]-5 < 1)                   ? 1                   : $params["page"]-3;
			$last  = ($params["page"]+5 > $params["max_page"]) ? $params["max_page"] : $params["page"]+3;

			if($start > 1){
				$html .= '<li style="margin-right: 5px;"><a href="'.$type.mokahoshi_create_qauery($querystring).'">1</a></li>';
				$html .= '<li style="margin-right: 5px;">…</li>';
			}

			for($i = $start;$i <= $last;$i++){

				$querystring["page"] = $i;

				$html .= '<li style="margin-right: 5px;">';
				if($i != $params["page"]){
					$html .= '<a href="'.$type.mokahoshi_create_qauery($querystring).'">'.$i.'</a>';
				}else{
					$html .= "<span>{$i}</span>";
				}
				$html .= "</li>";
			}

			$querystring["page"] = $params["max_page"];

			if($last < $params["max_page"]){
				$html .= '<li style="margin-right: 5px;">…</li>';
				$html .= '<li><a href="'.$type.mokahoshi_create_qauery($querystring).'">'.$params["max_page"].'</a></li>';
			}

			$html .= '<li class="next">';
			if(!empty($params["next_page"])){

				$querystring["page"] = $params["next_page"];

				$html .= '<a href="'.$type.mokahoshi_create_qauery($querystring).'">次へ&nbsp;&gt;&gt;</a>';
			}else{
				$html .= '次へ&nbsp;&gt;&gt;';
			}
			$html .= '</li></ul>';
		}
	}

	return $html;
}

function mokahoshi_create_qauery($query){

	$tmp = array();

	if(!empty($query['controller'])) $tmp[] = $query['controller'];
	if(!empty($query['action']))     $tmp[] = $query['action'];
	if(!empty($query['id']))         $tmp[] = $query['id'];

	$tmp[] = $query['page'];

	return "/".implode('/',$tmp);
}
?>

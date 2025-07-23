<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
function smarty_function_paginate($params, &$smarty)
{

	$querystring = $_GET;
	//画像管理で必要（タブごとにページネーションする為）
	if(!empty($params["category_id"])) $querystring["category_id"] = $params["category_id"];
	$html = '';
	if($params["max_page"] != "1"){
		$html  = '<ul class="pagination clearfix">';
		if(!empty($params["prev_page"])){
			$querystring["page"] = "1";
			$html .= '<li class="first"><a href="?'.http_build_query($querystring).'" class="button-blue">First</a></li>';
			$querystring["page"] = $params["prev_page"];
			$html .= '<li class="prev"><a href="?'.http_build_query($querystring).'" class="button-blue">≪</a></li>';
		}
		$start = ($params["page"]-5 < 1)?1:$params["page"]-5;
		$querystring["page"] = "1";
		$last  = ($params["page"]+5 > $params["max_page"])?$params["max_page"]:$params["page"]+5;

		if($start > 1)$html .= '<li class="page"><a href="?'.http_build_query($querystring).'" class="button-blue">1</a></li><li>…</li>';

		for($i = $start;$i <= $last;$i++){
			$querystring["page"] = $i;
			$html .= '<li class="page"><a href="?'.http_build_query($querystring).'" class="button-blue';
			if($i == $params["page"]){
				$html .= " current";
			}
			$html .= '">'.$i.'</a></li>';
		}

		$querystring["page"] = $params["max_page"];
		if($last < $params["max_page"])$html .= '<li>…</li><li class="page"><a href="?'.http_build_query($querystring).'"" class="button-blue">'.$params["max_page"].'</a></li>';

		if(!empty($params["next_page"])){
			$querystring["page"] = $params["next_page"];
			$html .= '<li class="next"><a href="?'.http_build_query($querystring).'" class="button-blue">≫</a></li>';
			$querystring["page"] = $params["max_page"];
			$html .= '<li class="last"><a href="?'.http_build_query($querystring).'" class="button-blue">Last</a></li>';
		}

		if(!empty($params["max_rec"])){
			$html .= '<li style="float:right;padding-right: 20px;font-size: 13px;">全 '.$params["max_rec"].' 件</li>';
		}

		$html .= '</ul>';
	}elseif(!empty($params["max_rec"])){
		$html  = '<ul class="pagination clearfix">';
		$html .= '<li style="float:right;padding-right: 20px;font-size: 13px;">全 '.$params["max_rec"].' 件</li>';
		$html .= '</ul>';
	}
	return $html;

}
?>

<?php
abstract class AbstractIdsiteModel{

	public function getFormDefaultArray(){
		return array(
			'password' => '','password_confirmation' => '',
			'zip_1' => '','zip_2' => '','prefecture' => '',
			'address_1' => '','address_2' => '','address_3' => '',
			'tel1' => '','tel2' => '','tel3' => '',
			'fax1' => '','fax2' => '','fax3' => '',
			'shop_tel1' => '','shop_tel2' => '','shop_tel3' => '',
			'subscribe_paper' => '',
			'corp_name' => '','corp_kana' => '','department'=>'',
			'demand_zip_1' => '','demand_zip_2' => '','demand_prefecture' => '',
			'demand_address_1' => '','demand_address_2'=>'','demand_department'=>'',
			'demand_tel1' => '','demand_tel2' => '','demand_tel3' => '',
			'demand_fax1' => '','demand_fax2' => '','demand_fax3' => '',
			'is_oversea' => '','purchase'=>'',
		);
	}
}

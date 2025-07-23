function inputCheck(thisFrom){
	var pet_name = $('form1').pet_name.value;
	var pet_kind = $('form1').pet_kind.value;
	var pet_age  = $('form1').pet_age.value;
	var pet_sex  = $('form1').pet_sex.value;
	var photoimage = $('form1').photofile.value;
	var mail_addr = $('form1').mail_address.value;
	var pet_prefecture = $('form1').pet_prefecture.value;

	var message	="";
	
	message += pnameCheck( pet_name );
	message += pkindCheck( pet_kind );
	message += pageCheck( pet_age );
	message += psexCheck( pet_sex );
	message += pphotoCheck( photoimage );
	message += pmailCheck( mail_addr );
	message += ppreCheck( pet_prefecture );
	
	if( message == "" ) {
		$('form1').submit();
		return;
	}
	alert("\n\n\n"+message+"\n\t\t\t\t\t");

}
function inputCheck2(thisFrom){
	var pet_name = $('form1').pet_name.value;
	var pet_kind = $('form1').pet_kind.value;
	var pet_age  = $('form1').pet_age.value;
	var pet_sex  = $('form1').pet_sex.value;
	var photoimage = $('form1').photofile.value;
	var pet_photo = $('form1').pet_photo.checked;
	var mail_addr = $('form1').mail_address.value;
	var pet_prefecture = $('form1').pet_prefecture.value;

	var message	="";
	
	message += pnameCheck( pet_name );
	message += pkindCheck( pet_kind );
	message += pageCheck( pet_age );
	message += psexCheck( pet_sex );
	message += photoCheck( photoimage,pet_photo );
	message += pmailCheck( mail_addr );
	message += ppreCheck( pet_prefecture );
	
	if( message == "" ) {
		$('form1').submit();
		return;
	}
	alert("\n\n\n"+message+"\n\t\t\t\t\t");

}
function pphotoCheck( val) {
	
	var message = "";
	if( val  == "" ) {
		message = "画像がアップロードされていません\n\n";
		return message;
	}
	return message;
}
function photoCheck( val,val2) {
	var message = "";
	if( val  == "" && val2 != 1) {
		message = "画像が選択されていません\n\n";
		return message;
	}
	return message;
}
function pnameCheck( val) {
	
	var message = "";
	if( val  == "" ) {
		message = "ペットの名前が未入力です\n\n";
		return message;
	}
	return message;
}

function pkindCheck( val) {
	
	var message = "";
	if( val  == "" ) {
		message = "ペットの種類が未入力です\n\n";
		return message;
	}
	return message;
}
function pageCheck( val) {
	
	var message = "";
	if( val  == "" ) {
		message = "年齢が未選択です\n\n";
		return message;
	}
	return message;
}
function psexCheck( val) {
	
	var message = "";
	if( val  == "" ) {
		message = "性別が未選択です\n\n";
		return message;
	}
	return message;
}
function pmailCheck( val) {
	
	var message = "";
	if( val  == "" ) {
		message = "メールアドレスが未入力です\n\n";
		return message;
	}
	return message;
}
function ppreCheck( val) {
	
	var message = "";
	if( val  == "" ) {
		message = "ペットの自慢項目が未入力です\n\n";
		return message;
	}
	return message;
}

//input check
function contestsCheck( thisForm ){

	//id
	var site_id	= thisForm.id.value;

	//name
	var site_name = thisForm.name.value;

	//url
//	var start_date	= thisForm.start_yy.value+"-"+thisForm.start_mm.value+"-"+thisForm.start_dd.value;
//	var end_date	= thisForm.end_yy.value+"-"+thisForm.end_mm.value+"-"+thisForm.end_dd.value;

	var message	="";
	
//	message += idCheck( site_id );
//	message += nameCheck( site_name );
//	message += urlCheck( site_url );
	
	if( message == "" ) {

		thisForm.submit();
		return;
	}
	alert("\n\n\n"+message+"\n\t\t\t\t\t");
}	
function c_nameCheck( val) {
	
	var message = "";
	if( val  == "" ) {
		message = "コンテンツ名が未入力です。\n\n";
		return message;
	}
	return message;
}
function c_commentCheck( val) {
	
	var message = "";
	if( val  == "" ) {
		message = "詳細。\n\n";
		return message;
	}
	return message;
}



function idCheck( id ) {

	var message = "";
	if( id == "" ) {

		message = "サイトIDが未入力です。\n\n";
		return message;
	}
	return message;
}
function cidCheck( id ) {

	var message = "";
	if( id == "" ) {

		message = "カードIDが未入力です。\n\n";
		return message;
	}
	return message;
}
function cnidCheck( id ) {

	var message = "";
	if( id == "" ) {

		message = "コンテンツIDが未入力です。\n\n";
		return message;
	}
	return message;
}
function tidCheck( id ) {

	var message = "";
	if( id == "" ) {

		message = "占い結果IDが未入力です。\n\n";
		return message;
	}
	return message;
}

function nameCheck( name ) {
	
	var message = "";
	if( name == "" ) {
		message = "サイト名が未入力です。\n\n";
		return message;
	}
	return message;
}
function cnameCheck( name ) {
	
	var message = "";
	if( name == "" ) {
		message = "カード名が未入力です。\n\n";
		return message;
	}
	return message;
}
function cnnameCheck( name ) {
	
	var message = "";
	if( name == "" ) {
		message = "コンテンツ名が未入力です。\n\n";
		return message;
	}
	return message;
}

function urlCheck( url ) {
	
	var message = "";
	//Nullチェック
	if( url == "" ) {
		message = "URLが未入力です。\n\n";
		return message;
	}
	if(url.length < 8){
		message = "URLが未入力です。\n\n";
	}
	return message;
}

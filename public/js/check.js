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
		message = "�R���e���c���������͂ł��B\n\n";
		return message;
	}
	return message;
}
function c_commentCheck( val) {
	
	var message = "";
	if( val  == "" ) {
		message = "�ڍׁB\n\n";
		return message;
	}
	return message;
}



function idCheck( id ) {

	var message = "";
	if( id == "" ) {

		message = "�T�C�gID�������͂ł��B\n\n";
		return message;
	}
	return message;
}
function cidCheck( id ) {

	var message = "";
	if( id == "" ) {

		message = "�J�[�hID�������͂ł��B\n\n";
		return message;
	}
	return message;
}
function cnidCheck( id ) {

	var message = "";
	if( id == "" ) {

		message = "�R���e���cID�������͂ł��B\n\n";
		return message;
	}
	return message;
}
function tidCheck( id ) {

	var message = "";
	if( id == "" ) {

		message = "�肢����ID�������͂ł��B\n\n";
		return message;
	}
	return message;
}

function nameCheck( name ) {
	
	var message = "";
	if( name == "" ) {
		message = "�T�C�g���������͂ł��B\n\n";
		return message;
	}
	return message;
}
function cnameCheck( name ) {
	
	var message = "";
	if( name == "" ) {
		message = "�J�[�h���������͂ł��B\n\n";
		return message;
	}
	return message;
}
function cnnameCheck( name ) {
	
	var message = "";
	if( name == "" ) {
		message = "�R���e���c���������͂ł��B\n\n";
		return message;
	}
	return message;
}

function urlCheck( url ) {
	
	var message = "";
	//Null�`�F�b�N
	if( url == "" ) {
		message = "URL�������͂ł��B\n\n";
		return message;
	}
	if(url.length < 8){
		message = "URL�������͂ł��B\n\n";
	}
	return message;
}

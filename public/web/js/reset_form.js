function clear_recode(form_id){
	var formObj  = document.getElementById(form_id);
	var inputObj = formObj.getElementsByTagName('input');
	var selectObj = formObj.getElementsByTagName('select');
	for(i=0;i<inputObj.length;i++){
		if(inputObj[i].type != "radio"){
			inputObj[i].value = "";
			inputObj[i].checked = "";
		}
		if(inputObj[i].name == "sex1"){
			inputObj[i].checked = "2";
		}
		if(inputObj[i].name == "sex2"){
			inputObj[i].checked = "1";
		}
	}
	for(i=0;i<selectObj.length;i++){
		if(selectObj[i].name == 'yy1' || selectObj[i].name == 'yy2'){
			selectObj[i].value = 1965;
		}
		if(selectObj[i].name == 'mm1' || selectObj[i].name == 'dd1' || selectObj[i].name == 'mm2' || selectObj[i].name == 'dd2'){
			selectObj[i].value = 1;
		}
	}
	document.getElementById('sex1').checked = "2";
	document.getElementById('sex2').checked = "1";
	document.cookie = "page_key=;";
	document.cookie = "sec_session=;";
}

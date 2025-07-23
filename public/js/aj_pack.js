function formReq(url , formName ,targetID){
	var msec = (new Date()).getTime();
		new Ajax.Request(
			url, {
				method: "post",
				parameters: Form.serialize(formName),
				onSuccess:function(httpObj){
					$(targetID).innerHTML = httpObj.responseText;
			},
		onFailure:function(httpObj){
		$(targetID).innerHTML = "エラーで読み込めませんでした";
		}
	});
}
function getReq(url , query_string , targetID){
	var msec = (new Date()).getTime();
		new Ajax.Request(
			url, {
				method: "get",
				parameters: query_string,
				onSuccess:function(httpObj){
					var result = httpObj.responseText;
					if(result == "failed"){
						location.href = "?action=Login";
					}else{
						$(targetID).innerHTML = result;
					}
			},
		onFailure:function(httpObj){
		$(targetID).innerHTML = "エラーで読み込めませんでした";
		}
	});
}
function changeType(formName , mode){
	formName.mode.value = mode;
	formReq('sh.php',formName,'SearchAnswer');
}



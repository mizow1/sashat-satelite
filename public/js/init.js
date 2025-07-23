
	//**************************************************************************************
	////////////////////   指定サイズでウィンドウを開き、センターに表示   //////////////////
	//--------------------------------------------------------------------------------------
	//	    gf_OpenNewWindow(URL,NAME,SIZE)
	//		SIZEは、"width=800:height=600"のように入力してください
	//**************************************************************************************
	function gf_OpenNewWindow(pURL,pName,pSize){
		var wWidth,wHeight;
		var wSize,wFeatures;
		var wLeft,wTop,PositionX,PositionY;

		wWidth = window.screen.availWidth/2;
		wHeight = window.screen.availHeight/2;
		wSize = pSize.split(":");
		wLeft = wSize[0].split("=");
		wTop = wSize[1].split("=");
		PositionX = wWidth-wLeft[1]/2;
		PositionY = wHeight-wTop[1]/2;
		
		wFeatures = wSize+",left="+PositionX+",top="+PositionY;
		
		wWindow = window.open(pURL,pName,wFeatures+",scrollbars=yes,status=yes,resizable=yes");

		wWindow.focus();

	}
	// ステータスバーなしのバージョン
	function gf_OpenNewWindow_StatusNo(pURL,pName,pSize){
		var wWidth,wHeight;
		var wSize,wFeatures;
		var wLeft,wTop,PositionX,PositionY;

		wWidth = window.screen.availWidth/2;
		wHeight = window.screen.availHeight/2;
		wSize = pSize.split(":");
		wLeft = wSize[0].split("=");
		wTop = wSize[1].split("=");
		PositionX = wWidth-wLeft[1]/2;
		PositionY = wHeight-wTop[1]/2;
		
		wFeatures = wSize+",left="+PositionX+",top="+PositionY;
		wWindow = window.open(pURL,pName,wFeatures+",scrollbars=yes,status=no,resizable=yes");

		wWindow.focus();

	}

	//********************************
	//   バイト数チェック
	//********************************
	function gf_GetLength(value){
		var i,nCnt=0;
		for(i=0; i<value.length; i++){
			if(escape(value.charAt(i)).length >= 4 ) nCnt+=2;
			else nCnt++;
		}
		return nCnt;
	}

	//********************************
	//   ページのヘルプ
	//********************************
	function gf_ShowHelp(pPage,pNo){
		wUrl = "?mode=help&state="+pPage+"#"+pNo;
		gf_OpenNewWindow(wUrl,"CP_HELP","width=450:height=520");
	}
	
	//********************************
	//   ログアウト
	//********************************
	function gf_Logout(){
		if( !confirm("ログアウトしてよろしいでしょうか？") ){ return; }
		location.href = "./?mode=logout";
	}
	
	//********************************
	//   ページの項目説明
	//********************************
	function gf_ShowItemHelp(pPage,pNo,pPrm1,pPrm2){
		wUrl = "?mode=item_help&state="+pPage+"&Prm1="+pPrm1+"&Prm2="+pPrm2+"#"+pNo;
		gf_OpenNewWindow(wUrl,"CP_IHELP","width=500:height=600");
	}
	
	//************************************
	//  規約ページへ
	//************************************
	function jf_ShowKiyaku(){
		gf_OpenNewWindow("?mode=kiyaku","CP_KIYAKU","width=800:height=700");
	}
	//************************************
	//  日付チェック
	//  説明：パラメータはy/m/d形式。戻り値はエラーメッセージ。
	//        エラーメッセージがない場合、チェックOK
	//************************************
	function gf_ChkDate(pChkStr) {
		yy = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31, 29);

		wErrMsg = "";

		// パラメータチェック
		if (pChkStr.match(/[0-9\/]/)) {
			wChkAry = pChkStr.split("/"); 
		} else {
			wErrMsg = "日付の形式が正しくありません";
			return wErrMsg;
		}
		for (i=0;i<3;i++) {
			if (!wChkAry[i]) { wErrMsg = "日付の形式が正しくありません"; return wErrMsg; }
			if (wChkAry[i] == "") { wErrMsg = "日付の形式が正しくありません"; return wErrMsg; }
		}

		wYear  = wChkAry[0];
		wMonth = wChkAry[1];
		wMChk  = wChkAry[1];
		wDay   = wChkAry[2];

		// 年の範囲検証
		if (!(wYear >= 1900 && wYear <= 2200)) {
			wErrMsg = "年の指定が正しくありません";
			return wErrMsg;
		}

		// 月の範囲検証
		if (!(wMonth >= 1 && wMonth <= 12)) {
			wErrMsg = "月の指定が正しくありません";
			return wErrMsg;
		}

		// 閏年の判定
		if (!(wYear % 4) && wMonth == 2) {
			wMChk = 12;	 // 閏年テーブル

			if (!(wYear % 100)) {
				if (wYear % 400) {
					wMChk = 1;	  // non閏年テーブル
				}
			}
		} else {
			wMChk--;
		}

		// 日の範囲検証
		if (!(1 <= wDay && yy[wMChk] >= wDay)) {
			wErrMsg = "日付の指定が間違ってます";
			return wErrMsg;
		}

		return wErrMsg;
	}
	//************************************
	//  時間チェック
	//  説明：パラメータはh:m:s形式。戻り値はエラーメッセージ。
	//        エラーメッセージがない場合、チェックOK
	//************************************
	function gf_ChkTime(pChkStr) {
		wErrMsg = "";

		// パラメータチェック
		if (pChkStr.match(/[0-9:]/)) {
			wChkAry = pChkStr.split(":"); 
		} else {
			wErrMsg = "時間の形式が正しくありません";
			return wErrMsg;
		}
		for (i=0;i<3;i++) {
			if (!wChkAry[i]) { wErrMsg = "時間の形式が正しくありません"; return wErrMsg; }
			if (wChkAry[i] == "") { wErrMsg = "時間の形式が正しくありません"; return wErrMsg; }
		}

		wHour   = wChkAry[0];
		wMinute = wChkAry[1];
		wSecond = wChkAry[2];

		// 時の範囲検証
		if (!(wHour >= 0 && wHour <= 23)) {
			wErrMsg = "時間の指定が正しくありません";
			return wErrMsg;
		}

		// 分の範囲検証
		if (!(wMinute >= 0 && wMinute <= 59)) {
			wErrMsg = "分の指定が正しくありません";
			return wErrMsg;
		}

		// 秒の範囲検証
		if (!(wSecond >= 0 && wSecond <= 59)) {
			wErrMsg = "秒の指定が正しくありません";
			return wErrMsg;
		}

		return wErrMsg;
	}

	//******************************************
	//       『textarea内でtabを有効に』
	//******************************************
	function gf_PutTab(pObj) {

		if(document.selection){
			if(event.keyCode==9) {
				pObj.focus();
				r = document.selection.createRange();
				pObj.blur();
				r.collapse(false);
				r.text='\t';
				r.select();
			}
		}
		setTimeout("gf_SetFocus('"+pObj.name+"')",0);
	}

	//******************************************
	//       『textarea内でtabを有効に』
	//******************************************
	function gf_SetFocus(pObjNm) {
		ob = eval('document.add_form.'+pObjNm);
		ob.focus();
	}

	//************************************
	//  表示の変更(displayの切り替え)
	//************************************
	function gf_ChgDisplay(pId){
		var disp = document.getElementById(pId).style.display;
		if(disp == "block"){
			document.getElementById(pId).style.display = "none";
		} else {
			document.getElementById(pId).style.display = "block";
		}
	}

	//************************************
	//  タグの追加
	//************************************
	function gf_AddTag(pObj, preTag, sufTag) {
		
		//IE
		if (document.selection) {
			pObj.focus();
			var str = document.selection.createRange().text;
			if(!str) {
				return;
			}
			document.selection.createRange().text = preTag + str + sufTag;
			return;
		}
		//Mozilla
		else if ((pObj.selectionEnd - pObj.selectionStart) >0) {
			var startPos = pObj.selectionStart;
			var endPos   = pObj.selectionEnd;
			
			
			pObj.value = pObj.value.substring(0, startPos)
					  + preTag
					  + pObj.value.substring(startPos, endPos)
					  + sufTag
					  + pObj.value.substring(endPos, pObj.value.length);
			return;
		}
		//Other
		else {
			pObj.value += preTag + sufTag;
		}
	}

	//************************************
	//  通常タグの追加
	//************************************
	function gf_AddNormalTag(pObj, pStr) {
		var preTag = '<' + pStr + '>';
		var sufTag = '</' + pStr + '>';
		gf_AddTag(pObj, preTag, sufTag);
	}
	//************************************
	//  フォントサイズタグの追加
	//************************************
	function gf_AddFontsizeTag(pObj, pStr) {
		
		var preTag = '<span style=\"font-size:' + pStr + '\;\">';
		var sufTag = '</span>';
		gf_AddTag(pObj, preTag, sufTag);
	}
	//************************************
	//  リンクタグの追加
	//************************************
	function gf_AddLinkTag(pObj) {
		var url = prompt('リンクするサイトのURLを入力してください。', 'http://');
		if (url == null) {
			return;
		}
		
		var preTag = '<a href="' + url + '" target="_blank">';
		var sufTag = '</a>';
		gf_AddTag(pObj, preTag, sufTag);
	}

//文字列を実体参照化 
// reference from simpleboxes.jugem.cc (c)takkyun
function replaceEntity(str) { // 置換処理
  str = str.split("&").join("&amp;"); // & から変換すること
  str = str.split("<").join("&lt;");
  str = str.split(">").join("&gt;");
  str = str.split('"').join("&quot;");
  return(str);
}
function changeEntity(obj) {
  if (document.selection) { // WinIE
    obj.focus();
    var str = document.selection.createRange().text;
    if (str) {
      document.selection.createRange().text = replaceEntity(str);
    } else if (obj.value && confirm('テキストエリア内の「&,<,>,"」を実体参照化します。\n\nよろしいですか？')) { // 選択されていないとき
      obj.value = replaceEntity(obj.value);
    }
  } else if ( (obj.selectionEnd - obj.selectionStart) > 0 ) { // Mozilla
    var bgnPos = obj.selectionStart;
    var endPos = obj.selectionEnd;
    var bfrStr = obj.value.substring(0, bgnPos);
    var fcsStr = replaceEntity(obj.value.substring(bgnPos, endPos));
    var difLen = fcsStr.length - (endPos - bgnPos);
    var aftStr = obj.value.substring(endPos, obj.value.length);
    obj.value = Array(bfrStr,fcsStr,aftStr).join('');
    obj.setSelectionRange(bgnPos,endPos + difLen); // 選択し直し
  } else if (obj.value) { // Others (テキストエリア内全てが対象)
    if (confirm('テキストエリア内の「&,<,>,"」を実体参照化します。\n\nよろしいですか？')) {
      obj.value = replaceEntity(obj.value);
    }
  }
  return;
}


//実体参照化解除
// reference from simpleboxes.jugem.cc (c)takkyun
function changeTag(str) { // 置換処理
  str = str.split('&lt;').join("<");
  str = str.split('&gt;').join(">");
  str = str.split('&quot;').join('"');
  str = str.split('&amp;').join("&"); // & は最後に変換
  return(str);
}
function reverseEntity(obj) {
  if (document.selection) { // WinIE
    obj.focus();
    var str = document.selection.createRange().text;
    if (str) {
      document.selection.createRange().text = changeTag(str);
    } else if (obj.value && confirm('テキストエリア内の実体参照文字を「&,<,>,"」に変換します。\n\nよろしいですか？')) { // 選択されていないとき
      obj.value = changeTag(obj.value);
    }
  } else if ( (obj.selectionEnd - obj.selectionStart) > 0 ) { // Mozilla
    var bgnPos = obj.selectionStart;
    var endPos = obj.selectionEnd;
    var bfrStr = obj.value.substring(0, bgnPos);
    var fcsStr = changeTag(obj.value.substring(bgnPos, endPos));
    var difLen = fcsStr.length - (endPos - bgnPos);
    var aftStr = obj.value.substring(endPos, obj.value.length);
    obj.value = Array(bfrStr,fcsStr,aftStr).join('');
    obj.setSelectionRange(bgnPos,endPos + difLen); // 選択し直し
  } else if (obj.value) { // Others (テキストエリア内全てが対象)
    if (confirm('テキストエリア内の実体参照文字を「&,<,>,"」に変換します。\n\nよろしいですか？')) {
      obj.value = changeTag(obj.value);
    }
  }
  return;
}


//textarea伸縮
function increaseNotesHeight(thisTextarea, add) {
	if (thisTextarea) {
		newHeight = parseInt(thisTextarea.style.height) + add;
		thisTextarea.style.height = newHeight + "px";
	}
	if (document.getElementById('notes_height')) {
		document.getElementById('notes_height').value = newHeight;
	}
}

function decreaseNotesHeight(thisTextarea, subtract) {
	if (thisTextarea) {
		if ((parseInt(thisTextarea.style.height) - subtract) > 30) {
			newHeight = parseInt(thisTextarea.style.height) - subtract;
			thisTextarea.style.height = newHeight + "px";
		}
		else {
			newHeight = 30;
			thisTextarea.style.height = "30px";
		}			
	}
	if (document.getElementById('notes_height')) {
		document.getElementById('notes_height').value = newHeight;
	}
}

	//*******************************************
	//      『リセットボタン』
	//*******************************************
	function gf_Reset(){

		obj = eval("document.add_form.elements");
		wEleNo = obj.length;

		for(i = 0; i < wEleNo; i++){
			if(obj[i].type == "checkbox"){
//				obj[i].checked = false;
			}else if(obj[i].type == "select-one"){
				obj[i].options[0].selected = true;
			}else if(obj[i].type == "text" || obj[i].type == "password"){
				obj[i].value = "";
			}
		}

	}
	//*******************************************
	//      『ログ表示』
	//*******************************************
	function inputTagShowHide(id){
	    var disp = document.getElementById(id).style.display;
	    if(disp == "block"){
	        document.getElementById(id).style.display = "none";
	    }else{
	        document.getElementById(id).style.display = "block";
	    }
	    return false;
	}

	function jf_ShowLog(id1){
	    inputTagShowHide(id1);
	}
	
	//********************************
	//   携帯電話用プレビュー
	//********************************
	function previewModeMobile(pURL,pName){
		var pSize = "width=176:height=220";//ウィンドウサイズ指定
		var wWidth,wHeight;
		var wSize,wFeatures;
		var wLeft,wTop,PositionX,PositionY;
		
		wWidth = window.screen.availWidth/2;
		wHeight = window.screen.availHeight/2;
		wSize = pSize.split(":");
		wLeft = wSize[0].split("=");
		wTop = wSize[1].split("=");
		PositionX = wWidth-wLeft[1]/2;
		PositionY = wHeight-wTop[1]/2;
		
		wFeatures = wSize+",left="+PositionX+",top="+PositionY;
		
		wWindow = window.open(pURL,pName,wFeatures+",scrollbars=yes,status=yes,resizable=yes,menubar=yes");
		
		wWindow.focus();
	}

//*******************************************
//      『画像読み込み失敗時』
//*******************************************
function errImg(imgObj){
	if (imgObj.src != imgObj.src.replace('http:\/\/imaging0.calamel.jp\/cmadmin\/','')){
		imgObj.src = imgObj.src.replace('http:\/\/imaging0.calamel.jp\/cmadmin\/','');
	} else if (imgObj.src != imgObj.src.replace('https:\/\/sv1.acc.shop-pro.jp\/cmadmin\/','')){
		imgObj.src = imgObj.src.replace('https:\/\/sv1.acc.shop-pro.jp\/cmadmin\/','');
	}
}


	//**************************************************************************************
	////////////////////   �w��T�C�Y�ŃE�B���h�E���J���A�Z���^�[�ɕ\��   //////////////////
	//--------------------------------------------------------------------------------------
	//	    gf_OpenNewWindow(URL,NAME,SIZE)
	//		SIZE�́A"width=800:height=600"�̂悤�ɓ��͂��Ă�������
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
	// �X�e�[�^�X�o�[�Ȃ��̃o�[�W����
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
	//   �o�C�g���`�F�b�N
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
	//   �y�[�W�̃w���v
	//********************************
	function gf_ShowHelp(pPage,pNo){
		wUrl = "?mode=help&state="+pPage+"#"+pNo;
		gf_OpenNewWindow(wUrl,"CP_HELP","width=450:height=520");
	}
	
	//********************************
	//   ���O�A�E�g
	//********************************
	function gf_Logout(){
		if( !confirm("���O�A�E�g���Ă�낵���ł��傤���H") ){ return; }
		location.href = "./?mode=logout";
	}
	
	//********************************
	//   �y�[�W�̍��ڐ���
	//********************************
	function gf_ShowItemHelp(pPage,pNo,pPrm1,pPrm2){
		wUrl = "?mode=item_help&state="+pPage+"&Prm1="+pPrm1+"&Prm2="+pPrm2+"#"+pNo;
		gf_OpenNewWindow(wUrl,"CP_IHELP","width=500:height=600");
	}
	
	//************************************
	//  �K��y�[�W��
	//************************************
	function jf_ShowKiyaku(){
		gf_OpenNewWindow("?mode=kiyaku","CP_KIYAKU","width=800:height=700");
	}
	//************************************
	//  ���t�`�F�b�N
	//  �����F�p�����[�^��y/m/d�`���B�߂�l�̓G���[���b�Z�[�W�B
	//        �G���[���b�Z�[�W���Ȃ��ꍇ�A�`�F�b�NOK
	//************************************
	function gf_ChkDate(pChkStr) {
		yy = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31, 29);

		wErrMsg = "";

		// �p�����[�^�`�F�b�N
		if (pChkStr.match(/[0-9\/]/)) {
			wChkAry = pChkStr.split("/"); 
		} else {
			wErrMsg = "���t�̌`��������������܂���";
			return wErrMsg;
		}
		for (i=0;i<3;i++) {
			if (!wChkAry[i]) { wErrMsg = "���t�̌`��������������܂���"; return wErrMsg; }
			if (wChkAry[i] == "") { wErrMsg = "���t�̌`��������������܂���"; return wErrMsg; }
		}

		wYear  = wChkAry[0];
		wMonth = wChkAry[1];
		wMChk  = wChkAry[1];
		wDay   = wChkAry[2];

		// �N�͈̔͌���
		if (!(wYear >= 1900 && wYear <= 2200)) {
			wErrMsg = "�N�̎w�肪����������܂���";
			return wErrMsg;
		}

		// ���͈̔͌���
		if (!(wMonth >= 1 && wMonth <= 12)) {
			wErrMsg = "���̎w�肪����������܂���";
			return wErrMsg;
		}

		// �[�N�̔���
		if (!(wYear % 4) && wMonth == 2) {
			wMChk = 12;	 // �[�N�e�[�u��

			if (!(wYear % 100)) {
				if (wYear % 400) {
					wMChk = 1;	  // non�[�N�e�[�u��
				}
			}
		} else {
			wMChk--;
		}

		// ���͈̔͌���
		if (!(1 <= wDay && yy[wMChk] >= wDay)) {
			wErrMsg = "���t�̎w�肪�Ԉ���Ă܂�";
			return wErrMsg;
		}

		return wErrMsg;
	}
	//************************************
	//  ���ԃ`�F�b�N
	//  �����F�p�����[�^��h:m:s�`���B�߂�l�̓G���[���b�Z�[�W�B
	//        �G���[���b�Z�[�W���Ȃ��ꍇ�A�`�F�b�NOK
	//************************************
	function gf_ChkTime(pChkStr) {
		wErrMsg = "";

		// �p�����[�^�`�F�b�N
		if (pChkStr.match(/[0-9:]/)) {
			wChkAry = pChkStr.split(":"); 
		} else {
			wErrMsg = "���Ԃ̌`��������������܂���";
			return wErrMsg;
		}
		for (i=0;i<3;i++) {
			if (!wChkAry[i]) { wErrMsg = "���Ԃ̌`��������������܂���"; return wErrMsg; }
			if (wChkAry[i] == "") { wErrMsg = "���Ԃ̌`��������������܂���"; return wErrMsg; }
		}

		wHour   = wChkAry[0];
		wMinute = wChkAry[1];
		wSecond = wChkAry[2];

		// ���͈̔͌���
		if (!(wHour >= 0 && wHour <= 23)) {
			wErrMsg = "���Ԃ̎w�肪����������܂���";
			return wErrMsg;
		}

		// ���͈̔͌���
		if (!(wMinute >= 0 && wMinute <= 59)) {
			wErrMsg = "���̎w�肪����������܂���";
			return wErrMsg;
		}

		// �b�͈̔͌���
		if (!(wSecond >= 0 && wSecond <= 59)) {
			wErrMsg = "�b�̎w�肪����������܂���";
			return wErrMsg;
		}

		return wErrMsg;
	}

	//******************************************
	//       �wtextarea����tab��L���Ɂx
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
	//       �wtextarea����tab��L���Ɂx
	//******************************************
	function gf_SetFocus(pObjNm) {
		ob = eval('document.add_form.'+pObjNm);
		ob.focus();
	}

	//************************************
	//  �\���̕ύX(display�̐؂�ւ�)
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
	//  �^�O�̒ǉ�
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
	//  �ʏ�^�O�̒ǉ�
	//************************************
	function gf_AddNormalTag(pObj, pStr) {
		var preTag = '<' + pStr + '>';
		var sufTag = '</' + pStr + '>';
		gf_AddTag(pObj, preTag, sufTag);
	}
	//************************************
	//  �t�H���g�T�C�Y�^�O�̒ǉ�
	//************************************
	function gf_AddFontsizeTag(pObj, pStr) {
		
		var preTag = '<span style=\"font-size:' + pStr + '\;\">';
		var sufTag = '</span>';
		gf_AddTag(pObj, preTag, sufTag);
	}
	//************************************
	//  �����N�^�O�̒ǉ�
	//************************************
	function gf_AddLinkTag(pObj) {
		var url = prompt('�����N����T�C�g��URL����͂��Ă��������B', 'http://');
		if (url == null) {
			return;
		}
		
		var preTag = '<a href="' + url + '" target="_blank">';
		var sufTag = '</a>';
		gf_AddTag(pObj, preTag, sufTag);
	}

//����������̎Q�Ɖ� 
// reference from simpleboxes.jugem.cc (c)takkyun
function replaceEntity(str) { // �u������
  str = str.split("&").join("&amp;"); // & ����ϊ����邱��
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
    } else if (obj.value && confirm('�e�L�X�g�G���A���́u&,<,>,"�v�����̎Q�Ɖ����܂��B\n\n��낵���ł����H')) { // �I������Ă��Ȃ��Ƃ�
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
    obj.setSelectionRange(bgnPos,endPos + difLen); // �I��������
  } else if (obj.value) { // Others (�e�L�X�g�G���A���S�Ă��Ώ�)
    if (confirm('�e�L�X�g�G���A���́u&,<,>,"�v�����̎Q�Ɖ����܂��B\n\n��낵���ł����H')) {
      obj.value = replaceEntity(obj.value);
    }
  }
  return;
}


//���̎Q�Ɖ�����
// reference from simpleboxes.jugem.cc (c)takkyun
function changeTag(str) { // �u������
  str = str.split('&lt;').join("<");
  str = str.split('&gt;').join(">");
  str = str.split('&quot;').join('"');
  str = str.split('&amp;').join("&"); // & �͍Ō�ɕϊ�
  return(str);
}
function reverseEntity(obj) {
  if (document.selection) { // WinIE
    obj.focus();
    var str = document.selection.createRange().text;
    if (str) {
      document.selection.createRange().text = changeTag(str);
    } else if (obj.value && confirm('�e�L�X�g�G���A���̎��̎Q�ƕ������u&,<,>,"�v�ɕϊ����܂��B\n\n��낵���ł����H')) { // �I������Ă��Ȃ��Ƃ�
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
    obj.setSelectionRange(bgnPos,endPos + difLen); // �I��������
  } else if (obj.value) { // Others (�e�L�X�g�G���A���S�Ă��Ώ�)
    if (confirm('�e�L�X�g�G���A���̎��̎Q�ƕ������u&,<,>,"�v�ɕϊ����܂��B\n\n��낵���ł����H')) {
      obj.value = changeTag(obj.value);
    }
  }
  return;
}


//textarea�L�k
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
	//      �w���Z�b�g�{�^���x
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
	//      �w���O�\���x
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
	//   �g�ѓd�b�p�v���r���[
	//********************************
	function previewModeMobile(pURL,pName){
		var pSize = "width=176:height=220";//�E�B���h�E�T�C�Y�w��
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
//      �w�摜�ǂݍ��ݎ��s���x
//*******************************************
function errImg(imgObj){
	if (imgObj.src != imgObj.src.replace('http:\/\/imaging0.calamel.jp\/cmadmin\/','')){
		imgObj.src = imgObj.src.replace('http:\/\/imaging0.calamel.jp\/cmadmin\/','');
	} else if (imgObj.src != imgObj.src.replace('https:\/\/sv1.acc.shop-pro.jp\/cmadmin\/','')){
		imgObj.src = imgObj.src.replace('https:\/\/sv1.acc.shop-pro.jp\/cmadmin\/','');
	}
}

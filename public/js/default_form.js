var cookie_data = {};
cookie_data['name1'] ="no_name";
cookie_data['name2'] ="no_name";
cookie_data['yy1'] ="1965";
cookie_data['yy2'] ="1965";
cookie_data['mm1'] ="1";
cookie_data['mm2'] ="1";
cookie_data['dd1'] ="1";
cookie_data['dd2'] ="1";
cookie_data['sex1'] ="2";
cookie_data['sex2'] ="1";
cookie_data['default_check'] ="1";

function encodeURL(str) {
  var character = '';
  var unicode   = '';
  var string    = '';
  var i         = 0;

  for (i = 0; i < str.length; i++) {
    character = str.charAt(i);
    unicode   = str.charCodeAt(i);

    if (character == ' ') {
      string += '+';
    } else {
      if (unicode == 0x2a || unicode == 0x2d || unicode == 0x2e || unicode == 0x5f || ((unicode >= 0x30) && (unicode <= 0x39)) || ((unicode >= 0x41) && (unicode <= 0x5a)) || ((unicode >= 0x61) && (unicode <= 0x7a))) {
        string = string + character;
      } else {
        if ((unicode >= 0x0) && (unicode <= 0x7f)) {
          character   = '0' + unicode.toString(16);
          string += '%' + character.substr(character.length - 2);
        } else if (unicode > 0x1fffff) {
          string += '%' + (oxf0 + ((unicode & 0x1c0000) >> 18)).toString(16);
          string += '%' + (0x80 + ((unicode & 0x3f000) >> 12)).toString(16);
          string += '%' + (0x80 + ((unicode & 0xfc0) >> 6)).toString(16);
          string += '%' + (0x80 + (unicode & 0x3f)).toString(16);
        } else if (unicode > 0x7ff) {
          string += '%' + (0xe0 + ((unicode & 0xf000) >> 12)).toString(16);
          string += '%' + (0x80 + ((unicode & 0xfc0) >> 6)).toString(16);
          string += '%' + (0x80 + (unicode & 0x3f)).toString(16);
        } else {
          string += '%' + (0xc0 + ((unicode & 0x7c0) >> 6)).toString(16);
          string += '%' + (0x80 + (unicode & 0x3f)).toString(16);
        }
      }
    }
  }

  return string;
}

function decodeURL(str){
    var s0, i, j, s, ss, u, n, f;
    s0 = "";                // decoded str
    for (i = 0; i < str.length; i++){   // scan the source str
        s = str.charAt(i);
        if (s == "+"){s0 += " ";}       // "+" should be changed to SP
        else {
            if (s != "%"){s0 += s;}     // add an unescaped char
            else{               // escape sequence decoding
                u = 0;          // unicode of the character
                f = 1;          // escape flag, zero means end of this sequence
                while (true) {
                    ss = "";        // local str to parse as int
                        for (j = 0; j < 2; j++ ) {  // get two maximum hex characters for parse
                            sss = str.charAt(++i);
                            if (((sss >= "0") && (sss <= "9")) || ((sss >= "a") && (sss <= "f"))  || ((sss >= "A") && (sss <= "F"))) {
                                ss += sss;      // if hex, add the hex character
                            } else {--i; break;}    // not a hex char., exit the loop
                        }
                    n = parseInt(ss, 16);           // parse the hex str as byte
                    if (n <= 0x7f){u = n; f = 1;}   // single byte format
                    if ((n >= 0xc0) && (n <= 0xdf)){u = n & 0x1f; f = 2;}   // double byte format
                    if ((n >= 0xe0) && (n <= 0xef)){u = n & 0x0f; f = 3;}   // triple byte format
                    if ((n >= 0xf0) && (n <= 0xf7)){u = n & 0x07; f = 4;}   // quaternary byte format (extended)
                    if ((n >= 0x80) && (n <= 0xbf)){u = (u << 6) + (n & 0x3f); --f;}         // not a first, shift and add 6 lower bits
                    if (f <= 1){break;}         // end of the utf byte sequence
                    if (str.charAt(i + 1) == "%"){ i++ ;}                   // test for the next shift byte
                    else {break;}                   // abnormal, format error
                }
            s0 += String.fromCharCode(u);           // add the escaped character
            }
        }
    }
    return s0;
}
function loadCookie(target_flag){

	//document.cookie = "page_key=;";
	//document.cookie = "sec_session=;";

	var cookie_dat = document.cookie.split(';');

	for(ii=0 ; ii<cookie_dat.length;ii++){
		var tmp = cookie_dat[ii].split('=');
		tmp[0] = tmp[0].replace(' ','');
		cookie_data[tmp[0]] = tmp[1];
	}

	if(cookie_data['name1'] != '' && cookie_data['name1'] != 'no_name'){
		document.getElementById('name1').value=decodeURL(cookie_data['name1']);
	}
	var yy1_obj = document.getElementById('yy1');
	var mm1_obj = document.getElementById('mm1');
	var dd1_obj = document.getElementById('dd1');


	for(i=0;i<yy1_obj.options.length;i++){
		if(yy1_obj.options[i].value==cookie_data['yy1']){
			yy1_obj.options[i].selected = 1;
		};
	}
	for(i=0;i<mm1_obj.options.length;i++){
		if(mm1_obj.options[i].value==cookie_data['mm1']){
			mm1_obj.options[i].selected = 1;
		};
	}
	for(i=0;i<dd1_obj.options.length;i++){
		if(dd1_obj.options[i].value==cookie_data['dd1']){
			dd1_obj.options[i].selected = 1;
		};
	}

	if(cookie_data['sex1'] == 2){
		document.getElementById('sex12').checked = 1;
	}else{
		document.getElementById('sex11').checked = 1;
	}
	//if(cookie_data['default_check']==1){
	//	document.getElementById('default_check').checked= 1;
	//}else{
	//	document.getElementById('default_check').checked= 0;
	//}
	if(target_flag==2){
		if(cookie_data['name2'] != '' && cookie_data['name2'] != 'no_name'){
			document.getElementById('name2').value=decodeURL(cookie_data['name2']);
		}
		var yy2_obj = document.getElementById('yy2');
		var mm2_obj = document.getElementById('mm2');
		var dd2_obj = document.getElementById('dd2');
		for(i=0;i<yy2_obj.options.length;i++){
			if(yy2_obj.options[i].value==cookie_data['yy2']){
				yy2_obj.options[i].selected = 1;
			};
		}
		for(i=0;i<mm2_obj.options.length;i++){
			if(mm2_obj.options[i].value==cookie_data['mm2']){
				mm2_obj.options[i].selected = 1;
			};
		}
		for(i=0;i<dd2_obj.options.length;i++){
			if(dd2_obj.options[i].value==cookie_data['dd2']){
				dd2_obj.options[i].selected = 1;
			};
		}
		if(cookie_data['sex2'] == 2){
			document.getElementById('sex22').checked = 1;
		}else{
			document.getElementById('sex21').checked = 1;
		}
	}



}

function setCookie(target_flag){

	var period = 30;					// 有効期限日数

	var nowtime = new Date().getTime();
	var clear_time = new Date(nowtime + (60 * 60 * 24 * 1000 * period));
	var expires = clear_time.toGMTString();

	if(document.getElementById('default_check').checked){
		document.cookie = "default_check = 1 ; expires=" + expires;
	}else{
		document.cookie = "default_check = 0 ; expires=" + expires;
	}

// クッキーの発行（書き込み）
	//document.cookie = 'name1' + "= ; expires=" + expires;

	if(document.getElementById('name1').value == ""){
		document.cookie = 'name1=' +encodeURL('no_name') + " ; expires=" + expires;
	}else{
		document.cookie = 'name1=' + encodeURL(document.getElementById('name1').value)+ " ; expires=" + expires;
	}
	document.cookie = 'yy1='   + document.getElementById('yy1').value+ " ; expires=" + expires;
	document.cookie = 'mm1='   + document.getElementById('mm1').value+ " ; expires=" + expires;
	document.cookie = 'dd1='   + document.getElementById('dd1').value+ " ; expires=" + expires;

	if(document.getElementById('sex11').checked){
		document.cookie = "sex1=1 ; expires=" + expires;
	}else{
		document.cookie = "sex1=2 ; expires=" + expires;
	}
	if(target_flag==2){
		if(document.getElementById('name2').value == ""){
			document.cookie = 'name2=' +encodeURL('no_name') + " ; expires=" + expires;
		}else{
			document.cookie = 'name2=' + encodeURL(document.getElementById('name2').value)+ " ; expires=" + expires;
		}
		document.cookie = 'yy2='   + document.getElementById('yy2').value+ " ; expires=" + expires;
		document.cookie = 'mm2='   + document.getElementById('mm2').value+ " ; expires=" + expires;
		document.cookie = 'dd2='   + document.getElementById('dd2').value+ " ; expires=" + expires;
		if(document.getElementById('sex21').checked){
			document.cookie = "sex2=1 ; expires=" + expires;
		}else{
			document.cookie = "sex2=2 ; expires=" + expires;
		}
	
	}
}
function reset_form(target_flag){
	setDefault(target_flag);
	setCookie(target_flag);
}

function dropCookie(){
	var period = 30;					// 有効期限日数
	var nowtime = new Date().getTime();
	var clear_time = new Date(nowtime + (60 * 60 * 24 * 1000 * period));
	var expires = clear_time.toGMTString();

// クッキーの発行（書き込み）
	document.cookie = 'name1' + "= no_name; expires=" + expires;
	document.cookie = 'name2' + "= no_name; expires=" + expires;
	document.cookie = 'yy1' + "= 1965; expires=" + expires;
	document.cookie = 'yy2' + "= 1965; expires=" + expires;
	document.cookie = 'mm1' + "= 1; expires=" + expires;
	document.cookie = 'mm2' + "= 1; expires=" + expires;
	document.cookie = 'dd1' + "= 1; expires=" + expires;
	document.cookie = 'dd2' + "= 1; expires=" + expires;
	document.cookie = 'sex1' + "= 2; expires=" + expires;
	document.cookie = 'sex2' + "= 1; expires=" + expires;
	document.cookie = 'default_check' + "=0 ; expires=" + expires;

}
function setDefault(target_flag){
	document.getElementById('name1').value="";
	var yy1_obj = document.getElementById('yy1');
	var mm1_obj = document.getElementById('mm1');
	var dd1_obj = document.getElementById('dd1');

	for(i=0;i<yy1_obj.options.length;i++){
		if(yy1_obj.options[i].value==1965){
			yy1_obj.options[i].selected = 1;
		};
	}
	for(i=0;i<mm1_obj.options.length;i++){
		if(mm1_obj.options[i].value==1){
			mm1_obj.options[i].selected = 1;
		};
	}
	for(i=0;i<dd1_obj.options.length;i++){
		if(dd1_obj.options[i].value==1){
			dd1_obj.options[i].selected = 1;
		};
	}
	document.getElementById('sex12').checked = 1;
	document.getElementById('default_check').checked= 0;

	if(target_flag==2){
		document.getElementById('name2').value="";
		var yy2_obj = document.getElementById('yy2');
		var mm2_obj = document.getElementById('mm2');
		var dd2_obj = document.getElementById('dd2');
		for(i=0;i<yy2_obj.options.length;i++){
			if(yy2_obj.options[i].value==1965){
				yy2_obj.options[i].selected = 1;
			};
		}
		for(i=0;i<mm2_obj.options.length;i++){
			if(mm2_obj.options[i].value==1){
				mm2_obj.options[i].selected = 1;
			};
		}
		for(i=0;i<dd2_obj.options.length;i++){
			if(dd2_obj.options[i].value==1){
				dd2_obj.options[i].selected = 1;
			};
		}
		document.getElementById('sex21').checked = 1;
	}
}

//多重クリック防止処理
var SubmitCount = 0;

function sample_submit(nc_flag,target_count,form_id){
	form_submit(nc_flag,target_count,form_id);
}

//Googleタグマネージャーメソッド実行用
function gtag_submit(target,cid){
	// var label = '';
	// if(target == 'nc'){
	// 	label = '一部無料';
	// }else{
	// 	label = '有料';
	// }
	// gtag('event', target, {'event_category': cid,'event_label':label,'value': '1'});
}

function nc_submit(form_id){
	var tgt_form_id = form_id == "" ? 'astrology_form' : form_id ;
	document.getElementById(tgt_form_id).submit();
}


function form_submit(nc_flag,target_count,form_id){
	if(SubmitCount==1){
		return ;
	}

	SubmitCount=1;

	form_data = $("form[id='"+form_id+"']").clone();

	form_data.find("input[name='name1']").attr("value",encodeURL($("input[name='name1']").attr("value")));

	var arr = [];
	$("form[id='"+form_id+"']").find("select").each(function(i){
		arr[i] = $(this).val();
	});
	form_data.find("select").each(function(h){
			$(this).val(arr[h]);
	});
	serializeData = form_data.serialize();
	$.ajax({
		type:"POST",
		url:'./check.php',
		data:serializeData,
		success:function(data){
			if(data == "true"){
				if(form_id != 'ow_sample_form'){
					// if(form_data.find("input[name='default_check']").prop('checked')){
					// 	setCookie(target_count);
					// }else{
					// 	dropCookie(target_count);
					// }
				}
				if(nc_flag == 0){
					document.getElementById("nc_flag").setAttribute("value","0");
				}else{
					document.getElementById("nc_flag").setAttribute("value","1");
				}
				document.getElementById(form_id).submit();
//				animationStart();

			}else{
				alert(decodeURI(data));
				SubmitCount=0;
			}
		}
	});
}


var animation_flag = 0;
function animationStart(){
	if(animation_flag == 0){
		animation_flag = 1
		animationInit();
		$(".wrap01").css("display","none");
		$(".wrap02").css("display","block");
		$(".entryImg").css("display","block");

		$(".entryImg").animate({opacity:1},0,function(){
			$(".animation01").css("opacity",0);
			$(".animation02").css("opacity",0);
			$(".animation03").css("opacity",0);
			$(".animation01").animate({opacity:1},2500,function(){

				timer = setTimeout(function(){
					//$(".animation01").animate({opacity:0},2000,function(){
						$(".animation02").animate({opacity:1},2500,function(){
							$(".animation01").animate({opacity:0},0);
							timer = setTimeout(function(){
								//$(".animation02").animate({opacity:0},2000,function(){
									$(".animation03").animate({opacity:1},2500,function(){
										timer = setTimeout(function(){
//											document.getElementById('astrology_form').submit();
										},1000);
									});
								//});
							},1000);
						});
					//});
				},1000);
			});
		});
	}
}


function animationSkip(){
	animation_flag = 0;
	document.getElementById('astrology_form').submit();
	animationStop();
}

function animationCancel(){

		$(".entryImg").clearQueue().stop(true,true);
		$(".animation01").clearQueue().stop(true,true);
		$(".animation02").clearQueue().stop(true,true);
		$(".animation03").clearQueue().stop(true,true);

		clearTimeout(timer);

	$(".wrap01").css("display","block");
	$(".wrap02").css("display","none");
	$(".entryImg").css("opacity","0");
	$(".entryImg").css("display","none");

	$(".animation01").css("opacity",1);
	$(".animation02").css("opacity",1);
	$(".animation03").css("opacity",1);
	animation_flag = 0;

}

function animationStop(){
	$(".entryImg").clearQueue().stop(true,true);
	$(".animation01").clearQueue().stop(true,true);
	$(".animation02").clearQueue().stop(true,true);
	$(".animation03").clearQueue().stop(true,true);
	clearTimeout(timer);
}

function animationInit(){


	var target_offset = $(".formBox").offset();
	var target_top = target_offset.top;
	$('html, body').animate({scrollTop:target_top}, 1000);
	animationResize();
}

function animationResize(){
	if($("body").width() < 640 ){
		var ratio = $("body").width() / 1100 ;
		var img_width = 732 * ratio ;
		var img_height = 490 * ratio ;
		$(".entryImg").css("height",img_height);
	}else{
		var img_width = 732;
		var img_height = 490;
		$(".entryImg").css("height",img_height);
	}
}

//画面表示後にウィンドウサイズを変更した際、アニメーション部分のサイズを調整する
var resize_timer = false;
$(window).resize(function() {
	if (resize_timer !== false) {
	    clearTimeout(resize_timer);
	}
	resize_timer = setTimeout(function() {
		animationResize();
	}, 100);
});

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

var cookie_data = {};
cookie_data['name1'] ="no_name";

cookie_data['yy1'] ="";
cookie_data['mm1'] ="";
cookie_data['dd1'] ="";
cookie_data['death_yy1'] ="";
cookie_data['death_mm1'] ="";
cookie_data['death_dd1'] ="";

cookie_data['hh'] ="";
cookie_data['ii'] ="";
cookie_data['pref'] ="";


// cookie_data['default_check'] ="0";

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


	if(document.cookie != ""){
	 	var document_cookie = document.cookie.split('　').join('').split(' ').join('');
		if( document_cookie != "" ){
			var cookie_dat = document_cookie.split(';');
			if(cookie_dat.length > 1 ){
				for(j=0 ; j<cookie_dat.length;j++){
					var tmp = cookie_dat[j].split('=');
					if(tmp[0] && tmp[1]){
						tmp[0] = tmp[0].replace(' ','');
						tmp[1] = tmp[1].replace(' ','');
						cookie_data[tmp[0]] = tmp[1];
					}
				}
			}
		}
	}

	if(cookie_data['name1'] != '' && cookie_data['name1'] != 'no_name'){
		document.getElementById('name1').value=decodeURL(cookie_data['name1']);
	}

	var yy1_obj = document.getElementById('yy1');
	var mm1_obj = document.getElementById('mm1');
	var dd1_obj = document.getElementById('dd1');
//	var hh_obj = document.getElementById('hh');
//	var ii_obj = document.getElementById('ii');
//	var pref_obj = document.getElementById('pref');


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
	//紫音専用
	if(cookie_data['death_yy1'] != '' && document.getElementById('death_yy1') != null){
		document.getElementById('death_yy1').value=decodeURL(cookie_data['death_yy1']);
		document.getElementById('death_mm1').value=decodeURL(cookie_data['death_mm1']);
		document.getElementById('death_dd1').value=decodeURL(cookie_data['death_dd1']);
	}

	// if(cookie_data['default_check']==1){
	// 	document.getElementById('default_check').checked= 1;
	// }else{
	// 	document.getElementById('default_check').checked= 0;
	// }

}

function setCookie(target_flag){

	var period = 30;					// 有効期限日数

	var nowtime = new Date().getTime();
	var clear_time = new Date(nowtime + (60 * 60 * 24 * 1000 * period));
	var expires = clear_time.toGMTString();

	// if(document.getElementById('default_check').checked){
	// 	document.cookie = "default_check = 1 ; expires=" + expires;
	// }else{
	// 	document.cookie = "default_check = 0 ; expires=" + expires;
	// }

// クッキーの発行（書き込み）
	document.cookie = 'name1' + "= ; expires=" + expires;

	if(document.getElementById('name1').value == ""){
		document.cookie = 'name1=' +encodeURL('no_name') + " ; expires=" + expires;
	}else{
		document.cookie = 'name1=' + encodeURL(document.getElementById('name1').value) + " ; expires=" + expires;
	}

	document.cookie = 'yy1='   + document.getElementById('yy1').value+ " ; expires=" + expires;
	document.cookie = 'mm1='   + document.getElementById('mm1').value+ " ; expires=" + expires;
	document.cookie = 'dd1='   + document.getElementById('dd1').value+ " ; expires=" + expires;
	if(document.getElementById('death_yy1') != null){
		document.cookie = 'death_yy1='   + document.getElementById('death_yy1').value+ " ; expires=" + expires;
		document.cookie = 'death_mm1='   + document.getElementById('death_mm1').value+ " ; expires=" + expires;
		document.cookie = 'death_dd1='   + document.getElementById('death_dd1').value+ " ; expires=" + expires;
	}

//	document.cookie = 'hh='   + document.getElementById('hh').value+ " ; expires=" + expires;
//	document.cookie = 'ii='   + document.getElementById('ii').value+ " ; expires=" + expires;
//	document.cookie = 'pref='   + document.getElementById('pref').value+ " ; expires=" + expires;

}

function reset_form(target_flag){
	setDefault(target_flag);
	setCookie(target_flag);
}

function dropCookie(){
	var period = -30;					// 有効期限日数
	var nowtime = new Date().getTime();
	var clear_time = new Date(nowtime + (60 * 60 * 24 * 1000 * period));
	var expires = clear_time.toGMTString();

// クッキーの発行（書き込み）
	document.cookie = 'name1' + "= no_name; expires=" + expires;

	document.cookie = 'yy1' + "= 1975; expires=" + expires;
	document.cookie = 'mm1' + "= 1; expires=" + expires;
	document.cookie = 'dd1' + "= 1; expires=" + expires;

	document.cookie = 'death_yy1' + "= 1975; expires=" + expires;
	document.cookie = 'death_mm1' + "= 1; expires=" + expires;
	document.cookie = 'death_dd1' + "= 1; expires=" + expires;

	// document.cookie = 'default_check' + "=0 ; expires=" + expires;

}
function setDefault(target_flag){
	document.getElementById('name1').value="";
	// document.getElementById('default_check').checked= 0;

}

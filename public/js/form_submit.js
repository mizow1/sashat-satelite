function form_submit(form_id){
	var tgt_form_id = form_id == "" ? 'astrology_form' : form_id ;
	document.getElementById(tgt_form_id).submit();
}

function form_submit2(form_id){
	document.getElementById("nc_flag").setAttribute("value","0");
	document.getElementById('astrology_form').submit();
}


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


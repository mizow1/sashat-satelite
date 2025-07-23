/*-----------------------------------------------------------------------------------*/
/*	topページ
/*-----------------------------------------------------------------------------------*/
var ow_new_intro = 0;
var ow_rec_intro = 0;
var ow_rec = 0;
var ow_sample = 0;
var ow_sample_2 = 0;
var ow_special = 0;

var ow_category_renai = 0;
var ow_category_deai = 0;
var ow_category_jinsei = 0;

$(window).load(function(){

  var w_height = $(window).height();

  // header
	$('.ow_header_text_1').fadeIn(1200);
	$('.ow_header_text_2').delay(1000).fadeIn(1200);
	$('.ow_header_catch_1').delay(2600).fadeIn(1500);
	$('.ow_header_catch_2').delay(3400).fadeIn(1800);
	$('.ow_header_title').delay(3800).fadeIn(1800);

// position
// --------------------------------------------------------------------
    // new_intro
    if ($('.ow_new_intro_teller').length) {
      var ow_new_intro_pos = $('.ow_new_intro_teller').offset().top;
    }
	
    // rec_intro
    if ($('.ow_eikyou_image').length) {
      var ow_rec_intro_pos = $('.ow_eikyou_image').offset().top;
    }
	
    // rec
    if ($('.ow_ninso_intro_title').length) {
      var ow_rec_pos = $('.ow_ninso_intro_title').offset().top;
    }

    // sample
    if ($('.ow_sample_intro_text').length) {
      var ow_sample_pos = $('.ow_sample_intro_text').offset().top;
    }

    // sample_2
    if ($('.ow_sample_intro_teller').length) {
      var ow_sample_2_pos = $('.ow_sample_intro_teller').offset().top;
    }

    // ow_special
    if ($('.ow_special_intro_teller').length) {
      var ow_special_pos = $('.ow_special_intro_teller').offset().top;
    }

    // category
    if($('.ow_category_renai .ow_category_mid').length){
    var ow_category_renai_pos = $('.ow_category_renai .ow_category_mid').offset().top;
    }
    if($('.ow_category_deai .ow_category_mid').length){
    var ow_category_deai_pos = $('.ow_category_deai .ow_category_mid').offset().top;
    }
    if($('.ow_category_jinsei .ow_category_mid').length){
    var ow_category_jinsei_pos = $('.ow_category_jinsei .ow_category_mid').offset().top;
    }

    $(window).scroll(function(){
      var topPos = $(window).scrollTop();
			
				//ow_new_intro
				if (topPos + w_height > ow_new_intro_pos && ow_new_intro == 0) {
					$(function(){
					$('.ow_intro_career').fadeIn(1000);
					$('.ow_new_intro_title_2').delay(500).fadeIn(1800);
					$('.ow_new_intro_title_1').delay(1000).fadeIn(1800);
					$('.ow_power_ward').delay(1500).fadeIn(1800);
					$('.ow_new_intro_title_3').delay(1800).fadeIn(1500);
					});
					ow_new_intro = 1;
				}

        // ow_rec
        if (topPos + w_height > ow_rec_pos && ow_rec == 0) {
          $(function(){
          $('.ow_ninso_intro_catch_1').fadeIn(1000);
          $('.ow_ninso_intro_catch_2').delay(500).fadeIn(2000);
          $('.ow_ninso_intro_title').delay(1000).fadeIn(1500).animate({'opacity':'1'},1800);
          $('.ow_ninso_intro_hand').delay(1300).fadeIn(1800);
          $('.ow_ninso_intro_baloon_1').delay(1800).fadeIn(1800);
          $('.ow_ninso_intro_baloon_2').delay(2000).fadeIn(1800);
          $('.ow_ninso_intro_baloon_3').delay(2200).fadeIn(1800);
          $('.ow_ninso_intro_baloon_4').delay(2400).fadeIn(1800);
          });
          ow_rec = 1;
        }

        // ow_rec_intro
        if (topPos + w_height > ow_rec_intro_pos && ow_rec_intro == 0) {
          $(function(){
          $('.ow_rec_intro_title_1').fadeIn(1000);
          $('.ow_rec_intro_title_2').delay(500).fadeIn(2000);
          $('.ow_eikyou_image').delay(1000).fadeIn(1500).animate({'opacity':'1'},1800);
          $('.ow_eikyou_text').delay(1300).fadeIn(1800);
          $('.ow_himotoku_image').delay(1800).fadeIn(1500).animate({'opacity':'1'},1800);
          $('.ow_himotoku_text').delay(2100).fadeIn(1800);
          $('.ow_bunki_image').delay(2600).fadeIn(1500).animate({'opacity':'1'},1800);
          $('.ow_bunki_text').delay(2900).fadeIn(1800);
          });
          ow_rec_intro = 1;
        }
			
				// ow_sample
				if (topPos + w_height > ow_sample_pos && ow_sample == 0) {
					$(function(){
          $('.ow_sample_intro_title').fadeIn(800);
					$('.ow_sample_intro_text').delay(1000).fadeIn(1500).animate({'opacity':'1'},1800);
					$('.ow_sample_intro_title_2').delay(1500).fadeIn(2000);
					});
					ow_sample = 1;
				}
			
				// ow_sample_2
				if (topPos + w_height > ow_sample_2_pos && ow_sample_2 == 0) {
					$(function(){
					$('.ow_sample_intro_idea_1,.ow_sample_intro_idea_2,.ow_sample_intro_idea_3').fadeIn(1500).animate({'opacity':'1'},1800);
					$('.ow_sample_intro_look_1,.ow_sample_intro_look_2').delay(800).fadeIn(2000);
					});
					ow_sample_2 = 1;
				}

				// ow_special
				if (topPos + w_height > ow_special_pos && ow_special == 0) {
					$(function(){
					$('.ow_special_intro_title').fadeIn(1000);
					$('.ow_special_intro_speech_balloon').delay(800).fadeIn(1500);
					$('.ow_special_intro_arawareru').delay(1000).fadeIn(1500);
					$('.ow_special_intro_saigo').delay(1200).fadeIn(1500);
					$('.ow_special_intro_suunen').delay(1400).fadeIn(1500);
					$('.ow_special_intro_suunen').delay(1800).fadeIn(2000);
					});
					ow_special = 1;
				}

        // category
        if(topPos + w_height> ow_category_renai_pos && ow_category_renai == 0){
          $(function(){
            $('.ow_category_renai .ow_category_title').fadeIn(1000);
          });
          ow_category_renai = 1;
        }
        if(topPos + w_height> ow_category_deai_pos && ow_category_deai == 0){
          $(function(){
            $('.ow_category_deai .ow_category_title').fadeIn(1000);
          });
          ow_category_deai = 1;
        }
        if(topPos + w_height> ow_category_jinsei_pos && ow_category_jinsei == 0){
          $(function(){
            $('.ow_category_jinsei .ow_category_title').fadeIn(1000);
          });
          ow_category_jinsei = 1;
        }

      });
  });

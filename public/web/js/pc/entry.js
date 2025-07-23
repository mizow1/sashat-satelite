/*-----------------------------------------------------------------------------------*/
/*	入力ページ
/*-----------------------------------------------------------------------------------*/
var ow_sample = 0;
var ow_sample_1 = 0;
var ow_sample_2 = 0;
var ow_sample_3 = 0;
var ow_rec = 0;
var ow_rec_intro = 0;


$(window).load(function(){

  var w_height = $(window).height();

  // header
	$('.ow_header_teller').fadeIn(1500);
	$('.ow_header_catch_1').delay(800).fadeIn(1500);
	$('.ow_header_catch_2').delay(1800).fadeIn(1800);
	$('.ow_header_title').delay(2200).fadeIn(1800);

// position
// --------------------------------------------------------------------

    // sample
    if($('.ow_sample').length){
    var ow_sample_pos = $('.ow_sample').offset().top;
    }
		if($('.ow_sample_contents_1 .ow_sample_img').length){
    var ow_sample_pos_1 = $('.ow_sample_contents_1 .ow_sample_img').offset().top;
    }
    if($('.ow_sample_contents_2 .ow_sample_img').length){
    var ow_sample_pos_2 = $('.ow_sample_contents_2 .ow_sample_img').offset().top;
    }
    if($('.ow_sample_contents_3 .ow_sample_img').length){
    var ow_sample_pos_3 = $('.ow_sample_contents_3 .ow_sample_img').offset().top;
    }
    // rec
    if ($('.ow_ninso_intro_title').length) {
      var ow_rec_pos = $('.ow_ninso_intro_title').offset().top;
    }

    // rec_intro
    if ($('.ow_eikyou_image').length) {
      var ow_rec_intro_pos = $('.ow_eikyou_image').offset().top;
    }

    $(window).scroll(function(){
      var topPos = $(window).scrollTop();

        // ow_sample
        if(topPos + w_height> ow_sample_pos && ow_sample == 0){
          $(function(){
            $('.ow_sample_intro_title').fadeIn(1000);
          });
          ow_sample = 1;
        }
				if(topPos + w_height> ow_sample_pos_1 && ow_sample_1 == 0){
          $(function(){
            $('.ow_sample_contents_1 .ow_sample_contents_title').delay(500).fadeIn(1000);
            $('.ow_sample_contents_1 .ow_sample_img').delay(1000).animate({'opacity':'1'},1500);
          });
          ow_sample_1 = 1;
        }
        if(topPos + w_height> ow_sample_pos_2 && ow_sample_2 == 0){
          $(function(){
            $('.ow_sample_contents_2 .ow_sample_contents_title').delay(500).fadeIn(1000);
            $('.ow_sample_contents_2 .ow_sample_img').delay(1000).animate({'opacity':'1'},1500);
          });
          ow_sample_2 = 1;
        }
        if(topPos + w_height> ow_sample_pos_3 && ow_sample_3 == 0){
          $(function(){
            $('.ow_sample_contents_3 .ow_sample_contents_title').delay(500).fadeIn(1000);
            $('.ow_sample_contents_3 .ow_sample_img').delay(1000).animate({'opacity':'1'},1500);
          });
          ow_sample_3 = 1;
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

  });
});

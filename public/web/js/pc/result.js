/*-----------------------------------------------------------------------------------*/
/*	結果ページ
/*-----------------------------------------------------------------------------------*/

var ow_result_intro_1 = 0;
var ow_result_intro_2 = 0;
var result_item_base = 0;
var ow_person_1 = 0;
var ow_person_2 = 0;
var ow_calender = 0;
var ow_rec = 0;
var ow_gift = 0;
var ow_gift_nc = 0;
var ow_about = 0;
var ow_joint_1 = 0;
var ow_joint_2 = 0;
var ow_card = 0;

var ow_result_intro_pos_1 = 0;
var ow_result_intro_pos_2 = 0;
var ow_person_pos_1 = 0;
var ow_person_pos_2 = 0;
var ow_calender_pos = 0;
var ow_rec_pos = 0;
var ow_gift_pos = 0;
var ow_gift_nc_pos = 0;
var ow_about_pos = 0;
var ow_joint_pos_1 = 0;
var ow_joint_pos_2 = 0;
var ow_card_pos = 0;


$(window).load(function(){
  var w_height = $(window).height();

  // header
  $(function(){
		$('.ow_header_teller').fadeIn(1500);
		$('.ow_header_catch_1').delay(800).fadeIn(1500);
		$('.ow_header_catch_2').delay(1800).fadeIn(1800);
		$('.ow_header_title').delay(2200).fadeIn(1800);
  });

// position
// --------------------------------------------------------------------



    // 本文イントロ
    if($('.ow_result_intro_1 .ow_balloon_menu_btm').length){
      var ow_result_intro_pos_1 = $('.ow_result_intro_1 .ow_balloon_menu_btm').offset().top;
    }
    if($('.ow_result_intro_2 .ow_balloon_menu_btm').length){
      var ow_result_intro_pos_2 = $('.ow_result_intro_2 .ow_balloon_menu_btm').offset().top;
    }

    // result_item_base
    if($('.ow_result_item_base').length){
      var result_item_count = $('.ow_result_item_base').length;
      var ow_result_item_base = new Array;
      for(var i=0; i<result_item_count; i++){
         ow_result_item_base[i] = $('.ow_result_item_base').eq(i).find('.ow_result_body').offset().top;
      }
    }

    // ow_result_item_person
    if($('.ow_result_item_person .ow_result_body_img').length){
      var ow_person_pos_1 = $('.ow_result_item_person .ow_result_body_img').offset().top;
    }
    if($('.ow_result_item_person .ow_result_body_text').eq(1).length){
      var ow_person_pos_2 = $('.ow_result_item_person .ow_result_body_text').eq(1).offset().top;
    }

    // ow_result_item_calender
    if($('.ow_result_item_calender .ow_result_body_img').length){
      var ow_calender_pos = $('.ow_result_item_calender .ow_result_body_img').offset().top;
    }

    // gift
    if($('.ow_gift_toll .ow_gift_item_lay').length){
      var ow_gift_pos = $('.ow_gift_toll .ow_gift_item_lay').offset().top;
    }

    if($('.ow_gift_nc .ow_gift_item_lay').length){
      var ow_gift_nc_pos = $('.ow_gift_nc .ow_gift_item_lay').offset().top;
    }

    // joint
    if($('.ow_joint_1 .ow_joint_img').length){
      var ow_joint_pos_1 = $('.ow_joint_1 .ow_joint_img').offset().top;
    }
    if($('.ow_joint_2 .ow_joint_img').length){
      var ow_joint_pos_2 = $('.ow_joint_2 .ow_joint_img').offset().top;
    }

    // rec
    if($('.ow_rec_renai_text').length){
    var ow_rec_pos = $('.ow_rec_renai_text').offset().top;
    }

    // about
    if($('.ow_about_img_01').length){
    var ow_about_pos = $('.ow_about_img_01').offset().top;
    }

    // カード（結果）
    if($('.ow_result_card_1').length){
    var ow_card_pos = $('.ow_result_card_1').offset().top;
    }




    $(window).scroll(function(){
      var topPos = $(window).scrollTop();


// 本題
// --------------------------------------------------------------------

    // ow_result_intro
      if(topPos + w_height> ow_result_intro_pos_1 && ow_result_intro_1 == 0){
        $(function(){
          $('.ow_result_intro_1 .ow_balloon_menu').animate({'opacity':'1'},1500);
          $('.ow_result_intro_1 .ow_result_intro_dec').delay(200).fadeIn(2000);
          $('.ow_result_intro_1 .ow_result_intro_oldman').delay(600).fadeIn(1500);
        });
        ow_result_intro_1 = 1;
      }

      if(topPos + w_height> ow_result_intro_pos_2 && ow_result_intro_2 == 0){
        $(function(){
          $('.ow_result_intro_2 .ow_balloon_menu').animate({'opacity':'1'},1500);
          $('.ow_result_intro_2 .ow_result_intro_dec').delay(200).fadeIn(2000);
          $('.ow_result_intro_2 .ow_result_intro_oldman').delay(600).fadeIn(1500);
        });
        ow_result_intro_2 = 1;
      }


      // result_item_base
      for(var i=0; i<result_item_count; i++){
        if(topPos + w_height> ow_result_item_base[i] && result_item_base < result_item_count){
          $(function(){
              $('.ow_result_item_base .ow_result_body_img_card').eq(i).delay(400).fadeIn(2000);
              $('.ow_result_item_base .ow_result_body_img_hand').eq(i).delay(400).fadeIn(1500);
              $('.ow_result_item_base .ow_result_body_img_card_title').eq(i).delay(1000).animate({'opacity':'1'},1500);
              $('.ow_result_item_base .ow_result_body_text').eq(i).delay(1000).animate({'opacity':'1'},2000);
              $('.ow_result_item_base .ow_result_body_img_oldman_1').eq(i).delay(1500).fadeIn(1500);
            });
          result_item_base = i+1;
        }
      }

    // ow_result_item_person
      if(topPos + w_height> ow_person_pos_1 && ow_person_1 == 0){
        $(function(){
          $('.ow_result_item_person .ow_result_body_img_card').delay(400).fadeIn(2000);
          $('.ow_result_item_person .ow_result_body_img_hand').delay(400).fadeIn(1500);
          $('.ow_result_item_person .ow_result_body_img_card_title').delay(1000).animate({'opacity':'1'},1500);
          $('.ow_result_item_person .ow_result_body_text').eq(0).delay(1000).animate({'opacity':'1'},2000);
        });
        ow_person_1 = 1;
      }

      if(topPos + w_height> ow_person_pos_2 && ow_person_2 == 0){
        $(function(){
          $('.ow_person_title_1').fadeIn(500);
          $('.ow_person_partner').delay(500).fadeIn(2000);
          $('.ow_person_title_2').delay(1000).fadeIn(2000);
          $('.ow_result_item_person .ow_result_body_text').eq(1).delay(1500).animate({'opacity':'1'},2000);
          $('.ow_person_oldman').delay(1500).fadeIn(1500);
        });
        ow_person_2 = 1;
      }

      // ow_result_item_calender
        if(topPos + w_height> ow_calender_pos && ow_calender == 0){
          $(function(){
            $('.ow_result_item_calender .ow_result_body_img_card').delay(400).fadeIn(2000);
            $('.ow_result_item_calender .ow_result_body_img_hand').delay(400).fadeIn(1500);
            $('.ow_result_item_calender .ow_result_body_img_card_title').delay(1000).animate({'opacity':'1'},1500);
            $('.ow_result_item_calender .ow_calender_title').delay(1300).animate({'opacity':'1'},2000);
            $('.ow_result_item_calender .ow_result_body_text').delay(1500).animate({'opacity':'1'},2000);
            $('.ow_result_item_calender .ow_result_body_img_oldman').delay(1500).fadeIn(1500);
          });
          ow_calender = 1;
        }

        // gift
        if(topPos + w_height> ow_gift_pos && ow_gift == 0){
          $(function(){
            $('.ow_gift_toll .ow_gift_item_hand').fadeIn(800);
            $('.ow_gift_toll .ow_gift_item_card_main').fadeIn(800);
            $('.ow_gift_toll .ow_gift_result').delay(2500).animate({'opacity':'0'},1500);
            $('.ow_gift_toll .ow_gift_title_1').delay(2500).animate({'opacity':'0'},1500);
            $('.ow_gift_toll .ow_gift_title_2').delay(2500).animate({'opacity':'0'},1500);
            $('.ow_gift_toll .ow_gift_item_oldman').delay(3000).fadeIn(1500);
            $('.ow_gift_toll .ow_gift_item_card_erase').delay(5500).fadeIn(1500);
            $('.ow_gift_toll .ow_gift_item_card_erase_2').delay(5500).fadeIn(1500);
            $('.ow_gift_toll .ow_gift_item_lay').delay(5500).animate({'opacity':'1'},2000);
            $('.ow_gift_toll .ow_gift_item_oldman').delay(2000).fadeOut(2000);
            $('.ow_gift_toll .ow_gift_title_3').delay(6500).animate({'opacity':'1'},2000);
          });
          ow_gift = 1;
        }

        if(topPos + w_height> ow_gift_nc_pos && ow_gift_nc == 0){
          $(function(){
            $('.ow_gift_nc .ow_gift_item_hand').fadeIn(800);
            $('.ow_gift_nc .ow_gift_item_card_main').fadeIn(800);
            $('.ow_gift_nc .ow_gift_item_oldman').delay(1000).fadeIn(1500);
            $('.ow_gift_nc .ow_gift_item_oldman').delay(1500).animate({'opacity':'0'},1500);
            $('.ow_gift_nc .ow_gift_item_card_erase_2').delay(2500).fadeIn(1500);
            $('.ow_gift_nc .ow_gift_item_lay').delay(2500).animate({'opacity':'1'},2000);
            $('.ow_gift_nc .ow_nc_white_2').delay(3500).animate({'opacity':'1'},2000);

          });
          ow_gif_nc = 1;
        }



        // joint
        if(topPos + w_height> ow_joint_pos_1 && ow_joint_1 == 0){
          $(function(){
            $('.ow_joint_1 .ow_joint_text').animate({'opacity':'1'},2000);
            $('.ow_joint_1 .ow_joint_oldman').delay(800).fadeIn(1000);
          });
          ow_joint_1 = 1;
        }

        if(topPos + w_height> ow_joint_pos_2 && ow_joint_2 == 0){
          $(function(){
            $('.ow_joint_2 .ow_joint_text').animate({'opacity':'1'},2000);
            $('.ow_joint_2 .ow_joint_oldman').delay(800).fadeIn(1000);
          });
          ow_joint_2 = 1;
        }

        // rec
        if(topPos + w_height> ow_rec_pos && ow_rec == 0){
          $(function(){
            $('.ow_rec_renai_text').animate({'opacity':'1'},2000)
          });
          ow_rec = 1;
        }

        // about
          if(topPos + w_height> ow_about_pos && ow_about == 0){
            $(function(){
              $('.ow_about_title_01').delay(400).fadeIn(1000);
              $('.ow_about_title_02').delay(600).fadeIn(1000);
              $('.ow_about_img_01').delay(800).animate({'opacity':'1'},1500);
            });
            ow_about = 1;
          }

// カード（結果）
// --------------------------------------------------------------------

        if(topPos + w_height> ow_card_pos && ow_card == 0){
          $(function(){
            $('.ow_result_card_item_base').delay(1000).fadeIn(2000,function(){
              $('.ow_result_card_item_base').delay(1000).fadeOut(100);
            });

            $('.ow_result_card_item').delay(3500).fadeIn(50);


          });
          ow_card = 1;
        }
    });



// カードシャッフル開始〜終了
// --------------------------------------------------------------------
    var cancelFlag = 0;
    $('.ow_shaffle_btn_start').on('click',function(){
      var index = $('.ow_shaffle_btn_start').index(this);

      if( cancelFlag == 0 ){
          cancelFlag = 1;
      $('.ow_shaffle_btn_start').eq(index).fadeOut(2000,function(){
      $('.ow_fadeout_animetion').eq(index).fadeOut(2000);
        $('.ow_shaffle_btn_end').eq(index).delay(1500).fadeIn(2000);
      });


      //シャッフル準備
      shuffle_ready(index);


      setTimeout(function(){
        cancelFlag = 0;
      },2000);

    }

      // シャッフル終了
      shuffle_end(index);


  });

});






function shuffle_ready(index){
  // シャッフル準備
  $('.ow_result_card_bundle_30').eq(index).addClass('ow_card_rise');
  setTimeout(function(){
  $('.ow_result_card_bundle_29').eq(index).addClass('ow_card_rise2');
  },50);
  setTimeout(function(){
  $('.ow_result_card_bundle_28').eq(index).addClass('ow_card_rise');
  },100);
  setTimeout(function(){
  $('.ow_result_card_bundle_27').eq(index).addClass('ow_card_rise2');
  },150);
  setTimeout(function(){
  $('.ow_result_card_bundle_26').eq(index).addClass('ow_card_rise');
  },200);
  setTimeout(function(){
  $('.ow_result_card_bundle_25').eq(index).addClass('ow_card_rise2');
  },250);
  setTimeout(function(){
  $('.ow_result_card_bundle_24').eq(index).addClass('ow_card_rise');
  },300);
  setTimeout(function(){
  $('.ow_result_card_bundle_23').eq(index).addClass('ow_card_rise2');
  },350);
  setTimeout(function(){
  $('.ow_result_card_bundle_22').eq(index).addClass('ow_card_rise');
  },400);
  setTimeout(function(){
  $('.ow_result_card_bundle_21').eq(index).addClass('ow_card_rise2');
  },450);
  setTimeout(function(){
  $('.ow_result_card_bundle_20').eq(index).addClass('ow_card_rise');
  },500);

  setTimeout(function(){
  $('.ow_result_card_bundle_19').eq(index).addClass('ow_card_rise2');
  },550);
  setTimeout(function(){
  $('.ow_result_card_bundle_18').eq(index).addClass('ow_card_rise');
  },600);
  setTimeout(function(){
  $('.ow_result_card_bundle_17').eq(index).addClass('ow_card_rise2');
  },650);
  setTimeout(function(){
  $('.ow_result_card_bundle_16').eq(index).addClass('ow_card_rise');
  },700);
  setTimeout(function(){
  $('.ow_result_card_bundle_15').eq(index).addClass('ow_card_rise2');
  },750);
  setTimeout(function(){
  $('.ow_result_card_bundle_14').eq(index).addClass('ow_card_rise');
  },800);
  setTimeout(function(){
  $('.ow_result_card_bundle_13').eq(index).addClass('ow_card_rise2');
  },850);
  setTimeout(function(){
  $('.ow_result_card_bundle_12').eq(index).addClass('ow_card_rise');
  },900);
  setTimeout(function(){
  $('.ow_result_card_bundle_11').eq(index).addClass('ow_card_rise2');
  },950);
  setTimeout(function(){
  $('.ow_result_card_bundle_10').eq(index).addClass('ow_card_rise');
  },1000);

  setTimeout(function(){
  $('.ow_result_card_bundle_9').eq(index).addClass('ow_card_rise2');
  },1050);
  setTimeout(function(){
  $('.ow_result_card_bundle_8').eq(index).addClass('ow_card_rise');
  },1100);
  setTimeout(function(){
  $('.ow_result_card_bundle_7').eq(index).addClass('ow_card_rise2');
  },1150);
  setTimeout(function(){
  $('.ow_result_card_bundle_6').eq(index).addClass('ow_card_rise');
  },1200);
  setTimeout(function(){
  $('.ow_result_card_bundle_5').eq(index).addClass('ow_card_rise2');
  },1250);
  setTimeout(function(){
  $('.ow_result_card_bundle_4').eq(index).addClass('ow_card_rise');
  },1300);
  setTimeout(function(){
  $('.ow_result_card_bundle_3').eq(index).addClass('ow_card_rise2');
  },1350);
  setTimeout(function(){
  $('.ow_result_card_bundle_2').eq(index).addClass('ow_card_rise');
  },1400);
  setTimeout(function(){
  $('.ow_result_card_bundle_1').eq(index).addClass('ow_card_rise2');
  },1450);

  // シャッフル
  $('.ow_result_card_conductor').eq(index).find('.ow_result_card_shaffle').delay(2000).animate({'opacity':'1'},1500);
}

function shuffle_end(index){

// シャッフル終了

var cancelFlag = 0;
$('.ow_shaffle_btn_end').eq(index).on('click', function() {

  if( cancelFlag == 0 ){
      cancelFlag = 1;

  $('.ow_shaffle_btn_end').eq(index).fadeOut(1500,function(){
    $('.ow_entry_form_btn_free').eq(index).delay(3500).fadeIn(2000);
    $('.ow_entry_form_btn_submit').eq(index).delay(3500).fadeIn(2000);
    $('.ow_entry_form_btn_nc').eq(index).delay(3500).fadeIn(2000);

    $('.ow_fadein_result_'+index+'_1').delay(4000).fadeIn(2000);
    $('.ow_fadein_result_'+index+'_2').delay(4000).fadeIn(2000);
    $('.ow_fadein_result_'+index+'_3').delay(4000).fadeIn(2000);

    $('.ow_fadein_nc_button').eq(index).delay(3500).fadeIn(2000);
  });
  $('.ow_result_card_conductor').eq(index).find('.ow_result_card_shaffle').fadeOut(600,function(){


    $('.ow_result_card_bundle_1').eq(index).addClass('ow_card_reverse2');
  setTimeout(function(){
    $('.ow_result_card_bundle_2').eq(index).addClass('ow_card_reverse1');
  },50);
  setTimeout(function(){
    $('.ow_result_card_bundle_3').eq(index).addClass('ow_card_reverse2');
  },100);
  setTimeout(function(){
    $('.ow_result_card_bundle_4').eq(index).addClass('ow_card_reverse1');
  },150);
  setTimeout(function(){
    $('.ow_result_card_bundle_5').eq(index).addClass('ow_card_reverse2');
  },200);
  setTimeout(function(){
    $('.ow_result_card_bundle_6').eq(index).addClass('ow_card_reverse1');
  },250);
  setTimeout(function(){
    $('.ow_result_card_bundle_7').eq(index).addClass('ow_card_reverse2');
  },300);
  setTimeout(function(){
    $('.ow_result_card_bundle_8').eq(index).addClass('ow_card_reverse1');
  },350);
  setTimeout(function(){
    $('.ow_result_card_bundle_9').eq(index).addClass('ow_card_reverse2');
  },400);
  setTimeout(function(){
    $('.ow_result_card_bundle_10').eq(index).addClass('ow_card_reverse1');
  },450);
  setTimeout(function(){
    $('.ow_result_card_bundle_11').eq(index).addClass('ow_card_reverse2');
  },500);

  setTimeout(function(){
    $('.ow_result_card_bundle_12').eq(index).addClass('ow_card_reverse1');
  },550);
  setTimeout(function(){
    $('.ow_result_card_bundle_13').eq(index).addClass('ow_card_reverse2');
  },600);
  setTimeout(function(){
    $('.ow_result_card_bundle_14').eq(index).addClass('ow_card_reverse1');
  },650);
  setTimeout(function(){
    $('.ow_result_card_bundle_15').eq(index).addClass('ow_card_reverse2');
  },700);
  setTimeout(function(){
    $('.ow_result_card_bundle_16').eq(index).addClass('ow_card_reverse1');
  },750);
  setTimeout(function(){
    $('.ow_result_card_bundle_17').eq(index).addClass('ow_card_reverse2');
  },800);
  setTimeout(function(){
    $('.ow_result_card_bundle_18').eq(index).addClass('ow_card_reverse1');
  },850);
  setTimeout(function(){
    $('.ow_result_card_bundle_19').eq(index).addClass('ow_card_reverse2');
  },900);
  setTimeout(function(){
    $('.ow_result_card_bundle_20').eq(index).addClass('ow_card_reverse1');
  },950);
  setTimeout(function(){
    $('.ow_result_card_bundle_21').eq(index).addClass('ow_card_reverse2');
  },1000);
  setTimeout(function(){
    $('.ow_result_card_bundle_22').eq(index).addClass('ow_card_reverse1');
  },1050);
  setTimeout(function(){
    $('.ow_result_card_bundle_23').eq(index).addClass('ow_card_reverse2');
  },1100);
  setTimeout(function(){
    $('.ow_result_card_bundle_24').eq(index).addClass('ow_card_reverse1');
  },1150);
  setTimeout(function(){
    $('.ow_result_card_bundle_25').eq(index).addClass('ow_card_reverse2');
  },1200);
  setTimeout(function(){
    $('.ow_result_card_bundle_26').eq(index).addClass('ow_card_reverse1');
  },1250);
  setTimeout(function(){
    $('.ow_result_card_bundle_27').eq(index).addClass('ow_card_reverse2');
  },1300);
  setTimeout(function(){
    $('.ow_result_card_bundle_28').eq(index).addClass('ow_card_reverse1');
  },1350);
  setTimeout(function(){
    $('.ow_result_card_bundle_29').eq(index).addClass('ow_card_reverse2');
  },1400);
  setTimeout(function(){
    $('.ow_result_card_bundle_30').eq(index).addClass('ow_card_reverse1');
    // カード束をサイドに移動
    setTimeout(function(){
      $('.ow_result_card_conductor').eq(index).find('.ow_result_card_bundle').addClass('ow_card_side_position');
    },800);
  },1450);





  });



  // それぞれのカードポジションへ移動
  // 胴
  $('.ow_result_card_bundle_1').eq(index).delay(3500).animate({'top':'200px','left':'710px'},500);
  $('.ow_result_card_bundle_2').eq(index).delay(3500).animate({'top':'200px','left':'610px'},500);
  $('.ow_result_card_bundle_3').eq(index).delay(3500).animate({'top':'200px','left':'810px'},500);
  $('.ow_result_card_bundle_4').eq(index).delay(3500).animate({'top':'330px','left':'710px'},500);
  $('.ow_result_card_bundle_5').eq(index).delay(3500).animate({'top':'330px','left':'610px'},500);
  $('.ow_result_card_bundle_6').eq(index).delay(3500).animate({'top':'330px','left':'810px'},500);
  $('.ow_result_card_bundle_7').eq(index).delay(3500).animate({'top':'460px','left':'710px'},500);
  $('.ow_result_card_bundle_8').eq(index).delay(3500).animate({'top':'460px','left':'610px'},500);
  $('.ow_result_card_bundle_9').eq(index).delay(3500).animate({'top':'460px','left':'810px'},500);
  $('.ow_result_card_bundle_10').eq(index).delay(3500).animate({'top':'590px','left':'710px'},500);
  $('.ow_result_card_bundle_11').eq(index).delay(3500).animate({'top':'590px','left':'610px'},500);
  $('.ow_result_card_bundle_12').eq(index).delay(3500).animate({'top':'590px','left':'810px'},500);

  // 右手
  $('.ow_result_card_bundle_15').eq(index).delay(4000).animate({'top':'270px','left':'510px'},500);
  $('.ow_result_card_bundle_16').eq(index).delay(4000).animate({'top':'400px','left':'510px'},500);
  $('.ow_result_card_bundle_17').eq(index).delay(4000).animate({'top':'530px','left':'510px'},500);
  // 左手
  $('.ow_result_card_bundle_18').eq(index).delay(4000).animate({'top':'270px','left':'910px'},500);
  $('.ow_result_card_bundle_19').eq(index).delay(4000).animate({'top':'400px','left':'910px'},500);
  $('.ow_result_card_bundle_20').eq(index).delay(4000).animate({'top':'530px','left':'910px'},500);
  // 右足
  $('.ow_result_card_bundle_13').eq(index).delay(4500).animate({'top':'720px','left':'610px'},500);
  $('.ow_result_card_bundle_24').eq(index).delay(4500).animate({'top':'830px','left':'645px'},500,function(){
    $(this).addClass('ow_card_rotate');
  });
  $('.ow_result_card_bundle_25').eq(index).delay(4500).animate({'top':'830px','left':'525px'},500,function(){
    $(this).addClass('ow_card_rotate');
  });
  $('.ow_result_card_bundle_26').eq(index).delay(4500).animate({'top':'810px','left':'420px'},500);
  // 左足
  $('.ow_result_card_bundle_14').eq(index).delay(4500).animate({'top':'720px','left':'810px'},500);
  $('.ow_result_card_bundle_28').eq(index).delay(4500).animate({'top':'830px','left':'770px'},500,function(){
    $(this).addClass('ow_card_rotate');
  });
  $('.ow_result_card_bundle_29').eq(index).delay(4500).animate({'top':'830px','left':'893px'},500,function(){
    $(this).addClass('ow_card_rotate');
  });
  $('.ow_result_card_bundle_30').eq(index).delay(4500).animate({'top':'810px','left':'999px'},500);
  // 外周
  $('.ow_result_card_bundle_21').eq(index).delay(5000).animate({'top':'200px','left':'400px'},500);
  $('.ow_result_card_bundle_22').eq(index).delay(5000).animate({'top':'200px','left':'1020px'},500);
  $('.ow_result_card_bundle_23').eq(index).delay(5000).animate({'top':'600px','left':'400px'},500);
  $('.ow_result_card_bundle_27').eq(index).delay(5000).animate({'top':'600px','left':'1020px'},500);


  setTimeout(function(){
    cancelFlag = 0;
  },2000);

 }
});



}
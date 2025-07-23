$(window).scroll(function () {

  var scroll = $(window).scrollTop();

  $('.ow_base').each(function () {
    var ow_base_1 = $(this).offset().top;

    if(scroll >= ow_base_1 - 200) {
      // 一人用
      $(this).find('.ow_parallel_world_bg').addClass('ow_parallel_world_bg_anime');
      $(this).find('.ow_this_world').addClass('ow_this_world_anime');
      $(this).find('.ow_parallel_world_1').addClass('ow_this_world_anime');
      $(this).find('.ow_parallel_world_2').addClass('ow_this_world_anime');
      $(this).find('.ow_this_world_human').addClass('ow_this_world_human_anime');
      $(this).find('.ow_this_world_type').addClass('ow_this_world_type_anime');
      $(this).find('.ow_this_world_seikaku').addClass('ow_this_world_type_anime');
      $(this).find('.ow_parallel_world_human_1').addClass('ow_parallel_world_human_1_anime');
      $(this).find('.ow_parallel_world_1_type').addClass('ow_parallel_world_1_type_anime');
      $(this).find('.ow_parallel_world_1_seikaku').addClass('ow_parallel_world_1_type_anime');
      $(this).find('.ow_parallel_world_human_2').addClass('ow_parallel_world_human_2_anime');
      $(this).find('.ow_parallel_world_2_type').addClass('ow_parallel_world_2_type_anime');
      $(this).find('.ow_parallel_world_2_seikaku').addClass('ow_parallel_world_2_type_anime');
      // 二人用
      $(this).find('.ow_kankei_view_0').addClass('ow_kankei_view_0_amime');
      $(this).find('.ow_kankei_view').addClass('ow_kankei_view_anime');
      $(this).find('.ow_kankei_title').addClass('ow_kankei_title_anime');
    };

  });

  $('.ow_result_item').each(function () {
    var ow_result_item = $(this).offset().top;

    if(scroll >= ow_result_item - 200) {
      //ここから.ow_crack
      $(this).find('.ow_parallel_view_1').addClass('ow_parallel_view_1_anime');
      $(this).find('.ow_parallel_teller_1').addClass('ow_parallel_teller_1_anime');
      $(this).find('.ow_parallel_teller_2').addClass('ow_parallel_teller_2_anime');
      $(this).find('.ow_parallel_light').addClass('ow_parallel_light_anime');
      $(this).find('.ow_parallel_teller_3').addClass('ow_parallel_teller_3_anime');
      $(this).find('.ow_parallel_teller_1').delay(5200).queue(function () {
        $(this).addClass('ow_parallel_teller_1_anime_2');
      });
      $(this).find('.ow_parallel_teller_2').delay(5500).queue(function () {
        $(this).addClass('ow_parallel_teller_2_anime_2');
      });
      $(this).find('.ow_parallel_light').delay(5500).queue(function () {
        $(this).addClass('ow_parallel_light_anime_2');
      });
      $(this).find('.ow_parallel_view_1').delay(6500).queue(function () {
        $(this).addClass('ow_parallel_view_1_anime_2');
      });
      $(this).find('.ow_parallel_teller_3').delay(6500).queue(function () {
        $(this).addClass('ow_parallel_teller_3_anime_2');
      });
      $(this).find('.ow_parallel_view_2').addClass('ow_parallel_view_2_anime');
      $(this).find('.ow_parallel_teller_4').addClass('ow_parallel_teller_4_anime');
      $(this).find('.ow_parallel_idea_1').addClass('ow_parallel_idea_1_anime');
      $(this).find('.ow_parallel_idea_2').addClass('ow_parallel_idea_2_anime');
      $(this).find('.ow_parallel_idea_3').addClass('ow_parallel_idea_3_anime');
      $(this).find('.ow_parallel_idea_1').delay(9800).queue(function () {
        $(this).addClass('ow_parallel_idea_1_anime_2');
      });
      $(this).find('.ow_parallel_idea_2').delay(10300).queue(function () {
        $(this).addClass('ow_parallel_idea_2_anime_2');
      });
      $(this).find('.ow_parallel_idea_3').delay(10800).queue(function () {
        $(this).addClass('ow_parallel_idea_3_anime_2');
      });
      // カレンダー
      $(this).find('.ow_calendar_day').addClass('ow_calendar_day_anime');
    };

  });

  $('.ow_welcome').each(function () {
    var ow_welcome = $(this).offset().top;

    if(scroll >= ow_welcome - 200) {
      //ここから.ow_crack
      $(this).find('.ow_balloon_menu').addClass('ow_balloon_menu_anime');
    };

  });

  $('.ow_joint').each(function () {
    var ow_joint = $(this).offset().top;

    if(scroll >= ow_joint - 200) {
      //ここから.ow_crack
      $(this).find('.ow_balloon_menu').addClass('ow_balloon_menu_anime');
    };

  });

  $('.ow_result_item_wrap').each(function () {
    var ow_welcome = $(this).offset().top;

    if(scroll >= ow_welcome - 400) {
      //ここから.ow_crack
      $(this).find('.ow_result_intro_img').addClass('ow_result_intro_img_anime');
    };

  });

  $('.ow_message').each(function () {
    var ow_message = $(this).offset().top;

    if(scroll >= ow_message - 200) {
      //ここから.ow_crack
      $(this).find('.ow_message_teller').addClass('ow_message_teller_anime');
    };

  });

});

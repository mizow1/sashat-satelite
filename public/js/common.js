/*-------------------------------------------------------------------------

// 手紙のポップアップjs

-------------------------------------------------------------------------*/
$(function() {

	$(".PopUpBox").hide() ;
	$(".PopUpItem").click(function() {
		var oya = $(".oya").offset().top;
		var oya_l = $(".oya").offset().left;
		var tegamig_h = $(document).height();
		$(".PopUpBg").css("height", tegamig_h);
		$(".PopUpBox").hide();
		$(".PopUpBg").fadeIn("fast");
		var tegami_left = Math.floor(($(window).width() - $(this).next(".PopUpBox").width()) / 2);
		var tegami_scrollTop = $(window).scrollTop();
		var tegami_top  = Math.floor(($(window).height() - $(this).next(".PopUpBox").height()) / 2 + tegami_scrollTop  );
		var scroll_event = 'onwheel' in document ? 'wheel' : 'onmousewheel' in document ? 'mousewheel' : 'DOMMouseScroll';
		//PC用
		$(document).on(scroll_event,function(e){e.preventDefault();});
		//SP用
		$(document).on('touchmove.noScroll', function(e) {e.preventDefault();});
    $("html").css({
				"overflow-y":"hidden"
    });
		$(this).next(".PopUpBox").css({
			"top": tegami_top - oya,
			"left": tegami_left - oya_l,
		 });
		$(this).next(".PopUpBox").fadeIn(100);
		var tegami_SectionWidth = $(this).next(".PopUpBox").children(".PopUpSection").width();
		var tegami_Sectionleft = Math.floor(($(this).next(".PopUpBox").width() - tegami_SectionWidth) / 2 );
		$(".PopUpBg").click(function () {
			$(".PopUpBox").fadeOut(100);
			$(".PopUpBg").fadeOut(100);
			//PC用
			var scroll_event = 'onwheel' in document ? 'wheel' : 'onmousewheel' in document ? 'mousewheel' : 'DOMMouseScroll';
			$(document).off(scroll_event);
			//SP用
			$(document).off('.noScroll');
			$("html").css({
					"overflow-y":"auto"
			});
		});
		$(".PopUpClose").click(function () {
			$(".PopUpBox").fadeOut(100);
			$(".PopUpBg").fadeOut(100);
			//PC用
			var scroll_event = 'onwheel' in document ? 'wheel' : 'onmousewheel' in document ? 'mousewheel' : 'DOMMouseScroll';
			$(document).off(scroll_event);
			//SP用
			$(document).off('.noScroll');
			$("html").css({
					"overflow-y":"auto"
			});
		});
	});
});

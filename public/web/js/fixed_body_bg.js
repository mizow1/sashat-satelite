// �w�i�摜�̈�ԉ��܂ŃX�N���[�����ꂽ��w�i�Œ�
$(function() {
	$(window).on('load',function() {
		var window_height = $(window).height();
		// body�̔w�i�Ɏg���摜�̍����i�蓮���́j
		var img_height = 1189;
		var scroll_num;

		$(window).on('scroll .fixed_body_bg',function() {
			scroll_num = $(window).scrollTop();
			if( img_height <= (parseInt(scroll_num) + parseInt(window_height)) ){
				$('.ow_body_bg_01').css({
					'background-position-y':'100%',
					'background-attachment':'fixed'
				});
			}else{
				$('.ow_body_bg_01').css({
					'background-position-y':'0',
					'background-attachment':'scroll'
				});
			}
		});

		$(window).trigger('.fixed_body_bg');

	});
});

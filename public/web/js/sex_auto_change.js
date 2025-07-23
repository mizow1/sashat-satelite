$(function(){
	if($('.js-sexAutoChange2').length){
		$('.js-sexAutoChange1').on('change', function(){
			if($(this).val() == 1){
				$('.js-sexAutoChange2[value="1"]').prop('checked',false);
				$('.js-sexAutoChange2[value="2"]').prop('checked',true);
			}else if($(this).val() == 2){
				$('.js-sexAutoChange2[value="1"]').prop('checked',true);
				$('.js-sexAutoChange2[value="2"]').prop('checked',false);
			}else{
				console.log('error 性別の値が不正です。');
			}
		});
		$('.js-sexAutoChange2').on('change', function(){
			if($(this).val() == 1){
				$('.js-sexAutoChange1[value="1"]').prop('checked',false);
				$('.js-sexAutoChange1[value="2"]').prop('checked',true);
			}else if($(this).val() == 2){
				$('.js-sexAutoChange1[value="1"]').prop('checked',true);
				$('.js-sexAutoChange1[value="2"]').prop('checked',false);
			}else{
				console.log('error 性別の値が不正です。');
			}
		});
	}
});

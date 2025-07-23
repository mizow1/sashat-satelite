$(function(){
	if($('.js-sexAutoChange2').length){
		$('.js-sexAutoChange1').on('change', function(){
			if($(this).val() == 1){
				$('.js-sexAutoChange2').val(2);
			}else if($(this).val() == 2){
				$('.js-sexAutoChange2').val(1);
			}else{
				console.log('error 性別の値が不正です。');
			}
		});
		$('.js-sexAutoChange2').on('change', function(){
			if($(this).val() == 1){
				$('.js-sexAutoChange1').val(2);
			}else if($(this).val() == 2){
				$('.js-sexAutoChange1').val(1);
			}else{
				console.log('error 性別の値が不正です。');
			}
		});
	}
});

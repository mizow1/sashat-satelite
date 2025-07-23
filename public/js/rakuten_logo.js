$(function(){
	if($(window).width() < 640){
		$('.serviceLogo').each(function(i,elem){
			var pass = $(elem).attr('src');
			pass = pass.replace('PC','SP');
			pass = pass.replace('/fortune/','/sp/fortune/');
			$(elem).attr('src',pass);
		});
	}
});
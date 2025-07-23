//ブラウザの戻るで戻ったとき強制リロードさせることでonloadイベントを実行
window.onpageshow = function(event) {
	if (event.persisted) {
		 window.location.reload();
	}
};

$(function(){

//ページ内の画像総数を取得し、読み込んだ画像数との比率で読み込み度合いを計算する。

	//必要なデータを変数に入れる
	var all_img=$("img");
	var img_len=all_img.length;
	var loaded_counter=0;
	
	
	
	//すべての画像にロードイベントを設定
	for(var i=0; i<img_len; i++){

		all_img[i].addEventListener("load",loadFunc);
	}
	
	//画像読み込み完了後にフェードイン
	function loadFunc(){
		var load_progress = Math.round(loaded_counter / img_len * 100);
		if(!loaded_counter){
			$('.ow_loading_error').hide();
		}
		loaded_counter++;
		$(".ow_loading").text("鑑定中です。しばらくお待ち下さい。("+load_progress+'%)');
		window.addEventListener('load',function(){
			$(".ow_loading").text('鑑定結果が出ました。').delay(500).fadeOut();
			$('.ow_result_wrap').css({'overflow':'visible'});
		});
		
	}
});
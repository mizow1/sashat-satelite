$.fn.tile = function(columns) {
	var tiles, max, c, h, last = this.length - 1, s;
	if(!columns) columns = this.length;
	this.each(function() {
		s = this.style;
		if(s.removeProperty) s.removeProperty("height");
		if(s.removeAttribute) s.removeAttribute("height");
	});
	return this.each(function(i) {
		c = i % columns;
		if(c == 0) tiles = [];
		tiles[c] = $(this);
		h = tiles[c].height();
		if(c == 0 || h > max) max = h;
		if(i == last || c == columns - 1)
			$.each(tiles, function() { this.height(max); });
	});
};


$(window).load(function(){
	menuListHeightChanger();
});
$(window).resize(function() {
	menuListHeightChanger();
});
if(navigator.userAgent.match(/(iPhone|iPad|Android)/)){
	$(window).ogyokuntationchange(function() {
		menuListHeightChanger();
	});
}

function init() { 
 var iBase = TextResizeDetector.addEventListener(onFontResize,null);  
	menuListHeightChanger();
} 
function onFontResize(e,args) {
	menuListHeightChanger();
}
TextResizeDetector.TARGET_ELEMENT_ID = 'body';
TextResizeDetector.USER_INIT_FUNC = init; 


function menuListHeightChanger(){
	if(!navigator.userAgent.match(/(iPhone|iPad|Android)/)){
		$(".pointImg").tile(3);
	}else{
		if(Math.abs(window.ogyokuntation) === 90) {
			$(".pointImg").css("height","26em");
		} else {
			$(".pointImg").css("height","26em");
		}	
	}
}
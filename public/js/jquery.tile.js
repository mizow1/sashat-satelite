$(window).load(function(){
	menuListHeightChanger();
});
$(window).resize(function() {
	menuListHeightChanger();
});
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

function menuListHeightChanger(){
	if(!navigator.userAgent.match(/(iPhone|iPad|Android)/)){
		$('#renai .js-tile').tile(2);
		$('#deai .js-tile').tile(2);
		$('#jinsei .js-tile').tile(2);
	}
}

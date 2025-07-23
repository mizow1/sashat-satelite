var myLayer;
var disped_id;
var Layer = Class.create();
Layer.prototype = {
	windowHeight:0,
	windowWidth:0,
	layerPositionY:0,
	initialize :function(){
		var objBody = document.getElementsByTagName("body").item(0);
		var objOverlay = document.createElement("div");
		objOverlay.setAttribute('id','overlay');
		objOverlay.style.display = 'none';
		objOverlay.onclick = function(){myLayer.end()};
		objBody.appendChild(objOverlay);

		var objDummy = document.createElement("iframe");
		objDummy.setAttribute('id','dummy_frame');
		objDummy.style.display = 'none';
		objDummy.style.position = 'absolute';

		objBody.appendChild(objDummy);
		this.size();
	},
	show : function(lay){
		disped_id = lay;
		document.getElementById("overlay").style.display = "block";
		document.getElementById(lay).style.display = "block";
		if(this.layerPositionY==0){
			this.layerPositionY = parseInt(document.getElementById(lay).offsetTop,10);
		}
		var objBody = document.getElementsByTagName("body").item(0);
		document.getElementById(lay).style.top = this.layerPositionY+parseInt(objBody.scrollTop,10);

		this.size();
		//alert(navigator.appName);
		if(navigator.appVersion.match(/MSIE 6.0/) == 'MSIE 6.0'){
			document.getElementById('dummy_frame').style.display ="block";
		}
		document.getElementById("overlay").style.height = this.windowHeight;
		document.getElementById("overlay").style.width = this.windowWidth;
	},
	end : function(){
		document.getElementById("overlay").style.display = "none";
		document.getElementById(disped_id).style.display = "none";
		if(navigator.appVersion.match(/MSIE 6.0/) == 'MSIE 6.0'){
			document.getElementById("dummy_frame").style.display = "none";
		}
	},
	size: function(){

		var xScroll, yScroll;

		if (window.innerHeight && window.scrollMaxY) {
			xScroll = window.innerWidth + window.scrollMaxX;
			yScroll = window.innerHeight + window.scrollMaxY;
		} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
			xScroll = document.body.scrollWidth;
			yScroll = document.body.scrollHeight;
		} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
			xScroll = document.body.offsetWidth;
			yScroll = document.body.offsetHeight;
		}

		var windowWidth, windowHeight;

		if (self.innerHeight) {	// all except Explorer
			if(document.documentElement.clientWidth){
				windowWidth = document.documentElement.clientWidth;
			} else {
				windowWidth = self.innerWidth;
			}
			windowHeight = self.innerHeight;
		} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
			windowWidth = document.documentElement.clientWidth;
			windowHeight = document.documentElement.clientHeight;
		} else if (document.body) { // other Explorers
			windowWidth = document.body.clientWidth;
			windowHeight = document.body.clientHeight;
		}

		// for small pages with total height less then height of the viewport
		if(yScroll < windowHeight){
			pageHeight = windowHeight;
		} else {
			pageHeight = yScroll;
		}

		if(xScroll < windowWidth){
			pageWidth = xScroll;
		} else {
			pageWidth = windowWidth;
		}

		this.windowWidth  = pageWidth;
		this.windowHeight = pageHeight;

		//alert(xScroll+":"+pageHeight);

		arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight)
		return arrayPageSize;
	}
}
function initLayer() { myLayer = new Layer(); }
Event.observe(window, 'load', initLayer, false);

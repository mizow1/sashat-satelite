function setDay(yObj,mObj,dObj){
	var years = parseInt(yObj.options[yObj.selectedIndex].value,10);
	var months = parseInt(mObj.options[mObj.selectedIndex].value,10);
	var lastday = monthday(years,months);
	var itemnum = dObj.length;
	if (lastday - 1 < dObj.selectedIndex) {
		dObj.selectedIndex = lastday - 1;
	}
	dObj.length = lastday;
	for (cnt = itemnum + 1;cnt <= lastday;cnt++) {
		dObj.options[cnt - 1].text = cnt;
	}
}
function monthday(years,months){
	var lastday = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	if (((years % 4 == 0) && (years % 100 != 0)) || (years % 400 == 0)){
		lastday[1] = 29;
	}

	return lastday[months - 1];
}
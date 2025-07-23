function seimei(){
	var sei = new Array("R");
	var sei2 = sei.length + 2;
	var mei = new Array("‘¾","˜Y","“c");
	var mei2 = mei.length + 2;
	var kaku = new Array(30,29,10,9);//‘Ši,ŠOŠi,“VŠi,’nŠi
	var reikaku = new Array("flame","flame","flame","flame");
	var soukaku = 30;
	var colCount = sei2 + mei2;
	var i = 0;//is–Ú
	var j = 0;
	var k = 0;
	var html_data = "";
	
	for(i=1;i<colCount+1;i++){
		alert("start:"+i);
		html_data += '<!--¥' + i + 's–Ú-->';
		html_data += '<tr>';
		alert(i+"s–Ú1—ñ");
		//1—ñ–Ú(‘‰æ)
		if(i==1){
			html_data += '<td class="kaku" rowspan="' + colCount + '">‘Ši<br />' + soukaku + '</td><!-- rowspan=©–¼+4 --><!-- 1—ñ -->';
		}
		
		//2—ñ–Ú(‘Ši˜g)
		//Å‰
		alert(i+"s–Ú2—ñ");
		if(i==1){
			alert("1s–Ú2—ñ–Ú‚Ìˆ—");
			html_data += '<td class="ue"></td><!-- 2—ñ 1-->';
		//ÅŒã
		}else if(i==colCount){
			alert("ÅŒãs2—ñ–Ú‚Ìˆ—");
			html_data += '<td class="sita"></td><!-- 2—ñ 2-->';
		//ŠÔ
		}else{
			//alert("‚ ‚¢‚¾2—ñ–Úˆ—");
			html_data += '<td class="hidari"></td><!-- rowspan=©–¼+2 --><!-- 2—ñ3 -->';
		}
		
		//3—ñ–Ú(–¼‘O—ìŠi)
		alert(i+"s–Ú3—ñ");
		//©
		if(i <= sei2){
			alert("©‹ó”’");
			html_data += '<td></td><!-- © --><!-- 3—ñ1 -->';
		}
		//–¼(Å‰)
		if(i == sei2+1){
			alert("–¼‘O—ìŠiã‹ó”’" + i+"s–Ú3—ñ")
			html_data += '<td></td><!--–¼Å‰--><!-- 3—ñ 2-->';
		}
		//–¼(Ÿ”•ª)
		if(i >= sei2+2 && i< colCount ){
			alert("–¼‘O—ìŠi"+reikaku[k]);
			html_data += '<td><img src="' + reikaku[k] + '.png" alt="" /></td><!-- 3 --><!-- mei' + k + 'š–Ú --><!-- 3—ñ3 -->';
		}
		//–¼(ÅŒã)
		if(i == colCount){
			alert("–¼‘O—ìŠi‰º‹ó”’");
			html_data += '<td></td><!--–¼ÅŒã--><!-- 3—ñ4 -->';
		}
		
		//4—ñ–Ú(’nŠi)
		alert(i+"s–Ú4—ñ");
		//©
		if(i <= sei2){
			alert("‹ó”’");
			html_data += '<td></td><!-- © --><!-- 4—ñ1 -->';
		}
		//–¼
		if(i == sei2+1){
			alert(kaku[3]);
			html_data += '<td class="kaku" rowspan="' + mei2 + '">’nŠi<br />' + kaku[3] + '</td><!-- 4—ñ 2-->';
		}
		
		//5—ñ–Ú(’nŠi˜g)
		alert(i+"s–Ú5—ñ")
		//©
		if(i <= sei2){
			//alert("‹ó”’");
			html_data += '<td></td><!-- © --><!-- 5—ñ1 -->';
		}else{
			//–¼Å‰
			if(i == sei2+1){
				html_data += '<td class="ue"></td><!-- 5—ñ 2-->';
			//–¼ÅŒã
			}else if(i == colCount){
				//alert("sita");
				html_data += '<td class="sita"></td><!-- 5—ñ 3-->';
			//–¼ŠÔ
			}else{
				//alert("hidarii");
				html_data += '<td class="hidari"></td><!-- 5—ñ 4-->';
			}
		}
		
		//6—ñ–Ú(©–¼)
		alert(i+"s–Ú6—ñ");
		//©Å‰
		if(i==1){
			alert("©Å‰");
			html_data += '<td></td><!-- ©Å‰ --><!-- 6—ñ 1-->';
		}
		//©
		if(1 < i && i < sei2){
			alert(i + "s" + sei[i-2]);
			html_data += '<td class="name">' + sei[i-2] + '</td><!-- 6—ñ 2-->';
		}
		//©ÅŒã
		if(i == sei2){
			alert("©ÅŒã");
			html_data += '<td></td><!-- ©ÅŒã --><!-- 6—ñ 3-->';
		}
		
		//–¼Å‰
		if(i == sei2+1){
			alert("–¼Å‰");
			html_data += '<td></td><!-- –¼Å‰ --><!-- 6—ñ 4-->';
		}
		//–¼
		if( sei2+1 < i && i < colCount){
			alert("mei"+ mei[i-(sei2+2)]);
			html_data += '<td class="name">' + mei[i-(sei2+2)] + '</td><!-- 6 --><!-- 6—ñ 5-->';
		}
		if(i == colCount){
			html_data += '<td></td><!-- –¼ÅŒã --><!-- 6—ñ 6-->';
		}
		
		//7—ñ–Ú(“VŠi˜g)
		//©
		if(i==1){
			html_data += '<td class="ue"></td><!-- 7—ñ©Å‰ -->';
		}
		if(1<i && i<sei2){
			html_data += '<td class="migi"></td><!-- 7—ñ©ŠÔ -->';
		}
		if(i == sei2){
			html_data += '<td class="sita"></td><!-- 7—ñ©ÅŒã -->';
		}
		//–¼
		if(i > sei2){
			html_data += '<td></td><!-- 7—ñ–¼ -->';
		}
		
		
		//8—ñ–Ú(“VŠi)
		if(i==1){
			html_data += '<td rowspan="' + sei2 + '">“VŠi' + kaku[2] + '</td><!-- rowspan=©+2 --><!-- 8—ñÅ‰ -->';
		}
		if(i<=sei2){
			html_data += '<!-- <td></td> --><!-- 8—ñÅ‰ˆÈŠO -->';
		}
		if(i > sei2){
			html_data += '<td></td><!-- 8—ñÅ‰ˆÈŠO -->';
		}
		
		//9—ñ–Ú(©—ìŠi)
		if(i==1){
			html_data += '<td></td><!-- 9—ñÅ‰ -->';
		}
		if(1<i && i<sei2){
			html_data += '<td><img src="' + reikaku[1] + '.png" alt="" /></td><!-- 9 -->';
		}
		if(i >= sei2){
			html_data += '<td></td><!-- 9—ñ‘¼ -->';
		}
		
		//10—ñ–Ú(ŠOŠi˜g)
		if(i == 1){
			html_data += '<td class="ue"></td><!-- 10—ñ––’[-->';
		}
		if(1 <i && i < colCount){
			html_data += '<td class="migi"></td><!-- 10—ñŠÔ -->';
		}
		if(i == colCount){
			html_data += '<td class="sita"></td><!-- 10—ñ––’[-->';
		}
		
		//11—ñ–Ú(ŠOŠi)
		if(i == 1){
			html_data += '<td class="kaku" rowspan="' + colCount + '">ŠOŠi<br />' + kaku[1] + '</td><!-- 11 --><!-- rowspan=©–¼+4 -->';
		}
		
		html_data += '';
		html_data += '';
		html_data += '';
		html_data += '';
		html_data += '';
		html_data += '';
		html_data += '';
		html_data += '';
		html_data += '</tr>';
	}
	$(".seimei table").html(html_data);
}// /seimei()

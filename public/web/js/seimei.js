function seimei(){
	var sei = new Array("�R");
	var sei2 = sei.length + 2;
	var mei = new Array("��","�Y","�c");
	var mei2 = mei.length + 2;
	var kaku = new Array(30,29,10,9);//���i,�O�i,�V�i,�n�i
	var reikaku = new Array("flame","flame","flame","flame");
	var soukaku = 30;
	var colCount = sei2 + mei2;
	var i = 0;//i�s��
	var j = 0;
	var k = 0;
	var html_data = "";
	
	for(i=1;i<colCount+1;i++){
		alert("start:"+i);
		html_data += '<!--��' + i + '�s��-->';
		html_data += '<tr>';
		alert(i+"�s��1��");
		//1���(����)
		if(i==1){
			html_data += '<td class="kaku" rowspan="' + colCount + '">���i<br />' + soukaku + '</td><!-- rowspan=����+4 --><!-- 1�� -->';
		}
		
		//2���(���i�g)
		//�ŏ�
		alert(i+"�s��2��");
		if(i==1){
			alert("1�s��2��ڂ̏���");
			html_data += '<td class="ue"></td><!-- 2�� 1-->';
		//�Ō�
		}else if(i==colCount){
			alert("�Ō�s2��ڂ̏���");
			html_data += '<td class="sita"></td><!-- 2�� 2-->';
		//��
		}else{
			//alert("������2��ڏ���");
			html_data += '<td class="hidari"></td><!-- rowspan=����+2 --><!-- 2��3 -->';
		}
		
		//3���(���O��i)
		alert(i+"�s��3��");
		//��
		if(i <= sei2){
			alert("����");
			html_data += '<td></td><!-- �� --><!-- 3��1 -->';
		}
		//��(�ŏ�)
		if(i == sei2+1){
			alert("���O��i���" + i+"�s��3��")
			html_data += '<td></td><!--���ŏ�--><!-- 3�� 2-->';
		}
		//��(������)
		if(i >= sei2+2 && i< colCount ){
			alert("���O��i"+reikaku[k]);
			html_data += '<td><img src="' + reikaku[k] + '.png" alt="" /></td><!-- 3 --><!-- mei' + k + '���� --><!-- 3��3 -->';
		}
		//��(�Ō�)
		if(i == colCount){
			alert("���O��i����");
			html_data += '<td></td><!--���Ō�--><!-- 3��4 -->';
		}
		
		//4���(�n�i)
		alert(i+"�s��4��");
		//��
		if(i <= sei2){
			alert("��");
			html_data += '<td></td><!-- �� --><!-- 4��1 -->';
		}
		//��
		if(i == sei2+1){
			alert(kaku[3]);
			html_data += '<td class="kaku" rowspan="' + mei2 + '">�n�i<br />' + kaku[3] + '</td><!-- 4�� 2-->';
		}
		
		//5���(�n�i�g)
		alert(i+"�s��5��")
		//��
		if(i <= sei2){
			//alert("��");
			html_data += '<td></td><!-- �� --><!-- 5��1 -->';
		}else{
			//���ŏ�
			if(i == sei2+1){
				html_data += '<td class="ue"></td><!-- 5�� 2-->';
			//���Ō�
			}else if(i == colCount){
				//alert("sita");
				html_data += '<td class="sita"></td><!-- 5�� 3-->';
			//����
			}else{
				//alert("hidarii");
				html_data += '<td class="hidari"></td><!-- 5�� 4-->';
			}
		}
		
		//6���(����)
		alert(i+"�s��6��");
		//���ŏ�
		if(i==1){
			alert("���ŏ�");
			html_data += '<td></td><!-- ���ŏ� --><!-- 6�� 1-->';
		}
		//��
		if(1 < i && i < sei2){
			alert(i + "�s" + sei[i-2]);
			html_data += '<td class="name">' + sei[i-2] + '</td><!-- 6�� 2-->';
		}
		//���Ō�
		if(i == sei2){
			alert("���Ō�");
			html_data += '<td></td><!-- ���Ō� --><!-- 6�� 3-->';
		}
		
		//���ŏ�
		if(i == sei2+1){
			alert("���ŏ�");
			html_data += '<td></td><!-- ���ŏ� --><!-- 6�� 4-->';
		}
		//��
		if( sei2+1 < i && i < colCount){
			alert("mei"+ mei[i-(sei2+2)]);
			html_data += '<td class="name">' + mei[i-(sei2+2)] + '</td><!-- 6 --><!-- 6�� 5-->';
		}
		if(i == colCount){
			html_data += '<td></td><!-- ���Ō� --><!-- 6�� 6-->';
		}
		
		//7���(�V�i�g)
		//��
		if(i==1){
			html_data += '<td class="ue"></td><!-- 7�񐩍ŏ� -->';
		}
		if(1<i && i<sei2){
			html_data += '<td class="migi"></td><!-- 7�񐩊� -->';
		}
		if(i == sei2){
			html_data += '<td class="sita"></td><!-- 7�񐩍Ō� -->';
		}
		//��
		if(i > sei2){
			html_data += '<td></td><!-- 7�� -->';
		}
		
		
		//8���(�V�i)
		if(i==1){
			html_data += '<td rowspan="' + sei2 + '">�V�i' + kaku[2] + '</td><!-- rowspan=��+2 --><!-- 8��ŏ� -->';
		}
		if(i<=sei2){
			html_data += '<!-- <td></td> --><!-- 8��ŏ��ȊO -->';
		}
		if(i > sei2){
			html_data += '<td></td><!-- 8��ŏ��ȊO -->';
		}
		
		//9���(����i)
		if(i==1){
			html_data += '<td></td><!-- 9��ŏ� -->';
		}
		if(1<i && i<sei2){
			html_data += '<td><img src="' + reikaku[1] + '.png" alt="" /></td><!-- 9 -->';
		}
		if(i >= sei2){
			html_data += '<td></td><!-- 9�� -->';
		}
		
		//10���(�O�i�g)
		if(i == 1){
			html_data += '<td class="ue"></td><!-- 10�񖖒[-->';
		}
		if(1 <i && i < colCount){
			html_data += '<td class="migi"></td><!-- 10��� -->';
		}
		if(i == colCount){
			html_data += '<td class="sita"></td><!-- 10�񖖒[-->';
		}
		
		//11���(�O�i)
		if(i == 1){
			html_data += '<td class="kaku" rowspan="' + colCount + '">�O�i<br />' + kaku[1] + '</td><!-- 11 --><!-- rowspan=����+4 -->';
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

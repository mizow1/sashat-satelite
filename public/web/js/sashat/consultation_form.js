//多重クリック防止処理
var consultation_SubmitCount = 0;

function consultation(){
  if(consultation_SubmitCount==1){
    return ;
  }
  consultation_SubmitCount=1;

  var alert_text = '';
  var alert_text1 = '';
  var alert_text2 = '';
  var alert_text3 = '';
  var alert_text4 = '';
  if($('#sashat_text').val() === ''){
    alert_text4 = '相談内容を入力してください。\n';
  }
  if($('#sashat_email').val() === ''){
    alert_text3 = 'メールアドレスを入力してください。\n';
  }
  if($('#ashat_yy').val() == "-1" || $('#sashat_mm').val() == "-1" || $('#sashat_dd').val() == "-1"){
    alert_text2 = '生年月日を入力してください。\n';
  }
  if($('#sashat_name').val() === ''){
    alert_text1 = '名前を入力してください。\n';
  }
  if(alert_text1 || alert_text2 || alert_text3 || alert_text4){
    alert(alert_text1+alert_text2+alert_text3+alert_text4);
    consultation_SubmitCount=0;
  }else{
    $('#consultation_form2').submit();
  }
};

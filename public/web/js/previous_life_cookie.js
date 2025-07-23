//htmlが読み込まれた際に、一度もリロードしてなかった場合、特定のcookieを削除する。
// $(window).ready(function(){
//   if (window.performance) {
//     if (performance.navigation.type === 1) {
//     } else {
//       $.cookie('previous_life_flag',"",{path:"/",expires:-1});
//     }
//   }
// });


//アニメーションのボタンを押下した際に下記メソッド実行　引数は選択した前世ナンバー
function previous_life_cookie(target_count,yymmdd){
  var previous_life_cookie_data =  getCookieArray();
  //処理済み判定のcookieが存在しない時のみ下記の処理を実行
  if(!$.cookie('previous_life_flag')){
    //1人用か2人用か判定
    if(target_count == 1){
      previous_life_1(previous_life_cookie_data,1,yymmdd);
    }else{
      previous_life_2(previous_life_cookie_data,2,yymmdd);
    }
  }
}

//前世1のcookie処理
function previous_life_1(previous_life_cookie_data,target_count,yymmdd){
  var cookie_pattern = '/^etemnei_previous_life_2'+yymmdd+'/g';
  var previous_life_cookie_tmp = 'etemnei_previous_life_1_'+yymmdd;
  getPrevious_life_cookie(previous_life_cookie_data,cookie_pattern,previous_life_cookie_tmp,target_count);
}

//前世1のcookie処理
function previous_life_2(previous_life_cookie_data,target_count,yymmdd){
  var cookie_pattern = '/^etemnei_previous_life_2'+yymmdd+'/g';
  var previous_life_cookie_tmp = 'etemnei_previous_life_2_'+yymmdd;
  getPrevious_life_cookie(previous_life_cookie_data,cookie_pattern,previous_life_cookie_tmp,target_count);
}

//cookie情報を配列にして返す
function getCookieArray(){
  var arr = new Array();
  if(document.cookie != ''){
    var tmp = document.cookie.split('; ');
    for(var i=0;i<tmp.length;i++){
      var data = tmp[i].split('=');
      arr[i] = decodeURIComponent(data[1]);
    }
  }
  return arr;
}

function getPrevious_life_cookie(previous_life_cookie_data,cookie_pattern,previous_life_cookie_tmp,target_count){
  var result = new Array();
  //cookie内に対象の文字列(cookie)がいくつ存在するか確認
  previous_life_cookie_data.forEach(function(element) {
    if(element.match(cookie_pattern) != null){
      result.push(element);
    }
  });


  // //対象のcookieが3つ存在する場合、一番古いcookieを削除する。
  // if(result.length == 3){
  //   dropPrevious_life_cookie(result);
  // }

  //cookie作成 引数は作成するcookieのテンプレート？
  createPrevious_life_cookie(previous_life_cookie_tmp,target_count);
}

//一番古いcookieを取得して削除
// function dropPrevious_life_cookie(result){
//
//   //cookieの中身を取得
//   var cookie_1 = result[0].split(';');
//   var cookie_2 = result[1].split(';');
//   var cookie_3 = result[2].split(';');
//
//   //タイムスタンプの情報を比較して一番古い(値が小さい)ものを取得
//   var min = cookie_1[1];
//   var min_cookie_key = cookie_1[0];
//
//   if(cookie_2[1] < min){
//     min = cookie_2[1];
//     min_cookie_key = cookie_1[0];
//   }
//   if(cookie_3[1] < min){
//     min = cookie_3[1];
//     min_cookie_key = cookie_1[0];
//   }
//
//   //古いcookieを過去日付で上書き削除
//   $.cookie(min_cookie_key,"",{path:"/",expires:-1});
//   return;
// }

//cookieの作成 javascriptでcookieを設定すると期限が付与できないのでphpでcookieを設定
function createPrevious_life_cookie(previous_life_cookie_tmp,target_count){
  $.ajax({
      url:'https://contents.goodfortune.jp/etenmei/web/js/setCookie.php',
      type:'POST',
      dataType: "text",
      data:{
        'target_count':target_count,
        'tmp':previous_life_cookie_tmp
      }
  })
  // Ajaxリクエストが成功した時発動
  .done( (data) => {
      // $('.result').html(data);
      //処理の実行が正常に終了する前にcookieを設定
      $.cookie('previous_life_flag',"1",{path:"/",expires:7});
      location.reload();
  })
  // Ajaxリクエストが失敗した時発動
  .fail( (data) => {
      // $('.result').html(data);
      console.log(data);
  })
  // Ajaxリクエストが成功・失敗どちらでも発動
  .always( (data) => {
    var function_flag = 0;
  });
}

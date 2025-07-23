<?php
class KyodoSocket{

	private $timeout;
	private $usleep_time;

	public function __construct(){
		// タイムアウト時間：仕事的に許されている全体の時間から 2/3
		// スリープ時間：下記集計結果を元に指定
		// https://drive.google.com/file/d/1uJhBHYOvAzda_4HtmBUglOwQ4rAXYX_K/view?usp=sharing
		$this->timeout = ceil(1 + ini_get('max_execution_time') * 2 / 3); // タイムアウト時間
		$this->usleep_time = 1700; // スリープ時間
	}

	function httpsPost($url_string, $data){
		$curl=1;
		if($curl){
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url_string);
			curl_setopt($curl, CURLOPT_POST, TRUE);
			$post = $this->toPostFormat($data);
			$postdata = implode('&', $post);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  // オレオレ証明書対策
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);  //
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_COOKIEJAR,      'cookie');
			curl_setopt($curl, CURLOPT_COOKIEFILE,     'tmp');
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); // Locationヘッダを追跡
			$response = curl_exec($curl);
			curl_close($curl);
		}else{
			$url = parse_url($url_string);
			$request = $this->getRequestString($url, $data);
			$url['url'] = 'ssl://'.$url['host'].':443';
			$errno = 0;
			$errstr = '';

			$context_opt = stream_context_create();
			stream_context_set_option($context_opt, 'ssl', 'verify_peer', false);
			stream_context_set_option($context_opt, 'ssl', 'verify_peer_name', false);
			stream_context_set_option($context_opt, 'ssl', 'verify_host', false);
			stream_context_set_option($context_opt, 'ssl', 'allow_self_signed', true);

			if(
				$_SERVER[SERVER_ENV_NAME] == SERVER_ENV_STAGE ||
				$_SERVER[SERVER_ENV_NAME] == SERVER_ENV_TEST
			){
				//ステージ環境の場合のみSSLの自己証明のための設定
				// WEBサーバーのhostsに　106.187.80.36　k-vaphkid01　を追加しておく事
				stream_context_set_option($context_opt, 'ssl', 'cafile', LIB_DIR."local/kyodo/stage_ca.pem");
			}
			$fp = stream_socket_client($url['url'], $errno, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $context_opt );

			if (!$fp) {
				return '';
			}
			fwrite($fp, $request);
			$response = '';
			while (!feof($fp)) {
				$response = fgets($fp, 8192);
			}
			fclose($fp);
		}

		return $response;

	}

	function httpsPostMulti($urls, $data){

		//マルチハンドル初期化
		$mh = curl_multi_init();

		//後で使うため個別ハンドル保管用の配列を準備
		$ch_array = array();

		foreach($urls as $resource => $val) {
			$url_string = $val['url'];
			$curl = curl_init();
			$ch_array[$resource] = $curl;
			curl_setopt($curl, CURLOPT_URL, $url_string);
			curl_setopt($curl, CURLOPT_POST, TRUE);
			$post = $this->toPostFormat($data[$resource]);
			$postdata = implode('&', $post);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  // オレオレ証明書対策
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);  //
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_COOKIEJAR,      'cookie');
			curl_setopt($curl, CURLOPT_COOKIEFILE,     'tmp');
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); // Locationヘッダを追跡
			curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout); // タイムアウト時間
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout); // サーバーへのタイムアウト時間
			curl_multi_add_handle($mh, $curl);
		}

		$curl_get_count = 0;
		do {
			curl_multi_exec($mh, $running); //multiリクエストスタート及びステータスを更新
			$ready = curl_multi_select($mh, $this->timeout); // レスポンスをcurl_multi_selectで待つ
			$info = curl_multi_info_read($mh, $remains); //変化のあったcurlハンドラを取得する
			if($info !== false){
				$curl_get_count++;
			}
			if ($ready == -1) {
				//selectに失敗。ちょっと待ってからretry
				usleep($this->usleep_time);
			}elseif ($info === false || $remains > 0) {
				// 現在の転送についての情報取得に失敗
				continue;
			}elseif ($ready == 0) {
				// 全てのmultiリクエスト処理が完了しており、リクエスト数と取得数が同一であればループからぬける
				if(
					$running == CURLM_OK &&
					$curl_get_count == count($urls)
				){
					break;
				}

				// タイムアウト。ログ出力
				$date = "[".date('Y-m-d H:i:s')."] ";
				$log_message = 'curl_multi_exec : access_point(TimeOut): '.json_encode($urls,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
				error_log($date.$log_message."\n\n");
//				return array();
			}
		} while ($running > 0);

		//HTML取得
		foreach ($ch_array as $resource => $curl) {
			$response[$resource] = curl_multi_getcontent($curl);
			curl_multi_remove_handle($mh, $curl);
			curl_close($curl);
		}
		//終了処理
		curl_multi_close($mh);
		return $response;
	}

	private function getRequestString($url, $data){
		// ヘッダ
		$request = 'POST '.$url['path'].' HTTP/1.0'."\r\n";
		$request .= 'Host: '.$url['host']."\r\n";
		$request .= 'User-Agent: PHP/'.phpversion()."\r\n";

		// postデータ追加
		$post = $this->toPostFormat($data);
		$postdata = implode('&', $post);
		$request .= 'Content-Type: application/x-www-form-urlencoded'."\r\n";
		$request .= 'Content-Length: '.strlen($postdata)."\r\n";
		$request .= "\r\n";
		$request .= $postdata;
		return $request;
	}

	private function toPostFormat($data){
		$post = array();
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				// 同一添字であっても添字が数値の場合は上書きされない
				$post = array_merge($post, $this->toPostFormat($value));
			} else {
				$post[] = $key.'='.rawurlencode($value);
			}
		}
		return $post;
	}
}

<?php
// TODO:他にもredis系のPHP関数存在するので、必要に応じて追加する

class SRA{
	const REDIS_DB_MIN = 0;
	const REDIS_DB_MAX = 15;

	private static $objRedis;
	private static $readonly;
	//取得した値をプロセス内で引き回すためのクラス内変数　特定のキーのときに値を格納
	private static $get_temporary = array(
		REDIS_KEY_CATEGORY_URL_MASTER => '',
	);
	/**
	 * init redisへの接続を行う
	 */
	public static function init($readonly = false){
		$ping_result = '';
		if(is_object(self::$objRedis)){
			$ping_result = self::ping();
		}

		if(
			$ping_result=="+PONG" &&
			$readonly == self::$readonly
		){
			// 同じ$readonlyで接続作成済みなので、向き先のDBのみデフォルトに設定しなおしてinit処理終了
			self::selectDB();
			return;
		}
		self::$objRedis = new Redis();
		self::$readonly = $readonly;

		// slave
		if($readonly){
			self::$objRedis->connect(READ_REDIS_VIP, READ_REDIS_PORT);
			if(!empty(READ_REDIS_PASS)){
				self::$objRedis->auth(READ_REDIS_PASS);
			}
		// master
		}else{
			self::$objRedis->connect(REDIS_VIP, REDIS_PORT);
			if(!empty(REDIS_PASS)){
				self::$objRedis->auth(REDIS_PASS);
			}
		}
	}

	/**
	 * 接続の閉鎖
	 */
	public static function close(){
		self::$objRedis->close();
	}

	/**
	 * 再接続
	 */
	public static function reconnect($readonly = false){
		self::close();

		// slave
		if($readonly){
			self::$objRedis->connect(READ_REDIS_VIP, READ_REDIS_PORT);
			if(!empty(READ_REDIS_PASS)){
				self::$objRedis->auth(READ_REDIS_PASS);
			}
		// master
		}else{
			self::$objRedis->connect(REDIS_VIP, REDIS_PORT);
			if(!empty(REDIS_PASS)){
				self::$objRedis->auth(REDIS_PASS);
			}
		}
	}

	/**
	 * 接続確認
	 */
	public static function ping(){
		return self::$objRedis->ping();
	}

	/**
	 * [初期化関連]使用するDBを指定
	 *
	 * id:0以外を指定したい場合に使用する。
	 * id範囲は0～15。
	 *
	 * @param int $db_id
	 * @return int $db_id
	 */
	public static function selectDB($db_id=REDIS_DB_DEFAULT){
		if(
			!is_numeric($db_id) ||
			$db_id<self::REDIS_DB_MIN ||
			$db_id>self::REDIS_DB_MAX
		){
			ErrorLog::write('[redis error] select db_id irregular');
			return 0;
		}
		self::$objRedis->select($db_id);
		return $db_id;
	}

	/**
	 * [初期化関連]データベース単位で全部消す
	 */
	public static function flushDb(){
		// returnは常にtrueなので返り値必要なし
		self::$objRedis->flushDb();
	}


	/**
	 * [文字列型]キーと値をセットする。第3引数にexpireを秒単位で指定可能。
	 *
	 * @param string $key
	 * @param string $val
	 * @param int $expire
	 * @return bool
	 */
	public static function set($key='', $val='', $expire=0){
		if(empty($key)){
			ErrorLog::write('[redis error] set key empty');
			return false;
		}

		if(empty($expire)){
			self::$objRedis->set($key, $val);
		}elseif(
			is_numeric($expire) &&
			$expire > 0
		){
			self::$objRedis->set($key, $val, $expire);
		}else{
			ErrorLog::write('[redis error] set expire irregular');
			return false;
		}

		return true;
	}

	/**
	 * [文字列型]既存がない時だけキーと値をセットする。
	 *
	 * @param string $key
	 * @param string $val
	 * @return bool
	 */
	public static function setNx($key='', $val=''){
		if(empty($key)){
			ErrorLog::write('[redis error] setNx key empty');
			return false;
		}
		self::$objRedis->setNx($key, $val);
		return true;
	}

	/**
	 * [文字列型]キーの値を取得
	 *
	 * @param string $key
	 * @return string
	 */
	public static function get($key=''){
		if(empty($key)){
			ErrorLog::write('[redis error] get key empty');
			return '';
		}
		//$get_temporaryで定義されているキーだった場合はクラス内変数に値が格納されていないかを確認
		//格納されていたらgetせずにその値を返す
		if(array_key_exists($key, self::$get_temporary)){
			if(!empty(self::$get_temporary[$key])){
				return self::$get_temporary[$key];
			}
			self::$get_temporary[$key] = self::$objRedis->get($key);
			return self::$get_temporary[$key];
		}
		return self::$objRedis->get($key);
	}

	/**
	 * [bool型]クラス変数内に指定のキーとキーに紐づく値が入っているか
	 * @param string $key
	 * @return boolean
	 */
	public static function existsTemporary($key){
		if(empty($key)){
			ErrorLog::write('[redis error] existsTemporary key empty');
			return false;
		}
		if(array_key_exists($key, self::$get_temporary)){
			return !empty(self::$get_temporary[$key]);
		}
		return false;
	}




	/**
	 * [文字列型]置換を行い、置換前のvalueがreturnされる
	 *
	 * @param string $key
	 * @return string
	 */
	public static function getSet($key='', $val=''){
		if(empty($key)){
			ErrorLog::write('[redis error] getSet key empty');
			return '';
		}
		return self::$objRedis->getSet($key, $val);
	}

	/**
	 * [リスト型]キーで指定した要素の先頭へのpush
	 *
	 * @param string $key
	 * @param string $val
	 * @return int
	 */
	public static function lPush($key='', $val=''){
		if(empty($key)){
			ErrorLog::write('[redis error] lPush key empty');
			return 0;
		}

		return self::$objRedis->lPush($key, $val);
	}

	/**
	 * [リスト型]キーで指定した要素の末尾へのpush
	 *
	 * @param string $key
	 * @param string $val
	 * @return int
	 */
	public static function rPush($key='', $val=''){
		if(empty($key)){
			ErrorLog::write('[redis error] rPush key empty');
			return 0;
		}

		return self::$objRedis->rPush($key, $val);
	}

	/**
	 * [リスト型]キーで指定した要素の先頭からpop。もとの配列からは除外される。
	 *
	 * @param string $key
	 * @return string
	 */
	public static function lPop($key=''){
		if(empty($key)){
			ErrorLog::write('[redis error] lPop key empty');
			return '';
		}

		return self::$objRedis->lPop($key);
	}

	/**
	 * [リスト型]キーで指定した要素の末尾からpop。もとの配列からは除外される。
	 *
	 * @param string $key
	 * @return string
	 */
	public static function rPop($key=''){
		if(empty($key)){
			ErrorLog::write('[redis error] rPop key empty');
			return '';
		}

		return self::$objRedis->rPop($key);
	}

	/**
	 * [リスト型]キーで指定した要素から1個だけ取得。
	 * 配列をインデックスでアクセスするみたいに第２引数に数値渡せばいい
	 *
	 * @param string $key
	 * @param int $index
	 * @return string
	 */
	public static function lGet($key='', $index=0){
		if(empty($key)){
			ErrorLog::write('[redis error] lGet key empty');
			return '';
		}

		return self::$objRedis->lGet($key, $index);
	}

	/**
	 * [リスト型]キーで指定した要素を範囲で取得。第2、第3引数にstart~endのインデックス番号を渡す。
	 * start: 0, end: -1にすれば全部取れる
	 *
	 * @param string $key
	 * @param int $start
	 * @param int $end
	 * @return array
	 */
	public static function lRange($key='', $start=0, $end=-1){
		if(empty($key)){
			ErrorLog::write('[redis error] lRange key empty');
			return array();
		}

		return self::$objRedis->lRange($key, $start, $end);
	}

	/**
	 * [リスト型]キーで指定した要素の配列サイズの取得
	 *
	 * @param string $key
	 * @return int
	 */
	public static function lSize($key=''){
		if(empty($key)){
			ErrorLog::write('[redis error] lSize key empty');
			return 0;
		}

		return self::$objRedis->lSize($key);
	}

	/**
	 * キーでの削除。一度に複数削除できて、配列で指定する。
	 * 削除できたkeyの数がreturnされる
	 *
	 * @param array $key_list
	 * @return int 削除できたkeyの数
	 */
	public static function delete($key_list=array()){
		if(empty($key_list)){
			ErrorLog::write('[redis error] delete key_list empty');
			return 0;
		}

		return self::$objRedis->delete($key_list);
	}

	/**
	 * 存在確認
	 *
	 * @param string $key
	 * @return int 0 or 1
	 */
	public static function exists($key=''){
		if(empty($key)){
			ErrorLog::write('[redis error] exists key empty');
			return 0;
		}

		return self::$objRedis->exists($key);
	}


	/**
	 * keyの文字列に該当する一覧を全部取得する。'*'がワイルドカード。返ってくるのはkeyだけ。
	 *
	 * @param string $key
	 * @return array key一覧
	 */
	public static function getKeyList($key='*'){
		return self::$objRedis->keys($key);
	}

	/**
	 * publish（送信）
	 */
	public static function publish($channel='', $message=''){
		if(empty($channel) || empty($message)){
			return false;
		}

		self::write_log($channel, '------------------------');

	 	$ret_pub = 0;
	 	$count = 0;

		for($count=0;$count<10;$count++){
			if($ret_pub==0){
				if($count!=0){
					self::write_log($channel, $channel.":  ".$message.": failed count: ".$count);
					self::write_log($channel, $channel.":  ".$message.": reconnect");
					self::reconnect(self::$readonly);
				}
				$ret_pub = self::$objRedis->publish($channel, $message);
				self::write_log($channel, $channel.":  ".$message.": publish return ".$ret_pub);

			}else{
				break;
			}
		}

		if($ret_pub!=0){
			self::write_log($channel, $channel.":  ".$message.": success!!");
		}

		return true;
	}

	/**
	 * ログ出力
	 */
	private static function write_log($channel='', $log_message=''){
		$log_file = TMP_DIR.'/log/cron/redis_publish_'.$channel.'.log';
		$date = "[".date('Y-m-d H:i:s')."] ";
		error_log($date.$log_message."\n",3,$log_file);
	}
}

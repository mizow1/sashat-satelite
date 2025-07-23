<?php

class BrightcoveApi{
	// アカウント情報
	const ACCOUNT_ID	= '5098879574001';
	const CLIENT_ID		= '4b315614-15ad-43a7-9d92-4fcba6b23734';
	const CLIENT_SECRET	= 'n18aUnsl322_GKYNLPDOapcg-cB1OddWrfHeHes_QU8jXow_QHTGTvPX6JG95IaE1QUS1GFigP7aPowIgApxqw';
	private $access_token = '';

	// APIのBASE URL
	const API_ENDPOINT_BASE_URL	= 'https://oauth.brightcove.com/v3';
	const API_CMS_BASE_URL		= 'https://cms.api.brightcove.com/v1/accounts/';
	const API_ANALYTICS_BASE_URL= 'https://analytics.api.brightcove.com/v1/data?accounts=';

	// 各APIのURL
	const API_CMS_GET_VIDEOS				= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/videos';	// 動画リスト Video - Get Videos
	const API_CMS_GET_VIDEO_COUNT			= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/counts/videos';	// 動画数 Video - Get Video Count
	const API_CMS_GET_VIDEO_REFERENCE		= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/videos/<video_id>';	// 個別動画取得 Video - Get Video by ID or Reference ID
	const API_CMS_GET_VIDEO_SOURCES			= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/videos/<video_id>/sources';	// 個別動画取得 Video - Get Video Sources
	const API_CMS_GET_VIDEO_IMAGES			= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/videos/<video_id>/images';	// 個別動画取得 Video - Get Video Images
	const API_CMS_GET_VIDEO_DIGITAL_MASTER	= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/videos/<video_id>/digital_master';	// 個別動画取得 Video - Get Digital Master Info
	const API_CMS_GET_VIDEO_PLAYLISTS		= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/videos/<video_id>/references';	// 個別動画取得 Video - Get Playlists for Video
	const API_CMS_GET_CUSTOM_FILEDS			= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/video_fields';	// Video - Get Custom Fields
	const API_CMS_GET_PLYALISTS				= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/playlists';	// プレイリスト Playlist - Get Playlists
	const API_CMS_GET_PLYALIST_COUNT		= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/counts/playlists';	// プレイリスト Playlist - Get Playlist Count
	const API_CMS_GET_PLYALIST_ID			= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/playlists/<playlist_id>';	// プレイリスト Playlist - Get Playlist by ID
	const API_CMS_GET_PLYALIST_VIDEO_COUNT	= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/counts/playlists/<playlist_id>/videos';	// プレイリスト Playlist - Get Video Count in Playlist
	const API_CMS_GET_PLYALIST_VIDEO_LIST	= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/playlists/<playlist_id>/videos';	// プレイリスト Playlist - Get Videos in Playlist
	const API_CMS_GET_ASSETS_RENDITION_LIST	= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/videos/<video_id>/assets/renditions';	// Assets - Get Rendition List
	const API_CMS_GET_ASSETS_RENDITION		= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/videos/<video_id>/assets/renditions/<asset_id>';	// Assets - Get Rendition
	const API_CMS_GET_ASSETS_THUMBNAIL_LIST	= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/videos/<video_id>/assets/thumbnail';	// Assets - Get Thumbnail List
	const API_CMS_GET_ASSETS_THUMBNAIL		= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/videos/<video_id>/assets/thumbnail/<asset_id>';	// Assets - Get Thumbnail
	const API_CMS_GET_ASSETS_CAPTION_LIST	= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/videos/<video_id>/assets/caption';	// Assets - Get Caption List
	const API_CMS_GET_ASSETS_CAPTION		= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/videos/<video_id>/assets/caption/<asset_id>';	// Assets - Get Caption
	const API_CMS_GET_FOLDERS				= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/folders';	// フォルダ Folders - Get Folders
	const API_CMS_GET_FOLDERS_VIDEOS		= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/folders/<folder_id>/videos';	// フォルダ Folders - Get Videos in Folder
	const API_CMS_GET_FOLDERS_INFORMATION	= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/folders/<folder_id>';	// フォルダ Folders - Get Folder Information
	const API_CMS_GET_NOTIFICATIONS			= self::API_CMS_BASE_URL.self::ACCOUNT_ID.'/subscriptions';	// 通知 Notifications - Get Subscriptions List
	const API_ANALYTICS_DATA				= self::API_ANALYTICS_BASE_URL.self::ACCOUNT_ID;	// Analytics API v1

	public function __construct(){
		$oauth_data = $this->oauthSend();
		$this->access_token = $oauth_data->access_token;
	}

	// API叩くラッパー
	private function getBcApiSend($resource,$method,$data=''){
		$options = array(
			'http'	=> array(
				'method'	=> $method,
				'header'	=> array(
					'Content-Type: application/x-www-form-urlencoded',
					'Authorization: '.sprintf( 'Bearer %s', $this->access_token ),
				),
				'content'	=> $data,
			),
		);
		$data = @file_get_contents($resource, false, stream_context_create($options));
		if($http_response_header[0]!='HTTP/1.1 200 OK'){
			// var_dump($http_response_header,$resource);
            if(!file_exists(BACKGROUND_DIR.'brightcove/api_fail.log')){
                touch(BACKGROUND_DIR.'brightcove/api_fail.log');
                sleep(60);
				$oauth_data = $this->oauthSend();
				$this->access_token = $oauth_data->access_token;
				$options = array(
					'http'	=> array(
						'method'	=> $method,
						'header'	=> array(
							'Content-Type: application/x-www-form-urlencoded',
							'Authorization: '.sprintf( 'Bearer %s', $this->access_token ),
						),
						'content'	=> $data,
					),
				);
				$data = @file_get_contents($resource, false, stream_context_create($options));
				if($http_response_header[0]=='HTTP/1.1 200 OK'){
					// var_dump($http_response_header,$resource);
					unlink(BACKGROUND_DIR.'brightcove/api_fail.log');
				}
			}
		}
		return $this->objToArray(json_decode($data));
	}

	// 動画リスト Video - Get Videos
	public function getVideos($param=array()){
		$method = 'GET';
		$url = self::API_CMS_GET_VIDEOS.'?1=1';
		$url .= (!empty($param['limit'])			? '&limit='.$param['limit']							: '');
		$url .= (!empty($param['offset'])			? '&offset='.$param['offset']						: '');
		$url .= (!empty($param['playable_only'])	? '&playable_only='.empty($param['playable_only'])	: '');
		$url .= (!empty($param['q'])				? '&q='.$param['q']									: '');
		$url .= (!empty($param['sort'])				? '&sort=-'.$param['sort']							: '');
		return $this->getBcApiSend($url, $method);
	}

	// 動画数 Video - Get Video Count
	public function getVideoCount(){
		$method = 'GET';
		$url = self::API_CMS_GET_VIDEO_COUNT;
		return $this->getBcApiSend($url, $method);
	}

	// 個別動画取得 Video - Get Video by ID or Reference ID
	public function getVideoReference($param){
		$method = 'GET';
		$url = str_replace('<video_id>',$param['video_id'],self::API_CMS_GET_VIDEO_REFERENCE);
		return $this->getBcApiSend($url, $method);
	}

	// 個別動画取得 Video - Get Video Sources
	public function getVideoSources($param){
		$method = 'GET';
		$url = str_replace('<video_id>',$param['video_id'],self::API_CMS_GET_VIDEO_SOURCES);
		return $this->getBcApiSend($url, $method);
	}

	// 個別動画取得 Video - Get Video Images
	public function getVideoImages($param){
		$method = 'GET';
		$url = str_replace('<video_id>',$param['video_id'],self::API_CMS_GET_VIDEO_IMAGES);
		return $this->getBcApiSend($url, $method);
	}

	// 個別動画取得 Video - Get Digital Master Info
	public function getVideoDigitalMaster($param){
		$method = 'GET';
		$url = str_replace('<video_id>',$param['video_id'],self::API_CMS_GET_VIDEO_DIGITAL_MASTER);
		return $this->getBcApiSend($url, $method);
	}

	// 個別動画取得 Video - Get Playlists for Video
	public function getVideoPlaylists($param){
		$method = 'GET';
		$url = str_replace('<video_id>',$param['video_id'],self::API_CMS_GET_VIDEO_PLAYLISTS);
		return $this->getBcApiSend($url, $method);
	}

	// Video - Get Custom Fields
	public function getCustomFileds(){
		$method = 'GET';
		$url = self::API_CMS_GET_CUSTOM_FILEDS;
		return $this->getBcApiSend($url, $method);
	}

	// プレイリスト Playlist - Get Playlists
	public function getPlaylists($param){
		$method = 'GET';
		$url = self::API_CMS_GET_PLYALISTS.'?1=1';
		$url .= (!empty($param['limit'])	? '&limit='.$param['limit']		: '');
		$url .= (!empty($param['offset'])	? '&offset='.$param['offset']	: '');
		$url .= (!empty($param['q'])		? '&q='.$param['q']				: '');
		$url .= (!empty($param['sort'])		? '&sort='.$param['sort']		: '');
		return $this->getBcApiSend($url, $method);
	}

	// プレイリスト Playlist - Get Playlist Count
	public function getPlaylistCount(){
		$method = 'GET';
		$url = self::API_CMS_GET_PLYALIST_COUNT;
		return $this->getBcApiSend($url, $method);
	}

	// プレイリスト Playlist - Get Playlist by ID
	public function getPlaylistId($param){
		$method = 'GET';
		$url = str_replace('<playlist_id>',$param['playlist_id'],self::API_CMS_GET_PLYALIST_ID);
		return $this->getBcApiSend($url, $method);
	}

	// プレイリスト Playlist - Get Video Count in Playlist
	public function getPlaylistVideoCount($param){
		$method = 'GET';
		$url = str_replace('<playlist_id>',$param['playlist_id'],self::API_CMS_GET_PLYALIST_VIDEO_COUNT);
		return $this->getBcApiSend($url, $method);
	}

	// プレイリスト Playlist - Get Videos in Playlist
	public function getPlaylistVideoList($param){
		$method = 'GET';
		$url = str_replace('<playlist_id>',$param['playlist_id'],self::API_CMS_GET_PLYALIST_VIDEO_LIST);
		return $this->getBcApiSend($url, $method);
	}

	// Assets - Get Rendition List
	public function getAssetsRenditionList($param){
		$method = 'GET';
		$url = str_replace('<playlist_id>',$param['playlist_id'],self::API_CMS_GET_ASSETS_RENDITION_LIST);
		$url = str_replace('<video_id>',$param['video_id'],$url);
		return $this->getBcApiSend($url, $method);
	}

	// Assets - Get Rendition
	public function getAssetsRendition($param){
		$method = 'GET';
		$url = str_replace('<asset_id>',$param['asset_id'],self::API_CMS_GET_ASSETS_RENDITION);
		$url = str_replace('<video_id>',$param['video_id'],$url);
		return $this->getBcApiSend($url, $method);
	}

	// Assets - Get Thumbnail List
	public function getAssetsThumbnailList($param){
		$method = 'GET';
		$url = str_replace('<video_id>',$param['video_id'],self::API_CMS_GET_ASSETS_THUMBNAIL_LIST);
		return $this->getBcApiSend($url, $method);
	}

	// Assets - Get Thumbnail
	public function getAssetsThumbnail($param){
		$method = 'GET';
		$url = str_replace('<video_id>',$param['video_id'],self::API_CMS_GET_ASSETS_THUMBNAIL);
		$url = str_replace('<asset_id>',$param['asset_id'],$url);
		return $this->getBcApiSend($url, $method);
	}

	// Assets - Get Caption List
	public function getAssetsCaptionList($param){
		$method = 'GET';
		$url = str_replace('<video_id>',$param['video_id'],self::API_CMS_GET_ASSETS_CAPTION_LIST);
		return $this->getBcApiSend($url, $method);
	}

	// Assets - Get Caption
	public function getAssetsCaption($param){
		$method = 'GET';
		$url = str_replace('<video_id>',$param['video_id'],self::API_CMS_GET_ASSETS_CAPTION);
		$url = str_replace('<asset_id>',$param['asset_id'],$url);
		return $this->getBcApiSend($url, $method);
	}

	// フォルダ Folders - Get Folders
	public function getFolders(){
		$method = 'GET';
		$url = self::API_CMS_GET_FOLDERS;
		return $this->getBcApiSend($url, $method);
	}

	// フォルダ Folders - Get Videos in Folder
	public function getFolderVideos($param){
		$method = 'GET';
		$url = str_replace('<folder_id>',$param['folder_id'],self::API_CMS_GET_FOLDERS_VIDEOS);
		return $this->getBcApiSend($url, $method);
	}

	// フォルダ Folders - Get Folder Information
	public function getFolderInformation($param){
		$method = 'GET';
		$url = str_replace('<folder_id>',$param['folder_id'],self::API_CMS_GET_FOLDERS_INFORMATION);
		return $this->getBcApiSend($url, $method);
	}

	// 通知 Notifications - Get Subscriptions List
	public function getNotifications(){
		$method = 'GET';
		$url = self::API_CMS_GET_NOTIFICATIONS;
		return $this->getBcApiSend($url, $method);
	}

	// Analytics API v1
	public function getAnalyticsData($param){
		$method = 'GET';
		$url = self::API_ANALYTICS_DATA;
		$url .= (!empty($param['dimensions'])	? '&dimensions='.$param['dimensions']	: '');
		$url .= (!empty($param['fields'])		? '&fields='.$param['fields']			: '');
		$url .= (!empty($param['sort'])			? '&sort='.$param['sort']				: '');
		$url .= (!empty($param['from'])			? '&from='.$param['from']				: '');
		$url .= (!empty($param['to'])			? '&to='.$param['to']					: '');
		return $this->getBcApiSend($url, $method);
	}

	// Oauth認証
	private function oauthSend(){
		$url	= self::API_ENDPOINT_BASE_URL.'/access_token?grant_type=client_credentials';
		$data	= '';
		$options= array(
			'http'	=> array(
			'method'  => 'POST',
				'header'	=> array(
					'Content-Type: application/json',
					'Authorization: '.sprintf( 'Basic %s', base64_encode(self::CLIENT_ID.':'.self::CLIENT_SECRET) ),
				),
				'content'	=> $data,
			),
		);
		return json_decode(file_get_contents($url, false, stream_context_create($options)));
	}

	// オブジェクトを配列に変換
	private function objToArray($arr_obj){
		if(is_object($arr_obj)){
			$arr_obj = (array)$arr_obj;
		}
		if(is_array($arr_obj)){
			foreach($arr_obj as $key => &$value){
				$value = $this->objToArray($value);
			}
		}
		return $arr_obj;
	}
}

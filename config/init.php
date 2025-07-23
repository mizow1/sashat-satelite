<?php
mb_language('Japanese');
date_default_timezone_set('Asia/Tokyo');

/****************************************
*	システムのバージョン情報など
****************************************/
define('COPYRIGHT','outward company');
define('SYSTEM_VER','1.0.0');
define('SYSTEM_NAME','[北海道 CMS]');
define('SERVER_ENV_NAME','PANDA_ENV');
define('SERVER_ENV_TEST','test');
define('SERVER_ENV_STAGE','stage');
define('SERVER_ENV_PRODUCTION','production');

/****************************************
*	文字コード設定
****************************************/
/*システム内部文字コード*/
define('ENCODE_SYSTEM','UTF-8');

/*HTMLテンプレート拡張子*/
define('HTML_TEMPLATE_EXTENSION','.html');

/*SQLテンプレート拡張子*/
define('SQL_TEMPLATE_EXTENSION','.sql');

/****************************************
*	開発者モード識別子
****************************************/
define('DEVELOP_MODE_KEY','ow_admin');

/****************************************
*	システムパス設定
****************************************/
define('SYSTEM_ROOT',dirname(dirname(__FILE__)).'/');
define('BACKGROUND_DIR',SYSTEM_ROOT.'background/');
define('BACKGROUND_CONVERT_DIR',BACKGROUND_DIR.'convert/');
define('CONFIG_DIR',SYSTEM_ROOT.'config/');
define('LIB_DIR',SYSTEM_ROOT.'libs/');
define('WEBAPP',SYSTEM_ROOT.'webapp/');
define('TMP_DIR',SYSTEM_ROOT.'tmp/');
define('GITIGNORE',SYSTEM_ROOT.'gitignore/');
define('LOG_DIR',TMP_DIR.'log/');
define('CONTROLLER',WEBAPP.'controller/');
define('FRONT_CONTROLLER',WEBAPP.'front_controller/');
define('MODEL',WEBAPP.'model/');
define('WIDGET',WEBAPP.'widget/');
define('ADMIN_VALIDATE_INI',MODEL.'administrator/validate/rule/');
define('VALIDATE_INI',MODEL.'front/validate/rule/');

define('EXPORT_DIR',GITIGNORE.'export/');


define('DOCUMENT_ROOT',SYSTEM_ROOT.'public_html/');
define('ADMIN_DOCUMENT_ROOT',SYSTEM_ROOT.'admin_public_html/');
define('IDSITE_DOCUMENT_ROOT',SYSTEM_ROOT.'id_public_html/');
define('PC_CSS_DIR_NAME','css/');
define('PC_CSS_DIR',DOCUMENT_ROOT.PC_CSS_DIR_NAME);
define('SP_CSS_DIR_NAME','sp/css/');
define('SP_CSS_DIR',DOCUMENT_ROOT.SP_CSS_DIR_NAME);
define('PC_JS_DIR_NAME','js/');
define('PC_JS_DIR',DOCUMENT_ROOT.PC_JS_DIR_NAME);
define('SP_JS_DIR_NAME','sp/js/');
define('SP_JS_DIR',DOCUMENT_ROOT.SP_JS_DIR_NAME);
define('PC_FONTS_DIR_NAME','fonts/');
define('PC_FONTS_DIR',DOCUMENT_ROOT.PC_FONTS_DIR_NAME);
define('SP_FONTS_DIR_NAME','sp/fonts/');
define('SP_FONTS_DIR',DOCUMENT_ROOT.SP_FONTS_DIR_NAME);

//山陽の config
define('WEBAPP_CONFIG',WEBAPP.'config/');

/****************************************
*	Smarty 設定
******************************************/
define('SMARTY_DIR',LIB_DIR.'Smarty/');
define('CACHE_TIME', '+0 minutes');
define('CACHE_TIME10', '+10 minutes');
define('CACHE_LIFETIME', '50');
define('SMARTY_LIFETIME',300); //compileファイルのライフサイクル

/*HTMLテンプレート*/
define('HTML_TEMPLATE_DIR',WEBAPP.'view/');
define('HTML_COMPILE_DIR',TMP_DIR.'compile/');
define('HTML_CACHE_DIR',TMP_DIR.'cache/');
define('HTML_CACHING_FLG',false);

/*SQLテンプレート*/
define('SQL_TEMPLATE_DIR',WEBAPP.'sql/');
define('SQL_COMPILE_DIR',TMP_DIR.'sql_compile/');
define('SQL_CACHE_DIR',TMP_DIR.'sql_cache/');
define('SQL_CACHING_FLG',false);

/****************************************
*	EFSマウント領域
******************************************/
define('MNT_EFS_DIR','/mnt/efs/');



/****************************************
*	他設定ファイル読み込み
****************************************/
require_once('constant.php');


<?php

if(empty($_GET['api'])){
	exit;
}
require_once(dirname(dirname(dirname(__FILE__))) . "/config/init.php");
require_once(WEBAPP.'lib/pg_sql.php');
require_once(WEBAPP.'lib/ViewSmarty.php');
require_once(WEBAPP.'lib/session.php');
require_once(WEBAPP.'lib/PgDb.php');


require_once(WEBAPP."action/admin/PreviewAction.php");
$actions = new PreviewAction();
$actions->Execute($_GET,[]);

?>

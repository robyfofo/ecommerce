<?php
/* ajax/processcaptcha.php v.3.0.0. 10/10/2016 */
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
define('PATH','../');
include_once(PATH."wscms/include/configuration.inc.php");
include_once(PATH."wscms/classes/class.Config.php");
include_once(PATH."wscms/classes/class.Core.php");
include_once(PATH."wscms/classes/class.Sessions.php");
include_once(PATH."wscms/classes/class.Sql.php");

$Config = new Config();
Config::setGlobalSettings($globalSettings);
$Core = new Core();

/* avvio sessione */
$my_session = new my_session(SESSIONS_TIME, SESSIONS_GC_TIME,SESSIONS_COOKIE_NAME);
$my_session->my_session_start();
$_MY_SESSION_VARS = array();
$_MY_SESSION_VARS = $my_session->my_session_read();

if ($_GET['captcha'] == $_MY_SESSION_VARS['site']['captcha_id']) {
	echo 'true';		
	} else {
		echo 'false';
	}
die();
?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
define('PATH','../');

include_once(PATH."include/configuration.inc.php");
include_once(PATH."classes/class.Config.php");
include_once(PATH."classes/class.Core.php");
include_once(PATH."classes/class.Sessions.php");
include_once(PATH."classes/class.Sql.php");
include_once(PATH."classes/class.ToolsStrings.php");
include_once(PATH."classes/class.SanitizeStrings.php");

Core::setDebugMode(1);

$Config = new Config();
Config::setGlobalSettings($globalSettings);
$Core = new Core();

/* avvio sessione */
$my_session = new my_session(SESSIONS_TIME, SESSIONS_GC_TIME,SESSIONS_COOKIE_NAME);
$my_session->my_session_start();
$_MY_SESSION_VARS = array();
$_MY_SESSION_VARS = $my_session->my_session_read();

/* variabili globali */
$App = new stdClass;
define('Config::$dbTablePrefix',Config::$dbTablePrefix());
echo Sql::countRecordQry(Config::$dbTablePrefix."users",'id','username = BINARY ?',array($_POST['username']))
?>
<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/ajax/renderuseravatarfromdb.php v.1.0.0. 06/07/2021
*/

error_reporting(E_ALL);
ini_set('display_errors', 0);
define('PATH','../');

include_once(PATH."admin/include/configuration.inc.php");
include_once(PATH."admin/classes/class.Config.php");
include_once(PATH."admin/classes/class.Core.php");
include_once(PATH."admin/classes/class.Sessions.php");
include_once(PATH."admin/classes/class.Sql.php");
include_once(PATH."admin/classes/class.SanitizeStrings.php");
include_once(PATH."admin/classes/class.Permissions.php");

Config::setGlobalSettings($globalSettings);
Config::init();
Core::init();

/* variabili globali */
$App = new stdClass;
$App->item = new stdClass;

/* avvio sessione */
$my_session = new my_session(SESSIONS_TIME, SESSIONS_GC_TIME,SESSIONS_COOKIE_NAME);
$my_session->my_session_start();
$_MY_SESSION_VARS = array();
$_MY_SESSION_VARS = $my_session->my_session_read();

$array_avatarInfo = '';
$avatarInfo = '';
$id = (isset($_GET['id']) ? intval($_GET['id']) : 0);
if ($id > 0) {	
	Sql::initQuery(Config::$dbTablePrefix.'users',array('*'),array($id),"id = ?");
	$App->item = Sql::getRecord();	
	if (Core::$resultOp->error == 0) {	
		$avatarInfo = $App->item->avatar;
		$array_avatarInfo = unserialize($App->item->avatar_info);
	}
}
//print_r($App->item);die();
//echo $avatarInfo; die();
//print_r($array_avatarInfo); die();

if ($avatarInfo != '') {
	$img = $avatarInfo;
	@header ("Content-type: ".$array_avatarInfo['type']);
	echo $img;
} else {
	$file = PATH.'templates/default/img/avatar.png';
	@header ("Content-type: image/png");
	@header('Content-Length: ' . filesize($file));
	echo file_get_contents($file);
}
die();
?>
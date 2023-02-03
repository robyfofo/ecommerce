<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/ajax/getComuneFromDbId.php v.1.0.0. 06/07/2021
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);
define('PATH','../admin/');

include_once(PATH."include/configuration.inc.php");
include_once(PATH."classes/class.Config.php");
include_once(PATH."classes/class.Core.php");
include_once(PATH."classes/class.Sessions.php");
include_once(PATH."classes/class.Sql.php");
include_once(PATH."classes/class.SanitizeStrings.php");
include_once(PATH."classes/class.Permissions.php");

Config::setGlobalSettings($globalSettings);
Config::init();
Core::init();

/* variabili globali */
$App = new stdClass;

/* avvio sessione */
$my_session = new my_session(SESSIONS_TIME, SESSIONS_GC_TIME,SESSIONS_COOKIE_NAME);
$my_session->my_session_start();
$_MY_SESSION_VARS = array();
$_MY_SESSION_VARS = $my_session->my_session_read();

$App->params = new stdClass;
$App->params->tables['comuni'] = Config::$dbTablePrefix.'location_comuni';

$comuni_id = (isset($_POST['comuni_id']) ? intval($_POST['comuni_id']) : 6242);

$data = array();
if ($comuni_id > 0) {
    $where = 'id = ?';
    $f = array('*');
    $fv = array($comuni_id);
    Sql::initQuery($App->params->tables['comuni'],$f,$fv,$where);
    $data = Sql::getRecord();
}

echo json_encode($data);
die();
?>
<?php
/**
 * Framework App PHP-Mysql
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * ajax/getComuneFromDbId.php v.1.0.0. 06/07/2021
*/

error_reporting(E_ALL);
ini_set('display_errors', 0);
define('PATH','../admin/');

include_once(PATH."include/configuration.inc.php");
include_once(PATH."classes/class.Config.php");
include_once(PATH."classes/class.Core.php");
include_once(PATH."classes/class.Sessions.php");
include_once(PATH."classes/class.Sql.php");
include_once(PATH."classes/class.SanitizeStrings.php");
include_once(PATH."classes/class.ToolsStrings.php");
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

$App->params->tables['nations'] = Config::$dbTablePrefix.'location_nations';
$App->params->tables['province'] = Config::$dbTablePrefix.'location_province';
$App->params->tables['comuni'] = Config::$dbTablePrefix.'location_comuni';

$comuniArray = array();
$comuniArray[] = array('nome'=>'Altro comune','id'=>0);

$q = (isset($_POST['q']) ? $_POST['q'] : '');
$province_id = (isset($_POST['province_id']) ? intval($_POST['province_id']) : '0');

$where = '';
$f[] = 'id,nome';
$fv = array();
$and = '';

if ($q != '') {
    $where .= $and.'nome LIKE ? AND active = 1';
    $fv[] = '%'.$q.'%';
    $and = ' AVD ';
}


$fv[] = $province_id;
$where .= $and.'location_province_id = ?';

/*
ToolsStrings::debug($fv);
echo $where;
*/

Sql::initQuery($App->params->tables['comuni'],$f,$fv,$where,'nome ASC');
$pdoObject = Sql::getPdoObjRecords();
while ($row = $pdoObject->fetch()) {
        $comuniArray[] = array('nome'=>$row->nome,'id'=>$row->id);
}		

//ToolsStrings::dump($data);die();
echo json_encode($comuniArray);
die();
?>
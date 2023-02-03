<?php
/* ajax/checkcustomerexistdb.php v.3.0.0. 10/10/2016 */
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
$dbtable =  Config::$dbTablePrefix().'ec_customers';
$res = 'false';
$username = (isset($_POST['username']) ? $_POST['username'] : '');
if ($username != '') {
	$obj = new stdClass;					
	Sql::initQuery($dbtable,array('id'),array($username),"username = ?");
	$obj = Sql::getRecord();			
	if (Core::$resultOp->error == 0 && Sql::getFoundRows() == 0) $res = 'true';
	}
echo $res;
die();
?>
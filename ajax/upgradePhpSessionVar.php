<?php
/* ajax/upgradePhpSessionVar.php v.1.0.0. 15/10/2021 */
session_start();

$keysession = '';
$keyarray = '';
$value = '';
$action = '';
$result = array('error' => 0,$message => '');

if ( isset($_REQUEST['keysession']) && $_REQUEST['keysession'] != '' ) $keysession = $_REQUEST['keysession'];
if ( isset($_REQUEST['keyarray']) && $_REQUEST['keyarray'] != '' ) $keyarray = $_REQUEST['keyarray'];
if ( isset($_REQUEST['value']) && $_REQUEST['value'] != '' ) $value = $_REQUEST['value'];
if ( isset($_REQUEST['action']) && $_REQUEST['action'] != '' ) $action = $_REQUEST['action'];



ToolsStrings::dump($_SESSION);
ToolsStrings::dump($_SESSION[$keysession][$keyarray]);


switch($action) {
	case 'addvalueinarray':
		if ($keysession != '' && $keyarray != '' && $value != '') {

			$foo = $_SESSION[$keysession];
			$foo[$keyarray] = $value;  
			


			$result = array('error' => 1, $message => 'Parametri passati errati!');
		}
	break;
	case 'removevaluefromarray':
		if ($keysession != '' && $keyarray != '' && $value != '') {
			$foo = $_SESSION[$key];
			$foo[$keyarray] = $value;  


			$_SESSION[$App->sessionName]['checklist'] = array_diff( $_SESSION[$App->sessionName]['checklist'], array('2') );
		} else {
			$result = array('error' => 1, $message => 'Parametri passati errati!');
		}
	break;

	default:
			$_SESSION[$key] = $value;
			
	break;
}
echo json_encode($result);
die();
?>
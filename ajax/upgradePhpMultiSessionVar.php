<?php
/* ajax/upgradePhpMultiSessionVar.php v.1.0.0. 15/10/2021 */
session_start();

$keysession = '';
$keylevel = '';
$keyarray = '';
$value = '';
$action = 'a';

$result = array('error' => 0,$message => '');

print_r("<pre style='font-size:10px'>".print_r($_REQUEST)."<?pre>");

if ( isset($_REQUEST['keysession']) && $_REQUEST['keysession'] != '' ) $keysession = $_REQUEST['keysession'];
if ( isset($_REQUEST['keylevel']) && $_REQUEST['keylevel'] != '' ) $keylevel = $_REQUEST['keylevel'];
if ( isset($_REQUEST['keyarray']) && $_REQUEST['keyarray'] != '' ) $keyarray = $_REQUEST['keyarray'];
if ( isset($_REQUEST['value']) && $_REQUEST['value'] != '' ) $value = $_REQUEST['value'];
if ( isset($_REQUEST['action']) && $_REQUEST['action'] != '' ) $action = $_REQUEST['action'];

//print_r("<pre style='font-size:10px'>".print_r($_SESSION[$keysession][$keylevel],true)."<?pre>");

switch($action) {
	case 'addvalueinarray':
		if ($keysession != '' && $keylevel != '' && $value != '') {
			$foo = $_SESSION[$keysession][$keylevel];
			if (!in_array($value,$foo)) {
				$foo[] = $value;  
				$_SESSION[$keysession][$keylevel] = $foo;
			}
		} else {
			$result = array('error' => 1, $message => 'Parametri passati errati!');
		}
	break;

	case 'removevalueinarray':
		if ($keysession != '' && $keylevel != '' && $value != '') {
			$foo = $_SESSION[$keysession][$keylevel];
			$foo = array_diff( $foo, array($value) );
			$_SESSION[$keysession][$keylevel] = $foo;
		} else {
			$result = array('error' => 1, $message => 'Parametri passati errati!');
		}
	break;

	default:
			$_SESSION[$key] = $value;
			
	break;
}
echo json_encode($result);

print_r("<pre style='font-size:10px'>".print_r($_SESSION[$keysession][$keylevel],true)."<?pre>");

die();
?>
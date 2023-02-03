<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 *	admin/contacts/configuration.php v.1.0.0. 29/06/2021 
*/

// carica la cofigurazione del modulo
$App->config = new stdClass();
Sql::initQuery(Config::$dbTablePrefix.'contacts_config',array('*'),array(),'');
$pdoObject = Sql::getPdoObjRecords();	
if (Core::$resultOp->error > 0) { die('errore lettura settings'); }
while ($row = $pdoObject->fetch()) {
	$App->contact_config[$row->keyword] = $row->value;
	if ( $row->comment != '') {
		$row->comment = utf8_encode($row->comment);
		$App->contact_help[$row->keyword] = json_decode($row->comment) or die('formato json errato');
	} else {
		$App->contact_help[$row->keyword] = array();
	}
}

//ToolsStrings::dump($App->contact_config);
//ToolsStrings::dump($App->contact_help);
//die();


switch (Core::$request->method) {
	case 'updateConf':
		if (!$_POST) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); 	}

		if (!isset($_POST['send_email_debug'])) $_POST['send_email_debug'] = 0;
		if (!isset($_POST['save_in_db'])) $_POST['save_in_db'] = 0;
		if (!isset($_POST['send_email_to_staff'])) $_POST['send_email_to_staff'] = 0;
		if (!isset($_POST['send_email_to_user'])) $_POST['send_email_to_user'] = 0;
		$changed = false;
		foreach($App->contact_config AS $key=>$value) {
			if ( isset($_POST[$key]) ) {
				if ($value !== $_POST[$key]) {
					Core::setDebugMode(1);
					Sql::initQuery(
						Config::$globalSettings['db table prefix'].'contacts_config',
						array('value'),
						array($_POST[$key],$key),
						'keyword = ?'
					);
					Sql::updateRecord();
					if (Core::$resultOp->error > 0) { die('errore update settings'); }	
					$changed = true;		

				}

			}

		} 
		
		if ($changed == true) $_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['configurazione'],Config::$localStrings['%ITEM% modificata'])).'!';
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listConf');
		$App->viewMethod = '';
	break;

	default:
		$App->pageSubTitle = preg_replace('/%ITEM%/', Config::$localStrings['configurazione'], Config::$localStrings['modifica %ITEM%']);
		$App->methodForm = 'updateConf';
		$App->viewMethod = '';
	break;
}

switch((string)$App->viewMethod) {
	default:
		$App->templateApp = 'formConfiguration.html';
		$App->jscript[] = '<script src="' . URL_SITE_ADMIN . $App->pathApplications . Core::$request->action . '/templates/' . $App->templateUser . '/js/formConfiguration.js"></script>';
	break;
}
?>
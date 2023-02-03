<?php
/**
* Framework Siti HTML-PHP-MySQL
* PHP Version 7
* @author Roberto Mantovani (<me@robertomantovani.vr.it>
* @copyright 2009 Roberto Mantovani
* @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
* core/nopassword.php v.1.0.0. 15/02/2021 
*/

Core::setDebugMode(1);

$App->pageTitle = $_LocalStrings['titolo sezione richiesta password'];
$App->pageSubTitle = $_LocalStrings['titolo sezione richiesta password'];
$App->pathApplications = 'application/core/';

$App->templateApp = Core::$request->action.'.html';
$App->action = '';
$App->item = new stdClass;
$App->id = intval(Core::$request->param);
if (isset($_POST['id'])) $App->id = intval($_POST['id']);

$App->templateBase = 'struttura-login.html';

$section = preg_replace('/%ITEM%/',$_LocalStrings['login'],$_LocalStrings['torna al %ITEM%']);
$section1 = '<a href="'.URL_SITE_ADMIN.'" title="'.ucfirst($section).'">'.ucfirst($_LocalStrings['login']).'</a>';
$App->returnlink = ucfirst(preg_replace('/%ITEM%/',$section1,$_LocalStrings['torna al %ITEM%']));

if (isset($_POST['submit'])) {	
	if ($_POST['username'] == "") {
		Core::$resultOp->error = 1;
		Core::$resultOp->message = preg_replace('/%ITEM%/',$_LocalStrings['nome utente'],$_LocalStrings['Devi inserire un %ITEM%!']);
	} else {
		$username = SanitizeStrings::stripMagic(strip_tags($_POST['username']));
	}
	if (Core::$resultOp->error == 0) {	
		/* legge username dalla email */
		/* (tabella,campi(array),valori campi(array),where clause, limit, order, option , pagination(default false)) */
		Sql::initQuery(Config::$dbTablePrefix.'users',array('id','username','email'),array($username),"username = ? AND active = 1");
		$App->item = Sql::getRecord();		
		if(Core::$resultOp->error == 0) {
			if (Sql::getFoundRows() > 0) {
				/* crea la nuova password */	
				$passw = ToolsStrings::setNewPassword('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890',8);
				//$passw = 'master'; // per test
				$criptPassw = password_hash($passw, PASSWORD_DEFAULT);			
				$titolo = $_LocalStrings['titolo email sezione richiesta password'];
				$titolo = preg_replace('/%SITENAME%/',SITE_NAME,$titolo);
				$testo = $_LocalStrings['testo email sezione richiesta password'];
				$testo = preg_replace('/%SITENAME%/',SITE_NAME,$testo);
				$testo = preg_replace('/%PASSWORD%/',$passw,$testo);
				$testo = preg_replace('/%CLEANPASSWORD%/',$passw,$testo);
				$testo = preg_replace('/%USERNAME%/',$App->item->username,$testo);
				$text_plain = Html2Text\Html2Text::convert($testo);
				//echo $titolo;	
				//echo $testo; 				
				/* aggiorno la password nel db */						
				/* (tabella,campi(array),valori campi(array),where clause, limit, order, option , pagination(default false)) */
				Sql::initQuery(Config::$dbTablePrefix.'users',array('password'),array($criptPassw,$App->item->id),"id = ?");
				Sql::updateRecord();
				if (Core::$resultOp->error > 0) die('errore db');
				if (Core::$resultOp->error == 0) {	
					$opt = array();
					$opt['fromEmail'] = $globalSettings['default email'];
					$opt['fromLabel'] = $globalSettings['default email label'];		
					$opt['sendDebug'] = $globalSettings['send email debug'];
					$opt['sendDebugEmail'] = $globalSettings['email debug'];								
					Mails::sendEmail($App->item->email,$titolo,$testo,$text_plain,$opt);
					//Core::$resultOp->error = 1; //per test
					if (Core::$resultOp->error == 0) {
						Core::$resultOp->message = $_LocalStrings['La nuova password vi è stata inviata con email indirizzo associato ed è stata memorizzata nel sistema!'];
					} else {
						Core::$resultOp->message = $_LocalStrings['Errore invio della email! Vi invitiamo a ripetere la procedura o contattare amministratore.']; 
					}
											
				} else { 
					Core::$resultOp->message = $_LocalStrings['Errore database! La nuova password NON è stata memorizzata nel sistema! Vi invitiamo a ripetere la procedura o contattare amministratore'];							
				}										
			}	else {	
				Core::$resultOp->error = 1;
				Core::$resultOp->message = $_LocalStrings['Il nome utente inserito non esiste! Vi invitiamo a ripetere la procedura o contattare amministratore del sistema.'];
			}
		}
	}	
}
?>
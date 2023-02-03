<?php
/**
 * Framework siti html-PHP-Mysql
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * pages/contacts.php v.1.0.0. 16/06/2021
*/
//Core::setDebugMode(1);

// carica la cofigurazione del modulo
$App->config = new stdClass();
Sql::initQuery(Config::$dbTablePrefix.'contacts_config',array('*'),array(),'');
$pdoObject = Sql::getPdoObjRecords();	
if (Core::$resultOp->error > 0) { die('errore lettura settings'); }
while ($row = $pdoObject->fetch()) {
	$App->contact_config[$row->keyword] = $row->value;
}
//ToolsStrings::dump($App->contact_config);

$pageId = Core::$request->page_id;
$pageAlias = Core::$request->page_alias;

$App->moduleName = 'contacts';
$App->moduleTitle = ucfirst(Config::$localStrings['contatti']);
$App->titles = Utilities::getTitlesPage($App->moduleTitle,$App->modulePageData,Config::$localStrings['user'],array());

$date = new DateTime();
$App->timeSubmit = $date->getTimestamp();

$App->moduleSent = 0;

switch (Core::$request->method) 
{ 
	case 'send':

		//ToolsStrings::dump($_POST);die();
		if (isset($_POST['namesubmit']) && $_POST['namesubmit'] != '')
		{
			$result = array(
				'error' => 1,
				'message' => $localStrings['Sei stato identificato come robot!']
			);
			echo json_encode($result);
			die();
		}
		
		$date = new DateTime();
		$timeSubmitServer = $date->getTimestamp();
		$timeSubmitForm = $timeSubmitServer;
		
		if (isset($_POST['timesubmit']) && $_POST['timesubmit'] != '')
		{
			$timeSubmitForm = $_POST['timesubmit'];
		}
		$timediff = $timeSubmitServer - $timeSubmitForm;
		if ( $timediff < 15)
		{
			$result = array(
				'error' => 1,
				'message' => 'Sei troppo veloce per essere un umano!'
			);
			echo json_encode($result);
			die();			
		}
		
		// controllo POST
		$fields = array(
			'message'						=> array(	
				'required'					=> true,
				'name'						=> 'message',
				'error message'             => preg_replace('/%ITEM%/',$localStrings['messaggio'],$localStrings['Devi inserire un %ITEM%!'])
			),
			'name'						=> array(	
				'required'					=> true,
				'name'						=> 'object',
				'error message'             => preg_replace('/%ITEM%/',$localStrings['nome'],$localStrings['Devi inserire un %ITEM%!'])
			),
			'object'						=> array(	
				'required'					=> true,
				'field'						=> 'object',
				'error message'             => preg_replace('/%ITEM%/',$localStrings['oggetto'],$localStrings['Devi inserire un %ITEM%!'])
			),
			'telephone'						=> array(	
				'required'					=> true,
				'field'						=> 'telephone',
				'error message'             => preg_replace('/%ITEM%/',$localStrings['numero di telefono'],$localStrings['Devi inserire un %ITEM%!']),
				'validate'					=> 'istelephonenumber',
			),
			'email'						=> array(	
				'required'					=> true,
				'field'						=> 'email',
				'error message'             => preg_replace('/%ITEM%/',$localStrings['indirizzo email valido'],$localStrings['Devi inserire un %ITEM%!']),
				'validate'					=> 'isemail',
			),
			'privacy'						=> array(	
				'required'					=> true,
				'field'						=> 'privacy',
				'error message'             => $localStrings['Devi autorizzare il trattamento della privacy!'],
				'validate'					=> 'issameintvalue',
				'valuerif'					=> 1
			),
		);
		Form::parsePostByFields($fields,$localStrings,array('stripmagicfields'=>false));
		//ToolsStrings::dump($_POST);die();
		if (Core::$resultOp->error > 0) {
			//ToolsStrings::dump(Core::$resultOp);
			$result = array(
				'error' => 1,
				'message' => implode('<br>',Core::$resultOp->messages)
			);
			echo json_encode($result);
			die();
		}


		// salva il messaggio
		if ($App->contact_config['save_in_db'] == 1)
		{
			//Sql::setDebugMode(1);
			$domain =  $_SERVER['REMOTE_ADDR'];
			$t = Config::$dbTablePrefix.'contacts';
			$f = array('name','object','message','telephone','email','created','ip_address','is_span');
			$fv = array($_POST['name'],	$_POST['object'], $_POST['message'], $_POST['telephone'],$_POST['email'],$App->nowDateTime,$domain,0);
			$w = '';
			$wa = '';
			Sql::initQuery($t,$f,$fv,$w,'','');
			Sql::insertRecord();
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); die(); }
		}

		// manda la email con il messaggio del modulo allo staff del sito
		if ($App->contact_config['send_email_to_staff'] == 1)
		{
			$opt = array();	
			$subject = $App->contact_config['admin_email_subject'];					
			$content = $App->contact_config['admin_email_content'];	
			$subject = Mails::parseEmailContent($subject,$_POST,$optt=array());
			$content = Mails::parseEmailContent($content,$_POST,$optt=array());	
			//echo $subject;
			//echo $content;	
			$content_plain = Html2Text\Html2Text::convert($content);				
			$opt['fromEmail'] = $_POST['email'];
			$opt['fromLabel'] = $_POST['email'];
			$address = $App->contact_config['email_address'];				
			$opt['sendDebug'] = $App->contact_config['send_email_debug'];
			$opt['sendDebugEmail'] = $App->contact_config['email_debug'];
			Mails::sendEmail($address,$subject,$content,$content_plain,$opt);
			if (Core::$resultOp->error > 0) {
				$result = array(
					'error' => 1,
					'message' => $localStrings['Il tuo messaggio NON è stato spedito! Riprova.']
				);
				echo json_encode($result);
				die();
			}
		}
		
		
		// manda la email con il messaggio del modulo allo utente 
		if ($App->contact_config['send_email_to_user'] == 1)
		{
			$opt = array();	
			$subject = Multilanguage::getLocaleArrayValue($App->contact_config,'user_email_subject_',$localStrings['user'],array());			
			$content = Multilanguage::getLocaleArrayValue($App->contact_config,'user_email_content_',$localStrings['user'],array());				
			$subject = Mails::parseEmailContent($subject,$_POST,$optt=array());
			$content = Mails::parseEmailContent($content,$_POST,$optt=array());	
			$content_plain = Html2Text\Html2Text::convert($content);	
			/*
			echo '<br>a'.$subject;
			echo '<br>b'.$content;	
			echo '<br>c'.$content_plain;
			die();
			*/	
			$opt['fromEmail'] =  $App->contact_config['email_address'];
			$opt['fromLabel'] =  $App->contact_config['label_email_address'];
			$address = $_POST['email'];				
			$opt['sendDebug'] = $App->contact_config['send_email_debug'];
			$opt['sendDebugEmail'] = $App->contact_config['email_debug'];
			Mails::sendEmail($address,$subject,$content,$content_plain,$opt);

			
			if (Config::$resultOp->error > 0) {
				$result = array(
					'error' => 1,
					'message' => $localStrings['Il tuo messaggio di conferma NON è stato spedito! Riprova.']
				);
				echo json_encode($result);
				die();			
			}
		}

		$result = array(
			'error' => 0,
			'message' => $localStrings['Il tuo messaggio è stato spedito! Riceverai un messaggio di conferma.']
		);
		echo json_encode($result);
		die();
		
	break;
	
		
	default:
		// campi form passati da home
		$App->passform_name = (isset($_POST['name2']) && $_POST['name2'] != '' ? $_POST['name2'] : '');
		$App->passform_email = (isset($_POST['email2']) && $_POST['email2'] != '' ? $_POST['email2'] : '');
		$App->passform_message = (isset($_POST['message2']) && $_POST['message2'] != '' ? $_POST['message2'] : '');
		
  		$App->text_intro = Multilanguage::getLocaleArrayValue($App->contact_config,'text_intro_',Config::$localStrings['user'],array());
  		$App->page_content = Multilanguage::getLocaleArrayValue($App->contact_config,'page_content_',Config::$localStrings['user'],array());
		$App->urlPrivacyPage = $App->contact_config['url_privacy_page'] ;
		$App->urlPrivacyPage = ToolsStrings::parseHtmlContent($App->urlPrivacyPage,array());		
		$contentString = '<div id="content-map">'.addslashes(Config::$globalSettings['azienda referente']).'</h3><p>'.addslashes(Config::$globalSettings['azienda indirizzo'].'<br>'.Config::$globalSettings['azienda cap']).' '.Config::$globalSettings['azienda comune'].'</p></div>';
	
		$App->titlepage = $App->titles['title'];

		$breadcrumbsTitle = $App->titles['title'];
		$App->breadcrumbs->items[] = array('class'=>'active','title'=>$App->titles['title']);
	
		$metaTitlePage = $App->titles['titleMeta'];
		$metaDescriptionPage = '';
		$metaKeywordsPage = '';
		$App->view = '';
		
	break;

}		

// gestione titolo pagina
$App->titlepage = $metaTitlePage;
// gestione breadcrumbs
$App->breadcrumbs->title = $breadcrumbsTitle;
// SEO
$App->metaTitlePage = $globalSettings['meta tags page']['title ini'].$metaTitlePage.$globalSettings['meta tags page']['title separator'].$globalSettings['meta tags page']['title end'];
$App->metaDescriptionPage .= $metaDescriptionPage;
$App->metaKeywordsPage .= $metaKeywordsPage;

switch ($App->view) 
{
	default:	
		$App->pluginJscript[] = '<script src="'.URL_SITE.'templates/'.$App->templateUser.'/assets/vendor/gmaps/gmaps.min.js"></script>';
		$App->pluginComponentJscript[] = '<script src="'.URL_SITE.'templates/'.$App->templateUser.'/assets/js/components/gmap/hs.map.js"></script>';
		$App->jscript[] = '<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyAtt1z99GtrHZt_IcnK-wryNsQ30A112J0&amp;callback=initMap" async="" defer=""></script>';
	break;
}

?>
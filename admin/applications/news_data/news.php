<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/news/news.php v.1.0.0. 25/03/2021
*/

if (!isset($_MY_SESSION_VARS[$App->sessionName]['page'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleVars($_MY_SESSION_VARS,$App->sessionName,array('page'=>1,'ifp'=>'10','srcTab'=>''));
if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);

switch(Core::$request->method) {	
	case 'activeItem':
	case 'disactiveItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		Sql::manageFieldActive(substr(Core::$request->method,0,-4),$App->params->tables['item'],$App->id,array('label'=>Config::$localStrings['voce'],'attivata'=>Config::$localStrings['attivata'],'disattivata'=>Config::$localStrings['disattivata']));
		$_SESSION['message'] = '0|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');	
	break;
	
	case 'deleteItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		Sql::initQuery($App->params->tables['item'],array('id'),array($App->id),'id = ?');
		Sql::deleteRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['%ITEM% cancellata'])).'!';	
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');		
	break;
	
	case 'insertItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if (!$_POST) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
			
		// set seo tag default
		foreach ($globalSettings['languages'] AS $lang){
			$_POST['title_seo_'.$lang] = SanitizeStrings::cleanTitleUrl($_POST['title_'.$lang]);
			$_POST['meta_title_'.$lang] = SanitizeStrings::cleanTitleUrl($_POST['title_'.$lang]);
		}	
	
		// preleva il filename dal form	  
		ToolsUpload::setFilenameFormat($globalSettings['image type available']);
		ToolsUpload::getFilenameFromForm();
		$_POST['filename'] = ToolsUpload::getFilenameMd5();
		$_POST['org_filename'] = ToolsUpload::getOrgFilename();
		if (Core::$resultOp->error > 0) {ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');}

		//ToolsStrings::dump($_POST);//die();
		
		// parsa i post in base ai campi
		Form::parsePostByFields($App->params->fields['item'],Config::$localStrings,array());
		if (Core::$resultOp->error > 0) { 
			echo $_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newItem');
		}

		//ToolsStrings::dump($_POST);die();
		
		// se scadenza controlla le date
		if ($_POST['scadenza'] == 1) {
			if ( false == DateFormat::checkDateTimeIsoIniEndInterval($_POST['datatimescaini'],$_POST['datatimescaend'])) {
				$_SESSION['message'] = '1|'.Config::$localStrings['La data di scadenza non può essere prima della data di inserimento!'];
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newItem');
			}		
		}
		
		//Sql::insertRawlyPost($App->params->fields['item'],$App->params->tables['item']);
		if (Core::$resultOp->error > 0) {ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');}
		
		// sposto il file
		if ($_POST['filename'] != '') {
			move_uploaded_file(ToolsUpload::getTempFilename(),$App->params->uploadPaths['item'].$_POST['filename']) or die('Errore caricamento file');
		}
		
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['%ITEM% inserita']));
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');	
	break;
	
	case 'updateItem':
		//Sql::setDebugMode(1);
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if (!$_POST) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); 	}
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		
		// requpero i vecchi dati
		$App->oldItem = new stdClass;
		Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
		$App->oldItem = Sql::getRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); die(); }	
				
		// preleva il filename dal form
		ToolsUpload::setFilenameFormat($globalSettings['image type available']);	
		ToolsUpload::getFilenameFromForm();	   			   	
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
		}
					
		$_POST['filename'] = ToolsUpload::getFilenameMd5();
		$_POST['org_filename'] = ToolsUpload::getOrgFilename(); 		   		   	
		$uploadFilename = $_POST['filename'];
		// imposta il nomefile precedente se non si è caricata un file (serve per far passare il controllo campo file presente)
		if($_POST['filename'] == '' && $App->oldItem->filename != '') $_POST['filename'] = $App->oldItem->filename;
		if($_POST['org_filename'] == '' && $App->oldItem->org_filename != '') $_POST['org_filename'] = $App->oldItem->org_filename;
		
		if (isset($_POST['deleteFilename']) && $_POST['deleteFilename'] == 1) {
			if (file_exists($App->params->uploadPaths['item'].$App->oldItem->filename)) {			
				@unlink($App->params->uploadPaths['item'].$App->oldItem->filename);	
			}	
			$_POST['filename'] = '';
			$_POST['org_filename'] = ''; 	
		}
		
		
		//ToolsStrings::dump($_POST);
		Form::parsePostByFields($App->params->fields['item'],Config::$localStrings,array());
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
		}
		//ToolsStrings::dump($_POST);
		//ToolsStrings::dump(Core::$resultOp);
		//die();

		// se scadenza controlla le date
		if ($_POST['scadenza'] == 1) {
			if ( false == DateFormat::checkDateTimeIsoIniEndInterval($_POST['datatimescaini'],$_POST['datatimescaend'])) {
				echo $_SESSION['message'] = '1|'.Config::$localStrings['La data di scadenza non può essere prima della data di inserimento!'];
				//ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
			}		
		}

		Sql::updateRawlyPost($App->params->fields['item'],$App->params->tables['item'],'id',$App->id);
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); die(); }

		//die();
					
		if ($uploadFilename != '') {
			move_uploaded_file(ToolsUpload::getTempFilename(),$App->params->uploadPaths['item'].$uploadFilename) or die('Errore caricamento file');   			
			// cancella l'immagine vecchia 
			if (isset($App->oldItem->filename) && file_exists($App->params->uploadPaths['item'].$App->oldItem->filename)) {			
				@unlink($App->params->uploadPaths['item'].$App->oldItem->filename);			
			}	   			
		}
		
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['%ITEM% modificata']));
		if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
		}						
	break;
	
	case 'newItem':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }			
		$App->item = new stdClass;	
		
		$App->defaultDatatimeins = DateFormat::dateFormating(Config::$nowDateTime,Config::$localStrings['datepicker data format']);
		$App->defaultDatatimescaini = DateFormat::dateFormating(Config::$nowDateTime,Config::$localStrings['datepicker data format']); 
		$App->defaultDatatimescaend = DateFormat::dateFormating(Config::$nowDateTime,Config::$localStrings['datepicker data format']);

		$App->item->filenameRequired = false;
		if (Core::$resultOp->error > 0) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['item']);
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['inserisci %ITEM%']);
		$App->viewMethod = 'form';
		$App->methodForm = 'insertItem';
	break;
	
	case 'modifyItem':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }				
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['modifica %ITEM%'],Config::$localStrings['modulo']);
		$App->viewMethod = 'formMod';
		$App->item = new stdClass;
		Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (Core::$resultOp->error > 0) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['item']);
		if (!isset($App->item->datatimeins)) $App->item->datatimeins = $App->nowDateTime;
		if (isset($App->item->id_cat)) $App->id_cat = $App->item->id_cat;
		$App->item->filenameRequired = (isset($App->item->filename) && $App->item->filename != '' ? false : false);

		$App->defaultDatatimeins = DateFormat::dateFormating($App->item->datatimeins,Config::$localStrings['datepicker data format']);
		$App->defaultDatatimescaini = DateFormat::dateFormating($App->item->datatimescaini,Config::$localStrings['datepicker data format']); 
		$App->defaultDatatimescaend = DateFormat::dateFormating($App->item->datatimescaend,Config::$localStrings['datepicker data format']);

		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['modifica %ITEM%']);

		$App->viewMethod = 'form';
		$App->methodForm = 'updateItem';	
	break;

	case 'modifySeoItem':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		$App->item = new stdClass;
		Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (!isset($App->item->id) || (isset($App->item->id) && $App->item->id < 1)) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }			
		$App->pageSubTitle = Config::$localStrings['modifica'].' '.Config::$localStrings['Tag SEO'].' '.Config::$localStrings['voce'];
		$App->methodForm = 'updateSeoItem';
		$App->viewMethod = 'formSeo';
	break;

	case 'updateSeoItem':
		Config::$debugMode = 1;

		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		if (!$_POST) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		$fields = array();
		$fieldsVal = array();
		foreach(Config::$globalSettings['languages'] AS $lang) {
			$fields[] = 'meta_title_'.$lang;	
			$fieldsVal[] = (isset($_POST['meta_title_'.$lang]) ? $_POST['meta_title_'.$lang] : '');									
			$fields[] = 'meta_description_'.$lang;
			$fieldsVal[] = (isset($_POST['meta_description_'.$lang]) ? $_POST['meta_description_'.$lang] : '');					
			$fields[] = 'meta_keyword_'.$lang;
			$fieldsVal[] = (isset($_POST['meta_keyword_'.$lang]) ? $_POST['meta_keyword_'.$lang] : '');					
			$fields[] = 'title_seo_'.$lang;
			$fieldsVal[] = (isset($_POST['title_seo_'.$lang]) ? $_POST['title_seo_'.$lang] : '');
		}					
		$fieldsVal[] = $App->id;

		Form::parsePostByFieldsCustom($App->params->fields['item'],$fields,$fieldsVal,array());
		if (Core::$resultOp->error > 0) { 
			echo $_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifySeoItem/'.$App->id);
		}


		//ToolsStrings::dump($fields);
		//ToolsStrings::dump($fieldsVal);

		Sql::initQuery($App->params->tables['item'],$fields,$fieldsVal,'id = ?','');
		Sql::updateRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
		
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['Tag SEO'],Config::$localStrings['%ITEM% modificati'])).'!';
		if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifySeoItem/'.$App->id);
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
		}	
		die();
	break;

	case 'pageItem':
		$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,Core::$request->action,'page',$App->id);
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;
					
	case 'listItem':
	default:
		//Core::setDebugMode(1);
		$App->items = new stdClass;						
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 5);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);

		$queryVars = Applications::resetDataTableArrayVars();
		$queryVars['table'] = $App->params->tables['item']." AS ite";
		$queryVars['fields'][] = 'ite.*';
		$queryVars['fieldsValue'] = array();
		
		if (isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != '') {
			list($sessClause,$qryFieldsValuesClause) = Sql::getClauseVarsFromAppSession($_MY_SESSION_VARS[$App->sessionName]['srcTab'],$App->params->fields['item'],'');
		}	
		if (isset($sessClause) && $sessClause != '') $queryVars['where'] .= $queryVars['and'].'('.$sessClause.')';
		if (isset($qryFieldsValuesClause) && is_array($qryFieldsValuesClause) && count($qryFieldsValuesClause) > 0) {
			$queryVars['fieldsValue'] = array_merge($queryVars['fieldsValue'],$qryFieldsValuesClause);	
		}

		Sql::initQuery($queryVars['table'],$queryVars['fields'],$queryVars['fieldsValue'],$queryVars['where']);
		Sql::setItemsForPage($App->itemsForPage);	
		Sql::setPage($App->page);		
		Sql::setResultPaged(true);
		Sql::setOrder('datatimeins '.$App->params->ordersType['item']);
		if (Core::$resultOp->error <> 1) $App->items = Sql::getRecords();
	
		$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$App->itemsForPage);
		$App->paginationTitle = Config::$localStrings['mostra da %START% a %END% di %ITEM% elementi'];
		$App->paginationTitle = preg_replace('/%START%/',$App->pagination->firstPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/',$App->pagination->lastPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/',$App->pagination->itemsTotal,$App->paginationTitle);		
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['voci'],Config::$localStrings['lista %ITEM%']);		
		$App->viewMethod = 'list';	
	break;
}

switch((string)$App->viewMethod) {
	
	case 'formSeo':
		$App->templateApp = 'formSeoNew.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formSeoNew.js"></script>';
	break;

	case 'form':	
		$App->templateApp = 'formNew.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications. Core::$request->action.'/templates/'.$App->templateUser.'/js/formNew.js"></script>';
	break;
	
	default:
	case 'list':
		$App->templateApp = 'listNews.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications. Core::$request->action.'/templates/'.$App->templateUser.'/js/listNews.js"></script>';
	break;
}	
?>
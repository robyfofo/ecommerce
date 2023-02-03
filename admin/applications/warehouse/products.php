<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/warehouse/products.php v.1.0.0. 30/03/2021
*/

if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);

if (Core::$request->method == 'listProd' && $App->id > 0) $_SESSION[$App->sessionName]['categories_id'] = $App->id;
if (isset($_POST['categories_id']) && isset($_SESSION[$App->sessionName]['categories_id']) && $_SESSION[$App->sessionName]['categories_id'] != $_POST['categories_id']) $_SESSION[$App->sessionName]['categories_id'] = $_POST['categories_id'];



//echo 'App->categories_id: '.$App->categories_id;

// sessione per associati
$_SESSION['associatedModule'] = array(
	'returnMethod'				=> 'listProd',
	'module'					=> 'warehouse',
	'rifParams'					=> 'prod',
	'rifTitleItem'				=> 'title',
	'rifImageDefault'			=> UPLOAD_DIR.'warehouse/default/product.jpg'
);

// select per parent
$App->categories = new stdClass();
Subcategories::$optLevelString = '-->';	
$App->categories = Subcategories::getObjFromSubCategories();

if (!is_array($App->categories) || (is_array($App->categories) && count($App->categories) == 0)) {
	$_SESSION['message'] = '2|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['categoria'],Config::$localStrings['Devi creare o attivare almeno una %ITEM%!']));
	ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listScat');
}

// GESTIONE TAG
$App->tags = new stdClass;	
Sql::initQuery($App->params->tables['tags'],array('*'),array(),'');
Sql::setOptions(array('fieldTokeyObj'=>'id'));
Sql::setOrder('ordering ASC');
$obj = Sql::getRecords();
if (Core::$resultOp->error > 0) {echo Core::$resultOp->message; die;}
// sistemo i dati
$arr = array();
if (is_array($obj) && is_array($obj) && count($obj) > 0) {
	foreach ($obj AS $key=>$value) {	
		$field = 'title_'.Config::$localStrings['user'];	
		$value->title = $value->$field;
		$arr[$key] = $value;
	}
}
$App->tags = $arr;

switch(Core::$request->method) {
	case 'moreOrderingProd':
		if ($App->id > 0) {
			Utilities::increaseFieldOrdering($App->id,Config::$localStrings,array('table'=>$App->params->tables['prod'],'orderingType'=>$App->params->ordersType['prod'],'parent'=>1,'parentField'=>'categories_id','label'=>ucfirst(Config::$localStrings['prodotto']).' '.Config::$localStrings['spostato']));
			Core::$resultOp->error = Core::$resultOp->type;
			$_SESSION['message'] = Core::$resultOp->error.'|'.Core::$resultOp->message;
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listProd');
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;
	case 'lessOrderingProd':
		if ($App->id > 0) {
			Utilities::decreaseFieldOrdering($App->id,Config::$localStrings,array('table'=>$App->params->tables['prod'],'orderingType'=>$App->params->ordersType['prod'],'parent'=>1,'parentField'=>'categories_id','label'=>ucfirst(Config::$localStrings['prodotto']).' '.Config::$localStrings['spostato']));
			Core::$resultOp->error = Core::$resultOp->type;
			$_SESSION['message'] = Core::$resultOp->error.'|'.Core::$resultOp->message;
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listProd');
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;

	case 'activeProd':
	case 'disactiveProd':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id == 0) {	ToolsStrings::redirect(URL_SITE.'error/404'); }	
		Sql::manageFieldActive(substr(Core::$request->method,0,-4),$App->params->tables['prod'],$App->id,array('label'=>Config::$localStrings['prodotto'],'attivata'=>Config::$localStrings['attivato'],'disattivata'=>Config::$localStrings['disattivato']));
		$_SESSION['message'] = '0|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listProd');
	break;
	
	case 'deleteProd':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id == 0) {	ToolsStrings::redirect(URL_SITE.'error/404'); }				
		// prendo i vecchi dati
		$App->itemOld = new stdClass;
		Sql::initQuery($App->params->tables['prod'],array('filename'),array($App->id),'id = ?');
		$App->itemOld = Sql::getRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
		
		// cancello attributi
		Sql::initQuery($App->params->tables['proa'],array(),array($App->id),'products_id = ?');
		Sql::deleteRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
		
		// cancello il record
		Sql::initQuery($App->params->tables['prod'],array(),array($App->id),'id = ?');
		Sql::deleteRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }

		// cancello il file associato
		if (isset($App->itemOld->filename) && file_exists($App->params->uploadPaths['prod'].$App->itemOld->filename)) {
			@unlink($App->params->uploadPaths['prod'].$App->itemOld->filename);			
		}	
		
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['prodotto'],Config::$localStrings['%ITEM% cancellato'])).'!';	
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listProd');
	break;
	
	case 'newProd':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }		
		$App->item = new stdClass;		
		$App->item->active = 1;
		$App->item->tax = 20.00;
		$App->itemTags = array();
		$App->item->ordering = 0;
		$App->item->is_new =0;
		$App->item->is_promo = 20.00;
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['prodotto'],Config::$localStrings['inserisci %ITEM%']);
		$App->methodForm = 'insertProd';
		$App->viewMethod = 'form';
	break;
			
	case 'insertProd':
		if (!$_POST) { ToolsStrings::redirect(URL_SITE.'error/404'); }

		//ToolsStrings::dump($_POST);

		// gestione automatica dell'ordering de in input = 0
		if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && $_POST['ordering'] == 0)) $_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['prod'],'ordering','categories_id = '.intval($_SESSION[$App->sessionName]['categories_id'])) + 1;
		
		// imposta alias
		if ($_POST['alias'] == '') $_POST['alias'] = $Module->getAlias($App->id,$_POST['alias'],$_POST['title_'.Config::$localStrings['user']]);
		
		// set seo tag default

		//ToolsStrings::dump($globalSettings);
		foreach ($globalSettings['languages'] AS $lang){
			$_POST['title_seo_'.$lang] = SanitizeStrings::cleanTitleUrl($_POST['title_'.$lang]);
			$_POST['meta_title_'.$lang] = SanitizeStrings::cleanTitleUrl($_POST['title_'.$lang]);
		}	
		
		// tagsId 		
		if (isset($_POST['id_tags']) && is_array($_POST['id_tags'])) {
			$_POST['id_tags'] = implode(',',$_POST['id_tags']);
		} else {
			$_POST['id_tags'] = '';
		}

		// preleva il filename dal form  
		ToolsUpload::setFilenameFormat($globalSettings['image type available']);
		ToolsUpload::getFilenameFromForm();
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newProd');
		}
		$_POST['filename'] = ToolsUpload::getFilenameMd5();
		$_POST['org_filename'] = ToolsUpload::getOrgFilename();

		//ToolsStrings::dump($_POST);
		
		// parsa i post in base ai campi
		Form::parsePostByFields($App->params->fields['prod'],Config::$localStrings,array());
		if (Core::$resultOp->error > 0) { 
			echo $_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			//ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newProd');
		}

		//ToolsStrings::dump($_POST);

		//die();

		Sql::insertRawlyPost($App->params->fields['prod'],$App->params->tables['prod']);
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
		
		$App->id = Sql::getLastInsertedIdVar(); /* preleva l'id della pagina */	 
		// sposto il file
		if ($_POST['filename'] != '') {
			move_uploaded_file(ToolsUpload::getTempFilename(),$App->params->uploadPaths['prod'].$_POST['filename']) or die('Errore caricamento file');
		}	
		
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['prodotto'],Config::$localStrings['%ITEM% inserito'])).'!';
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listProd');
	break;

	case 'modifyProd':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		//Core::setDebugMode(1);
		$App->item = new stdClass;
		Sql::initQuery($App->params->tables['prod'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (!isset($App->item->id) || (isset($App->item->id) && $App->item->id < 1)) { die('errore lettura prodotto'); ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }	
		$App->categories_id = $App->item->categories_id;
		
		$App->itemTags = array();
		if ($App->item->id_tags != '') $App->itemTags = explode(',',$App->item->id_tags);	
					
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['prodotto'],Config::$localStrings['modifica %ITEM%']);
		$App->methodForm = 'updateProd';
		$App->viewMethod = 'form';
	break;
	
	case 'updateProd':
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE.'error/404'); }	
		if (!$_POST) { ToolsStrings::redirect(URL_SITE.'error/404'); }
			
		// preleva dati vecchio
		Sql::initQuery($App->params->tables['prod'],array('*'),array($App->id),'id = ?');
		$App->itemOld = Sql::getRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
		
		// gestione automatica dell'ordering de in input = 0
		if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && $_POST['ordering'] == 0)) $_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['prod'],'ordering','categories_id = '.intval($App->categories_id)) + 1;
		// se cambia categoria aggiorna l'ordering
		if ($_POST['categories_id'] != $App->itemOld->parent) {
			$_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['prod'],'ordering','categories_id = '.intval($_POST['categories_id'])) + 1;  
		} 	
	
		// imposta alias
		if ($_POST['alias'] == '') $_POST['alias'] = $Module->getAlias($App->id,$_POST['alias'],$_POST['title_'.Config::$localStrings['user']]);	

		// tagsId 			
		if (isset($_POST['id_tags']) && is_array($_POST['id_tags'])) {
			$_POST['id_tags'] = implode(',',$_POST['id_tags']);
		} else {
			$_POST['id_tags'] = '';
		}	
			
		//preleva il filename dal form
		ToolsUpload::setFilenameFormat($globalSettings['image type available']);	
		ToolsUpload::getFilenameFromForm();
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyProd/'.$App->id);
		}
		$_POST['filename'] = ToolsUpload::getFilenameMd5();
		$_POST['org_filename'] = ToolsUpload::getOrgFilename(); 		   		   	
		$uploadFilename = $_POST['filename'];
		// imposta il nomefile precedente se non si è caricata un file (serve per far passare il controllo campo file presente)
		if ($_POST['filename'] == '' && $App->itemOld->filename != '') $_POST['filename'] = $App->itemOld->filename;
		if ($_POST['org_filename'] == '' && $App->itemOld->org_filename != '') $_POST['org_filename'] = $App->itemOld->org_filename;	 
		// opzione cancella immagine
		if (isset($_POST['deleteFilename']) && $_POST['deleteFilename'] == 1) {
			if (file_exists($App->params->uploadPaths['prod'].$App->itemOld->filename)) {			
				@unlink($App->params->uploadPaths['prod'].$App->itemOld->filename);	
			}	
			$_POST['filename'] = '';
			$_POST['org_filename'] = ''; 	
		}
			
		// parsa i post in base ai campi
		Form::parsePostByFields($App->params->fields['prod'],Config::$localStrings,array());
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyProd/'.$App->id);
		}

		Sql::updateRawlyPost($App->params->fields['prod'],$App->params->tables['prod'],'id',$App->id);
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
		
		if ($uploadFilename != '') {
			move_uploaded_file(ToolsUpload::getTempFilename(),$App->params->uploadPaths['prod'].$uploadFilename) or die('Errore caricamento file');   			
			// cancella l'immagine vecchia
			if (isset($App->itemOld->filename) && file_exists($App->params->uploadPaths['prod'].$App->itemOld->filename)) {			
				@unlink($App->params->uploadPaths['prod'].$App->itemOld->filename);			
			}
		}
		
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['prodotto'],Config::$localStrings['%ITEM% modificato'])).'!';
		if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyProd/'.$App->id);
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listProd');
		}								
	break;
	
	case 'modifySeoProd':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		$App->item = new stdClass;
		Sql::initQuery($App->params->tables['prod'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (!isset($App->item->id) || (isset($App->item->id) && $App->item->id < 1)) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }			
		$App->pageSubTitle = Config::$localStrings['modifica'].' '.Config::$localStrings['Tag SEO'].' '.Config::$localStrings['prodotto'];
		$App->methodForm = 'updateSeoProd';
		$App->viewMethod = 'formSeo';
	break;

	case 'updateSeoProd':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id > 0) {
			if ($_POST) {
				$fields = array();
				$fieldsVal = array();
				foreach($globalSettings['languages'] AS $lang) {
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
				Sql::initQuery($App->params->tables['prod'],$fields,$fieldsVal,'id = ?','');
				Sql::updateRecord();
				if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
				
				$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['Tag SEO'],Config::$localStrings['%ITEM% modificati'])).'!';
				if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
					ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifySeoProd/'.$App->id);
				} else {
					ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listProd');
				}	
				
			} else {
				$App->viewMethod = 'formSeoMod';	
			}
			
			
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');
		}			
	break;

	
	case 'pageProd':
		$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'page',$App->id);
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listProd');
	break;

	case 'listProd':
	default;		
		$App->items = new stdClass;
		$App->item = new stdClass;						
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 5);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);
		$qryFieldsValues = array();
		$qryFieldsValuesClause = array();
		$qryClause = '';
		$and = '';
		if (isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != '') {
			list($sessClause,$qryFieldsValuesClause) = Sql::getClauseVarsFromAppSession($_MY_SESSION_VARS[$App->sessionName]['srcTab'],$App->params->fields['prod'],'');
		}	
		if (isset($sessClause) && $sessClause != '') $qryClause .= $and.'('.$sessClause.')';
		if (is_array($qryFieldsValuesClause) && count($qryFieldsValuesClause) > 0) {
			$qryFieldsValues = array_merge($qryFieldsValues,$qryFieldsValuesClause);	
		}

		Products::resetQryVars();
		Products::$optGetOnlyActive = false;
		Products::$optQryClause = $qryClause;
		Products::$optQryForPage = $App->itemsForPage;	
		Products::$optQryPage = $App->page;
		Products::$optQryDoPagination = true;
		$App->items = Products::getProductsList($_SESSION[$App->sessionName]['categories_id']);	
		//ToolsStrings::dump($App->items);	
		$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$App->itemsForPage);
		$App->paginationTitle = Config::$localStrings['mostra da %START% a %END% di %ITEM% elementi'];
		$App->paginationTitle = preg_replace('/%START%/',$App->pagination->firstPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/',$App->pagination->lastPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/',$App->pagination->itemsTotal,$App->paginationTitle);

		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['prodotti'],Config::$localStrings['lista dei %ITEM%']);
		$App->viewMethod = 'list';	
	break;	
	}


/* SEZIONE SWITCH VISUALIZZAZIONE TEMPLATE (LIST, FORM, ECC) */

switch((string)$App->viewMethod) {
	
	case 'formSeo':
		$App->templateApp = 'formSeoProduct.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formSeoProduct.js"></script>';
	break;
	
	case 'form':
		$App->templateApp = 'formProduct.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formProduct.js"></script>';
	break;

	case 'list':
	default:
		$App->templateApp = 'listProducts.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/listProducts.js"></script>';
	break;	

}	

$App->defaultJavascript = "messages['Il prezzo deve essere in formato valuta!'] = '".preg_replace('/%ITEM%/',Config::$localStrings['prezzo'],Config::$localStrings['Il valore per il campo %ITEM% deve essere in formato valuta!'])."';";

?>
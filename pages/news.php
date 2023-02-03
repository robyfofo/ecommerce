<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * news.php v.1.0.0. 02/07/2021 
*/

//Sql::setDebugMode(1);
$App->moduleName = 'news';
$App->moduleTitle = ucfirst(Config::$localStrings['notizie']);

/* gestione titoli pagina */ 
$App->titles = Utilities::getTitlesPage(ucfirst(Config::$localStrings['notizie']),$App->modulePageData,Config::$localStrings['user'],array());
//print_r($App->titles);

Sql::setOptAddRowFields(1);
Sql::setOptImageFolder('news/');
Sql::setOptDetailAction($App->moduleName.'/dt/');

if (Core::$resultOp->error == 0) {
	switch (Core::$request->method) {								
	
		case 'df':
			if (intval(Core::$request->param) > 0) {
				$renderTpl = false;		
				//ToolsUpload::downloadFileFromDB(PATH_UPLOAD_DIR."news/files/",intval(Core::$request->param),array('table'=>Config::$dbTablePrefix.'news_resources','whereClause'=>'id = ? AND resource_type = 2'));	
				if (Core::$resultOp->error == 1) echo Core::$resultOp->message;					
				}
			die();
		break;
		
		case 'dt':

			$id = intval(Core::$request->param);
			if ($id > 0) {
				Sql::initQuery(Config::$dbTablePrefix.'news',array('*'),array($id),"active = 1 AND id = ?",'');
				$App->item = Sql::getRecord();
				//print_r($App->item); 
				//die();
				if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); die(); }
				if (!isset($App->item->id) || (isset($App->item->id) && $App->item->id == 0)) { ToolsStrings::redirect(URL_SITE.'error/404'); die(); }
			
				//  gestione breadcrumbs
				$App->breadcrumbs->title = $App->titles['title'];
				$App->breadcrumbs->items[] = array('class'=>'','url'=>URL_SITE.Core::$request->action.'/ls','title'=>$App->titles['title']);
				$App->breadcrumbs->items[] = array('class'=>'active','title'=>$App->item->title);
				
		   		// SEO										
				$App->metaTitlePage = $globalSettings['meta tags page']['title ini'].$App->item->meta_title_locale.$globalSettings['meta tags page']['title separator'].$globalSettings['meta tags page']['title end'];
				$App->metaDescriptionPage = $App->item->meta_description_locale;
				$App->metaKeywordsPage = $App->item->meta_keyword_locale;
				$App->view = 'dt';						
			
			} else {
				ToolsStrings::redirect(URL_SITE.'error/404'); 
				die();
			}
		
		break;
		
		default:
			/* preleva le voci */
			$arr = array();
			$App->page = Core::$request->page;
			$itemsForPage = 6;

			Sql::initQuery(Config::$dbTablePrefix.'news',array('*'),array(),'active = 1','datatimeins ASC');
			Sql::setResultPaged(true);
			Sql::setPage($App->page);
			Sql::setItemsForPage($itemsForPage);
			Sql::setOrder('datatimeins DESC');
			$App->items = Sql::getRecords();		
			if (Core::$resultOp->error> 0) {	ToolsStrings::redirect(URL_SITE.'error/db'); die(); }
			//ToolsStrings::dump($App->items);//die();
			
			$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$itemsForPage);
			$App->pageUrl = URL_SITE.Core::$request->action.'/ls';
						
			/* gestione titolo pagina */
			$App->titlepage = $App->titles['title'];
			$App->pageUrl = URL_SITE.Core::$request->action.'/ls';
			
			/* gestione breadcrumbs */
			$App->breadcrumbs->title = $App->titles['title'];
			$App->breadcrumbs->items[] = array('class'=>'active','title'=>$App->titles['titleSeo']);				
		   /* SEO **/
			$App->metaTitlePage = $globalSettings['meta tags page']['title ini'].$App->titles['titleMeta'].$globalSettings['meta tags page']['title separator'].$globalSettings['meta tags page']['title end'];
			$App->metaDescriptionPage .= '';
			$App->metaKeywordsPage .= '';
			$App->view = '';		
		break;
	
		}
	}	
	
/* SEZIONE VIEW */

switch ($App->view) {
	case 'dt':
		$App->templateApp = 'new';
	break;
	
	default:
	break;
}
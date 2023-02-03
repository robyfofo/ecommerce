<?php
/**
 * Framework siti html-PHP-Mysql
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * pages/pages.php v.1.0.0. 17/02/2021
*/

//Core::setDebugMode(1);
$App->view = '';

/*
echo 'page_id: '.intval(Core::$request->page_id);
echo '<br>page_alias: '.Core::$request->page_alias;
echo '<br>method: '.Core::$request->method;
//die('modulo pages');
*/

switch (Core::$request->method) {	

	case 'df':
		if (intval(Core::$request->param) > 0) {
			$renderTpl = false;		
			ToolsDownload::downloadFileFromDB(PATH_UPLOAD_DIR."pages/",intval(Core::$request->param),array('table'=>Config::$dbTablePrefix.'pages_resources','whereClause'=>'id = ? AND resource_type = 2'));	
			if (Core::$resultOp->error == 1) {
				ToolsStrings::redirect(URL_SITE.'error/404');die();
			}				
		}
		die();
	break;

	default:
		if ( intval(Core::$request->page_id) == 0  && Core::$request->page_alias == '' ) {
			die('nessun parametro');ToolsStrings::redirect(URL_SITE.'error/404');
		}	
		// preleva i dati della pagina
		$where = 'active = 1 AND ';
		$key = (isset(Core::$request->param) ? Core::$request->param : '');
		$auth = (isset(Core::$request->params[0]) ? Core::$request->params[0] : '');
		if ($key == $globalSettings['site code key'] && $auth == 'aprew') $where = '';
		Sql::initQuery(Config::$dbTablePrefix.'pages',array('*'),array(Core::$request->page_alias,Core::$request->page_id,Core::$request->page_alias,Core::$request->page_id),$where.'(alias = ? OR alias = ? OR id = ? OR id = ?)');
		$App->pageData = Sql::getRecord();
		//print_r($App->pageData);die();
	
		if (!isset($App->pageData->id) || (isset($App->pageData->id) && $App->pageData->id == 0)) {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}							
		// gestione titoli pagina
		$App->titles = Utilities::getTitlesPage(ucfirst(Core::$localStrings['pagina']),$App->pageData,Core::$localStrings['user'],array());
		//ToolsStrings::dump($App->titles);
		
		// preveva i dati del template 
		Sql::initQuery(Config::$dbTablePrefix.'pagetemplates',array('*'),array($App->pageData->id_template),'id = ?');
		$App->templateData = Sql::getRecord();	
		if (Core::$resultOp->error > 0) {
			ToolsStrings::redirect(URL_SITE.'error/db');
		}
		//ToolsStrings::dump($App->templateData);
			
		// gestione datipagina
		$App->pageTitleMeta = strip_tags(Multilanguage::getLocaleObjectValue($App->pageData,'title_meta_',Core::$localStrings['user'],array()));
		$App->pageTitleSeo = Multilanguage::getLocaleObjectValue($App->pageData,'title_seo_',Core::$localStrings['user'],array());
		$App->pageTitle = Multilanguage::getLocaleObjectValue($App->pageData,'title_',Core::$localStrings['user'],array());
			
		$App->pageData->content = Multilanguage::getLocaleObjectValue($App->pageData,'content_',Core::$localStrings['user'],array());
		$App->pageData->contentNoHtml = strip_tags($App->pageData->content);
		$App->pageData->contentNoP = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $App->pageData->content);
			
		$App->pageData->title_alt = Multilanguage::getLocaleObjectValue($App->pageData,'title_alt_',Core::$localStrings['user'],array());
		$App->pageData->title_alt1 = Multilanguage::getLocaleObjectValue($App->pageData,'title_alt1_',Core::$localStrings['user'],array());
		//die('fatto');
		//print_r($App->pageData);
			
		// prende i dati dei blocchi associati
		$App->pageData->blocks = array();
		$App->pageData->blocks_images = array();			
		
		$arr = array();
		
		Sql::initQuery(Config::$dbTablePrefix.'pages_blocks',array('*'),array($App->pageData->id),'active = 1 AND id_owner= ?');
		Sql::setOrder('ordering ASC');	
		$obj = Sql::getRecords();
		
		if (Core::$resultOp->error > 0) {
			die('errore 3');
			ToolsStrings::redirect(URL_SITE.'error/db');die();
		}
		if (is_array($obj) && is_array($obj) && count($obj) > 0) {
			foreach ($obj AS $value) {		
				$value->title =  Multilanguage::getLocaleObjectValue($value,'title_',Core::$localStrings['user'],array());
				$value->content = Multilanguage::getLocaleObjectValue($value,'content_',Core::$localStrings['user'],array());
				$value->contentNoP = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $value->content);						
				$value->url_text = Multilanguage::getLocaleObjectValue($value,'url_text_',Core::$localStrings['user'],array());
				$value->url = ToolsStrings::parseHtmlContent($value->url,array('urlscheme'=>true));						
				$arr[] = $value;
			}
		}
		$App->pageData->blocks = $arr;
		//print_r($App->pageData->blocks);
		
		if (isset($dataMenuPages[$App->pageData->alias]->breadcrumbs) && is_array($dataMenuPages[$App->pageData->alias]->breadcrumbs) && count($dataMenuPages[$App->pageData->alias]->breadcrumbs) > 0) array_pop($dataMenuPages[$App->pageData->alias]->breadcrumbs);
	
		//aggiorna breadcrumbs 	
		if (isset($dataMenuPages[$App->pageData->alias]->breadcrumbs)) $breadcrumbs = $dataMenuPages[$App->pageData->alias]->breadcrumbs;
		$x = 1;	
		
		if (isset($breadcrumbs) && is_array($breadcrumbs) && count($breadcrumbs) > 0) {							
			foreach ($breadcrumbs AS $key=>$value) {
				$url = URL_SITE.$value['alias'];
				if ($value['type'] == 'label') $url = "javascript:void(0);";					
				$App->breadcrumbs->items[$x] = array('class'=>'','url'=>$url,'title'=>$value['title_it']);
				$x++;
			}
		}
						
		$App->breadcrumbs->items[$x] = array('class'=>'active','url'=>'','title'=>$App->pageTitle);				
		$App->breadcrumbs->title = $App->pageTitle;
		$App->breadcrumbs->tree =  Utilities:: generateBreadcrumbsTree($App->breadcrumbs->items,Core::$localStrings,array('template'=>$templateBreadcrumbsBar,'title'=>$App->breadcrumbs->title));

		$App->metaTitlePage = $globalSettings['meta tags page']['title ini'].$App->titles['titleMeta'].$globalSettings['meta tags page']['title separator'].$globalSettings['meta tags page']['title end'];
		$App->metaDescriptionPage = Multilanguage::getLocaleObjectValue($App->pageData,'meta_description_',Core::$localStrings['user'],array());;
		$App->metaKeywordsPage = Multilanguage::getLocaleObjectValue($App->pageData,'meta_keyword_',Core::$localStrings['user'],array());;
		$App->view = '';
		
		
		// imposta il template in uso dalla pagina
		$App->templateApp = $App->templateData->template;
	break;
}		

			
switch ($App->view) {
	default:	
	break;
}
?>
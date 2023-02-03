<?php 
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * products.php v.1.0.0. 05/07/2021 
*/

//Sql::setDebugMode(1);

//ToolsStrings::dump(Core::$request);

$pageId = Core::$request->page_id;
$pageAlias = Core::$request->page_alias;

echo '<br>page_id: '.$pageId;
echo '<br>page_alias: '.$pageAlias;
//die('page products');

$App->moduleName = 'products';
$App->moduleTitle = ucfirst(Config::$localStrings['prodotti']);
$App->titles = Utilities::getTitlesPage($App->moduleTitle,$App->modulePageData,Config::$localStrings['user'],array());
//ToolsStrings::dump($App->titles);

$continue = true;

// trova se esiste una categorie con il parametro
$App->category = Subcategories::getCategoryDetails($pageId,$pageAlias);
//ToolsStrings::dump($App->category);die('step 1');

if (isset($App->category->id) && $App->category->id > 0) {
	//echo '<br>cerco nella categoria...';die('step 2');
	//Sql::setDebugMode(1);
	Products::resetQryVars();
	//Products::$qryFields = array('');
	Products::$optQryClause = 'active = 1';
	Products::$optQryForPage = 3;	
	Products::$optQryPage = Core::$request->page;;
	Products::$optQryDoPagination = true;
	$App->products =  Products::getProductsList($App->category->id);
	if (!is_array($App->products) || (is_array($App->products) && count($App->products) == 0)) {
		die('nessun prodotto nella categoria');
		ToolsStrings::redirect(URL_SITE.'error/404');	
	}
	$App->pagination = Utilities::getPagination(Products::$optQryPage,Sql::getTotalsItems(),Products::$optQryForPage);
	$App->pageUrl = URL_SITE.Core::$request->action;
	if ($pageId != '') $App->pageUrl .= '/'.$pageId;
	if ($pageAlias != '') $App->pageUrl .= '/'.$pageAlias;

	// gestione titolo pagina
	$App->titlepage = $App->titles['title'];
	
	// gestione breadcrumbs
	$breadcrumbsTitle = $App->titles['title'];
	$App->breadcrumbs->items[] = array('class'=>'','url'=>$App->pageUrl,'title'=>$App->titles['title']);
	$App->breadcrumbs->items[] = array('class'=>'active','title'=>$App->category->title_seo);	

	$metaTitlePage = $App->titles['titleMeta'];
	$metaDescriptionPage = $App->category->meta_description;
	$metaKeywordsPage = $App->category->meta_keyword;
	$App->view = 'products';
	$continue = false;
}

if ($continue == true) {

	//echo '<br>cerco nei prodotti';die('step 3');
	Products::resetQryVars();
	Products::$optGetCategoryOwner = true;
	Products::$optGetOnlyActive = true;

	$App->product = Products::getProductDetails($pageId,$pageAlias);
	//ToolsStrings::dump($App->product);//die('step 4');
	if (isset($App->product->id) && $App->product->id > 0) {

		// creo la gellery a pertire dalle immagini
		$App->product->images = array();
		if ($App->product->filename != '')
		{
			$App->product->images[] = array(
				'filename'		=> $App->product->filename,
				'org_filename'	=> $App->product->org_filename,
				'title'			=> $App->product->title_seo,
				'url'			=> UPLOAD_DIR.'warehouse/products/'.$App->product->filename
			);
		}

		// prelevo le immagini associate
		//Sql::setDebugMode(1);
		Sql::initQueryBasic(Config::$dbTablePrefix.'modules_resources',array('*'),array($App->product->id,Products::$dbTable),'active = 1
		AND id_owner = ? AND module_table = ?','','');
		$pdoObject = Sql::getPdoObjRecords();	
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); die(); }
		while ($row = $pdoObject->fetch()) {
			$titleField = 'title_'.Config::$localStrings['user'];
			$App->product->images[] = array(
				'filename'		=> $row->filename,
				'org_filename'	=> $row->org_filename,
				'title'			=> $row->$titleField,
				'url'			=> UPLOAD_DIR.'warehouse/products/'.$row->filename
			);	
		}
		//ToolsStrings::dump($App->product->images);//die();
		// gestione titolo pagina
		$metaTitlePage =  $App->product->meta_title;
		$App->pageUrl = URL_SITE.Core::$request->action;
		// gestione breadcrumbs
		$breadcrumbsTitle = $App->product->meta_title;
		$App->breadcrumbs->items[] = array('class'=>'','url'=>$App->pageUrl,'title'=>$App->titles['title']);
		$App->breadcrumbs->items[] = array('class'=>'','url'=>URL_SITE.$App->product->category->alias,'title'=>$App->product->category->title_seo);	
		$App->breadcrumbs->items[] = array('class'=>'active','title'=>$App->product->title);		
		// SEO
		$metaDescriptionPage = $App->product->meta_description;
		$metaKeywordsPage = $App->product->meta_keyword;		
		$App->view = 'product';
		$continue = false;
	}

}

// gestione titolo pagina
$App->titlepage = $metaTitlePage;
// gestione breadcrumbs
$App->breadcrumbs->title = $breadcrumbsTitle;
// SEO
$App->metaTitlePage = $globalSettings['meta tags page']['title ini'].$metaTitlePage.$globalSettings['meta tags page']['title separator'].$globalSettings['meta tags page']['title end'];
$App->metaDescriptionPage .= $metaDescriptionPage;
$App->metaKeywordsPage .= $metaKeywordsPage;


switch ($App->view) {
	case 'products':
		echo 'products';
		$App->templateApp = 'products';
	break;
	case 'product':
		$App->templateApp = 'product';
	break;
	default:
		$App->templateApp = 'products';
	break;
}
?>
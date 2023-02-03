<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * site_init.php v.1.0.0. 11/02/2021 
 */

//Sql::setDebugMode(1);

Config::setShopSessionId('sess1234567890'); 
Config::setShopSessionToken('token1234567890'); 

Products::$optImageFolder = 'warehouse/products/';


$App->pageIsInMenu = false;
$App->pageActive = 'home';

// main menus section
$App->pageActive = 'home';
Menu::$optHideInactive = true;

// menu1
Menu::$typeAlias = 'menu1';
$dataMainMenu1 = Menu::getObjFromMenus();
//ToolsStrings::dump($dataMainMenu1);//die('vedi dataMainMenu1 ');
// menu2
Menu::$typeAlias = 'menu2';
$dataMainMenu2 = Menu::getObjFromMenus();
//ToolsStrings::dump($dataMainMenu2);die('vedi dataMainMenu2 ');
// fine main menus section

//ToolsStrings::dump($dataMainMenu);
//ToolsStrings::dump(Core::$request);
if (isset($dataMainMenu[Core::$request->action]->breadcrumbs[0]['parent'])) {
	$App->pageActive = $dataMainMenu[Core::$request->action]->breadcrumbs[0]['alias'];
	$App->pageIsInMenu = true;
}
/* ToolsStrings::dump($dataMainMenu);die(); */
// end menu page section

// menu pages section
Pages::$optGetBreadcrumbs = 1;
Pages::$optHideInactive = true;
Pages::$optShowInMenuOnly = true;
$dataMenuPages = Pages::getObjFromPages();
$App->pageAlias = Core::$request->page_alias;
$App->pageID = Core::$request->page_id;
if (Core::$request->page_alias != '') $App->pageActive = Core::$request->page_alias;
if (isset($dataMenuPages[Core::$request->page_alias]->breadcrumbs[0]['parent'])) {
	$App->pageActive = $dataMenuPages[Core::$request->page_alias]->breadcrumbs[0]['alias'];
	$App->pageID = $dataMenuPages[Core::$request->page_alias]->breadcrumbs[0]['parent'];
}
//ToolsStrings::dump($dataMenuPages);die('fatto');
// fine menu pages section

// menu categories sections
Subcategories::setOptSqlOptions(array('fieldTokeyObj'=>'id'));
$dataMenuCategories = Subcategories::getObjFromSubCategories();
//ToolsStrings::dump($dataMenuCategories);die('fatto');

// carrello
$App->carts = new stdClass;
$App->cartsProducts = array();
if ( isset($_SESSION['CartsId']) && $_SESSION['CartsId'] > 0 ) 
{
    Carts::setCartsId($_SESSION['CartsId']);
	Carts::loadCartsProducts();
	$App->cartsProducts = Carts::$cartProducts;
	$App->carts->valuta_total_products_price_total = Carts::$valuta_total_products_price_total;
}
//echo 'CartsId: '.$_SESSION['CartsId'];
//ToolsStrings::dump($App->cartsProducts);
//die('fatto');
// fine carrello

// lista desideri
$App->wishlist = new stdClass;
$App->wishlistProducts = array();
if ( isset($_SESSION['WishlistId']) && $_SESSION['WishlistId'] > 0 ) 
{
    Wishlist::setId($_SESSION['WishlistId']);
	Wishlist::loadProducts();
	$App->WishlistProducts = Wishlist::$WishlistProducts;
}
//echo 'WishlistID: '.$_SESSION['WishlistId'];
//ToolsStrings::dump($App->WishlistProducts);
//die('fatto');
// fine lista desideri

// gestione chhokie terze parti
$App->cookiesThirdyParts = false;
if (isset($_COOKIE[$globalSettings['cookiesterzeparti']]) && $_COOKIE[Config::$globalSettings['cookiesterzeparti']] == 1) $App->cookiesThirdyParts = true;

/* SEO */
$App->metaTitlePage = $globalSettings['meta tags page']['title ini'].Config::$globalSettings['meta tags page']['title separator'].$globalSettings['meta tags page']['title end'];
$App->metaDescriptionPage = $globalSettings['meta tags page']['description'];
$App->metaKeywordsPage = $globalSettings['meta tags page']['keyword'];

/*
echo 'meta title: '.$App->metaTitlePage;
echo 'meta descriptions: '.$App->metaDescriptionPage;
echo 'meta keyword: '.$App->metaKeywordsPage;
*/

/* BREADCRUMBS */
$App->breadcrumbs = new stdClass();
$App->breadcrumbs->items = array();
$App->breadcrumbs->items[] = array('class'=>'home','url'=>URL_SITE,'title'=>'Home');

/* LINGUE */
$App->languagesBar = Utilities::generateLanguageBar($templateLanguagesBar,Config::$localStrings,$_SESSION['lang']);

// se e un modulo carica i dati
$App->modulePageData = new stdClass();
if ($App->pageActive != '') {
	Sql::initQuery(Config::$dbTablePrefix.'pages',array('*'),array($App->pageActive),'active = 1 AND (alias LIKE ?)');
	$obj = Sql::getRecord();
	if (Core::$resultOp->error == 0 && isset($obj) && count((array)$obj) > 1) {
		$App->modulePageData = $obj;
		/* preleva eventuali breadcrumbs superiori */
		if (isset($dataMenuPages[$App->modulePageData->alias]->breadcrumbs) && is_array($dataMenuPages[$App->modulePageData->alias]->breadcrumbs) && count($dataMenuPages[$App->modulePageData->alias]->breadcrumbs) > 0) array_pop($dataMenuPages[$App->modulePageData->alias]->breadcrumbs);
		/* aggiorna breadcrumbs */		
		if (isset($dataMenuPages[$App->modulePageData->alias]->breadcrumbs)) $breadcrumbs = $dataMenuPages[$App->modulePageData->alias]->breadcrumbs;
		$x = 1;	
		if (isset($breadcrumbs) && is_array($breadcrumbs) && count($breadcrumbs) > 0) {							
			foreach ($breadcrumbs AS $key=>$value) {
			$url = URL_SITE.$value['alias'];
			if ($value['type'] == 'label') $url = "javascript:void(0);";					
				$App->breadcrumbs->items[$x] = array('class'=>'','url'=>$url,'title'=>$value['title_it']);
				$x++;
			}
		}
	}
}

//echo 'App->pageActive: '.$App->pageActive;	

/* se ha dati pagina li carica */

$App->modulePageData = new stdClass();

if ($App->pageActive != '') {
	Sql::initQuery(Config::$dbTablePrefix.'pages',array('*'),array($App->pageActive),'active = 1 AND (alias LIKE ?)');
	$obj = Sql::getRecord();
	if (Core::$resultOp->error == 0 && isset($obj) && count((array)$obj) > 1) {
		$App->modulePageData = $obj;
		/* preleva eventuali breadcrumbs superiori */
		if (isset($dataMenuPages[$App->modulePageData->alias]->breadcrumbs) && is_array($dataMenuPages[$App->modulePageData->alias]->breadcrumbs) && count($dataMenuPages[$App->modulePageData->alias]->breadcrumbs) > 0) array_pop($dataMenuPages[$App->modulePageData->alias]->breadcrumbs);
		/* aggiorna breadcrumbs */		
		if (isset($dataMenuPages[$App->modulePageData->alias]->breadcrumbs)) $breadcrumbs = $dataMenuPages[$App->modulePageData->alias]->breadcrumbs;
		$x = 1;	
		if (isset($breadcrumbs) && is_array($breadcrumbs) && count($breadcrumbs) > 0) {							
			foreach ($breadcrumbs AS $key=>$value) {
			$url = URL_SITE.$value['alias'];
			if ($value['type'] == 'label') $url = "javascript:void(0);";					
				$App->breadcrumbs->items[$x] = array('class'=>'','url'=>$url,'title'=>$value['title_it']);
				$x++;
			}
		}
	}
}

// controlla se method e nell'array menu
if (Core::$request->action == Core::$globalSettings['requestoption']['defaultpagesmodule'] && isset($dataMainMenu[Core::$request->method]->breadcrumbs[0]['parent'])) {
	$App->pageActive = $dataMainMenu[Core::$request->method]->breadcrumbs[0]['alias'];	
}

// gestione immagine top e bottom pagina
$App->modulePageData->image_top =  UPLOAD_DIR.'pages/default/default-image-top-pages.jpg';
$App->modulePageData->image_bottom = UPLOAD_DIR.'pages/default/default-image-bottom-pages.jpg';
if (is_object($App->modulePageData)) {
	if (isset($App->modulePageData->filename) && $App->modulePageData->filename != '') $App->modulePageData->image_top =  UPLOAD_DIR.'pages/'.$App->modulePageData->filename;
	if (isset($App->modulePageData->filename1) && $App->modulePageData->filename1 != '') $App->modulePageData->image_bottom =  UPLOAD_DIR.'pages/'.$App->modulePageData->filename1;
}

// users
$App->user_details = new stdClass();
if ( isset($_MY_SESSION_VARS['UsersId']) && $_MY_SESSION_VARS['UsersId'] > 0 ) {
	Users::getDetails($_MY_SESSION_VARS['UsersId'],array());
	if (Core::$resultOp->error > 0) {	ToolsStrings::redirect(URL_SITE.'error/db'); die(); }	
	$App->user_details = Users::$details;
	//print_r($App->user_details);
}

// CUSTOM 
$App->view = '';

<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * site_main.php v.1.0.0. 30/06/2021 
 */

//echo 'pageActive: '.$App->pageActive;

$optMainMenu['activeMenuAlias'] = $App->pageActive;
$App->mainMenu1 = Menu::createMenuOutput($dataMainMenu1,0,$optMainMenu);
//ToolsStrings::dump($App->mainMenu1);die();

Menu::resetOutput();
$App->mainMenu2 = Menu::createMenuOutput($dataMainMenu2,0,$optMainMenu);
//ToolsStrings::dump($App->mainMenu2);die();

$optMenuPages['languages'] = Config::$globalSettings['languages'];
$optMenuPages['activepage'] = $App->pageActive;
$App->menuPages = '';
$App->menuPages = Pages::createMenuFromSubPages($dataMenuPages,0,$optMenuPages);
//ToolsStrings::dump($App->menuPages);


$App->megamenuCategories = Subcategories::createMegamenuOutput(3,$optMegamenuCategorie);
//echo $App->megamenuCategories; die();

$App->megamenuCatalogo = Subcategories::createMegamenuOutput(4,$optMegamenuCatalogo);

//ToolsStrings::dump(Config::$localStrings);
$App->languagesBar = Utilities::generateLanguageBar($templateLanguagesBar,Config::$localStrings,$_SESSION['lang']);
$App->languagesBarFooter = Utilities::generateLanguageBar($templateLanguagesBarFooter,Config::$localStrings,$_SESSION['lang']);
$App->breadcrumbsBar =  Utilities::generateBreadcrumbsTree($App->breadcrumbs->items,Config::$localStrings,array('template'=>$templateBreadcrumbsBar,'title'=>$App->breadcrumbs->title));
//ToolsStrings::dump($App->breadcrumbs);

// messaggi sistema
$App->systemMessages = '';
$systemMessages = new stdClass();
if (isset($_SESSION['message']) && $_SESSION['message'] != '') {
	$mess = explode('|',$_SESSION['message']);
	unset($_SESSION['message']);
}
if (isset($mess[0])) $systemMessages->error = $mess[0];
if (isset($mess[1])) $systemMessages->message =$mess[1];
$appErrors = Utilities::getMessagesCore($systemMessages);
list($show,$error,$type,$content) = $appErrors;
if ($show == true) {
	if ($type == 0 && $error > 0) $type = $error;
	
	if (isset($templateSystemMessages) && $templateSystemMessages != '') {
		$App->systemMessages .= $templateSystemMessages['container'];
		if ($type == 2) $App->systemMessages = preg_replace('/%ALERT%/', $templateSystemMessages['warning'], $App->systemMessages);
		if ($type == 1) $App->systemMessages = preg_replace('/%ALERT%/', $templateSystemMessages['danger'], $App->systemMessages);
		if ($type == 0) $App->systemMessages = preg_replace('/%ALERT%/', $templateSystemMessages['success'], $App->systemMessages);
		if ($type > 2) $App->systemMessages = preg_replace('/%ALERT%/', $templateSystemMessages['danger'], $App->systemMessages);
		$App->systemMessages = preg_replace('/%MESSAGE%/', $content, $App->systemMessages);	
	} else {
		$App->systemMessages .= '<div id="systemMessageID" class="alert';
		if ($type == 2) $App->systemMessages .= ' alert-warning';
		if ($type == 1) $App->systemMessages .= ' alert-danger';
		if ($type == 0) $App->systemMessages .= ' alert-success';
		if ($type > 2) $App->systemMessages .= ' alert-danger';
		$App->systemMessages .= '">'.$content.'</div>';
	}
}

// varie globali per sito
$App->linkPrivacyPage = '<a href="'.ToolsStrings::parseHtmlContent(Config::$globalSettings['link privacy policy page']).'" title="'.Config::$localStrings['Autorizza il trattamento della privacy'].'">'.Config::$localStrings['privacy policy'].'</a>';
$App->linkTermsConditionsPage = '<a href="'.ToolsStrings::parseHtmlContent(Config::$globalSettings['link terms and contidions page']).'" title="'.Config::$localStrings['Accetta i nostri termini e condizioni'].'">'.Config::$localStrings['termini e condizioni'].'</a>';
$App->linkCookiePolicy = '<a href="'.ToolsStrings::parseHtmlContent(Config::$globalSettings['link cookies policy page']).'" title="'.Config::$localStrings['Accetta la gestione dei nostri cookies'].'">'.Config::$localStrings['cookies policy'].'</a>';


// preleva un proddto random
Products::resetQryVars();
Products::$optGetOnlyActive = true;
$App->randomProductDetails = Products::getOneRandomProductDetails();
$App->randomProductDetails->category = new stdClass;
$App->randomProductDetails->category =  Products::addCategoryOwnerFields($App->randomProductDetails->categories_id);

//ToolsStrings::dump($App->randomProductDetails);

// preleva categoria custom e relativi prodotti
$App->randomCategoryDetails = Subcategories::getOneRandomCategoryDetails();
//ToolsStrings::dump($App->randomCategoryDetails);//die();
$App->randomCategoryProducts = Products::getProductsList($App->randomCategoryDetails->id);
//ToolsStrings::dump($App->randomCategoryProducts);die();

// preleva un prodotto random da una categoria
$App->randomCategoryProductDetails = Subcategories::getOneRandomProductDetails($App->randomCategoryDetails->id);
//ToolsStrings::dump($App->randomCategoryProductDetails);//die();

// preleva ultimi prodotti
Products::resetQryVars();
Products::$optGetOnlyActive = true;
Products::$optGetCategoryOwner = true;
Products::$qryClause = 'is_new = 1';
Products::$qryAndClause = ' AND ';
$App->lastNumbersProducts =  Products::getProductsList(0);
//ToolsStrings::dump($App->lastNumbersProducts);

// preleva brand
$App->siteBrand = array();
Sql::initQuery(Config::$dbTablePrefix.'brands',array('*'),array(),'active = 1 AND in_footer = 1');	
$pdoObject = Sql::getPdoObjRecords();
if (Core::$resultOp->error > 0) {	ToolsStrings::redirect(URL_SITE.'error/db'); die(); }
$x = 0;
while ($row = $pdoObject->fetch()) {
    if ($x % 2 == 0) {
        $App->siteBrand['left'][] = $row;
    } else {
        $App->siteBrand['right'][] = $row;
    }
    $x++;
}
//ToolsStrings::dump($App->siteBrand);

// preleva categories in footer
$App->siteCategories = array();
Sql::initQuery(Config::$dbTablePrefix.'footer_categories',array('*'),array(),'active = 1');	
$pdoObject = Sql::getPdoObjRecords();
if (Core::$resultOp->error > 0) {	ToolsStrings::redirect(URL_SITE.'error/db'); die(); }
$x = 1;
while ($row = $pdoObject->fetch()) {
    if ($x  == 1) {
        $App->siteCategories['left'][] = $row;
    } else if ($x  == 2) {
        $App->siteCategories['center'][] = $row;
    } else {
        $App->siteCategories['right'][] = $row;
    }
    $x++;
    if ($x > 3) $x = 1;
}
//ToolsStrings::dump($App->siteCategories);
// preleva ultime news
Sql::setOptAddRowFields(1);
Sql::setOptImageFolder('news/');
$itemsForPage = 3;
Sql::initQuery(Config::$dbTablePrefix.'news',array('*'),array(),'active = 1','datatimeins ASC');
Sql::setResultPaged(true);
Sql::setPage(1);
Sql::setItemsForPage($itemsForPage);
Sql::setOrder('datatimeins DESC');
$App->homeNews = Sql::getRecords();		
if (Core::$resultOp->error> 0) {	ToolsStrings::redirect(URL_SITE.'error/db'); die(); }
//ToolsStrings::dump($App->siteNews);//die();

?>
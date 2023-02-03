<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * pages/home.php v.1.0.0. 11/02/2021
*/

$App->pageActive = 'home';
Core::$resultOp->error = 0;

//Sql::setDebugMode(1);

// preleva le sliders - modulo tbl_slides_home_rev
Sql::initQuery(Config::$dbTablePrefix.'slides_home_rev',array('*'),array(),'active = 1',' ordering ASC');
$obj = Sql::getRecords();
$arr = array();
if (is_array($obj) && is_array($obj) && count($obj) > 0) {
	foreach ($obj AS $value) {		
		$value->li_data  = preg_replace('/%TITLE%/',$value->title,$value->li_data);					
		$value->layers = array();
		Sql::initQuery(Config::$dbTablePrefix.'slides_home_rev_layers',array('*'),array($value->id),'slide_id = ? AND active = 1',' ordering ASC');
		$layers = Sql::getRecords();
		$arrL = array();

        $value->li_data = preg_replace('/%SLIDEIMAGE%/',UPLOAD_DIR.'slides-home-rev/'.$value->filename,$value->li_data);		

		if (is_array($layers) && is_array($layers) && count($layers) > 0) {
			foreach ($layers AS $valueL) {	
                
                
				$valueL->url =  ToolsStrings:: parseHtmlContent($valueL->url,array());			
				$valueL->template = MultiLanguage::getLocaleObjectValue($valueL,'template_',$localStrings['user'],array('htmLawed'=>0,'parse'=>1));
				$valueL->content = MultiLanguage::getLocaleObjectValue($valueL,'content_',$localStrings['user'],array('htmLawed'=>1,'parse'=>1));
				$valueL->contentNoP = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $valueL->content);				
				$valueL->template = preg_replace('/%CONTENT%/',$valueL->contentNoP,$valueL->template);	
				$valueL->template = preg_replace('/%URL%/',$valueL->url,$valueL->template);
				$valueL->template = preg_replace('/%TARGET%/',$valueL->target,$valueL->template);
                
                
				$arrL[] = $valueL;				
			}
		}			
		$value->layers = $arrL;						
		$arr[] = $value;
	}
}
$App->homeSliders = $arr;
//ToolsStrings::dump($App->homeSliders);


// preleva categoria custom e relativi prodotti
$App->homeCategoryDetails = Subcategories::getOneRandomCategoryDetails();
Products::resetQryVars();
$App->homeCategoryDetailsProducts = Products::getProductsList($App->homeCategoryDetails->id);
//ToolsStrings::dump($App->homeCategoryDetailsProducts);

// preleva 10 prodotti per prodotti sponsorizzati
//Sql::setDebugMode(1);
Products::resetQryVars();
//Products::$qryFields = array('id,categories_id');
Products::$optQryOrder = 'RAND()';
Products::$qryClause = 'is_promo = 1';
Products::$qryAndClause = ' AND ';
$App->homeFeaturedProducts =  Products::getProductsList(0);
//ToolsStrings::dump($App->homeFeaturedProducts);die();


// preleva un proddto promo (random)
Products::resetQryVars();
Products::$qryClause = 'is_promo = 1';
Products::$qryAndClause = ' AND ';
$App->homePromoProductDetails = Products::getOneRandomProductDetails();
$App->homePromoProductDetails->category = new stdClass;
$App->homePromoProductDetails->category =  Products::addCategoryOwnerFields($App->homePromoProductDetails->categories_id);
//ToolsStrings::dump($App->homePromoProductDetails);

// preleva ultimi arrivi prodotti
Products::resetQryVars();
Products::$qryClause = 'is_new = 1';
Products::$qryAndClause = ' AND ';
$App->homeNewArrivalProducts =  Products::getProductsList(0);

// preleva categoria e un prodotto
$App->homeRandomCategoryDetails = Subcategories::getOneRandomCategoryDetails();
//ToolsStrings::dump($App->randomCategoryDetails);//die();
$App->homeRandomCategoryProducts = Products::getOneRandomProductDetails($categories_id = $App->homeRandomCategoryDetails->id);
//ToolsStrings::dump($App->randomCategoryProducts);die();

// SEO
$App->metaTitlePage = $globalSettings['meta tags page']['title ini'].'Home'.$globalSettings['meta tags page']['title separator'].$globalSettings['meta tags page']['title end'];

/* BREADCRUMBS */
$App->breadcrumbs->title = '';
$App->breadcrumbs->items = array();

$App->templateBase = 'struttura-home';
//die('fatto');
?>
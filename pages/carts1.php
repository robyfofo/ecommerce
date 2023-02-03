<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * carts.php v.1.0.0. 09/03/2021 
*/

$App->moduleName = 'carts';
$App->moduleTitle = ucfirst($localStrings['carrello']);
$App->titles = Utilities::getTitlesPage($App->moduleTitle,$App->modulePageData,$App->moduleTitle,array());
$App->view = '';

if (!isset($_SESSION['CartsId']) || (isset($_SESSION['CartsId']) && $_SESSION['CartsId'] == ''))
{
	Core::$request->method = 'empty';
}

//ToolsStrings::dump($App->cartProducts);


switch (Core::$request->method)
{
	case 'empty':
		$App->breadcrumbs->items[] = array('class'=>'active','url'=>'','title'=>$App->titles['title']);
        $App->view = 'empty';
    break;
}


    
//ToolsStrings::dump($_SESSION);

//die('carts');


$breadcrumbsTitle = ucfirst($localStrings['il tuo carrello']);
$metaTitlePage = $App->titles['titleMeta'];


// gestione titolo pagina
$App->titlepage = $metaTitlePage;
// gestione breadcrumbs
$App->breadcrumbs->title = $breadcrumbsTitle;
// SEO
$App->metaTitlePage = $globalSettings['meta tags page']['title ini'].$metaTitlePage.$globalSettings['meta tags page']['title separator'].$globalSettings['meta tags page']['title end'];
$App->metaDescriptionPage .= '';
$App->metaKeywordsPage .= '';

switch ($App->view) {
    case 'empty':
        $App->templateApp = 'carts-empty';
    break;

	default:
	break;
}

$App->jscript[] = '<script src="'.URL_SITE.'templates/'.$App->templateUser.'/assets/js/pages/carts1.js"></script>';

?>
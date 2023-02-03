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

if (Core::$request->method != 'addPro' && isset($App->cartProducts) && count($App->cartProducts) == 0)
{
	Core::$request->method = 'empty';
}

switch (Core::$request->method)
{
    case 'empty':
        $App->breadcrumbs->items[] = array('class'=>'active','url'=>'','title'=>$App->titles['title']);
        $App->view = 'empty';
    break;

    case 'addPro':
        $products_id = (isset(Core::$request->param) && Core::$request->param != '' ? intval(Core::$request->param) : 0);
        $quantity = (isset(Core::$request->params[0]) && Core::$request->params[0] != '' ? intval(Core::$request->params[0]) : 1);
        if ($products_id == 0) { die('nessun prodotto scelto'); ToolsStrings::redirect(URL_SITE.'error/404');  }
        if ( !isset($_SESSION['CartsId']) || ( isset($_SESSION['CartsId']) && $_SESSION['CartsId'] == 0) ) {
            Carts::addNewCart();
            $_SESSION['CartsId'] = Carts::getCartsId();	        
        } else { 
			Carts::setCartsId($_SESSION['CartsId']);
        }
        Carts::addProduct($products_id,$quantity);
        ToolsStrings::redirect(URL_SITE.$App->moduleName);
    break;

    case 'modQtyPro':
        $products_id = (isset(Core::$request->param) && Core::$request->param != '' ? intval(Core::$request->param) : 0);
        $quantity = (isset(Core::$request->params[0]) && Core::$request->params[0] != '' ? intval(Core::$request->params[0]) : 1);
        if ($products_id == 0) { die('nessun prodotto scelto'); ToolsStrings::redirect(URL_SITE.'error/404');  }
        if ( isset($_SESSION['CartsId'])) {
            Carts::setQtyPro($products_id,$quantity);
        }
        ToolsStrings::redirect(URL_SITE.$App->moduleName);
    break;

    case 'delPro':
        echo $products_id = (isset(Core::$request->param) && Core::$request->param != '' ? intval(Core::$request->param) : 0);
        if ($products_id == 0) { die('nessun prodotto scelto'); ToolsStrings::redirect(URL_SITE.'error/404');  }
        if ( isset($_SESSION['CartsId'])) {
            Carts::delProduct($products_id);
        }
        ToolsStrings::redirect(URL_SITE.$App->moduleName);
    break;
}

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

$App->jscript[] = '<script src="'.URL_SITE.'templates/'.$App->templateUser.'/assets/js/pages/carts.js"></script>';

?>
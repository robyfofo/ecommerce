<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * wishes.php v.1.0.0. 09/03/2021 
*/

$App->moduleName = 'wishes';
$App->moduleTitle = ucfirst(Config::$localStrings['lista dei desideri']);
$App->titles = Utilities::getTitlesPage($App->moduleTitle,$App->modulePageData,$App->moduleTitle,array());
$App->view = '';

//ToolsStrings::dump($_SESSION);

switch (Core::$request->method)
{
    case 'clear':
        if (isset($_SESSION['wishesId'])) {
            Wishes::clear($_SESSION['wishesId']);
            unset($_SESSION['wishesId']);
        }
        $App->breadcrumbs->items[] = array('class'=>'active','url'=>'','title'=>$App->titles['title']);
        $App->view = 'clear';
    break;

    case 'empty':
        $App->breadcrumbs->items[] = array('class'=>'active','url'=>'','title'=>$App->titles['title']);
        $App->view = 'empty';
    break;

    case 'addPro':
        $products_id = (isset(Core::$request->param) && Core::$request->param != '' ? intval(Core::$request->param) : 0);
        $quantity = 1;
        if ($products_id == 0) { die('nessun prodotto scelto'); ToolsStrings::redirect(URL_SITE.'error/404');  }
        if ( !isset($_SESSION['wishesId']) || ( isset($_SESSION['wishesId']) && $_SESSION['wishesId'] == 0) ) {
            Wishes::addNew();
            $_SESSION['wishesId'] = Wishes::getId();	        
        }
        Wishes::setId($_SESSION['wishesId']);
        Wishes::addProduct($products_id,$quantity);
        //ToolsStrings::redirect(URL_SITE.$App->moduleName);
    break;

    case 'modQtyProAjax':
        $products_id = 0;
        if (isset(Core::$request->param) && Core::$request->param != '') $products_id = intval(Core::$request->param);
        if (isset($_REQUEST['products_id']) && $_REQUEST['products_id'] != '') $products_id = intval($_REQUEST['products_id']);
        $quantity = 0;
        if (isset($_REQUEST['quantity']) && $_REQUEST['quantity'] != '') $quantity = intval($_REQUEST['quantity']);
        Wishes::setId($_SESSION['wishesId']);
        $data['result'] = '0';
        $data['price_total'] = '0.00';

        if ($products_id > 0) {
            // aggiorna quantita
            $obj = Wishes::setQtyProduct($products_id,$quantity);   

            $data['result'] = '1';
            $data['price_total'] = number_format($obj->price_total,2,',','.');
        } else {
            $data['result'] = '0';
        }
        echo json_encode($data);
        die();
    break;


    case 'delProAjax':
        $products_id = 0;
        if (isset(Core::$request->param) && Core::$request->param != '') $products_id = intval(Core::$request->param);
        if (isset($_REQUEST['products_id']) && $_REQUEST['products_id'] != '') $products_id = intval($_REQUEST['products_id']);
        Wishes::setId($_SESSION['wishesId']);

        if ($products_id > 0) {
            Wishes:: delProduct($products_id);
            $data['result'] = '1';
        } else {
            $data['result'] = '0';
        }
        // controlla de è vuota
       
        if ( true == Wishes::checkIfIsEmpty() ) {
            $data['status'] = 'empty';
        } else {
            $data['status'] = 'full';

        }
        echo json_encode($data);
        die();
    break;

    default:
            if ( isset($_SESSION['wishesId']) && $_SESSION['wishesId'] > 0 ) {
                Wishes::setId($_SESSION['wishesId']);
                Wishes::loadProducts(); 
                $App->items = Wishes::$WishlistProducts;
                //ToolsStrings::dump($App->items);
            }
    break;
}

$breadcrumbsTitle = ucfirst(Config::$localStrings['lista dei desideri']);
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
        $App->templateApp = 'wishes-empty';
    break;

	default:
	break;
}

$App->jscriptCodeTop = "
messages['prodotto cancellato'] = '".ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['prodotto'],Config::$localStrings['%ITEM% cancellato']))."!';
";
$App->jscript[] = '<script src="'.URL_SITE.'templates/'.$App->templateUser.'/assets/js/pages/wishes.js"></script>';
?>
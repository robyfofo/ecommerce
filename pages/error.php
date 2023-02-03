<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * error.php v.1.0.0. 01/07/2021 
*/

switch(Core::$request->method) {
	case '404':
		$App->error_title = Core::$localStrings['Errore!'];
		$App->error_subtitle = Core::$localStrings['Error 404!'];
		$App->error_content = Core::$localStrings['testo errore 404'];
	break;

	case 'access':
		$App->error_title = Core::$localStrings['Errore!'];
		$App->error_subtitle = Core::$localStrings['Access Error!'];
		$App->error_content = Core::$localStrings['testo errore accesso'];
	break;

	case 'db':
		$App->error_title = Core::$localStrings['Errore!'];
		$App->error_subtitle = Core::$localStrings['Database Error!'];
		$App->error_content = Core::$localStrings['testo errore database'];
		$App->error_contentAlt = (Core::$request->param != '' ? Core::$request->param : '');
	break;

	default:
		$App->error_title = Core::$localStrings['Errore!'];
		$App->error_subtitle = Core::$localStrings['Internal Server Error!'];
		$App->error_content = Core::$localStrings['testo errore generico'];
	break;

}

/* gestione breadcrumbs */
$App->breadcrumbs->items = array();
$App->breadcrumbs->items[] = array('class'=>'home','url'=>URL_SITE,'title'=>'Home');
$App->breadcrumbs->items[] = array('class'=>'active','title'=>$App->error_subtitle);		
$App->breadcrumbs->title = $App->error_title ;

/* SEO **/
$App->metaTitlePage = $globalSettings['meta tags page']['title ini'].$App->error_title.$globalSettings['meta tags page']['title separator'].$globalSettings['meta tags page']['title end'];
$App->metaDescriptionPage .= '';
$App->metaKeywordsPage .= '';
$App->templateBase = 'struttura-error';
$App->templateApp = 'error';
?>
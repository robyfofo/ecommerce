<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * include/configuration.inc.php v.1.0.1. 06/07/2021
*/

$servermode = 'remote';
if ($_SERVER['HTTP_HOST'] == '192.168.1.11') $servermode = 'local';


// server
$globalSettings['folder site'] = 'ecommerce100/';
$globalSettings['folder admin'] = 'admin/';
$globalSettings['site host'] = 'www.phprojekt.altervista.org/';
if ($servermode == 'local') {
	$globalSettings['folder site'] = 'phprojekt.altervista.org/ecommerce100/';
	$globalSettings['folder admin'] = 'admin/';
	$globalSettings['site host'] = '192.168.1.11/';
}
// altro per server
if ($servermode == 'remote') {
	$_SERVER['DOCUMENT_ROOT'] = "/membri/phprojekt/"; // specifiche altervista
}

$globalSettings['server timezone'] = '';
$http = 'http://';
if (isset($_SERVER['HTTPS'])) $http = 'https://';

// database
$globalSettings['database'] = array(
	'local'=>array('user'=>'root','password'=>'fofofofo','host'=>'localhost','name'=>'phprojekt.altervista_ecommerce100','tableprefix'=>'ecc_'),
	'remote'=>array('user'=>'phprojekt','password'=>'fofo010966','host'=>'localhost','name'=>'my_phprojekt','tableprefix'=>'ecc_')
);

/* COOKIES */
$globalSettings['cookiestecnicidatabase'] = 'ecommerce100database';
$globalSettings['cookiestecnici'] = 'ecommerce100site';
$globalSettings['cookiesterzeparti'] = 'ecommerce100thirdyparts';
if ($servermode == 'locale')
{
	$globalSettings['cookiestecnicidatabase'] = 'loc'.$globalSettings['cookiestecnicidatabase'];
	$globalSettings['cookiestecnici'] = 'loc'.$globalSettings['cookiestecnici'];
	$globalSettings['cookiesterzeparti'] = 'loc'.$globalSettings['cookiesterzeparti'];
} 

/* CHIAVE HASH */
$globalSettings['site code key'] = '123456789';

/* meta for site */
$globalSettings['meta tags page'] = array(
	'title ini'=>'',
	'title separator'=>' | ',
	'title end'=>"Ecommerce 1.0.0.",
	'description'=>"Ecommerce demo per la prova di sviluppo di un ecommerce e testare le sue funzionalità",
	'keyword'=>"verona, php, mysql, ecommerce, negozio, vendita, onine, internet, acqiusti, carrello , ordini, ordine"
);

/* CONFIGURAZIONI GENERALI */
$globalSettings['mesi'] = array('','Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novenbre','Dicembre');

// tipi menus
$globalSettings['menus type available'] = array(
	'menu1'			=> array(
		'title'		=> 'menu principale',
		'content'	=> 'Il menu principlae'
	),
	'menu2'			=> array(
		'title'		=> 'menu secondario',
		'content'	=> 'Un menu secondario'
	)
);

/* LANGUAGE */
$globalSettings['default languages'] = 'it';
$globalSettings['languages'] = array('it','en');

/* UPLOAD */
$globalSettings['image type available'] = array('JPG','PNG','GIF');
$globalSettings['file type available'] = array('DOC','PDF','SQL');

/* GOOGLE ReCaptcha */
$globalSettings['google recaptcha key'] = '6LfWBk0UAAAAAI-spf1qfnHwf-ahORwDM9g1KOiF';
$globalSettings['google recaptcha secret'] = '6LfWBk0UAAAAAFjlDnm9RQrWOP_z0xXqiZkSToAE';

/* DA NON MODIFICARE */
$globalSettings['requestoption'] = array(
	'defaulttemplate'=>'default',
	'templatesforusers'=>array('default','unify'),
	'managechangeaction'=>0,'defaultaction'=>''
	
);

$globalSettings['months'] = array('01' => 'Gennaio','02' => 'Febbraio','03' => 'Marzo','04' => 'Aprile','05' => 'Maggio','06' => 'Giugno','07' => 'Luglio','08' => 'Agosto','09' => 'Settembre','10' => 'Ottobre','11' => 'Novenbre','12' => 'Dicembre');
$globalSettings['menu-type'] = array('default'=>'Default','label'=>'Etichetta','url'=>'Url','module-link'=>'Link a modulo','module-menu'=>'Menu generato da modulo');
$globalSettings['url-targets'] = array('_self','_blank','_parent','_top');
$globalSettings['module sections'] = array('Moduli Core','Moduli Personalizzati','Impostazioni','Root');
	
define('FOLDER_SITE',$globalSettings['folder site']);
define('FOLDER_ADMIN',$globalSettings['folder admin']);
define('SITE_HOST',$globalSettings['site host']);
define('TIMEZONE',$globalSettings['server timezone']);
define('URL_SITE', $http.SITE_HOST.FOLDER_SITE);
define('URL_SITE_ADMIN', $http.SITE_HOST.FOLDER_SITE.FOLDER_ADMIN);
define('URL_SITE_APPLICATION', $http.SITE_HOST.FOLDER_SITE.FOLDER_ADMIN.'application/');
define('TMP_DIR', $http.SITE_HOST.FOLDER_SITE.FOLDER_ADMIN.'tmp/');
define('PATH_DOCUMENT', $_SERVER['DOCUMENT_ROOT'].'/');
define('PATH_SITE', $_SERVER['DOCUMENT_ROOT'].'/'.FOLDER_SITE);
define('PATH_SITE_ADMIN', $_SERVER['DOCUMENT_ROOT'].'/'.FOLDER_SITE.FOLDER_ADMIN);
/* upload */
define('UPLOAD_DIR', $http.SITE_HOST.FOLDER_SITE.'uploads/');
define('PATH_UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'].'/'.FOLDER_SITE.'uploads/');
define('ADMIN_PATH_UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'].'/'.FOLDER_SITE.'uploads/');
define('DATABASEUSED',$servermode);
define('DB_ENCODING','utf8');
define('SESSIONS_TABLE_NAME',$globalSettings['database'][$servermode]['tableprefix'].'sessions');
//define('SESSIONS_TIME',86400*10);
define('SESSIONS_TIME',0);
define('SESSIONS_GC_TIME',864000); // 10 giorni
define('SESSIONS_COOKIE_NAME',$globalSettings['cookiestecnicidatabase']);
define('AD_SESSIONS_COOKIE_NAME','admin_'.$globalSettings['cookiestecnicidatabase']);
define('DATA_SESSIONS_COOKIE_NAME','data_'.$globalSettings['cookiestecnicidatabase']);
define('TEMPLATE_DEFAULT',$globalSettings['requestoption']['defaulttemplate']);
define('SITE_CODE_KEY',$globalSettings['site code key']);
?>

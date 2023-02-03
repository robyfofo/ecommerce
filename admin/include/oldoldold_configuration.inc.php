<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * include/configuration.inc.php v.1.0.0. 11/02/2021
*/

$servermode = 'remote';

/* SERVER */
$globalSettings['folder site'] = 'ecommerce100/';
$globalSettings['folder admin'] = 'admin/';
$globalSettings['site host'] = 'www.phprojekt.altervista.org/';
/* specifiche altervista */
$_SERVER['DOCUMENT_ROOT'] = "/membri/phprojekt/";
if ($servermode == 'locale') {
	$globalSettings['folder site'] = 'phprojekt.altervista.org/ecommerce100/';
	$globalSettings['folder admin'] = 'admin/';
	$globalSettings['site host'] = '192.168.1.11/';
}

$globalSettings['server timezone'] = '';
$http = 'http://';
if (isset($_SERVER['HTTPS'])) $http = 'https://';

/* DATABASE */
$database = 'locale';
if ($servermode == 'remote') $database = 'remote';
$globalSettings['database'] = array(
	'locale'=>array('user'=>'root','password'=>'fofofofo','host'=>'localhost','name'=>'phprojekt.altervista_ecommerce100','tableprefix'=>'ecc_'),
	'remote'=>array('user'=>'phprojekt','password'=>'fofo010966','host'=>'localhost','name'=>'my_phprojekt','tableprefix'=>'ecc_')
);

/* COOKIES */
$cookies = 'ecommerce100';
if ($servermode == 'locale') $cookies = 'loc'.$cookies;

/* EMAILS */ 
$globalSettings['default email'] = 'me@robertomantovani.vr.it';
$globalSettings['default email label'] = 'Roberto Mantovani';
$globalSettings['send email debug'] = 1;
$globalSettings['email debug'] = "me@robertomantovani.vr.it";

/* send email */
$globalSettings['use send mail class'] = 2; /* use chass for mails: 0 = no class; 1 = PHP7 Swiftmailer class; 2 = php5.x PHPMAILER class */
$globalSettings['mail server'] = 'SMTP'; /* SMTP, PHP or sendmail  */
$globalSettings['sendmai server'] = '/usr/sbin/sendmail -bs';
$globalSettings['SMTP server'] = 'localhost';
$globalSettings['SMTP port'] = '25';
$globalSettings['SMTP username'] = '';
$globalSettings['SMTP password'] = '';

/* CHIAVE HASH */
$globalSettings['site code key'] = '123456789';

/* meta for admin */
$globalSettings['site name'] = "Ecommerce demo";
$globalSettings['code version'] = '1.0.0.';
$globalSettings['site owner'] = 'Roberto Mantovani';
$globalSettings['copyright'] = '&copy; 2021 Roberto Mantovani';

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
$globalSettings['anno creazione'] = '2021';
$globalSettings['azienda breve'] = "Roberto Mantovani";
$globalSettings['azienda slogan'] = "Roberto Mantovani";
$globalSettings['azienda sito'] = "www.robertomantovani.vr.it";
$globalSettings['azienda sito url'] = "www.robertomantovani.vr.it";
$globalSettings['azienda targa'] = 'VR';
$globalSettings['azienda nazione'] = 'Italia';
$globalSettings['azienda email pec'] = 'me@pec.robertomantovani.vr.it';
$globalSettings['azienda cellulare'] = '291566132';
$globalSettings['sito credits'] = 'www.robertomantovani.vr.it';
$globalSettings['sito credits url'] = 'http://www.robertomantovani.vr.it';
$globalSettings['azienda referente'] = 'Roberto Mantovani';
$globalSettings['azienda sito'] = 'Roberto Mantovani';
$globalSettings['azienda indirizzo'] = "Via Garofoli, 302";
$globalSettings['azienda comune'] = 'San Giovanni Lupatoto';
$globalSettings['azienda cap'] = '37057';
$globalSettings['azienda provincia'] = 'Verona';
$globalSettings['azienda provincia abbreviata'] = 'Vr';
$globalSettings['azienda email'] = 'me@robertomantovani.vr.it';
$globalSettings['azienda pec'] = 'me@pec.robertomantovani.vr.it';
$globalSettings['azienda telefono'] = '+39 045.045548841';
$globalSettings['azienda fax'] = '+39 045.2589600';
$globalSettings['azienda mobile'] = '+39 3291566132';
$globalSettings['azienda sid'] = '01010101010';
$globalSettings['azienda codice fiscale'] = 'MNTRRT66P01L781T';
$globalSettings['azienda partita iva'] = '03781010230';
$globalSettings['azienda latitudine'] = '45.39731968';
$globalSettings['azienda longitudine'] = '11.02548289';

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

/* LINK SOCIAL */
$globalSettings['facebook link'] = 'https://www.facebook.com/roberto.mantovani2';
$globalSettings['linkedin link'] = 'https://www.linkedin.com/in/roberto-mantovani-84643214/';
$globalSettings['twitter link'] = '';
$globalSettings['google-plus link'] = '';
$globalSettings['pinterest link'] = '';
$globalSettings['vimeo link'] = '';
$globalSettings['youtube link'] = 'https://www.youtube.com/user/';
$globalSettings['tumblr link'] = '';

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
$globalSettings['page-type'] = array('default'=>'Default','label'=>'Etichetta','url'=>'Url','module-link'=>'Link a modulo');
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
define('DATABASE',$database);
define('SESSIONS_TABLE_NAME',$globalSettings['database'][DATABASE]['tableprefix'].'sessions');
//define('SESSIONS_TIME',86400*10);
define('SESSIONS_TIME',0);
define('SESSIONS_GC_TIME',2592000);
define('SESSIONS_COOKIE_NAME',$cookies);
define('AD_SESSIONS_COOKIE_NAME','ad_'.$cookies);
define('DATA_SESSIONS_COOKIE_NAME','data_'.$cookies);
define('TEMPLATE_DEFAULT',$globalSettings['requestoption']['defaulttemplate']);
define('SITE_CODE_KEY',$globalSettings['site code key']);
define('SITE_NAME', $globalSettings['site name']);
define('CODE_VERSION',$globalSettings['code version']);
define('SITE_OWNER',$globalSettings['site owner']);
define('COPYRIGHT',$globalSettings['copyright']);
?>

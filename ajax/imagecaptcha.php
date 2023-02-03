<?php
/* ajax/imagecaptcha.php v.3.0.0. 10/10/2016 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
define('PATH','../');
include_once(PATH."wscms/include/configuration.inc.php");
include_once(PATH."wscms/classes/class.Config.php");
include_once(PATH."wscms/classes/class.Core.php");
include_once(PATH."wscms/classes/class.Sessions.php");
include_once(PATH."wscms/classes/class.Sql.php");

$Config = new Config();
Config::setGlobalSettings($globalSettings);
$Core = new Core();

/* avvio sessione */
$my_session = new my_session(SESSIONS_TIME, SESSIONS_GC_TIME,AD_SESSIONS_COOKIE_NAME);
$my_session->my_session_start();
$_MY_SESSION_VARS = array();
$_MY_SESSION_VARS = $my_session->my_session_read();

$path = PATH_SITE.'templates/default/plugins/sky-forms-pro/skyforms/captcha/';
if(!isset($_MY_SESSION_VARS['site']['captcha_id'])) {
	$str = 'ERROR!';
	} else {
		$str = $_MY_SESSION_VARS['site']['captcha_id'];
		}
//header('Content-type: image/png');
header('Cache-control: no-cache');
$image = imagecreatefrompng($path.'button.png');
$colour = imagecolorallocate($image, 183, 178, 152);
$font = $path.'font/anorexia.ttf';
$rotate = rand(-10, 10);
imagettftext($image, 20, $rotate, 18, 30, $colour, $font, $str);
imagepng($image);
die();
?>
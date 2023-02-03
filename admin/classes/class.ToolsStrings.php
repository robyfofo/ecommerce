 <?php
 /**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * classes/class.ToolsStrings.php v.1.0.0. 22/06/2021 
 */

class ToolsStrings extends Core {

	public function __construct() {
		parent::__construct();
	}
		
	public static function redirect($url) 
	{
		$protocol = "http://";
		$server_name = $_SERVER["HTTP_HOST"];
		if ($server_name != '') {
			$protocol = "http://";
			if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == "on")) {
				$protocol = "https://";
			}
			if (preg_match("#^/#", $url)) {
			$url = $protocol.$server_name.$url;
			} else if (!preg_match("#^[a-z]+://#", $url)) {
				$script = $_SERVER['PHP_SELF'];
				if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] != '' && $_SERVER['PATH_INFO'] != $_SERVER['PHP_SELF']) {
					$script = substr($script, 0, strlen($script) - strlen($_SERVER['PATH_INFO']));
				}
				$url = $protocol.$server_name.(preg_replace("#/[^/]*$#", "/", $script)).$url;
			}
			$url = str_replace(" ","%20",$url);
			header("Location: ".$url);
		}
			exit;
	}

	public static function dump($var) 
	{
		print("<pre style='font-size:10px'>".print_r($var,true)."</pre>");
	}
		
	public static function setNewPassword($caratteri_disponibili,$lunghezza){
		$password = "";
		for($i = 0; $i<$lunghezza; $i++){
			$password = $password.substr($caratteri_disponibili,rand(0,strlen($caratteri_disponibili)-1),1);
			}
		return $password;
   	}


	public static function getStringFromTotNumberChar($str='',$opz=array()) 
	{
		$opzDef = array('suffix'=>'','numchars'=>100);	
		$opz = array_merge($opzDef,$opz);
		$str = strip_tags($str);
		if (mb_strlen($str) > $opz['numchars']) $str = mb_strcut($str,0, $opz['numchars']).$opz['suffix'];
		return $str;
	}

	public static function getStringFromTotNumberWords($str='',$opt=array()) 
	{
		$optDef = array('suffix'=>'...','numwords'=>100);	
		$opt = array_merge($optDef,$opt);
		$str = strip_tags($str);
		$array = explode(' ',$str);
		if (count($array) > $opt['numwords']) $array = array_slice($array,0,$opt['numwords']);		
		$str = implode(' ',$array).$opt['suffix'];
		return $str;
	}
		
/* SPECIFICHE ARRAY */
   
   public static function multiSearch(array $array, array $pairs)
   {
		$found = array();
		foreach ($array as $aKey => $aVal) {
			$coincidences = 0;
			foreach ($pairs as $pKey => $pVal) {
				if (array_key_exists($pKey, $aVal) && $aVal[$pKey] == $pVal) {
					$coincidences++;
				}
			}
			if ($coincidences == count($pairs)) {
				$found[$aKey] = $aVal;
			}
		}
		return $found;
	}
		
	public static function arrayDeleteByValue($array,$value)
	{
		$key = array_search($value,$array);
		if($key!==false){
			unset($array[$key]);
		}
		return $array;
	}

		
	/* GESTIONE CONTENUTO HTML */
	public static function getHtmlMultiContent($obj,$value,$lang,$opz) 
	{
		//$str = Multilanguage::getLocaleObjectValue($obj,$value,$lang,array('xss'=>true));		
		$opzDef = array('parse'=>true);	
		$opz = array_merge($opzDef,$opz);	
		$str = Multilanguage::getLocaleObjectValue($obj,$value,$lang,array('xss'=>true));
		if ($opz['parse'] == true) $str = self::parseHtmlContent($str);
		return $str;	
	}
		
		
	public static function getPageHtmlContent($str)
	{
		$str = self::filterHtmlContent($str,array('parse'=>true));
		return $str;
	}
		
	public static function getHtmlContent($obj,$value,$opz) 
	{
		$str = 'error object value';
		$opzDef = array('parse'=>true);	
		$opz = array_merge($opzDef,$opz);		
		if (isset($obj->$value)) $str = $obj->$value;	
		$str = self::filterHtmlContent($str,$opz);
		return $str;	
	}

	public static function filterHtmlContent($str,$opz) 
	{
		$opzDef = array('htmlout'=>0,'htmLawed'=>1,'parse'=>1,'striptags'=>0);
		$opz = array_merge($opzDef,$opz);		

		if ($opz['striptags'] == 1) {
			$str = strip_tags($str);
			$opz['htmLawed'] = 0;
		}
						
		if ($opz['htmlout'] == 1) {
			$str = SanitizeStrings::htmlout($str);
			$opz['htmLawed'] = 0;
		}	
			
		if (isset($opz['maxchar']) && $opz['maxchar'] > 0) {
			$str = ToolsStrings::getStringFromTotNumberChar($str,array('numchars'=>$opz['maxchar']));
			$opz['htmLawed'] = 0;
		}		
		
		if ($opz['htmLawed'] == 1) $str = htmLawed::hl($str);
		if ($opz['parse'] == 1) $str = self::parseHtmlContent($str,$opz);
		return $str;	
	}

	
	public static function parseHtmlContent($str,$opt=array())
	{
		$optDef = array('customtag'=>'','customtagvalue'=>'','urlscheme'=>false);
		$opt = array_merge($optDef,$opt);
		
		//$str = str_replace('uploads/',UPLOAD_DIR,$str);
		//$str = str_replace('../uploads/',UPLOAD_DIR,$str);	
		$str = preg_replace('/%URLSITE%/',URL_SITE,$str);	
		$str = preg_replace('/%URLSITEADMIN%/',URL_SITE_ADMIN,$str);
		$str = preg_replace('/%FOLDERSITE%/',FOLDER_SITE,$str);	
		$str = preg_replace('/%ABSURLFOLDER%/',URL_SITE.'uploads/',$str);	
		$str = preg_replace('/%AZIENDAREFERENTE%/',Core::$globalSettings['azienda referente'],$str);
		$str = preg_replace('/%AZIENDAINDIRIZZO%/',Core::$globalSettings['azienda indirizzo'],$str);
		$str = preg_replace('/%AZIENDACAP%/',Core::$globalSettings['azienda cap'],$str);
		$str = preg_replace('/%AZIENDACOMUNE%/',Core::$globalSettings['azienda comune'],$str);
		$str = preg_replace('/%AZIENDATARGA%/',Core::$globalSettings['azienda targa'],$str);
		$str = preg_replace('/%AZIENDAPROVINCIA%/',Core::$globalSettings['azienda provincia'],$str);
		$str = preg_replace('/%AZIENDAEMAIL%/',Core::$globalSettings['azienda email'],$str);
		$str = preg_replace('/%AZIENDATELEFONO%/',Core::$globalSettings['azienda telefono'],$str);
		$str = preg_replace('/%AZIENDAFAX%/',Core::$globalSettings['azienda fax'],$str);
		$str = preg_replace('/%AZIENDACELLULARE%/',Core::$globalSettings['azienda mobile'],$str);
		$str = preg_replace('/%AZIENDAMOBILE%/',Core::$globalSettings['azienda mobile'],$str);
		$str = preg_replace('/%AZIENDACODICEFISCALE%/',Core::$globalSettings['azienda codice fiscale'],$str);
		if ($opt['customtag'] != '') {
			if ($opt['customtagvalue'] != '') $str = preg_replace($opt['customtag'],$opt['customtagvalue'],$str);
			}	
			
		/* UrlScheme */
		if ($opt['urlscheme'] == true) $str = SanitizeStrings::addUrlScheme($str, $scheme = 'https://');
		return $str;	
	}		
		
	public static function encodeHtmlContent($str,$opz=array()) 
	{
		$opzDef = array('customtag'=>'','customtagvalue'=>'');
		$opz = array_merge($opzDef,$opz);
		if ($opz['customtag'] != '') {
			if ($opz['customtagvalue'] != '') $str = preg_replace('/'.$opz['customtag'].'/',$opz['customtagvalue'],$str);
		}	
		return $str;	
	}	
		
	public static function decodeHtmlContent($str,$opz=array()) 
	{
		$opzDef = array('customtag'=>'','customtagvalue'=>'');
		$opz = array_merge($opzDef,$opz);
		if ($opz['customtag'] != '') {
			if ($opz['customtagvalue'] != '') $str = preg_replace('/'.$opz['customtag'].'/',$opz['customtagvalue'],$str);
		}	
		return $str;	
	}

	public static function replaceContentTags($content,$othersTags=array(),$urlscheme=false)
	{
		$content = preg_replace('/%URLSITE%/',URL_SITE,$content);	
		$content = preg_replace('/%URLSITEADMIN%/',URL_SITE_ADMIN,$content);
		$content = preg_replace('/%FOLDERSITE%/',FOLDER_SITE,$content);	
		$content = preg_replace('/%ABSURLFOLDER%/',URL_SITE.'uploads/',$content);	
		$content = preg_replace('/%AZIENDAREFERENTE%/',Config::$globalSettings['azienda referente'],$content);
		$content = preg_replace('/%AZIENDAINDIRIZZO%/',Config::$globalSettings['azienda indirizzo'],$content);
		$content = preg_replace('/%AZIENDACAP%/',Config::$globalSettings['azienda cap'],$content);
		$content = preg_replace('/%AZIENDACOMUNE%/',Config::$globalSettings['azienda comune'],$content);
		$content = preg_replace('/%AZIENDATARGA%/',Config::$globalSettings['azienda targa'],$content);
		$content = preg_replace('/%AZIENDAPROVINCIA%/',Config::$globalSettings['azienda provincia'],$content);
		$content = preg_replace('/%AZIENDAEMAIL%/',Config::$globalSettings['azienda email'],$content);
		$content = preg_replace('/%AZIENDATELEFONO%/',Config::$globalSettings['azienda telefono'],$content);
		$content = preg_replace('/%AZIENDAFAX%/',Config::$globalSettings['azienda fax'],$content);
		$content = preg_replace('/%AZIENDACELLULARE%/',Config::$globalSettings['azienda mobile'],$content);
		$content = preg_replace('/%AZIENDAMOBILE%/',Config::$globalSettings['azienda mobile'],$content);
		$content = preg_replace('/%AZIENDACODICEFISCALE%/',Config::$globalSettings['azienda codice fiscale'],$content);
		$content = preg_replace('/%SITENAME%/',Config::$globalSettings['site name'],$content);

		// altri 
		if ( count($othersTags) > 0) {
			foreach ($othersTags AS $key=>$value) {
				$content = preg_replace('/'.$key.'/',$value,$content);
			}
		}	

		if ($urlscheme == true) $str = SanitizeStrings::addUrlScheme($content, $scheme = 'https://');
		return $content;
	}
}
?>
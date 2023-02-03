<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * classes/class.Multilanguage.php v.1.0.0. 11/02/2021 
 * */

class Multilanguage {
	private static $deflocaleSuffix = 'it';
	
	public function __construct() {
		}
				
	public static function getLocaleObjectValue($obj,$field,$localeSuffix,$opz){
		$str = '';
		$opzDef = array('filter'=>1);	
		$opz = array_merge($opzDef,$opz);		
		$rifObj = $field.$localeSuffix;
		$rifObjDef = $field.self::$deflocaleSuffix;				
		if (isset($obj->$rifObj) && $obj->$rifObj != '') {
			$str = $obj->$rifObj;
			} else if (isset($obj->$rifObjDef)) {
				$str = $obj->$rifObjDef;				
				}				
		$str = ToolsStrings::filterHtmlContent($str,$opz);
		return $str;
		}
				
	public static function getLocaleArrayValue($array,$field,$localeSuffix,$opz){
		$str = '';
		$opzDef = array('');	
		$opz = array_merge($opzDef,$opz);	
		
		
		$rifString = $field.$localeSuffix;
		$rifStringDef = $field.self::$deflocaleSuffix;
		
		if (isset($array[$rifString]) && $array[$rifString] != '') {
			$str = $array[$rifString];
		} else if (isset($array[$rifStringDef])) {
			$str = $array[$rifStringDef];				
		}
				
		$str = ToolsStrings::filterHtmlContent($str,$opz);
		return $str;
	}

	public static function getLanguageUrl($lang,$opz=array())
	{
		$opzDef = array('addlanglink'=>true);	
		$opz = array_merge($opzDef,$opz);
		$strUrl = URL_SITE;
		$url = array();
		$urlFinal = array();
		if ($opz['addlanglink'] == true) $url[] = $lang;
		if (Core::$request->action != '') $url[] = Core::$request->action;
		if (Core::$request->method != '') $url[] = Core::$request->method;
		if (Core::$request->param != '') $url[] = Core::$request->param;
		if (is_array(Core::$request->params) && count(Core::$request->params) > 0) {
			array_merge($url,Core::$request->params);	
		}
		if (is_array($url) && count($url) > 0) {
			$strUrl .= implode('/',$url);	
		}		 
		return $strUrl;
	}			
	public static function getLanguagesLinksBar($lang,$array,$opt){
		$optDef = array('template'=>'','title bar'=>'');	
	
		$opt = array_merge($optDef,$opt);
		$str = '';
		$str = $opt['template']['container'];
		$str = preg_replace('/%LANGUSER%/',$lang['user'],$str);
		$str = preg_replace('/%TITLEBAR%/',$opt['title bar'],$str);
		$str = preg_replace('/%URLSITE%/',URL_SITE,$str);	
		if (isset($opt['templateuser'])) $str = preg_replace('/%TEMPLATEUSER%/',$opt['templateuser'],$str);
		$links = '';
		foreach ($array AS $value) {
			$links .= $opt['template']['links'];
			if (isset($opt['urlsite'])) $links = preg_replace('/%URLSITE%/',$opt['urlsite'],$links);
			if (isset($opt['templateuser'])) $links = preg_replace('/%TEMPLATEUSER%/',$opt['templateuser'],$links);
			if (isset($lang['user'])) $links = preg_replace('/%LANGUSER%/',$lang['user'],$links);
			if (isset($lang['lista lingue'][$value])) $links = preg_replace('/%TITLE%/',$lang['lista lingue'][$value],$links);
			if (isset($lang['lista lingue'][$value])) $links = preg_replace('/%TTITLE%/',ucfirst($lang['lista lingue'][$value]),$links);
			
			/* genera url per la lingua */
			$strUrl = URL_SITE;
			$url = array();
			$url[] = $value;
			if (Core::$request->action != '') $url[] = Core::$request->action;
			if (Core::$request->method != '') $url[] = Core::$request->method;
			if (Core::$request->param != '') $url[] = Core::$request->param;
			if (is_array(Core::$request->params) && count(Core::$request->params) > 0) {
				array_merge($url,Core::$request->params);	
				}
			if (is_array($url) && count($url) > 0) {
				$strUrl .= implode('/',$url);	
				}		 			
			
			$links = preg_replace('/%URL%/',$strUrl,$links);
			$links = preg_replace('/%LANG%/',$value,$links);
			
			}
		
		$str = preg_replace('/%LINKS%/',$links,$str);	
			
		return $str;
		}
		
	public static function setDeflocalesuffix($value) {
		if ($value == '') $value == 'it';
		return self::$deflocaleSuffix = $value;
		}	
	}
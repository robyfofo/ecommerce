<?php
/*
	framework siti html-PHP-Mysql
	copyright 2011 Roberto Mantovani
	http://www.robertomantovani.vr;it
	email: me@robertomantovani.vr.it
	admin/classes/class.Core.php v.1.0.0. 02/03/2021
*/

class Core extends Config
{
	public static $request;
	public static $sessionValues;

	public function __construct()
	{
		parent::__construct();
		self::$sessionValues = array();
	}

	public static function init()
	{
		self::$request = new stdclass;
		self::$request->type = 'module';
		self::$request->action = Config::$globalSettings['requestoption']['defaultaction'];
		self::$request->method = '';
		self::$request->param = '';
		self::$request->params = array();
		// altre sezioni
		self::$request->page = 1;
		self::$request->lang = '';
		self::$request->templateUser = '';
		// pagina
		self::$request->page_alias = '';
		self::$request->page_id = 0;
	}

	public static function getRequest()
	{
		$reqs = (empty($_GET['request'])) ? '' : $_GET['request'];
		if (!empty($reqs)) {
			/*** le prime parti (action,method e id) ***/

			$parts = explode('/', $reqs);
			$parts = self::parseInitReqs($parts);

			self::$request->action = (isset($parts[0]) ? $parts[0] : Core::$globalSettings['requestoption']['defaultaction']);
			self::$request->method = (isset($parts[1]) ? $parts[1] : '');
			self::$request->param = (isset($parts[2]) ? $parts[2] : '');
			self::$request->params = array();
			
			//ToolsStrings::dump(self::$request);//die();

			/* prende le altre parti e le salva come params */
			if (count($parts) > 3) {
				unset($parts[0]);
				unset($parts[1]);
				unset($parts[2]);
				$pageKey = 0;
				$langKey = 0;
				foreach ($parts as $key => $value) {
					self::$request->params[] = ($value != '' ? $value : 'NULL');
					if ($value == 'pages') $pageKey = $key + 1;
					/* se trova il key memorizza */
					if ($pageKey == $key && $value != '') self::$request->page = $value;
				}
			}
		}
		/* gestisce il post */
		/* parametri post */
		if (isset($_POST['action']) && $_POST['action'] != '') self::$request->action = $_POST['action'];
		if (isset($_POST['method']) && $_POST['method'] != '') self::$request->method = $_POST['method'];
		if (isset($_POST['id']) && $_POST['id'] != '') self::$request->param = intval($_POST['id']);
		if (isset($_POST['param']) && $_POST['param'] != '') self::$request->param = $_POST['param'];
		if (isset($_POST['pages']) && $_POST['pages'] != '') self::$request->page = $_POST['page'];
		if (isset($_POST['lang']) && $_POST['lang'] != '') self::$request->lang = $_POST['lang'];
		//ToolsStrings::dump(self::$request);
		/* pulisce variabili */
		self::$request->action = SanitizeStrings::xssClean(self::$request->action);
		self::$request->method = SanitizeStrings::xssClean(self::$request->method);
		self::$request->param = SanitizeStrings::xssClean(self::$request->param);

		if (count(self::$request->params) > 0) {
			foreach (self::$request->params as $key => $value) {
				if (isset(self::$request->params[$key])) self::$request->params[$key] = SanitizeStrings::xssClean(self::$request->params[$key]);
			}
		}
		//ToolsStrings::dump(self::$request);//die();
	}

	public static function parseInitReqs($parts)
	{
		//ToolsStrings::dump($parts);

		$changeaction = false;		
		$action = (isset($parts[0]) ? $parts[0] : '');
		$method = (isset($parts[1]) ? $parts[1] : '');
		$pageaction = $action;		
		if (Core::$globalSettings['requestoption']['getlasturlparam'] == true) {
			$pageaction = end($parts);			
		}

		$userLoggedData = new stdClass();
		$userLoggedData->is_root = 0;
		if (Core::$globalSettings['requestoption']['isRoot'] == 1) $userLoggedData->is_root = 1;

		if (isset($action) && $action != '') {	

			//  controlla se il lingua
			if (in_array($action,Core::$globalSettings['languages'])) {
				self::$request->type = "lang";
				self::$request->lang = $action;			
				/* azioni da fare al cambio lingua */				
				//if (self::$sessionValues['lang'] != $action) {
					//if (class_exists('EcomBasket')) {
						//EcomBasket:: deleteBasket($opt=array());
						//}
				//}	
				unset($parts[0]);
				$parts = array_values($parts);
				
				if ($parts[0] == 'page') {
					unset($parts[0]);
					$parts = array_values($parts);
					$changeaction = true;
					$pageaction = $parts[1];					
				}
				$parts = array_values($parts);
				$action = (isset($parts[0]) ? $parts[0] : '');													
			}	

			// controlla se template
			if ($action == 'settpl') {
				if (in_array($method,Core::$globalSettings['requestoption']['templatesforusers'])) {
					self::$request->type = "template";
					self::$request->templateUser = $method;
					$parts = array();
					$action = Core::$globalSettings['requestoption']['defaultaction'];
					$changeaction == false; 
				}				
			}	

			//ToolsStrings::dump($parts);
			// toglie la page
			if (in_array('page',$parts)) {	
				$key = array_search('page',$parts);
				$key1 = $key + 1;
				if (isset($parts[$key1])) self::$request->page = $parts[$key1];
				array_splice($parts, $key,2);

			}

			//ToolsStrings::dump($parts);
			//echo 'action: '.$action;//die();
			

			if (array_key_exists($action,Config::$userModules)) {
				//ToolsStrings::dump(Config::$userModules);
				//echo '<br>e in un modulo db';
				if (array_key_exists($action,Config::$userModules)) {
 					//echo '<br>con accesso';
 					
					if (in_array($parts,Core::$globalSettings['requestoption']['methods'])) {
						Core::$request->method = $parts[0];
						array_shift($parts);
					}
					
					if (isset($parts[0])) {
						Core::$request->page_id = $parts[0];
						if (isset($parts[1])) Core::$request->page_alias = $parts[1];
					}
					
				}

			} else if (in_array($action,Core::$globalSettings['requestoption']['othermodules'])) {	
				//echo '<br>e in un modulo other';
				
				//ToolsStrings::dump($parts);
				array_shift($parts);	
				
				if (in_array($parts,Core::$globalSettings['requestoption']['methods'])) {
					Core::$request->method = $parts[0];
					array_shift($parts);
				}
				
				//ToolsStrings::dump($parts);
				if (isset($parts[0])) {
					Core::$request->page_id = intval($parts[0]);
					Core::$request->page_alias = $parts[0];
					if (isset($parts[1]) && $parts[1] != '') Core::$request->page_alias = $parts[1];
				}

				$arr = array($action);
				$parts = array_merge($arr,$parts);

			} else {
				//echo '<br>e nel modulo default';
				$action = Config::$globalSettings['requestoption']['defaultpagesmodule'];
				if (isset($parts[0])) {
					Core::$request->page_id = intval($parts[0]);
					Core::$request->page_alias = $parts[0];
					if (isset($parts[1]) && $parts[1] != '') Core::$request->page_alias = $parts[1];
				}
				$arr = array($action);
				$parts = array_merge($arr,$parts);

			}

			//ToolsStrings::dump($parts);//die();

		}

		return $parts;
	}

	public static function getRequestParam($param)
	{	
		$paramValue = '';	
		/* se è un method */
		if (self::$request->method === $param && isset(self::$request->param)) $paramValue = self::$request->param;
		/* se è un param */
		if (self::$request->param === $param && isset(self::$request->params[0])) $paramValue = self::$request->params[0];
		/* se è un params */
		if (is_array(self::$request->params) && count(self::$request->params) > 0) {
			$paramKey = -1;
			foreach (self::$request->params AS $key=>$value) {
				if ($value === $param) $paramKey = $key+1;
				/* se trova il key memorizza */
				if ($paramKey == $key && $value != '') $paramValue = $value;
				}
			}
		return $paramValue;
	}

	public static function createUrl($opt = array())
	{

		/* opzioni */
		$otherparams = (isset($opt['otherparams']) ? $opt['otherparams'] : '');
		$parampage = (isset($opt['parampage']) ? $opt['parampage'] : true);

		$url_arr = array();
		if (self::$request->action != '') {
			$url_arr[] = self::$request->action;
		}
		if (self::$request->method != '') {
			$url_arr[] = self::$request->method;
		}
		if (self::$request->param != '') {
			$url_arr[] = self::$request->param;
		}

		if (isset(self::$request->params) && is_array(self::$request->params) && count(self::$request->params) > 0) {
			$url_arr = array_merge($url_arr, self::$request->params);
		}

		// aggiungi alti parametri se presenti
		if (is_array($otherparams) && count($otherparams) > 0) {
			foreach ($otherparams as $key => $value) {
				$url_arr[] = $key;
				$url_arr[] = $value;
			}
		}

		// elimina il parametro page * per siti normali
		if ($parampage == false) {
			$key = array_search('page', $url_arr);
			unset($url_arr[$key]);
			unset($url_arr[$key + 1]);
		}

		// elimina il parametro products * per siti ecommerce
		if ($parampage == false) {
			$key = array_search('products', $url_arr);
			unset($url_arr[$key]);
			unset($url_arr[$key + 1]);
		}


		$url = URL_SITE . implode('/', $url_arr);
		return $url;
	}

	public static function setDebugMode($value)
	{
		self::$debugMode = $value;
	}

	public static function setSessionValues($value)
	{
		self::$sessionValues = $value;
	}

	public static function resetResultOp()
	{
		self::$resultOp->type =  0;
		self::$resultOp->message =  '';
		self::$resultOp->messages =  array();
	}

	public static function resetMessageToUser($value)
	{
		self::$messageToUser->type =  0;
		self::$messageToUser->message =  '';
		self::$messageToUser->messages =  array();
	}
}

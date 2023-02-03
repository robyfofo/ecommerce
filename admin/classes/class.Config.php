<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/classes/class.Config.php v.1.0.1. 31/08/2021
*/
class Config {
	static $confArray;
	public static $resultOp;
	public static $messageToUser;
	public static $debugMode;
	public static $moduleConfig;	
	public static $dbName;
	public static $databaseUsed; // locale o remoto
	public static $dbTablePrefix;
	public static $dbConfig;
	public static $globalSettings;

	public static $nowDateIso;
	public static $nowDateTimeIso;
	public static $nowTimeIso;
	public static $nowDateIta;
	public static $nowDateTimeIta;
	public static $nowTimeIta; 

	public static $defPath;
	public static $localStrings;
	public static $langUser;

	public static $modules;
	public static $userModules;
	public static $userLevels;

	public static $queryParams;
	/* 
		$queryParams = array()
		tables 							@string 	=> tebelle della query
		fields							@array 		=> campi della select
		fieldsVal						@array		=> valori campi usati nelle where
		fieldsFieldsVal					@array		=> valori campi  e dati (campo = valore) usati nelle query UPDATE
		where							@string		=> la clause usata nella query
		and								@string		=> la and da concatenare alla where

		opzioni
		onlyUserActive					@int(1)		=> aggiunge alla select users.active = 1
		onlyAssCodeUserActive           @int(1)		= aggiunge alla select associazione companies code e usente attivo

	*/

	public static $DatabaseTables;
	public static $DatabaseTablesFields;

	private static $shopSessionId; 
	private static $shopSessionToken;  

	public function __construct(){	
	}	

	public static function init() 
	{
		self::$resultOp =  new stdclass;
		self::$resultOp->type = 0;
		self::$resultOp->error =  0;
		self::$resultOp->message =  '';
		self::$resultOp->messages =  array();	
		self::$messageToUser =  new stdclass;
		self::$messageToUser->type =  0;
		self::$messageToUser->message =  '';
		self::$messageToUser->messages =  array();
		self::$debugMode = 0;
		self::$databaseUsed = DATABASEUSED;

		self::$nowDateIso = date('Y-m-d');
		self::$nowDateTimeIso = date('Y-m-d H:i:s');
		self::$nowTimeIso = date('H:i:s');
		self::$nowDateIta = date('d/m/Y');
		self::$nowDateTimeIta = date('d/m/Y H:i:s');
		self::$nowTimeIta = date('H:i:s'); 
		
		self::$defPath = PATH;
		self::$localStrings = array();
		self::$langUser = 'it';

		self::$shopSessionId = ''; 
		self::$shopSessionToken = '';  

		self::$dbTablePrefix = self::$globalSettings['database'][self::$databaseUsed]['tableprefix'];

		self::$queryParams = array();


		// legge la tabella settings
		Sql::initQueryBasic(self::$dbTablePrefix.'settings',array('keyword,value'),array(),'','','');
		$pdoObject = Sql::getPdoObjRecords();	
		if (Core::$resultOp->error > 0) { die('errore lettura settings'); }
		while ($row = $pdoObject->fetch()) {
			self::$globalSettings[$row->keyword] = $row->value;
		}

		// carica i dati modulo
		foreach(Core::$globalSettings['module sections'] AS $key=>$value) {
			Sql::initQuery(Config::$dbTablePrefix.'modules',array('*'),array($key),'active = 1 AND section = ?','ordering ASC');
			self::$modules[$key] = Sql::getRecords();
			if (self::$resultOp->error == 1) die('Errore db livello utenti!');
		}

		// carica i moduli utente
		self::$userModules = Permissions::getUserModules();

		// carica i livelli utente
		self::$userLevels = Permissions::getUserLevels();

	}

	public static function initDatabaseTables($path = '') 
	{
		// carica file 
		if (file_exists($path."admin/include/configuration_database_core_structure.php")) {
			include_once($path."admin/include/configuration_database_core_structure.php");
		} else {
			die('il file '.$path.'admin/include/configuration_database_core_structure.php non esiste!');
		}

		if (file_exists($path."admin/include/configuration_database_modules_structure.php")) {
			include_once($path."admin/include/configuration_database_modules_structure.php");
		} else {
			die('il file '.$path.'admin/include/configuration_database_modules_structure.php non esiste!');
		}
		self::$DatabaseTables = $DatabaseTables;
		self::$DatabaseTablesFields = $DatabaseTablesFields;	
	}
	
	public static function loadLanguageVars($currentlanguage)
	{
		
		if ($currentlanguage != '') {
			if (file_exists(PATH."admin/languages/".$currentlanguage.".inc.php")) {
				include_once(PATH."admin/languages/".$currentlanguage.".inc.php");
			} else {
				include_once(PATH."admin/languages/it.inc.php");
			}			
			if (file_exists(PATH."languages/".$currentlanguage.".inc.php")) {
				include_once(PATH."languages/".$currentlanguage.".inc.php");
			} else {
				include_once(PATH."languages/it.inc.php");
			}			
		} else {
			include_once(PATH."admin/languages/it.inc.php");
			include_once(PATH."languages/it.inc.php");
		}
		self::$localStrings = $localStrings;
		self::$langUser = $localStrings['user'];
	}
	
	public static function loadLanguageVarsAdmin($currentlanguage)
	{
		if ($currentlanguage != '') {
			if (file_exists(PATH."languages/".$currentlanguage.".inc.php")) {
				include_once(PATH."languages/".$currentlanguage.".inc.php");
			} else {
				include_once(PATH."languages/it.inc.php");
			}					
		} else {
			include_once(PATH."languages/it.inc.php");
		}
		self::$localStrings = $localStrings;
	}

	public static function checkModuleConfig($table,$configs) {	
		if (Sql::tableExists($table) == true) {
		/* legge la configurazione */
		self::$moduleConfig = new stdClass();
		Sql::initQuery($table,array('*'),array(),'active = 1');
		Sql::setOptions(array('fieldTokeyObj'=>'name'));
		self::$moduleConfig = Sql::getRecords();
		
		/* controlla se ci sono i parametri richiesti */
		if (is_array($configs) && count($configs) > 0) {
			foreach ($configs AS $value) {
				if (!isset(self::$moduleConfig[$value['name']]) || (isset(self::$moduleConfig[$value['name']]) && self::$moduleConfig[$value['name']]->name == '')) {
					self::$resultOp->error = 1;
					self::$resultOp->messages[] = 'Il parametro di configurazione "'.$value['name'].'" non è presente oppure è vuoto!';
					}
				}
			}
		
		if (self::$resultOp->type == 1) {
			self::$resultOp->error = 1;
			self::$resultOp->messages[] = 'La tabella della configurazione non è presente!';
			} else {
				/* controlla se ci sono presenti le configurazioni */
				}
		} else {
			self::$resultOp->error = 1;
			self::$resultOp->messages[] = 'La tabella della configurazione non è presente!';
			}
		}

	public static function read($name) {
		return self::$confArray[$name];
		}
	
	public static function write($name, $value) {
		self::$confArray[$name] = $value;
		}
	public static function setGlobalSettings($globalSettings) {
		self::$globalSettings = $globalSettings;	
		}
		
	public static function getDatabaseSettings() {
		$dbConfig = self::$globalSettings['database'][self::$databaseUsed];
		return $dbConfig;
	}
		
	public static function setShopSessionId($value) {
		self::$shopSessionId = $value;
	}

	public static function setShopSessionToken($value) {
		self::$shopSessionToken = $value;
	}

	public static function getShopSessionId() {
		return self::$shopSessionId;
	}

	public static function getShopSessionToken() {
		return self::$shopSessionToken;
	}

	public static function initQueryParams()
	{
		self::$queryParams = array();
		self::$queryParams['tables'] = '';
		self::$queryParams['fields'] = array();
		self::$queryParams['fieldsVal'] = array();
		self::$queryParams['fieldsFieldsVal'] = array();
		self::$queryParams['where'] = '';
		self::$queryParams['and'] = '';
	}
}
?>
<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * class.Permissions.php v.1.0.0. 16/06/2021
*/

class Permissions extends Core {
	
	static $accessModules = array();
	static $userModules = array();
	
	private static $dbTableModules;
	
	public function __construct() {
		parent::__construct();		
	}
	
	public static function dbgetUserModules(){	
		//Sql::setDebugMode(1);
		// carica array access modules
		$table = Config::$dbTablePrefix.'modules';
		$fields = array('*');
		Sql::initQuery($table,$fields,array(),'active = 1','');
		Sql::setOptions(array('fieldTokeyObj'=>'name'));
		self::$userModules = Sql::getRecords();	
		//print_r(self::$userModules);
	}
	
	
	public static function getUserModules(){	
		self::dbgetUserModules();
		return self::$userModules;		
	}
	
	public static function dbgetUserLevelModulesRights($user){	
		// carica array access modules
		//echo '<br>carica db array access modules';
		$levels_id = (isset($user->id_level) ? $user->id_level : 0);
		$table = Config::$dbTablePrefix.'modules_levels_access AS a INNER JOIN '.Config::$dbTablePrefix.'modules AS m ON (a.modules_id = m.id)';
		$fields = array('a.id AS id, a.read_access AS read_access, a.write_access AS write_access,m.name AS module');
		Sql::initQuery($table,$fields,array($levels_id),'a.levels_id = ? AND m.active = 1','');
		Sql::setOptions(array('fieldTokeyObj'=>'module'));
		self::$accessModules = Sql::getRecords();	
		//print_r(self::$accessModules);
	}
		
		
	public static function getLevelModulesRights($levels_id) {
		$table = Config::$dbTablePrefix.'modules_levels_access AS mla INNER JOIN '.Config::$dbTablePrefix.'modules AS m ON (mla.modules_id = m.id)';
		$fields = array('mla.*,m.name AS module_name');
		Sql::initQuery($table,$fields,array($levels_id),'mla.levels_id = ?','');
		Sql::setOptions(array('fieldTokeyObj'=>'module_name'));
		$obj = Sql::getRecords();	
		return $obj;
	
	}

		
	public static function getUserLevelModulesRights($user){	
		self::dbgetUserLevelModulesRights($user);
		return self::$accessModules;		
	}
	
	
	public static function checkIfModulesIsReadable($module,$user,$checkUser = true){	

		//ToolsStrings::dump(self::$accessModules);
		$result = false;		
		if (isset($user->is_root) && $user->is_root == 1) {
			$result = true;
		} 
		else {
			if ($checkUser == true) 
			{
				if (isset(self::$accessModules[$module]->read_access) && self::$accessModules[$module]->read_access == 1) {
					$result = true;
				}
			}
			else
			{
				$result = true;
			}
		}			
		// aggiunge il controllo sul core
		if (in_array($module,self::$globalSettings['requestoption']['coremodules'])) {
			$result = true;
		}
       
		return $result;
	}
	
	public static function checkIfUserModuleIsActive($module) {
		//print_r(self::$userModules);die();
		//print_r(self::$globalSettings['requestoption']);die();
		$result = false;	
		if (array_key_exists($module,self::$userModules )) {
			$result = true;
		}
		if (in_array($module,Config::$globalSettings['requestoption']['othermodules'])) {
			$result = true;
		}
		return $result;	
	}
	
	public static function checkIfModulesIsWritable($module,$user){	
		$result = false;		
		if (isset($user->is_root) && $user->is_root == 1) {
			$result = true;
		} else {
			if (isset(self::$accessModules[$module]->write_access) && self::$accessModules[$module]->write_access == 1) {
				$result = true;
			}
		}			
		return $result;
	}
	
	// OLD 
	
	public static function getUserLevels(){		
		Sql::initQuery(Config::$dbTablePrefix.'levels',array('*'),array(),'active = 1','title ASC');
		//Sql::setOptions(array('fieldTokeyObj'=>'id'));
		$obj1 = Sql::getRecords();
		$obj2[0] = (object)array('id'=>0,'title'=>'Anonimo','modules'=>'','active'=>1);
		$obj = array_merge($obj2,$obj1);
		return $obj;		
	}
		
	public static function getUserLevelLabel($id_level,$is_root=0) {
		$s = '';
		if($is_root == 1) {
			$s = 'Root';
		} else {
			//$s .= $id_level;
			if (is_array(Config::$userLevels) && count(Config::$userLevels) > 0) {
				foreach(Config::$userLevels AS $value) {
					if ($value->id == $id_level) {
						$s = $value->title;
						break;
					}
				}
			}
		}
		return $s;
	}	
}
?>
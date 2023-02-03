<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/classes/class.Menu.php v.1.0.0. 05/07/2021
*/

class Menu extends Core {

	public static $output = '';
	public static $level = 0;
	public static $colum = array();
	public static $subItems = 0;
	public static $treeData = '';
	
	// new
	private static $dbTable = 'menu';
	
	public static $optOrdering = 'm.ordering ASC';
	public static $optInitParent = 0;
	public static $optHideId = 0;
	public static $optHideSons = 0;
	public static $optRifId = '';
	public static $optRifIdValue = 0;
	public static $optGetBreadcrumbs = 0;
	public static $optLevelString = '<i class="fas fa-chevron-right"></i>';
	public static $optHideInactive = false;

	public static $typeAlias = '';
	
	public function __construct() 	{
		parent::__construct();
	}
	
	public static function createMenuOutput($obj,$parent,$opt) 
	{	
		$optDef = array(
			'modulesmenu'		=> array(),
			'ulIsMain'			=> 0,
			'ulMain'			=> '<ul>',
			'ulSubMenu'			=> '<ul>',
			'ulDefault'			=> '<ul>',
			'liMain'			=> '<li>',
			'liSubMenu'				=> '<li>',
			'liSubMenuNoSon' 		=> '<li>',
			'liSubSubMenu'		=> '<li>',
			'liDefault'			=> '<li>',
			'hrefMain'			=> '<a>',
			'hrefSubMenu'		=> '<a>',
			'hrefDefault'		=> '<a>',
			'lang'				=> 'it',
			'urlDefault'		=> '#!',
			'pagesModule'		=> 'pages/',
			'valueUrlDefault'	=> '%ID%/%SEOCLEAN%',
			'titleField'		=>'',
			'activeMenuAlias'	=>''
			); 
		$opt = array_merge($optDef,$opt);

		$has_children = false;
		if (is_array($obj) && count($obj) > 0) {
			foreach($obj AS $key=>$value) {				
				if (intval($value->parent) == $parent) { 	
				
					/* per menu module */
					if ($value->type == 'module-menu' && $value->type != '') {
						$alias = $value->alias;
					
						if (is_array($opt['modulesmenu']) && count($opt['modulesmenu']) > 0) {
							foreach($opt['modulesmenu'] AS $mmkey=>$mmvalue) {
								//echo '<br>module: #'.$mmkey.'#';
								//echo '<br>replaces: #'.$mmvalue['replace'].'#';
								$alias = preg_replace($mmvalue['replace'],$mmvalue['values'],$alias);
							}
						}
						$alias = preg_replace('/%SUBMODULEMENULABEL%/',$value->title,$alias);
						self::$output .= $alias;
						
					} else {
					
						$ul = $opt['ulDefault'];
						$li = $opt['liDefault'];
						$href = $opt['hrefDefault'];						    
						if ($has_children === false) {
							$has_children = true;											
							if (self::$level == 0) $ul = $opt['ulMain'];
							if (self::$level == 1) $ul = $opt['ulSubMenu'];	
							if (self::$level > 1) $ul = $opt['ulSubSubMenu'];	
							$ul = preg_replace('/%ACTIVEPAGE%%/',$opt['activeMenuAlias'],$ul);						
		            		if (self::$level > $opt['ulIsMain']) self::$output .= $ul.PHP_EOL;  
		     			}								
						/* gestione tag dinamici */
						if (self::$level == 0) $li = $opt['liMain'];
						if (self::$level > 0 && $value->sons == 0) $li = $opt['liSubMenu']; 
						if (self::$level == 0 && $value->sons > 0) $li = $opt['liSubMenu'];
						if (self::$level > 0 && $value->sons == 0)  $ul = $opt['liDefault'];
						if (self::$level > 0 && $value->sons == 0) $li = $opt['liSubMenuNoSon'];
						if (self::$level > 0 && $value->sons > 0) $li = $opt['liSubSubMenu'];

						if (self::$level == 0 && $value->sons == 0) $href = $opt['hrefMain'];
						if (self::$level == 0 && $value->sons > 0) $href = $opt['hrefSubMenu'];
						if (self::$level > 0 && $value->sons > 0) $href = $opt['hrefSubSubMenu'];
						
						//echo 'alias: '.$value->alias;
		       			if (self::$level == 0 && $value->alias == $opt['activeMenuAlias']) {
		        			$li = preg_replace('/%CLASSACTIVE%/',' active',$li);
		  				} else {
							$li = preg_replace('/%CLASSACTIVE%/','',$li);
						}
		    		
						/* crea url */
						$hrefUrl = $opt['urlDefault'];
						if (isset($value->type)) {
			  				$hrefUrl = self::getUrlFromType($value,$value->title,$opt);
						}           
		 				$li = preg_replace('/%URL%/',$hrefUrl,$li);
		 				$li = preg_replace('/%URLTITLE%/',$value->title,$li);
		 				$li = preg_replace('/%TITLE%/',$value->title,$li);
		 				$href = preg_replace('/%URL%/',$hrefUrl,$href);
		 				$href = preg_replace('/%URLTITLE%/',$value->title,$href);
		 				$href = preg_replace('/%TITLE%/',$value->title,$href);
		 					
						if (self::$level == 0 && $value->alias == $opt['activeMenuAlias']) {
							$href = preg_replace('/%CLASSACTIVE%/',' active',$href);
						} else {
							$href = preg_replace('/%CLASSACTIVE%/','',$href);
						}
						
						$href = preg_replace('/%TARGET%/',$value->target,$href);
						
						self::$output .= $li.PHP_EOL;
						self::$output .= $href.PHP_EOL;
						self::$output = preg_replace('/%LEVEL%/',self::$level,self::$output);
						self::$output = preg_replace('/%SONS%/',$value->sons,self::$output);
						$id = intval($value->id);
						self::$level++;	
							self::createMenuOutput($obj,$id,$opt); 
						self::$level--;	
															
						self::$output .= '</li>'.PHP_EOL;	
					
					}
			
				}	 		
			}
		}
		if ($has_children === true && self::$level > $opt['ulIsMain']) self::$output .= '</ul>'.PHP_EOL;
		return self::$output;
	}	
		

	public static function getUrlFromType($value,$title,$opt) {
		switch($value->type) {
			case 'label':
				$url = $opt['urlDefault'];						
			break;
			case 'module-link':
				$url = URL_SITE.$value->url;
			break;	
			case 'modulemenu':
				$url = 'javascript:void(0);';
			break;		
			default:		      
	  			$url = $value->url;
			break;
	  	}
	  		
	  	$parentstring = '';
	  	/* trova il parent alias */
	  	$parentalias = '';
	  	if ($value->parent > 0) {
	  		if (isset($value->breadcrumbs[0]['alias'])) $parentalias = $value->breadcrumbs[0]['alias'].'/';
	  	}
		
		$url = preg_replace('/%URLSITE%/',URL_SITE,$url);	
		$url = preg_replace('/%ID%/',$value->id,$url);
		//$url = preg_replace('/%ALIAS%/',$value->alias,$url);
		$url = preg_replace('/%SEO%/',$title,$url);
		$url = preg_replace('/%SEOCLEAN%/', SanitizeStrings::urlslug($title,array('delimiter'=>'-')),$url);
		$url = preg_replace('/%SEOENCODE%/', urlencode($title),$url);
		$url = preg_replace('/%TITLE%/', urlencode($title),$url); 
		$url = preg_replace('/%PARENTSTRING%/', urlencode($parentstring),$url);
		$url = preg_replace('/%PARENTALIAS%/', $parentalias,$url);
				
		return $url;
	}
	
	
	/* SQL QUERIES */
	public static function getObjFromMenus() {	
		
		$dbTable = self::$dbTablePrefix.self::$dbTable;				
		$qry = "
			SELECT 
			m.id AS id,
			m.parent AS parent,
			m.alias AS alias,
			m.title".Config::$localStrings['db_prefix']." AS title_locale,
			m.alias AS alias,
			m.ordering AS ordering,
			m.active AS active,
			m.type As type,
			m.target As target,
			m.menu_type_alias As menu_type_alias,
			m.url AS url,
			";
		
		foreach (Config::$globalSettings['languages'] AS $lang) {
			$qry .= 'm.title_'.$lang.' AS title_'.$lang.',';
		}	
		
		$qry .= "m.filename AS filename,m.org_filename AS org_filename,";
		
			
		foreach (Config::$globalSettings['languages'] AS $lang) {
			$qry .= '(SELECT p.title_'.$lang.' FROM '.$dbTable.' AS p WHERE m.parent = p.id)  AS titleparent_'.$lang.',';
		}			
		$qry .= "(SELECT COUNT(id) FROM ".$dbTable." AS s WHERE s.parent = m.id)  AS sons";	
		$qry .= " FROM ".$dbTable." AS m
		WHERE m.parent = :parent";

		if (self::$optHideInactive == true) {
			$qry .= " AND m.active = 1";
		}

		if (self::$typeAlias !== '') {
			$qry .= " AND m.menu_type_alias = '".self::$typeAlias."'";
		}
		

		$qry .= " ORDER BY ".self::$optOrdering;
		
		//echo $qry;//die('fatto');
			
		$obj = '';
		Sql::resetListTreeData();
		Sql::resetListDataVar();
		$opt = array(
		'orgQry'					=>	$qry,
		'qryCountParentZero'		=>	"SELECT id FROM ".$dbTable." WHERE parent = 0",
		'fieldKey'					=>	'alias',
		'hideId'					=>	self::$optHideId,
		'hideSons'					=>	self::$optHideSons,
		'rifIdValue'				=>	self::$optRifIdValue,
		'rifId'						=>	self::$optRifId,
		'getbreadcrumbs'			=>	self::$optGetBreadcrumbs,
		'levelString'				=>	self::$optLevelString
		);

		Sql::setListTreeData($qry,self::$optInitParent,$opt);		
		$obj = Sql::getListTreeData();
		return $obj;		
	}

	public static function resetOutput() {
		self::$output = '';	
	}
	
	public static function setDbTable($value) {
		self::$dbTable = $value;	
	}
}
?>
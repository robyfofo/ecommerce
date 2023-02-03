<?php
/*
	framework siti html-PHP-Mysql
	copyright 2011 Roberto Mantovani
	http://www.robertomantovani.vr;it
	email: me@robertomantovani.vr.it
	admin/classes/class.Pages.php v.1.0.0. 01/07/2021
*/

class Pages extends Core {

	public static $output = '';
	public static $level = 0;
	public static $colum = array();
	public static $subItems = 0;
	public static $treeData = '';	
	private static $mainData;
	private static $treeLevel = 0;
	private static $optSqlOptions = '';
	private static $optImageFolder = 'pages/';
		
	// new
	private static $dbTable = 'pages';
	private static $dbTableBlocks = 'pages_blocks';
	private static $dbTableTemplates = 'pagetemplates';
	
	public static $optOrdering = 'p.ordering DESC';
	public static $optInitParent = 0;
	public static $optHideId = 0;
	public static $optHideSons = 0;
	public static $optRifId = '';
	public static $optRifIdValue = 0;
	public static $optGetBreadcrumbs = 0;
	public static $optLevelString = '<i class="fas fa-chevron-right"></i>';
	public static $optHideInactive = false;
	public static $optShowInMenuOnly = false;

	
	public function __construct() 	{
		parent::__construct();
	}
	
	
	public static function createMenuFromSubPages($obj,$parent,$opt) 
	{	
		$optDef = array(
			'ulIsMain'=>0, 
			'ulMain'=>'<ul>', 
			'ulSubMenu'=>'<ul>',	 
			'ulDefault'=>'<ul>',	 
			'liMain'=>'<li>', 
			'liSubMenu'=>'<li>',
			'liSubMenuNoSon' 		=> '<li>',
			'liSubSubMenu'=>'<li>',
			'liDefault'=>'<li>',
			'hrefMain'=>'<a>', 'hrefSubMenu'=>'<a>', 'hrefDefault'=>'<a>', 'lang'=>'it', 'urlDefault'=>'#!', 'pagesModule'=>'pages/', 'valueUrlDefault'=>'%ID%/%SEOCLEAN%', 'titleField'=>'', 'activepage'=>'pages'
			); 
		$opt = array_merge($optDef,$opt);
		$has_children = false;
		//print_r($obj);
		if (is_array($obj) && count($obj) > 0) {
			foreach($obj AS $key=>$value) {				
				if (intval($value->parent) == $parent) { 	
					$ul = $opt['ulDefault'];
					$li = $opt['liDefault'];
					$href = $opt['hrefDefault'];
					
					/* crea voce mnu */
					if ($value->menu > 0) {	
								
						if ($has_children === false) {
							$has_children = true;											
							if (self::$level == 0) $ul = $opt['ulMain'];
							if (self::$level == 1) $ul = $opt['ulSubMenu'];	
							if (self::$level > 1) $ul = $opt['ulSubSubMenu'];	
							$ul = preg_replace('/%ACTIVEPAGE%%/',$opt['activepage'],$ul);						
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
						if (self::$level == 0 && $value->alias == $opt['activepage']) {
							$li = preg_replace('/%CLASSACTIVE%/',' active',$li);
						} else {
							$li = preg_replace('/%CLASSACTIVE%/','',$li);
						}	

						/* crea titles */
						$titlesVal = self::getTitlesVal($value,$opt);          		
						/* crea url */
						$hrefUrl = $opt['urlDefault'];					
						$hrefUrl = self::getUrlFromPageType($value,$titlesVal,$opt);	

						$li = preg_replace('/%URL%/',$hrefUrl,$li);
						$li = preg_replace('/%URLTITLE%/',$titlesVal['titleSeo'],$li);
						$li = preg_replace('/%TITLE%/',$titlesVal['title'],$li);
						$href = preg_replace('/%URL%/',$hrefUrl,$href);
						$href = preg_replace('/%URLTITLE%/',$titlesVal['titleSeo'],$href);
						$href = preg_replace('/%TITLE%/',$titlesVal['title'],$href);
						
						if (self::$level == 0 && $value->alias == $opt['activepage']) {
						$href = preg_replace('/%CLASSACTIVE%/',' active',$href);
						} else {
							$li = preg_replace('/%CLASSACTIVE%/','',$li);
						}	 					
						self::$output .= $li.PHP_EOL;
						self::$output .= $href.PHP_EOL;	 					
						self::$output = preg_replace('/%LEVEL%/',self::$level,self::$output);
						self::$output = preg_replace('/%SONS%/',$value->sons,self::$output);	 					    					
						$id = intval($value->id);
						self::$level++;	
						self::createMenuFromSubPages($obj,$id,$opt); 
						self::$level--;										
						self::$output .= '</li>'.PHP_EOL;
					}
	 			}	 		
		 	}
		}
		if ($has_children === true && self::$level > $opt['ulIsMain']) self::$output .= '</ul>'.PHP_EOL;
		return self::$output;
	}	
				
	
	public static function getTitlesVal($value,$opt) {
		$titlesVal = array();
		$fieldTitle = 'title_';
 		$fieldTitleSeo = 'title_seo_';
 		$fieldTitleMeta = 'title_meta_';         		        		
 		//if ($opt['titleField'] != '')  $fieldTitle = rtrim($opt['titleField'] ,'_').'_'.$langSuffix;				 
 		/* gestione multilingua */  
 		$titlesVal['title'] = Multilanguage::getLocaleObjectValue($value,$fieldTitle,$opt['lang'],array());
 		$titlesVal['titleSeo'] = Multilanguage::getLocaleObjectValue($value,$fieldTitleSeo,$opt['lang'],array());
 		$titlesVal['titleMeta'] = Multilanguage::getLocaleObjectValue($value,$fieldTitleMeta,$opt['lang'],array()); 
		return $titlesVal;	
	}
	
	
	public static function getUrlFromPageType($value,$titlesVal,$opt) {	
		$url = URL_SITE.$opt['pagesModule'].$opt['valueUrlDefault'];
		if ($value->is_label == 1) {
			$url = $opt['urlDefault'];	
		} else {
			if ($value->url != '') $url = $value->url;	
		}
		 		
	  	$parentstring = '';
	  	/* trova il parent alias */
	  	$parentalias = '';
	  	if ($value->parent > 0) {
	  		if (isset($value->breadcrumbs[0]['alias'])) $parentalias = $value->breadcrumbs[0]['alias'].'/';
	  	}
		
		$url = preg_replace('/%URLSITE%/',URL_SITE,$url);	
		$url = preg_replace('/%ID%/',$value->id,$url);
		$url = preg_replace('/%ALIAS%/',$value->alias,$url);
		$url = preg_replace('/%SEO%/',$titlesVal['titleSeo'],$url);
		$url = preg_replace('/%SEOCLEAN%/', SanitizeStrings::urlslug($titlesVal['titleSeo'],array('delimiter'=>'-')),$url);
		$url = preg_replace('/%SEOENCODE%/', urlencode($titlesVal['titleSeo']),$url);
		$url = preg_replace('/%TITLE%/', urlencode($titlesVal['titleSeo']),$url); 
		$url = preg_replace('/%PARENTSTRING%/', urlencode($parentstring),$url);
		$url = preg_replace('/%PARENTALIAS%/', $parentalias,$url);
				
		return $url;
	}
		
	/* SQL QUERIES */
	
	public static function getObjFromPages() {	
	  		
		$dbTable = Config::$dbTablePrefix.self::$dbTable;
		$dbTableBlocks = Config::$dbTablePrefix.self::$dbTableBlocks;
		$dbTableTemplates = Config::$dbTablePrefix.self::$dbTableTemplates;
		$languages = self::$globalSettings['languages'];		
		
		$qry = 'SELECT 
				p.id AS id,
				p.parent AS parent,
				p.alias AS alias,
				p.title'.Config::$localStrings['db_prefix'].' AS title_locale,
				p.title_seo'.Config::$localStrings['db_prefix'].' AS title_seo_locale,
				p.menu AS menu,
				p.is_label AS is_label,
				p.url AS url,
				p.target AS target,
				p.id_template AS id_template,
				p.org_filename AS org_filename,				
				p.ordering AS ordering,
				p.active AS active,';
				
		foreach(Config::$globalSettings['languages'] AS $lang) {
			$qry .= "p.title_seo_".$lang." AS title_seo_".$lang.",
				p.meta_title_".$lang." AS meta_title_".$lang.",
				p.title_".$lang." AS title_".$lang.",
			";
		}
		
		foreach ($languages AS $lang) {
			$qry .= 'p.title_'.$lang.' AS title_'.$lang.',';
		}	
				
		$qry .= "(SELECT COUNT(i.id) FROM ".$dbTableBlocks." AS i WHERE i.id_owner = p.id) AS blocks,";	
		
		$qry .= "(SELECT tp.title FROM ".$dbTableTemplates." AS tp WHERE p.id_template = tp.id)  AS template_name,";

		foreach ($languages AS $lang) {
			$qry .= '(SELECT p.title_'.$lang.' FROM '.$dbTable.' AS pp WHERE p.parent = pp.id)  AS titleparent_'.$lang.',';
		}			
		$qry .= "(SELECT COUNT(id) FROM ".$dbTable." AS s WHERE s.parent = p.id)  AS sons";	
		$qry .= " FROM ".$dbTable." AS p
		WHERE p.parent = :parent";
		if (self::$optHideInactive == true) {
			$qry .= " AND p.active = 1";
		}
		if (self::$optShowInMenuOnly == true) {
			$qry .= " AND p.menu = 1";
		}

		$qry .= " ORDER BY ".self::$optOrdering;
		
		//echo $qry; //die('fatto');
		
			
		$obj = '';
		Sql::resetListTreeData();
		Sql::resetListDataVar();
		
		$opt = array(
		'orgQry'					=>	$qry,
		'qryCountParentZero'		=>	"SELECT id FROM ".$dbTable." WHERE parent = 0",
		'fieldKey'					=>	'',
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
	
	public static function setDbTableBlocks($value) {
	    self::$dbTableBlocks = $value;
	}
	
	public static function setDbTableTemplates($value) {
	    self::$dbTableTemplates = $value;
	}
	
	public static function setOptSqlOptions($value) {
	    self::$optSqlOptions = $value;
	}
	
	public static function setOptImageFolder($value) {
	    self::$optImageFolder = $value;
	}
	
	public static function getMainData() {
		return self::$mainData;
    }
    
    public static function addCustomFields($obj) {		
		$field = 'title'.Config::$localStrings['db_prefix'];	
		$obj->title_locale = $obj->$field;
		$obj->title =  Multilanguage::getLocaleObjectValue($obj,'title_',Config::$localStrings['user'],array());
		$obj->title_seo = Multilanguage::getLocaleObjectValue($obj,'title_seo_',Config::$localStrings['user'],array());
		$obj->summary = Multilanguage::getLocaleObjectValue($obj,'summary_',Config::$localStrings['user'],array());
		$obj->summary_nop = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $obj->summary);
		$obj->content = Multilanguage::getLocaleObjectValue($obj,'content_',Config::$localStrings['user'],array());
		$obj->content_nop = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $obj->content);
		$obj->meta_title =  Multilanguage::getLocaleObjectValue($obj,'meta_title_',Config::$localStrings['user'],array());	
		$obj->meta_description =  Multilanguage::getLocaleObjectValue($obj,'meta_description_',Config::$localStrings['user'],array());
		$obj->meta_keyword = Multilanguage::getLocaleObjectValue($obj,'meta_keyword_',Config::$localStrings['user'],array());
		$obj->url = URL_SITE.'pages/dt/'.$obj->id.'/'.SanitizeStrings::urlslug($obj->title,array('deliliter'=>'-'));	
		$obj->image_url = UPLOAD_DIR.self::$optImageFolder.$obj->filename;					
		return $obj;	
	}
}
?>
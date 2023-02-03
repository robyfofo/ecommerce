<?php
/*
	framework siti html-PHP-Mysql
	copyright 2011 Roberto Mantovani
	http://www.robertomantovani.vr;it
	email: me@robertomantovani.vr.it
	admin/classes/class.Subcategories.php v.1.0.0. 04/03/2021
*/

class Subcategories extends Core {	
	private static $mainData;
	private static $treeLevel = 0;
	private static $colummsOutput = array();
	public static $output = '';

	public static $optOrdering = 'c.ordering DESC';
	public static $optInitParent = 0;
	public static $optHideId = 0;
	public static $optHideSons = 0;
	public static $optRifId = '';
	public static $optRifIdValue = 0;
	public static $langUser = 'it';
	public static $optGetBreadcrumbs = 0;
	public static $optLevelString = '<i class="fas fa-chevron-right"></i>';
	public static $optGetNumProductds = 0;
	public static $optSqlOptions = '';
	public static $optImageFolder = 'warehouse/categories/';
	public static $optFieldKey = 'id';

	
	public function __construct(){
		parent::__construct();
	}

	public static function getMegamenuColumsData($colums) {
		//Sql::setDebugMode(1);
		//self::$colummsOutput = array();
		$languages = self::$globalSettings['languages'];
		// preleva le catecorie principali
		$fields = array(
			'c.id AS id',
			'c.parent AS parent',
			'c.alias AS alias',
			'c.title_'.self::$langUser.' AS title_locale',
			'c.title_seo_'.self::$langUser.' AS title_seo_locale',
			'c.alias AS alias',
			'c.filename AS filename',
			'c.org_filename AS org_filename',
			'c.ordering AS ordering',
			'c.active AS active'
		);
		foreach ($languages AS $lang) {
			$fields[] = 'c.title_'.$lang.' AS title_'.$lang;
		}		
		$fields[] = "(SELECT COUNT(i.id) FROM ".Config::$DatabaseTables['warehouse products']." AS i WHERE i.categories_id = c.id) AS items";		
		foreach ($languages AS $lang) {
			$fields[] = '(SELECT p.title_'.$lang.' FROM '.Config::$DatabaseTables['warehouse categories'].' AS p WHERE c.parent = p.id)  AS titleparent_'.$lang;
		}			
		$fields[] = "(SELECT COUNT(id) FROM ".Config::$DatabaseTables['warehouse categories']." AS s WHERE s.parent = c.id)  AS sons";	
		Sql::initQuery(Config::$DatabaseTables['warehouse categories'].' AS c',$fields,array(),'c.parent = 0','c.ordering ASC');
		$mainCategories = Sql::getRecords();
		//ToolsStrings::dump($mainCategories);die();
		if (is_array($mainCategories) && count($mainCategories) > 0) {
			$col = 1;
			$mainCat = 1;
			foreach ($mainCategories AS $key=>$value) {
				//Sql::setDebugMode(1);
				self::$colummsOutput[$col]['main'][$key] = $value;
				//preleva tutte le sotto categorie della principale
				Sql::initQuery(Config::$DatabaseTables['warehouse categories'].' AS c',$fields,array($value->id),'c.parent = ?','c.ordering DESC');
				$foo = Sql::getRecords();
				//ToolsStrings::dump($foo);
				if (is_array($foo) && count($foo) > 0) {
					//self::$colummsOutput[$col][$key]['sub'] = array();
 					self::$colummsOutput[$col]['sub'][$key] = $foo;
				}
				$col++;
				$mainCat++;
				if ($col > $colums) {
					$col = 1;
					$mainCat = 1;
				}
			}
		}
	}

	public static function createMegamenuOutput($colums = 3,$opt) 
	{
		$optDef = array(
			'viewProduct'			=> true,
			'colsContainer'			=> array(
				'1'					=> '<div class="col-sm-6 col-lg-2 g-mb-30 g-mb-0--md">%ITEMS%</div>',
				'2'					=> '<div class="col-sm-6 col-lg-3 g-mb-30 g-mb-0--md">%ITEMS%</div>',
				'3'					=> '<div class="col-sm-6 col-lg-3 g-mb-30 g-mb-0--md">%ITEMS%</div>',
				'4'					=> '<div class="col-sm-6 col-lg-3 g-mb-30 g-mb-0--md">%ITEMS%</div>',
			)
		);
		$opt = array_merge($optDef,$opt);
		self::getMegamenuColumsData($colums);
		$subOutput = '';
		if (is_array(self::$colummsOutput) && count(self::$colummsOutput) > 0) {
			foreach (self::$colummsOutput AS $kcols=>$cols) {

				if ($kcols == 1 && $opt['viewProduct'] == true) {
					$subOutput .= $opt['colsContainer'][1];
				} else {
					if (isset($opt['colsContainer'][$kcols])) {
						$subOutput .= $opt['colsContainer'][$kcols];
					} else {
						$subOutput .= $opt['colsContainer'][1];
					}
				}
				
				$subSubOutput = '';
				if (isset($cols['main']) && is_array($cols['main']) && count($cols['main']) > 0) {
					foreach ($cols['main'] AS $kcats=>$cats) {
						$subSubOutput .= '<div class="mb-5">';					
						if ( $kcats == 0) {
							$subSubOutput .= '<span class="d-block g-font-weight-500 text-uppercase mb-2">';
						} else {
							$subSubOutput .= '<span class="d-block g-font-weight-500 text-uppercase mb-2">';
						}					
						$subSubOutput .= $cats->title_locale;
						$subSubOutput .= '</span>';
						// stampa i figli
						if (isset($cols['sub'][$kcats]) && is_array($cols['sub'][$kcats]) && count($cols['sub'][$kcats]) > 0) {
							$subSubOutput .= '<ul class="list-unstyled">';
							foreach ($cols['sub'][$kcats] AS $kscats=>$scats) {
								$subSubOutput .= '<li>';
								if ( $kcats == 0) {
                                $subSubOutput .= '<a class="d-block g-color-text g-color-primary--hover g-text-underline--none--hover g-py-5" title="'.$scats->title_seo_locale.'" href="'.URL_SITE.$scats->alias.'">';
								} else {
									$subSubOutput .= '<a class="d-block g-color-text g-color-primary--hover g-text-underline--none--hover g-py-5" title="'.$scats->title_seo_locale.'" href="'.URL_SITE.$scats->alias.'">';
								}
								$subSubOutput .= $scats->title_locale;
								$subSubOutput .= '</a></li>';			
							}
							$subSubOutput .= '</ul>';
						}
						$subSubOutput .= '</div>';
					}
				}	

				$subOutput = preg_replace('/%ITEMS%/',$subSubOutput,$subOutput);
			}
		}
		return $subOutput;
	}
		
	public static function createMenuOutput($obj,$parent,$opt) 
	{
		$optDef = array(
			'modulesmenu'=>array(),'ulIsMain'=>0, 'ulMain'=>'<ul>', 'ulSubMenu'=>'<ul>',	 'ulDefault'=>'<ul>',	 'liMain'=>'<li>', 'liSubMenu'=>'<li>', 'liSubSubMenu'=>'<li>', 'liDefault'=>'<li>', 'hrefMain'=>'<a>', 'hrefSubMenu'=>'<a>', 'hrefDefault'=>'<a>', 'lang'=>'it','pagesModule'=>'pages/', 'valueUrlDefault'=>'%ID%/%SEOCLEAN%', 'titleField'=>'', 'activepage'=>'pages'
		);
		$opt = array_merge($optDef,$opt);    
		$has_children = false;
		if (is_array($obj) && count($obj) > 0) {
			foreach($obj AS $key=>$value) {
				if (intval($value->parent) == $parent) {	                    
					$ul = $opt['ulDefault'];
					$li = $opt['liDefault'];
					$href = $opt['hrefDefault'];
					if ($has_children === false) {
						$has_children = true;
						if (self::$treeLevel == 0) $ul = $opt['ulMain'];
						if (self::$treeLevel == 1) $ul = $opt['ulSubMenu'];
						if (self::$treeLevel > 1) $ul = $opt['ulSubSubMenu'];
						$ul = preg_replace('/%ACTIVEPAGE%%/',$opt['activepage'],$ul);
						if (self::$treeLevel > $opt['ulIsMain']) self::$output .= $ul.PHP_EOL;
					}
					/* gestione tag dinamici */
					if (self::$treeLevel == 0) $li = $opt['liMain'];
					if (self::$treeLevel == 0 && $value->sons > 0) $li = $opt['liSubMenu'];
					if (self::$treeLevel > 0 && $value->sons == 0)  $ul = $opt['liDefault'];
					if (self::$treeLevel > 0 && $value->sons > 0) $li = $opt['liSubSubMenu'];
					if (self::$treeLevel == 0 && $value->sons == 0) $href = $opt['hrefMain'];
					if (self::$treeLevel == 0 && $value->sons > 0) $href = $opt['hrefSubMenu'];
					if (self::$treeLevel > 0 && $value->sons > 0) $href = $opt['hrefSubSubMenu'];
						
					//echo 'alias: '.$value->alias;
					if (self::$treeLevel == 0 && $value->alias == $opt['activepage']) {
						$li = preg_replace('/%CLASSACTIVE%/',' active',$li);
					} else {
						$li = preg_replace('/%CLASSACTIVE%/','',$li);
					}
					
					/* crea url */
					$hrefUrl = URL_SITE.$opt['valueUrlDefault'];
					$hrefUrl = preg_replace('/%ALIAS%/',$value->alias,$hrefUrl);
					$li = preg_replace('/%URL%/',$hrefUrl,$li);
					$li = preg_replace('/%URLTITLE%/',$value->title,$li);
					$li = preg_replace('/%TITLE%/',$value->title,$li);
					$href = preg_replace('/%URL%/',$hrefUrl,$href);
					$href = preg_replace('/%URLTITLE%/',$value->title,$href);
					$href = preg_replace('/%TITLE%/',$value->title,$href);
						
					if (self::$treeLevel == 0 && $value->alias == $opt['activepage']) {
						$href = preg_replace('/%CLASSACTIVE%/',' active',$href);
					} else {
						$href = preg_replace('/%CLASSACTIVE%/','',$href);
					}		                    
					$href = preg_replace('/%TARGET%/',$value->title_seo_locale,$href);		                    
					self::$output .= $li.PHP_EOL;
					self::$output .= $href.PHP_EOL;
					self::$output = preg_replace('/%LEVEL%/',self::$treeLevel,self::$output);
					self::$output = preg_replace('/%SONS%/',$value->sons,self::$output);
					$id = intval($value->id);
					self::$treeLevel = self::$treeLevel + 1;
					self::createMenuOutput($obj,$id,$opt);
					self::$treeLevel = self::$treeLevel - 1;					
					self::$output .= '</li>'.PHP_EOL;				
				}	
			}
		}
		if ($has_children === true && self::$treeLevel > $opt['ulIsMain']) self::$output .= '</ul>'.PHP_EOL;
		return self::$output;
	}
			
	// sql queries
	public static function getOneRandomProductDetails($id) {
		//Sql::setDebugMode(1);
		$f = array('*');
		$fv = array();
		Sql::initQuery(Config::$DatabaseTables['warehouse products'],$f,$fv,'active = 1','RAND()');
		$obj = Sql::getRecord();	
		//ToolsStrings::dump($obj);
		$obj = Products::addCustomFields($obj);
		if (Core::$resultOp->error > 0) {	die('errore'); ToolsStrings::redirect(URL_SITE.'error/db'); die(); }	
		return $obj;
	}

	public static function getOneRandomCategoryDetails() 
	{
		//Sql::setDebugMode(1);
		$f = array('c.*');
		$fv = array();
		Sql::initQuery(Config::$DatabaseTables['warehouse categories'].' AS c',$f,$fv,
		'c.active = 1 
		AND (SELECT COUNT(i.id) FROM '.Config::$DatabaseTables['warehouse products'].' AS i WHERE i.categories_id = c.id) > 0
		','RAND()');
		$obj = Sql::getRecord();	
		$obj = self::addCustomFields($obj);
		//ToolsStrings::dump($obj);
		if (Core::$resultOp->error > 0) {	die('errore'); ToolsStrings::redirect(URL_SITE.'error/db'); die(); }	
		//$obj = self::addProductFields($obj);
		//print_r($obj);die();
		return $obj;	
	}

	public static function getCategoryDetails($id=0,$alias="",$opz=array()) 
	{
		/*
		echo '<br>id: '.$id;
		echo '<bralias: '.$alias;
		*/
		$obj =  new stdClass;
		$actived = (isset($opz['actived']) ? $opz['actived'] : true);	
		$fv = array();
		$clause = '';
		$and = '';
		if ($id != '' && $alias == "" )
		{
			$clause .= '(id = ? OR alias = ?)';
			$fv[] = intval($id);
			$fv[] = $id;
			$and = ' AND ';
		}
		if ($id == 0 && $alias != "" )
		{
			$clause .= '(id = ? OR alias = ?)';
			$fv[] = intval($alias);
			$fv[] = $alias;
			$and = ' AND ';
		}
		if ($id != '' && $alias != "" )
		{
			$clause .= '(id = ? OR alias = ?) AND (id = ? OR alias = ?)';
			$fv[] = $id;
			$fv[] = intval($alias);
			$fv[] = intval($id);
			$fv[] = $id;
			$and = ' AND ';
		}
		//echo '<br>clause: '.$clause;
		//ToolsStrings::dump($fv);
		if ($actived == true) $clause .= $and.'active = 1';
		Sql::initQuery(Config::$DatabaseTables['warehouse categories'],array('*'),$fv,$clause);
		$obj = Sql::getRecord();	
		if (Core::$resultOp->error > 0) {die('errore db lettura dettaglio categoria aaaa');	ToolsStrings::redirect(URL_SITE.'error/db'); }
		if (isset($obj->id)) $obj = self::addCustomFields($obj);
		return $obj;
	}
		
	public static function getObjFromSubCategories() 
	{	
		$languages = self::$globalSettings['languages'];			
		$qry = 'SELECT 
			c.id AS id,
			c.parent AS parent,
			c.alias AS alias,
			c.title_'.self::$langUser.' AS title_locale,
			c.title_seo_'.self::$langUser.' AS title_seo_locale,
			c.alias AS alias,
			c.filename AS filename,
			c.org_filename AS org_filename,
			c.ordering AS ordering,
			c.active AS active,';
		
		foreach ($languages AS $lang) {
			$qry .= 'c.title_'.$lang.' AS title_'.$lang.',';
		}		
		$qry .= "(SELECT COUNT(i.id) FROM ".Config::$DatabaseTables['warehouse products']." AS i WHERE i.categories_id = c.id) AS items,";		
		foreach ($languages AS $lang) {
			$qry .= '(SELECT p.title_'.$lang.' FROM '.Config::$DatabaseTables['warehouse categories'].' AS p WHERE c.parent = p.id)  AS titleparent_'.$lang.',';
		}			
		$qry .= "(SELECT COUNT(id) FROM ".Config::$DatabaseTables['warehouse categories']." AS s WHERE s.parent = c.id)  AS sons";	
		$qry .= " FROM ".Config::$DatabaseTables['warehouse categories']." AS c
		WHERE c.parent = :parent 
		ORDER BY ".self::$optOrdering;
		//echo $qry;//die('fatto');
		$obj = '';
		Sql::resetListTreeData();
		Sql::resetListDataVar();
		$opt = array (
			'orgQry'					=>	$qry,
			'qryCountParentZero'		=>	"SELECT id FROM ".Config::$DatabaseTables['warehouse categories']." WHERE parent = 0",
			'lang'						=>	self::$langUser,
			'fieldKey'					=>	self::$optFieldKey,
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
		public static function setTreeLevel($value) {
		self::$treeLevel = $value;
    }
		
	public static function setDbTable($value) 
	{
	    Config::$DatabaseTables['warehouse categories'] = $value;
	}
	
	public static function setLangUser($value) {
	    self::$langUser = $value;
	}
	
	public static function setOptGetNumProductds($value) {
	    self::$optGetNumProductds = $value;
	}
	
	public static function setOptSqlOptions($value) {
	    self::$optSqlOptions = $value;
	}
	
	public static function setOptImageFolder($value) {
	    self::$optImageFolder = $value;
	}
	
	public static function getMainData() 
	{
		return self::$mainData;
    }

	public static function resetOutput() 
	{
		self::$output = '';	
	}
    
    public static function addCustomFields($obj) {		
		$field = 'title_'.self::$langUser;	
		$obj->title_locale = $obj->$field;
		$obj->title =  Multilanguage::getLocaleObjectValue($obj,'title_',self::$langUser,array());
		$obj->title_seo = Multilanguage::getLocaleObjectValue($obj,'title_seo_',self::$langUser,array());
		$obj->summary = Multilanguage::getLocaleObjectValue($obj,'summary_',self::$langUser,array());
		$obj->summary_nop = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $obj->summary);
		$obj->content = Multilanguage::getLocaleObjectValue($obj,'content_',self::$langUser,array());
		$obj->content_nop = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $obj->content);
		$obj->meta_title =  Multilanguage::getLocaleObjectValue($obj,'meta_title_',self::$langUser,array());	
		$obj->meta_description =  Multilanguage::getLocaleObjectValue($obj,'meta_description_',self::$langUser,array());
		$obj->meta_keyword = Multilanguage::getLocaleObjectValue($obj,'meta_keyword_',self::$langUser,array());
		$obj->url = URL_SITE.'products/dt/'.$obj->id.'/'.SanitizeStrings::urlslug($obj->title,array('deliliter'=>'-'));	
		$img = 'default.jpg';
		if ($obj->filename != '') $img = $obj->filename;
		$obj->image_url = UPLOAD_DIR.self::$optImageFolder.$img;
		$obj->image = $img;			
		return $obj;	
	}

		
}
?>

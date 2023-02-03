<?php 
/* wscms/class.Search.php v.3.5.4. 24/07/2019 */

class Search extends Core {

	public function __construct() 	{
		parent::__construct();
		}
		
	public static function inFrameWork($keyword,$languages,$lang,$languser) {
		$res = array();
		
		/* ricerca nelle pagine */
		$fields = array('id,id_owner');
		$fields[] = 'title_'.$languser.' AS title';
		$fields[] = 'content_'.$languser.' AS content';
		$fieldsVal = array();
		$clause = array();			
		$fieldsVal[] = '%'.$keyword.'%';
 		$fieldsVal[] = '%'.$keyword.'%';
		$clause[] = 'title_'.$languser.' LIKE ?';	
 		$clause[] = 'content_'.$languser.' LIKE ?';
		$clause = implode(' OR ',$clause);			
		Sql::initQuery(Config::$dbTablePrefix.'pages_blocks',$fields,$fieldsVal,$clause);		
		Sql::setOrder('id_owner ASC');
		$obj = Sql::getRecords();		
		/* preleva i dati delle pagine */
		$contents = array();
		$pages = array();
		if (is_array($obj) && count($obj) > 0) {
			foreach($obj AS $value) {
				/* prende i dati delle pagine */
				Sql::initQuery(Config::$dbTablePrefix.'pages',array('*'),array($value->id_owner),'id = ?');		
				$pages[$value->id_owner] = Sql::getRecord();
				
				// preleva il contenuto
				 if (!isset($contents[$value->id_owner])) {
				 	$contents[$value->id_owner] = $value->content;
				 } else {
				 	$content = $contents[$value->id_owner];
				 	$content .= $value->content;
				 	$contents[$value->id_owner] = $content;
				 }
				
			}
		}
		
		/* sistemo i dati */
		$arrPages = array();
		if (Core::$resultOp->error == 0) {			
			if (is_array($pages) && is_array($pages) && count($pages) > 0) {
				foreach ($pages AS $value) {						
					$fieldRif = 'title_'.$languser;				
					$value->title = $value->$fieldRif;					
					$value->foundurl = URL_SITE.'pages/'.$value->alias;					
					$value->content = self::getSearchContent($contents[$value->id],$keyword,array());
					$arrPages[] = $value;
				}
			}
		}
		$pages = $arrPages;
		$module = 'pages';
		$res[$module] = array();
		$res[$module]['alias'] = 'pages';
		$res[$module]['itemidfield'] = 'id';
		$res[$module]['section'] = $lang['pagine'];
		$res[$module]['sqldata'] = $pages;
		/* fine ricerca nelle pagine */
		return $res;
	}
	
	public static function inTableModule($keyword,$languages,$lang,$languser,$module,$opz) {
		$opzDef = array('alias'=>'');	
		$opz = array_merge($opzDef,$opz);
		
		$res = array();		
		switch ($module) {			
		 	case 'news':
		 		$opz['table'] = Config::$dbTablePrefix.'news';
		 		$opz['active'] = true;
		 		$opz['fields'] = array('id');		 		
		 		$opz['fieldsSearch'] = array();
		 		$opz['section'] = $lang['notizie'];
		 		$opz['ordering'] = 'datatimeins ASC';
		 		$opz['fields'][] = 'title_'.$languser;	
		 		$opz['fields'][] = 'content_'.$languser;	 			
		 		$opz['fieldsSearch'][] = 'title_'.$languser;
				$opz['fieldsSearch'][] = 'summary_'.$languser;
		 		$opz['fieldsSearch'][] = 'content_'.$languser;	
		 		$opz['foundurl'] = '%URLSITE%%ALIAS%/dt/%IDITEM%';
	 		
			break;
						
			case 'faq':
		 		$opz['table'] = Config::$dbTablePrefix.'faq';
		 		$opz['active'] = true;
		 		$opz['fields'] = array('id');		 		
		 		$opz['fieldsSearch'] = array();
		 		$opz['section'] = $lang['faq'];
		 		$opz['ordering'] = 'ordering ASC';
		 		$opz['fields'][] = 'title_'.$languser;	
		 		$opz['fields'][] = 'content_'.$languser;	 			
		 		$opz['fieldsSearch'][] = 'title_'.$languser;
		 		$opz['fieldsSearch'][] = 'content_'.$languser;	
		 		$opz['foundurl'] = '%URLSITE%%ALIAS%/dt/%IDITEM%';
			break;
			
			case 'ec-categorie':
		 		$opz['table'] = Config::$dbTablePrefix.'ec_categories';
		 		$opz['active'] = true;
		 		$opz['fields'] = array('id');		 		
		 		$opz['fieldsSearch'] = array();
		 		$opz['section'] = $lang['categorie'];
		 		$opz['alias'] = 'products';
		 		$opz['ordering'] = 'ordering ASC';
		 		$opz['foundurl'] = '%URLSITE%%ALIAS%/ls/%IDITEM%';
		 		foreach($languages AS $l) {
		 			$opz['fields'][] = 'title_'.$l;		 			
		 			$opz['fieldsSearch'][] = 'title_'.$l;
					//$opz['fieldsSearch'][] = 'content_'.$l;	
		 		}	 		
			break;	
			
						
			case 'ec-products':
		 		$opz['table'] = Config::$dbTablePrefix.'ec_products';
		 		$opz['active'] = true;
		 		$opz['fields'] = array('id');		 		
		 		$opz['fieldsSearch'] = array();
		 		$opz['section'] = $lang['prodotti'];
		 		$opz['alias'] = 'products';
		 		$opz['ordering'] = 'ordering ASC';
		 		$opz['foundurl'] = '%URLSITE%%ALIAS%/dt/%IDITEM%';
		 		foreach($languages AS $l) {
		 			$opz['fields'][] = 'title_'.$l;		 			
		 			$opz['fieldsSearch'][] = 'title_'.$l;
					$opz['fieldsSearch'][] = 'content_'.$l;	
					$opz['fieldsSearch'][] = 'content_alt_'.$l;
					$opz['fieldsSearch'][] = 'content_info_'.$l;
		 		}	 		
			break;						
					
			}
						
		if ($opz['table'] != '') {
			$res = self::inTable($keyword,$languages,$languser,$module,$opz);
		} else {
			Core::$resultOp->error = 1;
		}			
		return $res;
		}	

	public static function inTable($keyword,$languages,$languser,$module,$opt) {
		$res = array();
		$optDef = array('foundurl'=>'%URLSITE%%ALIAS%%IDITEM%','table'=>'','active'=>true,'ordering'=>'','section'=>'','alias'=>'','itemidfield'=>'id','itemtitlefield'=>'title','itemmethod'=>'dt','itemaction'=>'');	
		$opt = array_merge($optDef,$opt);
		$res = array();
		$clause = '';
		$and = '';		
		$fieldsVal = array();			
		if ($opt['active'] == true) {
			$clause = 'active = 1';
			$and = ' AND ';	
		}			
		list($subClause,$subFieldsVal) = Sql::getClauseVarsFromSession($keyword,$opt['fieldsSearch'],array('separator'=>','));
		if ($subClause != '') {
			$clause .= $and.'('.$subClause.')';
			$and = ' AND ';
		}				
		if (count($subFieldsVal) > 0) $fieldsVal = array_merge ($fieldsVal,$subFieldsVal);		
		Sql::initQuery($opt['table'],$opt['fields'],$fieldsVal,$clause);		
		if ($opt['ordering'] != '') Sql::setOrder($opt['ordering']);
		$obj = Sql::getRecords();
		
		/* sistemo i dati */
		$arr = array();
		if (Core::$resultOp->error == 0) {			
			if (is_array($obj) && is_array($obj) && count($obj) > 0) {
				foreach ($obj AS $value) {	
					
					$field = $opt['itemtitlefield'];
					$fieldRif = $field.'_'.$languser;				
					$value->title = $value->$fieldRif;
					
					$field = $opt['itemcontentfield'];
					$fieldRif = $field.'_'.$languser;
					$value->content = self::getSearchContent($value->$fieldRif,$keyword,$opt);
					$arr[] = $value;
					
					$value->foundurl = $opt['foundurl'];
					$value->foundurl = preg_replace('/%URLSITE%/',URL_SITE,$value->foundurl);
					$value->foundurl = preg_replace('/%ALIAS%/',$opt['alias'],$value->foundurl);
					$value->foundurl = preg_replace('/%METHOD%/',$opt['itemmethod'],$value->foundurl);
					$value->foundurl = preg_replace('/%IDITEM%/',$value->id,$value->foundurl);

				}
			}
		}
		$obj = $arr;
		
		
		$res[$module] = array();
		$res[$module]['alias'] = $opt['alias'];
		$res[$module]['itemidfield'] = $opt['itemidfield'];
		//$res[$module]['itemtitlefield'] = $opt['itemtitlefield'];
		//$res[$module]['itemaction'] = ($opz['itemaction'] != '' ? $opz['itemaction'] : $opz['alias']);
		//$res[$module]['itemmethod'] = $opz['itemmethod'];		
		$res[$module]['section'] = $opt['section'];
		$res[$module]['sqldata'] = $obj;
		return $res;
	}
	
	public static function getSearchContent($content,$keyword,$opt) {
		$optDef = array();	
		$opt = array_merge($optDef,$opt);
		$content = strip_tags($content);					
		$pos = strpos($content, $keyword);
		$start = $pos - 50;
		if ($start < 0) $start = 0;
		$content = substr($content, $start, 100);
		return $content;
	}

}
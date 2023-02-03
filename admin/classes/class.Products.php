<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/classes/class.Products.php v.1.0.0. 05/03/2021 
*/

class Products extends Core {

	//public static $optSqlOptions = '';
	public static $optImageFolder = '';

	public static $qryFields = array();
	public static $qryFieldsValues = array();
	public static $qryClause = '';
	public static $qryAndClause = '';
	public static $qryOrder = '';
	public static $qryLimit = '';
	
	public static $optQryFields = array();
	public static $optQryFieldsValues = array();
	public static $optQryClause = '';
	public static $optQryOrder = '';
	public static $optQryLimit = '';

	public static $optQryForPage = '';	
	public static $optQryPage = '';
	public static $optQryDoPagination = '';

	public static $optGetCategoryOwner;
	public static $optGetCustomFields;
	public static $optGetOnlyActive;

	public function __construct() 	{
		parent::__construct();
	}

	public static function resetQryVars()
	{
		self::$qryFields = array('*');
		self::$qryFieldsValues = array();
		self::$qryClause = '';
		self::$qryAndClause = '';
		self::$qryOrder = 'ordering ASC';
		self::$qryLimit = '';
		self::$optQryFields = array();
		self::$optQryFieldsValues = array();
		self::$optQryClause = '';
		self::$optQryOrder = '';
		self::$optQryLimit = '';
		self::$optGetCategoryOwner = true;
		self::$optGetCustomFields = true;
		self::$optQryForPage = '';	
		self::$optQryPage = '';
		self::$optQryDoPagination = '';
		self::$optGetOnlyActive = true;
	}

	public static function getOneRandomProductDetails($categories_id = 0)
	{	
		//Sql::setDebugMode(1);
		if (self::$optGetOnlyActive == true)
		{
			self::$qryClause .= self::$qryAndClause.'active = 1';
			self::$qryAndClause = ' AND ';
		}
		if (intval($categories_id > 0)) {
			self::$qryFieldsValues[] = intval($categories_id);
			self::$qryClause .= self::$qryAndClause.'categories_id = ?'; 
		}
		Sql::initQuery(Config::$DatabaseTables['warehouse products'],self::$qryFields,self::$qryFieldsValues,self::$qryClause,'RAND()');
		$obj = Sql::getRecord();	
		if (Core::$resultOp->error > 0) { die('errore lettura rendon prodotto'); ToolsStrings::redirect(URL_SITE.'error/db'); die(); }	
		if (isset($obj->id)) 
		{ 
			$obj = self::addCustomFields($obj);	
		}
		if (self::$optGetCategoryOwner == true )
		{
			if (isset($obj->categories_id)) $obj->category = self::addCategoryOwnerFields($obj->categories_id);			
		}

		//print_r($obj);die();
		return $obj;	
	}
	
	public static function getProductDetails($id=0,$alias="",$opz=array())
	{	
		//Sql::setDebugMode(1);
		if (self::$optGetOnlyActive == true)
		{
			self::$qryClause .= self::$qryAndClause.'active = 1';
			self::$qryAndClause = ' AND ';
		}
		if ($id != '' && $alias == "" )
		{
			self::$qryClause .= self::$qryAndClause.'id = ? OR alias = ?';
			self::$qryFieldsValues[] = intval($id);
			self::$qryFieldsValues[] = $id;
			self::$qryAndClause = ' AND ';
		}
		if ($id == 0 && $alias != "" )
		{
 			self::$qryClause .= self::$qryAndClause.'id = ? OR alias = ?';
			self::$qryFieldsValues[] = intval($alias);
			self::$qryFieldsValues[] = $alias;
			self::$qryAndClause = ' AND ';
		}
		if ($id != '' && $alias != "" )
		{
			self::$qryClause .= self::$qryAndClause.'(id = ? OR alias = ?) OR (id = ? OR alias = ?)';
			self::$qryFieldsValues[] = $id;
			self::$qryFieldsValues[] = intval($alias);
			self::$qryFieldsValues[] = intval($id);
			self::$qryFieldsValues[] = $id;
			self::$qryAndClause = ' AND ';
		}
		//echo self::$qryClause;
		Sql::initQuery(Config::$DatabaseTables['warehouse products'],self::$qryFields,self::$qryFieldsValues,self::$qryClause);
		$obj = Sql::getRecord();	

		//ToolsStrings::dump($obj);die('vedi dettaglio prodotto');

		if (Core::$resultOp->error > 0) { die('errore db lettura prodotto');	ToolsStrings::redirect(URL_SITE.'error/db');  }	
		if (isset($obj->id)) 
		{ 
			$obj = self::addCustomFields($obj);	
		}
		if (self::$optGetCategoryOwner == true && isset($obj->categories_id))
		{
			$obj->category = self::addCategoryOwnerFields($obj->categories_id);			
		}
		//ToolsStrings::dump($obj);die();
		return $obj;	
	}

	public static function getProductsList($id) {
		//echo 'order: '.self::$optQryOrder;
		//echo 'limit: '.self::$optQryLimit;
		//Sql::setDebugMode(1);
		$obj = array();		
		if (self::$optGetOnlyActive == true)
		{
			self::$qryClause .= self::$qryAndClause.'active = 1';
			self::$qryAndClause = ' AND ';
		}
		
		if (intval($id) > 0) 
		{
			self::$qryFieldsValues[] = intval($id);
			self::$qryClause .= self::$qryAndClause.'categories_id = ?'; 
			self::$qryAndClause = ' AND ';
		}

		if (is_array(self::$optQryFieldsValues) && count(self::$optQryFieldsValues) > 0) {
			self::$qryFieldsValues = array_merge(self::$qryFieldsValues,self::$optQryFieldsValues);
		}

		if (self::$optQryClause != '') 
		{
			self::$qryClause .= self::$qryAndClause.self::$optQryClause;
			self::$qryAndClause = ' AND ';
		}
		if (self::$optQryOrder != '') self::$qryOrder = self::$optQryOrder;
		if (self::$optQryLimit != '') self::$qryLimit = self::$optQryLimit;

		/*
		echo 'order: '.self::$optQryOrder;
		echo 'limit: '.self::$optQryLimit;	
		die();
		*/
	
		Sql::initQueryBasic(Config::$DatabaseTables['warehouse products'],self::$qryFields,self::$qryFieldsValues,self::$qryClause,self::$qryOrder,self::$qryLimit);
		if (self::$optQryDoPagination == true)
		{
			Sql::setResultPaged(true);
			if (self::$optQryForPage > 0) 
			{
				Sql::setItemsForPage(self::$optQryForPage);	
			}

			if (self::$optQryPage > 0) 
			{
				Sql::setPage(self::$optQryPage);	
			}
		}	
		
		$pdoObject = Sql::getPdoObjRecords();	
		if (Core::$resultOp->error > 0) { die('errore lettura prodotti'); ToolsStrings::redirect(URL_SITE.'error/db');}
		while ($row = $pdoObject->fetch()) {
			if (self::$optGetCustomFields == true) 
			{
				$row = self::addCustomFields($row);	
			}
			
			if (self::$optGetCategoryOwner == true && isset($row->categories_id)) $row->category = self::addCategoryOwnerFields($row->categories_id);			
			$obj[] = $row;
		}
		return $obj;	
	}
	
	public static function addCustomFields($proobject)
	{		
		$field = 'title'.Config::$localStrings['db_prefix'];
		$proobject->title_locale = $proobject->$field;
		$proobject->title =  Multilanguage::getLocaleObjectValue($proobject,'title_',Config::$localStrings['user'],array());
		$proobject->title_seo = Multilanguage::getLocaleObjectValue($proobject,'title_seo_',Config::$localStrings['user'],array());

		$proobject->content = Multilanguage::getLocaleObjectValue($proobject,'content_',Config::$localStrings['user'],array());
		$proobject->content_nop = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $proobject->content);

		$proobject->summary = Multilanguage::getLocaleObjectValue($proobject,'summary_',Config::$localStrings['user'],array());
		if ($proobject->summary == '' && $proobject->content != '') $proobject->summary = $proobject->content;
		$proobject->summary_nop = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $proobject->summary);
		$proobject->summary_nop_limited = ToolsStrings::getStringFromTotNumberWords($proobject->summary_nop,array('suffix'=>'','numwords'=>50));
		
		$proobject->meta_title =  Multilanguage::getLocaleObjectValue($proobject,'meta_title_',Config::$localStrings['user'],array());	
		$proobject->meta_description =  Multilanguage::getLocaleObjectValue($proobject,'meta_description_',Config::$localStrings['user'],array());
		$proobject->meta_keyword = Multilanguage::getLocaleObjectValue($proobject,'meta_keyword_',Config::$localStrings['user'],array());

		$img = 'default.jpg';
		if ($proobject->filename != '') $img = $proobject->filename;
		$proobject->image_url = UPLOAD_DIR.self::$optImageFolder.$img;
		$proobject->image = $img;
			
		$proobject->url = URL_SITE.'products/dt/'.$proobject->id.'/'.SanitizeStrings::urlslug($proobject->title,array('deliliter'=>'-'));
		
		$proobject->price_tax = ($proobject->price_unity * $proobject->tax) / 100;
		$proobject->price_total = $proobject->price_unity + $proobject->price_tax;
		
		$proobject->price_total_scontato = $proobject->price_total;
		if ($proobject->price_sconto > 0) $proobject->price_total_scontato = $proobject->price_total_scontato - (($proobject->price_total_scontato * $proobject->price_sconto) / 100);
		
		$proobject->valuta_price_unity = number_format(floatval($proobject->price_unity), 2, '.', '');
		$proobject->valuta_tax = number_format(floatval($proobject->tax), 2, '.', '');
		$proobject->valuta_price_tax = number_format(floatval($proobject->price_tax), 2, '.', '');
		$proobject->valuta_price_total = number_format(floatval($proobject->price_total), 2, '.', '');
		$proobject->valuta_price_total_scontato = number_format($proobject->price_total_scontato, 2, '.', '');

		$proobject->valuta_price_total_int = intval($proobject->valuta_price_total);
		$proobject->valuta_price_total_dec = substr($proobject->valuta_price_total,-2);
				
		return $proobject;	
	}
	
	public static function addCategoryOwnerFields($id,$alias = '') {
		$obj = Subcategories::getCategoryDetails($id,$alias,Config::$DatabaseTables['warehouse categories'],array('findOne'=>true));
		//ToolsStrings::dump($obj);
		return $obj;
	}

}
?>
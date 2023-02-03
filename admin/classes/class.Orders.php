<?php
/*
	framework siti html-PHP-Mysql
	copyright 2011 Roberto Mantovani
	http://www.robertomantovani.vr;it
	email: me@robertomantovani.vr.it
	admin/classes/class.Orders.php v.1.0.0. 05/07/2021
*/

class Orders extends Core {
	
	private static $dbTable = '';
	private static $dbTableBillingAddresses = '';
	private static $dbTableShipmentAddresses = '';
	
	public static $carts_id = '';
	
	public static $billingAddresses;	
	public static $shipmentAddresses;
	
	public function __construct(){			
		parent::__construct();
	}	
	
	public static function loadBillingAddresses() 
	{
     	$f = array('*');
    	$fv = array(self::$carts_id);
    	$clause = 'carts_id = ?';
        Sql::initQuery(self::$dbTableBillingAddresses,$f,$fv,$clause,'','','',false);
		self::$billingAddresses = Sql::getRecord();	
		if (Core::$resultOp->error > 0) {	ToolsStrings::redirect(URL_SITE.'error/db'); die(); }	
		return self::$billingAddresses;	
	}

	public static function addBillingAddresses() 
	{
	    $f = array(
	    	'carts_id',
	    	'name',
	    	'surname',
	    	'street',
	    	'city',
	    	'zip_code',
	    	'province',
	    	'state',
	    	'email',
	    	'telephone',
	    	'fax',
	    	'mobile'
	    	
	    	);
	    $fv = array(
	    	self::$carts_id,
	    	self::$billingAddresses->name,
			self::$billingAddresses->surname,
			self::$billingAddresses->street,
			self::$billingAddresses->city,
			self::$billingAddresses->zip_code,
			self::$billingAddresses->province,
			self::$billingAddresses->state,
			self::$billingAddresses->email,
			self::$billingAddresses->telephone,
			self::$billingAddresses->fax,
			self::$billingAddresses->mobile	    	
	    );
        Sql::initQuery(self::$dbTableBillingAddresses,$f,$fv,'','','','',false);
		Sql::insertRecord();
        if (Core::$resultOp->error > 0) {	ToolsStrings::redirect(URL_SITE.'error/db'); die(); }
    }	
    
    public static function addShipmentAddresses() 
	{
	    $f = array(
	    	'carts_id',
	    	'name',
	    	'surname',
	    	'street',
	    	'city',
	    	'zip_code',
	    	'province',
	    	'state',
	    	'email',
	    	'telephone',
	    	'fax',
	    	'mobile'
	    	
	    	);
	    $fv = array(
	    	self::$carts_id,
	    	self::$shipmentAddresses->name,
			self::$shipmentAddresses->surname,
			self::$shipmentAddresses->street,
			self::$shipmentAddresses->city,
			self::$shipmentAddresses->zip_code,
			self::$shipmentAddresses->province,
			self::$shipmentAddresses->state,
			self::$shipmentAddresses->email,
			self::$shipmentAddresses->telephone,
			self::$shipmentAddresses->fax,
			self::$shipmentAddresses->mobile	    	
	    );
        Sql::initQuery(self::$dbTableShipmentAddresses,$f,$fv,'','','','',false);
		Sql::insertRecord();
        if (Core::$resultOp->error > 0) {	ToolsStrings::redirect(URL_SITE.'error/db'); die(); }
    }
    
    public static function setDbTable($value) 
	{
		self::$dbTable = $value;
	}
	
	public static function setDbTableBillingAddresses($value) 
	{
		self::$dbTableBillingAddresses = $value;
	}
	
	public static function setDbTableShipmentAddresses($value) 
	{
		self::$dbTableShipmentAddresses = $value;
	}
	
	public static function resetShipmentAddresses() 
	{
		self::$shipmentAddresses = new stdClass;
	}
	
	public static function resetBillingAddresses() 
	{
		self::$billingAddresses = new stdClass;
	}
	
	public static function setCartsId($value) 
	{
    	self::$carts_id = $value;
    }
}
?>
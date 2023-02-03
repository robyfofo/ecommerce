<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/classes/class.Carts.php v.1.0.0. 05/03/2021 
*/

class Carts extends Core {
    
    private static $carts_id = 0;
    private static $session_id = '';
    private static $token = '';

    public static $dbTable = '';
    public static $dbTablePro = '';
    public static $dbTableCartPro = '';
    public static $langUser = '';

	public static $total_products_price_unity = '0';
	public static $total_products_price_unity_quantity = '0';
	public static $total_products_price_tax = '0';
	public static $total_products_price_total = '0';
	
	public static $total_products_quantity = '0';
	
	public static $valuta_total_products_price_unity = '0';
	public static $valuta_total_products_quantity = '0';
	public static $valuta_total_products_price_unity_quantity = '0';
	public static $valuta_total_products_price_tax = '0';
	public static $valuta_total_products_price_total = '0';

	public static $cartProducts = '';
    
    public function __construct(){
        parent::__construct();  
    }
    
    public static function checkIfExists($carts_id) {
    	$result = false;
    	if (Sql::countRecordQry(self::$dbTable,'id','id = ?',array($carts_id)) > 0) {   		
    		$result = true;
    	}	
    	return $result;
    }
    
	public static function clear($carts_id) 
	{	
		Sql::initQuery(self::$dbTableCartPro,array('id'),array($carts_id),'carts_id = ?','','','',false);
		if (Core::$resultOp->error > 0) { die('errore cancellazone prodotti pulizia cart');ToolsStrings::redirect(URL_SITE.'error/db');  }
		Sql::deleteRecord();
		self::$carts_id = 0; 	
    }

	public static function addNewCarts() 
	{
    	$f = array('session_id','token');
    	$fv = array(self::$session_id,self::$token);
        Sql::initQuery(self::$dbTable,$f,$fv,'','','','',false);
        Sql::insertRecord();   
        self::$carts_id = Sql::getLastInsertedIdVar();
    }
    
	public static function addProduct($id,$quantity) 
	{
		Products::resetQryVars();
		Products::$optGetCategoryOwner = false;
		$product = Products::getProductDetails($id);
		$product->quantity = $quantity;
		$product = self::calcolateProductPrices($product);    
		//ToolsStrings::dump($product);
		//Sql::setDebugMode(1);
        if (isset($product->id)) {        	  	
            $f = array(
		    	'carts_id',
		    	'products_id',
		    	'price_unity',
		    	'quantity',
		    	'price_unity_quantity',
		    	'tax',
		    	'price_tax',
		    	'price_total',
				'price_sconto',
				'price_total_scontato'
		    	);
		    $fv = array(
		    	self::$carts_id,
		    	$product->id,
		    	$product->price_unity,
		    	$product->quantity,
		    	$product->price_unity_quantity,
		    	$product->tax,
		    	$product->price_tax,
		    	$product->price_total,
				$product->price_sconto,
				$product->price_total_scontato			    	
		    );
         	Sql::initQuery(self::$dbTableCartPro,$f,$fv,'','','','',false);
			Sql::insertRecord();
        	if (Core::$resultOp->error > 0) { die('errore memorizzazione prodotto nel carrello');	ToolsStrings::redirect(URL_SITE.'error/db');  }
		}
    }

	public static function calcolateProductPrices($obj) 
	{	
		$obj->price_unity_tax = ($obj->price_unity * $obj->tax ) / 100;
		$obj->price_unity_taxed = $obj->price_unity + $obj->price_unity_tax;
		$obj->price_unity_quantity = $obj->price_unity * $obj->quantity;
        $obj->price_tax = ($obj->price_unity_quantity * $obj->tax ) / 100;
        $obj->price_total = $obj->price_unity_quantity + $obj->price_tax;	

		$obj->price_total_scontato = $obj->price_total;
		if ($obj->price_sconto > 0) $obj->price_total_scontato = $obj->price_total_scontato - (($obj->price_total_scontato * $obj->price_sconto) / 100);
		return $obj;	
    }

 	public static function loadCartsProducts() 
	 {
		//Sql::setDebugMode(1);
    	$obj = array();
     	$f = array('cp.*','product.alias AS product_alias','product.title_'.self::$langUser.' AS product_title','product.filename','product.org_filename');
    	$fv = array(self::$carts_id);
    	$clause = 'cp.carts_id = ?';
        Sql::initQuery(self::$dbTableCartPro.' AS cp INNER JOIN '.self::$dbTablePro.' AS product ON (cp.products_id = product.id)',$f,$fv,$clause,'','','',false);
       	//Sql::setOptAddRowFields(1);
		$pdoObject = Sql::getPdoObjRecords();	
		if (Core::$resultOp->error > 0) {die('errore lettura prodotti carrello');ToolsStrings::redirect(URL_SITE.'error/db');  }
		self::$total_products_price_unity = 0;
		self::$total_products_quantity = 0;
		self::$total_products_price_unity_quantity = 0;
		self::$total_products_price_tax = 0;
		self::$total_products_price_total = 0;
		while ($row = $pdoObject->fetch()) {
			$row = self::calcolateProductPrices($row);
			//totali
			self::$total_products_price_unity += $row->price_unity;
			self::$total_products_quantity += $row->quantity;
			self::$total_products_price_unity_quantity += $row->price_unity_quantity;			
			self::$total_products_price_tax += $row->price_tax;
			self::$total_products_price_total += $row->price_total;
			// trasformo 
			$row->valuta_price_unity_taxed =  number_format($row->price_unity_taxed, 2, ',', '.');
			$row->valuta_price_unity = number_format($row->price_unity, 2, ',', '.');
			$row->valuta_tax = number_format($row->tax, 2, '.', '');
			$row->valuta_price_unity_quantity = number_format($row->price_unity_quantity, 2,',', '.');
			$row->valuta_price_tax = number_format($row->price_tax, 2, '.', ',');
			$row->valuta_price_total = number_format($row->price_total, 2, ',', '.');
			$obj[] = $row;
		}
		///ToolsStrings::dump($obj);							
		self::$valuta_total_products_price_unity = number_format(self::$total_products_price_unity, 2, ',', '.');
		self::$valuta_total_products_price_unity_quantity = number_format(self::$total_products_price_unity_quantity, 2, ',', '.');
		self::$valuta_total_products_price_tax = number_format(self::$total_products_price_tax, 2, '.', '');
		self::$valuta_total_products_price_total = number_format(self::$total_products_price_total, 2, ',', '.');		
		self::$cartProducts = $obj;	
	}

	public static function delProduct($id) 
	{
		Sql::initQuery(self::$dbTableCartPro,array('id'),array($id),'id = ?','','','',false);
		Sql::deleteRecord();
       	if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); die(); }
    }

	public static function setQtyPro($id,$products_quantity) 
	{
		//Sql::setDebugMode(1);
		// prende dati riga prodotto
		Sql::initQuery(self::$dbTableCartPro,array('*'),array(self::$carts_id,$id),'carts_id = ? AND id = ?','','','',false);
		$product = Sql::getRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); die(); }
		if (isset($product->id)) { 
			$product->quantity = $products_quantity;
			$product = self::calcolateProductPrices($product);
		
			Sql::initQuery(self::$dbTableCartPro,
			array('quantity','price_unity_quantity','price_tax','price_total'),
			array($product->quantity,$product->price_unity_quantity,$product->price_tax,$product->price_total,self::$carts_id,$id)
			,'carts_id = ? AND id = ?','','','',false);
			Sql::updateRecord();
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); die(); }

		}
	}

    public static function setSessionId($value) 
	{
    	self::$session_id = $value;
    }
    
    public static function setToken($value) 
	{
    	self::$token = $value;
    }
    
    public static function setCartsId($value) 
	{
    	self::$carts_id = $value;
    }
    
    public static function getCartsId() {
		return self::$carts_id;
	}
}
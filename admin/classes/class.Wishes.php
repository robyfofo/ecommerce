<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/classes/class.Wishes.php v.1.0.0. 10/03/2021 
*/

class Wishes extends Core {
    
    private static $wishes_id = 0;
	public static $WishlistProducts;
	public static $WishlistProductsTotals;
	public static $WishlistProductsTotalsValute;
    
    public function __construct(){
        parent::__construct();  
    }
    
    public static function checkIfExists($carts_id) {
    	$result = false;
    	if (Sql::countRecordQry(Config::$DatabaseTables['whises'],'id','id = ?',array($carts_id)) > 0) {   		
    		$result = true;
    	}	
    	return $result;
    }
    
	public static function clear($carts_id) 
	{	
		Sql::initQuery(Config::$DatabaseTables['whises products'],array('id'),array($carts_id),'carts_id = ?','','','',false);
		if (Core::$resultOp->error > 0) { die('errore cancellazone prodotti pulizia cart');ToolsStrings::redirect(URL_SITE.'error/db');  }
		Sql::deleteRecord();
		self::$wishes_id = 0; 	
    }

	public static function addNew() 
	{
    	$f = array('session_id','token');
    	$fv = array(Config::getShopSessionId(),Config::getShopSessionToken());
        Sql::initQuery(Config::$DatabaseTables['wishes'],$f,$fv,'','','','',false);
        Sql::insertRecord();   
        self::$wishes_id  = Sql::getLastInsertedIdVar();
    }
    
	public static function addProduct($id,$quantity) 
	{
		// controlla se è gia presente
		if (0 == Sql::countRecordQry(Config::$DatabaseTables['whises products'],'id','wishes_id = ? AND products_id = ?',array(self::$wishes_id,$id))) {
			Products::resetQryVars();
			Products::$optGetCategoryOwner = false;
			$product = Products::getProductDetails($id);
			$product->quantity = $quantity;
			$product = self::calcolateProductPrices($product);    
			//ToolsStrings::dump($product);
			//Config::$debugMode = 1;
        	if (isset($product->id)) {        	  	
				$f = array(
					'wishes_id',
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
					self::$wishes_id,
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
				Sql::initQuery(Config::$DatabaseTables['whises products'],$f,$fv,'','','','',false);
				Sql::insertRecord();
        		if (Core::$resultOp->error > 0) { die('errore memorizzazione prodotto nei desideri');	ToolsStrings::redirect(URL_SITE.'error/db');  }
			} 
		}
    }

	public static function setQtyProduct($id,$quantity)
	{	
		$product = new stdClass();

		if (isset($id)) {  
			// carica i dati del prodotto
			$product = self::getProductDatail($id);
			//ToolsStrings::dump($product);
			$product->quantity = $quantity;
			$product = self::calcolateProductPrices($product);
			//ToolsStrings::dump($product);    	  	
			$f = array(
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
				$product->price_unity,
				$product->quantity,
				$product->price_unity_quantity,
				$product->tax,
				$product->price_tax,
				$product->price_total,
				$product->price_sconto,
				$product->price_total_scontato,
				$id			    	
			);
			Sql::initQuery(Config::$DatabaseTables['whises products'],$f,$fv,'id = ?','','','',false);
			Sql::updateRecord();
			if (Core::$resultOp->error > 0) { 
				die('errore modifica quantita prodotto nei desideri');	
				ToolsStrings::redirect(URL_SITE.'error/db'); 
			}
		}
		return $product;
	}

	public static function getProductDatail($id)
	{
		Sql::initQuery(Config::$DatabaseTables['whises products'],array('*'),array($id),'id = ?','','','',false);
		return Sql::getRecord();
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

	public static function checkIfIsEmpty() {
		//Config::$debugMode = 1;
		$result = false;
    	if (Sql::countRecordQry(Config::$DatabaseTables['whises products'],'id','wishes_id = ?',array(self::$wishes_id)) == 0) {   		
    		$result = true;
    	}	
    	return $result;
	}

 	public static function loadProducts() 
	{
 		//Config::$debugMode = 1;
    	$obj = array();
     	$f = array('cp.*','product.alias AS product_alias','product.title_'.self::$langUser.' AS product_title','product.filename','product.org_filename');
    	$fv = array(self::$wishes_id);
    	$clause = 'cp.wishes_id = ?';
        Sql::initQuery(Config::$DatabaseTables['whises products'].' AS cp INNER JOIN '.Config::$DatabaseTables['products'].' AS product ON (cp.products_id = product.id)',$f,$fv,$clause,'','','',false);
       	//Sql::setOptAddRowFields(1);
		$pdoObject = Sql::getPdoObjRecords();	
		if (Core::$resultOp->error > 0) {	ToolsStrings::redirect(URL_SITE.'error/db'); die(); }

		/*
		self::$total_products_price_unity = 0;
		self::$total_products_quantity = 0;
		self::$total_products_price_unity_quantity = 0;
		self::$total_products_price_tax = 0;
		self::$total_products_price_total = 0;
		*/
		while ($row = $pdoObject->fetch()) {
			$row = self::calcolateProductPrices($row);
			/*
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
			*/
			$obj[] = $row;
		}
		///ToolsStrings::dump($obj);		
		/*					
		self::$valuta_total_products_price_unity = number_format(self::$total_products_price_unity, 2, ',', '.');
		self::$valuta_total_products_price_unity_quantity = number_format(self::$total_products_price_unity_quantity, 2, ',', '.');
		self::$valuta_total_products_price_tax = number_format(self::$total_products_price_tax, 2, '.', '');
		self::$valuta_total_products_price_total = number_format(self::$total_products_price_total, 2, ',', '.');	
		*/	

		//ToolsStrings::dump($obj);
		self::$WishlistProducts = $obj;	
	}

	public static function delProduct($id) 
	{
		Sql::initQuery(Config::$DatabaseTables['whises products'],array('id'),array($id),'id = ?','','','',false);
		Sql::deleteRecord();
       	if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); die(); }
    }

    public static function setId($value) 
	{
    	self::$wishes_id = $value;
    }
    
    public static function getId() {
		return self::$wishes_id;
	}
}
<?php
/*	wscms/classes/class.EcomBasket.php v.3.3.0. 21/06/2017 */

class EcomBasket extends Core {
	
	private static $basketId;
	private static $basketSession;
	
	private static $basketProductsList;
	private static $basketProductsNumber;
	private static $basketProductsTotal;
	private static $basketTotal;
	
	public function __construct() {
		parent::__construct();
		}
		
	public static function basketInit() {
		self::$resultOp->error = 0;
		self::setBasketId(0); /* se è 0 id ordine sara id basket */
		self::setBasketSession();
		//echo '<br>id_basket : '.self::$basketId;
		//echo '<br>session_basket : '.self::$basketSession;
		}
		
	public static function getBasketStatus() {	
		if (self::$basketId != '' && self::$basketSession != '') {
			return 1;
			}	else {
				return 0;
				}
		}

	public static function setBasketId() {
		if (isset(self::$sessionValues['ecomm']['basketid']) && self::$sessionValues['ecomm']['basketid'] != '') {
			self::$basketId = self::$sessionValues['ecomm']['basketid'];
			} else {
				self::$basketId = time();
				/* memorizza in sessione database */
				$my_session = new my_session(SESSIONS_TIME, SESSIONS_GC_TIME,SESSIONS_COOKIE_NAME);
				$my_session->my_session_start();
				self::$sessionValues = array();
				self::$sessionValues = $my_session->my_session_read();
				$my_session->addSessionsModuleSingleVar(self::$sessionValues,'ecomm','basketid',self::$basketId);
				//Core::setSessionValues($_MY_SESSION_VARS);
				//print_r($_MY_SESSION_VAR);
				}	
		}

	public static function setBasketSession() {
		if (isset(self::$sessionValues['ecomm']['basketsession']) && self::$sessionValues['ecomm']['basketsession'] != '') {
			self::$basketSession = self::$sessionValues['ecomm']['basketsession'];
			} else {
				/* memorizza in sessione database */
				$my_session = new my_session(SESSIONS_TIME, SESSIONS_GC_TIME,SESSIONS_COOKIE_NAME);
				$my_session->my_session_start();
				self::$sessionValues = array();
				self::$sessionValues = $my_session->my_session_read();
				$my_session->addSessionsModuleSingleVar(self::$sessionValues,'ecomm','basketsession',$my_session->getSessionId());
				
				//Core::setSessionValues($_MY_SESSION_VARS);
				//print_r($_MY_SESSION_VAR);

				}
		}
		
	public static function deleteBasket($opt=array()) {
		EcomBasket:: basketInit();	
		$optDef = array('basketId'=>self::$basketId,'basketSession'=>self::$basketSession);
		$opt = array_merge($optDef,$opt);		
		/* cancella i prodotti del basket */
		//self::delBasketProducts($opt);
		if (Core::$resultOp->error == 0) {
			/* cancella i basket non corrispondenti alle sessioni */
			self::garbageBasketSession($opt); 
			if (Core::$resultOp->error == 0) {
				/* azzera sessione basket */
				self::resetBasketSession($opt);
				}			
			}		
		}
		
	public static function delBasketProducts($opt=array()) {
		$optDef = array('basketId'=>self::$basketId,'basketSession'=>self::$basketSession);
		$opt = array_merge($optDef,$opt);
		$obj = new stdClass();	
		Sql::initQuery(Config::$dbTablePrefix().'ec_basket_products',array(),array($opt['basketId'],$opt['basketSession']),'id_basket = ? AND session_basket = ?','');
		Sql::deleteRecord();		
		}


	public static function garbageBasketSession($opt=array()) {
		$optDef = array('basketId'=>self::$basketId,'basketSession'=>self::$basketSession);
		$optDef = array();
		$opt = array_merge($optDef,$opt);
		$dbprefix = Config::$dbTablePrefix();
		/* legge tutti prodotti basket */
		Sql::initQuery($dbprefix.'ec_basket_products',array('id,session_basket'),array(),'','');
		Sql::getRecords();
		$obj = Sql::getRecords();
		if (Core::$resultOp->error == 0) {
			if (is_array($obj) && is_array($obj) && count($obj) > 0) {
				foreach ($obj AS $value) {	
					//echo 'session_basket: '.$obj->session_basket;
					/* controlla se esite nella talella sessions */
					Sql::initQuery($dbprefix.'ec_basket_products',array('COUNT(id) AS num'),array(self::$basketId,self::$basketSession),'id_basket = ? AND session_basket = ?','');
					$obj = Sql::getRecord();
					if (Core::$resultOp->error == 0) {
						$match = $obj->num;
						if ($match > 0) {
							/* cancello i prodotti con basket session che non esiste */
							if (isset($obj->session_basket) && $obj->session_basket != '') Sql::initQuery($dbprefix.'ec_basket_products',array(),array($obj->session_basket),'session_basket = ?','');
							Sql::deleteRecord();
							}
						} else {
							break;
							}
					}
				}
			}
		}
		
	public static function resetBasketSession($opt) {
		$optDef = array();
		$opt = array_merge($optDef,$opt);
		$my_session = new my_session(SESSIONS_TIME, SESSIONS_GC_TIME,SESSIONS_COOKIE_NAME);
		$my_session->my_session_start();
		$_MY_SESSION_VARS = array();
		$_MY_SESSION_VARS = $my_session->my_session_read();		
		$my_session->my_session_unsetVar('ecomm');
		}

		
		
	public static function getProductDetails($id,$opt) {
		$optDef = array('lang'=>'it');
		$opt = array_merge($optDef,$opt);
		$obj = new stdClass();	
		$id = intval($id);
		Sql::initQuery(Config::$dbTablePrefix.'ec_products',array('*'),array($id),'active = 1 AND id = ?','');
		$obj = Sql::getRecord();
		if (Core::$resultOp->error == 0) {
			if (isset($obj->id) && $obj->id > 0) {
				$obj->title =  Multilanguage::getLocaleObjectValue($obj,'title_',$opt['lang'],array());
				return $obj;
				}
			}
		return false;
		}

		
	public static function setBasketProductsList($opt=array()) {
		$optDef = array();
		$opt = array_merge($optDef,$opt);
		$obj = new stdClass();			
		//echo '<br>id_basket : '.self::$basketId;
		//echo '<br>session_basket : '.self::$basketSession;		
		Sql::initQuery(Config::$dbTablePrefix.'ec_basket_products',array('*'),array(self::$basketId,self::$basketSession),'id_basket = ? AND session_basket = ?','');
		$obj = Sql::getRecords();
		if (Core::$resultOp->error == 0) {
			/* sistemo i dati */
			$arr = array();
			$subtotal = 0;
			if (is_array($obj) && is_array($obj) && count($obj) > 0) {
				foreach ($obj AS $value) {
					$value->subprice = $value->price * $value->quantity;
					$subtotal = $subtotal + $value->subprice;
					$arr[] = $value;
					}
				}			
			self::$basketProductsList = $arr;
			self::$basketProductsTotal = $subtotal;			
			Sql::initQuery(Config::$dbTablePrefix.'ec_basket_products',array('COUNT(id) AS num'),array(self::$basketId,self::$basketSession),'id_basket = ? AND session_basket = ?','');
			$obj = Sql::getRecord();
			if (Core::$resultOp->error == 0) {
				self::$basketProductsNumber = $obj->num;
				}
			}
			//print_r(self::$basketProductsList);
		}
		
	public static function addProductToBasket($id=0,$quantity=0,$opt=array()) {
		$item = self::getProductDetails(intval($id),array());
		if (isset($item->id) && $item->id > 0) {
			$fields = array('id_basket','session_basket','title','price','quantity','type','filename');
			$fieldsValues = array(self::$basketId,self::$basketSession,$item->title,$item->price,intval($quantity),1,$item->filename);
			Sql::initQuery(Config::$dbTablePrefix.'ec_basket_products',$fields,$fieldsValues,'','');
			Sql::insertRecord();
			}	
		}
		
	public static function addProductAccToBasket($id=0,$quantity=0,$opt=array()) {
		$item = self::getProductDetails(intval($id),array());
		if (isset($item->id) && $item->id > 0) {
			$fields = array('id_basket','session_basket','title','price','quantity','type','filename');
			$fieldsValues = array(self::$basketId,self::$basketSession,$item->title,$item->price_acc,intval($quantity),1,$item->filename);
			Sql::initQuery(Config::$dbTablePrefix.'ec_basket_products',$fields,$fieldsValues,'','');
			Sql::insertRecord();
			}	
		}
		
	public static function updateQuantityProductBasket($id_product=0,$quantity=0,$opt=array()) {
		if ($id_product > 0) {
			$fields = array('quantity');
			$fieldsValues = array(intval($quantity),self::$basketId,self::$basketSession,intval($id_product));
			Sql::initQuery(Config::$dbTablePrefix.'ec_basket_products',$fields,$fieldsValues,'id_basket = ? AND session_basket = ? AND id = ?','');
			Sql::updateRecord();
			}	
		}
		
	public static function getBasketProductsList($opt=array()) {
		//print_r(self::$basketProductsList);
		return self::$basketProductsList;
		}
	
	public static function getBasketProductsNumber($opt=array()) {
		return self::$basketProductsNumber;
		}
	
	public static function getBasketProductsTotal($opt=array()) {
		return self::$basketProductsTotal;
		}
		
	public static function getBasketTotal($opt=array()) {
		self::$basketTotal = 0;
		self::$basketTotal = self::$basketTotal + self::$basketProductsTotal;
		return self::$basketTotal;
		}
	
	public static function getBasketDetails($opt=array()) {	
		$obj = new stdClass();
		EcomBasket::basketInit();
		EcomBasket::setBasketProductsList();
		$obj->id = self::$basketId;	
		$obj->session = self::$basketSession;		
		$obj->products = self::$basketProductsList;	
		$obj->products_number = self::$basketProductsNumber;
		$obj->products_total = self::$basketProductsTotal;
		$obj->total = EcomBasket::getBasketTotal();	
		return $obj;
		}
		
	public static function delProductToBasket($id,$opt=array()) {
		$optDef = array();
		$opt = array_merge($optDef,$opt);
		$obj = new stdClass();	
		Sql::initQuery(Config::$dbTablePrefix.'ec_basket_products',array('*'),array($id,self::$basketId,self::$basketSession),'id = ? AND id_basket = ? AND session_basket = ?','');
		Sql::deleteRecord();		
		}
		

	}
?>
<?php
/*	wscms/classes/class.EcomCustomer.php v.3.3.0. 16/05/2017 */

class EcomCustomer extends Core {
	public static $dbtable;
	
	public function __construct() {
		parent::__construct();
		self::$dbtable = Config::$dbTablePrefix().'ec_customers';
		}
		
	public static function getCustomerDetails($id,$opt) {
		$optDef = array();
		$opt = array_merge($optDef,$opt);	
		if ($id > 0) {
			Sql::initQuery(self::$dbtable,array('*'),array($id),'id = ? AND active = 1');
			$obj = Sql::getRecord();
			if (Core::$resultOp->error == 0) {
				if (isset($obj->id) && $obj->id > 0) return $obj;
				}			
			}
			return false;
		}

		
	public static function checkCustomer($sessionvars,$_lang,$opz) {
		$opzDef = array();
		$opz = array_merge($opzDef,$opz);	
		$result = false;
		/* controlla se ha 'id */
		if (isset($sessionvars['id_customer']) && $sessionvars['id_customer'] > 0) {
			/* controlla se esiste */
			Sql::initQuery(self::$dbtable,array('id'),array($sessionvars['id_customer']),'id = ? AND active = 1');
			$obj = Sql::getRecord();			
				if (Core::$resultOp->error == 0) {
					if (isset($obj->id) && $obj->id > 0) {
						$result = true;
						}	
					}	
			}
			return $result;
		}
		
	public static function sendEmailUsernameCustomer($globalSettings,$_lang,$opz) {
		$opzDef = array('to email'=>'','username'=>'');
		$opz = array_merge($opzDef,$opz);	
		$subject = (isset($_lang['cliente - soggetto email recupero username']) ? $_lang['cliente - soggetto email recupero username'] : $globalSettings['utente - soggetto email recupero username']);
		$subject = preg_replace('/%SITENAME%/',SITE_NAME,$subject);	
		$content = (isset($_lang['cliente - contenuto email recupero username']) ? $_lang['cliente - contenuto email recupero username'] : $globalSettings['utente - soggetto email recupero username']);
		$content = preg_replace('/%USERNAME%/',$opz['username'],$content);
		$content = preg_replace('/%EMAIL%/',$opz['to email'],$content);	
		$content = preg_replace('/%SITENAME%/',SITE_NAME,$content);	
		//echo '<br>subject: '.$subject;
		//echo '<br>content: '.$content;							
		$opz['content'] = $content;
		$opz['from email'] = $globalSettings['utente - from email recupero username'];
		$opz['from email label'] = $globalSettings['utente - from email label recupero username'];
		$opz['to email'] = $opz['to email'];				
		$opz['send copy'] = $globalSettings['utente - send email debug recupero username'];
		$opz['copy email'] = $globalSettings['utente - email debug recupero username'];
		//print_r($opz);				
		Mails::sendEmail($opz);
		}

	public static function sendEmailPasswordUser($globalSettings,$_lang,$opz) {
		$opzDef = array('to email'=>'','username'=>'','password'=>'');
		$opz = array_merge($opzDef,$opz);	
		$subject = (isset($_lang['cliente - soggetto email recupero password']) ? $_lang['cliente - soggetto email recupero password'] : $globalSettings['utente - soggetto email recupero password']);
		$subject = preg_replace('/%SITENAME%/',SITE_NAME,$subject);	
		$content = (isset($_lang['cliente - contenuto email recupero password']) ? $_lang['cliente - contenuto email recupero password'] : $globalSettings['utente - soggetto email recupero password']);
		$content = preg_replace('/%PASSWORD%/',$opz['password'],$content);
		$content = preg_replace('/%USERNAME%/',$opz['username'],$content);
		$content = preg_replace('/%EMAIL%/',$opz['to email'],$content);	
		$content = preg_replace('/%SITENAME%/',SITE_NAME,$content);	
		//echo '<br>subject: '.$subject;
		echo '<br>content: '.$content;							
		$opz['content'] = $content;
		$opz['from email'] = $globalSettings['utente - from email recupero password'];
		$opz['from email label'] = $globalSettings['utente - from email label recupero password'];
		$opz['to email'] = $opz['to email'];				
		$opz['send copy'] = $globalSettings['utente - send email debug recupero password'];
		$opz['copy email'] = $globalSettings['utente - email debug recupero password'];
		//print_r($opz);				
		Mails::sendEmail($opz);
		}
		
	public static function sendEmailRegistrationCustomer($globalSettings,$_lang,$opz) {
		$opzDef = array();
		$opz = array_merge($opzDef,$opz);	
		$subject = (isset($_lang['cliente - soggetto email conferma registrazione']) ? $_lang['cliente - soggetto email conferma registrazione'] : $globalSettings['cliente - soggetto email conferma registrazione']);
		$subject = preg_replace('/%SITENAME%/',SITE_NAME,$subject);	
		$content = (isset($_lang['cliente - contenuto email conferma registrazione']) ? $_lang['cliente - contenuto email conferma registrazione'] : $globalSettings['cliente - soggetto email conferma registrazione']);
		$content = preg_replace('/%URLCONFIRM%/',$opz['urlconfirm'],$content);
		$content = preg_replace('/%SITENAME%/',SITE_NAME,$content);
		$content = preg_replace('/%USERNAME%/',$_POST['username'],$content);
		$content = preg_replace('/%EMAIL%/',$_POST['email'],$content);	
		//echo '<br>subject: '.$subject;
		//echo '<br>content: '.$content;							
		$opz['content'] = $content;
		$opz['from email'] = $globalSettings['cliente - from email conferma registrazione'];
		$opz['from email label'] = $globalSettings['cliente - from email label conferma registrazione'];
		$opz['to email'] = $_POST['email'];				
		$opz['send copy'] = $globalSettings['cliente - send email debug conferma registrazione'];
		$opz['copy email'] = $globalSettings['cliente - email debug conferma registrazione'];
		//print_r($opz);				
		Mails::sendEmail($opz);
		}
		
	public static function sendEmailRegistrationStaff($globalSettings,$_lang,$opz) {
		$opzDef = array();
		$opz = array_merge($opzDef,$opz);	
		$subject = (isset($_lang['cliente - soggetto email staff conferma registrazione']) ? $_lang['cliente - soggetto email conferma registrazione'] : $globalSettings['cliente - soggetto email staff conferma registrazione']);
		$subject = preg_replace('/%SITENAME%/',SITE_NAME,$subject);	
		$content = (isset($_lang['cliente - contenuto email staff conferma registrazione']) ? $_lang['cliente - contenuto email staff conferma registrazione'] : $globalSettings['cliente - contenuto email staff conferma registrazione']);
		$content = preg_replace('/%SITENAME%/',SITE_NAME,$content);
		$content = preg_replace('/%USERNAME%/',$_POST['username'],$content);
		$content = preg_replace('/%IDUSER%/',$_POST['id_user'],$content);
		$content = preg_replace('/%EMAIL%/',$_POST['email'],$content);	
		//echo '<br>subject: '.$subject;
		//echo '<br>content: '.$content;							
		$opz['content'] = $content;
		$opz['from email'] = $globalSettings['cliente - from email conferma registrazione'];
		$opz['from email label'] = $globalSettings['cliente - from email label conferma registrazione'];
		$opz['to email'] = $globalSettings['cliente - email staff conferma registrazione'];			
		$opz['send copy'] = $globalSettings['cliente - send email debug conferma registrazione'];
		$opz['copy email'] = $globalSettings['cliente - email debug conferma registrazione'];
		//print_r($opz);				
		Mails::sendEmail($opz);
		}

	}
?>
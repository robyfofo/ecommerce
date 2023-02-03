<?php
/*
	framework siti html-PHP-Mysql
	copyright 2011 Roberto Mantovani
	http://www.robertomantovani.vr;it
	email: me@robertomantovani.vr.it
	admin/classes/class.Users.php v.4.5.1. 29/06/2020
*/

class Users extends Core {
	public static $details;

	public static $optGetOnlyActive;
	public static $optGetOnlyFromSite;
	
	public function __construct(){			
		parent::__construct();		
	}	



	public static function getUserDetails($id=0,$opz=array())
	{	
		//Sql::setDebugMode(1);
 		//Config::$debugMode = 1;

		Config::initQueryParams();
		Config::$queryParams['tables'] = Config::$DatabaseTables['users'];
		Config::$queryParams['fields'] = array('*');
		if (self::$optGetOnlyActive == true)
		{
			Config::$queryParams['where'] .= Config::$queryParams['and'].'active = 1';
			Config::$queryParams['and'] = ' AND ';
		}
		if (self::$optGetOnlyFromSite == true)
		{
			Config::$queryParams['where'] .= Config::$queryParams['and'].'from_site = 1';
			Config::$queryParams['and'] = ' AND ';
		}
		if ($id > 0 )
		{
			Config::$queryParams['where'] .= Config::$queryParams['and'].'id = ?';
			Config::$queryParams['fieldsVal'][] = intval($id);
			Config::$queryParams['and'] = ' AND ';
		}
		Sql::initQuery(Config::$queryParams['tables'],Config::$queryParams['fields'],Config::$queryParams['fieldsVal'],Config::$queryParams['where']);
		$obj = Sql::getRecord();	
		if (Core::$resultOp->error > 0) { die('errore db lettura utente');	ToolsStrings::redirect(URL_SITE.'error/db');  }	
		return $obj;	
	}

	public static function getAvatarFormData($opt) {
		$optDef = array('max size'=>400000);
		$opt = array_merge($optDef,$opt);

		$avatar = '';
		$avatar_info = '';		
		if (isset($_FILES['avatar']) && is_uploaded_file($_FILES['avatar']['tmp_name']) && $_FILES['avatar']['size'] > 0) {	
			if ($_FILES['avatar']['error'] == 0 ) {         
            $array_avatarInfo = array();
            $max_size = $opt['max size'];
            $result = @is_uploaded_file($_FILES['avatar']['tmp_name']);
            if (!$result) {
               Core::$resultOp->message = Config::$localStrings['Impossibile eseguire upload! Se è presente è stato mantenuto il file precedente!'];
               Core::$resultOp->error = 1;
  				} else {
      			$size = $_FILES['avatar']['size'];
				if ($size > $max_size) {
					Core::$resultOp->message = Config::$localStrings['Il file indicato è troppo grande! Dimensioni massime %DIMENSIONS% Kilobyte. Se il file precedente è presente è stato mantenuto il file precedente!'];
					Core::$resultOp->message = preg_replace('/%DIMENSIONS%/',($max_size / 1000),Core::$resultOp->message);     				
					Core::$resultOp->error = 1;	         	
				} else {
					$array_avatarInfo['type'] = $_FILES['avatar']['type'];
					$array_avatarInfo['nome'] = $_FILES['avatar']['name'];
					$array_avatarInfo['size'] = $_FILES['avatar']['size'];
					$avatar = @file_get_contents($_FILES['avatar']['tmp_name']);
					$avatar_info = serialize($array_avatarInfo);
				}                  
       		}
			}	else {
				Core::$resultOp->message = Config::$localStrings['Impossibile eseguire upload: problemi accesso immagine! Se è presente è stato mantenuto il file precedente!'];
				Core::$resultOp->error = 1;
			}	            
		}
		return array($avatar,$avatar_info);
	}
	
	public static function upgradePassword($id) {
		$f = array('password');
		$fv = array(self::$details->password,$id);
		//Sql::initQuery(self::$dbtable,$f,$fv ,'id = ?');
		Sql::updateRecord();		
	}
	
	public static function activate($hash) {

		//Sql::setDebugMode(1);	
		//Config::$debugMode = 1;

		// controlla se esiste
		if (Sql::countRecordQry(Config::$DatabaseTables['users'],'id','hash = ?',array($hash)) == 0) {
			Core::$resultOp->error = 1;
			Core::$resultOp->messages[] = Config::$localStrings['Errore nell attivazione di un account che non è stato trovato!'];
			return false;
		}
		Config::initQueryParams();
		Config::$queryParams['tables'] = Config::$DatabaseTables['users'];
		Config::$queryParams['fields'] = array('active');
		Config::$queryParams['fieldsVal'] = array(1,$hash);
		Config::$queryParams['where'] = 'hash = ?';
		Sql::initQuery(Config::$queryParams['tables'],Config::$queryParams['fields'],Config::$queryParams['fieldsVal'],Config::$queryParams['where']);
		Sql::updateRecord();
		if (Core::$resultOp->error > 0) die('Errore aggiornamento attivazione record users');
	}
	
	public static function upgrade($id,$postdata,$opt) {
		$optDef = array();
		$opt = array_merge($optDef,$opt);

		Sql::setDebugMode(1);	
		Config::$debugMode = 1;

		Config::initQueryParams();
		Config::$queryParams['tables'] = Config::$DatabaseTables['users'];
		list(Config::$queryParams['fields'],Config::$queryParams['fieldsVal'],Config::$queryParams['fieldsFieldsVal']) = Sql::generateFieldsParamsQuery($postdata,Config::$DatabaseTablesFields['users'],array());
		 
		/*
		ToolsStrings::dump($postdata);
		ToolsStrings::dump(Core::$resultOp);
		die();
		*/
		
		if (Core::$resultOp->error == 0) {

			Config::$queryParams['fieldsVal'][] = $id;
			Config::$queryParams['where'] .= 'id = ?';
			Config::$queryParams['and'] = ' and ';


			ToolsStrings::dump(Config::$queryParams['fields']);
			ToolsStrings::dump(Config::$queryParams['fieldsVal']);
			ToolsStrings::dump(Config::$queryParams['fieldsFieldsVal']);
			
			Sql::initQuery(Config::$queryParams['tables'],Config::$queryParams['fields'],Config::$queryParams['fieldsVal'],Config::$queryParams['where']);
			Sql::updateRecord();
			if (Core::$resultOp->error > 0) die('Errore aggiornamento record users');
			//die();
		}	
	}
	
	public static function add($postdata,$opt) {
		$optDef = array();
		$opt = array_merge($optDef,$opt);
		//Sql::setDebugMode(1);	
		//Config::$debugMode = 1;
		if (!isset($postdata['active'])) $postdata['active'] = 0;
		if (!isset($postdata['is_root'])) $postdata['is_root'] = 0;
		if (!isset($postdata['from_site'])) $postdata['from_site'] = 1;
		if (!isset($postdata['in_admin'])) $postdata['in_admin'] = 0;
		if (!isset($postdata['id_level'])) $postdata['id_level'] = 1;
		if (!isset($postdata['template'])) $postdata['template'] = 'default';
		if (!isset($postdata['created'])) $postdata['created'] = Config::$nowDateTime;
		$postdata['hash'] = password_hash(SITE_CODE_KEY.$postdata['username'].$postdata['email'],PASSWORD_DEFAULT);
		$postdata['hash'] = SanitizeStrings::base64url_encode($postdata['hash']);

		$org_passord = $_POST['password'];
		$postdata['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);	
		Config::initQueryParams();
		Config::$queryParams['tables'] = Config::$DatabaseTables['users'];
		list(Config::$queryParams['fields'],Config::$queryParams['fieldsVal'],Config::$queryParams['fieldsFieldsVal']) = Sql::generateFieldsParamsQuery($postdata,Config::$DatabaseTablesFields['users'],array());	
		if (Core::$resultOp->error > 0) return false;

		Sql::initQuery(Config::$queryParams['tables'],Config::$queryParams['fields'],Config::$queryParams['fieldsVal'],Config::$queryParams['where']);
		Sql::insertRecord();
		if (Core::$resultOp->error > 0) die('Errore inserimento record users');	

		$urlForActivation = URL_SITE.Core::$request->action.'/activate/'.$postdata['hash'];
		$othersTags = array(
			'%USERNAME%' => $_POST['username'],
			'%EMAIL%' => $_POST['email'],
			'%PASSWORD%' => $org_passord,
			'%URLFORACTIVATIONPAGE%' => $urlForActivation,

		);
		// creo email staff
	
		$staffEmailSubject = Config::$localStrings['soggetto email sezione registazione sito a staff'];
		$staffEmailSubject = ToolsStrings::replaceContentTags($staffEmailSubject,$othersTags,false);
		//echo '<br>staffEmailSubject: '.$staffEmailSubject;

		$staffEmailContent = Config::$localStrings['contenuto email sezione registazione sito a staff'];
		$staffEmailContent = ToolsStrings::replaceContentTags($staffEmailContent,$othersTags,false);
		//echo '<br>staffEmailContent: '.$staffEmailContent;
		
		$userEmailSubject = Config::$localStrings['soggetto email sezione registazione sito a user'];
		$userEmailSubject = ToolsStrings::replaceContentTags($userEmailSubject,$othersTags,false);
		//echo '<br>userEmailSubject: '.$userEmailSubject;

		$userEmailContent = Config::$localStrings['contenuto email sezione registazione sito a user'];
		$userEmailContent = ToolsStrings::replaceContentTags($userEmailContent,$othersTags,false);
		//echo '<br>userEmailContent: '.$userEmailContent;



		//die();
			
		
	}
	
	public static function getDetails($valueRif,$opt) {
		//Sql::setDebugMode(1);
		$optDef = array('fields'=>array('*'),'fields values'=>array($valueRif),'clause'=>'id = ? AND active = 1 AND from_site = 1');
		$opt = array_merge($optDef,$opt);	
		if ($valueRif != '') {
			Sql::initQuery(Config::$DatabaseTables['users'],$opt['fields'],$opt['fields values'],$opt['clause']);
			$obj = Sql::getRecord();
			if (Core::$resultOp->error == 0) {
				if (isset($obj->id) && $obj->id > 0) {
					
					$obj->has_avatar = 0;
					if (isset($obj->avatar) && $obj->avatar != '') $obj->has_avatar = 1;
					
					self::$details = $obj;
				}	
			}			
		}
		return false;
	}

	public static function checkExists($valueRif,$opt) 
	{

		$optDef = array('fields values'=>array($valueRif),'clause'=>'id = ? AND active = 1 AND from_site = 1');
		$opt = array_merge($optDef,$opt);	
		$result = false;
		Sql::initQuery(Config::$DatabaseTables['users'],array('id'),$opt['fields values'],$opt['clause']);
		$obj = Sql::getRecord();	
		if (Core::$resultOp->error == 0) {
			if (isset($obj->id) && $obj->id > 0) {
				$result = true;
			}	
		}	
		return $result;
	}
	
	public static function checkLogin($sessionvars,$_lang,$opt) 
	{
		$optDef = array();
		$opt = array_merge($optDef,$opt);	
		$result = false;
		/* controlla se ha 'id */
		if (isset($sessionvars['UsersId']) && $sessionvars['UsersId'] > 0) {
			/* controlla se esiste */
			//Sql::initQuery(self::$dbTable,array('id'),array($sessionvars['UsersId']),'id = ? AND active = 1 AND from_site = 1');
			$obj = Sql::getRecord();			
			if (Core::$resultOp->error == 0) {
				if (isset($obj->id) && $obj->id > 0) {
					$result = true;
				}	
			}	
		}
		return $result;
	}
	
	public static function checkUsernameExist($username,$opt=array()) {
		$optDef = array();
		$opt = array_merge($optDef,$opt);	
		$result = true;	
		$f = array('id');
		$fv = array($username);
		$clause = 'username = ?';
		//Sql::initQuery(self::$dbtable,$f,$fv,$clause);
		if (Sql::countRecord() == 0) { $result = false; }
		return $result;
	}
	   
	public static function checkEmailExist($email,$opt=array()) {
		$optDef = array();
		$opt = array_merge($optDef,$opt);	
		$result = true;
		$f = array('id');
		$fv = array($email);
		$clause = 'email = ?';
		//Sql::initQuery(self::$dbtable,$f,$fv,$clause);
		if (Sql::countRecord() == 0) { $result = false; }
		return $result;
	}
	
	public static function createHash($sitecodekey,$username,$email) {
		return md5($sitecodekey.$username.$email);
	}
	
	public static function parseEmailText($text,$opt=array()) 
	{
		$optDef = array();
		$opt = array_merge($optDef,$opt);	
		$text = preg_replace('/%SITENAME%/',Config::$globalSettings['site name'],$text);
		if (isset($opt['other vars']) && is_array($opt['other vars']) && count($opt['other vars']) > 0) {
			foreach ($opt['other vars'] AS $key=>$value) {
				$text = preg_replace('/'.$key.'/',$value,$text);	
			}
		}
		if (isset(self::$details->username)) $text = preg_replace('/%USERNAME%/',self::$details->username,$text);
		if (isset(self::$details->email)) $text = preg_replace('/%EMAIL%/',self::$details->email,$text);
		return $text;
	}
	
}
?>
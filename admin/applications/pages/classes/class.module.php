<?php
/* wscms/pages/class.module.php v.3.5.2. 26/04/2018 */

class Module {
	private $action;
	private $mainData;
	private $pagination;
	public $error;
	public $message;
	public $messages;	
	public $mySessionsApp;

	public function __construct($table,$action,$session) 	{
		$this->table = $table;
		$this->action = $action;
		$this->error = 0;	
		$this->message ='';
		$this->messages = array();		
		$this->mySessionsApp = $session;
		}
		
	public function getAlias($id,$alias,$title) {
		if ($alias == '') $alias = $title;
		$alias = SanitizeStrings::cleanTitleUrl($title);
		
			
		$clause = 'alias = ?';
		$fieldValues = array($alias);
		if ($id > 0) {
			$clause .= 'AND id <> ?';
			$fieldValues[] = $id;
			}
		Sql::initQuery($this->table,array('id'),$fieldValues,$clause);
		$count = Sql::countRecord();
		if (Core::$resultOp->error == 0) {
			if ($count > 0) $alias .= $alias.time();		
			}
		return $alias;
		}
	
	public function getTemplatesPage(){
		$obj = '';
		Sql::initQuery( Config::$dbTablePrefix.'pagetemplates',array('*'),array(),'active = 1','ordering DESC','');
		$obj = Sql::getRecords();
		return $obj;
		}
		
	public function getTemplatePredefinito($id=0){
		$obj = '';
		/* prende il template indicato */
		Sql::initQuery( Config::$dbTablePrefix.'pagetemplates',array('*'),array((int)$id),'active = 1 AND id = ?');
		$obj = Sql::getRecord();
		/* se non è nulla prende il predefinito */
		if(!isset($obj->id) || intval($obj->id)== 0) {
			Sql::initQuery( Config::$dbTablePrefix.'pagetemplates',array('*'),array(),'active = 1 AND predefinito = 1');
			$obj = Sql::getRecord();
			/* se è ancora nullo prende il primo */
			if(!isset($obj->id) || intval($obj->id) == 0) {
				Sql::initQuery( Config::$dbTablePrefix.'pagetemplates',array('*'),array(),'active = 1');
				$obj = Sql::getRecord();
				/* se è ancora nullo segnale errore */
				if(!isset($obj->id) || intval($obj->id)== 0) {
					$this->message = "Devi creare almeno un template per le pagine!";
					$this->error = 1;
					}				
				}			
			}		
		return $obj;
		}
		
		
	public function manageParentField() {
		Sql::initQuery( Config::$dbTablePrefix.'pages',array('parent'),array($_POST['bk_parent'],0),'parent = ?');
		//Sql::updateRecord();
	}
		
	/* gestione contenuti gestiti dal template */

	
	
	/* SEZIONE PER IL RECUPERO VAR */
	
	public function setAction($value){
		Core::$request->action = $value;
		}

	public function getMainData(){
		return $this->mainData;
		}
		
	public function getPagination(){
		return $this->pagination;
		}
		
	public function setMySessionApp($session){
		$this->mySessionsApp = $session;
		}
	
	}
?>

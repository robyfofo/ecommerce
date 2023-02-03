<?php
/*	admin/help/items.php v.4.5.1. 30/07/2019 */

switch (Core::$request->method) {
	case 'listItem':
	default;	
		$App->items = new stdClass;
		$qryFields = array('*');
		$qryFieldsValues = array();
		$qryFieldsValuesClause = array();
		$clause = '';
		Sql::initQuery($App->params->tables['item'],$qryFields,$qryFieldsValues,$clause);
		Sql::setOrder('ordering '.$App->params->ordersType['item']);
				if (Core::$resultOp->error <> 1) $obj = Sql::getRecords();
		/* sistemo i dati */
		$arr = array();
		if (is_array($obj) && is_array($obj) && count($obj) > 0) {
			foreach ($obj AS $value) {	
				$field = 'title_'.$localStrings['user'];	
				$value->title = $value->$field;
				$field = 'content_'.$localStrings['user'];	
				$value->content = $value->$field;
				$arr[] = $value;
				}
			}
		$App->items = $arr;		
		$App->pageSubTitle = '';
		$App->viewMethod = 'list';	
	break;	
}


/* SEZIONE SWITCH VISUALIZZAZIONE TEMPLATE (LIST, FORM, ECC) */

switch((string)$App->viewMethod) {
		case 'list':
			$App->templateApp = 'listItems.html';
			$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/listItems.js"></script>';
	break;	
	
	default:
	break;
}	
?>
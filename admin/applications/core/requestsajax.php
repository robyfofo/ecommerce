<?php
/**
 * Framework App PHP-MySQL
 * PHP Version 7
 * @copyright 2020 Websync
 * core/requestsajax.php v.1.0.0. 10/08/2021
*/

switch(Core::$request->method) {
    /* 
    Controlla se un dato esiste già nella tabella->campo indicata
    Parametri richiesti:
    $table @string = la tabella della ricerca
    $fieldid @string = il campo COUNT() della tabella della ricerca
    $field @string = il campo della tabella della ricerca
    $fieldsvalue @array = i valori per i campi della where e da ricercare
    $matchtype @string = il controlllo da fare (=, like, ecc)
    Risposta:
    array(
        result => 1 = il dato esiste; 0 = il dato non esiste
        messagge => eventuale messaggio
    )
    
    */
    case 'checkIfItemExistInDb':
        //Core::setDebugMode(1);
        //ToolsStrings::dump($_REQUEST);
        //ToolsStrings::dump($_POST);
        //ToolsStrings::dump($_GET);
        $table = '';
        $fieldId = 'id';
        $field = '';
        $fieldLabel = '';
        $fieldsValue = array();
        $matchType = '=';
        $customClause = '';
        $foo = 0;
        if ( isset($_REQUEST['table']) && $_REQUEST['table'] != '' ) $table = $_REQUEST['table'];
        if ( isset($_REQUEST['fieldLabel']) && $_REQUEST['fieldLabel'] != '' ) $fieldLabel = $_REQUEST['fieldLabel'];
        if ( isset($_REQUEST['fieldId']) && $_REQUEST['fieldId'] != '' ) $fieldId = $_REQUEST['fieldId'];
        if ( isset($_REQUEST['field']) && $_REQUEST['field'] != '' ) $field = $_REQUEST['field'];
        if ( isset($_REQUEST['fieldsValue']) && $_REQUEST['fieldsValue'] != '' ) $fieldsValue = $_REQUEST['fieldsValue'];
        if ( isset($_REQUEST['matchType']) && $_REQUEST['matchType'] != '' ) $matchType = $_REQUEST['matchType'];
        if ( isset($_REQUEST['customClause']) && $_REQUEST['customClause'] != '' ) $customClause = $_REQUEST['customClause'];

        //echo '<br>table: '.$table;
        //echo '<br>field: '.$field;
        if ($table != '' && $field != '') {         
            $clause = $field . $matchType . '?';
            if ($customClause != '') $clause .= ' AND ('.$customClause.')';   
            Config::$queryParams = array();
            Config::$queryParams['tables'] = $table;
            Config::$queryParams['keyRif'] = $fieldId;
            Config::$queryParams['whereClause'] = $clause;
            Config::$queryParams['fieldsValues'] = $fieldsValue;
            //ToolsStrings::dump(Config::$queryParams);
            $foo = Sql::checkIfRecordExists();
        }

        if ($fieldLabel == '') $fieldLabel = $field;
        if ($foo > 0) {
            $data['result'] = '1';
            $data['message'] = preg_replace('/%ITEM%/',$fieldLabel,$_lang['Il valore per il campo %ITEM% è già presente nel nostro database!']);
          
        } else {
            $data['result'] = '0';
            $data['message'] = preg_replace('/%ITEM%/',$fieldLabel,$_lang['Il valore per il campo %ITEM% è disponibile!']);
        }
        echo json_encode($data);
        die();
    break;

    default:
    break;
}
?>
<?php
/**
 * Framework App PHP-Mysql
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * ajax/checkIfItemExistInDb.php v.1.0.0. 06/09/2021
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);
define('PATH','../');

include_once(PATH."admin/include/configuration.inc.php");
require_once PATH."admin/classes/vendor/autoload.php";	

Config::setGlobalSettings($globalSettings);
Config::init();
Config::$globalSettings['requestoption']['defaultaction'] = 'home';
Config::$globalSettings['requestoption']['defaultpagesmodule'] = 'products';
Config::$globalSettings['requestoption']['othermodules'] = array('wishes','users','news','pages','error','products','carts','carts1','carts2');
Config::$globalSettings['requestoption']['methods'] = array('dt','ls');
Config::$globalSettings['requestoption']['coremodules'] = array();
Config::$globalSettings['requestoption']['sectionadmin'] = 0;
Config::$globalSettings['requestoption']['isRoot'] = 0;
Config::$globalSettings['requestoption']['getlasturlparam'] = array();
Config::$globalSettings['requestoption']['methods'] = array();
Core::init();

$my_session = new my_session(SESSIONS_TIME, SESSIONS_GC_TIME,SESSIONS_COOKIE_NAME);
$my_session->my_session_start();
$_MY_SESSION_VARS = array();
$_MY_SESSION_VARS = $my_session->my_session_read();

Config::$localStrings['user'] = 'it';
// cambia la sessione 
if( Core::$request->lang != '' && in_array(Core::$request->lang, Config::$globalSettings['languages'])) {
	$_MY_SESSION_VARS =  $my_session->addSessionsSingleVar($_MY_SESSION_VARS,'lang',Core::$request->lang);
}
if(!isset($_MY_SESSION_VARS['lang'])) {
	$_MY_SESSION_VARS =  $my_session->addSessionsSingleVar($_MY_SESSION_VARS,'lang',Config::$localStrings['user']);
}
//echo '<br>lingua :'.$_MY_SESSION_VARS['lang'];
Config::loadLanguageVars($_MY_SESSION_VARS['lang']);
setlocale(LC_TIME,Config::$localStrings['lista lingue abbreviate'][Config::$localStrings['user']], Config::$localStrings['charset date']);
Config::initDatabaseTables('../');

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
  
//Core::setDebugMode(1);
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
    $data['message'] = preg_replace('/%ITEM%/',$fieldLabel,Config::$localStrings['Il valore per il campo %ITEM% è già presente nel nostro database!']);
    
} else {
    $data['result'] = '0';
    $data['message'] = preg_replace('/%ITEM%/',$fieldLabel,Config::$localStrings['Il valore per il campo %ITEM% è disponibile!']);
}
echo json_encode($data);

?>
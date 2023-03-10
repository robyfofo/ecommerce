<?php

/**
 * Framework siti html-PHP-Mysql
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/classes/class.Form.php v.1.0.1. 31/08/2021
 */

class Form extends Core
{

	public function __construct()
	{
		parent::__construct();
	}

	public static function getUpdateRecordFromPostResults($id, $resultOp, $lang, $opt)
	{
		$optDef = array('label modified' => 'voce modificata', 'label modify' => 'modifica voce', 'label insert' => 'inserisci voce', 'label modify applied' => 'modifiche applicate');
		$opt = array_merge($optDef, $opt);
		$viewMethod = '';
		$pageSubTitle = '';
		$message = $resultOp->message;
		if ($resultOp->error == 1) {
			$pageSubTitle = ucfirst($opt['label modify']);
			$viewMethod = 'formMod';
		} else {
			if (isset($_POST['submitForm'])) {
				$viewMethod = 'list';
				$message = ucfirst($opt['label modified']) . '!';
			} else {
				if (isset($_POST['id'])) {
					$id = $_POST['id'];
					$pageSubTitle = $opt['label modify'];
					$viewMethod = 'formMod';
					$message = ucfirst($opt['label modify applied']) . '!';
				} else {
					$viewMethod = 'formNew';
					$pageSubTitle = $opt['label insert'];
				}
			}
		}
		return array($id, $viewMethod, $pageSubTitle, $message);
	}


	public static function getInsertRecordFromPostResults($id, $resultOp, $lang, $opt)
	{
		$optDef = array('label inserted' => 'voce inserita', 'label insert' => 'inserisci voce');
		$opt = array_merge($optDef, $opt);
		$viewMethod = '';
		$pageSubTitle = '';
		$message = $resultOp->message;
		if ($resultOp->error == 1) {
			$pageSubTitle = $opt['label insert'];
			$viewMethod = 'formNew';
		} else {
			$viewMethod = 'list';
			$message = ucfirst($opt['label inserted']) . '!';
		}
		return array($id, $viewMethod, $pageSubTitle, $message);
	}


	public static function checkRequirePostByFields($fields, $_lang, $opz)
	{
		$opzDef = array();
		$opz = array_merge($opzDef, $opz);
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $key => $value) {
				$namefield = $key;
				if (isset($value['required']) && $value['required'] == true && (!isset($_POST[$namefield]) || (isset($_POST[$namefield]) && $_POST[$namefield] == ''))) {
					self::$resultOp->error = 1;
					self::$resultOp->message = preg_replace('/%FIELD%/', $value['label'], $_lang['Devi inserire il campo %FIELD%!']) . '<br>';
				}
			}
		}
	}

	public static function parsePostByFieldsCustom($fieldDetails,$fields,$fieldsVal,$opt) 
	{
		$optDef = array('stripmagicfields' => true);
		$opt = array_merge($optDef, $opt);

		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $value) {
				$fieldName = $value;
				$fieldType = (isset($fieldDetails['type']) ? $fieldDetails['type'] : '');
				$fieldLabel = (isset($fieldDetails['label']) ? $fieldDetails['label'] : $fieldName);
				$fieldPostValue = (isset($fieldsVal[$fieldName]) ? $fieldsVal[$fieldName] : '');
				//echo '<br>namefield: '.$fieldName;
				//echo ' - '.$_POST[$fieldName];
				
				$labelField = (isset($value['label']) ? $value['label'] : '');

				/* aggiorna con il default se vuoti */
				if (!isset($fieldPostValue)) {
					if (isset($fieldDetails['defValue'])) $fieldPostValue = $fieldDetails['defValue'];
				}

				// valida i i tipi di campo (se ?? text int varchar ecc)  e fa dei controlli. Es. se e varchar|255 controlla che non si superino i 255 caratteri		
				self::doValidationFieldType($fieldName,$fieldLabel,$fieldType,$fieldPostValue);

				/* controlla se e richiesto */
				if (isset($value['required']) && $value['required'] == true) {
					//echo '<br>valida '.$fieldName;
					self::checkIfRequired($fieldName,$value,$_POST);
				}

				// valida i campi se richiesto
				if (isset($value['validate']) && $value['validate'] != false) {
					self::doFieldValidation($fieldName,$value,$_POST,$fieldLabel,$fieldPostValue);				
				}

				/* aggiunge gli slashes */
				if ($opt['stripmagicfields'] == true && isset($_POST[$fieldName])) $_POST[$fieldName] = SanitizeStrings::stripMagic($_POST[$fieldName]);
			}
		}


	}

	public static function parsePostByFields($fields, $opt)
	{
		//print_r($fields);
		$optDef = array('stripmagicfields' => true);
		$opt = array_merge($optDef, $opt);

		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $fieldName => $fieldDetails) {
				$fieldType = (isset($fieldDetails['type']) ? $fieldDetails['type'] : '');
				$fieldLabel = (isset($fieldDetails['label']) ? $fieldDetails['label'] : $fieldName);
				$fieldPostValue = (isset($_POST[$fieldName]) ? $_POST[$fieldName] : '');

				//echo '<br>namefield: '.$fieldName;
				//echo ' - '.$_POST[$fieldName];

				/* aggiorna con il default se vuoti */
				if (!isset($_POST[$fieldName])) {
					if (isset($fieldDetails['defValue'])) $_POST[$fieldName] = $fieldDetails['defValue'];
				}

			

				/* controlla se e richiesto */
				if (isset($value['required']) && $value['required'] == true) {
					//echo '<br>valida '.$fieldName;
					self::checkIfRequired($fieldName,$value,$_POST);
				}

				// valida i campi se richiesto
				if (isset($fieldDetails['validate']) && $fieldDetails['validate'] != false) {
					$returnvalue = self::doFieldValidation($fieldName,$fieldDetails,$_POST,$fieldLabel,$fieldPostValue);				
					if ($returnvalue != '') {
						$_POST[$fieldName] = $returnvalue;	
						$fieldPostValue = $returnvalue;
					}

				}

				// forza il valore
				if ($_POST[$fieldName] == '' && (isset($fieldDetails['forcedValue']) && $fieldDetails['forcedValue'] != '')) $_POST[$fieldName] = $fieldDetails['forcedValue']; 

				// valida i i tipi di campo (se ?? text int varchar ecc)  e fa dei controlli. Es. se e varchar|255 controlla che non si superino i 255 caratteri		
				self::doValidationFieldType($fieldName,$fieldLabel,$fieldType,$fieldPostValue);

				/* aggiunge gli slashes */
				if ($opt['stripmagicfields'] == true && isset($_POST[$fieldName])) $_POST[$fieldName] = SanitizeStrings::stripMagic($_POST[$fieldName]);
			}
		}
	}

	public static function doValidationFieldType($fieldName,$fieldLabel,$fieldType,$fieldPostvalue)
	{
		
		/*
		echo '<br>valida campo type: '.$fieldName;
		echo '<br>label: '.$fieldLabel;
		echo '<br>type: '.$fieldType;
		echo '<br>valore: '.$fieldPostvalue;
		echo '<br>------------------';
		*/
	
		$foo = explode('|',$fieldType);
		switch ($foo[0]) {
			case 'float':
				$valueRif = (isset($foo[1]) ? intval($foo[1]) : 0);
				$res = self::isTrueFloat($fieldPostvalue);
				if ($res == false) {
					Config::$resultOp->error = 1;
					$messaggio = preg_replace( array('/%ITEM%/'), array($fieldLabel,$valueRif), Config::$localStrings['Il valore per il campo %ITEM% deve essere in formato virgola mobile!'] );
					if ($messaggio != '') Config::$resultOp->messages[$fieldName] = $messaggio;
				}
			break;
			case 'varchar':
				$valueRif = (isset($foo[1]) ? intval($foo[1]) : 0);

				//echo '<br>valueRif: '.$valueRif;
				//echo '<br>fieldPostvalue: '.$fieldPostvalue;

				if ($valueRif > 0) {
					$res = self::validateMaxCharsInString($fieldPostvalue,$valueRif);

					//echo ($res == true ? 'true' : 'false');
					if ($res == true) {
						Config::$resultOp->error = 1;
						$messaggio = preg_replace( array('/%ITEM%/','/%VALUERIF%/'), array($fieldLabel,$valueRif), Config::$localStrings['Il campo %ITEM% NON deve superare i %VALUERIF% caratteri!'] );
						if ($messaggio != '') Config::$resultOp->messages[$fieldName.'field'] = $messaggio;
					}

				}
			break;	

			default:
			break;
		}	
	}

	/* 
		creal la vaidazione del capo
		$result @boolean 0 (falsa) la validazione non ?? passata; 1 (true) la validazione ?? corretta

	*/
	public static function doFieldValidation($fieldName,$fieldDetails,$fieldPostValueArray,$fieldLabel,$fieldPostValue) 
	{
		
		/*
		echo '<br>fieldname: '.$fieldName;
		//echo '<br>fieldDetails: ';
		//ToolsStrings::dump($fieldDetails);
		//ToolsStrings::dump($fieldPostValueArray);
		echo '<br>fieldPostValue: '.$fieldPostValue;
		echo '<br>-----------';
		*/
		
		
		list($returnvalue,$result,$returnmessage) = self::validateField($fieldName, $fieldDetails, $fieldPostValueArray,$fieldLabel,$fieldPostValue);
		
		//echo '<br>'.($result == false ? 'false' : 'true');
		if ($result == false) {
			Config::$resultOp->error = 1;
			$messaggio = preg_replace( '/%ITEM%/', $fieldName, Config::$localStrings['Il valore per il campo %ITEM% non ?? stato validato!'] );
			if (isset($fieldDetails['label'])) $messaggio = preg_replace( '/%ITEM%/', $fieldDetails['label'], Config::$localStrings['Il valore per il campo %ITEM% non ?? stato validato!'] );
			if ($returnmessage != '') $messaggio = $returnmessage;
			if (isset($fieldDetails['errorValidateMessage'])) $messaggio = $fieldDetails['errorValidateMessage'];
			if ($messaggio != '') Config::$resultOp->messages[$fieldName.'-validation'] = $messaggio;
		}
		return $returnvalue;
	}

	public static function checkIfRequired($fieldName,$fieldDetails,$fieldPostValue) {
		if (!isset($fieldPostValue[$fieldName]) || (isset($fieldPostValue[$fieldName]) && $fieldPostValue[$fieldName] == '')) {
			self::$resultOp->error = 1;
			$messaggio = '';
			if (isset($fieldDetails['label'])) $messaggio = preg_replace( '/%ITEM%/', $fieldDetails['label'], Config::$localStrings['Devi inserire il campo %ITEM%!'] );
			if (isset($fieldDetails['errorMessage'])) $messaggio = $fieldDetails['errorMessage'];
			if ($messaggio != '') self::$resultOp->messages[$fieldName] = $messaggio;
		}
	}

	/* la differenza tra la precedente che passa il singolo valore POST e non l'intero array POST */
	public static function checkIfFieldValueIsRequired($fieldName,$fieldDetails,$fieldValue) {
		if (!isset($fieldValue) || (isset($fieldValue) && $fieldValue == '')) {
			self::$resultOp->error = 1;
			$messaggio = '';
			if (isset($fieldDetails['label'])) $messaggio = preg_replace( '/%ITEM%/', $fieldDetails['label'], Config::$localStrings['Devi inserire il campo %ITEM%!'] );
			if (isset($fieldDetails['errorMessage'])) $messaggio = $fieldDetails['errorMessage'];
			if ($messaggio != '') self::$resultOp->messages[$fieldName] = $messaggio;
		}
	}

	/* 
		valida i campi in base al tipo
		$result @boolean 0 (falsa) la validazione non ?? passata; 1 (true) la validazione ?? corretta

	*/
	public static function validateField($fieldName, $fieldDetails, $fieldPostValueArray,$fieldLabel,$fieldPostValue)
	{
		$returnvalue = $fieldPostValue;
		$result = true;
		$message = '';
		//echo 'fieldPostValue: '.$fieldPostValue;
		switch ($fieldDetails['validate']) {
			case 'maxchar':
				$valuerif = (isset($fieldDetails['valuerif']) ? $fieldDetails['valuerif'] : 0);
				$valuepost = (isset($fieldPostValue) ? $fieldPostValue : '');
				if ($valuerif > 0) {
					//echo '<br>valida numero!';
					if (mb_strlen($valuepost) > $valuerif) $result = false;
				}
				$returnvalue = $valuepost;

			break;
			case 'int':
				$returnvalue = self::validateInt($fieldPostValue);
			break;
			case 'issameintvalue':
				$returnvalue = false;
				$valuepost = (isset($fieldPostValue) ? $fieldPostValue : '');
				$valuerif = (isset($fieldDetail['valuerif']) ? $fieldDetail['valuerif'] : '');
				if ($valuepost != '' && $valuerif != '') {
					$valuepost = intval($fieldPostValue);
					$valuerif = intval($fieldDetails['valuerif']);
					$result = ($valuepost == $valuerif ? true : false);
				}
			break;
			case 'isemail':
				$result = true;
				if ($fieldPostValue != '') {
					$foo = self::validateEmail($fieldPostValue);
					$result = ($foo == false ? false : true);
				}		
			break;
			case 'json':
				$result = json_decode($fieldPostValue);
				if (json_last_error() === JSON_ERROR_NONE) {
					$returnvalue =  $fieldPostValue;
				} else {
					self::$resultOp->error = 1;
					self::$resultOp->messages[] = preg_replace('/%ITEM%/', $fieldDetails['label'], 'il campo %ITEM% deve essere in formato json valido!');
					$returnvalue =  '[]';
				}
			break;
			case 'float':
				$result = self::isTrueFloat($fieldPostValue);
				if ($result ==  false) {
					if (isset($fieldDetails['defValue']) && $fieldDetails['defValue'] != '') {
						$returnvalue = $fieldDetails['defValue'];
						$result = true;
					} else {

						$returnvalue = $fieldDetails['defValue'];
						$message = self::$resultOp->messages[] = preg_replace('/%ITEM%/', $fieldDetails['label'], Config::$localStrings['Il valore per il campo %ITEM% deve essere in formato virgola mobile!']);
					}
				}
			break;
			case 'datetimeiso':
				if ($returnvalue == '') $returnvalue = $fieldDetails['defValue'];
				$result = self::validateDatetimeIso($returnvalue);
				if ($result == false) {
					$message = preg_replace('/%FIELD%/',$fieldLabel,Config::$localStrings['La data %FIELD% non ?? valida!']);
				}
			break;
			case 'minmax':
				$minvalue = (isset($fieldDetails['valuesRif']['min']) && $fieldDetails['valuesRif']['min'] != '' ? $fieldDetails['valuesRif']['min'] : 0);
				$maxvalue = (isset($fieldDetails['valuesRif']['max']) && $fieldDetails['valuesRif']['max'] != '' ? $fieldDetails['valuesRif']['max'] : 0);
				$result = self::validateMinMaxValues($fieldPostValue, $minvalue, $maxvalue);
				if ($result == false) {
					$s = Config::$localStrings['Il campo %FIELD% deve avere un valore superiore o uguale a %MIN% e inferiore o uguale a %MAX%!'];
					$s = preg_replace('/%MIN%/', $minvalue, $s);
					$s = preg_replace('/%MAX%/', $maxvalue, $s);
					$message = preg_replace('/%FIELD%/', $fieldLabel, $s);
				}
			break;
			case 'time':
				$result = self::validateTime($fieldPostValue);
				if ($result == false) {
					$s = Config::$localStrings['Il campo %FIELD% non ?? un tempo valido!'];
					$message = preg_replace('/%FIELD%/', $fieldLabel, $s);
				}
			break;
			case 'explodearray':
				$opz = (isset($value['opz']) ? $value['opz'] : array());
				$returnvalue = self::validateExplodearray($fieldPostValue, $opz);
				break;

			case 'timepicker':
				if (!isset($fieldDetails['defValue'])) $fieldDetails['defValue'] = date('H:i:s');
				$time = DateFormat::dateFormating($fieldPostValue, 'H:i:s', false, $fieldDetails['defValue']);
				$returnvalue = $time;
			break;
			case 'telephonenumber':
				//echo 'fieldPostValue: '.$fieldPostValue;
				$result = self::validateTelephoneNumber($fieldPostValue);
				//echo '<br>'.($result == false ? 'false' : 'true');
			break;	
			case 'datetimepicker':
				$datetime = DateFormat::dateFormating($fieldPostValue, 'Y-m-d H:i:s', false, $fieldDetails['defValue']);
				$returnvalue = $datetime;
			break;		
			case 'datepicker':
				if (!isset($value['defValue'])) $value['defValue'] = date('Y-m-d');
				//$date = DateFormat::convertDatepickerToIso($_POST[$namefield], $_lang['datepicker data format'], 'Y-m-d', $value['defValue']);
				//$returnvalue = $date;
			break;
			case 'username':
				$result = self::validateVariableUsername($fieldPostValue);
			break;
			case 'currency':
				$result = self::validateCurrency($returnvalue);
				if ($result == false) {
					$message = preg_replace('/%FIELD%/',$fieldLabel,Config::$localStrings['Il valore %FIELD% non ?? di un formato valuta!']);	
				}
			break;
			default:
				$returnvalue = '';
			break;
		}
		//echo '<br>srt: '.$returnvalue;
		return array($returnvalue,$result,$message);
	}

	/* VALITAZIONE CAMPI */

	public static function validateExplodearray($array, $opz)
	{
		$opzDef = array('delimiter' => ',');
		$opz = array_merge($opzDef, $opz);
		if (is_array($array)) {
			$array = implode( $opz['delimiter'],$array );
		}
		return $array;
	}
	public static function validateTime($value)
	{
		return false;
	}
	public static function validateDatetimeIso($value)
	{
		//echo '<br>str: '.$value;
		$result = DateFormat::checkDateFormat($value, 'Y-m-d H:i:s');
		//echo '<br>'.($result == true ? 'true' : 'false');
		return $result;
	}
	public static function validateInt($value)
	{
		return intval($value);
	}
	public static function validateFloat($value)
	{
		echo 'valida float';
	}
	public static function validateInputFloat($fieldName)
	{
		return filter_input(INPUT_POST, $fieldName, FILTER_VALIDATE_FLOAT);
	
	}

	public static function validateMinMaxValues($valuesrif, $minvalue, $maxvalue)
	{
		if ($valuesrif < $minvalue || $valuesrif > $maxvalue) {
			return false;
		}
		return true;
	}
	public static function validateEmail($email)
	{
		//by Femi Hasani [www.vision.to]
		if (!preg_match("/^[\w\.-]{1,}\@([\da-zA-Z-]{1,}\.){1,}[\da-zA-Z-]+$/", $email)) return false;
		list($prefix, $domain) = preg_split("/@/", $email);
		if (function_exists("getmxrr") && getmxrr($domain, $mxhosts)) {
			return true;
		} elseif (@fsockopen($domain, 25, $errno, $errstr, 5)) {
			return true;
		} else {
			return false;
		}
	}
	public static function validateTelephoneNumber($telephone)
	{
		return ( !preg_match("/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im", $telephone)  ?  false : true);
	}
	
	public static function isTrueFloat($f)
	{
    	return ($f == (string)(float)$f);
	} 

	public static function validateZIPcode($v, $country = 'IT')
	{
		switch ($country) {
			case 'IT':
			case 'DE':
			case 'FR':
			case 'ES':
			case 'FI':
			case 'GR':
			case 'MC':
			case 'SM':
				if (!preg_match("/^[0-9]{5}$/", $v)) {
					//return "Invalid postal code";
					return false;
				} else {
					return true;
				}
				break;
			case 'GB':
				if (!preg_match("/^[A-Z]{1}[0-9A-Z]{3,5}[A-Z]{1}$/", $v)) {
					//return "Invalid postal code";
					return false;
				} else {
					return true;
				}
				break;
			case 'LU':
				if (!preg_match("/^L[U]{0,1}[0-9]{4}$/", $v)) {
					//return "Invalid postal code";
					return false;
				} else {
					return true;
				}
				break;
			case 'AD':
				if (!preg_match("/^AD[0-9]{3}$/", $v)) {
					//return "Invalid postal code";
					return false;
				} else {
					return true;
				}
				break;
			case 'PL':
				if (!preg_match("/^[0-9]{2}-[0-9]{3}$/", $v)) {
					//return "Invalid postal code";
					return false;
				} else {
					return true;
				}
				break;
			case 'PT':
				if (!preg_match("/^[0-9]{4}-[0-9]{3}$/", $v)) {
					//return "Invalid postal code";
					return false;
				} else {
					return true;
				}
				break;
			case 'SE':
				if (!preg_match("/^[0-9]{3} [0-9]{2}$/", $v)) {
					//return "Invalid postal code";
					return false;
				} else {
					return true;
				}
				break;
			case 'AT':
			case 'BE':
			case 'CH':
			case 'DK':
			case 'GL':
			case 'HU':
			case 'NL':
			case 'NO':
			case 'SI':
				if (!preg_match("/^[0-9]{4}$/", $v)) {
					// return "Invalid postal code";
					return false;
				} else {
					return true;
				}
				break;
			case 'FO':
			case 'IS':
				if (!preg_match("/^[0-9]{3}$/", $v)) {
					//return "Invalid postal code";
					return false;
				} else {
					return true;
				}
				break;
			default:  // per i paesi senza check vedo solo se c'e' ma non faccio i controlli formali
				if (empty($v)) {
					//return "Postal code empty";
					return false;
				} else {
					return true;
				}
				break;
		}
	}
	public static function validateIBAN($v)
	{
		$val = str_replace(' ', '', $v);
		if (strlen($val) < 5 or strlen($val) > 34) {
			return false;
		}
		$b = substr($val, 4) . substr($val, 0, 4);
		$r = 0;
		for ($i = 0; $i < strlen($b); $i++) {
			$c = ord(substr($b, $i, 1));
			if ($c <= 57 and $c >= 48) { // number
				if ($i == strlen($b) - 4 || $i == strlen($b) - 3) { // Positions 1 and 2 cannot contain  numbers
					return false;
				}
				$k = $c - 48;
			} elseif ($c <= 90 and $c >= 65) { // char
				if ($i == strlen($b) - 1 || $i == strlen($b) - 2) { // Positions 1 and 2 cannot contain  letters
					return false;
				}
				$k = $c - 55;
			} else { //char not valid
				return false;
			}
			if ($k > 9) {
				$r = (100 * $r + $k) % 97;
			} else {
				$r = (10 * $r + $k) % 97;
			}
		}
		if ($r != 1) {
			return false;
		}
		return true;
	}
	public static function validateVariableUsername($value)
	{
		$aValid = array('-', '_', '.', ',', '?', '#', '!');

		if (!ctype_alnum(str_replace($aValid, '', $value))) {
			return false;
		} else {
			return true;
		}
	}
	public static function validateCurrency($value) 
	{
		return preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $value);
	}
	public static function validateMaxCharsInString($valueCheked,$valueRif)
	{
		//echo '<br>valueCheked: '.$valueCheked;
		//echo '<br>valueRif: '.$valueRif;
		return (mb_strlen($valueCheked) > $valueRif ? true : false);
	}
	
		public static function validateVAT($pi, $country = 'IT')
	{
		$validation = true;
		$message = '';

		if ($pi == '') {
			return array(false,'');
		}
		switch ($country) {
			case 'IT':
				// -- BEGIN ITALIAN CHECK
				if (strlen($pi) != 11) {
					$message = "La lunghezza della partita IVA non &egrave;\n" ."corretta: la partita IVA dovrebbe essere lunga\n" ."esattamente 11 caratteri.\n";
					$validation = false;
					return array($validation,$message);
				}
				if (!preg_match("/^[0-9]+$/", $pi)) {
					$message = "La partita IVA contiene dei caratteri non ammessi:\n" ."la partita IVA dovrebbe contenere solo cifre.\n";
					$validation = false;
					return array($validation,$message);
				}
				$s = 0;
				for ($i = 0; $i <= 9; $i += 2) {
					$s += ord($pi[$i]) - ord('0');
				}
				for ($i = 1; $i <= 9; $i += 2) {
					$c = 2 * (ord($pi[$i]) - ord('0'));
					if ($c > 9) $c = $c - 9;
					$s += $c;
				}
				if ((10 - $s % 10) % 10 != ord($pi[10]) - ord('0')) {
					$message = "La partita IVA non &egrave; valida:\n" ."il codice di controllo non corrisponde.";
					$validation  = false;
					return array($validation,$message);
				}
				// -- END ITALIAN CHECK
				break;
				// -- HERE CODE FOR CHECK OTHER COUNTRY
			default:
			break;
		}
		//echo '<br>validation: '.($validation == true ? 'true' : 'false').'<br>';
		return array($validation,$message);
	}

	public static function validateCF($cf, $country = 'IT')
	{

		$validation = true;
		$message = '';
		if ($cf == '') {
			$validation = true;
			$message = '';
			//die(1);
		}
		switch ($country) {
			case 'IT':
				// -- BEGIN ITALIAN CHECK
				if (strlen($cf) == 11) { // e' un codice fiscale di persona giuridica
					list($validation,$message) = self::validateVAT($cf, $country = 'IT');
					return array($validation,$message);

				}
				if (strlen($cf) != 16) {
					$message = "La lunghezza del codice fiscale non &egrave;\n" . "corretta: il codice fiscale dovrebbe essere lungo\n" . "esattamente 16 caratteri.";
					$validation = false;
					return array($validation,$message);
				}
				$cf = strtoupper($cf);
				if (!preg_match("/^[A-Z0-9]+$/", $cf)) {
					$message = "Il codice fiscale contiene dei caratteri non validi:\n" . "i soli caratteri validi sono le lettere e le cifre.";
					$validation = false;
					return array($validation,$message);
				}
				$s = 0;
				for ($i = 1; $i <= 13; $i += 2) {
					$c = $cf[$i];
					if ('0' <= $c && $c <= '9') {
						$s += ord($c) - ord('0');
					} else {
						$s += ord($c) - ord('A');
					}
				}

				for ($i = 0; $i <= 14; $i += 2) {
					$c = $cf[$i];
					switch ($c) {
						case '0':
							$s += 1;
							break;
						case '1':
							$s += 0;
							break;
						case '2':
							$s += 5;
							break;
						case '3':
							$s += 7;
							break;
						case '4':
							$s += 9;
							break;
						case '5':
							$s += 13;
							break;
						case '6':
							$s += 15;
							break;
						case '7':
							$s += 17;
							break;
						case '8':
							$s += 19;
							break;
						case '9':
							$s += 21;
							break;
						case 'A':
							$s += 1;
							break;
						case 'B':
							$s += 0;
							break;
						case 'C':
							$s += 5;
							break;
						case 'D':
							$s += 7;
							break;
						case 'E':
							$s += 9;
							break;
						case 'F':
							$s += 13;
							break;
						case 'G':
							$s += 15;
							break;
						case 'H':
							$s += 17;
							break;
						case 'I':
							$s += 19;
							break;
						case 'J':
							$s += 21;
							break;
						case 'K':
							$s += 2;
							break;
						case 'L':
							$s += 4;
							break;
						case 'M':
							$s += 18;
							break;
						case 'N':
							$s += 20;
							break;
						case 'O':
							$s += 11;
							break;
						case 'P':
							$s += 3;
							break;
						case 'Q':
							$s += 6;
							break;
						case 'R':
							$s += 8;
							break;
						case 'S':
							$s += 12;
							break;
						case 'T':
							$s += 14;
							break;
						case 'U':
							$s += 16;
							break;
						case 'V':
							$s += 10;
							break;
						case 'W':
							$s += 22;
							break;
						case 'X':
							$s += 25;
							break;
						case 'Y':
							$s += 24;
							break;
						case 'Z':
							$s += 23;
							break;
					}
				}
				if (chr($s % 26 + ord('A')) != $cf[15]) {
					$message = "Il codice fiscale non &egrave; corretto:\n" . "il codice di controllo non corrisponde.";
					$validation = false;
					return array($validation,$message);
				}
				// -- END ITALIAN CHECK
				break;
				// -- HERE CODE FOR CHECK OTHER COUNTRY
			default:
				
			break;
		}

		//echo '<br>validation: '.($validation == true ? 'true' : 'false').'<br>';
		return array($validation,$message);
	}

}

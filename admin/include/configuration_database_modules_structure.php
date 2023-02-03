<?php

// whises
$DatabaseTables['wishes']  = self::$dbTablePrefix . 'wishes';
$DatabaseTables['whises products']  = self::$dbTablePrefix . 'wishes_products';

// wareouse products
$DatabaseTables['warehouse products']  = self::$dbTablePrefix . 'warehouse_products';
$DatabaseTablesFields['warehouse products'] = array(
	'id'												=> array(
		'label'											=> 'ID',
		'required'										=> false,
		'type'											=> 'int|8',
		'autoinc'										=> true,
		'primary'										=> true
	),
	'users_id'											=> array(
		'label'											=> Config::$localStrings['proprietario'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'int|8',
		'defValue'										=> 0
	),
	'categories_id'										=> array(
		'label'											=> 'ID Cat',
		'required'										=> false,
		'type'											=> 'int|8'
	),
	'alias'												=> array(
		'label'											=> Config::$localStrings['alias'],
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255'
	),
	'price_unity'										=> array(
		'label'											=> Config::$localStrings['prezzo unitario'],
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'float|10,2',
		'defValue'										=> '0.00',
		'validate'										=> 'float',
		'errorValidateMessage'							=> 'error validate message custom'
	),
	'price_sconto'										=> array(
		'label'											=> Config::$localStrings['sconto'],
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'float|10,2',
		'defValue'										=> '0.00',
		'validate'										=> 'float',
		'errorValidateMessage'							=> 'error validate message custom'
	),
	'tax'												=> array(
		'label'											=> Config::$localStrings['iva'],
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'float|10,2',
		'defValue'										=> '0.00',
		'validate'										=>'float'
	),
	'ordering'											=> array(
		'label'											=> Config::$localStrings['ordinamento'],
		'required'										=> false,
		'type'											=> 'int|8',
		'defValue'										=> 1
	),
	'filename'											=> array(
		'label'											=> Config::$localStrings['immagine'],
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255'
	),
	'org_filename'										=> array(
		'label'											=> Config::$localStrings['nome file originale'],
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255'
	),
	'id_tags'											=> array(
		'label'											=> 'Id '.Config::$localStrings['tags'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'text',
		'defValue'										=> ''
	),
	'is_new'											=> array(
		'label'											=> Config::$localStrings['nuovo'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'int|1',
		'validate'										=> 'int',
		'defValue'										=> '0'
	),
	'is_promo'											=> array(
		'label'											=> Config::$localStrings['promozione'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'int|1',
		'validate'										=>	'int',
		'defValue'										=> '0'
	),
	'created'											=> array(
		'label'											=> Config::$localStrings['creazione'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'datatime',
		'defValue'										=> Config::$nowDateTimeIso,
		'validate'										=> 'datetimeiso',
		'forcedValue'                   				=> Config::$nowDateTimeIso
	),
	'active'											=> array(
		'label'											=> Config::$localStrings['attiva'],
		'required'										=> false,
		'type'											=> 'int|1',
		'validate'										=> 'int',
		'defValue'										=> '0',
		'forcedValue'      								=> 1
	)
);
foreach(Config::$globalSettings['languages'] AS $lang) {
	$required = ($lang == Config::$localStrings['user'] ? true : false);
	$DatabaseTablesFields['warehouse products']['meta_title_'.$lang] = array(
		'label'											=> ucfirst(Config::$localStrings['titolo']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','300',Config::$localStrings['massimo %NUMBER% caratteri']),
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255',
		'validate'										=> 'maxchar',
		'valuerif'										=> 255,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,'300'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
	$DatabaseTablesFields['warehouse products']['meta_description_'.$lang] = array(
		'label'											=> ucfirst(Config::$localStrings['descrizione']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','300',Config::$localStrings['massimo %NUMBER% caratteri']),
		'searchTable'									=> true,'required'=>false,
		'type'											=> 'varchar|300',
		'validate'										=> 'maxchar',
		'valuerif'										=> 300,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['descrizione']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,'300'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
	$DatabaseTablesFields['warehouse products']['meta_keyword_'.$lang] = array(
 		'label'											=> ucfirst(Config::$localStrings['keyword']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','255',Config::$localStrings['massimo %NUMBER% caratteri']),
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255',
		'validate'										=> 'maxchar',
		'valuerif'  									=> 255,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['keyword']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
	$DatabaseTablesFields['warehouse products']['title_seo_'.$lang] = array(
		'label'											=> ucfirst(Config::$localStrings['titolo']).' '.strtoupper(Config::$localStrings['seo']).' '.$lang,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','255',Config::$localStrings['massimo %NUMBER% caratteri']),
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=>'varchar|255',
		'validate'										=> 'maxchar',
		'valuerif'  									=> 255,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']).' '.strtoupper(Config::$localStrings['seo']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
	$DatabaseTablesFields['warehouse products']['title_'.$lang] = array(
		'label'											=> Config::$localStrings['titolo'].' '.$lang,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','255',Config::$localStrings['massimo %NUMBER% caratteri']),
		'searchTable'									=> true,
		'required'										=> $required,
		'type'											=> 'varchar|255',
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
	$DatabaseTablesFields['warehouse products']['content_'.$lang] = array(
		'label'											=> Config::$localStrings['descrizione'].' '.$lang,
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'mediumtext',
		'defValue'										=> ''
	);
	$DatabaseTablesFields['warehouse products']['summary_'.$lang] = array(
		'label'											=> Config::$localStrings['sommario'].' '.$lang,
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'text',
		'defValue'										=> ''
	);
}

// warehouse categories
$DatabaseTables['warehouse categories']  = self::$dbTablePrefix . 'warehouse_categories';
$DatabaseTablesFields['warehouse categories'] = array(
	'id'												=> array(
		'label'											=> 'ID',
		'required'										=> false,
		'type'											=> 'int|8',
		'autoinc'										=> true,
		'primary'										=> true
	),
	'parent'											=> array(
		'label'											=> 'Parent',
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'int|8',
		'defValue'										=> 0
	),
	'users_id'											=> array(
		'label'											=> Config::$localStrings['proprietario'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'int|8',
		'defValue'										=> 0
	),
	'alias'												=> array(
		'label'											=> Config::$localStrings['alias'],
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255',
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']),'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	),
	'ordering'											=> array(
		'label'											=> Config::$localStrings['ordinamento'],
		'required'										=> false,
		'type'											=> 'int|8',
		'defValue'										=> 1
	),
	'filename'											=> array(
		'label'											=> Config::$localStrings['immagine'],
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255'
	),
	'org_filename'										=> array(
		'label'											=> Config::$localStrings['nome file originale'],
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=>'varchar|255'
	),
	'id_tags'											=> array(
		'label'											=> 'Id '.Config::$localStrings['tags'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'text',
		'defValue'										=> ''
	),
	'created'											=> array(
		'label'											=> Config::$localStrings['creazione'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'datatime',
		'defValue'										=> Config::$nowDateTimeIso,
		'validate'										=> 'datetimeiso',
		'forcedValue'                   				=> Config::$nowDateTimeIso
	),
	'active'											=> array(
		'label'											=> Config::$localStrings['attiva'],
		'required'										=> false,
		'type'											=> 'int|1',
		'validate'										=> 'int',
		'defValue'										=> '0',
		'forcedValue'      								=> 1
	)
);
foreach(Config::$globalSettings['languages'] AS $lang) {
	$required = ($lang == Config::$localStrings['user'] ? true : false);
	$DatabaseTablesFields['warehouse categories']['meta_title_'.$lang] = array(
		'label'											=> ucfirst(Config::$localStrings['titolo']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','300',Config::$localStrings['massimo %NUMBER% caratteri']),
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255',
		'validate'										=> 'maxchar',
		'valuerif'										=> 255,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,'300'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
	$DatabaseTablesFields['warehouse categories']['meta_description_'.$lang] = array(
		'label'											=> ucfirst(Config::$localStrings['descrizione']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','300',Config::$localStrings['massimo %NUMBER% caratteri']),
		'searchTable'									=> true,'required'=>false,
		'type'											=> 'varchar|300',
		'validate'										=> 'maxchar',
		'valuerif'										=> 300,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['descrizione']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,'300'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
	$DatabaseTablesFields['warehouse categories']['meta_keyword_'.$lang] = array(
 		'label'											=> ucfirst(Config::$localStrings['keyword']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','255',Config::$localStrings['massimo %NUMBER% caratteri']),
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255',
		'validate'										=> 'maxchar',
		'valuerif'  									=> 255,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['keyword']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
	$DatabaseTablesFields['warehouse categories']['title_seo_'.$lang] = array(
		'label'											=> ucfirst(Config::$localStrings['titolo']).' '.strtoupper(Config::$localStrings['seo']).' '.$lang,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','255',Config::$localStrings['massimo %NUMBER% caratteri']),
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255',
		'validate'										=> 'maxchar',
		'valuerif'  									=> 255,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']).' '.strtoupper(Config::$localStrings['seo']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
	$DatabaseTablesFields['warehouse categories']['title_'.$lang] = array(
		'label'											=> Config::$localStrings['titolo'].' '.$lang,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','255',Config::$localStrings['massimo %NUMBER% caratteri']),
		'searchTable'									=> true,
		'required'										=> $required,
		'type'											=> 'varchar|255',
		'validate'										=> 'maxchar',
		'valuerif'  									=> 255,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
}

// warehouse tags
$DatabaseTables['warehouse tags']  = self::$dbTablePrefix . 'warehouse_tags';
$DatabaseTablesFields['warehouse tags'] = array(
	'id'												=> array(
		'label'											=> 'ID',
		'required'										=> false,
		'type'											=> 'int|8',
		'autoinc'										=> true,
		'primary'										=> true
	),
	'ordering'											=> array(
		'label'											=> Config::$localStrings['ordinamento'],
		'required'										=> false,
		'type'											=> 'int|8',
		'defValue'										=> 1
	),
	'created'											=> array(
		'label'											=> Config::$localStrings['creazione'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'datatime',
		'defValue'										=> Config::$nowDateTimeIso,
		'validate'										=> 'datetimeiso',
		'forcedValue'                   				=> Config::$nowDateTimeIso
	),
	'active'											=> array(
		'label'											=> Config::$localStrings['attiva'],
		'required'										=> false,
		'type'											=> 'int|1',
		'validate'										=> 'int',
		'defValue'										=> '0',
		'forcedValue'      								=> 1
	)
);
foreach(Config::$globalSettings['languages'] AS $lang) {
	$required = ($lang == Config::$localStrings['user'] ? true : false);
	$DatabaseTablesFields['warehouse tags']['title_'.$lang] 	= array(
		'label'											=> Config::$localStrings['titolo'].' '.$lang,
		'searchTable'									=> true,
		'required'										=> $required,
		'type'											=> 'varchar|255',
		'validate'										=> 'maxchar',
		'valuerif'  									=> 255,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
}

// contacts
$DatabaseTables['contacts'] = self::$dbTablePrefix . 'contacts';
$DatabaseTablesFields['contacts'] = array(
	'id'																=> array(
		'label'															=> 'ID',
		'required'														=> false,
 		'type'															=> 'autoinc',
		'primary'														=>	true
	),	
	'name'																=> array(
		'label'															=> Config::$localStrings['nome'],
		'searchTable'													=> true,
		'required'														=> true,
		'type'															=> 'varchar|255',
		'defValue'														=> Config::$nowDateTimeIso,
	),
	'email'																=> array(
		'label'															=> Config::$localStrings['email'],
		'searchTable'													=> true,
		'required'														=> true,
		'type'															=> 'varchar|255',
		'defValue'														=> '',
		'validate'														=> 'isemail',
		'error message'             									=> preg_replace('/%ITEM%/',Config::$localStrings['indirizzo email valido'],Config::$localStrings['Devi inserire un %ITEM%!'])
	),
	'telephone'															=> array(
		'label'															=> Config::$localStrings['telefono'],
		'searchTable'													=> true,
		'required'														=> true,
		'type'															=> 'varchar|2',
		'defValue'														=> '',
		'validate'														=> 'telephonenumber',
		'error message'             									=> preg_replace('/%ITEM%/',Config::$localStrings['numero di telefono'],Config::$localStrings['Devi inserire un %ITEM%!']),
		'validation error message'     									=> preg_replace('/%ITEM%/',Config::$localStrings['numero di telefono valido'],Config::$localStrings['Devi inserire un %ITEM%!'])
	),
	'object'															=> array(
		'label'															=> Config::$localStrings['oggetto'],
		'searchTable'													=> true,
		'required'														=> true,
		'type'															=> 'varchar|255',
		'defValue'														=> '',
	),
	'message'															=> array(
		'label'															=> Config::$localStrings['messaggio'],
		'searchTable'													=> true,
		'required'														=> true,
		'type'															=> 'text',
		'defValue'														=> '',
	),
	'ip_address'														=> array(
		'label'															=> Config::$localStrings['indirizzo ip'],
		'searchTable'													=> true,
		'required'														=> false,
		'type'															=> 'varchar|50',
		'defValue'														=> '',
	),
	'is_span'															=> array(
		'label'															=> Config::$localStrings['è span'],
		'searchTable'													=> true,
		'required'														=> false,
		'type'															=> 'int|1',
		'defValue'														=> '0',
	),
	'created'															=> array(
		'label'															=> Config::$localStrings['creazione'],
		'searchTable'													=> false,
		'required'														=> false,
		'type'															=> 'datatime',
		'defValue'														=> Config::$nowDateTimeIso,
		'validate'														=> 'datetimeiso'
	)
);

// news o blog
$DatabaseTables['news'] = self::$dbTablePrefix . 'news';
$DatabaseTablesFields['news'] = array(
	'id'												=> array(
		'label'											=> 'ID',
		'required'										=> false,
		'type'											=> 'int|8',
		'autoinc'										=> true,
		'primary'										=> true
	),
	'id_user'											=> array(
		'label'											=> Config::$localStrings['proprietario'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'int|8',
		'defValue'										=> ''
	),
	'id_cat'											=> array(
		'label'											=> 'ID Cat',
		'required'										=> false,
		'type' 											=>'int|8',
		'defValue'										=> '0'
	),
	'datatimeins'										=> array (
		'label'											=> Config::$localStrings['data'],
		'searchTable'									=> false,
		'required'										=> true,
		'type'											=> 'datatime',
		'defValue'										=> Config::$nowDateTimeIso,
		'validate'										=> 'datetimepicker',
		'error message'									=> Config::$localStrings['Devi inserire una data valida!']
	),
	'datatimescaini'									=> array(
		'label'											=> Config::$localStrings['inizio scadenza'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'datatime',
		'defValue'										=> Config::$nowDateTimeIso,
		'validate'										=> 'datetimepicker',
		'error message'									=> Config::$localStrings['Devi inserire una data valida!']
	),
	'datatimescaend'									=> array(
		'label'											=> Config::$localStrings['fine scadenza'],
		'searchTable'									=> false,
		'required'										=> false,
		'type' 											=> 'datatime',
		'defValue'										=> Config::$nowDateTimeIso,
		'validate'										=> 'datetimepicker',
		'errorMessage'									=> Config::$localStrings['Devi inserire una data valida!'],
        'errorValidateMessage'  						=> Config::$localStrings['Devi inserire una data valida!']
	),
	'filename'											=> array(
		'label'											=> 'Nome File',
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=>'varchar|255'
	),
	'org_filename'										=> array(
		'label'											=> '',
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255'
	),
	'embedded'											=> array(
		'label'											=> 'Embedded',
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'text',
		'defValue'										=> ''
	),
	'scadenza'											=> array(
		'label'											=> 'Scadenza',
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'int|1',
		'defValue'										=> '0'
	),
	'access_type'										=> array(
		'label'											=> Config::$localStrings['tipo accesso'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'int|1',
		'defValue'										=> '0'
	),
	'access_read'										=> array(
		'label'											=> Config::$localStrings['accesso lettura'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'text',
		'defValue'										=> 'none'
	),
	'access_write'										=> array(
		'label'											=> Config::$localStrings['accesso scrittura'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'text',
		'defValue'										=> 'none'
	),
	'created'											=> array(
		'label'											=> Config::$localStrings['creazione'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'datatime',
		'defValue'										=> Config::$nowDateTimeIso,
		'validate'										=> 'datetimeiso',
		'forcedValue'                   				=> Config::$nowDateTimeIso
	),
	'active'											=> array(
		'label'											=> Config::$localStrings['attiva'],
		'required'										=> false,
		'type'											=> 'int|1',
		'validate'										=> 'int',
		'defValue'										=> '0',
		'forcedValue'      								=> 1
	)
);
foreach(Config::$globalSettings['languages'] AS $lang) {
	$required = ($lang == Config::$localStrings['user'] ? true : false);
	$DatabaseTablesFields['news']['meta_title_'.$lang] = array(
		'label'											=> ucfirst(Config::$localStrings['titolo']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','300',Config::$localStrings['massimo %NUMBER% caratteri']),
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255',
		'validate'										=> 'maxchar',
		'valuerif'										=> 255,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,'300'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
	$DatabaseTablesFields['news']['meta_description_'.$lang] = array(
		'label'											=> ucfirst(Config::$localStrings['descrizione']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','300',Config::$localStrings['massimo %NUMBER% caratteri']),
		'searchTable'									=> true,'required'=>false,
		'type'											=> 'varchar|300',
		'validate'										=> 'maxchar',
		'valuerif'										=> 300,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['descrizione']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,'300'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
	$DatabaseTablesFields['news']['meta_keyword_'.$lang] = array(
 		'label'											=> ucfirst(Config::$localStrings['keyword']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','255',Config::$localStrings['massimo %NUMBER% caratteri']),
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255',
		'validate'										=> 'maxchar',
		'valuerif'  									=> 255,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['keyword']).' '.strtoupper(Config::$localStrings['meta']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
	$DatabaseTablesFields['news']['title_seo_'.$lang] = array(
		'label'											=> ucfirst(Config::$localStrings['titolo']).' '.strtoupper(Config::$localStrings['seo']).' '.$lang,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','255',Config::$localStrings['massimo %NUMBER% caratteri']),
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255',
		'validate'										=> 'maxchar',
		'valuerif'  									=> 255,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']).' '.strtoupper(Config::$localStrings['seo']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
	$DatabaseTablesFields['news']['title_'.$lang] = array(
		'label'											=> ucfirst(Config::$localStrings['titolo']).' '.$lang,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','255',Config::$localStrings['massimo %NUMBER% caratteri']),
		'searchTable'									=> true,
		'required'										=> $required,
		'type'											=> 'varchar|255',
		'validate'										=> 'maxchar',
		'valuerif'  									=> 255,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
	$DatabaseTablesFields['news']['summary_'.$lang] = array(
		'label'											=> ucfirst(Config::$localStrings['sommario']).' '.$lang,
		'searchTable'									=> true,
		'labelsubtext'									=> preg_replace('/%NUMBER%/','255',Config::$localStrings['massimo %NUMBER% caratteri']),
 		'required'										=> false,
		'type'											=> 'text',
		'validate'										=> 'maxchar',
		'valuerif'  									=> 255,
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['sommario']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])

	);
	$DatabaseTablesFields['news']['content_'.$lang] = array(
		'label' 										=> 'Contenuto '.$lang,
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'mediumtext'
	);
}

// footer categorie
$DatabaseTables['footer categories'] = self::$dbTablePrefix . 'footer_categories';
$DatabaseTablesFields['footer categories'] = array(
	'id'												=> array(
		'label'											=> 'ID',
		'required'										=> false,
		'type'											=> 'int|8',
		'autoinc'										=> true,
		'primary'										=> true
	),
	'url'												=> array(
		'label'											=> Config::$localStrings['url'],
		'searchTable'									=> true,
		'required'										=> true,
		'type'											=> 'varchar|255'
	),
	'created'											=> array(
		'label'											=> Config::$localStrings['creazione'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'datatime',
		'defValue'										=> Config::$nowDateTimeIso,
		'validate'										=> 'datetimeiso',
		'forcedValue'                   				=> Config::$nowDateTimeIso
	),
	'active'											=> array(
		'label'											=> Config::$localStrings['attiva'],
		'required'										=> false,
		'type'											=> 'int|1',
		'validate'										=> 'int',
		'defValue'										=> '0',
		'forcedValue'      								=> 1
	)
);
foreach(Config::$globalSettings['languages'] AS $lang) {
	$required = ($lang == Config::$localStrings['user'] ? true : false);
	$DatabaseTablesFields['footer categories']['title_'.$lang]  = array(
		'label'											=> Config::$localStrings['titolo'].' '.$lang,
		'searchTable'									=> true,
		'required'										=> $required,
		'type'											=> 'varchar|255',
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
}

// brands
$DatabaseTables['brands'] = self::$dbTablePrefix . 'brands';
$DatabaseTablesFields['brands'] = array(
	'id'												=> array(
		'label'											=> 'ID',
		'required'										=> false,
		'type'											=> 'int|8',
		'autoinc'										=> true,
		'primary'										=> true
	),
	'url'												=> array(
		'label'											=> Config::$localStrings['url'],
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255'
	),
	'in_footer'											=> array(
		'label'											=> 'in footer',
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'int|1',
		'validate'										=> 'int|1',
		'defValue'										=> '0',
		'forcedValue'      								=> 1
	),
	'created'											=> array(
		'label'											=> Config::$localStrings['creazione'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'datatime',
		'defValue'										=> Config::$nowDateTimeIso,
		'validate'										=> 'datetimeiso',
		'forcedValue'                   				=> Config::$nowDateTimeIso
	),
	'active'											=> array(
		'label'											=> Config::$localStrings['attiva'],
		'required'										=> false,
		'type'											=> 'int|1',
		'validate'										=> 'int',
		'defValue'										=> '0',
		'forcedValue'      								=> 1
	)
);
foreach(Config::$globalSettings['languages'] AS $lang) {
	$required = ($lang == Config::$localStrings['user'] ? true : false);
	$DatabaseTablesFields['brands']['title_'.$lang] = array(
		'label'											=> Config::$localStrings['titolo'].' '.$lang,
		'searchTable'									=> true,
		'required'										=> $required,
		'type'											=> 'varchar|255',
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
}

// slideshome rev
$DatabaseTables['slides home rev'] = self::$dbTablePrefix . 'slides_home_rev';
$DatabaseTablesFields['slides home rev'] = array(
	'id'												=> array(
		'label'											=> 'ID',
		'required'										=> false,
		'type'											=> 'autoinc',
		'autoinc'										=> true,
		'primary'										=> true
	),
	'user_id'											=> array(
		'label'											=> Config::$localStrings['proprietario'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'int|8',
		'defValue'										=> 0
	),
	'title' 											=> array(
		'label'											=> ucfirst(Config::$localStrings['titolo']),
		'searchTable'									=> true,
		'required'										=> true,
		'type'											=> 'varchar|255',
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	),
	'filename'											=> array(
		'label'											=> 'Nome File',
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'varchar|255'
	),
	'org_filename'										=> array(
		'label'											=> '',
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255'
	),
	'li_data'											=> array (
		'label'											=> 'LI Data',
		'required'										=> false,
		'type'											=> 'text',
		'defValue'										=> ''
	),
	'ordering'											=> array(
		'label'											=> Config::$localStrings['ordinamento'],
		'required'										=> false,
		'type'											=> 'int8',
		'defValue'										=> 1
	),
	'slide_type'										=> array(
		'label'											=> 'Tipo',
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'int|1',
		'defValue'										=>'0'
	),
	'access_type'										=> array(
		'label'											=> 'Tipo accesso',
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'int|1',
		'defValue'										=> '0'
	),
	'access_read'										=> array(
		'label'											=> Config::$localStrings['accesso lettura'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'text',
		'defValue'										=> 'none'
	),
	'access_write'										=> array(
		'label'											=> Config::$localStrings['accesso scrittura'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'text',
		'defValue'										=> 'none'
	),
	'created'											=> array(
		'label'											=> Config::$localStrings['creazione'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'datatime',
		'defValue'										=> Config::$nowDateTimeIso,
		'validate'										=> 'datetimeiso',
		'forcedValue'                   				=> Config::$nowDateTimeIso
	),
	'active'											=> array(
		'label'											=> Config::$localStrings['attiva'],
		'required'										=> false,
		'type'											=> 'int|1',
		'validate'										=> 'int',
		'defValue'										=> '0',
		'forcedValue'      								=> 1
		)
	);

// slideshome rev layers
$DatabaseTables['slides home rev layers'] = self::$dbTablePrefix . 'slides_home_rev_layers';
$DatabaseTablesFields['slides home rev layers'] 		= array(
	'id'												=> array(
		'label'											=> 'ID',
		'required'										=> false,
		'type'											=> 'autoinc',
		'autoinc'										=> true,
		'primary'										=> true
	),
	'slide_id'											=> array(
		'label'											=> '',
		'searchTable'									=> false,
		'required'										=> true,
		'type'											=> 'int|8',
		'defValue'										=> 0
	),
	'title' 											=> array(
		'label'											=> Config::$localStrings['titolo'],
		'searchTable'									=> true,
		'required'										=> true,
		'type'											=> 'varchar|255',
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	),
	'filename'											=> array(
		'label'											=> 'Nome File',
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'varchar|255'
	),
	'org_filename'										=> array(
		'label'											=> '',
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar255'
	),
	'ordering'											=> array(
		'label'											=> Config::$localStrings['ordinamento'],
		'required'										=> false,
		'type'											=> 'int8',
		'defValue'										=>1
	),
	'url'												=> array(
		'label'											=> Config::$localStrings['url'],
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|255',
		'defValue'										=> ''
	),
	'target'											=> array(
		'label'											=> Config::$localStrings['target'],
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'varchar|20',
		'defValue'										=> ''
	),
	'type'												=> array(
		'label'											=> Config::$localStrings['tipo'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'int|1',
		'defValue'										=> '0'
	),
	'created'											=> array(
		'label'											=> Config::$localStrings['creazione'],
		'searchTable'									=> false,
		'required'										=> false,
		'type'											=> 'datatime',
		'defValue'										=> Config::$nowDateTimeIso,
		'validate'										=> 'datetimeiso',
		'forcedValue'                   				=> Config::$nowDateTimeIso
	),
	'active'											=> array(
		'label'											=> Config::$localStrings['attiva'],
		'required'										=> false,
		'type'											=> 'int|1',
		'validate'										=> 'int',
		'defValue'										=> '0',
		'forcedValue'      								=> 1
	)
);
foreach(Config::$globalSettings['languages'] AS $lang) {
	$required = ($lang == Config::$localStrings['user'] ? true : false);
	$DatabaseTablesFields['slides home rev layers']['content_'.$lang] = array(
		'label'											=> Config::$localStrings['contenuto'].' '.$lang,
		'searchTable'									=> true,
		'required'										=> $required,
		'type'											=> 'text',
		'error message'									=> preg_replace(array('/%FIELD%/','/%NUMBER%/'),array(ucfirst(Config::$localStrings['titolo']).' '.$lang,'255'),Config::$localStrings['Il campo %FIELD% ha superato i %NUMBER% caratteri!'])
	);
	$DatabaseTablesFields['slides home rev layers']['template_'.$lang] = array(
		'label'											=> Config::$localStrings['template'].' '.$lang,
		'searchTable'									=> true,
		'required'										=> false,
		'type'											=> 'text',
		'defValue'										=> ''
	);
}

	//ToolsStrings::dump($DatabaseTablesFields['news']);

<?php
// settings
$DatabaseTables['settings']  = self::$dbTablePrefix . 'settings';
$DatabaseTablesFields['settings'] = array(
    'id'                                                => array(
        'label'                                         => 'ID',
        'required'                                      => false,
        'type'                                          => 'autoinc',
        'primary'                                       => true
    ),
    'keyword'                                           => array(
        'label'                                         => 'Keyword',
        'required'                                      => true,
        'searchTable'                                   => true,
        'type'						        	        => 'varchar|255',
        'defValue'                                      => ''
    ),
    'value'                                             => array(
        'label'                                         => 'Value',
        'required'                                      => true,
        'searchTable'                                   => true,
        'type'					        		        => 'varchar|10000',
        'defValue'                                      => ''
    ),
    'comment'                                           => array(
        'label'                                         => 'Commento',
        'required'                                      => true,
        'searchTable'                                   => true,
        'type'					        		        => 'varchar|512',
        'defValue'                                      => ''
    )
);

// users
$DatabaseTables['users']  = self::$dbTablePrefix . 'users';
$DatabaseTablesFields['users'] = array(
    'id'                                                => array(
        'label'                                         => 'ID',
        'required'                                      => false,
        'type'                                          => 'autoinc',
        'primary'                                       => true
    ),
    'username'                                          => array(
        'label'                                         => Config::$localStrings['nome utente'],
        'searchTable'                                   => true, 
        'required'                                      => true,
        'type'                                          => 'varchar|255',
        'validate'                                      => 'username',
        'errorMessage'                                  => preg_replace('/%ITEM%/',Core::$localStrings['nome utente'],Config::$localStrings['Devi inserire una %ITEM%!']),
        'errorValidateMessage'                          => preg_replace('/%ITEM%/',Core::$localStrings['nome utente'],Config::$localStrings['Il valore per il campo %ITEM% non è stato validato!']),
    ),
    'password'                                          => array(
        'label'                                         => self::$localStrings['password'],
        'searchTable'                                   => false,
        'required'                                      => true,
        'type'                                          => 'password'
    ),
    'name'                                              => array(
        'label'                                         => Core::$localStrings['nome'],
        'searchTable'                                   => true,
        'required'                                      => true,
        'type'                                          => 'varchar|255'),
    'surname'                                           => array(
        'label'                                         => Core::$localStrings['cognome'],
        'searchTable'                                   => true,
        'required'                                      => true,
        'type'                                          => 'varchar'
    ),
    'street'                                            => array(
        'label'                                         => Core::$localStrings['via'],
        'searchTable'                                   => false,
        'required'                                      => true,
        'type'                                          => 'varchar'
    ),
    'location_comuni_id'                                => array(
        'label'                                         => Core::$localStrings['comune'],
        'searchTable'                                   => false,
        'required'                                      => true, 
        'type'                                          => 'int|10',
        'defValue'                                      => 0
    ),
    'comune_alt'                                        => array(
        'label'                                         => Core::$localStrings['altro comune'],
        'searchTable'                                   => false,
        'required'                                      => false, 
        'type'                                          => 'varchar|150'
    ),
    'zip_code'                                          => array(
        'label'                                         => Core::$localStrings['c.a.p.'],
        'searchTable'                                   => false,
        'required'                                      => true,
        'type'                                          => 'varchar'
    ),
    'location_province_id'                              => array(
        'label'                                         => Core::$localStrings['provincia'],
        'searchTable'                                   => false,
        'required'                                      => true,
        'type'                                          => 'int|10',
        'defValue'                                      => 0
    ),
    'provincia_alt'                                     => array(
        'label'                                         => Core::$localStrings['altra provincia'],
        'searchTable'                                   => true,
        'required'                                      => false,
        'type'                                          => 'varchar|150',
        'defValue'                                      => ''
    ),
    'location_nations_id'                               => array(
        'label'                                         => Core::$localStrings['nazione'],
        'searchTable'                                   => false,
        'required'                                      => false,
        'type'                                          => 'int|10',
        'defValue'                                      => 0
    ),
    'telephone'                                         => array(
        'label'                                         => Core::$localStrings['telefono'],
        'searchTable'                                   => false, 
        'required'                                      => true, 
        'type'                                          => 'varchar|20',
        'validate'                                      => 'telephonenumber',
        'errorValidateMessage'                          => preg_replace('/%ITEM%/',ucfirst(Config::$localStrings['numero di telefono']),Config::$localStrings['%ITEM% non valido!'])
    ),
    'email'                                             => array(
        'label'                                         => Core::$localStrings['email'],
        'searchTable'                                   => true,
        'required'                                      => true,
        'type'                                          => 'varchar|255',
        'defValue'                                      => '',
        'validate'                                      => 'isemail',
        'errorMessage'                                  => preg_replace('/%ITEM%/',Core::$localStrings['email'],Config::$localStrings['Devi inserire una %ITEM%!']),
        'errorValidateMessage'                          => preg_replace('/%ITEM%/',Core::$localStrings['email'],Config::$localStrings['Il valore per il campo %ITEM% non è stato validato!']),
    ),
    'mobile'                                            => array(
        'label'                                         => Core::$localStrings['mobile'],
        'searchTable'                                   => true,
        'required'                                      => false,
        'type'                                          => 'varchar'
    ),
    'fax'                                               => array(
        'label'                                         => Core::$localStrings['fax'],
        'searchTable'                                   => true,
        'required'                                      => false,
        'type'                                          => 'varchar'
    ),
    'skype'                                             => array(
        'label'                                         => Core::$localStrings['skype'],
        'searchTable'                                   => true,
        'type'                                          => 'varchar'
    ),
    'avatar'                                            => array(
        'label'                                         => Core::$localStrings['avatar'],
        'searchTable'                                   => false,
        'type'                                          => 'blob'
    ),
    'avatar_info'                                       => array(
        'label'                                         => Core::$localStrings['avatar info'],
        'searchTable'                                   => false,
        'type'                                          => 'varchar|255'
    ),
    'id_level'                                          => array(
        'label'                                         => Core::$localStrings['livello'],
        'searchTable'                                   => false,
        'type'                                          => 'int|1'
    ),
    'is_root'                                           => array(
        'label'                                         => 'Root',
        'searchTable'                                   => false,
        'type'                                          => 'varchar',
        'defValue'                                      => 0
    ),
    'in_admin'                                          => array(
        'label'                                         => Core::$localStrings['amministrazione'],
        'searchTable'                                   => false,
        'type'                                          => 'varchar',
        'defValue'                                      => 0
    ),
    'from_site'                                         => array(
        'label'                                         => Core::$localStrings['dal sito'],
        'searchTable'                                   => false,
        'type'                                          => 'varchar',
        'defValue'                                      => 0
    ),
    'template'                                          => array(
        'label'                                         => 'template',
        'searchTable'                                   => false,
        'type'                                          => 'varchar|100'
    ),
    'hash'                        	                    => array(
        'label'                                         => 'Hash',
        'searchTable'                                   => false,
        'type'                                          => 'varchar'
    ),
	'created'									        => array(
		'label'									        => Config::$localStrings['creazione'],
		'searchTable'							        => false,
		'required'								        => false,
		'type'									        => 'datatime',
		'defValue'								        => Config::$nowDateTimeIso,
		'validate'								        => 'datetimeiso',
		'forcedValue'              				        => self::$nowDateTimeIso
	),
	'active'									        => array(
		'label'									        => Config::$localStrings['attiva'],
		'required'								        => false,
		'type'									        => 'int|1',
		'validate'			    				        => 'int',
		'defValue'								        => '0',
		'forcedValue'              				        => 1
	));

?>

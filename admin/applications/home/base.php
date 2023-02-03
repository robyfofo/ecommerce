<?php
/* admin/home/base.php v.1.0.0. 17/03/2021 */

/* users */
if (

in_array(Config::$dbTablePrefix.'users',$App->tablesOfDatabase) && 
file_exists(PATH.$App->pathApplications."users/index.php") && 
Permissions::checkIfModulesIsReadable('users',$App->userLoggedData) === true

) {
	
	$App->homeBlocks['users'] = array(
		'table'=>Config::$dbTablePrefix.'users',
		'query opt'=>array(
					'clause'=>"is_root = 0 AND created > '".$App->lastLogin."'",
					'clauseValRif'=>array()
					),
		'icon panel'=>'fa-users',
		'label'=>ucfirst(Config::$localStrings['utenti']),
		'sex suffix'=>ucfirst(Config::$localStrings['nuovi']),
		'type'=>'info',
		'url'=>true,
		'url item'=>array (
			'string'=>URL_SITE_ADMIN.'users',
			'opt'=>array()
			)
		);	
				
	$App->homeTables['users'] = array(
		'table'=>Config::$dbTablePrefix.'users',
		'query opt'=>array(
			'clause'=>'is_root = 0',
			'clauseValRif'=>array()
			),
		'icon panel'=>'fa-users',
		'label'=>ucfirst(Config::$localStrings['ultimi']).' '.Config::$localStrings['utenti'],
		'fields'=>array(
			'name'=>array(
				'multilanguage'=>0,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['nome']),
				'url'=>true,
				'url item'=>array(
					'string'=>URL_SITE_ADMIN.'users',
					'opt'=>array(
						)
					)
				),
			'avatar'=>array(
				'multilanguage'=>0,
				'type'=>'avatar',
				'label'=>ucfirst(Config::$localStrings['avatar']),
				'url'=>false,
				)
			
			)
		);

	//echo 'accesso a users consentito';		
		
} else {
	//echo 'accesso a users negato';	
}

/* pages */
if (

in_array(Config::$dbTablePrefix.'pages',$App->tablesOfDatabase) && 
file_exists(PATH.$App->pathApplications."pages/index.php") && 
Permissions::checkIfModulesIsReadable('pages',$App->userLoggedData) === true

) {
	$App->homeBlocks['pages'] = array(
		'table'=>Config::$dbTablePrefix.'pages',
		'icon panel'=>'fa-pager',
		'label'=>ucfirst(Config::$localStrings['pagine']),
		'sex suffix'=>ucfirst(Config::$localStrings['nuove']),
		'type'=>'info',
		'url'=>true,
		'url item'=>array (
			'string'=>URL_SITE_ADMIN.'pages',
			'opt'=>array()
			)
		);			
	$App->homeTables['pages'] = array(
		'table'=>Config::$dbTablePrefix.'pages',
		'icon panel'=>'fa-pager',
		'label'=>ucfirst(Config::$localStrings['ultime']).' '.Config::$localStrings['pagine'],
		'fields'=>array(
			'title'=>array(
				'multilanguage'=>1,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['titolo']),
				'url'=>true,
				'url item'=>array(
					'string'=>URL_SITE_ADMIN.'pages',
					'opt'=>array(
						)
					)
				)
			)
		);
	
	//echo 'accesso a pages consentito';		
		
} else {
	//echo 'accesso a pages negato';	
}
	
/* pages blocks*/
if (

in_array(Config::$dbTablePrefix.'pages',$App->tablesOfDatabase) && 
file_exists(PATH.$App->pathApplications."pages/index.php") && 
Permissions::checkIfModulesIsReadable('pages',$App->userLoggedData) === true

) {
	$App->homeBlocks['pages-blocks'] = array(
		'table'=>Config::$dbTablePrefix.'pages_blocks',
		'icon panel'=>'fa-tasks',
		'label'=>ucfirst(Config::$localStrings['blocchi contenuto']),
		'sex suffix'=>ucfirst(Config::$localStrings['nuovi']),
		'type'=>'info',
		'url'=>true,
		'url item'=>array (
			'string'=>URL_SITE_ADMIN.'pages',
			'opt'=>array(
				'fieldItemRif'=>'id_owner'
			)
		)
	);			
	$App->homeTables['pages-blocks'] = array(
		'table'=>Config::$dbTablePrefix.'pages_blocks',
		'icon panel'=>'fa-tasks',
		'label'=>ucfirst(Config::$localStrings['ultimi']).' '.Config::$localStrings['blocchi contenuto pagine'],
		'fields'=>array(
			'title'=>array(
				'multilanguage'=>1,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['titolo']),
				'url'=>true,
				'url item'=>array(
					'string'=>URL_SITE_ADMIN.'pages/listIblo',
					'opt'=>array(
						'fieldItemRif'=>'id_owner'
					)
				)
			)
		)
	);
}
?>
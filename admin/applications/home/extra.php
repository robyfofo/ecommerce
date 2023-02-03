<?php
/* wscms/home/extra.php v.1.0.0. 01/03/2021 */

// slide home rev
if (in_array(Config::$dbTablePrefix.'slides-home-rev',$App->tablesOfDatabase) && file_exists(PATH.$App->pathApplications."slides-home-rev/index.php") && Permissions::checkIfModulesIsReadable('slides-home-rev',$App->userLoggedData) === true) 
{
	$App->homeBlocks['slides-home-rev'] = array(
		'table'=>Config::$dbTablePrefix.'slides_home_rev',
		'icon panel'=>'fa-picture-o',
		'label'=>ucfirst(Config::$localStrings['slide']),
		'sex suffix'=>ucfirst(Config::$localStrings['nuove']),
		'type'=>'info',
		'url'=>true,
		'url item'=>array (
			'string'=>URL_SITE_ADMIN.'slides-home-rev',
			'opt'=>array()
			)
		);			
	$App->homeTables['slides-home-rev'] = array(
		'table'=>Config::$dbTablePrefix.'slides_home_rev',
		'icon panel'=>'fa-picture-o',
		'label'=>ucfirst(Config::$localStrings['ultime']).' '.Config::$localStrings['slide'],
		'fields'=>array(
			'title'=>array(
				'multilanguage'=>0,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['titolo']),
				'url'=>true,
				'url item'=>array(
					'string'=>URL_SITE_ADMIN.'slides-home-rev',
					'opt'=>array(
						'fieldItemRif'=>'id_owner'
						)
					)
				),
			'filename'=>array(
				'multilanguage'=>0,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['immagine']),
				'type'=>'image',
				'pathdef'=>UPLOAD_DIR.'slides-home-rev/',
				'path'=>UPLOAD_DIR.'slides-home-rev/'
				)
			)
		);	
	
}	
// slide home rev

// slide home
if (in_array(Config::$dbTablePrefix.'slides-home',$App->tablesOfDatabase) && file_exists(PATH.$App->pathApplications."slides-home/index.php") && Permissions::checkIfModulesIsReadable('slides-home',$App->userLoggedData) === true) 
{
	$App->homeBlocks['slides-home'] = array(
		'table'=>Config::$dbTablePrefix.'slides_home',
		'icon panel'=>'fa-picture-o',
		'label'=>ucfirst(Config::$localStrings['slide']),
		'sex suffix'=>ucfirst(Config::$localStrings['nuove']),
		'type'=>'info',
		'url'=>true,
		'url item'=>array (
			'string'=>URL_SITE_ADMIN.'slides-home-rev',
			'opt'=>array()
			)
		);			
	$App->homeTables['slides-home'] = array(
		'table'=>Config::$dbTablePrefix.'slides_home',
		'icon panel'=>'fa-picture-o',
		'label'=>ucfirst(Config::$localStrings['ultime']).' '.Config::$localStrings['slide'],
		'fields'=>array(
			'title'=>array(
				'multilanguage'=>1,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['titolo']),
				'url'=>true,
				'url item'=>array(
					'string'=>URL_SITE_ADMIN.'slides-home',
					'opt'=>array(
						'fieldItemRif'=>'id_owner'
						)
					)
				),
			'filename'=>array(
				'multilanguage'=>0,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['immagine']),
				'type'=>'image',
				'pathdef'=>UPLOAD_DIR.'slides-home/',
				'path'=>UPLOAD_DIR.'slides-home/'
				)
			)
		);	
	
}
// slide home
	
// news
if (in_array(Config::$dbTablePrefix.'news',$App->tablesOfDatabase) && file_exists(PATH.$App->pathApplications."news/index.php") && Permissions::checkIfModulesIsReadable('news',$App->userLoggedData) === true) 
{
	$App->homeBlocks['news'] = array(
		'table'=>Config::$dbTablePrefix.'news',
		'icon panel'=>'fa-newspaper-o',
		'label'=>ucfirst(Config::$localStrings['notizie']),
		'sex suffix'=>ucfirst(Config::$localStrings['nuove']),
		'type'=>'infoaaaaa',
		'url'=>true,
		'url item'=>array (
			'string'=>URL_SITE_ADMIN.'news',
			'opt'=>array()
			)
		);			
	$App->homeTables['news'] = array(
		'table'=>Config::$dbTablePrefix.'news',
		'icon panel'=>'fa-newspaper',
		'label'=>ucfirst(Config::$localStrings['ultime']).' '.Config::$localStrings['notizie'],
		'fields'=>array(
			'title'=>array(
				'multilanguage'=>1,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['titolo']),
				'url'=>true,
				'url item'=>array(
					'string'=>URL_SITE_ADMIN.'news',
					'opt'=>array(
						'fieldItemRif'=>''
						)
					)
				),
			'filename'=>array(
				'multilangage'=>0,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['immagine']),
				'type'=>'image',
				'pathdef'=>UPLOAD_DIR.'news/',
				'path'=>UPLOAD_DIR.'news/'
				)
			)
		);	
}
// news
	
// blog
if (in_array(Config::$dbTablePrefix.'blog',$App->tablesOfDatabase) && file_exists(PATH.$App->pathApplications."blog/index.php") && Permissions::checkIfModulesIsReadable('blog',$App->userLoggedData) === true) 
{
	$App->homeBlocks['blog'] = array(
		'table'=>Config::$dbTablePrefix.'blog',
		'icon panel'=>'fa-comments',
		'label'=>ucfirst(Config::$localStrings['post']),
		'sex suffix'=>ucfirst(Config::$localStrings['nuovi']),
		'type'=>'info',
		'url'=>true,
		'url item'=>array (
			'string'=>URL_SITE_ADMIN.'blog',
			'opt'=>array()
		)
	);

	$App->homeTables['blog'] = array(
		'table'=>Config::$dbTablePrefix.'blog',
		'icon panel'=>'fa-comments',
		'label'=>ucfirst(Config::$localStrings['ultimi']).' '.Config::$localStrings['post'],
		'fields'=>array(
			'title'=>array(
				'multilanguage'=>1,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['titolo']),
				'url'=>true,
				'url item'=>array(
					'string'=>URL_SITE_ADMIN.'blog',
					'opt'=>array(
						'fieldItemRif'=>''
					)
				)
			),
			'filename'=>array(
				'multilanguage'=>0,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['immagine']),
				'type'=>'image',
				'pathdef'=>UPLOAD_DIR.'blog/',
				'path'=>UPLOAD_DIR.'blog/'
			)
		)
	);	
	
}
// blog
	
// faq
if (in_array(Config::$dbTablePrefix.'faq',$App->tablesOfDatabase) && file_exists(PATH.$App->pathApplications."faq/index.php") && Permissions::checkIfModulesIsReadable('faq',$App->userLoggedData) === true) 
{
	//Core::setDebugMode(1);	
	$App->homeBlocks['faq'] = array(
		'table'=>Config::$dbTablePrefix.'faq',
		'icon panel'=>'fa-question',
		'label'=>ucfirst(Config::$localStrings['faq']),
		'sex suffix'=>ucfirst(Config::$localStrings['nuove']),
		'type'=>'info',
		'url'=>true,
		'url item'=>array (
			'string'=>URL_SITE_ADMIN.'news',
			'opt'=>array()
		)
	);

	$App->homeTables['faq'] = array(
		'table'=>Config::$dbTablePrefix.'faq',
		'icon panel'=>'fa-question',
		'label'=>ucfirst(Config::$localStrings['ultime']).' '.Config::$localStrings['faq'],
		'fields'=>array(
			'title'=>array(
				'multilanguage'=>1,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['titolo']),
				'url'=>true,
				'url item'=>array(
					'string'=>URL_SITE_ADMIN.'faq',
					'opt'=>array(
						'fieldItemRif'=>''
					)
				)
			)
		)
	);	
	
}
// faq

// warehouse products
if (in_array(Config::$dbTablePrefix.'warehouse_products',$App->tablesOfDatabase) && file_exists(PATH.$App->pathApplications."warehouse/index.php") && Permissions::checkIfModulesIsReadable('warehouse',$App->userLoggedData) === true) 
{

	$App->homeBlocks['products'] = array(
		'table'					=> Config::$dbTablePrefix.'warehouse_products',
		'icon panel'			=> 'fa-tags',
		'label'					=> ucfirst(Config::$localStrings['prodotti']),
		'sex suffix'			=> ucfirst(Config::$localStrings['nuovi']),
		'type'					=> 'info',
		'url'					=> true,
		'url item'				=> array (
			'string'			=> URL_SITE_ADMIN.'products',
			'opt'				=> array()
		)
	);

	$App->homeTables['products'] = array(
		'table'=>Config::$dbTablePrefix.'warehouse_products',
		'icon panel'=>'fa-tags',
		'label'=>ucfirst(Config::$localStrings['ultimi']).' '.Config::$localStrings['prodotti'],
		'fields'=>array(
			/*
			'code'=>array(
				'multiLocalStrings['uage'=>0,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['codice']),
				'url'=>true,
				'url item'=>array(
					'string'=>URL_SITE_ADMIN.'warehouse',
					'opt'=>array(
						'fieldItemRif'=>''
					)
				)
			),
			*/
			'title'=>array(
				'multilanguage'=>1,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['titolo']),
				'url'=>false,
			),
			'filename'=>array(
				'multilanguage'=>0,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['immagine']),
				'type'=>'image',
				'pathdef'=>UPLOAD_DIR.'warehouse/products/',
				'path'=>UPLOAD_DIR.'warehouse/products/'
			)
		)
	);	
	
}
// warehouse products

/* NEWSLETTER */
if (

in_array(Config::$dbTablePrefix.'newsletter',$App->tablesOfDatabase) && 
file_exists(PATH.$App->pathApplications."newsletter/index.php") && 
Permissions::checkIfModulesIsReadable('newsletter',$App->userLoggedData) === true

) {
	$App->homeBlocks['newsletter-indsos'] = array(
		'table'=>Config::$dbTablePrefix.'newsletter_indirizzi',
		'query opt'=>array('clause' => 'created > ? AND confirmed = 0'),
		'icon panel'=>'fa-user-secret',
		'label'=>ucfirst(Config::$localStrings['indirizzi sospesi']).' '.Config::$localStrings['newsletter'],
		'sex suffix'=>ucfirst(Config::$localStrings['nuovi']),
		'type'=>'info',
		'url'=>true,
		'url item'=>array (
			'string'=>URL_SITE_ADMIN.'newsletter/listIndSos',
			'opt'=>array()
			)
		);	
	
	$App->homeTables['newsletter-indsos'] = array(
		'table'=>Config::$dbTablePrefix.'newsletter_indirizzi',
		'query opt'=>array('clause' => 'confirmed = 0'),
		'icon panel'=>'fa-user-secret',
		'label'=>ucfirst(Config::$localStrings['indirizzi sospesi']).' '.Config::$localStrings['newsletter'],
		'fields'=>array(
			'email'=>array(
				'multilanguage'=>0,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['email']),
				'url'=>true,
				'url item'=>array(
					'string'=>URL_SITE_ADMIN.'newsletter/listIndSos',
					'opt'=>array()
					)
				)
			)
		);	

	$App->homeBlocks['newsletter-ind'] = array(
		'table'=>Config::$dbTablePrefix.'newsletter_indirizzi',
		'query opt'=>array('clause' => 'created > ? AND confirmed = 1'),
		'icon panel'=>'fa-user',
		'label'=>ucfirst(Config::$localStrings['indirizzi']).' '.Config::$localStrings['newsletter'],
		'sex suffix'=>ucfirst(Config::$localStrings['nuovi']),
		'type'=>'info',
		'url'=>true,
		'url item'=>array (
			'string'=>URL_SITE_ADMIN.'newsletter/listInd',
			'opt'=>array()
			)
		);	
		
	$App->homeTables['newsletter-ind'] = array(
		'table'=>Config::$dbTablePrefix.'newsletter_indirizzi',
		'query opt'=>array('clause' => 'confirmed = 1'),
		'icon panel'=>'fa-user',
		'label'=>ucfirst(Config::$localStrings['indirizzi']).' '.Config::$localStrings['newsletter'],
		'fields'=>array(
			'email'=>array(
				'multilanguage'=>0,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['email']),
				'url'=>true,
				'url item'=>array(
					'string'=>URL_SITE_ADMIN.'newsletter/listInd',
					'opt'=>array()
					)
				)
			)
		);	
	}


/* VIDEO */
if (

in_array(Config::$dbTablePrefix.'video',$App->tablesOfDatabase) && 
file_exists(PATH.$App->pathApplications."video/index.php") && 
Permissions::checkIfModulesIsReadable('video',$App->userLoggedData) === true

) {
	$App->homeBlocks['video'] = array(
		'table'=>Config::$dbTablePrefix.'video',
		'icon panel'=>'fa-youtube',
		'label'=>ucfirst(Config::$localStrings['video']),
		'sex suffix'=>ucfirst(Config::$localStrings['nuovi']),
		'type'=>'info',
		'url'=>true,
		'url item'=>array (
			'string'=>URL_SITE_ADMIN.'video',
			'opt'=>array()
			)
		);			
	$App->homeTables['youtube'] = array(
		'table'=>Config::$dbTablePrefix.'video',
		'icon panel'=>'fa-youtube',
		'label'=>ucfirst(Config::$localStrings['ultimi']).' '.Config::$localStrings['video'],
		'fields'=>array(
			'title'=>array(
				'multilanguage' =>1,
				'type'=>'varchar',
				'label'=>ucfirst(Config::$localStrings['titolo']),
				'url'=>true,
				'url item'=>array(
					'string'=>URL_SITE_ADMIN.'video',
					'opt'=>array(
						'fieldItemRif'=>''
						)
					)
				)
			)
		);	
	
	}
	
?>
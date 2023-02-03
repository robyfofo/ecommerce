<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/home/custom.php v.1.0.0. 15/02/2021
*/

//Core::setDebugMode(1);

// brands
if (
    in_array(Config::$dbTablePrefix.'brands',$App->tablesOfDatabase) && 
    file_exists(PATH.$App->pathApplications."brands/index.php") && 
    Permissions::checkIfModulesIsReadable('barnds',$App->userLoggedData) === true
    )
{
    //Core::setDebugMode(1);	
    $App->homeBlocks['brands'] = array(
        'table'=>Config::$dbTablePrefix.'brands',
        'icon panel'=>'fa fa-copyright',
        'label'=>ucfirst(Config::$localStrings['marchi']),
        'sex suffix'=>ucfirst(Config::$localStrings['nuovi']),
        'type'=>'info',
        'url'=>true,
        'url item'=>array (
            'string'=>URL_SITE_ADMIN.'brands',
            'opt'=>array()
        )
    );			
    $App->homeTables['bands'] = array(
        'table'=>Config::$dbTablePrefix.'brands',
        'icon panel'=>'fa fa-copyright',
        'label'=>ucfirst(Config::$localStrings['ultimi']).' '.Config::$localStrings['marchi'],
        'fields'=>array(
            'title'=>array(
                'multilanguage'=>1,
                'type'=>'varchar',
                'label'=>ucfirst(Config::$localStrings['titolo']),
                'url'=>true,
                'url item'=>array(
                    'string'=>URL_SITE_ADMIN.'brands',
                    'opt'=>array(
                        'fieldItemRif'=>''
                    )
                )
            )
        )
    );	    
}

// footer categories
if (
    in_array(Config::$dbTablePrefix.'footer_categories',$App->tablesOfDatabase) && 
    file_exists(PATH.$App->pathApplications."footercategories/index.php") && 
    Permissions::checkIfModulesIsReadable('footercategories',$App->userLoggedData) === true
    )
{
    //Core::setDebugMode(1);	
    $App->homeBlocks['footercategories'] = array(
        'table'=>Config::$dbTablePrefix.'footer_categories',
        'icon panel'=>'fa fa-folder',
        'label'=>ucfirst(Config::$localStrings['categorie']).' footer',
        'sex suffix'=>ucfirst(Config::$localStrings['nuove']),
        'type'=>'info',
        'url'=>true,
        'url item'=>array (
            'string'=>URL_SITE_ADMIN.'footercategories',
            'opt'=>array()
        )
    );			
    $App->homeTables['footercategories'] = array(
        'table'=>Config::$dbTablePrefix.'footer_categories',
        'icon panel'=>'fa fa-folder',
        'label'=>ucfirst(Config::$localStrings['ultime']).' '.Config::$localStrings['categorie'].' footer',
        'fields'=>array(
            'title'=>array(
                'multilanguage'=>1,
                'type'=>'varchar',
                'label'=>ucfirst(Config::$localStrings['titolo']),
                'url'=>true,
                'url item'=>array(
                    'string'=>URL_SITE_ADMIN.'footercategories',
                    'opt'=>array(
                        'fieldItemRif'=>''
                    )
                )
            )
        )
    );	    
}


?>
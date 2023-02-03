<?php
/* admin/menu/lang/it.inc.php v.1.0.0. 22/03/2021 */
Config::$localStrings = array_merge(Config::$localStrings,array
	(
        'voce' => 'menu',
        'voci' => 'menu',
        'Url' => 'Url',
        'menu-type-vars' => array(
        'menupages'=>array(
            'varreplace'=>'%MENUPAGES%',
            'title'=>'Il menu pagine dinamiche',
            'info'=>'Il menu generato dal modulo pages è in genere il menu principale del sito, dove vengono mostrate nel menu le pagine dinamiche gestite dal modulo stesso'
        ),
        //'menusubcategories'=>array('varreplace'=>'/%MENUSUBCATEGORIES%/','title'=>'Il menu sottocategorie','info'=>'Il menu generato dal modulo prodotti, ecommerce od equivalente dove vengono mostrate nel menu le categorie ad albero (sottocategorie) del catalogo prodotti del sito'),
        'menucategories' => array(
            'varreplace'=>'%MENUCATEGORIES%',
            'title'=>'Il menu categorie',
            'info'=>'Il menu generato dal modulo prodotti, ecommerce od equivalenti e dove vengono mostrate nel menu le categorie del catalogo prodotti del sito'
        ),
        //'menuproducts'=>array('varreplace'=>'/%MENUPRODUCTS%/','title'=>'Il menu prodotti','info'=>'Il menu generato dal modulo prodotti, ecommerce od equivalenti e dove vengono mostrati nel menu i prodotti del catalogo prodotti del sito'),
        ),

    )
);
?>
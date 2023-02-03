<?php
/**
 * Framework siti html-PHP-Mysql
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * pages/templates/unify/configuration.php v.1.0.0. 11/02/2021
*/

$templateLanguagesBar = array(
	'container'					=> '
	<li class="list-inline-item g-pos-rel">
    <a id="languages-dropdown-invoker-2"
        class="g-color-white-opacity-0_6 g-color-primary--hover g-font-weight-400 g-text-underline--none--hover"
        href="#" aria-controls="languages-dropdown-2" aria-haspopup="true"
        aria-expanded="false" data-dropdown-event="hover"
        data-dropdown-target="#languages-dropdown-2" data-dropdown-type="css-animation"
        data-dropdown-duration="300" data-dropdown-hide-on-scroll="false"
        data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">%LANGACTIVE%
		</a>
		<ul id="languages-dropdown-2"
			class="list-unstyled u-shadow-v29 g-pos-abs g-left-0 g-bg-white g-width-160 g-pb-5 g-mt-19 g-z-index-2"
			aria-labelledby="languages-dropdown-invoker-2">%LINKS%</ul></li>',
	'links'						=> '<li class=""><a class="d-block g-color-black g-color-primary--hover g-text-underline--none--hover g-font-weight-400 g-py-5 g-px-20" title="%TITLE%" href="%URL%">%TITLE%</a></li>',
	'links active'				=> '<li class=""><a class="d-block g-color-black g-color-primary--hover g-text-underline--none--hover g-font-weight-400 g-py-5 g-px-20" title="%TITLE%" href="%URL%">%TITLE%</a></li>'
);
$templateLanguagesBarFooter = array(
	'container'					=> '
	<div class="btn-group dropup">
		<button
			class="btn btn-black g-bg-main-light-v1 btn-lg g-color-gray-dark-v5 g-color-primary--hover g-font-size-default g-pl-0 mr-5"
			type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			%LANGACTIVE%
			<i class="g-font-size-12 ml-2 fa fa-caret-up"></i>
		</button>
		<div class="dropdown-menu g-brd-gray-dark-v2 g-bg-main-light-v2">
		%LINKS%
		</div>
	</div>',
	'links'						=> '<li class="dropdown-item g-color-gray-dark-v5"><a class="" title="%TITLE%" href="%URL%">%TITLE%</a></li>',
	'links active'				=> '<li class="dropdown-item g-color-gray-dark-v5"><a class="" title="%TITLE%" href="%URL%">%TITLE%</a></li>'
);

$templateMessagesBar = '<div class="container"><div class="row"><div class="col-12"><div class="alert%CLASS%">%CONTENT%</div></div></div></div>';

$templateBreadcrumbsBar = array(
	'container'=>'
 <section class="g-brd-bottom g-brd-gray-light-v4 g-py-30">
      <div class="container">
        <ul class="u-list-inline">
				%LINKS%
        </ul>
      </div>
    </section>',
	'links home'=>'
	<li class="list-inline-item g-mr-7">
		<a class="u-link-v5 g-color-text" href="%URL%" title="%TITLE%">%TITLE%</a>
		<i class="g-color-gray-light-v2 g-ml-5 fa fa-angle-right"></i>
	</li>',
	'nolinks'=>'
	<li class="list-inline-item g-mr-5">
		%TITLE%
		<i class="g-color-gray-light-v2 g-ml-5 fa fa-angle-right">/</i>
	</li>',
	'links'=>'
	<li class="list-inline-item g-mr-5">
		<a class="u-link-v5 g-color-text" href="%URL%" title="%TITLE%">
			%TITLE%
		</a>
		<i class="g-color-gray-light-v2 g-ml-5 fa fa-angle-right"></i>
	</li>',
	'links active'=>'
	<li class="list-inline-item g-color-primary">
		<span>%TITLE%</span>
	</li>'
);

$templateSystemMessages = array(
	'container'=>'<div class="container g-pt-10 g-pb-0"><div class="row justify-content-between">%ALERT%</div></div>',
	'warning'=>'<div id="systemMessageID" class="alert alert-warning">%MESSAGE%</div>',
	'danger'=>'<div id="systemMessageID" class="alert alert-danger">%MESSAGE%</div>',
	'success'=>'<div id="systemMessageID" class="alert alert-success">%MESSAGE%</div>'
);

$optMainMenu = array(
	'ulIsMain'				=> 0,
	'ulMain'				=> '<ul 
								data-ref="m-ulMain" 
								id="L%LEVEL%-S%SONS%"
								>',
	'ulSubMenu'				=> '<ul 
								class="hs-sub-menu list-unstyled u-shadow-v11 g-min-width-220 g-brd-top g-brd-primary g-brd-top-2 g-mt-17 animated" 
								aria-labelledby="nav-link--pages" 
								style="display: none;" 
								data-ref="m-ulSubMenu" 
								id="nav-submenu--L%LEVEL%-S%SONS%"
								>',
	'ulSubSubMenu'			=> '<ul data-ref="m-ulSubSubMenu" id="L%LEVEL%-S%SONS%">',
	'ulDefault'				=> '<ul data-ref="m-ulDefault" id="L%LEVEL%-S%SONS%">',	
	'liMain'				=> '<li class="nav-item%CLASSACTIVE% g-ml-10--lg" id="L%LEVEL%-S%SONS%">',
	'liSubMenu'				=> '<li class="nav-item%CLASSACTIVE% hs-has-sub-menu  g-mx-10--lg g-mx-15--xl" data-animation-in="fadeIn" data-animation-out="fadeOut" data-ref="m-liSubMenu" id="L%LEVEL%-S%SONS%">',
	'liSubSubMenu'			=> '<li data-ref="m-liSubSubMenu" id="L%LEVEL%-S%SONS%">',
	'liDefault'				=> '<li  class="dropdown-item" data-ref="m-liDefault" id="L%LEVEL%-S%SONS%">',
	'hrefMain'				=> '<a class="nav-link%CLASSACTIVE% text-uppercase g-color-primary--hover g-pl-5 g-pr-0 g-py-20" id="L%LEVEL%-S%SONS%" href="%URL%" title="%URLTITLE%">%TITLE%</a>',
	'hrefSubMenu'			=> '<a 
								class="nav-link text-uppercase g-color-primary--hover g-px-5 g-py-20" 
								aria-haspopup="true" 
								aria-expanded="false" 
								aria-controls="nav-submenu--pages"
								data-ref="m-hrefSubMenu" 
								id="nav-link--L%LEVEL%-S%SONS%" 
								href="%URL%" title="%URLTITLE%">%TITLE%
								</a>',
	'hrefSubSubMenu'		=> '<a data-ref="m-hrefSubSubMenu" id="L%LEVEL%-S%SONS%" href="%URL%" title="%URLTITLE%">%TITLE%</a>',
	'hrefDefault'			=> '<a class="nav-link" data-ref="m-hrefDefault" id="L%LEVEL%-S%SONS%" href="%URL%" title="%URLTITLE%">%TITLE%</a>',
	'urlDefault'			=> '#!',
	'valueUrlDefault'		=> '%ID%/%SEOCLEAN%'
);

$optMenuPages = array(
	'ulIsMain'				=> 0,
	'ulMain'				=> '<ul 
								data-ref="mp-ulMain" 
								id="L%LEVEL%-S%SONS%"
								>',
	'ulSubMenu'				=> '<ul 
								class="hs-sub-menu list-unstyled u-shadow-v11 g-min-width-220 g-brd-top g-brd-primary g-brd-top-2 g-mt-17 animated" 
								aria-labelledby="nav-link--pages" 
								style="display: none;" 
								data-ref="mp-ulSubMenu" 
								id="nav-submenu--L%LEVEL%-S%SONS%"
								>',
 	'ulSubSubMenu'			=> '<ul 
	 							data-ref="mp-ulSubSubMenu" 
								id="L%LEVEL%-S%SONS%"
								>',
	'ulDefault'				=> '<ul data-ref="mp-ulDefault" id="L%LEVEL%-S%SONS%">',

	'liMain'				=> '<li 
								class="nav-item%CLASSACTIVE% g-ml-10--lg"
								data-ref="mp-liMain" 
								id="L%LEVEL%-S%SONS%"
								>',
	'liSubMenu'				=> '<li 
								class="nav-item hs-has-sub-menu g-mx-10--lg g-mx-15--xl" 
								data-ref="mp-liSubMenu" 
								id="L%LEVEL%-S%SONS%"
								>',
	'liSubMenuNoSon'		=> '<li
								class="dropdown-item" 
								data-ref="mp-liSubMenuNoSon" 
								id="L%LEVEL%-S%SONS%" 
								>',
	'liSubSubMenu'			=> '<li data-ref="mp-liSubSubMenu" id="L%LEVEL%-S%SONS%">',
	'liDefault'				=> '<li  class="" data-ref="mp-liDefault" id="L%LEVEL%-S%SONS%">',

	'hrefMain'				=> '<a 
								class="nav-link text-uppercase g-color-primary--hover g-pl-5 g-pr-0 g-py-20"
								data-ref="mp-hrefMain" 
								id="L%LEVEL%-S%SONS%" 
								href="%URL%" title="%URLTITLE%">%TITLE%
								</a>',
	'hrefSubMenu'			=> '<a 
								class="nav-link text-uppercase g-color-primary--hover g-px-5 g-py-20" 
								aria-haspopup="true" 
								aria-expanded="false" 
								aria-controls="nav-submenu--pages"
								data-ref="mp-hrefSubMenu" 
								id="nav-link--L%LEVEL%-S%SONS%" 
								href="%URL%" title="%URLTITLE%">%TITLE%
								</a>',
	'hrefSubSubMenu'		=> '<a class="nav-link g-color-gray-dark-v4" 
								id="L%LEVEL%-S%SONS%" 
								href="%URL%" title="%URLTITLE%">%TITLE%
								</a>',
	'hrefDefault'			=> '<a 
								class="nav-link g-color-gray-dark-v4" 
								data-ref="mp-hrefDefault" 
								id="L%LEVEL%-S%SONS%" 
								href="%URL%" title="%URLTITLE%">%TITLE%
								</a>',
	'urlDefault'			=> '#!',
	'valueUrlDefault'		=> '%ID%/%SEOCLEAN%'
);

$optMegamenuCategorie = array(
	'viewProduct'			=> true,
	'colsContainer'			=> array(
		'1'					=> '<div class="col-sm-6 col-lg-2 g-mb-30 g-mb-0--md">%ITEMS%</div>',
		'2'					=> '<div class="col-sm-6 col-lg-3 g-mb-30 g-mb-0--md">%ITEMS%</div>',
		'3'					=> '<div class="col-sm-6 col-lg-3 g-mb-30 g-mb-0--md">%ITEMS%</div>',
		'4'					=> '<div class="col-sm-6 col-lg-3 g-mb-30 g-mb-0--md">%ITEMS%</div>',
	)
);

$optMegamenuCatalogo = array(
	'viewProduct'			=> false,
	'colsContainer'			=> array(
		'1'					=> '<div class="col-sm-6 col-lg-3 g-mb-30 g-mb-0--md">%ITEMS%</div>',
		'2'					=> '<div class="col-sm-6 col-lg-3 g-mb-30 g-mb-0--md">%ITEMS%</div>',
		'3'					=> '<div class="col-sm-6 col-lg-3 g-mb-30 g-mb-0--md">%ITEMS%</div>',
		'4'					=> '<div class="col-sm-6 col-lg-3 g-mb-30 g-mb-0--md">%ITEMS%</div>',
	)
);

$optMenuCategories = array(
	'ulIsMain'=>0,
	'ulMain'=>'<ul data-ref="mp-ulMain" id="L%LEVEL%-S%SONS%">',
	'ulSubMenu'=>'<ul class="hs-sub-menu list-unstyled u-shadow-v11 g-brd-top g-brd-primary g-brd-top-2 g-min-width-220 g-mt-18 g-mt-8--lg--scrolling" aria-labelledby="nav-link--L%LEVEL%-S%SONS%" data-ref="mp-ulSubMenu" id="nav-submenu--L%LEVEL%-S%SONS%">',
	'ulSubSubMenu'=>'<ul data-classref="ulSubSubMenu" data-levels="L%LEVEL%-S%SONS%" class="hs-sub-menu list-unstyled u-shadow-v11 g-brd-top g-brd-primary g-brd-top-2 g-min-width-220 g-mt-minus-2 animated">',
	'ulDefault'=>'<ul data-ref="mp-ulDefault" id="L%LEVEL%-S%SONS%">',	
	'liMain'=>'<li data-classref="liMain" data-level="L%LEVEL%-S%SONS%">',
	'liSubMenu'=>'<li class="nav-item%CLASSACTIVE% hs-has-sub-menu  g-mx-10--lg g-mx-15--xl" data-animation-in="fadeIn" data-animation-out="fadeOut" data-ref="mp-liSubMenu" id="L%LEVEL%-S%SONS%">',
	'liSubSubMenu'=>'<li data-ref="liSubSubMenu" data-id="L%LEVEL%-S%SONS%" class="dropdown-item hs-has-sub-menu">',
	'liDefault'=>'<li  class="dropdown-item" data-ref="mp-liDefault" id="L%LEVEL%-S%SONS%">',
	'hrefMain'=>'<a data-ref="mp-hrefMain" id="L%LEVEL%-S%SONS%" href="%URL%" title="%URLTITLE%">%TITLE%</a>',
	'hrefSubMenu'=>'<a class="nav-link g-py-7 g-px-0" aria-haspopup="true" aria-expanded="false" aria-controls="nav-submenu--L%LEVEL%-S%SONS%" data-ref="mp-hrefSubMenu" id="nav-link--L%LEVEL%-S%SONS%" href="%URL%" title="%URLTITLE%">%TITLE%</a>',
	'hrefSubSubMenu'=>'<a data-ref="hrefSubSubMenu" id="L%LEVEL%-S%SONS%" class="nav-link" aria-haspopup="true" aria-expanded="false" aria-controls="nav-submenu--features--sliders" href="%URL%" title="%URLTITLE%">%TITLE%</a>',
	'hrefDefault'=>'<a data-classref="hrefDefault" data-levels="L%LEVEL%-S%SONS%" class="nav-link" href="%URL%" title="%URLTITLE%">%TITLE%</a>',
	'urlDefault'=>'#!',
	'valueUrlDefault'=>'products/%ALIAS%'
);
?>
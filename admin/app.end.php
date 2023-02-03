<?php
/* app/app.end.php v.3.5.5. 14/05/2019 */



/* DIV MESSAGGI SISTEMA */


/* prende il messaggio */
if (isset($_MY_SESSION_VARS['message']) && $_MY_SESSION_VARS['message'] != '') {
	$mess = explode('|',$_MY_SESSION_VARS['message']);
	$_MY_SESSION_VARS = $my_session->my_session_unsetVar('message');
	}

if (isset($_SESSION['message']) && $_SESSION['message'] != '') {
	$mess = explode('|',$_SESSION['message']);
	unset($_SESSION['message']);
	}

if (isset($mess[0])) Core::$resultOp->error = $mess[0];
if (isset($mess[1])) Core::$resultOp->message =$mess[1];

$App->systemMessages = '';
$appErrors = Utilities::getMessagesCore(Core::$resultOp);
list($show,$error,$type,$content) = $appErrors;
if ($show == true) {
	if ($type == 0 && $error > 0) $type = $error;
	$App->systemMessages .= '<div id="systemMessageID" class="alert';
	if ($type == 2) $App->systemMessages .= ' alert-warning';
	if ($type == 1) $App->systemMessages .= ' alert-danger';
	if ($type == 0) $App->systemMessages .= ' alert-success';
	if ($type > 2) $App->systemMessages .= ' alert-danger';
	$App->systemMessages .= '">'.$content.'</div>';
	}

/* DIV MESSAGGI SISTEMA */

/* MENU */


//print_r($App->modules);

$App->rightCodeMenu = '';
foreach(Config::$modules AS $sectionKey=>$sectionModules) {
	$x1 = 0;
	foreach($sectionModules AS $module) {
		
		//if (Permissions::checkIfModulesIsReadable($module->name,$App->userLoggedData) === true) {
			
			//print_r($module);
			
			$outputMenu = '';
			
			$menu = json_decode($module->code_menu) or die('Errore nel campo menu. Formato Json non valido!'.$module->code_menu);	
			
			//print_r($menu);
			
			$havesubmenu = 0;
			if (isset($menu->submenus) && count($menu->submenus)) $havesubmenu = 1;
			
			$classLiMain = ' class="nav-item"';
			if (isset($App->breadcrumb[1]['name']) && $App->breadcrumb[1]['name'] == $module->name) $classLiMain = ' class="nav-item active"'; 
			
			$auth = 0;
			if (isset($menu->auth) && $menu->auth == "1") $auth = 1; 
			
			if ($auth == 0) {
				
				$outputUlSubmenu = '';
				$outputLiSubmenu = '';

				$moduleName = (isset($module->name) ? $module->name : '');
				$moduleLabel = (isset($module->label) ? $module->label : '');
				$menuName = (isset($menu->name) ? $menu->name : '');
				$menuIcon = (isset($menu->icon) ? $menu->icon : '');
				$menuAction = (isset($menu->action) ? $menu->action : '');
				$menuLabel = (isset($menu->label) ? $menu->label : '');				
				$havesubmenu = 0;			
				$collapsed = ' collapsed';		

				// crea sub menu
				$divSubmenuClass = 'collapse';
				$divSubmenuData = ' aria-labelledby="heading'.$moduleName.'" data-parent="#accordionSidebar"';
					
				if (isset($menu->submenus) && is_array($menu->submenus) && count($menu->submenus) > 0) {
					
					$havesubmenu = 1;
					// crea li sub menu				
					foreach ($menu->submenus AS $submenu) {
						//print_r($submenu);
						$havesubmenu1 = 0;
						if (isset($submenu->submenus) && count($submenu->submenus)) $havesubmenu1 = 1;
						
						$submanuClass = '';
						if (isset( $App->breadcrumb[2]['name']) && $App->breadcrumb[2]['name'] == $submenu->name) $submanuClass = ' class="active"'; 
												
						$subauth = 0;
						if (isset($submenu->auth) && $submenu->auth == "1") $subauth = 1; 						
						if ($subauth == 0) {
							
							$submenuUrl = URL_SITE_ADMIN;							
							$submanuLabel = $submenu->label;
							if (isset($localStrings[$submanuLabel])) $submanuLabel = $localStrings[$submanuLabel];								
							$submenuName = (isset($submenu->name) ? $submenu->name : '');
							$submenuIcon = (isset($submenu->icon) ? $submenu->icon : '');
							$submenuAction = (isset($submenu->action) ? $submenu->action : '');
							
							$submenuUrl .= $moduleName.'/'.$submenuName;
							
							if ($submenuAction == Core::$request->action) {
								$divSubmenuClass = 'collapse show';
								$collapsed = '';
							}
							
							$submanuClass = 'collapse-item';	
							$outputLiSubmenu .= '<a class="'.$submanuClass.'" href="'.$submenuUrl.'">'.$submenuIcon.' '.$submanuLabel.($havesubmenu1 == 1 ? '<span class="fa arrow"></span>' : '').'</a>'.PHP_EOL;			
							
						}	
					}
		
					
					
					$outputUlSubmenu .= '<div id="collapse'.$moduleName.'" class="'.$divSubmenuClass.'"'.$divSubmenuData.'><div class="bg-white py-2 collapse-inner rounded">'.$outputLiSubmenu.'</div></div>';
				}
				
				$liMainClass = 'nav-item';
				$liMainData = '';
				$liMainHrefClass = 'nav-link';	
				$liMainHrefData = '';	
				
				if ($havesubmenu == 1) {
					$liMainHrefClass .= $collapsed;
					$liMainHrefData = ' data-toggle="collapse" data-target="#collapse'.$moduleName.'" aria-expanded="true" aria-controls="collapse'.$moduleName.'"';
				}
	
				// crea il li principale
				$outputMenu = '<li class="'.$liMainClass.'"'.$liMainData.'>';PHP_EOL;
				$outputMenu .= '<a class="'.$liMainHrefClass.'"'.$liMainHrefData.' href="'.URL_SITE_ADMIN.$moduleName.'">'.$menuIcon.' <span>'.$menuLabel.'</span>'.($havesubmenu == 1 ? '<span class="fa arrow"></span>' : '').'</a>';
				
				$outputMenu .= $outputUlSubmenu;
				
				$outputMenu .= '</li>'.PHP_EOL;	
				
				// sostituiso il modulename con la localizzazione se esiste
				if (isset($localStrings[$moduleLabel])) $moduleLabel = $localStrings[$moduleLabel];	
				$outputMenu = preg_replace('/%LABEL%/',$moduleLabel,$outputMenu);
				$outputMenu = preg_replace('/%NAME%/',$moduleLabel,$outputMenu);	
								
				$App->rightCodeMenu .= $outputMenu;		
			
				$x1++;	
				
			}			
			
		//}
		
	}
	if ($x1 > 0) $App->rightCodeMenu .= '';		
}






/*
$App->rightCodeMenu = '';
foreach($App->modules AS $sectionKey=>$sectionModules) {
	$x1 = 0;
	foreach($sectionModules AS $module) {
		
		
		if (Permissions::checkIfModulesIsReadable($module->name,$App->userLoggedData) === true) {
		//if (Permissions::checkAccessUserModule($module->name,$App->userLoggedData,$App->user_modules_active,$App->modulesCore) === true) {
			
			
			$menu = json_decode($module->code_menu) or die('Errore nel campo menu. Formato Json non valido!'.$module->code_menu);	
			
			//print_r($menu);
			$havesubmenu = 0;
			if (isset($menu->submenus) && count($menu->submenus)) $havesubmenu = 1;
			$class = ' class="nav-item"';
			if (isset($App->breadcrumb[1]['name']) && $App->breadcrumb[1]['name'] == $module->name) $class = ' class="nav-item active"'; 
			
			$auth = 0;
			if (isset($menu->auth) && $menu->auth == "1") $auth = 1; 
			
			if ($auth == 0) {
				
				$li = '<li'.$class.'>';
				$codemenu = $li.PHP_EOL;
				
				$label = '';
				if (isset($menu->label)) $label = $menu->label;
				if (isset($localStrings[$label])) $label = $localStrings[$label];
				
				$moduleName = (isset($menu->name) ? $menu->name : '');
				$moduleIcon = (isset($menu->icon) ? $menu->icon : '');
				echo 'aa:'.$moduleAction = (isset($menu->action) ? $menu->action : '');
				
				
				$hrefclass = 'nav-link';	
				$hrefdata = '';		
				if (isset($menu->submenus) && is_array($menu->submenus) && count($menu->submenus) > 0) {
					$hrefclass = 'nav-link collapsed';
					if ($moduleAction == Core::$request->action) $hrefclass = 'nav-link';
					
					
					$hrefdata = ' data-toggle="collapse" data-target="#collapse'.$moduleName.'" aria-expanded="true" aria-controls="collapse'.$moduleName.'"';
				}										
				$codemenu .= '<a class="'.$hrefclass.'"'.$hrefdata.' href="'.URL_SITE_ADMIN.$moduleName.'">'.$moduleIcon.' <span>'.$label.'</span>'.($havesubmenu == 1 ? '<span class="fa arrow"></span>' : '').'</a>';
				
				// aggiunge submenu 1
				if (isset($menu->submenus) && is_array($menu->submenus) && count($menu->submenus) > 0) {
					
					$moduleAction = (isset($menu->action) ? $menu->action : '');
					
					$divclass = 'collapse aa';
					if ($moduleAction == Core::$request->action) $divclass = 'collapse bb show';					
					
$divclass .= ' ma: '.$moduleAction.' ra'.Core::$request->action;					
					
					
					$codemenu .= '<div id="collapse'.$moduleName.'" class="'.$divclass.'" aria-labelledby="heading'.$moduleName.'" data-parent="#accordionSidebar"><div class="bg-white py-2 collapse-inner rounded">';
					foreach ($menu->submenus AS $submenu) {
						$havesubmenu1 = 0;
						if (isset($submenu->submenus) && count($submenu->submenus)) $havesubmenu1 = 1;
						$class = '';
						if (isset( $App->breadcrumb[2]['name']) && $App->breadcrumb[2]['name'] == $submenu->name) $class = ' class="active"'; 						
						$subauth = 0;
						if (isset($submenu->auth) && $submenu->auth == "1") $subauth = 1; 						
						if ($subauth == 0) {
							$url = URL_SITE_ADMIN;							
							$label = $submenu->label;
							if (isset($localStrings[$label])) $label = $localStrings[$label];								
							$submoduleName = (isset($submenu->name) ? $submenu->name : '');
							$submoduleIcon = (isset($submenu->icon) ? $submenu->icon : '');
							
							if (isset($submenu->action) && $submenu->action != '') $url .= $submenu->action.'/';	
							$hrefclass = 'collapse-item';	
							$hrefdata = '';	
							$codemenu .= '<a class="'.$hrefclass.'" href="'.$url.$submoduleName.'">'.$submoduleIcon.' '.$label.($havesubmenu1 == 1 ? '<span class="fa arrow"></span>' : '').'</a>'.PHP_EOL;			
							
							}	
						}
					$codemenu .= '</div></div>'.PHP_EOL;
					}
				// fine aggiunge submenu 1
		
				$codemenu .= '</li>'.PHP_EOL;	
			}
			
			$codemenu = preg_replace('/%LABEL%/',$module->label,$codemenu);
			$codemenu = preg_replace('/%NAME%/',$module->name,$codemenu);	
			$App->rightCodeMenu .= $codemenu;			
			
			$x1++;								
		}			
	}
	if ($x1 > 0) $App->rightCodeMenu .= '';			
}
*/
?>
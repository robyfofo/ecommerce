<?php

/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/classes/class.Utilities.php v.1.0.0. 02/07/2021
 */

class Utilities extends Core
{
	static $totalpage = 0;
	static $treeResult = '';
	static $level = 0;
	private static $pagination;
	private static $arrayTitle = array(); /* gestione titoli parent sub categorie */

	public function __construct()
	{
		parent::__construct();
	}

	public static function getTitlesPage($moduledata, $pagedata, $lang, $opt)
	{
		$optDef = array('titleField' => 'title');
		$opt = array_merge($optDef, $opt);
		$titles[$opt['titleField']] = '';
		$titles[$opt['titleField'] . 'Seo'] = '';
		$titles[$opt['titleField'] . 'Meta'] = '';
		$titles[$opt['titleField'] . 'Alt'] = '';
		$titles[$opt['titleField'] . 'Alt1'] = '';
		if (is_object($pagedata)) {
			$titles[$opt['titleField']] = Multilanguage::getLocaleObjectValue($pagedata, $opt['titleField'] . '_', $lang, array());
			$titles[$opt['titleField'] . 'Seo'] = Multilanguage::getLocaleObjectValue($pagedata, $opt['titleField'] . '_seo_', $lang, array());
			$titles[$opt['titleField'] . 'Meta'] = Multilanguage::getLocaleObjectValue($pagedata, 'meta_' . $opt['titleField'] . '_', $lang, array());
			$titles[$opt['titleField'] . 'Alt'] = Multilanguage::getLocaleObjectValue($pagedata, $opt['titleField'] . '_alt_', $lang, array());
			$titles[$opt['titleField'] . 'Alt1'] = Multilanguage::getLocaleObjectValue($pagedata, $opt['titleField'] . '_alt1_', $lang, array());
		}

		if ($titles[$opt['titleField']] == '') $titles[$opt['titleField']] = $moduledata;
		if ($titles[$opt['titleField'] . 'Seo'] == '') $titles[$opt['titleField'] . 'Seo'] = $moduledata;
		if ($titles[$opt['titleField'] . 'Meta'] == '') $titles[$opt['titleField'] . 'Meta'] = $moduledata;
		if ($titles[$opt['titleField'] . 'Alt'] == '') $titles[$opt['titleField'] . 'Alt'] = $moduledata;
		if ($titles[$opt['titleField'] . 'Alt1'] == '') $titles[$opt['titleField'] . 'Alt1'] = $moduledata;
		return $titles;
	}

	public static function getUnivocalAlias($alias, $opt)
	{
		$optDef = array('fieldrif' => 'alias', 'exclude id' => '', 'table' => '', 'default alias' => '');
		$opt = array_merge($optDef, $opt);
		/* imposta default */
		if ($alias == '') $alias = $opt['default alias'];
		/* controlla se esiste gia un alias */
		if ($opt['table'] != '') {
			$clause = 'alias = ?';
			$fieldValues = array($alias);
			if ($opt['exclude id'] != '') {
				$clause .= ' AND id <> ?';
				$fieldValues[] = intval($opt['exclude id']);
			}
			Sql::initQuery($opt['table'], array('id'), $fieldValues, $clause);
			if (Sql::countRecord() > 0) {
				/* trovato corrispondenza */
				$alias = $alias . '_' . (string)time();
			}
		}
		/* filtra alias */
		$alias = SanitizeStrings::urlslug($alias, array());
		return $alias;
	}

	public static function generatelanguageBar($template, $lang, $sessionuser)
	{
		//ToolsStrings::dump($lang);
		$html = $template['container'];
		$links = '';
		foreach (Config::$globalSettings['languages'] as $l) {
			$url = Multilanguage::getLanguageUrl($l, array());
			if ($l == $sessionuser) {
				$lk = preg_replace('/%URL%/', $url, $template['links active']);
			} else {
				$lk = preg_replace('/%URL%/', $url, $template['links']);
			}
			$lk = preg_replace('/%TITLE%/', ucfirst($lang['lista lingue'][$l]), $lk);
			$links .= $lk;
		}
		$html = preg_replace('/%LINKS%/', $links, $html);
		$html = preg_replace('/%LANGACTIVE%/', ucfirst($lang['label']), $html);
		return $html;
	}

	public static function generateBreadcrumbsTree($array, $lang, $opt = array())
	{
		$optDef = array('template' => '');
		$opt = array_merge($optDef, $opt);
		$str = '';
		$links = '';
		/* aggiunge i link */
		if (is_array($array) && count($array) > 0) {
			$str = $opt['template']['container'];
			foreach ($array as $value) {
				if ($value['class'] == 'home') {
					$links .= $opt['template']['links home'];
				} else if ($value['class'] == 'active') {
					$links .= $opt['template']['links active'];
				} else {
					if (isset($value['url']) && $value['url'] != '') {
						$links .= $opt['template']['links'];
					} else {
						$links .= $opt['template']['nolinks'];
					}
				}
				if (isset($value['title'])) $links = preg_replace('/%TITLE%/', $value['title'], $links);
				if (isset($value['url'])) $links = preg_replace('/%URL%/', $value['url'], $links);
			}

			$str = preg_replace('/%LINKS%/', $links, $str);
			$str = preg_replace('/%TITLEPAGE%/', $value['title'], $str);
		}
		return $str;
	}

	public static function redirect($url)
	{
		$protocol = "http://";
		$server_name = $_SERVER["HTTP_HOST"];
		if ($server_name != '') {
			$protocol = "http://";
			if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == "on")) {
				$protocol = "https://";
			}
			if (preg_match("#^/#", $url)) {
				$url = $protocol . $server_name . $url;
			} else if (!preg_match("#^[a-z]+://#", $url)) {
				$script = $_SERVER['PHP_SELF'];
				if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] != '' && $_SERVER['PATH_INFO'] != $_SERVER['PHP_SELF']) {
					$script = substr($script, 0, strlen($script) - strlen($_SERVER['PATH_INFO']));
				}
				$url = $protocol . $server_name . (preg_replace("#/[^/]*$#", "/", $script)) . $url;
			}
			$url = str_replace(" ", "%20", $url);
			header("Location: " . $url);
		}
		exit;
	}

	public static function getHTMLMessagesCore($obj, $opz = array())
	{
		$opzDef = array(
			'template' => '<div class="container"><div class="row"><div class="col-12"><div class="alert%CLASS%">%CONTENT%</div></div></div></div>',
			'class danger' => ' alert-danger fade in',
			'class success' => ' alert-success fade in',
			'class warning' => ' alert-warning fade in',
			'class default' => 'white'
		);
		$opz = array_merge($opzDef, $opz);
		$html = '';
		list($show, $error, $type, $content) = self::getMessagesCore($obj);
		if ($content != '') {
			$html = $opz['template'];
			$alert = $opz['class default'];
			if ($error == 1) $alert = $opz['class danger'];
			if ($error == 0) $alert = $opz['class success'];
			if ($error == 2) $alert = $opz['class warning'];

			$html = preg_replace('/%CLASS%/', $alert, $html);
			$html = preg_replace('/%CONTENT%/', $content, $html);
		}
		return $html;
	}

	public static function getMessagesCore($obj)
	{
		$show = false;
		$error = 0;
		$content = '';
		$type = 0;
		if (isset($obj->error)) $error = $obj->error;
		if (isset($obj->type)) $type = $obj->type;
		if (isset($obj->message) && $obj->message != '') $obj->messages[] = $obj->message;
		/* content */
		if (isset($obj->messages) && is_array($obj->messages) && count($obj->messages) > 0) {
			$content .= implode('<br>', $obj->messages);
			$show = true;
		}
		return array($show, $error, $type, $content);
	}

	public static function getPagination($page = 1, $itemsTotal = 1, $itemsForPage = 1)
	{
		self::$pagination = new StdClass;
		$arr = '';
		$loop_previous = 2;
		$loop_next = 2;
		$totalpage = 1;
		$previous = 1;
		$next = 1;
		$pagePrevious = array();
		$pageNext = array();
		$firstPartItem = 1;
		$lastPartItem = 1;

		if ($itemsForPage > 0) $totalpage = ceil($itemsTotal / $itemsForPage);
		if ($page > $totalpage) $page = 1;
		if ($itemsTotal >= $itemsForPage) {
			$previous = ($page > 1 ? $page - 1 : $page);
			if ($page == $totalpage) {
				$next = $page;
			} else if ($page < $totalpage) {
				$next = $page + 1;
			}
			if ($page < $loop_previous) {
				$loop_previous = 1;
			} else if ($page == $loop_previous) {
				$loop_previous = $page - $loop_previous + 1;
			} else if ($page > $loop_previous) {
				$loop_previous = $page - $loop_previous;
			}
			for ($i = $loop_previous; $i < $page; $i++) {
				$pagePrevious[] = $i;
			}

			$pgleft = $totalpage - $page;

			if ($pgleft < $loop_next) {
				$loop_next = $page + $pgleft;
			}
			if ($pgleft == $loop_next) {
				$loop_next = $page + $pgleft;
			}
			if ($pgleft > $loop_next) {
				$loop_next = $page + $loop_next;
			}
			for ($i = $page + 1; $i < $loop_next + 1; $i++) {
				$pageNext[] = $i;
			}

			$firstPartItem = ($page * $itemsForPage + 1) - $itemsForPage;
			$lastPartItem  = ($page * $itemsForPage);
			if ($lastPartItem > $itemsTotal) $lastPartItem  = $itemsTotal;
		}

		self::$pagination->totalpage = $totalpage;
		self::$pagination->itemPrevious = $previous;
		self::$pagination->itemNext = $next;
		self::$pagination->pagePrevious = $pagePrevious;
		self::$pagination->pageNext = $pageNext;
		self::$pagination->itemsTotal = $itemsTotal;
		self::$pagination->itemsForPage = $itemsForPage;
		self::$pagination->firstPartItem = $firstPartItem;
		self::$pagination->lastPartItem = $lastPartItem;
		self::$pagination->page = $page;

		self::$pagination->summaryLabel = 'aaaaa';
		if (isset(Config::$localStrings['pagina %PAGE% di %TOTALPAGE%'])) {
			self::$pagination->summaryLabel = preg_replace(
				array('/%PAGE%/','/%TOTALPAGE%/'),
				array($page,$totalpage),
				Config::$localStrings['pagina %PAGE% di %TOTALPAGE%']
			);
			//Config::$localStrings['pagina %PAGE% di %TOTALPAGE%'];
		}
		return self::$pagination;
	}

	public static function formatObjWithPagination($obj, $itemsForPage, $firstPartItem)
	{
		/* crea l'array in base alla paginazione */
		$objTemp = new stdClass;
		$p1 = 0;
		for ($p = 0; $p <= $itemsForPage - 1; $p++) {
			$key = $firstPartItem + $p - 1;
			if (isset($obj->$key)) {
				$objTemp->$key = new stdClass;
				$objTemp->$key = $obj->$key;
				$p1++;
			}
		}
		return $objTemp;
	}

	public static function formatArrayWithPagination($obj, $itemsForPage, $firstPartItem)
	{
		/* crea l'array in base alla paginazione */
		$objTemp = '';
		$p1 = 0;
		for ($p = 0; $p <= $itemsForPage - 1; $p++) {
			$key = $firstPartItem + $p - 1;
			if (isset($obj[$key])) {
				$objTemp[$key] = new stdClass;
				$objTemp[$key] = $obj[$key];
				$p1++;
			}
		}
		return $objTemp;
	}

	public static function decreaseFieldOrdering($id, $lang, $opt)
	{
		$optDef = array('addclauseparent' => '', 'addclauseparentvalues' => array(), 'idFieldRif' => 'id', 'parent' => 0, 'parentField' => 'parent', 'orderingFieldRif' => 'ordering', 'orderingType' => 'DESC', 'label' => $lang['voce'] . ' ' . $lang['spostata'], 'table' => '');
		$opt = array_merge($optDef, $opt);
		$orderingFieldRif = $opt['orderingFieldRif'];
		$parentField = $opt['parentField'];
		/* recupera l'orinamento */
		/* imposta i campi di riferimento */
		$field = array($opt['orderingFieldRif']);
		if ($opt['parent'] == 1) {
			$field = array($opt['parentField'], $opt['orderingFieldRif']);
		}
		/* prende l'ordinamento memorizzato */
		Sql::initQuery($opt['table'], $field, array($id), $opt['idFieldRif'] . ' = ?');
		$itemData = Sql::getRecord();
		if (self::$resultOp->error == 0) {
			if (isset($itemData->$orderingFieldRif) && $itemData->$orderingFieldRif > 0) {
				/* controlla che si siano valori inferiori */
				/* imposta i campi di riferimento */
				$where = $opt['orderingFieldRif'] . ' < ?';
				$fieldsValues = array($itemData->$orderingFieldRif);
				if ($opt['parent'] == 1) {
					$fieldsValues = array($itemData->$orderingFieldRif, $itemData->$parentField);
					if (count($opt['addclauseparentvalues']) > 0) $fieldsValues = array_merge($fieldsValues, $opt['addclauseparentvalues']);
					$where .= ' AND ' . $opt['parentField'] . ' = ?';
					if ($opt['addclauseparent'] != '') $where .= ' AND ' . $opt['addclauseparent'];
				}
				$count = Sql::initQuery($opt['table'], array($id), $fieldsValues, $where);
				if (self::$resultOp->type == 0) {
					$count = Sql::countRecord();
					if ($count > 0) {
						$where = $opt['orderingFieldRif'] . ' = ?';
						$fieldsValues = array($itemData->$orderingFieldRif - 1);
						if ($opt['parent'] == 1) {
							$fieldsValues = array($itemData->$orderingFieldRif - 1, $itemData->$parentField);
							if (count($opt['addclauseparentvalues']) > 0) $fieldsValues = array_merge($fieldsValues, $opt['addclauseparentvalues']);
							$where .= ' AND ' . $opt['parentField'] . ' = ?';
							if ($opt['addclauseparent'] != '') $where .= ' AND ' . $opt['addclauseparent'];
						}
						/* controlla se c'e un ordine inferiore */
						$count = Sql::initQuery($opt['table'], array($id), $fieldsValues, $where);
						$count = Sql::countRecord();
						if (self::$resultOp->type == 0) {
							if ($count > 0) {
								$where = $opt['orderingFieldRif'] . ' = ?';
								$fieldsValues = array($itemData->$orderingFieldRif, $itemData->$orderingFieldRif - 1);
								if ($opt['parent'] == 1) {
									$fieldsValues = array($itemData->$orderingFieldRif, $itemData->$orderingFieldRif - 1, $itemData->$parentField);
									if (count($opt['addclauseparentvalues']) > 0) $fieldsValues = array_merge($fieldsValues, $opt['addclauseparentvalues']);
									$where .= ' AND ' . $opt['parentField'] . ' = ?';
									if ($opt['addclauseparent'] != '') $where .= ' AND ' . $opt['addclauseparent'];
								}
								Sql::initQuery($opt['table'], array($opt['orderingFieldRif']), $fieldsValues, $where);
								Sql::updateRecord();
							}
							$where = $opt['idFieldRif'] . ' = ?';
							$fieldsValues = array($itemData->$orderingFieldRif - 1, $id);
							if ($opt['parent'] == 1) {
								$fieldsValues = array($itemData->$orderingFieldRif - 1, $id, $itemData->$parentField);
								if (count($opt['addclauseparentvalues']) > 0) $fieldsValues = array_merge($fieldsValues, $opt['addclauseparentvalues']);
								$where .= ' AND ' . $opt['parentField'] . ' = ?';
								if ($opt['addclauseparent'] != '') $where .= ' AND ' . $opt['addclauseparent'];
							}
							Sql::initQuery($opt['table'], array($opt['orderingFieldRif']), $fieldsValues, $where);
							Sql::updateRecord();
							if (self::$resultOp->type == 0) {
								self::$resultOp->message =  ($opt['orderingType'] == 'DESC' ? $opt['label'] . ' ' . $lang['giu'] . '!' : $opt['label'] . ' ' . $lang['su'] . '!');
								self::$resultOp->message = ucfirst(self::$resultOp->message);
							} else {
								self::$resultOp->type = 1;
								self::$resultOp->message = $lang['Non è possibile diminuire ordinamento!'];
							}
						} else {
							self::$resultOp->type = 1;
							self::$resultOp->message = $lang['Non è possibile diminuire ordinamento!'];
						}
					} else {
						self::$resultOp->type = 1;
						self::$resultOp->message = $lang['Non è possibile diminuire ordinamento!'];
					}
				} else {
					self::$resultOp->type = 1;
					self::$resultOp->message = $lang['Non è possibile diminuire ordinamento!'];
				}
			} else {
				self::$resultOp->type = 1;
				self::$resultOp->message = $lang['Non è possibile diminuire ordinamento!'];
			}
		} else {
			self::$resultOp->type = 1;
			self::$resultOp->message = $lang['Non è possibile diminuire ordinamento!'];
		}
		self::$resultOp->error = 0;
	}

	public static function increaseFieldOrdering($id, $lang, $opt)
	{
		$optDef = array('addclauseparent' => '', 'addclauseparentvalues' => array(), 'idFieldRif' => 'id', 'parent' => 0, 'parentField' => 'parent', 'orderingFieldRif' => 'ordering', 'orderingType' => 'DESC', 'label' => $lang['voce'] . ' ' . $lang['spostata'], 'table' => '');
		$opt = array_merge($optDef, $opt);
		$orderingFieldRif = $opt['orderingFieldRif'];
		$parentField = $opt['parentField'];
		/* recupera l'orinamento */
		/* imposta i campi di riferimento */
		$field = array($opt['orderingFieldRif']);
		if ($opt['parent'] == 1) {
			$field = array($opt['parentField'], $opt['orderingFieldRif']);
		}
		/* prende l'ordinamento memorizzato */
		Sql::initQuery($opt['table'], $field, array($id), $opt['idFieldRif'] . ' = ?');
		$itemData = Sql::getRecord();
		if (self::$resultOp->error == 0) {
			if (isset($itemData->$orderingFieldRif) && $itemData->$orderingFieldRif > 0) {
				/* controlla che si siano valori superiori */
				/* imposta i campi di riferimento */
				$where = $opt['orderingFieldRif'] . ' > ?';
				$fieldsValues = array($itemData->$orderingFieldRif);
				if ($opt['parent'] == 1) {
					$fieldsValues = array($itemData->$orderingFieldRif, $itemData->$parentField);
					if (count($opt['addclauseparentvalues']) > 0) $fieldsValues = array_merge($fieldsValues, $opt['addclauseparentvalues']);
					$where .= ' AND ' . $parentField . ' = ?';
					if ($opt['addclauseparent'] != '') $where .= ' AND ' . $opt['addclauseparent'];
				}
				$count = Sql::initQuery($opt['table'], array($id), $fieldsValues, $where);
				if (self::$resultOp->type == 0) {
					$count = Sql::countRecord();
					if ($count > 0) {
						/* controlla se c'e un ordine superiore */
						$where = $opt['orderingFieldRif'] . ' = ?';
						$fieldsValues = array($itemData->$orderingFieldRif + 1);
						if ($opt['parent'] == 1) {
							$fieldsValues = array($itemData->$orderingFieldRif + 1, $itemData->$parentField);
							if (count($opt['addclauseparentvalues']) > 0) $fieldsValues = array_merge($fieldsValues, $opt['addclauseparentvalues']);
							$where .= ' AND ' . $parentField . ' = ?';
							if ($opt['addclauseparent'] != '') $where .= ' AND ' . $opt['addclauseparent'];
						}
						/* controlla se c'e un ordine superiore */
						$count = Sql::initQuery($opt['table'], array($id), $fieldsValues, $where);
						$count = Sql::countRecord();
						if (self::$resultOp->type == 0) {
							if ($count > 0) {
								$where = $opt['orderingFieldRif'] . ' = ?';
								$fieldsValues = array($itemData->$orderingFieldRif, $itemData->$orderingFieldRif + 1);
								if ($opt['parent'] == 1) {
									$fieldsValues = array($itemData->$orderingFieldRif, $itemData->$orderingFieldRif + 1, $itemData->$parentField);
									if (count($opt['addclauseparentvalues']) > 0) $fieldsValues = array_merge($fieldsValues, $opt['addclauseparentvalues']);
									$where .= ' AND ' . $opt['parentField'] . ' = ?';
									if ($opt['addclauseparent'] != '') $where .= ' AND ' . $opt['addclauseparent'];
								}
								Sql::initQuery($opt['table'], array($opt['orderingFieldRif']), $fieldsValues, $where);
								Sql::updateRecord();
							}
							$where = $opt['idFieldRif'] . ' = ?';
							$fieldsValues = array($itemData->$orderingFieldRif + 1, $id);
							if ($opt['parent'] == 1) {
								$fieldsValues = array($itemData->$orderingFieldRif + 1, $id, $itemData->$parentField);
								if (count($opt['addclauseparentvalues']) > 0) $fieldsValues = array_merge($fieldsValues, $opt['addclauseparentvalues']);
								$where .= ' AND ' . $opt['parentField'] . ' = ?';
								if ($opt['addclauseparent'] != '') $where .= ' AND ' . $opt['addclauseparent'];
							}
							Sql::initQuery($opt['table'], array($opt['orderingFieldRif']), $fieldsValues, $where);
							Sql::updateRecord();
							if (self::$resultOp->type == 0) {
								self::$resultOp->message = ($opt['orderingType'] == 'DESC' ? $opt['label'] . ' ' . $lang['su'] . '!' : $opt['label'] . ' ' . $lang['giu'] . '!');
								self::$resultOp->message = ucfirst(self::$resultOp->message);
							} else {
								self::$resultOp->type == 1;
								self::$resultOp->message = $lang['Non è possibile aumentare ordinamento!'];
							}
						} else {
							self::$resultOp->type = 1;
							self::$resultOp->message = $lang['Non è possibile aumentare ordinamento!'];
						}
					} else {
						self::$resultOp->type = 1;
						self::$resultOp->message = $lang['Non è possibile aumentare ordinamento!'];
					}
				} else {
					self::$resultOp->type = 1;
					self::$resultOp->message = $lang['Non è possibile aumentare ordinamento!'];
				}
			} else {
				self::$resultOp->type = 1;
				self::$resultOp->message = $lang['Non è possibile aumentare ordinamento!'];
			}
		} else {
			self::$resultOp->type = 1;
			self::$resultOp->message = $lang['Non è possibile aumentare ordinamento!'];
		}
		self::$resultOp->error = 0;
	}

	public static function setItemDataObjWithPost($obj, $fields)
	{
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $key => $value) {
				if (isset($_POST[$key])) $obj->$key = $_POST[$key];
			}
		}
		return $obj;
	}

	/* VOCI AD ALBERO */

	public static function filterListDataObj($mainData, $fieldsSearch, $search)
	{
		$obj = new stdclass();
		$arr = array();
		if (is_array($mainData)) {
			foreach ($mainData as $key => $value) {
				$copy = false;
				if (is_array($fieldsSearch)) {
					foreach ($fieldsSearch as $key1 => $value1) {
						if (isset($value->$key1)) {
							if (strripos($value->$key1, $search) !== false) $copy = true;
						}
					}
				}
				if ($copy == true) $arr[] = $value;
			}
		}
		return $arr;
	}

	public static function filterListDataArray($mainData, $fieldsSearch, $search)
	{
		$obj = new stdclass();
		$arr = array();
		if (is_array($mainData)) {
			foreach ($mainData as $key => $value) {
				$copy = false;
				if (is_array($fieldsSearch)) {
					foreach ($fieldsSearch as $key1 => $value1) {
						if (isset($value->$key1)) {
							if (strripos($value->$key1, $search) !== false) {
								$copy = true;
							}
						}
					}
				}
				if ($copy == true) $arr[] = $value;
			}
		}
		return $arr;
	}

	public static function generatePagesTreeUlList($obj, $parent, $opz, $activePage = 1)
	{
		$has_children = false;
		$classMainUl = (isset($opz['classMainUl']) && $opz['classMainUl'] != '' ? $opz['classMainUl'] : '');
		$classSubUl = (isset($opz['classSubUl']) && $opz['classSubUl'] != '' ? $opz['classSubUl'] : '');
		$ulclass = '';

		$classMainLi = (isset($opz['classMainLi']) && $opz['classMainLi'] != '' ? $opz['classMainLi'] : '');
		$classSubLiParent = (isset($opz['classSubLiParent']) && $opz['classSubLiParent'] != '' ? $opz['classSubLiParent'] : '');
		$classSubLi = (isset($opz['classSubLi']) && $opz['classSubLi'] != '' ? $opz['classSubLi'] : '');
		$classDefLi = (isset($opz['classDefLi']) && $opz['classDefLi'] != '' ? $opz['classDefLi'] : '');
		$classLi = $classDefLi;

		$classMainAref = (isset($opz['classMainAref']) && $opz['classMainAref'] != '' ? $opz['classMainAref'] : '');
		$classSubArefParent = (isset($opz['classSubArefParent']) && $opz['classSubArefParent'] != '' ? $opz['classSubArefParent'] : '');
		$classSubAref = (isset($opz['classSubAref']) && $opz['classSubAref'] != '' ? $opz['classSubAref'] : '');
		$classAref = $classSubAref;

		$aRefSuffixStringMain = (isset($opz['aRefSuffixStringMain']) && $opz['aRefSuffixStringMain'] != '' ? $opz['aRefSuffixStringMain'] : '');
		$aRefSuffixStringSubParent = (isset($opz['aRefSuffixStringSubParent']) && $opz['aRefSuffixStringSubParent'] != '' ? $opz['aRefSuffixStringSubParent'] : '');
		$aRefSuffixStringSub = (isset($opz['aRefSuffixStringSub']) && $opz['aRefSuffixStringSub'] != '' ? $opz['aRefSuffixStringSub'] : '');
		$aRefSuffixString =  $aRefSuffixStringSub;

		$titlePrefixStringMain = (isset($opz['titlePrefixStringMain']) && $opz['titleSuffixStringMain'] != '' ? $opz['titlePrefixStringMain'] : '');
		$titlePrefixStringSubParent = (isset($opz['titlePrefixStringSubParent']) && $opz['titlePrefixStringSubParent'] != '' ? $opz['titlePrefixStringSubParent'] : '');
		$titlePrefixStringSub = (isset($opz['titlePrefixStringSub']) && $opz['titlePrefixStringSub'] != '' ? $opz['titlePrefixStringSub'] : '');
		$titlePrefixString =  $titlePrefixStringSub;

		$titleSuffixStringMain = (isset($opz['titleSuffixStringMain']) && $opz['titleSuffixStringMain'] != '' ? $opz['titleSuffixStringMain'] : '');
		$titleSuffixStringSubParent = (isset($opz['titleSuffixStringSubParent']) && $opz['titleSuffixStringSubParent'] != '' ? $opz['titleSuffixStringSubParent'] : '');
		$titleSuffixStringSub = (isset($opz['titleSuffixStringSub']) && $opz['titleSuffixStringSub'] != '' ? $opz['titleSuffixStringSub'] : '');
		$titleSuffixString =  $titleSuffixStringSub;

		$showId = (isset($opz['showId']) && $opz['showId'] != '' ? $opz['showId'] : false);

		$titleField = (isset($opz['titleField']) && $opz['titleField'] != '' ? $opz['titleField'] : '');

		$langSuffix = (isset($opz['langSuffix']) && $opz['langSuffix'] != '' ? $opz['langSuffix'] : 'it');
		$valueUrlDefault = (isset($opz['valueUrlDefault']) && $opz['valueUrlDefault'] != '' ? $opz['valueUrlDefault'] : '');
		$valueUrlEmpty = (isset($opz['valueUrlEmpty']) && $opz['valueUrlEmpty'] != '' ? $opz['valueUrlEmpty'] : '');

		if (is_array($obj) && count($obj) > 0) {
			foreach ($obj as $key => $value) {

				if (intval($value->parent) == $parent) {

					if ($has_children === false) {
						/* Switch the flag, start the list wrapper, increase the level count */
						$has_children = true;
						/* mostra id */
						$strShowHrefId = '';
						$strShowLiId = '';
						$strShowUlId = '';
						if ($showId == true) {
							$strShowHrefId = ' id="APage' . $value->id . 'ID"';
							$strShowLiId = ' id="liPage' . $value->id . 'ID"';
							$strShowUlId = ' id="UlPage' . $value->id . 'ID"';
						}
						if (self::$level == 0) $ulclass = $classMainUl;
						if (self::$level > 0) $ulclass = $classSubUl;
						if (self::$level > $opz['MainUl']) self::$treeResult .= '<ul' . $strShowUlId . ' class="' . $ulclass . '">' . "\n";
					}

					$fieldTitle = 'title_';
					$fieldTitleSeo = 'title_seo_';
					$fieldTitleMeta = 'title_meta_';

					if ($titleField != '')  $fieldTitle = rtrim($titleField, '_') . '_' . $langSuffix;

					/* gestione multilingua */
					$valueTitle = Multilanguage::getLocaleObjectValue($value, $fieldTitle, $langSuffix, array());
					$valueTitleSeo = Multilanguage::getLocaleObjectValue($value, $fieldTitleSeo, $langSuffix, array());
					$valueTitleMeta = Multilanguage::getLocaleObjectValue($value, $fieldTitleMeta, $langSuffix, array());


					if (self::$level == 0) $classLi = $classMainLi;
					if (self::$level == 0 && $value->sons == 0) $classLi = $classSubLi;
					if (self::$level > 0 && $value->sons > 0) $classLi = $classSubLiParent;
					if (self::$level > 0 && $value->sons == 0) $classLi = $classDefLi;
					/* active page */
					if (self::$level == 0 && $value->alias == $activePage) $classLi .= ' active';
					if (self::$level == 0) $classAref = $classMainAref;
					if (self::$level == 0 && $value->sons == 0) $classAref = $classSubAref;
					if (self::$level > 0 && $value->sons > 0) $classAref = $classSubArefParent;
					if (self::$level == 0) $aRefSuffixString = $aRefSuffixStringMain;
					if (self::$level == 0 && $value->sons == 0) $aRefSuffixString = $aRefSuffixStringSub;
					if (self::$level > 0 && $value->sons > 0) $aRefSuffixString = $aRefSuffixStringSubParent;
					if (self::$level == 0) $titlePrefixString = $titlePrefixStringMain;
					if (self::$level == 0 && $value->sons == 0) $titlePrefixString = $titlePrefixStringSub;
					if (self::$level > 0 && $value->sons > 0) $titlePrefixString = $titlePrefixStringSubParent;
					if (self::$level > 0 && $value->sons == 0) $titlePrefixString = $titlePrefixStringSub;
					if (self::$level == 0) $titleSuffixString = $titleSuffixStringMain;
					if (self::$level == 0 && $value->sons == 0) $titleSuffixString = $titleSuffixStringSub;
					if (self::$level > 0 && $value->sons > 0) $titleSuffixString = $titleSuffixStringSubParent;
					if (self::$level > 0 && $value->sons == 0) $titleSuffixString = $titlePrefixStringSub;

					/* crea l'url */
					switch ($value->type) {
						case 'label':
							$target = '';
							$hrefValue = $valueUrlEmpty;
							$pagesModule = '';
							break;
						case 'module':
							$target = '';
							$hrefValue = URL_SITE . $value->url;
							$pagesModule = '';
							break;
						case 'url':
							$target = $value->target;
							$hrefValue = $value->url;
							$pagesModule = '';
							break;
						default:
							$target = '';
							$pagesModule = (isset($opz['pagesModule']) && $opz['pagesModule'] != '' ? $opz['pagesModule'] :  URL_SITE . 'page/');
							$hrefValue = $valueUrlDefault;
							$hrefValue = $pagesModule . $hrefValue;
							break;
					}

					$hrefValue = str_replace('{{URLSITE}}', URL_SITE, $hrefValue);
					$hrefValue = preg_replace('/{{ID}}/', $value->id, $hrefValue);
					$hrefValue = preg_replace('/{{SEO}}/', $valueTitleSeo, $hrefValue);
					$hrefValue = preg_replace('/{{SEOCLEAN}}/', SanitizeStrings::urlslug($valueTitleSeo, array()), $hrefValue);
					$hrefValue = preg_replace('/{{SEOENCODE}}/', urlencode($valueTitleSeo), $hrefValue);
					$hrefValue = preg_replace('/{{TITLE}}/', urlencode($valueTitleSeo), $hrefValue);

					self::$treeResult .= '<li' . $strShowLiId . ' class="' . $classLi . '">' . "\n";
					self::$treeResult .= '<a' . $strShowHrefId . ' class="' . $classAref . '" href="' . $hrefValue . '"';
					if ($target != '') self::$treeResult .= ' target="' . $target . '"';
					self::$treeResult .= $aRefSuffixString . '>' . "\n";
					self::$treeResult .= $titlePrefixString . $valueTitle . $titleSuffixString . "\n";
					self::$treeResult .= '</a>' . "\n";
					$id = intval($value->id);
					self::$level++;
					self::generatePagesTreeUlList($obj, $id, $opz);
					self::$level--;
					self::$treeResult .= '</li>' . "\n";
				}
			}
		}
		if ($has_children === true && self::$level > $opz['MainUl']) self::$treeResult .= '</ul>' . "\n";
	}

	public static function getTitleParent($obj, $id, $field, $opz = '')
	{
		$output = '';
		self::$arrayTitle = '';
		self::getTitleParentSub($obj, $id, $field, $opz);
		if (is_array(self::$arrayTitle)) {
			$c = count(self::$arrayTitle);
			unset(self::$arrayTitle[0]);
			krsort(self::$arrayTitle);
			$output = implode('->', self::$arrayTitle);
		}
		if ($output != '') $output .= '->';
		return $output;
	}

	public static function getTitleParentSub($obj, $id, $field, $opz)
	{
		if ($id > 0) {
			foreach ($obj as $key => $value) {
				if ($id == $value->id) {
					$key = $value->parent;
					self::$arrayTitle[] = $value->$field;
					self::getTitleParentSub($obj, $key, $field, $opz);
					break;
				}
			}
		}
	}

	public static function resetTreeResult()
	{
		self::$treeResult = '';
	}

	public static function getTreeResult()
	{
		return self::$treeResult;
	}
}

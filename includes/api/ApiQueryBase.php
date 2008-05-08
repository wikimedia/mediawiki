<?php

/*
 * Created on Sep 7, 2006
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if (!defined('MEDIAWIKI')) {
	// Eclipse helper - will be ignored in production
	require_once ('ApiBase.php');
}

/**
 * This is a base class for all Query modules.
 * It provides some common functionality such as constructing various SQL queries.
 *
 * @addtogroup API
 */
abstract class ApiQueryBase extends ApiBase {

	private $mQueryModule, $mDb, $tables, $where, $fields, $options;
	
	const LEFT_JOIN = 1;
	const RIGHT_JOIN = 2;

	public function __construct($query, $moduleName, $paramPrefix = '') {
		parent :: __construct($query->getMain(), $moduleName, $paramPrefix);
		$this->mQueryModule = $query;
		$this->mDb = null;
		$this->resetQueryParams();
	}

	protected function resetQueryParams() {
		$this->tables = array ();
		$this->where = array ();
		$this->fields = array ();
		$this->options = array ();
	}

	protected function addTables($tables, $alias = null) {
		if (is_array($tables)) {
			if (!is_null($alias))
				ApiBase :: dieDebug(__METHOD__, 'Multiple table aliases not supported');
			$this->tables = array_merge($this->tables, $tables);
		} else {
			if (!is_null($alias))
				$tables = $this->getDB()->tableName($tables) . ' ' . $alias;
			$this->tables[] = $tables;
		}
	}

	protected function addJoin($tables, $types, $onClauses, $aliases = null) {
		if(is_null($aliases))
			foreach($tables as $unused)
				$aliases[] = null;
		if(!is_array($tables) || !is_array($types) || !is_array($onClauses) || !is_array($aliases))
			ApiBase::dieDebug(__METHOD__, 'This function only takes arrays as parameters');
		$sql = $this->getDB()->tableName($tables[0]) . (is_null($aliases[0]) ? "" : " {$aliases[0]}");
		for($i = 0; $i < count($tables) - 1; $i++)
		{
			if($types[$i] == self::LEFT_JOIN)
				$join = "LEFT JOIN";
			else if($types[$i] == self::RIGHT_JOIN)
				$join = "RIGHT JOIN";
			else
				ApiBase::dieDebug(__METHOD__, "Invalid join type {$types[$i]}");
			
			if(is_array($onClauses[$i]))
				$on = $this->getDB()->makeList($onClauses[$i], LIST_AND);
			else
				$on = $onClauses[$i];
			$alias = $aliases[$i+1];
			$tblname = $this->getDB()->tableName($tables[$i+1]) . (is_null($alias) ? "" : " $alias");
			$sql = "$sql $join $tblname ON $on";
		}
		$this->addTables($sql);
	}
		
		

	protected function addFields($value) {
		if (is_array($value))
			$this->fields = array_merge($this->fields, $value);
		else
			$this->fields[] = $value;
	}

	protected function addFieldsIf($value, $condition) {
		if ($condition) {
			$this->addFields($value);
			return true;
		}
		return false;
	}

	protected function addWhere($value) {
		if (is_array($value))
			$this->where = array_merge($this->where, $value);
		else
			$this->where[] = $value;
	}

	protected function addWhereIf($value, $condition) {
		if ($condition) {
			$this->addWhere($value);
			return true;
		}
		return false;
	}

	protected function addWhereFld($field, $value) {
		if (!is_null($value))
			$this->where[$field] = $value;
	}

	protected function addWhereRange($field, $dir, $start, $end) {
		$isDirNewer = ($dir === 'newer');
		$after = ($isDirNewer ? '>=' : '<=');
		$before = ($isDirNewer ? '<=' : '>=');
		$db = $this->getDB();

		if (!is_null($start))
			$this->addWhere($field . $after . $db->addQuotes($start));

		if (!is_null($end))
			$this->addWhere($field . $before . $db->addQuotes($end));

		$order = $field . ($isDirNewer ? '' : ' DESC');
		if (!isset($this->options['ORDER BY']))
			$this->addOption('ORDER BY', $order);
		else
			$this->addOption('ORDER BY', $this->options['ORDER BY'] . ', ' . $order);
	}

	protected function addOption($name, $value = null) {
		if (is_null($value))
			$this->options[] = $name;
		else
			$this->options[$name] = $value;
	}

	protected function select($method) {

		// getDB has its own profileDBIn/Out calls
		$db = $this->getDB();

		$this->profileDBIn();
		$res = $db->select($this->tables, $this->fields, $this->where, $method, $this->options);
		$this->profileDBOut();

		return $res;
	}

	protected function checkRowCount() {
		$db = $this->getDB();
		$this->profileDBIn();
		$rowcount = $db->estimateRowCount($this->tables, $this->fields, $this->where, __METHOD__, $this->options);
		$this->profileDBOut();

		global $wgAPIMaxDBRows;
		if($rowcount > $wgAPIMaxDBRows)
			return false;
		return true;
	}

	public static function addTitleInfo(&$arr, $title, $prefix='') {
		$arr[$prefix . 'ns'] = intval($title->getNamespace());
		$arr[$prefix . 'title'] = $title->getPrefixedText();
	}

	/**
	 * Override this method to request extra fields from the pageSet
	 * using $pageSet->requestField('fieldName')
	 */
	public function requestExtraData($pageSet) {
	}

	/**
	 * Get the main Query module
	 */
	public function getQuery() {
		return $this->mQueryModule;
	}

	/**
	 * Add sub-element under the page element with the given pageId.
	 */
	protected function addPageSubItems($pageId, $data) {
		$result = $this->getResult();
		$result->setIndexedTagName($data, $this->getModulePrefix());
		$result->addValue(array ('query', 'pages', intval($pageId)),
			$this->getModuleName(),
			$data);
	}

	protected function setContinueEnumParameter($paramName, $paramValue) {

		$paramName = $this->encodeParamName($paramName);
		$msg = array( $paramName => $paramValue );

//		This is an alternative continue format as a part of the URL string
//		ApiResult :: setContent($msg, $paramName . '=' . urlencode($paramValue));

		$this->getResult()->addValue('query-continue', $this->getModuleName(), $msg);
	}

	/**
	 * Get the Query database connection (readonly)
	 */
	protected function getDB() {
		if (is_null($this->mDb))
			$this->mDb = $this->getQuery()->getDB();
		return $this->mDb;
	}

	/**
	 * Selects the query database connection with the given name.
	 * If no such connection has been requested before, it will be created.
	 * Subsequent calls with the same $name will return the same connection
	 * as the first, regardless of $db or $groups new values.
	 */
	public function selectNamedDB($name, $db, $groups) {
		$this->mDb = $this->getQuery()->getNamedDB($name, $db, $groups);
	}

	/**
	 * Get the PageSet object to work on
	 * @return ApiPageSet data
	 */
	protected function getPageSet() {
		return $this->getQuery()->getPageSet();
	}

	/**
	 * This is a very simplistic utility function
	 * to convert a non-namespaced title string to a db key.
	 * It will replace all ' ' with '_'
	 */
	public static function titleToKey($title) {
		return str_replace(' ', '_', $title);
	}

	public static function keyToTitle($key) {
		return str_replace('_', ' ', $key);
	}

	public function getTokenFlag($tokenArr, $action) {
		if ($this->getMain()->getRequest()->getVal('callback') !== null) {
			// Don't do any session-specific data.
			return false;
		}
		if (in_array($action, $tokenArr)) {
			global $wgUser;
			if ($wgUser->isAllowed($action))
				return true;
			else
				$this->dieUsage("Action '$action' is not allowed for the current user", 'permissiondenied');
		}
		return false;
	}

	public static function getBaseVersion() {
		return __CLASS__ . ': $Id$';
	}
}

/**
 * @addtogroup API
 */
abstract class ApiQueryGeneratorBase extends ApiQueryBase {

	private $mIsGenerator;

	public function __construct($query, $moduleName, $paramPrefix = '') {
		parent :: __construct($query, $moduleName, $paramPrefix);
		$this->mIsGenerator = false;
	}

	public function setGeneratorMode() {
		$this->mIsGenerator = true;
	}

	/**
	 * Overrides base class to prepend 'g' to every generator parameter
	 */
	public function encodeParamName($paramName) {
		if ($this->mIsGenerator)
			return 'g' . parent :: encodeParamName($paramName);
		else
			return parent :: encodeParamName($paramName);
	}

	/**
	 * Execute this module as a generator
	 * @param $resultPageSet PageSet: All output should be appended to this object
	 */
	public abstract function executeGenerator($resultPageSet);
}

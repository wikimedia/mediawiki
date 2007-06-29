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

	protected function addTables($value) {
		if (is_array($value))
			$this->tables = array_merge($this->tables, $value);
		else
			$this->tables[] = $value;
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

		$this->addOption('ORDER BY', $field . ($isDirNewer ? '' : ' DESC'));
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

	public static function addTitleInfo(&$arr, $title, $includeRestricted=false, $prefix='') {
		if ($includeRestricted || $title->userCanRead()) {
			$arr[$prefix . 'ns'] = intval($title->getNamespace());
			$arr[$prefix . 'title'] = $title->getPrefixedText();
		}
		if (!$title->userCanRead())
			$arr[$prefix . 'inaccessible'] = "";
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

	protected function setContinueEnumParameter($paramName, $paramValue) {
		$msg = array (
			$this->encodeParamName($paramName
		) => $paramValue);
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


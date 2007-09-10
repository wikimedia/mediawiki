<?php


/*
 * Created on Sep 7, 2006
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2006 Yuri Astrakhan <FirstnameLastname@gmail.com>
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

abstract class ApiQueryBase extends ApiBase {

	private $mQueryModule;
    
	public function __construct($query, $moduleName, $paramPrefix = '') {
		parent :: __construct($query->getMain(), $moduleName, $paramPrefix);
		$this->mQueryModule = $query;
	}

	/**
	 * Override this method to request extra fields from the pageSet
	 * using $this->getPageSet()->requestField('fieldName')
	 */
	public function requestExtraData() {
	}

	/**
	 * Get the main Query module 
	 */
	public function getQuery() {
		return $this->mQueryModule;
	}

	/**
	 * Get the Query database connection (readonly)
	 */
	protected function getDB() {
		return $this->getQuery()->getDB();
	}

	/**
	 * Get the PageSet object to work on
	 * @return ApiPageSet data
	 */
	protected function getPageSet() {
		return $this->mQueryModule->getPageSet();
	}

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

abstract class ApiQueryGeneratorBase extends ApiQueryBase {

	private $mIsGenerator;

	public function __construct($query, $moduleName, $paramPrefix = '') {
		parent :: __construct($query, $moduleName, $paramPrefix);
		$mIsGenerator = false;
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
?>
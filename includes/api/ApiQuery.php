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
	require_once ("ApiBase.php");
}

class ApiQuery extends ApiBase {

	private $mQueryPropModules = array (
		'content' => 'ApiQueryContent'
	);
	private $mQueryListModules = array (
		'backlinks' => 'ApiQueryBacklinks'
	);

	private $mSlaveDB = null;

	/**
	* Constructor
	*/
	public function __construct($main, $action) {
		parent :: __construct($main);
		$this->mPropModuleNames = array_keys($this->mQueryPropModules);
		$this->mListModuleNames = array_keys($this->mQueryListModules);
	}

	public function GetDB() {
		if (!isset ($this->mSlaveDB))
			$this->mSlaveDB = & wfGetDB(DB_SLAVE);
		return $this->mSlaveDB;
	}

	public function Execute() {

	}

	/**
	 * Returns an array of allowed parameters (keys) => default value for that parameter
	 */
	protected function GetAllowedParams() {
		return array (
			'titles' => array (
				GN_ENUM_DFLT => null,
				GN_ENUM_ISMULTI => true
			),
			'pageids' => array (
				GN_ENUM_DFLT => 0,
				GN_ENUM_ISMULTI => true
			),
			'revids' => array (
				GN_ENUM_DFLT => 0,
				GN_ENUM_ISMULTI => true
			),
			'prop' => array (
				GN_ENUM_DFLT => null,
				GN_ENUM_ISMULTI => true,
				GN_ENUM_CHOICES => array_keys($this->mPropModuleNames
			)
		), 'list' => array (
			GN_ENUM_DFLT => null,
			GN_ENUM_ISMULTI => true,
			GN_ENUM_CHOICES => array_keys($this->mListModuleNames
		)));
	}

	/**
	 * Returns the description string for this module
	 */
	protected function GetDescription() {
		return 'Query Module';
	}

	/**
	 * Returns usage examples for this module. Return null if no examples are available.
	 */
	protected function GetExamples() {
		return array (
			'api.php ? action=query & what=content & titles=ArticleA|ArticleB'
		);
	}
}
?>
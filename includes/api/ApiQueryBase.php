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

abstract class ApiQueryBase extends ApiBase {

	private $mQueryModule;
	
	public function __construct($main, $query) {
		parent :: __construct($main);
		$this->mQueryModule = $query;
	}
	
	/**
	 * Get the name of the query being executed by this instance 
	 */
	public function GetQuery() {
		return $this->mQueryModule;
	}
	
	/**
	 * Derived classes return true when they can be used as title generators for other query modules.
	 */
	protected static abstract function GetCanGenerate();
	
	/**
	 * Return true if this instance is being used as a generator.
	 */
	protected function GetIsGenerator() {
		return false;
	}
}
?>
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
	require_once ("ApiQueryBase.php");
}

class ApiQueryContent extends ApiQueryBase {

	/**
	* Constructor
	*/
	public function __construct($main, $action) {
		parent :: __construct($main);
	}

	public function Execute() {
		
	}

	/**
	 * Returns an array of allowed parameters (keys) => default value for that parameter
	 */
	protected function GetAllowedParams() {
		return array (
			'param' => 'default',
			'enumparam' => array (
				GN_ENUM_DFLT => 'default',
				GN_ENUM_ISMULTI => false,
				GN_ENUM_CHOICES => array (
					'a',
					'b'
				)
			)
		);
	}

	/**
	 * Returns the description string for this module
	 */
	protected function GetDescription() {
		return 'module a';
	}

	/**
	 * Returns usage examples for this module. Return null if no examples are available.
	 */
	protected function GetExamples() {
		return array (
			'http://...'
		);
	}
}
?>
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

class ApiQueryRevisions extends ApiQueryBase {

	public function __construct($query, $moduleName, $generator = false) {
		parent :: __construct($query, $moduleName, $generator);
	}

	public function Execute() {

	}

	protected function GetAllowedParams() {
		return array (
			'rvlimit' => 0,
			'rvstartid' => 0,
			'rvendid' => 0,
			'rvstart' => array (
				GN_ENUM_TYPE => 'timestamp'
			),
			'rvend' => array (
				GN_ENUM_TYPE => 'timestamp'
			),
			'rvdir' => array (
				GN_ENUM_DFLT => 'newer',
				GN_ENUM_TYPE => array (
					'newer',
					'older'
				)
			),
			'rvprop' => array (
				GN_ENUM_ISMULTI => true,
				GN_ENUM_TYPE => array (
					'timestamp',
					'user',
					'comment',
					'content'
				)
			)
		);
	}

	protected function GetDescription() {
		return 'module a';
	}

	protected function GetExamples() {
		return array (
			'http://...'
		);
	}
}
?>
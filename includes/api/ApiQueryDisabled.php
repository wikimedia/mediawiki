<?php

/*
 * Created on Sep 25, 2008
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2008 Roan Kattouw <Firstname>.<Lastname>@home.nl
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


/**
 * API module that does nothing 
 *
 * Use this to disable core modules with e.g.
 * $wgAPIPropModules['modulename'] = 'ApiQueryDisabled';
 *
 * To disable top-level modules, use ApiDisabled instead
 *
 * @ingroup API
 */
class ApiQueryDisabled extends ApiQueryBase {

	public function __construct($main, $action) {
		parent :: __construct($main, $action);
	}

	public function execute() {
		$this->setWarning("The ``{$this->getModuleName()}'' module has been disabled.");
	}

	public function getAllowedParams() {
		return array ();
	}

	public function getParamDescription() {
		return array ();
	}

	public function getDescription() {
		return array(
			'This module has been disabled.'
		);
	}

	protected function getExamples() {
		return array ();
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}

<?php
/**
 * Copyright © 2008 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * API module that dies with an error immediately.
 *
 * Use this to disable core modules with
 * $wgAPIModules['modulename'] = 'ApiDisabled';
 *
 * To disable submodules of action=query, use ApiQueryDisabled instead
 *
 * @ingroup API
 */
class ApiDisabled extends ApiBase {

	public function execute() {
		$this->dieWithError( [ 'apierror-moduledisabled', $this->getModuleName() ] );
	}

	public function isReadMode() {
		return false;
	}

	protected function getDescriptionMessage() {
		return 'apihelp-disabled-summary';
	}

	protected function getSummaryMessage() {
		return 'apihelp-disabled-summary';
	}

	protected function getExtendedDescription() {
		return 'apihelp-disabled-extended-description';
	}
}

<?php
/**
 *
 *
 * Created on Jan 4, 2008
 *
 * Copyright © 2008 Yuri Astrakhan "<Firstname><Lastname>@gmail.com",
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
 * API module to allow users to log out of the wiki. API equivalent of
 * Special:Userlogout.
 *
 * @ingroup API
 */
class ApiLogout extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		$user = $this->getUser();
		$oldName = $user->getName();
		$user->logout();

		// Give extensions to do something after user logout
		$injected_html = '';
		wfRunHooks( 'UserLogoutComplete', array( &$user, &$injected_html, $oldName ) );
	}

	public function isReadMode() {
		return false;
	}

	public function getAllowedParams() {
		return array();
	}

	public function getResultProperties() {
		return array();
	}

	public function getParamDescription() {
		return array();
	}

	public function getDescription() {
		return 'Log out and clear session data';
	}

	public function getExamples() {
		return array(
			'api.php?action=logout' => 'Log the current user out',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Logout';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}

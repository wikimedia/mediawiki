<?php
/**
 * Copyright © 2016 Brad Jorsch <bjorsch@wikimedia.org>
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

use MediaWiki\Auth\AuthManager;

/**
 * Reset password, with AuthManager
 *
 * @ingroup API
 */
class ApiResetPassword extends ApiBase {

	public function execute() {
		$this->dieUsage( 'Implement me', 'notimplemented' );
	}

	public function isWriteMode() {
		return true;
	}

	public function needsToken() {
		return 'csrf';
	}

	public function getAllowedParams() {
		// XXX: See $wgPasswordResetRoutes, or the equivalent from whatever
		// controller we make for this.
		return array(
			'user' => array(
				ApiBase::PARAM_TYPE => 'user',
			),
			'email' => array(
				ApiBase::PARAM_TYPE => 'string',
			),
			'capture' => false,
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=resetpassword&user=Example&token=123ABC'
				=> 'apihelp-resetpassword-example-user',
			'action=resetpassword&user=user@example.com&token=123ABC'
				=> 'apihelp-resetpassword-example-email',
			'action=resetpassword&user=Example&capture=1&token=123ABC'
				=> 'apihelp-resetpassword-example-user-capture',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Manage_authentication_data';
	}
}

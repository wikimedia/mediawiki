<?php
/**
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

use MediaWiki\Session\BotPasswordSessionProvider;

/**
 * API module to allow users to log out of the wiki. API equivalent of
 * Special:Userlogout.
 *
 * @ingroup API
 */
class ApiLogout extends ApiBase {

	public function execute() {
		$session = MediaWiki\Session\SessionManager::getGlobalSession();

		// Handle bot password logout specially
		if ( $session->getProvider() instanceof BotPasswordSessionProvider ) {
			$session->unpersist();
			return;
		}

		// Make sure it's possible to log out
		if ( !$session->canSetUser() ) {
			$this->dieWithError(
				[
					'cannotlogoutnow-text',
					$session->getProvider()->describe( $this->getErrorFormatter()->getLanguage() )
				],
				'cannotlogout'
			);
		}

		$user = $this->getUser();
		$oldName = $user->getName();
		$user->logout();

		// Give extensions to do something after user logout
		$injected_html = '';
		Hooks::run( 'UserLogoutComplete', [ &$user, &$injected_html, $oldName ] );
	}

	public function mustBePosted() {
		return true;
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getWebUITokenSalt( array $params ) {
		return 'logoutToken';
	}

	public function isReadMode() {
		return false;
	}

	protected function getExamplesMessages() {
		return [
			'action=logout&token=123ABC'
				=> 'apihelp-logout-example-logout',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Logout';
	}
}

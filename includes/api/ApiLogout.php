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

namespace MediaWiki\Api;

use MediaWiki\Session\BotPasswordSessionProvider;
use MediaWiki\Session\SessionManager;

/**
 * API module to allow users to log out of the wiki. API equivalent of
 * Special:Userlogout.
 *
 * @ingroup API
 */
class ApiLogout extends ApiBase {

	public function execute() {
		$session = SessionManager::getGlobalSession();

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

		if ( $user->isAnon() ) {
			// Cannot logout a anon user, so add a warning and return early.
			$this->addWarning( 'apierror-mustbeloggedin-generic', 'notloggedin' );
			return;
		}

		$oldName = $user->getName();
		$user->logout();

		// Give extensions to do something after user logout
		$injected_html = '';
		$this->getHookRunner()->onUserLogoutComplete( $user, $injected_html, $oldName );
	}

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'csrf';
	}

	/** @inheritDoc */
	public function isWriteMode() {
		// While core is optimized by default to not require DB writes on log out,
		// these are authenticated POST requests and extensions (eg. CheckUser) are
		// allowed to perform DB writes here without warnings.
		return true;
	}

	/** @inheritDoc */
	protected function getWebUITokenSalt( array $params ) {
		return 'logoutToken';
	}

	/** @inheritDoc */
	public function isReadMode() {
		return false;
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=logout&token=123ABC'
				=> 'apihelp-logout-example-logout',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Logout';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiLogout::class, 'ApiLogout' );

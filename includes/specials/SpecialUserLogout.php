<?php
/**
 * Implements Special:Userlogout
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
 * @ingroup SpecialPage
 */

/**
 * Implements Special:Userlogout
 *
 * @ingroup SpecialPage
 */
class SpecialUserLogout extends UnlistedSpecialPage {
	function __construct() {
		parent::__construct( 'Userlogout' );
	}

	public function doesWrites() {
		return true;
	}

	function execute( $par ) {
		/**
		 * Some satellite ISPs use broken precaching schemes that log people out straight after
		 * they're logged in (T19790). Luckily, there's a way to detect such requests.
		 */
		if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], '&amp;' ) !== false ) {
			wfDebug( "Special:UserLogout request {$_SERVER['REQUEST_URI']} looks suspicious, denying.\n" );
			throw new HttpError( 400, $this->msg( 'suspicious-userlogout' ), $this->msg( 'loginerror' ) );
		}

		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$user = $this->getUser();
		$request = $this->getRequest();

		$logoutToken = $request->getVal( 'logoutToken' );
		$urlParams = [
			'logoutToken' => $user->getEditToken( 'logoutToken', $request )
		] + $request->getValues();
		unset( $urlParams['title'] );
		$continueLink = $this->getFullTitle()->getFullUrl( $urlParams );

		if ( $logoutToken === null ) {
			$this->getOutput()->addWikiMsg( 'userlogout-continue', $continueLink );
			return;
		}
		if ( !$this->getUser()->matchEditToken(
			$logoutToken, 'logoutToken', $this->getRequest(), 24 * 60 * 60
		) ) {
			$this->getOutput()->addWikiMsg( 'userlogout-sessionerror', $continueLink );
			return;
		}

		// Make sure it's possible to log out
		$session = MediaWiki\Session\SessionManager::getGlobalSession();
		if ( !$session->canSetUser() ) {
			throw new ErrorPageError(
				'cannotlogoutnow-title',
				'cannotlogoutnow-text',
				[
					$session->getProvider()->describe( RequestContext::getMain()->getLanguage() )
				]
			);
		}

		$user = $this->getUser();
		$oldName = $user->getName();

		$user->logout();

		$loginURL = SpecialPage::getTitleFor( 'Userlogin' )->getFullURL(
			$this->getRequest()->getValues( 'returnto', 'returntoquery' ) );

		$out = $this->getOutput();
		$out->addWikiMsg( 'logouttext', $loginURL );

		// Hook.
		$injected_html = '';
		Hooks::run( 'UserLogoutComplete', [ &$user, &$injected_html, $oldName ] );
		$out->addHTML( $injected_html );

		$out->returnToMain();
	}

	protected function getGroupName() {
		return 'login';
	}
}

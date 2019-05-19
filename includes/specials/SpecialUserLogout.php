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
class SpecialUserLogout extends FormSpecialPage {
	function __construct() {
		parent::__construct( 'Userlogout' );
	}

	public function doesWrites() {
		return true;
	}

	public function isListed() {
		return false;
	}

	protected function getGroupName() {
		return 'login';
	}

	protected function getFormFields() {
		return [];
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

	public function execute( $par ) {
		if ( $this->getUser()->isAnon() ) {
			$this->setHeaders();
			$this->showSuccess();
			return;
		}

		parent::execute( $par );
	}

	public function alterForm( HTMLForm $form ) {
		$form->setTokenSalt( 'logoutToken' );
		$form->addHeaderText( $this->msg( 'userlogout-continue' ) );

		$form->addHiddenFields( $this->getRequest()->getValues( 'returnto', 'returntoquery' ) );
	}

	/**
	 * Process the form.  At this point we know that the user passes all the criteria in
	 * userCanExecute(), and if the data array contains 'Username', etc, then Username
	 * resets are allowed.
	 * @param array $data
	 * @throws MWException
	 * @throws ThrottledError|PermissionsError
	 * @return Status
	 */
	public function onSubmit( array $data ) {
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

		$user->logout();
		return new Status();
	}

	public function onSuccess() {
		$this->showSuccess();

		$user = $this->getUser();
		$oldName = $user->getName();
		$out = $this->getOutput();
		// Hook.
		$injected_html = '';
		Hooks::run( 'UserLogoutComplete', [ &$user, &$injected_html, $oldName ] );
		$out->addHTML( $injected_html );
	}

	private function showSuccess() {
		$loginURL = SpecialPage::getTitleFor( 'Userlogin' )->getFullURL(
			$this->getRequest()->getValues( 'returnto', 'returntoquery' ) );

		$out = $this->getOutput();
		$out->addWikiMsg( 'logouttext', $loginURL );

		$out->returnToMain();
	}

	/**
	 * Let blocked users to log out and come back with their sockpuppets
	 */
	public function requiresUnblock() {
		return false;
	}
}

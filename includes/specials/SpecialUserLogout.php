<?php
/**
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

namespace MediaWiki\Specials;

use MediaWiki\Exception\ErrorPageError;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Session\SessionManager;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\User\TempUser\TempUserConfig;

/**
 * Implements Special:Userlogout
 *
 * @ingroup SpecialPage
 * @ingroup Auth
 */
class SpecialUserLogout extends FormSpecialPage {
	/**
	 * @var string|null
	 */
	private $oldUserName;

	private TempUserConfig $tempUserConfig;

	public function __construct( TempUserConfig $tempUserConfig ) {
		parent::__construct( 'Userlogout' );
		$this->tempUserConfig = $tempUserConfig;
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	/** @inheritDoc */
	public function isListed() {
		return $this->getAuthManager()->canAuthenticateNow();
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'login';
	}

	/** @inheritDoc */
	protected function getFormFields() {
		return [];
	}

	/** @inheritDoc */
	protected function getDisplayFormat() {
		return 'ooui';
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$user = $this->getUser();
		if ( $user->isAnon() ) {
			$this->setHeaders();
			$this->showSuccess();
			return;
		}
		$this->oldUserName = $user->getName();

		parent::execute( $par );
	}

	public function alterForm( HTMLForm $form ) {
		$form->setTokenSalt( 'logoutToken' );
		$form->addHeaderHtml( $this->msg(
			$this->getUser()->isTemp() ? 'userlogout-temp' : 'userlogout-continue'
		) );

		$form->addHiddenFields( $this->getRequest()->getValues( 'returnto', 'returntoquery' ) );
	}

	/**
	 * Process the form.  At this point we know that the user passes all the criteria in
	 * userCanExecute(), and if the data array contains 'Username', etc, then Username
	 * resets are allowed.
	 * @param array $data
	 * @return Status
	 */
	public function onSubmit( array $data ) {
		// Make sure it's possible to log out
		$session = SessionManager::getGlobalSession();
		if ( !$session->canSetUser() ) {
			throw new ErrorPageError(
				'cannotlogoutnow-title',
				'cannotlogoutnow-text',
				[
					$session->getProvider()->describe( $this->getLanguage() )
				]
			);
		}

		$user = $this->getUser();

		$user->logout();
		return new Status();
	}

	public function onSuccess() {
		$this->showSuccess();

		$out = $this->getOutput();
		// Hook.
		$injected_html = '';
		$this->getHookRunner()->onUserLogoutComplete( $this->getUser(), $injected_html, $this->oldUserName );
		$out->addHTML( $injected_html );
	}

	private function showSuccess() {
		$loginURL = SpecialPage::getTitleFor( 'Userlogin' )->getFullURL(
			$this->getRequest()->getValues( 'returnto', 'returntoquery' ) );

		$out = $this->getOutput();

		$messageKey = 'logouttext';
		if (
			( $this->oldUserName !== null && $this->tempUserConfig->isTempName( $this->oldUserName ) ) ||
			$this->getRequest()->getCheck( 'wasTempUser' )
		) {
			// Generates the message key logouttext-for-temporary-account which is used to customise the success
			// message for a temporary account.
			$messageKey .= '-for-temporary-account';
		}
		$out->addWikiMsg( $messageKey, $loginURL );

		$out->returnToMain();
	}

	/**
	 * Let blocked users to log out and come back with their sockpuppets
	 * @return bool
	 */
	public function requiresUnblock() {
		return false;
	}

	/** @inheritDoc */
	public function getDescription() {
		// Set the page title as "templogout" if the user is (or just was) logged in to a temporary account
		if (
			$this->getUser()->isTemp() ||
			( $this->oldUserName !== null && $this->tempUserConfig->isTempName( $this->oldUserName ) ) ||
			$this->getRequest()->getCheck( 'wasTempUser' )
		) {
			return $this->msg( 'templogout' );
		}
		return parent::getDescription();
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUserLogout::class, 'SpecialUserLogout' );

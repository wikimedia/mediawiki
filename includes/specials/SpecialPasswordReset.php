<?php
/**
 * Implements Special:PasswordReset
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

use MediaWiki\Auth\AuthManager;

/**
 * Special page for requesting a password reset email.
 *
 * Requires the TemporaryPasswordPrimaryAuthenticationProvider and the
 * EmailNotificationSecondaryAuthenticationProvider (or something providing equivalent
 * functionality) to be enabled.
 *
 * @ingroup SpecialPage
 */
class SpecialPasswordReset extends FormSpecialPage {
	/** @var PasswordReset */
	private $passwordReset;

	/**
	 * @var string[] Temporary storage for the passwords which have been sent out, keyed by username.
	 */
	private $passwords = [];

	/**
	 * @var Status
	 */
	private $result;

	/**
	 * @var string $method Identifies which password reset field was specified by the user.
	 */
	private $method;

	public function __construct() {
		parent::__construct( 'PasswordReset', 'editmyprivateinfo' );
		$this->passwordReset = new PasswordReset( $this->getConfig(), AuthManager::singleton() );
	}

	public function doesWrites() {
		return true;
	}

	public function userCanExecute( User $user ) {
		return $this->passwordReset->isAllowed( $user )->isGood();
	}

	public function checkExecutePermissions( User $user ) {
		$status = Status::wrap( $this->passwordReset->isAllowed( $user ) );
		if ( !$status->isGood() ) {
			throw new ErrorPageError( 'internalerror', $status->getMessage() );
		}

		parent::checkExecutePermissions( $user );
	}

	protected function getFormFields() {
		$resetRoutes = $this->getConfig()->get( 'PasswordResetRoutes' );
		$a = [];
		if ( isset( $resetRoutes['username'] ) && $resetRoutes['username'] ) {
			$a['Username'] = [
				'type' => 'text',
				'label-message' => 'passwordreset-username',
			];

			if ( $this->getUser()->isLoggedIn() ) {
				$a['Username']['default'] = $this->getUser()->getName();
			}
		}

		if ( isset( $resetRoutes['email'] ) && $resetRoutes['email'] ) {
			$a['Email'] = [
				'type' => 'email',
				'label-message' => 'passwordreset-email',
			];
		}

		if ( $this->getUser()->isAllowed( 'passwordreset' ) ) {
			$a['Capture'] = [
				'type' => 'check',
				'label-message' => 'passwordreset-capture',
				'help-message' => 'passwordreset-capture-help',
			];
		}

		return $a;
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

	public function alterForm( HTMLForm $form ) {
		$resetRoutes = $this->getConfig()->get( 'PasswordResetRoutes' );

		$form->addHiddenFields( $this->getRequest()->getValues( 'returnto', 'returntoquery' ) );

		$i = 0;
		if ( isset( $resetRoutes['username'] ) && $resetRoutes['username'] ) {
			$i++;
		}
		if ( isset( $resetRoutes['email'] ) && $resetRoutes['email'] ) {
			$i++;
		}

		$message = ( $i > 1 ) ? 'passwordreset-text-many' : 'passwordreset-text-one';

		$form->setHeaderText( $this->msg( $message, $i )->parseAsBlock() );
		$form->setSubmitTextMsg( 'mailmypassword' );
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
		if ( isset( $data['Capture'] ) && !$this->getUser()->isAllowed( 'passwordreset' ) ) {
			// The user knows they don't have the passwordreset permission,
			// but they tried to spoof the form. That's naughty
			throw new PermissionsError( 'passwordreset' );
		}

		$username = isset( $data['Username'] ) ? $data['Username'] : null;
		$email = isset( $data['Email'] ) ? $data['Email'] : null;
		$capture = !empty( $data['Capture'] );

		$this->method = $username ? 'username' : 'email';
		$this->result = Status::wrap(
			$this->passwordReset->execute( $this->getUser(), $username, $email, $capture ) );
		if ( $capture && $this->result->isOK() ) {
			$this->passwords = $this->result->getValue();
		}

		if ( $this->result->hasMessage( 'actionthrottledtext' ) ) {
			throw new ThrottledError;
		}

		return $this->result;
	}

	public function onSuccess() {
		if ( $this->getUser()->isAllowed( 'passwordreset' ) && $this->passwords ) {
			// @todo Logging

			if ( $this->result->isGood() ) {
				$this->getOutput()->addWikiMsg( 'passwordreset-emailsent-capture2',
					count( $this->passwords ) );
			} else {
				$this->getOutput()->addWikiMsg( 'passwordreset-emailerror-capture2',
					$this->result->getMessage(), key( $this->passwords ), count( $this->passwords ) );
			}

			$this->getOutput()->addHTML( Html::openElement( 'ul' ) );
			foreach ( $this->passwords as $username => $pwd ) {
				$this->getOutput()->addHTML( Html::rawElement( 'li', [],
					htmlspecialchars( $username, ENT_QUOTES )
					. $this->msg( 'colon-separator' )->text()
					. htmlspecialchars( $pwd, ENT_QUOTES )
				) );
			}
			$this->getOutput()->addHTML( Html::closeElement( 'ul' ) );
		}

		if ( $this->method === 'email' ) {
			$this->getOutput()->addWikiMsg( 'passwordreset-emailsentemail' );
		} else {
			$this->getOutput()->addWikiMsg( 'passwordreset-emailsentusername' );
		}

		$this->getOutput()->returnToMain();
	}

	/**
	 * Hide the password reset page if resets are disabled.
	 * @return bool
	 */
	public function isListed() {
		if ( $this->passwordReset->isAllowed( $this->getUser() )->isGood() ) {
			return parent::isListed();
		}

		return false;
	}

	protected function getGroupName() {
		return 'users';
	}
}

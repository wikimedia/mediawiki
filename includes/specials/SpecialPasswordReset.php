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
	private $passwordReset = null;

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
	}

	private function getPasswordReset() {
		if ( $this->passwordReset === null ) {
			$this->passwordReset = new PasswordReset( $this->getConfig(), AuthManager::singleton() );
		}
		return $this->passwordReset;
	}

	public function doesWrites() {
		return true;
	}

	public function userCanExecute( User $user ) {
		return $this->getPasswordReset()->isAllowed( $user )->isGood();
	}

	public function checkExecutePermissions( User $user ) {
		$status = Status::wrap( $this->getPasswordReset()->isAllowed( $user ) );
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
				'type' => 'user',
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

		return $a;
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

	public function alterForm( HTMLForm $form ) {
		$resetRoutes = $this->getConfig()->get( 'PasswordResetRoutes' );

		$form->setSubmitDestructive();

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
		$username = $data['Username'] ?? null;
		$email = $data['Email'] ?? null;

		$this->method = $username ? 'username' : 'email';
		$this->result = Status::wrap(
			$this->getPasswordReset()->execute( $this->getUser(), $username, $email ) );

		if ( $this->result->hasMessage( 'actionthrottledtext' ) ) {
			throw new ThrottledError;
		}

		return $this->result;
	}

	public function onSuccess() {
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
		if ( $this->getPasswordReset()->isAllowed( $this->getUser() )->isGood() ) {
			return parent::isListed();
		}

		return false;
	}

	protected function getGroupName() {
		return 'users';
	}
}

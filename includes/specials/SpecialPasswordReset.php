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

use ErrorPageError;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\User\PasswordReset;
use MediaWiki\User\User;
use ThrottledError;

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
	private PasswordReset $passwordReset;

	/**
	 * @param PasswordReset $passwordReset
	 */
	public function __construct( PasswordReset $passwordReset ) {
		parent::__construct( 'PasswordReset', 'editmyprivateinfo' );

		$this->passwordReset = $passwordReset;
	}

	public function doesWrites() {
		return true;
	}

	/** @inheritDoc */
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

	/**
	 * @param string|null $par
	 */
	public function execute( $par ) {
		$out = $this->getOutput();
		$out->disallowUserJs();
		parent::execute( $par );
	}

	/** @inheritDoc */
	protected function getFormFields() {
		$resetRoutes = $this->getConfig()->get( MainConfigNames::PasswordResetRoutes );
		$a = [];
		if ( isset( $resetRoutes['username'] ) && $resetRoutes['username'] ) {
			$a['Username'] = [
				'type' => 'user',
				'default' => $this->getRequest()->getSession()->suggestLoginUsername(),
				'label-message' => 'passwordreset-username',
				'excludetemp' => true,
			];

			if ( $this->getUser()->isRegistered() ) {
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

	/** @inheritDoc */
	protected function getDisplayFormat() {
		return 'ooui';
	}

	public function alterForm( HTMLForm $form ) {
		$resetRoutes = $this->getConfig()->get( MainConfigNames::PasswordResetRoutes );

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

		$form->setHeaderHtml( $this->msg( $message, $i )->parseAsBlock() );
		$form->setSubmitTextMsg( 'mailmypassword' );
	}

	/**
	 * Process the form.
	 * At this point, we know that the user passes all the criteria in
	 * userCanExecute(), and if the data array contains 'Username', etc., then Username
	 * resets are allowed.
	 * @param array $data
	 * @return Status
	 */
	public function onSubmit( array $data ) {
		$username = $data['Username'] ?? null;
		$email = $data['Email'] ?? null;

		$result = Status::wrap(
			$this->passwordReset->execute( $this->getUser(), $username, $email ) );

		if ( $result->hasMessage( 'actionthrottledtext' ) ) {
			throw new ThrottledError;
		}

		// Show a message on the successful processing of the form.
		// This doesn't necessarily mean a reset email was sent.
		if ( $result->isGood() ) {
			$output = $this->getOutput();

			// Information messages.
			$output->addWikiMsg( 'passwordreset-success' );
			$output->addWikiMsg( 'passwordreset-success-details-generic',
				$this->getConfig()->get( MainConfigNames::PasswordReminderResendTime ) );

			// Confirmation of what the user has just submitted.
			$info = "\n";
			if ( $username ) {
				$info .= "* " . $this->msg( 'passwordreset-username' ) . ' '
					. wfEscapeWikiText( $username ) . "\n";
			}
			if ( $email ) {
				$info .= "* " . $this->msg( 'passwordreset-email' ) . ' '
					. wfEscapeWikiText( $email ) . "\n";
			}
			$output->addWikiMsg( 'passwordreset-success-info', $info );

			// Add a return to link to the main page.
			$output->returnToMain();
		}

		return $result;
	}

	/**
	 * Hide the password reset page if resets are disabled.
	 * @return bool
	 */
	public function isListed() {
		if ( !$this->passwordReset->isEnabled()->isGood() ) {
			return false;
		}

		return parent::isListed();
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'login';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialPasswordReset::class, 'SpecialPasswordReset' );

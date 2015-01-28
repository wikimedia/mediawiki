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

/**
 * Special page for requesting a password reset email
 *
 * @ingroup SpecialPage
 */
class SpecialPasswordReset extends FormSpecialPage {
	/**
	 * @var Message
	 */
	private $email;

	/**
	 * @var User
	 */
	private $firstUser;

	/**
	 * @var Status
	 */
	private $result;

	public function __construct() {
		parent::__construct( 'PasswordReset', 'editmyprivateinfo' );
	}

	public function userCanExecute( User $user ) {
		return $this->canChangePassword( $user )->isGood() && parent::userCanExecute( $user );
	}

	public function checkExecutePermissions( User $user ) {
		$status = $this->canChangePassword( $user );
		if ( !$status->isGood() ) {
			throw new ErrorPageError( 'internalerror', $status->getMessage() );
		}

		return parent::checkExecutePermissions( $user );
	}

	protected function getFormFields() {
		global $wgAuth;
		$resetRoutes = $this->getConfig()->get( 'PasswordResetRoutes' );
		$a = array();
		if ( isset( $resetRoutes['username'] ) && $resetRoutes['username'] ) {
			$a['Username'] = array(
				'type' => 'text',
				'label-message' => 'passwordreset-username',
			);

			if ( $this->getUser()->isLoggedIn() ) {
				$a['Username']['default'] = $this->getUser()->getName();
			}
		}

		if ( isset( $resetRoutes['email'] ) && $resetRoutes['email'] ) {
			$a['Email'] = array(
				'type' => 'email',
				'label-message' => 'passwordreset-email',
			);
		}

		if ( isset( $resetRoutes['domain'] ) && $resetRoutes['domain'] ) {
			$domains = $wgAuth->domainList();
			$a['Domain'] = array(
				'type' => 'select',
				'options' => $domains,
				'label-message' => 'passwordreset-domain',
			);
		}

		if ( $this->getUser()->isAllowed( 'passwordreset' ) ) {
			$a['Capture'] = array(
				'type' => 'check',
				'label-message' => 'passwordreset-capture',
				'help-message' => 'passwordreset-capture-help',
			);
		}

		return $a;
	}

	protected function getDisplayFormat() {
		return 'vform';
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
		if ( isset( $resetRoutes['domain'] ) && $resetRoutes['domain'] ) {
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
	 * @param array $formData
	 * @throws MWException
	 * @throws ThrottledError|PermissionsError
	 * @return bool|array
	 */
	public function onSubmit( array $formData ) {
		$data = array();
		foreach ( array(
			'Domain' => 'domain',
			'Username' => 'username',
			'Email' => 'email',
		) as $formKey => $dataKey ) {
			if ( isset( $formData[$formKey] ) ) {
				$data[$dataKey] = $formData[$formKey];
			}
		}

		if ( isset( $data['Capture'] ) && !$this->getUser()->isAllowed( 'passwordreset' ) ) {
			throw new PermissionsError( 'passwordreset' );
		}
		$capture = !empty( $formData['Capture'] );

		$status = UserManager::resetPassword( $this->getContext(), $data, $capture );

		if ( $status instanceof Status ) {
			if ( !empty( $status->throttled ) ) {
				throw new ThrottledError;
			}

			$this->result = $status->value->mailresult ?: $status;
			$this->firstUser = $status->value->users ? $status->value->users[0] : null;
			$this->email = $status->value->email;

			// If we're capturing and the email got created, that's good enough
			// to be considered "good" (onSuccess will display things correctly)
			if ( $capture && $this->email ) {
				$status = Status::newGood();
			}
		}

		return $status;
	}

	public function onSuccess() {
		if ( $this->getUser()->isAllowed( 'passwordreset' ) && $this->email != null ) {
			// @todo Logging

			if ( $this->result->isGood() ) {
				$this->getOutput()->addWikiMsg( 'passwordreset-emailsent-capture' );
			} else {
				$this->getOutput()->addWikiMsg( 'passwordreset-emailerror-capture',
					$this->result->getMessage(), $this->firstUser->getName() );
			}

			$this->getOutput()->addHTML( Html::rawElement( 'pre', array(), $this->email->escaped() ) );
		}

		$this->getOutput()->addWikiMsg( 'passwordreset-emailsent' );
		$this->getOutput()->returnToMain();
	}

	protected function canChangePassword( User $user ) {
		$context = new DerivativeContext( $this->getContext() );
		$context->setUser( $user );
		return UserManager::canResetPassword( $context );
	}

	/**
	 * Hide the password reset page if resets are disabled.
	 * @return bool
	 */
	function isListed() {
		if ( $this->canChangePassword( $this->getUser() )->isGood() ) {
			return parent::isListed();
		}

		return false;
	}

	protected function getGroupName() {
		return 'users';
	}
}

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
		return $this->canChangePassword( $user ) === true && parent::userCanExecute( $user );
	}

	public function checkExecutePermissions( User $user ) {
		$error = $this->canChangePassword( $user );
		if ( is_string( $error ) ) {
			throw new ErrorPageError( 'internalerror', $error );
		} elseif ( !$error ) {
			throw new ErrorPageError( 'internalerror', 'resetpass_forbidden' );
		}

		return parent::checkExecutePermissions( $user );
	}

	protected function getFormFields() {
		global $wgPasswordResetRoutes, $wgAuth;
		$a = array();
		if ( isset( $wgPasswordResetRoutes['username'] ) && $wgPasswordResetRoutes['username'] ) {
			$a['Username'] = array(
				'type' => 'text',
				'label-message' => 'passwordreset-username',
			);

			if ( $this->getUser()->isLoggedIn() ) {
				$a['Username']['default'] = $this->getUser()->getName();
			}
		}

		if ( isset( $wgPasswordResetRoutes['email'] ) && $wgPasswordResetRoutes['email'] ) {
			$a['Email'] = array(
				'type' => 'email',
				'label-message' => 'passwordreset-email',
			);
		}

		if ( isset( $wgPasswordResetRoutes['domain'] ) && $wgPasswordResetRoutes['domain'] ) {
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

	public function alterForm( HTMLForm $form ) {
		global $wgPasswordResetRoutes;

		$form->setDisplayFormat( 'vform' );
		// Turn the old-school line around the form off.
		// XXX This wouldn't be necessary here if we could set the format of
		// the HTMLForm to 'vform' at its creation, but there's no way to do so
		// from a FormSpecialPage class.
		$form->setWrapperLegend( false );

		$form->addHiddenFields( $this->getRequest()->getValues( 'returnto', 'returntoquery' ) );

		$i = 0;
		if ( isset( $wgPasswordResetRoutes['username'] ) && $wgPasswordResetRoutes['username'] ) {
			$i++;
		}
		if ( isset( $wgPasswordResetRoutes['email'] ) && $wgPasswordResetRoutes['email'] ) {
			$i++;
		}
		if ( isset( $wgPasswordResetRoutes['domain'] ) && $wgPasswordResetRoutes['domain'] ) {
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
	 * @param $data array
	 * @throws MWException
	 * @throws ThrottledError|PermissionsError
	 * @return Bool|Array
	 */
	public function onSubmit( array $data ) {
		global $wgAuth;

		if ( isset( $data['Domain'] ) ) {
			if ( $wgAuth->validDomain( $data['Domain'] ) ) {
				$wgAuth->setDomain( $data['Domain'] );
			} else {
				$wgAuth->setDomain( 'invaliddomain' );
			}
		}

		if ( isset( $data['Capture'] ) && !$this->getUser()->isAllowed( 'passwordreset' ) ) {
			// The user knows they don't have the passwordreset permission,
			// but they tried to spoof the form. That's naughty
			throw new PermissionsError( 'passwordreset' );
		}

		/**
		 * @var $firstUser User
		 * @var $users User[]
		 */

		if ( isset( $data['Username'] ) && $data['Username'] !== '' ) {
			$method = 'username';
			$users = array( User::newFromName( $data['Username'] ) );
		} elseif ( isset( $data['Email'] )
			&& $data['Email'] !== ''
			&& Sanitizer::validateEmail( $data['Email'] )
		) {
			$method = 'email';
			$res = wfGetDB( DB_SLAVE )->select(
				'user',
				User::selectFields(),
				array( 'user_email' => $data['Email'] ),
				__METHOD__
			);

			if ( $res ) {
				$users = array();

				foreach ( $res as $row ) {
					$users[] = User::newFromRow( $row );
				}
			} else {
				// Some sort of database error, probably unreachable
				throw new MWException( 'Unknown database error in ' . __METHOD__ );
			}
		} else {
			// The user didn't supply any data
			return false;
		}

		// Check for hooks (captcha etc), and allow them to modify the users list
		$error = array();
		if ( !wfRunHooks( 'SpecialPasswordResetOnSubmit', array( &$users, $data, &$error ) ) ) {
			return array( $error );
		}

		if ( count( $users ) == 0 ) {
			if ( $method == 'email' ) {
				// Don't reveal whether or not an email address is in use
				return true;
			} else {
				return array( 'noname' );
			}
		}

		$firstUser = $users[0];

		if ( !$firstUser instanceof User || !$firstUser->getID() ) {
			// Don't parse username as wikitext (bug 65501)
			return array( array( 'nosuchuser', wfEscapeWikiText( $data['Username'] ) ) );
		}

		// Check against the rate limiter
		if ( $this->getUser()->pingLimiter( 'mailpassword' ) ) {
			throw new ThrottledError;
		}

		// Check against password throttle
		foreach ( $users as $user ) {
			if ( $user->isPasswordReminderThrottled() ) {
				global $wgPasswordReminderResendTime;

				# Round the time in hours to 3 d.p., in case someone is specifying
				# minutes or seconds.
				return array( array(
					'throttled-mailpassword',
					round( $wgPasswordReminderResendTime, 3 )
				) );
			}
		}

		global $wgNewPasswordExpiry;

		// All the users will have the same email address
		if ( $firstUser->getEmail() == '' ) {
			// This won't be reachable from the email route, so safe to expose the username
			return array( array( 'noemail', wfEscapeWikiText( $firstUser->getName() ) ) );
		}

		// We need to have a valid IP address for the hook, but per bug 18347, we should
		// send the user's name if they're logged in.
		$ip = $this->getRequest()->getIP();
		if ( !$ip ) {
			return array( 'badipaddress' );
		}
		$caller = $this->getUser();
		wfRunHooks( 'User::mailPasswordInternal', array( &$caller, &$ip, &$firstUser ) );
		$username = $caller->getName();
		$msg = IP::isValid( $username )
			? 'passwordreset-emailtext-ip'
			: 'passwordreset-emailtext-user';

		// Send in the user's language; which should hopefully be the same
		$userLanguage = $firstUser->getOption( 'language' );

		$passwords = array();
		foreach ( $users as $user ) {
			$password = $user->randomPassword();
			$user->setNewpassword( $password );
			$user->saveSettings();
			$passwords[] = $this->msg( 'passwordreset-emailelement', $user->getName(), $password )
				->inLanguage( $userLanguage )->text(); // We'll escape the whole thing later
		}
		$passwordBlock = implode( "\n\n", $passwords );

		$this->email = $this->msg( $msg )->inLanguage( $userLanguage );
		$this->email->params(
			$username,
			$passwordBlock,
			count( $passwords ),
			'<' . Title::newMainPage()->getCanonicalURL() . '>',
			round( $wgNewPasswordExpiry / 86400 )
		);

		$title = $this->msg( 'passwordreset-emailtitle' );

		$this->result = $firstUser->sendMail( $title->text(), $this->email->text() );

		if ( isset( $data['Capture'] ) && $data['Capture'] ) {
			// Save the user, will be used if an error occurs when sending the email
			$this->firstUser = $firstUser;
		} else {
			// Blank the email if the user is not supposed to see it
			$this->email = null;
		}

		if ( $this->result->isGood() ) {
			return true;
		} elseif ( isset( $data['Capture'] ) && $data['Capture'] ) {
			// The email didn't send, but maybe they knew that and that's why they captured it
			return true;
		} else {
			// @todo FIXME: The email wasn't sent, but we have already set
			// the password throttle timestamp, so they won't be able to try
			// again until it expires...  :(
			return array( array( 'mailerror', $this->result->getMessage() ) );
		}
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
		global $wgPasswordResetRoutes, $wgEnableEmail, $wgAuth;

		// Maybe password resets are disabled, or there are no allowable routes
		if ( !is_array( $wgPasswordResetRoutes ) ||
			!in_array( true, array_values( $wgPasswordResetRoutes ) )
		) {
			return 'passwordreset-disabled';
		}

		// Maybe the external auth plugin won't allow local password changes
		if ( !$wgAuth->allowPasswordChange() ) {
			return 'resetpass_forbidden';
		}

		// Maybe email features have been disabled
		if ( !$wgEnableEmail ) {
			return 'passwordreset-emaildisabled';
		}

		// Maybe the user is blocked (check this here rather than relying on the parent
		// method as we have a more specific error message to use here
		if ( $user->isBlocked() ) {
			return 'blocked-mailpassword';
		}

		return true;
	}

	/**
	 * Hide the password reset page if resets are disabled.
	 * @return Bool
	 */
	function isListed() {
		if ( $this->canChangePassword( $this->getUser() ) === true ) {
			return parent::isListed();
		}

		return false;
	}

	protected function getGroupName() {
		return 'users';
	}
}

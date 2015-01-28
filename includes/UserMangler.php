<?php
/**
 * Methods to do things to a User
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
 * @since 1.25
 */

class UserMangler {
	/** @var IContextSource */
	protected $context;

	/** User being worked with
	 * @var User */
	protected $user;

	/**
	 * @param User $user User to work with
	 * @param IContextSource $context
	 */
	public function __construct( User $user, IContextSource $context ) {
		$this->user = $user;
		$this->context = $context;
	}

	/**
	 * Access the context
	 * @return IContextSource
	 */
	public function getContext() {
		return $this->context;
	}

	/**
	 * Access the user being worked with
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * Indicate whether a password can be reset using the given context
	 * @param IContextSource $context
	 * @return Status
	 */
	public static function canResetPassword( IContextSource $context ) {
		global $wgAuth;
		$resetRoutes = $context->getConfig()->get( 'PasswordResetRoutes' );

		// No if password resets are disabled, or there are no allowable routes
		if ( !is_array( $resetRoutes ) ||
			!in_array( true, array_values( $resetRoutes ) )
		) {
			return Status::newFatal( 'passwordreset-disabled' );
		}

		// No if the external auth plugin won't allow local password changes
		if ( !$wgAuth->allowPasswordChange() ) {
			return Status::newFatal( 'resetpass_forbidden' );
		}

		// No if email features have been disabled
		if ( !$context->getConfig()->get( 'EnableEmail' ) ) {
			return Status::newFatal( 'passwordreset-emaildisabled' );
		}

		// Don't allow blocked users to submit password resets, to prevent
		// blocked users from being annoying by flooding people with password
		// reset emails.
		if ( $context->getUser()->isBlocked() ) {
			return Status::newFatal( 'blocked-mailpassword' );
		}

		return Status::newGood();
	}

	/**
	 * Attempt to reset a password
	 *
	 * $data array should contain keys corresponding to true entries in
	 * $wgPasswordResetRoutes.
	 *
	 * Return values are:
	 * - false: $data did not contain non-empty values
	 * - Status, fatal: Reset or mail sending failed
	 * - Status, good: Reset succeeded, or is pretended to be so.
	 *
	 * Status object value is an object with the following data:
	 *    - users: Users reset. May be empty; the client shouldn't be informed of this.
	 *    - email: If $capture (and Users is non-empty), the text of the email sent.
	 *    - mailresult: Status from the mail-send attempt, if any
	 *
	 * @param IContextSource $context
	 * @param array $data
	 * @param bool $capture If true, capture the sent email text. Requires passwordreset right.
	 * @return Status|false
	 * @throws MWException
	 */
	public static function resetPassword( IContextSource $context, array $data, $capture = false ) {
		global $wgAuth;
		$resetRoutes = $context->getConfig()->get( 'PasswordResetRoutes' );

		if ( $capture && !$context->getUser()->isAllowed( 'passwordreset' ) ) {
			return User::newFatalPermissionDeniedStatus( 'passwordreset' );
		}

		if ( !empty( $resetRoutes['domain'] ) && isset( $data['domain'] ) ) {
			if ( $wgAuth->validDomain( $data['domain'] ) ) {
				$wgAuth->setDomain( $data['domain'] );
			} else {
				$wgAuth->setDomain( 'invaliddomain' );
			}
		}

		$status = Status::newGood();
		$status->value =  (object)array(
			'email' => null,
			'users' => array(),
			'mailresult' => null,
		);

		if ( !empty( $resetRoutes['username'] ) &&
			isset( $data['username'] ) && $data['username'] !== ''
		) {
			$method = 'username';
			$status->value->users = array( User::newFromName( $data['username'] ) );
		} elseif ( !empty( $resetRoutes['email'] ) &&
			isset( $data['email'] ) && $data['email'] !== ''
			&& Sanitizer::validateEmail( $data['email'] )
		) {
			$method = 'email';
			$res = wfGetDB( DB_SLAVE )->select(
				'user',
				User::selectFields(),
				array( 'user_email' => $data['email'] ),
				__METHOD__
			);

			if ( $res ) {
				$status->value->users = array();
				foreach ( $res as $row ) {
					$status->value->users[] = User::newFromRow( $row );
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
		// Sigh, backwards compatability.
		$hookData = array();
		foreach ( $data as $k => $v ) {
			$hookData[ucfirst($k)] = $v;
		}
		$hookData['Capture'] = $capture;
		if ( !Hooks::run( 'SpecialPasswordResetOnSubmit', array( &$status->value->users, $hookData, &$error ) ) ) {
			if ( is_array( $error ) ) {
				call_user_func_array( array( $status, 'fatal' ), $error );
			} elseif ( $error === false ) {
				// Uh, ok.
				return false;
			} else {
				// Probably a non-localized string. Sigh.
				$status->fatal( new RawMessage( '$1', array( $error ) ) );
			}
			return $status;
		}

		if ( count( $status->value->users ) == 0 ) {
			// Don't reveal whether or not an email address is in use
			if ( $method !== 'email' ) {
				$status->fatal( 'noname' );
			}
			return $status;
		}

		/** @var $firstUser User */
		$firstUser = $status->value->users[0];

		if ( !$firstUser instanceof User || !$firstUser->getID() ) {
			// Don't parse username as wikitext (bug 65501)
			$status->value->users = array();
			$status->fatal( 'nosuchuser', wfEscapeWikiText( $data['username'] ) );
			return $status;
		}

		// Check against the rate limiter
		if ( $context->getUser()->pingLimiter( 'mailpassword' ) ) {
			$status->throttled = true; // Used by Special:PasswordReset
			$status->fatal( 'actionthrottledtext' );
			return $status;
		}

		// Check against password throttle
		foreach ( $status->value->users as $user ) {
			if ( $user->isPasswordReminderThrottled() ) {
				# Round the time in hours to 3 d.p., in case someone is specifying
				# minutes or seconds.
				$status->fatal(
					'throttled-mailpassword',
					round( $context->getConfig()->get( 'PasswordReminderResendTime' ), 3 )
				);
				return $status;
			}
		}

		// All the users will have the same email address
		if ( $firstUser->getEmail() == '' ) {
			// This won't be reachable from the email route, so safe to expose the username
			$status->fatal( 'noemail', wfEscapeWikiText( $firstUser->getName() ) );
			return $status;
		}

		// We need to have a valid IP address for the hook, but per bug 18347, we should
		// send the user's name if they're logged in.
		$ip = $context->getRequest()->getIP();
		if ( !$ip ) {
			$status->fatal( 'badipaddress' );
			return $status;
		}
		$caller = $context->getUser();
		Hooks::run( 'User::mailPasswordInternal', array( &$caller, &$ip, &$firstUser ) );
		$username = $caller->getName();
		$msg = IP::isValid( $username )
			? 'passwordreset-emailtext-ip'
			: 'passwordreset-emailtext-user';

		// Send in the user's language; which should hopefully be the same
		$userLanguage = $firstUser->getOption( 'language' );

		$passwords = array();
		foreach ( $status->value->users as $user ) {
			$password = $user->randomPassword();
			$user->setNewpassword( $password );
			$user->saveSettings();
			$passwords[] = $context->msg( 'passwordreset-emailelement', $user->getName(), $password )
				->inLanguage( $userLanguage )->text(); // We'll escape the whole thing later
		}
		$passwordBlock = implode( "\n\n", $passwords );

		$email = $context->msg( $msg )->inLanguage( $userLanguage );
		$email->params(
			$username,
			$passwordBlock,
			count( $passwords ),
			'<' . Title::newMainPage()->getCanonicalURL() . '>',
			round( $context->getConfig()->get( 'NewPasswordExpiry' ) / 86400 )
		);

		$title = $context->msg( 'passwordreset-emailtitle' );

		$result = $firstUser->sendMail( $title->text(), $email->text() );
		$status->value->mailresult = $result;

		if ( $capture ) {
			$status->value->email = $email;
		}

		if ( !$result->isGood() ) {
			// @todo FIXME: The email wasn't sent, but we have already set
			// the password throttle timestamp, so they won't be able to try
			// again until it expires...  :(
			$status->fatal( 'mailerror', $result->getMessage() );
		}

		return $status;
	}

	/**
	 * Change the user's password
	 * @param string $oldpass
	 * @param string $newpass
	 * @param string $retype
	 * @return Status
	 */
	public function changePassword( $oldpass, $newpass, $retype ) {
		global $wgAuth;

		if ( !$wgAuth->allowPasswordChange() ) {
			return Status::newFatal( 'resetpass_forbidden' );
		}

		$context = $this->getContext();
		$user = $this->getUser();
		$isSelf = ( $user->getName() === $context->getUser()->getName() );

		if ( !$user || $user->isAnon() ) {
			return Status::newFatal( 'nosuchusershort', $user->getName() );
		}

		if ( $newpass !== $retype ) {
			Hooks::run( 'PrefsPasswordAudit', array( $user, $newpass, 'badretype' ) );
			return Status::newFatal( 'badretype' );
		}

		$throttleCount = LoginForm::incLoginThrottle( $user->getName() );
		if ( $throttleCount === true ) {
			$lang = $context->getLanguage();
			$throttleInfo = $context->getConfig()->get( 'PasswordAttemptThrottle' );
			return Status::newFatal( 'changepassword-throttled',
				$lang->formatDuration( $throttleInfo['seconds'] )
			);
		}

		// @todo Make these separate messages, since the message is written for both cases
		if ( !$user->checkTemporaryPassword( $oldpass ) && !$user->checkPassword( $oldpass ) ) {
			Hooks::run( 'PrefsPasswordAudit', array( $user, $newpass, 'wrongpassword' ) );
			return Status::newFatal( 'resetpass-wrong-oldpass' );
		}

		// User is resetting their password to their old password
		if ( $oldpass === $newpass ) {
			return Status::newFatal( 'resetpass-recycled' );
		}

		// Do AbortChangePassword after checking mOldpass, so we don't leak information
		// by possibly aborting a new password before verifying the old password.
		$abortMsg = 'resetpass-abort-generic';
		if ( !Hooks::run( 'AbortChangePassword', array( $user, $oldpass, $newpass, &$abortMsg ) ) ) {
			Hooks::run( 'PrefsPasswordAudit', array( $user, $newpass, 'abortreset' ) );
			return Status::newFatal( $abortMsg );
		}

		// Please reset throttle for successful logins, thanks!
		if ( $throttleCount ) {
			LoginForm::clearLoginThrottle( $user->getName() );
		}

		try {
			$user->setPassword( $newpass );
			Hooks::run( 'PrefsPasswordAudit', array( $user, $newpass, 'success' ) );
		} catch ( PasswordError $e ) {
			Hooks::run( 'PrefsPasswordAudit', array( $user, $newpass, 'error' ) );
			return Status::newFatal( new RawMessage( '$1', array( $e->getMessage() ) ) );
		}

		if ( $isSelf ) {
			// This is needed to keep the user connected since
			// changing the password also modifies the user's token.
			$remember = $context->getRequest()->getCookie( 'Token' ) !== null;
			$user->setCookies( null, null, $remember );
		}
		$user->resetPasswordExpiration();
		$user->saveSettings();
		return Status::newGood();
	}


	/**
	 * Change the user's email address
	 * @param string $pass
	 * @param string $newaddr
	 * @return Status
	 */
	public function changeEmail( $pass, $newaddr ) {
		global $wgAuth;

		$context = $this->getContext();
		$user = $this->getUser();

		if ( !$user || $user->isAnon() ) {
			return Status::newFatal( 'nosuchusershort', $user->getName() );
		}

		if ( $newaddr != '' && !Sanitizer::validateEmail( $newaddr ) ) {
			return Status::newFatal( 'invalidemailaddress' );
		}

		$throttleCount = LoginForm::incLoginThrottle( $user->getName() );
		if ( $throttleCount === true ) {
			$lang = $context->getLanguage();
			$throttleInfo = $context->getConfig()->get( 'PasswordAttemptThrottle' );
			return Status::newFatal(
				'changeemail-throttled',
				$lang->formatDuration( $throttleInfo['seconds'] )
			);
		}

		if ( $context->getConfig()->get( 'RequirePasswordforEmailChange' )
			&& !$user->checkTemporaryPassword( $pass )
			&& !$user->checkPassword( $pass )
		) {
			return Status::newFatal( 'wrongpassword' );
		}

		if ( $throttleCount ) {
			LoginForm::clearLoginThrottle( $user->getName() );
		}

		$oldaddr = $user->getEmail();
		$status = $user->setEmailWithConfirmation( $newaddr );
		if ( !$status->isGood() ) {
			return $status;
		}

		Hooks::run( 'PrefsEmailAudit', array( $user, $oldaddr, $newaddr ) );

		$user->saveSettings();

		$wgAuth->updateExternalDB( $user );

		return $status;
	}

}

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
 * @ingroup Auth
 */

namespace MediaWiki\Auth;

use User;

/**
 * A primary authentication provider that uses the temporary password field in
 * the 'user' table.
 *
 * A successful login will force a password reset.
 *
 * @note For proper operation, this should generally come before any other
 *  password-based authentication providers.
 * @ingroup Auth
 * @since 1.27
 */
class TemporaryPasswordPrimaryAuthenticationProvider
	extends AbstractPasswordPrimaryAuthenticationProvider
{
	/** @var bool */
	protected $emailEnabled = null;

	/** @var int */
	protected $newPasswordExpiry = null;

	/** @var int */
	protected $passwordReminderResendTime = null;

	/**
	 * @param array $params
	 *  - emailEnabled: (bool) must be true for the option to email passwords to be present
	 *  - newPasswordExpiry: (int) expiraton time of temporary passwords, in seconds
	 *  - passwordReminderResendTime: (int) cooldown period in hours until a password reminder can
	 *    be sent to the same user again,
	 */
	public function __construct( $params = [] ) {
		parent::__construct( $params );

		if ( isset( $params['emailEnabled'] ) ) {
			$this->emailEnabled = (bool)$params['emailEnabled'];
		}
		if ( isset( $params['newPasswordExpiry'] ) ) {
			$this->newPasswordExpiry = (int)$params['newPasswordExpiry'];
		}
		if ( isset( $params['passwordReminderResendTime'] ) ) {
			$this->passwordReminderResendTime = $params['passwordReminderResendTime'];
		}
	}

	public function setConfig( \Config $config ) {
		parent::setConfig( $config );

		if ( $this->emailEnabled === null ) {
			$this->emailEnabled = $this->config->get( 'EnableEmail' );
		}
		if ( $this->newPasswordExpiry === null ) {
			$this->newPasswordExpiry = $this->config->get( 'NewPasswordExpiry' );
		}
		if ( $this->passwordReminderResendTime === null ) {
			$this->passwordReminderResendTime = $this->config->get( 'PasswordReminderResendTime' );
		}
	}

	protected function getPasswordResetData( $username, $data ) {
		// Always reset
		return (object)[
			'msg' => wfMessage( 'resetpass-temp-emailed' ),
			'hard' => true,
		];
	}

	public function getAuthenticationRequests( $action, array $options ) {
		switch ( $action ) {
			case AuthManager::ACTION_LOGIN:
				return [ new PasswordAuthenticationRequest() ];

			case AuthManager::ACTION_CHANGE:
				return [ TemporaryPasswordAuthenticationRequest::newRandom() ];

			case AuthManager::ACTION_CREATE:
				if ( isset( $options['username'] ) && $this->emailEnabled ) {
					// Creating an account for someone else
					return [ TemporaryPasswordAuthenticationRequest::newRandom() ];
				} else {
					// It's not terribly likely that an anonymous user will
					// be creating an account for someone else.
					return [];
				}

			case AuthManager::ACTION_REMOVE:
				return [ new TemporaryPasswordAuthenticationRequest ];

			default:
				return [];
		}
	}

	public function beginPrimaryAuthentication( array $reqs ) {
		$req = AuthenticationRequest::getRequestByClass( $reqs, PasswordAuthenticationRequest::class );
		if ( !$req || $req->username === null || $req->password === null ) {
			return AuthenticationResponse::newAbstain();
		}

		$username = User::getCanonicalName( $req->username, 'usable' );
		if ( $username === false ) {
			return AuthenticationResponse::newAbstain();
		}

		$dbr = wfGetDB( DB_REPLICA );
		$row = $dbr->selectRow(
			'user',
			[
				'user_id', 'user_newpassword', 'user_newpass_time',
			],
			[ 'user_name' => $username ],
			__METHOD__
		);
		if ( !$row ) {
			return AuthenticationResponse::newAbstain();
		}

		$status = $this->checkPasswordValidity( $username, $req->password );
		if ( !$status->isOK() ) {
			// Fatal, can't log in
			return AuthenticationResponse::newFail( $status->getMessage() );
		}

		$pwhash = $this->getPassword( $row->user_newpassword );
		if ( !$pwhash->verify( $req->password ) ) {
			return $this->failResponse( $req );
		}

		if ( !$this->isTimestampValid( $row->user_newpass_time ) ) {
			return $this->failResponse( $req );
		}

		// Add an extra log entry since a temporary password is
		// an unusual way to log in, so its important to keep track
		// of in case of abuse.
		$this->logger->info( "{user} successfully logged in using temp password",
			[
				'user' => $username,
				'requestIP' => $this->manager->getRequest()->getIP()
			]
		);

		$this->setPasswordResetFlag( $username, $status );

		return AuthenticationResponse::newPass( $username );
	}

	public function testUserCanAuthenticate( $username ) {
		$username = User::getCanonicalName( $username, 'usable' );
		if ( $username === false ) {
			return false;
		}

		$dbr = wfGetDB( DB_REPLICA );
		$row = $dbr->selectRow(
			'user',
			[ 'user_newpassword', 'user_newpass_time' ],
			[ 'user_name' => $username ],
			__METHOD__
		);
		if ( !$row ) {
			return false;
		}

		if ( $this->getPassword( $row->user_newpassword ) instanceof \InvalidPassword ) {
			return false;
		}

		if ( !$this->isTimestampValid( $row->user_newpass_time ) ) {
			return false;
		}

		return true;
	}

	public function testUserExists( $username, $flags = User::READ_NORMAL ) {
		$username = User::getCanonicalName( $username, 'usable' );
		if ( $username === false ) {
			return false;
		}

		list( $db, $options ) = \DBAccessObjectUtils::getDBOptions( $flags );
		return (bool)wfGetDB( $db )->selectField(
			[ 'user' ],
			'user_id',
			[ 'user_name' => $username ],
			__METHOD__,
			$options
		);
	}

	public function providerAllowsAuthenticationDataChange(
		AuthenticationRequest $req, $checkData = true
	) {
		if ( get_class( $req ) !== TemporaryPasswordAuthenticationRequest::class ) {
			// We don't really ignore it, but this is what the caller expects.
			return \StatusValue::newGood( 'ignored' );
		}

		if ( !$checkData ) {
			return \StatusValue::newGood();
		}

		$username = User::getCanonicalName( $req->username, 'usable' );
		if ( $username === false ) {
			return \StatusValue::newGood( 'ignored' );
		}

		$row = wfGetDB( DB_MASTER )->selectRow(
			'user',
			[ 'user_id', 'user_newpass_time' ],
			[ 'user_name' => $username ],
			__METHOD__
		);

		if ( !$row ) {
			return \StatusValue::newGood( 'ignored' );
		}

		$sv = \StatusValue::newGood();
		if ( $req->password !== null ) {
			$sv->merge( $this->checkPasswordValidity( $username, $req->password ) );

			if ( $req->mailpassword ) {
				if ( !$this->emailEnabled ) {
					return \StatusValue::newFatal( 'passwordreset-emaildisabled' );
				}

				// We don't check whether the user has an email address;
				// that information should not be exposed to the caller.

				// do not allow temporary password creation within
				// $wgPasswordReminderResendTime from the last attempt
				if (
					$this->passwordReminderResendTime
					&& $row->user_newpass_time
					&& time() < wfTimestamp( TS_UNIX, $row->user_newpass_time )
						+ $this->passwordReminderResendTime * 3600
				) {
					// Round the time in hours to 3 d.p., in case someone is specifying
					// minutes or seconds.
					return \StatusValue::newFatal( 'throttled-mailpassword',
						round( $this->passwordReminderResendTime, 3 ) );
				}

				if ( !$req->caller ) {
					return \StatusValue::newFatal( 'passwordreset-nocaller' );
				}
				if ( !\IP::isValid( $req->caller ) ) {
					$caller = User::newFromName( $req->caller );
					if ( !$caller ) {
						return \StatusValue::newFatal( 'passwordreset-nosuchcaller', $req->caller );
					}
				}
			}
		}
		return $sv;
	}

	public function providerChangeAuthenticationData( AuthenticationRequest $req ) {
		$username = $req->username !== null ? User::getCanonicalName( $req->username, 'usable' ) : false;
		if ( $username === false ) {
			return;
		}

		$dbw = wfGetDB( DB_MASTER );

		$sendMail = false;
		if ( $req->action !== AuthManager::ACTION_REMOVE &&
			get_class( $req ) === TemporaryPasswordAuthenticationRequest::class
		) {
			$pwhash = $this->getPasswordFactory()->newFromPlaintext( $req->password );
			$newpassTime = $dbw->timestamp();
			$sendMail = $req->mailpassword;
		} else {
			// Invalidate the temporary password when any other auth is reset, or when removing
			$pwhash = $this->getPasswordFactory()->newFromCiphertext( null );
			$newpassTime = null;
		}

		$dbw->update(
			'user',
			[
				'user_newpassword' => $pwhash->toString(),
				'user_newpass_time' => $newpassTime,
			],
			[ 'user_name' => $username ],
			__METHOD__
		);

		if ( $sendMail ) {
			// Send email after DB commit
			$dbw->onTransactionCommitOrIdle(
				function () use ( $req ) {
					/** @var TemporaryPasswordAuthenticationRequest $req */
					$this->sendPasswordResetEmail( $req );
				},
				__METHOD__
			);
		}
	}

	public function accountCreationType() {
		return self::TYPE_CREATE;
	}

	public function testForAccountCreation( $user, $creator, array $reqs ) {
		/** @var TemporaryPasswordAuthenticationRequest $req */
		$req = AuthenticationRequest::getRequestByClass(
			$reqs, TemporaryPasswordAuthenticationRequest::class
		);

		$ret = \StatusValue::newGood();
		if ( $req ) {
			if ( $req->mailpassword ) {
				if ( !$this->emailEnabled ) {
					$ret->merge( \StatusValue::newFatal( 'emaildisabled' ) );
				} elseif ( !$user->getEmail() ) {
					$ret->merge( \StatusValue::newFatal( 'noemailcreate' ) );
				}
			}

			$ret->merge(
				$this->checkPasswordValidity( $user->getName(), $req->password )
			);
		}
		return $ret;
	}

	public function beginPrimaryAccountCreation( $user, $creator, array $reqs ) {
		/** @var TemporaryPasswordAuthenticationRequest $req */
		$req = AuthenticationRequest::getRequestByClass(
			$reqs, TemporaryPasswordAuthenticationRequest::class
		);
		if ( $req && $req->username !== null && $req->password !== null ) {
			// Nothing we can do yet, because the user isn't in the DB yet
			if ( $req->username !== $user->getName() ) {
				$req = clone $req;
				$req->username = $user->getName();
			}

			if ( $req->mailpassword ) {
				// prevent EmailNotificationSecondaryAuthenticationProvider from sending another mail
				$this->manager->setAuthenticationSessionData( 'no-email', true );
			}

			$ret = AuthenticationResponse::newPass( $req->username );
			$ret->createRequest = $req;
			return $ret;
		}
		return AuthenticationResponse::newAbstain();
	}

	public function finishAccountCreation( $user, $creator, AuthenticationResponse $res ) {
		/** @var TemporaryPasswordAuthenticationRequest $req */
		$req = $res->createRequest;
		$mailpassword = $req->mailpassword;
		$req->mailpassword = false; // providerChangeAuthenticationData would send the wrong email

		// Now that the user is in the DB, set the password on it.
		$this->providerChangeAuthenticationData( $req );

		if ( $mailpassword ) {
			// Send email after DB commit
			wfGetDB( DB_MASTER )->onTransactionCommitOrIdle(
				function () use ( $user, $creator, $req ) {
					$this->sendNewAccountEmail( $user, $creator, $req->password );
				},
				__METHOD__
			);
		}

		return $mailpassword ? 'byemail' : null;
	}

	/**
	 * Check that a temporary password is still valid (hasn't expired).
	 * @param string $timestamp A timestamp in MediaWiki (TS_MW) format
	 * @return bool
	 */
	protected function isTimestampValid( $timestamp ) {
		$time = wfTimestampOrNull( TS_MW, $timestamp );
		if ( $time !== null ) {
			$expiry = wfTimestamp( TS_UNIX, $time ) + $this->newPasswordExpiry;
			if ( time() >= $expiry ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Send an email about the new account creation and the temporary password.
	 * @param User $user The new user account
	 * @param User $creatingUser The user who created the account (can be anonymous)
	 * @param string $password The temporary password
	 * @return \Status
	 */
	protected function sendNewAccountEmail( User $user, User $creatingUser, $password ) {
		$ip = $creatingUser->getRequest()->getIP();
		// @codeCoverageIgnoreStart
		if ( !$ip ) {
			return \Status::newFatal( 'badipaddress' );
		}
		// @codeCoverageIgnoreEnd

		\Hooks::run( 'User::mailPasswordInternal', [ &$creatingUser, &$ip, &$user ] );

		$mainPageUrl = \Title::newMainPage()->getCanonicalURL();
		$userLanguage = $user->getOption( 'language' );
		$subjectMessage = wfMessage( 'createaccount-title' )->inLanguage( $userLanguage );
		$bodyMessage = wfMessage( 'createaccount-text', $ip, $user->getName(), $password,
			'<' . $mainPageUrl . '>', round( $this->newPasswordExpiry / 86400 ) )
			->inLanguage( $userLanguage );

		$status = $user->sendMail( $subjectMessage->text(), $bodyMessage->text() );

		// TODO show 'mailerror' message on error, 'accmailtext' success message otherwise?
		// @codeCoverageIgnoreStart
		if ( !$status->isGood() ) {
			$this->logger->warning( 'Could not send account creation email: ' .
				$status->getWikiText( false, false, 'en' ) );
		}
		// @codeCoverageIgnoreEnd

		return $status;
	}

	/**
	 * @param TemporaryPasswordAuthenticationRequest $req
	 * @return \Status
	 */
	protected function sendPasswordResetEmail( TemporaryPasswordAuthenticationRequest $req ) {
			$user = User::newFromName( $req->username );
			if ( !$user ) {
				return \Status::newFatal( 'noname' );
			}
			$userLanguage = $user->getOption( 'language' );
			$callerIsAnon = \IP::isValid( $req->caller );
			$callerName = $callerIsAnon ? $req->caller : User::newFromName( $req->caller )->getName();
			$passwordMessage = wfMessage( 'passwordreset-emailelement', $user->getName(),
				$req->password )->inLanguage( $userLanguage );
			$emailMessage = wfMessage( $callerIsAnon ? 'passwordreset-emailtext-ip'
				: 'passwordreset-emailtext-user' )->inLanguage( $userLanguage );
			$emailMessage->params( $callerName, $passwordMessage->text(), 1,
				'<' . \Title::newMainPage()->getCanonicalURL() . '>',
				round( $this->newPasswordExpiry / 86400 ) );
			$emailTitle = wfMessage( 'passwordreset-emailtitle' )->inLanguage( $userLanguage );
			return $user->sendMail( $emailTitle->text(), $emailMessage->text() );
	}
}

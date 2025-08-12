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

namespace MediaWiki\Auth;

use MediaWiki\MainConfigNames;
use MediaWiki\Password\InvalidPassword;
use MediaWiki\Password\Password;
use MediaWiki\Password\PasswordFactory;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\User\UserRigorOptions;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * A primary authentication provider that uses a temporary password.
 *
 * A successful login will force a password reset.
 *
 * @note For proper operation, this should generally come before any other
 *  password-based authentication providers.
 * @stable to extend
 * @ingroup Auth
 * @since 1.43
 */
abstract class AbstractTemporaryPasswordPrimaryAuthenticationProvider
	extends AbstractPasswordPrimaryAuthenticationProvider
{
	/** @var bool */
	protected $emailEnabled = null;

	/** @var int */
	protected $newPasswordExpiry = null;

	/** @var int */
	protected $passwordReminderResendTime = null;

	protected IConnectionProvider $dbProvider;
	protected UserOptionsLookup $userOptionsLookup;

	/**
	 * @param IConnectionProvider $dbProvider
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param array $params
	 *  - emailEnabled: (bool) must be true for the option to email passwords to be present
	 *  - newPasswordExpiry: (int) expiration time of temporary passwords, in seconds
	 *  - passwordReminderResendTime: (int) cooldown period in hours until a password reminder can
	 *    be sent to the same user again
	 */
	public function __construct(
		IConnectionProvider $dbProvider,
		UserOptionsLookup $userOptionsLookup,
		$params = []
	) {
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
		$this->dbProvider = $dbProvider;
		$this->userOptionsLookup = $userOptionsLookup;
	}

	protected function postInitSetup() {
		$this->emailEnabled ??= $this->config->get( MainConfigNames::EnableEmail );
		$this->newPasswordExpiry ??= $this->config->get( MainConfigNames::NewPasswordExpiry );
		$this->passwordReminderResendTime ??=
			$this->config->get( MainConfigNames::PasswordReminderResendTime );
	}

	/** @inheritDoc */
	protected function getPasswordResetData( $username, $data ) {
		// Always reset
		return (object)[
			'msg' => wfMessage( 'resetpass-temp-emailed' ),
			'hard' => true,
		];
	}

	/** @inheritDoc */
	public function getAuthenticationRequests( $action, array $options ) {
		switch ( $action ) {
			case AuthManager::ACTION_LOGIN:
				return [ new PasswordAuthenticationRequest() ];

			case AuthManager::ACTION_CHANGE:
				return [ TemporaryPasswordAuthenticationRequest::newRandom() ];

			case AuthManager::ACTION_CREATE:
				// Allow named users creating a new account to email a temporary password to a given address
				// in case they are creating an account for somebody else.
				// This isn't a likely scenario for account creations by anonymous or temporary users
				// and is therefore disabled for them (T328718).
				if (
					isset( $options['username'] ) &&
					!$this->userNameUtils->isTemp( $options['username'] ) &&
					$this->emailEnabled
				) {
					return [ TemporaryPasswordAuthenticationRequest::newRandom() ];
				} else {
					return [];
				}

			case AuthManager::ACTION_REMOVE:
				return [ new TemporaryPasswordAuthenticationRequest ];

			default:
				return [];
		}
	}

	/** @inheritDoc */
	public function beginPrimaryAuthentication( array $reqs ) {
		$req = AuthenticationRequest::getRequestByClass( $reqs, PasswordAuthenticationRequest::class );
		if ( !$req || $req->username === null || $req->password === null ) {
			return AuthenticationResponse::newAbstain();
		}

		$username = $this->userNameUtils->getCanonical(
			$req->username, UserRigorOptions::RIGOR_USABLE );
		if ( $username === false ) {
			return AuthenticationResponse::newAbstain();
		}

		[ $tempPassHash, $tempPassTime ] = $this->getTemporaryPassword( $username, IDBAccessObject::READ_LATEST );
		if ( !$tempPassHash ) {
			return AuthenticationResponse::newAbstain();
		}

		$status = $this->checkPasswordValidity( $username, $req->password );
		if ( !$status->isOK() ) {
			return $this->getFatalPasswordErrorResponse( $username, $status );
		}

		if ( !$tempPassHash->verify( $req->password ) ||
			!$this->isTimestampValid( $tempPassTime )
		) {
			return $this->failResponse( $req );
		}

		// Add an extra log entry since a temporary password is
		// an unusual way to log in, so its important to keep track
		// of in case of abuse.
		$this->logger->info( "{user} successfully logged in using temp password",
			[
				'provider' => static::class,
				'user' => $username,
				'requestIP' => $this->manager->getRequest()->getIP()
			]
		);

		$this->setPasswordResetFlag( $username, $status );

		return AuthenticationResponse::newPass( $username );
	}

	/** @inheritDoc */
	public function testUserCanAuthenticate( $username ) {
		$username = $this->userNameUtils->getCanonical( $username, UserRigorOptions::RIGOR_USABLE );
		if ( $username === false ) {
			return false;
		}

		[ $tempPassHash, $tempPassTime ] = $this->getTemporaryPassword( $username );
		return $tempPassHash &&
			!( $tempPassHash instanceof InvalidPassword ) &&
			$this->isTimestampValid( $tempPassTime );
	}

	/** @inheritDoc */
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

		$username = $this->userNameUtils->getCanonical(
			$req->username, UserRigorOptions::RIGOR_USABLE );
		if ( $username === false ) {
			return \StatusValue::newGood( 'ignored' );
		}

		[ $tempPassHash, $tempPassTime ] = $this->getTemporaryPassword( $username, IDBAccessObject::READ_LATEST );
		if ( !$tempPassHash ) {
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
					&& $tempPassTime
					&& time() < (int)wfTimestamp( TS_UNIX, $tempPassTime )
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
				if ( !IPUtils::isValid( $req->caller ) ) {
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
		$username = $req->username !== null ?
			$this->userNameUtils->getCanonical( $req->username, UserRigorOptions::RIGOR_USABLE ) : false;
		if ( $username === false ) {
			return;
		}

		$sendMail = false;
		if ( $req->action !== AuthManager::ACTION_REMOVE &&
			get_class( $req ) === TemporaryPasswordAuthenticationRequest::class
		) {
			$tempPassHash = $this->getPasswordFactory()->newFromPlaintext( $req->password );
			$tempPassTime = wfTimestampNow();
			$sendMail = $req->mailpassword;
			// Prevent other temp password providers from sending duplicate emails
			$req->mailpassword = false;
		} else {
			// Invalidate the temporary password when any other auth is reset, or when removing
			$tempPassHash = PasswordFactory::newInvalidPassword();
			$tempPassTime = null;
		}

		$this->setTemporaryPassword( $username, $tempPassHash, $tempPassTime );

		if ( $sendMail ) {
			$this->maybeSendPasswordResetEmail( $req );
		}
	}

	/** @inheritDoc */
	public function accountCreationType() {
		return self::TYPE_CREATE;
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function finishAccountCreation( $user, $creator, AuthenticationResponse $res ) {
		/** @var TemporaryPasswordAuthenticationRequest $req */
		$req = $res->createRequest;
		$mailpassword = $req->mailpassword;
		// Prevent providerChangeAuthenticationData() from sending the wrong email
		$req->mailpassword = false;

		// Now that the user is in the DB, set the password on it.
		$this->providerChangeAuthenticationData( $req );

		if ( $mailpassword ) {
			$this->maybeSendNewAccountEmail( $user, $creator, $req->password );
		}

		return $mailpassword ? 'byemail' : null;
	}

	/**
	 * Check that a temporary password is still valid (hasn't expired).
	 * @param string|int|null $timestamp Timestamp in the database's format; null means it doesn't
	 *   expire
	 * @return bool
	 */
	protected function isTimestampValid( $timestamp ) {
		$time = wfTimestampOrNull( TS_MW, $timestamp );
		if ( $time !== null ) {
			$expiry = (int)wfTimestamp( TS_UNIX, $time ) + $this->newPasswordExpiry;
			if ( time() >= $expiry ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Wait for the new account to be recorded, and if successful, send an email about the new
	 * account creation and the temporary password.
	 *
	 * If overridden, the override must call sendNewAccountEmail().
	 *
	 * @stable to override
	 * @param User $user The new user account
	 * @param User $creatingUser The user who created the account (can be anonymous)
	 * @param string $password The temporary password
	 */
	protected function maybeSendNewAccountEmail( User $user, User $creatingUser, $password ): void {
		// Send email after DB commit (the callback does not run in case of DB rollback)
		$this->dbProvider->getPrimaryDatabase()->onTransactionCommitOrIdle(
			function () use ( $user, $creatingUser, $password ) {
				$this->sendNewAccountEmail( $user, $creatingUser, $password );
			},
			__METHOD__
		);
	}

	/**
	 * Send an email about the new account creation and the temporary password.
	 *
	 * @stable to override
	 * @param User $user The new user account
	 * @param User $creatingUser The user who created the account (can be anonymous)
	 * @param string $password The temporary password
	 */
	protected function sendNewAccountEmail( User $user, User $creatingUser, $password ): void {
		$ip = $creatingUser->getRequest()->getIP();
		// @codeCoverageIgnoreStart
		if ( !$ip ) {
			return;
		}
		// @codeCoverageIgnoreEnd

		$this->getHookRunner()->onUser__mailPasswordInternal( $creatingUser, $ip, $user );

		$mainPageUrl = Title::newMainPage()->getCanonicalURL();
		$userLanguage = $this->userOptionsLookup->getOption( $user, 'language' );
		$subjectMessage = wfMessage( 'createaccount-title' )->inLanguage( $userLanguage );
		$bodyMessage = wfMessage( 'createaccount-text', $ip, $user->getName(), $password,
			'<' . $mainPageUrl . '>', round( $this->newPasswordExpiry / 86400 ) )
			->inLanguage( $userLanguage );

		$status = $user->sendMail( $subjectMessage->text(), $bodyMessage->text() );

		// @codeCoverageIgnoreStart
		if ( !$status->isGood() ) {
			$this->logger->warning( 'Could not send account creation email: ' .
				$status->getWikiText( false, false, 'en' ) );
		}
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Wait for the new temporary password to be recorded, and if successful, send an email about it.
	 *
	 * If overridden, the override must call sendPasswordResetEmail().
	 *
	 * @stable to override
	 * @param TemporaryPasswordAuthenticationRequest $req
	 */
	protected function maybeSendPasswordResetEmail( TemporaryPasswordAuthenticationRequest $req ): void {
		// Send email after DB commit (the callback does not run in case of DB rollback)
		$this->dbProvider->getPrimaryDatabase()->onTransactionCommitOrIdle(
			function () use ( $req ) {
				$this->sendPasswordResetEmail( $req );
			},
			__METHOD__
		);
	}

	/**
	 * Send an email about the new temporary password.
	 *
	 * @stable to override
	 * @param TemporaryPasswordAuthenticationRequest $req
	 */
	protected function sendPasswordResetEmail( TemporaryPasswordAuthenticationRequest $req ): void {
		$user = User::newFromName( $req->username );
		if ( !$user ) {
			return;
		}
		$userLanguage = $this->userOptionsLookup->getOption( $user, 'language' );
		$callerIsAnon = IPUtils::isValid( $req->caller );
		$callerName = $callerIsAnon ? $req->caller : User::newFromName( $req->caller )->getName();
		$passwordMessage = wfMessage( 'passwordreset-emailelement', $user->getName(),
			$req->password )->inLanguage( $userLanguage );
		$emailMessage = wfMessage( $callerIsAnon ? 'passwordreset-emailtext-ip'
			: 'passwordreset-emailtext-user' )->inLanguage( $userLanguage );
		$body = $emailMessage->params( $callerName, $passwordMessage->text(), 1,
			'<' . Title::newMainPage()->getCanonicalURL() . '>',
			round( $this->newPasswordExpiry / 86400 ) )->text();

		// Hint that the user can choose to require email address to request a temporary password
		if (
			!$this->userOptionsLookup->getBoolOption( $user, 'requireemail' )
		) {
			$url = SpecialPage::getTitleFor( 'Preferences', false, 'mw-prefsection-personal-email' )
				->getCanonicalURL();
			$body .= "\n\n" . wfMessage( 'passwordreset-emailtext-require-email' )
				->inLanguage( $userLanguage )
				->params( "<$url>" )
				->text();
		}

		$emailTitle = wfMessage( 'passwordreset-emailtitle' )->inLanguage( $userLanguage );
		$user->sendMail( $emailTitle->text(), $body );
	}

	/**
	 * Return a tuple of temporary password and the time when it was generated.
	 *
	 * The password may be an InvalidPassword to represent that it was unset, or null if the user
	 * can't authenticate for other reasons.
	 *
	 * The time is a a timestamp in the database's format or null (use wfTimestampOrNull() to parse
	 * it). If it's null, the password doesn't expire. Otherwise, the password should be considered
	 * expired after $wgNewPasswordExpiry seconds since that time.
	 *
	 * @stable to override
	 * @param string $username Canonical username
	 * @param int $flags Bitfield of IDBAccessObject::READ_* constants
	 * @return array
	 * @phan-return array{0:?Password, 1:?string|int}
	 */
	abstract protected function getTemporaryPassword( string $username, $flags = IDBAccessObject::READ_NORMAL ): array;

	/**
	 * Set a temporary password and the time when it was generated.
	 *
	 * @param string $username Canonical username
	 * @param Password $tempPassHash Password, or an InvalidPassword to unset
	 * @param string|int|null $tempPassTime Timestamp in a format accepted by wfTimestampOrNull();
	 *   null means it doesn't expire
	 */
	abstract protected function setTemporaryPassword( string $username, Password $tempPassHash, $tempPassTime ): void;
}

<?php

namespace MediaWiki\Auth;

use Config;
use Hooks;
use IP;
use RequestContext;
use StatusValue;
use Title;
use User;

/**
 * Handles notifications for account creation:
 * - Adds a record to the log table.
 * - Sends an email to the new user.
 */
class NotificationSecondaryAuthenticationProvider
	extends AbstractSecondaryAuthenticationProvider
{
	/** @var bool */
	protected $emailEnabled = null;

	/** @var int */
	protected $newPasswordExpiry = null;

	/** @var bool */
	protected $confirmEmail = null;

	/** @var int */
	protected $passwordReminderResendTime = null;

	/**
	 * @param array $params
	 *  - emailEnabled: (bool) must be true for the option to email passwords to be present
	 *  - newPasswordExpiry: (int) expiraton time of temporary passwords, in seconds
	 *  - confirmEmail: (bool) send a confirmation email to verify the new user's email address
	 *  - passwordReminderResendTime: (int) cooldown period in hours until a password reminder can
	 *    be sent to the same user again,
	 */
	public function __construct( $params = [] ) {
		if ( isset( $params['emailEnabled'] ) ) {
			$this->emailEnabled = (bool)$params['emailEnabled'];
		}
		if ( isset( $params['newPasswordExpiry'] ) ) {
			$this->newPasswordExpiry = (int)$params['newPasswordExpiry'];
		}
		if ( isset( $params['confirmEmail'] ) ) {
			$this->confirmEmail = (int)$params['confirmEmail'];
		}
		if ( isset( $params['passwordReminderResendTime'] ) ) {
			$this->passwordReminderResendTime = $params['passwordReminderResendTime'];
		}
	}

	public function setConfig( Config $config ) {
		parent::setConfig( $config );

		if ( $this->emailEnabled === null ) {
			$this->emailEnabled = $this->config->get( 'EnableEmail' );
		}
		if ( $this->newPasswordExpiry === null ) {
			$this->newPasswordExpiry = $this->config->get( 'NewPasswordExpiry' );
		}
		if ( $this->confirmEmail === null ) {
			$this->confirmEmail = $this->config->get( 'EmailAuthentication' );
		}
		if ( $this->passwordReminderResendTime === null ) {
			$this->passwordReminderResendTime = $this->config->get( 'PasswordReminderResendTime' );
		}
	}

	public function getAuthenticationRequests( $action, array $options ) {
		switch ( $action ) {
			case AuthManager::ACTION_CREATE:
				if ( isset( $options['username'] ) && $this->emailEnabled ) {
					return [ new MailPasswordAuthenticationRequest() ];
				}
				break;
		}
		return [];
	}

	public function testForAccountCreation( $user, $creator, array $reqs ) {
		/** @var MailPasswordAuthenticationRequest $mailPasswordReq */
		$mailPasswordReq = AuthenticationRequest::getRequestByClass( $reqs,
			'MediaWiki\\Auth\\MailPasswordAuthenticationRequest' );
		$mailPassword = $mailPasswordReq ? $mailPasswordReq->mailpassword : false;
		/** @var UserDataAuthenticationRequest $userDataReq */
		$userDataReq = AuthenticationRequest::getRequestByClass( $reqs,
			'MediaWiki\\Auth\\UserDataAuthenticationRequest' );
		$email = $userDataReq->email;

		// FIXME this is not really correct. The user could obtain a valid email field in some other way.
		if ( $mailPassword && !$email ) {
			return StatusValue::newFatal( 'noemailcreate' );
		}

		$ip = $creator->getRequest()->getIP();
		if ( !$ip ) {
			return StatusValue::newFatal( 'badipaddress' );
		}

		return StatusValue::newGood();
	}

	public function beginSecondaryAuthentication( $user, array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

	public function beginSecondaryAccountCreation( $user, $creator, array $reqs ) {
		/** @var MailPasswordAuthenticationRequest $mailPasswordReq */
		$mailPasswordReq = AuthenticationRequest::getRequestByClass( $reqs,
			'MediaWiki\\Auth\\MailPasswordAuthenticationRequest' );
		$mailPassword = $mailPasswordReq ? $mailPasswordReq->mailpassword : false;

		$this->sendNewAccountEmail( $user, $creator, 'createaccount-title',
			'createaccount-text', $mailPassword );

		return AuthenticationResponse::newPass();
	}

	public function providerAllowsAuthenticationDataChange( AuthenticationRequest $req,
		$checkData = true
	) {
		if (
			$req instanceof TemporaryPasswordAuthenticationRequest
			&& $checkData
			&& $req->mailpassword
		) {
			if ( !$this->emailEnabled ) {
				return StatusValue::newFatal( 'passwordreset-emaildisabled' );
			}

			$user = User::newFromName( $req->username );
			if ( !$user ) {
				return StatusValue::newFatal( 'nosuchuser', wfEscapeWikiText( $req->username ) );
			}

			// We don't check whether the user has an email address;
			// that information should not be exposed to the caller.

			if ( $this->isPasswordReminderThrottled( $user ) ) {
				// Round the time in hours to 3 d.p., in case someone is specifying
				// minutes or seconds.
				return StatusValue::newFatal( 'throttled-mailpassword',
					round( $this->passwordReminderResendTime, 3 ) );
			}

			if ( !$req->caller ) {
				return StatusValue::newFatal( 'passwordreset-nocaller' );
			}
			if ( !IP::isValid( $req->caller ) ) {
				$caller = User::newFromName( $req->caller );
				if ( !$caller ) {
					return StatusValue::newFatal( 'passwordreset-nosuchcaller', $req->caller );
				}
			}
		}

		return StatusValue::newGood();
	}

	public function providerChangeAuthenticationData( AuthenticationRequest $req ) {
		if ( $req instanceof TemporaryPasswordAuthenticationRequest && $req->mailpassword ) {
			// send password reset email

			$user = User::newFromName( $req->username );
			$userLanguage = $user->getOption( 'language' );
			$callerIsAnon = IP::isValid( $req->caller );
			$callerName = $callerIsAnon ? $req->caller : User::newFromName( $req->caller )->getName();
			$passwordMessage = wfMessage( 'passwordreset-emailelement', $user->getName(),
				$req->password )->inLanguage( $userLanguage );
			$emailMessage = wfMessage( $callerIsAnon ? 'passwordreset-emailtext-ip'
				: 'passwordreset-emailtext-user' )->inLanguage( $userLanguage );
			$emailMessage->params( $callerName, $passwordMessage->text(), 1,
				'<' . Title::newMainPage()->getCanonicalURL() . '>',
				round( $this->newPasswordExpiry / 86400 ) );
			$emailTitle = wfMessage( 'passwordreset-emailtitle' )->inLanguage( $userLanguage );
			$status = $user->sendMail( $emailTitle->text(), $emailMessage->text() );

			// TODO capture and return to client
		}
	}

	/**
	 * @param User $user The new user account
	 * @param User $creatingUser The user who created the account (can be anonymous)
	 * @param string $emailSubject Message key for the subject
	 * @param string $emailText Message key for the body. It should have the following parameters:
	 *   $1: IP address, $2: name of the new account, $3: temporary password,
	 *   $4: link to wiki main page, $5: time until password expires.
	 * @param boolean $mailPassword Whether to mail a random password
	 */
	protected function sendNewAccountEmail(
		User $user, User $creatingUser, $emailSubject, $emailText, $mailPassword
	) {
		// FIXME user language needs to be set by this point
		if ( $mailPassword ) {
			$ip = $creatingUser->getRequest()->getIP();
			Hooks::run( 'User::mailPasswordInternal', [ &$creatingUser, &$ip, &$user ] );

			$password = $this->manager->getAuthenticationSessionData( 'temp-password' );

			$mainPageUrl = Title::newMainPage()->getCanonicalURL();
			$userLanguage = $user->getOption( 'language' );
			$subjectMessage = wfMessage( $emailSubject )->inLanguage( $userLanguage );
			$bodyMessage =
				wfMessage( $emailText, $ip, $user->getName(), $password, '<' . $mainPageUrl . '>',
					round( $this->newPasswordExpiry / 86400 ) )->inLanguage( $userLanguage );

			$status = $user->sendMail( $subjectMessage->text(), $bodyMessage->text() );

			if ( !$status->isGood() ) {
				// TODO show 'mailerror' message?
				$this->logger->warning( 'Could not send account creation email: ' .
					$status->getWikiText( false, false, 'en' ) );
			}
			// else show 'accmailtext' success message?
		} else {
			# Send out an email authentication message if needed
			global $wgEmailAuthentication ;
			if ( $wgEmailAuthentication && $user->getEmail() ) {
				$status = $user->sendConfirmationMail();
				$user->saveSettings();
				if ( $status->isGood() ) {
					// TODO show 'confirmemail_oncreate' success message
				} else {
					// TODO show 'confirmemail_sendfailed' error message
					$this->logger->warning( 'Could not send confirmation email: ' .
						$status->getWikiText( false, false, 'en' ) );
				}
			}
		}
	}

	/**
	 * Has password reminder email been sent within the last $wgPasswordReminderResendTime hours?
	 * @param User $user
	 * @return bool
	 */
	protected function isPasswordReminderThrottled( User $user ) {
		if ( !$this->passwordReminderResendTime ) {
			return false;
		}

		$newpassTime = wfGetDB( DB_SLAVE )->selectField(
			'user',
			'user_newpass_time',
			[ 'user_id' => $user->getId() ],
			__METHOD__
		);

		if ( $newpassTime === null ) {
			return false;
		}
		$expiry = wfTimestamp( TS_UNIX, $newpassTime ) + $this->passwordReminderResendTime * 3600;
		return time() < $expiry;
	}
}

<?php

namespace MediaWiki\Auth;

use Config;
use Hooks;
use RequestContext;
use StatusValue;
use Title;
use User;

/**
 * Handles notifications for account creation:
 * - Adds a record to the log table.
 * - Sends an email to the new user.
 */
class CreationNotificationSecondaryAuthenticationProvider
	extends AbstractSecondaryAuthenticationProvider
{
	/** @var bool */
	protected $emailEnabled = null;

	/** @var int */
	protected $newPasswordExpiry = null;

	/** @var bool */
	protected $confirmEmail = null;

	/**
	 * @param array $params
	 *  - emailEnabled: (bool) must be true for the option to email passwords to be present
	 *  - newPasswordExpiry: (int) expiraton time of temporary passwords, in seconds
	 *  - confirmEmail: (bool) send a confirmation email to verify the new user's email address
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
	}

	public function getAuthenticationRequests( $action, array $options ) {
		switch ( $action ) {
			case AuthManager::ACTION_CREATE:
				if ( isset( $options['username'] ) ) {
					$reqs = [ new CreationReasonAuthenticationRequest() ];
					if ( $this->emailEnabled ) {
						$reqs[] = new MailPasswordAuthenticationRequest();
					}
					return $reqs;
				}
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

		$ip = $this->manager->getRequest()->getIP();
		if ( !$ip ) {
			return StatusValue::newFatal( 'badipaddress' );
		}

		return StatusValue::newGood();
	}

	public function beginSecondaryAuthentication( $user, array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

	public function beginSecondaryAccountCreation( $user, array $reqs ) {
		/** @var CreationReasonAuthenticationRequest $reasonReq */
		$reasonReq = AuthenticationRequest::getRequestByClass( $reqs,
			'MediaWiki\\Auth\\CreationReasonAuthenticationRequest' );
		/** @var MailPasswordAuthenticationRequest $mailPasswordReq */
		$mailPasswordReq = AuthenticationRequest::getRequestByClass( $reqs,
			'MediaWiki\\Auth\\MailPasswordAuthenticationRequest' );
		$reason = $reasonReq ? $reasonReq->reason : null;
		$mailPassword = $mailPasswordReq ? $mailPasswordReq->mailpassword : false;

		$creatingUser = RequestContext::getMain()->getUser();

		$this->logUserCreation( $user, $creatingUser, $reason, $mailPassword );
		$this->sendNewAccountEmail( $user, $creatingUser, 'createaccount-title',
			'createaccount-text', $mailPassword );

		return AuthenticationResponse::newPass();
	}

	/**
	 * @param User $user The new user account
	 * @param User $creatingUser The user who created the account (can be anonymous)
	 * @param string $reason Account creation reason
	 * @param boolean $mailPassword Whether to mail a random password
	 */
	protected function logUserCreation( $user, $creatingUser, $reason, $mailPassword ) {
		if ( $mailPassword ) {
			$res = $user->addNewUserLogEntry( 'byemail', $reason );
		} elseif ( $creatingUser->isAnon() ) {
			$res = $user->addNewUserLogEntry( 'create' );
		} else {
			$res = $user->addNewUserLogEntry( 'create2', $reason );
		}

		if ( $res === 0 ) {
			$this->logger->warning( 'Writing into user creation log failed for {user}', [
				'user' => $user->getName(),
				'creatingUser' => $creatingUser->isAnon() ? '-' : $creatingUser->getName(),
				'reason' => $reason,
				'mailPassword' => $mailPassword,
			] );
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
			$ip = $this->manager->getRequest()->getIP();
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
}

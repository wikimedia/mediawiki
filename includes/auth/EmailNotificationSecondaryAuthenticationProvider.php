<?php

namespace MediaWiki\Auth;

use Config;
use StatusValue;

/**
 * Handles email notifications for account creation.
 */
class EmailNotificationSecondaryAuthenticationProvider
	extends AbstractSecondaryAuthenticationProvider
{
	/** @var bool */
	protected $emailEnabled;

	/** @var bool */
	protected $confirmEmail;

	/**
	 * @param array $params
	 *  - emailEnabled: (bool) must be true for the option to email passwords to be present
	 *  - confirmEmail: (bool) send a confirmation email to verify the new user's email address
	 */
	public function __construct( $params = [] ) {
		if ( isset( $params['emailEnabled'] ) ) {
			$this->emailEnabled = (bool)$params['emailEnabled'];
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
		if ( $this->confirmEmail === null ) {
			$this->confirmEmail = $this->config->get( 'EmailAuthentication' );
		}
	}

	public function getAuthenticationRequests( $action, array $options ) {
		return [];
	}

	public function beginSecondaryAuthentication( $user, array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

	public function beginSecondaryAccountCreation( $user, $creator, array $reqs ) {
		if (
			$this->emailEnabled
			&& $this->confirmEmail
			&& $user->getEmail()
			// flag used by TemporaryPasswordPrimaryAuthenticationProvider when it takes over email sending
			&& !$this->manager->getAuthenticationSessionData( 'no-email' )
		) {
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

		return AuthenticationResponse::newPass();
	}
}

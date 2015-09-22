<?php

namespace MediaWiki\Auth;

use Config;
use StatusValue;

/**
 * Handles email notification / email address confirmation for account creation.
 *
 * Set 'no-email' to true (via AuthManager::setAuthenticationSessionData) to skip this provider.
 * Primary providers doing so are expected to take care of email address confirmation.
 */
class EmailNotificationSecondaryAuthenticationProvider
	extends AbstractSecondaryAuthenticationProvider
{
	/** @var bool */
	protected $sendConfirmationeEmail;

	/**
	 * @param array $params
	 *  - sendConfirmationeEmail: (bool) send an email asking the user to confirm their email
	 *    address after a successful registration
	 */
	public function __construct( $params = [] ) {
		if ( isset( $params['sendConfirmationeEmail'] ) ) {
			$this->sendConfirmationeEmail = (bool)$params['sendConfirmationeEmail'];
		}
	}

	public function setConfig( Config $config ) {
		parent::setConfig( $config );

		if ( $this->sendConfirmationeEmail === null ) {
			$this->sendConfirmationeEmail = $this->config->get( 'EnableEmail' )
				&& $this->config->get( 'EmailAuthentication' );
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
			$this->sendConfirmationeEmail
			&& $user->getEmail()
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

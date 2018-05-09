<?php

namespace MediaWiki\Auth;

use Config;

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
	protected $sendConfirmationEmail;

	/**
	 * @param array $params
	 *  - sendConfirmationEmail: (bool) send an email asking the user to confirm their email
	 *    address after a successful registration
	 */
	public function __construct( $params = [] ) {
		if ( isset( $params['sendConfirmationEmail'] ) ) {
			$this->sendConfirmationEmail = (bool)$params['sendConfirmationEmail'];
		}
	}

	public function setConfig( Config $config ) {
		parent::setConfig( $config );

		if ( $this->sendConfirmationEmail === null ) {
			$this->sendConfirmationEmail = $this->config->get( 'EnableEmail' )
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
			$this->sendConfirmationEmail
			&& $user->getEmail()
			&& !$this->manager->getAuthenticationSessionData( 'no-email' )
		) {
			// TODO show 'confirmemail_oncreate'/'confirmemail_sendfailed' message
			wfGetDB( DB_MASTER )->onTransactionCommitOrIdle(
				function () use ( $user ) {
					$user = $user->getInstanceForUpdate();
					$status = $user->sendConfirmationMail();
					$user->saveSettings();
					if ( !$status->isGood() ) {
						$this->logger->warning( 'Could not send confirmation email: ' .
							$status->getWikiText( false, false, 'en' ) );
					}
				},
				__METHOD__
			);
		}

		return AuthenticationResponse::newPass();
	}
}

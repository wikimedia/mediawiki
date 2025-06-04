<?php

namespace MediaWiki\Auth;

use MediaWiki\MainConfigNames;
use Wikimedia\Rdbms\IConnectionProvider;

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

	private IConnectionProvider $dbProvider;

	/**
	 * @param IConnectionProvider $dbProvider
	 * @param array $params
	 *  - sendConfirmationEmail: (bool) send an email asking the user to confirm their email
	 *    address after a successful registration
	 */
	public function __construct( IConnectionProvider $dbProvider, $params = [] ) {
		if ( isset( $params['sendConfirmationEmail'] ) ) {
			$this->sendConfirmationEmail = (bool)$params['sendConfirmationEmail'];
		}
		$this->dbProvider = $dbProvider;
	}

	protected function postInitSetup() {
		$this->sendConfirmationEmail ??= $this->config->get( MainConfigNames::EnableEmail )
				&& $this->config->get( MainConfigNames::EmailAuthentication );
	}

	/** @inheritDoc */
	public function getAuthenticationRequests( $action, array $options ) {
		return [];
	}

	/** @inheritDoc */
	public function beginSecondaryAuthentication( $user, array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

	/** @inheritDoc */
	public function beginSecondaryAccountCreation( $user, $creator, array $reqs ) {
		if (
			$this->sendConfirmationEmail
			&& $user->getEmail()
			&& !$this->manager->getAuthenticationSessionData( 'no-email' )
		) {
			// TODO show 'confirmemail_oncreate'/'confirmemail_sendfailed' message
			$this->dbProvider->getPrimaryDatabase()->onTransactionCommitOrIdle(
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

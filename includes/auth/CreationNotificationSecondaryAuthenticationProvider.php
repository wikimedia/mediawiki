<?php

namespace MediaWiki\Auth;

use MediaWiki\Session\SessionManager;
use User;

/**
 * Handles notifications for account creation:
 * - Adds a record to the log table.
 * - Sends an email to the new user.
 */
class CreationNotificationSecondaryAuthenticationProvider extends AbstractSecondaryAuthenticationProvider {
	/**
	 * Add a "reason" field if a logged-in user is creating the new account.
	 *
	 * @see AuthManager::getAuthenticationRequestTypes()
	 * @param string $action
	 * @return string[] AuthenticationRequest class names
	 */
	public function getAuthenticationRequestTypes( $action ) {
		$userId = SessionManager::getGlobalSession()->getUser()->getId();
		switch ( $action ) {
			case AuthManager::ACTION_CREATE:
				return $userId ? array( 'CreationReasonAuthenticationRequest' ) : array();
			case AuthManager::ACTION_ALL:
				return array( 'CreationReasonAuthenticationRequest' );
			default:
				return array();
		}
	}

	/**
	 * @param User $user User being authenticated.
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse
	 */
	public function beginSecondaryAuthentication( $user, array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

	/**
	 * Start an account creation flow
	 * @param User $user User being created (has been added to the database).
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse FAIL response is not allowed
	 */
	public function beginSecondaryAccountCreation( $user, array $reqs ) {
		$reason = isset( $reqs['CreationReasonAuthenticationRequest'] ) ?
			$reqs['CreationReasonAuthenticationRequest']->reason : null;
		$mailPassword = isset( $reqs['MailPasswordAuthenticationRequest'] ) ?
			$reqs['MailPasswordAuthenticationRequest']->mailpassword : false;

		$creatingUser = SessionManager::getGlobalSession()->getUser();

		$this->logUserCreation( $user, $creatingUser, $reason, $mailPassword );
		$this->sendNewAccountEmail( $user, $creatingUser, $reason, $mailPassword );
	}

	protected function logUserCreation( $user, $creatingUser, $reason, $mailPassword ) {

	}

	protected function sendNewAccountEmail( $user, $creatingUser, $reason, $mailPassword ) {

	}
}

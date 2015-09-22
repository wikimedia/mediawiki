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
	public function getAuthenticationRequests( $action ) {
		$userId = SessionManager::getGlobalSession()->getUser()->getId();
		switch ( $action ) {
			case AuthManager::ACTION_CREATE:
				return $userId ? array( new CreationReasonAuthenticationRequest() ) : array();
			default:
				return array();
		}
	}

	public function beginSecondaryAuthentication( $user, array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

	public function beginSecondaryAccountCreation( $user, array $reqs ) {
		/** @var CreationReasonAuthenticationRequest $creationReq */
		$creationReq = AuthenticationRequest::getRequestByClass( $reqs,
			'MediaWiki\\Auth\\CreationReasonAuthenticationRequest' );
		/** @var MailPasswordAuthenticationRequest $mailPasswordReq */
		$mailPasswordReq = AuthenticationRequest::getRequestByClass( $reqs,
			'MediaWiki\\Auth\\MailPasswordAuthenticationRequest' );
		$reason = $creationReq ? $creationReq->reason : null;
		$mailPassword = $mailPasswordReq ? $mailPasswordReq->mailpassword : false;

		$creatingUser = SessionManager::getGlobalSession()->getUser();

		$this->logUserCreation( $user, $creatingUser, $reason, $mailPassword );
		$this->sendNewAccountEmail( $user, $creatingUser, $reason, $mailPassword );
	}

	protected function logUserCreation( $user, $creatingUser, $reason, $mailPassword ) {

	}

	protected function sendNewAccountEmail( $user, $creatingUser, $reason, $mailPassword ) {

	}
}

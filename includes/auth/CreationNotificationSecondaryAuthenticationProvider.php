<?php

namespace MediaWiki\Auth;

use RequestContext;

/**
 * Handles notifications for account creation:
 * - Adds a record to the log table.
 * - Sends an email to the new user.
 */
class CreationNotificationSecondaryAuthenticationProvider extends AbstractSecondaryAuthenticationProvider {
	public function getAuthenticationRequests( $action, array $options ) {
		switch ( $action ) {
			case AuthManager::ACTION_CREATE:
				if ( $options['username'] ) {
					return [
						new CreationReasonAuthenticationRequest(),
						new MailPasswordAuthenticationRequest(),
					];
				} else {
					return [];
				}
			default:
				return [];
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

		$creatingUser = RequestContext::getMain()->getUser();

		$this->logUserCreation( $user, $creatingUser, $reason, $mailPassword );
		$this->sendNewAccountEmail( $user, $creatingUser, $reason, $mailPassword );
	}

	protected function logUserCreation( $user, $creatingUser, $reason, $mailPassword ) {

	}

	protected function sendNewAccountEmail( $user, $creatingUser, $reason, $mailPassword ) {

	}
}

<?php

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\MediaWikiServices;
use MediaWiki\Session\SessionManager;

class SpecialUnlinkAccounts extends AuthManagerSpecialPage {
	protected static $allowedActions = [ AuthManager::ACTION_UNLINK ];

	public function __construct() {
		parent::__construct( 'UnlinkAccounts' );
	}

	protected function getLoginSecurityLevel() {
		return 'UnlinkAccount';
	}

	protected function getDefaultAction( $subPage ) {
		return AuthManager::ACTION_UNLINK;
	}

	/**
	 * Under which header this special page is listed in Special:SpecialPages.
	 * @return string
	 */
	protected function getGroupName() {
		return 'users';
	}

	public function isListed() {
		return MediaWikiServices::getInstance()->getAuthManager()->canLinkAccounts();
	}

	protected function getRequestBlacklist() {
		return $this->getConfig()->get( 'RemoveCredentialsBlacklist' );
	}

	public function execute( $subPage ) {
		$this->setHeaders();
		$this->loadAuth( $subPage );

		if ( !$this->isActionAllowed( $this->authAction ) ) {
			if ( $this->authAction === AuthManager::ACTION_UNLINK ) {
				// Looks like there are no linked accounts to unlink
				$titleMessage = $this->msg( 'cannotunlink-no-provider-title' );
				$errorMessage = $this->msg( 'cannotunlink-no-provider' );
				throw new ErrorPageError( $titleMessage, $errorMessage );
			} else {
				// user probably back-button-navigated into an auth session that no longer exists
				// FIXME would be nice to show a message
				$this->getOutput()->redirect( $this->getPageTitle()->getFullURL( '', false, PROTO_HTTPS ) );
				return;
			}
		}

		$this->outputHeader();

		$status = $this->trySubmit();

		if ( $status === false || !$status->isOK() ) {
			$this->displayForm( $status );
			return;
		}

		/** @var AuthenticationResponse $response */
		$response = $status->getValue();

		if ( $response->status === AuthenticationResponse::FAIL ) {
			$this->displayForm( StatusValue::newFatal( $response->message ) );
			return;
		}

		$status = StatusValue::newGood();
		$status->warning( $this->msg( 'unlinkaccounts-success' ) );
		$this->loadAuth( $subPage, null, true ); // update requests so the unlinked one doesn't show up

		// Reset sessions - if the user unlinked an account because it was compromised,
		// log attackers out from sessions obtained via that account.
		$session = $this->getRequest()->getSession();
		$user = $this->getUser();
		SessionManager::singleton()->invalidateSessionsForUser( $user );
		$session->setUser( $user );
		$session->resetId();

		$this->displayForm( $status );
	}

	public function handleFormSubmit( $data ) {
		// unlink requests do not accept user input so repeat parent code but skip call to
		// AuthenticationRequest::loadRequestsFromSubmission
		$response = $this->performAuthenticationStep( $this->authAction, $this->authRequests );
		return Status::newGood( $response );
	}
}

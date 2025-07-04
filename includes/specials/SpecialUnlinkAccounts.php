<?php

namespace MediaWiki\Specials;

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\MainConfigNames;
use MediaWiki\Session\SessionManager;
use MediaWiki\SpecialPage\AuthManagerSpecialPage;
use MediaWiki\Status\Status;
use StatusValue;

/**
 * @ingroup SpecialPage
 * @ingroup Auth
 */
class SpecialUnlinkAccounts extends AuthManagerSpecialPage {
	/** @inheritDoc */
	protected static $allowedActions = [ AuthManager::ACTION_UNLINK ];

	public function __construct( AuthManager $authManager ) {
		parent::__construct( 'UnlinkAccounts' );
		$this->setAuthManager( $authManager );
	}

	/** @inheritDoc */
	protected function getLoginSecurityLevel() {
		return 'UnlinkAccount';
	}

	/** @inheritDoc */
	protected function getDefaultAction( $subPage ) {
		return AuthManager::ACTION_UNLINK;
	}

	/**
	 * Under which header this special page is listed in Special:SpecialPages.
	 * @return string
	 */
	protected function getGroupName() {
		return 'login';
	}

	/** @inheritDoc */
	public function isListed() {
		return $this->getAuthManager()->canLinkAccounts();
	}

	/** @inheritDoc */
	protected function getRequestBlacklist() {
		return $this->getConfig()->get( MainConfigNames::RemoveCredentialsBlacklist );
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function handleFormSubmit( $data ) {
		// unlink requests do not accept user input so repeat parent code but skip call to
		// AuthenticationRequest::loadRequestsFromSubmission
		$response = $this->performAuthenticationStep( $this->authAction, $this->authRequests );
		return Status::newGood( $response );
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUnlinkAccounts::class, 'SpecialUnlinkAccounts' );

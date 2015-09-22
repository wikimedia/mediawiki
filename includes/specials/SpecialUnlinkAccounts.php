<?php

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;

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
	 */
	protected function getGroupName() {
		return 'users';
	}

	public function isListed() {
		return AuthManager::singleton()->canLinkAccounts();
	}

	public function execute( $subPage ) {
		$this->setHeaders();
		$this->loadAuth( $subPage );
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
		$status->warning( wfMessage( 'unlinkaccounts-success' ) );
		$this->loadAuth( $subPage, null, true ); // update requests so the unlinked one doesn't show up
		$this->displayForm( $status );
	}
}

<?php

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Session\SessionManager;

class SpecialUnlinkAccounts extends AuthManagerSpecialPage {
	protected static $allowedActions = [ AuthManager::ACTION_UNLINK ];

	public function __construct() {
		parent::__construct( 'UnlinkAccounts', '', false );
	}

	protected function getLoginSecurityLevel() {
		return 'UnlinkAccount';
	}

	protected function getDefaultAction( $subPage ) {
		return AuthManager::ACTION_UNLINK;
	}

	public function execute( $subPage ) {
		$this->getOutput()->redirect( SPecialPage::getTitleFor( 'RemoveCredentials' )->getLocalURL() );
	}
}

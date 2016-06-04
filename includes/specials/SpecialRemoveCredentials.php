<?php

use MediaWiki\Auth\AuthManager;

/**
 * Special change to remove credentials (such as a two-factor token).
 */
class SpecialRemoveCredentials extends SpecialChangeCredentials {
	protected static $allowedActions = [ AuthManager::ACTION_REMOVE, AuthManager::ACTION_UNLINK ];

	protected static $messagePrefix = 'removecredentials';

	protected static $loadUserData = false;

	public function __construct() {
		parent::__construct( 'RemoveCredentials' );
	}

	protected function getDefaultAction( $subPage ) {
		if ( $this->getRequest()->getBool( AuthManager::ACTION_UNLINK ) === true ) {
			$this->getOutput()->setSubtitle( $this->msg( self::$messagePrefix . '-unlink-only',
				$this->getPageTitle()->getPrefixedText() ) );
			return AuthManager::ACTION_UNLINK;
		}
		return AuthManager::ACTION_REMOVE;
	}

	protected function getRequestBlacklist() {
		return $this->getConfig()->get( 'RemoveCredentialsBlacklist' );
	}
}

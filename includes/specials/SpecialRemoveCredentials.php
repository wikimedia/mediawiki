<?php

use MediaWiki\Auth\AuthManager;

/**
 * Special change to remove credentials (such as a two-factor token).
 */
class SpecialRemoveCredentials extends SpecialChangeCredentials {
	protected static $allowedActions = [ AuthManager::ACTION_REMOVE ];

	protected static $messagePrefix = 'removecredentials';

	protected static $loadUserData = false;

	public function __construct() {
		parent::__construct( 'RemoveCredentials' );
	}

	protected function getDefaultAction( $subPage ) {
		return AuthManager::ACTION_REMOVE;
	}

	protected function getRequestBlacklist() {
		return $this->getConfig()->get( 'RemoveCredentialsBlacklist' );
	}
}

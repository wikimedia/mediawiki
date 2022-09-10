<?php

use MediaWiki\Auth\AuthManager;
use MediaWiki\MainConfigNames;

/**
 * Special change to remove credentials (such as a two-factor token).
 */
class SpecialRemoveCredentials extends SpecialChangeCredentials {
	protected static $allowedActions = [ AuthManager::ACTION_REMOVE ];

	protected static $messagePrefix = 'removecredentials';

	protected static $loadUserData = false;

	/**
	 * @param AuthManager $authManager
	 */
	public function __construct( AuthManager $authManager ) {
		parent::__construct( $authManager );
		$this->mName = 'RemoveCredentials';
	}

	protected function getDefaultAction( $subPage ) {
		return AuthManager::ACTION_REMOVE;
	}

	protected function getRequestBlacklist() {
		return $this->getConfig()->get( MainConfigNames::RemoveCredentialsBlacklist );
	}
}

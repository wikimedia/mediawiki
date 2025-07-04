<?php

namespace MediaWiki\Specials;

use MediaWiki\Auth\AuthManager;
use MediaWiki\MainConfigNames;

/**
 * Special change to remove credentials (such as a two-factor token).
 *
 * @ingroup SpecialPage
 */
class SpecialRemoveCredentials extends SpecialChangeCredentials {
	/** @inheritDoc */
	protected static $allowedActions = [ AuthManager::ACTION_REMOVE ];

	/** @inheritDoc */
	protected static $messagePrefix = 'removecredentials';

	/** @inheritDoc */
	protected static $loadUserData = false;

	public function __construct( AuthManager $authManager ) {
		parent::__construct( $authManager );
		$this->mName = 'RemoveCredentials';
	}

	/** @inheritDoc */
	protected function getDefaultAction( $subPage ) {
		return AuthManager::ACTION_REMOVE;
	}

	/** @inheritDoc */
	protected function getRequestBlacklist() {
		return $this->getConfig()->get( MainConfigNames::RemoveCredentialsBlacklist );
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialRemoveCredentials::class, 'SpecialRemoveCredentials' );

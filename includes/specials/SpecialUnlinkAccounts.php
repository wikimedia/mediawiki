<?php

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Session\SessionManager;

class SpecialUnlinkAccounts extends SpecialRedirectToSpecial {
	protected static $allowedActions = [ AuthManager::ACTION_UNLINK ];

	public function __construct() {
		parent::__construct( 'UnlinkAccounts', 'RemoveCredentials' );
	}
}

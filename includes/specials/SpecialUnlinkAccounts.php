<?php

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Session\SessionManager;

class SpecialUnlinkAccounts extends SpecialRedirectToSpecial {
	public function __construct() {
		parent::__construct( 'UnlinkAccounts', 'RemoveCredentials',
			false, [], [ 'unlink' => 1 ] );
	}
}

<?php

namespace MediaWiki\Auth;

use DatabaseBase;

/**
 * Manages a blacklist of users who should not be allowed to log in (but can perform any other
 * action). Such users are typically used for system actions (e.g. maintenance scripts).
 * Meant to be used by PrimaryAccountProvider::providerRevokeAccessForUser().
 */
class AuthBlacklist {
	protected static $instance;

	/** @var DatabaseBase */
	public $db;

	public static function getInstance() {
		if ( !self::$instance ) {
			self::$instance = new self( wfGetDB( DB_MASTER ) );
		}
		return self::$instance;
	}

	protected function __construct( DatabaseBase $db ) {
		$this->db = $db;
	}

	/**
	 * Add user to blacklist
	 * @param string $username
	 */
	public function add( $username ) {
		$this->db->insert( 'auth_blacklist', array( 'ab_name' => $username ), __METHOD__,
			array( 'IGNORE' ) );
	}

	/**
	 * Check if a user is on the blacklist
	 * @param string $username
	 * @return bool
	 */
	public function has( $username ) {
		return !!$this->db->selectRowCount( 'auth_blacklist', '*',
			array( 'ab_name' => $username ), __METHOD__ );
	}
}

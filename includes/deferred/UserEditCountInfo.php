<?php

use MediaWiki\User\UserIdentity;

/**
 * Helper class for UserEditCountUpdate
 * @since 1.38
 */
class UserEditCountInfo {
	/** @var UserIdentity */
	private $user;

	/** @var int */
	private $increment;

	/**
	 * @internal
	 * @param UserIdentity $user
	 * @param int $increment
	 */
	public function __construct( UserIdentity $user, int $increment ) {
		$this->user = $user;
		$this->increment = $increment;
	}

	/**
	 * Merge another UserEditCountInfo into this one
	 *
	 * @param UserEditCountInfo $other
	 */
	public function merge( self $other ) {
		if ( !$this->user->equals( $other->user ) ) {
			throw new InvalidArgumentException( __METHOD__ . ': user does not match' );
		}
		$this->increment += $other->increment;
	}

	/**
	 * @return UserIdentity
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @return int
	 */
	public function getIncrement() {
		return $this->increment;
	}
}

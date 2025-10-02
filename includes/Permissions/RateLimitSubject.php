<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Permissions;

use MediaWiki\User\UserIdentity;

/**
 * Represents the subject that rate limits are applied to.
 *
 * @unstable
 * @since 1.39
 */
class RateLimitSubject {

	/**
	 * @var UserIdentity
	 */
	private $user;

	/**
	 * @var string|null
	 */
	private $ip;

	/**
	 * @var array
	 */
	private $flags;

	/** @var string Flag indicating the user is exempt from rate limits */
	public const EXEMPT = 'exempt';

	/** @var string Flag indicating the user is a newbie */
	public const NEWBIE = 'newbie';

	/**
	 * @internal
	 *
	 * @param UserIdentity $user
	 * @param string|null $ip
	 * @param array<string,bool> $flags
	 */
	public function __construct( UserIdentity $user, ?string $ip, array $flags ) {
		$this->user = $user;
		$this->ip = $ip;
		$this->flags = $flags;
	}

	public function getUser(): UserIdentity {
		return $this->user;
	}

	public function getIP(): ?string {
		return $this->ip;
	}

	/**
	 * Checks whether the given flag applies.
	 *
	 * @param string $flag
	 *
	 * @return bool
	 */
	public function is( string $flag ) {
		return !empty( $this->flags[$flag] );
	}

}

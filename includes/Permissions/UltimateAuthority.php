<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Permissions;

use InvalidArgumentException;
use MediaWiki\Block\Block;
use MediaWiki\Page\PageIdentity;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Represents an authority that has all permissions.
 * This is intended for use in maintenance scripts and tests.
 *
 * @newable
 * @since 1.36
 */
class UltimateAuthority implements Authority {

	/** @var UserIdentity */
	private $actor;

	/** @var bool */
	private $isTemp;

	/**
	 * @stable to call
	 * @param UserIdentity $actor
	 * @param bool $isTemp
	 */
	public function __construct( UserIdentity $actor, $isTemp = false ) {
		$this->actor = $actor;
		$this->isTemp = $isTemp;
	}

	/** @inheritDoc */
	public function getUser(): UserIdentity {
		return $this->actor;
	}

	/** @inheritDoc */
	public function getBlock( int $freshness = IDBAccessObject::READ_NORMAL ): ?Block {
		return null;
	}

	/** @inheritDoc */
	public function isAllowed( string $permission, ?PermissionStatus $status = null ): bool {
		return true;
	}

	/** @inheritDoc */
	public function isAllowedAny( ...$permissions ): bool {
		if ( !$permissions ) {
			throw new InvalidArgumentException( 'At least one permission must be specified' );
		}

		return true;
	}

	/** @inheritDoc */
	public function isAllowedAll( ...$permissions ): bool {
		if ( !$permissions ) {
			throw new InvalidArgumentException( 'At least one permission must be specified' );
		}

		return true;
	}

	/** @inheritDoc */
	public function probablyCan(
		string $action,
		PageIdentity $target,
		?PermissionStatus $status = null
	): bool {
		return true;
	}

	/** @inheritDoc */
	public function definitelyCan(
		string $action,
		PageIdentity $target,
		?PermissionStatus $status = null
	): bool {
		return true;
	}

	/** @inheritDoc */
	public function isDefinitelyAllowed( string $action, ?PermissionStatus $status = null ): bool {
		return true;
	}

	/** @inheritDoc */
	public function authorizeAction( string $action, ?PermissionStatus $status = null ): bool {
		return true;
	}

	/** @inheritDoc */
	public function authorizeRead(
		string $action,
		PageIdentity $target,
		?PermissionStatus $status = null
	): bool {
		return true;
	}

	/** @inheritDoc */
	public function authorizeWrite(
		string $action,
		PageIdentity $target,
		?PermissionStatus $status = null
	): bool {
		return true;
	}

	public function isRegistered(): bool {
		return $this->actor->isRegistered();
	}

	public function isTemp(): bool {
		return $this->isTemp;
	}

	public function isNamed(): bool {
		return $this->isRegistered() && !$this->isTemp();
	}
}

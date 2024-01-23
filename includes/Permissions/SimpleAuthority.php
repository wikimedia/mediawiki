<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Permissions;

use IDBAccessObject;
use InvalidArgumentException;
use MediaWiki\Block\Block;
use MediaWiki\Page\PageIdentity;
use MediaWiki\User\UserIdentity;

/**
 * Represents an authority that has a specific set of permissions
 * which are specified explicitly. This is useful for testing, but
 * may also be used to represent a fixed set of permissions to be
 * used in some context, e.g. in an asynchronous job.
 *
 * @since 1.36
 * @newable
 */
class SimpleAuthority implements Authority {

	/** @var UserIdentity */
	private $actor;

	/** @var bool */
	private $isTemp;

	/** @var true[] permissions (stored in the keys, values are ignored) */
	private $permissions;

	/**
	 * @stable to call
	 * @param UserIdentity $actor
	 * @param string[] $permissions A list of permissions to grant to the actor
	 * @param bool $isTemp Whether the user is auto-created (since 1.39)
	 */
	public function __construct(
		UserIdentity $actor,
		array $permissions,
		bool $isTemp = false
	) {
		$this->actor = $actor;
		$this->isTemp = $isTemp;
		$this->permissions = array_fill_keys( $permissions, true );
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
	public function isAllowed( string $permission, PermissionStatus $status = null ): bool {
		return isset( $this->permissions[ $permission ] );
	}

	/** @inheritDoc */
	public function isAllowedAny( ...$permissions ): bool {
		if ( !$permissions ) {
			throw new InvalidArgumentException( 'At least one permission must be specified' );
		}

		foreach ( $permissions as $perm ) {
			if ( $this->isAllowed( $perm ) ) {
				return true;
			}
		}

		return false;
	}

	/** @inheritDoc */
	public function isAllowedAll( ...$permissions ): bool {
		if ( !$permissions ) {
			throw new InvalidArgumentException( 'At least one permission must be specified' );
		}

		foreach ( $permissions as $perm ) {
			if ( !$this->isAllowed( $perm ) ) {
				return false;
			}
		}

		return true;
	}

	private function checkPermission( string $permission, ?PermissionStatus $status ): bool {
		$ok = $this->isAllowed( $permission );

		if ( !$ok && $status ) {
			// TODO: use a message that at includes the permission name
			$status->fatal( 'permissionserrors' );
			$status->setPermission( $permission );
		}

		return $ok;
	}

	/** @inheritDoc */
	public function probablyCan(
		string $action,
		PageIdentity $target,
		PermissionStatus $status = null
	): bool {
		return $this->checkPermission( $action, $status );
	}

	/** @inheritDoc */
	public function definitelyCan(
		string $action,
		PageIdentity $target,
		PermissionStatus $status = null
	): bool {
		return $this->checkPermission( $action, $status );
	}

	/** @inheritDoc */
	public function isDefinitelyAllowed( string $action, PermissionStatus $status = null ): bool {
		return $this->checkPermission( $action, $status );
	}

	/** @inheritDoc */
	public function authorizeAction( string $action, PermissionStatus $status = null ): bool {
		return $this->checkPermission( $action, $status );
	}

	/** @inheritDoc */
	public function authorizeRead(
		string $action,
		PageIdentity $target,
		PermissionStatus $status = null
	): bool {
		return $this->checkPermission( $action, $status );
	}

	/** @inheritDoc */
	public function authorizeWrite(
		string $action,
		PageIdentity $target,
		PermissionStatus $status = null
	): bool {
		return $this->checkPermission( $action, $status );
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

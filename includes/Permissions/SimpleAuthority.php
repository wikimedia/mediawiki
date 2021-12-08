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

	/** @var true[] permissions (stored in the keys, values are ignored) */
	private $permissions;

	/**
	 * @stable to call
	 * @param UserIdentity $actor
	 * @param string[] $permissions A list of permissions to grant to the actor
	 */
	public function __construct( UserIdentity $actor, array $permissions ) {
		$this->actor = $actor;
		$this->permissions = array_fill_keys( $permissions, true );
	}

	/**
	 * The user identity associated with this authority.
	 *
	 * @return UserIdentity
	 */
	public function getUser(): UserIdentity {
		return $this->actor;
	}

	/**
	 * @param int $freshness
	 *
	 * @return ?Block always null
	 * @since 1.37
	 */
	public function getBlock( int $freshness = self::READ_NORMAL ): ?Block {
		return null;
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $permission
	 *
	 * @return bool
	 */
	public function isAllowed( string $permission ): bool {
		return isset( $this->permissions[ $permission ] );
	}

	/**
	 * @inheritDoc
	 *
	 * @param string ...$permissions
	 *
	 * @return bool
	 */
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

	/**
	 * @inheritDoc
	 *
	 * @param string ...$permissions
	 *
	 * @return bool
	 */
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
		}

		return $ok;
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $action
	 * @param PageIdentity $target
	 * @param PermissionStatus|null $status
	 *
	 * @return bool
	 */
	public function probablyCan(
		string $action,
		PageIdentity $target,
		PermissionStatus $status = null
	): bool {
		return $this->checkPermission( $action, $status );
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $action
	 * @param PageIdentity $target
	 * @param PermissionStatus|null $status
	 *
	 * @return bool
	 */
	public function definitelyCan(
		string $action,
		PageIdentity $target,
		PermissionStatus $status = null
	): bool {
		return $this->checkPermission( $action, $status );
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $action
	 * @param PageIdentity $target
	 * @param PermissionStatus|null $status
	 *
	 * @return bool
	 */
	public function authorizeRead(
		string $action,
		PageIdentity $target,
		PermissionStatus $status = null
	): bool {
		return $this->checkPermission( $action, $status );
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $action
	 * @param PageIdentity $target
	 * @param PermissionStatus|null $status
	 *
	 * @return bool
	 */
	public function authorizeWrite(
		string $action,
		PageIdentity $target,
		PermissionStatus $status = null
	): bool {
		return $this->checkPermission( $action, $status );
	}

}

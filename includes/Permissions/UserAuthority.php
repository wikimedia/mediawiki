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
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentity;
use MediaWiki\User\UserIdentity;
use TitleValue;
use User;

/**
 * Represents the authority of a given User. For anonymous visitors, this will typically
 * allow only basic permissions. For logged in users, permissions are generally based on group
 * membership, but may be adjusted based on things like IP range blocks, OAuth grants, or
 * rate limits.
 *
 * @note This is intended as an intermediate step towards an implementation of Authority that
 * contains much of the logic currently in PermissionManager, and is based directly on
 * WebRequest and Session, rather than a User object. However, for now, code that needs an
 * Authority that reflects the current user and web request should use a User object directly.
 *
 * @unstable
 * @since 1.36
 */
class UserAuthority implements Authority {

	/**
	 * @var PermissionManager
	 */
	private $permissionManager;

	/**
	 * @var User
	 */
	private $actor;

	/**
	 * Local cache for user block information. False is used to indicate that there is no block,
	 * while null indicates that we don't know and have to check.
	 * @var Block|false|null
	 */
	private $userBlock = null;

	/**
	 * @param User $user
	 * @param PermissionManager $permissionManager
	 */
	public function __construct(
		User $user,
		PermissionManager $permissionManager
	) {
		$this->permissionManager = $permissionManager;
		$this->actor = $user;
	}

	/**
	 * @inheritDoc
	 *
	 * @return UserIdentity
	 */
	public function getUser(): UserIdentity {
		return $this->actor;
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $permission
	 *
	 * @return bool
	 */
	public function isAllowed( string $permission ): bool {
		return $this->permissionManager->userHasRight( $this->actor, $permission );
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
		return $this->internalCan(
			PermissionManager::RIGOR_QUICK,
			$action,
			$target,
			$status
		);
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
		// Note that we do not use RIGOR_SECURE to avoid hitting the primary
		// database for read operations. RIGOR_FULL performs the same checks,
		// but is subject to replication lag.
		return $this->internalCan(
			PermissionManager::RIGOR_FULL,
			$action,
			$target,
			$status
		);
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
		// Any side-effects can be added here.

		// Note that we do not use RIGOR_SECURE to avoid hitting the primary
		// database for read operations. RIGOR_FULL performs the same checks,
		// but is subject to replication lag.
		return $this->internalCan(
			PermissionManager::RIGOR_FULL,
			$action,
			$target,
			$status
		);
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
		// Any side-effects can be added here.

		// Note that we need to use RIGOR_SECURE here to ensure that we do not
		// miss a user block or page protection due to replication lag.
		return $this->internalCan(
			PermissionManager::RIGOR_SECURE,
			$action,
			$target,
			$status
		);
	}

	/**
	 * @param string $rigor
	 * @param string $action
	 * @param PageIdentity $target
	 * @param PermissionStatus|null $status
	 *
	 * @return bool
	 */
	private function internalCan(
		string $rigor,
		string $action,
		PageIdentity $target,
		PermissionStatus $status = null
	): bool {
		if ( !( $target instanceof LinkTarget ) ) {
			// FIXME: PermissionManager should accept PageIdentity!
			$target = TitleValue::newFromPage( $target );
		}

		if ( $status ) {
			$errors = $this->permissionManager->getPermissionErrors(
				$action,
				$this->actor,
				$target,
				$rigor
			);

			$blockError = false;
			foreach ( $errors as $err ) {
				$status->fatal( ...$err );

				// HACK: Detect whether the permission was denied because the user is blocked.
				//       A similar hack exists in ApiBase::$blockMsgMap.
				//       When permission checking logic is moved out of PermissionManager,
				//       we can record the block info directly when first checking the block,
				//       rather than doing that here.
				if ( strpos( $err[0], 'blockedtext' ) !== false ) {
					$block = $this->getBlock();

					if ( $block ) {
						$status->setBlock( $block );
					}
				}
			}

			return $status->isOK();
		} else {
			// allow PermissionManager to short-circuit
			return $this->permissionManager->userCan(
				$action,
				$this->actor,
				$target,
				$rigor
			);
		}
	}

	/**
	 * Returns any user block affecting the Authority.
	 *
	 * @param int $freshness
	 *
	 * @return ?Block
	 * @since 1.37
	 *
	 */
	public function getBlock( int $freshness = self::READ_NORMAL ): ?Block {
		// Cache block info, so we don't have to fetch it again unnecessarily.
		if ( $this->userBlock === null || $freshness === self::READ_LATEST ) {
			$this->userBlock = $this->actor->getBlock( $freshness );

			// if we got null back, remember this as "false"
			$this->userBlock = $this->userBlock ?: false;
		}

		// if we remembered "false", return null
		return $this->userBlock ?: null;
	}

}

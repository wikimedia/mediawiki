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
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentity;
use MediaWiki\User\UserIdentity;
use TitleValue;
use WebRequest;

/**
 * Represents the authority of a given web request. For anonymous visitors, this will typically
 * allow only basic permissions. For logged in users, permissions are generally based on group
 * membership, but may be adjusted based on things like IP range blocks, OAuth grants, or
 * rate limits.
 *
 * @unstable
 * @since 1.36
 */
class WebRequestAuthority implements Authority {

	/**
	 * @var PermissionManager
	 */
	private $permissionManager;

	/**
	 * @var UserIdentity
	 */
	private $actor;

	/**
	 * @param WebRequest $request
	 * @param PermissionManager $permissionManager
	 */
	public function __construct(
		WebRequest $request,
		PermissionManager $permissionManager
	) {
		$this->permissionManager = $permissionManager;

		$this->actor = $request->getSession()->getUser();
	}

	/**
	 * @inheritDoc
	 *
	 * @return UserIdentity
	 */
	public function getActor(): UserIdentity {
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
		// Note that we do not use RIGOR_SECURE to avoid hitting the master
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

		// Note that we do not use RIGOR_SECURE to avoid hitting the master
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

			foreach ( $errors as $err ) {
				$status->fatal( ...$err );
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

}

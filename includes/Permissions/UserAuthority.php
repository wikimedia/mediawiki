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
use Wikimedia\DebugInfo\DebugInfoTrait;

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

	use DebugInfoTrait;

	/**
	 * @var PermissionManager
	 * @noVarDump
	 */
	private $permissionManager;

	/**
	 * @var RateLimiter
	 * @noVarDump
	 */
	private $rateLimiter;

	/**
	 * @var User
	 * @noVarDump
	 */
	private $actor;

	/**
	 * Local cache for user block information. False is used to indicate that there is no block,
	 * while null indicates that we don't know and have to check.
	 * @var Block|false|null
	 */
	private $userBlock = null;

	/**
	 * Cache for the outcomes of rate limit checks.
	 * We cache the outcomes primarily so we don't bump the counter multiple times
	 * per request.
	 * @var array<string,array> Map of actions to [ int, bool ] pairs.
	 *      The first element is the increment performed so far (typically 1).
	 *      The second element is the cached outcome of the check (whether the limit was reached)
	 */
	private $limitCache = [];

	/**
	 * Whether the limit cache should be used. Generally, the limit cache should be used in web
	 * requests, since we don't want to bump the same limit more than once per request. It
	 * should not be used during testing, so limits can easily be tested without knowledge
	 * about the caching mechanism.
	 *
	 * @var bool
	 */
	private $useLimitCache;

	/**
	 * @param User $user
	 * @param PermissionManager $permissionManager
	 * @param RateLimiter $rateLimiter
	 */
	public function __construct(
		User $user,
		PermissionManager $permissionManager,
		RateLimiter $rateLimiter
	) {
		$this->permissionManager = $permissionManager;
		$this->actor = $user;
		$this->rateLimiter = $rateLimiter;
		$this->useLimitCache = !defined( 'MW_PHPUNIT_TEST' );
	}

	/**
	 * @internal
	 * @param bool $useLimitCache
	 */
	public function setUseLimitCache( bool $useLimitCache ) {
		$this->useLimitCache = $useLimitCache;
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
			$status,
			false // do not check the rate limit
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
			$status,
			0 // only check the rate limit, don't count it as a hit
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
			$status,
			1 // count a hit towards the rate limit
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
			$status,
			1 // count a hit towards the rate limit
		);
	}

	/**
	 * @param string $rigor
	 * @param string $action
	 * @param PageIdentity $target
	 * @param ?PermissionStatus $status
	 * @param int|false $limitRate False means no check, 0 means check only,
	 *        a non-zero values means check and increment
	 *
	 * @return bool
	 */
	private function internalCan(
		string $rigor,
		string $action,
		PageIdentity $target,
		?PermissionStatus $status,
		$limitRate
	): bool {
		// Check and bump the rate limit.
		if ( $limitRate !== false ) {
			$isLimited = $this->limit( $action, $limitRate, $status );
			if ( $isLimited && !$status ) {
				// bail early if we don't have a status object
				return false;
			}
		}

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
				$status->fatal( wfMessage( ...$err ) );

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
	 * Check whether a rate limit has been exceeded for the given action.
	 *
	 * @see RateLimiter::limit
	 * @internal For use by User::pingLimiter only.
	 *
	 * @param string $action
	 * @param int $incrBy
	 * @param PermissionStatus|null $status
	 *
	 * @return bool
	 */
	public function limit( string $action, int $incrBy, ?PermissionStatus $status ): bool {
		$isLimited = null;

		if ( $this->useLimitCache && isset( $this->limitCache[ $action ] ) ) {
			// subtract the increment that was already applied earlier
			$incrRemaining = $incrBy - $this->limitCache[ $action ][ 0 ];

			// if no increment is left to apply, return the cached outcome
			if ( $incrRemaining < 1 ) {
				$isLimited = $this->limitCache[ $action ][ 1 ];
			}
		} else {
			$incrRemaining = $incrBy;
		}

		if ( $isLimited === null ) {
			// NOTE: Avoid toRateLimitSubject() is possible, for performance
			if ( $this->rateLimiter->isLimitable( $action ) ) {
				$isLimited = $this->rateLimiter->limit(
					$this->actor->toRateLimitSubject(),
					$action,
					$incrRemaining
				);
			} else {
				$isLimited = false;
			}

			// Cache the outcome, so we don't bump the counter twice during the same request.
			$this->limitCache[ $action ] = [ $incrBy, $isLimited ];
		}

		if ( $isLimited && $status ) {
			$status->setRateLimitExceeded();
		}

		return $isLimited;
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

	public function isRegistered(): bool {
		return $this->actor->isRegistered();
	}

	public function isTemp(): bool {
		return $this->actor->isTemp();
	}

	public function isNamed(): bool {
		return $this->actor->isNamed();
	}
}

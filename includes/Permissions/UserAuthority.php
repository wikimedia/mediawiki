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
use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Context\IContextSource;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Request\WebRequest;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;
use Wikimedia\DebugInfo\DebugInfoTrait;
use Wikimedia\Rdbms\IDBAccessObject;

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
	private bool $useLimitCache;

	private WebRequest $request;
	private IContextSource $uiContext;
	private BlockErrorFormatter $blockErrorFormatter;

	/**
	 * @param User $user
	 * @param WebRequest $request
	 * @param IContextSource $uiContext
	 * @param PermissionManager $permissionManager
	 * @param RateLimiter $rateLimiter
	 * @param BlockErrorFormatter $blockErrorFormatter
	 */
	public function __construct(
		User $user,
		WebRequest $request,
		IContextSource $uiContext,
		PermissionManager $permissionManager,
		RateLimiter $rateLimiter,
		BlockErrorFormatter $blockErrorFormatter
	) {
		$this->actor = $user;
		$this->request = $request;
		$this->uiContext = $uiContext;
		$this->permissionManager = $permissionManager;
		$this->rateLimiter = $rateLimiter;
		$this->blockErrorFormatter = $blockErrorFormatter;
		$this->useLimitCache = !defined( 'MW_PHPUNIT_TEST' );
	}

	/**
	 * @internal
	 * @param bool $useLimitCache
	 */
	public function setUseLimitCache( bool $useLimitCache ) {
		$this->useLimitCache = $useLimitCache;
	}

	/** @inheritDoc */
	public function getUser(): UserIdentity {
		return $this->actor;
	}

	/** @inheritDoc */
	public function isAllowed( string $permission, ?PermissionStatus $status = null ): bool {
		return $this->internalAllowed( $permission, $status, false, null );
	}

	/** @inheritDoc */
	public function isAllowedAny( ...$permissions ): bool {
		if ( !$permissions ) {
			throw new InvalidArgumentException( 'At least one permission must be specified' );
		}

		return $this->permissionManager->userHasAnyRight( $this->actor, ...$permissions );
	}

	/** @inheritDoc */
	public function isAllowedAll( ...$permissions ): bool {
		if ( !$permissions ) {
			throw new InvalidArgumentException( 'At least one permission must be specified' );
		}

		return $this->permissionManager->userHasAllRights( $this->actor, ...$permissions );
	}

	/** @inheritDoc */
	public function probablyCan(
		string $action,
		PageIdentity $target,
		?PermissionStatus $status = null
	): bool {
		return $this->internalCan(
			PermissionManager::RIGOR_QUICK,
			$action,
			$target,
			$status,
			false // do not check the rate limit
		);
	}

	/** @inheritDoc */
	public function definitelyCan(
		string $action,
		PageIdentity $target,
		?PermissionStatus $status = null
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

	/** @inheritDoc */
	public function isDefinitelyAllowed( string $action, ?PermissionStatus $status = null ): bool {
		$userBlock = $this->getApplicableBlock( PermissionManager::RIGOR_FULL, $action );
		return $this->internalAllowed( $action, $status, 0, $userBlock );
	}

	/** @inheritDoc */
	public function authorizeAction(
		string $action,
		?PermissionStatus $status = null
	): bool {
		// Any side-effects can be added here.

		$userBlock = $this->getApplicableBlock( PermissionManager::RIGOR_SECURE, $action );

		return $this->internalAllowed(
			$action,
			$status,
			1,
			$userBlock
		);
	}

	/** @inheritDoc */
	public function authorizeRead(
		string $action,
		PageIdentity $target,
		?PermissionStatus $status = null
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

	/** @inheritDoc */
	public function authorizeWrite(
		string $action,
		PageIdentity $target,
		?PermissionStatus $status = null
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
	 * Check whether the user is allowed to perform the action, taking into account
	 * the user's block status as well as any rate limits.
	 *
	 * @param string $action
	 * @param PermissionStatus|null $status
	 * @param int|false $limitRate False means no check, 0 means check only,
	 *        and 1 means check and increment
	 * @param ?Block $userBlock
	 *
	 * @return bool
	 */
	private function internalAllowed(
		string $action,
		?PermissionStatus $status,
		$limitRate,
		?Block $userBlock
	): bool {
		if ( $status ) {
			Assert::precondition(
				$status->isGood(),
				'The PermissionStatus passed as $status parameter must still be good'
			);
		}

		if ( !$this->permissionManager->userHasRight( $this->actor, $action ) ) {
			if ( !$status ) {
				return false;
			}

			$status->setPermission( $action );
			$status->merge(
				$this->permissionManager->newFatalPermissionDeniedStatus(
					$action,
					$this->uiContext
				)
			);
		}

		if ( $userBlock ) {
			if ( !$status ) {
				return false;
			}

			$messages = $this->blockErrorFormatter->getMessages(
				$userBlock,
				$this->actor,
				$this->request->getIP()
			);

			$status->setPermission( $action );
			foreach ( $messages as $message ) {
				$status->fatal( $message );
			}
		}

		// Check and bump the rate limit.
		if ( $limitRate !== false ) {
			$isLimited = $this->limit( $action, $limitRate, $status );
			if ( $isLimited && !$status ) {
				return false;
			}
		}

		return !$status || $status->isOK();
	}

	// See ApiBase::BLOCK_CODE_MAP
	private const BLOCK_CODES = [
		'blockedtext',
		'blockedtext-partial',
		'autoblockedtext',
		'systemblockedtext',
		'blockedtext-composite',
		'blockedtext-tempuser',
		'autoblockedtext-tempuser',
	];

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
			// TODO: PermissionManager should accept PageIdentity!
			$target = TitleValue::newFromPage( $target );
		}

		if ( $status ) {
			$status->setPermission( $action );

			$tempStatus = $this->permissionManager->getPermissionStatus(
				$action,
				$this->actor,
				$target,
				$rigor
			);

			if ( $tempStatus->isGood() ) {
				// Nothing to merge, return early
				return $status->isOK();
			}

			// Instead of `$status->merge( $tempStatus )`, process the messages like this to ensure that
			// the resulting status contains Message objects instead of strings+arrays, and thus does not
			// trigger wikitext escaping in a legacy code path. See T368821 for more information about
			// that behavior, and see T306494 for the specific bug this fixes.
			foreach ( $tempStatus->getMessages() as $msg ) {
				$status->fatal( $msg );
			}

			foreach ( self::BLOCK_CODES as $code ) {
				// HACK: Detect whether the permission was denied because the user is blocked.
				//       A similar hack exists in ApiBase::BLOCK_CODE_MAP.
				//       When permission checking logic is moved out of PermissionManager,
				//       we can record the block info directly when first checking the block,
				//       rather than doing that here.
				if ( $tempStatus->hasMessage( $code ) ) {
					$block = $this->getBlock();
					if ( $block ) {
						$status->setBlock( $block );
					}
					break;
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
			// NOTE: Avoid toRateLimitSubject() if possible, for performance
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

	/** @inheritDoc */
	public function getBlock( int $freshness = IDBAccessObject::READ_NORMAL ): ?Block {
		// Cache block info, so we don't have to fetch it again unnecessarily.
		if ( $this->userBlock === null || $freshness === IDBAccessObject::READ_LATEST ) {
			$this->userBlock = $this->actor->getBlock( $freshness );

			// if we got null back, remember this as "false"
			$this->userBlock = $this->userBlock ?: false;
		}

		// if we remembered "false", return null
		return $this->userBlock ?: null;
	}

	private function getApplicableBlock(
		string $rigor,
		string $action,
		?PageIdentity $target = null
	): ?Block {
		// NOTE: We follow the parameter order of internalCan here.
		//       It doesn't match the one in PermissionManager.
		return $this->permissionManager->getApplicableBlock(
			$action,
			$this->actor,
			$rigor,
			$target,
			$this->request
		);
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

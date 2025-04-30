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

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use Psr\Log\LoggerInterface;
use Wikimedia\IPUtils;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\WRStats\LimitCondition;
use Wikimedia\WRStats\WRStatsFactory;

/**
 * Provides rate limiting for a set of actions based on several counter
 * buckets.
 *
 * @since 1.39
 */
class RateLimiter {

	private LoggerInterface $logger;
	private StatsFactory $statsFactory;

	private ServiceOptions $options;
	private WRStatsFactory $wrstatsFactory;
	private ?CentralIdLookup $centralIdLookup;
	private UserFactory $userFactory;
	private UserGroupManager $userGroupManager;
	private HookContainer $hookContainer;
	private HookRunner $hookRunner;

	/** @var array */
	private $rateLimits;

	/**
	 * Actions that are exempt from all rate limiting.
	 *
	 * Actions listed here will bypass all rate limiting,
	 * including limits implemented in hooks.
	 *
	 * This serves as a performance optimization, to avoid overhead for actions
	 * that are performed a lot and have no need to be limited.
	 *
	 * @note This is currently hard-coded to contain just the 'read' action.
	 * It can be made configurable to extended to include more actions if needed.
	 *
	 * @var array<string,bool>
	 */
	private array $nonLimitableActions = [
		'read' => true,
	];

	/**
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::RateLimits,
		MainConfigNames::RateLimitsExcludedIPs,
	];

	public function __construct(
		ServiceOptions $options,
		WRStatsFactory $wrstatsFactory,
		?CentralIdLookup $centralIdLookup,
		UserFactory $userFactory,
		UserGroupManager $userGroupManager,
		HookContainer $hookContainer
	) {
		$this->logger = LoggerFactory::getInstance( 'ratelimit' );
		$this->statsFactory = StatsFactory::newNull();

		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->wrstatsFactory = $wrstatsFactory;
		$this->centralIdLookup = $centralIdLookup;
		$this->userFactory = $userFactory;
		$this->userGroupManager = $userGroupManager;
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );

		$this->rateLimits = $this->options->get( MainConfigNames::RateLimits );
	}

	public function setStats( StatsFactory $statsFactory ) {
		$this->statsFactory = $statsFactory;
	}

	/**
	 * Is this user exempt from rate limiting?
	 *
	 * @param RateLimitSubject $subject The subject of the rate limit, representing the
	 *        client performing the action.
	 *
	 * @return bool
	 */
	public function isExempt( RateLimitSubject $subject ) {
		$rateLimitsExcludedIPs = $this->options->get( MainConfigNames::RateLimitsExcludedIPs );

		$ip = $subject->getIP();
		if ( $ip && IPUtils::isInRanges( $ip, $rateLimitsExcludedIPs ) ) {
			return true;
		}

		// NOTE: To avoid circular dependencies, we rely on a flag here rather than using an
		//       Authority instance to check the permission. Using PermissionManager might work,
		//       but keeping cross-dependencies to a minimum seems best. The code that constructs
		//       the RateLimitSubject should know where to get the relevant info.
		return $subject->is( RateLimitSubject::EXEMPT );
	}

	/**
	 * Checks whether the given action may be limited.
	 * Can be used for optimization, to avoid calling limit() if we can know in advance that no limit will apply.
	 *
	 * @param string $action
	 *
	 * @return bool
	 */
	public function isLimitable( $action ) {
		// Bypass limit checks for actions that are defined to be non-limitable.
		// This is a performance optimization.
		if ( $this->nonLimitableActions[$action] ?? false ) {
			return false;
		}

		if ( isset( $this->rateLimits[$action] ) ) {
			return true;
		}

		if ( $this->hookContainer->isRegistered( 'PingLimiter' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Implements simple rate limits: enforce maximum actions per time period
	 * to put a brake on flooding.
	 *
	 * @note This method will always return false for any action listed in
	 *       $this->nonLimitableActions. This allows rate limit checks to
	 *       be bypassed for certain actions to avoid overhead and improve
	 *       performance.
	 *
	 * @param RateLimitSubject $subject The subject of the rate limit, representing the
	 *        client performing the action.
	 * @param string $action Action to enforce
	 * @param int $incrBy Positive amount to increment counter by, 1 by default.
	 *        Use 0 to check the limit without bumping the counter.
	 *
	 * @return bool True if a rate limit was exceeded.
	 */
	public function limit( RateLimitSubject $subject, string $action, int $incrBy = 1 ) {
		// Bypass limit checks for actions that are defined to be non-limitable.
		// This is a performance optimization.
		if ( $this->nonLimitableActions[$action] ?? false ) {
			return false;
		}
		$actionMetric = $this->statsFactory->getCounter( 'RateLimiter_limit_actions_total' )
			->setLabel( 'action', $action );

		$user = $subject->getUser();
		$ip = $subject->getIP();

		// Call the 'PingLimiter' hook
		$result = false;
		$legacyUser = $this->userFactory->newFromUserIdentity( $user );
		if ( !$this->hookRunner->onPingLimiter( $legacyUser, $action, $result, $incrBy ) ) {
			$statsResult = ( $result ? 'tripped_by_hook' : 'passed_by_hook' );
			$actionMetric->setLabel( 'result', $statsResult )
				->copyToStatsdAt( "RateLimiter.limit.$action.result." . $statsResult )
				->increment();
			return $result;
		}

		if ( !isset( $this->rateLimits[$action] ) ) {
			return false;
		}

		// Some groups shouldn't trigger the ping limiter, ever
		if ( $this->canBypass( $action ) && $this->isExempt( $subject ) ) {
			$actionMetric->setLabel( 'result', 'exempt' )
				->copyToStatsdAt( "RateLimiter.limit.$action.result.exempt" )
				->increment();
			return false;
		}

		$conds = $this->getConditions( $action );
		$limiter = $this->wrstatsFactory->createRateLimiter( $conds, [ 'limiter', $action ] );
		$peekMode = $incrBy === 0;
		$limitBatch = $limiter->createBatch( $incrBy ?: 1 );
		$this->logger->debug( __METHOD__ . ": limiting $action rate for {$user->getName()}" );

		$id = $user->getId();
		$isNewbie = $subject->is( RateLimitSubject::NEWBIE );

		if ( $id == 0 ) {
			// "shared anon" limit, for all anons combined
			if ( isset( $conds['anon'] ) ) {
				$limitBatch->localOp( 'anon', [] );
			}
		} else {
			// "global per name" limit, across sites
			if ( isset( $conds['user-global'] ) ) {

				$centralId = $this->centralIdLookup
					? $this->centralIdLookup->centralIdFromLocalUser( $user, CentralIdLookup::AUDIENCE_RAW )
					: 0;

				if ( $centralId ) {
					// We don't have proper realms, use provider ID.
					$realm = $this->centralIdLookup->getProviderId();
					$limitBatch->globalOp( 'user-global', [ $realm, $centralId ] );
				} else {
					// Fall back to a local key for a local ID
					$limitBatch->localOp( 'user-global', [ 'local', $id ] );
				}
			}
		}

		if ( $isNewbie && $ip ) {
			// "per ip" limit for anons and newbie users
			if ( isset( $conds['ip'] ) ) {
				$limitBatch->globalOp( 'ip', $ip );
			}
			// "per subnet" limit for anons and newbie users
			if ( isset( $conds['subnet'] ) ) {
				$subnet = IPUtils::getSubnet( $ip );
				if ( $subnet !== false ) {
					$limitBatch->globalOp( 'subnet', $subnet );
				}
			}
		}

		// determine the "per user account" limit
		$userEntityType = false;
		if ( $id !== 0 && isset( $conds['user'] ) ) {
			// default limit for logged-in users
			$userEntityType = 'user';
		}
		// limits for newbie logged-in users (overrides all the normal user limits)
		if ( $id !== 0 && $isNewbie && isset( $conds['newbie'] ) ) {
			$userEntityType = 'newbie';
		} else {
			// Check for group-specific limits
			// If more than one group applies, use the highest allowance (if higher than the default)
			$userGroups = $this->userGroupManager->getUserGroups( $user );
			foreach ( $userGroups as $group ) {
				if ( isset( $conds[$group] ) ) {
					if ( $userEntityType === false
						|| $conds[$group]->perSecond() > $conds[$userEntityType]->perSecond()
					) {
						$userEntityType = $group;
					}
				}
			}
		}

		// Set the user limit key
		if ( $userEntityType !== false ) {
			$limitBatch->localOp( $userEntityType, $id );
		}

		// ip-based limits for all ping-limitable users
		if ( isset( $conds['ip-all'] ) && $ip ) {
			// ignore if user limit is more permissive
			if ( $isNewbie || $userEntityType === false
				|| $conds['ip-all']->perSecond() > $conds[$userEntityType]->perSecond()
			) {
				$limitBatch->globalOp( 'ip-all', $ip );
			}
		}

		// subnet-based limits for all ping-limitable users
		if ( isset( $conds['subnet-all'] ) && $ip ) {
			$subnet = IPUtils::getSubnet( $ip );
			if ( $subnet !== false ) {
				// ignore if user limit is more permissive
				if ( $isNewbie || $userEntityType === false
					|| $conds['ip-all']->perSecond() > $conds[$userEntityType]->perSecond()
				) {
					$limitBatch->globalOp( 'subnet-all', $subnet );
				}
			}
		}

		$loggerInfo = [
			'name' => $user->getName(),
			'ip' => $ip,
		];

		$batchResult = $peekMode ? $limitBatch->peek() : $limitBatch->tryIncr();
		$failedMetric = $this->statsFactory->getCounter( 'RateLimiter_limit_cause_total' )
			->setLabel( 'action', $action );
		foreach ( $batchResult->getFailedResults() as $type => $result ) {
			$this->logger->info(
				'User::pingLimiter: User tripped rate limit',
				[
					'action' => $action,
					'limit' => $result->condition->limit,
					'period' => $result->condition->window,
					'count' => $result->prevTotal,
					'key' => $type
				] + $loggerInfo
			);
			$failedMetric->setLabel( 'tripped_by', $type )
				->copyToStatsdAt( "RateLimiter.limit.$action.tripped_by.$type" )
				->increment();
		}

		$allowed = $batchResult->isAllowed();

		$actionMetric->setLabel( 'result', ( $allowed ? 'passed' : 'tripped' ) )
			->copyToStatsdAt( "RateLimiter.limit.$action.result." . ( $allowed ? 'passed' : 'tripped' ) )
			->increment();

		return !$allowed;
	}

	private function canBypass( string $action ): bool {
		return $this->rateLimits[$action]['&can-bypass'] ?? true;
	}

	/**
	 * @param string $action
	 * @return array<string,LimitCondition>
	 */
	private function getConditions( $action ) {
		if ( !isset( $this->rateLimits[$action] ) ) {
			return [];
		}
		$conds = [];
		foreach ( $this->rateLimits[$action] as $entityType => $limitInfo ) {
			if ( $entityType[0] === '&' ) {
				continue;
			}
			[ $limit, $window ] = $limitInfo;
			$conds[$entityType] = new LimitCondition(
				$limit,
				$window
			);
		}
		return $conds;
	}

}

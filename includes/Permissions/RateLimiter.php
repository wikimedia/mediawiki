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

use BagOStuff;
use CentralIdLookup;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use MWTimestamp;
use Psr\Log\LoggerInterface;
use Wikimedia\IPUtils;

/**
 * Provides rate limiting for a set of actions based on severa counter
 * buckets.
 *
 * @since 1.39
 */
class RateLimiter {

	/** @var LoggerInterface */
	private $logger;

	/** @var BagOStuff */
	private $store;

	/** @var ServiceOptions */
	private $options;

	/** @var array */
	private $rateLimits;

	/** @var HookRunner */
	private $hookRunner;

	/** @var CentralIdLookup|null */
	private $centralIdLookup;

	/** @var UserGroupManager */
	private $userGroupManager;

	/** @var UserFactory */
	private $userFactory;

	/**
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::RateLimits,
		MainConfigNames::RateLimitsExcludedIPs,
	];

	/**
	 * @param ServiceOptions $options
	 * @param BagOStuff $store
	 * @param CentralIdLookup|null $centralIdLookup
	 * @param UserFactory $userFactory
	 * @param UserGroupManager $userGroupManager
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		ServiceOptions $options,
		BagOStuff $store,
		?CentralIdLookup $centralIdLookup,
		UserFactory $userFactory,
		UserGroupManager $userGroupManager,
		HookContainer $hookContainer
	) {
		$this->logger = LoggerFactory::getInstance( 'ratelimit' );

		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->store = $store;
		$this->centralIdLookup = $centralIdLookup;
		$this->userFactory = $userFactory;
		$this->userGroupManager = $userGroupManager;
		$this->hookRunner = new HookRunner( $hookContainer );

		$this->rateLimits = $this->options->get( MainConfigNames::RateLimits );
	}

	private function makeGlobalKey( $action, ...$components ): string {
		return $this->store->makeGlobalKey( 'limiter', $action, ...$components );
	}

	private function makeLocalKey( $action, ...$components ): string {
		return $this->store->makeKey( 'limiter', $action, ...$components );
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
	 * Implements simple rate limits: enforce maximum actions per time period
	 * to put a brake on flooding.
	 *
	 * @param RateLimitSubject $subject The subject of the rate limit, representing the
	 *        client performing the action.
	 * @param string $action Action to enforce
	 * @param int $incrBy Positive amount to increment counter by, 1 per default.
	 *        Use 0 to check the limit without bumping the counter.
	 *
	 * @return bool True if a rate limit as exceeded.
	 */
	public function limit( RateLimitSubject $subject, string $action, int $incrBy = 1 ) {
		$user = $subject->getUser();
		$ip = $subject->getIP();

		// Call the 'PingLimiter' hook
		$result = false;
		$legacyUser = $this->userFactory->newFromUserIdentity( $user );
		if ( !$this->hookRunner->onPingLimiter( $legacyUser, $action, $result, $incrBy ) ) {
			return $result;
		}

		if ( !isset( $this->rateLimits[$action] ) ) {
			return false;
		}

		$limits = array_merge(
			[ '&can-bypass' => true ],
			$this->rateLimits[$action]
		);

		// Some groups shouldn't trigger the ping limiter, ever
		if ( $limits['&can-bypass'] && $this->isExempt( $subject ) ) {
			return false;
		}

		$this->logger->debug( __METHOD__ . ": limiting $action rate for {$user->getName()}" );

		$keys = [];
		$id = $user->getId();
		$isNewbie = $subject->is( RateLimitSubject::NEWBIE );

		if ( $id == 0 ) {
			// "shared anon" limit, for all anons combined
			if ( isset( $limits['anon'] ) ) {
				$keys[$this->makeLocalKey( $action, 'anon' )] = $limits['anon'];
			}
		} else {
			// "global per name" limit, across sites
			if ( isset( $limits['user-global'] ) ) {

				$centralId = $this->centralIdLookup
					? $this->centralIdLookup->centralIdFromLocalUser( $user, CentralIdLookup::AUDIENCE_RAW )
					: 0;

				if ( $centralId ) {
					// We don't have proper realms, use provider ID.
					$realm = $this->centralIdLookup->getProviderId();

					$globalKey = $this->makeGlobalKey( $action, 'user-global', $realm, $centralId );
				} else {
					// Fall back to a local key for a local ID
					$globalKey = $this->makeLocalKey( $action, 'user-global', 'local', $id );
				}
				$keys[$globalKey] = $limits['user-global'];
			}
		}

		if ( $isNewbie && $ip ) {
			// "per ip" limit for anons and newbie users
			if ( isset( $limits['ip'] ) ) {
				$keys[$this->makeGlobalKey( $action, 'ip', $ip )] = $limits['ip'];
			}
			// "per subnet" limit for anons and newbie users
			if ( isset( $limits['subnet'] ) ) {
				$subnet = IPUtils::getSubnet( $ip );
				if ( $subnet !== false ) {
					$keys[$this->makeGlobalKey( $action, 'subnet', $subnet )] = $limits['subnet'];
				}
			}
		}

		// determine the "per user account" limit
		$userLimit = false;
		if ( $id !== 0 && isset( $limits['user'] ) ) {
			// default limit for logged-in users
			$userLimit = $limits['user'];
		}
		// limits for newbie logged-in users (overrides all the normal user limits)
		if ( $id !== 0 && $isNewbie && isset( $limits['newbie'] ) ) {
			$userLimit = $limits['newbie'];
		} else {
			// Check for group-specific limits
			// If more than one group applies, use the highest allowance (if higher than the default)
			$userGroups = $this->userGroupManager->getUserGroups( $user );
			foreach ( $userGroups as $group ) {
				if ( isset( $limits[$group] ) ) {
					if ( $userLimit === false
						// @phan-suppress-next-line PhanTypeArraySuspicious False positive
						|| $limits[$group][0] / $limits[$group][1] > $userLimit[0] / $userLimit[1]
					) {
						$userLimit = $limits[$group];
					}
				}
			}
		}

		// Set the user limit key
		if ( $userLimit !== false ) {
			// phan is confused because &can-bypass's value is a bool, so it assumes
			// that $userLimit is also a bool here.
			// @phan-suppress-next-line PhanTypeInvalidExpressionArrayDestructuring
			[ $max, $period ] = $userLimit;
			$this->logger->debug( __METHOD__ . ": effective user limit: $max in {$period}s" );
			$keys[$this->makeLocalKey( $action, 'user', $id )] = $userLimit;
		}

		// ip-based limits for all ping-limitable users
		if ( isset( $limits['ip-all'] ) && $ip ) {
			// ignore if user limit is more permissive
			if ( $isNewbie || $userLimit === false
				// @phan-suppress-next-line PhanTypeArraySuspicious False positive
				|| $limits['ip-all'][0] / $limits['ip-all'][1] > $userLimit[0] / $userLimit[1] ) {
				$keys[$this->makeGlobalKey( $action, 'ip-all', $ip )] = $limits['ip-all'];
			}
		}

		// subnet-based limits for all ping-limitable users
		if ( isset( $limits['subnet-all'] ) && $ip ) {
			$subnet = IPUtils::getSubnet( $ip );
			if ( $subnet !== false ) {
				// ignore if user limit is more permissive
				if ( $isNewbie || $userLimit === false
					// @phan-suppress-next-line PhanTypeArraySuspicious False positive
					|| $limits['ip-all'][0] / $limits['ip-all'][1]
					// @phan-suppress-next-line PhanTypeArraySuspicious False positive
					> $userLimit[0] / $userLimit[1] ) {
					$keys[$this->makeGlobalKey( $action, 'subnet-all', $subnet )] = $limits['subnet-all'];
				}
			}
		}

		$loggerInfo = [
			'name' => $user->getName(),
			'ip' => $ip,
		];

		return $this->checkLimits( $action, $incrBy, $keys, $loggerInfo );
	}

	private function checkLimits( string $action, int $incrBy, array $limits, array $loggerInfo ): bool {
		// XXX: We may want to use $this->store->getCurrentTime() here, but that would make it
		//      harder to test for T246991. Also $this->store->getCurrentTime() is documented
		//      as being for testing only, so it apparently should not be called here.
		$now = MWTimestamp::time();
		$clockFudge = 3; // avoid log spam when a clock is slightly off

		$triggered = false;
		foreach ( $limits as $key => $limit ) {

			// Do the update in a merge callback, for atomicity.
			// To use merge(), we need to explicitly track the desired expiry timestamp.
			// This tracking was introduced to investigate T246991. Once it is no longer needed,
			// we could go back to incrWithInit(), though that has more potential for race
			// conditions between the get() and incrWithInit() calls.
			$this->store->merge(
				$key,
				function ( $store, $key, $data, &$expiry )
				use ( $action, &$triggered, $loggerInfo, $now, $clockFudge, $limit, $incrBy )
				{
					// phan is confused because &can-bypass's value is a bool, so it assumes
					// that $userLimit is also a bool here.
					[ $max, $period ] = $limit;

					$expiry = $now + (int)$period;
					$count = 0;

					// Already pinged?
					if ( $data ) {
						// NOTE: in order to investigate T246991, we write the expiry time
						//       into the payload, along with the count.
						$fields = explode( '|', $data );
						$storedCount = (int)( $fields[0] ?? 0 );
						$storedExpiry = (int)( $fields[1] ?? PHP_INT_MAX );

						// Found a stale entry. This should not happen!
						if ( $storedExpiry < ( $now + $clockFudge ) ) {
							$this->logger->info(
								'User::pingLimiter: '
								. 'Stale rate limit entry, cache key failed to expire (T246991)',
								[
									'action' => $action,
									'limit' => $max,
									'period' => $period,
									'count' => $storedCount,
									'key' => $key,
									'expiry' => MWTimestamp::convert( TS_DB, $storedExpiry ),
								] + $loggerInfo
							);
						} else {
							// NOTE: We'll keep the original expiry when bumping counters,
							//       resulting in a kind of fixed-window throttle.
							$expiry = min( $storedExpiry, $now + (int)$period );
							$count = $storedCount;
						}
					}

					// Limit exceeded!
					if ( $count >= $max ) {
						if ( !$triggered ) {
							$this->logger->info(
								'User::pingLimiter: User tripped rate limit',
								[
									'action' => $action,
									'limit' => $max,
									'period' => $period,
									'count' => $count,
									'key' => $key
								] + $loggerInfo
							);
						}

						$triggered = true;
					}

					$count += $incrBy;
					$data = "$count|$expiry";
					return $data;
				}
			);
		}

		return $triggered;
	}

}

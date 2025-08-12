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

namespace MediaWiki\Auth;

use InvalidArgumentException;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Wikimedia\ObjectCache\BagOStuff;

/**
 * A helper class for throttling authentication attempts.
 *
 * @ingroup Auth
 * @since 1.27
 */
class Throttler implements LoggerAwareInterface {

	/** @var string */
	protected $type;

	/**
	 * See documentation of $wgPasswordAttemptThrottle for format. Old (pre-1.27) format is not
	 * allowed here.
	 * @var array[]
	 * @see https://www.mediawiki.org/wiki/Manual:$wgPasswordAttemptThrottle
	 */
	protected $conditions;

	/** @var int|float */
	protected $warningLimit;

	protected BagOStuff $cache;
	protected LoggerInterface $logger;
	private HookRunner $hookRunner;

	/**
	 * @param array|null $conditions An array of arrays describing throttling conditions.
	 *     Defaults to $wgPasswordAttemptThrottle. See documentation of that variable for format.
	 * @param array $params Parameters (all optional):
	 *   - type: throttle type, used as a namespace for counters,
	 *   - cache: a BagOStuff object where throttle counters are stored.
	 *   - warningLimit: the log level will be raised to warning when rejecting an attempt after
	 *     no less than this many failures.
	 */
	public function __construct( ?array $conditions = null, array $params = [] ) {
		$invalidParams = array_diff_key( $params,
			array_fill_keys( [ 'type', 'cache', 'warningLimit' ], true ) );
		if ( $invalidParams ) {
			throw new InvalidArgumentException( 'unrecognized parameters: '
				. implode( ', ', array_keys( $invalidParams ) ) );
		}

		$services = MediaWikiServices::getInstance();
		$this->hookRunner = new HookRunner( $services->getHookContainer() );

		$objectCacheFactory = $services->getObjectCacheFactory();

		if ( $conditions === null ) {
			$config = $services->getMainConfig();
			$conditions = $config->get( MainConfigNames::PasswordAttemptThrottle );
			$params += [
				'type' => 'password',
				'cache' => $objectCacheFactory->getLocalClusterInstance(),
				'warningLimit' => 50,
			];
		} else {
			$params += [
				'type' => 'custom',
				'cache' => $objectCacheFactory->getLocalClusterInstance(),
				'warningLimit' => INF,
			];
		}

		$this->type = $params['type'];
		$this->conditions = static::normalizeThrottleConditions( $conditions );
		$this->cache = $params['cache'];
		$this->warningLimit = $params['warningLimit'];

		$this->setLogger( LoggerFactory::getInstance( 'throttler' ) );
	}

	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	/**
	 * Increase the throttle counter and return whether the attempt should be throttled.
	 *
	 * Should be called before an authentication attempt.
	 *
	 * @param string|null $username
	 * @param string|null $ip
	 * @param string|null $caller The authentication method from which we were called.
	 * @return array|false False if the attempt should not be throttled, an associative array
	 *   with three keys otherwise:
	 *   - throttleIndex: which throttle condition was met (a key of the conditions array)
	 *   - count: throttle count (ie. number of failed attempts)
	 *   - wait: time in seconds until authentication can be attempted
	 */
	public function increase( $username = null, $ip = null, $caller = null ) {
		if ( $username === null && $ip === null ) {
			throw new InvalidArgumentException( 'Either username or IP must be set for throttling' );
		}

		$userKey = $username ? md5( $username ) : null;
		foreach ( $this->conditions as $index => $throttleCondition ) {
			$ipKey = isset( $throttleCondition['allIPs'] ) ? null : $ip;
			$count = $throttleCondition['count'];
			$expiry = $throttleCondition['seconds'];

			// a limit of 0 is used as a disable flag in some throttling configuration settings
			// throttling the whole world is probably a bad idea
			if ( !$count || ( $userKey === null && $ipKey === null ) ) {
				continue;
			}

			$throttleKey = $this->getThrottleKey( $this->type, $index, $ipKey, $userKey );
			$throttleCount = $this->cache->get( $throttleKey );
			if ( $throttleCount && $throttleCount >= $count ) {
				// Throttle limited reached
				$this->logRejection( [
					'throttle' => $this->type,
					'index' => $index,
					'ipKey' => $ipKey,
					'username' => $username,
					'count' => $count,
					'expiry' => $expiry,
					// @codeCoverageIgnoreStart
					'method' => $caller ?: __METHOD__,
					// @codeCoverageIgnoreEnd
				] );

				// Allow extensions to perform actions when a throttle causes throttling.
				$this->hookRunner->onAuthenticationAttemptThrottled( $this->type, $username, $ip );

				return [ 'throttleIndex' => $index, 'count' => $count, 'wait' => $expiry ];
			} else {
				$this->cache->incrWithInit( $throttleKey, $expiry, 1 );
			}
		}

		return false;
	}

	/**
	 * Clear the throttle counter.
	 *
	 * Should be called after a successful authentication attempt.
	 *
	 * @param string|null $username
	 * @param string|null $ip
	 */
	public function clear( $username = null, $ip = null ) {
		$userKey = $username ? md5( $username ) : null;
		foreach ( $this->conditions as $index => $specificThrottle ) {
			$ipKey = isset( $specificThrottle['allIPs'] ) ? null : $ip;
			$throttleKey = $this->getThrottleKey( $this->type, $index, $ipKey, $userKey );
			$this->cache->delete( $throttleKey );
		}
	}

	/**
	 * Construct a cache key for the throttle counter
	 * @param string $type
	 * @param int $index
	 * @param string|null $ipKey
	 * @param string|null $userKey
	 * @return string
	 */
	private function getThrottleKey( string $type, int $index, ?string $ipKey, ?string $userKey ): string {
		return $this->cache->makeGlobalKey(
			'throttler',
			$type,
			$index,
			$ipKey ?? '',
			$userKey ?? ''
		);
	}

	/**
	 * Handles B/C for $wgPasswordAttemptThrottle.
	 * @param array $throttleConditions
	 * @return array[]
	 * @see $wgPasswordAttemptThrottle for structure
	 */
	protected static function normalizeThrottleConditions( $throttleConditions ) {
		if ( !is_array( $throttleConditions ) ) {
			return [];
		}
		if ( isset( $throttleConditions['count'] ) ) { // old style
			$throttleConditions = [ $throttleConditions ];
		}
		return $throttleConditions;
	}

	protected function logRejection( array $context ) {
		$logMsg = 'Throttle {throttle} hit, throttled for {expiry} seconds due to {count} attempts '
			. 'from username {username} and IP {ipKey}';

		// If we are hitting a throttle for >= warningLimit attempts, it is much more likely to be
		// an attack than someone simply forgetting their password, so log it at a higher level.
		$level = $context['count'] >= $this->warningLimit ? LogLevel::WARNING : LogLevel::INFO;

		// It should be noted that once the throttle is hit, every attempt to login will
		// generate the log message until the throttle expires, not just the attempt that
		// puts the throttle over the top.
		$this->logger->log( $level, $logMsg, $context );
	}

}

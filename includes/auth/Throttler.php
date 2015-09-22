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
 * @ingroup Auth
 */

namespace MediaWiki\Auth;

use BagOStuff;
use Config;
use MediaWiki\Logger\LoggerFactory;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use User;

/**
 * A helper class for throttling authentication attempts.
 * @package MediaWiki\Auth
 * @ingroup Auth
 * @since 1.27
 */
class Throttler implements LoggerAwareInterface {
	/** @var Throttler */
	protected static $instance;

	/** @var BagOStuff */
	protected $cache;
	/** @var Config */
	protected $config;
	/** @var LoggerInterface */
	protected $logger;

	public static function getInstance() {
		if ( !self::$instance ) {
			self::$instance = new static(
				\ObjectCache::getLocalClusterInstance(),
				\ConfigFactory::getDefaultInstance()->makeConfig( 'main' )
			);
			self::$instance->setLogger( LoggerFactory::getInstance( 'password-throttle' ) );
		}
		return self::$instance;
	}

	public function __construct( BagOStuff $cache, Config $config ) {
		$this->cache = $cache;
		$this->config = $config;
	}

	/**
	 * @param LoggerInterface $logger
	 * @return null
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Increase the throttle counter and return whether the attempt should be throttled.
	 *
	 * Should be called before an authentication attempt.
	 *
	 * @param string $username
	 * @param string $ip
	 * @param string $caller The authentication method from which we were called.
	 * @return array|bool False if the attempt should not be throttled, an associative array
	 *   with three keys otherwise:
	 *   - throttleIndex: which throttle condition was met (an array key of PasswordAttemptThrottle)
	 *   - count: throttle count (ie. number of failed attempts)
	 *   - wait: time in seconds until authentication can be attempted
	 */
	public function increase( $username, $ip = null, $caller = null ) {
		$ip = $ip ?: 'All';
		foreach ( $this->getThrottleConditions() as $index => $throttleCondition ) {
			$ipOrAll = isset( $throttleCondition['allIPs'] ) ? 'All' : $ip;
			$count = $throttleCondition['count'];
			$period = $throttleCondition['seconds'];

			$throttleKey = wfGlobalCacheKey( 'password-throttle', $index, $ipOrAll, md5( $username ) );
			$throttleCount = $this->cache->get( $throttleKey );

			if ( !$throttleCount ) {
				$this->cache->add( $throttleKey, 1, $period ); // start counter
			} elseif ( $throttleCount < $count ) {
				$this->cache->incr( $throttleKey );
			} elseif ( $throttleCount >= $count ) {
				$this->logRejection( [
					'ip' => $ipOrAll,
					'period' => $period,
					'acct' => $username,
					'count' => $count,
					'throttleIdentifier' => $index,
					'method' => $caller ?: __METHOD__,
				] );

				return [
					'throttleIndex' => $index,
					'count' => $count,
					'wait' => $period,
				];
			}
		}
		return false;
	}

	/**
	 * Clear the throttle counter.
	 *
	 * Should be called after a successful authentication attempt.
	 *
	 * @param string $username
	 * @param string $ip
	 * @throws \MWException
	 */
	public function clear( $username, $ip = null ) {
		$ip = $ip ?: 'All';
		foreach ( $this->getThrottleConditions() as $index => $specificThrottle ) {
			$ipOrAll = isset( $specificThrottle['allIPs'] ) ? 'All' : $ip;
			$throttleKey = wfGlobalCacheKey( 'password-throttle', $index, $ipOrAll, md5( $username ) );
			$this->cache->delete( $throttleKey );
		}
	}

	/**
	 * Handles B/C for $wgPasswordAttemptThrottle.
	 * @return array
	 * @see $wgPasswordAttemptThrottle for structure
	 */
	protected function getThrottleConditions() {
		$throttleConditions = $this->config->get( 'PasswordAttemptThrottle' );
		if ( !is_array( $throttleConditions ) ) {
			return array();
		}
		if ( isset( $throttleConditions['count'] ) ) { // old style
			$throttleConditions = [ $throttleConditions ];
		}
		return $throttleConditions;
	}

	protected function logRejection( array $context ) {
		if ( !$this->logger ) {
			return;
		}

		$logMsg = 'Login attempt rejected because logins to {acct} from IP {ip} have been '
			. 'throttled for {period} seconds due to {count} failed attempts';
		// If we are hitting a throttle for >= 50 attempts, it is much more likely to be an attack
		// than someone simply forgetting their password, so log it at a higher level.
		$level = $context['count'] >= 50 ? LogLevel::WARNING : LogLevel::INFO;
		// It should be noted that once the throttle is hit, every attempt to login will
		// generate the log message until the throttle expires, not just the attempt that
		// puts the throttle over the top.
		$this->logger->log( $level, $logMsg, $context );
	}

}

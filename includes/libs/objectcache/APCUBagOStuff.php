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
namespace Wikimedia\ObjectCache;

/**
 * Store data in the local server memory via APCu (php-apcu)
 *
 * Past issues of note:
 * - Memory corruption when `apc.serializer=default` in INI:
 *   https://phabricator.wikimedia.org/T120267
 * - We used to recommend `apc.serializer=php` as non-default setting, and if not set,
 *   applied serialize() manually to workaround bugs and to create values we can use
 *   as CAS tokens. Upstream defaults to serializer=php since php-apcu 5.1.15 (2018).
 *   https://gerrit.wikimedia.org/r/671634
 *
 * @see https://www.php.net/apcu
 * @ingroup Cache
 */
class APCUBagOStuff extends MediumSpecificBagOStuff {
	/**
	 * @var string String to append to each APC key. This may be changed
	 *  whenever the handling of values is changed, to prevent existing code
	 *  from encountering older values which it cannot handle.
	 */
	private const KEY_SUFFIX = ':5';

	/** @var int Max attempts for implicit CAS operations */
	private static $CAS_MAX_ATTEMPTS = 100;

	public function __construct( array $params = [] ) {
		// No use in segmenting values
		$params['segmentationSize'] = INF;
		parent::__construct( $params );
		// Versions of apcu < 5.1.19 use apc.use_request_time=1 by default, causing new keys
		// to be assigned timestamps based on the start of the PHP request/script. The longer
		// the request has been running, the more likely that newly stored keys will instantly
		// be seen as expired by other requests. Disable apc.use_request_time.
		ini_set( 'apc.use_request_time', '0' );

		if ( PHP_SAPI === 'cli' ) {
			$this->attrMap[self::ATTR_DURABILITY] = ini_get( 'apc.enable_cli' )
				? self::QOS_DURABILITY_SCRIPT
				: self::QOS_DURABILITY_NONE;
		} else {
			$this->attrMap[self::ATTR_DURABILITY] = self::QOS_DURABILITY_SERVICE;
		}
	}

	/** @inheritDoc */
	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$getToken = ( $casToken === self::PASS_BY_REF );
		$casToken = null;

		$value = apcu_fetch( $key . self::KEY_SUFFIX );
		if ( $getToken && $value !== false ) {
			// Note that if the driver handles serialization then this uses the PHP value
			// as the token. This might require inspection or re-serialization in doCas().
			$casToken = $value;
		}

		return $value;
	}

	/** @inheritDoc */
	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		$ttl = $this->getExpirationAsTTL( $exptime );

		return apcu_store( $key . self::KEY_SUFFIX, $value, $ttl );
	}

	/** @inheritDoc */
	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		if ( apcu_exists( $key . self::KEY_SUFFIX ) ) {
			// Avoid global write locks for high contention keys
			return false;
		}

		$ttl = $this->getExpirationAsTTL( $exptime );

		return apcu_add( $key . self::KEY_SUFFIX, $value, $ttl );
	}

	/** @inheritDoc */
	protected function doDelete( $key, $flags = 0 ) {
		apcu_delete( $key . self::KEY_SUFFIX );

		return true;
	}

	/** @inheritDoc */
	protected function doIncrWithInit( $key, $exptime, $step, $init, $flags ) {
		// Use apcu 5.1.12 $ttl argument if apcu_inc() will initialize to $init:
		// https://www.php.net/manual/en/function.apcu-inc.php
		if ( $step === $init ) {
			/** @noinspection PhpMethodParametersCountMismatchInspection */
			$ttl = $this->getExpirationAsTTL( $exptime );
			$result = apcu_inc( $key . self::KEY_SUFFIX, $step, $success, $ttl );
		} else {
			$result = false;
			for ( $i = 0; $i < self::$CAS_MAX_ATTEMPTS; ++$i ) {
				$oldCount = apcu_fetch( $key . self::KEY_SUFFIX );
				if ( $oldCount === false ) {
					$count = $init;
					$ttl = $this->getExpirationAsTTL( $exptime );
					if ( apcu_add( $key . self::KEY_SUFFIX, $count, $ttl ) ) {
						$result = $count;
						break;
					}
				} elseif ( is_int( $oldCount ) ) {
					$count = $oldCount + $step;
					if ( apcu_cas( $key . self::KEY_SUFFIX, $oldCount, $count ) ) {
						$result = $count;
						break;
					}
				} else {
					break;
				}
			}
		}

		return $result;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( APCUBagOStuff::class, 'APCUBagOStuff' );

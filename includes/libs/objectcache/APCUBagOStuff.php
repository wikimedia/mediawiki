<?php
/**
 * Object caching using PHP's APCU accelerator.
 *
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
 * @ingroup Cache
 */

/**
 * This is a wrapper for APCU's shared memory functions
 *
 * Use PHP serialization to avoid bugs and easily create CAS tokens.
 * APCu has a memory corruption bug when the serializer is set to 'default'.
 * See T120267, and upstream bug reports:
 *  - https://github.com/krakjoe/apcu/issues/38
 *  - https://github.com/krakjoe/apcu/issues/35
 *  - https://github.com/krakjoe/apcu/issues/111
 *
 * @ingroup Cache
 */
class APCUBagOStuff extends MediumSpecificBagOStuff {
	/** @var bool Whether to trust the APC implementation to serialization */
	private $nativeSerialize;
	/** @var bool */
	private $useIncrTTLArg;

	/**
	 * @var string String to append to each APC key. This may be changed
	 *  whenever the handling of values is changed, to prevent existing code
	 *  from encountering older values which it cannot handle.
	 */
	private const KEY_SUFFIX = ':4';

	/** @var int Max attempts for implicit CAS operations */
	private static $CAS_MAX_ATTEMPTS = 100;

	public function __construct( array $params = [] ) {
		$params['segmentationSize'] = $params['segmentationSize'] ?? INF;
		parent::__construct( $params );
		// The extension serializer is still buggy, unlike "php" and "igbinary"
		$this->nativeSerialize = ( ini_get( 'apc.serializer' ) !== 'default' );
		$this->useIncrTTLArg = version_compare( phpversion( 'apcu' ), '5.1.12', '>=' );
		// Avoid back-dated values that expire too soon. In particular, regenerating a hot
		// key before it expires should never have the end-result of purging that key. Using
		// the web request time becomes increasingly problematic the longer the request lasts.
		ini_set( 'apc.use_request_time', '0' );
	}

	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$casToken = null;

		$blob = apcu_fetch( $key . self::KEY_SUFFIX );
		$value = $this->nativeSerialize ? $blob : $this->unserialize( $blob );
		if ( $value !== false ) {
			$casToken = $blob; // don't bother hashing this
		}

		return $value;
	}

	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		$blob = $this->nativeSerialize ? $value : $this->getSerialized( $value, $key );
		$success = apcu_store( $key . self::KEY_SUFFIX, $blob, $exptime );
		return $success;
	}

	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		$blob = $this->nativeSerialize ? $value : $this->getSerialized( $value, $key );
		$success = apcu_add( $key . self::KEY_SUFFIX, $blob, $exptime );
		return $success;
	}

	protected function doDelete( $key, $flags = 0 ) {
		apcu_delete( $key . self::KEY_SUFFIX );

		return true;
	}

	public function incr( $key, $value = 1, $flags = 0 ) {
		$result = false;

		// https://github.com/krakjoe/apcu/issues/166
		for ( $i = 0; $i < self::$CAS_MAX_ATTEMPTS; ++$i ) {
			$oldCount = apcu_fetch( $key . self::KEY_SUFFIX );
			if ( !is_int( $oldCount ) ) {
				break;
			}
			$count = $oldCount + (int)$value;
			if ( apcu_cas( $key . self::KEY_SUFFIX, $oldCount, $count ) ) {
				$result = $count;
				break;
			}
		}

		return $result;
	}

	public function decr( $key, $value = 1, $flags = 0 ) {
		$result = false;

		// https://github.com/krakjoe/apcu/issues/166
		for ( $i = 0; $i < self::$CAS_MAX_ATTEMPTS; ++$i ) {
			$oldCount = apcu_fetch( $key . self::KEY_SUFFIX );
			if ( !is_int( $oldCount ) ) {
				break;
			}
			$count = $oldCount - (int)$value;
			if ( apcu_cas( $key . self::KEY_SUFFIX, $oldCount, $count ) ) {
				$result = $count;
				break;
			}
		}

		return $result;
	}

	public function incrWithInit( $key, $exptime, $value = 1, $init = null, $flags = 0 ) {
		$init = is_int( $init ) ? $init : $value;
		// Use apcu 5.1.12 $ttl argument if apcu_inc() will initialize to $init:
		// https://www.php.net/manual/en/function.apcu-inc.php
		if ( $value === $init && $this->useIncrTTLArg ) {
			/** @noinspection PhpMethodParametersCountMismatchInspection */
			$result = apcu_inc( $key . self::KEY_SUFFIX, $value, $success, $exptime );
		} else {
			$result = false;
			for ( $i = 0; $i < self::$CAS_MAX_ATTEMPTS; ++$i ) {
				$oldCount = apcu_fetch( $key . self::KEY_SUFFIX );
				if ( $oldCount === false ) {
					$count = (int)$init;
					if ( apcu_add( $key . self::KEY_SUFFIX, $count, $exptime ) ) {
						$result = $count;
						break;
					}
				} elseif ( is_int( $oldCount ) ) {
					$count = $oldCount + (int)$value;
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

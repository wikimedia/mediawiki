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
 * This is a wrapper for APCu's shared memory functions
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

	/**
	 * @var string String to append to each APC key. This may be changed
	 *  whenever the handling of values is changed, to prevent existing code
	 *  from encountering older values which it cannot handle.
	 */
	private const KEY_SUFFIX = ':4';

	/** @var int Max attempts for implicit CAS operations */
	private static $CAS_MAX_ATTEMPTS = 100;

	public function __construct( array $params = [] ) {
		// No use in segmenting values
		$params['segmentationSize'] = INF;
		parent::__construct( $params );
		// The extension serializer is still buggy, unlike "php" and "igbinary"
		$this->nativeSerialize = ( ini_get( 'apc.serializer' ) !== 'default' );
		// Avoid back-dated values that expire too soon. In particular, regenerating a hot
		// key before it expires should never have the end-result of purging that key. Using
		// the web request time becomes increasingly problematic the longer the request lasts.
		ini_set( 'apc.use_request_time', '0' );

		if ( PHP_SAPI === 'cli' ) {
			$this->attrMap[self::ATTR_DURABILITY] = ini_get( 'apc.enable_cli' )
				? self::QOS_DURABILITY_SCRIPT
				: self::QOS_DURABILITY_NONE;
		} else {
			$this->attrMap[self::ATTR_DURABILITY] = self::QOS_DURABILITY_SERVICE;
		}
	}

	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$getToken = ( $casToken === self::PASS_BY_REF );
		$casToken = null;

		$blob = apcu_fetch( $key . self::KEY_SUFFIX );
		$value = $this->nativeSerialize ? $blob : $this->unserialize( $blob );
		if ( $getToken && $value !== false ) {
			// Note that if the driver handles serialization then this uses the PHP value
			// as the token. This might require inspection or re-serialization in doCas().
			$casToken = $blob;
		}

		return $value;
	}

	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		$blob = $this->nativeSerialize ? $value : $this->getSerialized( $value, $key );
		$ttl = $this->getExpirationAsTTL( $exptime );
		return apcu_store( $key . self::KEY_SUFFIX, $blob, $ttl );
	}

	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		if ( apcu_exists( $key . self::KEY_SUFFIX ) ) {
			return false;
		}

		$blob = $this->nativeSerialize ? $value : $this->getSerialized( $value, $key );
		$ttl = $this->getExpirationAsTTL( $exptime );
		return apcu_add( $key . self::KEY_SUFFIX, $blob, $ttl );
	}

	protected function doDelete( $key, $flags = 0 ) {
		apcu_delete( $key . self::KEY_SUFFIX );

		return true;
	}

	public function incr( $key, $value = 1, $flags = 0 ) {
		$result = false;
		$value = (int)$value;

		// https://github.com/krakjoe/apcu/issues/166
		for ( $i = 0; $i < self::$CAS_MAX_ATTEMPTS; ++$i ) {
			$oldCount = apcu_fetch( $key . self::KEY_SUFFIX );
			if ( !is_int( $oldCount ) ) {
				break;
			}
			$count = $oldCount + $value;
			if ( apcu_cas( $key . self::KEY_SUFFIX, $oldCount, $count ) ) {
				$result = $count;
				break;
			}
		}

		return $result;
	}

	public function decr( $key, $value = 1, $flags = 0 ) {
		$result = false;
		$value = (int)$value;

		// https://github.com/krakjoe/apcu/issues/166
		for ( $i = 0; $i < self::$CAS_MAX_ATTEMPTS; ++$i ) {
			$oldCount = apcu_fetch( $key . self::KEY_SUFFIX );
			if ( !is_int( $oldCount ) ) {
				break;
			}
			$count = $oldCount - $value;
			if ( apcu_cas( $key . self::KEY_SUFFIX, $oldCount, $count ) ) {
				$result = $count;
				break;
			}
		}

		return $result;
	}

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

	public function setNewPreparedValues( array $valueByKey ) {
		// Do not bother staging serialized values if the PECL driver does the serializing
		return $this->nativeSerialize
			? $this->guessSerialSizeOfValues( $valueByKey )
			: parent::setNewPreparedValues( $valueByKey );
	}

	public function makeKeyInternal( $keyspace, $components ) {
		return $this->genericKeyFromComponents( $keyspace, ...$components );
	}

	protected function convertGenericKey( $key ) {
		// short-circuit; already uses "generic" keys
		return $key;
	}
}

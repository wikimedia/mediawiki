<?php
/**
 * Object caching using WinCache.
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
 * Wrapper for WinCache object caching functions; identical interface
 * to the APC wrapper
 *
 * @ingroup Cache
 */
class WinCacheBagOStuff extends MediumSpecificBagOStuff {
	public function __construct( array $params = [] ) {
		$params['segmentationSize'] ??= INF;
		parent::__construct( $params );

		if ( PHP_SAPI === 'cli' ) {
			$this->attrMap[self::ATTR_DURABILITY] = ini_get( 'wincache.enablecli' )
				? self::QOS_DURABILITY_SCRIPT
				: self::QOS_DURABILITY_NONE;
		} else {
			$this->attrMap[self::ATTR_DURABILITY] = self::QOS_DURABILITY_SERVICE;
		}
	}

	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$getToken = ( $casToken === self::PASS_BY_REF );
		$casToken = null;

		$data = wincache_ucache_get( $key );
		if ( !is_string( $data ) && !is_int( $data ) ) {
			return false;
		}

		$value = $this->unserialize( $data );
		if ( $getToken && $value !== false ) {
			$casToken = $data;
		}

		return $value;
	}

	protected function doCas( $casToken, $key, $value, $exptime = 0, $flags = 0 ) {
		// optimize with FIFO lock
		if ( !wincache_lock( $key ) ) {
			return false;
		}

		$curCasToken = self::PASS_BY_REF;
		$this->doGet( $key, self::READ_LATEST, $curCasToken );
		if ( $casToken === $curCasToken ) {
			$success = $this->set( $key, $value, $exptime, $flags );
		} else {
			$this->logger->info(
				__METHOD__ . ' failed due to race condition for {key}.',
				[ 'key' => $key ]
			);

			// mismatched or failed
			$success = false;
		}

		wincache_unlock( $key );

		return $success;
	}

	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		$ttl = $this->getExpirationAsTTL( $exptime );
		$result = wincache_ucache_set( $key, $this->getSerialized( $value, $key ), $ttl );

		// false positive, wincache_ucache_set returns an empty array
		// in some circumstances.
		// @phan-suppress-next-line PhanTypeComparisonToArray
		return ( $result === [] || $result === true );
	}

	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		if ( wincache_ucache_exists( $key ) ) {
			// avoid warnings
			return false;
		}

		$ttl = $this->getExpirationAsTTL( $exptime );
		$result = wincache_ucache_add( $key, $this->getSerialized( $value, $key ), $ttl );

		// false positive, wincache_ucache_add returns an empty array
		// in some circumstances.
		// @phan-suppress-next-line PhanTypeComparisonToArray
		return ( $result === [] || $result === true );
	}

	protected function doDelete( $key, $flags = 0 ) {
		wincache_ucache_delete( $key );

		return true;
	}

	protected function makeKeyInternal( $keyspace, $components ) {
		// WinCache keys have a maximum length of 150 characters. From that,
		// subtract the number of characters we need for the keyspace and for
		// the separator character needed for each argument. To handle some
		// custom prefixes used by thing like WANObjectCache, limit to 125.
		// NOTE: Same as in memcached, except the max key length there is 255.
		$charsLeft = 125 - strlen( $keyspace ) - count( $components );

		$components = array_map(
			static function ( $component ) use ( &$charsLeft ) {
				// 33 = 32 characters for the MD5 + 1 for the '#' prefix.
				if ( $charsLeft > 33 && strlen( $component ) > $charsLeft ) {
					$component = '#' . md5( $component );
				}

				$charsLeft -= strlen( $component );
				return $component;
			},
			$components
		);

		if ( $charsLeft < 0 ) {
			return $keyspace . ':BagOStuff-long-key:##' . md5( implode( ':', $components ) );
		}

		return $keyspace . ':' . implode( ':', $components );
	}

	protected function requireConvertGenericKey(): bool {
		return true;
	}

	public function doIncrWithInit( $key, $exptime, $step, $init, $flags ) {
		// optimize with FIFO lock
		if ( !wincache_lock( $key ) ) {
			return false;
		}

		$curValue = $this->doGet( $key );
		if ( $curValue === false ) {
			$newValue = $this->doSet( $key, $init, $exptime ) ? $init : false;
		} elseif ( $this->isInteger( $curValue ) ) {
			$sum = max( $curValue + $step, 0 );
			$oldTTL = wincache_ucache_info( false, $key )["ucache_entries"][1]["ttl_seconds"];
			$newValue = $this->doSet( $key, $sum, $oldTTL ) ? $sum : false;
		} else {
			$newValue = false;
		}

		wincache_unlock( $key );

		return $newValue;
	}
}

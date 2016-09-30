<?php
/**
 * Base class for memcached clients.
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
 * Base class for memcached clients.
 *
 * @ingroup Cache
 */
class MemcachedBagOStuff extends BagOStuff {
	/** @var MemcachedClient|Memcached */
	protected $client;

	function __construct( array $params ) {
		parent::__construct( $params );

		$this->attrMap[self::ATTR_SYNCWRITES] = self::QOS_SYNCWRITES_BE; // unreliable
	}

	/**
	 * Fill in some defaults for missing keys in $params.
	 *
	 * @param array $params
	 * @return array
	 */
	protected function applyDefaultParams( $params ) {
		return $params + [
			'compress_threshold' => 1500,
			'connect_timeout' => .5,
			'debug' => false
		];
	}

	protected function doGet( $key, $flags = 0 ) {
		$casToken = null;

		return $this->getWithToken( $key, $casToken, $flags );
	}

	protected function getWithToken( $key, &$casToken, $flags = 0 ) {
		return $this->client->get( $this->validateKeyEncoding( $key ), $casToken );
	}

	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		return $this->client->set( $this->validateKeyEncoding( $key ), $value,
			$this->fixExpiry( $exptime ) );
	}

	protected function cas( $casToken, $key, $value, $exptime = 0 ) {
		return $this->client->cas( $casToken, $this->validateKeyEncoding( $key ),
			$value, $this->fixExpiry( $exptime ) );
	}

	public function delete( $key ) {
		return $this->client->delete( $this->validateKeyEncoding( $key ) );
	}

	public function add( $key, $value, $exptime = 0 ) {
		return $this->client->add( $this->validateKeyEncoding( $key ), $value,
			$this->fixExpiry( $exptime ) );
	}

	public function merge( $key, callable $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		return $this->mergeViaCas( $key, $callback, $exptime, $attempts );
	}

	public function changeTTL( $key, $exptime = 0 ) {
		return $this->client->touch( $this->validateKeyEncoding( $key ),
			$this->fixExpiry( $exptime ) );
	}

	/**
	 * Get the underlying client object. This is provided for debugging
	 * purposes.
	 * @return MemcachedClient|Memcached
	 */
	public function getClient() {
		return $this->client;
	}

	/**
	 * Construct a cache key.
	 *
	 * @since 1.27
	 * @param string $keyspace
	 * @param array $args
	 * @return string
	 */
	public function makeKeyInternal( $keyspace, $args ) {
		// Memcached keys have a maximum length of 255 characters. From that,
		// subtract the number of characters we need for the keyspace and for
		// the separator character needed for each argument. To handle some
		// custom prefixes used by thing like WANObjectCache, limit to 205.
		$charsLeft = 205 - strlen( $keyspace ) - count( $args );

		$args = array_map(
			function ( $arg ) use ( &$charsLeft ) {
				$arg = strtr( $arg, ' ', '_' );

				// Make sure %, #, and non-ASCII chars are escaped
				$arg = preg_replace_callback(
					'/[^\x21-\x22\x24\x26-\x39\x3b-\x7e]+/',
					function ( $m ) {
						return rawurlencode( $m[0] );
					},
					$arg
				);

				// 33 = 32 characters for the MD5 + 1 for the '#' prefix.
				if ( $charsLeft > 33 && strlen( $arg ) > $charsLeft ) {
					$arg = '#' . md5( $arg );
				}

				$charsLeft -= strlen( $arg );
				return $arg;
			},
			$args
		);

		if ( $charsLeft < 0 ) {
			return $keyspace . ':##' . md5( implode( ':', $args ) );
		}

		return $keyspace . ':' . implode( ':', $args );
	}

	/**
	 * Ensure that a key is safe to use (contains no control characters and no
	 * characters above the ASCII range.)
	 *
	 * @param string $key
	 * @return string
	 * @throws Exception
	 */
	public function validateKeyEncoding( $key ) {
		if ( preg_match( '/[^\x21-\x7e]+/', $key ) ) {
			throw new Exception( "Key contains invalid characters: $key" );
		}
		return $key;
	}

	/**
	 * TTLs higher than 30 days will be detected as absolute TTLs
	 * (UNIX timestamps), and will result in the cache entry being
	 * discarded immediately because the expiry is in the past.
	 * Clamp expires >30d at 30d, unless they're >=1e9 in which
	 * case they are likely to really be absolute (1e9 = 2011-09-09)
	 * @param int $expiry
	 * @return int
	 */
	function fixExpiry( $expiry ) {
		if ( $expiry > 2592000 && $expiry < 1000000000 ) {
			$expiry = 2592000;
		}
		return (int)$expiry;
	}

	/**
	 * Send a debug message to the log
	 * @param string $text
	 */
	protected function debugLog( $text ) {
		$this->logger->debug( $text );
	}

	public function modifySimpleRelayEvent( array $event ) {
		if ( array_key_exists( 'val', $event ) ) {
			$event['flg'] = 0; // data is not serialized nor gzipped (for memcached driver)
		}

		return $event;
	}
}

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

use Exception;
use InvalidArgumentException;
use RuntimeException;

/**
 * Store data in a memcached server or memcached cluster.
 *
 * This is a base class for MemcachedPhpBagOStuff and MemcachedPeclBagOStuff.
 *
 * @ingroup Cache
 */
abstract class MemcachedBagOStuff extends MediumSpecificBagOStuff {
	/** @var string Routing prefix appended to keys during operations */
	protected $routingPrefix;

	/**
	 * @param array $params Additional parameters include:
	 *   - routingPrefix: a routing prefix of the form "<datacenter>/<cluster>/" used to convey
	 *      the location/strategy to use for handling keys accessed from this instance. The prefix
	 *      is prepended to keys during cache operations. The memcached proxy must preserve these
	 *      prefixes in any responses that include requested keys (e.g. get/gets). The proxy is
	 *      also assumed to strip the routing prefix from the stored key name, which allows for
	 *      unprefixed access. This can be used with mcrouter. [optional]
	 */
	public function __construct( array $params ) {
		$params['segmentationSize'] ??= 917_504; // < 1MiB
		parent::__construct( $params );

		$this->routingPrefix = $params['routingPrefix'] ?? '';

		// ...and does not use special disk-cache plugins
		$this->attrMap[self::ATTR_DURABILITY] = self::QOS_DURABILITY_SERVICE;
	}

	/**
	 * Format a cache key.
	 *
	 * @since 1.27
	 * @see BagOStuff::makeKeyInternal
	 *
	 * @param string $keyspace
	 * @param string[]|int[]|null $components
	 *
	 * @return string
	 */
	protected function makeKeyInternal( $keyspace, $components ) {
		$key = $keyspace;
		foreach ( $components as $component ) {
			$component = strtr( $component ?? '', ' ', '_' );

			// Make sure %, #, and non-ASCII chars are escaped
			$component = preg_replace_callback(
				'/[^\x21-\x22\x24\x26-\x39\x3b-\x7e]+/',
				static function ( $m ) {
					return rawurlencode( $m[0] );
				},
				$component
			);

			$key .= ':' . $component;
		}

		// Memcached keys have a maximum length of 250 characters.
		// * Reserve 45 chars for prefixes used by wrappers like WANObjectCache.
		return $this->makeFallbackKey( $key, 205 );
	}

	protected function requireConvertGenericKey(): bool {
		return true;
	}

	/**
	 * Ensure that a key is safe to use (contains no control characters and no
	 * characters above the ASCII range.)
	 *
	 * @param string $key
	 *
	 * @return string
	 * @throws Exception
	 */
	public function validateKeyEncoding( $key ) {
		if ( preg_match( '/[^\x21-\x7e]+/', $key ) ) {
			throw new InvalidArgumentException( "Key contains invalid characters: $key" );
		}

		return $key;
	}

	/**
	 * @param string $key
	 *
	 * @return string
	 */
	protected function validateKeyAndPrependRoute( $key ) {
		$this->validateKeyEncoding( $key );

		if ( $this->routingPrefix === '' ) {
			return $key;
		}

		if ( $key[0] === '/' ) {
			throw new RuntimeException( "Key '$key' already contains a route." );
		}

		return $this->routingPrefix . $key;
	}

	/**
	 * @param string $key
	 *
	 * @return string
	 */
	protected function stripRouteFromKey( $key ) {
		if ( $this->routingPrefix === '' ) {
			return $key;
		}

		if ( str_starts_with( $key, $this->routingPrefix ) ) {
			return substr( $key, strlen( $this->routingPrefix ) );
		}

		return $key;
	}

	/**
	 * @param int|float $exptime
	 *
	 * @return int
	 */
	protected function fixExpiry( $exptime ) {
		if ( $exptime < 0 ) {
			// The PECL driver does not seem to like negative relative values
			$expiresAt = $this->getCurrentTime() + $exptime;
		} elseif ( $this->isRelativeExpiration( $exptime ) ) {
			// TTLs higher than 30 days will be detected as absolute TTLs
			// (UNIX timestamps), and will result in the cache entry being
			// discarded immediately because the expiry is in the past.
			// Clamp expires >30d at 30d, unless they're >=1e9 in which
			// case they are likely to really be absolute (1e9 = 2011-09-09)
			$expiresAt = min( $exptime, self::TTL_MONTH );
		} else {
			$expiresAt = $exptime;
		}

		return (int)$expiresAt;
	}

	/** @inheritDoc */
	protected function doIncrWithInit( $key, $exptime, $step, $init, $flags ) {
		if ( $flags & self::WRITE_BACKGROUND ) {
			return $this->doIncrWithInitAsync( $key, $exptime, $step, $init );
		} else {
			return $this->doIncrWithInitSync( $key, $exptime, $step, $init );
		}
	}

	/**
	 * @param string $key
	 * @param int $exptime
	 * @param int $step
	 * @param int $init
	 *
	 * @return bool True on success, false on failure
	 */
	abstract protected function doIncrWithInitAsync( $key, $exptime, $step, $init );

	/**
	 * @param string $key
	 * @param int $exptime
	 * @param int $step
	 * @param int $init
	 *
	 * @return int|bool New value or false on failure
	 */
	abstract protected function doIncrWithInitSync( $key, $exptime, $step, $init );
}

/** @deprecated class alias since 1.43 */
class_alias( MemcachedBagOStuff::class, 'MemcachedBagOStuff' );

<?php
/**
 * Functions to get cache objects.
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

use MediaWiki\Logger\LoggerFactory;

/**
 * Functions to get cache objects
 *
 * The word "cache" has two main dictionary meanings, and both
 * are used in this factory class. They are:
 *   - a) A place to store copies or computations on existing data
 *        for higher access speeds (the computer science definition)
 *   - b) A place to store lightweight data that is not canonically
 *        stored anywhere else (e.g. a "hoard" of objects)
 *
 * The former should always use strongly consistent stores, so callers don't
 * have to deal with stale reads. The later may be eventually consistent, but
 * callers can use BagOStuff:READ_LATEST to see the latest available data.
 *
 * @ingroup Cache
 */
class ObjectCache {
	/** @var Array Map of (id => BagOStuff) */
	public static $instances = array();

	/** @var Array Map of (id => WANObjectCache) */
	public static $wanInstances = array();

	/**
	 * Get a cached instance of the specified type of cache object.
	 *
	 * @param string $id
	 * @return BagOStuff
	 */
	static function getInstance( $id ) {
		if ( !isset( self::$instances[$id] ) ) {
			self::$instances[$id] = self::newFromId( $id );
		}

		return self::$instances[$id];
	}

	/**
	 * Get a cached instance of the specified type of cache object.
	 *
	 * @since 1.26
	 * @param string $id
	 * @return WANObjectCache
	 */
	static function getWANInstance( $id ) {
		if ( !isset( self::$wanInstances[$id] ) ) {
			self::$wanInstances[$id] = self::newWANCacheFromId( $id );
		}

		return self::$wanInstances[$id];
	}

	/**
	 * Clear all the cached instances.
	 */
	static function clear() {
		self::$instances = array();
		self::$wanInstances = array();
	}

	/**
	 * Create a new cache object of the specified type.
	 *
	 * @param string $id
	 * @return BagOStuff
	 * @throws MWException
	 */
	static function newFromId( $id ) {
		global $wgObjectCaches;

		if ( !isset( $wgObjectCaches[$id] ) ) {
			throw new MWException( "Invalid object cache type \"$id\" requested. " .
				"It is not present in \$wgObjectCaches." );
		}

		return self::newFromParams( $wgObjectCaches[$id] );
	}

	/**
	 * Create a new cache object from parameters
	 *
	 * @param array $params
	 * @return BagOStuff
	 * @throws MWException
	 */
	static function newFromParams( $params ) {
		if ( isset( $params['loggroup'] ) ) {
			$params['logger'] = LoggerFactory::getInstance( $params['loggroup'] );
		} else {
			// For backwards-compatability with custom parameters, lets not
			// have all logging suddenly disappear
			$params['logger'] = LoggerFactory::getInstance( 'objectcache' );
		}
		if ( isset( $params['factory'] ) ) {
			return call_user_func( $params['factory'], $params );
		} elseif ( isset( $params['class'] ) ) {
			$class = $params['class'];
			return new $class( $params );
		} else {
			throw new MWException( "The definition of cache type \""
				. print_r( $params, true ) . "\" lacks both "
				. "factory and class parameters." );
		}
	}

	/**
	 * Factory function referenced from DefaultSettings.php for CACHE_ANYTHING
	 *
	 * CACHE_ANYTHING means that stuff has to be cached, not caching is not an option.
	 * If a caching method is configured for any of the main caches ($wgMainCacheType,
	 * $wgMessageCacheType, $wgParserCacheType), then CACHE_ANYTHING will effectively
	 * be an alias to the configured cache choice for that.
	 * If no cache choice is configured (by default $wgMainCacheType is CACHE_NONE),
	 * then CACHE_ANYTHING will forward to CACHE_DB.
	 *
	 * @param array $params
	 * @return BagOStuff
	 */
	static function newAnything( $params ) {
		global $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType;
		$candidates = array( $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType );
		foreach ( $candidates as $candidate ) {
			if ( $candidate !== CACHE_NONE && $candidate !== CACHE_ANYTHING ) {
				return self::getInstance( $candidate );
			}
		}
		return self::getInstance( CACHE_DB );
	}

	/**
	 * Factory function referenced from DefaultSettings.php for CACHE_ACCEL.
	 *
	 * This will look for any APC style server-local cache.
	 * A fallback cache can be specified if none is found.
	 *
	 * @param array $params
	 * @param int|string $fallback Fallback cache, e.g. (CACHE_NONE, "hash") (since 1.24)
	 * @return BagOStuff
	 * @throws MWException
	 */
	static function newAccelerator( $params, $fallback = null ) {
		if ( function_exists( 'apc_fetch' ) ) {
			$id = 'apc';
		} elseif ( function_exists( 'xcache_get' ) && wfIniGetBool( 'xcache.var_size' ) ) {
			$id = 'xcache';
		} elseif ( function_exists( 'wincache_ucache_get' ) ) {
			$id = 'wincache';
		} else {
			if ( $fallback === null ) {
				throw new MWException( 'CACHE_ACCEL requested but no suitable object ' .
					'cache is present. You may want to install APC.' );
			}
			$id = $fallback;
		}
		return self::newFromId( $id );
	}

	/**
	 * Factory function that creates a memcached client object.
	 *
	 * This always uses the PHP client, since the PECL client has a different
	 * hashing scheme and a different interpretation of the flags bitfield, so
	 * switching between the two clients randomly would be disastrous.
	 *
	 * @param array $params
	 * @return MemcachedPhpBagOStuff
	 */
	static function newMemcached( $params ) {
		return new MemcachedPhpBagOStuff( $params );
	}

	/**
	 * Create a new cache object of the specified type
	 *
	 * @since 1.26
	 * @param string $id
	 * @return WANObjectCache
	 * @throws MWException
	 */
	static function newWANCacheFromId( $id ) {
		global $wgWANObjectCaches;

		if ( !isset( $wgWANObjectCaches[$id] ) ) {
			throw new MWException( "Invalid object cache type \"$id\" requested. " .
				"It is not present in \$wgWANObjectCaches." );
		}

		$params = $wgWANObjectCaches[$id];
		$class = $params['relayerConfig']['class'];
		$params['relayer'] = new $class( $params['relayerConfig'] );
		$params['cache'] = self::newFromId( $params['cacheId'] );
		$class = $params['class'];

		return new $class( $params );
	}

	/**
	 * Get the main WAN cache object
	 *
	 * @since 1.26
	 * @return WANObjectCache
	 */
	static function getMainWANInstance() {
		global $wgMainWANCache;

		return self::getWANInstance( $wgMainWANCache );
	}

	/**
	 * Stash objects are BagOStuff instances suitable for storing light
	 * weight data that is not canonically stored elsewhere (such as RDBMS).
	 * Stashes should be configured to propagate changes to all data-centers.
	 *
	 * Callers should be prepared for:
	 *   - a) Writes to be slower in non-"primary" (e.g. HTTP GET/HEAD only) DCs
	 *   - b) Reads to be eventually consistent, e.g. for get()/getMulti()
	 * In general, this means avoiding updates on idempotent HTTP requests and
	 * avoiding an assumption of perfect serializability (or accepting anomalies).
	 * Reads may be eventually consistent or data might rollback as nodes flap.
	 * Callers can use BagOStuff:READ_LATEST to see the latest available data.
	 *
	 * @return BagOStuff
	 * @since 1.26
	 */
	static function getMainStashInstance() {
		global $wgMainStash;

		return self::getInstance( $wgMainStash );
	}
}

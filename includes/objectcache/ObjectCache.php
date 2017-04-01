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
use MediaWiki\MediaWikiServices;

/**
 * Functions to get cache objects
 *
 * The word "cache" has two main dictionary meanings, and both
 * are used in this factory class. They are:
 *
 *   - a) Cache (the computer science definition).
 *        A place to store copies or computations on existing data for
 *        higher access speeds.
 *   - b) Storage.
 *        A place to store lightweight data that is not canonically
 *        stored anywhere else (e.g. a "hoard" of objects).
 *
 * The former should always use strongly consistent stores, so callers don't
 * have to deal with stale reads. The later may be eventually consistent, but
 * callers can use BagOStuff:READ_LATEST to see the latest available data.
 *
 * Primary entry points:
 *
 * - ObjectCache::getMainWANInstance()
 *   Purpose: Memory cache.
 *   Stored in the local data-center's main cache (keyspace different from local-cluster cache).
 *   Delete events are broadcasted to other DCs main cache. See WANObjectCache for details.
 *
 * - ObjectCache::getLocalServerInstance( $fallbackType )
 *   Purpose: Memory cache for very hot keys.
 *   Stored only on the individual web server (typically APC or APCu for web requests,
 *   and EmptyBagOStuff in CLI mode).
 *   Not replicated to the other servers.
 *
 * - ObjectCache::getLocalClusterInstance()
 *   Purpose: Memory storage for per-cluster coordination and tracking.
 *   A typical use case would be a rate limit counter or cache regeneration mutex.
 *   Stored centrally within the local data-center. Not replicated to other DCs.
 *   Configured by $wgMainCacheType.
 *
 * - ObjectCache::getMainStashInstance()
 *   Purpose: Ephemeral global storage.
 *   Stored centrally within the primary data-center.
 *   Changes are applied there first and replicated to other DCs (best-effort).
 *   To retrieve the latest value (e.g. not from a slave), use BagOStuff::READ_LATEST.
 *   This store may be subject to LRU style evictions.
 *
 * - ObjectCache::getInstance( $cacheType )
 *   Purpose: Special cases (like tiered memory/disk caches).
 *   Get a specific cache type by key in $wgObjectCaches.
 *
 * All the above cache instances (BagOStuff and WANObjectCache) have their makeKey()
 * method scoped to the *current* wiki ID. Use makeGlobalKey() to avoid this scoping
 * when using keys that need to be shared amongst wikis.
 *
 * @ingroup Cache
 */
class ObjectCache {
	/** @var BagOStuff[] Map of (id => BagOStuff) */
	public static $instances = [];
	/** @var WANObjectCache[] Map of (id => WANObjectCache) */
	public static $wanInstances = [];

	/**
	 * Get a cached instance of the specified type of cache object.
	 *
	 * @param string $id A key in $wgObjectCaches.
	 * @return BagOStuff
	 */
	public static function getInstance( $id ) {
		if ( !isset( self::$instances[$id] ) ) {
			self::$instances[$id] = self::newFromId( $id );
		}

		return self::$instances[$id];
	}

	/**
	 * Get a cached instance of the specified type of WAN cache object.
	 *
	 * @since 1.26
	 * @param string $id A key in $wgWANObjectCaches.
	 * @return WANObjectCache
	 */
	public static function getWANInstance( $id ) {
		if ( !isset( self::$wanInstances[$id] ) ) {
			self::$wanInstances[$id] = self::newWANCacheFromId( $id );
		}

		return self::$wanInstances[$id];
	}

	/**
	 * Create a new cache object of the specified type.
	 *
	 * @param string $id A key in $wgObjectCaches.
	 * @return BagOStuff
	 * @throws MWException
	 */
	public static function newFromId( $id ) {
		global $wgObjectCaches;

		if ( !isset( $wgObjectCaches[$id] ) ) {
			throw new MWException( "Invalid object cache type \"$id\" requested. " .
				"It is not present in \$wgObjectCaches." );
		}

		return self::newFromParams( $wgObjectCaches[$id] );
	}

	/**
	 * Get the default keyspace for this wiki.
	 *
	 * This is either the value of the `CachePrefix` configuration variable,
	 * or (if the former is unset) the `DBname` configuration variable, with
	 * `DBprefix` (if defined).
	 *
	 * @return string
	 */
	public static function getDefaultKeyspace() {
		global $wgCachePrefix;

		$keyspace = $wgCachePrefix;
		if ( is_string( $keyspace ) && $keyspace !== '' ) {
			return $keyspace;
		}

		return wfWikiID();
	}

	/**
	 * Create a new cache object from parameters.
	 *
	 * @param array $params Must have 'factory' or 'class' property.
	 *  - factory: Callback passed $params that returns BagOStuff.
	 *  - class: BagOStuff subclass constructed with $params.
	 *  - loggroup: Alias to set 'logger' key with LoggerFactory group.
	 *  - .. Other parameters passed to factory or class.
	 * @return BagOStuff
	 * @throws MWException
	 */
	public static function newFromParams( $params ) {
		if ( isset( $params['loggroup'] ) ) {
			$params['logger'] = LoggerFactory::getInstance( $params['loggroup'] );
		} else {
			$params['logger'] = LoggerFactory::getInstance( 'objectcache' );
		}
		if ( !isset( $params['keyspace'] ) ) {
			$params['keyspace'] = self::getDefaultKeyspace();
		}
		if ( isset( $params['factory'] ) ) {
			return call_user_func( $params['factory'], $params );
		} elseif ( isset( $params['class'] ) ) {
			$class = $params['class'];
			// Automatically set the 'async' update handler
			$params['asyncHandler'] = isset( $params['asyncHandler'] )
				? $params['asyncHandler']
				: 'DeferredUpdates::addCallableUpdate';
			// Enable reportDupes by default
			$params['reportDupes'] = isset( $params['reportDupes'] )
				? $params['reportDupes']
				: true;
			// Do b/c logic for MemcachedBagOStuff
			if ( is_subclass_of( $class, 'MemcachedBagOStuff' ) ) {
				if ( !isset( $params['servers'] ) ) {
					$params['servers'] = $GLOBALS['wgMemCachedServers'];
				}
				if ( !isset( $params['debug'] ) ) {
					$params['debug'] = $GLOBALS['wgMemCachedDebug'];
				}
				if ( !isset( $params['persistent'] ) ) {
					$params['persistent'] = $GLOBALS['wgMemCachedPersistent'];
				}
				if ( !isset( $params['timeout'] ) ) {
					$params['timeout'] = $GLOBALS['wgMemCachedTimeout'];
				}
			}
			return new $class( $params );
		} else {
			throw new MWException( "The definition of cache type \""
				. print_r( $params, true ) . "\" lacks both "
				. "factory and class parameters." );
		}
	}

	/**
	 * Factory function for CACHE_ANYTHING (referenced from DefaultSettings.php)
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
	public static function newAnything( $params ) {
		global $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType;
		$candidates = [ $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType ];
		foreach ( $candidates as $candidate ) {
			$cache = false;
			if ( $candidate !== CACHE_NONE && $candidate !== CACHE_ANYTHING ) {
				$cache = self::getInstance( $candidate );
				// CACHE_ACCEL might default to nothing if no APCu
				// See includes/ServiceWiring.php
				if ( !( $cache instanceof EmptyBagOStuff ) ) {
					return $cache;
				}
			}
		}
		return self::getInstance( CACHE_DB );
	}

	/**
	 * Factory function for CACHE_ACCEL (referenced from DefaultSettings.php)
	 *
	 * This will look for any APC or APCu style server-local cache.
	 * A fallback cache can be specified if none is found.
	 *
	 *     // Direct calls
	 *     ObjectCache::getLocalServerInstance( $fallbackType );
	 *
	 *     // From $wgObjectCaches via newFromParams()
	 *     ObjectCache::getLocalServerInstance( array( 'fallback' => $fallbackType ) );
	 *
	 * @param int|string|array $fallback Fallback cache or parameter map with 'fallback'
	 * @return BagOStuff
	 * @throws MWException
	 * @since 1.27
	 */
	public static function getLocalServerInstance( $fallback = CACHE_NONE ) {
		if ( function_exists( 'apc_fetch' ) ) {
			$id = 'apc';
		} elseif ( function_exists( 'apcu_fetch' ) ) {
			$id = 'apcu';
		} elseif ( function_exists( 'xcache_get' ) && wfIniGetBool( 'xcache.var_size' ) ) {
			$id = 'xcache';
		} elseif ( function_exists( 'wincache_ucache_get' ) ) {
			$id = 'wincache';
		} else {
			if ( is_array( $fallback ) ) {
				$id = isset( $fallback['fallback'] ) ? $fallback['fallback'] : CACHE_NONE;
			} else {
				$id = $fallback;
			}
		}

		return self::getInstance( $id );
	}

	/**
	 * @param array $params [optional] Array key 'fallback' for $fallback.
	 * @param int|string $fallback Fallback cache, e.g. (CACHE_NONE, "hash") (since 1.24)
	 * @return BagOStuff
	 * @deprecated 1.27
	 */
	public static function newAccelerator( $params = [], $fallback = null ) {
		if ( $fallback === null ) {
			if ( is_array( $params ) && isset( $params['fallback'] ) ) {
				$fallback = $params['fallback'];
			} elseif ( !is_array( $params ) ) {
				$fallback = $params;
			}
		}

		return self::getLocalServerInstance( $fallback );
	}

	/**
	 * Create a new cache object of the specified type.
	 *
	 * @since 1.26
	 * @param string $id A key in $wgWANObjectCaches.
	 * @return WANObjectCache
	 * @throws MWException
	 */
	public static function newWANCacheFromId( $id ) {
		global $wgWANObjectCaches;

		if ( !isset( $wgWANObjectCaches[$id] ) ) {
			throw new MWException( "Invalid object cache type \"$id\" requested. " .
				"It is not present in \$wgWANObjectCaches." );
		}

		$params = $wgWANObjectCaches[$id];
		foreach ( $params['channels'] as $action => $channel ) {
			$params['relayers'][$action] = MediaWikiServices::getInstance()->getEventRelayerGroup()
				->getRelayer( $channel );
			$params['channels'][$action] = $channel;
		}
		$params['cache'] = self::newFromId( $params['cacheId'] );
		if ( isset( $params['loggroup'] ) ) {
			$params['logger'] = LoggerFactory::getInstance( $params['loggroup'] );
		} else {
			$params['logger'] = LoggerFactory::getInstance( 'objectcache' );
		}
		$class = $params['class'];

		return new $class( $params );
	}

	/**
	 * Get the main cluster-local cache object.
	 *
	 * @since 1.27
	 * @return BagOStuff
	 */
	public static function getLocalClusterInstance() {
		global $wgMainCacheType;

		return self::getInstance( $wgMainCacheType );
	}

	/**
	 * Get the main WAN cache object.
	 *
	 * @since 1.26
	 * @return WANObjectCache
	 */
	public static function getMainWANInstance() {
		global $wgMainWANCache;

		return self::getWANInstance( $wgMainWANCache );
	}

	/**
	 * Get the cache object for the main stash.
	 *
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
	public static function getMainStashInstance() {
		global $wgMainStash;

		return self::getInstance( $wgMainStash );
	}

	/**
	 * Clear all the cached instances.
	 */
	public static function clear() {
		self::$instances = [];
		self::$wanInstances = [];
	}
}

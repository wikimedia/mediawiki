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
 * have to deal with stale reads. The latter may be eventually consistent, but
 * callers can use BagOStuff:READ_LATEST to see the latest available data.
 *
 * Primary entry points:
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
 * - ObjectCache::getInstance( $cacheType )
 *   Purpose: Special cases (like tiered memory/disk caches).
 *   Get a specific cache type by key in $wgObjectCaches.
 *
 * All the above BagOStuff cache instances have their makeKey()
 * method scoped to the *current* wiki ID. Use makeGlobalKey() to avoid this scoping
 * when using keys that need to be shared amongst wikis.
 *
 * @ingroup Cache
 */
class ObjectCache {
	/** @var BagOStuff[] Map of (id => BagOStuff) */
	public static $instances = [];

	/**
	 * Get a cached instance of the specified type of cache object.
	 *
	 * @param string|int $id A key in $wgObjectCaches.
	 * @return BagOStuff
	 */
	public static function getInstance( $id ) {
		if ( !isset( self::$instances[$id] ) ) {
			self::$instances[$id] = self::newFromId( $id );
		}

		return self::$instances[$id];
	}

	/**
	 * Create a new cache object of the specified type.
	 *
	 * @param string|int $id A key in $wgObjectCaches.
	 * @return BagOStuff
	 * @throws InvalidArgumentException
	 */
	private static function newFromId( $id ) {
		global $wgObjectCaches;

		if ( !isset( $wgObjectCaches[$id] ) ) {
			// Always recognize these ones
			if ( $id === CACHE_NONE ) {
				return new EmptyBagOStuff();
			} elseif ( $id === 'hash' ) {
				return new HashBagOStuff();
			}

			throw new InvalidArgumentException( "Invalid object cache type \"$id\" requested. " .
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
	private static function getDefaultKeyspace() {
		global $wgCachePrefix;

		$keyspace = $wgCachePrefix;
		if ( is_string( $keyspace ) && $keyspace !== '' ) {
			return $keyspace;
		}

		return WikiMap::getCurrentWikiDbDomain()->getId();
	}

	/**
	 * Create a new cache object from parameters.
	 *
	 * @param array $params Must have 'factory' or 'class' property.
	 *  - factory: Callback passed $params that returns BagOStuff.
	 *  - class: BagOStuff subclass constructed with $params.
	 *  - loggroup: Alias to set 'logger' key with LoggerFactory group.
	 *  - .. Other parameters passed to factory or class.
	 * @param Config|null $conf (Since 1.35)
	 * @return BagOStuff
	 * @throws InvalidArgumentException
	 */
	public static function newFromParams( array $params, Config $conf = null ) {
		// Apply default parameters and resolve the logger instance
		$params += [
			'logger' => LoggerFactory::getInstance( $params['loggroup'] ?? 'objectcache' ),
			'keyspace' => self::getDefaultKeyspace(),
			'asyncHandler' => [ DeferredUpdates::class, 'addCallableUpdate' ],
			'reportDupes' => true,
		];

		if ( isset( $params['factory'] ) ) {
			return call_user_func( $params['factory'], $params );
		}

		if ( !isset( $params['class'] ) ) {
			throw new InvalidArgumentException(
				'No "factory" nor "class" provided; got "' . print_r( $params, true ) . '"'
			);
		}

		$class = $params['class'];
		$conf = $conf ?? MediaWikiServices::getInstance()->getMainConfig();

		// Do b/c logic for SqlBagOStuff
		if ( is_a( $class, SqlBagOStuff::class, true ) ) {
			if ( isset( $params['server'] ) && !isset( $params['servers'] ) ) {
				$params['servers'] = [ $params['server'] ];
				unset( $params['server'] );
			}
			// In the past it was not required to set 'dbDirectory' in $wgObjectCaches
			if ( isset( $params['servers'] ) ) {
				foreach ( $params['servers'] as &$server ) {
					if ( $server['type'] === 'sqlite' && !isset( $server['dbDirectory'] ) ) {
						$server['dbDirectory'] = $conf->get( 'SQLiteDataDir' );
					}
				}
			} elseif ( !isset( $params['localKeyLB'] ) ) {
				$params['localKeyLB'] = [
					'factory' => function () {
						return MediaWikiServices::getInstance()->getDBLoadBalancer();
					}
				];
			}
		}

		// Do b/c logic for MemcachedBagOStuff
		if ( is_subclass_of( $class, MemcachedBagOStuff::class ) ) {
			$params += [
				'servers' => $conf->get( 'MemCachedServers' ),
				'persistent' => $conf->get( 'MemCachedPersistent' ),
				'timeout' => $conf->get( 'MemCachedTimeout' ),
			];
		}

		return new $class( $params );
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
			if ( $candidate !== CACHE_NONE && $candidate !== CACHE_ANYTHING ) {
				$cache = self::getInstance( $candidate );
				// CACHE_ACCEL might default to nothing if no APCu
				// See includes/ServiceWiring.php
				if ( !( $cache instanceof EmptyBagOStuff ) ) {
					return $cache;
				}
			}
		}

		if ( MediaWikiServices::getInstance()->isServiceDisabled( 'DBLoadBalancer' ) ) {
			// The LoadBalancer is disabled, probably because
			// MediaWikiServices::disableStorageBackend was called.
			$candidate = CACHE_NONE;
		} else {
			$candidate = CACHE_DB;
		}

		return self::getInstance( $candidate );
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
	 *     ObjectCache::getLocalServerInstance( [ 'fallback' => $fallbackType ] );
	 *
	 * @param int|string|array $fallback Fallback cache or parameter map with 'fallback'
	 * @return BagOStuff
	 * @throws InvalidArgumentException
	 * @since 1.27
	 */
	public static function getLocalServerInstance( $fallback = CACHE_NONE ) {
		$cache = MediaWikiServices::getInstance()->getLocalServerObjectCache();
		if ( $cache instanceof EmptyBagOStuff ) {
			if ( is_array( $fallback ) ) {
				$fallback = $fallback['fallback'] ?? CACHE_NONE;
			}
			$cache = self::getInstance( $fallback );
		}

		return $cache;
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
	 * Clear all the cached instances.
	 */
	public static function clear() {
		self::$instances = [];
	}

	/**
	 * Create a new BagOStuff instance for local-server caching.
	 *
	 * Only use this if you explicitly require the creation of
	 * a fresh instance. Whenever possible, use or inject the object
	 * from MediaWikiServices::getLocalServerObjectCache() instead.
	 *
	 * NOTE: This method is called very early via Setup.php by ExtensionRegistry,
	 * and thus must remain fairly standalone so as to not cause initialization
	 * of the MediaWikiServices singleton.
	 *
	 * @since 1.35
	 * @return BagOStuff
	 */
	public static function makeLocalServerCache() : BagOStuff {
		$params = [
			'reportDupes' => false,
			// Even simple caches must use a keyspace (T247562)
			'keyspace' => self::getDefaultKeyspace(),
		];
		if ( function_exists( 'apcu_fetch' ) ) {
			// Make sure the APCu methods actually store anything
			if ( PHP_SAPI !== 'cli' || ini_get( 'apc.enable_cli' ) ) {
				return new APCUBagOStuff( $params );
			}
		} elseif ( function_exists( 'wincache_ucache_get' ) ) {
			return new WinCacheBagOStuff( $params );
		}

		return new EmptyBagOStuff( $params );
	}

	/**
	 * Detects which local server cache library is present and returns a configuration for it.
	 *
	 * @since 1.32
	 * @deprecated since 1.35 Use MediaWikiServices::getLocalServerObjectCache() or
	 * ObjectCache::makeLocalServerCache() instead.
	 * @return int|string Index to cache in $wgObjectCaches
	 */
	public static function detectLocalServerCache() {
		wfDeprecated( __METHOD__, '1.35' );

		if ( function_exists( 'apcu_fetch' ) ) {
			// Make sure the APCu methods actually store anything
			if ( PHP_SAPI !== 'cli' || ini_get( 'apc.enable_cli' ) ) {
				return 'apcu';
			}
		} elseif ( function_exists( 'wincache_ucache_get' ) ) {
			return 'wincache';
		}

		return CACHE_NONE;
	}
}

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

use MediaWiki\Http\Telemetry;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\WikiMap\WikiMap;

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
	 * @internal for ObjectCacheTest
	 * @var string
	 */
	public static $localServerCacheClass;

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
			} elseif ( $id === CACHE_HASH ) {
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
	 * @param MediaWikiServices|null $services [internal]
	 * @return BagOStuff
	 */
	public static function newFromParams( array $params, MediaWikiServices $services = null ) {
		$services ??= MediaWikiServices::getInstance();
		$conf = $services->getMainConfig();

		// Apply default parameters and resolve the logger instance
		$params += [
			'logger' => LoggerFactory::getInstance( $params['loggroup'] ?? 'objectcache' ),
			'keyspace' => self::getDefaultKeyspace(),
			'asyncHandler' => [ DeferredUpdates::class, 'addCallableUpdate' ],
			'reportDupes' => true,
			'stats' => $services->getStatsdDataFactory(),
		];

		if ( isset( $params['factory'] ) ) {
			$args = $params['args'] ?? [ $params ];

			return call_user_func( $params['factory'], ...$args );
		}

		if ( !isset( $params['class'] ) ) {
			throw new InvalidArgumentException(
				'No "factory" nor "class" provided; got "' . print_r( $params, true ) . '"'
			);
		}

		$class = $params['class'];

		// Normalization and DI for SqlBagOStuff
		if ( is_a( $class, SqlBagOStuff::class, true ) ) {
			if ( isset( $params['globalKeyLB'] ) ) {
				throw new InvalidArgumentException(
					'globalKeyLB in $wgObjectCaches is no longer supported' );
			}
			if ( isset( $params['server'] ) && !isset( $params['servers'] ) ) {
				$params['servers'] = [ $params['server'] ];
				unset( $params['server'] );
			}
			if ( isset( $params['servers'] ) ) {
				// In the past it was not required to set 'dbDirectory' in $wgObjectCaches
				foreach ( $params['servers'] as &$server ) {
					if ( $server['type'] === 'sqlite' && !isset( $server['dbDirectory'] ) ) {
						$server['dbDirectory'] = $conf->get( MainConfigNames::SQLiteDataDir );
					}
				}
			} elseif ( isset( $params['cluster'] ) ) {
				$cluster = $params['cluster'];
				$params['loadBalancerCallback'] = static function () use ( $services, $cluster ) {
					return $services->getDBLoadBalancerFactory()->getExternalLB( $cluster );
				};
				$params += [ 'dbDomain' => false ];
			} else {
				$params['loadBalancerCallback'] = static function () use ( $services ) {
					return $services->getDBLoadBalancer();
				};
				$params += [ 'dbDomain' => false ];
			}
			$params += [ 'writeBatchSize' => $conf->get( MainConfigNames::UpdateRowsPerQuery ) ];
		}

		// Normalization and DI for MemcachedBagOStuff
		if ( is_subclass_of( $class, MemcachedBagOStuff::class ) ) {
			$params += [
				'servers' => $conf->get( MainConfigNames::MemCachedServers ),
				'persistent' => $conf->get( MainConfigNames::MemCachedPersistent ),
				'timeout' => $conf->get( MainConfigNames::MemCachedTimeout ),
			];
		}

		// Normalization and DI for MultiWriteBagOStuff
		if ( is_a( $class, MultiWriteBagOStuff::class, true ) ) {
			// Phan warns about foreach with non-array because it
			// thinks any key can be Closure|IBufferingStatsdDataFactory
			'@phan-var array{caches:array[]} $params';
			foreach ( $params['caches'] ?? [] as $i => $cacheInfo ) {
				// Ensure logger, keyspace, asyncHandler, etc are injected just as if
				// one of these was configured without MultiWriteBagOStuff.
				$params['caches'][$i] = self::newFromParams( $cacheInfo, $services );
			}
		}
		if ( is_a( $class, RESTBagOStuff::class, true ) ) {
			$params['telemetry'] = Telemetry::getInstance();
		}

		return new $class( $params );
	}

	/**
	 * Factory function for CACHE_ANYTHING (referenced by configuration)
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
		return self::getInstance( self::getAnythingId() );
	}

	/**
	 * Get the ID that will be used for CACHE_ANYTHING
	 * @return string|int
	 */
	private static function getAnythingId() {
		global $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType;
		$candidates = [ $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType ];
		foreach ( $candidates as $candidate ) {
			if ( $candidate === CACHE_ACCEL ) {
				// CACHE_ACCEL might default to nothing if no APCu
				// See includes/ServiceWiring.php
				$class = self::getLocalServerCacheClass();
				if ( $class !== EmptyBagOStuff::class ) {
					return $candidate;
				}
			} elseif ( $candidate !== CACHE_NONE && $candidate !== CACHE_ANYTHING ) {
				return $candidate;
			}
		}

		$services = MediaWikiServices::getInstance();

		if ( $services->isServiceDisabled( 'DBLoadBalancer' ) ) {
			// The DBLoadBalancer service is disabled, so we can't use the database!
			$candidate = CACHE_NONE;
		} elseif ( $services->isStorageDisabled() ) {
			// Storage services are disabled because MediaWikiServices::disableStorage()
			// was called. This is typically the case during installation.
			$candidate = CACHE_NONE;
		} else {
			$candidate = CACHE_DB;
		}
		return $candidate;
	}

	/**
	 * Factory function for CACHE_ACCEL (referenced from configuration)
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
		return MediaWikiServices::getInstance()->get( '_LocalClusterCache' );
	}

	/**
	 * Determine whether a config ID would access the database
	 *
	 * @param string|int $id A key in $wgObjectCaches
	 * @return bool
	 */
	public static function isDatabaseId( $id ) {
		global $wgObjectCaches;
		if ( !isset( $wgObjectCaches[$id] ) ) {
			return false;
		}
		$cache = $wgObjectCaches[$id];
		if ( ( $cache['class'] ?? '' ) === SqlBagOStuff::class ) {
			return true;
		}
		// Ideally we would inspect the config, but it's complicated. The ID is suggestive.
		if ( $id === 'db-replicated' ) {
			return true;
		}
		if ( ( $cache['factory'] ?? '' ) === 'ObjectCache::newAnything' ) {
			$id = self::getAnythingId();
			return self::isDatabaseId( $id );
		}
		return false;
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
	public static function makeLocalServerCache(): BagOStuff {
		$params = [
			'reportDupes' => false,
			// Even simple caches must use a keyspace (T247562)
			'keyspace' => self::getDefaultKeyspace(),
		];
		$class = self::getLocalServerCacheClass();
		return new $class( $params );
	}

	/**
	 * Get the class which will be used for the local server cache
	 * @return string
	 */
	private static function getLocalServerCacheClass() {
		if ( self::$localServerCacheClass !== null ) {
			return self::$localServerCacheClass;
		}
		if ( function_exists( 'apcu_fetch' ) ) {
			// Make sure the APCu methods actually store anything
			if ( PHP_SAPI !== 'cli' || ini_get( 'apc.enable_cli' ) ) {
				return APCUBagOStuff::class;

			}
		} elseif ( function_exists( 'wincache_ucache_get' ) ) {
			return WinCacheBagOStuff::class;
		}

		return EmptyBagOStuff::class;
	}
}

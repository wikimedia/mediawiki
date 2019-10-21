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
	 * @deprecated since 1.34 Use MediaWikiServices::getMainWANObjectCache instead
	 */
	public static function getWANInstance( $id ) {
		wfDeprecated( __METHOD__, '1.34' );
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
	 * @return BagOStuff
	 * @throws InvalidArgumentException
	 */
	public static function newFromParams( $params ) {
		$params['logger'] = $params['logger'] ??
			LoggerFactory::getInstance( $params['loggroup'] ?? 'objectcache' );
		if ( !isset( $params['keyspace'] ) ) {
			$params['keyspace'] = self::getDefaultKeyspace();
		}
		if ( isset( $params['factory'] ) ) {
			return call_user_func( $params['factory'], $params );
		} elseif ( isset( $params['class'] ) ) {
			$class = $params['class'];
			// Automatically set the 'async' update handler
			$params['asyncHandler'] = $params['asyncHandler']
				?? [ DeferredUpdates::class, 'addCallableUpdate' ];
			// Enable reportDupes by default
			$params['reportDupes'] = $params['reportDupes'] ?? true;
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
							$server['dbDirectory'] = MediaWikiServices::getInstance()
								->getMainConfig()->get( 'SQLiteDataDir' );
						}
					}
				}
			}

			// Do b/c logic for MemcachedBagOStuff
			if ( is_subclass_of( $class, MemcachedBagOStuff::class ) ) {
				if ( !isset( $params['servers'] ) ) {
					$params['servers'] = $GLOBALS['wgMemCachedServers'];
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
			throw new InvalidArgumentException( "The definition of cache type \""
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
	 * Create a new cache object of the specified type.
	 *
	 * @since 1.26
	 * @param string $id A key in $wgWANObjectCaches.
	 * @return WANObjectCache
	 * @throws UnexpectedValueException
	 */
	private static function newWANCacheFromId( $id ) {
		global $wgWANObjectCaches, $wgObjectCaches;

		if ( !isset( $wgWANObjectCaches[$id] ) ) {
			throw new UnexpectedValueException(
				"Cache type \"$id\" requested is not present in \$wgWANObjectCaches." );
		}

		$params = $wgWANObjectCaches[$id];
		if ( !isset( $wgObjectCaches[$params['cacheId']] ) ) {
			throw new UnexpectedValueException(
				"Cache type \"{$params['cacheId']}\" is not present in \$wgObjectCaches." );
		}
		$params['store'] = $wgObjectCaches[$params['cacheId']];

		return self::newWANCacheFromParams( $params );
	}

	/**
	 * Create a new cache object of the specified type.
	 *
	 * @since 1.28
	 * @param array $params
	 * @return WANObjectCache
	 * @throws UnexpectedValueException
	 * @suppress PhanTypeMismatchReturn
	 * @deprecated since 1.34 Use MediaWikiServices::getMainWANObjectCache
	 *  instead or use WANObjectCache::__construct directly
	 */
	public static function newWANCacheFromParams( array $params ) {
		wfDeprecated( __METHOD__, '1.34' );
		global $wgCommandLineMode, $wgSecretKey;

		$services = MediaWikiServices::getInstance();
		$params['cache'] = self::newFromParams( $params['store'] );
		$params['logger'] = LoggerFactory::getInstance( $params['loggroup'] ?? 'objectcache' );
		if ( !$wgCommandLineMode ) {
			// Send the statsd data post-send on HTTP requests; avoid in CLI mode (T181385)
			$params['stats'] = $services->getStatsdDataFactory();
			// Let pre-emptive refreshes happen post-send on HTTP requests
			$params['asyncHandler'] = [ DeferredUpdates::class, 'addCallableUpdate' ];
		}
		$params['secret'] = $params['secret'] ?? $wgSecretKey;
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
	 * Clear all the cached instances.
	 */
	public static function clear() {
		self::$instances = [];
		self::$wanInstances = [];
	}

	/**
	 * Detects which local server cache library is present and returns a configuration for it
	 * @since 1.32
	 *
	 * @return int|string Index to cache in $wgObjectCaches
	 */
	public static function detectLocalServerCache() {
		if ( function_exists( 'apcu_fetch' ) ) {
			// Make sure the APCu methods actually store anything
			if ( PHP_SAPI !== 'cli' || ini_get( 'apc.enable_cli' ) ) {
				return 'apcu';
			}
		} elseif ( function_exists( 'apc_fetch' ) ) {
			// Make sure the APC methods actually store anything
			if ( PHP_SAPI !== 'cli' || ini_get( 'apc.enable_cli' ) ) {
				return 'apc';
			}
		} elseif ( function_exists( 'wincache_ucache_get' ) ) {
			return 'wincache';
		}

		return CACHE_NONE;
	}
}

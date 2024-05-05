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

use MediaWiki\MediaWikiServices;

/**
 * @see ObjectCacheFactory
 * @ingroup Cache
 */
class ObjectCache {
	/**
	 * @deprecated since 1.43; use ObjectCacheFactory instead.
	 * @var BagOStuff[] Map of (id => BagOStuff)
	 */
	public static $instances = [];

	/**
	 * @internal for ObjectCacheTest
	 * @var string
	 */
	public static $localServerCacheClass;

	/**
	 * Get a cached instance of the specified type of cache object.
	 *
	 * @deprecated since 1.43; use ObjectCacheFactory::getInstance instead.
	 *
	 * @param string|int $id A key in $wgObjectCaches.
	 * @return BagOStuff
	 */
	public static function getInstance( $id ) {
		return MediaWikiServices::getInstance()->getObjectCacheFactory()->getInstance( $id );
	}

	/**
	 * @see ObjectCacheFactory::newFromParams()
	 *
	 * @deprecated since 1.42, Use ObjectCacheFactory::newFromParams instead.
	 * @param array $params
	 *
	 * @return BagOStuff
	 */
	public static function newFromParams( array $params ) {
		return MediaWikiServices::getInstance()->getObjectCacheFactory()
			->newFromParams( $params );
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
	 * @deprecated since 1.42,
	 *     Use ObjectCacheFactory::getInstance( ObjectCache::getAnythingId() );
	 *
	 * @return BagOStuff
	 */
	public static function newAnything() {
		return MediaWikiServices::getInstance()->getObjectCacheFactory()
			->getInstance( self::getAnythingId() );
	}

	/**
	 * @internal Used by ObjectCacheFactory and ObjectCache.
	 *
	 * Get the ID that will be used for CACHE_ANYTHING
	 * @return string|int
	 */
	public static function getAnythingId() {
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
	 * @deprecated since 1.42, Use ObjectCacheFactory::getLocalServerInstance()
	 * @param int|string|array $fallback Fallback cache or parameter map with 'fallback'
	 * @return BagOStuff
	 * @throws InvalidArgumentException
	 * @since 1.27
	 */
	public static function getLocalServerInstance( $fallback = CACHE_NONE ) {
		return MediaWikiServices::getInstance()->getObjectCacheFactory()
			->getLocalServerInstance( $fallback );
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

		// NOTE: Sanity check if $id is set to CACHE_ANYTHING and
		// everything is going through service wiring. CACHE_ANYTHING
		// would default to CACHE_DB, let's handle that early for cases
		// where all cache configs are set to CACHE_ANYTHING (T362686).
		if ( $id === CACHE_ANYTHING ) {
			$id = self::getAnythingId();
			return self::isDatabaseId( $id );
		}

		if ( !isset( $wgObjectCaches[$id] ) ) {
			return false;
		}
		$cache = $wgObjectCaches[$id];
		if ( ( $cache['class'] ?? '' ) === SqlBagOStuff::class ) {
			return true;
		}

		return false;
	}

	/**
	 * @deprecated since 1.42, Use ObjectCacheFactory::clear() instead.
	 *
	 * Clear all the cached instances.
	 */
	public static function clear() {
		MediaWikiServices::getInstance()->getObjectCacheFactory()->clear();
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
	 * @internal For use by ServiceWiring and ExtensionRegistry. There are use
	 *   cases whereby we want to build up local server cache without service
	 *   wiring available.
	 * @since 1.35
	 * @param string $keyspace
	 * @return BagOStuff
	 */
	public static function makeLocalServerCache( $keyspace ): BagOStuff {
		$params = [
			'reportDupes' => false,
			// Even simple caches must use a keyspace (T247562)
			'keyspace' => $keyspace,
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

<?php
/**
 * Functions to get cache objects.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Cache
 */

use MediaWiki\MediaWikiServices;
use Wikimedia\ObjectCache\BagOStuff;

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
	 * @deprecated since 1.42, Use ObjectCacheFactory::getInstance( CACHE_ANYTHING );
	 *
	 * @return BagOStuff
	 */
	public static function newAnything() {
		return MediaWikiServices::getInstance()->getObjectCacheFactory()
			->getInstance( CACHE_ANYTHING );
	}

	/**
	 * @deprecated since 1.42, Use ObjectCacheFactory::getLocalServerInstance()
	 * @param int|string|array $fallback Fallback cache or parameter map with 'fallback'
	 * @return BagOStuff
	 * @since 1.27
	 */
	public static function getLocalServerInstance( $fallback = CACHE_NONE ) {
		return MediaWikiServices::getInstance()->getObjectCacheFactory()
			->getLocalServerInstance( $fallback );
	}

	/**
	 * Get the main cluster-local cache object.
	 *
	 * @deprecated since 1.43, Use ObjectCacheFactory::getLocalClusterInstance()
	 *
	 * @since 1.27
	 * @return BagOStuff
	 */
	public static function getLocalClusterInstance() {
		return MediaWikiServices::getInstance()->getObjectCacheFactory()
			->getLocalClusterInstance();
	}

	/**
	 * @deprecated since 1.42, Use ObjectCacheFactory::clear() instead.
	 *
	 * Clear all the cached instances.
	 */
	public static function clear() {
		MediaWikiServices::getInstance()->getObjectCacheFactory()->clear();
	}
}

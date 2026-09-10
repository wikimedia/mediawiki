<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\ObjectCache;

/**
 * Source of fluent builders for WANObjectCache::getWithSetCallback() calls
 *
 * @ingroup Cache
 * @since 1.47
 */
interface IWANCacheBuilder {
	/**
	 * Create a builder for a getWithSetCallback() call
	 *
	 * This is the preferred way to call getWithSetCallback(): the builder makes the cache key
	 * and names each of the options, so that a call reads as prose rather than as an options
	 * map that has to be looked up.
	 *
	 * @code
	 *     $stats = $cache->buildGetWithSetCallback()
	 *         ->key( 'language-stats' )
	 *         ->keepIndefinitely()
	 *         ->invalidatedByKey( 'language-stats' )
	 *         ->shortProcessCache()
	 *         ->getWithSetCallback( static function () {
	 *             return self::getAllLanguageStats();
	 *         } );
	 * @endcode
	 *
	 * @see WANGetWithSetCallbackBuilder
	 * @since 1.47
	 * @return WANGetWithSetCallbackBuilder
	 */
	public function buildGetWithSetCallback(): WANGetWithSetCallbackBuilder;
}

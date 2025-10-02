<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Page;

use LogicException;
use MediaWiki\DAO\WikiAwareEntity;
use Wikimedia\Parsoid\Core\LinkTarget;

/**
 * Helper class for mapping page value objects to a string key.
 *
 * This logic should not reside in a class like PageStoreRecord or Title, because:
 *
 * 1. These value object should not contain care about caching in other classes.
 *
 * 2. We type against interfaces like PageReference and LinkTarget, and for
 *    caching to work, all implementations would have to derive cache keys in the
 *    exact same way. Otherwise, caches will break when different implementations
 *    are passed to the same cache.
 *
 * Furthermore, the logic for deriving cache keys also should not reside in a service class,
 * because there must only ever be one implementation, it must not depend on configuration,
 * and it may never change.
 *
 * @since 1.37
 * @ingroup Page
 */
abstract class CacheKeyHelper {

	/**
	 * @return never
	 */
	private function __construct() {
		throw new LogicException( 'May not be instantiated' );
	}

	/**
	 * Returns a stable key for identifying the given page in a cache.
	 * The return value takes into account the page's DB key, namespace and
	 * wiki ID or interwiki prefix. It is suitable for use with
	 * BagOStuff::makeKey and BagOStuff::makeGlobalKey.
	 *
	 * @note The key that this method returns for a given page should never change.
	 * Changing the return value may have sever impact on deployment, as it would
	 * cause caches that rely on this method to become effectively "cold" (empty).
	 *
	 * @param LinkTarget|PageReference $page
	 * @return string A string suitable for identifying the given page. Callers
	 *         should not attempt to extract information from this value, it
	 *         should be treated as opaque (but stable).
	 */
	public static function getKeyForPage( $page ): string {
		$prefix = 'ns' . $page->getNamespace();

		if ( $page instanceof WikiAwareEntity && $page->getWikiId() !== WikiAwareEntity::LOCAL ) {
			$prefix .= '@id@' . $page->getWikiId();
		} elseif ( $page instanceof LinkTarget && $page->getInterwiki() !== '' ) {
			$prefix .= '@iw@' . $page->getInterwiki();
		}

		return $prefix . ':' . $page->getDBkey();
	}
}

/** @deprecated class alias since 1.45 */
class_alias( CacheKeyHelper::class, 'MediaWiki\Cache\CacheKeyHelper' );

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

namespace MediaWiki\Page;

use LogicException;
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
	 * @param LinkTarget|PageReference $page
	 * @return string
	 */
	public static function getKeyForPage( $page ): string {
		return 'ns' . $page->getNamespace() . ':' . $page->getDBkey();
	}
}

/** @deprecated class alias since 1.45 */
class_alias( CacheKeyHelper::class, 'MediaWiki\Cache\CacheKeyHelper' );

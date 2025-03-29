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

namespace MediaWiki\Cache;

use LogicException;
use MediaWiki\Page\PageReference;
use Wikimedia\Parsoid\Core\LinkTarget;

/**
 * Helper class for mapping value objects representing basic entities to cache keys.
 *
 * Rationale:
 * The logic for deriving the cache key should not be in the value object themselves for two reasons:
 * Firstly, the value object should not contain knowledge about caching or keys in general.
 * Secondly, all implementations of a given interface must have the exact same logic for deriving
 * the cache key. Otherwise, caches will break when different implementations are used when
 * interacting with a cache.
 *
 * Furthermore, the logic for deriving cache keys should not be in a service instance: there can
 * only ever be one implementation, it must not depend on configuration, and it should never change.
 *
 * @ingroup Cache
 */
abstract class CacheKeyHelper {

	/**
	 * Private constructor to defy instantiation.
	 * @return never
	 */
	private function __construct() {
		// we should never even get here...
		throw new LogicException( 'Should not instantiate ' . __CLASS__ );
	}

	/**
	 * @param LinkTarget|PageReference $page
	 *
	 * @return string
	 */
	public static function getKeyForPage( $page ): string {
		return 'ns' . $page->getNamespace() . ':' . $page->getDBkey();
	}
}

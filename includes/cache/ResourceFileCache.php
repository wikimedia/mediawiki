<?php
/**
 * Resource loader request result caching in the file system.
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

/**
 * Resource loader request result caching in the file system.
 *
 * @ingroup Cache
 */
class ResourceFileCache extends FileCacheBase {
	protected $mCacheWorthy;

	/* @todo configurable? */
	const MISS_THRESHOLD = 360; // 6/min * 60 min

	/**
	 * Construct an ResourceFileCache from a context
	 * @param $context ResourceLoaderContext
	 * @return ResourceFileCache
	 */
	public static function newFromContext( ResourceLoaderContext $context ) {
		$cache = new self();

		if ( $context->getOnly() === 'styles' ) {
			$cache->mType = 'css';
		} else {
			$cache->mType = 'js';
		}
		$modules = array_unique( $context->getModules() ); // remove duplicates
		sort( $modules ); // normalize the order (permutation => combination)
		$cache->mKey = sha1( $context->getHash() . implode( '|', $modules ) );
		if ( count( $modules ) == 1 ) {
			$cache->mCacheWorthy = true; // won't take up much space
		}

		return $cache;
	}

	/**
	 * Check if an RL request can be cached.
	 * Caller is responsible for checking if any modules are private.
	 * @param $context ResourceLoaderContext
	 * @return bool
	 */
	public static function useFileCache( ResourceLoaderContext $context ) {
		global $wgUseFileCache, $wgDefaultSkin, $wgLanguageCode;
		if ( !$wgUseFileCache ) {
			return false;
		}
		// Get all query values
		$queryVals = $context->getRequest()->getValues();
		foreach ( $queryVals as $query => $val ) {
			if ( $query === 'modules' || $query === 'version' || $query === '*' ) {
				continue; // note: &* added as IE fix
			} elseif ( $query === 'skin' && $val === $wgDefaultSkin ) {
				continue;
			} elseif ( $query === 'lang' && $val === $wgLanguageCode ) {
				continue;
			} elseif ( $query === 'only' && in_array( $val, array( 'styles', 'scripts' ) ) ) {
				continue;
			} elseif ( $query === 'debug' && $val === 'false' ) {
				continue;
			}

			return false;
		}

		return true; // cacheable
	}

	/**
	 * Get the base file cache directory
	 * @return string
	 */
	protected function cacheDirectory() {
		return $this->baseCacheDirectory() . '/resources';
	}

	/**
	 * Item has many recent cache misses
	 * @return bool
	 */
	public function isCacheWorthy() {
		if ( $this->mCacheWorthy === null ) {
			$this->mCacheWorthy = (
				$this->isCached() || // even stale cache indicates it was cache worthy
				$this->getMissesRecent() >= self::MISS_THRESHOLD // many misses
			);
		}

		return $this->mCacheWorthy;
	}
}

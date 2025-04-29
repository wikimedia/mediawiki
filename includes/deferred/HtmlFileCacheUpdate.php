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

namespace MediaWiki\Deferred;

use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Cache\HTMLFileCache;
use MediaWiki\Page\PageIdentity;
use Wikimedia\Assert\Assert;

/**
 * HTMLFileCache purge update for a set of titles
 *
 * @ingroup Cache
 * @since 1.35
 */
class HtmlFileCacheUpdate implements DeferrableUpdate, MergeableUpdate {
	/** @var PageIdentity[] List of pages */
	private $pages;

	/**
	 * @param PageIdentity[] $pages List of pages
	 */
	private function __construct( array $pages ) {
		$this->pages = $pages;
	}

	public function merge( MergeableUpdate $update ) {
		/** @var self $update */
		Assert::parameterType( __CLASS__, $update, '$update' );
		'@phan-var self $update';

		$this->pages = array_merge( $this->pages, $update->pages );
	}

	/**
	 * @since 1.37
	 * @param iterable<PageIdentity> $pages PageIdentity instances
	 *
	 * @return HtmlFileCacheUpdate
	 */
	public static function newFromPages( $pages ) {
		$pagesByKey = [];
		foreach ( $pages as $pg ) {
			$key = CacheKeyHelper::getKeyForPage( $pg );
			$pagesByKey[$key] = $pg;
		}

		return new self( $pagesByKey );
	}

	public function doUpdate() {
		foreach ( $this->pages as $pg ) {
			HTMLFileCache::clearFileCache( $pg );
		}
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HtmlFileCacheUpdate::class, 'HtmlFileCacheUpdate' );

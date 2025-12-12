<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Deferred;

use MediaWiki\Cache\HTMLFileCache;
use MediaWiki\Page\CacheKeyHelper;
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

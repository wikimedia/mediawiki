<?php

namespace MediaWiki\Edit;

use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use Wikimedia\Parsoid\Core\PageBundle;

/**
 * Interface for saving and retrieval of Parsoid HTML and
 * Parsoid metadata from storage.
 *
 * @since 1.39
 */
interface ParsoidOutputStash {

	/**
	 * Stash a PageBundle representing a rendering of a revision at a given point in time.
	 *
	 * The given PageBundle can for some time later be retrieved by calling get( $renderId ).
	 *
	 * @param ParsoidRenderID $renderId Combination of revision ID and revision's time ID
	 * @param PageBundle $bundle
	 *
	 * @return bool True on success
	 */
	public function set( ParsoidRenderID $renderId, PageBundle $bundle ): bool;

	/**
	 * Retrieve a page bundle (that was previously put in the stash using the ->set() method)
	 * from the stash using a unique render ID.
	 *
	 * The page bundle stays in the stash for some time and not guaranteed to be persistent
	 * across requests.
	 *
	 * @param ParsoidRenderID $renderId
	 *
	 * @return ?PageBundle
	 */
	public function get( ParsoidRenderID $renderId ): ?PageBundle;

}

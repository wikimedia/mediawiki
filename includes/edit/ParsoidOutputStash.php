<?php

namespace MediaWiki\Edit;

use Wikimedia\Parsoid\Core\SelserData;

/**
 * Stash for Parsoid output and associated data as needed to perform selective serialization (aka "selser")
 * of modified HTML.
 *
 * @see SelserData
 *
 * @internal
 * @since 1.39
 */
interface ParsoidOutputStash {

	/**
	 * Stash a SelserContext representing a rendering of a revision at a given point in time,
	 * along with information about the content the rendering was based on.
	 *
	 * A SelserContext stashed by calling this method can for some time be retrieved by
	 * calling the get() method.
	 *
	 * @param ParsoidRenderID $renderId Combination of revision ID and revision's time ID
	 * @param SelserContext $selserContext
	 *
	 * @return bool True on success
	 */
	public function set( ParsoidRenderID $renderId, SelserContext $selserContext ): bool;

	/**
	 * Retrieve a SelserContext representing a rendering of a revision at a given point in time,
	 * along with information about the content the rendering was based on.
	 *
	 * If a SelserContext was stahed using the set() method not too long ago, it can be expected
	 * to be returned from this method.
	 *
	 * @param ParsoidRenderID $renderId
	 *
	 * @return ?SelserContext
	 */
	public function get( ParsoidRenderID $renderId ): ?SelserContext;

}

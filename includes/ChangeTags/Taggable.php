<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace MediaWiki\ChangeTags;

/**
 * Interface that defines how to tag objects
 *
 * @since 1.33
 * @ingroup ChangeTags
 * @author Piotr Miazga
 */
interface Taggable {

	/**
	 * Append tags to the tagged object
	 *
	 * @since 1.33
	 * @param string|string[] $tags Tags to apply
	 */
	public function addTags( $tags );

}

<?php

namespace MediaWiki\Search\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SearchResultsAugmentHook {
	/**
	 * Allows extension to add its code to the list of search
	 * result augmentors.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$setAugmentors List of whole-set augmentor objects, must implement
	 *   ResultSetAugmentor.
	 * @param ?mixed &$rowAugmentors List of per-row augmentor objects, must implement
	 *   ResultAugmentor.
	 *   Note that lists should be in the format name => object and the names in both
	 *   lists should be distinct.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSearchResultsAugment( &$setAugmentors, &$rowAugmentors );
}

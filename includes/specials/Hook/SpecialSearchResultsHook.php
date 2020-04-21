<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialSearchResultsHook {
	/**
	 * Called before search result display
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $term string of search term
	 * @param ?mixed &$titleMatches empty or ISearchResultSet object
	 * @param ?mixed &$textMatches empty or ISearchResultSet object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchResults( $term, &$titleMatches, &$textMatches );
}

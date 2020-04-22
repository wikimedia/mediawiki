<?php

namespace MediaWiki\Hook;

use ISearchResultSet;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialSearchResultsHook {
	/**
	 * This hook is called before search result display
	 *
	 * @since 1.35
	 *
	 * @param string $term string of search term
	 * @param ?ISearchResultSet &$titleMatches empty or ISearchResultSet object
	 * @param ?ISearchResultSet &$textMatches empty or ISearchResultSet object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchResults( $term, &$titleMatches, &$textMatches );
}

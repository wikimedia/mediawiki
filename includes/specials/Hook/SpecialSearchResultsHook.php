<?php

namespace MediaWiki\Hook;

use ISearchResultSet;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialSearchResultsHook {
	/**
	 * This hook is called before search result display
	 *
	 * @since 1.35
	 *
	 * @param string $term Search term
	 * @param ?ISearchResultSet &$titleMatches Empty or ISearchResultSet object
	 * @param ?ISearchResultSet &$textMatches Empty or ISearchResultSet object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchResults( $term, &$titleMatches, &$textMatches );
}

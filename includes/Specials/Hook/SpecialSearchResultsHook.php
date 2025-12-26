<?php

namespace MediaWiki\Specials\Hook;

use MediaWiki\Search\ISearchResultSet;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialSearchResults" to register handlers implementing this interface.
 *
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

/** @deprecated class alias since 1.46 */
class_alias( SpecialSearchResultsHook::class, 'MediaWiki\\Hook\\SpecialSearchResultsHook' );

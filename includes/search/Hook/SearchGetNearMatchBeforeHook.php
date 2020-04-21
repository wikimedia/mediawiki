<?php

namespace MediaWiki\Search\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SearchGetNearMatchBeforeHook {
	/**
	 * Perform exact-title-matches in "go" searches before
	 * the normal operations.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $allSearchTerms Array of the search terms in all content languages
	 * @param ?mixed &$titleResult Outparam; the value to return. A Title object or null.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSearchGetNearMatchBefore( $allSearchTerms, &$titleResult );
}

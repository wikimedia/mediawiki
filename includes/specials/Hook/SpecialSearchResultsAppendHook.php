<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialSearchResultsAppendHook {
	/**
	 * Called immediately before returning HTML
	 * on the search results page.  Useful for including a feedback link.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $specialSearch SpecialSearch object ($this)
	 * @param ?mixed $output $wgOut
	 * @param ?mixed $term Search term specified by the user
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchResultsAppend( $specialSearch, $output, $term );
}

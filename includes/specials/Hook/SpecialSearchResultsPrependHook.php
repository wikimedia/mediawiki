<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialSearchResultsPrependHook {
	/**
	 * Called immediately before returning HTML
	 * on the search results page.  Useful for including an external search
	 * provider.  To disable the output of MediaWiki search output, return
	 * false.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $specialSearch SpecialSearch object ($this)
	 * @param ?mixed $output $wgOut
	 * @param ?mixed $term Search term specified by the user
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchResultsPrepend( $specialSearch, $output, $term );
}

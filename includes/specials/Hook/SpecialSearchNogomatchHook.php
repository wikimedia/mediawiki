<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialSearchNogomatchHook {
	/**
	 * Called when the 'Go' feature is triggered (generally
	 * from autocomplete search other than the main bar on Special:Search) and the
	 * target doesn't exist. Full text search results are generated after this hook is
	 * called.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$title title object generated from the text entered by the user
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchNogomatch( &$title );
}

<?php

namespace MediaWiki\Hook;

use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialSearchNogomatchHook {
	/**
	 * This hook is called when the 'Go' feature is triggered and the target doesn't exist.
	 *
	 * This generally comes from autocomplete search other than the main bar on Special:Search.
	 * Full text search results are generated after this hook is called.
	 *
	 * @since 1.35
	 *
	 * @param Title &$title Title object generated from the text entered by the user
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchNogomatch( &$title );
}

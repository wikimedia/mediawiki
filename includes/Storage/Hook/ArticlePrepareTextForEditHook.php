<?php

namespace MediaWiki\Storage\Hook;

use ParserOptions;
use WikiPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ArticlePrepareTextForEdit" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticlePrepareTextForEditHook {
	/**
	 * This hook is called when preparing text to be saved.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikiPage WikiPage being saved
	 * @param ParserOptions $popts Parser options to be used for pre-save transformation
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticlePrepareTextForEdit( $wikiPage, $popts );
}

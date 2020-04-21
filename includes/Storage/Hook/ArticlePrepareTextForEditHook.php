<?php

namespace MediaWiki\Storage\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticlePrepareTextForEditHook {
	/**
	 * Called when preparing text to be saved.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage the WikiPage being saved
	 * @param ?mixed $popts parser options to be used for pre-save transformation
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticlePrepareTextForEdit( $wikiPage, $popts );
}

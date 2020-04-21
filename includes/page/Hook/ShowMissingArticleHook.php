<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ShowMissingArticleHook {
	/**
	 * Called when generating the output for a non-existent page.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $article The article object corresponding to the page
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onShowMissingArticle( $article );
}

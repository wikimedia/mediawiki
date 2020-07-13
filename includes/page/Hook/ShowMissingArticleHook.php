<?php

namespace MediaWiki\Page\Hook;

use Article;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ShowMissingArticleHook {
	/**
	 * This hook is called when generating the output for a non-existent page.
	 *
	 * @since 1.35
	 *
	 * @param Article $article Article corresponding to the page
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onShowMissingArticle( $article );
}

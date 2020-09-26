<?php

namespace MediaWiki\Page\Hook;

use Article;
use OutputPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ArticleConfirmDelete" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleConfirmDeleteHook {
	/**
	 * This hook is called before writing the confirmation form for article
	 * deletion.
	 *
	 * @since 1.35
	 *
	 * @param Article $article Article being deleted
	 * @param OutputPage $output
	 * @param string &$reason Reason the article is being deleted
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleConfirmDelete( $article, $output, &$reason );
}

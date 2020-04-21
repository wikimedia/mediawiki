<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleConfirmDeleteHook {
	/**
	 * Before writing the confirmation form for article
	 * deletion.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $article the article (object) being deleted
	 * @param ?mixed $output the OutputPage object
	 * @param ?mixed &$reason the reason (string) the article is being deleted
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleConfirmDelete( $article, $output, &$reason );
}

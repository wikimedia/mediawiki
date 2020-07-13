<?php

namespace MediaWiki\Page\Hook;

use Article;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleShowPatrolFooterHook {
	/**
	 * This hook is called at the beginning of Article#showPatrolFooter.
	 * Use this hook to not show the [mark as patrolled] link in certain
	 * circumstances.
	 *
	 * @since 1.35
	 *
	 * @param Article $article
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleShowPatrolFooter( $article );
}

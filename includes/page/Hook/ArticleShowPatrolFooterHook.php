<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleShowPatrolFooterHook {
	/**
	 * Called at the beginning of Article#showPatrolFooter.
	 * Extensions can use this to not show the [mark as patrolled] link in certain
	 * circumstances.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $article the Article object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleShowPatrolFooter( $article );
}

<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleRollbackCompleteHook {
	/**
	 * After an article rollback is completed.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage the WikiPage that was edited
	 * @param ?mixed $user the user who did the rollback
	 * @param ?mixed $revision the revision the page was reverted back to
	 * @param ?mixed $current the reverted revision
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleRollbackComplete( $wikiPage, $user, $revision,
		$current
	);
}

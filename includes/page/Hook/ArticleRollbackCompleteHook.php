<?php

namespace MediaWiki\Page\Hook;

use Revision;
use User;
use WikiPage;

/**
 * @deprecated since 1.35. Use RollbackComplete instead.
 * @ingroup Hooks
 */
interface ArticleRollbackCompleteHook {
	/**
	 * This hook is called after an article rollback is completed.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikiPage WikiPage that was edited
	 * @param User $user User who did the rollback
	 * @param Revision $revision Revision the page was reverted back to
	 * @param Revision $current Reverted revision
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleRollbackComplete( $wikiPage, $user, $revision,
		$current
	);
}

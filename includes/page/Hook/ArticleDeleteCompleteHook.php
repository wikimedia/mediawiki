<?php

namespace MediaWiki\Page\Hook;

use Content;
use ManualLogEntry;
use User;
use WikiPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleDeleteCompleteHook {
	/**
	 * This hook is called after an article is deleted.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikiPage WikiPage that was deleted
	 * @param User $user User that deleted the article
	 * @param string $reason Reason the article was deleted
	 * @param int $id ID of the article that was deleted
	 * @param Content|null $content Content of the deleted page (or null, when deleting a broken page)
	 * @param ManualLogEntry $logEntry ManualLogEntry used to record the deletion
	 * @param int $archivedRevisionCount Number of revisions archived during the deletion
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleDeleteComplete( $wikiPage, $user, $reason, $id,
		$content, $logEntry, $archivedRevisionCount
	);
}

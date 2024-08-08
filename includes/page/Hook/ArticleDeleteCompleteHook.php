<?php

namespace MediaWiki\Page\Hook;

use ManualLogEntry;
use MediaWiki\Content\Content;
use MediaWiki\User\User;
use WikiPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ArticleDeleteComplete" to register handlers implementing this interface.
 *
 * @ingroup Hooks
 * @deprecated since 1.37, use PageDeleteCompleteHook instead. The new hook uses more modern typehints and replaces
 * the Content object with a RevisionRecord.
 */
interface ArticleDeleteCompleteHook {
	/**
	 * This hook is called after an article is deleted.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikiPage WikiPage that was deleted.
	 *   This object represents state before deletion (e.g. $wikiPage->exists() will return true).
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

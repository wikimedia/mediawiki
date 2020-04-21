<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleDeleteCompleteHook {
	/**
	 * After an article is deleted.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage the WikiPage that was deleted
	 * @param ?mixed $user the user that deleted the article
	 * @param ?mixed $reason the reason the article was deleted
	 * @param ?mixed $id id of the article that was deleted
	 * @param ?mixed $content the Content of the deleted page (or null, when deleting a broken page)
	 * @param ?mixed $logEntry the ManualLogEntry used to record the deletion
	 * @param ?mixed $archivedRevisionCount the number of revisions archived during the deletion
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleDeleteComplete( $wikiPage, $user, $reason, $id,
		$content, $logEntry, $archivedRevisionCount
	);
}

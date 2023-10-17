<?php

namespace MediaWiki\Page\Hook;

use ManualLogEntry;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\RevisionRecord;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "PageDeleteComplete" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface PageDeleteCompleteHook {
	/**
	 * This hook is called after a page is deleted.
	 *
	 * @since 1.37
	 *
	 * @param ProperPageIdentity $page Page that was deleted.
	 *   This object represents state before deletion (e.g. $page->exists() will return true).
	 * @param Authority $deleter Who deleted the page
	 * @param string $reason Reason the page was deleted
	 * @param int $pageID ID of the page that was deleted
	 * @param RevisionRecord $deletedRev Last revision of the deleted page
	 * @param ManualLogEntry $logEntry ManualLogEntry used to record the deletion
	 * @param int $archivedRevisionCount Number of revisions archived during the deletion
	 * @return true|void
	 */
	public function onPageDeleteComplete(
		ProperPageIdentity $page,
		Authority $deleter,
		string $reason,
		int $pageID,
		RevisionRecord $deletedRev,
		ManualLogEntry $logEntry,
		int $archivedRevisionCount
	);
}

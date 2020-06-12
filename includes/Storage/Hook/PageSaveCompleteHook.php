<?php

namespace MediaWiki\Storage\Hook;

use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentity;
use WikiPage;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PageSaveCompleteHook {
	/**
	 * This hook is called after an article has been updated.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikiPage WikiPage modified
	 * @param UserIdentity $user User performing the modification
	 * @param string $summary Edit summary/comment
	 * @param int $flags Flags passed to WikiPage::doEditContent()
	 * @param RevisionRecord $revisionRecord New RevisionRecord of the article
	 * @param int|bool $originalRevId If the edit restores or repeats an earlier revision (such as a
	 *   rollback or a null revision), the ID of that earlier revision. False otherwise.
	 *   (Used to be called $baseRevId.)
	 * @param int $undidRevId Rev ID (or 0) this edit undid
	 * @return bool|void True or no return value to continue or false to stop other hook handlers
	 *    from being called; save cannot be aborted
	 */
	public function onPageSaveComplete(
		$wikiPage,
		$user,
		$summary,
		$flags,
		$revisionRecord,
		$originalRevId,
		$undidRevId
	);
}

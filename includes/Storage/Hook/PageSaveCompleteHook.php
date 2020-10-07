<?php

namespace MediaWiki\Storage\Hook;

use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\User\UserIdentity;
use WikiPage;

/**
 * @stable to implement
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
	 * @param EditResult $editResult Object storing information about the effects of this edit,
	 *   including which edits were reverted and which edit is this based on (for reverts and null
	 *   edits).
	 * @return bool|void True or no return value to continue or false to stop other hook handlers
	 *    from being called; save cannot be aborted
	 */
	public function onPageSaveComplete(
		$wikiPage,
		$user,
		$summary,
		$flags,
		$revisionRecord,
		$editResult
	);
}

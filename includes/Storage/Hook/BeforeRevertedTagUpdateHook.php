<?php

namespace MediaWiki\Storage\Hook;

use CommentStoreComment;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\User\UserIdentity;
use WikiPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "BeforeRevertedTagUpdate" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface BeforeRevertedTagUpdateHook {
	/**
	 * This hook is called before scheduling a RevertedTagUpdateJob.
	 *
	 * Various content management extensions that involve some kind of approval mechanism
	 * for edits can use this to indicate that the RevertedTagUpdate should not be performed
	 * right after the edit is made, but rather it should wait for the edit to be approved.
	 * To delay the execution of the update simply implement this hook and set the $approved
	 * parameter to false when the user does not have an "autoreview" user right or similar.
	 *
	 * The update can be later rescheduled using RevertedTagUpdateManager. In your code
	 * that marks an edit as "approved" use:
	 *
	 *  ```php
	 *  $revertedTagUpdateManager =
	 *    MediaWikiServices::getInstance()->getRevertedTagUpdateManager();
	 *  $revertedTagUpdateManager->approveRevertedTagForRevision( $acceptedRevisionId );
	 *  ```
	 *
	 * And that's it.
	 *
	 * There should be no adverse effects due to enqueueing the same update multiple times.
	 *
	 * @since 1.36
	 *
	 * @param WikiPage $wikiPage WikiPage modified
	 * @param UserIdentity $user User performing the modification
	 * @param CommentStoreComment $summary Edit summary/comment
	 * @param int $flags Flags passed to WikiPage::doUserEditContent()
	 * @param RevisionRecord $revisionRecord New RevisionRecord of the article
	 * @param EditResult $editResult Object storing information about the effects of this
	 *   edit, including which edits were reverted and which edit is this based on (for
	 *   reverts and null edits).
	 * @param bool &$approved Whether the edit is considered approved. Setting it to false
	 *   will abort the update, true will cause the update to be executed normally. If
	 *   patrolling is enabled, the passed value will indicate whether the edit is
	 *   autopatrolled or not. In case patrolling is disabled on the wiki, the passed
	 *   value will always be true, unless modified by other extensions.
	 * @return void This hook must not abort, it must return no value
	 */
	public function onBeforeRevertedTagUpdate(
		$wikiPage,
		$user,
		$summary,
		$flags,
		$revisionRecord,
		$editResult,
		&$approved
	): void;
}

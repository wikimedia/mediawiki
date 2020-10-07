<?php

namespace MediaWiki\Page\Hook;

use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentity;
use WikiPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface RevisionFromEditCompleteHook {
	/**
	 * This hook is called when a revision was inserted due to an edit,
	 * file upload, import or page move.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikiPage WikiPage edited
	 * @param RevisionRecord $rev New revision
	 * @param int|bool $originalRevId If the edit restores or repeats an earlier revision (such as a
	 *   rollback or a null revision), the ID of that earlier revision. False otherwise.
	 *   (Used to be called $baseID.)
	 * @param UserIdentity $user Editing user
	 * @param string[] &$tags Tags to apply to the edit and recent change. This is empty, and
	 *   replacement is ignored, in the case of import or page move.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRevisionFromEditComplete( $wikiPage, $rev, $originalRevId,
		$user, &$tags
	);
}

<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface NewRevisionFromEditCompleteHook {
	/**
	 * Called when a revision was inserted due to an
	 * edit, file upload, import or page move.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage the WikiPage edited
	 * @param ?mixed $rev the new revision
	 * @param ?mixed $originalRevId if the edit restores or repeats an earlier revision (such as a
	 *   rollback or a null revision), the ID of that earlier revision. False otherwise.
	 *   (Used to be called $baseID.)
	 * @param ?mixed $user the editing user
	 * @param ?mixed &$tags tags to apply to the edit and recent change. This is empty, and
	 *   replacement is ignored, in the case of import or page move.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onNewRevisionFromEditComplete( $wikiPage, $rev, $originalRevId,
		$user, &$tags
	);
}

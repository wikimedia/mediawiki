<?php

namespace MediaWiki\Storage\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MultiContentSaveHook {
	/**
	 * Before an article is saved.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $renderedRevision RenderedRevision (object) representing the planned revision.
	 *   Provides access to: (1) ParserOutput of all slots,
	 *   (2) RevisionRecord, which contains
	 *     (2a) Title of the page that will be edited,
	 *     (2b) RevisionSlots - content of all slots, including inherited slots,
	 *     where content has already been modified by PreSaveTransform.
	 *   NOTE: because this revision is not yet saved, slots don't have content ID
	 *   or address, and revision itself doesn't have an ID.
	 * @param ?mixed $user UserIdentity (object): author of this new revision.
	 * @param ?mixed $summary CommentStoreComment (object): user-provided edit comment/summary
	 *   (not an autosummary: will be empty if the user hasn't provided a comment).
	 * @param ?mixed $flags combination of EDIT_* flags, e.g. EDIT_MINOR.
	 * @param ?mixed $status if the hook is aborted, error code can be placed into this Status.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMultiContentSave( $renderedRevision, $user, $summary, $flags,
		$status
	);
}

<?php

namespace MediaWiki\Revision\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface RevisionInsertCompleteHook {
	/**
	 * DEPRECATED since 1.31! Use RevisionRecordInserted hook
	 * instead. Called after a revision is inserted into the database.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $revision the Revision
	 * @param ?mixed $data DEPRECATED! Always null!
	 * @param ?mixed $flags DEPRECATED! Always null!
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRevisionInsertComplete( $revision, $data, $flags );
}

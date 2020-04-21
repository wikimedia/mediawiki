<?php

namespace MediaWiki\Revision\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface RevisionRecordInsertedHook {
	/**
	 * Called after a revision is inserted into the database.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $revisionRecord the RevisionRecord that has just been inserted.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRevisionRecordInserted( $revisionRecord );
}

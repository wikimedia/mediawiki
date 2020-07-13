<?php

namespace MediaWiki\Revision\Hook;

use MediaWiki\Revision\RevisionRecord;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface RevisionRecordInsertedHook {
	/**
	 * This hook is called after a revision is inserted into the database.
	 *
	 * @since 1.35
	 *
	 * @param RevisionRecord $revisionRecord RevisionRecord that has just been inserted
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRevisionRecordInserted( $revisionRecord );
}

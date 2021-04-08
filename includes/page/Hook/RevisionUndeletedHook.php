<?php

namespace MediaWiki\Page\Hook;

use MediaWiki\Revision\RevisionRecord;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "RevisionUndeleted" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface RevisionUndeletedHook {
	/**
	 * After an article revision is restored.
	 *
	 * @since 1.35
	 *
	 * @param RevisionRecord $revisionRecord the RevisionRecord that was restored
	 * @param ?int $oldPageID the page ID of the revision when archived (may be null)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRevisionUndeleted( $revisionRecord, $oldPageID );
}

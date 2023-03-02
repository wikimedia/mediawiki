<?php

namespace MediaWiki\Page\Hook;

use DeferrableUpdate;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "PageDeletionDataUpdates" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface PageDeletionDataUpdatesHook {
	/**
	 * This hook is called when constructing a list of DeferrableUpdate to be
	 * executed when a page is deleted.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title of the page being deleted
	 * @param RevisionRecord $revision RevisionRecord representing the page's
	 *   current revision at the time of deletion
	 * @param DeferrableUpdate[] &$updates List of DeferrableUpdate that can be
	 *   manipulated by the hook handler
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageDeletionDataUpdates( $title, $revision, &$updates );
}

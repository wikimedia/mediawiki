<?php

namespace MediaWiki\Page\Hook;

// phpcs:disable Generic.Files.LineLength -- Remove this after doc review
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PageDeletionDataUpdatesHook {
	/**
	 * Called when constructing a list of DeferrableUpdate to be
	 * executed when a page is deleted.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title The Title of the page being deleted.
	 * @param ?mixed $revision A RevisionRecord representing the page's current revision at the time of deletion.
	 * @param ?mixed &$updates A list of DeferrableUpdate that can be manipulated by the hook handler.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageDeletionDataUpdates( $title, $revision, &$updates );
}

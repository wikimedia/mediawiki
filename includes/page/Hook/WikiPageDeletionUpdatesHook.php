<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface WikiPageDeletionUpdatesHook {
	/**
	 * DEPRECATED since 1.32! Use PageDeletionDataUpdates or
	 * override ContentHandler::getDeletionDataUpdates instead.
	 * Manipulates the list of DeferrableUpdates to be applied when a page is deleted.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $page the WikiPage
	 * @param ?mixed $content the Content to generate updates for, or null in case the page revision
	 *   could not be loaded. The delete will succeed despite this.
	 * @param ?mixed &$updates the array of objects that implement DeferrableUpdate. Hook function
	 *   may want to add to it.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWikiPageDeletionUpdates( $page, $content, &$updates );
}

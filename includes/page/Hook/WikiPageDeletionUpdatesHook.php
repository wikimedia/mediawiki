<?php

namespace MediaWiki\Page\Hook;

use Content;
use MediaWiki\Deferred\DeferrableUpdate;
use WikiPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "WikiPageDeletionUpdates" to register handlers implementing this interface.
 *
 * @deprecated since 1.32 Use PageDeletionDataUpdates or override
 *   ContentHandler::getDeletionDataUpdates instead
 * @ingroup Hooks
 */
interface WikiPageDeletionUpdatesHook {
	/**
	 * Use this hook to manipulate the list of DeferrableUpdates to be applied
	 * when a page is deleted.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $page
	 * @param Content|null $content Content to generate updates for, or null in
	 *   case the page revision could not be loaded. The delete will succeed
	 *   despite this.
	 * @param DeferrableUpdate[] &$updates Array of objects that implement
	 *   DeferrableUpdate. Hook function may want to add to it.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWikiPageDeletionUpdates( $page, $content, &$updates );
}

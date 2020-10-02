<?php

namespace MediaWiki\Hook;

use LinksUpdate;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LinksUpdateAfterInsert" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LinksUpdateAfterInsertHook {
	/**
	 * This hook is called at the end of LinksUpdate::incrTableUpdate() after
	 * each link table insert. For example: pagelinks, imagelinks, externallinks.
	 *
	 * @since 1.35
	 *
	 * @param LinksUpdate $linksUpdate
	 * @param string $table Table to insert links to
	 * @param array $insertions Array of links to insert
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinksUpdateAfterInsert( $linksUpdate, $table, $insertions );
}

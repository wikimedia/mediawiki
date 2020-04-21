<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LinksUpdateAfterInsertHook {
	/**
	 * At the end of LinksUpdate::incrTableUpdate() after
	 * each link table insert.  For example, pagelinks, imagelinks, externallinks.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $linksUpdate LinksUpdate object
	 * @param ?mixed $table the table to insert links to
	 * @param ?mixed $insertions an array of links to insert
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinksUpdateAfterInsert( $linksUpdate, $table, $insertions );
}

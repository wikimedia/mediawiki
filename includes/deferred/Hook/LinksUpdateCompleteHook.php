<?php

namespace MediaWiki\Hook;

use LinksUpdate;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface LinksUpdateCompleteHook {
	/**
	 * This hook is called at the end of LinksUpdate::doUpdate() when updating,
	 * including delete and insert, has completed for all link tables.
	 *
	 * @since 1.35
	 *
	 * @param LinksUpdate $linksUpdate
	 * @param mixed $ticket Prior result of LBFactory::getEmptyTransactionTicket()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinksUpdateComplete( $linksUpdate, $ticket );
}

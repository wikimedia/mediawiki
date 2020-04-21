<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LinksUpdateCompleteHook {
	/**
	 * At the end of LinksUpdate::doUpdate() when updating,
	 * including delete and insert, has completed for all link tables
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $linksUpdate the LinksUpdate object
	 * @param ?mixed $ticket prior result of LBFactory::getEmptyTransactionTicket()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinksUpdateComplete( $linksUpdate, $ticket );
}

<?php

namespace MediaWiki\Hook;

use LinksUpdate;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface LinksUpdateHook {
	/**
	 * This hook is called at the beginning of LinksUpdate::doUpdate() just before the
	 * actual update.
	 *
	 * @since 1.35
	 *
	 * @param LinksUpdate $linksUpdate
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinksUpdate( $linksUpdate );
}

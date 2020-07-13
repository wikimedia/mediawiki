<?php

namespace MediaWiki\Hook;

use LinksUpdate;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface LinksUpdateConstructedHook {
	/**
	 * This hook is called at the end of LinksUpdate() is construction.
	 *
	 * @since 1.35
	 *
	 * @param LinksUpdate $linksUpdate
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinksUpdateConstructed( $linksUpdate );
}

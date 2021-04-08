<?php

namespace MediaWiki\Hook;

use LinksUpdate;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LinksUpdateConstructed" to register handlers implementing this interface.
 *
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

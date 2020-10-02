<?php

namespace MediaWiki\Hook;

use Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "GetDefaultSortkey" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface GetDefaultSortkeyHook {
	/**
	 * Use this hook to override the default sortkey for a page.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title object that we need to get a sortkey for
	 * @param string &$sortkey Sortkey to use
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetDefaultSortkey( $title, &$sortkey );
}

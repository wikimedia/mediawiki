<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetDefaultSortkeyHook {
	/**
	 * Override the default sortkey for a page.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title object that we need to get a sortkey for
	 * @param ?mixed &$sortkey Sortkey to use.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetDefaultSortkey( $title, &$sortkey );
}

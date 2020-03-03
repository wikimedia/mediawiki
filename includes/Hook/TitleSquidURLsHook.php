<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface TitleSquidURLsHook {
	/**
	 * Called to determine which URLs to purge from HTTP caches.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title object to purge
	 * @param ?mixed &$urls An array of URLs to purge from the caches, to be manipulated.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleSquidURLs( $title, &$urls );
}

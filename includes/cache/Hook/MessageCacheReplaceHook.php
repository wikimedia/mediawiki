<?php

namespace MediaWiki\Cache\Hook;

use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface MessageCacheReplaceHook {
	/**
	 * This hook is called when a message page is changed. Use this hook to update caches.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Name of the page changed
	 * @param string $text New contents of the page
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMessageCacheReplace( $title, $text );
}

<?php

namespace MediaWiki\Cache\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MessageCacheReplace" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface MessageCacheReplaceHook {
	/**
	 * This hook is called when a message page is changed. Use this hook to update caches.
	 *
	 * @since 1.35
	 *
	 * @param string $title Name of the page changed
	 * @param string $text New contents of the page
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMessageCacheReplace( $title, $text );
}

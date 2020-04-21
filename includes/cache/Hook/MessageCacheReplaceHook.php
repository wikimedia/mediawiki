<?php

namespace MediaWiki\Cache\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MessageCacheReplaceHook {
	/**
	 * When a message page is changed. Useful for updating
	 * caches.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title name of the page changed.
	 * @param ?mixed $text new contents of the page.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMessageCacheReplace( $title, $text );
}

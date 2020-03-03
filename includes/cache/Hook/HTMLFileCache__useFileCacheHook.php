<?php

namespace MediaWiki\Cache\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface HTMLFileCache__useFileCacheHook {
	/**
	 * Override whether a page should be cached in file
	 * cache.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $context An IContextSource object with information about the request being
	 *   served.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onHTMLFileCache__useFileCache( $context );
}

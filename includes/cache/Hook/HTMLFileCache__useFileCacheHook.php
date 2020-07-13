<?php

namespace MediaWiki\Cache\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use IContextSource;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface HTMLFileCache__useFileCacheHook {
	/**
	 * Use this hook to override whether a page should be cached in file cache.
	 *
	 * @since 1.35
	 *
	 * @param IContextSource $context IContextSource object with information about the request
	 *   being served
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onHTMLFileCache__useFileCache( $context );
}

<?php

namespace MediaWiki\Cache\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface HtmlCacheUpdaterVaryUrlsHook {
	/**
	 * This hook is used to add variants of URLs to purge from HTTP caches.
	 *
	 * Extensions that provide site-wide variants of all URLs, such as by serving from an
	 * alternate domain or path, can use this hook to append alternative URLs for each url in
	 * $urls.
	 *
	 * @since 1.35
	 *
	 * @param array $urls Canonical URLs
	 * @param array &$append Append alternative URLs for $urls
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onHtmlCacheUpdaterVaryUrls( $urls, &$append );
}

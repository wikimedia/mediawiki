<?php

namespace MediaWiki\Cache\Hook;

use MediaWiki\Title\Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "HtmlCacheUpdaterAppendUrls" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface HtmlCacheUpdaterAppendUrlsHook {
	/**
	 * This hook is used to declare extra URLs to purge from HTTP caches.
	 *
	 * Use $mode to decide whether to gather all related URLs or only those affected by a
	 * re-render of the same content. For example, after a direct revision to the content the
	 * history page will need to be purged. However when re-rendering after a cascading change
	 * from a template, only URLs that render content need purging. The $mode will be either
	 * HTMLCacheUpdater::PURGE_URLS_LINKSUPDATE_ONLY or 0.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title of the page being updated
	 * @param int $mode
	 * @param array &$append Append URLs relating to the title
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onHtmlCacheUpdaterAppendUrls( $title, $mode, &$append );
}

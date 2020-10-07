<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface AfterBuildFeedLinksHook {
	/**
	 * This hook is called in OutputPage.php after all feed links (atom,
	 * rss,...) are created. Use this hook to omit specific feeds from being outputted.
	 * You must not use this hook to add feeds; use OutputPage::addFeedLink() instead.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$feedLinks Array of created feed links
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAfterBuildFeedLinks( &$feedLinks );
}

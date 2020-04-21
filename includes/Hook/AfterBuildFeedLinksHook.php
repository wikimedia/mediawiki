<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface AfterBuildFeedLinksHook {
	/**
	 * Executed in OutputPage.php after all feed links (atom,
	 * rss,...) are created. Can be used to omit specific feeds from being outputted.
	 * You must not use this hook to add feeds, use OutputPage::addFeedLink() instead.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$feedLinks Array of created feed links
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAfterBuildFeedLinks( &$feedLinks );
}

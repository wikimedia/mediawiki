<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface WatchArticleHook {
	/**
	 * Before a watch is added to an article.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user user that will watch
	 * @param ?mixed $page WikiPage object to be watched
	 * @param ?mixed &$status Status object to be returned if the hook returns false
	 * @param ?mixed $expiry Optional expiry timestamp in any format acceptable to wfTimestamp()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWatchArticle( $user, $page, &$status, $expiry );
}

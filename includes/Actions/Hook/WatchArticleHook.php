<?php

namespace MediaWiki\Hook;

use MediaWiki\Page\WikiPage;
use MediaWiki\Status\Status;
use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "WatchArticle" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface WatchArticleHook {
	/**
	 * This hook is called before a watch is added to an article.
	 *
	 * @since 1.35
	 *
	 * @param UserIdentity $user User that will watch
	 * @param WikiPage $page WikiPage object to be watched
	 * @param Status &$status Status object to be returned if the hook returns false
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to wfTimestamp()
	 * @return bool|void True or no return value to continue or false to abort and
	 *   return Status object
	 */
	public function onWatchArticle( $user, $page, &$status, $expiry );
}

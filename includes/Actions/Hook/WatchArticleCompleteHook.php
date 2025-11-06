<?php

namespace MediaWiki\Hook;

use MediaWiki\Page\WikiPage;
use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "WatchArticleComplete" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface WatchArticleCompleteHook {
	/**
	 * This hook is called after a watch is added to an article.
	 *
	 * @since 1.35
	 *
	 * @param UserIdentity $user User that watched
	 * @param WikiPage $page WikiPage object watched
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWatchArticleComplete( $user, $page );
}

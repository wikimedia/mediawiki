<?php

namespace MediaWiki\Hook;

use MediaWiki\Page\WikiPage;
use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UnwatchArticleComplete" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UnwatchArticleCompleteHook {
	/**
	 * This hook is called after a watch is removed from an article.
	 *
	 * @since 1.35
	 *
	 * @param UserIdentity $user User that watched
	 * @param WikiPage $page WikiPage object that was watched
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUnwatchArticleComplete( $user, $page );
}

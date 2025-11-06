<?php

namespace MediaWiki\Hook;

use MediaWiki\Page\WikiPage;
use MediaWiki\Status\Status;
use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UnwatchArticle" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UnwatchArticleHook {
	/**
	 * This hook is called before a watch is removed from an article.
	 *
	 * @since 1.35
	 *
	 * @param UserIdentity $user User watching
	 * @param WikiPage $page WikiPage object to be removed
	 * @param Status &$status Status object to be returned if the hook returns false
	 * @return bool|void True or no return value to continue or false to abort and
	 *   return Status object
	 */
	public function onUnwatchArticle( $user, $page, &$status );
}

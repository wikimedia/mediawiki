<?php

namespace MediaWiki\Hook;

use User;
use WikiPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UnwatchArticleCompleteHook {
	/**
	 * This hook is called after a watch is removed from an article.
	 *
	 * @since 1.35
	 *
	 * @param User $user User that watched
	 * @param WikiPage $page WikiPage object that was watched
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUnwatchArticleComplete( $user, $page );
}

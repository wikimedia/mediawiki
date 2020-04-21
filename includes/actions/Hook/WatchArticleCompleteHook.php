<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface WatchArticleCompleteHook {
	/**
	 * After a watch is added to an article.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user user that watched
	 * @param ?mixed $page WikiPage object watched
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWatchArticleComplete( $user, $page );
}

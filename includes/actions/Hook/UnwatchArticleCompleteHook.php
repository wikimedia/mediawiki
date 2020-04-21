<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UnwatchArticleCompleteHook {
	/**
	 * After a watch is removed from an article.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user user that watched
	 * @param ?mixed $page WikiPage object that was watched
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUnwatchArticleComplete( $user, $page );
}

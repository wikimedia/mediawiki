<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UnwatchArticleHook {
	/**
	 * Before a watch is removed from an article.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user user watching
	 * @param ?mixed $page WikiPage object to be removed
	 * @param ?mixed &$status Status object to be returned if the hook returns false
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUnwatchArticle( $user, $page, &$status );
}

<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleProtectCompleteHook {
	/**
	 * After an article is protected.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage the WikiPage that was protected
	 * @param ?mixed $user the user who did the protection
	 * @param ?mixed $protect Set of restriction keys
	 * @param ?mixed $reason Reason for protect
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleProtectComplete( $wikiPage, $user, $protect, $reason );
}

<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleProtectHook {
	/**
	 * Before an article is protected.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage the WikiPage being protected
	 * @param ?mixed $user the user doing the protection
	 * @param ?mixed $protect Set of restriction keys
	 * @param ?mixed $reason Reason for protect
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleProtect( $wikiPage, $user, $protect, $reason );
}

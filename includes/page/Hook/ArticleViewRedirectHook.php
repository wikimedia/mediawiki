<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleViewRedirectHook {
	/**
	 * Before setting "Redirected from ..." subtitle when a
	 * redirect was followed.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $article target article (object)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleViewRedirect( $article );
}

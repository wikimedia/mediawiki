<?php

namespace MediaWiki\Page\Hook;

use Article;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleViewRedirectHook {
	/**
	 * This hook is called before setting "Redirected from ..." subtitle when a
	 * redirect was followed.
	 *
	 * @since 1.35
	 *
	 * @param Article $article Target article
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleViewRedirect( $article );
}

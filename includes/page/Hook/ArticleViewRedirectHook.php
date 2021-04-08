<?php

namespace MediaWiki\Page\Hook;

use Article;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ArticleViewRedirect" to register handlers implementing this interface.
 *
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

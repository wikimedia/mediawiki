<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BeforeDisplayNoArticleTextHook {
	/**
	 * Before displaying message key "noarticletext" or
	 * "noarticletext-nopermission" at Article::showMissingArticle().
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $article article object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforeDisplayNoArticleText( $article );
}

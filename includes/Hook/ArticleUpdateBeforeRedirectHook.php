<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleUpdateBeforeRedirectHook {
	/**
	 * After a page is updated (usually on save), before
	 * the user is redirected back to the page.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $article the article
	 * @param ?mixed &$sectionanchor The section anchor link (e.g. "#overview" )
	 * @param ?mixed &$extraq Extra query parameters which can be added via hooked functions
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleUpdateBeforeRedirect( $article, &$sectionanchor,
		&$extraq
	);
}

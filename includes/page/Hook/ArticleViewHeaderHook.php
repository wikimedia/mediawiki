<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleViewHeaderHook {
	/**
	 * Control article output. Called before the parser cache is about
	 * to be tried for article viewing.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $article the article
	 * @param ?mixed &$pcache whether to try the parser cache or not
	 * @param ?mixed &$outputDone whether the output for this page finished or not. Set to
	 *   a ParserOutput object to both indicate that the output is done and what
	 *   parser output was used.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleViewHeader( $article, &$pcache, &$outputDone );
}

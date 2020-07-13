<?php

namespace MediaWiki\Page\Hook;

use Article;
use ParserOutput;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleViewHeaderHook {
	/**
	 * Use this hook to control article output. This hook is called before the parser
	 * cache is about to be tried for article viewing.
	 *
	 * @since 1.35
	 *
	 * @param Article $article
	 * @param bool &$pcache Whether to try the parser cache or not
	 * @param bool|ParserOutput &$outputDone Whether the output for this page finished or not. Set to
	 *   a ParserOutput object to both indicate that the output is done and what
	 *   parser output was used.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleViewHeader( $article, &$pcache, &$outputDone );
}

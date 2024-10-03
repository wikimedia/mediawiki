<?php

namespace MediaWiki\Page\Hook;

use Article;
use MediaWiki\Parser\ParserOptions;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ArticleParserOptions" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleParserOptionsHook {
	/**
	 * This hook is called before parsing wikitext for an article, and allows
	 * setting particular parser options based on title, user preferences,
	 * etc.
	 *
	 * @since 1.36
	 *
	 * @param Article $article Article about to be parsed
	 * @param ParserOptions $popts Mutable parser options
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleParserOptions(
		Article $article, ParserOptions $popts
	);

}

<?php

namespace MediaWiki\Diff\Hook;

use DifferenceEngine;
use OutputPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ArticleContentOnDiff" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleContentOnDiffHook {
	/**
	 * This hook is called before showing the article content below a diff. Use
	 * this hook to change the content in this area or how it is loaded.
	 *
	 * @since 1.35
	 *
	 * @param DifferenceEngine $diffEngine
	 * @param OutputPage $output
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleContentOnDiff( $diffEngine, $output );
}

<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleContentOnDiffHook {
	/**
	 * Before showing the article content below a diff. Use
	 * this to change the content in this area or how it is loaded.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $diffEngine the DifferenceEngine
	 * @param ?mixed $output the OutputPage object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleContentOnDiff( $diffEngine, $output );
}

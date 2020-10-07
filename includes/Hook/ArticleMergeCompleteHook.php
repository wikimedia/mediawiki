<?php

namespace MediaWiki\Hook;

use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleMergeCompleteHook {
	/**
	 * This hook is called after merging to article using Special:Mergehistory.
	 *
	 * @since 1.35
	 *
	 * @param Title $targetTitle Target title
	 * @param Title $destTitle Destination title
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleMergeComplete( $targetTitle, $destTitle );
}

<?php

namespace MediaWiki\Hook;

use Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ArticleMergeComplete" to register handlers implementing this interface.
 *
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

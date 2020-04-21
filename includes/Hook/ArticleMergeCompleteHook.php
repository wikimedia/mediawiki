<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleMergeCompleteHook {
	/**
	 * After merging to article using Special:Mergehistory.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $targetTitle target title (object)
	 * @param ?mixed $destTitle destination title (object)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleMergeComplete( $targetTitle, $destTitle );
}

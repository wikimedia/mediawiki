<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleRevisionVisibilitySetHook {
	/**
	 * Called when changing visibility of one or more
	 * revisions of an article.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title object of the article
	 * @param ?mixed $ids Ids to set the visibility for
	 * @param ?mixed $visibilityChangeMap Map of revision id to oldBits and newBits.  This array can be
	 *   examined to determine exactly what visibility bits have changed for each
	 *   revision.  This array is of the form
	 *   [id => ['oldBits' => $oldBits, 'newBits' => $newBits], ... ]
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleRevisionVisibilitySet( $title, $ids,
		$visibilityChangeMap
	);
}

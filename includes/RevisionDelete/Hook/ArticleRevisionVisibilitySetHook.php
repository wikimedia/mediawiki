<?php

namespace MediaWiki\Hook;

use MediaWiki\Title\Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ArticleRevisionVisibilitySet" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleRevisionVisibilitySetHook {
	/**
	 * This hook is called when changing visibility of one or more
	 * revisions of an article.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title of the article
	 * @param int[] $ids IDs to set the visibility for
	 * @param array $visibilityChangeMap Map of revision ID to oldBits and newBits.
	 *   This array can be examined to determine exactly what visibility bits
	 *   have changed for each revision. This array is of the form:
	 *   [id => ['oldBits' => $oldBits, 'newBits' => $newBits], ... ]
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleRevisionVisibilitySet( $title, $ids,
		$visibilityChangeMap
	);
}

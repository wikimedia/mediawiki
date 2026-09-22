<?php

namespace MediaWiki\RevisionDelete\Hook;

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
	 * @param array<int,array{oldBits: int, newBits: int}> $visibilityChangeMap Map from revision
	 *  ids to visibility changes from old to new bitfield. This array can be examined to determine
	 *  exactly which of the {@link RevisionRecord} visibility bits have changed for each revision.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleRevisionVisibilitySet( $title, $ids,
		$visibilityChangeMap
	);
}

/** @deprecated class alias since 1.46 */
class_alias( ArticleRevisionVisibilitySetHook::class, 'MediaWiki\\Hook\\ArticleRevisionVisibilitySetHook' );

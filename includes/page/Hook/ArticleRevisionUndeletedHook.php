<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleRevisionUndeletedHook {
	/**
	 * After an article revision is restored.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title the article title
	 * @param ?mixed $revision the revision
	 * @param ?mixed $oldPageID the page ID of the revision when archived (may be null)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleRevisionUndeleted( $title, $revision, $oldPageID );
}

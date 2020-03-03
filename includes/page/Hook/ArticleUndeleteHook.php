<?php

namespace MediaWiki\Page\Hook;

// phpcs:disable Generic.Files.LineLength -- Remove this after doc review
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleUndeleteHook {
	/**
	 * When one or more revisions of an article are restored.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title corresponding to the article restored
	 * @param ?mixed $create Whether or not the restoration caused the page to be created (i.e. it
	 *   didn't exist before).
	 * @param ?mixed $comment The comment associated with the undeletion.
	 * @param ?mixed $oldPageId ID of page previously deleted (from archive table). This ID will be used
	 *   for the restored page.
	 * @param ?mixed $restoredPages Set of page IDs that have revisions restored for this undelete,
	 *   with keys being page IDs and values are 'true'.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleUndelete( $title, $create, $comment, $oldPageId,
		$restoredPages
	);
}

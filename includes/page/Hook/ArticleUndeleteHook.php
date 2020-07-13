<?php

namespace MediaWiki\Page\Hook;

// phpcs:disable Generic.Files.LineLength -- Remove this after doc review
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleUndeleteHook {
	/**
	 * This hook is called when one or more revisions of an article are restored.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title corresponding to the article restored
	 * @param bool $create Whether or not the restoration caused the page to be created (i.e. it
	 *   didn't exist before)
	 * @param string $comment Comment associated with the undeletion
	 * @param int $oldPageId ID of page previously deleted (from archive table). This ID will be used
	 *   for the restored page.
	 * @param array $restoredPages Set of page IDs that have revisions restored for this undelete,
	 *   with keys set to page IDs and values set to 'true'
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleUndelete( $title, $create, $comment, $oldPageId,
		$restoredPages
	);
}

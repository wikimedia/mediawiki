<?php

namespace MediaWiki\Page\Hook;

use Revision;
use Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ArticleRevisionUndeleted" to register handlers implementing this interface.
 *
 * @deprecated since 1.35. Use RevisionUndeleted instead.
 * @ingroup Hooks
 */
interface ArticleRevisionUndeletedHook {
	/**
	 * This hook is called after an article revision is restored.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Article title
	 * @param Revision $revision
	 * @param int|null $oldPageID Page ID of the revision when archived
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleRevisionUndeleted( $title, $revision, $oldPageID );
}

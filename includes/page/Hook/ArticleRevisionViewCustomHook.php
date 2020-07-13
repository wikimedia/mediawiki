<?php

namespace MediaWiki\Page\Hook;

use MediaWiki\Revision\RevisionRecord;
use OutputPage;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleRevisionViewCustomHook {
	/**
	 * Use this hook for custom rendering of an article's content.
	 * Note that it is preferable to implement proper handing for a custom data type using
	 * the ContentHandler facility.
	 *
	 * @since 1.35
	 *
	 * @param RevisionRecord|null $revision Content of the page (or null if the revision
	 *   could not be loaded). May also be a fake that wraps content supplied by an extension.
	 * @param Title $title Title of the page
	 * @param int $oldid Requested revision ID, or 0 for the current revision
	 * @param OutputPage $output
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleRevisionViewCustom( $revision, $title, $oldid,
		$output
	);
}

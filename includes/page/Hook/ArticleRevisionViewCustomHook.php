<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleRevisionViewCustomHook {
	/**
	 * Allows custom rendering of an article's content.
	 * Note that it is preferable to implement proper handing for a custom data type using
	 * the ContentHandler facility.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $revision content of the page, as a RevisionRecord object, or null if the revision
	 *   could not be loaded. May also be a fake that wraps content supplied by an extension.
	 * @param ?mixed $title title of the page
	 * @param ?mixed $oldid the requested revision id, or 0 for the currrent revision.
	 * @param ?mixed $output a ParserOutput object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleRevisionViewCustom( $revision, $title, $oldid,
		$output
	);
}

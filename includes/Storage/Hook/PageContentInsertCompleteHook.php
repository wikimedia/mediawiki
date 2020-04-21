<?php

namespace MediaWiki\Storage\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PageContentInsertCompleteHook {
	/**
	 * After a new article is created.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage WikiPage created
	 * @param ?mixed $user User creating the article
	 * @param ?mixed $content New content as a Content object
	 * @param ?mixed $summary Edit summary/comment
	 * @param ?mixed $isMinor Whether or not the edit was marked as minor
	 * @param ?mixed $isWatch (No longer used)
	 * @param ?mixed $section (No longer used)
	 * @param ?mixed $flags Flags passed to WikiPage::doEditContent()
	 * @param ?mixed $revision New Revision of the article
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageContentInsertComplete( $wikiPage, $user, $content,
		$summary, $isMinor, $isWatch, $section, $flags, $revision
	);
}

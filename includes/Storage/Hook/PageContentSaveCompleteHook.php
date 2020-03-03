<?php

namespace MediaWiki\Storage\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PageContentSaveCompleteHook {
	/**
	 * After an article has been updated.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage WikiPage modified
	 * @param ?mixed $user User performing the modification
	 * @param ?mixed $content New content, as a Content object
	 * @param ?mixed $summary Edit summary/comment
	 * @param ?mixed $isMinor Whether or not the edit was marked as minor
	 * @param ?mixed $isWatch (No longer used)
	 * @param ?mixed $section (No longer used)
	 * @param ?mixed $flags Flags passed to WikiPage::doEditContent()
	 * @param ?mixed $revision New Revision of the article
	 * @param ?mixed $status Status object about to be returned by doEditContent(). Since
	 *   MediaWiki 1.32, replacing the Status object by assigning through the reference
	 *   has had no effect.
	 * @param ?mixed $originalRevId if the edit restores or repeats an earlier revision (such as a
	 *   rollback or a null revision), the ID of that earlier revision. False otherwise.
	 *   (Used to be called $baseRevId.)
	 * @param ?mixed $undidRevId the rev ID (or 0) this edit undid
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageContentSaveComplete( $wikiPage, $user, $content,
		$summary, $isMinor, $isWatch, $section, $flags, $revision, $status,
		$originalRevId, $undidRevId
	);
}

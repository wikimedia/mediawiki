<?php

namespace MediaWiki\Storage\Hook;

use Content;
use Revision;
use Status;
use User;
use WikiPage;

/**
 * @deprecated since 1.35, use PageSaveComplete
 * @ingroup Hooks
 */
interface PageContentSaveCompleteHook {
	/**
	 * This hook is called after an article has been updated.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikiPage WikiPage modified
	 * @param User $user User performing the modification
	 * @param Content $content New content
	 * @param string $summary Edit summary/comment
	 * @param bool $isMinor Whether or not the edit was marked as minor
	 * @param null $isWatch (No longer used)
	 * @param null $section (No longer used)
	 * @param int $flags Flags passed to WikiPage::doEditContent()
	 * @param Revision $revision New Revision of the article
	 * @param Status $status Status object about to be returned by doEditContent(). Since
	 *   MediaWiki 1.32, replacing the Status object by assigning through the reference
	 *   has had no effect.
	 * @param int|bool $originalRevId If the edit restores or repeats an earlier revision (such as a
	 *   rollback or a null revision), the ID of that earlier revision. False otherwise.
	 *   (Used to be called $baseRevId.)
	 * @param int $undidRevId Rev ID (or 0) this edit undid
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageContentSaveComplete( $wikiPage, $user, $content,
		$summary, $isMinor, $isWatch, $section, $flags, $revision, $status,
		$originalRevId, $undidRevId
	);
}

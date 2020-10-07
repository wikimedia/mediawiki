<?php

namespace MediaWiki\Storage\Hook;

use Content;
use Revision;
use User;
use WikiPage;

/**
 * @deprecated since 1.35, use PageSaveComplete
 * @ingroup Hooks
 */
interface PageContentInsertCompleteHook {
	/**
	 * This hook is called after a new article is created.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikiPage WikiPage created
	 * @param User $user User creating the article
	 * @param Content $content New content
	 * @param string $summary Edit summary/comment
	 * @param bool $isMinor Whether or not the edit was marked as minor
	 * @param null $isWatch (No longer used)
	 * @param null $section (No longer used)
	 * @param int $flags Flags passed to WikiPage::doEditContent()
	 * @param Revision $revision New Revision of the article
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageContentInsertComplete( $wikiPage, $user, $content,
		$summary, $isMinor, $isWatch, $section, $flags, $revision
	);
}

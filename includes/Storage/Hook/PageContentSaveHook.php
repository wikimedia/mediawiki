<?php

namespace MediaWiki\Storage\Hook;

// phpcs:disable Generic.Files.LineLength -- Remove this after doc review
use CommentStoreComment;
use Content;
use StatusValue;
use User;
use WikiPage;

/**
 * @deprecated since 1.35 Use MultiContentSave instead
 * @ingroup Hooks
 */
interface PageContentSaveHook {
	/**
	 * This hook is called before an article is saved.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikiPage WikiPage being saved
	 * @param User $user User saving the article
	 * @param Content $content New article content
	 * @param CommentStoreComment &$summary Edit comment. Can be replaced with a new one.
	 * @param bool $isminor Whether the edit was marked as minor
	 * @param null $iswatch Previously a watch flag. Currently unused, always null.
	 * @param null $section Previously the section number being edited. Currently unused, always null.
	 * @param int $flags All EDIT_… flags (including EDIT_MINOR) as an integer number.
	 *   See WikiPage::doEditContent documentation for flags' definition.
	 * @param StatusValue $status StatusValue object for the hook handlers resulting status.
	 *   Either set $status->fatal() or return false to abort the save action.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageContentSave( $wikiPage, $user, $content, &$summary,
		$isminor, $iswatch, $section, $flags, $status
	);
}

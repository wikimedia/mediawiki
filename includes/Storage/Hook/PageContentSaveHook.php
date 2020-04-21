<?php

namespace MediaWiki\Storage\Hook;

// phpcs:disable Generic.Files.LineLength -- Remove this after doc review
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PageContentSaveHook {
	/**
	 * DEPRECATED since 1.35! Use MultiContentSave instead.
	 * Before an article is saved.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage the WikiPage (object) being saved
	 * @param ?mixed $user the user (object) saving the article
	 * @param ?mixed $content the new article content, as a Content object
	 * @param ?mixed &$summary CommentStoreComment object containing the edit comment. Can be replaced with a new one.
	 * @param ?mixed $isminor Boolean flag specifying if the edit was marked as minor.
	 * @param ?mixed $iswatch Previously a watch flag. Currently unused, always null.
	 * @param ?mixed $section Previously the section number being edited. Currently unused, always null.
	 * @param ?mixed $flags All EDIT_… flags (including EDIT_MINOR) as an integer number. See WikiPage::doEditContent
	 *   documentation for flags' definition.
	 * @param ?mixed $status StatusValue object for the hook handlers resulting status. Either set $status->fatal() or
	 *   return false to abort the save action.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageContentSave( $wikiPage, $user, $content, &$summary,
		$isminor, $iswatch, $section, $flags, $status
	);
}

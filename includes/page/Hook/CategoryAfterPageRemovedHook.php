<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface CategoryAfterPageRemovedHook {
	/**
	 * After a page is removed from a category.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $category Category that page was removed from
	 * @param ?mixed $wikiPage WikiPage that was removed
	 * @param ?mixed $id the page ID (original ID in case of page deletions)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onCategoryAfterPageRemoved( $category, $wikiPage, $id );
}

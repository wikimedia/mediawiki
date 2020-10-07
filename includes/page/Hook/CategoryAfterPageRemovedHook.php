<?php

namespace MediaWiki\Page\Hook;

use Category;
use WikiPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface CategoryAfterPageRemovedHook {
	/**
	 * This hook is called after a page is removed from a category.
	 *
	 * @since 1.35
	 *
	 * @param Category $category Category that page was removed from
	 * @param WikiPage $wikiPage WikiPage that was removed
	 * @param int $id Page ID (original ID in case of page deletions)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onCategoryAfterPageRemoved( $category, $wikiPage, $id );
}

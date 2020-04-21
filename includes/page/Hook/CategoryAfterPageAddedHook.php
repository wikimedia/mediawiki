<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface CategoryAfterPageAddedHook {
	/**
	 * After a page is added to a category.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $category Category that page was added to
	 * @param ?mixed $wikiPage WikiPage that was added
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onCategoryAfterPageAdded( $category, $wikiPage );
}

<?php

namespace MediaWiki\Page\Hook;

use CategoryPage;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface CategoryPageViewHook {
	/**
	 * This hook is called before viewing a categorypage in CategoryPage::view.
	 *
	 * @since 1.35
	 *
	 * @param CategoryPage $catpage
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onCategoryPageView( $catpage );
}

<?php

namespace MediaWiki\Page\Hook;

use CategoryPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "CategoryPageView" to register handlers implementing this interface.
 *
 * @stable to implement
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

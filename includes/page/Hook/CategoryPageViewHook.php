<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface CategoryPageViewHook {
	/**
	 * Before viewing a categorypage in CategoryPage::view.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $catpage CategoryPage instance
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onCategoryPageView( $catpage );
}

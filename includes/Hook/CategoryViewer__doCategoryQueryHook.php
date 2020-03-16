<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface CategoryViewer__doCategoryQueryHook {
	/**
	 * This hook is called after querying for pages to be displayed in a Category page.
	 * Use this hook to batch load any related data about the pages.
	 *
	 * @since 1.35
	 *
	 * @param string $type Category type, either 'page', 'file', or 'subcat'
	 * @param array $res Query result from Wikimedia\Rdbms\IDatabase::select()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onCategoryViewer__doCategoryQuery( $type, $res );
}

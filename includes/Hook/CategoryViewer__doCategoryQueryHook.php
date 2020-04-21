<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface CategoryViewer__doCategoryQueryHook {
	/**
	 * After querying for pages to be displayed
	 * in a Category page. Gives extensions the opportunity to batch load any
	 * related data about the pages.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $type The category type. Either 'page', 'file' or 'subcat'
	 * @param ?mixed $res Query result from Wikimedia\Rdbms\IDatabase::select()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onCategoryViewer__doCategoryQuery( $type, $res );
}

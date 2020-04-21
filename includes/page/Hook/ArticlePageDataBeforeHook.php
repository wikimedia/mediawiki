<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticlePageDataBeforeHook {
	/**
	 * Before loading data of an article from the database.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage WikiPage (object) that data will be loaded
	 * @param ?mixed &$fields fields (array) to load from the database
	 * @param ?mixed &$tables tables (array) to load from the database
	 * @param ?mixed &$joinConds join conditions (array) to load from the database
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticlePageDataBefore( $wikiPage, &$fields, &$tables,
		&$joinConds
	);
}

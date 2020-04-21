<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticlePageDataAfterHook {
	/**
	 * After loading data of an article from the database.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage WikiPage (object) whose data were loaded
	 * @param ?mixed &$row row (object) returned from the database server
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticlePageDataAfter( $wikiPage, &$row );
}

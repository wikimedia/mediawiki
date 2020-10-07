<?php

namespace MediaWiki\Page\Hook;

use stdClass;
use WikiPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticlePageDataAfterHook {
	/**
	 * This hook is called after loading data of an article from the database.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikiPage WikiPage whose data were loaded
	 * @param stdClass &$row Row returned from the database server
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticlePageDataAfter( $wikiPage, &$row );
}

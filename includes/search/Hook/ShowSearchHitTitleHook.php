<?php

namespace MediaWiki\Search\Hook;

use HtmlArmor;
use SearchResult;
use SpecialSearch;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ShowSearchHitTitleHook {
	/**
	 * Use this hook to customise display of search hit title/link.
	 *
	 * @since 1.35
	 *
	 * @param Title &$title Title to link to
	 * @param string|HtmlArmor|null &$titleSnippet Label for the link representing
	 *   the search result. Typically the article title.
	 * @param SearchResult $result
	 * @param array $terms Array of search terms extracted by SearchDatabase search engines
	 *   (may not be populated by other search engines)
	 * @param SpecialSearch $specialSearch
	 * @param string[] &$query Array of query string parameters for the link representing the search
	 *   result
	 * @param string[] &$attributes Array of title link attributes, can be modified by extension
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onShowSearchHitTitle( &$title, &$titleSnippet, $result, $terms,
		$specialSearch, &$query, &$attributes
	);
}

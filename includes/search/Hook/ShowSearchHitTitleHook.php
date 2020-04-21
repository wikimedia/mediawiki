<?php

namespace MediaWiki\Search\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ShowSearchHitTitleHook {
	/**
	 * Customise display of search hit title/link.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$title Title to link to
	 * @param ?mixed &$titleSnippet Label for the link representing the search result. Typically the
	 *   article title.
	 * @param ?mixed $result The SearchResult object
	 * @param ?mixed $terms array of search terms extracted by SearchDatabase search engines
	 *   (may not be populated by other search engines).
	 * @param ?mixed $specialSearch The SpecialSearch object
	 * @param ?mixed &$query Array of query string parameters for the link representing the search
	 *   result.
	 * @param ?mixed &$attributes Array of title link attributes, can be modified by extension.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onShowSearchHitTitle( &$title, &$titleSnippet, $result, $terms,
		$specialSearch, &$query, &$attributes
	);
}

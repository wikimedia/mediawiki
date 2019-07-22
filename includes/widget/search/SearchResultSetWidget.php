<?php

namespace MediaWiki\Widget\Search;

use ISearchResultSet;

/**
 * Renders a set of search results to HTML
 */
interface SearchResultSetWidget {
	/**
	 * @param string $term User provided search term
	 * @param ISearchResultSet|ISearchResultSet[] $resultSets List of interwiki
	 *  results to render.
	 * @return string HTML
	 */
	public function render( $term, $resultSets );
}

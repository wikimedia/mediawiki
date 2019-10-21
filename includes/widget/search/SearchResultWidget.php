<?php

namespace MediaWiki\Widget\Search;

use SearchResult;

/**
 * Renders a single search result to HTML
 */
interface SearchResultWidget {
	/**
	 * @param SearchResult $result The result to render
	 * @param int $position The zero indexed result position, including offset
	 * @return string HTML
	 */
	public function render( SearchResult $result, $position );
}

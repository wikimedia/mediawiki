<?php

namespace MediaWiki\Widget;

use SearchResult;

interface SearchResultWidget {
	/**
	 * @param SearchResult $result The result to render
	 * @param string $terms Terms to be highlighted (@see SearchResult::getTextSnippet)
	 * @param int $position The result position, including offset
	 * @return string HTML
	 */
	public function render( SearchResult $result, $terms, $position );
}

<?php

use MediaWiki\Title\Title;

/**
 * A ISearchResultSet wrapper for TitleMatcher
 */
class SearchNearMatchResultSet extends SearchResultSet {
	/**
	 * @param Title|null $match Title if matched, else null
	 */
	public function __construct( $match ) {
		if ( $match === null ) {
			$this->results = [];
		} else {
			$this->results = [ SearchResult::newFromTitle( $match, $this ) ];
		}
	}

	/** @inheritDoc */
	public function numRows() {
		return $this->results ? 1 : 0;
	}
}

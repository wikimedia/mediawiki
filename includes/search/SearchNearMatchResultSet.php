<?php
/**
 * A SearchResultSet wrapper for SearchNearMatcher
 */
class SearchNearMatchResultSet extends SearchResultSet {
	/**
	 * @param Title|null $match Title if matched, else null
	 */
	public function __construct( $match ) {
		if ( $match === null ) {
			$this->results = [];
		} else {
			$this->results = [ SearchResult::newFromtitle( $this->result, $this ) ];
		}
	}

	public function numRows() {
		return $this->results ? 1 : 0;
	}
}

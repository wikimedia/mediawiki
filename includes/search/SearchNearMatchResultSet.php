<?php
/**
 * A SearchResultSet wrapper for SearchNearMatcher
 */
class SearchNearMatchResultSet extends SearchResultSet {
	private $fetched = false;

	/**
	 * @param Title|null $match Title if matched, else null
	 */
	public function __construct( $match ) {
		$this->result = $match;
	}

	public function numRows() {
		return $this->result ? 1 : 0;
	}

	public function next() {
		if ( $this->fetched || !$this->result ) {
			return false;
		}
		$this->fetched = true;
		return SearchResult::newFromTitle( $this->result );
	}

	public function rewind() {
		$this->fetched = false;
	}
}

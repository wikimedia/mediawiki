<?php
/**
 * A SearchResultSet wrapper for SearchNearMatcher
 */
class SearchNearMatchResultSet extends SearchResultSet {
	/** @var Title|null Title if matched, else null */
	private $result;

	/**
	 * @param Title|null $match Title if matched, else null
	 */
	public function __construct( $match ) {
		if ( $match === null ) {
			$this->result = [];
		} else {
			$this->result = [ SearchResult::newFromtitle( $this->result, $this ) ];
		}
	}

	public function numRows() {
		return $this->result ? 1 : 0;
	}

	/**
	 * @return SearchResult[]
	 */
	public function extractResults() {
		return $this->result;
	}
}

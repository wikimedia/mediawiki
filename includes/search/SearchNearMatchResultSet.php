<?php
/**
 * A SearchResultSet wrapper for SearchNearMatcher
 */
class SearchNearMatchResultSet extends SearchResultSet {
	private $fetched = false;
	/** @var Title|null Title if matched, else null */
	private $result;

	/**
	 * @param Title|null $match Title if matched, else null
	 */
	public function __construct( $match ) {
		$this->result = $match;
	}

	public function numRows() {
		return $this->result ? 1 : 0;
	}

	public function extractResults() {
		if ( $this->result ) {
			return [ SearchResult::newFromTitle( $this->result, $this ) ];
		} else {
			return [];
		}
	}
}

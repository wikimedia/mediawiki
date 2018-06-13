<?php

class MockSearchResultSet extends SearchResultSet {
	/*
	 * @var SearchResultSet[][] Map from result type to list of results for
	 *  that type.
	 */
	private $interwikiResults;

	/**
	 * @param SearchResult[] $results
	 * @param SearchResultSet[][] $interwikiResults Map from result type
	 *  to list of results for that type.
	 */
	public function __construct( array $results, array $interwikiResults = [] ) {
		parent::__construct( false, false );
		$this->results = $results;
		$this->interwikiResults = $interwikiResults;
	}

	public function numRows() {
		return count( $this->results );
	}

	public function hasInterwikiResults( $type = self::SECONDARY_RESULTS ) {
		return isset( $this->interwikiResults[$type] ) &&
			count( $this->interwikiResults[$type] ) > 0;
	}

	public function getInterwikiResults( $type = self::SECONDARY_RESULTS ) {
		if ( $this->hasInterwikiResults( $type ) ) {
			return $this->interwikiResults[$type];
		} else {
			return null;
		}
	}
}

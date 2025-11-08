<?php

class MockSearchResultSet extends SearchResultSet {
	/**
	 * @var SearchResultSet[][] Map from result type to list of results for
	 *  that type.
	 */
	private $interwikiResults;

	/**
	 * @param SearchResult[]|callable[] $results
	 * @param ISearchResultSet[][]|callable[][] $interwikiResults Map from result type
	 *  to list of results for that type.
	 */
	public function __construct( array $results, array $interwikiResults = [] ) {
		parent::__construct( false, false );
		$this->results = $results;
		$this->interwikiResults = $interwikiResults;
	}

	/** @inheritDoc */
	public function numRows() {
		return count( $this->results );
	}

	/** @inheritDoc */
	public function hasInterwikiResults( $type = self::SECONDARY_RESULTS ) {
		return (bool)( $this->interwikiResults[$type] ?? false );
	}

	/** @inheritDoc */
	public function extractResults() {
		$results = parent::extractResults();

		foreach ( $results as &$result ) {
			// Resolve deferred results; needed to work around T203279
			if ( is_callable( $result ) ) {
				$result = $result();
			}
		}

		return $results;
	}

	/** @inheritDoc */
	public function getInterwikiResults( $type = self::SECONDARY_RESULTS ) {
		return $this->interwikiResults[$type] ?? [];
	}

	/** @inheritDoc */
	public function getTotalHits() {
		return $this->numRows();
	}
}

<?php

use Wikimedia\Rdbms\ResultWrapper;

/**
 * This class is used for different SQL-based search engines shipped with MediaWiki
 * @ingroup Search
 */
class SqlSearchResultSet extends SearchResultSet {
	/** @var ResultWrapper Result object from database */
	protected $resultSet;
	/** @var string Requested search query */
	protected $terms;
	/** @var int|null Total number of hits for $terms */
	protected $totalHits;

	function __construct( ResultWrapper $resultSet, $terms, $total = null ) {
		$this->resultSet = $resultSet;
		$this->terms = $terms;
		$this->totalHits = $total;
	}

	function termMatches() {
		return $this->terms;
	}

	function numRows() {
		if ( $this->resultSet === false ) {
			return false;
		}

		return $this->resultSet->numRows();
	}

	public function extractResults() {
		if ( $this->resultSet === false ) {
			return [];
		}

		if ( $this->results === null ) {
			$this->results = [];
			$this->resultSet->rewind();
			while ( ( $row = $this->resultSet->fetchObject() ) !== false ) {
				$this->results[] = SearchResult::newFromTitle(
					Title::makeTitle( $row->page_namespace, $row->page_title ), $this
				);
			}
		}
		return $this->results;
	}

	function free() {
		if ( $this->resultSet === false ) {
			return false;
		}

		$this->resultSet->free();
	}

	function getTotalHits() {
		if ( !is_null( $this->totalHits ) ) {
			return $this->totalHits;
		} else {
			// Special:Search expects a number here.
			return $this->numRows();
		}
	}
}

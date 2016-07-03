<?php
/**
 * This class is used for different SQL-based search engines shipped with MediaWiki
 * @ingroup Search
 */
class SqlSearchResultSet extends SearchResultSet {
	protected $resultSet;
	protected $terms;
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

	function next() {
		if ( $this->resultSet === false ) {
			return false;
		}

		$row = $this->resultSet->fetchObject();
		if ( $row === false ) {
			return false;
		}

		return SearchResult::newFromTitle(
			Title::makeTitle( $row->page_namespace, $row->page_title )
		);
	}

	function rewind() {
		if ( $this->resultSet ) {
			$this->resultSet->rewind();
		}
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

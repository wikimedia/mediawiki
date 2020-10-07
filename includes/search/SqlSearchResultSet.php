<?php

use Wikimedia\Rdbms\IResultWrapper;

/**
 * This class is used for different SQL-based search engines shipped with MediaWiki
 * @ingroup Search
 */
class SqlSearchResultSet extends SearchResultSet {
	/** @noinspection PhpMissingParentConstructorInspection */

	/** @var IResultWrapper Result object from database */
	protected $resultSet;
	/** @var string[] Requested search query */
	protected $terms;
	/** @var int|null Total number of hits for $terms */
	protected $totalHits;

	/**
	 * @param IResultWrapper $resultSet
	 * @param string[] $terms
	 * @param int|null $total
	 */
	public function __construct( IResultWrapper $resultSet, array $terms, $total = null ) {
		parent::__construct();
		$this->resultSet = $resultSet;
		$this->terms = $terms;
		$this->totalHits = $total;
	}

	/**
	 * @return string[]
	 * @deprecated since 1.34
	 */
	public function termMatches() {
		return $this->terms;
	}

	public function numRows() {
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
			$terms = \MediaWiki\MediaWikiServices::getInstance()->getContentLanguage()
				->convertForSearchResult( $this->terms );
			while ( ( $row = $this->resultSet->fetchObject() ) !== false ) {
				$result = new SqlSearchResult(
					Title::makeTitle( $row->page_namespace, $row->page_title ),
					$terms
				);
				$this->augmentResult( $result );
				$this->results[] = $result;
			}
		}
		return $this->results;
	}

	public function getTotalHits() {
		if ( $this->totalHits !== null ) {
			return $this->totalHits;
		} else {
			// Special:Search expects a number here.
			return $this->numRows();
		}
	}
}

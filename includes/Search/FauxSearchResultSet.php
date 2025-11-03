<?php

use MediaWiki\Title\Title;

/**
 * A manually constructed search result set.
 * Mainly meant for supporting developer setups where the search operation might be
 * mocked or proxied.
 */
class FauxSearchResultSet extends SearchResultSet {

	/**
	 * @var int|null
	 * @see getTotalHits
	 */
	private $totalHits;

	/**
	 * @param array<Title|SearchResult> $results Search results
	 * @param int|null $totalHits See getTotalHits()
	 */
	public function __construct( array $results, $totalHits = null ) {
		$totalHits = max( count( $results ), $totalHits );
		$hasMoreResults = count( $results ) < $totalHits;
		parent::__construct( false, $hasMoreResults );
		$this->results = array_map( static function ( $result ) {
			if ( $result instanceof SearchResult ) {
				return $result;
			} elseif ( $result instanceof Title ) {
				return new FauxSearchResult( $result );
			} else {
				throw new InvalidArgumentException( '$results must contain Title or SearchResult' );
			}
		}, $results );
		$this->totalHits = $totalHits;
	}

	/** @inheritDoc */
	public function getTotalHits() {
		return $this->totalHits;
	}

}

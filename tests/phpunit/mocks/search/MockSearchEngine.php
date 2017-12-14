<?php

use MediaWiki\MediaWikiServices;

class MockSearchEngine extends SearchEngine {
	/** @var SearchResult[][] */
	private static $results = [];
	/** @var SearchResultSet[][] */
	private static $interwikiResults = [];

	public static function clearMockResults() {
		self::$results = [];
		self::$interwikiResults = [];
	}

	/**
	 * @param string $query The query searched for *after* initial
	 *  transformations have been applied.
	 * @param SearchResult[] $results The results to return for $query
	 */
	public static function addMockResults( $query, array $results ) {
		self::$results[$query] = $results;
		$lc = MediaWikiServices::getInstance()->getLinkCache();
		foreach ( $results as $result ) {
			// TODO: better page ids? Does it matter?
			$lc->addGoodLinkObj( mt_rand(), $result->getTitle() );
		}
	}

	/**
	 * @param SearchResultSet[][] $interwikiResults
	 */
	public static function setMockInterwikiResults( array $interwikiResults ) {
		self::$interwikiResults = $interwikiResults;
	}

	protected function doSearchText( $term ) {
		if ( isset( self::$results[ $term ] ) ) {
			$results = array_slice( self::$results[ $term ], $this->offset, $this->limit );
		} else {
			$results = [];
		}
		return new MockSearchResultSet( $results, self::$interwikiResults );
	}
}

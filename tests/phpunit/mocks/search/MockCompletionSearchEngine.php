<?php

use MediaWiki\MediaWikiServices;

/**
 * SearchEngine implementation for returning mocked completion search results.
 */
class MockCompletionSearchEngine extends SearchEngine {
	/** @var string[][] */
	private static $results = [];

	/**
	 * Reset any mocked results
	 */
	public static function clearMockResults() {
		self::$results = [];
	}

	/**
	 * Allows returning arbitrary lists of titles for completion search.
	 * Provided results will be sliced based on offset/limit of query.
	 *
	 * For results to exit the search engine they must pass Title::isKnown.
	 * Injecting into link cache is not enough, as LinkBatch will 
	 *
	 * @param string $query Search term as seen in completionSearchBackend
	 * @param string[] $result List of titles to respond to query with
	 */
	public static function addMockResults( $query, array $result ) {
		// Leading : ensures we don't treat another : as a namespace separator
		$normalized = Title::newFromText( ":$query" )->getText();
		self::$results[$normalized] = $result;
	}

	public function completionSearchBackend( $search ) {
		if ( !isset( self::$results[$search] ) ) {
			return SearchSuggestionSet::emptySet();
		}
		$results = array_slice( self::$results[$search], $this->offset, $this->limit );

		return SearchSuggestionSet::fromStrings( $results );
	}
}

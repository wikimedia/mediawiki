<?php

use MediaWiki\Title\Title;

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
	 * Injecting into link cache is not enough, as LinkBatch will mark them
	 * bad, they need to be injected into the DB.
	 *
	 * @param string $query Search term as seen in completionSearchBackend
	 * @param string[] $result List of titles to respond to query with
	 */
	public static function addMockResults( $query, array $result ) {
		// Leading : ensures we don't treat another : as a namespace separator
		$normalized = mb_strtolower( Title::newFromText( ":$query" )->getText() );
		self::$results[$normalized] = $result;
	}

	/** @inheritDoc */
	public function completionSearchBackend( $search ) {
		$search = mb_strtolower( $search );
		if ( !isset( self::$results[$search] ) ) {
			return SearchSuggestionSet::emptySuggestionSet();
		}
		$results = array_slice( self::$results[$search], $this->offset, $this->limit );

		return SearchSuggestionSet::fromStrings( $results );
	}
}

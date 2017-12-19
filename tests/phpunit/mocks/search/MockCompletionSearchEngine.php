<?php

use MediaWiki\MediaWikiServices;

/**
 * SearchEngine implementation for returning mocked completion search results.
 */
class MockCompletionSearchEngine extends SearchEngine {
	private static $completionSearchResult = [];

	public function completionSearchBackend( $search ) {
		if ( self::$completionSearchResult == null ) {
			self::$completionSearchResult = [];
			// TODO: Or does this have to be setup per-test?
			$lc = MediaWikiServices::getInstance()->getLinkCache();
			foreach ( range( 0, 10 ) as $i ) {
				$dbkey = "Search_Result_$i";
				$lc->addGoodLinkObj( 6543 + $i, new TitleValue( NS_MAIN, $dbkey ) );
				self::$completionSearchResult[] = "Search Result $i";
			}
		}
		$results = array_slice( self::$completionSearchResult, $this->offset, $this->limit );

		return SearchSuggestionSet::fromStrings( $results );
	}

}

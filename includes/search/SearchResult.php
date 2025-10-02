<?php
/**
 * Search engine result
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Search
 */

use MediaWiki\Title\Title;

/**
 * NOTE: this class is being refactored into an abstract base class.
 * If you extend this class directly, please implement all the methods declared
 * in RevisionSearchResultTrait or extend RevisionSearchResult.
 *
 * Once the hard-deprecation period is over (1.36?):
 * - all methods declared in RevisionSearchResultTrait should be declared
 *   as abstract in this class
 * - RevisionSearchResultTrait body should be moved to RevisionSearchResult and then removed without
 *   deprecation
 * - caveat: all classes extending this one may potentially break if they did not properly implement
 *   all the methods.
 * @ingroup Search
 */
class SearchResult {
	use SearchResultTrait;
	use RevisionSearchResultTrait;

	public function __construct() {
		if ( self::class === static::class ) {
			wfDeprecated( __METHOD__, '1.34' );
		}
	}

	/**
	 * Return a new SearchResult and initializes it with a title.
	 *
	 * @param Title $title
	 * @param ISearchResultSet|null $parentSet
	 * @return SearchResult
	 */
	public static function newFromTitle( $title, ?ISearchResultSet $parentSet = null ) {
		$result = new RevisionSearchResult( $title );
		if ( $parentSet ) {
			$parentSet->augmentResult( $result );
		}
		return $result;
	}
}

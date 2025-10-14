<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Search
 */

use MediaWiki\Title\Title;

/**
 * An abstract base class representing a search engine result
 *
 * @ingroup Search
 */
abstract class SearchResult {
	use SearchResultTrait;
	use RevisionSearchResultTrait;

	/**
	 * Return a new RevisionSearchResult and initializes it with a title.
	 *
	 * @param Title $title
	 * @param ISearchResultSet|null $parentSet
	 * @return RevisionSearchResult|ISearchResultSet
	 */
	public static function newFromTitle( $title, ?ISearchResultSet $parentSet = null ) {
		$result = new RevisionSearchResult( $title );
		if ( $parentSet ) {
			$parentSet->augmentResult( $result );
		}
		return $result;
	}
}

<?php
/**
 * Search engine result
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Search
 */

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
	public static function newFromTitle( $title, ISearchResultSet $parentSet = null ) {
		$result = new RevisionSearchResult( $title );
		if ( $parentSet ) {
			$parentSet->augmentResult( $result );
		}
		return $result;
	}
}

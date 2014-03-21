<?php
/**
 * Search result sets
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
 * @ingroup Search
 */
class SearchResultSet {
	/**
	 * Fetch an array of regular expression fragments for matching
	 * the search terms as parsed by this engine in a text extract.
	 * STUB
	 *
	 * @return Array
	 */
	function termMatches() {
		return array();
	}

	function numRows() {
		return 0;
	}

	/**
	 * Return true if results are included in this result set.
	 * STUB
	 *
	 * @return Boolean
	 */
	function hasResults() {
		return false;
	}

	/**
	 * Some search modes return a total hit count for the query
	 * in the entire article database. This may include pages
	 * in namespaces that would not be matched on the given
	 * settings.
	 *
	 * Return null if no total hits number is supported.
	 *
	 * @return Integer
	 */
	function getTotalHits() {
		return null;
	}

	/**
	 * Some search modes return a suggested alternate term if there are
	 * no exact hits. Returns true if there is one on this set.
	 *
	 * @return Boolean
	 */
	function hasSuggestion() {
		return false;
	}

	/**
	 * @return String: suggested query, null if none
	 */
	function getSuggestionQuery() {
		return null;
	}

	/**
	 * @return String: HTML highlighted suggested query, '' if none
	 */
	function getSuggestionSnippet() {
		return '';
	}

	/**
	 * Return a result set of hits on other (multiple) wikis associated with this one
	 *
	 * @return SearchResultSet
	 */
	function getInterwikiResults() {
		return null;
	}

	/**
	 * Check if there are results on other wikis
	 *
	 * @return Boolean
	 */
	function hasInterwikiResults() {
		return $this->getInterwikiResults() != null;
	}

	/**
	 * Fetches next search result, or false.
	 * STUB
	 *
	 * @return SearchResult
	 */
	function next() {
		return false;
	}

	/**
	 * Frees the result set, if applicable.
	 */
	function free() {
		// ...
	}

	/**
	 * Did the search contain search syntax?  If so, Special:Search won't offer
	 * the user a link to a create a page named by the search string because the
	 * name would contain the search syntax.
	 */
	public function searchContainedSyntax() {
		return false;
	}
}

/**
 * This class is used for different SQL-based search engines shipped with MediaWiki
 * @ingroup Search
 */
class SqlSearchResultSet extends SearchResultSet {

	protected $mResultSet;

	function __construct( $resultSet, $terms ) {
		$this->mResultSet = $resultSet;
		$this->mTerms = $terms;
	}

	function termMatches() {
		return $this->mTerms;
	}

	function numRows() {
		if ( $this->mResultSet === false ) {
			return false;
		}

		return $this->mResultSet->numRows();
	}

	function next() {
		if ( $this->mResultSet === false ) {
			return false;
		}

		$row = $this->mResultSet->fetchObject();
		if ( $row === false ) {
			return false;
		}

		return SearchResult::newFromRow( $row );
	}

	function free() {
		if ( $this->mResultSet === false ) {
			return false;
		}

		$this->mResultSet->free();
	}
}

/**
 * A SearchResultSet wrapper for SearchEngine::getNearMatch
 */
class SearchNearMatchResultSet extends SearchResultSet {
	private $fetched = false;

	/**
	 * @param $match mixed Title if matched, else null
	 */
	public function __construct( $match ) {
		$this->result = $match;
	}

	public function hasResult() {
		return (bool)$this->result;
	}

	public function numRows() {
		return $this->hasResults() ? 1 : 0;
	}

	public function next() {
		if ( $this->fetched || !$this->result ) {
			return false;
		}
		$this->fetched = true;
		return SearchResult::newFromTitle( $this->result );
	}
}

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
	protected $containedSyntax = false;

	public function __construct( $containedSyntax = false ) {
		$this->containedSyntax = $containedSyntax;
	}

	/**
	 * Fetch an array of regular expression fragments for matching
	 * the search terms as parsed by this engine in a text extract.
	 * STUB
	 *
	 * @return array
	 */
	function termMatches() {
		return array();
	}

	function numRows() {
		return 0;
	}

	/**
	 * Some search modes return a total hit count for the query
	 * in the entire article database. This may include pages
	 * in namespaces that would not be matched on the given
	 * settings.
	 *
	 * Return null if no total hits number is supported.
	 *
	 * @return int
	 */
	function getTotalHits() {
		return null;
	}

	/**
	 * Some search modes will run an alternative query that it thinks gives
	 * a better result than the provided search. Returns true if this has
	 * occured.
	 *
	 * @return bool
	 */
	function hasRewrittenQuery() {
		return false;
	}

	/**
	 * @return string|null The search the query was internally rewritten to,
	 *  or null when the result of the original query was returned.
	 */
	function getQueryAfterRewrite() {
		return null;
	}

	/**
	 * @return string|null Same as self::getQueryAfterRewrite(), but in HTML
	 *  and with changes highlighted. Null when the query was not rewritten.
	 */
	function getQueryAfterRewriteSnippet() {
		return null;
	}

	/**
	 * Some search modes return a suggested alternate term if there are
	 * no exact hits. Returns true if there is one on this set.
	 *
	 * @return bool
	 */
	function hasSuggestion() {
		return false;
	}

	/**
	 * @return string Suggested query, null if none
	 */
	function getSuggestionQuery() {
		return null;
	}

	/**
	 * @return string HTML highlighted suggested query, '' if none
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
	 * @return bool
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
	 * @return bool
	 */
	public function searchContainedSyntax() {
		return $this->containedSyntax;
	}
}

/**
 * This class is used for different SQL-based search engines shipped with MediaWiki
 * @ingroup Search
 */
class SqlSearchResultSet extends SearchResultSet {
	protected $resultSet;
	protected $terms;
	protected $totalHits;

	function __construct( $resultSet, $terms, $total = null ) {
		$this->resultSet = $resultSet;
		$this->terms = $terms;
		$this->totalHits = $total;
	}

	function termMatches() {
		return $this->terms;
	}

	function numRows() {
		if ( $this->resultSet === false ) {
			return false;
		}

		return $this->resultSet->numRows();
	}

	function next() {
		if ( $this->resultSet === false ) {
			return false;
		}

		$row = $this->resultSet->fetchObject();
		if ( $row === false ) {
			return false;
		}

		return SearchResult::newFromTitle(
			Title::makeTitle( $row->page_namespace, $row->page_title )
		);
	}

	function free() {
		if ( $this->resultSet === false ) {
			return false;
		}

		$this->resultSet->free();
	}

	function getTotalHits() {
		if ( !is_null( $this->totalHits ) ) {
			return $this->totalHits;
		} else {
			// Special:Search expects a number here.
			return $this->numRows();
		}
	}
}

/**
 * A SearchResultSet wrapper for SearchEngine::getNearMatch
 */
class SearchNearMatchResultSet extends SearchResultSet {
	private $fetched = false;

	/**
	 * @param Title|null $match Title if matched, else null
	 */
	public function __construct( $match ) {
		$this->result = $match;
	}

	public function numRows() {
		return $this->result ? 1 : 0;
	}

	public function next() {
		if ( $this->fetched || !$this->result ) {
			return false;
		}
		$this->fetched = true;
		return SearchResult::newFromTitle( $this->result );
	}
}

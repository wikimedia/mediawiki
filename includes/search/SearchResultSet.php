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
	 * Types of interwiki results
	 */
	/**
	 * Results that are displayed only together with existing main wiki results
	 * @var int
	 */
	const SECONDARY_RESULTS = 0;
	/**
	 * Results that can displayed even if no existing main wiki results exist
	 * @var int
	 */
	const INLINE_RESULTS = 1;

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
		return [];
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
	 * @return string|null Suggested query, null if none
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
	 * @return SearchResultSet[]
	 */
	function getInterwikiResults( $type = self::SECONDARY_RESULTS ) {
		return null;
	}

	/**
	 * Check if there are results on other wikis
	 *
	 * @return bool
	 */
	function hasInterwikiResults( $type = self::SECONDARY_RESULTS ) {
		return false;
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
	 * Rewind result set back to begining
	 */
	function rewind() {
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

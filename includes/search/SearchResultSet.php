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

use MediaWiki\Title\Title;
use Wikimedia\HtmlArmor\HtmlArmor;

/**
 * @ingroup Search
 */
class SearchResultSet extends BaseSearchResultSet {

	use SearchResultSetTrait;

	/** @var bool */
	protected $containedSyntax = false;

	/**
	 * Cache of titles.
	 * Lists titles of the result set, in the same order as results.
	 * @var Title[]|null
	 */
	private $titles;

	/**
	 * Cache of results - serialization of the result iterator
	 * as an array.
	 * @var SearchResult[]
	 */
	protected $results;

	/**
	 * @var bool True when there are more pages of search results available.
	 */
	private $hasMoreResults;

	/**
	 * @param bool $containedSyntax True when query is not requesting a simple
	 *  term match
	 * @param bool $hasMoreResults True when there are more pages of search
	 *  results available.
	 */
	public function __construct( $containedSyntax = false, $hasMoreResults = false ) {
		if ( static::class === self::class ) {
			// This class will eventually be abstract. SearchEngine implementations
			// already have to extend this class anyways to provide the actual
			// search results.
			wfDeprecated( __METHOD__, '1.32' );
		}
		$this->containedSyntax = $containedSyntax;
		$this->hasMoreResults = $hasMoreResults;
	}

	/** @inheritDoc */
	public function numRows() {
		return $this->count();
	}

	final public function count(): int {
		return count( $this->extractResults() );
	}

	/**
	 * Some search modes return a total hit count for the query
	 * in the entire article database. This may include pages
	 * in namespaces that would not be matched on the given
	 * settings.
	 *
	 * Return null if no total hits number is supported.
	 *
	 * @return int|null
	 */
	public function getTotalHits() {
		return null;
	}

	/**
	 * Some search modes will run an alternative query that it thinks gives
	 * a better result than the provided search. Returns true if this has
	 * occurred.
	 *
	 * @return bool
	 */
	public function hasRewrittenQuery() {
		return false;
	}

	/**
	 * @return string|null The search the query was internally rewritten to,
	 *  or null when the result of the original query was returned.
	 */
	public function getQueryAfterRewrite() {
		return null;
	}

	/**
	 * @return HtmlArmor|string|null Same as self::getQueryAfterRewrite(), but
	 *  with changes highlighted if HtmlArmor is returned. Null when the query
	 *  was not rewritten.
	 */
	public function getQueryAfterRewriteSnippet() {
		return null;
	}

	/**
	 * Some search modes return a suggested alternate term if there are
	 * no exact hits. Returns true if there is one on this set.
	 *
	 * @return bool
	 */
	public function hasSuggestion() {
		return false;
	}

	/**
	 * @return string|null Suggested query, null if none
	 */
	public function getSuggestionQuery() {
		return null;
	}

	/**
	 * @return HtmlArmor|string HTML highlighted suggested query, '' if none
	 */
	public function getSuggestionSnippet() {
		return '';
	}

	/**
	 * Return a result set of hits on other (multiple) wikis associated with this one
	 *
	 * @param int $type
	 * @return ISearchResultSet[]|null
	 */
	public function getInterwikiResults( $type = self::SECONDARY_RESULTS ) {
		return null;
	}

	/**
	 * Check if there are results on other wikis
	 *
	 * @param int $type
	 * @return bool
	 */
	public function hasInterwikiResults( $type = self::SECONDARY_RESULTS ) {
		return false;
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

	/**
	 * @return bool True when there are more pages of search results available.
	 */
	public function hasMoreResults() {
		return $this->hasMoreResults;
	}

	/**
	 * @param int $limit Shrink result set to $limit and flag
	 *  if more results are available.
	 */
	public function shrink( $limit ) {
		if ( $this->count() > $limit ) {
			$this->hasMoreResults = true;
			// shrinking result set for implementations that
			// have not implemented extractResults and use
			// the default cache location. Other implementations
			// must override this as well.
			if ( is_array( $this->results ) ) {
				$this->results = array_slice( $this->results, 0, $limit );
				$this->titles = null;
			} else {
				throw new \UnexpectedValueException(
					"When overriding result store extending classes must "
					. " also override " . __METHOD__ );
			}
		}
	}

	/**
	 * Extract all the results in the result set as array.
	 * @return SearchResult[]
	 */
	public function extractResults() {
		if ( $this->results === null ) {
			$this->results = [];
			if ( $this->numRows() == 0 ) {
				// Don't bother if we've got empty result
				return $this->results;
			}
			$this->rewind();
			foreach ( $this as $result ) {
				$this->results[] = $result;
			}
			$this->rewind();
		}
		return $this->results;
	}

	/**
	 * Extract all the titles in the result set.
	 * @return Title[]
	 */
	public function extractTitles() {
		if ( $this->titles === null ) {
			if ( $this->numRows() == 0 ) {
				// Don't bother if we've got empty result
				$this->titles = [];
			} else {
				$this->titles = array_map(
					static function ( SearchResult $result ) {
						return $result->getTitle();
					},
					$this->extractResults() );
			}
		}
		return $this->titles;
	}
}

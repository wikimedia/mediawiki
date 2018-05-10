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
class SearchResultSet implements IteratorAggregate {

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

	/**
	 * Cache of titles.
	 * Lists titles of the result set, in the same order as results.
	 * @var Title[]
	 */
	private $titles;

	/**
	 * Cache of results - serialization of the result iterator
	 * as an array.
	 * @var SearchResult[]
	 */
	protected $results;

	/**
	 * Set of result's extra data, indexed per result id
	 * and then per data item name.
	 * The structure is:
	 * PAGE_ID => [ augmentor name => data, ... ]
	 * @var array[]
	 */
	protected $extraData = [];

	/** @var ArrayIterator|null Iterator supporting BC iteration methods */
	private $bcIterator;

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
	 * @param int $type
	 * @return SearchResultSet[]
	 */
	function getInterwikiResults( $type = self::SECONDARY_RESULTS ) {
		return null;
	}

	/**
	 * Check if there are results on other wikis
	 *
	 * @param int $type
	 * @return bool
	 */
	function hasInterwikiResults( $type = self::SECONDARY_RESULTS ) {
		return false;
	}

	/**
	 * Fetches next search result, or false.
	 * STUB
	 * FIXME: refactor as iterator, so we could use nicer interfaces.
	 * @deprecated since 1.32; Use self::extractResults() or foreach
	 * @return SearchResult|false
	 */
	public function next() {
		wfDeprecated( __METHOD__, '1.32' );
		$it = $this->bcIterator();
		$searchResult = $it->current();
		$it->next();
		return $searchResult === null ? false : $searchResult;
	}

	/**
	 * Rewind result set back to beginning
	 * @deprecated since 1.32; Use self::extractResults() or foreach
	 */
	public function rewind() {
		wfDeprecated( __METHOD__, '1.32' );
		$this->bcIterator()->rewind();
	}

	private function bcIterator() {
		if ( $this->bcIterator === null ) {
			$this->bcIterator = 'RECURSION';
			$this->bcIterator = new ArrayIterator( $this->extractResults() );
		} elseif ( $this->bcIterator === 'RECURSION' ) {
			// Either next/rewind or extractResults must be implemented.
			// This class was potentially instantiated directly. It should
			// be abstract but thats a breaking change...
			if ( get_class( $this ) === __CLASS__ ) {
				wfDeprecated( __CLASS__ . ' should not be instantiated directly', '1.32' );
			} else {
				wfDeprecated(
					__CLASS__ . ' subclassing SearchResultSet must implement extractResults()',
					'1.32'
				);
			}
			$this->bcIterator = new ArrayIterator( [] );
		}
		return $this->bcIterator;
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

	/**
	 * Extract all the results in the result set as array.
	 * @return SearchResult[]
	 */
	public function extractResults() {
		if ( is_null( $this->results ) ) {
			$this->results = [];
			if ( $this->numRows() == 0 ) {
				// Don't bother if we've got empty result
				return $this->results;
			}
			$this->rewind();
			while ( ( $result = $this->next() ) != false ) {
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
		if ( is_null( $this->titles ) ) {
			if ( $this->numRows() == 0 ) {
				// Don't bother if we've got empty result
				$this->titles = [];
			} else {
				$this->titles = array_map(
					function ( SearchResult $result ) {
						return $result->getTitle();
					},
					$this->extractResults() );
			}
		}
		return $this->titles;
	}

	/**
	 * Sets augmented data for result set.
	 * @param string $name Extra data item name
	 * @param array[] $data Extra data as PAGEID => data
	 */
	public function setAugmentedData( $name, $data ) {
		foreach ( $data as $id => $resultData ) {
			$this->extraData[$id][$name] = $resultData;
		}
	}

	/**
	 * Returns extra data for specific result and store it in SearchResult object.
	 * @param SearchResult $result
	 * @return array|null List of data as name => value or null if none present.
	 */
	public function augmentResult( SearchResult $result ) {
		$id = $result->getTitle()->getArticleID();
		if ( !$id ) {
			return null;
		}
		$fn = function () use ( $id ) {
			if ( isset( $this->extraData[$id] ) ) {
				return $this->extraData[$id];
			} else {
				return null;
			}
		};
		$result->setExtensionData( $fn );
		return $fn();
	}

	/**
	 * @return int|null The offset the current page starts at. Typically
	 *  this should be null to allow the UI to decide on its own, but in
	 *  special cases like interleaved AB tests specifying explicitly is
	 *  necessary.
	 */
	public function getOffset() {
		return null;
	}

	final public function getIterator() {
		return new ArrayIterator( $this->extractResults() );
	}
}

<?php

/**
 * BaseSearchResultSet is the base class that must be extended by SearchEngine
 * search result set implementations.
 *
 * This base class is meant to hold B/C behaviors and to be useful it must never:
 * - be used as type hints (ISearchResultSet must be used for this)
 * - implement a constructor
 * - declare utility methods
 *
 * @stable to extend
 * @ingroup Search
 */
abstract class BaseSearchResultSet implements ISearchResultSet {

	/**
	 * @var ArrayIterator|null Iterator supporting BC iteration methods
	 */
	private $bcIterator;

	/**
	 * Fetches next search result, or false.
	 * @deprecated since 1.32; Use self::extractResults() or foreach
	 * @return SearchResult|false
	 */
	public function next() {
		wfDeprecated( __METHOD__, '1.32' );
		$it = $this->bcIterator();
		$searchResult = $it->current();
		$it->next();
		return $searchResult ?? false;
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
			$this->bcIterator = $this->getIterator();
		} elseif ( $this->bcIterator === 'RECURSION' ) {
			// Either next/rewind or extractResults must be implemented.  This
			// class was potentially instantiated directly. It should be
			// abstract with abstract methods to enforce this but that's a
			// breaking change...
			wfDeprecated( static::class . ' without implementing extractResults', '1.32' );
			$this->bcIterator = new ArrayIterator( [] );
		}
		return $this->bcIterator;
	}

	/**
	 * Fetch an array of regular expression fragments for matching
	 * the search terms as parsed by this engine in a text extract.
	 * STUB
	 *
	 * @return string[]
	 * @deprecated since 1.34 (use SqlSearchResult)
	 */
	public function termMatches() {
		return [];
	}

	/**
	 * Frees the result set, if applicable.
	 * @deprecated noop since 1.34
	 */
	public function free() {
	}
}

<?php

/**
 * A set of SearchEngine results.
 * Must not be directly implemented by extension, please extend BaseSearchResultSet instead.
 * This interface must only be used for type hinting.
 *
 * @unstable for implementation, extensions should extend the BaseSearchResultSet base class.
 * @see BaseSearchResultSet
 * @ingroup Search
 */
interface ISearchResultSet extends \Countable, \IteratorAggregate {
	/**
	 * Identifier for interwiki results that are displayed only together with existing main wiki
	 * results.
	 */
	public const SECONDARY_RESULTS = 0;

	/**
	 * Identifier for interwiki results that can be displayed even if no existing main wiki results
	 * exist.
	 */
	public const INLINE_RESULTS = 1;

	/**
	 * @return int
	 */
	public function numRows();

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
	public function getTotalHits();

	/**
	 * Some search modes will run an alternative query that it thinks gives
	 * a better result than the provided search. Returns true if this has
	 * occurred.
	 *
	 * NOTE: In practice this has only been applied when the original query
	 * returned no results. UI messages, such as `search-rewritten`, have
	 * this assumption baked in.
	 *
	 * @return bool
	 */
	public function hasRewrittenQuery();

	/**
	 * @return string|null The search the query was internally rewritten to,
	 *  or null when the result of the original query was returned.
	 */
	public function getQueryAfterRewrite();

	/**
	 * @return HtmlArmor|string|null Same as self::getQueryAfterRewrite(), but
	 *  with changes to the string highlighted in HTML and wrapped in
	 *  HtmlArmor.  If highlighting is not available the rewritten query will
	 *  be returned. When the self::hasRewrittenQuery returns false (i.e. the
	 *  results have not been rewritten) null will be returned.
	 */
	public function getQueryAfterRewriteSnippet();

	/**
	 * Some search modes return a suggested alternate term if there are
	 * no exact hits. Returns true if there is one on this set.
	 *
	 * @return bool
	 */
	public function hasSuggestion();

	/**
	 * @return string|null Suggested query, null if none
	 */
	public function getSuggestionQuery();

	/**
	 * @return HtmlArmor|string Same as self::getSuggestionQuery(), but with
	 *  changes to the string highlighted in HTML and wrapped in HtmlArmor. If
	 *  highlighting is not available the suggested query will be returned. When
	 *  self::hasSuggestion returns false (i.e no suggested query) the empty
	 *  string will be returned.
	 */
	public function getSuggestionSnippet();

	/**
	 * Return a result set of hits on other (multiple) wikis associated with this one
	 *
	 * @param int $type
	 * @return ISearchResultSet[]
	 */
	public function getInterwikiResults( $type = self::SECONDARY_RESULTS );

	/**
	 * Check if there are results on other wikis
	 *
	 * @param int $type
	 * @return bool
	 */
	public function hasInterwikiResults( $type = self::SECONDARY_RESULTS );

	/**
	 * Did the search contain search syntax?  If so, Special:Search won't offer
	 * the user a link to a create a page named by the search string because the
	 * name would contain the search syntax.
	 * @return bool
	 */
	public function searchContainedSyntax();

	/**
	 * @return bool True when there are more pages of search results available.
	 */
	public function hasMoreResults();

	/**
	 * @param int $limit Shrink result set to $limit and flag
	 *  if more results are available.
	 */
	public function shrink( $limit );

	/**
	 * Extract all the results in the result set as array.
	 * @return SearchResult[]
	 */
	public function extractResults();

	/**
	 * Extract all the titles in the result set.
	 * @return Title[]
	 */
	public function extractTitles();

	/**
	 * Sets augmented data for result set.
	 * @param string $name Extra data item name
	 * @param array[] $data Extra data as PAGEID => data
	 */
	public function setAugmentedData( $name, $data );

	/**
	 * Returns extra data for specific result and store it in SearchResult object.
	 * @param SearchResult $result
	 */
	public function augmentResult( SearchResult $result );

	/**
	 * @return int|null The offset the current page starts at. Typically
	 *  this should be null to allow the UI to decide on its own, but in
	 *  special cases like interleaved AB tests specifying explicitly is
	 *  necessary.
	 */
	public function getOffset();
}

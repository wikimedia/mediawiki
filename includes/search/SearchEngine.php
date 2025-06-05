<?php
/**
 * Basic search engine
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
 * @defgroup Search Search
 */

use MediaWiki\Config\Config;
use MediaWiki\Content\Content;
use MediaWiki\Exception\MWUnknownContentModelException;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;
use MediaWiki\Search\TitleMatcher;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * Contain a class for special pages
 * @stable to extend
 * @ingroup Search
 */
abstract class SearchEngine {
	public const DEFAULT_SORT = 'relevance';

	/** @var string */
	public $prefix = '';

	/** @var int[]|null */
	public $namespaces = [ NS_MAIN ];

	/** @var int */
	protected $limit = 10;

	/** @var int */
	protected $offset = 0;

	/**
	 * @var string[]
	 * @deprecated since 1.34
	 */
	protected $searchTerms = [];

	/** @var bool */
	protected $showSuggestion = true;
	/** @var string */
	private $sort = self::DEFAULT_SORT;

	/** @var array Feature values */
	protected $features = [];

	/** @var HookContainer */
	private $hookContainer;

	/** @var HookRunner */
	private $hookRunner;

	/** Profile type for completionSearch */
	public const COMPLETION_PROFILE_TYPE = 'completionSearchProfile';

	/** Profile type for query independent ranking features */
	public const FT_QUERY_INDEP_PROFILE_TYPE = 'fulltextQueryIndepProfile';

	/** Integer flag for legalSearchChars: includes all chars allowed in a search query */
	protected const CHARS_ALL = 1;

	/** Integer flag for legalSearchChars: includes all chars allowed in a search term */
	protected const CHARS_NO_SYNTAX = 2;

	/**
	 * Perform a full text search query and return a result set.
	 * If full text searches are not supported or disabled, return null.
	 *
	 * @note As of 1.32 overriding this function is deprecated. It will
	 * be converted to final in 1.34. Override self::doSearchText().
	 *
	 * @param string $term Raw search term
	 * @return ISearchResultSet|Status|null
	 */
	public function searchText( $term ) {
		return $this->maybePaginate( function () use ( $term ) {
			return $this->doSearchText( $term );
		} );
	}

	/**
	 * Perform a full text search query and return a result set.
	 *
	 * @stable to override
	 *
	 * @param string $term Raw search term
	 * @return ISearchResultSet|Status|null
	 * @since 1.32
	 */
	protected function doSearchText( $term ) {
		return null;
	}

	/**
	 * Perform a title search in the article archive.
	 * NOTE: these results still should be filtered by
	 * matching against PageArchive, permissions checks etc
	 * The results returned by this methods are only suggestions and
	 * may not end up being shown to the user.
	 *
	 * @note As of 1.32 overriding this function is deprecated. It will
	 * be converted to final in 1.34. Override self::doSearchArchiveTitle().
	 *
	 * @param string $term Raw search term
	 * @return Status
	 * @since 1.29
	 */
	public function searchArchiveTitle( $term ) {
		return $this->doSearchArchiveTitle( $term );
	}

	/**
	 * Perform a title search in the article archive.
	 *
	 * @stable to override
	 *
	 * @param string $term Raw search term
	 * @return Status
	 * @since 1.32
	 */
	protected function doSearchArchiveTitle( $term ) {
		return Status::newGood( [] );
	}

	/**
	 * Perform a title-only search query and return a result set.
	 * If title searches are not supported or disabled, return null.
	 * STUB
	 *
	 * @note As of 1.32 overriding this function is deprecated. It will
	 * be converted to final in 1.34. Override self::doSearchTitle().
	 *
	 * @param string $term Raw search term
	 * @return ISearchResultSet|null
	 */
	public function searchTitle( $term ) {
		return $this->maybePaginate( function () use ( $term ) {
			return $this->doSearchTitle( $term );
		} );
	}

	/**
	 * Perform a title-only search query and return a result set.
	 *
	 * @stable to override
	 *
	 * @param string $term Raw search term
	 * @return ISearchResultSet|null
	 * @since 1.32
	 */
	protected function doSearchTitle( $term ) {
		return null;
	}

	/**
	 * Performs an overfetch and shrink operation to determine if
	 * the next page is available for search engines that do not
	 * explicitly implement their own pagination.
	 *
	 * @param Closure $fn Takes no arguments
	 * @return ISearchResultSet|Status<ISearchResultSet>|null Result of calling $fn
	 */
	private function maybePaginate( Closure $fn ) {
		if ( $this instanceof PaginatingSearchEngine ) {
			return $fn();
		}
		$this->limit++;
		try {
			$resultSetOrStatus = $fn();
		} finally {
			$this->limit--;
		}

		$resultSet = null;
		if ( $resultSetOrStatus instanceof ISearchResultSet ) {
			$resultSet = $resultSetOrStatus;
		} elseif ( $resultSetOrStatus instanceof Status &&
			$resultSetOrStatus->getValue() instanceof ISearchResultSet
		) {
			$resultSet = $resultSetOrStatus->getValue();
		}
		if ( $resultSet ) {
			$resultSet->shrink( $this->limit );
		}

		return $resultSetOrStatus;
	}

	/**
	 * @since 1.18
	 * @stable to override
	 *
	 * @param string $feature
	 * @return bool
	 */
	public function supports( $feature ) {
		switch ( $feature ) {
			case 'search-update':
				return true;
			case 'title-suffix-filter':
			default:
				return false;
		}
	}

	/**
	 * Way to pass custom data for engines
	 * @since 1.18
	 * @param string $feature
	 * @param mixed $data
	 */
	public function setFeatureData( $feature, $data ) {
		$this->features[$feature] = $data;
	}

	/**
	 * Way to retrieve custom data set by setFeatureData
	 * or by the engine itself.
	 * @since 1.29
	 * @param string $feature feature name
	 * @return mixed the feature value or null if unset
	 */
	public function getFeatureData( $feature ) {
		return $this->features[$feature] ?? null;
	}

	/**
	 * When overridden in derived class, performs database-specific conversions
	 * on text to be used for searching or updating search index.
	 * Default implementation does nothing (simply returns $string).
	 *
	 * @param string $string String to process
	 * @return string
	 */
	public function normalizeText( $string ) {
		// Some languages such as Chinese require word segmentation
		return MediaWikiServices::getInstance()->getContentLanguage()->segmentByWord( $string );
	}

	/**
	 * Get service class to finding near matches.
	 *
	 * @return TitleMatcher
	 * @deprecated since 1.40, use MediaWikiServices::getInstance()->getTitleMatcher()
	 */
	public function getNearMatcher( Config $config ) {
		return MediaWikiServices::getInstance()->getTitleMatcher();
	}

	/**
	 * Get near matcher for default SearchEngine.
	 *
	 * @return TitleMatcher
	 * @deprecated since 1.40, MediaWikiServices::getInstance()->getTitleMatcher()
	 */
	protected static function defaultNearMatcher() {
		wfDeprecated( __METHOD__, '1.40' );
		return MediaWikiServices::getInstance()->getTitleMatcher();
	}

	/**
	 * Get chars legal for search
	 * @param int $type type of search chars (see self::CHARS_ALL
	 * and self::CHARS_NO_SYNTAX). Defaults to CHARS_ALL
	 * @return string
	 */
	public function legalSearchChars( $type = self::CHARS_ALL ) {
		return "A-Za-z_'.0-9\\x80-\\xFF\\-";
	}

	/**
	 * Set the maximum number of results to return
	 * and how many to skip before returning the first.
	 *
	 * @param int $limit
	 * @param int $offset
	 */
	public function setLimitOffset( $limit, $offset = 0 ) {
		$this->limit = intval( $limit );
		$this->offset = intval( $offset );
	}

	/**
	 * Set which namespaces the search should include.
	 * Give an array of namespace index numbers.
	 *
	 * @param int[]|null $namespaces
	 */
	public function setNamespaces( $namespaces ) {
		if ( $namespaces ) {
			// Filter namespaces to only keep valid ones
			$validNs = MediaWikiServices::getInstance()->getSearchEngineConfig()->searchableNamespaces();
			$namespaces = array_filter( $namespaces, static function ( $ns ) use( $validNs ) {
				return $ns < 0 || isset( $validNs[$ns] );
			} );
		} else {
			$namespaces = [];
		}
		$this->namespaces = $namespaces;
	}

	/**
	 * Set whether the searcher should try to build a suggestion.  Note: some searchers
	 * don't support building a suggestion in the first place and others don't respect
	 * this flag.
	 *
	 * @param bool $showSuggestion Should the searcher try to build suggestions
	 */
	public function setShowSuggestion( $showSuggestion ) {
		$this->showSuggestion = $showSuggestion;
	}

	/**
	 * Get the valid sort directions.  All search engines support 'relevance' but others
	 * might support more. The default in all implementations must be 'relevance.'
	 *
	 * @since 1.25
	 * @stable to override
	 *
	 * @return string[] the valid sort directions for setSort
	 */
	public function getValidSorts() {
		return [ self::DEFAULT_SORT ];
	}

	/**
	 * Set the sort direction of the search results. Must be one returned by
	 * SearchEngine::getValidSorts()
	 *
	 * @since 1.25
	 * @param string $sort sort direction for query result
	 */
	public function setSort( $sort ) {
		if ( !in_array( $sort, $this->getValidSorts() ) ) {
			throw new InvalidArgumentException( "Invalid sort: $sort. " .
				"Must be one of: " . implode( ', ', $this->getValidSorts() ) );
		}
		$this->sort = $sort;
	}

	/**
	 * Get the sort direction of the search results
	 *
	 * @since 1.25
	 * @return string
	 */
	public function getSort() {
		return $this->sort;
	}

	/**
	 * Parse some common prefixes: all (search everything)
	 * or namespace names and set the list of namespaces
	 * of this class accordingly.
	 *
	 * @deprecated since 1.32; should be handled internally by the search engine
	 * @param string $query
	 * @return string
	 */
	public function replacePrefixes( $query ) {
		return $query;
	}

	/**
	 * Parse some common prefixes: all (search everything)
	 * or namespace names
	 *
	 * @param string $query
	 * @param bool $withAllKeyword activate support of the "all:" keyword and its
	 * translations to activate searching on all namespaces.
	 * @param bool $withPrefixSearchExtractNamespaceHook call the PrefixSearchExtractNamespace hook
	 *  if classic namespace identification did not match.
	 * @return false|array false if no namespace was extracted, an array
	 * with the parsed query at index 0 and an array of namespaces at index
	 * 1 (or null for all namespaces).
	 */
	public static function parseNamespacePrefixes(
		$query,
		$withAllKeyword = true,
		$withPrefixSearchExtractNamespaceHook = false
	) {
		$parsed = $query;
		if ( strpos( $query, ':' ) === false ) { // nothing to do
			return false;
		}
		$extractedNamespace = null;

		$allQuery = false;
		if ( $withAllKeyword ) {
			$allkeywords = [];

			$allkeywords[] = wfMessage( 'searchall' )->inContentLanguage()->text() . ":";
			// force all: so that we have a common syntax for all the wikis
			if ( !in_array( 'all:', $allkeywords ) ) {
				$allkeywords[] = 'all:';
			}

			foreach ( $allkeywords as $kw ) {
				if ( str_starts_with( $query, $kw ) ) {
					$parsed = substr( $query, strlen( $kw ) );
					$allQuery = true;
					break;
				}
			}
		}

		if ( !$allQuery && strpos( $query, ':' ) !== false ) {
			$prefix = str_replace( ' ', '_', substr( $query, 0, strpos( $query, ':' ) ) );
			$services = MediaWikiServices::getInstance();
			$index = $services->getContentLanguage()->getNsIndex( $prefix );
			if ( $index !== false ) {
				$extractedNamespace = [ $index ];
				$parsed = substr( $query, strlen( $prefix ) + 1 );
			} elseif ( $withPrefixSearchExtractNamespaceHook ) {
				$hookNamespaces = [ NS_MAIN ];
				$hookQuery = $query;
				( new HookRunner( $services->getHookContainer() ) )
					->onPrefixSearchExtractNamespace( $hookNamespaces, $hookQuery );
				if ( $hookQuery !== $query ) {
					$parsed = $hookQuery;
					$extractedNamespace = $hookNamespaces;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		return [ $parsed, $extractedNamespace ];
	}

	/**
	 * Find snippet highlight settings for all users
	 * @return array Contextlines, contextchars
	 * @deprecated since 1.34; use the SearchHighlighter constants directly
	 * @see SearchHighlighter::DEFAULT_CONTEXT_CHARS
	 * @see SearchHighlighter::DEFAULT_CONTEXT_LINES
	 */
	public static function userHighlightPrefs() {
		$contextlines = SearchHighlighter::DEFAULT_CONTEXT_LINES;
		$contextchars = SearchHighlighter::DEFAULT_CONTEXT_CHARS;
		return [ $contextlines, $contextchars ];
	}

	/**
	 * Create or update the search index record for the given page.
	 * Title and text should be pre-processed.
	 * STUB
	 *
	 * @param int $id
	 * @param string $title
	 * @param string $text
	 */
	public function update( $id, $title, $text ) {
		// no-op
	}

	/**
	 * Update a search index record's title only.
	 * Title should be pre-processed.
	 * STUB
	 *
	 * @param int $id
	 * @param string $title
	 */
	public function updateTitle( $id, $title ) {
		// no-op
	}

	/**
	 * Delete an indexed page
	 * Title should be pre-processed.
	 * STUB
	 *
	 * @param int $id Page id that was deleted
	 * @param string $title Title of page that was deleted
	 */
	public function delete( $id, $title ) {
		// no-op
	}

	/**
	 * Get the raw text for updating the index from a content object
	 * Nicer search backends could possibly do something cooler than
	 * just returning raw text
	 *
	 * @todo This isn't ideal, we'd really like to have content-specific handling here
	 * @param Title $t Title we're indexing
	 * @param Content|null $c Content of the page to index
	 * @return string
	 * @deprecated since 1.34 use Content::getTextForSearchIndex directly
	 */
	public function getTextFromContent( Title $t, ?Content $c = null ) {
		return $c ? $c->getTextForSearchIndex() : '';
	}

	/**
	 * If an implementation of SearchEngine handles all of its own text processing
	 * in getTextFromContent() and doesn't require SearchUpdate::updateText()'s
	 * rather silly handling, it should return true here instead.
	 *
	 * @return bool
	 * @deprecated since 1.34 no longer needed since getTextFromContent is being deprecated
	 */
	public function textAlreadyUpdatedForIndex() {
		return false;
	}

	/**
	 * Makes search simple string if it was namespaced.
	 * Sets namespaces of the search to namespaces extracted from string.
	 * @param string $search
	 * @return string Simplified search string
	 */
	protected function normalizeNamespaces( $search ) {
		$queryAndNs = self::parseNamespacePrefixes( $search, false, true );
		if ( $queryAndNs !== false ) {
			$this->setNamespaces( $queryAndNs[1] );
			return $queryAndNs[0];
		}
		return $search;
	}

	/**
	 * Perform an overfetch of completion search results. This allows
	 * determining if another page of results is available.
	 *
	 * @param string $search
	 * @return SearchSuggestionSet
	 */
	protected function completionSearchBackendOverfetch( $search ) {
		$this->limit++;
		try {
			return $this->completionSearchBackend( $search );
		} finally {
			$this->limit--;
		}
	}

	/**
	 * Perform a completion search.
	 * Does not resolve namespaces and does not check variants.
	 * Search engine implementations may want to override this function.
	 *
	 * @stable to override
	 *
	 * @param string $search
	 * @return SearchSuggestionSet
	 */
	protected function completionSearchBackend( $search ) {
		$results = [];

		$search = trim( $search );

		if ( !in_array( NS_SPECIAL, $this->namespaces ) && // We do not run hook on Special: search
			!$this->getHookRunner()->onPrefixSearchBackend(
				$this->namespaces, $search, $this->limit, $results, $this->offset )
		) {
			// False means hook worked.
			// FIXME: Yes, the API is weird. That's why it is going to be deprecated.

			return SearchSuggestionSet::fromStrings( $results );
		} else {
			// Hook did not do the job, use default simple search
			$results = $this->simplePrefixSearch( $search );
			return SearchSuggestionSet::fromTitles( $results );
		}
	}

	/**
	 * Perform a completion search.
	 * @param string $search
	 * @return SearchSuggestionSet
	 */
	public function completionSearch( $search ) {
		if ( trim( $search ) === '' ) {
			return SearchSuggestionSet::emptySuggestionSet(); // Return empty result
		}
		$search = $this->normalizeNamespaces( $search );
		$suggestions = $this->completionSearchBackendOverfetch( $search );
		return $this->processCompletionResults( $search, $suggestions );
	}

	/**
	 * Perform a completion search with variants.
	 * @stable to override
	 *
	 * @param string $search
	 * @return SearchSuggestionSet
	 */
	public function completionSearchWithVariants( $search ) {
		if ( trim( $search ) === '' ) {
			return SearchSuggestionSet::emptySuggestionSet(); // Return empty result
		}
		$search = $this->normalizeNamespaces( $search );

		$results = $this->completionSearchBackendOverfetch( $search );
		$fallbackLimit = 1 + $this->limit - $results->getSize();
		if ( $fallbackLimit > 0 ) {
			$services = MediaWikiServices::getInstance();
			$fallbackSearches = $services->getLanguageConverterFactory()
				->getLanguageConverter( $services->getContentLanguage() )
				->autoConvertToAllVariants( $search );
			$fallbackSearches = array_diff( array_unique( $fallbackSearches ), [ $search ] );

			$origLimit = $this->limit;
			$origOffset = $this->offset;
			foreach ( $fallbackSearches as $fbs ) {
				try {
					$this->setLimitOffset( $fallbackLimit );
					$fallbackSearchResult = $this->completionSearch( $fbs );
					$results->appendAll( $fallbackSearchResult );
					$fallbackLimit -= $fallbackSearchResult->getSize();
				} finally {
					$this->setLimitOffset( $origLimit, $origOffset );
				}
				if ( $fallbackLimit <= 0 ) {
					break;
				}
			}
		}
		return $this->processCompletionResults( $search, $results );
	}

	/**
	 * Extract titles from completion results
	 * @param SearchSuggestionSet $completionResults
	 * @return Title[]
	 */
	public function extractTitles( SearchSuggestionSet $completionResults ) {
		return $completionResults->map( static function ( SearchSuggestion $sugg ) {
			return $sugg->getSuggestedTitle();
		} );
	}

	/**
	 * Process completion search results.
	 * Resolves the titles and rescores.
	 * @param string $search
	 * @param SearchSuggestionSet $suggestions
	 * @return SearchSuggestionSet
	 */
	protected function processCompletionResults( $search, SearchSuggestionSet $suggestions ) {
		// We over-fetched to determine pagination. Shrink back down if we have extra results
		// and mark if pagination is possible
		$suggestions->shrink( $this->limit );

		$search = trim( $search );
		// preload the titles with LinkBatch
		$suggestedTitles = $suggestions->map( static function ( SearchSuggestion $sugg ) {
			return $sugg->getSuggestedTitle();
		} );
		$linkBatchFactory = MediaWikiServices::getInstance()->getLinkBatchFactory();
		$linkBatchFactory->newLinkBatch( $suggestedTitles )
			->setCaller( __METHOD__ )
			->execute();

		$diff = $suggestions->filter( static function ( SearchSuggestion $sugg ) {
			return $sugg->getSuggestedTitle()->isKnown();
		} );
		if ( $diff > 0 ) {
			$statsFactory = MediaWikiServices::getInstance()->getStatsFactory();
			$statsFactory->getCounter( 'search_completion_missing_total' )
				->incrementBy( $diff );
		}

		// SearchExactMatchRescorer should probably be refactored to work directly on top of a SearchSuggestionSet
		// instead of converting it to array and trying to infer if it has re-scored anything by inspected the head
		// of the returned array.
		$results = $suggestions->map( static function ( SearchSuggestion $sugg ) {
			return $sugg->getSuggestedTitle()->getPrefixedText();
		} );

		$rescorer = new SearchExactMatchRescorer();
		if ( $this->offset === 0 ) {
			// Rescore results with an exact title match
			// NOTE: in some cases like cross-namespace redirects
			// (frequently used as shortcuts e.g. WP:WP on huwiki) some
			// backends like Cirrus will return no results. We should still
			// try an exact title match to workaround this limitation
			$rescoredResults = $rescorer->rescore( $search, $this->namespaces, $results, $this->limit );
		} else {
			// No need to rescore if offset is not 0
			// The exact match must have been returned at position 0
			// if it existed.
			$rescoredResults = $results;
		}

		if ( count( $rescoredResults ) > 0 ) {
			$found = array_search( $rescoredResults[0], $results );
			if ( $found === false ) {
				// If the first result is not in the previous array it
				// means that we found a new exact match
				$exactMatch = SearchSuggestion::fromTitle( 0, Title::newFromText( $rescoredResults[0] ) );
				$suggestions->prepend( $exactMatch );
				if ( $rescorer->getReplacedRedirect() !== null ) {
					// the exact match rescorer replaced one of the suggestion found by the search engine
					// let's remove it from our suggestions set to avoid showing duplicates
					$suggestions->remove( SearchSuggestion::fromTitle( 0,
						Title::newFromText( $rescorer->getReplacedRedirect() ) ) );
				}
				$suggestions->shrink( $this->limit );
			} else {
				// if the first result is not the same we need to rescore
				if ( $found > 0 ) {
					$suggestions->rescore( $found );
				}
			}
		}

		return $suggestions;
	}

	/**
	 * Simple prefix search for subpages.
	 * @param string $search
	 * @return Title[]
	 */
	public function defaultPrefixSearch( $search ) {
		if ( trim( $search ) === '' ) {
			return [];
		}

		$search = $this->normalizeNamespaces( $search );
		return $this->simplePrefixSearch( $search );
	}

	/**
	 * Call out to simple search backend.
	 * Defaults to TitlePrefixSearch.
	 * @param string $search
	 * @return Title[]
	 */
	protected function simplePrefixSearch( $search ) {
		// Use default database prefix search
		$backend = new TitlePrefixSearch;
		return $backend->defaultSearchBackend( $this->namespaces, $search, $this->limit, $this->offset );
	}

	/**
	 * Get a list of supported profiles.
	 * Some search engine implementations may expose specific profiles to fine-tune
	 * its behaviors.
	 * The profile can be passed as a feature data with setFeatureData( $profileType, $profileName )
	 * The array returned by this function contains the following keys:
	 * - name: the profile name to use with setFeatureData
	 * - desc-message: the i18n description
	 * - default: set to true if this profile is the default
	 *
	 * @since 1.28
	 * @stable to override
	 *
	 * @param string $profileType the type of profiles
	 * @param User|null $user the user requesting the list of profiles
	 * @return array|null the list of profiles or null if none available
	 * @phan-return null|array{name:string,desc-message:string,default?:bool}
	 */
	public function getProfiles( $profileType, ?User $user = null ) {
		return null;
	}

	/**
	 * Create a search field definition.
	 * Specific search engines should override this method to create search fields.
	 * @stable to override
	 *
	 * @param string $name
	 * @param string $type One of the types in SearchIndexField::INDEX_TYPE_*
	 * @return SearchIndexField
	 * @since 1.28
	 */
	public function makeSearchFieldMapping( $name, $type ) {
		return new NullIndexField();
	}

	/**
	 * Get fields for search index
	 * @since 1.28
	 * @return SearchIndexField[] Index field definitions for all content handlers
	 */
	public function getSearchIndexFields() {
		$models = MediaWikiServices::getInstance()->getContentHandlerFactory()->getContentModels();
		$fields = [];
		$seenHandlers = new SplObjectStorage();
		foreach ( $models as $model ) {
			try {
				$handler = MediaWikiServices::getInstance()
					->getContentHandlerFactory()
					->getContentHandler( $model );
			} catch ( MWUnknownContentModelException $e ) {
				// If we can find no handler, ignore it
				continue;
			}
			// Several models can have the same handler, so avoid processing it repeatedly
			if ( $seenHandlers->contains( $handler ) ) {
				// We already did this one
				continue;
			}
			$seenHandlers->attach( $handler );
			$handlerFields = $handler->getFieldsForSearchIndex( $this );
			foreach ( $handlerFields as $fieldName => $fieldData ) {
				if ( empty( $fields[$fieldName] ) ) {
					$fields[$fieldName] = $fieldData;
				} else {
					// TODO: do we allow some clashes with the same type or reject all of them?
					$mergeDef = $fields[$fieldName]->merge( $fieldData );
					if ( !$mergeDef ) {
						throw new InvalidArgumentException( "Duplicate field $fieldName for model $model" );
					}
					$fields[$fieldName] = $mergeDef;
				}
			}
		}
		// Hook to allow extensions to produce search mapping fields
		$this->getHookRunner()->onSearchIndexFields( $fields, $this );
		return $fields;
	}

	/**
	 * Augment search results with extra data.
	 */
	public function augmentSearchResults( ISearchResultSet $resultSet ) {
		$setAugmentors = [];
		$rowAugmentors = [];
		$this->getHookRunner()->onSearchResultsAugment( $setAugmentors, $rowAugmentors );
		if ( !$setAugmentors && !$rowAugmentors ) {
			// We're done here
			return;
		}

		// Convert row augmentors to set augmentor
		foreach ( $rowAugmentors as $name => $row ) {
			if ( isset( $setAugmentors[$name] ) ) {
				throw new InvalidArgumentException( "Both row and set augmentors are defined for $name" );
			}
			$setAugmentors[$name] = new PerRowAugmentor( $row );
		}

		/**
		 * @var string $name
		 * @var ResultSetAugmentor $augmentor
		 */
		foreach ( $setAugmentors as $name => $augmentor ) {
			$data = $augmentor->augmentAll( $resultSet );
			if ( $data ) {
				$resultSet->setAugmentedData( $name, $data );
			}
		}
	}

	/**
	 * @since 1.35
	 * @internal
	 * @param HookContainer $hookContainer
	 */
	public function setHookContainer( HookContainer $hookContainer ) {
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Get a HookContainer, for running extension hooks or for hook metadata.
	 *
	 * @since 1.35
	 * @return HookContainer
	 */
	protected function getHookContainer(): HookContainer {
		if ( !$this->hookContainer ) {
			// This shouldn't be hit in core, but it is needed for CirrusSearch
			// which commonly creates a CirrusSearch object without cirrus being
			// configured in $wgSearchType/$wgSearchTypeAlternatives.
			$this->hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		}
		return $this->hookContainer;
	}

	/**
	 * Get a HookRunner for running core hooks.
	 *
	 * @internal This is for use by core only. Hook interfaces may be removed
	 *   without notice.
	 * @since 1.35
	 * @return HookRunner
	 */
	protected function getHookRunner(): HookRunner {
		if ( !$this->hookRunner ) {
			$this->hookRunner = new HookRunner( $this->getHookContainer() );
		}
		return $this->hookRunner;
	}

}

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

use MediaWiki\MediaWikiServices;

/**
 * Contain a class for special pages
 * @ingroup Search
 */
abstract class SearchEngine {
	/** @var string */
	public $prefix = '';

	/** @var int[]|null */
	public $namespaces = [ NS_MAIN ];

	/** @var int */
	protected $limit = 10;

	/** @var int */
	protected $offset = 0;

	/** @var array|string */
	protected $searchTerms = [];

	/** @var bool */
	protected $showSuggestion = true;
	private $sort = 'relevance';

	/** @var array Feature values */
	protected $features = [];

	/** @const string profile type for completionSearch */
	const COMPLETION_PROFILE_TYPE = 'completionSearchProfile';

	/** @const string profile type for query independent ranking features */
	const FT_QUERY_INDEP_PROFILE_TYPE = 'fulltextQueryIndepProfile';

	/** @const int flag for legalSearchChars: includes all chars allowed in a search query */
	const CHARS_ALL = 1;

	/** @const int flag for legalSearchChars: includes all chars allowed in a search term */
	const CHARS_NO_SYNTAX = 2;

	/**
	 * Perform a full text search query and return a result set.
	 * If full text searches are not supported or disabled, return null.
	 * STUB
	 *
	 * @param string $term Raw search term
	 * @return SearchResultSet|Status|null
	 */
	function searchText( $term ) {
		return null;
	}

	/**
	 * Perform a title search in the article archive.
	 * NOTE: these results still should be filtered by
	 * matching against PageArchive, permissions checks etc
	 * The results returned by this methods are only sugegstions and
	 * may not end up being shown to the user.
	 *
	 * @param string $term Raw search term
	 * @return Status<Title[]>
	 * @since 1.29
	 */
	function searchArchiveTitle( $term ) {
		return Status::newGood( [] );
	}

	/**
	 * Perform a title-only search query and return a result set.
	 * If title searches are not supported or disabled, return null.
	 * STUB
	 *
	 * @param string $term Raw search term
	 * @return SearchResultSet|null
	 */
	function searchTitle( $term ) {
		return null;
	}

	/**
	 * @since 1.18
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
		if ( isset ( $this->features[$feature] ) ) {
			return $this->features[$feature];
		}
		return null;
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
		global $wgContLang;

		// Some languages such as Chinese require word segmentation
		return $wgContLang->segmentByWord( $string );
	}

	/**
	 * Transform search term in cases when parts of the query came as different
	 * GET params (when supported), e.g. for prefix queries:
	 * search=test&prefix=Main_Page/Archive -> test prefix:Main Page/Archive
	 * @param string $term
	 * @return string
	 */
	public function transformSearchTerm( $term ) {
		return $term;
	}

	/**
	 * Get service class to finding near matches.
	 * @param Config $config Configuration to use for the matcher.
	 * @return SearchNearMatcher
	 */
	public function getNearMatcher( Config $config ) {
		global $wgContLang;
		return new SearchNearMatcher( $config, $wgContLang );
	}

	/**
	 * Get near matcher for default SearchEngine.
	 * @return SearchNearMatcher
	 */
	protected static function defaultNearMatcher() {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		return MediaWikiServices::getInstance()->newSearchEngine()->getNearMatcher( $config );
	}

	/**
	 * If an exact title match can be found, or a very slightly close match,
	 * return the title. If no match, returns NULL.
	 * @deprecated since 1.27; Use SearchEngine::getNearMatcher()
	 * @param string $searchterm
	 * @return Title
	 */
	public static function getNearMatch( $searchterm ) {
		return static::defaultNearMatcher()->getNearMatch( $searchterm );
	}

	/**
	 * Do a near match (see SearchEngine::getNearMatch) and wrap it into a
	 * SearchResultSet.
	 * @deprecated since 1.27; Use SearchEngine::getNearMatcher()
	 * @param string $searchterm
	 * @return SearchResultSet
	 */
	public static function getNearMatchResultSet( $searchterm ) {
		return static::defaultNearMatcher()->getNearMatchResultSet( $searchterm );
	}

	/**
	 * Get chars legal for search
	 * NOTE: usage as static is deprecated and preserved only as BC measure
	 * @param int $type type of search chars (see self::CHARS_ALL
	 * and self::CHARS_NO_SYNTAX). Defaults to CHARS_ALL
	 * @return string
	 */
	public static function legalSearchChars( $type = self::CHARS_ALL ) {
		return "A-Za-z_'.0-9\\x80-\\xFF\\-";
	}

	/**
	 * Set the maximum number of results to return
	 * and how many to skip before returning the first.
	 *
	 * @param int $limit
	 * @param int $offset
	 */
	function setLimitOffset( $limit, $offset = 0 ) {
		$this->limit = intval( $limit );
		$this->offset = intval( $offset );
	}

	/**
	 * Set which namespaces the search should include.
	 * Give an array of namespace index numbers.
	 *
	 * @param int[]|null $namespaces
	 */
	function setNamespaces( $namespaces ) {
		if ( $namespaces ) {
			// Filter namespaces to only keep valid ones
			$validNs = $this->searchableNamespaces();
			$namespaces = array_filter( $namespaces, function( $ns ) use( $validNs ) {
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
	function setShowSuggestion( $showSuggestion ) {
		$this->showSuggestion = $showSuggestion;
	}

	/**
	 * Get the valid sort directions.  All search engines support 'relevance' but others
	 * might support more. The default in all implementations should be 'relevance.'
	 *
	 * @since 1.25
	 * @return array(string) the valid sort directions for setSort
	 */
	public function getValidSorts() {
		return [ 'relevance' ];
	}

	/**
	 * Set the sort direction of the search results. Must be one returned by
	 * SearchEngine::getValidSorts()
	 *
	 * @since 1.25
	 * @throws InvalidArgumentException
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
	 * @param string $query
	 * @return string
	 */
	function replacePrefixes( $query ) {
		$queryAndNs = self::parseNamespacePrefixes( $query );
		if ( $queryAndNs === false ) {
			return $query;
		}
		$this->namespaces = $queryAndNs[1];
		return $queryAndNs[0];
	}

	/**
	 * Parse some common prefixes: all (search everything)
	 * or namespace names
	 *
	 * @param string $query
	 * @return false|array false if no namespace was extracted, an array
	 * with the parsed query at index 0 and an array of namespaces at index
	 * 1 (or null for all namespaces).
	 */
	public static function parseNamespacePrefixes( $query ) {
		global $wgContLang;

		$parsed = $query;
		if ( strpos( $query, ':' ) === false ) { // nothing to do
			return false;
		}
		$extractedNamespace = null;

		$allkeyword = wfMessage( 'searchall' )->inContentLanguage()->text() . ":";
		if ( strncmp( $query, $allkeyword, strlen( $allkeyword ) ) == 0 ) {
			$extractedNamespace = null;
			$parsed = substr( $query, strlen( $allkeyword ) );
		} elseif ( strpos( $query, ':' ) !== false ) {
			// TODO: should we unify with PrefixSearch::extractNamespace ?
			$prefix = str_replace( ' ', '_', substr( $query, 0, strpos( $query, ':' ) ) );
			$index = $wgContLang->getNsIndex( $prefix );
			if ( $index !== false ) {
				$extractedNamespace = [ $index ];
				$parsed = substr( $query, strlen( $prefix ) + 1 );
			} else {
				return false;
			}
		}

		if ( trim( $parsed ) == '' ) {
			$parsed = $query; // prefix was the whole query
		}

		return [ $parsed, $extractedNamespace ];
	}

	/**
	 * Find snippet highlight settings for all users
	 * @return array Contextlines, contextchars
	 */
	public static function userHighlightPrefs() {
		$contextlines = 2; // Hardcode this. Old defaults sucked. :)
		$contextchars = 75; // same as above.... :P
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
	function update( $id, $title, $text ) {
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
	function updateTitle( $id, $title ) {
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
	function delete( $id, $title ) {
		// no-op
	}

	/**
	 * Get OpenSearch suggestion template
	 *
	 * @deprecated since 1.25
	 * @return string
	 */
	public static function getOpenSearchTemplate() {
		wfDeprecated( __METHOD__, '1.25' );
		return ApiOpenSearch::getOpenSearchTemplate( 'application/x-suggestions+json' );
	}

	/**
	 * Get the raw text for updating the index from a content object
	 * Nicer search backends could possibly do something cooler than
	 * just returning raw text
	 *
	 * @todo This isn't ideal, we'd really like to have content-specific handling here
	 * @param Title $t Title we're indexing
	 * @param Content $c Content of the page to index
	 * @return string
	 */
	public function getTextFromContent( Title $t, Content $c = null ) {
		return $c ? $c->getTextForSearchIndex() : '';
	}

	/**
	 * If an implementation of SearchEngine handles all of its own text processing
	 * in getTextFromContent() and doesn't require SearchUpdate::updateText()'s
	 * rather silly handling, it should return true here instead.
	 *
	 * @return bool
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
		// Find a Title which is not an interwiki and is in NS_MAIN
		$title = Title::newFromText( $search );
		$ns = $this->namespaces;
		if ( $title && !$title->isExternal() ) {
			$ns = [ $title->getNamespace() ];
			$search = $title->getText();
			if ( $ns[0] == NS_MAIN ) {
				$ns = $this->namespaces; // no explicit prefix, use default namespaces
				Hooks::run( 'PrefixSearchExtractNamespace', [ &$ns, &$search ] );
			}
		} else {
			$title = Title::newFromText( $search . 'Dummy' );
			if ( $title && $title->getText() == 'Dummy'
					&& $title->getNamespace() != NS_MAIN
					&& !$title->isExternal() )
			{
				$ns = [ $title->getNamespace() ];
				$search = '';
			} else {
				Hooks::run( 'PrefixSearchExtractNamespace', [ &$ns, &$search ] );
			}
		}

		$ns = array_map( function( $space ) {
			return $space == NS_MEDIA ? NS_FILE : $space;
		}, $ns );

		$this->setNamespaces( $ns );
		return $search;
	}

	/**
	 * Perform a completion search.
	 * Does not resolve namespaces and does not check variants.
	 * Search engine implementations may want to override this function.
	 * @param string $search
	 * @return SearchSuggestionSet
	 */
	protected function completionSearchBackend( $search ) {
		$results = [];

		$search = trim( $search );

		if ( !in_array( NS_SPECIAL, $this->namespaces ) && // We do not run hook on Special: search
			 !Hooks::run( 'PrefixSearchBackend',
				[ $this->namespaces, $search, $this->limit, &$results, $this->offset ]
		) ) {
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
		return $this->processCompletionResults( $search, $this->completionSearchBackend( $search ) );
	}

	/**
	 * Perform a completion search with variants.
	 * @param string $search
	 * @return SearchSuggestionSet
	 */
	public function completionSearchWithVariants( $search ) {
		if ( trim( $search ) === '' ) {
			return SearchSuggestionSet::emptySuggestionSet(); // Return empty result
		}
		$search = $this->normalizeNamespaces( $search );

		$results = $this->completionSearchBackend( $search );
		$fallbackLimit = $this->limit - $results->getSize();
		if ( $fallbackLimit > 0 ) {
			global $wgContLang;

			$fallbackSearches = $wgContLang->autoConvertToAllVariants( $search );
			$fallbackSearches = array_diff( array_unique( $fallbackSearches ), [ $search ] );

			foreach ( $fallbackSearches as $fbs ) {
				$this->setLimitOffset( $fallbackLimit );
				$fallbackSearchResult = $this->completionSearch( $fbs );
				$results->appendAll( $fallbackSearchResult );
				$fallbackLimit -= count( $fallbackSearchResult );
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
		return $completionResults->map( function( SearchSuggestion $sugg ) {
			return $sugg->getSuggestedTitle();
		} );
	}

	/**
	 * Process completion search results.
	 * Resolves the titles and rescores.
	 * @param SearchSuggestionSet $suggestions
	 * @return SearchSuggestionSet
	 */
	protected function processCompletionResults( $search, SearchSuggestionSet $suggestions ) {
		$search = trim( $search );
		// preload the titles with LinkBatch
		$titles = $suggestions->map( function( SearchSuggestion $sugg ) {
			return $sugg->getSuggestedTitle();
		} );
		$lb = new LinkBatch( $titles );
		$lb->setCaller( __METHOD__ );
		$lb->execute();

		$results = $suggestions->map( function( SearchSuggestion $sugg ) {
			return $sugg->getSuggestedTitle()->getPrefixedText();
		} );

		if ( $this->offset === 0 ) {
			// Rescore results with an exact title match
			// NOTE: in some cases like cross-namespace redirects
			// (frequently used as shortcuts e.g. WP:WP on huwiki) some
			// backends like Cirrus will return no results. We should still
			// try an exact title match to workaround this limitation
			$rescorer = new SearchExactMatchRescorer();
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
	 * Make a list of searchable namespaces and their canonical names.
	 * @deprecated since 1.27; use SearchEngineConfig::searchableNamespaces()
	 * @return array
	 */
	public static function searchableNamespaces() {
		return MediaWikiServices::getInstance()->getSearchEngineConfig()->searchableNamespaces();
	}

	/**
	 * Extract default namespaces to search from the given user's
	 * settings, returning a list of index numbers.
	 * @deprecated since 1.27; use SearchEngineConfig::userNamespaces()
	 * @param user $user
	 * @return array
	 */
	public static function userNamespaces( $user ) {
		return MediaWikiServices::getInstance()->getSearchEngineConfig()->userNamespaces( $user );
	}

	/**
	 * An array of namespaces indexes to be searched by default
	 * @deprecated since 1.27; use SearchEngineConfig::defaultNamespaces()
	 * @return array
	 */
	public static function defaultNamespaces() {
		return MediaWikiServices::getInstance()->getSearchEngineConfig()->defaultNamespaces();
	}

	/**
	 * Get a list of namespace names useful for showing in tooltips
	 * and preferences
	 * @deprecated since 1.27; use SearchEngineConfig::namespacesAsText()
	 * @param array $namespaces
	 * @return array
	 */
	public static function namespacesAsText( $namespaces ) {
		return MediaWikiServices::getInstance()->getSearchEngineConfig()->namespacesAsText( $namespaces );
	}

	/**
	 * Load up the appropriate search engine class for the currently
	 * active database backend, and return a configured instance.
	 * @deprecated since 1.27; Use SearchEngineFactory::create
	 * @param string $type Type of search backend, if not the default
	 * @return SearchEngine
	 */
	public static function create( $type = null ) {
		return MediaWikiServices::getInstance()->getSearchEngineFactory()->create( $type );
	}

	/**
	 * Return the search engines we support. If only $wgSearchType
	 * is set, it'll be an array of just that one item.
	 * @deprecated since 1.27; use SearchEngineConfig::getSearchTypes()
	 * @return array
	 */
	public static function getSearchTypes() {
		return MediaWikiServices::getInstance()->getSearchEngineConfig()->getSearchTypes();
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
	 * @param string $profileType the type of profiles
	 * @param User|null $user the user requesting the list of profiles
	 * @return array|null the list of profiles or null if none available
	 */
	public function getProfiles( $profileType, User $user = null ) {
		return null;
	}

	/**
	 * Create a search field definition.
	 * Specific search engines should override this method to create search fields.
	 * @param string $name
	 * @param int    $type One of the types in SearchIndexField::INDEX_TYPE_*
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
		$models = ContentHandler::getContentModels();
		$fields = [];
		$seenHandlers = new SplObjectStorage();
		foreach ( $models as $model ) {
			try {
				$handler = ContentHandler::getForModelID( $model );
			}
			catch ( MWUnknownContentModelException $e ) {
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
		Hooks::run( 'SearchIndexFields', [ &$fields, $this ] );
		return $fields;
	}

	/**
	 * Augment search results with extra data.
	 *
	 * @param SearchResultSet $resultSet
	 */
	public function augmentSearchResults( SearchResultSet $resultSet ) {
		$setAugmentors = [];
		$rowAugmentors = [];
		Hooks::run( "SearchResultsAugment", [ &$setAugmentors, &$rowAugmentors ] );

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

		foreach ( $setAugmentors as $name => $augmentor ) {
			$data = $augmentor->augmentAll( $resultSet );
			if ( $data ) {
				$resultSet->setAugmentedData( $name, $data );
			}
		}
	}
}

/**
 * Dummy class to be used when non-supported Database engine is present.
 * @todo FIXME: Dummy class should probably try something at least mildly useful,
 * such as a LIKE search through titles.
 * @ingroup Search
 */
class SearchEngineDummy extends SearchEngine {
	// no-op
}

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

/**
 * Contain a class for special pages
 * @ingroup Search
 */
class SearchEngine {
	var $limit = 10;
	var $offset = 0;
	var $prefix = '';
	var $searchTerms = array();
	var $namespaces = array( NS_MAIN );
	protected $showSuggestion = true;

	/** @var Array Feature values */
	protected $features = array();

	/**
	 * Perform a full text search query and return a result set.
	 * If title searches are not supported or disabled, return null.
	 * STUB
	 *
	 * @param string $term raw search term
	 * @return SearchResultSet|Status|null
	 */
	function searchText( $term ) {
		return null;
	}

	/**
	 * Perform a title-only search query and return a result set.
	 * If title searches are not supported or disabled, return null.
	 * STUB
	 *
	 * @param string $term raw search term
	 * @return SearchResultSet|null
	 */
	function searchTitle( $term ) {
		return null;
	}

	/**
	 * @since 1.18
	 * @param $feature String
	 * @return Boolean
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
	 * @param $feature String
	 * @param $data Mixed
	 * @return bool
	 */
	public function setFeatureData( $feature, $data ) {
		$this->features[$feature] = $data;
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
	 * Transform search term in cases when parts of the query came as different GET params (when supported)
	 * e.g. for prefix queries: search=test&prefix=Main_Page/Archive -> test prefix:Main Page/Archive
	 */
	function transformSearchTerm( $term ) {
		return $term;
	}

	/**
	 * If an exact title match can be found, or a very slightly close match,
	 * return the title. If no match, returns NULL.
	 *
	 * @param $searchterm String
	 * @return Title
	 */
	public static function getNearMatch( $searchterm ) {
		$title = self::getNearMatchInternal( $searchterm );

		wfRunHooks( 'SearchGetNearMatchComplete', array( $searchterm, &$title ) );
		return $title;
	}

	/**
	 * Do a near match (see SearchEngine::getNearMatch) and wrap it into a
	 * SearchResultSet.
	 *
	 * @param $searchterm string
	 * @return SearchResultSet
	 */
	public static function getNearMatchResultSet( $searchterm ) {
		return new SearchNearMatchResultSet( self::getNearMatch( $searchterm ) );
	}

	/**
	 * Really find the title match.
	 * @return null|Title
	 */
	private static function getNearMatchInternal( $searchterm ) {
		global $wgContLang, $wgEnableSearchContributorsByIP;

		$allSearchTerms = array( $searchterm );

		if ( $wgContLang->hasVariants() ) {
			$allSearchTerms = array_merge( $allSearchTerms, $wgContLang->autoConvertToAllVariants( $searchterm ) );
		}

		$titleResult = null;
		if ( !wfRunHooks( 'SearchGetNearMatchBefore', array( $allSearchTerms, &$titleResult ) ) ) {
			return $titleResult;
		}

		foreach ( $allSearchTerms as $term ) {

			# Exact match? No need to look further.
			$title = Title::newFromText( $term );
			if ( is_null( $title ) ) {
				return null;
			}

			# Try files if searching in the Media: namespace
			if ( $title->getNamespace() == NS_MEDIA ) {
				$title = Title::makeTitle( NS_FILE, $title->getText() );
			}

			if ( $title->isSpecialPage() || $title->isExternal() || $title->exists() ) {
				return $title;
			}

			# See if it still otherwise has content is some sane sense
			$page = WikiPage::factory( $title );
			if ( $page->hasViewableContent() ) {
				return $title;
			}

			if ( !wfRunHooks( 'SearchAfterNoDirectMatch', array( $term, &$title ) ) ) {
				return $title;
			}

			# Now try all lower case (i.e. first letter capitalized)
			$title = Title::newFromText( $wgContLang->lc( $term ) );
			if ( $title && $title->exists() ) {
				return $title;
			}

			# Now try capitalized string
			$title = Title::newFromText( $wgContLang->ucwords( $term ) );
			if ( $title && $title->exists() ) {
				return $title;
			}

			# Now try all upper case
			$title = Title::newFromText( $wgContLang->uc( $term ) );
			if ( $title && $title->exists() ) {
				return $title;
			}

			# Now try Word-Caps-Breaking-At-Word-Breaks, for hyphenated names etc
			$title = Title::newFromText( $wgContLang->ucwordbreaks( $term ) );
			if ( $title && $title->exists() ) {
				return $title;
			}

			// Give hooks a chance at better match variants
			$title = null;
			if ( !wfRunHooks( 'SearchGetNearMatch', array( $term, &$title ) ) ) {
				return $title;
			}
		}

		$title = Title::newFromText( $searchterm );

		# Entering an IP address goes to the contributions page
		if ( $wgEnableSearchContributorsByIP ) {
			if ( ( $title->getNamespace() == NS_USER && User::isIP( $title->getText() ) )
				|| User::isIP( trim( $searchterm ) ) ) {
				return SpecialPage::getTitleFor( 'Contributions', $title->getDBkey() );
			}
		}

		# Entering a user goes to the user page whether it's there or not
		if ( $title->getNamespace() == NS_USER ) {
			return $title;
		}

		# Go to images that exist even if there's no local page.
		# There may have been a funny upload, or it may be on a shared
		# file repository such as Wikimedia Commons.
		if ( $title->getNamespace() == NS_FILE ) {
			$image = wfFindFile( $title );
			if ( $image ) {
				return $title;
			}
		}

		# MediaWiki namespace? Page may be "implied" if not customized.
		# Just return it, with caps forced as the message system likes it.
		if ( $title->getNamespace() == NS_MEDIAWIKI ) {
			return Title::makeTitle( NS_MEDIAWIKI, $wgContLang->ucfirst( $title->getText() ) );
		}

		# Quoted term? Try without the quotes...
		$matches = array();
		if ( preg_match( '/^"([^"]+)"$/', $searchterm, $matches ) ) {
			return SearchEngine::getNearMatch( $matches[1] );
		}

		return null;
	}

	public static function legalSearchChars() {
		return "A-Za-z_'.0-9\\x80-\\xFF\\-";
	}

	/**
	 * Set the maximum number of results to return
	 * and how many to skip before returning the first.
	 *
	 * @param $limit Integer
	 * @param $offset Integer
	 */
	function setLimitOffset( $limit, $offset = 0 ) {
		$this->limit = intval( $limit );
		$this->offset = intval( $offset );
	}

	/**
	 * Set which namespaces the search should include.
	 * Give an array of namespace index numbers.
	 *
	 * @param $namespaces Array
	 */
	function setNamespaces( $namespaces ) {
		$this->namespaces = $namespaces;
	}

	/**
	 * Set whether the searcher should try to build a suggestion.  Note: some searchers
	 * don't support building a suggestion in the first place and others don't respect
	 * this flag.
	 *
	 * @param boolean $showSuggestion should the searcher try to build suggestions
	 */
	function setShowSuggestion( $showSuggestion ) {
		$this->showSuggestion = $showSuggestion;
	}

	/**
	 * Parse some common prefixes: all (search everything)
	 * or namespace names
	 *
	 * @param $query String
	 * @return string
	 */
	function replacePrefixes( $query ) {
		global $wgContLang;

		$parsed = $query;
		if ( strpos( $query, ':' ) === false ) { // nothing to do
			wfRunHooks( 'SearchEngineReplacePrefixesComplete', array( $this, $query, &$parsed ) );
			return $parsed;
		}

		$allkeyword = wfMessage( 'searchall' )->inContentLanguage()->text() . ":";
		if ( strncmp( $query, $allkeyword, strlen( $allkeyword ) ) == 0 ) {
			$this->namespaces = null;
			$parsed = substr( $query, strlen( $allkeyword ) );
		} elseif ( strpos( $query, ':' ) !== false ) {
			$prefix = str_replace( ' ', '_', substr( $query, 0, strpos( $query, ':' ) ) );
			$index = $wgContLang->getNsIndex( $prefix );
			if ( $index !== false ) {
				$this->namespaces = array( $index );
				$parsed = substr( $query, strlen( $prefix ) + 1 );
			}
		}
		if ( trim( $parsed ) == '' ) {
			$parsed = $query; // prefix was the whole query
		}

		wfRunHooks( 'SearchEngineReplacePrefixesComplete', array( $this, $query, &$parsed ) );

		return $parsed;
	}

	/**
	 * Make a list of searchable namespaces and their canonical names.
	 * @return Array
	 */
	public static function searchableNamespaces() {
		global $wgContLang;
		$arr = array();
		foreach ( $wgContLang->getNamespaces() as $ns => $name ) {
			if ( $ns >= NS_MAIN ) {
				$arr[$ns] = $name;
			}
		}

		wfRunHooks( 'SearchableNamespaces', array( &$arr ) );
		return $arr;
	}

	/**
	 * Extract default namespaces to search from the given user's
	 * settings, returning a list of index numbers.
	 *
	 * @param $user User
	 * @return Array
	 */
	public static function userNamespaces( $user ) {
		global $wgSearchEverythingOnlyLoggedIn;

		$searchableNamespaces = SearchEngine::searchableNamespaces();

		// get search everything preference, that can be set to be read for logged-in users
		// it overrides other options
		if ( !$wgSearchEverythingOnlyLoggedIn || $user->isLoggedIn() ) {
			if ( $user->getOption( 'searcheverything' ) ) {
				return array_keys( $searchableNamespaces );
			}
		}

		$arr = array();
		foreach ( $searchableNamespaces as $ns => $name ) {
			if ( $user->getOption( 'searchNs' . $ns ) ) {
				$arr[] = $ns;
			}
		}

		return $arr;
	}

	/**
	 * Find snippet highlight settings for all users
	 *
	 * @return Array contextlines, contextchars
	 */
	public static function userHighlightPrefs() {
		$contextlines = 2; // Hardcode this. Old defaults sucked. :)
		$contextchars = 75; // same as above.... :P
		return array( $contextlines, $contextchars );
	}

	/**
	 * An array of namespaces indexes to be searched by default
	 *
	 * @return Array
	 */
	public static function defaultNamespaces() {
		global $wgNamespacesToBeSearchedDefault;

		return array_keys( $wgNamespacesToBeSearchedDefault, true );
	}

	/**
	 * Get a list of namespace names useful for showing in tooltips
	 * and preferences
	 *
	 * @param $namespaces Array
	 * @return array
	 */
	public static function namespacesAsText( $namespaces ) {
		global $wgContLang;

		$formatted = array_map( array( $wgContLang, 'getFormattedNsText' ), $namespaces );
		foreach ( $formatted as $key => $ns ) {
			if ( empty( $ns ) ) {
				$formatted[$key] = wfMessage( 'blanknamespace' )->text();
			}
		}
		return $formatted;
	}

	/**
	 * Return the help namespaces to be shown on Special:Search
	 *
	 * @return Array
	 */
	public static function helpNamespaces() {
		global $wgNamespacesToBeSearchedHelp;

		return array_keys( $wgNamespacesToBeSearchedHelp, true );
	}

	/**
	 * Return a 'cleaned up' search string
	 *
	 * @param $text String
	 * @return String
	 */
	function filter( $text ) {
		$lc = $this->legalSearchChars();
		return trim( preg_replace( "/[^{$lc}]/", " ", $text ) );
	}

	/**
	 * Load up the appropriate search engine class for the currently
	 * active database backend, and return a configured instance.
	 *
	 * @param String $type Type of search backend, if not the default
	 * @return SearchEngine
	 */
	public static function create( $type = null ) {
		global $wgSearchType;
		$dbr = null;

		$alternatives = self::getSearchTypes();

		if ( $type && in_array( $type, $alternatives ) ) {
			$class = $type;
		} elseif ( $wgSearchType !== null ) {
			$class = $wgSearchType;
		} else {
			$dbr = wfGetDB( DB_SLAVE );
			$class = $dbr->getSearchEngine();
		}

		$search = new $class( $dbr );
		return $search;
	}

	/**
	 * Return the search engines we support. If only $wgSearchType
	 * is set, it'll be an array of just that one item.
	 *
	 * @return array
	 */
	public static function getSearchTypes() {
		global $wgSearchType, $wgSearchTypeAlternatives;

		$alternatives = $wgSearchTypeAlternatives ?: array();
		array_unshift( $alternatives, $wgSearchType );

		return $alternatives;
	}

	/**
	 * Create or update the search index record for the given page.
	 * Title and text should be pre-processed.
	 * STUB
	 *
	 * @param $id Integer
	 * @param $title String
	 * @param $text String
	 */
	function update( $id, $title, $text ) {
		// no-op
	}

	/**
	 * Update a search index record's title only.
	 * Title should be pre-processed.
	 * STUB
	 *
	 * @param $id Integer
	 * @param $title String
	 */
	function updateTitle( $id, $title ) {
		// no-op
	}

	/**
	 * Delete an indexed page
	 * Title should be pre-processed.
	 * STUB
	 *
	 * @param Integer $id Page id that was deleted
	 * @param String $title Title of page that was deleted
	 */
	function delete( $id, $title ) {
		// no-op
	}

	/**
	 * Get OpenSearch suggestion template
	 *
	 * @return String
	 */
	public static function getOpenSearchTemplate() {
		global $wgOpenSearchTemplate, $wgCanonicalServer;
		if ( $wgOpenSearchTemplate ) {
			return $wgOpenSearchTemplate;
		} else {
			$ns = implode( '|', SearchEngine::defaultNamespaces() );
			if ( !$ns ) {
				$ns = "0";
			}
			return $wgCanonicalServer . wfScript( 'api' ) . '?action=opensearch&search={searchTerms}&namespace=' . $ns;
		}
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
}

/**
 * @ingroup Search
 */
class SearchResultTooMany {
	# # Some search engines may bail out if too many matches are found
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

<?php
/**
 * Contain a class for special pages
 * @addtogroup Search
 */
class SearchEngine {
	var $limit = 10;
	var $offset = 0;
	var $searchTerms = array();
	var $namespaces = array( NS_MAIN );
	var $showRedirects = false;

	/**
	 * Perform a full text search query and return a result set.
	 * If title searches are not supported or disabled, return null.
	 *
	 * @param string $term - Raw search term
	 * @return SearchResultSet
	 * @access public
	 * @abstract
	 */
	function searchText( $term ) {
		return null;
	}

	/**
	 * Perform a title-only search query and return a result set.
	 * If title searches are not supported or disabled, return null.
	 *
	 * @param string $term - Raw search term
	 * @return SearchResultSet
	 * @access public
	 * @abstract
	 */
	function searchTitle( $term ) {
		return null;
	}

	/**
	 * If an exact title match can be find, or a very slightly close match,
	 * return the title. If no match, returns NULL.
	 *
	 * @static
	 * @param string $term
	 * @return Title
	 * @private
	 */
	function getNearMatch( $searchterm ) {
		global $wgContLang;

		$allSearchTerms = array($searchterm);

		if($wgContLang->hasVariants()){
			$allSearchTerms = array_merge($allSearchTerms,$wgContLang->convertLinkToAllVariants($searchterm));
		}

		foreach($allSearchTerms as $term){

			# Exact match? No need to look further.
			$title = Title::newFromText( $term );
			if (is_null($title))
				return NULL;

			if ( $title->getNamespace() == NS_SPECIAL || $title->exists() ) {
				return $title;
			}

			# Now try all lower case (i.e. first letter capitalized)
			#
			$title = Title::newFromText( $wgContLang->lc( $term ) );
			if ( $title->exists() ) {
				return $title;
			}

			# Now try capitalized string
			#
			$title = Title::newFromText( $wgContLang->ucwords( $term ) );
			if ( $title->exists() ) {
				return $title;
			}

			# Now try all upper case
			#
			$title = Title::newFromText( $wgContLang->uc( $term ) );
			if ( $title->exists() ) {
				return $title;
			}

			# Now try Word-Caps-Breaking-At-Word-Breaks, for hyphenated names etc
			$title = Title::newFromText( $wgContLang->ucwordbreaks($term) );
			if ( $title->exists() ) {
				return $title;
			}

			global $wgCapitalLinks, $wgContLang;
			if( !$wgCapitalLinks ) {
				// Catch differs-by-first-letter-case-only
				$title = Title::newFromText( $wgContLang->ucfirst( $term ) );
				if ( $title->exists() ) {
					return $title;
				}
				$title = Title::newFromText( $wgContLang->lcfirst( $term ) );
				if ( $title->exists() ) {
					return $title;
				}
			}
		}

		$title = Title::newFromText( $searchterm );

		# Entering an IP address goes to the contributions page
		if ( ( $title->getNamespace() == NS_USER && User::isIP($title->getText() ) )
			|| User::isIP( trim( $searchterm ) ) ) {
			return SpecialPage::getTitleFor( 'Contributions', $title->getDbkey() );
		}


		# Entering a user goes to the user page whether it's there or not
		if ( $title->getNamespace() == NS_USER ) {
			return $title;
		}
		
		# Go to images that exist even if there's no local page.
		# There may have been a funny upload, or it may be on a shared
		# file repository such as Wikimedia Commons.
		if( $title->getNamespace() == NS_IMAGE ) {
			$image = new Image( $title );
			if( $image->exists() ) {
				return $title;
			}
		}

		# MediaWiki namespace? Page may be "implied" if not customized.
		# Just return it, with caps forced as the message system likes it.
		if( $title->getNamespace() == NS_MEDIAWIKI ) {
			return Title::makeTitle( NS_MEDIAWIKI, $wgContLang->ucfirst( $title->getText() ) );
		}

		# Quoted term? Try without the quotes...
		$matches = array();
		if( preg_match( '/^"([^"]+)"$/', $searchterm, $matches ) ) {
			return SearchEngine::getNearMatch( $matches[1] );
		}
		
		return NULL;
	}

	public static function legalSearchChars() {
		return "A-Za-z_'0-9\\x80-\\xFF\\-";
	}

	/**
	 * Set the maximum number of results to return
	 * and how many to skip before returning the first.
	 *
	 * @param int $limit
	 * @param int $offset
	 * @access public
	 */
	function setLimitOffset( $limit, $offset = 0 ) {
		$this->limit = intval( $limit );
		$this->offset = intval( $offset );
	}

	/**
	 * Set which namespaces the search should include.
	 * Give an array of namespace index numbers.
	 *
	 * @param array $namespaces
	 * @access public
	 */
	function setNamespaces( $namespaces ) {
		$this->namespaces = $namespaces;
	}

	/**
	 * Make a list of searchable namespaces and their canonical names.
	 * @return array
	 * @access public
	 */
	function searchableNamespaces() {
		global $wgContLang;
		$arr = array();
		foreach( $wgContLang->getNamespaces() as $ns => $name ) {
			if( $ns >= NS_MAIN ) {
				$arr[$ns] = $name;
			}
		}
		return $arr;
	}

	/**
	 * Return a 'cleaned up' search string
	 *
	 * @return string
	 * @access public
	 */
	function filter( $text ) {
		$lc = $this->legalSearchChars();
		return trim( preg_replace( "/[^{$lc}]/", " ", $text ) );
	}
	/**
	 * Load up the appropriate search engine class for the currently
	 * active database backend, and return a configured instance.
	 *
	 * @return SearchEngine
	 */
	public static function create() {
		global $wgDBtype, $wgSearchType;
		if( $wgSearchType ) {
			$class = $wgSearchType;
		} elseif( $wgDBtype == 'mysql' ) {
			$class = 'SearchMySQL4';
		} else if ( $wgDBtype == 'postgres' ) {
			$class = 'SearchPostgres';
		} else if ( $wgDBtype == 'oracle' ) {
			$class = 'SearchOracle';
		} else {
			$class = 'SearchEngineDummy';
		}
		$search = new $class( wfGetDB( DB_SLAVE ) );
		$search->setLimitOffset(0,0);
		return $search;
	}

	/**
	 * Create or update the search index record for the given page.
	 * Title and text should be pre-processed.
	 *
	 * @param int $id
	 * @param string $title
	 * @param string $text
	 * @abstract
	 */
	function update( $id, $title, $text ) {
		// no-op
	}

	/**
	 * Update a search index record's title only.
	 * Title should be pre-processed.
	 *
	 * @param int $id
	 * @param string $title
	 * @abstract
	 */
    function updateTitle( $id, $title ) {
		// no-op
    }
}


/**
 * @addtogroup Search
 */
class SearchResultSet {
	/**
	 * Fetch an array of regular expression fragments for matching
	 * the search terms as parsed by this engine in a text extract.
	 *
	 * @return array
	 * @access public
	 * @abstract
	 */
	function termMatches() {
		return array();
	}

	function numRows() {
		return 0;
	}

	/**
	 * Return true if results are included in this result set.
	 * @return bool
	 * @abstract
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
	 * @return int
	 * @access public
	 */
	function getTotalHits() {
		return null;
	}

	/**
	 * Some search modes return a suggested alternate term if there are
	 * no exact hits. Returns true if there is one on this set.
	 *
	 * @return bool
	 * @access public
	 */
	function hasSuggestion() {
		return false;
	}

	/**
	 * Some search modes return a suggested alternate term if there are
	 * no exact hits. Check hasSuggestion() first.
	 *
	 * @return string
	 * @access public
	 */
	function getSuggestion() {
		return '';
	}

	/**
	 * Fetches next search result, or false.
	 * @return SearchResult
	 * @access public
	 * @abstract
	 */
	function next() {
		return false;
	}
}


/**
 * @addtogroup Search
 */
class SearchResult {
	function SearchResult( $row ) {
		$this->mTitle = Title::makeTitle( $row->page_namespace, $row->page_title );
	}

	/**
	 * @return Title
	 * @access public
	 */
	function getTitle() {
		return $this->mTitle;
	}

	/**
	 * @return double or null if not supported
	 */
	function getScore() {
		return null;
	}
}

/**
 * @addtogroup Search
 */
class SearchEngineDummy {
	function search( $term ) {
		return null;
	}
	function setLimitOffset($l, $o) {}
	function legalSearchChars() {}
	function update() {}
	function setnamespaces() {}
	function searchtitle() {}
	function searchtext() {}
}
?>

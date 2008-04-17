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
	 * @param string $term
	 * @return Title
	 */
	public static function getNearMatch( $searchterm ) {
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

			// Give hooks a chance at better match variants
			$title = null;
			if( !wfRunHooks( 'SearchGetNearMatch', array( $term, &$title ) ) ) {
				return $title;
			}
		}

		$title = Title::newFromText( $searchterm );

		# Entering an IP address goes to the contributions page
		if ( ( $title->getNamespace() == NS_USER && User::isIP($title->getText() ) )
			|| User::isIP( trim( $searchterm ) ) ) {
			return SpecialPage::getTitleFor( 'Contributions', $title->getDBkey() );
		}


		# Entering a user goes to the user page whether it's there or not
		if ( $title->getNamespace() == NS_USER ) {
			return $title;
		}

		# Go to images that exist even if there's no local page.
		# There may have been a funny upload, or it may be on a shared
		# file repository such as Wikimedia Commons.
		if( $title->getNamespace() == NS_IMAGE ) {
			$image = wfFindFile( $title );
			if( $image ) {
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
	 * Parse some common prefixes: all (search everything)
	 * or namespace names
	 *
	 * @param string $query
	 */
	function replacePrefixes( $query ){
		global $wgContLang;

		if( strpos($query,':') === false )
			return $query; // nothing to do

		$parsed = $query;
		$allkeyword = wfMsgForContent('searchall').":";
		if( strncmp($query, $allkeyword, strlen($allkeyword)) == 0 ){
			$this->namespaces = null;
			$parsed = substr($query,strlen($allkeyword));
		} else if( strpos($query,':') !== false ) {
			$prefix = substr($query,0,strpos($query,':'));
			$index = $wgContLang->getNsIndex($prefix);
			if($index !== false){
				$this->namespaces = array($index);
				$parsed = substr($query,strlen($prefix)+1);
			}
		}
		if(trim($parsed) == '')
			return $query; // prefix was the whole query

		return $parsed;
	}

	/**
	 * Make a list of searchable namespaces and their canonical names.
	 * @return array
	 */
	public static function searchableNamespaces() {
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
	 * Extract default namespaces to search from the given user's
	 * settings, returning a list of index numbers.
	 *
	 * @param User $user
	 * @return array
	 * @static 
	 */
	public static function userNamespaces( &$user ) {
		$arr = array();
		foreach( SearchEngine::searchableNamespaces() as $ns => $name ) {
			if( $user->getOption( 'searchNs' . $ns ) ) {
				$arr[] = $ns;
			}
		}
		return $arr;
	}
	
	/**
	 * Find snippet highlight settings for a given user
	 *
	 * @param User $user
	 * @return array contextlines, contextchars 
	 * @static
	 */
	public static function userHighlightPrefs( &$user ){
		//$contextlines = $user->getOption( 'contextlines',  5 );
		$contextlines = 2; // Hardcode this. Old defaults sucked. :)
		$contextchars = $user->getOption( 'contextchars', 50 );
		return array($contextlines, $contextchars);
	}
	
	/**
	 * An array of namespaces indexes to be searched by default
	 * 
	 * @return array 
	 * @static
	 */
	public static function defaultNamespaces(){
		global $wgNamespacesToBeSearchedDefault;
		
		return array_keys($wgNamespacesToBeSearchedDefault, true);
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
			$class = 'SearchMySQL';
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
	
	/**
	 * Get OpenSearch suggestion template
	 * 
	 * @return string
	 * @static 
	 */
	public static function getOpenSearchTemplate() {
		global $wgOpenSearchTemplate, $wgServer, $wgScriptPath;
		if($wgOpenSearchTemplate)		
			return $wgOpenSearchTemplate;
		else{ 
			$ns = implode(',',SearchEngine::defaultNamespaces());
			if(!$ns) $ns = "0";
			return $wgServer . $wgScriptPath . '/api.php?action=opensearch&search={searchTerms}&namespace='.$ns;
		}
	}
	
	/**
	 * Get internal MediaWiki Suggest template 
	 * 
	 * @return string
	 * @static
	 */
	public static function getMWSuggestTemplate() {
		global $wgMWSuggestTemplate, $wgServer, $wgScriptPath;
		if($wgMWSuggestTemplate)		
			return $wgMWSuggestTemplate;
		else 
			return $wgServer . $wgScriptPath . '/api.php?action=opensearch&search={searchTerms}&namespace={namespaces}';
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
	 * @return string suggested query, null if none
	 */
	function getSuggestionQuery(){
		return null;
	}

	/**
	 * @return string highlighted suggested query, '' if none
	 */
	function getSuggestionSnippet(){
		return '';
	}
	
	/**
	 * Return information about how and from where the results were fetched,
	 * should be useful for diagnostics and debugging 
	 *
	 * @return string
	 */
	function getInfo() {
		return null;
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
	 * @return boolean
	 */
	function hasInterwikiResults() {
		return $this->getInterwikiResults() != null;
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

	/**
	 * Frees the result set, if applicable.
	 * @ access public
	 */
	function free() {
		// ...
	}
}


/**
 * @addtogroup Search
 */
class SearchResultTooMany {
	## Some search engines may bail out if too many matches are found
}


/**
 * @addtogroup Search
 */
class SearchResult {

	function SearchResult( $row ) {
		$this->mTitle = Title::makeTitle( $row->page_namespace, $row->page_title );
		if( !is_null($this->mTitle) )
			$this->mRevision = Revision::newFromTitle( $this->mTitle );
	}
	
	/**
	 * Check if this is result points to an invalid title
	 *
	 * @return boolean
	 * @access public
	 */
	function isBrokenTitle(){
		if( is_null($this->mTitle) )
			return true;
		return false;
	}
	
	/**
	 * Check if target page is missing, happens when index is out of date
	 * 
	 * @return boolean
	 * @access public
	 */
	function isMissingRevision(){
		if( !$this->mRevision )
			return true;
		return false;
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

	/**
	 * Lazy initialization of article text from DB
	 */
	protected function initText(){
		if( !isset($this->mText) ){
			$this->mText = $this->mRevision->getText();
		}
	}

	/**
	 * @param array $terms terms to highlight
	 * @return string highlighted text snippet, null (and not '') if not supported 
	 */
	function getTextSnippet($terms){
		global $wgUser;
		$this->initText();
		list($contextlines,$contextchars) = SearchEngine::userHighlightPrefs($wgUser);
		return $this->extractText( $this->mText, $terms, $contextlines, $contextchars); 		
	}
	
	/**
	 * Default implementation of snippet extraction
	 *
	 * @param string $text
	 * @param array $terms
	 * @param int $contextlines
	 * @param int $contextchars
	 * @return string
	 */
	protected function extractText( $text, $terms, $contextlines, $contextchars ) {
		global $wgLang, $wgContLang;
		$fname = __METHOD__;
	
		$lines = explode( "\n", $text );
		
		$terms = implode( '|', $terms );
		$terms = str_replace( '/', "\\/", $terms);
		$max = intval( $contextchars ) + 1;
		$pat1 = "/(.*)($terms)(.{0,$max})/i";

		$lineno = 0;

		$extract = "";
		wfProfileIn( "$fname-extract" );
		foreach ( $lines as $line ) {
			if ( 0 == $contextlines ) {
				break;
			}
			++$lineno;
			$m = array();
			if ( ! preg_match( $pat1, $line, $m ) ) {
				continue;
			}
			--$contextlines;
			$pre = $wgContLang->truncate( $m[1], -$contextchars, ' ... ' );

			if ( count( $m ) < 3 ) {
				$post = '';
			} else {
				$post = $wgContLang->truncate( $m[3], $contextchars, ' ... ' );
			}

			$found = $m[2];

			$line = htmlspecialchars( $pre . $found . $post );
			$pat2 = '/(' . $terms . ")/i";
			$line = preg_replace( $pat2,
			  "<span class='searchmatch'>\\1</span>", $line );

			$extract .= "${line}\n";
		}
		wfProfileOut( "$fname-extract" );
		
		return $extract;
	}
	
	/**
	 * @param array $terms terms to highlight
	 * @return string highlighted title, '' if not supported
	 */
	function getTitleSnippet($terms){
		return '';
	}

	/**
	 * @param array $terms terms to highlight
	 * @return string highlighted redirect name (redirect to this page), '' if none or not supported
	 */
	function getRedirectSnippet($terms){
		return '';
	}

	/**
	 * @return Title object for the redirect to this page, null if none or not supported
	 */
	function getRedirectTitle(){
		return null;
	}

	/**
	 * @return string highlighted relevant section name, null if none or not supported
	 */
	function getSectionSnippet(){
		return '';
	}

	/**
	 * @return Title object (pagename+fragment) for the section, null if none or not supported
	 */
	function getSectionTitle(){
		return null;
	}

	/**
	 * @return string timestamp
	 */
	function getTimestamp(){
		return $this->mRevision->getTimestamp();
	}

	/**
	 * @return int number of words
	 */
	function getWordCount(){
		$this->initText();
		return str_word_count( $this->mText );
	}

	/**
	 * @return int size in bytes
	 */
	function getByteSize(){
		$this->initText();
		return strlen( $this->mText );
	}
	
	/**
	 * @return boolean if hit has related articles
	 */
	function hasRelated(){
		return false;
	}
	
	/**
	 * @return interwiki prefix of the title (return iw even if title is broken)
	 */
	function getInterwikiPrefix(){
		return '';
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

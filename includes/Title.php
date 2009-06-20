<?php
/**
 * See title.txt
 * @file
 */

if ( !class_exists( 'UtfNormal' ) ) {
	require_once( dirname(__FILE__) . '/normal/UtfNormal.php' );
}

define ( 'GAID_FOR_UPDATE', 1 );


/**
 * Constants for pr_cascade bitfield
 */
define( 'CASCADE', 1 );

/**
 * Represents a title within MediaWiki.
 * Optionally may contain an interwiki designation or namespace.
 * @note This class can fetch various kinds of data from the database;
 *       however, it does so inefficiently.
 */
class Title {
	/** @name Static cache variables */
	//@{
	static private $titleCache=array();
	static private $interwikiCache=array();
	//@}

	/**
	 * Title::newFromText maintains a cache to avoid expensive re-normalization of
	 * commonly used titles. On a batch operation this can become a memory leak
	 * if not bounded. After hitting this many titles reset the cache.
	 */
	const CACHE_MAX = 1000;


	/**
	 * @name Private member variables
	 * Please use the accessor functions instead.
	 * @private
	 */
	//@{

	var $mTextform = '';           	  ///< Text form (spaces not underscores) of the main part
	var $mUrlform = '';            	  ///< URL-encoded form of the main part
	var $mDbkeyform = '';          	  ///< Main part with underscores
	var $mUserCaseDBKey;              ///< DB key with the initial letter in the case specified by the user
	var $mNamespace = NS_MAIN;        ///< Namespace index, i.e. one of the NS_xxxx constants
	var $mInterwiki = '';          	  ///< Interwiki prefix (or null string)
	var $mFragment;           	  ///< Title fragment (i.e. the bit after the #)
	var $mArticleID = -1;             ///< Article ID, fetched from the link cache on demand
	var $mLatestID = false;           ///< ID of most recent revision
	var $mRestrictions = array();     ///< Array of groups allowed to edit this article
	var $mOldRestrictions = false;
	var $mCascadeRestriction;	  ///< Cascade restrictions on this page to included templates and images?
	var $mRestrictionsExpiry = array();	  ///< When do the restrictions on this page expire?
	var $mHasCascadingRestrictions;	  ///< Are cascading restrictions in effect on this page?
	var $mCascadeSources;  ///< Where are the cascading restrictions coming from on this page?
	var $mRestrictionsLoaded = false; ///< Boolean for initialisation on demand
	var $mPrefixedText;       	  ///< Text form including namespace/interwiki, initialised on demand
	# Don't change the following default, NS_MAIN is hardcoded in several
	# places.  See bug 696.
	var $mDefaultNamespace = NS_MAIN; ///< Namespace index when there is no namespace
	                    		  # Zero except in {{transclusion}} tags
	var $mWatched = null;      	  ///< Is $wgUser watching this page? null if unfilled, accessed through userIsWatching()
	var $mLength = -1;                ///< The page length, 0 for special pages
	var $mRedirect = null;            ///< Is the article at this title a redirect?
	var $mNotificationTimestamp = array(); ///< Associative array of user ID -> timestamp/false
	var $mBacklinkCache = null; ///< Cache of links to this title
	//@}


	/**
	 * Constructor
	 * @private
	 */
	/* private */ function __construct() {}

	/**
	 * Create a new Title from a prefixed DB key
	 * @param $key \type{\string} The database key, which has underscores
	 *	instead of spaces, possibly including namespace and
	 *	interwiki prefixes
	 * @return \type{Title} the new object, or NULL on an error
	 */
	public static function newFromDBkey( $key ) {
		$t = new Title();
		$t->mDbkeyform = $key;
		if( $t->secureAndSplit() )
			return $t;
		else
			return NULL;
	}

	/**
	 * Create a new Title from text, such as what one would find in a link. De-
	 * codes any HTML entities in the text.
	 *
	 * @param $text             string  The link text; spaces, prefixes, and an
	 *   initial ':' indicating the main namespace are accepted.
	 * @param $defaultNamespace int     The namespace to use if none is speci-
	 *   fied by a prefix.  If you want to force a specific namespace even if
	 *   $text might begin with a namespace prefix, use makeTitle() or
	 *   makeTitleSafe().
	 * @return Title  The new object, or null on an error.
	 */
	public static function newFromText( $text, $defaultNamespace = NS_MAIN ) {
		if( is_object( $text ) ) {
			throw new MWException( 'Title::newFromText given an object' );
		}

		/**
		 * Wiki pages often contain multiple links to the same page.
		 * Title normalization and parsing can become expensive on
		 * pages with many links, so we can save a little time by
		 * caching them.
		 *
		 * In theory these are value objects and won't get changed...
		 */
		if( $defaultNamespace == NS_MAIN && isset( Title::$titleCache[$text] ) ) {
			return Title::$titleCache[$text];
		}

		/**
		 * Convert things like &eacute; &#257; or &#x3017; into real text...
		 */
		$filteredText = Sanitizer::decodeCharReferences( $text );

		$t = new Title();
		$t->mDbkeyform = str_replace( ' ', '_', $filteredText );
		$t->mDefaultNamespace = $defaultNamespace;

		static $cachedcount = 0 ;
		if( $t->secureAndSplit() ) {
			if( $defaultNamespace == NS_MAIN ) {
				if( $cachedcount >= self::CACHE_MAX ) {
					# Avoid memory leaks on mass operations...
					Title::$titleCache = array();
					$cachedcount=0;
				}
				$cachedcount++;
				Title::$titleCache[$text] =& $t;
			}
			return $t;
		} else {
			$ret = NULL;
			return $ret;
		}
	}

	/**
	 * THIS IS NOT THE FUNCTION YOU WANT. Use Title::newFromText().
	 *
	 * Example of wrong and broken code:
	 * $title = Title::newFromURL( $wgRequest->getVal( 'title' ) );
	 *
	 * Example of right code:
	 * $title = Title::newFromText( $wgRequest->getVal( 'title' ) );
	 *
	 * Create a new Title from URL-encoded text. Ensures that
	 * the given title's length does not exceed the maximum.
	 * @param $url \type{\string} the title, as might be taken from a URL
	 * @return \type{Title} the new object, or NULL on an error
	 */
	public static function newFromURL( $url ) {
		global $wgLegalTitleChars;
		$t = new Title();

		# For compatibility with old buggy URLs. "+" is usually not valid in titles,
		# but some URLs used it as a space replacement and they still come
		# from some external search tools.
		if ( strpos( $wgLegalTitleChars, '+' ) === false ) {
			$url = str_replace( '+', ' ', $url );
		}

		$t->mDbkeyform = str_replace( ' ', '_', $url );
		if( $t->secureAndSplit() ) {
			return $t;
		} else {
			return NULL;
		}
	}

	/**
	 * Create a new Title from an article ID
	 *
	 * @param $id \type{\int} the page_id corresponding to the Title to create
	 * @param $flags \type{\int} use GAID_FOR_UPDATE to use master
	 * @return \type{Title} the new object, or NULL on an error
	 */
	public static function newFromID( $id, $flags = 0 ) {
		$db = ($flags & GAID_FOR_UPDATE) ? wfGetDB( DB_MASTER ) : wfGetDB( DB_SLAVE );
		$row = $db->selectRow( 'page', '*', array( 'page_id' => $id ), __METHOD__ );
		if( $row !== false ) {
			$title = Title::newFromRow( $row );
		} else {
			$title = NULL;
		}
		return $title;
	}

	/**
	 * Make an array of titles from an array of IDs
	 * @param $ids \type{\arrayof{\int}} Array of IDs
	 * @return \type{\arrayof{Title}} Array of Titles
	 */
	public static function newFromIDs( $ids ) {
		if ( !count( $ids ) ) {
			return array();
		}
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'page', array( 'page_namespace', 'page_title' ),
			'page_id IN (' . $dbr->makeList( $ids ) . ')', __METHOD__ );

		$titles = array();
		foreach( $res as $row ) {
			$titles[] = Title::makeTitle( $row->page_namespace, $row->page_title );
		}
		return $titles;
	}

	/**
	 * Make a Title object from a DB row
	 * @param $row \type{Row} (needs at least page_title,page_namespace)
	 * @return \type{Title} corresponding Title
	 */
	public static function newFromRow( $row ) {
		$t = self::makeTitle( $row->page_namespace, $row->page_title );

		$t->mArticleID = isset($row->page_id) ? intval($row->page_id) : -1;
		$t->mLength = isset($row->page_len) ? intval($row->page_len) : -1;
		$t->mRedirect = isset($row->page_is_redirect) ? (bool)$row->page_is_redirect : NULL;
		$t->mLatestID = isset($row->page_latest) ? $row->page_latest : false;

		return $t;
	}

	/**
	 * Create a new Title from a namespace index and a DB key.
	 * It's assumed that $ns and $title are *valid*, for instance when
	 * they came directly from the database or a special page name.
	 * For convenience, spaces are converted to underscores so that
	 * eg user_text fields can be used directly.
	 *
	 * @param $ns \type{\int} the namespace of the article
	 * @param $title \type{\string} the unprefixed database key form
	 * @param $fragment \type{\string} The link fragment (after the "#")
	 * @return \type{Title} the new object
	 */
	public static function &makeTitle( $ns, $title, $fragment = '' ) {
		$t = new Title();
		$t->mInterwiki = '';
		$t->mFragment = $fragment;
		$t->mNamespace = $ns = intval( $ns );
		$t->mDbkeyform = str_replace( ' ', '_', $title );
		$t->mArticleID = ( $ns >= 0 ) ? -1 : 0;
		$t->mUrlform = wfUrlencode( $t->mDbkeyform );
		$t->mTextform = str_replace( '_', ' ', $title );
		return $t;
	}

	/**
	 * Create a new Title from a namespace index and a DB key.
	 * The parameters will be checked for validity, which is a bit slower
	 * than makeTitle() but safer for user-provided data.
	 *
	 * @param $ns \type{\int} the namespace of the article
	 * @param $title \type{\string} the database key form
	 * @param $fragment \type{\string} The link fragment (after the "#")
	 * @return \type{Title} the new object, or NULL on an error
	 */
	public static function makeTitleSafe( $ns, $title, $fragment = '' ) {
		$t = new Title();
		$t->mDbkeyform = Title::makeName( $ns, $title, $fragment );
		if( $t->secureAndSplit() ) {
			return $t;
		} else {
			return NULL;
		}
 	}

	/**
	 * Create a new Title for the Main Page
	 * @return \type{Title} the new object
	 */
	public static function newMainPage() {
		$title = Title::newFromText( wfMsgForContent( 'mainpage' ) );
		// Don't give fatal errors if the message is broken
		if ( !$title ) {
			$title = Title::newFromText( 'Main Page' );
		}
		return $title;
	}

	/**
	 * Extract a redirect destination from a string and return the
	 * Title, or null if the text doesn't contain a valid redirect
	 * This will only return the very next target, useful for
	 * the redirect table and other checks that don't need full recursion
	 *
	 * @param $text \type{\string} Text with possible redirect
	 * @return \type{Title} The corresponding Title
	 */
	public static function newFromRedirect( $text ) {
		return self::newFromRedirectInternal( $text );
	}
	
	/**
	 * Extract a redirect destination from a string and return the
	 * Title, or null if the text doesn't contain a valid redirect
	 * This will recurse down $wgMaxRedirects times or until a non-redirect target is hit
	 * in order to provide (hopefully) the Title of the final destination instead of another redirect
	 *
	 * @param $text \type{\string} Text with possible redirect
	 * @return \type{Title} The corresponding Title
	 */
	public static function newFromRedirectRecurse( $text ) {
		$titles = self::newFromRedirectArray( $text );
		return $titles ? array_pop( $titles ) : null;
	}
	
	/**
	 * Extract a redirect destination from a string and return an
	 * array of Titles, or null if the text doesn't contain a valid redirect
	 * The last element in the array is the final destination after all redirects
	 * have been resolved (up to $wgMaxRedirects times)
	 *
	 * @param $text \type{\string} Text with possible redirect
	 * @return \type{\array} Array of Titles, with the destination last
	 */
	public static function newFromRedirectArray( $text ) {
		global $wgMaxRedirects;
		// are redirects disabled?
		if( $wgMaxRedirects < 1 )
			return null;
		$title = self::newFromRedirectInternal( $text );
		if( is_null( $title ) )
			return null;
		// recursive check to follow double redirects
		$recurse = $wgMaxRedirects;
		$titles = array( $title );
		while( --$recurse > 0 ) {
			if( $title->isRedirect() ) {
				$article = new Article( $title, 0 );
				$newtitle = $article->getRedirectTarget();
			} else {
				break;
			}
			// Redirects to some special pages are not permitted
			if( $newtitle instanceOf Title && $newtitle->isValidRedirectTarget() ) {
				// the new title passes the checks, so make that our current title so that further recursion can be checked
				$title = $newtitle;
				$titles[] = $newtitle;
			} else {
				break;
			}
		}
		return $titles;
	}
	
	/**
	 * Really extract the redirect destination
	 * Do not call this function directly, use one of the newFromRedirect* functions above
	 *
	 * @param $text \type{\string} Text with possible redirect
	 * @return \type{Title} The corresponding Title
	 */
	protected static function newFromRedirectInternal( $text ) {
		$redir = MagicWord::get( 'redirect' );
		$text = trim($text);
		if( $redir->matchStartAndRemove( $text ) ) {
			// Extract the first link and see if it's usable
			// Ensure that it really does come directly after #REDIRECT
			// Some older redirects included a colon, so don't freak about that!
			$m = array();
			if( preg_match( '!^\s*:?\s*\[{2}(.*?)(?:\|.*?)?\]{2}!', $text, $m ) ) {
				// Strip preceding colon used to "escape" categories, etc.
				// and URL-decode links
				if( strpos( $m[1], '%' ) !== false ) {
					// Match behavior of inline link parsing here;
					// don't interpret + as " " most of the time!
					// It might be safe to just use rawurldecode instead, though.
					$m[1] = urldecode( ltrim( $m[1], ':' ) );
				}
				$title = Title::newFromText( $m[1] );
				// If the title is a redirect to bad special pages or is invalid, return null
				if( !$title instanceof Title || !$title->isValidRedirectTarget() ) {
					return null;
				}
				return $title;
			}
		}
		return null;
	}

#----------------------------------------------------------------------------
#	Static functions
#----------------------------------------------------------------------------

	/**
	 * Get the prefixed DB key associated with an ID
	 * @param $id \type{\int} the page_id of the article
	 * @return \type{Title} an object representing the article, or NULL
	 * 	if no such article was found
	 */
	public static function nameOf( $id ) {
		$dbr = wfGetDB( DB_SLAVE );

		$s = $dbr->selectRow( 'page',
			array( 'page_namespace','page_title' ),
			array( 'page_id' => $id ), 
			__METHOD__ );
		if ( $s === false ) { return NULL; }

		$n = self::makeName( $s->page_namespace, $s->page_title );
		return $n;
	}

	/**
	 * Get a regex character class describing the legal characters in a link
	 * @return \type{\string} the list of characters, not delimited
	 */
	public static function legalChars() {
		global $wgLegalTitleChars;
		return $wgLegalTitleChars;
	}

	/**
	 * Get a string representation of a title suitable for
	 * including in a search index
	 *
	 * @param $ns \type{\int} a namespace index
	 * @param $title \type{\string} text-form main part
	 * @return \type{\string} a stripped-down title string ready for the
	 * 	search index
	 */
	public static function indexTitle( $ns, $title ) {
		global $wgContLang;

		$lc = SearchEngine::legalSearchChars() . '&#;';
		$t = $wgContLang->stripForSearch( $title );
		$t = preg_replace( "/[^{$lc}]+/", ' ', $t );
		$t = $wgContLang->lc( $t );

		# Handle 's, s'
		$t = preg_replace( "/([{$lc}]+)'s( |$)/", "\\1 \\1's ", $t );
		$t = preg_replace( "/([{$lc}]+)s'( |$)/", "\\1s ", $t );

		$t = preg_replace( "/\\s+/", ' ', $t );

		if ( $ns == NS_FILE ) {
			$t = preg_replace( "/ (png|gif|jpg|jpeg|ogg)$/", "", $t );
		}
		return trim( $t );
	}

	/**
	 * Make a prefixed DB key from a DB key and a namespace index
	 * @param $ns \type{\int} numerical representation of the namespace
	 * @param $title \type{\string} the DB key form the title
	 * @param $fragment \type{\string} The link fragment (after the "#")
	 * @return \type{\string} the prefixed form of the title
	 */
	public static function makeName( $ns, $title, $fragment = '' ) {
		global $wgContLang;

		$namespace = $wgContLang->getNsText( $ns );
		$name = $namespace == '' ? $title : "$namespace:$title";
		if ( strval( $fragment ) != '' ) {
			$name .= '#' . $fragment;
		}
		return $name;
	}

	/**
	 * Determine whether the object refers to a page within
	 * this project.
	 *
	 * @return \type{\bool} TRUE if this is an in-project interwiki link
	 *	or a wikilink, FALSE otherwise
	 */
	public function isLocal() {
		if ( $this->mInterwiki != '' ) {
			return Interwiki::fetch( $this->mInterwiki )->isLocal();
		} else {
			return true;
		}
	}

	/**
	 * Determine whether the object refers to a page within
	 * this project and is transcludable.
	 *
	 * @return \type{\bool} TRUE if this is transcludable
	 */
	public function isTrans() {
		if ($this->mInterwiki == '')
			return false;
		
		return Interwiki::fetch( $this->mInterwiki )->isTranscludable();
	}

	/**
	 * Escape a text fragment, say from a link, for a URL
	 */
	static function escapeFragmentForURL( $fragment ) {
		global $wgEnforceHtmlIds;
		# Note that we don't urlencode the fragment.  urlencoded Unicode
		# fragments appear not to work in IE (at least up to 7) or in at least
		# one version of Opera 9.x.  The W3C validator, for one, doesn't seem
		# to care if they aren't encoded.
		return Sanitizer::escapeId( $fragment,
			$wgEnforceHtmlIds ? 'noninitial' : 'xml' );
	}

#----------------------------------------------------------------------------
#	Other stuff
#----------------------------------------------------------------------------

	/** Simple accessors */
	/**
	 * Get the text form (spaces not underscores) of the main part
	 * @return \type{\string} Main part of the title
	 */
	public function getText() { return $this->mTextform; }
	/**
	 * Get the URL-encoded form of the main part
	 * @return \type{\string} Main part of the title, URL-encoded
	 */
	public function getPartialURL() { return $this->mUrlform; }
	/**
	 * Get the main part with underscores
	 * @return \type{\string} Main part of the title, with underscores
	 */
	public function getDBkey() { return $this->mDbkeyform; }
	/**
	 * Get the namespace index, i.e.\ one of the NS_xxxx constants.
	 * @return \type{\int} Namespace index
	 */
	public function getNamespace() { return $this->mNamespace; }
	/**
	 * Get the namespace text
	 * @return \type{\string} Namespace text
	 */
	public function getNsText() {
		global $wgContLang, $wgCanonicalNamespaceNames;

		if ( '' != $this->mInterwiki ) {
			// This probably shouldn't even happen. ohh man, oh yuck.
			// But for interwiki transclusion it sometimes does.
			// Shit. Shit shit shit.
			//
			// Use the canonical namespaces if possible to try to
			// resolve a foreign namespace.
			if( isset( $wgCanonicalNamespaceNames[$this->mNamespace] ) ) {
				return $wgCanonicalNamespaceNames[$this->mNamespace];
			}
		}
		return $wgContLang->getNsText( $this->mNamespace );
	}
	/**
	 * Get the DB key with the initial letter case as specified by the user
	 * @return \type{\string} DB key
	 */
	function getUserCaseDBKey() {
		return $this->mUserCaseDBKey;
	}
	/**
	 * Get the namespace text of the subject (rather than talk) page
	 * @return \type{\string} Namespace text
	 */
	public function getSubjectNsText() {
		global $wgContLang;
		return $wgContLang->getNsText( MWNamespace::getSubject( $this->mNamespace ) );
	}
	/**
	 * Get the namespace text of the talk page
	 * @return \type{\string} Namespace text
	 */
	public function getTalkNsText() {
		global $wgContLang;
		return( $wgContLang->getNsText( MWNamespace::getTalk( $this->mNamespace ) ) );
	}
	/**
	 * Could this title have a corresponding talk page?
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function canTalk() {
		return( MWNamespace::canTalk( $this->mNamespace ) );
	}
	/**
	 * Get the interwiki prefix (or null string)
	 * @return \type{\string} Interwiki prefix
	 */
	public function getInterwiki() { return $this->mInterwiki; }
	/**
	 * Get the Title fragment (i.e.\ the bit after the #) in text form
	 * @return \type{\string} Title fragment
	 */
	public function getFragment() { return $this->mFragment; }
	/**
	 * Get the fragment in URL form, including the "#" character if there is one
	 * @return \type{\string} Fragment in URL form
	 */
	public function getFragmentForURL() {
		if ( $this->mFragment == '' ) {
			return '';
		} else {
			return '#' . Title::escapeFragmentForURL( $this->mFragment );
		}
	}
	/**
	 * Get the default namespace index, for when there is no namespace
	 * @return \type{\int} Default namespace index
	 */
	public function getDefaultNamespace() { return $this->mDefaultNamespace; }

	/**
	 * Get title for search index
	 * @return \type{\string} a stripped-down title string ready for the
	 * 	search index
	 */
	public function getIndexTitle() {
		return Title::indexTitle( $this->mNamespace, $this->mTextform );
	}

	/**
	 * Get the prefixed database key form
	 * @return \type{\string} the prefixed title, with underscores and
	 * 	any interwiki and namespace prefixes
	 */
	public function getPrefixedDBkey() {
		$s = $this->prefix( $this->mDbkeyform );
		$s = str_replace( ' ', '_', $s );
		return $s;
	}

	/**
	 * Get the prefixed title with spaces.
	 * This is the form usually used for display
	 * @return \type{\string} the prefixed title, with spaces
	 */
	public function getPrefixedText() {
		if ( empty( $this->mPrefixedText ) ) { // FIXME: bad usage of empty() ?
			$s = $this->prefix( $this->mTextform );
			$s = str_replace( '_', ' ', $s );
			$this->mPrefixedText = $s;
		}
		return $this->mPrefixedText;
	}

	/**
	 * Get the prefixed title with spaces, plus any fragment
	 * (part beginning with '#')
	 * @return \type{\string} the prefixed title, with spaces and
	 * 	the fragment, including '#'
	 */
	public function getFullText() {
		$text = $this->getPrefixedText();
		if( '' != $this->mFragment ) {
			$text .= '#' . $this->mFragment;
		}
		return $text;
	}

	/**
	 * Get the base name, i.e. the leftmost parts before the /
	 * @return \type{\string} Base name
	 */
	public function getBaseText() {
		if( !MWNamespace::hasSubpages( $this->mNamespace ) ) {
			return $this->getText();
		}

		$parts = explode( '/', $this->getText() );
		# Don't discard the real title if there's no subpage involved
		if( count( $parts ) > 1 )
			unset( $parts[ count( $parts ) - 1 ] );
		return implode( '/', $parts );
	}

	/**
	 * Get the lowest-level subpage name, i.e. the rightmost part after /
	 * @return \type{\string} Subpage name
	 */
	public function getSubpageText() {
		if( !MWNamespace::hasSubpages( $this->mNamespace ) ) {
			return( $this->mTextform );
		}
		$parts = explode( '/', $this->mTextform );
		return( $parts[ count( $parts ) - 1 ] );
	}

	/**
	 * Get a URL-encoded form of the subpage text
	 * @return \type{\string} URL-encoded subpage name
	 */
	public function getSubpageUrlForm() {
		$text = $this->getSubpageText();
		$text = wfUrlencode( str_replace( ' ', '_', $text ) );
		return( $text );
	}

	/**
	 * Get a URL-encoded title (not an actual URL) including interwiki
	 * @return \type{\string} the URL-encoded form
	 */
	public function getPrefixedURL() {
		$s = $this->prefix( $this->mDbkeyform );
		$s = wfUrlencode( str_replace( ' ', '_', $s ) );
		return $s;
	}

	/**
	 * Get a real URL referring to this title, with interwiki link and
	 * fragment
	 *
	 * @param $query \twotypes{\string,\array} an optional query string, not used for interwiki
	 *   links. Can be specified as an associative array as well, e.g.,
	 *   array( 'action' => 'edit' ) (keys and values will be URL-escaped).
	 * @param $variant \type{\string} language variant of url (for sr, zh..)
	 * @return \type{\string} the URL
	 */
	public function getFullURL( $query = '', $variant = false ) {
		global $wgContLang, $wgServer, $wgRequest;

		if( is_array( $query ) ) {
			$query = wfArrayToCGI( $query );
		}

		$interwiki = Interwiki::fetch( $this->mInterwiki );
		if ( !$interwiki ) {
			$url = $this->getLocalUrl( $query, $variant );

			// Ugly quick hack to avoid duplicate prefixes (bug 4571 etc)
			// Correct fix would be to move the prepending elsewhere.
			if ($wgRequest->getVal('action') != 'render') {
				$url = $wgServer . $url;
			}
		} else {
			$baseUrl = $interwiki->getURL( );

			$namespace = wfUrlencode( $this->getNsText() );
			if ( '' != $namespace ) {
				# Can this actually happen? Interwikis shouldn't be parsed.
				# Yes! It can in interwiki transclusion. But... it probably shouldn't.
				$namespace .= ':';
			}
			$url = str_replace( '$1', $namespace . $this->mUrlform, $baseUrl );
			$url = wfAppendQuery( $url, $query );
		}

		# Finally, add the fragment.
		$url .= $this->getFragmentForURL();

		wfRunHooks( 'GetFullURL', array( &$this, &$url, $query ) );
		return $url;
	}

	/**
	 * Get a URL with no fragment or server name.  If this page is generated
	 * with action=render, $wgServer is prepended.
	 * @param mixed $query an optional query string; if not specified,
	 * 	 $wgArticlePath will be used.  Can be specified as an associative array
	 *   as well, e.g., array( 'action' => 'edit' ) (keys and values will be
	 *   URL-escaped).
	 * @param $variant \type{\string} language variant of url (for sr, zh..)
	 * @return \type{\string} the URL
	 */
	public function getLocalURL( $query = '', $variant = false ) {
		global $wgArticlePath, $wgScript, $wgServer, $wgRequest;
		global $wgVariantArticlePath, $wgContLang, $wgUser;

		if( is_array( $query ) ) {
			$query = wfArrayToCGI( $query );
		}

		// internal links should point to same variant as current page (only anonymous users)
		if($variant == false && $wgContLang->hasVariants() && !$wgUser->isLoggedIn()){
			$pref = $wgContLang->getPreferredVariant(false);
			if($pref != $wgContLang->getCode())
				$variant = $pref;
		}

		if ( $this->isExternal() ) {
			$url = $this->getFullURL();
			if ( $query ) {
				// This is currently only used for edit section links in the
				// context of interwiki transclusion. In theory we should
				// append the query to the end of any existing query string,
				// but interwiki transclusion is already broken in that case.
				$url .= "?$query";
			}
		} else {
			$dbkey = wfUrlencode( $this->getPrefixedDBkey() );
			if ( $query == '' ) {
				if( $variant != false && $wgContLang->hasVariants() ) {
					if( $wgVariantArticlePath == false ) {
						$variantArticlePath =  "$wgScript?title=$1&variant=$2"; // default
					} else {
						$variantArticlePath = $wgVariantArticlePath;
					}
					$url = str_replace( '$2', urlencode( $variant ), $variantArticlePath );
					$url = str_replace( '$1', $dbkey, $url  );
				} else {
					$url = str_replace( '$1', $dbkey, $wgArticlePath );
				}
			} else {
				global $wgActionPaths;
				$url = false;
				$matches = array();
				if( !empty( $wgActionPaths ) &&
					preg_match( '/^(.*&|)action=([^&]*)(&(.*)|)$/', $query, $matches ) )
				{
					$action = urldecode( $matches[2] );
					if( isset( $wgActionPaths[$action] ) ) {
						$query = $matches[1];
						if( isset( $matches[4] ) ) $query .= $matches[4];
						$url = str_replace( '$1', $dbkey, $wgActionPaths[$action] );
						if( $query != '' ) {
							$url = wfAppendQuery( $url, $query );
						}
					}
				}
				if ( $url === false ) {
					if ( $query == '-' ) {
						$query = '';
					}
					$url = "{$wgScript}?title={$dbkey}&{$query}";
				}
			}

			// FIXME: this causes breakage in various places when we
			// actually expected a local URL and end up with dupe prefixes.
			if ($wgRequest->getVal('action') == 'render') {
				$url = $wgServer . $url;
			}
		}
		wfRunHooks( 'GetLocalURL', array( &$this, &$url, $query ) );
		return $url;
	}

	/**
	 * Get a URL that's the simplest URL that will be valid to link, locally,
	 * to the current Title.  It includes the fragment, but does not include
	 * the server unless action=render is used (or the link is external).  If
	 * there's a fragment but the prefixed text is empty, we just return a link
	 * to the fragment.
	 *
	 * The result obviously should not be URL-escaped, but does need to be
	 * HTML-escaped if it's being output in HTML.
	 *
	 * @param $query \type{\arrayof{\string}} An associative array of key => value pairs for the
	 *   query string.  Keys and values will be escaped.
	 * @param $variant \type{\string} Language variant of URL (for sr, zh..).  Ignored
	 *   for external links.  Default is "false" (same variant as current page,
	 *   for anonymous users).
	 * @return \type{\string} the URL
	 */
	public function getLinkUrl( $query = array(), $variant = false ) {
		wfProfileIn( __METHOD__ );
		if( !is_array( $query ) ) {
			wfProfileOut( __METHOD__ );
			throw new MWException( 'Title::getLinkUrl passed a non-array for '.
			'$query' );
		}
		if( $this->isExternal() ) {
			$ret = $this->getFullURL( $query );
		} elseif( $this->getPrefixedText() === '' && $this->getFragment() !== '' ) {
			$ret = $this->getFragmentForURL();
		} else {
			$ret = $this->getLocalURL( $query, $variant ) . $this->getFragmentForURL();
		}
		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * Get an HTML-escaped version of the URL form, suitable for
	 * using in a link, without a server name or fragment
	 * @param $query \type{\string} an optional query string
	 * @return \type{\string} the URL
	 */
	public function escapeLocalURL( $query = '' ) {
		return htmlspecialchars( $this->getLocalURL( $query ) );
	}

	/**
	 * Get an HTML-escaped version of the URL form, suitable for
	 * using in a link, including the server name and fragment
	 *
	 * @param $query \type{\string} an optional query string
	 * @return \type{\string} the URL
	 */
	public function escapeFullURL( $query = '' ) {
		return htmlspecialchars( $this->getFullURL( $query ) );
	}

	/**
	 * Get the URL form for an internal link.
	 * - Used in various Squid-related code, in case we have a different
	 * internal hostname for the server from the exposed one.
	 *
	 * @param $query \type{\string} an optional query string
	 * @param $variant \type{\string} language variant of url (for sr, zh..)
	 * @return \type{\string} the URL
	 */
	public function getInternalURL( $query = '', $variant = false ) {
		global $wgInternalServer;
		$url = $wgInternalServer . $this->getLocalURL( $query, $variant );
		wfRunHooks( 'GetInternalURL', array( &$this, &$url, $query ) );
		return $url;
	}

	/**
	 * Get the edit URL for this Title
	 * @return \type{\string} the URL, or a null string if this is an
	 * 	interwiki link
	 */
	public function getEditURL() {
		if ( '' != $this->mInterwiki ) { return ''; }
		$s = $this->getLocalURL( 'action=edit' );

		return $s;
	}

	/**
	 * Get the HTML-escaped displayable text form.
	 * Used for the title field in <a> tags.
	 * @return \type{\string} the text, including any prefixes
	 */
	public function getEscapedText() {
		return htmlspecialchars( $this->getPrefixedText() );
	}

	/**
	 * Is this Title interwiki?
	 * @return \type{\bool}
	 */
	public function isExternal() { return ( '' != $this->mInterwiki ); }

	/**
	 * Is this page "semi-protected" - the *only* protection is autoconfirm?
	 *
	 * @param @action \type{\string} Action to check (default: edit)
	 * @return \type{\bool}
	 */
	public function isSemiProtected( $action = 'edit' ) {
		if( $this->exists() ) {
			$restrictions = $this->getRestrictions( $action );
			if( count( $restrictions ) > 0 ) {
				foreach( $restrictions as $restriction ) {
					if( strtolower( $restriction ) != 'autoconfirmed' )
						return false;
				}
			} else {
				# Not protected
				return false;
			}
			return true;
		} else {
			# If it doesn't exist, it can't be protected
			return false;
		}
	}

	/**
	 * Does the title correspond to a protected article?
	 * @param $what \type{\string} the action the page is protected from,
	 * by default checks move and edit
	 * @return \type{\bool}
	 */
	public function isProtected( $action = '' ) {
		global $wgRestrictionLevels, $wgRestrictionTypes;

		# Special pages have inherent protection
		if( $this->getNamespace() == NS_SPECIAL )
			return true;

		# Check regular protection levels
		foreach( $wgRestrictionTypes as $type ){
			if( $action == $type || $action == '' ) {
				$r = $this->getRestrictions( $type );
				foreach( $wgRestrictionLevels as $level ) {
					if( in_array( $level, $r ) && $level != '' ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Is $wgUser watching this page?
	 * @return \type{\bool}
	 */
	public function userIsWatching() {
		global $wgUser;

		if ( is_null( $this->mWatched ) ) {
			if ( NS_SPECIAL == $this->mNamespace || !$wgUser->isLoggedIn()) {
				$this->mWatched = false;
			} else {
				$this->mWatched = $wgUser->isWatched( $this );
			}
		}
		return $this->mWatched;
	}

	/**
	 * Can $wgUser perform $action on this page?
	 * This skips potentially expensive cascading permission checks.
	 *
	 * Suitable for use for nonessential UI controls in common cases, but
	 * _not_ for functional access control.
	 *
	 * May provide false positives, but should never provide a false negative.
	 *
	 * @param $action \type{\string} action that permission needs to be checked for
	 * @return \type{\bool}
 	 */
	public function quickUserCan( $action ) {
		return $this->userCan( $action, false );
	}

	/**
	 * Determines if $wgUser is unable to edit this page because it has been protected
	 * by $wgNamespaceProtection.
	 *
	 * @return \type{\bool}
	 */
	public function isNamespaceProtected() {
		global $wgNamespaceProtection, $wgUser;
		if( isset( $wgNamespaceProtection[ $this->mNamespace ] ) ) {
			foreach( (array)$wgNamespaceProtection[ $this->mNamespace ] as $right ) {
				if( $right != '' && !$wgUser->isAllowed( $right ) )
					return true;
			}
		}
		return false;
	}

	/**
	 * Can $wgUser perform $action on this page?
	 * @param $action \type{\string} action that permission needs to be checked for
	 * @param $doExpensiveQueries \type{\bool} Set this to false to avoid doing unnecessary queries.
	 * @return \type{\bool}
 	 */
	public function userCan( $action, $doExpensiveQueries = true ) {
		global $wgUser;
		return ($this->getUserPermissionsErrorsInternal( $action, $wgUser, $doExpensiveQueries, true ) === array());
	}

	/**
	 * Can $user perform $action on this page?
	 *
	 * FIXME: This *does not* check throttles (User::pingLimiter()).
	 *
	 * @param $action \type{\string}action that permission needs to be checked for
	 * @param $user \type{User} user to check
	 * @param $doExpensiveQueries \type{\bool} Set this to false to avoid doing unnecessary queries.
	 * @param $ignoreErrors \type{\arrayof{\string}} Set this to a list of message keys whose corresponding errors may be ignored.
	 * @return \type{\array} Array of arrays of the arguments to wfMsg to explain permissions problems.
	 */
	public function getUserPermissionsErrors( $action, $user, $doExpensiveQueries = true, $ignoreErrors = array() ) {
		if( !StubObject::isRealObject( $user ) ) {
			//Since StubObject is always used on globals, we can unstub $wgUser here and set $user = $wgUser
			global $wgUser;
			$wgUser->_unstub( '', 5 );
			$user = $wgUser;
		}
		$errors = $this->getUserPermissionsErrorsInternal( $action, $user, $doExpensiveQueries );

		global $wgContLang;
		global $wgLang;
		global $wgEmailConfirmToEdit;

		if ( $wgEmailConfirmToEdit && !$user->isEmailConfirmed() && $action != 'createaccount' ) {
			$errors[] = array( 'confirmedittext' );
		}

		// Edit blocks should not affect reading. Account creation blocks handled at userlogin.
		if ( $action != 'read' && $action != 'createaccount' && $user->isBlockedFrom( $this ) ) {
			$block = $user->mBlock;

			// This is from OutputPage::blockedPage
			// Copied at r23888 by werdna

			$id = $user->blockedBy();
			$reason = $user->blockedFor();
			if( $reason == '' ) {
				$reason = wfMsg( 'blockednoreason' );
			}
			$ip = wfGetIP();

			if ( is_numeric( $id ) ) {
				$name = User::whoIs( $id );
			} else {
				$name = $id;
			}

			$link = '[[' . $wgContLang->getNsText( NS_USER ) . ":{$name}|{$name}]]";
			$blockid = $block->mId;
			$blockExpiry = $user->mBlock->mExpiry;
			$blockTimestamp = $wgLang->timeanddate( wfTimestamp( TS_MW, $user->mBlock->mTimestamp ), true );

			if ( $blockExpiry == 'infinity' ) {
				// Entry in database (table ipblocks) is 'infinity' but 'ipboptions' uses 'infinite' or 'indefinite'
				$scBlockExpiryOptions = wfMsg( 'ipboptions' );

				foreach ( explode( ',', $scBlockExpiryOptions ) as $option ) {
					if ( strpos( $option, ':' ) == false )
						continue;

					list ($show, $value) = explode( ":", $option );

					if ( $value == 'infinite' || $value == 'indefinite' ) {
						$blockExpiry = $show;
						break;
					}
				}
			} else {
				$blockExpiry = $wgLang->timeanddate( wfTimestamp( TS_MW, $blockExpiry ), true );
			}

			$intended = $user->mBlock->mAddress;

			$errors[] = array( ($block->mAuto ? 'autoblockedtext' : 'blockedtext'), $link, $reason, $ip, $name, 
				$blockid, $blockExpiry, $intended, $blockTimestamp );
		}
		
		// Remove the errors being ignored.
		
		foreach( $errors as $index => $error ) {
			$error_key = is_array($error) ? $error[0] : $error;
			
			if (in_array( $error_key, $ignoreErrors )) {
				unset($errors[$index]);
			}
		}

		return $errors;
	}

	/**
	 * Can $user perform $action on this page? This is an internal function,
	 * which checks ONLY that previously checked by userCan (i.e. it leaves out
	 * checks on wfReadOnly() and blocks)
	 *
	 * @param $action \type{\string} action that permission needs to be checked for
	 * @param $user \type{User} user to check
	 * @param $doExpensiveQueries \type{\bool} Set this to false to avoid doing unnecessary queries.
	 * @param $short \type{\bool} Set this to true to stop after the first permission error.
	 * @return \type{\array} Array of arrays of the arguments to wfMsg to explain permissions problems.
	 */
	private function getUserPermissionsErrorsInternal( $action, $user, $doExpensiveQueries=true, $short=false ) {
		wfProfileIn( __METHOD__ );

		$errors = array();

		// First stop is permissions checks, which fail most often, and which are easiest to test.
		if ( $action == 'move' ) {
			if( !$user->isAllowed( 'move-rootuserpages' )
					&& $this->getNamespace() == NS_USER && !$this->isSubpage() )
			{
				// Show user page-specific message only if the user can move other pages
				$errors[] = array( 'cant-move-user-page' );
			}
			
			// Check if user is allowed to move files if it's a file
			if( $this->getNamespace() == NS_FILE && !$user->isAllowed( 'movefile' ) ) {
				$errors[] = array( 'movenotallowedfile' );
			}
			
			if( !$user->isAllowed( 'move' ) ) {
				// User can't move anything
				global $wgGroupPermissions;
				if( $user->isAnon() && ( $wgGroupPermissions['user']['move']
				|| $wgGroupPermissions['autoconfirmed']['move'] ) ) {
					// custom message if logged-in users without any special rights can move
					$errors[] = array ( 'movenologintext' );
				} else {
					$errors[] = array ('movenotallowed');
				}
			}
		} elseif ( $action == 'create' ) {
			if( ( $this->isTalkPage() && !$user->isAllowed( 'createtalk' ) ) ||
				( !$this->isTalkPage() && !$user->isAllowed( 'createpage' ) ) )
			{
				$errors[] = $user->isAnon() ? array ('nocreatetext') : array ('nocreate-loggedin');
			}
		} elseif( $action == 'move-target' ) {
			if( !$user->isAllowed( 'move' ) ) {
				// User can't move anything
				$errors[] = array ('movenotallowed');
			} elseif( !$user->isAllowed( 'move-rootuserpages' )
				&& $this->getNamespace() == NS_USER && !$this->isSubpage() )
			{
				// Show user page-specific message only if the user can move other pages
				$errors[] = array( 'cant-move-to-user-page' );
			}
		} elseif( !$user->isAllowed( $action ) ) {
			$return = null;
			$groups = array_map( array( 'User', 'makeGroupLinkWiki' ),
				User::getGroupsWithPermission( $action ) );
			if( $groups ) {
				$return = array( 'badaccess-groups',
					array( implode( ', ', $groups ), count( $groups ) ) );
			} else {
				$return = array( "badaccess-group0" );
			}
			$errors[] = $return;
		}

		# Short-circuit point
		if( $short && count($errors) > 0 ) {
			wfProfileOut( __METHOD__ );
			return $errors;
		}

		// Use getUserPermissionsErrors instead
		if( !wfRunHooks( 'userCan', array( &$this, &$user, $action, &$result ) ) ) {
			wfProfileOut( __METHOD__ );
			return $result ? array() : array( array( 'badaccess-group0' ) );
		}
		// Check getUserPermissionsErrors hook
		if( !wfRunHooks( 'getUserPermissionsErrors', array(&$this,&$user,$action,&$result) ) ) {
			if( is_array($result) && count($result) && !is_array($result[0]) )
				$errors[] = $result; # A single array representing an error
			else if( is_array($result) && is_array($result[0]) )
				$errors = array_merge( $errors, $result ); # A nested array representing multiple errors
			else if( $result !== '' && is_string($result) )
				$errors[] = array($result); # A string representing a message-id
			else if( $result === false )
				$errors[] = array('badaccess-group0'); # a generic "We don't want them to do that"
		}
		# Short-circuit point
		if( $short && count($errors) > 0 ) {
			wfProfileOut( __METHOD__ );
			return $errors;
		}
		// Check getUserPermissionsErrorsExpensive hook
		if( $doExpensiveQueries && !wfRunHooks( 'getUserPermissionsErrorsExpensive', array(&$this,&$user,$action,&$result) ) ) {
			if( is_array($result) && count($result) && !is_array($result[0]) )
				$errors[] = $result; # A single array representing an error
			else if( is_array($result) && is_array($result[0]) )
				$errors = array_merge( $errors, $result ); # A nested array representing multiple errors
			else if( $result !== '' && is_string($result) )
				$errors[] = array($result); # A string representing a message-id
			else if( $result === false )
				$errors[] = array('badaccess-group0'); # a generic "We don't want them to do that"
		}
		# Short-circuit point
		if( $short && count($errors) > 0 ) {
			wfProfileOut( __METHOD__ );
			return $errors;
		}
		
		# Only 'createaccount' and 'execute' can be performed on
		# special pages, which don't actually exist in the DB.
		$specialOKActions = array( 'createaccount', 'execute' );
		if( NS_SPECIAL == $this->mNamespace && !in_array( $action, $specialOKActions) ) {
			$errors[] = array('ns-specialprotected');
		}

		# Check $wgNamespaceProtection for restricted namespaces
		if( $this->isNamespaceProtected() ) {
			$ns = $this->getNamespace() == NS_MAIN ?
				wfMsg( 'nstab-main' ) : $this->getNsText();
			$errors[] = NS_MEDIAWIKI == $this->mNamespace ?
				array('protectedinterface') : array( 'namespaceprotected',  $ns );
		}

		# Protect css/js subpages of user pages
		# XXX: this might be better using restrictions
		# XXX: Find a way to work around the php bug that prevents using $this->userCanEditCssJsSubpage() from working
		if( $this->isCssJsSubpage() && !$user->isAllowed('editusercssjs')
			&& !preg_match('/^'.preg_quote($user->getName(), '/').'\//', $this->mTextform) )
		{
			$errors[] = array('customcssjsprotected');
		}

		# Check against page_restrictions table requirements on this
		# page. The user must possess all required rights for this action.
		foreach( $this->getRestrictions($action) as $right ) {
			// Backwards compatibility, rewrite sysop -> protect
			if( $right == 'sysop' ) {
				$right = 'protect';
			}
			if( '' != $right && !$user->isAllowed( $right ) ) {
				// Users with 'editprotected' permission can edit protected pages
				if( $action=='edit' && $user->isAllowed( 'editprotected' ) ) {
					// Users with 'editprotected' permission cannot edit protected pages
					// with cascading option turned on.
					if( $this->mCascadeRestriction ) {
						$errors[] = array( 'protectedpagetext', $right );
					}
				} else {
					$errors[] = array( 'protectedpagetext', $right );
				}
			}
		}
		# Short-circuit point
		if( $short && count($errors) > 0 ) {
			wfProfileOut( __METHOD__ );
			return $errors;
		}
		
		if( $doExpensiveQueries && !$this->isCssJsSubpage() ) {
			# We /could/ use the protection level on the source page, but it's fairly ugly
			#  as we have to establish a precedence hierarchy for pages included by multiple
			#  cascade-protected pages. So just restrict it to people with 'protect' permission,
			#  as they could remove the protection anyway.
			list( $cascadingSources, $restrictions ) = $this->getCascadeProtectionSources();
			# Cascading protection depends on more than this page...
			# Several cascading protected pages may include this page...
			# Check each cascading level
			# This is only for protection restrictions, not for all actions
			if( $cascadingSources > 0 && isset($restrictions[$action]) ) {
				foreach( $restrictions[$action] as $right ) {
					$right = ( $right == 'sysop' ) ? 'protect' : $right;
					if( '' != $right && !$user->isAllowed( $right ) ) {
						$pages = '';
						foreach( $cascadingSources as $page )
							$pages .= '* [[:' . $page->getPrefixedText() . "]]\n";
						$errors[] = array( 'cascadeprotected', count( $cascadingSources ), $pages );
					}
				}
			}
		}
		# Short-circuit point
		if( $short && count($errors) > 0 ) {
			wfProfileOut( __METHOD__ );
			return $errors;
		}

		if( $action == 'protect' ) {
			if( $this->getUserPermissionsErrors('edit', $user) != array() ) {
				$errors[] = array( 'protect-cantedit' ); // If they can't edit, they shouldn't protect.
			}
		}

		if( $action == 'create' ) {
			$title_protection = $this->getTitleProtection();
			if( is_array($title_protection) ) {
				extract($title_protection); // is this extract() really needed?

				if( $pt_create_perm == 'sysop' ) {
					$pt_create_perm = 'protect'; // B/C
				}
				if( $pt_create_perm == '' || !$user->isAllowed($pt_create_perm) ) {
					$errors[] = array( 'titleprotected', User::whoIs($pt_user), $pt_reason );
				}
			}
		} elseif( $action == 'move' ) {
			// Check for immobile pages
			if( !MWNamespace::isMovable( $this->getNamespace() ) ) {
				// Specific message for this case
				$errors[] = array( 'immobile-source-namespace', $this->getNsText() );
			} elseif( !$this->isMovable() ) {
				// Less specific message for rarer cases
				$errors[] = array( 'immobile-page' );
			}
		} elseif( $action == 'move-target' ) {
			if( !MWNamespace::isMovable( $this->getNamespace() ) ) {
				$errors[] = array( 'immobile-target-namespace', $this->getNsText() );
			} elseif( !$this->isMovable() ) {
				$errors[] = array( 'immobile-target-page' );
			}
		}

		wfProfileOut( __METHOD__ );
		return $errors;
	}

	/**
	 * Is this title subject to title protection?
	 * @return \type{\mixed} An associative array representing any existent title
	 *   protection, or false if there's none.
	 */
	private function getTitleProtection() {
		// Can't protect pages in special namespaces
		if ( $this->getNamespace() < 0 ) {
			return false;
		}

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'protected_titles', '*',
			array( 'pt_namespace' => $this->getNamespace(), 'pt_title' => $this->getDBkey() ),
			__METHOD__ );

		if ($row = $dbr->fetchRow( $res )) {
			return $row;
		} else {
			return false;
		}
	}

	/**
	 * Update the title protection status
	 * @param $create_perm \type{\string} Permission required for creation
	 * @param $reason \type{\string} Reason for protection
	 * @param $expiry \type{\string} Expiry timestamp
	 */
	public function updateTitleProtection( $create_perm, $reason, $expiry ) {
		global $wgUser,$wgContLang;

		if ($create_perm == implode(',',$this->getRestrictions('create'))
			&& $expiry == $this->mRestrictionsExpiry['create']) {
			// No change
			return true;
		}

		list ($namespace, $title) = array( $this->getNamespace(), $this->getDBkey() );

		$dbw = wfGetDB( DB_MASTER );

		$encodedExpiry = Block::encodeExpiry($expiry, $dbw );

		$expiry_description = '';
		if ( $encodedExpiry != 'infinity' ) {
			$expiry_description = ' (' . wfMsgForContent( 'protect-expiring', $wgContLang->timeanddate( $expiry ) , $wgContLang->date( $expiry ) , $wgContLang->time( $expiry ) ).')';
		}
		else {
			$expiry_description .= ' (' . wfMsgForContent( 'protect-expiry-indefinite' ).')';
		}
	
		# Update protection table
		if ($create_perm != '' ) {
			$dbw->replace( 'protected_titles', array(array('pt_namespace', 'pt_title')),
				array( 'pt_namespace' => $namespace, 'pt_title' => $title
					, 'pt_create_perm' => $create_perm
					, 'pt_timestamp' => Block::encodeExpiry(wfTimestampNow(), $dbw)
					, 'pt_expiry' => $encodedExpiry
					, 'pt_user' => $wgUser->getId(), 'pt_reason' => $reason ), __METHOD__  );
		} else {
			$dbw->delete( 'protected_titles', array( 'pt_namespace' => $namespace,
				'pt_title' => $title ), __METHOD__ );
		}
		# Update the protection log
		$log = new LogPage( 'protect' );

		if( $create_perm ) {
			$params = array("[create=$create_perm] $expiry_description",'');
			$log->addEntry( ( isset( $this->mRestrictions['create'] ) && $this->mRestrictions['create'] ) ? 'modify' : 'protect', $this, trim( $reason ), $params );
		} else {
			$log->addEntry( 'unprotect', $this, $reason );
		}

		return true;
	}

	/**
	 * Remove any title protection due to page existing
	 */
	public function deleteTitleProtection() {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->delete( 'protected_titles',
			array( 'pt_namespace' => $this->getNamespace(), 'pt_title' => $this->getDBkey() ), 
			__METHOD__ );
	}

	/**
	 * Would anybody with sufficient privileges be able to move this page?
	 * Some pages just aren't movable.
	 *
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function isMovable() {
		return MWNamespace::isMovable( $this->getNamespace() ) && $this->getInterwiki() == '';
	}

	/**
	 * Can $wgUser read this page?
	 * @return \type{\bool} TRUE or FALSE
	 * @todo fold these checks into userCan()
	 */
	public function userCanRead() {
		global $wgUser, $wgGroupPermissions;

		$result = null;
		wfRunHooks( 'userCan', array( &$this, &$wgUser, 'read', &$result ) );
		if ( $result !== null ) {
			return $result;
		}

		if( $wgUser->isAllowed( 'read' ) ) {
			return true;
		} else {
			global $wgWhitelistRead;

			/**
			 * Always grant access to the login page.
			 * Even anons need to be able to log in.
			*/
			if( $this->isSpecial( 'Userlogin' ) || $this->isSpecial( 'Resetpass' ) ) {
				return true;
			}

			/**
			 * Bail out if there isn't whitelist
			 */
			if( !is_array($wgWhitelistRead) ) {
				return false;
			}

			/**
			 * Check for explicit whitelisting
			 */
			$name = $this->getPrefixedText();
			$dbName = $this->getPrefixedDBKey();
			// Check with and without underscores
			if( in_array($name,$wgWhitelistRead,true) || in_array($dbName,$wgWhitelistRead,true) )
				return true;

			/**
			 * Old settings might have the title prefixed with
			 * a colon for main-namespace pages
			 */
			if( $this->getNamespace() == NS_MAIN ) {
				if( in_array( ':' . $name, $wgWhitelistRead ) )
					return true;
			}

			/**
			 * If it's a special page, ditch the subpage bit
			 * and check again
			 */
			if( $this->getNamespace() == NS_SPECIAL ) {
				$name = $this->getDBkey();
				list( $name, /* $subpage */) = SpecialPage::resolveAliasWithSubpage( $name );
				if ( $name === false ) {
					# Invalid special page, but we show standard login required message
					return false;
				}

				$pure = SpecialPage::getTitleFor( $name )->getPrefixedText();
				if( in_array( $pure, $wgWhitelistRead, true ) )
					return true;
			}

		}
		return false;
	}

	/**
	 * Is this a talk page of some sort?
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function isTalkPage() {
		return MWNamespace::isTalk( $this->getNamespace() );
	}

	/**
	 * Is this a subpage?
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function isSubpage() {
		return MWNamespace::hasSubpages( $this->mNamespace )
			? strpos( $this->getText(), '/' ) !== false
			: false;
	}

	/**
	 * Does this have subpages?  (Warning, usually requires an extra DB query.)
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function hasSubpages() {
		if( !MWNamespace::hasSubpages( $this->mNamespace ) ) {
			# Duh
			return false;
		}

		# We dynamically add a member variable for the purpose of this method
		# alone to cache the result.  There's no point in having it hanging
		# around uninitialized in every Title object; therefore we only add it
		# if needed and don't declare it statically.
		if( isset( $this->mHasSubpages ) ) {
			return $this->mHasSubpages;
		}

		$subpages = $this->getSubpages( 1 );
		if( $subpages instanceof TitleArray )
			return $this->mHasSubpages = (bool)$subpages->count();
		return $this->mHasSubpages = false;
	}
	
	/**
	 * Get all subpages of this page.
	 * @param $limit Maximum number of subpages to fetch; -1 for no limit
	 * @return mixed TitleArray, or empty array if this page's namespace
	 *  doesn't allow subpages
	 */
	public function getSubpages( $limit = -1 ) {
		if( !MWNamespace::hasSubpages( $this->getNamespace() ) )
			return array();

		$dbr = wfGetDB( DB_SLAVE );
		$conds['page_namespace'] = $this->getNamespace();
		$conds[] = 'page_title LIKE ' . $dbr->addQuotes(
				$dbr->escapeLike( $this->getDBkey() ) . '/%' );
		$options = array();
		if( $limit > -1 )
			$options['LIMIT'] = $limit;
		return $this->mSubpages = TitleArray::newFromResult(
			$dbr->select( 'page',
				array( 'page_id', 'page_namespace', 'page_title', 'page_is_redirect' ),
				$conds,
				__METHOD__,
				$options
			)
		);
	}

	/**
	 * Could this page contain custom CSS or JavaScript, based
	 * on the title?
	 *
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function isCssOrJsPage() {
		return $this->mNamespace == NS_MEDIAWIKI
			&& preg_match( '!\.(?:css|js)$!u', $this->mTextform ) > 0;
	}

	/**
	 * Is this a .css or .js subpage of a user page?
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function isCssJsSubpage() {
		return ( NS_USER == $this->mNamespace and preg_match("/\\/.*\\.(?:css|js)$/", $this->mTextform ) );
	}
	/**
	 * Is this a *valid* .css or .js subpage of a user page?
	 * Check that the corresponding skin exists
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function isValidCssJsSubpage() {
		if ( $this->isCssJsSubpage() ) {
			$skinNames = Skin::getSkinNames();
			return array_key_exists( $this->getSkinFromCssJsSubpage(), $skinNames );
		} else {
			return false;
		}
	}
	/**
	 * Trim down a .css or .js subpage title to get the corresponding skin name
	 */
	public function getSkinFromCssJsSubpage() {
		$subpage = explode( '/', $this->mTextform );
		$subpage = $subpage[ count( $subpage ) - 1 ];
		return( str_replace( array( '.css', '.js' ), array( '', '' ), $subpage ) );
	}
	/**
	 * Is this a .css subpage of a user page?
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function isCssSubpage() {
		return ( NS_USER == $this->mNamespace && preg_match("/\\/.*\\.css$/", $this->mTextform ) );
	}
	/**
	 * Is this a .js subpage of a user page?
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function isJsSubpage() {
		return ( NS_USER == $this->mNamespace && preg_match("/\\/.*\\.js$/", $this->mTextform ) );
	}
	/**
	 * Protect css/js subpages of user pages: can $wgUser edit
	 * this page?
	 *
	 * @return \type{\bool} TRUE or FALSE
	 * @todo XXX: this might be better using restrictions
	 */
	public function userCanEditCssJsSubpage() {
		global $wgUser;
		return ( $wgUser->isAllowed('editusercssjs') || preg_match('/^'.preg_quote($wgUser->getName(), '/').'\//', $this->mTextform) );
	}

	/**
	 * Cascading protection: Return true if cascading restrictions apply to this page, false if not.
	 *
	 * @return \type{\bool} If the page is subject to cascading restrictions.
	 */
	public function isCascadeProtected() {
		list( $sources, /* $restrictions */ ) = $this->getCascadeProtectionSources( false );
		return ( $sources > 0 );
	}

	/**
	 * Cascading protection: Get the source of any cascading restrictions on this page.
	 *
	 * @param $get_pages \type{\bool} Whether or not to retrieve the actual pages that the restrictions have come from.
	 * @return \type{\arrayof{mixed title array, restriction array}} Array of the Title objects of the pages from 
	 *         which cascading restrictions have come, false for none, or true if such restrictions exist, but $get_pages was not set.
	 *         The restriction array is an array of each type, each of which contains an array of unique groups.
	 */
	public function getCascadeProtectionSources( $get_pages = true ) {
		global $wgRestrictionTypes;

		# Define our dimension of restrictions types
		$pagerestrictions = array();
		foreach( $wgRestrictionTypes as $action )
			$pagerestrictions[$action] = array();

		if ( isset( $this->mCascadeSources ) && $get_pages ) {
			return array( $this->mCascadeSources, $this->mCascadingRestrictions );
		} else if ( isset( $this->mHasCascadingRestrictions ) && !$get_pages ) {
			return array( $this->mHasCascadingRestrictions, $pagerestrictions );
		}

		wfProfileIn( __METHOD__ );

		$dbr = wfGetDB( DB_SLAVE );

		if ( $this->getNamespace() == NS_FILE ) {
			$tables = array ('imagelinks', 'page_restrictions');
			$where_clauses = array(
				'il_to' => $this->getDBkey(),
				'il_from=pr_page',
				'pr_cascade' => 1 );
		} else {
			$tables = array ('templatelinks', 'page_restrictions');
			$where_clauses = array(
				'tl_namespace' => $this->getNamespace(),
				'tl_title' => $this->getDBkey(),
				'tl_from=pr_page',
				'pr_cascade' => 1 );
		}

		if ( $get_pages ) {
			$cols = array('pr_page', 'page_namespace', 'page_title', 'pr_expiry', 'pr_type', 'pr_level' );
			$where_clauses[] = 'page_id=pr_page';
			$tables[] = 'page';
		} else {
			$cols = array( 'pr_expiry' );
		}

		$res = $dbr->select( $tables, $cols, $where_clauses, __METHOD__ );

		$sources = $get_pages ? array() : false;
		$now = wfTimestampNow();
		$purgeExpired = false;

		foreach( $res as $row ) {
			$expiry = Block::decodeExpiry( $row->pr_expiry );
			if( $expiry > $now ) {
				if ($get_pages) {
					$page_id = $row->pr_page;
					$page_ns = $row->page_namespace;
					$page_title = $row->page_title;
					$sources[$page_id] = Title::makeTitle($page_ns, $page_title);
					# Add groups needed for each restriction type if its not already there
					# Make sure this restriction type still exists
					if ( isset($pagerestrictions[$row->pr_type]) && !in_array($row->pr_level, $pagerestrictions[$row->pr_type]) ) {
						$pagerestrictions[$row->pr_type][]=$row->pr_level;
					}
				} else {
					$sources = true;
				}
			} else {
				// Trigger lazy purge of expired restrictions from the db
				$purgeExpired = true;
			}
		}
		if( $purgeExpired ) {
			Title::purgeExpiredRestrictions();
		}

		wfProfileOut( __METHOD__ );

		if ( $get_pages ) {
			$this->mCascadeSources = $sources;
			$this->mCascadingRestrictions = $pagerestrictions;
		} else {
			$this->mHasCascadingRestrictions = $sources;
		}
		return array( $sources, $pagerestrictions );
	}

	function areRestrictionsCascading() {
		if (!$this->mRestrictionsLoaded) {
			$this->loadRestrictions();
		}

		return $this->mCascadeRestriction;
	}

	/**
	 * Loads a string into mRestrictions array
	 * @param $res \type{Resource} restrictions as an SQL result.
	 */
	private function loadRestrictionsFromRow( $res, $oldFashionedRestrictions = NULL ) {
		global $wgRestrictionTypes;
		$dbr = wfGetDB( DB_SLAVE );

		foreach( $wgRestrictionTypes as $type ){
			$this->mRestrictions[$type] = array();
			$this->mRestrictionsExpiry[$type] = Block::decodeExpiry('');
		}

		$this->mCascadeRestriction = false;

		# Backwards-compatibility: also load the restrictions from the page record (old format).

		if ( $oldFashionedRestrictions === NULL ) {
			$oldFashionedRestrictions = $dbr->selectField( 'page', 'page_restrictions', 
				array( 'page_id' => $this->getArticleId() ), __METHOD__ );
		}

		if ($oldFashionedRestrictions != '') {

			foreach( explode( ':', trim( $oldFashionedRestrictions ) ) as $restrict ) {
				$temp = explode( '=', trim( $restrict ) );
				if(count($temp) == 1) {
					// old old format should be treated as edit/move restriction
					$this->mRestrictions['edit'] = explode( ',', trim( $temp[0] ) );
					$this->mRestrictions['move'] = explode( ',', trim( $temp[0] ) );
				} else {
					$this->mRestrictions[$temp[0]] = explode( ',', trim( $temp[1] ) );
				}
			}

			$this->mOldRestrictions = true;

		}

		if( $dbr->numRows( $res ) ) {
			# Current system - load second to make them override.
			$now = wfTimestampNow();
			$purgeExpired = false;

			foreach( $res as $row ) {
				# Cycle through all the restrictions.

				// Don't take care of restrictions types that aren't in $wgRestrictionTypes
				if( !in_array( $row->pr_type, $wgRestrictionTypes ) )
					continue;

				// This code should be refactored, now that it's being used more generally,
				// But I don't really see any harm in leaving it in Block for now -werdna
				$expiry = Block::decodeExpiry( $row->pr_expiry );

				// Only apply the restrictions if they haven't expired!
				if ( !$expiry || $expiry > $now ) {
					$this->mRestrictionsExpiry[$row->pr_type] = $expiry;
					$this->mRestrictions[$row->pr_type] = explode( ',', trim( $row->pr_level ) );

					$this->mCascadeRestriction |= $row->pr_cascade;
				} else {
					// Trigger a lazy purge of expired restrictions
					$purgeExpired = true;
				}
			}

			if( $purgeExpired ) {
				Title::purgeExpiredRestrictions();
			}
		}

		$this->mRestrictionsLoaded = true;
	}

	/**
	 * Load restrictions from the page_restrictions table
	 */
	public function loadRestrictions( $oldFashionedRestrictions = NULL ) {
		if( !$this->mRestrictionsLoaded ) {
			if ($this->exists()) {
				$dbr = wfGetDB( DB_SLAVE );

				$res = $dbr->select( 'page_restrictions', '*',
					array ( 'pr_page' => $this->getArticleId() ), __METHOD__ );

				$this->loadRestrictionsFromRow( $res, $oldFashionedRestrictions );
			} else {
				$title_protection = $this->getTitleProtection();

				if (is_array($title_protection)) {
					extract($title_protection);

					$now = wfTimestampNow();
					$expiry = Block::decodeExpiry($pt_expiry);

					if (!$expiry || $expiry > $now) {
						// Apply the restrictions
						$this->mRestrictionsExpiry['create'] = $expiry;
						$this->mRestrictions['create'] = explode(',', trim($pt_create_perm) );
					} else { // Get rid of the old restrictions
						Title::purgeExpiredRestrictions();
					}
				} else {
					$this->mRestrictionsExpiry['create'] = Block::decodeExpiry('');
				}
				$this->mRestrictionsLoaded = true;
			}
		}
	}

	/**
	 * Purge expired restrictions from the page_restrictions table
	 */
	static function purgeExpiredRestrictions() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'page_restrictions',
			array( 'pr_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ),
			__METHOD__ );

		$dbw->delete( 'protected_titles',
			array( 'pt_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ),
			__METHOD__ );
	}

	/**
	 * Accessor/initialisation for mRestrictions
	 *
	 * @param $action \type{\string} action that permission needs to be checked for
	 * @return \type{\arrayof{\string}} the array of groups allowed to edit this article
	 */
	public function getRestrictions( $action ) {
		if( !$this->mRestrictionsLoaded ) {
			$this->loadRestrictions();
		}
		return isset( $this->mRestrictions[$action] )
				? $this->mRestrictions[$action]
				: array();
	}

	/**
	 * Get the expiry time for the restriction against a given action
	 * @return 14-char timestamp, or 'infinity' if the page is protected forever 
	 * or not protected at all, or false if the action is not recognised.
	 */
	public function getRestrictionExpiry( $action ) {
		if( !$this->mRestrictionsLoaded ) {
			$this->loadRestrictions();
		}
		return isset( $this->mRestrictionsExpiry[$action] ) ? $this->mRestrictionsExpiry[$action] : false;
	}

	/**
	 * Is there a version of this page in the deletion archive?
	 * @return \type{\int} the number of archived revisions
	 */
	public function isDeleted() {
		if( $this->getNamespace() < 0 ) {
			$n = 0;
		} else {
			$dbr = wfGetDB( DB_SLAVE );
			$n = $dbr->selectField( 'archive', 'COUNT(*)', 
				array( 'ar_namespace' => $this->getNamespace(), 'ar_title' => $this->getDBkey() ),
				__METHOD__
			);
			if( $this->getNamespace() == NS_FILE ) {
				$n += $dbr->selectField( 'filearchive', 'COUNT(*)',
					array( 'fa_name' => $this->getDBkey() ),
					__METHOD__
				);
			}
		}
		return (int)$n;
	}
	
	/**
	 * Is there a version of this page in the deletion archive?
	 * @return bool
	 */
	public function isDeletedQuick() {
		if( $this->getNamespace() < 0 ) {
			return false;
		}
		$dbr = wfGetDB( DB_SLAVE );
		$deleted = (bool)$dbr->selectField( 'archive', '1',
			array( 'ar_namespace' => $this->getNamespace(), 'ar_title' => $this->getDBkey() ),
			__METHOD__
		);
		if( !$deleted && $this->getNamespace() == NS_FILE ) {
			$deleted = (bool)$dbr->selectField( 'filearchive', '1',
				array( 'fa_name' => $this->getDBkey() ),
				__METHOD__
			);
		}
		return $deleted;
	}

	/**
	 * Get the article ID for this Title from the link cache,
	 * adding it if necessary
	 * @param $flags \type{\int} a bit field; may be GAID_FOR_UPDATE to select
	 * 	for update
	 * @return \type{\int} the ID
	 */
	public function getArticleID( $flags = 0 ) {
		if( $this->getNamespace() < 0 ) {
			return $this->mArticleID = 0;
		}
		$linkCache = LinkCache::singleton();
		if( $flags & GAID_FOR_UPDATE ) {
			$oldUpdate = $linkCache->forUpdate( true );
			$linkCache->clearLink( $this );
			$this->mArticleID = $linkCache->addLinkObj( $this );
			$linkCache->forUpdate( $oldUpdate );
		} else {
			if( -1 == $this->mArticleID ) {
				$this->mArticleID = $linkCache->addLinkObj( $this );
			}
		}
		return $this->mArticleID;
	}

	/**
	 * Is this an article that is a redirect page?
	 * Uses link cache, adding it if necessary
	 * @param $flags \type{\int} a bit field; may be GAID_FOR_UPDATE to select for update
	 * @return \type{\bool}
	 */
	public function isRedirect( $flags = 0 ) {
		if( !is_null($this->mRedirect) )
			return $this->mRedirect;
		# Calling getArticleID() loads the field from cache as needed
		if( !$this->getArticleID($flags) ) {
			return $this->mRedirect = false;
		}
		$linkCache = LinkCache::singleton();
		$this->mRedirect = (bool)$linkCache->getGoodLinkFieldObj( $this, 'redirect' );

		return $this->mRedirect;
	}

	/**
	 * What is the length of this page?
	 * Uses link cache, adding it if necessary
	 * @param $flags \type{\int} a bit field; may be GAID_FOR_UPDATE to select for update
	 * @return \type{\bool}
	 */
	public function getLength( $flags = 0 ) {
		if( $this->mLength != -1 )
			return $this->mLength;
		# Calling getArticleID() loads the field from cache as needed
		if( !$this->getArticleID($flags) ) {
			return $this->mLength = 0;
		}
		$linkCache = LinkCache::singleton();
		$this->mLength = intval( $linkCache->getGoodLinkFieldObj( $this, 'length' ) );

		return $this->mLength;
	}

	/**
	 * What is the page_latest field for this page?
	 * @param $flags \type{\int} a bit field; may be GAID_FOR_UPDATE to select for update
	 * @return \type{\int}
	 */
	public function getLatestRevID( $flags = 0 ) {
		if( $this->mLatestID !== false )
			return $this->mLatestID;

		$db = ($flags & GAID_FOR_UPDATE) ? wfGetDB(DB_MASTER) : wfGetDB(DB_SLAVE);
		$this->mLatestID = $db->selectField( 'page', 'page_latest', $this->pageCond(), __METHOD__ );
		return $this->mLatestID;
	}

	/**
	 * This clears some fields in this object, and clears any associated
	 * keys in the "bad links" section of the link cache.
	 *
	 * - This is called from Article::insertNewArticle() to allow
	 * loading of the new page_id. It's also called from
	 * Article::doDeleteArticle()
	 *
	 * @param $newid \type{\int} the new Article ID
	 */
	public function resetArticleID( $newid ) {
		$linkCache = LinkCache::singleton();
		$linkCache->clearBadLink( $this->getPrefixedDBkey() );

		if ( $newid === false ) { $this->mArticleID = -1; }
		else { $this->mArticleID = $newid; }
		$this->mRestrictionsLoaded = false;
		$this->mRestrictions = array();
	}

	/**
	 * Updates page_touched for this page; called from LinksUpdate.php
	 * @return \type{\bool} true if the update succeded
	 */
	public function invalidateCache() {
		if( wfReadOnly() ) {
			return;
		}
		$dbw = wfGetDB( DB_MASTER );
		$success = $dbw->update( 'page',
			array( 'page_touched' => $dbw->timestamp() ), 
			$this->pageCond(), 
			__METHOD__
		);
		HTMLFileCache::clearFileCache( $this );
		return $success;
	}

	/**
	 * Prefix some arbitrary text with the namespace or interwiki prefix
	 * of this object
	 *
	 * @param $name \type{\string} the text
	 * @return \type{\string} the prefixed text
	 * @private
	 */
	/* private */ function prefix( $name ) {
		$p = '';
		if ( '' != $this->mInterwiki ) {
			$p = $this->mInterwiki . ':';
		}
		if ( 0 != $this->mNamespace ) {
			$p .= $this->getNsText() . ':';
		}
		return $p . $name;
	}

	/**
	 * Secure and split - main initialisation function for this object
	 *
	 * Assumes that mDbkeyform has been set, and is urldecoded
	 * and uses underscores, but not otherwise munged.  This function
	 * removes illegal characters, splits off the interwiki and
	 * namespace prefixes, sets the other forms, and canonicalizes
	 * everything.
	 * @return \type{\bool} true on success
	 */
	private function secureAndSplit() {
		global $wgContLang, $wgLocalInterwiki, $wgCapitalLinks;

		# Initialisation
		static $rxTc = false;
		if( !$rxTc ) {
			# Matching titles will be held as illegal.
			$rxTc = '/' .
				# Any character not allowed is forbidden...
				'[^' . Title::legalChars() . ']' .
				# URL percent encoding sequences interfere with the ability
				# to round-trip titles -- you can't link to them consistently.
				'|%[0-9A-Fa-f]{2}' .
				# XML/HTML character references produce similar issues.
				'|&[A-Za-z0-9\x80-\xff]+;' .
				'|&#[0-9]+;' .
				'|&#x[0-9A-Fa-f]+;' .
				'/S';
		}

		$this->mInterwiki = $this->mFragment = '';
		$this->mNamespace = $this->mDefaultNamespace; # Usually NS_MAIN

		$dbkey = $this->mDbkeyform;

		# Strip Unicode bidi override characters.
		# Sometimes they slip into cut-n-pasted page titles, where the
		# override chars get included in list displays.
		$dbkey = preg_replace( '/\xE2\x80[\x8E\x8F\xAA-\xAE]/S', '', $dbkey );

		# Clean up whitespace
		#
		$dbkey = preg_replace( '/[ _]+/', '_', $dbkey );
		$dbkey = trim( $dbkey, '_' );

		if ( '' == $dbkey ) {
			return false;
		}

		if( false !== strpos( $dbkey, UTF8_REPLACEMENT ) ) {
			# Contained illegal UTF-8 sequences or forbidden Unicode chars.
			return false;
		}

		$this->mDbkeyform = $dbkey;

		# Initial colon indicates main namespace rather than specified default
		# but should not create invalid {ns,title} pairs such as {0,Project:Foo}
		if ( ':' == $dbkey{0} ) {
			$this->mNamespace = NS_MAIN;
			$dbkey = substr( $dbkey, 1 ); # remove the colon but continue processing
			$dbkey = trim( $dbkey, '_' ); # remove any subsequent whitespace
		}

		# Namespace or interwiki prefix
		$firstPass = true;
		$prefixRegexp = "/^(.+?)_*:_*(.*)$/S";
		do {
			$m = array();
			if ( preg_match( $prefixRegexp, $dbkey, $m ) ) {
				$p = $m[1];
				if ( $ns = $wgContLang->getNsIndex( $p ) ) {
					# Ordinary namespace
					$dbkey = $m[2];
					$this->mNamespace = $ns;
					# For Talk:X pages, check if X has a "namespace" prefix
					if( $ns == NS_TALK && preg_match( $prefixRegexp, $dbkey, $x ) ) {
						if( $wgContLang->getNsIndex( $x[1] ) )
							return false; # Disallow Talk:File:x type titles...
						else if( Interwiki::isValidInterwiki( $x[1] ) )
							return false; # Disallow Talk:Interwiki:x type titles...
					}
				} elseif( Interwiki::isValidInterwiki( $p ) ) {
					if( !$firstPass ) {
						# Can't make a local interwiki link to an interwiki link.
						# That's just crazy!
						return false;
					}

					# Interwiki link
					$dbkey = $m[2];
					$this->mInterwiki = $wgContLang->lc( $p );

					# Redundant interwiki prefix to the local wiki
					if ( 0 == strcasecmp( $this->mInterwiki, $wgLocalInterwiki ) ) {
						if( $dbkey == '' ) {
							# Can't have an empty self-link
							return false;
						}
						$this->mInterwiki = '';
						$firstPass = false;
						# Do another namespace split...
						continue;
					}

					# If there's an initial colon after the interwiki, that also
					# resets the default namespace
					if ( $dbkey !== '' && $dbkey[0] == ':' ) {
						$this->mNamespace = NS_MAIN;
						$dbkey = substr( $dbkey, 1 );
					}
				}
				# If there's no recognized interwiki or namespace,
				# then let the colon expression be part of the title.
			}
			break;
		} while( true );

		# We already know that some pages won't be in the database!
		#
		if ( '' != $this->mInterwiki || NS_SPECIAL == $this->mNamespace ) {
			$this->mArticleID = 0;
		}
		$fragment = strstr( $dbkey, '#' );
		if ( false !== $fragment ) {
			$this->setFragment( $fragment );
			$dbkey = substr( $dbkey, 0, strlen( $dbkey ) - strlen( $fragment ) );
			# remove whitespace again: prevents "Foo_bar_#"
			# becoming "Foo_bar_"
			$dbkey = preg_replace( '/_*$/', '', $dbkey );
		}

		# Reject illegal characters.
		#
		if( preg_match( $rxTc, $dbkey ) ) {
			return false;
		}

		/**
		 * Pages with "/./" or "/../" appearing in the URLs will often be un-
		 * reachable due to the way web browsers deal with 'relative' URLs.
		 * Also, they conflict with subpage syntax.  Forbid them explicitly.
		 */
		if ( strpos( $dbkey, '.' ) !== false &&
		     ( $dbkey === '.' || $dbkey === '..' ||
		       strpos( $dbkey, './' ) === 0  ||
		       strpos( $dbkey, '../' ) === 0 ||
		       strpos( $dbkey, '/./' ) !== false ||
		       strpos( $dbkey, '/../' ) !== false  ||
		       substr( $dbkey, -2 ) == '/.' ||
		       substr( $dbkey, -3 ) == '/..' ) )
		{
			return false;
		}

		/**
		 * Magic tilde sequences? Nu-uh!
		 */
		if( strpos( $dbkey, '~~~' ) !== false ) {
			return false;
		}

		/**
		 * Limit the size of titles to 255 bytes.
		 * This is typically the size of the underlying database field.
		 * We make an exception for special pages, which don't need to be stored
		 * in the database, and may edge over 255 bytes due to subpage syntax
		 * for long titles, e.g. [[Special:Block/Long name]]
		 */
		if ( ( $this->mNamespace != NS_SPECIAL && strlen( $dbkey ) > 255 ) ||
		  strlen( $dbkey ) > 512 )
		{
			return false;
		}

		/**
		 * Normally, all wiki links are forced to have
		 * an initial capital letter so [[foo]] and [[Foo]]
		 * point to the same place.
		 *
		 * Don't force it for interwikis, since the other
		 * site might be case-sensitive.
		 */
		$this->mUserCaseDBKey = $dbkey;
		if( $wgCapitalLinks && $this->mInterwiki == '') {
			$dbkey = $wgContLang->ucfirst( $dbkey );
		}

		/**
		 * Can't make a link to a namespace alone...
		 * "empty" local links can only be self-links
		 * with a fragment identifier.
		 */
		if( $dbkey == '' &&
			$this->mInterwiki == '' &&
			$this->mNamespace != NS_MAIN ) {
			return false;
		}
		// Allow IPv6 usernames to start with '::' by canonicalizing IPv6 titles.
		// IP names are not allowed for accounts, and can only be referring to
		// edits from the IP. Given '::' abbreviations and caps/lowercaps,
		// there are numerous ways to present the same IP. Having sp:contribs scan
		// them all is silly and having some show the edits and others not is
		// inconsistent. Same for talk/userpages. Keep them normalized instead.
		$dbkey = ($this->mNamespace == NS_USER || $this->mNamespace == NS_USER_TALK) ?
			IP::sanitizeIP( $dbkey ) : $dbkey;
		// Any remaining initial :s are illegal.
		if ( $dbkey !== '' && ':' == $dbkey{0} ) {
			return false;
		}

		# Fill fields
		$this->mDbkeyform = $dbkey;
		$this->mUrlform = wfUrlencode( $dbkey );

		$this->mTextform = str_replace( '_', ' ', $dbkey );

		return true;
	}

	/**
	 * Set the fragment for this title. Removes the first character from the
	 * specified fragment before setting, so it assumes you're passing it with 
	 * an initial "#".
	 *
	 * Deprecated for public use, use Title::makeTitle() with fragment parameter.
	 * Still in active use privately.
	 *
	 * @param $fragment \type{\string} text
	 */
	public function setFragment( $fragment ) {
		$this->mFragment = str_replace( '_', ' ', substr( $fragment, 1 ) );
	}

	/**
	 * Get a Title object associated with the talk page of this article
	 * @return \type{Title} the object for the talk page
	 */
	public function getTalkPage() {
		return Title::makeTitle( MWNamespace::getTalk( $this->getNamespace() ), $this->getDBkey() );
	}

	/**
	 * Get a title object associated with the subject page of this
	 * talk page
	 *
	 * @return \type{Title} the object for the subject page
	 */
	public function getSubjectPage() {
		// Is this the same title?
		$subjectNS = MWNamespace::getSubject( $this->getNamespace() );
		if( $this->getNamespace() == $subjectNS ) {
			return $this;
		}
		return Title::makeTitle( $subjectNS, $this->getDBkey() );
	}

	/**
	 * Get an array of Title objects linking to this Title
	 * Also stores the IDs in the link cache.
	 *
	 * WARNING: do not use this function on arbitrary user-supplied titles!
	 * On heavily-used templates it will max out the memory.
	 *
	 * @param array $options may be FOR UPDATE
	 * @return \type{\arrayof{Title}} the Title objects linking here
	 */
	public function getLinksTo( $options = array(), $table = 'pagelinks', $prefix = 'pl' ) {
		$linkCache = LinkCache::singleton();

		if ( count( $options ) > 0 ) {
			$db = wfGetDB( DB_MASTER );
		} else {
			$db = wfGetDB( DB_SLAVE );
		}

		$res = $db->select( array( 'page', $table ),
			array( 'page_namespace', 'page_title', 'page_id', 'page_len', 'page_is_redirect' ),
			array(
				"{$prefix}_from=page_id",
				"{$prefix}_namespace" => $this->getNamespace(),
				"{$prefix}_title"     => $this->getDBkey() ),
			__METHOD__,
			$options );

		$retVal = array();
		if ( $db->numRows( $res ) ) {
			foreach( $res as $row ) {
				if ( $titleObj = Title::makeTitle( $row->page_namespace, $row->page_title ) ) {
					$linkCache->addGoodLinkObj( $row->page_id, $titleObj, $row->page_len, $row->page_is_redirect );
					$retVal[] = $titleObj;
				}
			}
		}
		$db->freeResult( $res );
		return $retVal;
	}

	/**
	 * Get an array of Title objects using this Title as a template
	 * Also stores the IDs in the link cache.
	 *
	 * WARNING: do not use this function on arbitrary user-supplied titles!
	 * On heavily-used templates it will max out the memory.
	 *
	 * @param array $options may be FOR UPDATE
	 * @return \type{\arrayof{Title}} the Title objects linking here
	 */
	public function getTemplateLinksTo( $options = array() ) {
		return $this->getLinksTo( $options, 'templatelinks', 'tl' );
	}

	/**
	 * Get an array of Title objects referring to non-existent articles linked from this page
	 *
	 * @todo check if needed (used only in SpecialBrokenRedirects.php, and should use redirect table in this case)
	 * @return \type{\arrayof{Title}} the Title objects
	 */
	public function getBrokenLinksFrom() {
		if ( $this->getArticleId() == 0 ) {
			# All links from article ID 0 are false positives
			return array();
		}

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
			array( 'page', 'pagelinks' ),
			array( 'pl_namespace', 'pl_title' ),
			array(
				'pl_from' => $this->getArticleId(),
				'page_namespace IS NULL'
			),
			__METHOD__, array(),
			array(
				'page' => array( 
					'LEFT JOIN', 
					array( 'pl_namespace=page_namespace', 'pl_title=page_title' )
				)
			)
		);

		$retVal = array();
		foreach( $res as $row ) {
			$retVal[] = Title::makeTitle( $row->pl_namespace, $row->pl_title );
		}
		return $retVal;
	}


	/**
	 * Get a list of URLs to purge from the Squid cache when this
	 * page changes
	 *
	 * @return \type{\arrayof{\string}} the URLs
	 */
	public function getSquidURLs() {
		global $wgContLang;

		$urls = array(
			$this->getInternalURL(),
			$this->getInternalURL( 'action=history' )
		);

		// purge variant urls as well
		if($wgContLang->hasVariants()){
			$variants = $wgContLang->getVariants();
			foreach($variants as $vCode){
				if($vCode==$wgContLang->getCode()) continue; // we don't want default variant
				$urls[] = $this->getInternalURL('',$vCode);
			}
		}

		return $urls;
	}

	/**
	 * Purge all applicable Squid URLs
	 */
	public function purgeSquid() {
		global $wgUseSquid;
		if ( $wgUseSquid ) {
			$urls = $this->getSquidURLs();
			$u = new SquidUpdate( $urls );
			$u->doUpdate();
		}
	}

	/**
	 * Move this page without authentication
	 * @param &$nt \type{Title} the new page Title
	 */
	public function moveNoAuth( &$nt ) {
		return $this->moveTo( $nt, false );
	}

	/**
	 * Check whether a given move operation would be valid.
	 * Returns true if ok, or a getUserPermissionsErrors()-like array otherwise
	 * @param &$nt \type{Title} the new title
	 * @param $auth \type{\bool} indicates whether $wgUser's permissions
	 * 	should be checked
	 * @param $reason \type{\string} is the log summary of the move, used for spam checking
	 * @return \type{\mixed} True on success, getUserPermissionsErrors()-like array on failure
	 */
	public function isValidMoveOperation( &$nt, $auth = true, $reason = '' ) {
		global $wgUser;

		$errors = array();	
		if( !$nt ) {
			// Normally we'd add this to $errors, but we'll get
			// lots of syntax errors if $nt is not an object
			return array(array('badtitletext'));
		}
		if( $this->equals( $nt ) ) {
			$errors[] = array('selfmove');
		}
		if( !$this->isMovable() ) {
			$errors[] = array( 'immobile-source-namespace', $this->getNsText() );
		}
		if ( $nt->getInterwiki() != '' ) {
			$errors[] = array( 'immobile-target-namespace-iw' );
		}
		if ( !$nt->isMovable() ) {
			$errors[] = array('immobile-target-namespace', $nt->getNsText() );
		}

		$oldid = $this->getArticleID();
		$newid = $nt->getArticleID();

		if ( strlen( $nt->getDBkey() ) < 1 ) {
			$errors[] = array('articleexists');
		}
		if ( ( '' == $this->getDBkey() ) ||
			 ( !$oldid ) ||
		     ( '' == $nt->getDBkey() ) ) {
			$errors[] = array('badarticleerror');
		}

		// Image-specific checks
		if( $this->getNamespace() == NS_FILE ) {
			$file = wfLocalFile( $this );
			if( $file->exists() ) {
				if( $nt->getNamespace() != NS_FILE ) {
					$errors[] = array('imagenocrossnamespace');
				}
				if( $nt->getText() != wfStripIllegalFilenameChars( $nt->getText() ) ) {
					$errors[] = array('imageinvalidfilename');
				}
				if( !File::checkExtensionCompatibility( $file, $nt->getDBkey() ) ) {
					$errors[] = array('imagetypemismatch');
				}
			}
		}

		if ( $auth ) {
			$errors = wfMergeErrorArrays( $errors,
				$this->getUserPermissionsErrors('move', $wgUser),
				$this->getUserPermissionsErrors('edit', $wgUser),
				$nt->getUserPermissionsErrors('move-target', $wgUser),
				$nt->getUserPermissionsErrors('edit', $wgUser) );
		}

		$match = EditPage::matchSummarySpamRegex( $reason );
		if( $match !== false ) {
			// This is kind of lame, won't display nice
			$errors[] = array('spamprotectiontext');
		}
		
		$err = null;
		if( !wfRunHooks( 'AbortMove', array( $this, $nt, $wgUser, &$err, $reason ) ) ) {
			$errors[] = array('hookaborted', $err);
		}

		# The move is allowed only if (1) the target doesn't exist, or
		# (2) the target is a redirect to the source, and has no history
		# (so we can undo bad moves right after they're done).

		if ( 0 != $newid ) { # Target exists; check for validity
			if ( ! $this->isValidMoveTarget( $nt ) ) {
				$errors[] = array('articleexists');
			}
		} else {
			$tp = $nt->getTitleProtection();
			$right = ( $tp['pt_create_perm'] == 'sysop' ) ? 'protect' : $tp['pt_create_perm'];
			if ( $tp and !$wgUser->isAllowed( $right ) ) {
				$errors[] = array('cantmove-titleprotected');
			}
		}
		if(empty($errors))
			return true;
		return $errors;
	}

	/**
	 * Move a title to a new location
	 * @param &$nt \type{Title} the new title
	 * @param $auth \type{\bool} indicates whether $wgUser's permissions
	 * 	should be checked
	 * @param $reason \type{\string} The reason for the move
	 * @param $createRedirect \type{\bool} Whether to create a redirect from the old title to the new title.
	 *  Ignored if the user doesn't have the suppressredirect right.
	 * @return \type{\mixed} true on success, getUserPermissionsErrors()-like array on failure
	 */
	public function moveTo( &$nt, $auth = true, $reason = '', $createRedirect = true ) {
		$err = $this->isValidMoveOperation( $nt, $auth, $reason );
		if( is_array( $err ) ) {
			return $err;
		}

		// If it is a file, more it first. It is done before all other moving stuff is done because it's hard to revert
		$dbw = wfGetDB( DB_MASTER );
		if( $this->getNamespace() == NS_FILE ) {
			$file = wfLocalFile( $this );
			if( $file->exists() ) {
				$status = $file->move( $nt );
				if( !$status->isOk() ) {
					return $status->getErrorsArray();
				}
			}
		}

		$pageid = $this->getArticleID();
		$protected = $this->isProtected();
		if( $nt->exists() ) {
			$err = $this->moveOverExistingRedirect( $nt, $reason, $createRedirect );
			$pageCountChange = ($createRedirect ? 0 : -1);
		} else { # Target didn't exist, do normal move.
			$err = $this->moveToNewTitle( $nt, $reason, $createRedirect );
			$pageCountChange = ($createRedirect ? 1 : 0);
		}

		if( is_array( $err ) ) {
			return $err;
		}
		$redirid = $this->getArticleID();

		// Category memberships include a sort key which may be customized.
		// If it's left as the default (the page title), we need to update
		// the sort key to match the new title.
		//
		// Be careful to avoid resetting cl_timestamp, which may disturb
		// time-based lists on some sites.
		//
		// Warning -- if the sort key is *explicitly* set to the old title,
		// we can't actually distinguish it from a default here, and it'll
		// be set to the new title even though it really shouldn't.
		// It'll get corrected on the next edit, but resetting cl_timestamp.
		$dbw->update( 'categorylinks',
			array(
				'cl_sortkey' => $nt->getPrefixedText(),
				'cl_timestamp=cl_timestamp' ),
			array(
				'cl_from' => $pageid,
				'cl_sortkey' => $this->getPrefixedText() ),
			__METHOD__ );

		if( $protected ) {
			# Protect the redirect title as the title used to be...
			$dbw->insertSelect( 'page_restrictions', 'page_restrictions',
				array( 
					'pr_page'    => $redirid,
					'pr_type'    => 'pr_type',
					'pr_level'   => 'pr_level',
					'pr_cascade' => 'pr_cascade',
					'pr_user'    => 'pr_user',
					'pr_expiry'  => 'pr_expiry'
				),
				array( 'pr_page' => $pageid ),
				__METHOD__,
				array( 'IGNORE' )
			);
			# Update the protection log
			$log = new LogPage( 'protect' );
			$comment = wfMsgForContent( 'prot_1movedto2', $this->getPrefixedText(), $nt->getPrefixedText() );
			if( $reason ) $comment .= wfMsgForContent( 'colon-separator' ) . $reason;
			$log->addEntry( 'move_prot', $nt, $comment, array($this->getPrefixedText()) ); // FIXME: $params?
		}

		# Update watchlists
		$oldnamespace = $this->getNamespace() & ~1;
		$newnamespace = $nt->getNamespace() & ~1;
		$oldtitle = $this->getDBkey();
		$newtitle = $nt->getDBkey();

		if( $oldnamespace != $newnamespace || $oldtitle != $newtitle ) {
			WatchedItem::duplicateEntries( $this, $nt );
		}

		# Update search engine
		$u = new SearchUpdate( $pageid, $nt->getPrefixedDBkey() );
		$u->doUpdate();
		$u = new SearchUpdate( $redirid, $this->getPrefixedDBkey(), '' );
		$u->doUpdate();

		# Update site_stats
		if( $this->isContentPage() && !$nt->isContentPage() ) {
			# No longer a content page
			# Not viewed, edited, removing
			$u = new SiteStatsUpdate( 0, 1, -1, $pageCountChange );
		} elseif( !$this->isContentPage() && $nt->isContentPage() ) {
			# Now a content page
			# Not viewed, edited, adding
			$u = new SiteStatsUpdate( 0, 1, +1, $pageCountChange );
		} elseif( $pageCountChange ) {
			# Redirect added
			$u = new SiteStatsUpdate( 0, 0, 0, 1 );
		} else {
			# Nothing special
			$u = false;
		}
		if( $u )
			$u->doUpdate();
		# Update message cache for interface messages
		if( $nt->getNamespace() == NS_MEDIAWIKI ) {
			global $wgMessageCache;

			# @bug 17860: old article can be deleted, if this the case,
			# delete it from message cache
			if ( $this->getArticleID() === 0 ) {
				$wgMessageCache->replace( $this->getDBkey(), false );
			} else {
				$oldarticle = new Article( $this );
				$wgMessageCache->replace( $this->getDBkey(), $oldarticle->getContent() );
			}

			$newarticle = new Article( $nt );
			$wgMessageCache->replace( $nt->getDBkey(), $newarticle->getContent() );
		}

		global $wgUser;
		wfRunHooks( 'TitleMoveComplete', array( &$this, &$nt, &$wgUser, $pageid, $redirid ) );
		return true;
	}

	/**
	 * Move page to a title which is at present a redirect to the
	 * source page
	 *
	 * @param &$nt \type{Title} the page to move to, which should currently
	 * 	be a redirect
	 * @param $reason \type{\string} The reason for the move
	 * @param $createRedirect \type{\bool} Whether to leave a redirect at the old title.
	 *  Ignored if the user doesn't have the suppressredirect right
	 */
	private function moveOverExistingRedirect( &$nt, $reason = '', $createRedirect = true ) {
		global $wgUseSquid, $wgUser;
		$fname = 'Title::moveOverExistingRedirect';
		$comment = wfMsgForContent( '1movedto2_redir', $this->getPrefixedText(), $nt->getPrefixedText() );

		if ( $reason ) {
			$comment .= ": $reason";
		}

		$now = wfTimestampNow();
		$newid = $nt->getArticleID();
		$oldid = $this->getArticleID();
		$latest = $this->getLatestRevID();

		$dbw = wfGetDB( DB_MASTER );

		# Delete the old redirect. We don't save it to history since
		# by definition if we've got here it's rather uninteresting.
		# We have to remove it so that the next step doesn't trigger
		# a conflict on the unique namespace+title index...
		$dbw->delete( 'page', array( 'page_id' => $newid ), $fname );
		if ( !$dbw->cascadingDeletes() ) {
			$dbw->delete( 'revision', array( 'rev_page' => $newid ), __METHOD__ );
			global $wgUseTrackbacks;
			if ($wgUseTrackbacks)
				$dbw->delete( 'trackbacks', array( 'tb_page' => $newid ), __METHOD__ );
			$dbw->delete( 'pagelinks', array( 'pl_from' => $newid ), __METHOD__ );
			$dbw->delete( 'imagelinks', array( 'il_from' => $newid ), __METHOD__ );
			$dbw->delete( 'categorylinks', array( 'cl_from' => $newid ), __METHOD__ );
			$dbw->delete( 'templatelinks', array( 'tl_from' => $newid ), __METHOD__ );
			$dbw->delete( 'externallinks', array( 'el_from' => $newid ), __METHOD__ );
			$dbw->delete( 'langlinks', array( 'll_from' => $newid ), __METHOD__ );
			$dbw->delete( 'redirect', array( 'rd_from' => $newid ), __METHOD__ );
		}

		# Save a null revision in the page's history notifying of the move
		$nullRevision = Revision::newNullRevision( $dbw, $oldid, $comment, true );
		$nullRevId = $nullRevision->insertOn( $dbw );
		
		$article = new Article( $this );
		wfRunHooks( 'NewRevisionFromEditComplete', array($article, $nullRevision, $latest, $wgUser) );

		# Change the name of the target page:
		$dbw->update( 'page',
			/* SET */ array(
				'page_touched'   => $dbw->timestamp($now),
				'page_namespace' => $nt->getNamespace(),
				'page_title'     => $nt->getDBkey(),
				'page_latest'    => $nullRevId,
			),
			/* WHERE */ array( 'page_id' => $oldid ),
			$fname
		);
		$nt->resetArticleID( $oldid );

		# Recreate the redirect, this time in the other direction.
		if( $createRedirect || !$wgUser->isAllowed('suppressredirect') ) {
			$mwRedir = MagicWord::get( 'redirect' );
			$redirectText = $mwRedir->getSynonym( 0 ) . ' [[' . $nt->getPrefixedText() . "]]\n";
			$redirectArticle = new Article( $this );
			$newid = $redirectArticle->insertOn( $dbw );
			$redirectRevision = new Revision( array(
				'page'    => $newid,
				'comment' => $comment,
				'text'    => $redirectText ) );
			$redirectRevision->insertOn( $dbw );
			$redirectArticle->updateRevisionOn( $dbw, $redirectRevision, 0 );
			
			wfRunHooks( 'NewRevisionFromEditComplete', array($redirectArticle, $redirectRevision, false, $wgUser) );

			# Now, we record the link from the redirect to the new title.
			# It should have no other outgoing links...
			$dbw->delete( 'pagelinks', array( 'pl_from' => $newid ), $fname );
			$dbw->insert( 'pagelinks',
				array(
					'pl_from'      => $newid,
					'pl_namespace' => $nt->getNamespace(),
					'pl_title'     => $nt->getDBkey() ),
				$fname );
			$redirectSuppressed = false;
		} else {
			$this->resetArticleID( 0 );
			$redirectSuppressed = true;
		}

		# Log the move
		$log = new LogPage( 'move' );
		$log->addEntry( 'move_redir', $this, $reason, array( 1 => $nt->getPrefixedText(), 2 => $redirectSuppressed ) );

		# Purge squid
		if ( $wgUseSquid ) {
			$urls = array_merge( $nt->getSquidURLs(), $this->getSquidURLs() );
			$u = new SquidUpdate( $urls );
			$u->doUpdate();
		}
		
	}

	/**
	 * Move page to non-existing title.
	 * @param &$nt \type{Title} the new Title
	 * @param $reason \type{\string} The reason for the move
	 * @param $createRedirect \type{\bool} Whether to create a redirect from the old title to the new title
	 *  Ignored if the user doesn't have the suppressredirect right
	 */
	private function moveToNewTitle( &$nt, $reason = '', $createRedirect = true ) {
		global $wgUseSquid, $wgUser;
		$fname = 'MovePageForm::moveToNewTitle';
		$comment = wfMsgForContent( '1movedto2', $this->getPrefixedText(), $nt->getPrefixedText() );
		if ( $reason ) {
			$comment .= wfMsgExt( 'colon-separator',
				array( 'escapenoentities', 'content' ) );
			$comment .= $reason;
		}

		$newid = $nt->getArticleID();
		$oldid = $this->getArticleID();
		$latest = $this->getLatestRevId();
		
		$dbw = wfGetDB( DB_MASTER );
		$now = $dbw->timestamp();

		# Save a null revision in the page's history notifying of the move
		$nullRevision = Revision::newNullRevision( $dbw, $oldid, $comment, true );
		$nullRevId = $nullRevision->insertOn( $dbw );
		
		$article = new Article( $this );
		wfRunHooks( 'NewRevisionFromEditComplete', array($article, $nullRevision, $latest, $wgUser) );

		# Rename page entry
		$dbw->update( 'page',
			/* SET */ array(
				'page_touched'   => $now,
				'page_namespace' => $nt->getNamespace(),
				'page_title'     => $nt->getDBkey(),
				'page_latest'    => $nullRevId,
			),
			/* WHERE */ array( 'page_id' => $oldid ),
			$fname
		);
		$nt->resetArticleID( $oldid );

		if( $createRedirect || !$wgUser->isAllowed('suppressredirect') ) {
			# Insert redirect
			$mwRedir = MagicWord::get( 'redirect' );
			$redirectText = $mwRedir->getSynonym( 0 ) . ' [[' . $nt->getPrefixedText() . "]]\n";
			$redirectArticle = new Article( $this );
			$newid = $redirectArticle->insertOn( $dbw );
			$redirectRevision = new Revision( array(
				'page'    => $newid,
				'comment' => $comment,
				'text'    => $redirectText ) );
			$redirectRevision->insertOn( $dbw );
			$redirectArticle->updateRevisionOn( $dbw, $redirectRevision, 0 );
			
			wfRunHooks( 'NewRevisionFromEditComplete', array($redirectArticle, $redirectRevision, false, $wgUser) );

			# Record the just-created redirect's linking to the page
			$dbw->insert( 'pagelinks',
				array(
					'pl_from'      => $newid,
					'pl_namespace' => $nt->getNamespace(),
					'pl_title'     => $nt->getDBkey() ),
				$fname );
			$redirectSuppressed = false;
		} else {
			$this->resetArticleID( 0 );
			$redirectSuppressed = true;
		}

		# Log the move
		$log = new LogPage( 'move' );
		$log->addEntry( 'move', $this, $reason, array( 1 => $nt->getPrefixedText(), 2 => $redirectSuppressed ) );

		# Purge caches as per article creation
		Article::onArticleCreate( $nt );

		# Purge old title from squid
		# The new title, and links to the new title, are purged in Article::onArticleCreate()
		$this->purgeSquid();
		
	}
	
	/**
	 * Move this page's subpages to be subpages of $nt
	 * @param $nt Title Move target
	 * @param $auth bool Whether $wgUser's permissions should be checked
	 * @param $reason string The reason for the move
	 * @param $createRedirect bool Whether to create redirects from the old subpages to the new ones
	 *  Ignored if the user doesn't have the 'suppressredirect' right
	 * @return mixed array with old page titles as keys, and strings (new page titles) or
	 *  arrays (errors) as values, or an error array with numeric indices if no pages were moved
	 */
	public function moveSubpages( $nt, $auth = true, $reason = '', $createRedirect = true ) {
		global $wgMaximumMovedPages;
		// Check permissions
		if( !$this->userCan( 'move-subpages' ) )
			return array( 'cant-move-subpages' );
		// Do the source and target namespaces support subpages?
		if( !MWNamespace::hasSubpages( $this->getNamespace() ) )
			return array( 'namespace-nosubpages',
				MWNamespace::getCanonicalName( $this->getNamespace() ) );
		if( !MWNamespace::hasSubpages( $nt->getNamespace() ) )
			return array( 'namespace-nosubpages',
				MWNamespace::getCanonicalName( $nt->getNamespace() ) );

		$subpages = $this->getSubpages($wgMaximumMovedPages + 1);
		$retval = array();
		$count = 0;
		foreach( $subpages as $oldSubpage ) {
			$count++;
			if( $count > $wgMaximumMovedPages ) {
				$retval[$oldSubpage->getPrefixedTitle()] =
						array( 'movepage-max-pages',
							$wgMaximumMovedPages );
				break;
			}

			if( $oldSubpage->getArticleId() == $this->getArticleId() )
				// When moving a page to a subpage of itself,
				// don't move it twice
				continue;
			$newPageName = preg_replace(
					'#^'.preg_quote( $this->getDBkey(), '#' ).'#',
					$nt->getDBkey(), $oldSubpage->getDBkey() );
			if( $oldSubpage->isTalkPage() ) {
				$newNs = $nt->getTalkPage()->getNamespace();
			} else {
				$newNs = $nt->getSubjectPage()->getNamespace();
			}
			# Bug 14385: we need makeTitleSafe because the new page names may
			# be longer than 255 characters.
			$newSubpage = Title::makeTitleSafe( $newNs, $newPageName );

			$success = $oldSubpage->moveTo( $newSubpage, $auth, $reason, $createRedirect );
			if( $success === true ) {
				$retval[$oldSubpage->getPrefixedText()] = $newSubpage->getPrefixedText();
			} else {
				$retval[$oldSubpage->getPrefixedText()] = $success;
			}
		}
		return $retval;
	}
	
	/**
	 * Checks if this page is just a one-rev redirect.
	 * Adds lock, so don't use just for light purposes.
	 *
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function isSingleRevRedirect() {
		$dbw = wfGetDB( DB_MASTER );
		# Is it a redirect?
		$row = $dbw->selectRow( 'page',
			array( 'page_is_redirect', 'page_latest', 'page_id' ),
			$this->pageCond(),
			__METHOD__,
			array( 'FOR UPDATE' )
		);
		# Cache some fields we may want
		$this->mArticleID = $row ? intval($row->page_id) : 0;
		$this->mRedirect = $row ? (bool)$row->page_is_redirect : false;
		$this->mLatestID = $row ? intval($row->page_latest) : false;
		if( !$this->mRedirect ) {
			return false;
		}
		# Does the article have a history?
		$row = $dbw->selectField( array( 'page', 'revision'),
			'rev_id',
			array( 'page_namespace' => $this->getNamespace(),
				'page_title' => $this->getDBkey(),
				'page_id=rev_page',
				'page_latest != rev_id'
			), 
			__METHOD__,
			array( 'FOR UPDATE' )
		);
		# Return true if there was no history
		return ($row === false);
	}

	/**
	 * Checks if $this can be moved to a given Title
	 * - Selects for update, so don't call it unless you mean business
	 *
	 * @param &$nt \type{Title} the new title to check
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function isValidMoveTarget( $nt ) {
		$dbw = wfGetDB( DB_MASTER );
		# Is it an existsing file?
		if( $nt->getNamespace() == NS_FILE ) {
			$file = wfLocalFile( $nt );
			if( $file->exists() ) {
				wfDebug( __METHOD__ . ": file exists\n" );
				return false;
			}
		}
		# Is it a redirect with no history?
		if( !$nt->isSingleRevRedirect() ) {
			wfDebug( __METHOD__ . ": not a one-rev redirect\n" );
			return false;
		}
		# Get the article text
		$rev = Revision::newFromTitle( $nt );
		$text = $rev->getText();
		# Does the redirect point to the source?
		# Or is it a broken self-redirect, usually caused by namespace collisions?
		$m = array();
		if ( preg_match( "/\\[\\[\\s*([^\\]\\|]*)]]/", $text, $m ) ) {
			$redirTitle = Title::newFromText( $m[1] );
			if( !is_object( $redirTitle ) ||
				( $redirTitle->getPrefixedDBkey() != $this->getPrefixedDBkey() &&
				$redirTitle->getPrefixedDBkey() != $nt->getPrefixedDBkey() ) ) {
				wfDebug( __METHOD__ . ": redirect points to other page\n" );
				return false;
			}
		} else {
			# Fail safe
			wfDebug( __METHOD__ . ": failsafe\n" );
			return false;
		}
		return true;
	}

	/**
	 * Can this title be added to a user's watchlist?
	 *
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function isWatchable() {
		return !$this->isExternal() && MWNamespace::isWatchable( $this->getNamespace() );
	}

	/**
	 * Get categories to which this Title belongs and return an array of
	 * categories' names.
	 *
	 * @return \type{\array} array an array of parents in the form:
	 *	$parent => $currentarticle
	 */
	public function getParentCategories() {
		global $wgContLang;

		$titlekey = $this->getArticleId();
		$dbr = wfGetDB( DB_SLAVE );
		$categorylinks = $dbr->tableName( 'categorylinks' );

		# NEW SQL
		$sql = "SELECT * FROM $categorylinks"
		     ." WHERE cl_from='$titlekey'"
			 ." AND cl_from <> '0'"
			 ." ORDER BY cl_sortkey";

		$res = $dbr->query( $sql );

		if( $dbr->numRows( $res ) > 0 ) {
			foreach( $res as $row )
				//$data[] = Title::newFromText($wgContLang->getNSText ( NS_CATEGORY ).':'.$row->cl_to);
				$data[$wgContLang->getNSText( NS_CATEGORY ).':'.$row->cl_to] = $this->getFullText();
			$dbr->freeResult( $res );
		} else {
			$data = array();
		}
		return $data;
	}

	/**
	 * Get a tree of parent categories
	 * @param $children \type{\array} an array with the children in the keys, to check for circular refs
	 * @return \type{\array} Tree of parent categories
	 */
	public function getParentCategoryTree( $children = array() ) {
	  	$stack = array();
		$parents = $this->getParentCategories();

		if( $parents ) {
			foreach( $parents as $parent => $current ) {
				if ( array_key_exists( $parent, $children ) ) {
					# Circular reference
					$stack[$parent] = array();
				} else {
					$nt = Title::newFromText($parent);
					if ( $nt ) {
						$stack[$parent] = $nt->getParentCategoryTree( $children + array($parent => 1) );
					}
				}
			}
			return $stack;
		} else {
			return array();
		}
	}


	/**
	 * Get an associative array for selecting this title from
	 * the "page" table
	 *
	 * @return \type{\array} Selection array
	 */
	public function pageCond() {
		if( $this->mArticleID > 0 ) {
			// PK avoids secondary lookups in InnoDB, shouldn't hurt other DBs
			return array( 'page_id' => $this->mArticleID );
		} else {
			return array( 'page_namespace' => $this->mNamespace, 'page_title' => $this->mDbkeyform );
		}
	}

	/**
	 * Get the revision ID of the previous revision
	 *
	 * @param $revId \type{\int} Revision ID. Get the revision that was before this one.
	 * @param $flags \type{\int} GAID_FOR_UPDATE
	 * @return \twotypes{\int,\bool} Old revision ID, or FALSE if none exists
	 */
	public function getPreviousRevisionID( $revId, $flags=0 ) {
		$db = ($flags & GAID_FOR_UPDATE) ? wfGetDB( DB_MASTER ) : wfGetDB( DB_SLAVE );
		return $db->selectField( 'revision', 'rev_id',
			array(
				'rev_page' => $this->getArticleId($flags),
				'rev_id < ' . intval( $revId )
			),
			__METHOD__,
			array( 'ORDER BY' => 'rev_id DESC' )
		);
	}

	/**
	 * Get the revision ID of the next revision
	 *
	 * @param $revId \type{\int} Revision ID. Get the revision that was after this one.
	 * @param $flags \type{\int} GAID_FOR_UPDATE
	 * @return \twotypes{\int,\bool} Next revision ID, or FALSE if none exists
	 */
	public function getNextRevisionID( $revId, $flags=0 ) {
		$db = ($flags & GAID_FOR_UPDATE) ? wfGetDB( DB_MASTER ) : wfGetDB( DB_SLAVE );
		return $db->selectField( 'revision', 'rev_id',
			array(
				'rev_page' => $this->getArticleId($flags),
				'rev_id > ' . intval( $revId )
			),
			__METHOD__,
			array( 'ORDER BY' => 'rev_id' )
		);
	}
	
	/**
	 * Get the first revision of the page
	 *
	 * @param $flags \type{\int} GAID_FOR_UPDATE
	 * @return Revision (or NULL if page doesn't exist)
	 */
	public function getFirstRevision( $flags=0 ) {
		$db = ($flags & GAID_FOR_UPDATE) ? wfGetDB( DB_MASTER ) : wfGetDB( DB_SLAVE );
		$pageId = $this->getArticleId($flags);
		if( !$pageId ) return NULL;
		$row = $db->selectRow( 'revision', '*',
			array( 'rev_page' => $pageId ),
			__METHOD__,
			array( 'ORDER BY' => 'rev_timestamp ASC', 'LIMIT' => 1 )
		);
		if( !$row ) {
			return NULL;
		} else {
			return new Revision( $row );
		}
	}
	
	/**
	 * Check if this is a new page
	 *
	 * @return bool
	 */
	public function isNewPage() {
		$dbr = wfGetDB( DB_SLAVE );
		return (bool)$dbr->selectField( 'page', 'page_is_new', $this->pageCond(), __METHOD__ );
	}

	/**
	 * Get the oldest revision timestamp of this page
	 *
	 * @return string, MW timestamp
	 */
	public function getEarliestRevTime() {
		$dbr = wfGetDB( DB_SLAVE );
		if( $this->exists() ) {
			$min = $dbr->selectField( 'revision',
				'MIN(rev_timestamp)',
				array( 'rev_page' => $this->getArticleId() ),
				__METHOD__ );
			return wfTimestampOrNull( TS_MW, $min );
		}
		return null;
	}

	/**
	 * Get the number of revisions between the given revision IDs.
	 * Used for diffs and other things that really need it.
	 *
	 * @param $old \type{\int} Revision ID.
	 * @param $new \type{\int} Revision ID.
	 * @return \type{\int} Number of revisions between these IDs.
	 */
	public function countRevisionsBetween( $old, $new ) {
		$dbr = wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'revision', 'count(*)',
			'rev_page = ' . intval( $this->getArticleId() ) .
			' AND rev_id > ' . intval( $old ) .
			' AND rev_id < ' . intval( $new ),
			__METHOD__
		);
	}

	/**
	 * Compare with another title.
	 *
	 * @param \type{Title} $title
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function equals( Title $title ) {
		// Note: === is necessary for proper matching of number-like titles.
		return $this->getInterwiki() === $title->getInterwiki()
			&& $this->getNamespace() == $title->getNamespace()
			&& $this->getDBkey() === $title->getDBkey();
	}

	/**
	 * Callback for usort() to do title sorts by (namespace, title)
	 */
	public static function compare( $a, $b ) {
		if( $a->getNamespace() == $b->getNamespace() ) {
			return strcmp( $a->getText(), $b->getText() );
		} else {
			return $a->getNamespace() - $b->getNamespace();
		}
	}

	/**
	 * Return a string representation of this title
	 *
	 * @return \type{\string} String representation of this title
	 */
	public function __toString() {
		return $this->getPrefixedText();
	}

	/**
	 * Check if page exists.  For historical reasons, this function simply
	 * checks for the existence of the title in the page table, and will
	 * thus return false for interwiki links, special pages and the like.
	 * If you want to know if a title can be meaningfully viewed, you should
	 * probably call the isKnown() method instead.
	 *
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function exists() {
		return $this->getArticleId() != 0;
	}

	/**
	 * Should links to this title be shown as potentially viewable (i.e. as
	 * "bluelinks"), even if there's no record by this title in the page
	 * table?
	 *
	 * This function is semi-deprecated for public use, as well as somewhat
	 * misleadingly named.  You probably just want to call isKnown(), which
	 * calls this function internally.
	 *
	 * (ISSUE: Most of these checks are cheap, but the file existence check
	 * can potentially be quite expensive.  Including it here fixes a lot of
	 * existing code, but we might want to add an optional parameter to skip
	 * it and any other expensive checks.)
	 *
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function isAlwaysKnown() {
		if( $this->mInterwiki != '' ) {
			return true;  // any interwiki link might be viewable, for all we know
		}
		switch( $this->mNamespace ) {
		case NS_MEDIA:
		case NS_FILE:
			return wfFindFile( $this );  // file exists, possibly in a foreign repo
		case NS_SPECIAL:
			return SpecialPage::exists( $this->getDBkey() );  // valid special page
		case NS_MAIN:
			return $this->mDbkeyform == '';  // selflink, possibly with fragment
		case NS_MEDIAWIKI:
			// If the page is form Mediawiki:message/lang, calling wfMsgWeirdKey causes
			// the full l10n of that language to be loaded. That takes much memory and
			// isn't needed. So we strip the language part away.
			// Also, extension messages which are not loaded, are shown as red, because
			// we don't call MessageCache::loadAllMessages.
			list( $basename, /* rest */ ) = explode( '/', $this->mDbkeyform, 2 );
			return wfMsgWeirdKey( $basename );  // known system message
		default:
			return false;
		}
	}

	/**
	 * Does this title refer to a page that can (or might) be meaningfully
	 * viewed?  In particular, this function may be used to determine if
	 * links to the title should be rendered as "bluelinks" (as opposed to
	 * "redlinks" to non-existent pages).
	 *
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function isKnown() {
		return $this->exists() || $this->isAlwaysKnown();
	}
	
	/**
	* Is this in a namespace that allows actual pages?
	*
	* @return \type{\bool} TRUE or FALSE
	*/
	public function canExist() {
		return $this->mNamespace >= 0 && $this->mNamespace != NS_MEDIA;
	}

	/**
	 * Update page_touched timestamps and send squid purge messages for
	 * pages linking to this title.	May be sent to the job queue depending
	 * on the number of links. Typically called on create and delete.
	 */
	public function touchLinks() {
		$u = new HTMLCacheUpdate( $this, 'pagelinks' );
		$u->doUpdate();

		if ( $this->getNamespace() == NS_CATEGORY ) {
			$u = new HTMLCacheUpdate( $this, 'categorylinks' );
			$u->doUpdate();
		}
	}

	/**
	 * Get the last touched timestamp
	 * @param Database $db, optional db
	 * @return \type{\string} Last touched timestamp
	 */
	public function getTouched( $db = NULL ) {
		$db = isset($db) ? $db : wfGetDB( DB_SLAVE );
		$touched = $db->selectField( 'page', 'page_touched', $this->pageCond(), __METHOD__ );
		return $touched;
	}

	/**
	 * Get the timestamp when this page was updated since the user last saw it.
	 * @param User $user
	 * @return mixed string/NULL
	 */
	public function getNotificationTimestamp( $user = NULL ) {
		global $wgUser, $wgShowUpdatedMarker;
		// Assume current user if none given
		if( !$user ) $user = $wgUser;
		// Check cache first
		$uid = $user->getId();
		if( isset($this->mNotificationTimestamp[$uid]) ) {
			return $this->mNotificationTimestamp[$uid];
		}
		if( !$uid || !$wgShowUpdatedMarker ) {
			return $this->mNotificationTimestamp[$uid] = false;
		}
		// Don't cache too much!
		if( count($this->mNotificationTimestamp) >= self::CACHE_MAX ) {
			$this->mNotificationTimestamp = array();
		}
		$dbr = wfGetDB( DB_SLAVE );
		$this->mNotificationTimestamp[$uid] = $dbr->selectField( 'watchlist',
			'wl_notificationtimestamp',
			array( 'wl_namespace' => $this->getNamespace(),
				'wl_title' => $this->getDBkey(),
				'wl_user' => $user->getId()
			),
			__METHOD__
		);
		return $this->mNotificationTimestamp[$uid];
	}

	/**
	 * Get the trackback URL for this page
	 * @return \type{\string} Trackback URL
	 */
	public function trackbackURL() {
		global $wgScriptPath, $wgServer, $wgScriptExtension;

		return "$wgServer$wgScriptPath/trackback$wgScriptExtension?article="
			. htmlspecialchars(urlencode($this->getPrefixedDBkey()));
	}

	/**
	 * Get the trackback RDF for this page
	 * @return \type{\string} Trackback RDF
	 */
	public function trackbackRDF() {
		$url = htmlspecialchars($this->getFullURL());
		$title = htmlspecialchars($this->getText());
		$tburl = $this->trackbackURL();

		// Autodiscovery RDF is placed in comments so HTML validator
		// won't barf. This is a rather icky workaround, but seems
		// frequently used by this kind of RDF thingy.
		//
		// Spec: http://www.sixapart.com/pronet/docs/trackback_spec
		return "<!--
<rdf:RDF xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
         xmlns:dc=\"http://purl.org/dc/elements/1.1/\"
         xmlns:trackback=\"http://madskills.com/public/xml/rss/module/trackback/\">
<rdf:Description
   rdf:about=\"$url\"
   dc:identifier=\"$url\"
   dc:title=\"$title\"
   trackback:ping=\"$tburl\" />
</rdf:RDF>
-->";
	}

	/**
	 * Generate strings used for xml 'id' names in monobook tabs
	 * @return \type{\string} XML 'id' name
	 */
	public function getNamespaceKey() {
		global $wgContLang;
		switch ($this->getNamespace()) {
			case NS_MAIN:
			case NS_TALK:
				return 'nstab-main';
			case NS_USER:
			case NS_USER_TALK:
				return 'nstab-user';
			case NS_MEDIA:
				return 'nstab-media';
			case NS_SPECIAL:
				return 'nstab-special';
			case NS_PROJECT:
			case NS_PROJECT_TALK:
				return 'nstab-project';
			case NS_FILE:
			case NS_FILE_TALK:
				return 'nstab-image';
			case NS_MEDIAWIKI:
			case NS_MEDIAWIKI_TALK:
				return 'nstab-mediawiki';
			case NS_TEMPLATE:
			case NS_TEMPLATE_TALK:
				return 'nstab-template';
			case NS_HELP:
			case NS_HELP_TALK:
				return 'nstab-help';
			case NS_CATEGORY:
			case NS_CATEGORY_TALK:
				return 'nstab-category';
			default:
				return 'nstab-' . $wgContLang->lc( $this->getSubjectNsText() );
		}
	}

	/**
	 * Returns true if this title resolves to the named special page
	 * @param $name \type{\string} The special page name
	 */
	public function isSpecial( $name ) {
		if ( $this->getNamespace() == NS_SPECIAL ) {
			list( $thisName, /* $subpage */ ) = SpecialPage::resolveAliasWithSubpage( $this->getDBkey() );
			if ( $name == $thisName ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * If the Title refers to a special page alias which is not the local default,
	 * @return \type{Title} A new Title which points to the local default. Otherwise, returns $this.
	 */
	public function fixSpecialName() {
		if ( $this->getNamespace() == NS_SPECIAL ) {
			$canonicalName = SpecialPage::resolveAlias( $this->mDbkeyform );
			if ( $canonicalName ) {
				$localName = SpecialPage::getLocalNameFor( $canonicalName );
				if ( $localName != $this->mDbkeyform ) {
					return Title::makeTitle( NS_SPECIAL, $localName );
				}
			}
		}
		return $this;
	}

	/**
	 * Is this Title in a namespace which contains content?
	 * In other words, is this a content page, for the purposes of calculating
	 * statistics, etc?
	 *
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function isContentPage() {
		return MWNamespace::isContent( $this->getNamespace() );
	}

	/**
	 * Get all extant redirects to this Title
	 *
	 * @param $ns \twotypes{\int,\null} Single namespace to consider; 
	 *            NULL to consider all namespaces
	 * @return \type{\arrayof{Title}} Redirects to this title
	 */
	public function getRedirectsHere( $ns = null ) {
		$redirs = array();
		
		$dbr = wfGetDB( DB_SLAVE );	
		$where = array(
			'rd_namespace' => $this->getNamespace(),
			'rd_title' => $this->getDBkey(),
			'rd_from = page_id'
		);
		if ( !is_null($ns) ) $where['page_namespace'] = $ns;
		
		$res = $dbr->select(
			array( 'redirect', 'page' ),
			array( 'page_namespace', 'page_title' ),
			$where,
			__METHOD__
		);


		foreach( $res as $row ) {
			$redirs[] = self::newFromRow( $row );
		}
		return $redirs;
	}
	
	/**
	 * Check if this Title is a valid redirect target
	 *
	 * @return \type{\bool} TRUE or FALSE
	 */
	public function isValidRedirectTarget() {
		global $wgInvalidRedirectTargets;
		
		// invalid redirect targets are stored in a global array, but explicity disallow Userlogout here
		if( $this->isSpecial( 'Userlogout' ) ) {
			return false;
		}
		
		foreach( $wgInvalidRedirectTargets as $target ) {
			if( $this->isSpecial( $target ) ) {
				return false;
			}
		}
		
		return true;
	}

	/**
	 * Get a backlink cache object
	 */
	function getBacklinkCache() {
		if ( is_null( $this->mBacklinkCache ) ) {
			$this->mBacklinkCache = new BacklinkCache( $this );
		}
		return $this->mBacklinkCache;
	}
}

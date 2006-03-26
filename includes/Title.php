<?php
/**
 * See title.txt
 *
 * @package MediaWiki
 */

/** */
require_once( 'normal/UtfNormal.php' );

$wgTitleInterwikiCache = array();
define ( 'GAID_FOR_UPDATE', 1 );

# Title::newFromTitle maintains a cache to avoid
# expensive re-normalization of commonly used titles.
# On a batch operation this can become a memory leak
# if not bounded. After hitting this many titles,
# reset the cache.
define( 'MW_TITLECACHE_MAX', 1000 );

/**
 * Title class
 * - Represents a title, which may contain an interwiki designation or namespace
 * - Can fetch various kinds of data from the database, albeit inefficiently.
 *
 * @package MediaWiki
 */
class Title {
	/**
	 * All member variables should be considered private
	 * Please use the accessor functions
	 */

	 /**#@+
	 * @access private
	 */

	var $mTextform;           # Text form (spaces not underscores) of the main part
	var $mUrlform;            # URL-encoded form of the main part
	var $mDbkeyform;          # Main part with underscores
	var $mNamespace;          # Namespace index, i.e. one of the NS_xxxx constants
	var $mInterwiki;          # Interwiki prefix (or null string)
	var $mFragment;           # Title fragment (i.e. the bit after the #)
	var $mArticleID;          # Article ID, fetched from the link cache on demand
	var $mLatestID;         # ID of most recent revision
	var $mRestrictions;       # Array of groups allowed to edit this article
                              # Only null or "sysop" are supported
	var $mRestrictionsLoaded; # Boolean for initialisation on demand
	var $mPrefixedText;       # Text form including namespace/interwiki, initialised on demand
	var $mDefaultNamespace;   # Namespace index when there is no namespace
                              # Zero except in {{transclusion}} tags
	var $mWatched;            # Is $wgUser watching this page? NULL if unfilled, accessed through userIsWatching()
	/**#@-*/


	/**
	 * Constructor
	 * @access private
	 */
	/* private */ function Title() {
		$this->mInterwiki = $this->mUrlform =
		$this->mTextform = $this->mDbkeyform = '';
		$this->mArticleID = -1;
		$this->mNamespace = NS_MAIN;
		$this->mRestrictionsLoaded = false;
		$this->mRestrictions = array();
		# Dont change the following, NS_MAIN is hardcoded in several place
		# See bug #696
		$this->mDefaultNamespace = NS_MAIN;
		$this->mWatched = NULL;
		$this->mLatestID = false;
	}

	/**
	 * Create a new Title from a prefixed DB key
	 * @param string $key The database key, which has underscores
	 *	instead of spaces, possibly including namespace and
	 *	interwiki prefixes
	 * @return Title the new object, or NULL on an error
	 * @static
	 * @access public
	 */
	/* static */ function newFromDBkey( $key ) {
		$t = new Title();
		$t->mDbkeyform = $key;
		if( $t->secureAndSplit() )
			return $t;
		else
			return NULL;
	}

	/**
	 * Create a new Title from text, such as what one would
	 * find in a link. Decodes any HTML entities in the text.
	 *
	 * @param string $text the link text; spaces, prefixes,
	 *	and an initial ':' indicating the main namespace
	 *	are accepted
	 * @param int $defaultNamespace the namespace to use if
	 * 	none is specified by a prefix
	 * @return Title the new object, or NULL on an error
	 * @static
	 * @access public
	 */
	function newFromText( $text, $defaultNamespace = NS_MAIN ) {
		$fname = 'Title::newFromText';
		wfProfileIn( $fname );

		if( is_object( $text ) ) {
			wfDebugDieBacktrace( 'Title::newFromText given an object' );
		}

		/**
		 * Wiki pages often contain multiple links to the same page.
		 * Title normalization and parsing can become expensive on
		 * pages with many links, so we can save a little time by
		 * caching them.
		 *
		 * In theory these are value objects and won't get changed...
		 */
		static $titleCache = array();
		if( $defaultNamespace == NS_MAIN && isset( $titleCache[$text] ) ) {
			wfProfileOut( $fname );
			return $titleCache[$text];
		}

		/**
		 * Convert things like &eacute; &#257; or &#x3017; into real text...
		 */
		$filteredText = Sanitizer::decodeCharReferences( $text );

		$t =& new Title();
		$t->mDbkeyform = str_replace( ' ', '_', $filteredText );
		$t->mDefaultNamespace = $defaultNamespace;

		if( $t->secureAndSplit() ) {
			if( $defaultNamespace == NS_MAIN ) {
				if( count( $titleCache ) >= MW_TITLECACHE_MAX ) {
					# Avoid memory leaks on mass operations...
					$titleCache = array();
				}
				$titleCache[$text] =& $t;
			}
			wfProfileOut( $fname );
			return $t;
		} else {
			wfProfileOut( $fname );
			return NULL;
		}
	}

	/**
	 * Create a new Title from URL-encoded text. Ensures that
	 * the given title's length does not exceed the maximum.
	 * @param string $url the title, as might be taken from a URL
	 * @return Title the new object, or NULL on an error
	 * @static
	 * @access public
	 */
	function newFromURL( $url ) {
		global $wgLang, $wgServer;
		$t = new Title();

		# For compatibility with old buggy URLs. "+" is not valid in titles,
		# but some URLs used it as a space replacement and they still come
		# from some external search tools.
		$s = str_replace( '+', ' ', $url );

		$t->mDbkeyform = str_replace( ' ', '_', $s );
		if( $t->secureAndSplit() ) {
			return $t;
		} else {
			return NULL;
		}
	}

	/**
	 * Create a new Title from an article ID
	 *
	 * @todo This is inefficiently implemented, the page row is requested
	 *       but not used for anything else
	 *
	 * @param int $id the page_id corresponding to the Title to create
	 * @return Title the new object, or NULL on an error
	 * @access public
	 * @static
	 */
	function newFromID( $id ) {
		$fname = 'Title::newFromID';
		$dbr =& wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'page', array( 'page_namespace', 'page_title' ),
			array( 'page_id' => $id ), $fname );
		if ( $row !== false ) {
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
		} else {
			$title = NULL;
		}
		return $title;
	}

	/**
	 * Create a new Title from a namespace index and a DB key.
	 * It's assumed that $ns and $title are *valid*, for instance when
	 * they came directly from the database or a special page name.
	 * For convenience, spaces are converted to underscores so that
	 * eg user_text fields can be used directly.
	 *
	 * @param int $ns the namespace of the article
	 * @param string $title the unprefixed database key form
	 * @return Title the new object
	 * @static
	 * @access public
	 */
	function &makeTitle( $ns, $title ) {
		$t =& new Title();
		$t->mInterwiki = '';
		$t->mFragment = '';
		$t->mNamespace = IntVal( $ns );
		$t->mDbkeyform = str_replace( ' ', '_', $title );
		$t->mArticleID = ( $ns >= 0 ) ? -1 : 0;
		$t->mUrlform = wfUrlencode( $t->mDbkeyform );
		$t->mTextform = str_replace( '_', ' ', $title );
		return $t;
	}

	/**
	 * Create a new Title frrom a namespace index and a DB key.
	 * The parameters will be checked for validity, which is a bit slower
	 * than makeTitle() but safer for user-provided data.
	 *
	 * @param int $ns the namespace of the article
	 * @param string $title the database key form
	 * @return Title the new object, or NULL on an error
	 * @static
	 * @access public
	 */
	function makeTitleSafe( $ns, $title ) {
		$t = new Title();
		$t->mDbkeyform = Title::makeName( $ns, $title );
		if( $t->secureAndSplit() ) {
			return $t;
		} else {
			return NULL;
		}
 	}

	/**
	 * Create a new Title for the Main Page
	 *
	 * @static
	 * @return Title the new object
	 * @access public
	 */
	function newMainPage() {
		return Title::newFromText( wfMsgForContent( 'mainpage' ) );
	}

	/**
	 * Create a new Title for a redirect
	 * @param string $text the redirect title text
	 * @return Title the new object, or NULL if the text is not a
	 *	valid redirect
	 * @static
	 * @access public
	 */
	function newFromRedirect( $text ) {
		global $wgMwRedir;
		$rt = NULL;
		if ( $wgMwRedir->matchStart( $text ) ) {
			if ( preg_match( '/\[{2}(.*?)(?:\||\]{2})/', $text, $m ) ) {
				# categories are escaped using : for example one can enter:
				# #REDIRECT [[:Category:Music]]. Need to remove it.
				if ( substr($m[1],0,1) == ':') {
					# We don't want to keep the ':'
					$m[1] = substr( $m[1], 1 );
				}

				$rt = Title::newFromText( $m[1] );
				# Disallow redirects to Special:Userlogout
				if ( !is_null($rt) && $rt->getNamespace() == NS_SPECIAL && preg_match( '/^Userlogout/i', $rt->getText() ) ) {
					$rt = NULL;
				}
			}
		}
		return $rt;
	}

#----------------------------------------------------------------------------
#	Static functions
#----------------------------------------------------------------------------

	/**
	 * Get the prefixed DB key associated with an ID
	 * @param int $id the page_id of the article
	 * @return Title an object representing the article, or NULL
	 * 	if no such article was found
	 * @static
	 * @access public
	 */
	function nameOf( $id ) {
		$fname = 'Title::nameOf';
		$dbr =& wfGetDB( DB_SLAVE );

		$s = $dbr->selectRow( 'page', array( 'page_namespace','page_title' ),  array( 'page_id' => $id ), $fname );
		if ( $s === false ) { return NULL; }

		$n = Title::makeName( $s->page_namespace, $s->page_title );
		return $n;
	}

	/**
	 * Get a regex character class describing the legal characters in a link
	 * @return string the list of characters, not delimited
	 * @static
	 * @access public
	 */
	function legalChars() {
		# Missing characters:
		#  * []|# Needed for link syntax
		#  * % and + are corrupted by Apache when they appear in the path
		#
		# % seems to work though
		#
		# The problem with % is that URLs are double-unescaped: once by Apache's
		# path conversion code, and again by PHP. So %253F, for example, becomes "?".
		# Our code does not double-escape to compensate for this, indeed double escaping
		# would break if the double-escaped title was passed in the query string
		# rather than the path. This is a minor security issue because articles can be
		# created such that they are hard to view or edit. -- TS
		#
		# Theoretically 0x80-0x9F of ISO 8859-1 should be disallowed, but
		# this breaks interlanguage links

		$set = " %!\"$&'()*,\\-.\\/0-9:;=?@A-Z\\\\^_`a-z~\\x80-\\xFF";
		return $set;
	}

	/**
	 * Get a string representation of a title suitable for
	 * including in a search index
	 *
	 * @param int $ns a namespace index
	 * @param string $title text-form main part
	 * @return string a stripped-down title string ready for the
	 * 	search index
	 */
	/* static */ function indexTitle( $ns, $title ) {
		global $wgDBminWordLen, $wgContLang;
		require_once( 'SearchEngine.php' );

		$lc = SearchEngine::legalSearchChars() . '&#;';
		$t = $wgContLang->stripForSearch( $title );
		$t = preg_replace( "/[^{$lc}]+/", ' ', $t );
		$t = strtolower( $t );

		# Handle 's, s'
		$t = preg_replace( "/([{$lc}]+)'s( |$)/", "\\1 \\1's ", $t );
		$t = preg_replace( "/([{$lc}]+)s'( |$)/", "\\1s ", $t );

		$t = preg_replace( "/\\s+/", ' ', $t );

		if ( $ns == NS_IMAGE ) {
			$t = preg_replace( "/ (png|gif|jpg|jpeg|ogg)$/", "", $t );
		}
		return trim( $t );
	}

	/*
	 * Make a prefixed DB key from a DB key and a namespace index
	 * @param int $ns numerical representation of the namespace
	 * @param string $title the DB key form the title
	 * @return string the prefixed form of the title
	 */
	/* static */ function makeName( $ns, $title ) {
		global $wgContLang;

		$n = $wgContLang->getNsText( $ns );
		return $n == '' ? $title : "$n:$title";
	}

	/**
	 * Returns the URL associated with an interwiki prefix
	 * @param string $key the interwiki prefix (e.g. "MeatBall")
	 * @return the associated URL, containing "$1", which should be
	 * 	replaced by an article title
	 * @static (arguably)
	 * @access public
	 */
	function getInterwikiLink( $key, $transludeonly = false ) {
		global $wgMemc, $wgDBname, $wgInterwikiExpiry, $wgTitleInterwikiCache;
		$fname = 'Title::getInterwikiLink';

		wfProfileIn( $fname );

		$k = $wgDBname.':interwiki:'.$key;
		if( array_key_exists( $k, $wgTitleInterwikiCache ) ) {
			wfProfileOut( $fname );
			return $wgTitleInterwikiCache[$k]->iw_url;
		}

		$s = $wgMemc->get( $k );
		# Ignore old keys with no iw_local
		if( $s && isset( $s->iw_local ) && isset($s->iw_trans)) {
			$wgTitleInterwikiCache[$k] = $s;
			wfProfileOut( $fname );
			return $s->iw_url;
		}

		$dbr =& wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'interwiki',
			array( 'iw_url', 'iw_local', 'iw_trans' ),
			array( 'iw_prefix' => $key ), $fname );
		if( !$res ) {
			wfProfileOut( $fname );
			return '';
		}

		$s = $dbr->fetchObject( $res );
		if( !$s ) {
			# Cache non-existence: create a blank object and save it to memcached
			$s = (object)false;
			$s->iw_url = '';
			$s->iw_local = 0;
			$s->iw_trans = 0;
		}
		$wgMemc->set( $k, $s, $wgInterwikiExpiry );
		$wgTitleInterwikiCache[$k] = $s;

		wfProfileOut( $fname );
		return $s->iw_url;
	}

	/**
	 * Determine whether the object refers to a page within
	 * this project.
	 *
	 * @return bool TRUE if this is an in-project interwiki link
	 *	or a wikilink, FALSE otherwise
	 * @access public
	 */
	function isLocal() {
		global $wgTitleInterwikiCache, $wgDBname;

		if ( $this->mInterwiki != '' ) {
			# Make sure key is loaded into cache
			$this->getInterwikiLink( $this->mInterwiki );
			$k = $wgDBname.':interwiki:' . $this->mInterwiki;
			return (bool)($wgTitleInterwikiCache[$k]->iw_local);
		} else {
			return true;
		}
	}

	/**
	 * Determine whether the object refers to a page within
	 * this project and is transcludable.
	 *
	 * @return bool TRUE if this is transcludable
	 * @access public
	 */
	function isTrans() {
		global $wgTitleInterwikiCache, $wgDBname;

		if ($this->mInterwiki == '' || !$this->isLocal())
			return false;
		# Make sure key is loaded into cache
		$this->getInterwikiLink( $this->mInterwiki );
		$k = $wgDBname.':interwiki:' . $this->mInterwiki;
		return (bool)($wgTitleInterwikiCache[$k]->iw_trans);
	}

	/**
	 * Update the page_touched field for an array of title objects
	 * @todo Inefficient unless the IDs are already loaded into the
	 *	link cache
	 * @param array $titles an array of Title objects to be touched
	 * @param string $timestamp the timestamp to use instead of the
	 *	default current time
	 * @static
	 * @access public
	 */
	function touchArray( $titles, $timestamp = '' ) {
		global $wgUseFileCache;

		if ( count( $titles ) == 0 ) {
			return;
		}
		$dbw =& wfGetDB( DB_MASTER );
		if ( $timestamp == '' ) {
			$timestamp = $dbw->timestamp();
		}
		$page = $dbw->tableName( 'page' );
		/*
		$sql = "UPDATE $page SET page_touched='{$timestamp}' WHERE page_id IN (";
		$first = true;

		foreach ( $titles as $title ) {
			if ( $wgUseFileCache ) {
				$cm = new CacheManager($title);
				@unlink($cm->fileCacheName());
			}

			if ( ! $first ) {
				$sql .= ',';
			}
			$first = false;
			$sql .= $title->getArticleID();
		}
		$sql .= ')';
		if ( ! $first ) {
			$dbw->query( $sql, 'Title::touchArray' );
		}
		*/
		// hack hack hack -- brion 2005-07-11. this was unfriendly to db.
		// do them in small chunks:
		$fname = 'Title::touchArray';
		foreach( $titles as $title ) {
			$dbw->update( 'page',
				array( 'page_touched' => $timestamp ),
				array(
					'page_namespace' => $title->getNamespace(),
					'page_title'     => $title->getDBkey() ),
				$fname );
		}
	}

#----------------------------------------------------------------------------
#	Other stuff
#----------------------------------------------------------------------------

	/** Simple accessors */
	/**
	 * Get the text form (spaces not underscores) of the main part
	 * @return string
	 * @access public
	 */
	function getText() { return $this->mTextform; }
	/**
	 * Get the URL-encoded form of the main part
	 * @return string
	 * @access public
	 */
	function getPartialURL() { return $this->mUrlform; }
	/**
	 * Get the main part with underscores
	 * @return string
	 * @access public
	 */
	function getDBkey() { return $this->mDbkeyform; }
	/**
	 * Get the namespace index, i.e. one of the NS_xxxx constants
	 * @return int
	 * @access public
	 */
	function getNamespace() { return $this->mNamespace; }
	/**
	 * Get the interwiki prefix (or null string)
	 * @return string
	 * @access public
	 */
	function getInterwiki() { return $this->mInterwiki; }
	/**
	 * Get the Title fragment (i.e. the bit after the #)
	 * @return string
	 * @access public
	 */
	function getFragment() { return $this->mFragment; }
	/**
	 * Get the default namespace index, for when there is no namespace
	 * @return int
	 * @access public
	 */
	function getDefaultNamespace() { return $this->mDefaultNamespace; }

	/**
	 * Get title for search index
	 * @return string a stripped-down title string ready for the
	 * 	search index
	 */
	function getIndexTitle() {
		return Title::indexTitle( $this->mNamespace, $this->mTextform );
	}

	/**
	 * Get the prefixed database key form
	 * @return string the prefixed title, with underscores and
	 * 	any interwiki and namespace prefixes
	 * @access public
	 */
	function getPrefixedDBkey() {
		$s = $this->prefix( $this->mDbkeyform );
		$s = str_replace( ' ', '_', $s );
		return $s;
	}

	/**
	 * Get the prefixed title with spaces.
	 * This is the form usually used for display
	 * @return string the prefixed title, with spaces
	 * @access public
	 */
	function getPrefixedText() {
		global $wgContLang;
		if ( empty( $this->mPrefixedText ) ) {
			$s = $this->prefix( $this->mTextform );
			$s = str_replace( '_', ' ', $s );
			$this->mPrefixedText = $s;
		}
		return $this->mPrefixedText;
	}

	/**
	 * Get the prefixed title with spaces, plus any fragment
	 * (part beginning with '#')
	 * @return string the prefixed title, with spaces and
	 * 	the fragment, including '#'
	 * @access public
	 */
	function getFullText() {
		global $wgContLang;
		$text = $this->getPrefixedText();
		if( '' != $this->mFragment ) {
			$text .= '#' . $this->mFragment;
		}
		return $text;
	}

	/**
	 * Get a URL-encoded title (not an actual URL) including interwiki
	 * @return string the URL-encoded form
	 * @access public
	 */
	function getPrefixedURL() {
		$s = $this->prefix( $this->mDbkeyform );
		$s = str_replace( ' ', '_', $s );

		$s = wfUrlencode ( $s ) ;

		# Cleaning up URL to make it look nice -- is this safe?
		$s = str_replace( '%28', '(', $s );
		$s = str_replace( '%29', ')', $s );

		return $s;
	}

	/**
	 * Get a real URL referring to this title, with interwiki link and
	 * fragment
	 *
	 * @param string $query an optional query string, not used
	 * 	for interwiki links
	 * @return string the URL
	 * @access public
	 */
	function getFullURL( $query = '' ) {
		global $wgContLang, $wgServer, $wgScript, $wgMakeDumpLinks, $wgArticlePath;

		if ( '' == $this->mInterwiki ) {
			return $wgServer . $this->getLocalUrl( $query );
		} elseif ( $wgMakeDumpLinks && $wgContLang->getLanguageName( $this->mInterwiki ) ) {
			$baseUrl = str_replace( '$1', "../../{$this->mInterwiki}/$1", $wgArticlePath );
			$baseUrl = str_replace( '$1', $this->getHashedDirectory() . '/$1', $baseUrl );
		} else {
			$baseUrl = $this->getInterwikiLink( $this->mInterwiki );
		}

		$namespace = $wgContLang->getNsText( $this->mNamespace );
		if ( '' != $namespace ) {
			# Can this actually happen? Interwikis shouldn't be parsed.
			$namespace .= ':';
		}
		$url = str_replace( '$1', $namespace . $this->mUrlform, $baseUrl );
		if( $query != '' ) {
			if( false === strpos( $url, '?' ) ) {
				$url .= '?';
			} else {
				$url .= '&';
			}
			$url .= $query;
		}
		if ( '' != $this->mFragment ) {
			$url .= '#' . $this->mFragment;
		}
		return $url;
	}

	/**
	 * Get a relative directory for putting an HTML version of this article into
	 */
	function getHashedDirectory() {
		global $wgMakeDumpLinks, $wgInputEncoding;
		$dbkey = $this->getDBkey();

		# Split into characters
		if ( $wgInputEncoding == 'UTF-8' ) {
			preg_match_all( '/./us', $dbkey, $m );
		} else {
			preg_match_all( '/./s', $dbkey, $m );
		}
		$chars = $m[0];
		$length = count( $chars );
		$dir = '';

		for ( $i = 0; $i < $wgMakeDumpLinks; $i++ ) {
			if ( $i ) {
				$dir .= '/';
			}
			if ( $i >= $length ) {
				$dir .= '_';
			} elseif ( ord( $chars[$i] ) > 32 ) {
				$dir .= strtolower( $chars[$i] );
			} else {
				$dir .= sprintf( "%02X", ord( $chars[$i] ) );
			}
		}
		return $dir;
	}

	function getHashedFilename() {
		$dbkey = $this->getPrefixedDBkey();
		$mainPage = Title::newMainPage();
		if ( $mainPage->getPrefixedDBkey() == $dbkey ) {
			return 'index.html';
		}

		$dir = $this->getHashedDirectory();

		# Replace illegal charcters for Windows paths with underscores
		$friendlyName = strtr( $dbkey, '/\\*?"<>|~', '_________' );

		# Work out lower case form. We assume we're on a system with case-insensitive
		# filenames, so unless the case is of a special form, we have to disambiguate
		$lowerCase = $this->prefix( ucfirst( strtolower( $this->getDBkey() ) ) );

		# Make it mostly unique
		if ( $lowerCase != $friendlyName  ) {
			$friendlyName .= '_' . substr(md5( $dbkey ), 0, 4);
		}
		# Handle colon specially by replacing it with tilde
		# Thus we reduce the number of paths with hashes appended
		$friendlyName = str_replace( ':', '~', $friendlyName );
		return "$dir/$friendlyName.html";
	}

	/**
	 * Get a URL with no fragment or server name.  If this page is generated
	 * with action=render, $wgServer is prepended.
	 * @param string $query an optional query string; if not specified,
	 * 	$wgArticlePath will be used.
	 * @return string the URL
	 * @access public
	 */
	function getLocalURL( $query = '' ) {
		global $wgLang, $wgArticlePath, $wgScript, $wgMakeDumpLinks, $wgServer, $action;

		if ( $this->isExternal() ) {
			return $this->getFullURL();
		}

		$dbkey = wfUrlencode( $this->getPrefixedDBkey() );
		if ( $wgMakeDumpLinks ) {
			$url = str_replace( '$1', wfUrlencode( $this->getHashedFilename() ), $wgArticlePath );
		} elseif ( $query == '' ) {
			$url = str_replace( '$1', $dbkey, $wgArticlePath );
		} else {
			global $wgActionPaths;
			if( !empty( $wgActionPaths ) &&
				preg_match( '/^(.*&|)action=([^&]*)(&(.*)|)$/', $query, $matches ) ) {
				$action = urldecode( $matches[2] );
				if( isset( $wgActionPaths[$action] ) ) {
					$query = $matches[1];
					if( isset( $matches[4] ) ) $query .= $matches[4];
					$url = str_replace( '$1', $dbkey, $wgActionPaths[$action] );
					if( $query != '' ) $url .= '?' . $query;
					return $url;
				}
			}
			if ( $query == '-' ) {
				$query = '';
			}
			$url = "{$wgScript}?title={$dbkey}&{$query}";
		}

		if ($action == 'render')
			return $wgServer . $url;
		else
			return $url;
	}

	/**
	 * Get an HTML-escaped version of the URL form, suitable for
	 * using in a link, without a server name or fragment
	 * @param string $query an optional query string
	 * @return string the URL
	 * @access public
	 */
	function escapeLocalURL( $query = '' ) {
		return htmlspecialchars( $this->getLocalURL( $query ) );
	}

	/**
	 * Get an HTML-escaped version of the URL form, suitable for
	 * using in a link, including the server name and fragment
	 *
	 * @return string the URL
	 * @param string $query an optional query string
	 * @access public
	 */
	function escapeFullURL( $query = '' ) {
		return htmlspecialchars( $this->getFullURL( $query ) );
	}

	/**
	 * Get the URL form for an internal link.
	 * - Used in various Squid-related code, in case we have a different
	 * internal hostname for the server from the exposed one.
	 *
	 * @param string $query an optional query string
	 * @return string the URL
	 * @access public
	 */
	function getInternalURL( $query = '' ) {
		global $wgInternalServer;
		return $wgInternalServer . $this->getLocalURL( $query );
	}

	/**
	 * Get the edit URL for this Title
	 * @return string the URL, or a null string if this is an
	 * 	interwiki link
	 * @access public
	 */
	function getEditURL() {
		global $wgServer, $wgScript;

		if ( '' != $this->mInterwiki ) { return ''; }
		$s = $this->getLocalURL( 'action=edit' );

		return $s;
	}

	/**
	 * Get the HTML-escaped displayable text form.
	 * Used for the title field in <a> tags.
	 * @return string the text, including any prefixes
	 * @access public
	 */
	function getEscapedText() {
		return htmlspecialchars( $this->getPrefixedText() );
	}

	/**
	 * Is this Title interwiki?
	 * @return boolean
	 * @access public
	 */
	function isExternal() { return ( '' != $this->mInterwiki ); }

	/**
	 * Does the title correspond to a protected article?
	 * @param string $what the action the page is protected from,
	 *	by default checks move and edit
	 * @return boolean
	 * @access public
	 */
	function isProtected($action = '') {
		if ( -1 == $this->mNamespace ) { return true; }
		if($action == 'edit' || $action == '') {
			$a = $this->getRestrictions("edit");
			if ( in_array( 'sysop', $a ) ) { return true; }
		}
		if($action == 'move' || $action == '') {
			$a = $this->getRestrictions("move");
			if ( in_array( 'sysop', $a ) ) { return true; }
		}
		return false;
	}

	/**
	 * Is $wgUser is watching this page?
	 * @return boolean
	 * @access public
	 */
	function userIsWatching() {
		global $wgUser;

		if ( is_null( $this->mWatched ) ) {
			if ( -1 == $this->mNamespace || 0 == $wgUser->getID()) {
				$this->mWatched = false;
			} else {
				$this->mWatched = $wgUser->isWatched( $this );
			}
		}
		return $this->mWatched;
	}

 	/**
	 * Is $wgUser perform $action this page?
	 * @param string $action action that permission needs to be checked for
	 * @return boolean
	 * @access private
 	 */
	function userCan($action) {
		$fname = 'Title::userCanEdit';
		wfProfileIn( $fname );

		global $wgUser;
		if( NS_SPECIAL == $this->mNamespace ) {
			wfProfileOut( $fname );
			return false;
		}
		if( NS_MEDIAWIKI == $this->mNamespace &&
		    !$wgUser->isAllowed('editinterface') ) {
			wfProfileOut( $fname );
			return false;
		}
		if( $this->mDbkeyform == '_' ) {
			# FIXME: Is this necessary? Shouldn't be allowed anyway...
			wfProfileOut( $fname );
			return false;
		}

		# protect global styles and js
		if ( NS_MEDIAWIKI == $this->mNamespace
	         && preg_match("/\\.(css|js)$/", $this->mTextform )
		     && !$wgUser->isAllowed('editinterface') ) {
			wfProfileOut( $fname );
			return false;
		}

		# protect css/js subpages of user pages
		# XXX: this might be better using restrictions
		# XXX: Find a way to work around the php bug that prevents using $this->userCanEditCssJsSubpage() from working
		if( NS_USER == $this->mNamespace
			&& preg_match("/\\.(css|js)$/", $this->mTextform )
			&& !$wgUser->isAllowed('editinterface')
			&& !preg_match('/^'.preg_quote($wgUser->getName(), '/').'\//', $this->mTextform) ) {
			wfProfileOut( $fname );
			return false;
		}

		foreach( $this->getRestrictions($action) as $right ) {
			// Backwards compatibility, rewrite sysop -> protect
			if ( $right == 'sysop' ) {
				$right = 'protect';
			}
			if( '' != $right && !$wgUser->isAllowed( $right ) ) {
				wfProfileOut( $fname );
				return false;
			}
		}

		if( $action == 'move' &&
			!( $this->isMovable() && $wgUser->isAllowed( 'move' ) ) ) {
			wfProfileOut( $fname );
			return false;
		}

		wfProfileOut( $fname );
		return true;
	}

	/**
	 * Can $wgUser edit this page?
	 * @return boolean
	 * @access public
	 */
	function userCanEdit() {
		return $this->userCan('edit');
	}

	/**
	 * Can $wgUser move this page?
	 * @return boolean
	 * @access public
	 */
	function userCanMove() {
		return $this->userCan('move');
	}

	/**
	 * Would anybody with sufficient privileges be able to move this page?
	 * Some pages just aren't movable.
	 *
	 * @return boolean
	 * @access public
	 */
	function isMovable() {
		return Namespace::isMovable( $this->getNamespace() )
			&& $this->getInterwiki() == '';
	}

	/**
	 * Can $wgUser read this page?
	 * @return boolean
	 * @access public
	 */
	function userCanRead() {
		global $wgUser;

		if( $wgUser->isAllowed('read') ) {
			return true;
		} else {
			global $wgWhitelistRead;

			/** If anon users can create an account,
			    they need to reach the login page first! */
			if( $wgUser->isAllowed( 'createaccount' )
			    && $this->getNamespace() == NS_SPECIAL
			    && $this->getText() == 'Userlogin' ) {
				return true;
			}

			/** some pages are explicitly allowed */
			$name = $this->getPrefixedText();
			if( $wgWhitelistRead && in_array( $name, $wgWhitelistRead ) ) {
				return true;
			}

			# Compatibility with old settings
			if( $wgWhitelistRead && $this->getNamespace() == NS_MAIN ) {
				if( in_array( ':' . $name, $wgWhitelistRead ) ) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Is this a talk page of some sort?
	 * @return bool
	 * @access public
	 */
	function isTalkPage() {
		return Namespace::isTalk( $this->getNamespace() );
	}

	/**
	 * Is this a .css or .js subpage of a user page?
	 * @return bool
	 * @access public
	 */
	function isCssJsSubpage() {
		return ( NS_USER == $this->mNamespace and preg_match("/\\.(css|js)$/", $this->mTextform ) );
	}
	/**
	 * Is this a .css subpage of a user page?
	 * @return bool
	 * @access public
	 */
	function isCssSubpage() {
		return ( NS_USER == $this->mNamespace and preg_match("/\\.css$/", $this->mTextform ) );
	}
	/**
	 * Is this a .js subpage of a user page?
	 * @return bool
	 * @access public
	 */
	function isJsSubpage() {
		return ( NS_USER == $this->mNamespace and preg_match("/\\.js$/", $this->mTextform ) );
	}
	/**
	 * Protect css/js subpages of user pages: can $wgUser edit
	 * this page?
	 *
	 * @return boolean
	 * @todo XXX: this might be better using restrictions
	 * @access public
	 */
	function userCanEditCssJsSubpage() {
		global $wgUser;
		return ( $wgUser->isAllowed('editinterface') or preg_match('/^'.preg_quote($wgUser->getName(), '/').'\//', $this->mTextform) );
	}

	/**
	 * Loads a string into mRestrictions array
	 * @param string $res restrictions in string format
	 * @access public
	 */
	function loadRestrictions( $res ) {
		foreach( explode( ':', trim( $res ) ) as $restrict ) {
			$temp = explode( '=', trim( $restrict ) );
			if(count($temp) == 1) {
				// old format should be treated as edit/move restriction
				$this->mRestrictions["edit"] = explode( ',', trim( $temp[0] ) );
				$this->mRestrictions["move"] = explode( ',', trim( $temp[0] ) );
			} else {
				$this->mRestrictions[$temp[0]] = explode( ',', trim( $temp[1] ) );
			}
		}
		$this->mRestrictionsLoaded = true;
	}

	/**
	 * Accessor/initialisation for mRestrictions
	 * @param string $action action that permission needs to be checked for
	 * @return array the array of groups allowed to edit this article
	 * @access public
	 */
	function getRestrictions($action) {
		$id = $this->getArticleID();
		if ( 0 == $id ) { return array(); }

		if ( ! $this->mRestrictionsLoaded ) {
			$dbr =& wfGetDB( DB_SLAVE );
			$res = $dbr->selectField( 'page', 'page_restrictions', 'page_id='.$id );
			$this->loadRestrictions( $res );
		}
		if( isset( $this->mRestrictions[$action] ) ) {
			return $this->mRestrictions[$action];
		}
		return array();
	}

	/**
	 * Is there a version of this page in the deletion archive?
	 * @return int the number of archived revisions
	 * @access public
	 */
	function isDeleted() {
		$fname = 'Title::isDeleted';
		if ( $this->getNamespace() < 0 ) {
			$n = 0;
		} else {
			$dbr =& wfGetDB( DB_SLAVE );
			$n = $dbr->selectField( 'archive', 'COUNT(*)', array( 'ar_namespace' => $this->getNamespace(),
				'ar_title' => $this->getDBkey() ), $fname );
		}
		return (int)$n;
	}

	/**
	 * Get the article ID for this Title from the link cache,
	 * adding it if necessary
	 * @param int $flags a bit field; may be GAID_FOR_UPDATE to select
	 * 	for update
	 * @return int the ID
	 * @access public
	 */
	function getArticleID( $flags = 0 ) {
		global $wgLinkCache;

		if ( $flags & GAID_FOR_UPDATE ) {
			$oldUpdate = $wgLinkCache->forUpdate( true );
			$this->mArticleID = $wgLinkCache->addLinkObj( $this );
			$wgLinkCache->forUpdate( $oldUpdate );
		} else {
			if ( -1 == $this->mArticleID ) {
				$this->mArticleID = $wgLinkCache->addLinkObj( $this );
			}
		}
		return $this->mArticleID;
	}

	function getLatestRevID() {
		if ($this->mLatestID !== false)
			return $this->mLatestID;

		$db =& wfGetDB(DB_SLAVE);
		return $this->mLatestID = $db->selectField( 'revision',
			"max(rev_id)",
			array('rev_page' => $this->getArticleID()),
			'Title::getLatestRevID' );
	}

	/**
	 * This clears some fields in this object, and clears any associated
	 * keys in the "bad links" section of $wgLinkCache.
	 *
	 * - This is called from Article::insertNewArticle() to allow
	 * loading of the new page_id. It's also called from
	 * Article::doDeleteArticle()
	 *
	 * @param int $newid the new Article ID
	 * @access public
	 */
	function resetArticleID( $newid ) {
		global $wgLinkCache;
		$wgLinkCache->clearBadLink( $this->getPrefixedDBkey() );

		if ( 0 == $newid ) { $this->mArticleID = -1; }
		else { $this->mArticleID = $newid; }
		$this->mRestrictionsLoaded = false;
		$this->mRestrictions = array();
	}

	/**
	 * Updates page_touched for this page; called from LinksUpdate.php
	 * @return bool true if the update succeded
	 * @access public
	 */
	function invalidateCache() {
		global $wgUseFileCache;

		if ( wfReadOnly() ) {
			return;
		}

		$now = wfTimestampNow();
		$dbw =& wfGetDB( DB_MASTER );
		$success = $dbw->update( 'page',
			array( /* SET */
				'page_touched' => $dbw->timestamp()
			), array( /* WHERE */
				'page_namespace' => $this->getNamespace() ,
				'page_title' => $this->getDBkey()
			), 'Title::invalidateCache'
		);

		if ($wgUseFileCache) {
			$cache = new CacheManager($this);
			@unlink($cache->fileCacheName());
		}

		return $success;
	}

	/**
	 * Prefix some arbitrary text with the namespace or interwiki prefix
	 * of this object
	 *
	 * @param string $name the text
	 * @return string the prefixed text
	 * @access private
	 */
	/* private */ function prefix( $name ) {
		global $wgContLang;

		$p = '';
		if ( '' != $this->mInterwiki ) {
			$p = $this->mInterwiki . ':';
		}
		if ( 0 != $this->mNamespace ) {
			$p .= $wgContLang->getNsText( $this->mNamespace ) . ':';
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
	 * @return bool true on success
	 * @access private
	 */
	/* private */ function secureAndSplit() {
		global $wgContLang, $wgLocalInterwiki, $wgCapitalLinks;
		$fname = 'Title::secureAndSplit';
 		wfProfileIn( $fname );

		# Initialisation
		static $rxTc = false;
		if( !$rxTc ) {
			# % is needed as well
			$rxTc = '/[^' . Title::legalChars() . ']|%[0-9A-Fa-f]{2}/S';
		}

		$this->mInterwiki = $this->mFragment = '';
		$this->mNamespace = $this->mDefaultNamespace; # Usually NS_MAIN

		# Clean up whitespace
		#
		$t = preg_replace( '/[ _]+/', '_', $this->mDbkeyform );
		$t = trim( $t, '_' );

		if ( '' == $t ) {
			wfProfileOut( $fname );
			return false;
		}

		if( false !== strpos( $t, UTF8_REPLACEMENT ) ) {
			# Contained illegal UTF-8 sequences or forbidden Unicode chars.
			wfProfileOut( $fname );
			return false;
		}

		$this->mDbkeyform = $t;

		# Initial colon indicates main namespace rather than specified default
		# but should not create invalid {ns,title} pairs such as {0,Project:Foo}
		if ( ':' == $t{0} ) {
			$this->mNamespace = NS_MAIN;
			$t = substr( $t, 1 ); # remove the colon but continue processing
		}

		# Namespace or interwiki prefix
		$firstPass = true;
		do {
			if ( preg_match( "/^(.+?)_*:_*(.*)$/S", $t, $m ) ) {
				$p = $m[1];
				$lowerNs = strtolower( $p );
				if ( $ns = Namespace::getCanonicalIndex( $lowerNs ) ) {
					# Canonical namespace
					$t = $m[2];
					$this->mNamespace = $ns;
				} elseif ( $ns = $wgContLang->getNsIndex( $lowerNs )) {
					# Ordinary namespace
					$t = $m[2];
					$this->mNamespace = $ns;
				} elseif( $this->getInterwikiLink( $p ) ) {
					if( !$firstPass ) {
						# Can't make a local interwiki link to an interwiki link.
						# That's just crazy!
						wfProfileOut( $fname );
						return false;
					}

					# Interwiki link
					$t = $m[2];
					$this->mInterwiki = $p;

					# Redundant interwiki prefix to the local wiki
					if ( 0 == strcasecmp( $this->mInterwiki, $wgLocalInterwiki ) ) {
						if( $t == '' ) {
							# Can't have an empty self-link
							wfProfileOut( $fname );
							return false;
						}
						$this->mInterwiki = '';
						$firstPass = false;
						# Do another namespace split...
						continue;
					}
				}
				# If there's no recognized interwiki or namespace,
				# then let the colon expression be part of the title.
			}
			break;
		} while( true );
		$r = $t;

		# We already know that some pages won't be in the database!
		#
		if ( '' != $this->mInterwiki || -1 == $this->mNamespace ) {
			$this->mArticleID = 0;
		}
		$f = strstr( $r, '#' );
		if ( false !== $f ) {
			$this->mFragment = substr( $f, 1 );
			$r = substr( $r, 0, strlen( $r ) - strlen( $f ) );
			# remove whitespace again: prevents "Foo_bar_#"
			# becoming "Foo_bar_"
			$r = preg_replace( '/_*$/', '', $r );
		}

		# Reject illegal characters.
		#
		if( preg_match( $rxTc, $r ) ) {
			wfProfileOut( $fname );
			return false;
		}

		/**
		 * Pages with "/./" or "/../" appearing in the URLs will
		 * often be unreachable due to the way web browsers deal
		 * with 'relative' URLs. Forbid them explicitly.
		 */
		if ( strpos( $r, '.' ) !== false &&
		     ( $r === '.' || $r === '..' ||
		       strpos( $r, './' ) === 0  ||
		       strpos( $r, '../' ) === 0 ||
		       strpos( $r, '/./' ) !== false ||
		       strpos( $r, '/../' ) !== false ) )
		{
			wfProfileOut( $fname );
			return false;
		}

		# We shouldn't need to query the DB for the size.
		#$maxSize = $dbr->textFieldSize( 'page', 'page_title' );
		if ( strlen( $r ) > 255 ) {
			wfProfileOut( $fname );
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
		if( $wgCapitalLinks && $this->mInterwiki == '') {
			$t = $wgContLang->ucfirst( $r );
		} else {
			$t = $r;
		}

		/**
		 * Can't make a link to a namespace alone...
		 * "empty" local links can only be self-links
		 * with a fragment identifier.
		 */
		if( $t == '' &&
			$this->mInterwiki == '' &&
			$this->mNamespace != NS_MAIN ) {
			wfProfileOut( $fname );
			return false;
		}

		# Fill fields
		$this->mDbkeyform = $t;
		$this->mUrlform = wfUrlencode( $t );

		$this->mTextform = str_replace( '_', ' ', $t );

		wfProfileOut( $fname );
		return true;
	}

	/**
	 * Get a Title object associated with the talk page of this article
	 * @return Title the object for the talk page
	 * @access public
	 */
	function getTalkPage() {
		return Title::makeTitle( Namespace::getTalk( $this->getNamespace() ), $this->getDBkey() );
	}

	/**
	 * Get a title object associated with the subject page of this
	 * talk page
	 *
	 * @return Title the object for the subject page
	 * @access public
	 */
	function getSubjectPage() {
		return Title::makeTitle( Namespace::getSubject( $this->getNamespace() ), $this->getDBkey() );
	}

	/**
	 * Get an array of Title objects linking to this Title
	 * Also stores the IDs in the link cache.
	 *
	 * @param string $options may be FOR UPDATE
	 * @return array the Title objects linking here
	 * @access public
	 */
	function getLinksTo( $options = '' ) {
		global $wgLinkCache;
		$id = $this->getArticleID();

		if ( $options ) {
			$db =& wfGetDB( DB_MASTER );
		} else {
			$db =& wfGetDB( DB_SLAVE );
		}

		$res = $db->select( array( 'page', 'pagelinks' ),
			array( 'page_namespace', 'page_title', 'page_id' ),
			array(
				'pl_from=page_id',
				'pl_namespace' => $this->getNamespace(),
				'pl_title'     => $this->getDbKey() ),
			'Title::getLinksTo',
			$options );

		$retVal = array();
		if ( $db->numRows( $res ) ) {
			while ( $row = $db->fetchObject( $res ) ) {
				if ( $titleObj = Title::makeTitle( $row->page_namespace, $row->page_title ) ) {
					$wgLinkCache->addGoodLinkObj( $row->page_id, $titleObj );
					$retVal[] = $titleObj;
				}
			}
		}
		$db->freeResult( $res );
		return $retVal;
	}

	/**
	 * Get an array of Title objects referring to non-existent articles linked from this page
	 *
	 * @param string $options may be FOR UPDATE
	 * @return array the Title objects
	 * @access public
	 */
	function getBrokenLinksFrom( $options = '' ) {
		global $wgLinkCache;

		if ( $options ) {
			$db =& wfGetDB( DB_MASTER );
		} else {
			$db =& wfGetDB( DB_SLAVE );
		}

		$res = $db->safeQuery(
			  "SELECT pl_namespace, pl_title
			     FROM !
			LEFT JOIN !
			       ON pl_namespace=page_namespace
			      AND pl_title=page_title
			    WHERE pl_from=?
			      AND page_namespace IS NULL
			          !",
			$db->tableName( 'pagelinks' ),
			$db->tableName( 'page' ),
			$this->getArticleId(),
			$options );

		$retVal = array();
		if ( $db->numRows( $res ) ) {
			while ( $row = $db->fetchObject( $res ) ) {
				$retVal[] = Title::makeTitle( $row->pl_namespace, $row->pl_title );
			}
		}
		$db->freeResult( $res );
		return $retVal;
	}


	/**
	 * Get a list of URLs to purge from the Squid cache when this
	 * page changes
	 *
	 * @return array the URLs
	 * @access public
	 */
	function getSquidURLs() {
		return array(
			$this->getInternalURL(),
			$this->getInternalURL( 'action=history' )
		);
	}

	/**
	 * Move this page without authentication
	 * @param Title &$nt the new page Title
	 * @access public
	 */
	function moveNoAuth( &$nt ) {
		return $this->moveTo( $nt, false );
	}

	/**
	 * Check whether a given move operation would be valid.
	 * Returns true if ok, or a message key string for an error message
	 * if invalid. (Scarrrrry ugly interface this.)
	 * @param Title &$nt the new title
	 * @param bool $auth indicates whether $wgUser's permissions
	 * 	should be checked
	 * @return mixed true on success, message name on failure
	 * @access public
	 */
	function isValidMoveOperation( &$nt, $auth = true, $reason = '' ) {
		global $wgUser;
		if( !$this or !$nt ) {
			return 'badtitletext';
		}
		if( $this->equals( $nt ) ) {
			return 'selfmove';
		}
		if( !$this->isMovable() || !$nt->isMovable() ) {
			return 'immobile_namespace';
		}

		$fname = 'Title::move';
		$oldid = $this->getArticleID();
		$newid = $nt->getArticleID();

		if ( strlen( $nt->getDBkey() ) < 1 ) {
			return 'articleexists';
		}
		if ( ( '' == $this->getDBkey() ) ||
			 ( !$oldid ) ||
		     ( '' == $nt->getDBkey() ) ) {
			return 'badarticleerror';
		}

		if ( $auth && (
				!$this->userCanEdit() || !$nt->userCanEdit() ||
				!$this->userCanMove() || !$nt->userCanMove() ) ) {
			return 'protectedpage';
		}

		# The move is allowed only if (1) the target doesn't exist, or
		# (2) the target is a redirect to the source, and has no history
		# (so we can undo bad moves right after they're done).

		if ( 0 != $newid ) { # Target exists; check for validity
			if ( ! $this->isValidMoveTarget( $nt ) ) {
				return 'articleexists';
			}
		}
		return true;
	}

	/**
	 * Move a title to a new location
	 * @param Title &$nt the new title
	 * @param bool $auth indicates whether $wgUser's permissions
	 * 	should be checked
	 * @return mixed true on success, message name on failure
	 * @access public
	 */
	function moveTo( &$nt, $auth = true, $reason = '' ) {
		$err = $this->isValidMoveOperation( $nt, $auth, $reason );
		if( is_string( $err ) ) {
			return $err;
		}

		$pageid = $this->getArticleID();
		if( $nt->exists() ) {
			$this->moveOverExistingRedirect( $nt, $reason );
			$pageCountChange = 0;
		} else { # Target didn't exist, do normal move.
			$this->moveToNewTitle( $nt, $newid, $reason );
			$pageCountChange = 1;
		}
		$redirid = $this->getArticleID();

		# Fixing category links (those without piped 'alternate' names) to be sorted under the new title
		$dbw =& wfGetDB( DB_MASTER );
		$categorylinks = $dbw->tableName( 'categorylinks' );
		$sql = "UPDATE $categorylinks SET cl_sortkey=" . $dbw->addQuotes( $nt->getPrefixedText() ) .
			" WHERE cl_from=" . $dbw->addQuotes( $pageid ) .
			" AND cl_sortkey=" . $dbw->addQuotes( $this->getPrefixedText() );
		$dbw->query( $sql, 'SpecialMovepage::doSubmit' );

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
		if ( $this->getNamespace() == NS_MAIN and $nt->getNamespace() != NS_MAIN ) {
			# Moved out of main namespace
			# not viewed, edited, removing
			$u = new SiteStatsUpdate( 0, 1, -1, $pageCountChange);
		} elseif ( $this->getNamespace() != NS_MAIN and $nt->getNamespace() == NS_MAIN ) {
			# Moved into main namespace
			# not viewed, edited, adding
			$u = new SiteStatsUpdate( 0, 1, +1, $pageCountChange );
		} elseif ( $pageCountChange ) {
			# Added redirect
			$u = new SiteStatsUpdate( 0, 0, 0, 1 );
		} else{
			$u = false;
		}
		if ( $u ) {
			$u->doUpdate();
		}

		global $wgUser;
		wfRunHooks( 'TitleMoveComplete', array( &$this, &$nt, &$wgUser, $pageid, $redirid ) );
		return true;
	}

	/**
	 * Move page to a title which is at present a redirect to the
	 * source page
	 *
	 * @param Title &$nt the page to move to, which should currently
	 * 	be a redirect
	 * @access private
	 */
	function moveOverExistingRedirect( &$nt, $reason = '' ) {
		global $wgUser, $wgLinkCache, $wgUseSquid, $wgMwRedir;
		$fname = 'Title::moveOverExistingRedirect';
		$comment = wfMsgForContent( '1movedto2', $this->getPrefixedText(), $nt->getPrefixedText() );

		if ( $reason ) {
			$comment .= ": $reason";
		}

		$now = wfTimestampNow();
		$rand = wfRandom();
		$newid = $nt->getArticleID();
		$oldid = $this->getArticleID();
		$dbw =& wfGetDB( DB_MASTER );
		$links = $dbw->tableName( 'links' );

		# Delete the old redirect. We don't save it to history since
		# by definition if we've got here it's rather uninteresting.
		# We have to remove it so that the next step doesn't trigger
		# a conflict on the unique namespace+title index...
		$dbw->delete( 'page', array( 'page_id' => $newid ), $fname );

		# Save a null revision in the page's history notifying of the move
		$nullRevision = Revision::newNullRevision( $dbw, $oldid,
			wfMsgForContent( '1movedto2', $this->getPrefixedText(), $nt->getPrefixedText() ),
			true );
		$nullRevId = $nullRevision->insertOn( $dbw );

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
		$wgLinkCache->clearLink( $nt->getPrefixedDBkey() );

		# Recreate the redirect, this time in the other direction.
		$redirectText = $wgMwRedir->getSynonym( 0 ) . ' [[' . $nt->getPrefixedText() . "]]\n";
		$redirectArticle = new Article( $this );
		$newid = $redirectArticle->insertOn( $dbw );
		$redirectRevision = new Revision( array(
			'page'    => $newid,
			'comment' => $comment,
			'text'    => $redirectText ) );
		$revid = $redirectRevision->insertOn( $dbw );
		$redirectArticle->updateRevisionOn( $dbw, $redirectRevision, 0 );
		$wgLinkCache->clearLink( $this->getPrefixedDBkey() );

		# Log the move
		$log = new LogPage( 'move' );
		$log->addEntry( 'move_redir', $this, $reason, array( 1 => $nt->getPrefixedText() ) );

		# Now, we record the link from the redirect to the new title.
		# It should have no other outgoing links...
		$dbw->delete( 'pagelinks', array( 'pl_from' => $newid ), $fname );
		$dbw->insert( 'pagelinks',
			array(
				'pl_from'      => $newid,
				'pl_namespace' => $nt->getNamespace(),
				'pl_title'     => $nt->getDbKey() ),
			$fname );

		# Purge squid
		if ( $wgUseSquid ) {
			$urls = array_merge( $nt->getSquidURLs(), $this->getSquidURLs() );
			$u = new SquidUpdate( $urls );
			$u->doUpdate();
		}
	}

	/**
	 * Move page to non-existing title.
	 * @param Title &$nt the new Title
	 * @param int &$newid set to be the new article ID
	 * @access private
	 */
	function moveToNewTitle( &$nt, &$newid, $reason = '' ) {
		global $wgUser, $wgLinkCache, $wgUseSquid;
		global $wgMwRedir;
		$fname = 'MovePageForm::moveToNewTitle';
		$comment = wfMsgForContent( '1movedto2', $this->getPrefixedText(), $nt->getPrefixedText() );
		if ( $reason ) {
			$comment .= ": $reason";
		}

		$newid = $nt->getArticleID();
		$oldid = $this->getArticleID();
		$dbw =& wfGetDB( DB_MASTER );
		$now = $dbw->timestamp();
		wfSeedRandom();
		$rand = wfRandom();

		# Save a null revision in the page's history notifying of the move
		$nullRevision = Revision::newNullRevision( $dbw, $oldid,
			wfMsgForContent( '1movedto2', $this->getPrefixedText(), $nt->getPrefixedText() ),
			true );
		$nullRevId = $nullRevision->insertOn( $dbw );

		# Rename cur entry
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

		$wgLinkCache->clearLink( $nt->getPrefixedDBkey() );

		# Insert redirect
		$redirectText = $wgMwRedir->getSynonym( 0 ) . ' [[' . $nt->getPrefixedText() . "]]\n";
		$redirectArticle = new Article( $this );
		$newid = $redirectArticle->insertOn( $dbw );
		$redirectRevision = new Revision( array(
			'page'    => $newid,
			'comment' => $comment,
			'text'    => $redirectText ) );
		$revid = $redirectRevision->insertOn( $dbw );
		$redirectArticle->updateRevisionOn( $dbw, $redirectRevision, 0 );
		$wgLinkCache->clearLink( $this->getPrefixedDBkey() );

		# Log the move
		$log = new LogPage( 'move' );
		$log->addEntry( 'move', $this, $reason, array( 1 => $nt->getPrefixedText()) );

		# Purge caches as per article creation
		Article::onArticleCreate( $nt );

		# Record the just-created redirect's linking to the page
		$dbw->insert( 'pagelinks',
			array(
				'pl_from'      => $newid,
				'pl_namespace' => $nt->getNamespace(),
				'pl_title'     => $nt->getDBkey() ),
			$fname );

		# Non-existent target may have had broken links to it; these must
		# now be touched to update link coloring.
		$nt->touchLinks();

		# Purge old title from squid
		# The new title, and links to the new title, are purged in Article::onArticleCreate()
		$titles = $nt->getLinksTo();
		if ( $wgUseSquid ) {
			$urls = $this->getSquidURLs();
			foreach ( $titles as $linkTitle ) {
				$urls[] = $linkTitle->getInternalURL();
			}
			$u = new SquidUpdate( $urls );
			$u->doUpdate();
		}
	}

	/**
	 * Checks if $this can be moved to a given Title
	 * - Selects for update, so don't call it unless you mean business
	 *
	 * @param Title &$nt the new title to check
	 * @access public
	 */
	function isValidMoveTarget( $nt ) {

		$fname = 'Title::isValidMoveTarget';
		$dbw =& wfGetDB( DB_MASTER );

		# Is it a redirect?
		$id  = $nt->getArticleID();
		$obj = $dbw->selectRow( array( 'page', 'revision', 'text'),
			array( 'page_is_redirect','old_text','old_flags' ),
			array( 'page_id' => $id, 'page_latest=rev_id', 'rev_text_id=old_id' ),
			$fname, 'FOR UPDATE' );

		if ( !$obj || 0 == $obj->page_is_redirect ) {
			# Not a redirect
			return false;
		}
		$text = Revision::getRevisionText( $obj );

		# Does the redirect point to the source?
		if ( preg_match( "/\\[\\[\\s*([^\\]\\|]*)]]/", $text, $m ) ) {
			$redirTitle = Title::newFromText( $m[1] );
			if( !is_object( $redirTitle ) ||
				$redirTitle->getPrefixedDBkey() != $this->getPrefixedDBkey() ) {
				return false;
			}
		} else {
			# Fail safe
			return false;
		}

		# Does the article have a history?
		$row = $dbw->selectRow( array( 'page', 'revision'),
			array( 'rev_id' ),
			array( 'page_namespace' => $nt->getNamespace(),
				'page_title' => $nt->getDBkey(),
				'page_id=rev_page AND page_latest != rev_id'
			), $fname, 'FOR UPDATE'
		);

		# Return true if there was no history
		return $row === false;
	}

	/**
	 * Create a redirect; fails if the title already exists; does
	 * not notify RC
	 *
	 * @param Title $dest the destination of the redirect
	 * @param string $comment the comment string describing the move
	 * @return bool true on success
	 * @access public
	 */
	function createRedirect( $dest, $comment ) {
		global $wgUser;
		if ( $this->getArticleID() ) {
			return false;
		}

		$fname = 'Title::createRedirect';
		$dbw =& wfGetDB( DB_MASTER );

		$article = new Article( $this );
		$newid = $article->insertOn( $dbw );
		$revision = new Revision( array(
			'page'      => $newid,
			'comment'   => $comment,
			'text'      => "#REDIRECT [[" . $dest->getPrefixedText() . "]]\n",
			) );
		$revisionId = $revision->insertOn( $dbw );
		$article->updateRevisionOn( $dbw, $revision, 0 );

		# Link table
		$dbw->insert( 'pagelinks',
			array(
				'pl_from'      => $newid,
				'pl_namespace' => $dest->getNamespace(),
				'pl_title'     => $dest->getDbKey()
			), $fname
		);

		Article::onArticleCreate( $this );
		return true;
	}

	/**
	 * Get categories to which this Title belongs and return an array of
	 * categories' names.
	 *
	 * @return array an array of parents in the form:
	 *	$parent => $currentarticle
	 * @access public
	 */
	function getParentCategories() {
		global $wgContLang,$wgUser;

		$titlekey = $this->getArticleId();
		$sk =& $wgUser->getSkin();
		$parents = array();
		$dbr =& wfGetDB( DB_SLAVE );
		$categorylinks = $dbr->tableName( 'categorylinks' );

		# NEW SQL
		$sql = "SELECT * FROM $categorylinks"
		     ." WHERE cl_from='$titlekey'"
			 ." AND cl_from <> '0'"
			 ." ORDER BY cl_sortkey";

		$res = $dbr->query ( $sql ) ;

		if($dbr->numRows($res) > 0) {
			while ( $x = $dbr->fetchObject ( $res ) )
				//$data[] = Title::newFromText($wgContLang->getNSText ( NS_CATEGORY ).':'.$x->cl_to);
				$data[$wgContLang->getNSText ( NS_CATEGORY ).':'.$x->cl_to] = $this->getFullText();
			$dbr->freeResult ( $res ) ;
		} else {
			$data = '';
		}
		return $data;
	}

	/**
	 * Get a tree of parent categories
	 * @param array $children an array with the children in the keys, to check for circular refs
	 * @return array
	 * @access public
	 */
	function getParentCategoryTree( $children = array() ) {
		$parents = $this->getParentCategories();

		if($parents != '') {
			foreach($parents as $parent => $current)
			{
				if ( array_key_exists( $parent, $children ) ) {
					# Circular reference
					$stack[$parent] = array();
				} else {
					$nt = Title::newFromText($parent);
					$stack[$parent] = $nt->getParentCategoryTree( $children + array($parent => 1) );
				}
			}
			return $stack;
		} else {
			return array();
		}
	}


	/**
	 * Get an associative array for selecting this title from
	 * the "cur" table
	 *
	 * @return array
	 * @access public
	 */
	function curCond() {
		wfDebugDieBacktrace( 'curCond called' );
		return array( 'cur_namespace' => $this->mNamespace, 'cur_title' => $this->mDbkeyform );
	}

	/**
	 * Get an associative array for selecting this title from the
	 * "old" table
	 *
	 * @return array
	 * @access public
	 */
	function oldCond() {
		wfDebugDieBacktrace( 'oldCond called' );
		return array( 'old_namespace' => $this->mNamespace, 'old_title' => $this->mDbkeyform );
	}

	/**
	 * Get the revision ID of the previous revision
	 *
	 * @param integer $revision  Revision ID. Get the revision that was before this one.
	 * @return interger $oldrevision|false
	 */
	function getPreviousRevisionID( $revision ) {
		$dbr =& wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'revision', 'rev_id',
			'rev_page=' . IntVal( $this->getArticleId() ) .
			' AND rev_id<' . IntVal( $revision ) . ' ORDER BY rev_id DESC' );
	}

	/**
	 * Get the revision ID of the next revision
	 *
	 * @param integer $revision  Revision ID. Get the revision that was after this one.
	 * @return interger $oldrevision|false
	 */
	function getNextRevisionID( $revision ) {
		$dbr =& wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'revision', 'rev_id',
			'rev_page=' . IntVal( $this->getArticleId() ) .
			' AND rev_id>' . IntVal( $revision ) . ' ORDER BY rev_id' );
	}

	/**
	 * Compare with another title.
	 *
	 * @param Title $title
	 * @return bool
	 */
	function equals( $title ) {
		return $this->getInterwiki() == $title->getInterwiki()
			&& $this->getNamespace() == $title->getNamespace()
			&& $this->getDbkey() == $title->getDbkey();
	}

	/**
	 * Check if page exists
	 * @return bool
	 */
	function exists() {
		return $this->getArticleId() != 0;
	}

	/**
	 * Should a link should be displayed as a known link, just based on its title?
	 *
	 * Currently, a self-link with a fragment, special pages and image pages are in
	 * this category. Special pages never exist in the database. Some images do not
	 * have description pages in the database, but the description page contains
	 * useful history information that the user may want to link to.
	 */
	function isAlwaysKnown() {
		return  $this->isExternal() || ( 0 == $this->mNamespace && "" == $this->mDbkeyform )
		  || NS_SPECIAL == $this->mNamespace || NS_IMAGE == $this->mNamespace;
	}

	/**
	 * Update page_touched timestamps on pages linking to this title.
	 * In principal, this could be backgrounded and could also do squid
	 * purging.
	 */
	function touchLinks() {
		$fname = 'Title::touchLinks';

		$dbw =& wfGetDB( DB_MASTER );

		$res = $dbw->select( 'pagelinks',
			array( 'pl_from' ),
			array(
				'pl_namespace' => $this->getNamespace(),
				'pl_title'     => $this->getDbKey() ),
			$fname );
		if ( 0 == $dbw->numRows( $res ) ) {
			return;
		}

		$arr = array();
		$toucharr = array();
		while( $row = $dbw->fetchObject( $res ) ) {
			$toucharr[] = $row->pl_from;
		}

		$dbw->update( 'page', /* SET */ array( 'page_touched' => $dbw->timestamp() ),
							/* WHERE */ array( 'page_id' => $toucharr ),$fname);
	}

	function trackbackURL() {
		global $wgTitle, $wgScriptPath, $wgServer;

		return "$wgServer$wgScriptPath/trackback.php?article="
			. htmlspecialchars(urlencode($wgTitle->getPrefixedDBkey()));
	}

	function trackbackRDF() {
		$url = htmlspecialchars($this->getFullURL());
		$title = htmlspecialchars($this->getText());
		$tburl = $this->trackbackURL();

		return "
<rdf:RDF xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
         xmlns:dc=\"http://purl.org/dc/elements/1.1/\"
         xmlns:trackback=\"http://madskills.com/public/xml/rss/module/trackback/\">
<rdf:Description
   rdf:about=\"$url\"
   dc:identifier=\"$url\"
   dc:title=\"$title\"
   trackback:ping=\"$tburl\" />
</rdf:RDF>";
	}
}
?>

<?php
/**
 * See title.txt
 *
 */

/** */
if ( !class_exists( 'UtfNormal' ) ) {
	require_once( dirname(__FILE__) . '/normal/UtfNormal.php' );
}

define ( 'GAID_FOR_UPDATE', 1 );

# Title::newFromTitle maintains a cache to avoid
# expensive re-normalization of commonly used titles.
# On a batch operation this can become a memory leak
# if not bounded. After hitting this many titles,
# reset the cache.
define( 'MW_TITLECACHE_MAX', 1000 );

# Constants for pr_cascade bitfield
define( 'CASCADE', 1 );

/**
 * Title class
 * - Represents a title, which may contain an interwiki designation or namespace
 * - Can fetch various kinds of data from the database, albeit inefficiently.
 *
 */
class Title {
	/**
	 * Static cache variables
	 */
	static private $titleCache=array();
	static private $interwikiCache=array();
	
	
	/**
	 * All member variables should be considered private
	 * Please use the accessor functions
	 */

	 /**#@+
	 * @private
	 */

	var $mTextform;           	# Text form (spaces not underscores) of the main part
	var $mUrlform;            	# URL-encoded form of the main part
	var $mDbkeyform;          	# Main part with underscores
	var $mNamespace;          	# Namespace index, i.e. one of the NS_xxxx constants
	var $mInterwiki;          	# Interwiki prefix (or null string)
	var $mFragment;           	# Title fragment (i.e. the bit after the #)
	var $mArticleID;          	# Article ID, fetched from the link cache on demand
	var $mLatestID;         	# ID of most recent revision
	var $mRestrictions;       	# Array of groups allowed to edit this article
	var $mCascadeRestriction;	# Cascade restrictions on this page to included templates and images?
	var $mRestrictionsExpiry;	# When do the restrictions on this page expire?
	var $mHasCascadingRestrictions;	# Are cascading restrictions in effect on this page?
	var $mCascadeRestrictionSources;# Where are the cascading restrictions coming from on this page?
	var $mRestrictionsLoaded; 	# Boolean for initialisation on demand
	var $mPrefixedText;       	# Text form including namespace/interwiki, initialised on demand
	var $mDefaultNamespace;   	# Namespace index when there is no namespace
	                    		# Zero except in {{transclusion}} tags
	var $mWatched;      		# Is $wgUser watching this page? NULL if unfilled, accessed through userIsWatching()
	/**#@-*/


	/**
	 * Constructor
	 * @private
	 */
	/* private */ function __construct() {
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
		$this->mOldRestrictions = false;
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
				if( $cachedcount >= MW_TITLECACHE_MAX ) {
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
	 * Create a new Title from URL-encoded text. Ensures that
	 * the given title's length does not exceed the maximum.
	 * @param string $url the title, as might be taken from a URL
	 * @return Title the new object, or NULL on an error
	 * @static
	 * @access public
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
	 * @todo This is inefficiently implemented, the page row is requested
	 *       but not used for anything else
	 *
	 * @param int $id the page_id corresponding to the Title to create
	 * @return Title the new object, or NULL on an error
	 * @access public
	 * @static
	 */
	public static function newFromID( $id ) {
		$fname = 'Title::newFromID';
		$dbr = wfGetDB( DB_SLAVE );
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
	 * Make an array of titles from an array of IDs 
	 */
	function newFromIDs( $ids ) {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'page', array( 'page_namespace', 'page_title' ),
			'page_id IN (' . $dbr->makeList( $ids ) . ')', __METHOD__ );

		$titles = array();
		while ( $row = $dbr->fetchObject( $res ) ) {
			$titles[] = Title::makeTitle( $row->page_namespace, $row->page_title );
		}
		return $titles;
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
	public static function &makeTitle( $ns, $title ) {
		$t = new Title();
		$t->mInterwiki = '';
		$t->mFragment = '';
		$t->mNamespace = intval( $ns );
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
	 * @param int $ns the namespace of the article
	 * @param string $title the database key form
	 * @return Title the new object, or NULL on an error
	 * @static
	 * @access public
	 */
	public static function makeTitleSafe( $ns, $title ) {
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
	public static function newMainPage() {
		return Title::newFromText( wfMsgForContent( 'mainpage' ) );
	}

	/**
	 * Create a new Title for a redirect
	 * @param string $text the redirect title text
	 * @return Title the new object, or NULL if the text is not a
	 *	valid redirect
	 */
	public static function newFromRedirect( $text ) {
		$mwRedir = MagicWord::get( 'redirect' );
		$rt = NULL;
		if ( $mwRedir->matchStart( $text ) ) {
			$m = array();
			if ( preg_match( '/\[{2}(.*?)(?:\||\]{2})/', $text, $m ) ) {
				# categories are escaped using : for example one can enter:
				# #REDIRECT [[:Category:Music]]. Need to remove it.
				if ( substr($m[1],0,1) == ':') {
					# We don't want to keep the ':'
					$m[1] = substr( $m[1], 1 );
				}

				$rt = Title::newFromText( $m[1] );
				# Disallow redirects to Special:Userlogout
				if ( !is_null($rt) && $rt->isSpecial( 'Userlogout' ) ) {
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
		$dbr = wfGetDB( DB_SLAVE );

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
	public static function legalChars() {
		global $wgLegalTitleChars;
		return $wgLegalTitleChars;
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
	public static function makeName( $ns, $title ) {
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
	function getInterwikiLink( $key )  {
		global $wgMemc, $wgInterwikiExpiry;
		global $wgInterwikiCache, $wgContLang;
		$fname = 'Title::getInterwikiLink';

		$key = $wgContLang->lc( $key );

		$k = wfMemcKey( 'interwiki', $key );
		if( array_key_exists( $k, Title::$interwikiCache ) ) {
			return Title::$interwikiCache[$k]->iw_url;
		}

		if ($wgInterwikiCache) {
			return Title::getInterwikiCached( $key );
		}

		$s = $wgMemc->get( $k );
		# Ignore old keys with no iw_local
		if( $s && isset( $s->iw_local ) && isset($s->iw_trans)) {
			Title::$interwikiCache[$k] = $s;
			return $s->iw_url;
		}

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'interwiki',
			array( 'iw_url', 'iw_local', 'iw_trans' ),
			array( 'iw_prefix' => $key ), $fname );
		if( !$res ) {
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
		Title::$interwikiCache[$k] = $s;

		return $s->iw_url;
	}
	
	/**
	 * Fetch interwiki prefix data from local cache in constant database
	 *
	 * More logic is explained in DefaultSettings
	 *
	 * @return string URL of interwiki site
	 * @access public
	 */
	function getInterwikiCached( $key ) {
		global $wgInterwikiCache, $wgInterwikiScopes, $wgInterwikiFallbackSite;
		static $db, $site;

		if (!$db)
			$db=dba_open($wgInterwikiCache,'r','cdb');
		/* Resolve site name */
		if ($wgInterwikiScopes>=3 and !$site) {
			$site = dba_fetch('__sites:' . wfWikiID(), $db);
			if ($site=="")
				$site = $wgInterwikiFallbackSite;
		}
		$value = dba_fetch( wfMemcKey( $key ), $db);
		if ($value=='' and $wgInterwikiScopes>=3) {
			/* try site-level */
			$value = dba_fetch("_{$site}:{$key}", $db);
		}
		if ($value=='' and $wgInterwikiScopes>=2) {
			/* try globals */
			$value = dba_fetch("__global:{$key}", $db);
		}
		if ($value=='undef')
			$value='';
		$s = (object)false;
		$s->iw_url = '';
		$s->iw_local = 0;
		$s->iw_trans = 0;
		if ($value!='') {
			list($local,$url)=explode(' ',$value,2);
			$s->iw_url=$url;
			$s->iw_local=(int)$local;
		}
		Title::$interwikiCache[wfMemcKey( 'interwiki', $key )] = $s;
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
		if ( $this->mInterwiki != '' ) {
			# Make sure key is loaded into cache
			$this->getInterwikiLink( $this->mInterwiki );
			$k = wfMemcKey( 'interwiki', $this->mInterwiki );
			return (bool)(Title::$interwikiCache[$k]->iw_local);
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
		if ($this->mInterwiki == '')
			return false;
		# Make sure key is loaded into cache
		$this->getInterwikiLink( $this->mInterwiki );
		$k = wfMemcKey( 'interwiki', $this->mInterwiki );
		return (bool)(Title::$interwikiCache[$k]->iw_trans);
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

		if ( count( $titles ) == 0 ) {
			return;
		}
		$dbw = wfGetDB( DB_MASTER );
		if ( $timestamp == '' ) {
			$timestamp = $dbw->timestamp();
		}
		/*
		$page = $dbw->tableName( 'page' );
		$sql = "UPDATE $page SET page_touched='{$timestamp}' WHERE page_id IN (";
		$first = true;

		foreach ( $titles as $title ) {
			if ( $wgUseFileCache ) {
				$cm = new HTMLFileCache($title);
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

	/**
	 * Escape a text fragment, say from a link, for a URL
	 */
	static function escapeFragmentForURL( $fragment ) {
		$fragment = str_replace( ' ', '_', $fragment );
		$fragment = urlencode( Sanitizer::decodeCharReferences( $fragment ) );
		$replaceArray = array(
			'%3A' => ':',
			'%' => '.'
		);
		return strtr( $fragment, $replaceArray );
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
	 * Get the namespace text
	 * @return string
	 * @access public
	 */
	function getNsText() {
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
	 * Get the namespace text of the subject (rather than talk) page
	 * @return string
	 * @access public
	 */
	function getSubjectNsText() {
		global $wgContLang;
		return $wgContLang->getNsText( Namespace::getSubject( $this->mNamespace ) );
	}

	/**
	 * Get the namespace text of the talk page
	 * @return string
	 */
	function getTalkNsText() {
		global $wgContLang;
		return( $wgContLang->getNsText( Namespace::getTalk( $this->mNamespace ) ) );
	}
	
	/**
	 * Could this title have a corresponding talk page?
	 * @return bool
	 */
	function canTalk() {
		return( Namespace::canTalk( $this->mNamespace ) );
	}
	
	/**
	 * Get the interwiki prefix (or null string)
	 * @return string
	 * @access public
	 */
	function getInterwiki() { return $this->mInterwiki; }
	/**
	 * Get the Title fragment (i.e. the bit after the #) in text form
	 * @return string
	 * @access public
	 */
	function getFragment() { return $this->mFragment; }
	/**
	 * Get the fragment in URL form, including the "#" character if there is one
	 *
	 * @return string
	 * @access public
	 */
	function getFragmentForURL() {
		if ( $this->mFragment == '' ) {
			return '';
		} else {
			return '#' . Title::escapeFragmentForURL( $this->mFragment );
		}
	}
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
	 * @return string the prefixed title, with spaces and
	 * 	the fragment, including '#'
	 * @access public
	 */
	function getFullText() {
		$text = $this->getPrefixedText();
		if( '' != $this->mFragment ) {
			$text .= '#' . $this->mFragment;
		}
		return $text;
	}

	/**
	 * Get the base name, i.e. the leftmost parts before the /
	 * @return string Base name
	 */
	function getBaseText() {
		global $wgNamespacesWithSubpages;
		if( isset( $wgNamespacesWithSubpages[ $this->mNamespace ] ) && $wgNamespacesWithSubpages[ $this->mNamespace ] ) {
			$parts = explode( '/', $this->getText() );
			# Don't discard the real title if there's no subpage involved
			if( count( $parts ) > 1 )
				unset( $parts[ count( $parts ) - 1 ] );
			return implode( '/', $parts );
		} else {
			return $this->getText();
		}
	}

	/**
	 * Get the lowest-level subpage name, i.e. the rightmost part after /
	 * @return string Subpage name
	 */
	function getSubpageText() {
		global $wgNamespacesWithSubpages;
		if( isset( $wgNamespacesWithSubpages[ $this->mNamespace ] ) && $wgNamespacesWithSubpages[ $this->mNamespace ] ) {
			$parts = explode( '/', $this->mTextform );
			return( $parts[ count( $parts ) - 1 ] );
		} else {
			return( $this->mTextform );
		}
	}
	
	/**
	 * Get a URL-encoded form of the subpage text
	 * @return string URL-encoded subpage name
	 */
	function getSubpageUrlForm() {
		$text = $this->getSubpageText();
		$text = wfUrlencode( str_replace( ' ', '_', $text ) );
		$text = str_replace( '%28', '(', str_replace( '%29', ')', $text ) ); # Clean up the URL; per below, this might not be safe
		return( $text );
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
	 * @param string $variant language variant of url (for sr, zh..)
	 * @return string the URL
	 * @access public
	 */
	function getFullURL( $query = '', $variant = false ) {
		global $wgContLang, $wgServer, $wgRequest;

		if ( '' == $this->mInterwiki ) {
			$url = $this->getLocalUrl( $query, $variant );

			// Ugly quick hack to avoid duplicate prefixes (bug 4571 etc)
			// Correct fix would be to move the prepending elsewhere.
			if ($wgRequest->getVal('action') != 'render') {
				$url = $wgServer . $url;
			}
		} else {
			$baseUrl = $this->getInterwikiLink( $this->mInterwiki );

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
	 * @param string $query an optional query string; if not specified,
	 * 	$wgArticlePath will be used.
	 * @param string $variant language variant of url (for sr, zh..)
	 * @return string the URL
	 * @access public
	 */
	function getLocalURL( $query = '', $variant = false ) {
		global $wgArticlePath, $wgScript, $wgServer, $wgRequest;
		global $wgVariantArticlePath, $wgContLang, $wgUser;

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
				if($variant!=false && $wgContLang->hasVariants()){
					if($wgVariantArticlePath==false)
						$variantArticlePath =  "$wgScript?title=$1&variant=$2"; // default
					else 
						$variantArticlePath = $wgVariantArticlePath;
					
					$url = str_replace( '$2', urlencode( $variant ), $variantArticlePath );
					$url = str_replace( '$1', $dbkey, $url  );
					
				}
				else 
					$url = str_replace( '$1', $dbkey, $wgArticlePath );
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
						if( $query != '' ) $url .= '?' . $query;
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
	 * @param string $variant language variant of url (for sr, zh..)
	 * @return string the URL
	 * @access public
	 */
	function getInternalURL( $query = '', $variant = false ) {
		global $wgInternalServer;
		$url = $wgInternalServer . $this->getLocalURL( $query, $variant );
		wfRunHooks( 'GetInternalURL', array( &$this, &$url, $query ) );
		return $url;
	}

	/**
	 * Get the edit URL for this Title
	 * @return string the URL, or a null string if this is an
	 * 	interwiki link
	 * @access public
	 */
	function getEditURL() {
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
	 * Is this page "semi-protected" - the *only* protection is autoconfirm?
	 *
	 * @param string Action to check (default: edit)
	 * @return bool
	 */
	function isSemiProtected( $action = 'edit' ) {
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
	 * @param string $what the action the page is protected from,
	 *	by default checks move and edit
	 * @return boolean
	 * @access public
	 */
	function isProtected( $action = '' ) {
		global $wgRestrictionLevels;

		# Special pages have inherent protection
		if( $this->getNamespace() == NS_SPECIAL )
			return true;

		# Check regular protection levels				
		if( $action == 'edit' || $action == '' ) {
			$r = $this->getRestrictions( 'edit' );
			foreach( $wgRestrictionLevels as $level ) {
				if( in_array( $level, $r ) && $level != '' ) {
					return( true );
				}
			}
		}
		
		if( $action == 'move' || $action == '' ) {
			$r = $this->getRestrictions( 'move' );
			foreach( $wgRestrictionLevels as $level ) {
				if( in_array( $level, $r ) && $level != '' ) {
					return( true );
				}
			}
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
	 * @param string $action action that permission needs to be checked for
	 * @return boolean
 	 */
	public function quickUserCan( $action ) {
		return $this->userCan( $action, false );
	}

 	/**
	 * Can $wgUser perform $action on this page?
	 * @param string $action action that permission needs to be checked for
	 * @param bool $doExpensiveQueries Set this to false to avoid doing unnecessary queries.
	 * @return boolean
 	 */
	public function userCan( $action, $doExpensiveQueries = true ) {
		$fname = 'Title::userCan';
		wfProfileIn( $fname );

		global $wgUser, $wgNamespaceProtection;

		$result = null;
		wfRunHooks( 'userCan', array( &$this, &$wgUser, $action, &$result ) );
		if ( $result !== null ) {
			wfProfileOut( $fname );
			return $result;
		}

		if( NS_SPECIAL == $this->mNamespace ) {
			wfProfileOut( $fname );
			return false;
		}
		
		if ( array_key_exists( $this->mNamespace, $wgNamespaceProtection ) ) {
			$nsProt = $wgNamespaceProtection[ $this->mNamespace ];
			if ( !is_array($nsProt) ) $nsProt = array($nsProt);
			foreach( $nsProt as $right ) {
				if( '' != $right && !$wgUser->isAllowed( $right ) ) {
					wfProfileOut( $fname );
					return false;
				}
			}
		}

		if( $this->mDbkeyform == '_' ) {
			# FIXME: Is this necessary? Shouldn't be allowed anyway...
			wfProfileOut( $fname );
			return false;
		}

		# protect css/js subpages of user pages
		# XXX: this might be better using restrictions
		# XXX: Find a way to work around the php bug that prevents using $this->userCanEditCssJsSubpage() from working
		if( $this->isCssJsSubpage()
			&& !$wgUser->isAllowed('editinterface')
			&& !preg_match('/^'.preg_quote($wgUser->getName(), '/').'\//', $this->mTextform) ) {
			wfProfileOut( $fname );
			return false;
		}
		
		if ( $doExpensiveQueries && !$this->isCssJsSubpage() ) {
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
					if( '' != $right && !$wgUser->isAllowed( $right ) ) {
						wfProfileOut( $fname );
						return false;
					}
				}
			}
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

		if( $action == 'create' ) {
			if( (  $this->isTalkPage() && !$wgUser->isAllowed( 'createtalk' ) ) ||
				( !$this->isTalkPage() && !$wgUser->isAllowed( 'createpage' ) ) ) {
				wfProfileOut( $fname );
				return false;
			}
		}

		wfProfileOut( $fname );
		return true;
	}

	/**
	 * Can $wgUser edit this page?
	 * @return boolean
	 * @deprecated use userCan('edit')
	 */
	public function userCanEdit( $doExpensiveQueries = true ) {
		return $this->userCan( 'edit', $doExpensiveQueries );
	}

	/**
	 * Can $wgUser create this page?
	 * @return boolean
	 * @deprecated use userCan('create')
	 */
	public function userCanCreate( $doExpensiveQueries = true ) {
		return $this->userCan( 'create', $doExpensiveQueries );
	}

	/**
	 * Can $wgUser move this page?
	 * @return boolean
	 * @deprecated use userCan('move')
	 */
	public function userCanMove( $doExpensiveQueries = true ) {
		return $this->userCan( 'move', $doExpensiveQueries );
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
	 * @todo fold these checks into userCan()
	 */
	public function userCanRead() {
		global $wgUser;

		$result = null;
		wfRunHooks( 'userCan', array( &$this, &$wgUser, 'read', &$result ) );
		if ( $result !== null ) {
			return $result;
		}

		if( $wgUser->isAllowed('read') ) {
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
	 * Is this a subpage?
	 * @return bool
	 * @access public
	 */
	function isSubpage() {
		global $wgNamespacesWithSubpages;
		
		if( isset( $wgNamespacesWithSubpages[ $this->mNamespace ] ) ) {
			return ( strpos( $this->getText(), '/' ) !== false && $wgNamespacesWithSubpages[ $this->mNamespace ] == true );
		} else {
			return false;
		}
	}

	/**
	 * Is this a .css or .js subpage of a user page?
	 * @return bool
	 * @access public
	 */
	function isCssJsSubpage() {
		return ( NS_USER == $this->mNamespace and preg_match("/\\/.*\\.(?:css|js)$/", $this->mTextform ) );
	}
	/**
	 * Is this a *valid* .css or .js subpage of a user page?
	 * Check that the corresponding skin exists
	 */
	function isValidCssJsSubpage() {
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
	function getSkinFromCssJsSubpage() {
		$subpage = explode( '/', $this->mTextform );
		$subpage = $subpage[ count( $subpage ) - 1 ];
		return( str_replace( array( '.css', '.js' ), array( '', '' ), $subpage ) );
	}
	/**
	 * Is this a .css subpage of a user page?
	 * @return bool
	 * @access public
	 */
	function isCssSubpage() {
		return ( NS_USER == $this->mNamespace and preg_match("/\\/.*\\.css$/", $this->mTextform ) );
	}
	/**
	 * Is this a .js subpage of a user page?
	 * @return bool
	 * @access public
	 */
	function isJsSubpage() {
		return ( NS_USER == $this->mNamespace and preg_match("/\\/.*\\.js$/", $this->mTextform ) );
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
	 * Cascading protection: Return true if cascading restrictions apply to this page, false if not.
	 *
	 * @return bool If the page is subject to cascading restrictions.
	 * @access public
	 */
	function isCascadeProtected() {
		list( $sources, $restrictions ) = $this->getCascadeProtectionSources( false );
		return ( $sources > 0 );
	}

	/**
	 * Cascading protection: Get the source of any cascading restrictions on this page.
	 *
	 * @param $get_pages bool Whether or not to retrieve the actual pages that the restrictions have come from.
	 * @return array( mixed title array, restriction array)
	 * Array of the Title objects of the pages from which cascading restrictions have come, false for none, or true if such restrictions exist, but $get_pages was not set.
	 * The restriction array is an array of each type, each of which contains an array of unique groups
	 * @access public
	 */
	function getCascadeProtectionSources( $get_pages = true ) {
		global $wgEnableCascadingProtection, $wgRestrictionTypes;

		# Define our dimension of restrictions types
		$pagerestrictions = array();
		foreach( $wgRestrictionTypes as $action )
			$pagerestrictions[$action] = array();

		if (!$wgEnableCascadingProtection)
			return array( false, $pagerestrictions );

		if ( isset( $this->mCascadeSources ) && $get_pages ) {
			return array( $this->mCascadeSources, $this->mCascadingRestrictions );
		} else if ( isset( $this->mHasCascadingRestrictions ) && !$get_pages ) {
			return array( $this->mHasCascadingRestrictions, $pagerestrictions );
		}

		wfProfileIn( __METHOD__ );

		$dbr = wfGetDb( DB_SLAVE );

		if ( $this->getNamespace() == NS_IMAGE ) {
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
		
		while( $row = $dbr->fetchObject( $res ) ) {
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
	 * @param resource $res restrictions as an SQL result.
	 * @access public
	 */
	function loadRestrictionsFromRow( $res, $oldFashionedRestrictions = NULL ) {
		$dbr = wfGetDb( DB_SLAVE );

		$this->mRestrictions['edit'] = array();
		$this->mRestrictions['move'] = array();

		# Backwards-compatibility: also load the restrictions from the page record (old format).

		if ( $oldFashionedRestrictions == NULL ) {
			$oldFashionedRestrictions = $dbr->selectField( 'page', 'page_restrictions', array( 'page_id' => $this->getArticleId() ), __METHOD__ );
		}

		if ($oldFashionedRestrictions != '') {

			foreach( explode( ':', trim( $oldFashionedRestrictions ) ) as $restrict ) {
				$temp = explode( '=', trim( $restrict ) );
				if(count($temp) == 1) {
					// old old format should be treated as edit/move restriction
					$this->mRestrictions["edit"] = explode( ',', trim( $temp[0] ) );
					$this->mRestrictions["move"] = explode( ',', trim( $temp[0] ) );
				} else {
					$this->mRestrictions[$temp[0]] = explode( ',', trim( $temp[1] ) );
				}
			}

			$this->mOldRestrictions = true;
			$this->mCascadeRestriction = false;
			$this->mRestrictionsExpiry = Block::decodeExpiry('');

		}

		if( $dbr->numRows( $res ) ) {
			# Current system - load second to make them override.
			$now = wfTimestampNow();
			$purgeExpired = false;

			while ($row = $dbr->fetchObject( $res ) ) {
				# Cycle through all the restrictions.

				// This code should be refactored, now that it's being used more generally,
				// But I don't really see any harm in leaving it in Block for now -werdna
				$expiry = Block::decodeExpiry( $row->pr_expiry );

				// Only apply the restrictions if they haven't expired!
				if ( !$expiry || $expiry > $now ) {
					$this->mRestrictionsExpiry = $expiry;
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

	function loadRestrictions( $oldFashionedRestrictions = NULL ) {
		if( !$this->mRestrictionsLoaded ) {
			$dbr = wfGetDB( DB_SLAVE );
		
			$res = $dbr->select( 'page_restrictions', '*',
				array ( 'pr_page' => $this->getArticleId() ), __METHOD__ );

			$this->loadRestrictionsFromRow( $res, $oldFashionedRestrictions );
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
	}

	/**
	 * Accessor/initialisation for mRestrictions
	 *
	 * @access public
	 * @param string $action action that permission needs to be checked for
	 * @return array the array of groups allowed to edit this article
	 */
	function getRestrictions( $action ) {
		if( $this->exists() ) {
			if( !$this->mRestrictionsLoaded ) {
				$this->loadRestrictions();
			}
			return isset( $this->mRestrictions[$action] )
					? $this->mRestrictions[$action]
					: array();
		} else {
			return array();
		}
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
			$dbr = wfGetDB( DB_SLAVE );
			$n = $dbr->selectField( 'archive', 'COUNT(*)', array( 'ar_namespace' => $this->getNamespace(),
				'ar_title' => $this->getDBkey() ), $fname );
			if( $this->getNamespace() == NS_IMAGE ) {
				$n += $dbr->selectField( 'filearchive', 'COUNT(*)',
					array( 'fa_name' => $this->getDBkey() ), $fname );
			}
		}
		return (int)$n;
	}

	/**
	 * Get the article ID for this Title from the link cache,
	 * adding it if necessary
	 * @param int $flags a bit field; may be GAID_FOR_UPDATE to select
	 * 	for update
	 * @return int the ID
	 */
	public function getArticleID( $flags = 0 ) {
		$linkCache =& LinkCache::singleton();
		if ( $flags & GAID_FOR_UPDATE ) {
			$oldUpdate = $linkCache->forUpdate( true );
			$this->mArticleID = $linkCache->addLinkObj( $this );
			$linkCache->forUpdate( $oldUpdate );
		} else {
			if ( -1 == $this->mArticleID ) {
				$this->mArticleID = $linkCache->addLinkObj( $this );
			}
		}
		return $this->mArticleID;
	}

	function getLatestRevID() {
		if ($this->mLatestID !== false)
			return $this->mLatestID;

		$db = wfGetDB(DB_SLAVE);
		return $this->mLatestID = $db->selectField( 'revision',
			"max(rev_id)",
			array('rev_page' => $this->getArticleID()),
			'Title::getLatestRevID' );
	}

	/**
	 * This clears some fields in this object, and clears any associated
	 * keys in the "bad links" section of the link cache.
	 *
	 * - This is called from Article::insertNewArticle() to allow
	 * loading of the new page_id. It's also called from
	 * Article::doDeleteArticle()
	 *
	 * @param int $newid the new Article ID
	 * @access public
	 */
	function resetArticleID( $newid ) {
		$linkCache =& LinkCache::singleton();
		$linkCache->clearBadLink( $this->getPrefixedDBkey() );

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

		$dbw = wfGetDB( DB_MASTER );
		$success = $dbw->update( 'page',
			array( /* SET */
				'page_touched' => $dbw->timestamp()
			), array( /* WHERE */
				'page_namespace' => $this->getNamespace() ,
				'page_title' => $this->getDBkey()
			), 'Title::invalidateCache'
		);

		if ($wgUseFileCache) {
			$cache = new HTMLFileCache($this);
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
	 * @return bool true on success
	 * @private
	 */
	/* private */ function secureAndSplit() {
		global $wgContLang, $wgLocalInterwiki, $wgCapitalLinks;

		# Initialisation
		static $rxTc = false;
		if( !$rxTc ) {
			# % is needed as well
			$rxTc = '/[^' . Title::legalChars() . ']|%[0-9A-Fa-f]{2}/S';
		}

		$this->mInterwiki = $this->mFragment = '';
		$this->mNamespace = $this->mDefaultNamespace; # Usually NS_MAIN
		
		$dbkey = $this->mDbkeyform;

		# Strip Unicode bidi override characters.
		# Sometimes they slip into cut-n-pasted page titles, where the
		# override chars get included in list displays.
		$dbkey = str_replace( "\xE2\x80\x8E", '', $dbkey ); // 200E LEFT-TO-RIGHT MARK
		$dbkey = str_replace( "\xE2\x80\x8F", '', $dbkey ); // 200F RIGHT-TO-LEFT MARK
		
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
		}

		# Namespace or interwiki prefix
		$firstPass = true;
		do {
			$m = array();
			if ( preg_match( "/^(.+?)_*:_*(.*)$/S", $dbkey, $m ) ) {
				$p = $m[1];
				$lowerNs = $wgContLang->lc( $p );
				if ( $ns = Namespace::getCanonicalIndex( $lowerNs ) ) {
					# Canonical namespace
					$dbkey = $m[2];
					$this->mNamespace = $ns;
				} elseif ( $ns = $wgContLang->getNsIndex( $lowerNs )) {
					# Ordinary namespace
					$dbkey = $m[2];
					$this->mNamespace = $ns;
				} elseif( $this->getInterwikiLink( $p ) ) {
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
		 * Pages with "/./" or "/../" appearing in the URLs will
		 * often be unreachable due to the way web browsers deal
		 * with 'relative' URLs. Forbid them explicitly.
		 */
		if ( strpos( $dbkey, '.' ) !== false &&
		     ( $dbkey === '.' || $dbkey === '..' ||
		       strpos( $dbkey, './' ) === 0  ||
		       strpos( $dbkey, '../' ) === 0 ||
		       strpos( $dbkey, '/./' ) !== false ||
		       strpos( $dbkey, '/../' ) !== false ) )
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
	 * Set the fragment for this title
	 * This is kind of bad, since except for this rarely-used function, Title objects
	 * are immutable. The reason this is here is because it's better than setting the 
	 * members directly, which is what Linker::formatComment was doing previously.
	 *
	 * @param string $fragment text
	 * @access public
	 * @todo clarify whether access is supposed to be public (was marked as "kind of public")
	 */
	function setFragment( $fragment ) {
		$this->mFragment = str_replace( '_', ' ', substr( $fragment, 1 ) );
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
	 * WARNING: do not use this function on arbitrary user-supplied titles!
	 * On heavily-used templates it will max out the memory.
	 *
	 * @param string $options may be FOR UPDATE
	 * @return array the Title objects linking here
	 * @access public
	 */
	function getLinksTo( $options = '', $table = 'pagelinks', $prefix = 'pl' ) {
		$linkCache =& LinkCache::singleton();

		if ( $options ) {
			$db = wfGetDB( DB_MASTER );
		} else {
			$db = wfGetDB( DB_SLAVE );
		}

		$res = $db->select( array( 'page', $table ),
			array( 'page_namespace', 'page_title', 'page_id' ),
			array(
				"{$prefix}_from=page_id",
				"{$prefix}_namespace" => $this->getNamespace(),
				"{$prefix}_title"     => $this->getDbKey() ),
			'Title::getLinksTo',
			$options );

		$retVal = array();
		if ( $db->numRows( $res ) ) {
			while ( $row = $db->fetchObject( $res ) ) {
				if ( $titleObj = Title::makeTitle( $row->page_namespace, $row->page_title ) ) {
					$linkCache->addGoodLinkObj( $row->page_id, $titleObj );
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
	 * @param string $options may be FOR UPDATE
	 * @return array the Title objects linking here
	 * @access public
	 */
	function getTemplateLinksTo( $options = '' ) {
		return $this->getLinksTo( $options, 'templatelinks', 'tl' );
	}

	/**
	 * Get an array of Title objects referring to non-existent articles linked from this page
	 *
	 * @param string $options may be FOR UPDATE
	 * @return array the Title objects
	 * @access public
	 */
	function getBrokenLinksFrom( $options = '' ) {
		if ( $options ) {
			$db = wfGetDB( DB_MASTER );
		} else {
			$db = wfGetDB( DB_SLAVE );
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

	function purgeSquid() {
		global $wgUseSquid;
		if ( $wgUseSquid ) {
			$urls = $this->getSquidURLs();
			$u = new SquidUpdate( $urls );
			$u->doUpdate();
		}
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
	function isValidMoveOperation( &$nt, $auth = true ) {
		if( !$this or !$nt ) {
			return 'badtitletext';
		}
		if( $this->equals( $nt ) ) {
			return 'selfmove';
		}
		if( !$this->isMovable() || !$nt->isMovable() ) {
			return 'immobile_namespace';
		}

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
				!$this->userCan( 'edit' ) || !$nt->userCan( 'edit' ) ||
				!$this->userCan( 'move' ) || !$nt->userCan( 'move' ) ) ) {
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
		$err = $this->isValidMoveOperation( $nt, $auth );
		if( is_string( $err ) ) {
			return $err;
		}

		$pageid = $this->getArticleID();
		if( $nt->exists() ) {
			$this->moveOverExistingRedirect( $nt, $reason );
			$pageCountChange = 0;
		} else { # Target didn't exist, do normal move.
			$this->moveToNewTitle( $nt, $reason );
			$pageCountChange = 1;
		}
		$redirid = $this->getArticleID();

		# Fixing category links (those without piped 'alternate' names) to be sorted under the new title
		$dbw = wfGetDB( DB_MASTER );
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
	 * @private
	 */
	function moveOverExistingRedirect( &$nt, $reason = '' ) {
		global $wgUseSquid;
		$fname = 'Title::moveOverExistingRedirect';
		$comment = wfMsgForContent( '1movedto2_redir', $this->getPrefixedText(), $nt->getPrefixedText() );

		if ( $reason ) {
			$comment .= ": $reason";
		}

		$now = wfTimestampNow();
		$newid = $nt->getArticleID();
		$oldid = $this->getArticleID();
		$dbw = wfGetDB( DB_MASTER );
		$linkCache =& LinkCache::singleton();

		# Delete the old redirect. We don't save it to history since
		# by definition if we've got here it's rather uninteresting.
		# We have to remove it so that the next step doesn't trigger
		# a conflict on the unique namespace+title index...
		$dbw->delete( 'page', array( 'page_id' => $newid ), $fname );

		# Save a null revision in the page's history notifying of the move
		$nullRevision = Revision::newNullRevision( $dbw, $oldid, $comment, true );
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
		$linkCache->clearLink( $nt->getPrefixedDBkey() );

		# Recreate the redirect, this time in the other direction.
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
		$linkCache->clearLink( $this->getPrefixedDBkey() );

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
	 * @private
	 */
	function moveToNewTitle( &$nt, $reason = '' ) {
		global $wgUseSquid;
		$fname = 'MovePageForm::moveToNewTitle';
		$comment = wfMsgForContent( '1movedto2', $this->getPrefixedText(), $nt->getPrefixedText() );
		if ( $reason ) {
			$comment .= ": $reason";
		}

		$newid = $nt->getArticleID();
		$oldid = $this->getArticleID();
		$dbw = wfGetDB( DB_MASTER );
		$now = $dbw->timestamp();
		$linkCache =& LinkCache::singleton();

		# Save a null revision in the page's history notifying of the move
		$nullRevision = Revision::newNullRevision( $dbw, $oldid, $comment, true );
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

		$linkCache->clearLink( $nt->getPrefixedDBkey() );

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
		$linkCache->clearLink( $this->getPrefixedDBkey() );

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

		# Purge old title from squid
		# The new title, and links to the new title, are purged in Article::onArticleCreate()
		$this->purgeSquid();
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
		$dbw = wfGetDB( DB_MASTER );

		# Is it a redirect?
		$id  = $nt->getArticleID();
		$obj = $dbw->selectRow( array( 'page', 'revision', 'text'),
			array( 'page_is_redirect','old_text','old_flags' ),
			array( 'page_id' => $id, 'page_latest=rev_id', 'rev_text_id=old_id' ),
			$fname, 'FOR UPDATE' );

		if ( !$obj || 0 == $obj->page_is_redirect ) {
			# Not a redirect
			wfDebug( __METHOD__ . ": not a redirect\n" );
			return false;
		}
		$text = Revision::getRevisionText( $obj );

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
	 * Get categories to which this Title belongs and return an array of
	 * categories' names.
	 *
	 * @return array an array of parents in the form:
	 *	$parent => $currentarticle
	 * @access public
	 */
	function getParentCategories() {
		global $wgContLang;

		$titlekey = $this->getArticleId();
		$dbr = wfGetDB( DB_SLAVE );
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
			foreach($parents as $parent => $current) {
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
	 * @return array
	 * @access public
	 */
	function pageCond() {
		return array( 'page_namespace' => $this->mNamespace, 'page_title' => $this->mDbkeyform );
	}

	/**
	 * Get the revision ID of the previous revision
	 *
	 * @param integer $revision  Revision ID. Get the revision that was before this one.
	 * @return integer $oldrevision|false
	 */
	function getPreviousRevisionID( $revision ) {
		$dbr = wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'revision', 'rev_id',
			'rev_page=' . intval( $this->getArticleId() ) .
			' AND rev_id<' . intval( $revision ) . ' ORDER BY rev_id DESC' );
	}

	/**
	 * Get the revision ID of the next revision
	 *
	 * @param integer $revision  Revision ID. Get the revision that was after this one.
	 * @return integer $oldrevision|false
	 */
	function getNextRevisionID( $revision ) {
		$dbr = wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'revision', 'rev_id',
			'rev_page=' . intval( $this->getArticleId() ) .
			' AND rev_id>' . intval( $revision ) . ' ORDER BY rev_id' );
	}

	/**
	 * Get the number of revisions between the given revision IDs.
	 *
	 * @param integer $old  Revision ID.
	 * @param integer $new  Revision ID.
	 * @return integer  Number of revisions between these IDs.
	 */
	function countRevisionsBetween( $old, $new ) {
		$dbr = wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'revision', 'count(*)',
			'rev_page = ' . intval( $this->getArticleId() ) .
			' AND rev_id > ' . intval( $old ) .
			' AND rev_id < ' . intval( $new ) );
	}

	/**
	 * Compare with another title.
	 *
	 * @param Title $title
	 * @return bool
	 */
	function equals( $title ) {
		// Note: === is necessary for proper matching of number-like titles.
		return $this->getInterwiki() === $title->getInterwiki()
			&& $this->getNamespace() == $title->getNamespace()
			&& $this->getDbkey() === $title->getDbkey();
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
	 * Currently, a self-link with a fragment and special pages are in
	 * this category. Special pages never exist in the database.
	 */
	function isAlwaysKnown() {
		return  $this->isExternal() || ( 0 == $this->mNamespace && "" == $this->mDbkeyform )
		  || NS_SPECIAL == $this->mNamespace;
	}

	/**
	 * Update page_touched timestamps and send squid purge messages for
	 * pages linking to this title.	May be sent to the job queue depending 
	 * on the number of links. Typically called on create and delete.
	 */
	function touchLinks() {
		$u = new HTMLCacheUpdate( $this, 'pagelinks' );
		$u->doUpdate();

		if ( $this->getNamespace() == NS_CATEGORY ) {
			$u = new HTMLCacheUpdate( $this, 'categorylinks' );
			$u->doUpdate();
		}
	}

	/**
	 * Get the last touched timestamp
	 */
	function getTouched() {
		$dbr = wfGetDB( DB_SLAVE );
		$touched = $dbr->selectField( 'page', 'page_touched',
			array( 
				'page_namespace' => $this->getNamespace(),
				'page_title' => $this->getDBkey()
			), __METHOD__
		);
		return $touched;
	}

	/**
	 * Get a cached value from a global cache that is invalidated when this page changes
	 * @param string $key the key
	 * @param callback $callback A callback function which generates the value on cache miss
	 *
	 * @deprecated use DependencyWrapper
	 */
	function getRelatedCache( $memc, $key, $expiry, $callback, $params = array() ) {
		return DependencyWrapper::getValueFromCache( $memc, $key, $expiry, $callback, 
			$params, new TitleDependency( $this ) );
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

	/**
	 * Generate strings used for xml 'id' names in monobook tabs
	 * @return string
	 */
	function getNamespaceKey() {
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
			case NS_IMAGE:
			case NS_IMAGE_TALK:
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
	 * @param string $name The special page name
	 * @access public
	 */
	function isSpecial( $name ) {
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
	 * returns a new Title which points to the local default. Otherwise, returns $this.
	 */
	function fixSpecialName() {
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
	 * @return bool
	 */
	public function isContentPage() {
		return Namespace::isContent( $this->getNamespace() );
	}
	
}

?>

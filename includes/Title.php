<?php
/**
 * $Id$
 * See title.doc
 * 
 * @package MediaWiki
 */

/** */
require_once( 'normal/UtfNormal.php' );

$wgTitleInterwikiCache = array();
define ( 'GAID_FOR_UPDATE', 1 );

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
	var $mRestrictions;       # Array of groups allowed to edit this article
                              # Only null or "sysop" are supported
	var $mRestrictionsLoaded; # Boolean for initialisation on demand
	var $mPrefixedText;       # Text form including namespace/interwiki, initialised on demand
	var $mDefaultNamespace;   # Namespace index when there is no namespace
                              # Zero except in {{transclusion}} tags
	/**#@-*/
	

	/**
	 * Constructor
	 * @access private
	 */
	/* private */ function Title() {
		$this->mInterwiki = $this->mUrlform =
		$this->mTextform = $this->mDbkeyform = '';
		$this->mArticleID = -1;
		$this->mNamespace = 0;
		$this->mRestrictionsLoaded = false;
		$this->mRestrictions = array();
		$this->mDefaultNamespace = 0;
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
	/* static */ function &newFromText( $text, $defaultNamespace = 0 ) {	
		$fname = 'Title::newFromText';
		wfProfileIn( $fname );
		
		/**
		 * Wiki pages often contain multiple links to the same page.
		 * Title normalization and parsing can become expensive on
		 * pages with many links, so we can save a little time by
		 * caching them.
		 *
		 * In theory these are value objects and won't get changed...
		 */
		static $titleCache = array();
		if( $defaultNamespace == 0 && isset( $titleCache[$text] ) ) {
			wfProfileOut( $fname );
			return $titleCache[$text];
		}

		/**
		 * Convert things like &eacute; into real text...
		 */
		global $wgInputEncoding;
		$filteredText = do_html_entity_decode( $text, ENT_COMPAT, $wgInputEncoding );

		/**
		 * Convert things like &#257; or &#x3017; into real text...
		 * WARNING: Not friendly to internal links on a latin-1 wiki.
		 */
		$filteredText = wfMungeToUtf8( $filteredText );
		
		# What was this for? TS 2004-03-03
		# $text = urldecode( $text );

		$t =& new Title();
		$t->mDbkeyform = str_replace( ' ', '_', $filteredText );
		$t->mDefaultNamespace = $defaultNamespace;

		if( $t->secureAndSplit() ) {
			if( $defaultNamespace == 0 ) {
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
	/* static */ function newFromURL( $url ) {
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
	 * @todo This is inefficiently implemented, the cur row is requested
	 * but not used for anything else
	 * @param int $id the cur_id corresponding to the Title to create
	 * @return Title the new object, or NULL on an error
	 * @access public
	 */
	/* static */ function newFromID( $id ) {
		$fname = 'Title::newFromID';
		$dbr =& wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'cur', array( 'cur_namespace', 'cur_title' ), 
			array( 'cur_id' => $id ), $fname );
		if ( $row !== false ) {
			$title = Title::makeTitle( $row->cur_namespace, $row->cur_title );
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
	/* static */ function &makeTitle( $ns, $title ) {
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
	 * @param int $ns the namespace of the article
	 * @param string $title the database key form
	 * @return Title the new object, or NULL on an error
	 * @static
	 * @access public
	 */
	/* static */ function makeTitleSafe( $ns, $title ) {
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
	 * @static
	 * @return Title the new object
	 * @access public
	 */
	/* static */ function newMainPage() {
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
	/* static */ function newFromRedirect( $text ) {
		global $wgMwRedir;
		$rt = NULL;
		if ( $wgMwRedir->matchStart( $text ) ) {
			if ( preg_match( '/\\[\\[([^\\]\\|]+)[\\]\\|]/', $text, $m ) ) {
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
	 * @param int $id the cur_id of the article
	 * @return Title an object representing the article, or NULL
	 * 	if no such article was found
	 * @static
	 * @access public
	 */
	/* static */ function nameOf( $id ) {
		$fname = 'Title::nameOf';
		$dbr =& wfGetDB( DB_SLAVE );
		
		$s = $dbr->selectRow( 'cur', array( 'cur_namespace','cur_title' ),  array( 'cur_id' => $id ), $fname );
		if ( $s === false ) { return NULL; }

		$n = Title::makeName( $s->cur_namespace, $s->cur_title );
		return $n;
	}

	/**
	 * Get a regex character class describing the legal characters in a link
	 * @return string the list of characters, not delimited
	 * @static
	 * @access public
	 */
	/* static */ function legalChars() {
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
		if ( '' == $n ) { return $title; }
		else { return $n.':'.$title; }
	}
	
	/**
	 * Returns the URL associated with an interwiki prefix
	 * @param string $key the interwiki prefix (e.g. "MeatBall")
	 * @return the associated URL, containing "$1", which should be
	 * 	replaced by an article title
	 * @static (arguably)
	 * @access public
	 */
	function getInterwikiLink( $key ) {	
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
		if( $s && isset( $s->iw_local ) ) { 
			$wgTitleInterwikiCache[$k] = $s;
			wfProfileOut( $fname );
			return $s->iw_url;
		}
		
		$dbr =& wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'interwiki',
			array( 'iw_url', 'iw_local' ),
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
	 * Update the cur_touched field for an array of title objects
	 * @todo Inefficient unless the IDs are already loaded into the
	 *	link cache
	 * @param array $titles an array of Title objects to be touched
	 * @param string $timestamp the timestamp to use instead of the
	 *	default current time
	 * @static
	 * @access public
	 */
	/* static */ function touchArray( $titles, $timestamp = '' ) {
		if ( count( $titles ) == 0 ) {
			return;
		}
		$dbw =& wfGetDB( DB_MASTER );
		if ( $timestamp == '' ) {
			$timestamp = $dbw->timestamp();
		}
		$cur = $dbw->tableName( 'cur' );
		$sql = "UPDATE $cur SET cur_touched='{$timestamp}' WHERE cur_id IN (";
		$first = true;

		foreach ( $titles as $title ) {
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
		global $wgContLang, $wgArticlePath, $wgServer, $wgScript;

		if ( '' == $this->mInterwiki ) {
			$p = $wgArticlePath;
			return $wgServer . $this->getLocalUrl( $query );
		} else {
			$baseUrl = $this->getInterwikiLink( $this->mInterwiki );
			$namespace = $wgContLang->getNsText( $this->mNamespace );
			if ( '' != $namespace ) {
				# Can this actually happen? Interwikis shouldn't be parsed.
				$namepace .= ':';
			}
			$url = str_replace( '$1', $namespace . $this->mUrlform, $baseUrl );
			if ( '' != $this->mFragment ) {
				$url .= '#' . $this->mFragment;
			}
			return $url;
		}
	}

	/**
	 * Get a URL with no fragment or server name
	 * @param string $query an optional query string; if not specified,
	 * 	$wgArticlePath will be used.
	 * @return string the URL
	 * @access public
	 */
	function getLocalURL( $query = '' ) {
		global $wgLang, $wgArticlePath, $wgScript;
		
		if ( $this->isExternal() ) {
			return $this->getFullURL();
		}

		$dbkey = wfUrlencode( $this->getPrefixedDBkey() );
		if ( $query == '' ) {
			$url = str_replace( '$1', $dbkey, $wgArticlePath );
		} else {
			if ( $query == '-' ) {
				$query = '';
			}
			$url = "{$wgScript}?title={$dbkey}&{$query}";
		}
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

		if ( -1 == $this->mNamespace ) { return false; }
		if ( 0 == $wgUser->getID() ) { return false; }

		return $wgUser->isWatched( $this );
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
			if( '' != $right && !$wgUser->isAllowed( $right ) ) {
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
			    && $this->mId == NS_SPECIAL
			    && $this->getText() == 'Userlogin' ) {
				return true;
			}

			/** some pages are explicitly allowed */
			$name = $this->getPrefixedText();
			if( in_array( $name, $wgWhitelistRead ) ) {
				return true;
			}
			
			# Compatibility with old settings
			if( $this->getNamespace() == NS_MAIN ) {
				if( in_array( ':' . $name, $wgWhitelistRead ) ) {
					return true;
				}
			}
		}
		return false;
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
			$res = $dbr->selectField( 'cur', 'cur_restrictions', 'cur_id='.$id );
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
		$dbr =& wfGetDB( DB_SLAVE );
		$n = $dbr->selectField( 'archive', 'COUNT(*)', array( 'ar_namespace' => $this->getNamespace(), 
			'ar_title' => $this->getDBkey() ), $fname );
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

	/**
	 * This clears some fields in this object, and clears any associated
	 * keys in the "bad links" section of $wgLinkCache.
	 *
	 * - This is called from Article::insertNewArticle() to allow
	 * loading of the new cur_id. It's also called from
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
	 * Updates cur_touched for this page; called from LinksUpdate.php
	 * @return bool true if the update succeded
	 * @access public
	 */
	function invalidateCache() {
		$now = wfTimestampNow();
		$dbw =& wfGetDB( DB_MASTER );
		$success = $dbw->update( 'cur', 
			array( /* SET */ 
				'cur_touched' => $dbw->timestamp()
			), array( /* WHERE */ 
				'cur_namespace' => $this->getNamespace() ,
				'cur_title' => $this->getDBkey()
			), 'Title::invalidateCache'
		);
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
		$t = preg_replace( '/[\\s_]+/', '_', $this->mDbkeyform );
		$t = trim( $t, '_' );

		if ( '' == $t ) {
			wfProfileOut( $fname );
			return false;
		}
		
		global $wgUseLatin1;
		if( !$wgUseLatin1 && false !== strpos( $t, UTF8_REPLACEMENT ) ) {
			# Contained illegal UTF-8 sequences or forbidden Unicode chars.
			wfProfileOut( $fname );
			return false;
		}

		$this->mDbkeyform = $t;

		# Initial colon indicating main namespace
		if ( ':' == $t{0} ) {
			$r = substr( $t, 1 );
			$this->mNamespace = NS_MAIN;
		} else {
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
		}

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
		#$maxSize = $dbr->textFieldSize( 'cur', 'cur_title' );
		if ( strlen( $r ) > 255 ) {
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
	 * - Also stores the IDs in the link cache.
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
		$cur = $db->tableName( 'cur' );
		$links = $db->tableName( 'links' );

		$sql = "SELECT cur_namespace,cur_title,cur_id FROM $cur,$links WHERE l_from=cur_id AND l_to={$id} $options";
		$res = $db->query( $sql, 'Title::getLinksTo' );
		$retVal = array();
		if ( $db->numRows( $res ) ) {
			while ( $row = $db->fetchObject( $res ) ) {
				if ( $titleObj = Title::makeTitle( $row->cur_namespace, $row->cur_title ) ) {
					$wgLinkCache->addGoodLink( $row->cur_id, $titleObj->getPrefixedDBkey() );
					$retVal[] = $titleObj;
				}
			}
		}
		$db->freeResult( $res );
		return $retVal;
	}

	/**
	 * Get an array of Title objects linking to this non-existent title.
	 * - Also stores the IDs in the link cache.
	 *
	 * @param string $options may be FOR UPDATE 
	 * @return array the Title objects linking here
	 * @access public
	 */
	function getBrokenLinksTo( $options = '' ) {
		global $wgLinkCache;
		
		if ( $options ) {
			$db =& wfGetDB( DB_MASTER );
		} else {
			$db =& wfGetDB( DB_SLAVE );
		}
		$cur = $db->tableName( 'cur' );
		$brokenlinks = $db->tableName( 'brokenlinks' );
		$encTitle = $db->strencode( $this->getPrefixedDBkey() );

		$sql = "SELECT cur_namespace,cur_title,cur_id FROM $brokenlinks,$cur " .
		  "WHERE bl_from=cur_id AND bl_to='$encTitle' $options";
		$res = $db->query( $sql, "Title::getBrokenLinksTo" );
		$retVal = array();
		if ( $db->numRows( $res ) ) {
			while ( $row = $db->fetchObject( $res ) ) {
				$titleObj = Title::makeTitle( $row->cur_namespace, $row->cur_title );
				$wgLinkCache->addGoodLink( $row->cur_id, $titleObj->getPrefixedDBkey() );
				$retVal[] = $titleObj;
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
	 * Move a title to a new location
	 * @param Title &$nt the new title
	 * @param bool $auth indicates whether $wgUser's permissions
	 * 	should be checked
	 * @return mixed true on success, message name on failure
	 * @access public
	 */
	function moveTo( &$nt, $auth = true ) {
		if( !$this or !$nt ) {
			return 'badtitletext';
		}

		$fname = 'Title::move';
		$oldid = $this->getArticleID();
		$newid = $nt->getArticleID();

		if ( strlen( $nt->getDBkey() ) < 1 ) {
			return 'articleexists';
		}
		if ( ( ! Namespace::isMovable( $this->getNamespace() ) ) ||
			 ( '' == $this->getDBkey() ) ||
			 ( '' != $this->getInterwiki() ) ||
			 ( !$oldid ) ||
		     ( ! Namespace::isMovable( $nt->getNamespace() ) ) ||
			 ( '' == $nt->getDBkey() ) ||
			 ( '' != $nt->getInterwiki() ) ) {
			return 'badarticleerror';
		}

		if ( $auth && ( !$this->userCanEdit() || !$nt->userCanEdit() ) ) {
			return 'protectedpage';
		}
		
		# The move is allowed only if (1) the target doesn't exist, or
		# (2) the target is a redirect to the source, and has no history
		# (so we can undo bad moves right after they're done).

		if ( 0 != $newid ) { # Target exists; check for validity
			if ( ! $this->isValidMoveTarget( $nt ) ) {
				return 'articleexists';
			}
			$this->moveOverExistingRedirect( $nt );
		} else { # Target didn't exist, do normal move.
			$this->moveToNewTitle( $nt, $newid );
		}

		# Fixing category links (those without piped 'alternate' names) to be sorted under the new title
		
		$dbw =& wfGetDB( DB_MASTER );
		$sql = "UPDATE categorylinks SET cl_sortkey=" . $dbw->addQuotes( $nt->getPrefixedText() ) .
			" WHERE cl_from=" . $dbw->addQuotes( $this->getArticleID() ) .
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
		$u = new SearchUpdate( $oldid, $nt->getPrefixedDBkey() );
		$u->doUpdate();
		$u = new SearchUpdate( $newid, $this->getPrefixedDBkey(), '' );
		$u->doUpdate();

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
	/* private */ function moveOverExistingRedirect( &$nt ) {
		global $wgUser, $wgLinkCache, $wgUseSquid, $wgMwRedir;
		$fname = 'Title::moveOverExistingRedirect';
		$comment = wfMsg( '1movedto2', $this->getPrefixedText(), $nt->getPrefixedText() );
		
		$now = wfTimestampNow();
		$won = wfInvertTimestamp( $now );
		$newid = $nt->getArticleID();
		$oldid = $this->getArticleID();
		$dbw =& wfGetDB( DB_MASTER );
		$links = $dbw->tableName( 'links' );

		# Change the name of the target page:
		$dbw->update( 'cur',
			/* SET */ array( 
				'cur_touched' => $dbw->timestamp($now), 
				'cur_namespace' => $nt->getNamespace(),
				'cur_title' => $nt->getDBkey()
			), 
			/* WHERE */ array( 'cur_id' => $oldid ),
			$fname
		);
		$wgLinkCache->clearLink( $nt->getPrefixedDBkey() );

		# Repurpose the old redirect. We don't save it to history since
		# by definition if we've got here it's rather uninteresting.
		
		$redirectText = $wgMwRedir->getSynonym( 0 ) . ' [[' . $nt->getPrefixedText() . "]]\n";
		$dbw->update( 'cur',
			/* SET */ array(
				'cur_touched' => $dbw->timestamp($now),
				'cur_timestamp' => $dbw->timestamp($now),
				'inverse_timestamp' => $won,
				'cur_namespace' => $this->getNamespace(),
				'cur_title' => $this->getDBkey(),
				'cur_text' => $wgMwRedir->getSynonym( 0 ) . ' [[' . $nt->getPrefixedText() . "]]\n",
				'cur_comment' => $comment,
				'cur_user' => $wgUser->getID(),
				'cur_minor_edit' => 0,
				'cur_counter' => 0,
				'cur_restrictions' => '',
				'cur_user_text' => $wgUser->getName(),
				'cur_is_redirect' => 1,
				'cur_is_new' => 1
			),
			/* WHERE */ array( 'cur_id' => $newid ),
			$fname
		);
		
		$wgLinkCache->clearLink( $this->getPrefixedDBkey() );

		# Fix the redundant names for the past revisions of the target page.
		# The redirect should have no old revisions.
		$dbw->update(
			/* table */ 'old',
			/* SET */ array( 
				'old_namespace' => $nt->getNamespace(),
				'old_title' => $nt->getDBkey(),
			),
			/* WHERE */ array( 
				'old_namespace' => $this->getNamespace(),
				'old_title' => $this->getDBkey(),
			),
			$fname
		);
		
		RecentChange::notifyMoveOverRedirect( $now, $this, $nt, $wgUser, $comment );

		# Swap links
		
		# Load titles and IDs
		$linksToOld = $this->getLinksTo( 'FOR UPDATE' );
		$linksToNew = $nt->getLinksTo( 'FOR UPDATE' );
		
		# Delete them all
		$sql = "DELETE FROM $links WHERE l_to=$oldid OR l_to=$newid";
		$dbw->query( $sql, $fname );

		# Reinsert
		if ( count( $linksToOld ) || count( $linksToNew )) {
			$sql = "INSERT INTO $links (l_from,l_to) VALUES ";
			$first = true;

			# Insert links to old title
			foreach ( $linksToOld as $linkTitle ) {
				if ( $first ) {
					$first = false;
				} else {
					$sql .= ',';
				}
				$id = $linkTitle->getArticleID();
				$sql .= "($id,$newid)";
			}

			# Insert links to new title
			foreach ( $linksToNew as $linkTitle ) {
				if ( $first ) {
					$first = false;
				} else {
					$sql .= ',';
				}
				$id = $linkTitle->getArticleID();
				$sql .= "($id, $oldid)";
			}

			$dbw->query( $sql, DB_MASTER, $fname );
		}

		# Now, we record the link from the redirect to the new title.
		# It should have no other outgoing links...
		$dbw->delete( 'links', array( 'l_from' => $newid ) );
		$dbw->insert( 'links', array( 'l_from' => $newid, 'l_to' => $oldid ) );
		
		# Clear linkscc
		LinkCache::linksccClearLinksTo( $oldid );
		LinkCache::linksccClearLinksTo( $newid );
		
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
	/* private */ function moveToNewTitle( &$nt, &$newid ) {
		global $wgUser, $wgLinkCache, $wgUseSquid;
		$fname = 'MovePageForm::moveToNewTitle';
		$comment = wfMsg( '1movedto2', $this->getPrefixedText(), $nt->getPrefixedText() );

		$newid = $nt->getArticleID();
		$oldid = $this->getArticleID();
		$dbw =& wfGetDB( DB_MASTER );
		$now = $dbw->timestamp();
		$won = wfInvertTimestamp( wfTimestamp(TS_MW,$now) );
		wfSeedRandom();
		$rand = wfRandom();

		# Rename cur entry
		$dbw->update( 'cur',
			/* SET */ array(
				'cur_touched' => $now,
				'cur_namespace' => $nt->getNamespace(),
				'cur_title' => $nt->getDBkey()
			),
			/* WHERE */ array( 'cur_id' => $oldid ),
			$fname
		);
		
		$wgLinkCache->clearLink( $nt->getPrefixedDBkey() );

		# Insert redirect
		$dbw->insert( 'cur', array(
			'cur_id' => $dbw->nextSequenceValue('cur_cur_id_seq'),
			'cur_namespace' => $this->getNamespace(),
			'cur_title' => $this->getDBkey(),
			'cur_comment' => $comment,
			'cur_user' => $wgUser->getID(),
			'cur_user_text' => $wgUser->getName(),
			'cur_timestamp' => $now,
			'inverse_timestamp' => $won,
			'cur_touched' => $now,
			'cur_is_redirect' => 1,
			'cur_random' => $rand,
			'cur_is_new' => 1,
			'cur_text' => "#REDIRECT [[" . $nt->getPrefixedText() . "]]\n" ), $fname
		);
		$newid = $dbw->insertId();
		$wgLinkCache->clearLink( $this->getPrefixedDBkey() );

		# Rename old entries
		$dbw->update( 
			/* table */ 'old',
			/* SET */ array(
				'old_namespace' => $nt->getNamespace(),
				'old_title' => $nt->getDBkey()
			),
			/* WHERE */ array(
				'old_namespace' => $this->getNamespace(),
				'old_title' => $this->getDBkey()
			), $fname
		);
		
		# Record in RC
		RecentChange::notifyMoveToNew( $now, $this, $nt, $wgUser, $comment );

		# Purge squid and linkscc as per article creation
		Article::onArticleCreate( $nt );

		# Any text links to the old title must be reassigned to the redirect
		$dbw->update( 'links', array( 'l_to' => $newid ), array( 'l_to' => $oldid ), $fname );
		LinkCache::linksccClearLinksTo( $oldid );

		# Record the just-created redirect's linking to the page
		$dbw->insert( 'links', array( 'l_from' => $newid, 'l_to' => $oldid ), $fname );

		# Non-existent target may have had broken links to it; these must
		# now be removed and made into good links.
		$update = new LinksUpdate( $oldid, $nt->getPrefixedDBkey() );
		$update->fixBrokenLinks();

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
		$obj = $dbw->selectRow( 'cur', array( 'cur_is_redirect','cur_text' ), 
			array( 'cur_id' => $id ), $fname, 'FOR UPDATE' );

		if ( !$obj || 0 == $obj->cur_is_redirect ) { 
			# Not a redirect
			return false; 
		}

		# Does the redirect point to the source?
		if ( preg_match( "/\\[\\[\\s*([^\\]\\|]*)]]/", $obj->cur_text, $m ) ) {
			$redirTitle = Title::newFromText( $m[1] );
			if( !is_object( $redirTitle ) ||
				$redirTitle->getPrefixedDBkey() != $this->getPrefixedDBkey() ) {
				return false;
			}
		}

		# Does the article have a history?
		$row = $dbw->selectRow( 'old', array( 'old_id' ), 
			array( 
				'old_namespace' => $nt->getNamespace(),
				'old_title' => $nt->getDBkey() 
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
		$now = wfTimestampNow();
		$won = wfInvertTimestamp( $now );
		$seqVal = $dbw->nextSequenceValue( 'cur_cur_id_seq' );

		$dbw->insert( 'cur', array(
			'cur_id' => $seqVal,
			'cur_namespace' => $this->getNamespace(),
			'cur_title' => $this->getDBkey(),
			'cur_comment' => $comment,
			'cur_user' => $wgUser->getID(),
			'cur_user_text' => $wgUser->getName(),
			'cur_timestamp' => $now,
			'inverse_timestamp' => $won,
			'cur_touched' => $now,
			'cur_is_redirect' => 1,
			'cur_is_new' => 1,
			'cur_text' => "#REDIRECT [[" . $dest->getPrefixedText() . "]]\n" 
		), $fname );
		$newid = $dbw->insertId();
		$this->resetArticleID( $newid );
		
		# Link table
		if ( $dest->getArticleID() ) {
			$dbw->insert( 'links', 
				array(
					'l_to' => $dest->getArticleID(),
					'l_from' => $newid
				), $fname 
			);
		} else {
			$dbw->insert( 'brokenlinks', 
				array( 
					'bl_to' => $dest->getPrefixedDBkey(),
					'bl_from' => $newid
				), $fname
			);
		}

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
		return $dbr->selectField( 'old', 'old_id',
			'old_title=' . $dbr->addQuotes( $this->getDBkey() ) .
			' AND old_namespace=' . IntVal( $this->getNamespace() ) .
			' AND old_id<' . IntVal( $revision ) . ' ORDER BY old_id DESC' );
	}

	/**
	 * Get the revision ID of the next revision
	 *
	 * @param integer $revision  Revision ID. Get the revision that was after this one.
	 * @return interger $oldrevision|false
	 */
	function getNextRevisionID( $revision ) {
		$dbr =& wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'old', 'old_id',
			'old_title=' . $dbr->addQuotes( $this->getDBkey() ) .
			' AND old_namespace=' . IntVal( $this->getNamespace() ) .
			' AND old_id>' . IntVal( $revision ) . ' ORDER BY old_id' );
	}

}
?>

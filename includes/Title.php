<?php
# See title.doc

$wgTitleInterwikiCache = array();

# Title class
# 
# * Represents a title, which may contain an interwiki designation or namespace
# * Can fetch various kinds of data from the database, albeit inefficiently. 
#
class Title {
	# All member variables should be considered private
	# Please use the accessor functions

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
	
#----------------------------------------------------------------------------
#   Construction
#----------------------------------------------------------------------------

	/* private */ function Title()
	{
		$this->mInterwiki = $this->mUrlform =
		$this->mTextform = $this->mDbkeyform = "";
		$this->mArticleID = -1;
		$this->mNamespace = 0;
		$this->mRestrictionsLoaded = false;
		$this->mRestrictions = array();
        $this->mDefaultNamespace = 0;
	}

	# From a prefixed DB key
	/* static */ function newFromDBkey( $key )
	{
		$t = new Title();
		$t->mDbkeyform = $key;
		if( $t->secureAndSplit() )
			return $t;
		else
			return NULL;
	}
	
	# From text, such as what you would find in a link
	/* static */ function newFromText( $text, $defaultNamespace = 0 )
	{	
		$fname = "Title::newFromText";
		wfProfileIn( $fname );

		if( is_object( $text ) ) {
			wfDebugDieBacktrace( "Called with object instead of string." );
		}
		global $wgInputEncoding;
		$text = do_html_entity_decode( $text, ENT_COMPAT, $wgInputEncoding );

		$text = wfMungeToUtf8( $text );
		
		
		# What was this for? TS 2004-03-03
		# $text = urldecode( $text );

		$t = new Title();
		$t->mDbkeyform = str_replace( " ", "_", $text );
		$t->mDefaultNamespace = $defaultNamespace;

		wfProfileOut( $fname );
		if ( !is_object( $t ) ) {
			var_dump( debug_backtrace() );
		}
		if( $t->secureAndSplit() ) {
			return $t;
		} else {
			return NULL;
		}
	}

	# From a URL-encoded title
	/* static */ function newFromURL( $url )
	{
		global $wgLang, $wgServer;
		$t = new Title();
		
		# For compatibility with old buggy URLs. "+" is not valid in titles,
		# but some URLs used it as a space replacement and they still come
		# from some external search tools.
		$s = str_replace( "+", " ", $url );
		
		# For links that came from outside, check for alternate/legacy
		# character encoding.
		wfDebug( "Servr: $wgServer\n" );
		if( empty( $_SERVER["HTTP_REFERER"] ) ||
			strncmp($wgServer, $_SERVER["HTTP_REFERER"], strlen( $wgServer ) ) ) 
		{
			$s = $wgLang->checkTitleEncoding( $s );
		} else {
			wfDebug( "Refer: {$_SERVER['HTTP_REFERER']}\n" );
		}
		
		$t->mDbkeyform = str_replace( " ", "_", $s );
		if( $t->secureAndSplit() ) {
			# check that length of title is < cur_title size
			$dbr =& wfGetDB( DB_SLAVE );
			$maxSize = $dbr->textFieldSize( 'cur', 'cur_title' );
			if ( $maxSize != -1 && strlen( $t->mDbkeyform ) > $maxSize ) {
				return NULL;
			}

			return $t;
		} else {
			return NULL;
		}
	}
	
	# From a cur_id
	# This is inefficiently implemented, the cur row is requested but not 
	# used for anything else
	/* static */ function newFromID( $id ) 
	{
		$fname = "Title::newFromID";
		$dbr =& wfGetDB( DB_SLAVE );
		$row = $dbr->getArray( "cur", array( "cur_namespace", "cur_title" ), 
			array( "cur_id" => $id ), $fname );
		if ( $row !== false ) {
			$title = Title::makeTitle( $row->cur_namespace, $row->cur_title );
		} else {
			$title = NULL;
		}
		return $title;
	}
	
	# From a namespace index and a DB key
	/* static */ function makeTitle( $ns, $title )
	{
		$t = new Title();
		$t->mDbkeyform = Title::makeName( $ns, $title );
		if( $t->secureAndSplit() ) {
			return $t;
		} else {
			return NULL;
		}
	}

	/* static */ function newMainPage()
	{
		return Title::newFromText( wfMsg( "mainpage" ) );
	}

	# Get the title object for a redirect
	# Returns NULL if the text is not a valid redirect
	/* static */ function newFromRedirect( $text ) {
		global $wgMwRedir;
		$rt = NULL;
		if ( $wgMwRedir->matchStart( $text ) ) {
			if ( preg_match( '/\\[\\[([^\\]\\|]+)[\\]\\|]/', $text, $m ) ) {
				$rt = Title::newFromText( $m[1] );
			}
		}
		return $rt;
	}
	
#----------------------------------------------------------------------------
#	Static functions
#----------------------------------------------------------------------------

	# Get the prefixed DB key associated with an ID
	/* static */ function nameOf( $id )
	{
		$fname = 'Title::nameOf';
		$dbr =& wfGetDB( DB_SLAVE );
		
		$s = $dbr->getArray( 'cur', array( 'cur_namespace','cur_title' ),  array( 'cur_id' => $id ), $fname );
		if ( $s === false ) { return NULL; }

		$n = Title::makeName( $s->cur_namespace, $s->cur_title );
		return $n;
	}

	# Get a regex character class describing the legal characters in a link
	/* static */ function legalChars()
	{
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
		
		$set = " %!\"$&'()*,\\-.\\/0-9:;=?@A-Z\\\\^_`a-z{}~\\x80-\\xFF";
		return $set;
	}
	
	# Returns a stripped-down a title string ready for the search index
	# Takes a namespace index and a text-form main part
	/* static */ function indexTitle( $ns, $title )
	{
		global $wgDBminWordLen, $wgLang;

		$lc = SearchEngine::legalSearchChars() . "&#;";
		$t = $wgLang->stripForSearch( $title );
		$t = preg_replace( "/[^{$lc}]+/", " ", $t );
		$t = strtolower( $t );

		# Handle 's, s'
		$t = preg_replace( "/([{$lc}]+)'s( |$)/", "\\1 \\1's ", $t );
		$t = preg_replace( "/([{$lc}]+)s'( |$)/", "\\1s ", $t );

		$t = preg_replace( "/\\s+/", " ", $t );

		if ( $ns == Namespace::getImage() ) {
			$t = preg_replace( "/ (png|gif|jpg|jpeg|ogg)$/", "", $t );
		}
		return trim( $t );
	}
	
	# Make a prefixed DB key from a DB key and a namespace index
	/* static */ function makeName( $ns, $title )
	{
		global $wgLang;

		$n = $wgLang->getNsText( $ns );
		if ( "" == $n ) { return $title; }
		else { return "{$n}:{$title}"; }
	}
	
	# Arguably static
	# Returns the URL associated with an interwiki prefix
	# The URL contains $1, which is replaced by the title
	function getInterwikiLink( $key )
	{	
		global $wgMemc, $wgDBname, $wgInterwikiExpiry, $wgTitleInterwikiCache;
		$fname = 'Title::getInterwikiLink';
		$k = "$wgDBname:interwiki:$key";

		if( array_key_exists( $k, $wgTitleInterwikiCache ) )
			return $wgTitleInterwikiCache[$k]->iw_url;

		$s = $wgMemc->get( $k ); 
		# Ignore old keys with no iw_local
		if( $s && isset( $s->iw_local ) ) { 
			$wgTitleInterwikiCache[$k] = $s;
			return $s->iw_url;
		}
		$dbr =& wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'interwiki', array( 'iw_url', 'iw_local' ), array( 'iw_prefix' => $key ), $fname );
                if(!$res) return "";
		
		$s = $dbr->fetchObject( $res );
		if(!$s) {
			# Cache non-existence: create a blank object and save it to memcached
			$s = (object)false;
			$s->iw_url = "";
			$s->iw_local = 0;
		}
		$wgMemc->set( $k, $s, $wgInterwikiExpiry );
		$wgTitleInterwikiCache[$k] = $s;
		return $s->iw_url;
	}

	function isLocal() {
		global $wgTitleInterwikiCache, $wgDBname;

		if ( $this->mInterwiki != "" ) {
			# Make sure key is loaded into cache
			$this->getInterwikiLink( $this->mInterwiki );
			$k = "$wgDBname:interwiki:" . $this->mInterwiki;
			return (bool)($wgTitleInterwikiCache[$k]->iw_local);
		} else {
			return true;
		}
	}

	# Update the cur_touched field for an array of title objects
	# Inefficient unless the IDs are already loaded into the link cache
	/* static */ function touchArray( $titles, $timestamp = "" ) {
		if ( count( $titles ) == 0 ) {
			return;
		}
		if ( $timestamp == "" ) {
			$timestamp = wfTimestampNow();
		}
		$dbw =& wfGetDB( DB_MASTER );
		$cur = $dbw->tableName( 'cur' );
		$sql = "UPDATE $cur SET cur_touched='{$timestamp}' WHERE cur_id IN (";
		$first = true;

		foreach ( $titles as $title ) {
			if ( ! $first ) { 
				$sql .= ","; 
			}
			$first = false;
			$sql .= $title->getArticleID();
		}
		$sql .= ")";
		if ( ! $first ) {
			$dbw->query( $sql, "Title::touchArray" );
		}
	}

#----------------------------------------------------------------------------
#	Other stuff
#----------------------------------------------------------------------------

	# Simple accessors
	# See the definitions at the top of this file

	function getText() { return $this->mTextform; }
	function getPartialURL() { return $this->mUrlform; }
	function getDBkey() { return $this->mDbkeyform; }
	function getNamespace() { return $this->mNamespace; }
	function setNamespace( $n ) { $this->mNamespace = $n; }
	function getInterwiki() { return $this->mInterwiki; }
	function getFragment() { return $this->mFragment; }
	function getDefaultNamespace() { return $this->mDefaultNamespace; }

	# Get title for search index
	function getIndexTitle()
	{
		return Title::indexTitle( $this->mNamespace, $this->mTextform );
	}

	# Get prefixed title with underscores
	function getPrefixedDBkey()
	{
		$s = $this->prefix( $this->mDbkeyform );
		$s = str_replace( " ", "_", $s );
		return $s;
	}

	# Get prefixed title with spaces
	# This is the form usually used for display
	function getPrefixedText()
	{
		if ( empty( $this->mPrefixedText ) ) {
			$s = $this->prefix( $this->mTextform );
			$s = str_replace( "_", " ", $s );
			$this->mPrefixedText = $s;
		}
		return $this->mPrefixedText;
	}
	
	# As getPrefixedText(), plus fragment.
	function getFullText() {
		$text = $this->getPrefixedText();
		if( '' != $this->mFragment ) {
			$text .= '#' . $this->mFragment;
		}
		return $text;
	}

	# Get a URL-encoded title (not an actual URL) including interwiki
	function getPrefixedURL()
	{
		$s = $this->prefix( $this->mDbkeyform );
		$s = str_replace( " ", "_", $s );

		$s = wfUrlencode ( $s ) ;
		
		# Cleaning up URL to make it look nice -- is this safe?
		$s = preg_replace( "/%3[Aa]/", ":", $s );
		$s = preg_replace( "/%2[Ff]/", "/", $s );
		$s = str_replace( "%28", "(", $s );
		$s = str_replace( "%29", ")", $s );

		return $s;
	}

	# Get a real URL referring to this title, with interwiki link and fragment
	function getFullURL( $query = "" )
	{
		global $wgLang, $wgArticlePath, $wgServer, $wgScript;

		if ( "" == $this->mInterwiki ) {
			$p = $wgArticlePath;
			return $wgServer . $this->getLocalUrl( $query );
		} else {
			$baseUrl = $this->getInterwikiLink( $this->mInterwiki );
			$namespace = $wgLang->getNsText( $this->mNamespace );
			if ( "" != $namespace ) {
				# Can this actually happen? Interwikis shouldn't be parsed.
				$namepace .= ":";
			}
			$url = str_replace( "$1", $namespace . $this->mUrlform, $baseUrl );
			if ( '' != $this->mFragment ) {
				$url .= '#' . $this->mFragment;
			}
			return $url;
		}
	}

	# Get a URL with an optional query string, no fragment
	# * If $query=="", it will use $wgArticlePath
	# * Returns a full for an interwiki link, loses any query string
	# * Optionally adds the server and escapes for HTML
	# * Setting $query to "-" makes an old-style URL with nothing in the
	#   query except a title
	
	function getURL() {
		die( "Call to obsolete obsolete function Title::getURL()" );
	}
	
	function getLocalURL( $query = "" )
	{
		global $wgLang, $wgArticlePath, $wgScript;
		
		if ( $this->isExternal() ) {
			return $this->getFullURL();
		}

		$dbkey = wfUrlencode( $this->getPrefixedDBkey() );
		if ( $query == "" ) {
			$url = str_replace( "$1", $dbkey, $wgArticlePath );
		} else {
			if ( $query == "-" ) {
				$query = "";
			}
			if ( $wgScript != "" ) {
				$url = "{$wgScript}?title={$dbkey}&{$query}";
			} else {
				# Top level wiki
				$url = "/{$dbkey}?{$query}";
			}
		}
		return $url;
	}
	
	function escapeLocalURL( $query = "" ) {
		return wfEscapeHTML( $this->getLocalURL( $query ) );
	}
	
	function escapeFullURL( $query = "" ) {
		return wfEscapeHTML( $this->getFullURL( $query ) );
	}
	
	function getInternalURL( $query = "" ) {
		# Used in various Squid-related code, in case we have a different
		# internal hostname for the server than the exposed one.
		global $wgInternalServer;
		return $wgInternalServer . $this->getLocalURL( $query );
	}

	# Get the edit URL, or a null string if it is an interwiki link
	function getEditURL()
	{
		global $wgServer, $wgScript;

		if ( "" != $this->mInterwiki ) { return ""; }
		$s = $this->getLocalURL( "action=edit" );

		return $s;
	}
	
	# Get HTML-escaped displayable text
	# For the title field in <a> tags
	function getEscapedText()
	{
		return wfEscapeHTML( $this->getPrefixedText() );
	}
	
	# Is the title interwiki?
	function isExternal() { return ( "" != $this->mInterwiki ); }

	# Does the title correspond to a protected article?
	function isProtected()
	{
		if ( -1 == $this->mNamespace ) { return true; }
		$a = $this->getRestrictions();
		if ( in_array( "sysop", $a ) ) { return true; }
		return false;
	}

	# Is the page a log page, i.e. one where the history is messed up by 
	# LogPage.php? This used to be used for suppressing diff links in recent 
	# changes, but now that's done by setting a flag in the recentchanges 
	# table. Hence, this probably is no longer used.
	function isLog()
	{
		if ( $this->mNamespace != Namespace::getWikipedia() ) {
			return false;
		}
		if ( ( 0 == strcmp( wfMsg( "uploadlogpage" ), $this->mDbkeyform ) ) ||
		  ( 0 == strcmp( wfMsg( "dellogpage" ), $this->mDbkeyform ) ) ) {
			return true;
		}
		return false;
	}

	# Is $wgUser is watching this page?
	function userIsWatching()
	{
		global $wgUser;

		if ( -1 == $this->mNamespace ) { return false; }
		if ( 0 == $wgUser->getID() ) { return false; }

		return $wgUser->isWatched( $this );
	}

	# Can $wgUser edit this page?
	function userCanEdit()
	{
		global $wgUser;
		if ( -1 == $this->mNamespace ) { return false; }
		if ( NS_MEDIAWIKI == $this->mNamespace && !$wgUser->isSysop() ) { return false; }
		# if ( 0 == $this->getArticleID() ) { return false; }
		if ( $this->mDbkeyform == "_" ) { return false; }
		# protect global styles and js
		if ( NS_MEDIAWIKI == $this->mNamespace 
	             && preg_match("/\\.(css|js)$/", $this->mTextform )
		     && !$wgUser->isSysop() )
		{ return false; }
		//if ( $this->isCssJsSubpage() and !$this->userCanEditCssJsSubpage() ) { return false; }
		# protect css/js subpages of user pages
		# XXX: this might be better using restrictions
		# XXX: Find a way to work around the php bug that prevents using $this->userCanEditCssJsSubpage() from working
		if( Namespace::getUser() == $this->mNamespace
			and preg_match("/\\.(css|js)$/", $this->mTextform )
			and !$wgUser->isSysop()
			and !preg_match("/^".preg_quote($wgUser->getName(), '/')."/", $this->mTextform) )
		{ return false; }
		$ur = $wgUser->getRights();
		foreach ( $this->getRestrictions() as $r ) {
			if ( "" != $r && ( ! in_array( $r, $ur ) ) ) {
				return false;
			}
		}
		return true;
	}
	
	function userCanRead() {
		global $wgUser;
		global $wgWhitelistRead;
		
		if( 0 != $wgUser->getID() ) return true;
		if( !is_array( $wgWhitelistRead ) ) return true;
		
		$name = $this->getPrefixedText();
		if( in_array( $name, $wgWhitelistRead ) ) return true;
		
		# Compatibility with old settings
		if( $this->getNamespace() == NS_MAIN ) {
			if( in_array( ":" . $name, $wgWhitelistRead ) ) return true;
		}
		return false;
	}
	
	function isCssJsSubpage() {
		return ( Namespace::getUser() == $this->mNamespace and preg_match("/\\.(css|js)$/", $this->mTextform ) );
	}
	function isCssSubpage() {
		return ( Namespace::getUser() == $this->mNamespace and preg_match("/\\.css$/", $this->mTextform ) );
	}
	function isJsSubpage() {
		return ( Namespace::getUser() == $this->mNamespace and preg_match("/\\.js$/", $this->mTextform ) );
	}
	function userCanEditCssJsSubpage() {
		# protect css/js subpages of user pages
		# XXX: this might be better using restrictions
		global $wgUser;
		return ( $wgUser->isSysop() or preg_match("/^".preg_quote($wgUser->getName())."/", $this->mTextform) );
	}

	# Accessor/initialisation for mRestrictions
	function getRestrictions()
	{
		$id = $this->getArticleID();
		if ( 0 == $id ) { return array(); }

		if ( ! $this->mRestrictionsLoaded ) {
			$dbr =& wfGetDB( DB_SLAVE );
			$res = $dbr->getField( "cur", "cur_restrictions", "cur_id=$id" );
			$this->mRestrictions = explode( ",", trim( $res ) );
			$this->mRestrictionsLoaded = true;
		}
		return $this->mRestrictions;
	}
	
	# Is there a version of this page in the deletion archive?
	# Returns the number of archived revisions
	function isDeleted() {
		$fname = 'Title::isDeleted';
		$dbr =& wfGetDB( DB_SLAVE );
		$n = $dbr->getField( 'archive', 'COUNT(*)', array( 'ar_namespace' => $this->getNamespace(), 
			'ar_title' => $this->getDBkey() ), $fname );
		return (int)$n;
	}

	# Get the article ID from the link cache
	# Used very heavily, e.g. in Parser::replaceInternalLinks()
	function getArticleID()
	{
		global $wgLinkCache;

		if ( -1 != $this->mArticleID ) { return $this->mArticleID; }
		$this->mArticleID = $wgLinkCache->addLinkObj( $this );
		return $this->mArticleID;
	}

	# This clears some fields in this object, and clears any associated keys in the
	# "bad links" section of $wgLinkCache. This is called from Article::insertNewArticle()
	# to allow loading of the new cur_id. It's also called from Article::doDeleteArticle()
	function resetArticleID( $newid )
	{
		global $wgLinkCache;
		$wgLinkCache->clearBadLink( $this->getPrefixedDBkey() );

		if ( 0 == $newid ) { $this->mArticleID = -1; }
		else { $this->mArticleID = $newid; }
		$this->mRestrictionsLoaded = false;
		$this->mRestrictions = array();
	}
	
	# Updates cur_touched
	# Called from LinksUpdate.php
	function invalidateCache() {
		$now = wfTimestampNow();
		$dbw =& wfGetDB( DB_MASTER );
		$success = $dbw->updateArray( 'cur', 
			array( /* SET */ 
				'cur_touched' => wfTimestampNow()
			), array( /* WHERE */ 
				'cur_namespace' => $this->getNamespace() ,
				'cur_title' => $this->getDBkey()
			), 'Title::invalidateCache'
		);
		return $success;
	}

	# Prefixes some arbitrary text with the namespace or interwiki prefix of this object
	/* private */ function prefix( $name )
	{
		global $wgLang;

		$p = "";
		if ( "" != $this->mInterwiki ) {
			$p = $this->mInterwiki . ":";
		}
		if ( 0 != $this->mNamespace ) {
			$p .= $wgLang->getNsText( $this->mNamespace ) . ":";
		}
		return $p . $name;
	}

	# Secure and split - main initialisation function for this object
	# 
	# Assumes that mDbkeyform has been set, and is urldecoded
    # and uses underscores, but not otherwise munged.  This function
    # removes illegal characters, splits off the interwiki and
    # namespace prefixes, sets the other forms, and canonicalizes
    # everything.  	
	#
	/* private */ function secureAndSplit()
	{
		global $wgLang, $wgLocalInterwiki, $wgCapitalLinks;
		$fname = "Title::secureAndSplit";
 		wfProfileIn( $fname );
		
		static $imgpre = false;
		static $rxTc = false;

		# Initialisation
		if ( $imgpre === false ) {
			$imgpre = ":" . $wgLang->getNsText( Namespace::getImage() ) . ":";
			# % is needed as well
			$rxTc = "/[^" . Title::legalChars() . "]/";
		}

		$this->mInterwiki = $this->mFragment = "";
		$this->mNamespace = $this->mDefaultNamespace; # Usually NS_MAIN

		# Clean up whitespace
		#
		$t = preg_replace( "/[\\s_]+/", "_", $this->mDbkeyform );
		$t = preg_replace( '/^_*(.*?)_*$/', '$1', $t );

		if ( "" == $t ) {
			wfProfileOut( $fname );
			return false;
		}

		$this->mDbkeyform = $t;
		$done = false;

		# :Image: namespace
		if ( 0 == strncasecmp( $imgpre, $t, strlen( $imgpre ) ) ) {
			$t = substr( $t, 1 );
		}

		# Initial colon indicating main namespace
		if ( ":" == $t{0} ) {
			$r = substr( $t, 1 );
			$this->mNamespace = NS_MAIN;
		} else {
			# Namespace or interwiki prefix
	 		if ( preg_match( "/^(.+?)_*:_*(.*)$/", $t, $m ) ) {
				#$p = strtolower( $m[1] );
				$p = $m[1];
				$lowerNs = strtolower( $p );
				if ( $ns = Namespace::getCanonicalIndex( $lowerNs ) ) {
					# Canonical namespace
					$t = $m[2];
					$this->mNamespace = $ns;
				} elseif ( $ns = $wgLang->getNsIndex( $lowerNs )) {
					# Ordinary namespace
					$t = $m[2];
					$this->mNamespace = $ns;
				} elseif ( $this->getInterwikiLink( $p ) ) {
					# Interwiki link
					$t = $m[2];
					$this->mInterwiki = $p;

					if ( !preg_match( "/^([A-Za-z0-9_\\x80-\\xff]+):(.*)$/", $t, $m ) ) {
						$done = true;
					} elseif($this->mInterwiki != $wgLocalInterwiki) {
						$done = true;
					}
				}
			}
			$r = $t;
		}

		# Redundant interwiki prefix to the local wiki
		if ( 0 == strcmp( $this->mInterwiki, $wgLocalInterwiki ) ) {
			$this->mInterwiki = "";
		}
		# We already know that some pages won't be in the database!
		#
		if ( "" != $this->mInterwiki || -1 == $this->mNamespace ) {
			$this->mArticleID = 0;
		}
		$f = strstr( $r, "#" );
		if ( false !== $f ) {
			$this->mFragment = substr( $f, 1 );
			$r = substr( $r, 0, strlen( $r ) - strlen( $f ) );
		}

		# Reject illegal characters.
		#
		if( preg_match( $rxTc, $r ) ) {
			wfProfileOut( $fname );
			return false;
		}
		
		# "." and ".." conflict with the directories of those namesa
		if ( strpos( $r, "." ) !== false &&
		     ( $r === "." || $r === ".." ||
		       strpos( $r, "./" ) === 0  ||
		       strpos( $r, "../" ) === 0 ||
		       strpos( $r, "/./" ) !== false ||
		       strpos( $r, "/../" ) !== false ) )
		{
			wfProfileOut( $fname );
			return false;
		}

		# Initial capital letter
		if( $wgCapitalLinks && $this->mInterwiki == "") {
			$t = $wgLang->ucfirst( $r );
		} else {
			$t = $r;
		}
		
		# Fill fields
		$this->mDbkeyform = $t;
		$this->mUrlform = wfUrlencode( $t );
		
		$this->mTextform = str_replace( "_", " ", $t );
		
		wfProfileOut( $fname );
		return true;
	}
	
	# Get a title object associated with the talk page of this article
	function getTalkPage() {
		return Title::makeTitle( Namespace::getTalk( $this->getNamespace() ), $this->getDBkey() );
	}
	
	# Get a title object associated with the subject page of this talk page
	function getSubjectPage() {
		return Title::makeTitle( Namespace::getSubject( $this->getNamespace() ), $this->getDBkey() );
	}

	# Get an array of Title objects linking to this title
	# Also stores the IDs in the link cache
	# $options may be FOR UPDATE
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
		$res = $db->query( $sql, "Title::getLinksTo" );
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

	# Get an array of Title objects linking to this non-existent title
	# Also stores the IDs in the link cache
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

	function getSquidURLs() {
		return array(
			$this->getInternalURL(),
			$this->getInternalURL( "action=history" )
		);
	}

	function moveNoAuth( &$nt ) {
		return $this->moveTo( $nt, false );
	}
	
	# Move a title to a new location
	# Returns true on success, message name on failure
	# auth indicates whether wgUser's permissions should be checked
	function moveTo( &$nt, $auth = true ) {
		if( !$this or !$nt ) {
			return "badtitletext";
		}

		$fname = "Title::move";
		$oldid = $this->getArticleID();
		$newid = $nt->getArticleID();

		if ( strlen( $nt->getDBkey() ) < 1 ) {
			return "articleexists";
		}
		if ( ( ! Namespace::isMovable( $this->getNamespace() ) ) ||
			 ( "" == $this->getDBkey() ) ||
			 ( "" != $this->getInterwiki() ) ||
			 ( !$oldid ) ||
		     ( ! Namespace::isMovable( $nt->getNamespace() ) ) ||
			 ( "" == $nt->getDBkey() ) ||
			 ( "" != $nt->getInterwiki() ) ) {
			return "badarticleerror";
		}

		if ( $auth && ( !$this->userCanEdit() || !$nt->userCanEdit() ) ) {
			return "protectedpage";
		}
		
		# The move is allowed only if (1) the target doesn't exist, or
		# (2) the target is a redirect to the source, and has no history
		# (so we can undo bad moves right after they're done).

		if ( 0 != $newid ) { # Target exists; check for validity
			if ( ! $this->isValidMoveTarget( $nt ) ) {
				return "articleexists";
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
		$dbw->query( $sql, "SpecialMovepage::doSubmit" );
		

		# Update watchlists
		
		$oldnamespace = $this->getNamespace() & ~1;
		$newnamespace = $nt->getNamespace() & ~1;
		$oldtitle = $this->getDBkey();
		$newtitle = $nt->getDBkey();

		if( $oldnamespace != $newnamespace && $oldtitle != $newtitle ) {
			WatchedItem::duplicateEntries( $this, $nt );
		}

		# Update search engine
		$u = new SearchUpdate( $oldid, $nt->getPrefixedDBkey() );
		$u->doUpdate();
		$u = new SearchUpdate( $newid, $this->getPrefixedDBkey(), "" );
		$u->doUpdate();

		return true;
	}
	
	# Move page to title which is presently a redirect to the source page
	
	/* private */ function moveOverExistingRedirect( &$nt )
	{
		global $wgUser, $wgLinkCache, $wgUseSquid, $wgMwRedir;
		$fname = "Title::moveOverExistingRedirect";
		$comment = wfMsg( "1movedto2", $this->getPrefixedText(), $nt->getPrefixedText() );
		
        $now = wfTimestampNow();
        $won = wfInvertTimestamp( $now );
		$newid = $nt->getArticleID();
		$oldid = $this->getArticleID();
		$dbw =& wfGetDB( DB_MASTER );
		$links = $dbw->tableName( 'links' );

		# Change the name of the target page:
		$dbw->updateArray( 'cur',
			/* SET */ array( 
				'cur_touched' => $now, 
				'cur_namespace' => $nt->getNamespace(),
				'cur_title' => $nt->getDBkey()
			), 
			/* WHERE */ array( 'cur_id' => $oldid ),
			$fname
		);
		$wgLinkCache->clearLink( $nt->getPrefixedDBkey() );

		# Repurpose the old redirect. We don't save it to history since
		# by definition if we've got here it's rather uninteresting.
		
		$redirectText = $wgMwRedir->getSynonym( 0 ) . " [[" . $nt->getPrefixedText() . "]]\n";
		$dbw->updateArray( 'cur',
			/* SET */ array(
				'cur_touched' => $now,
				'cur_timestamp' => $now,
				'inverse_timestamp' => $won,
				'cur_namespace' => $this->getNamespace(),
				'cur_title' => $this->getDBkey(),
				'cur_text' => $wgMwRedir->getSynonym( 0 ) . " [[" . $nt->getPrefixedText() . "]]\n",
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
		$dbw->updateArray(
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
					$sql .= ",";
				}
				$id = $linkTitle->getArticleID();
				$sql .= "($id,$newid)";
			}

			# Insert links to new title
			foreach ( $linksToNew as $linkTitle ) {
				if ( $first ) {
					$first = false;
				} else {
					$sql .= ",";
				}
				$id = $linkTitle->getArticleID();
				$sql .= "($id, $oldid)";
			}

			$dbw->query( $sql, DB_MASTER, $fname );
		}

		# Now, we record the link from the redirect to the new title.
		# It should have no other outgoing links...
		$dbw->delete( 'links', array( 'l_from' => $newid ) );
		$dbw->insertArray( 'links', array( 'l_from' => $newid, 'l_to' => $oldid ) );
		
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

	# Move page to non-existing title.
	# Sets $newid to be the new article ID

	/* private */ function moveToNewTitle( &$nt, &$newid )
	{
		global $wgUser, $wgLinkCache, $wgUseSquid;
		$fname = "MovePageForm::moveToNewTitle";
		$comment = wfMsg( "1movedto2", $this->getPrefixedText(), $nt->getPrefixedText() );

		$now = wfTimestampNow();
		$won = wfInvertTimestamp( $now );
		$newid = $nt->getArticleID();
		$oldid = $this->getArticleID();
		$dbw =& wfGetDB( DB_MASTER );

		# Rename cur entry
		$dbw->updateArray( 'cur',
			/* SET */ array(
				'cur_touched' => $now,
				'cur_namespace' => $nt->getNamespace(),
				'cur_title' => $nt->getDBkey()
			),
			/* WHERE */ array( 'cur_id' => $oldid ),
			$fname
		);
		
		$wgLinkCache->clearLink( $nt->getPrefixedDBkey() );

		# Insert redirct
		$dbw->insertArray( 'cur', array(
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
			'cur_text' => "#REDIRECT [[" . $nt->getPrefixedText() . "]]\n" ), $fname
		);
		$newid = $dbw->insertId();
		$wgLinkCache->clearLink( $this->getPrefixedDBkey() );

		# Rename old entries
		$dbw->updateArray( 
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
		$dbw->updateArray( 'links', array( 'l_to' => $newid ), array( 'l_to' => $oldid ), $fname );
		LinkCache::linksccClearLinksTo( $oldid );

		# Record the just-created redirect's linking to the page
		$dbw->insertArray( 'links', array( 'l_from' => $newid, 'l_to' => $oldid ), $fname );

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

	# Checks if $this can be moved to $nt
	# Selects for update, so don't call it unless you mean business
	function isValidMoveTarget( $nt )
	{
		$fname = "Title::isValidMoveTarget";
		$dbw =& wfGetDB( DB_MASTER );

		# Is it a redirect?
		$id  = $nt->getArticleID();
		$obj = $dbw->getArray( 'cur', array( 'cur_is_redirect','cur_text' ), 
			array( 'cur_id' => $id ), $fname, 'FOR UPDATE' );

		if ( !$obj || 0 == $obj->cur_is_redirect ) { 
			# Not a redirect
			return false; 
		}

		# Does the redirect point to the source?
		if ( preg_match( "/\\[\\[\\s*([^\\]]*)]]/", $obj->cur_text, $m ) ) {
			$redirTitle = Title::newFromText( $m[1] );
			if( !is_object( $redirTitle ) ||
				$redirTitle->getPrefixedDBkey() != $this->getPrefixedDBkey() ) {
				return false;
			}
		}

		# Does the article have a history?
		$row = $dbw->getArray( 'old', array( 'old_id' ), 
			array( 
				'old_namespace' => $nt->getNamespace(),
				'old_title' => $nt->getDBkey() 
			), $fname, 'FOR UPDATE' 
		);

		# Return true if there was no history
		return $row === false;
	}
	
	# Create a redirect, fails if the title already exists, does not notify RC
	# Returns success
	function createRedirect( $dest, $comment ) {
		global $wgUser;
		if ( $this->getArticleID() ) {
			return false;
		}
		
		$fname = "Title::createRedirect";
		$dbw =& wfGetDB( DB_MASTER );
		$now = wfTimestampNow();
		$won = wfInvertTimestamp( $now );
		$seqVal = $dbw->nextSequenceValue( 'cur_cur_id_seq' );

		$dbw->insertArray( 'cur', array(
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
			$dbw->insertArray( 'links', 
				array(
					'l_to' => $dest->getArticleID(),
					'l_from' => $newid
				), $fname 
			);
		} else {
			$dbw->insertArray( 'brokenlinks', 
				array( 
					'bl_to' => $dest->getPrefixedDBkey(),
					'bl_from' => $newid
				), $fname
			);
		}

		Article::onArticleCreate( $this );
		return true;
	}
	
	# Get categories to wich belong this title and return an array of
	# categories names.
	function getParentCategories( )
	{
		global $wgLang,$wgUser;
		
		$titlekey = $this->getArticleId();
		$cns = Namespace::getCategory();
		$sk =& $wgUser->getSkin();
		$parents = array();
		$dbr =& wfGetDB( DB_SLAVE );
		$cur = $dbr->tableName( 'cur' );
		$categorylinks = $dbr->tableName( 'categorylinks' );

		# get the parents categories of this title from the database
		$sql = "SELECT DISTINCT cur_id FROM $cur,$categorylinks
		        WHERE cl_from='$titlekey' AND cl_to=cur_title AND cur_namespace='$cns'
				ORDER BY cl_sortkey" ;
		$res = $dbr->query ( $sql ) ;
		
		if($dbr->numRows($res) > 0) {
			while ( $x = $dbr->fetchObject ( $res ) ) $data[] = $x ;
			$dbr->freeResult ( $res ) ;
		} else {
			$data = '';
		}
		return $data;
	}
	
	# will get the parents and grand-parents
	# TODO : not sure what's happening when a loop happen like:
	# 	Encyclopedia > Astronomy > Encyclopedia
	function getAllParentCategories(&$stack)
	{
		global $wgUser,$wgLang;
		$result = '';
		
		# getting parents
		$parents = $this->getParentCategories( );

		if($parents == '')
		{
			# The current element has no more parent so we dump the stack
			# and make a clean line of categories
			$sk =& $wgUser->getSkin() ;

			foreach ( array_reverse($stack) as $child => $parent )
			{
				# make a link of that parent
				$result .= $sk->makeLink($wgLang->getNSText ( Namespace::getCategory() ).":".$parent,$parent);
				$result .= ' &gt; ';
				$lastchild = $child;
			}
			# append the last child.
			# TODO : We should have a last child unless there is an error in the
			# "categorylinks" table.
			if(isset($lastchild)) { $result .= $lastchild; }
			
			$result .= "<br/>\n";
			
			# now we can empty the stack
			$stack = array();
			
		} else {
			# look at parents of current category
			foreach($parents as $parent)
			{
				# create a title object for the parent
				$tpar = Title::newFromID($parent->cur_id);
				# add it to the stack
				$stack[$this->getText()] = $tpar->getText();
				# grab its parents
				$result .= $tpar->getAllParentCategories($stack);
			}
		}

		if(isset($result)) { return $result; }
		else { return ''; };
	}
	
	# Returns an associative array for selecting this title from cur
	function curCond() {
		return array( 'cur_namespace' => $this->mNamespace, 'cur_title' => $this->mDbkeyform );
	}

	function oldCond() {
		return array( 'old_namespace' => $this->mNamespace, 'old_title' => $this->mDbkeyform );
	}
}
?>

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
		$s = urldecode( $url ); # This is technically wrong, as anything
								# we've gotten is already decoded by PHP.
								# Kept for backwards compatibility with
								# buggy URLs we had for a while...
		$s = $url;
		
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

			# check that lenght of title is < cur_title size
			$sql = "SHOW COLUMNS FROM cur LIKE \"cur_title\";";
			$cur_title_object = wfFetchObject(wfQuery( $sql, DB_READ ));

			preg_match( "/\((.*)\)/", $cur_title_object->Type, $cur_title_size);

			if (strlen($t->mDbkeyform) > $cur_title_size[1] ) {
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
		$row = wfGetArray( "cur", array( "cur_namespace", "cur_title" ), 
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

	function newMainPage()
	{
		return Title::newFromText( wfMsg( "mainpage" ) );
	}
	
#----------------------------------------------------------------------------
#	Static functions
#----------------------------------------------------------------------------

	# Get the prefixed DB key associated with an ID
	/* static */ function nameOf( $id )
	{
		$sql = "SELECT cur_namespace,cur_title FROM cur WHERE " .
		  "cur_id={$id}";
		$res = wfQuery( $sql, DB_READ, "Article::nameOf" );
		if ( 0 == wfNumRows( $res ) ) { return NULL; }

		$s = wfFetchObject( $res );
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
		# Theoretically 0x80-0x9F of ISO 8859-1 should be disallowed, but
		# this breaks interlanguage links
		
		$set = " !\"$&'()*,\\-.\\/0-9:;<=>?@A-Z\\\\^_`a-z{}~\\x80-\\xFF";
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
		global $wgMemc, $wgDBname, $wgInterwikiExpiry;
		static $wgTitleInterwikiCache = array();

		$k = "$wgDBname:interwiki:$key";

		if( array_key_exists( $k, $wgTitleInterwikiCache ) )
			return $wgTitleInterwikiCache[$k]->iw_url;

		$s = $wgMemc->get( $k ); 
		# Ignore old keys with no iw_local
		if( $s && isset( $s->iw_local ) ) { 
			$wgTitleInterwikiCache[$k] = $s;
			return $s->iw_url;
		}
		$dkey = wfStrencode( $key );
		$query = "SELECT iw_url,iw_local FROM interwiki WHERE iw_prefix='$dkey'";
		$res = wfQuery( $query, DB_READ, "Title::getInterwikiLink" );
		if(!$res) return "";
		
		$s = wfFetchObject( $res );
		if(!$s) {
			$s = (object)false;
			$s->iw_url = "";
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
		$sql = "UPDATE cur SET cur_touched='{$timestamp}' WHERE cur_id IN (";
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
			wfQuery( $sql, DB_WRITE, "Title::touchArray" );
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
		}
		
		$p = $this->getInterwikiLink( $this->mInterwiki );
		$n = $wgLang->getNsText( $this->mNamespace );
		if ( "" != $n ) { $n .= ":"; }
		$u = str_replace( "$1", $n . $this->mUrlform, $p );
		if ( "" != $this->mFragment ) {
			$u .= "#" . wfUrlencode( $this->mFragment );
		}
		return $u;
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

		if ( -1 == $this->mNamespace ) { return false; }
		# if ( 0 == $this->getArticleID() ) { return false; }
		if ( $this->mDbkeyform == "_" ) { return false; }
		//if ( $this->isCssJsSubpage() and !$this->userCanEditCssJsSubpage() ) { return false; }
		# protect css/js subpages of user pages
		# XXX: this might be better using restrictions
		# XXX: Find a way to work around the php bug that prevents using $this->userCanEditCssJsSubpage() from working
		global $wgUser;
		if( Namespace::getUser() == $this->mNamespace
			and preg_match("/\\.(css|js)$/", $this->mTextform )
			and !$wgUser->isSysop()
			and !preg_match("/^".$wgUser->getName()."/", $this->mTextform) )
		{ return false; }
		$ur = $wgUser->getRights();
		foreach ( $this->getRestrictions() as $r ) {
			if ( "" != $r && ( ! in_array( $r, $ur ) ) ) {
				return false;
			}
		}
		return true;
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
		return ( $wgUser->isSysop() or preg_match("/^".$wgUser->getName()."/", $this->mTextform) );
	}

	# Accessor/initialisation for mRestrictions
	function getRestrictions()
	{
		$id = $this->getArticleID();
		if ( 0 == $id ) { return array(); }

		if ( ! $this->mRestrictionsLoaded ) {
			$res = wfGetSQL( "cur", "cur_restrictions", "cur_id=$id" );
			$this->mRestrictions = explode( ",", trim( $res ) );
			$this->mRestrictionsLoaded = true;
		}
		return $this->mRestrictions;
	}
	
	# Is there a version of this page in the deletion archive?
	function isDeleted() {
		$ns = $this->getNamespace();
		$t = wfStrencode( $this->getDBkey() );
		$sql = "SELECT COUNT(*) AS n FROM archive WHERE ar_namespace=$ns AND ar_title='$t'";
		if( $res = wfQuery( $sql, DB_READ ) ) {
			$s = wfFetchObject( $res );
			return $s->n;
		}
		return 0;
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
		$ns = $this->getNamespace();
		$ti = wfStrencode( $this->getDBkey() );
		$sql = "UPDATE cur SET cur_touched='$now' WHERE cur_namespace=$ns AND cur_title='$ti'";
		return wfQuery( $sql, DB_WRITE, "Title::invalidateCache" );
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
    # and uses undersocres, but not otherwise munged.  This function
    # removes illegal characters, splits off the winterwiki and
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
	 		if ( preg_match( "/^((?:i|x|[a-z]{2,3})(?:-[a-z0-9]+)?|[A-Za-z0-9_\\x80-\\xff]+?)_*:_*(.*)$/", $t, $m ) ) {
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
			return false;
		}
		
		# "." and ".." conflict with the directories of those names
		if ( $r === "." || $r === ".." ) {
			return false;
		}

		# Initial capital letter
		if( $wgCapitalLinks && $this->mInterwiki == "") {
			$t = $wgLang->ucfirst( $r );
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
	function getLinksTo() {
		global $wgLinkCache;
		$id = $this->getArticleID();
		$sql = "SELECT cur_namespace,cur_title,cur_id FROM cur,links WHERE l_from=cur_id AND l_to={$id}";
		$res = wfQuery( $sql, DB_READ, "Title::getLinksTo" );
		$retVal = array();
		if ( wfNumRows( $res ) ) {
			while ( $row = wfFetchObject( $res ) ) {
				$titleObj = Title::makeTitle( $row->cur_namespace, $row->cur_title );
				$wgLinkCache->addGoodLink( $row->cur_id, $titleObj->getPrefixedDBkey() );
				$retVal[] = $titleObj;
			}
		}
		wfFreeResult( $res );
		return $retVal;
	}

	# Get an array of Title objects linking to this non-existent title
	# Also stores the IDs in the link cache
	function getBrokenLinksTo() {
		global $wgLinkCache;
		$encTitle = wfStrencode( $this->getPrefixedDBkey() );
		$sql = "SELECT cur_namespace,cur_title,cur_id FROM brokenlinks,cur " .
		  "WHERE bl_from=cur_id AND bl_to='$encTitle'";
		$res = wfQuery( $sql, DB_READ, "Title::getBrokenLinksTo" );
		$retVal = array();
		if ( wfNumRows( $res ) ) {
			while ( $row = wfFetchObject( $res ) ) {
				$titleObj = Title::makeTitle( $row->cur_namespace, $row->cur_title );
				$wgLinkCache->addGoodLink( $titleObj->getPrefixedDBkey(), $row->cur_id );
				$retVal[] = $titleObj;
			}
		}
		wfFreeResult( $res );
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
		
		# Change the name of the target page:
		wfUpdateArray( 
			/* table */ 'cur',
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
		wfUpdateArray( 
			/* table */ 'cur',
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
		wfUpdateArray(
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
		
		RecentChange::notifyMove( $now, $this, $nt, $wgUser, $comment );

		# Swap links
		
		# Load titles and IDs
		$linksToOld = $this->getLinksTo();
		$linksToNew = $nt->getLinksTo();
		
		# Make function to convert Titles to IDs
		$titleToID = create_function('$t', 'return $t->getArticleID();');

		# Reassign links to old title
		if ( count( $linksToOld ) ) {
			$sql = "UPDATE links SET l_to=$newid WHERE l_from IN (";
			$sql .= implode( ",", array_map( $titleToID, $linksToOld ) );
			$sql .= ")";
			wfQuery( $sql, DB_WRITE, $fname );
		}
		
		# Reassign links to new title
		if ( count( $linksToNew ) ) {
			$sql = "UPDATE links SET l_to=$oldid WHERE l_from IN (";
			$sql .= implode( ",", array_map( $titleToID, $linksToNew ) );
			$sql .= ")";
			wfQuery( $sql, DB_WRITE, $fname );
		}

		# Note: the insert below must be after the updates above!

		# Now, we record the link from the redirect to the new title.
		# It should have no other outgoing links...
		$sql = "DELETE FROM links WHERE l_from={$newid}";
		wfQuery( $sql, DB_WRITE, $fname );
		$sql = "INSERT INTO links (l_from,l_to) VALUES ({$newid},{$oldid})";
		wfQuery( $sql, DB_WRITE, $fname );

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

		# Rename cur entry
		wfUpdateArray(
			/* table */ 'cur',
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
		wfInsertArray( 'cur', array(
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
			'cur_text' => "#REDIRECT [[" . $nt->getPrefixedText() . "]]\n" )
		);
		$newid = wfInsertId();
		$wgLinkCache->clearLink( $this->getPrefixedDBkey() );

		# Rename old entries
		wfUpdateArray( 
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
		
		# Miscellaneous updates

		RecentChange::notifyMove( $now, $this, $nt, $wgUser, $comment );
		Article::onArticleCreate( $nt );

		# Any text links to the old title must be reassigned to the redirect
		$sql = "UPDATE links SET l_to={$newid} WHERE l_to={$oldid}";
		wfQuery( $sql, DB_WRITE, $fname );

		# Record the just-created redirect's linking to the page
		$sql = "INSERT INTO links (l_from,l_to) VALUES ({$newid},{$oldid})";
		wfQuery( $sql, DB_WRITE, $fname );

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
	# Both titles must exist in the database, otherwise it will blow up
	function isValidMoveTarget( $nt )
	{
		$fname = "Title::isValidMoveTarget";

		# Is it a redirect?
		$id  = $nt->getArticleID();
		$sql = "SELECT cur_is_redirect,cur_text FROM cur " .
		  "WHERE cur_id={$id}";
		$res = wfQuery( $sql, DB_READ, $fname );
		$obj = wfFetchObject( $res );

		if ( 0 == $obj->cur_is_redirect ) { 
			# Not a redirect
			return false; 
		}

		# Does the redirect point to the source?
		if ( preg_match( "/\\[\\[\\s*([^\\]]*)]]/", $obj->cur_text, $m ) ) {
			$redirTitle = Title::newFromText( $m[1] );
			if ( 0 != strcmp( $redirTitle->getPrefixedDBkey(), $this->getPrefixedDBkey() ) ) {
				return false;
			}
		}

		# Does the article have a history?
		$row = wfGetArray( 'old', array( 'old_id' ), array( 
			'old_namespace' => $nt->getNamespace(),
			'old_title' => $nt->getDBkey() )
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
		
		$now = wfTimestampNow();
		$won = wfInvertTimestamp( $now );

		wfInsertArray( 'cur', array(
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
		));
		$newid = wfInsertId();
		$this->resetArticleID( $newid );
		
		# Link table
		if ( $dest->getArticleID() ) {
			wfInsertArray( 'links', array(
				'l_to' => $dest->getArticleID(),
				'l_from' => $newid
			));
		} else {
			wfInsertArray( 'brokenlinks', array( 
				'bl_to' => $dest->getPrefixedDBkey(),
				'bl_from' => $newid
			));
		}

		Article::onArticleCreate( $this );
		return true;
	}
}
?>

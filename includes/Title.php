<?php
# See title.doc

/* private static */ $title_interwiki_cache = array();

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
	/* static */ function newFromText( $text )
	{	
		static $trans;
		$fname = "Title::newFromText";
		wfProfileIn( $fname );
		
		# Note - mixing latin1 named entities and unicode numbered
		# ones will result in a bad link.
		if( !isset( $trans ) ) {
			global $wgInputEncoding;
			$trans = array_flip( get_html_translation_table( HTML_ENTITIES ) );
			if( strcasecmp( "utf-8", $wgInputEncoding ) == 0 ) {
			    $trans = array_map( "utf8_encode", $trans );
			}
		}

		if( is_object( $text ) ) {
			wfDebugDieBacktrace( "Called with object instead of string." );
		}
		$text = strtr( $text, $trans );
		
		$text = wfMungeToUtf8( $text );
		
		
		# What was this for? TS 2004-03-03
		# $text = urldecode( $text );

		$t = new Title();
		$t->mDbkeyform = str_replace( " ", "_", $text );
		wfProfileOut( $fname );
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
		wfDebug( "Refer: {$_SERVER['HTTP_REFERER']}\n" );
		wfDebug( "Servr: $wgServer\n" );
		if( empty( $_SERVER["HTTP_REFERER"] ) ||
			strncmp($wgServer, $_SERVER["HTTP_REFERER"], strlen( $wgServer ) ) ) 
		{
			$s = $wgLang->checkTitleEncoding( $s );
		}
		
		$t->mDbkeyform = str_replace( " ", "_", $s );
		if( $t->secureAndSplit() ) {
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
		global $wgMemc, $wgDBname, $title_interwiki_cache;
		$k = "$wgDBname:interwiki:$key";

		if( array_key_exists( $k, $title_interwiki_cache ) )
			return $title_interwiki_cache[$k]->iw_url;

		$s = $wgMemc->get( $k ); 
		if( $s ) { 
			$title_interwiki_cache[$k] = $s;
			return $s->iw_url;
		}
		$dkey = wfStrencode( $key );
		$query = "SELECT iw_url FROM interwiki WHERE iw_prefix='$dkey'";
		$res = wfQuery( $query, DB_READ, "Title::getInterwikiLink" );
		if(!$res) return "";
		
		$s = wfFetchObject( $res );
		if(!$s) {
			$s = (object)false;
			$s->iw_url = "";
		}
		$wgMemc->set( $k, $s );
		$title_interwiki_cache[$k] = $s;
		return $s->iw_url;
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
		global $wgUser;

		if ( -1 == $this->mNamespace ) { return false; }
		# if ( 0 == $this->getArticleID() ) { return false; }
		if ( $this->mDbkeyform == "_" ) { return false; }

		$ur = $wgUser->getRights();
		foreach ( $this->getRestrictions() as $r ) {
			if ( "" != $r && ( ! in_array( $r, $ur ) ) ) {
				return false;
			}
		}
		return true;
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
		global $wgLang, $wgLocalInterwiki;
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
		$this->mNamespace = 0;

		# Clean up whitespace
		#
		$t = preg_replace( "/[\\s_]+/", "_", $this->mDbkeyform );
		if ( "_" == $t{0} ) { 
			$t = substr( $t, 1 ); 
		}
		$l = strlen( $t );
		if ( $l && ( "_" == $t{$l-1} ) ) { 
			$t = substr( $t, 0, $l-1 ); 
		}
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

		# Redundant initial colon
		if ( ":" == $t{0} ) {
			$r = substr( $t, 1 );
		} else {
			# Namespace or interwiki prefix
	 		if ( preg_match( "/^((?:i|x|[a-z]{2,3})(?:-[a-z0-9]+)?|[A-Za-z0-9_\\x80-\\xff]+):_*(.*)$/", $t, $m ) ) {
				#$p = strtolower( $m[1] );
				$p = $m[1];
				if ( $ns = $wgLang->getNsIndex( strtolower( $p ) )) {
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
		if( $this->mInterwiki == "") $t = $wgLang->ucfirst( $r );
		
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
}
?>

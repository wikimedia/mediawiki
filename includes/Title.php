<?php
# See title.doc

/* private */ $wgTitleInterwikiCache = array();

class Title {
	/* private */ var $mTextform, $mUrlform, $mDbkeyform;
	/* private */ var $mNamespace, $mInterwiki, $mFragment;
	/* private */ var $mArticleID, $mRestrictions, $mRestrictionsLoaded;
	/* private */ var $mPrefixedText;

	/* private */ function Title()
	{
		$this->mInterwiki = $this->mUrlform =
		$this->mTextform = $this->mDbkeyform = "";
		$this->mArticleID = -1;
		$this->mNamespace = 0;
		$this->mRestrictionsLoaded = false;
		$this->mRestrictions = array();
	}

	# Static factory methods
	#
	function newFromDBkey( $key )
	{
		$t = new Title();
		$t->mDbkeyform = $key;
		if( $t->secureAndSplit() )
			return $t;
		else
			return NULL;
	}
	
	function newFromText( $text )
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

		$text = strtr( $text, $trans );
		
		$text = wfMungeToUtf8( $text );
		
		$text = urldecode( $text );

		$t = new Title();
		$t->mDbkeyform = str_replace( " ", "_", $text );
		wfProfileOut( $fname );
		if( $t->secureAndSplit() ) {
			return $t;
		} else {
			return NULL;
		}
	}

	function newFromURL( $url )
	{
		global $wgLang, $wgServer;
		
		$t = new Title();
		$s = urldecode( $url ); # This is technically wrong, as anything
								# we've gotten is already decoded by PHP.
								# Kept for backwards compatibility with
								# buggy URLs we had for a while...
		
		# For links that came from outside, check for alternate/legacy
		# character encoding.
		wfDebug( "Refer: {$_SERVER['HTTP_REFERER']}\n" );
		wfDebug( "Servr: $wgServer\n" );
		if( empty( $_SERVER["HTTP_REFERER"] ) ||
			strncmp($wgServer, $_SERVER["HTTP_REFERER"], strlen( $wgServer ) ) )
			$s = $wgLang->checkTitleEncoding( $s );
		
		$t->mDbkeyform = str_replace( " ", "_", $s );
		if( $t->secureAndSplit() ) {
			return $t;
		} else {
			return NULL;
		}
	}
	
	# Create a title from a cur id
	# This is inefficiently implemented
	function newFromID( $id ) 
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

	function nameOf( $id )
	{
		$sql = "SELECT cur_namespace,cur_title FROM cur WHERE " .
		  "cur_id={$id}";
		$res = wfQuery( $sql, DB_READ, "Article::nameOf" );
		if ( 0 == wfNumRows( $res ) ) { return NULL; }

		$s = wfFetchObject( $res );
		$n = Title::makeName( $s->cur_namespace, $s->cur_title );
		return $n;
	}


	function legalChars()
	{
		global $wgInputEncoding;
		if( $wgInputEncoding == "utf-8" ) {
			return "-,.()' &;%!?_0-9A-Za-z\\/:\\x80-\\xFF";
		} else {
			# ISO 8859-* don't allow 0x80-0x9F
			#return "-,.()' &;%!?_0-9A-Za-z\\/:\\xA0-\\xFF";
			# But that breaks interlanguage links at the moment. Temporary:
			return "-,.()' &;%!?_0-9A-Za-z\\/:\\x80-\\xFF";
		}
	}

	function getInterwikiLink( $key )
	{	
		global $wgMemc, $wgDBname, $wgTitleInterwikiCache, $wgInterwikiExpiry;
		$k = "$wgDBname:interwiki:$key";
		
		if( array_key_exists( $k, $wgTitleInterwikiCache ) ) {
			return $wgTitleInterwikiCache[$k]->iw_url;
		}

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

	function getText() { return $this->mTextform; }
	function getURL() { return $this->mUrlform; }
	function getDBkey() { return $this->mDbkeyform; }
	function getNamespace() { return $this->mNamespace; }
	function setNamespace( $n ) { $this->mNamespace = $n; }
	function getInterwiki() { return $this->mInterwiki; }
	function getFragment() { return $this->mFragment; }

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

	function getIndexTitle()
	{
		return Title::indexTitle( $this->mNamespace, $this->mTextform );
	}

	/* static */ function makeName( $ns, $title )
	{
		global $wgLang;

		$n = $wgLang->getNsText( $ns );
		if ( "" == $n ) { return $title; }
		else { return "{$n}:{$title}"; }
	}
	
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

	function getPrefixedDBkey()
	{
		$s = $this->prefix( $this->mDbkeyform );
		$s = str_replace( " ", "_", $s );
		return $s;
	}

	function getPrefixedText()
	{
	   # TEST THIS @@@
		if ( empty( $this->mPrefixedText ) ) {
			$s = $this->prefix( $this->mTextform );
			$s = str_replace( "_", " ", $s );
			$this->mPrefixedText = $s;
		}
		return $this->mPrefixedText;
	}

	function getPrefixedURL()
	{
		$s = $this->prefix( $this->mDbkeyform );
		$s = str_replace( " ", "_", $s );

		$s = urlencode ( $s ) ;
		# Cleaning up URL to make it look nice -- is this safe?
		$s = preg_replace( "/%3[Aa]/", ":", $s );
		$s = preg_replace( "/%2[Ff]/", "/", $s );
		$s = str_replace( "%28", "(", $s );
		$s = str_replace( "%29", ")", $s );
		return $s;
	}

	function getFullURL()
	{
		global $wgLang, $wgArticlePath;

		if ( "" == $this->mInterwiki ) {
			$p = $wgArticlePath;
		} else {
			$p = $this->getInterwikiLink( $this->mInterwiki );
		}
		$n = $wgLang->getNsText( $this->mNamespace );
		if ( "" != $n ) { $n .= ":"; }
		$u = str_replace( "$1", $n . $this->mUrlform, $p );
		if ( "" != $this->mFragment ) {
			$u .= "#" . $this->mFragment;
		}
		return $u;
	}

	function getEditURL()
	{
		global $wgServer, $wgScript;

		if ( "" != $this->mInterwiki ) { return ""; }
		$s = wfLocalUrl( $this->getPrefixedURL(), "action=edit" );

		return $s;
	}
	
	# For the title field in <a> tags
	function getEscapedText()
	{
		return wfEscapeHTML( $this->getPrefixedText() );
	}
	
	function isExternal() { return ( "" != $this->mInterwiki ); }

	function isProtected()
	{
		if ( -1 == $this->mNamespace ) { return true; }
		$a = $this->getRestrictions();
		if ( in_array( "sysop", $a ) ) { return true; }
		return false;
	}

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

	function userIsWatching()
	{
		global $wgUser;

		if ( -1 == $this->mNamespace ) { return false; }
		if ( 0 == $wgUser->getID() ) { return false; }

		return $wgUser->isWatched( $this );
	}

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

	function getArticleID()
	{
		global $wgLinkCache;

		if ( -1 != $this->mArticleID ) { return $this->mArticleID; }
		$this->mArticleID = $wgLinkCache->addLinkObj( $this );
		return $this->mArticleID;
	}

	function resetArticleID( $newid )
	{
		global $wgLinkCache;
		$wgLinkCache->clearBadLink( $this->getPrefixedDBkey() );

		if ( 0 == $newid ) { $this->mArticleID = -1; }
		else { $this->mArticleID = $newid; }
		$this->mRestrictionsLoaded = false;
		$this->mRestrictions = array();
	}
	
	function invalidateCache() {
		$now = wfTimestampNow();
		$ns = $this->getNamespace();
		$ti = wfStrencode( $this->getDBkey() );
		$sql = "UPDATE cur SET cur_touched='$now' WHERE cur_namespace=$ns AND cur_title='$ti'";
		return wfQuery( $sql, DB_WRITE, "Title::invalidateCache" );
	}

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

		if ( 0 == strncasecmp( $imgpre, $t, strlen( $imgpre ) ) ) {
			$t = substr( $t, 1 );
		}
		if ( ":" == $t{0} ) {
			$r = substr( $t, 1 );
		} else {
	 		if ( preg_match( "/^((?:i|x|[a-z]{2,3})(?:-[a-z0-9]+)?|[A-Za-z0-9_\\x80-\\xff]+):_*(.*)$/", $t, $m ) ) {
				#$p = strtolower( $m[1] );
				$p = $m[1];
				if ( $ns = $wgLang->getNsIndex( strtolower( $p ) )) {
					$t = $m[2];
					$this->mNamespace = $ns;
				} elseif ( $this->getInterwikiLink( $p ) ) {
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
		
		# "." and ".." conflict with the directories of those namesa
		if ( $r === "." || $r === ".." || strpos( $r, "./" ) !== false ) {
			return false;
		}

		if( $wgCapitalLinks && $this->mInterwiki == "") {
			$t = $wgLang->ucfirst( $r );
		}
		$this->mDbkeyform = $t;
		$this->mUrlform = wfUrlencode( $t );
		$this->mTextform = str_replace( "_", " ", $t );
		
		wfProfileOut( $fname );
		return true;
	}
	
	function getTalkPage() {
		return Title::makeTitle( Namespace::getTalk( $this->getNamespace() ), $this->getDBkey() );
	}
	
	function getSubjectPage() {
		return Title::makeTitle( Namespace::getSubject( $this->getNamespace() ), $this->getDBkey() );
	}
}
?>

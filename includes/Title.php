<?
# See title.doc
global $IP;
include_once( "$IP/Interwiki.php" );

class Title {
	/* private */ var $mTextform, $mUrlform, $mDbkeyform;
	/* private */ var $mNamespace, $mInterwiki, $mFragment;
	/* private */ var $mArticleID, $mRestrictions, $mRestrictionsLoaded;

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
		$t->secureAndSplit();
		return $t;
	}

	function newFromText( $text )
	{
		# Note - mixing latin1 named entities and unicode numbered
		# ones will result in a bad link.
		$trans = get_html_translation_table( HTML_ENTITIES );
		$trans = array_flip( $trans );
		$text = strtr( $text, $trans );
		
		$text = wfMungeToUtf8( $text );
		
		$text = urldecode( $text );

		$t = new Title();
		$t->mDbkeyform = str_replace( " ", "_", $text );
		$t->secureAndSplit();
		return $t;
	}

	function newFromURL( $url )
	{
		global $wgLang, $wgServer, $HTTP_SERVER_VARS;
		
		$t = new Title();
		$s = urldecode( $url ); # This is technically wrong, as anything
								# we've gotten is already decoded by PHP.
								# Kept for backwards compatibility with
								# buggy URLs we had for a while...
		
		# For links that came from outside, check for alternate/legacy
		# character encoding.
		if( strncmp($wgServer, $HTTP_SERVER_VARS["HTTP_REFERER"], strlen( $wgServer ) ) )
			$s = $wgLang->checkTitleEncoding( $s );
		
		$t->mDbkeyform = str_replace( " ", "_", $s );
		$t->secureAndSplit();
		return $t;
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
		global $wgValidInterwikis;

		if ( array_key_exists( $key, $wgValidInterwikis ) ) {
			return $wgValidInterwikis[$key];
		} else return "";
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
		$t->secureAndSplit();
		return $t;
	}

	function getPrefixedDBkey()
	{
		$s = $this->prefix( $this->mDbkeyform );
		$s = str_replace( " ", "_", $s );
		return $s;
	}

	function getPrefixedText()
	{
		$s = $this->prefix( $this->mTextform );
		$s = str_replace( "_", " ", $s );
		return $s;
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
		global $wgLang, $wgArticlePath, $wgValidInterwikis;

		if ( "" == $this->mInterwiki ) {
			$p = $wgArticlePath;
		} else {
			$p = $wgValidInterwikis[$this->mInterwiki];
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

	function getArticleID()
	{
		global $wgLinkCache;

		if ( -1 != $this->mArticleID ) { return $this->mArticleID; }
		$this->mArticleID = $wgLinkCache->addLink(
		  $this->getPrefixedDBkey() );
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
    # everything.  This one function is really at the core of
	# Wiki--don't mess with it unless you're really sure you know
	# what you're doing.
	#
	/* private */ function secureAndSplit()
	{
		global $wgLang, $wgValidInterwikis, $wgLocalInterwiki;

		$validNamespaces = $wgLang->getNamespaces();
		unset( $validNamespaces[0] );

		$this->mInterwiki = $this->mFragment = "";
		$this->mNamespace = 0;

		$t = preg_replace( "/[\\s_]+/", "_", $this->mDbkeyform );
		if ( "_" == $t{0} ) { $t = substr( $t, 1 ); }
		$l = strlen( $t );
		if ( $l && ( "_" == $t{$l-1} ) ) { $t = substr( $t, 0, $l-1 ); }
		if ( "" == $t ) { $t = "_"; }

		$this->mDbkeyform = $t;
		$done = false;

		$imgpre = ":" . $wgLang->getNsText( Namespace::getImage() ) . ":";
		if ( 0 == strncasecmp( $imgpre, $t, strlen( $imgpre ) ) ) {
			$t = substr( $t, 1 );
		}
		if ( ":" == $t{0} ) {
			$r = substr( $t, 1 );
		} else {
	 		if ( preg_match( "/^((?:i|x|[a-z]{2,3})(?:-[a-z0-9]+)?|[A-Za-z0-9_\\x80-\\xff]+):(.*)$/", $t, $m ) ) {
				#$p = strtolower( $m[1] );
				$p = $m[1];
				if ( array_key_exists( $p, $wgValidInterwikis ) ) {
					$t = $m[2];
					$this->mInterwiki = $p;

					if ( preg_match( "/^([A-Za-z0-9_\\x80-\\xff]+):(.*)$/",
					  $t, $m ) ) {
						$p = strtolower( $m[1] );
					} else {
						$done = true;
					}
					if($this->mInterwiki != $wgLocalInterwiki)
						$done = true;
				}
				if ( ! $done ) {
					if ( $ns = $wgLang->getNsIndex( str_replace( " ", "_", $p ))) {
						$t = $m[2];
						$this->mNamespace = $ns;
					}
				#	foreach ( $validNamespaces as $ns ) {
				#		if ( 0 == strcasecmp( $p, $ns ) ) {
				#			$t = $m[2];
				#			$this->mNamespace = $wgLang->getNsIndex(
				#			  str_replace( " ", "_", $p ) );
				#			break;
				#		}
				#	}
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
		# Strip illegal characters.
		#
		$tc = Title::legalChars();
		$t = preg_replace( "/[^{$tc}]/", "", $r );

		if( $this->mInterwiki == "") $t = $wgLang->ucfirst( $t );
		$this->mDbkeyform = $t;
		$this->mUrlform = wfUrlencode( $t );
		$this->mTextform = str_replace( "_", " ", $t );
	}
}
?>

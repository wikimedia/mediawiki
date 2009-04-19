<?php

/**
 * Various core parser functions, registered in Parser::firstCallInit()
 * @ingroup Parser
 */
class CoreParserFunctions {
	static function register( $parser ) {
		global $wgAllowDisplayTitle, $wgAllowSlowParserFunctions;

		# Syntax for arguments (see self::setFunctionHook):
		#  "name for lookup in localized magic words array",
		#  function callback,
		#  optional SFH_NO_HASH to omit the hash from calls (e.g. {{int:...}
		#    instead of {{#int:...}})

		$parser->setFunctionHook( 'int',              array( __CLASS__, 'intFunction'      ), SFH_NO_HASH );
		$parser->setFunctionHook( 'ns',               array( __CLASS__, 'ns'               ), SFH_NO_HASH );
		$parser->setFunctionHook( 'urlencode',        array( __CLASS__, 'urlencode'        ), SFH_NO_HASH );
		$parser->setFunctionHook( 'lcfirst',          array( __CLASS__, 'lcfirst'          ), SFH_NO_HASH );
		$parser->setFunctionHook( 'ucfirst',          array( __CLASS__, 'ucfirst'          ), SFH_NO_HASH );
		$parser->setFunctionHook( 'lc',               array( __CLASS__, 'lc'               ), SFH_NO_HASH );
		$parser->setFunctionHook( 'uc',               array( __CLASS__, 'uc'               ), SFH_NO_HASH );
		$parser->setFunctionHook( 'localurl',         array( __CLASS__, 'localurl'         ), SFH_NO_HASH );
		$parser->setFunctionHook( 'localurle',        array( __CLASS__, 'localurle'        ), SFH_NO_HASH );
		$parser->setFunctionHook( 'fullurl',          array( __CLASS__, 'fullurl'          ), SFH_NO_HASH );
		$parser->setFunctionHook( 'fullurle',         array( __CLASS__, 'fullurle'         ), SFH_NO_HASH );
		$parser->setFunctionHook( 'formatnum',        array( __CLASS__, 'formatnum'        ), SFH_NO_HASH );
		$parser->setFunctionHook( 'grammar',          array( __CLASS__, 'grammar'          ), SFH_NO_HASH );
		$parser->setFunctionHook( 'gender',           array( __CLASS__, 'gender'           ), SFH_NO_HASH );
		$parser->setFunctionHook( 'plural',           array( __CLASS__, 'plural'           ), SFH_NO_HASH );
		$parser->setFunctionHook( 'numberofpages',    array( __CLASS__, 'numberofpages'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'numberofusers',    array( __CLASS__, 'numberofusers'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'numberofactiveusers', array( __CLASS__, 'numberofactiveusers' ), SFH_NO_HASH );
		$parser->setFunctionHook( 'numberofarticles', array( __CLASS__, 'numberofarticles' ), SFH_NO_HASH );
		$parser->setFunctionHook( 'numberoffiles',    array( __CLASS__, 'numberoffiles'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'numberofadmins',   array( __CLASS__, 'numberofadmins'   ), SFH_NO_HASH );
		$parser->setFunctionHook( 'numberingroup',    array( __CLASS__, 'numberingroup'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'numberofedits',    array( __CLASS__, 'numberofedits'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'numberofviews',    array( __CLASS__, 'numberofviews'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'language',         array( __CLASS__, 'language'         ), SFH_NO_HASH );
		$parser->setFunctionHook( 'padleft',          array( __CLASS__, 'padleft'          ), SFH_NO_HASH );
		$parser->setFunctionHook( 'padright',         array( __CLASS__, 'padright'         ), SFH_NO_HASH );
		$parser->setFunctionHook( 'anchorencode',     array( __CLASS__, 'anchorencode'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'special',          array( __CLASS__, 'special'          ) );
		$parser->setFunctionHook( 'defaultsort',      array( __CLASS__, 'defaultsort'      ), SFH_NO_HASH );
		$parser->setFunctionHook( 'filepath',         array( __CLASS__, 'filepath'         ), SFH_NO_HASH );
		$parser->setFunctionHook( 'pagesincategory',  array( __CLASS__, 'pagesincategory'  ), SFH_NO_HASH );
		$parser->setFunctionHook( 'pagesize',         array( __CLASS__, 'pagesize'         ), SFH_NO_HASH );
		$parser->setFunctionHook( 'protectionlevel',  array( __CLASS__, 'protectionlevel'  ), SFH_NO_HASH );
		$parser->setFunctionHook( 'namespace',        array( __CLASS__, 'mwnamespace'      ), SFH_NO_HASH );
		$parser->setFunctionHook( 'namespacee',       array( __CLASS__, 'namespacee'       ), SFH_NO_HASH );
		$parser->setFunctionHook( 'talkspace',        array( __CLASS__, 'talkspace'        ), SFH_NO_HASH );
		$parser->setFunctionHook( 'talkspacee',       array( __CLASS__, 'talkspacee'       ), SFH_NO_HASH );
		$parser->setFunctionHook( 'subjectspace',     array( __CLASS__, 'subjectspace'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'subjectspacee',    array( __CLASS__, 'subjectspacee'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'pagename',         array( __CLASS__, 'pagename'         ), SFH_NO_HASH );
		$parser->setFunctionHook( 'pagenamee',        array( __CLASS__, 'pagenamee'        ), SFH_NO_HASH );
		$parser->setFunctionHook( 'fullpagename',     array( __CLASS__, 'fullpagename'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'fullpagenamee',    array( __CLASS__, 'fullpagenamee'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'basepagename',     array( __CLASS__, 'basepagename'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'basepagenamee',    array( __CLASS__, 'basepagenamee'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'subpagename',      array( __CLASS__, 'subpagename'      ), SFH_NO_HASH );
		$parser->setFunctionHook( 'subpagenamee',     array( __CLASS__, 'subpagenamee'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'talkpagename',     array( __CLASS__, 'talkpagename'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'talkpagenamee',    array( __CLASS__, 'talkpagenamee'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'subjectpagename',  array( __CLASS__, 'subjectpagename'  ), SFH_NO_HASH );
		$parser->setFunctionHook( 'subjectpagenamee', array( __CLASS__, 'subjectpagenamee' ), SFH_NO_HASH );
		$parser->setFunctionHook( 'revisionid',       array( __CLASS__, 'revisionid'       ), SFH_NO_HASH );
		$parser->setFunctionHook( 'revisiontimestamp',array( __CLASS__, 'revisiontimestamp'), SFH_NO_HASH );
		$parser->setFunctionHook( 'revisionday',      array( __CLASS__, 'revisionday'      ), SFH_NO_HASH );
		$parser->setFunctionHook( 'revisionday2',     array( __CLASS__, 'revisionday2'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'revisionmonth',    array( __CLASS__, 'revisionmonth'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'revisionyear',     array( __CLASS__, 'revisionyear'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'revisionuser',     array( __CLASS__, 'revisionuser'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'tag',              array( __CLASS__, 'tagObj'           ), SFH_OBJECT_ARGS );
		$parser->setFunctionHook( 'formatdate',		  array( __CLASS__, 'formatDate'	   ) );
		$parser->setFunctionHook( 'groupconvert', 	  array( __CLASS__, 'groupconvert'	   ), SFH_NO_HASH );

		if ( $wgAllowDisplayTitle ) {
			$parser->setFunctionHook( 'displaytitle', array( __CLASS__, 'displaytitle' ), SFH_NO_HASH );
		}
		if ( $wgAllowSlowParserFunctions ) {
			$parser->setFunctionHook( 'pagesinnamespace', array( __CLASS__, 'pagesinnamespace' ), SFH_NO_HASH );
		}
	}

	static function intFunction( $parser, $part1 = '' /*, ... */ ) {
		if ( strval( $part1 ) !== '' ) {
			$args = array_slice( func_get_args(), 2 );
			$message = wfMsgGetKey( $part1, true, false, false );
			$message = wfMsgReplaceArgs( $message, $args );
			$message = $parser->replaceVariables( $message ); // like $wgMessageCache->transform()
			return $message;
		} else {
			return array( 'found' => false );
		}
	}
	
	static function formatDate( $parser, $date, $defaultPref = null ) {
		$df = DateFormatter::getInstance();
		
		$date = trim($date);
		
		$pref = $parser->mOptions->getDateFormat();
		
		// Specify a different default date format other than the the normal default
		// iff the user has 'default' for their setting		
		if ($pref == 'default' && $defaultPref)
			$pref = $defaultPref;
		
		$date = $df->reformat( $pref, $date, array('match-whole') );
		return $date;
	}
	
	static function ns( $parser, $part1 = '' ) {
		global $wgContLang;
		if ( intval( $part1 ) || $part1 == "0" ) {
			$index = intval( $part1 );
		} else {
			$index = $wgContLang->getNsIndex( str_replace( ' ', '_', $part1 ) );
		}
		if ( $index !== false ) {
			return $wgContLang->getFormattedNsText( $index );
		} else {
			return array( 'found' => false );
		}
	}

	static function urlencode( $parser, $s = '' ) {
		return urlencode( $s );
	}

	static function lcfirst( $parser, $s = '' ) {
		global $wgContLang;
		return $wgContLang->lcfirst( $s );
	}

	static function ucfirst( $parser, $s = '' ) {
		global $wgContLang;
		return $wgContLang->ucfirst( $s );
	}

	static function lc( $parser, $s = '' ) {
		global $wgContLang;
		if ( is_callable( array( $parser, 'markerSkipCallback' ) ) ) {
			return $parser->markerSkipCallback( $s, array( $wgContLang, 'lc' ) );
		} else {
			return $wgContLang->lc( $s );
		}
	}

	static function uc( $parser, $s = '' ) {
		global $wgContLang;
		if ( is_callable( array( $parser, 'markerSkipCallback' ) ) ) {
			return $parser->markerSkipCallback( $s, array( $wgContLang, 'uc' ) );
		} else {
			return $wgContLang->uc( $s );
		}
	}

	static function localurl( $parser, $s = '', $arg = null ) { return self::urlFunction( 'getLocalURL', $s, $arg ); }
	static function localurle( $parser, $s = '', $arg = null ) { return self::urlFunction( 'escapeLocalURL', $s, $arg ); }
	static function fullurl( $parser, $s = '', $arg = null ) { return self::urlFunction( 'getFullURL', $s, $arg ); }
	static function fullurle( $parser, $s = '', $arg = null ) { return self::urlFunction( 'escapeFullURL', $s, $arg ); }

	static function urlFunction( $func, $s = '', $arg = null ) {
		$title = Title::newFromText( $s );
		# Due to order of execution of a lot of bits, the values might be encoded
		# before arriving here; if that's true, then the title can't be created
		# and the variable will fail. If we can't get a decent title from the first
		# attempt, url-decode and try for a second.
		if( is_null( $title ) )
			$title = Title::newFromUrl( urldecode( $s ) );
		if( !is_null( $title ) ) {
			# Convert NS_MEDIA -> NS_FILE
			if( $title->getNamespace() == NS_MEDIA ) {
				$title = Title::makeTitle( NS_FILE, $title->getDBKey() );
			}
			if( !is_null( $arg ) ) {
				$text = $title->$func( $arg );
			} else {
				$text = $title->$func();
			}
			return $text;
		} else {
			return array( 'found' => false );
		}
	}

	static function formatNum( $parser, $num = '', $raw = null) {
		if ( self::israw( $raw ) ) {
			return $parser->getFunctionLang()->parseFormattedNumber( $num );
		} else {
			return $parser->getFunctionLang()->formatNum( $num );
		}
	}

	static function grammar( $parser, $case = '', $word = '' ) {
		return $parser->getFunctionLang()->convertGrammar( $word, $case );
	}

	static function gender( $parser, $user ) {
		$forms = array_slice( func_get_args(), 2);

		// default
		$gender = User::getDefaultOption( 'gender' );
		
		// allow prefix.
		$title = Title::newFromText( $user );
		
		if (is_object( $title ) && $title->getNamespace() == NS_USER)
			$user = $title->getText();

		// check parameter, or use $wgUser if in interface message
		$user = User::newFromName( $user );
		if ( $user ) {
			$gender = $user->getOption( 'gender' );
		} elseif ( $parser->mOptions->getInterfaceMessage() ) {
			global $wgUser;
			$gender = $wgUser->getOption( 'gender' );
		}
		return $parser->getFunctionLang()->gender( $gender, $forms );
	}
	static function plural( $parser, $text = '') {
		$forms = array_slice( func_get_args(), 2);
		$text = $parser->getFunctionLang()->parseFormattedNumber( $text );
		return $parser->getFunctionLang()->convertPlural( $text, $forms );
	}

	/**
	 * Override the title of the page when viewed, provided we've been given a
	 * title which will normalise to the canonical title
	 *
	 * @param Parser $parser Parent parser
	 * @param string $text Desired title text
	 * @return string
	 */
	static function displaytitle( $parser, $text = '' ) {
		global $wgRestrictDisplayTitle;
		
		#list of disallowed tags for DISPLAYTITLE
		#these will be escaped even though they are allowed in normal wiki text
		$bad = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'blockquote', 'ol', 'ul', 'li',
			'table', 'tr', 'th', 'td', 'dl', 'dd', 'caption', 'p', 'ruby', 'rb', 'rt', 'rp' );
		
		#only requested titles that normalize to the actual title are allowed through
		#mimic the escaping process that occurs in OutputPage::setPageTitle
		$text = Sanitizer::normalizeCharReferences( Sanitizer::removeHTMLtags( $text, null, array(), array(), $bad ) );
		$title = Title::newFromText( Sanitizer::stripAllTags( $text ) );

		if( !$wgRestrictDisplayTitle ) {
			$parser->mOutput->setDisplayTitle( $text );
		} else {
			if ( $title instanceof Title && $title->getFragment() == '' && $title->equals( $parser->mTitle ) ) {
				$parser->mOutput->setDisplayTitle( $text );
			}
		}

		return '';
	}

	static function isRaw( $param ) {
		static $mwRaw;
		if ( !$mwRaw ) {
			$mwRaw =& MagicWord::get( 'rawsuffix' );
		}
		if ( is_null( $param ) ) {
			return false;
		} else {
			return $mwRaw->match( $param );
		}
	}

	static function formatRaw( $num, $raw ) {
		if( self::isRaw( $raw ) ) {
			return $num;
		} else {
			global $wgContLang;
			return $wgContLang->formatNum( $num );
		}
	}
	static function numberofpages( $parser, $raw = null ) {
		return self::formatRaw( SiteStats::pages(), $raw );
	}
	static function numberofusers( $parser, $raw = null ) {
		return self::formatRaw( SiteStats::users(), $raw );
	}
	static function numberofactiveusers( $parser, $raw = null ) {
		return self::formatRaw( SiteStats::activeUsers(), $raw );
	}
	static function numberofarticles( $parser, $raw = null ) {
		return self::formatRaw( SiteStats::articles(), $raw );
	}
	static function numberoffiles( $parser, $raw = null ) {
		return self::formatRaw( SiteStats::images(), $raw );
	}
	static function numberofadmins( $parser, $raw = null ) {
		return self::formatRaw( SiteStats::numberingroup('sysop'), $raw );
	}
	static function numberofedits( $parser, $raw = null ) {
		return self::formatRaw( SiteStats::edits(), $raw );
	}
	static function numberofviews( $parser, $raw = null ) {
		return self::formatRaw( SiteStats::views(), $raw );
	}
	static function pagesinnamespace( $parser, $namespace = 0, $raw = null ) {
		return self::formatRaw( SiteStats::pagesInNs( intval( $namespace ) ), $raw );
	}
	static function numberingroup( $parser, $name = '', $raw = null) {
		return self::formatRaw( SiteStats::numberingroup( strtolower( $name ) ), $raw );
	} 

	
	/**
	 * Given a title, return the namespace name that would be given by the
	 * corresponding magic word
	 * Note: function name changed to "mwnamespace" rather than "namespace"
	 * to not break PHP 5.3
	 */
	static function mwnamespace( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) )
			return '';
		return str_replace( '_', ' ', $t->getNsText() );
	}
	static function namespacee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) )
			return '';
		return wfUrlencode( $t->getNsText() );
	}
	static function talkspace( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) || !$t->canTalk() )
			return '';
		return str_replace( '_', ' ', $t->getTalkNsText() );
	}
	static function talkspacee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) || !$t->canTalk() )
			return '';
		return wfUrlencode( $t->getTalkNsText() );
	}
	static function subjectspace( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) )
			return '';
		return str_replace( '_', ' ', $t->getSubjectNsText() );
	}
	static function subjectspacee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) )
			return '';
		return wfUrlencode( $t->getSubjectNsText() );
	}
	/*
	 * Functions to get and normalize pagenames, corresponding to the magic words
	 * of the same names
	*/
	static function pagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) )
			return '';
		return wfEscapeWikiText( $t->getText() );
	}
	static function pagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) )
			return '';
		return $t->getPartialURL();
	}
	static function fullpagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) || !$t->canTalk() )
			return '';
		return wfEscapeWikiText( $t->getPrefixedText() );
	}
	static function fullpagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) || !$t->canTalk() )
			return '';
		return $t->getPrefixedURL();
	}
	static function subpagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) )
			return '';
		return $t->getSubpageText();
	}
	static function subpagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) )
			return '';
		return $t->getSubpageUrlForm();
	}
	static function basepagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) )
			return '';
		return $t->getBaseText();
	}
	static function basepagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) )
			return '';
		return wfUrlEncode( str_replace( ' ', '_', $t->getBaseText() ) );
	}	
	static function talkpagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) || !$t->canTalk() )
			return '';
		return wfEscapeWikiText( $t->getTalkPage()->getPrefixedText() );
	}
	static function talkpagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) || !$t->canTalk() )
			return '';
		return $t->getTalkPage()->getPrefixedUrl();
	}
	static function subjectpagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) )
			return '';
		return wfEscapeWikiText( $t->getSubjectPage()->getPrefixedText() );
	}
	static function subjectpagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null($t) )
			return '';
		return $t->getSubjectPage()->getPrefixedUrl();
	}
	/*
	 * Functions to get revision informations, corresponding to the magic words
	 * of the same names
	 */
	static function revisionid( $parser, $title = null ) {
		static $cache = array ();
		$t = Title::newFromText( $title );
		if ( is_null( $t ) )
			return '';
		if ( $t->equals( $parser->getTitle() ) ) {
			// Let the edit saving system know we should parse the page
			// *after* a revision ID has been assigned.
			$parser->mOutput->setFlag( 'vary-revision' );
			wfDebug( __METHOD__ . ": {{REVISIONID}} used, setting vary-revision...\n" );
			return $parser->getRevisionId();
		}
		if ( isset( $cache[$t->getPrefixedText()] ) )
			return $cache[$t->getPrefixedText()];
		elseif ( $parser->incrementExpensiveFunctionCount() ) {
			$a = new Article( $t );
			return $cache[$t->getPrefixedText()] = $a->getRevIdFetched();
		}
		return '';
	}
	static function revisiontimestamp( $parser, $title = null ) {
		static $cache = array ();
		$t = Title::newFromText( $title );
		if ( is_null( $t ) )
			return '';
		if ( $t->equals( $parser->getTitle() ) ) {
			// Let the edit saving system know we should parse the page
			// *after* a revision ID has been assigned. This is for null edits.
			$parser->mOutput->setFlag( 'vary-revision' );
			wfDebug( __METHOD__ . ": {{REVISIONTIMESTAMP}} or related parser function used, setting vary-revision...\n" );
			return $parser->getRevisionTimestamp();
		}
		if ( isset( $cache[$t->getPrefixedText()] ) )
			return $cache[$t->getPrefixedText()];
		elseif ( $parser->incrementExpensiveFunctionCount() ) {
			$a = new Article( $t );
			return $cache[$t->getPrefixedText()] = $a->getTimestamp();
		}
		return '';
	}
	static function revisionday( $parser, $title = null ) {
		$timestamp = self::revisiontimestamp( $parser, $title );
		if ( $timestamp == '' ) return '';
		return intval( substr( $timestamp, 6, 2 ) );
	}
	static function revisionday2( $parser, $title = null ) {
		$timestamp = self::revisiontimestamp( $parser, $title );
		if ( $timestamp == '' ) return '';
		return substr( $timestamp, 6, 2 );
	}
	static function revisionmonth( $parser, $title = null ) {
		$timestamp = self::revisiontimestamp( $parser, $title );
		if ( $timestamp == '' ) return '';
		return intval( substr( $timestamp, 4, 2 ) );
	}
	static function revisionyear( $parser, $title = null ) {
		$timestamp = self::revisiontimestamp( $parser, $title );
		if ( $timestamp == '' ) return '';
		return substr( $timestamp, 0, 4 );
	}
	static function revisionuser( $parser, $title = null ) {
		static $cache = array();
		$t = Title::newFromText( $title );
		if ( is_null( $t ) )
			return '';
		if ( $t->equals( $parser->getTitle() ) ) {
			// Let the edit saving system know we should parse the page
			// *after* a revision ID has been assigned. This is for null edits.
			$parser->mOutput->setFlag( 'vary-revision' );
			wfDebug( __METHOD__ . ": {{REVISIONUSER}} used, setting vary-revision...\n" );
			return $parser->getRevisionUser();
		}
		if ( isset( $cache[$t->getPrefixedText()] ) )
			return $cache[$t->getPrefixedText()];
		elseif ( $parser->incrementExpensiveFunctionCount() ) {
			$a = new Article( $t );
			return $cache[$t->getPrefixedText()] = $a->getUserText();
		}
		return '';
	}
	
	/**
	 * Return the number of pages in the given category, or 0 if it's nonexis-
	 * tent.  This is an expensive parser function and can't be called too many
	 * times per page.
	 */
	static function pagesincategory( $parser, $name = '', $raw = null ) {
		static $cache = array();
		$category = Category::newFromName( $name );

		if( !is_object( $category ) ) {
			$cache[$name] = 0;
			return self::formatRaw( 0, $raw );
		}

		# Normalize name for cache
		$name = $category->getName();

		$count = 0;
		if( isset( $cache[$name] ) ) {
			$count = $cache[$name];
		} elseif( $parser->incrementExpensiveFunctionCount() ) {
			$count = $cache[$name] = (int)$category->getPageCount();
		}
		return self::formatRaw( $count, $raw );
	}

	/**
	 * Return the size of the given page, or 0 if it's nonexistent.  This is an
	 * expensive parser function and can't be called too many times per page.
	 *
	 * @FIXME This doesn't work correctly on preview for getting the size of
	 *   the current page.
	 * @FIXME Title::getLength() documentation claims that it adds things to
	 *   the link cache, so the local cache here should be unnecessary, but in
	 *   fact calling getLength() repeatedly for the same $page does seem to
	 *   run one query for each call?
	 */
	static function pagesize( $parser, $page = '', $raw = null ) {
		static $cache = array();
		$title = Title::newFromText($page);

		if( !is_object( $title ) ) {
			$cache[$page] = 0;
			return self::formatRaw( 0, $raw );
		}

		# Normalize name for cache
		$page = $title->getPrefixedText();

		$length = 0;
		if( isset( $cache[$page] ) ) {
			$length = $cache[$page];
		} elseif( $parser->incrementExpensiveFunctionCount() ) {
			$rev = Revision::newFromTitle($title);
			$id = $rev ? $rev->getPage() : 0;
			$length = $cache[$page] = $rev ? $rev->getSize() : 0;
	
			// Register dependency in templatelinks
			$parser->mOutput->addTemplate( $title, $id, $rev ? $rev->getId() : 0 );
		}	
		return self::formatRaw( $length, $raw );
	}
	
	/**
	* Returns the requested protection level for the current page
	*/
	static function protectionlevel( $parser, $type = '' ) {
		$restrictions = $parser->mTitle->getRestrictions( strtolower( $type ) );
		# Title::getRestrictions returns an array, its possible it may have
		# multiple values in the future
		return implode( $restrictions, ',' );
	}

	static function language( $parser, $arg = '' ) {
		global $wgContLang;
		$lang = $wgContLang->getLanguageName( strtolower( $arg ) );
		return $lang != '' ? $lang : $arg;
	}

	/**
	 * Unicode-safe str_pad with the restriction that $length is forced to be <= 500
 	 */
	static function pad( $string, $length, $padding = '0', $direction = STR_PAD_RIGHT ) {
		$lengthOfPadding = mb_strlen( $padding );		
		if ( $lengthOfPadding == 0 ) return $string;
		
		# The remaining length to add counts down to 0 as padding is added
		$length = min( $length, 500 ) - mb_strlen( $string );
		# $finalPadding is just $padding repeated enough times so that 
		# mb_strlen( $string ) + mb_strlen( $finalPadding ) == $length
		$finalPadding = '';
		while ( $length > 0 ) {
			# If $length < $lengthofPadding, truncate $padding so we get the
			# exact length desired.
			$finalPadding .= mb_substr( $padding, 0, $length );
			$length -= $lengthOfPadding;
		}
		
		if ( $direction == STR_PAD_LEFT ) {
			return $finalPadding . $string;
		} else {
			return $string . $finalPadding;
		}
	}

	static function padleft( $parser, $string = '', $length = 0, $padding = '0' ) {
		return self::pad( $string, $length, $padding, STR_PAD_LEFT );
	}

	static function padright( $parser, $string = '', $length = 0, $padding = '0' ) {
		return self::pad( $string, $length, $padding );
	}

	static function anchorencode( $parser, $text ) {
		$a = urlencode( $text );
		$a = strtr( $a, array( '%' => '.', '+' => '_' ) );
		# leave colons alone, however
		$a = str_replace( '.3A', ':', $a );
		return $a;
	}

	static function special( $parser, $text ) {
		$title = SpecialPage::getTitleForAlias( $text );
		if ( $title ) {
			return $title->getPrefixedText();
		} else {
			return wfMsgForContent( 'nosuchspecialpage' );
		}
	}

	public static function defaultsort( $parser, $text ) {
		$text = trim( $text );
		if( strlen( $text ) == 0 )
			return '';
		$old = $parser->getCustomDefaultSort();
		$parser->setDefaultSort( $text );
		if( $old === false || $old == $text )
			return '';
		else
			return( '<span class="error">' .
				wfMsg( 'duplicate-defaultsort',
						 htmlspecialchars( $old ),
						 htmlspecialchars( $text ) ) .
				'</span>' );
	}

	public static function filepath( $parser, $name='', $option='' ) {
		$file = wfFindFile( $name );
		if( $file ) {
			$url = $file->getFullUrl();
			if( $option == 'nowiki' ) {
				return array( $url, 'nowiki' => true );
			}
			return $url;
		} else {
			return '';
		}
	}

	/**
	 * Parser function to extension tag adaptor
	 */
	public static function tagObj( $parser, $frame, $args ) {
		$xpath = false;
		if ( !count( $args ) ) {
			return '';
		}
		$tagName = strtolower( trim( $frame->expand( array_shift( $args ) ) ) );

		if ( count( $args ) ) {
			$inner = $frame->expand( array_shift( $args ) );
		} else {
			$inner = null;
		}

		$stripList = $parser->getStripList();
		if ( !in_array( $tagName, $stripList ) ) {
			return '<span class="error">' .
				wfMsg( 'unknown_extension_tag', $tagName ) .
				'</span>';
		}

		$attributes = array();
		foreach ( $args as $arg ) {
			$bits = $arg->splitArg();
			if ( strval( $bits['index'] ) === '' ) {
				$name = trim( $frame->expand( $bits['name'], PPFrame::STRIP_COMMENTS ) );
				$value = trim( $frame->expand( $bits['value'] ) );
				if ( preg_match( '/^(?:["\'](.+)["\']|""|\'\')$/s', $value, $m ) ) {
					$value = isset( $m[1] ) ? $m[1] : '';
				}
				$attributes[$name] = $value;
			}
		}

		$params = array(
			'name' => $tagName,
			'inner' => $inner,
			'attributes' => $attributes,
			'close' => "</$tagName>",
		);
		return $parser->extensionSubstitution( $params, $frame );
	}
	
	/**
	 * magic word call for a group convert from LanguageConverter.
	 */
	public static function groupconvert( $parser, $group ) {
		global $wgContLang;
		return $wgContLang->groupConvert( $group );
	}
}

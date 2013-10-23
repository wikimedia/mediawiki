<?php
/**
 * Parser functions provided by MediaWiki core
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Parser
 */

/**
 * Various core parser functions, registered in Parser::firstCallInit()
 * @ingroup Parser
 */
class CoreParserFunctions {
	/**
	 * @param $parser Parser
	 * @return void
	 */
	static function register( $parser ) {
		global $wgAllowDisplayTitle, $wgAllowSlowParserFunctions;

		# Syntax for arguments (see self::setFunctionHook):
		#  "name for lookup in localized magic words array",
		#  function callback,
		#  optional SFH_NO_HASH to omit the hash from calls (e.g. {{int:...}}
		#    instead of {{#int:...}})

		$parser->setFunctionHook( 'int',              array( __CLASS__, 'intFunction'      ), SFH_NO_HASH );
		$parser->setFunctionHook( 'ns',               array( __CLASS__, 'ns'               ), SFH_NO_HASH );
		$parser->setFunctionHook( 'nse',              array( __CLASS__, 'nse'              ), SFH_NO_HASH );
		$parser->setFunctionHook( 'urlencode',        array( __CLASS__, 'urlencode'        ), SFH_NO_HASH );
		$parser->setFunctionHook( 'lcfirst',          array( __CLASS__, 'lcfirst'          ), SFH_NO_HASH );
		$parser->setFunctionHook( 'ucfirst',          array( __CLASS__, 'ucfirst'          ), SFH_NO_HASH );
		$parser->setFunctionHook( 'lc',               array( __CLASS__, 'lc'               ), SFH_NO_HASH );
		$parser->setFunctionHook( 'uc',               array( __CLASS__, 'uc'               ), SFH_NO_HASH );
		$parser->setFunctionHook( 'localurl',         array( __CLASS__, 'localurl'         ), SFH_NO_HASH );
		$parser->setFunctionHook( 'localurle',        array( __CLASS__, 'localurle'        ), SFH_NO_HASH );
		$parser->setFunctionHook( 'fullurl',          array( __CLASS__, 'fullurl'          ), SFH_NO_HASH );
		$parser->setFunctionHook( 'fullurle',         array( __CLASS__, 'fullurle'         ), SFH_NO_HASH );
		$parser->setFunctionHook( 'canonicalurl',     array( __CLASS__, 'canonicalurl'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'canonicalurle',    array( __CLASS__, 'canonicalurle'    ), SFH_NO_HASH );
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
		$parser->setFunctionHook( 'speciale',         array( __CLASS__, 'speciale'         ) );
		$parser->setFunctionHook( 'defaultsort',      array( __CLASS__, 'defaultsort'      ), SFH_NO_HASH );
		$parser->setFunctionHook( 'filepath',         array( __CLASS__, 'filepath'         ), SFH_NO_HASH );
		$parser->setFunctionHook( 'pagesincategory',  array( __CLASS__, 'pagesincategory'  ), SFH_NO_HASH );
		$parser->setFunctionHook( 'pagesize',         array( __CLASS__, 'pagesize'         ), SFH_NO_HASH );
		$parser->setFunctionHook( 'protectionlevel',  array( __CLASS__, 'protectionlevel'  ), SFH_NO_HASH );
		$parser->setFunctionHook( 'namespace',        array( __CLASS__, 'mwnamespace'      ), SFH_NO_HASH );
		$parser->setFunctionHook( 'namespacee',       array( __CLASS__, 'namespacee'       ), SFH_NO_HASH );
		$parser->setFunctionHook( 'namespacenumber',  array( __CLASS__, 'namespacenumber'  ), SFH_NO_HASH );
		$parser->setFunctionHook( 'talkspace',        array( __CLASS__, 'talkspace'        ), SFH_NO_HASH );
		$parser->setFunctionHook( 'talkspacee',       array( __CLASS__, 'talkspacee'       ), SFH_NO_HASH );
		$parser->setFunctionHook( 'subjectspace',     array( __CLASS__, 'subjectspace'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'subjectspacee',    array( __CLASS__, 'subjectspacee'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'pagename',         array( __CLASS__, 'pagename'         ), SFH_NO_HASH );
		$parser->setFunctionHook( 'pagenamee',        array( __CLASS__, 'pagenamee'        ), SFH_NO_HASH );
		$parser->setFunctionHook( 'fullpagename',     array( __CLASS__, 'fullpagename'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'fullpagenamee',    array( __CLASS__, 'fullpagenamee'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'rootpagename',     array( __CLASS__, 'rootpagename'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'rootpagenamee',    array( __CLASS__, 'rootpagenamee'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'basepagename',     array( __CLASS__, 'basepagename'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'basepagenamee',    array( __CLASS__, 'basepagenamee'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'subpagename',      array( __CLASS__, 'subpagename'      ), SFH_NO_HASH );
		$parser->setFunctionHook( 'subpagenamee',     array( __CLASS__, 'subpagenamee'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'talkpagename',     array( __CLASS__, 'talkpagename'     ), SFH_NO_HASH );
		$parser->setFunctionHook( 'talkpagenamee',    array( __CLASS__, 'talkpagenamee'    ), SFH_NO_HASH );
		$parser->setFunctionHook( 'subjectpagename',  array( __CLASS__, 'subjectpagename'  ), SFH_NO_HASH );
		$parser->setFunctionHook( 'subjectpagenamee', array( __CLASS__, 'subjectpagenamee' ), SFH_NO_HASH );
		$parser->setFunctionHook( 'tag',              array( __CLASS__, 'tagObj'           ), SFH_OBJECT_ARGS );
		$parser->setFunctionHook( 'formatdate',       array( __CLASS__, 'formatDate'       ) );

		if ( $wgAllowDisplayTitle ) {
			$parser->setFunctionHook( 'displaytitle', array( __CLASS__, 'displaytitle' ), SFH_NO_HASH );
		}
		if ( $wgAllowSlowParserFunctions ) {
			$parser->setFunctionHook( 'pagesinnamespace', array( __CLASS__, 'pagesinnamespace' ), SFH_NO_HASH );
		}
	}

	/**
	 * @param $parser Parser
	 * @param string $part1
	 * @return array
	 */
	static function intFunction( $parser, $part1 = '' /*, ... */ ) {
		if ( strval( $part1 ) !== '' ) {
			$args = array_slice( func_get_args(), 2 );
			$message = wfMessage( $part1, $args )->inLanguage( $parser->getOptions()->getUserLangObj() )->plain();
			return array( $message, 'noparse' => false );
		} else {
			return array( 'found' => false );
		}
	}

	/**
	 * @param $parser Parser
	 * @param  $date
	 * @param null $defaultPref
	 * @return mixed|string
	 */
	static function formatDate( $parser, $date, $defaultPref = null ) {
		$lang = $parser->getFunctionLang();
		$df = DateFormatter::getInstance( $lang );

		$date = trim( $date );

		$pref = $parser->getOptions()->getDateFormat();

		// Specify a different default date format other than the the normal default
		// if the user has 'default' for their setting
		if ( $pref == 'default' && $defaultPref ) {
			$pref = $defaultPref;
		}

		$date = $df->reformat( $pref, $date, array( 'match-whole' ) );
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

	static function nse( $parser, $part1 = '' ) {
		$ret = self::ns( $parser, $part1 );
		if ( is_string( $ret ) ) {
			$ret = wfUrlencode( str_replace( ' ', '_', $ret ) );
		}
		return $ret;
	}

	/**
	 * urlencodes a string according to one of three patterns: (bug 22474)
	 *
	 * By default (for HTTP "query" strings), spaces are encoded as '+'.
	 * Or to encode a value for the HTTP "path", spaces are encoded as '%20'.
	 * For links to "wiki"s, or similar software, spaces are encoded as '_',
	 *
	 * @param $parser Parser object
	 * @param string $s The text to encode.
	 * @param string $arg (optional): The type of encoding.
	 * @return string
	 */
	static function urlencode( $parser, $s = '', $arg = null ) {
		static $magicWords = null;
		if ( is_null( $magicWords ) ) {
			$magicWords = new MagicWordArray( array( 'url_path', 'url_query', 'url_wiki' ) );
		}
		switch ( $magicWords->matchStartToEnd( $arg ) ) {

			// Encode as though it's a wiki page, '_' for ' '.
			case 'url_wiki':
				$func = 'wfUrlencode';
				$s = str_replace( ' ', '_', $s );
				break;

			// Encode for an HTTP Path, '%20' for ' '.
			case 'url_path':
				$func = 'rawurlencode';
				break;

			// Encode for HTTP query, '+' for ' '.
			case 'url_query':
			default:
				$func = 'urlencode';
		}
		return $parser->markerSkipCallback( $s, $func );
	}

	static function lcfirst( $parser, $s = '' ) {
		global $wgContLang;
		return $wgContLang->lcfirst( $s );
	}

	static function ucfirst( $parser, $s = '' ) {
		global $wgContLang;
		return $wgContLang->ucfirst( $s );
	}

	/**
	 * @param $parser Parser
	 * @param string $s
	 * @return
	 */
	static function lc( $parser, $s = '' ) {
		global $wgContLang;
		return $parser->markerSkipCallback( $s, array( $wgContLang, 'lc' ) );
	}

	/**
	 * @param $parser Parser
	 * @param string $s
	 * @return
	 */
	static function uc( $parser, $s = '' ) {
		global $wgContLang;
		return $parser->markerSkipCallback( $s, array( $wgContLang, 'uc' ) );
	}

	static function localurl( $parser, $s = '', $arg = null ) { return self::urlFunction( 'getLocalURL', $s, $arg ); }
	static function localurle( $parser, $s = '', $arg = null ) { return self::urlFunction( 'escapeLocalURL', $s, $arg ); }
	static function fullurl( $parser, $s = '', $arg = null ) { return self::urlFunction( 'getFullURL', $s, $arg ); }
	static function fullurle( $parser, $s = '', $arg = null ) { return self::urlFunction( 'escapeFullURL', $s, $arg ); }
	static function canonicalurl( $parser, $s = '', $arg = null ) { return self::urlFunction( 'getCanonicalURL', $s, $arg ); }
	static function canonicalurle( $parser, $s = '', $arg = null ) { return self::urlFunction( 'escapeCanonicalURL', $s, $arg ); }

	static function urlFunction( $func, $s = '', $arg = null ) {
		$title = Title::newFromText( $s );
		# Due to order of execution of a lot of bits, the values might be encoded
		# before arriving here; if that's true, then the title can't be created
		# and the variable will fail. If we can't get a decent title from the first
		# attempt, url-decode and try for a second.
		if ( is_null( $title ) ) {
			$title = Title::newFromURL( urldecode( $s ) );
		}
		if ( !is_null( $title ) ) {
			# Convert NS_MEDIA -> NS_FILE
			if ( $title->getNamespace() == NS_MEDIA ) {
				$title = Title::makeTitle( NS_FILE, $title->getDBkey() );
			}
			if ( !is_null( $arg ) ) {
				$text = $title->$func( $arg );
			} else {
				$text = $title->$func();
			}
			return $text;
		} else {
			return array( 'found' => false );
		}
	}

	/**
	 * @param $parser Parser
	 * @param string $num
	 * @param string $arg
	 * @return string
	 */
	static function formatnum( $parser, $num = '', $arg = null ) {
		if ( self::matchAgainstMagicword( 'rawsuffix', $arg ) ) {
			$func = array( $parser->getFunctionLang(), 'parseFormattedNumber' );
		} elseif ( self::matchAgainstMagicword( 'nocommafysuffix', $arg ) ) {
			$func = array( $parser->getFunctionLang(), 'formatNumNoSeparators' );
		} else {
			$func = array( $parser->getFunctionLang(), 'formatNum' );
		}
		return $parser->markerSkipCallback( $num, $func );
	}

	/**
	 * @param $parser Parser
	 * @param string $case
	 * @param string $word
	 * @return
	 */
	static function grammar( $parser, $case = '', $word = '' ) {
		$word = $parser->killMarkers( $word );
		return $parser->getFunctionLang()->convertGrammar( $word, $case );
	}

	/**
	 * @param $parser Parser
	 * @param $username string
	 * @return
	 */
	static function gender( $parser, $username ) {
		wfProfileIn( __METHOD__ );
		$forms = array_slice( func_get_args(), 2 );

		// Some shortcuts to avoid loading user data unnecessarily
		if ( count( $forms ) === 0 ) {
			wfProfileOut( __METHOD__ );
			return '';
		} elseif ( count( $forms ) === 1 ) {
			wfProfileOut( __METHOD__ );
			return $forms[0];
		}

		$username = trim( $username );

		// default
		$gender = User::getDefaultOption( 'gender' );

		// allow prefix.
		$title = Title::newFromText( $username );

		if ( $title && $title->getNamespace() == NS_USER ) {
			$username = $title->getText();
		}

		// check parameter, or use the ParserOptions if in interface message
		$user = User::newFromName( $username );
		if ( $user ) {
			$gender = GenderCache::singleton()->getGenderOf( $user, __METHOD__ );
		} elseif ( $username === '' && $parser->getOptions()->getInterfaceMessage() ) {
			$gender = GenderCache::singleton()->getGenderOf( $parser->getOptions()->getUser(), __METHOD__ );
		}
		$ret = $parser->getFunctionLang()->gender( $gender, $forms );
		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * @param $parser Parser
	 * @param string $text
	 * @return
	 */
	static function plural( $parser, $text = '' ) {
		$forms = array_slice( func_get_args(), 2 );
		$text = $parser->getFunctionLang()->parseFormattedNumber( $text );
		settype( $text, ctype_digit( $text ) ? 'int' : 'float' );
		return $parser->getFunctionLang()->convertPlural( $text, $forms );
	}

	/**
	 * Override the title of the page when viewed, provided we've been given a
	 * title which will normalise to the canonical title
	 *
	 * @param $parser Parser: parent parser
	 * @param string $text desired title text
	 * @return String
	 */
	static function displaytitle( $parser, $text = '' ) {
		global $wgRestrictDisplayTitle;

		// parse a limited subset of wiki markup (just the single quote items)
		$text = $parser->doQuotes( $text );

		// remove stripped text (e.g. the UNIQ-QINU stuff) that was generated by tag extensions/whatever
		$text = preg_replace( '/' . preg_quote( $parser->uniqPrefix(), '/' ) . '.*?'
			. preg_quote( Parser::MARKER_SUFFIX, '/' ) . '/', '', $text );

		// list of disallowed tags for DISPLAYTITLE
		// these will be escaped even though they are allowed in normal wiki text
		$bad = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'blockquote', 'ol', 'ul', 'li', 'hr',
			'table', 'tr', 'th', 'td', 'dl', 'dd', 'caption', 'p', 'ruby', 'rb', 'rt', 'rp', 'br' );

		// disallow some styles that could be used to bypass $wgRestrictDisplayTitle
		if ( $wgRestrictDisplayTitle ) {
			$htmlTagsCallback = function ( &$params ) {
				$decoded = Sanitizer::decodeTagAttributes( $params );

				if ( isset( $decoded['style'] ) ) {
					// this is called later anyway, but we need it right now for the regexes below to be safe
					// calling it twice doesn't hurt
					$decoded['style'] = Sanitizer::checkCss( $decoded['style'] );

					if ( preg_match( '/(display|user-select|visibility)\s*:/i', $decoded['style'] ) ) {
						$decoded['style'] = '/* attempt to bypass $wgRestrictDisplayTitle */';
					}
				}

				$params = Sanitizer::safeEncodeTagAttributes( $decoded );
			};
		} else {
			$htmlTagsCallback = null;
		}

		// only requested titles that normalize to the actual title are allowed through
		// if $wgRestrictDisplayTitle is true (it is by default)
		// mimic the escaping process that occurs in OutputPage::setPageTitle
		$text = Sanitizer::normalizeCharReferences( Sanitizer::removeHTMLtags( $text, $htmlTagsCallback, array(), array(), $bad ) );
		$title = Title::newFromText( Sanitizer::stripAllTags( $text ) );

		if ( !$wgRestrictDisplayTitle ) {
			$parser->mOutput->setDisplayTitle( $text );
		} elseif ( $title instanceof Title && $title->getFragment() == '' && $title->equals( $parser->mTitle ) ) {
			$parser->mOutput->setDisplayTitle( $text );
		}

		return '';
	}

	/**
	 * Matches the given value against the value of given magic word
	 *
	 * @param string $magicword magic word key
	 * @param mixed $value value to match
	 * @return boolean true on successful match
	 */
	static private function matchAgainstMagicword( $magicword, $value ) {
		if ( strval( $value ) === '' ) {
			return false;
		}
		$mwObject = MagicWord::get( $magicword );
		return $mwObject->match( $value );
	}

	static function formatRaw( $num, $raw ) {
		if ( self::matchAgainstMagicword( 'rawsuffix', $raw ) ) {
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
		return self::formatRaw( SiteStats::numberingroup( 'sysop' ), $raw );
	}
	static function numberofedits( $parser, $raw = null ) {
		return self::formatRaw( SiteStats::edits(), $raw );
	}
	static function numberofviews( $parser, $raw = null ) {
		global $wgDisableCounters;
		return !$wgDisableCounters ? self::formatRaw( SiteStats::views(), $raw ) : '';
	}
	static function pagesinnamespace( $parser, $namespace = 0, $raw = null ) {
		return self::formatRaw( SiteStats::pagesInNs( intval( $namespace ) ), $raw );
	}
	static function numberingroup( $parser, $name = '', $raw = null ) {
		return self::formatRaw( SiteStats::numberingroup( strtolower( $name ) ), $raw );
	}

	/**
	 * Given a title, return the namespace name that would be given by the
	 * corresponding magic word
	 * Note: function name changed to "mwnamespace" rather than "namespace"
	 * to not break PHP 5.3
	 * @return mixed|string
	 */
	static function mwnamespace( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) ) {
			return '';
		}
		return str_replace( '_', ' ', $t->getNsText() );
	}
	static function namespacee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) ) {
			return '';
		}
		return wfUrlencode( $t->getNsText() );
	}
	static function namespacenumber( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) ) {
			return '';
		}
		return $t->getNamespace();
	}
	static function talkspace( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) || !$t->canTalk() ) {
			return '';
		}
		return str_replace( '_', ' ', $t->getTalkNsText() );
	}
	static function talkspacee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) || !$t->canTalk() ) {
			return '';
		}
		return wfUrlencode( $t->getTalkNsText() );
	}
	static function subjectspace( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) ) {
			return '';
		}
		return str_replace( '_', ' ', $t->getSubjectNsText() );
	}
	static function subjectspacee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) ) {
			return '';
		}
		return wfUrlencode( $t->getSubjectNsText() );
	}

	/**
	 * Functions to get and normalize pagenames, corresponding to the magic words
	 * of the same names
	 * @return String
	 */
	static function pagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) ) {
			return '';
		}
		return wfEscapeWikiText( $t->getText() );
	}
	static function pagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) ) {
			return '';
		}
		return wfEscapeWikiText( $t->getPartialURL() );
	}
	static function fullpagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) || !$t->canTalk() ) {
			return '';
		}
		return wfEscapeWikiText( $t->getPrefixedText() );
	}
	static function fullpagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) || !$t->canTalk() ) {
			return '';
		}
		return wfEscapeWikiText( $t->getPrefixedURL() );
	}
	static function subpagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) ) {
			return '';
		}
		return wfEscapeWikiText( $t->getSubpageText() );
	}
	static function subpagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) ) {
			return '';
		}
		return wfEscapeWikiText( $t->getSubpageUrlForm() );
	}
	static function rootpagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) ) {
			return '';
		}
		return wfEscapeWikiText( $t->getRootText() );
	}
	static function rootpagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) ) {
			return '';
		}
		return wfEscapeWikiText( wfUrlEncode( str_replace( ' ', '_', $t->getRootText() ) ) );
	}
	static function basepagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) ) {
			return '';
		}
		return wfEscapeWikiText( $t->getBaseText() );
	}
	static function basepagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) ) {
			return '';
		}
		return wfEscapeWikiText( wfUrlEncode( str_replace( ' ', '_', $t->getBaseText() ) ) );
	}
	static function talkpagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) || !$t->canTalk() ) {
			return '';
		}
		return wfEscapeWikiText( $t->getTalkPage()->getPrefixedText() );
	}
	static function talkpagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) || !$t->canTalk() ) {
			return '';
		}
		return wfEscapeWikiText( $t->getTalkPage()->getPrefixedURL() );
	}
	static function subjectpagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) ) {
			return '';
		}
		return wfEscapeWikiText( $t->getSubjectPage()->getPrefixedText() );
	}
	static function subjectpagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( is_null( $t ) ) {
			return '';
		}
		return wfEscapeWikiText( $t->getSubjectPage()->getPrefixedURL() );
	}

	/**
	 * Return the number of pages, files or subcats in the given category,
	 * or 0 if it's nonexistent. This is an expensive parser function and
	 * can't be called too many times per page.
	 * @return string
	 */
	static function pagesincategory( $parser, $name = '', $arg1 = null, $arg2 = null ) {
		global $wgContLang;
		static $magicWords = null;
		if ( is_null( $magicWords ) ) {
			$magicWords = new MagicWordArray( array(
				'pagesincategory_all',
				'pagesincategory_pages',
				'pagesincategory_subcats',
				'pagesincategory_files'
			) );
		}
		static $cache = array();

		// split the given option to its variable
		if ( self::matchAgainstMagicword( 'rawsuffix', $arg1 ) ) {
			//{{pagesincategory:|raw[|type]}}
			$raw = $arg1;
			$type = $magicWords->matchStartToEnd( $arg2 );
		} else {
			//{{pagesincategory:[|type[|raw]]}}
			$type = $magicWords->matchStartToEnd( $arg1 );
			$raw = $arg2;
		}
		if ( !$type ) { //backward compatibility
			$type = 'pagesincategory_all';
		}

		$title = Title::makeTitleSafe( NS_CATEGORY, $name );
		if ( !$title ) { # invalid title
			return self::formatRaw( 0, $raw );
		}
		$wgContLang->findVariantLink( $name, $title, true );

		// Normalize name for cache
		$name = $title->getDBkey();

		if ( !isset( $cache[$name] ) ) {
			$category = Category::newFromTitle( $title );

			$allCount = $subcatCount = $fileCount = $pagesCount = 0;
			if ( $parser->incrementExpensiveFunctionCount() ) {
				// $allCount is the total number of cat members,
				// not the count of how many members are normal pages.
				$allCount = (int)$category->getPageCount();
				$subcatCount = (int)$category->getSubcatCount();
				$fileCount = (int)$category->getFileCount();
				$pagesCount = $allCount - $subcatCount - $fileCount;
			}
			$cache[$name]['pagesincategory_all'] = $allCount;
			$cache[$name]['pagesincategory_pages'] = $pagesCount;
			$cache[$name]['pagesincategory_subcats'] = $subcatCount;
			$cache[$name]['pagesincategory_files'] = $fileCount;
		}

		$count = $cache[$name][$type];
		return self::formatRaw( $count, $raw );
	}

	/**
	 * Return the size of the given page, or 0 if it's nonexistent.  This is an
	 * expensive parser function and can't be called too many times per page.
	 *
	 * @todo FIXME: Title::getLength() documentation claims that it adds things
	 *   to the link cache, so the local cache here should be unnecessary, but
	 *   in fact calling getLength() repeatedly for the same $page does seem to
	 *   run one query for each call?
	 * @todo Document parameters
	 *
	 * @param $parser Parser
	 * @param $page String Name of page to check (Default: empty string)
	 * @param $raw String Should number be human readable with commas or just number
	 * @return string
	 */
	static function pagesize( $parser, $page = '', $raw = null ) {
		static $cache = array();
		$title = Title::newFromText( $page );

		if ( !is_object( $title ) ) {
			$cache[$page] = 0;
			return self::formatRaw( 0, $raw );
		}

		# Normalize name for cache
		$page = $title->getPrefixedText();

		$length = 0;
		if ( isset( $cache[$page] ) ) {
			$length = $cache[$page];
		} elseif ( $parser->incrementExpensiveFunctionCount() ) {
			$rev = Revision::newFromTitle( $title, false, Revision::READ_NORMAL );
			$pageID = $rev ? $rev->getPage() : 0;
			$revID = $rev ? $rev->getId() : 0;
			$length = $cache[$page] = $rev ? $rev->getSize() : 0;

			// Register dependency in templatelinks
			$parser->mOutput->addTemplate( $title, $pageID, $revID );
		}
		return self::formatRaw( $length, $raw );
	}

	/**
	 * Returns the requested protection level for the current page
	 *
	 * @param Parser $parser
	 * @param string $type
	 * @param string $title
	 *
	 * @return string
	 */
	static function protectionlevel( $parser, $type = '', $title = '' ) {
		$titleObject = Title::newFromText( $title );
		if ( !( $titleObject instanceof Title ) ) {
			$titleObject = $parser->mTitle;
		}
		$restrictions = $titleObject->getRestrictions( strtolower( $type ) );
		# Title::getRestrictions returns an array, its possible it may have
		# multiple values in the future
		return implode( $restrictions, ',' );
	}

	/**
	 * Gives language names.
	 * @param $parser Parser
	 * @param string $code  Language code (of which to get name)
	 * @param string $inLanguage  Language code (in which to get name)
	 * @return String
	 */
	static function language( $parser, $code = '', $inLanguage = '' ) {
		$code = strtolower( $code );
		$inLanguage = strtolower( $inLanguage );
		$lang = Language::fetchLanguageName( $code, $inLanguage );
		return $lang !== '' ? $lang : wfBCP47( $code );
	}

	/**
	 * Unicode-safe str_pad with the restriction that $length is forced to be <= 500
	 * @return string
	 */
	static function pad( $parser, $string, $length, $padding = '0', $direction = STR_PAD_RIGHT ) {
		$padding = $parser->killMarkers( $padding );
		$lengthOfPadding = mb_strlen( $padding );
		if ( $lengthOfPadding == 0 ) {
			return $string;
		}

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
		return self::pad( $parser, $string, $length, $padding, STR_PAD_LEFT );
	}

	static function padright( $parser, $string = '', $length = 0, $padding = '0' ) {
		return self::pad( $parser, $string, $length, $padding );
	}

	/**
	 * @param $parser Parser
	 * @param  $text
	 * @return string
	 */
	static function anchorencode( $parser, $text ) {
		$text = $parser->killMarkers( $text );
		return (string)substr( $parser->guessSectionNameFromWikiText( $text ), 1 );
	}

	static function special( $parser, $text ) {
		list( $page, $subpage ) = SpecialPageFactory::resolveAlias( $text );
		if ( $page ) {
			$title = SpecialPage::getTitleFor( $page, $subpage );
			return $title->getPrefixedText();
		} else {
			// unknown special page, just use the given text as its title, if at all possible
			$title = Title::makeTitleSafe( NS_SPECIAL, $text );
			return $title ? $title->getPrefixedText() : self::special( $parser, 'Badtitle' );
		}
	}

	static function speciale( $parser, $text ) {
		return wfUrlencode( str_replace( ' ', '_', self::special( $parser, $text ) ) );
	}

	/**
	 * @param $parser Parser
	 * @param string $text The sortkey to use
	 * @param string $uarg Either "noreplace" or "noerror" (in en)
	 *   both suppress errors, and noreplace does nothing if
	 *   a default sortkey already exists.
	 * @return string
	 */
	public static function defaultsort( $parser, $text, $uarg = '' ) {
		static $magicWords = null;
		if ( is_null( $magicWords ) ) {
			$magicWords = new MagicWordArray( array( 'defaultsort_noerror', 'defaultsort_noreplace' ) );
		}
		$arg = $magicWords->matchStartToEnd( $uarg );

		$text = trim( $text );
		if ( strlen( $text ) == 0 ) {
			return '';
		}
		$old = $parser->getCustomDefaultSort();
		if ( $old === false || $arg !== 'defaultsort_noreplace' ) {
			$parser->setDefaultSort( $text );
		}

		if ( $old === false || $old == $text || $arg ) {
			return '';
		} else {
			$converter = $parser->getConverterLanguage()->getConverter();
			return '<span class="error">' .
				wfMessage( 'duplicate-defaultsort',
					// Message should be parsed, but these params should only be escaped.
					$converter->markNoConversion( wfEscapeWikiText( $old ) ),
					$converter->markNoConversion( wfEscapeWikiText( $text ) )
				)->inContentLanguage()->text() .
				'</span>';
		}
	}

	// Usage {{filepath|300}}, {{filepath|nowiki}}, {{filepath|nowiki|300}} or {{filepath|300|nowiki}}
	// or {{filepath|300px}}, {{filepath|200x300px}}, {{filepath|nowiki|200x300px}}, {{filepath|200x300px|nowiki}}
	public static function filepath( $parser, $name = '', $argA = '', $argB = '' ) {
		$file = wfFindFile( $name );

		if ( $argA == 'nowiki' ) {
			// {{filepath: | option [| size] }}
			$isNowiki = true;
			$parsedWidthParam = $parser->parseWidthParam( $argB );
		} else {
			// {{filepath: [| size [|option]] }}
			$parsedWidthParam = $parser->parseWidthParam( $argA );
			$isNowiki = ( $argB == 'nowiki' );
		}

		if ( $file ) {
			$url = $file->getFullUrl();

			// If a size is requested...
			if ( count( $parsedWidthParam ) ) {
				$mto = $file->transform( $parsedWidthParam );
				// ... and we can
				if ( $mto && !$mto->isError() ) {
					// ... change the URL to point to a thumbnail.
					$url = wfExpandUrl( $mto->getUrl(), PROTO_RELATIVE );
				}
			}
			if ( $isNowiki ) {
				return array( $url, 'nowiki' => true );
			}
			return $url;
		} else {
			return '';
		}
	}

	/**
	 * Parser function to extension tag adaptor
	 * @return string
	 */
	public static function tagObj( $parser, $frame, $args ) {
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
				wfMessage( 'unknown_extension_tag', $tagName )->inContentLanguage()->text() .
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
}

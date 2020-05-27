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

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionRecord;

/**
 * Various core parser functions, registered in Parser::firstCallInit()
 * @ingroup Parser
 */
class CoreParserFunctions {
	/**
	 * @param Parser $parser
	 * @return void
	 */
	public static function register( $parser ) {
		global $wgAllowDisplayTitle, $wgAllowSlowParserFunctions;

		# Syntax for arguments (see Parser::setFunctionHook):
		#  "name for lookup in localized magic words array",
		#  function callback,
		#  optional Parser::SFH_NO_HASH to omit the hash from calls (e.g. {{int:...}}
		#    instead of {{#int:...}})
		$noHashFunctions = [
			'ns', 'nse', 'urlencode', 'lcfirst', 'ucfirst', 'lc', 'uc',
			'localurl', 'localurle', 'fullurl', 'fullurle', 'canonicalurl',
			'canonicalurle', 'formatnum', 'grammar', 'gender', 'plural', 'bidi',
			'numberofpages', 'numberofusers', 'numberofactiveusers',
			'numberofarticles', 'numberoffiles', 'numberofadmins',
			'numberingroup', 'numberofedits', 'language',
			'padleft', 'padright', 'anchorencode', 'defaultsort', 'filepath',
			'pagesincategory', 'pagesize', 'protectionlevel', 'protectionexpiry',
			'namespacee', 'namespacenumber', 'talkspace', 'talkspacee',
			'subjectspace', 'subjectspacee', 'pagename', 'pagenamee',
			'fullpagename', 'fullpagenamee', 'rootpagename', 'rootpagenamee',
			'basepagename', 'basepagenamee', 'subpagename', 'subpagenamee',
			'talkpagename', 'talkpagenamee', 'subjectpagename',
			'subjectpagenamee', 'pageid', 'revisionid', 'revisionday',
			'revisionday2', 'revisionmonth', 'revisionmonth1', 'revisionyear',
			'revisiontimestamp', 'revisionuser', 'cascadingsources',
		];
		foreach ( $noHashFunctions as $func ) {
			$parser->setFunctionHook( $func, [ __CLASS__, $func ], Parser::SFH_NO_HASH );
		}

		$parser->setFunctionHook(
			'namespace',
			[ __CLASS__, 'mwnamespace' ],
			Parser::SFH_NO_HASH
		);
		$parser->setFunctionHook( 'int', [ __CLASS__, 'intFunction' ], Parser::SFH_NO_HASH );
		$parser->setFunctionHook( 'special', [ __CLASS__, 'special' ] );
		$parser->setFunctionHook( 'speciale', [ __CLASS__, 'speciale' ] );
		$parser->setFunctionHook( 'tag', [ __CLASS__, 'tagObj' ], Parser::SFH_OBJECT_ARGS );
		$parser->setFunctionHook( 'formatdate', [ __CLASS__, 'formatDate' ] );

		if ( $wgAllowDisplayTitle ) {
			$parser->setFunctionHook(
				'displaytitle',
				[ __CLASS__, 'displaytitle' ],
				Parser::SFH_NO_HASH
			);
		}
		if ( $wgAllowSlowParserFunctions ) {
			$parser->setFunctionHook(
				'pagesinnamespace',
				[ __CLASS__, 'pagesinnamespace' ],
				Parser::SFH_NO_HASH
			);
		}
	}

	/**
	 * @param Parser $parser
	 * @param string $part1 Message key
	 * @param mixed ...$params To pass to wfMessage()
	 * @return array
	 */
	public static function intFunction( $parser, $part1 = '', ...$params ) {
		if ( strval( $part1 ) !== '' ) {
			$message = wfMessage( $part1, $params )
				->inLanguage( $parser->getOptions()->getUserLangObj() );
			return [ $message->plain(), 'noparse' => false ];
		} else {
			return [ 'found' => false ];
		}
	}

	/**
	 * @param Parser $parser
	 * @param string $date
	 * @param string|null $defaultPref
	 *
	 * @return string
	 */
	public static function formatDate( $parser, $date, $defaultPref = null ) {
		$lang = $parser->getFunctionLang();
		$df = MediaWikiServices::getInstance()->getDateFormatterFactory()->get( $lang );

		$date = trim( $date );

		$pref = $parser->getOptions()->getDateFormat();

		// Specify a different default date format other than the normal default
		// if the user has 'default' for their setting
		if ( $pref == 'default' && $defaultPref ) {
			$pref = $defaultPref;
		}

		$date = $df->reformat( $pref, $date, [ 'match-whole' ] );
		return $date;
	}

	public static function ns( $parser, $part1 = '' ) {
		if ( intval( $part1 ) || $part1 == "0" ) {
			$index = intval( $part1 );
		} else {
			$index = $parser->getContentLanguage()->getNsIndex( str_replace( ' ', '_', $part1 ) );
		}
		if ( $index !== false ) {
			return $parser->getContentLanguage()->getFormattedNsText( $index );
		} else {
			return [ 'found' => false ];
		}
	}

	public static function nse( $parser, $part1 = '' ) {
		$ret = self::ns( $parser, $part1 );
		if ( is_string( $ret ) ) {
			$ret = wfUrlencode( str_replace( ' ', '_', $ret ) );
		}
		return $ret;
	}

	/**
	 * urlencodes a string according to one of three patterns: (T24474)
	 *
	 * By default (for HTTP "query" strings), spaces are encoded as '+'.
	 * Or to encode a value for the HTTP "path", spaces are encoded as '%20'.
	 * For links to "wiki"s, or similar software, spaces are encoded as '_',
	 *
	 * @param Parser $parser
	 * @param string $s The text to encode.
	 * @param string|null $arg (optional): The type of encoding.
	 * @return string
	 */
	public static function urlencode( $parser, $s = '', $arg = null ) {
		static $magicWords = null;
		if ( $magicWords === null ) {
			$magicWords =
				$parser->getMagicWordFactory()->newArray( [ 'url_path', 'url_query', 'url_wiki' ] );
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
		// See T105242, where the choice to kill markers and various
		// other options were discussed.
		return $func( $parser->killMarkers( $s ) );
	}

	public static function lcfirst( $parser, $s = '' ) {
		return $parser->getContentLanguage()->lcfirst( $s );
	}

	public static function ucfirst( $parser, $s = '' ) {
		return $parser->getContentLanguage()->ucfirst( $s );
	}

	/**
	 * @param Parser $parser
	 * @param string $s
	 * @return string
	 */
	public static function lc( $parser, $s = '' ) {
		return $parser->markerSkipCallback( $s, [ $parser->getContentLanguage(), 'lc' ] );
	}

	/**
	 * @param Parser $parser
	 * @param string $s
	 * @return string
	 */
	public static function uc( $parser, $s = '' ) {
		return $parser->markerSkipCallback( $s, [ $parser->getContentLanguage(), 'uc' ] );
	}

	public static function localurl( $parser, $s = '', $arg = null ) {
		return self::urlFunction( 'getLocalURL', $s, $arg );
	}

	public static function localurle( $parser, $s = '', $arg = null ) {
		$temp = self::urlFunction( 'getLocalURL', $s, $arg );
		if ( !is_string( $temp ) ) {
			return $temp;
		} else {
			return htmlspecialchars( $temp );
		}
	}

	public static function fullurl( $parser, $s = '', $arg = null ) {
		return self::urlFunction( 'getFullURL', $s, $arg );
	}

	public static function fullurle( $parser, $s = '', $arg = null ) {
		$temp = self::urlFunction( 'getFullURL', $s, $arg );
		if ( !is_string( $temp ) ) {
			return $temp;
		} else {
			return htmlspecialchars( $temp );
		}
	}

	public static function canonicalurl( $parser, $s = '', $arg = null ) {
		return self::urlFunction( 'getCanonicalURL', $s, $arg );
	}

	public static function canonicalurle( $parser, $s = '', $arg = null ) {
		$temp = self::urlFunction( 'getCanonicalURL', $s, $arg );
		if ( !is_string( $temp ) ) {
			return $temp;
		} else {
			return htmlspecialchars( $temp );
		}
	}

	public static function urlFunction( $func, $s = '', $arg = null ) {
		$title = Title::newFromText( $s );
		# Due to order of execution of a lot of bits, the values might be encoded
		# before arriving here; if that's true, then the title can't be created
		# and the variable will fail. If we can't get a decent title from the first
		# attempt, url-decode and try for a second.
		if ( $title === null ) {
			$title = Title::newFromURL( urldecode( $s ) );
		}
		if ( $title !== null ) {
			# Convert NS_MEDIA -> NS_FILE
			if ( $title->inNamespace( NS_MEDIA ) ) {
				$title = Title::makeTitle( NS_FILE, $title->getDBkey() );
			}
			if ( $arg !== null ) {
				$text = $title->$func( $arg );
			} else {
				$text = $title->$func();
			}
			return $text;
		} else {
			return [ 'found' => false ];
		}
	}

	/**
	 * @param Parser $parser
	 * @param string $num
	 * @param string|null $arg
	 * @return string
	 */
	public static function formatnum( $parser, $num = '', $arg = null ) {
		if ( self::matchAgainstMagicword( $parser->getMagicWordFactory(), 'rawsuffix', $arg ) ) {
			$func = [ $parser->getFunctionLang(), 'parseFormattedNumber' ];
		} elseif (
			self::matchAgainstMagicword( $parser->getMagicWordFactory(), 'nocommafysuffix', $arg )
		) {
			$func = [ $parser->getFunctionLang(), 'formatNumNoSeparators' ];
		} else {
			$func = [ $parser->getFunctionLang(), 'formatNum' ];
		}
		return $parser->markerSkipCallback( $num, $func );
	}

	/**
	 * @param Parser $parser
	 * @param string $case
	 * @param string $word
	 * @return string
	 */
	public static function grammar( $parser, $case = '', $word = '' ) {
		$word = $parser->killMarkers( $word );
		return $parser->getFunctionLang()->convertGrammar( $word, $case );
	}

	/**
	 * @param Parser $parser
	 * @param string $username
	 * @param string ...$forms What to output for each gender
	 * @return string
	 */
	public static function gender( $parser, $username, ...$forms ) {
		// Some shortcuts to avoid loading user data unnecessarily
		if ( count( $forms ) === 0 ) {
			return '';
		} elseif ( count( $forms ) === 1 ) {
			return $forms[0];
		}

		$username = trim( $username );

		$gender = User::getDefaultOption( 'gender' );

		// allow prefix and normalize (e.g. "&#42;foo" -> "*foo" ).
		$title = Title::newFromText( $username, NS_USER );

		if ( $title && $title->inNamespace( NS_USER ) ) {
			$username = $title->getText();
		}

		// check parameter, or use the ParserOptions if in interface message
		$user = User::newFromName( $username );
		$genderCache = MediaWikiServices::getInstance()->getGenderCache();
		if ( $user ) {
			$gender = $genderCache->getGenderOf( $user, __METHOD__ );
		} elseif ( $username === '' && $parser->getOptions()->getInterfaceMessage() ) {
			$gender = $genderCache->getGenderOf( $parser->getOptions()->getUser(), __METHOD__ );
		}
		$ret = $parser->getFunctionLang()->gender( $gender, $forms );
		return $ret;
	}

	/**
	 * @param Parser $parser
	 * @param string $text
	 * @param string ...$forms What to output for each number (singular, dual, plural, etc.)
	 * @return string
	 */
	public static function plural( $parser, $text = '', ...$forms ) {
		$text = $parser->getFunctionLang()->parseFormattedNumber( $text );
		settype( $text, ctype_digit( $text ) ? 'int' : 'float' );
		return $parser->getFunctionLang()->convertPlural( $text, $forms );
	}

	/**
	 * @param Parser $parser
	 * @param string $text
	 * @return string
	 */
	public static function bidi( $parser, $text = '' ) {
		return $parser->getFunctionLang()->embedBidi( $text );
	}

	/**
	 * Shorthand for getting a Language Converter for Target language
	 * @param Parser $parser Parent parser
	 * @return ILanguageConverter
	 */
	private static function getTargetLanguageConverter( Parser $parser ) : ILanguageConverter {
		return MediaWikiServices::getInstance()->getLanguageConverterFactory()
			->getLanguageConverter( $parser->getTargetLanguage() );
	}

	/**
	 * Override the title of the page when viewed, provided we've been given a
	 * title which will normalise to the canonical title
	 *
	 * @param Parser $parser Parent parser
	 * @param string $text Desired title text
	 * @param string $uarg
	 * @return string
	 */
	public static function displaytitle( $parser, $text = '', $uarg = '' ) {
		global $wgRestrictDisplayTitle;

		static $magicWords = null;
		if ( $magicWords === null ) {
			$magicWords = $parser->getMagicWordFactory()->newArray(
				[ 'displaytitle_noerror', 'displaytitle_noreplace' ] );
		}
		$arg = $magicWords->matchStartToEnd( $uarg );

		// parse a limited subset of wiki markup (just the single quote items)
		$text = $parser->doQuotes( $text );

		// remove stripped text (e.g. the UNIQ-QINU stuff) that was generated by tag extensions/whatever
		$text = $parser->killMarkers( $text );

		// list of disallowed tags for DISPLAYTITLE
		// these will be escaped even though they are allowed in normal wiki text
		$bad = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'blockquote', 'ol', 'ul', 'li', 'hr',
			'table', 'tr', 'th', 'td', 'dl', 'dd', 'caption', 'p', 'ruby', 'rb', 'rt', 'rtc', 'rp', 'br' ];

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
		$text = Sanitizer::normalizeCharReferences( Sanitizer::removeHTMLtags(
			$text,
			$htmlTagsCallback,
			[],
			[],
			$bad
		) );
		$title = Title::newFromText( Sanitizer::stripAllTags( $text ) );

		if ( !$wgRestrictDisplayTitle ||
			( $title instanceof Title
			&& !$title->hasFragment()
			&& $title->equals( $parser->getTitle() ) )
		) {
			$old = $parser->mOutput->getProperty( 'displaytitle' );
			if ( $old === false || $arg !== 'displaytitle_noreplace' ) {
				$parser->mOutput->setDisplayTitle( $text );
			}
			if ( $old !== false && $old !== $text && !$arg ) {

				$converter = self::getTargetLanguageConverter( $parser );
				return '<span class="error">' .
					wfMessage( 'duplicate-displaytitle',
						// Message should be parsed, but these params should only be escaped.
						$converter->markNoConversion( wfEscapeWikiText( $old ) ),
						$converter->markNoConversion( wfEscapeWikiText( $text ) )
					)->inContentLanguage()->text() .
					'</span>';
			} else {
				return '';
			}
		} else {
			$parser->getOutput()->addWarning(
				wfMessage( 'restricted-displaytitle',
					// Message should be parsed, but this param should only be escaped.
					wfEscapeWikiText( $text )
				)->text()
			);
			$parser->addTrackingCategory( 'restricted-displaytitle-ignored' );
		}
	}

	/**
	 * Matches the given value against the value of given magic word
	 *
	 * @param MagicWordFactory $magicWordFactory A factory to get the word from, e.g., from
	 *   $parser->getMagicWordFactory()
	 * @param string $magicword Magic word key
	 * @param string $value Value to match
	 * @return bool True on successful match
	 */
	private static function matchAgainstMagicword(
		MagicWordFactory $magicWordFactory, $magicword, $value
	) {
		$value = trim( strval( $value ) );
		if ( $value === '' ) {
			return false;
		}
		$mwObject = $magicWordFactory->get( $magicword );
		return $mwObject->matchStartToEnd( $value );
	}

	/**
	 * Formats a number according to a language.
	 *
	 * @param int|float $num
	 * @param string $raw
	 * @param Language|StubUserLang $language
	 * @param MagicWordFactory|null $magicWordFactory To evaluate $raw
	 * @return string
	 */
	public static function formatRaw(
		$num, $raw, $language, MagicWordFactory $magicWordFactory = null
	) {
		if ( $raw !== null && !$magicWordFactory ) {
			$magicWordFactory = MediaWikiServices::getInstance()->getMagicWordFactory();
		}
		if (
			$raw !== null && self::matchAgainstMagicword( $magicWordFactory, 'rawsuffix', $raw )
		) {
			return $num;
		} else {
			return $language->formatNum( $num );
		}
	}

	public static function numberofpages( $parser, $raw = null ) {
		return self::formatRaw( SiteStats::pages(), $raw, $parser->getFunctionLang() );
	}

	public static function numberofusers( $parser, $raw = null ) {
		return self::formatRaw( SiteStats::users(), $raw, $parser->getFunctionLang() );
	}

	public static function numberofactiveusers( $parser, $raw = null ) {
		return self::formatRaw( SiteStats::activeUsers(), $raw, $parser->getFunctionLang() );
	}

	public static function numberofarticles( $parser, $raw = null ) {
		return self::formatRaw( SiteStats::articles(), $raw, $parser->getFunctionLang() );
	}

	public static function numberoffiles( $parser, $raw = null ) {
		return self::formatRaw( SiteStats::images(), $raw, $parser->getFunctionLang() );
	}

	public static function numberofadmins( $parser, $raw = null ) {
		return self::formatRaw(
			SiteStats::numberingroup( 'sysop' ),
			$raw,
			$parser->getFunctionLang()
		);
	}

	public static function numberofedits( $parser, $raw = null ) {
		return self::formatRaw( SiteStats::edits(), $raw, $parser->getFunctionLang() );
	}

	public static function pagesinnamespace( $parser, $namespace = 0, $raw = null ) {
		return self::formatRaw(
			SiteStats::pagesInNs( intval( $namespace ) ),
			$raw,
			$parser->getFunctionLang()
		);
	}

	public static function numberingroup( $parser, $name = '', $raw = null ) {
		return self::formatRaw(
			SiteStats::numberingroup( strtolower( $name ) ),
			$raw,
			$parser->getFunctionLang()
		);
	}

	/**
	 * Given a title, return the namespace name that would be given by the
	 * corresponding magic word
	 * Note: function name changed to "mwnamespace" rather than "namespace"
	 * to not break PHP 5.3
	 * @param Parser $parser
	 * @param string|null $title
	 * @return mixed|string
	 */
	public static function mwnamespace( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		return str_replace( '_', ' ', $t->getNsText() );
	}

	public static function namespacee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		return wfUrlencode( $t->getNsText() );
	}

	public static function namespacenumber( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		return $t->getNamespace();
	}

	public static function talkspace( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null || !$t->canHaveTalkPage() ) {
			return '';
		}
		return str_replace( '_', ' ', $t->getTalkNsText() );
	}

	public static function talkspacee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null || !$t->canHaveTalkPage() ) {
			return '';
		}
		return wfUrlencode( $t->getTalkNsText() );
	}

	public static function subjectspace( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		return str_replace( '_', ' ', $t->getSubjectNsText() );
	}

	public static function subjectspacee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		return wfUrlencode( $t->getSubjectNsText() );
	}

	/**
	 * Functions to get and normalize pagenames, corresponding to the magic words
	 * of the same names
	 * @param Parser $parser
	 * @param string|null $title
	 * @return string
	 */
	public static function pagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		return wfEscapeWikiText( $t->getText() );
	}

	public static function pagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		return wfEscapeWikiText( $t->getPartialURL() );
	}

	public static function fullpagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null || !$t->canHaveTalkPage() ) {
			return '';
		}
		return wfEscapeWikiText( $t->getPrefixedText() );
	}

	public static function fullpagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null || !$t->canHaveTalkPage() ) {
			return '';
		}
		return wfEscapeWikiText( $t->getPrefixedURL() );
	}

	public static function subpagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		return wfEscapeWikiText( $t->getSubpageText() );
	}

	public static function subpagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		return wfEscapeWikiText( $t->getSubpageUrlForm() );
	}

	public static function rootpagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		return wfEscapeWikiText( $t->getRootText() );
	}

	public static function rootpagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		return wfEscapeWikiText( wfUrlencode( str_replace( ' ', '_', $t->getRootText() ) ) );
	}

	public static function basepagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		return wfEscapeWikiText( $t->getBaseText() );
	}

	public static function basepagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		return wfEscapeWikiText( wfUrlencode( str_replace( ' ', '_', $t->getBaseText() ) ) );
	}

	public static function talkpagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null || !$t->canHaveTalkPage() ) {
			return '';
		}
		return wfEscapeWikiText( $t->getTalkPage()->getPrefixedText() );
	}

	public static function talkpagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null || !$t->canHaveTalkPage() ) {
			return '';
		}
		return wfEscapeWikiText( $t->getTalkPage()->getPrefixedURL() );
	}

	public static function subjectpagename( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		return wfEscapeWikiText( $t->getSubjectPage()->getPrefixedText() );
	}

	public static function subjectpagenamee( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		return wfEscapeWikiText( $t->getSubjectPage()->getPrefixedURL() );
	}

	/**
	 * Return the number of pages, files or subcats in the given category,
	 * or 0 if it's nonexistent. This is an expensive parser function and
	 * can't be called too many times per page.
	 * @param Parser $parser
	 * @param string $name
	 * @param string|null $arg1
	 * @param string|null $arg2
	 * @return string
	 */
	public static function pagesincategory( $parser, $name = '', $arg1 = null, $arg2 = null ) {
		static $magicWords = null;
		if ( $magicWords === null ) {
			$magicWords = $parser->getMagicWordFactory()->newArray( [
				'pagesincategory_all',
				'pagesincategory_pages',
				'pagesincategory_subcats',
				'pagesincategory_files'
			] );
		}
		static $cache = [];

		// split the given option to its variable
		if ( self::matchAgainstMagicword( $parser->getMagicWordFactory(), 'rawsuffix', $arg1 ) ) {
			// {{pagesincategory:|raw[|type]}}
			$raw = $arg1;
			$type = $magicWords->matchStartToEnd( $arg2 );
		} else {
			// {{pagesincategory:[|type[|raw]]}}
			$type = $magicWords->matchStartToEnd( $arg1 );
			$raw = $arg2;
		}
		if ( !$type ) { // backward compatibility
			$type = 'pagesincategory_all';
		}

		$title = Title::makeTitleSafe( NS_CATEGORY, $name );
		if ( !$title ) { # invalid title
			return self::formatRaw( 0, $raw, $parser->getFunctionLang() );
		}
		$parser->getContentLanguage()->findVariantLink( $name, $title, true );

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
		return self::formatRaw( $count, $raw, $parser->getFunctionLang() );
	}

	/**
	 * Return the size of the given page, or 0 if it's nonexistent.  This is an
	 * expensive parser function and can't be called too many times per page.
	 *
	 * @param Parser $parser
	 * @param string $page Name of page to check (Default: empty string)
	 * @param string|null $raw Should number be human readable with commas or just number
	 * @return string
	 */
	public static function pagesize( $parser, $page = '', $raw = null ) {
		$title = Title::newFromText( $page );

		if ( !is_object( $title ) ) {
			return self::formatRaw( 0, $raw, $parser->getFunctionLang() );
		}

		// fetch revision from cache/database and return the value
		$rev = self::getCachedRevisionObject( $parser, $title, 'vary-revision-sha1' );
		$length = $rev ? $rev->getSize() : 0;
		if ( $length === null ) {
			// We've had bugs where rev_len was not being recorded for empty pages, see T135414
			$length = 0;
		}
		return self::formatRaw( $length, $raw, $parser->getFunctionLang() );
	}

	/**
	 * Returns the requested protection level for the current page. This
	 * is an expensive parser function and can't be called too many times
	 * per page, unless the protection levels/expiries for the given title
	 * have already been retrieved
	 *
	 * @param Parser $parser
	 * @param string $type
	 * @param string $title
	 *
	 * @return string
	 */
	public static function protectionlevel( $parser, $type = '', $title = '' ) {
		$titleObject = Title::newFromText( $title ) ?? $parser->getTitle();
		if ( $titleObject->areRestrictionsLoaded() || $parser->incrementExpensiveFunctionCount() ) {
			$restrictions = $titleObject->getRestrictions( strtolower( $type ) );
			# Title::getRestrictions returns an array, its possible it may have
			# multiple values in the future
			return implode( ',', $restrictions );
		}
		return '';
	}

	/**
	 * Returns the requested protection expiry for the current page. This
	 * is an expensive parser function and can't be called too many times
	 * per page, unless the protection levels/expiries for the given title
	 * have already been retrieved
	 *
	 * @param Parser $parser
	 * @param string $type
	 * @param string $title
	 *
	 * @return string
	 */
	public static function protectionexpiry( $parser, $type = '', $title = '' ) {
		$titleObject = Title::newFromText( $title ) ?? $parser->getTitle();
		if ( $titleObject->areRestrictionsLoaded() || $parser->incrementExpensiveFunctionCount() ) {
			$expiry = $titleObject->getRestrictionExpiry( strtolower( $type ) );
			// getRestrictionExpiry() returns false on invalid type; trying to
			// match protectionlevel() function that returns empty string instead
			if ( $expiry === false ) {
				$expiry = '';
			}
			return $expiry;
		}
		return '';
	}

	/**
	 * Gives language names.
	 * @param Parser $parser
	 * @param string $code Language code (of which to get name)
	 * @param string $inLanguage Language code (in which to get name)
	 * @return string
	 */
	public static function language( $parser, $code = '', $inLanguage = '' ) {
		$code = strtolower( $code );
		$inLanguage = strtolower( $inLanguage );
		$lang = MediaWikiServices::getInstance()
			->getLanguageNameUtils()
			->getLanguageName( $code, $inLanguage );
		return $lang !== '' ? $lang : LanguageCode::bcp47( $code );
	}

	/**
	 * Unicode-safe str_pad with the restriction that $length is forced to be <= 500
	 * @param Parser $parser
	 * @param string $string
	 * @param string $length
	 * @param string $padding
	 * @param int $direction
	 * @return string
	 */
	public static function pad(
		$parser, $string, $length, $padding = '0', $direction = STR_PAD_RIGHT
	) {
		$padding = $parser->killMarkers( $padding );
		$lengthOfPadding = mb_strlen( $padding );
		if ( $lengthOfPadding == 0 ) {
			return $string;
		}

		# The remaining length to add counts down to 0 as padding is added
		$length = min( (int)$length, 500 ) - mb_strlen( $string );
		if ( $length <= 0 ) {
			// Nothing to add
			return $string;
		}

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

	public static function padleft( $parser, $string = '', $length = 0, $padding = '0' ) {
		return self::pad( $parser, $string, $length, $padding, STR_PAD_LEFT );
	}

	public static function padright( $parser, $string = '', $length = 0, $padding = '0' ) {
		return self::pad( $parser, $string, $length, $padding );
	}

	/**
	 * @param Parser $parser
	 * @param string $text
	 * @return string
	 */
	public static function anchorencode( $parser, $text ) {
		$text = $parser->killMarkers( $text );
		$section = (string)substr( $parser->guessSectionNameFromWikiText( $text ), 1 );
		return Sanitizer::safeEncodeAttribute( $section );
	}

	public static function special( $parser, $text ) {
		list( $page, $subpage ) = MediaWikiServices::getInstance()->getSpecialPageFactory()->
			resolveAlias( $text );
		if ( $page ) {
			$title = SpecialPage::getTitleFor( $page, $subpage );
			return $title->getPrefixedText();
		} else {
			// unknown special page, just use the given text as its title, if at all possible
			$title = Title::makeTitleSafe( NS_SPECIAL, $text );
			return $title ? $title->getPrefixedText() : self::special( $parser, 'Badtitle' );
		}
	}

	public static function speciale( $parser, $text ) {
		return wfUrlencode( str_replace( ' ', '_', self::special( $parser, $text ) ) );
	}

	/**
	 * @param Parser $parser
	 * @param string $text The sortkey to use
	 * @param string $uarg Either "noreplace" or "noerror" (in en)
	 *   both suppress errors, and noreplace does nothing if
	 *   a default sortkey already exists.
	 * @return string
	 */
	public static function defaultsort( $parser, $text, $uarg = '' ) {
		static $magicWords = null;
		if ( $magicWords === null ) {
			$magicWords = $parser->getMagicWordFactory()->newArray(
				[ 'defaultsort_noerror', 'defaultsort_noreplace' ] );
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
			$converter = $parser->getTargetLanguage()->getConverter();
			return '<span class="error">' .
				wfMessage( 'duplicate-defaultsort',
					// Message should be parsed, but these params should only be escaped.
					$converter->markNoConversion( wfEscapeWikiText( $old ) ),
					$converter->markNoConversion( wfEscapeWikiText( $text ) )
				)->inContentLanguage()->text() .
				'</span>';
		}
	}

	/**
	 * Usage {{filepath|300}}, {{filepath|nowiki}}, {{filepath|nowiki|300}}
	 * or {{filepath|300|nowiki}} or {{filepath|300px}}, {{filepath|200x300px}},
	 * {{filepath|nowiki|200x300px}}, {{filepath|200x300px|nowiki}}.
	 *
	 * @param Parser $parser
	 * @param string $name
	 * @param string $argA
	 * @param string $argB
	 * @return array|string
	 */
	public static function filepath( $parser, $name = '', $argA = '', $argB = '' ) {
		$file = MediaWikiServices::getInstance()->getRepoGroup()->findFile( $name );

		if ( $argA == 'nowiki' ) {
			// {{filepath: | option [| size] }}
			$isNowiki = true;
			$parsedWidthParam = Parser::parseWidthParam( $argB );
		} else {
			// {{filepath: [| size [|option]] }}
			$parsedWidthParam = Parser::parseWidthParam( $argA );
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
				return [ $url, 'nowiki' => true ];
			}
			return $url;
		} else {
			return '';
		}
	}

	/**
	 * Parser function to extension tag adaptor
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @param PPNode[] $args
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

		$attributes = [];
		foreach ( $args as $arg ) {
			$bits = $arg->splitArg();
			if ( strval( $bits['index'] ) === '' ) {
				$name = trim( $frame->expand( $bits['name'], PPFrame::STRIP_COMMENTS ) );
				$value = trim( $frame->expand( $bits['value'] ) );
				if ( preg_match( '/^(?:["\'](.+)["\']|""|\'\')$/s', $value, $m ) ) {
					$value = $m[1] ?? '';
				}
				$attributes[$name] = $value;
			}
		}

		$stripList = $parser->getStripList();
		if ( !in_array( $tagName, $stripList ) ) {
			// we can't handle this tag (at least not now), so just re-emit it as an ordinary tag
			$attrText = '';
			foreach ( $attributes as $name => $value ) {
				$attrText .= ' ' . htmlspecialchars( $name ) . '="' . htmlspecialchars( $value ) . '"';
			}
			if ( $inner === null ) {
				return "<$tagName$attrText/>";
			}
			return "<$tagName$attrText>$inner</$tagName>";
		}

		$params = [
			'name' => $tagName,
			'inner' => $inner,
			'attributes' => $attributes,
			'close' => "</$tagName>",
		];
		return $parser->extensionSubstitution( $params, $frame );
	}

	/**
	 * Fetched the current revision of the given title and return this.
	 * Will increment the expensive function count and
	 * add a template link to get the value refreshed on changes.
	 * For a given title, which is equal to the current parser title,
	 * the revision object from the parser is used, when that is the current one
	 *
	 * @param Parser $parser
	 * @param Title $title
	 * @param string $vary ParserOuput vary-* flag
	 * @return RevisionRecord|null
	 * @since 1.23
	 */
	private static function getCachedRevisionObject( $parser, $title, $vary ) {
		if ( !$title ) {
			return null;
		}

		$revisionRecord = null;

		$isSelfReferential = $title->equals( $parser->getTitle() );
		if ( $isSelfReferential ) {
			// Revision is for the same title that is currently being parsed. Only use the last
			// saved revision, regardless of Parser::getRevisionId() or fake revision injection
			// callbacks against the current title.
			$parserRevisionRecord = $parser->getRevisionRecordObject();
			if ( $parserRevisionRecord && $parserRevisionRecord->isCurrent() ) {
				$revisionRecord = $parserRevisionRecord;
			}
		}

		$parserOutput = $parser->getOutput();
		if ( !$revisionRecord ) {
			if (
				!$parser->isCurrentRevisionOfTitleCached( $title ) &&
				!$parser->incrementExpensiveFunctionCount()
			) {
				return null; // not allowed
			}
			// Get the current revision, ignoring Parser::getRevisionId() being null/old
			$revisionRecord = $parser->fetchCurrentRevisionRecordOfTitle( $title );
			if ( !$revisionRecord ) {
				// Convert `false` error return to `null`
				$revisionRecord = null;
			}
			// Register dependency in templatelinks
			$parserOutput->addTemplate(
				$title,
				$revisionRecord ? $revisionRecord->getPageId() : 0,
				$revisionRecord ? $revisionRecord->getId() : 0
			);
		}

		if ( $isSelfReferential ) {
			wfDebug( __METHOD__ . ": used current revision, setting $vary" );
			// Upon page save, the result of the parser function using this might change
			$parserOutput->setFlag( $vary );
			if ( $vary === 'vary-revision-sha1' && $revisionRecord ) {
				try {
					$sha1 = $revisionRecord->getSha1();
				} catch ( RevisionAccessException $e ) {
					$sha1 = null;
				}
				$parserOutput->setRevisionUsedSha1Base36( $sha1 );
			}
		}

		return $revisionRecord;
	}

	/**
	 * Get the pageid of a specified page
	 * @param Parser $parser
	 * @param string|null $title Title to get the pageid from
	 * @return int|null|string
	 * @since 1.23
	 */
	public static function pageid( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( !$t ) {
			return '';
		} elseif ( !$t->canExist() || $t->isExternal() ) {
			return 0; // e.g. special page or interwiki link
		}

		$parserOutput = $parser->getOutput();

		if ( $t->equals( $parser->getTitle() ) ) {
			// Revision is for the same title that is currently being parsed.
			// Use the title from Parser in case a new page ID was injected into it.
			$parserOutput->setFlag( 'vary-page-id' );
			$id = $parser->getTitle()->getArticleID();
			if ( $id ) {
				$parserOutput->setSpeculativePageIdUsed( $id );
			}

			return $id;
		}

		// Check the link cache for the title
		$linkCache = MediaWikiServices::getInstance()->getLinkCache();
		$pdbk = $t->getPrefixedDBkey();
		$id = $linkCache->getGoodLinkID( $pdbk );
		if ( $id != 0 || $linkCache->isBadLink( $pdbk ) ) {
			$parserOutput->addLink( $t, $id );

			return $id;
		}

		// We need to load it from the DB, so mark expensive
		if ( $parser->incrementExpensiveFunctionCount() ) {
			$id = $t->getArticleID();
			$parserOutput->addLink( $t, $id );

			return $id;
		}

		return null;
	}

	/**
	 * Get the id from the last revision of a specified page.
	 * @param Parser $parser
	 * @param string|null $title Title to get the id from
	 * @return int|null|string
	 * @since 1.23
	 */
	public static function revisionid( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}

		$services = MediaWikiServices::getInstance();
		if (
			$t->equals( $parser->getTitle() ) &&
			$services->getMainConfig()->get( 'MiserMode' ) &&
			!$parser->getOptions()->getInterfaceMessage() &&
			// @TODO: disallow this word on all namespaces (T235957)
			$services->getNamespaceInfo()->isSubject( $t->getNamespace() )
		) {
			// Use a stub result instead of the actual revision ID in order to avoid
			// double parses on page save but still allow preview detection (T137900)
			if ( $parser->getRevisionId() || $parser->getOptions()->getSpeculativeRevId() ) {
				return '-';
			} else {
				$parser->getOutput()->setFlag( 'vary-revision-exists' );
				return '';
			}
		}
		// fetch revision from cache/database and return the value
		$rev = self::getCachedRevisionObject( $parser, $t, 'vary-revision-id' );
		return $rev ? $rev->getId() : '';
	}

	/**
	 * Get the day from the last revision of a specified page.
	 * @param Parser $parser
	 * @param string|null $title Title to get the day from
	 * @return string
	 * @since 1.23
	 */
	public static function revisionday( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		// fetch revision from cache/database and return the value
		$rev = self::getCachedRevisionObject( $parser, $t, 'vary-revision-timestamp' );
		return $rev ? MWTimestamp::getLocalInstance( $rev->getTimestamp() )->format( 'j' ) : '';
	}

	/**
	 * Get the day with leading zeros from the last revision of a specified page.
	 * @param Parser $parser
	 * @param string|null $title Title to get the day from
	 * @return string
	 * @since 1.23
	 */
	public static function revisionday2( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		// fetch revision from cache/database and return the value
		$rev = self::getCachedRevisionObject( $parser, $t, 'vary-revision-timestamp' );
		return $rev ? MWTimestamp::getLocalInstance( $rev->getTimestamp() )->format( 'd' ) : '';
	}

	/**
	 * Get the month with leading zeros from the last revision of a specified page.
	 * @param Parser $parser
	 * @param string|null $title Title to get the month from
	 * @return string
	 * @since 1.23
	 */
	public static function revisionmonth( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		// fetch revision from cache/database and return the value
		$rev = self::getCachedRevisionObject( $parser, $t, 'vary-revision-timestamp' );
		return $rev ? MWTimestamp::getLocalInstance( $rev->getTimestamp() )->format( 'm' ) : '';
	}

	/**
	 * Get the month from the last revision of a specified page.
	 * @param Parser $parser
	 * @param string|null $title Title to get the month from
	 * @return string
	 * @since 1.23
	 */
	public static function revisionmonth1( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		// fetch revision from cache/database and return the value
		$rev = self::getCachedRevisionObject( $parser, $t, 'vary-revision-timestamp' );
		return $rev ? MWTimestamp::getLocalInstance( $rev->getTimestamp() )->format( 'n' ) : '';
	}

	/**
	 * Get the year from the last revision of a specified page.
	 * @param Parser $parser
	 * @param string|null $title Title to get the year from
	 * @return string
	 * @since 1.23
	 */
	public static function revisionyear( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		// fetch revision from cache/database and return the value
		$rev = self::getCachedRevisionObject( $parser, $t, 'vary-revision-timestamp' );
		return $rev ? MWTimestamp::getLocalInstance( $rev->getTimestamp() )->format( 'Y' ) : '';
	}

	/**
	 * Get the timestamp from the last revision of a specified page.
	 * @param Parser $parser
	 * @param string|null $title Title to get the timestamp from
	 * @return string
	 * @since 1.23
	 */
	public static function revisiontimestamp( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		// fetch revision from cache/database and return the value
		$rev = self::getCachedRevisionObject( $parser, $t, 'vary-revision-timestamp' );
		return $rev ? MWTimestamp::getLocalInstance( $rev->getTimestamp() )->format( 'YmdHis' ) : '';
	}

	/**
	 * Get the user from the last revision of a specified page.
	 * @param Parser $parser
	 * @param string|null $title Title to get the user from
	 * @return string
	 * @since 1.23
	 */
	public static function revisionuser( $parser, $title = null ) {
		$t = Title::newFromText( $title );
		if ( $t === null ) {
			return '';
		}
		// fetch revision from cache/database and return the value
		$rev = self::getCachedRevisionObject( $parser, $t, 'vary-user' );
		if ( $rev === null ) {
			return '';
		}
		$user = $rev->getUser();
		return $user ? $user->getName() : '';
	}

	/**
	 * Returns the sources of any cascading protection acting on a specified page.
	 * Pages will not return their own title unless they transclude themselves.
	 * This is an expensive parser function and can't be called too many times per page,
	 * unless cascading protection sources for the page have already been loaded.
	 *
	 * @param Parser $parser
	 * @param string $title
	 *
	 * @return string
	 * @since 1.23
	 */
	public static function cascadingsources( $parser, $title = '' ) {
		$titleObject = Title::newFromText( $title ) ?? $parser->getTitle();
		if ( $titleObject->areCascadeProtectionSourcesLoaded()
			|| $parser->incrementExpensiveFunctionCount()
		) {
			$names = [];
			$sources = $titleObject->getCascadeProtectionSources();
			foreach ( $sources[0] as $sourceTitle ) {
				$names[] = $sourceTitle->getPrefixedText();
			}
			return implode( '|', $names );
		}
		return '';
	}

}

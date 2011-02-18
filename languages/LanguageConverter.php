<?php
/**
 * Contains the LanguageConverter class and ConverterRule class
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
 * @ingroup Language
 */

/**
 * Base class for language conversion.
 * @ingroup Language
 *
 * @author Zhengzhu Feng <zhengzhu@gmail.com>
 * @maintainers fdcn <fdcn64@gmail.com>, shinjiman <shinjiman@gmail.com>, PhiLiP <philip.npc@gmail.com>
 */
class LanguageConverter {
	var $mMainLanguageCode;
	var $mVariants, $mVariantFallbacks, $mVariantNames;
	var $mTablesLoaded = false;
	var $mTables;
	// 'bidirectional' 'unidirectional' 'disable' for each variant
	var $mManualLevel;
	var $mCacheKey;
	var $mLangObj;
	var $mFlags;
	var $mDescCodeSep = ':', $mDescVarSep = ';';
	var $mUcfirst = false;
	var $mConvRuleTitle = false;
	var $mURLVariant;
	var $mUserVariant;
	var $mHeaderVariant;
	var $mMaxDepth = 10;
	var $mVarSeparatorPattern;

	const CACHE_VERSION_KEY = 'VERSION 6';

	/**
	 * Constructor
	 *
	 * @param $langobj Language The Language Object
	 * @param $maincode String: the main language code of this language
	 * @param $variants Array: the supported variants of this language
	 * @param $variantfallbacks Array: the fallback language of each variant
	 * @param $flags Array: defining the custom strings that maps to the flags
	 * @param $manualLevel Array: limit for supported variants
	 */
	public function __construct( $langobj, $maincode,
								$variants = array(),
								$variantfallbacks = array(),
								$flags = array(),
								$manualLevel = array() ) {
		global $wgDisabledVariants, $wgLanguageNames;
		$this->mLangObj = $langobj;
		$this->mMainLanguageCode = $maincode;
		$this->mVariants = array_diff( $variants, $wgDisabledVariants );
		$this->mVariantFallbacks = $variantfallbacks;
		$this->mVariantNames = $wgLanguageNames;
		$this->mCacheKey = wfMemcKey( 'conversiontables', $maincode );
		$defaultflags = array(
			// 'S' show converted text
			// '+' add rules for alltext
			// 'E' the gave flags is error
			// these flags above are reserved for program
			'A' => 'A',	  // add rule for convert code (all text convert)
			'T' => 'T',	  // title convert
			'R' => 'R',	  // raw content
			'D' => 'D',	  // convert description (subclass implement)
			'-' => '-',	  // remove convert (not implement)
			'H' => 'H',	  // add rule for convert code
						  // (but no display in placed code )
			'N' => 'N'	  // current variant name
		);
		$this->mFlags = array_merge( $defaultflags, $flags );
		foreach ( $this->mVariants as $v ) {
			if ( array_key_exists( $v, $manualLevel ) ) {
				$this->mManualLevel[$v] = $manualLevel[$v];
			} else {
				$this->mManualLevel[$v] = 'bidirectional';
			}
			$this->mFlags[$v] = $v;
		}
	}

	/**
	 * Get all valid variants.
	 * Call this instead of using $this->mVariants directly.
	 *
	 * @return Array: contains all valid variants
	 */
	public function getVariants() {
		return $this->mVariants;
	}

	/**
	 * In case some variant is not defined in the markup, we need
	 * to have some fallback. For example, in zh, normally people
	 * will define zh-hans and zh-hant, but less so for zh-sg or zh-hk.
	 * when zh-sg is preferred but not defined, we will pick zh-hans
	 * in this case. Right now this is only used by zh.
	 *
	 * @param $variant String: the language code of the variant
	 * @return String: The code of the fallback language or the
	 *				 main code if there is no fallback
	 */
	public function getVariantFallbacks( $variant ) {
		if ( isset( $this->mVariantFallbacks[$variant] ) ) {
			return $this->mVariantFallbacks[$variant];
		}
		return $this->mMainLanguageCode;
	}

	/**
	 * Get the title produced by the conversion rule.
	 * @return String: The converted title text
	 */
	public function getConvRuleTitle() {
		return $this->mConvRuleTitle;
	}

	/**
	 * Get preferred language variant.
	 * @return String: the preferred language code
	 */
	public function getPreferredVariant() {
		global $wgDefaultLanguageVariant, $wgUser;

		$req = $this->getURLVariant();

		if ( $wgUser->isLoggedIn() && !$req ) {
			$req = $this->getUserVariant();
		}

		elseif ( !$req ) {
			$req = $this->getHeaderVariant();
		}

		if ( $wgDefaultLanguageVariant && !$req ) {
			$req = $this->validateVariant( $wgDefaultLanguageVariant );
		}

		// This function, unlike the other get*Variant functions, is
		// not memoized (i.e. there return value is not cached) since
		// new information might appear during processing after this
		// is first called.
		if ( $req ) {
			return $req;
		}
		return $this->mMainLanguageCode;
	}

	/**
	 * Get default variant.
	 * This function would not be affected by user's settings or headers
	 * @return String: the default variant code
	 */
	public function getDefaultVariant() {
		global $wgDefaultLanguageVariant;

		$req = $this->getURLVariant();

		if ( $wgDefaultLanguageVariant && !$req ) {
			$req = $this->validateVariant( $wgDefaultLanguageVariant );
		}

		if ( $req ) {
			return $req;
		}
		return $this->mMainLanguageCode;
	}

	/**
	 * Validate the variant
	 * @param $variant String: the variant to validate
	 * @return Mixed: returns the variant if it is valid, null otherwise
	 */
	protected function validateVariant( $variant = null ) {
		if ( $variant !== null &&
			 in_array( $variant, $this->mVariants ) ) {
			return $variant;
		}
		return null;
	}

	/**
	 * Get the variant specified in the URL
	 *
	 * @return Mixed: variant if one found, false otherwise.
	 */
	public function getURLVariant() {
		global $wgRequest;

		if ( $this->mURLVariant ) {
			return $this->mURLVariant;
		}

		// see if the preference is set in the request
		$ret = $wgRequest->getText( 'variant' );

		if ( !$ret ) {
			$ret = $wgRequest->getVal( 'uselang' );
		}

		return $this->mURLVariant = $this->validateVariant( $ret );
	}

	/**
	 * Determine if the user has a variant set.
	 *
	 * @return Mixed: variant if one found, false otherwise.
	 */
	protected function getUserVariant() {
		global $wgUser;

		// memoizing this function wreaks havoc on parserTest.php
		/* if ( $this->mUserVariant ) { */
		/* 	return $this->mUserVariant; */
		/* } */

		// get language variant preference from logged in users
		// Don't call this on stub objects because that causes infinite
		// recursion during initialisation
		if ( $wgUser->isLoggedIn() )  {
			$ret = $wgUser->getOption( 'variant' );
		}
		else {
			// figure out user lang without constructing wgLang to avoid
			// infinite recursion
			$ret = $wgUser->getOption( 'language' );
		}

		return $this->mUserVariant = $this->validateVariant( $ret );
	}

	/**
	 * Determine the language variant from the Accept-Language header.
	 *
	 * @return Mixed: variant if one found, false otherwise.
	 */
	protected function getHeaderVariant() {
		global $wgRequest;

		if ( $this->mHeaderVariant ) {
			return $this->mHeaderVariant;
		}

		// see if some supported language variant is set in the
		// http header.
		$languages = array_keys( $wgRequest->getAcceptLang() );
		if ( empty( $languages ) ) {
			return null;
		}

		$fallback_languages = array();
		foreach ( $languages as $language ) {
			$this->mHeaderVariant = $this->validateVariant( $language );
			if ( $this->mHeaderVariant ) {
				break;
			}

			// To see if there are fallbacks of current language.
			// We record these fallback variants, and process
			// them later.
			$fallbacks = $this->getVariantFallbacks( $language );
			if ( is_string( $fallbacks ) ) {
				$fallback_languages[] = $fallbacks;
			} elseif ( is_array( $fallbacks ) ) {
				$fallback_languages =
					array_merge( $fallback_languages,
								 $fallbacks );
			}
		}

		if ( !$this->mHeaderVariant ) {
			// process fallback languages now
			$fallback_languages = array_unique( $fallback_languages );
			foreach ( $fallback_languages as $language ) {
				$this->mHeaderVariant = $this->validateVariant( $language );
				if ( $this->mHeaderVariant ) {
					break;
				}
			}
		}

		return $this->mHeaderVariant;
	}

	/**
	 * Caption convert, base on preg_replace_callback.
	 *
	 * To convert text in "title" or "alt", like '<img alt="text" ... '
	 * or '<span title="text" ... '
	 *
	 * @return String like ' alt="yyyy"' or ' title="yyyy"'
	 */
	protected function captionConvert( $matches ) {
		// TODO: cache the preferred variant in every autoConvert() process,
		// this helps improve performance in a way.
		$toVariant = $this->getPreferredVariant();
		$title = $matches[1];
		$text = $matches[2];
		
		// we convert captions except URL
		if ( !strpos( $text, '://' ) ) {
			$text = $this->translate( $text, $toVariant );
		}
		
		// remove HTML tags to prevent disrupting the layout
		$text = preg_replace( '/<[^>]+>/', '', $text );
		// escape HTML special chars to prevent disrupting the layout
		$text = htmlspecialchars( $text );
		
		return " {$title}=\"{$text}\"";
	}

	/**
	 * Dictionary-based conversion.
	 * This function would not parse the conversion rules.
	 * If you want to parse rules, try to use convert() or
	 * convertTo().
	 *
	 * @param $text String: the text to be converted
	 * @param $toVariant String: the target language code
	 * @return String: the converted text
	 */
	public function autoConvert( $text, $toVariant = false ) {
		wfProfileIn( __METHOD__ );

		$this->loadTables();

		if ( !$toVariant ) {
			$toVariant = $this->getPreferredVariant();
			if ( !$toVariant ) {
				wfProfileOut( __METHOD__ );
				return $text;
			}
		}

		/* we convert everything except:
		   1. html markups (anything between < and >)
		   2. html entities
		   3. place holders created by the parser
		*/
		global $wgParser;
		if ( isset( $wgParser ) && $wgParser->UniqPrefix() != '' ) {
			$marker = '|' . $wgParser->UniqPrefix() . '[\-a-zA-Z0-9]+';
		} else {
			$marker = '';
		}

		// this one is needed when the text is inside an html markup
		$htmlfix = '|<[^>]+$|^[^<>]*>';

		// disable convert to variants between <code></code> tags
		$codefix = '<code>.+?<\/code>|';
		// disable convertsion of <script type="text/javascript"> ... </script>
		$scriptfix = '<script.*?>.*?<\/script>|';
		// disable conversion of <pre xxxx> ... </pre>
		$prefix = '<pre.*?>.*?<\/pre>|';

		$reg = '/' . $codefix . $scriptfix . $prefix .
			'<[^>]+>|&[a-zA-Z#][a-z0-9]+;' . $marker . $htmlfix . '/s';

		$matches = preg_split( $reg, $text, - 1, PREG_SPLIT_OFFSET_CAPTURE );

		$m = array_shift( $matches );

		$ret = $this->translate( $m[0], $toVariant );
		$mstart = $m[1] + strlen( $m[0] );

		// enable convertsion of '<img alt="xxxx" ... '
		// or '<span title="xxxx" ... '
		$captionpattern	 = '/\s(title|alt)\s*=\s*"([\s\S]*?)"/';

		$trtext = '';
		$trtextmark = "\0";
		$notrtext = array();
		foreach ( $matches as $m ) {
			$mark = substr( $text, $mstart, $m[1] - $mstart );
			$mark = preg_replace_callback( $captionpattern,
										   array( &$this, 'captionConvert' ),
										   $mark );
			// Let's convert the trtext only once,
			// it would give us more performance improvement
			$notrtext[] = $mark;
			$trtext .= $m[0] . $trtextmark;
			$mstart = $m[1] + strlen( $m[0] );
		}
		$notrtext[] = '';
		$trtext = $this->translate( $trtext, $toVariant );
		$trtext = StringUtils::explode( $trtextmark, $trtext );
		foreach ( $trtext as $t ) {
			$ret .= array_shift( $notrtext );
			$ret .= $t;
		}
		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * Translate a string to a variant.
	 * Doesn't parse rules or do any of that other stuff, for that use
	 * convert() or convertTo().
	 *
	 * @param $text String: text to convert
	 * @param $variant String: variant language code
	 * @return String: translated text
	 */
	protected function translate( $text, $variant ) {
		wfProfileIn( __METHOD__ );
		// If $text is empty or only includes spaces, do nothing
		// Otherwise translate it
		if ( trim( $text ) ) {
			$this->loadTables();
			$text = $this->mTables[$variant]->replace( $text );
		}
		wfProfileOut( __METHOD__ );
		return $text;
	}

	/**
	 * Call translate() to convert text to all valid variants.
	 *
	 * @param $text String: the text to be converted
	 * @return Array: variant => converted text
	 */
	public function autoConvertToAllVariants( $text ) {
		wfProfileIn( __METHOD__ );
		$this->loadTables();

		$ret = array();
		foreach ( $this->mVariants as $variant ) {
			$ret[$variant] = $this->translate( $text, $variant );
		}

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * Convert link text to all valid variants.
	 * In the first, this function only convert text outside the
	 * "-{" "}-" markups. Since the "{" and "}" are not allowed in
	 * titles, the text will get all converted always.
	 * So I removed this feature and deprecated the function.
	 *
	 * @param $text String: the text to be converted
	 * @return Array: variant => converted text
	 * @deprecated Use autoConvertToAllVariants() instead
	 */
	public function convertLinkToAllVariants( $text ) {
		return $this->autoConvertToAllVariants( $text );
	}

	/**
	 * Apply manual conversion rules.
	 *
	 * @param $convRule Object: Object of ConverterRule
	 */
	protected function applyManualConv( $convRule ) {
		// Use syntax -{T|zh-cn:TitleCN; zh-tw:TitleTw}- to custom
		// title conversion.
		// Bug 24072: $mConvRuleTitle was overwritten by other manual
		// rule(s) not for title, this breaks the title conversion.
		$newConvRuleTitle = $convRule->getTitle();
		if ( $newConvRuleTitle ) {
			// So I add an empty check for getTitle()
			$this->mConvRuleTitle = $newConvRuleTitle;
		}

		// merge/remove manual conversion rules to/from global table
		$convTable = $convRule->getConvTable();
		$action = $convRule->getRulesAction();
		foreach ( $convTable as $variant => $pair ) {
			if ( !$this->validateVariant( $variant ) ) {
				continue;
			}

			if ( $action == 'add' ) {
				foreach ( $pair as $from => $to ) {
					// to ensure that $from and $to not be left blank
					// so $this->translate() could always return a string
					if ( $from || $to ) {
						// more efficient than array_merge(), about 2.5 times.
						$this->mTables[$variant]->setPair( $from, $to );
					}
				}
			} elseif ( $action == 'remove' ) {
				$this->mTables[$variant]->removeArray( $pair );
			}
		}
	}

	/**
	 * Auto convert a Title object to a readable string in the
	 * preferred variant.
	 *
	 *@param $title Object: a object of Title
	 *@return String: converted title text
	 */
	public function convertTitle( $title ) {
		$variant = $this->getPreferredVariant();
		$index = $title->getNamespace();
		if ( $index === NS_MAIN ) {
			$text = '';
		} else {
			// first let's check if a message has given us a converted name
			$nsConvKey = 'conversion-ns' . $index;
			if ( !wfEmptyMsg( $nsConvKey ) ) {
				$text = wfMsgForContentNoTrans( $nsConvKey );
			} else {
				// the message does not exist, try retrieve it from the current
				// variant's namespace names.
				$langObj = $this->mLangObj->factory( $variant );
				$text = $langObj->getFormattedNsText( $index );
			}
			$text .= ':';
		}
		$text .= $title->getText();
		$text = $this->translate( $text, $variant );
		return $text;
	}

	/**
	 * Convert text to different variants of a language. The automatic
	 * conversion is done in autoConvert(). Here we parse the text
	 * marked with -{}-, which specifies special conversions of the
	 * text that can not be accomplished in autoConvert().
	 *
	 * Syntax of the markup:
	 * -{code1:text1;code2:text2;...}-  or
	 * -{flags|code1:text1;code2:text2;...}-  or
	 * -{text}- in which case no conversion should take place for text
	 *
	 * @param $text String: text to be converted
	 * @return String: converted text
	 */
	public function convert( $text ) {
		$variant = $this->getPreferredVariant();
		return $this->convertTo( $text, $variant );
	}

	/**
	 * Same as convert() except a extra parameter to custom variant.
	 *
	 * @param $text String: text to be converted
	 * @param $variant String: the target variant code
	 * @return String: converted text
	 */
	public function convertTo( $text, $variant ) {
		global $wgDisableLangConversion;
		if ( $wgDisableLangConversion ) return $text;
		return $this->recursiveConvertTopLevel( $text, $variant );
	}

	/**
	 * Recursively convert text on the outside. Allow to use nested
	 * markups to custom rules.
	 *
	 * @param $text String: text to be converted
	 * @param $variant String: the target variant code
	 * @param $depth Integer: depth of recursion
	 * @return String: converted text
	 */
	protected function recursiveConvertTopLevel( $text, $variant, $depth = 0 ) {
		$startPos = 0;
		$out = '';
		$length = strlen( $text );
		while ( $startPos < $length ) {
			$pos = strpos( $text, '-{', $startPos );

			if ( $pos === false ) {
				// No more markup, append final segment
				$out .= $this->autoConvert( substr( $text, $startPos ), $variant );
				return $out;
			}

			// Markup found
			// Append initial segment
			$out .= $this->autoConvert( substr( $text, $startPos, $pos - $startPos ), $variant );

			// Advance position
			$startPos = $pos;

			// Do recursive conversion
			$out .= $this->recursiveConvertRule( $text, $variant, $startPos, $depth + 1 );
		}

		return $out;
	}

	/**
	 * Recursively convert text on the inside.
	 *
	 * @param $text String: text to be converted
	 * @param $variant String: the target variant code
	 * @param $depth Integer: depth of recursion
	 * @return String: converted text
	 */
	protected function recursiveConvertRule( $text, $variant, &$startPos, $depth = 0 ) {
		// Quick sanity check (no function calls)
		if ( $text[$startPos] !== '-' || $text[$startPos + 1] !== '{' ) {
			throw new MWException( __METHOD__ . ': invalid input string' );
		}

		$startPos += 2;
		$inner = '';
		$warningDone = false;
		$length = strlen( $text );

		while ( $startPos < $length ) {
			$m = false;
			preg_match( '/-\{|\}-/', $text, $m,  PREG_OFFSET_CAPTURE, $startPos );
			if ( !$m ) {
				// Unclosed rule
				break;
			}

			$token = $m[0][0];
			$pos = $m[0][1];

			// Markup found
			// Append initial segment
			$inner .= substr( $text, $startPos, $pos - $startPos );

			// Advance position
			$startPos = $pos;

			switch ( $token ) {
				case '-{':
					// Check max depth
					if ( $depth >= $this->mMaxDepth ) {
						$inner .= '-{';
						if ( !$warningDone ) {
							$inner .= '<span class="error">' .
								wfMsgForContent( 'language-converter-depth-warning',
									$this->mMaxDepth ) .
								'</span>';
							$warningDone = true;
						}
						$startPos += 2;
						continue;
					}
					// Recursively parse another rule
					$inner .= $this->recursiveConvertRule( $text, $variant, $startPos, $depth + 1 );
					break;
				case '}-':
					// Apply the rule
					$startPos += 2;
					$rule = new ConverterRule( $inner, $this );
					$rule->parse( $variant );
					$this->applyManualConv( $rule );
					return $rule->getDisplay();
				default:
					throw new MWException( __METHOD__ . ': invalid regex match' );
			}
		}

		// Unclosed rule
		if ( $startPos < $length ) {
			$inner .= substr( $text, $startPos );
		}
		$startPos = $length;
		return '-{' . $this->autoConvert( $inner, $variant );
	}

	/**
	 * If a language supports multiple variants, it is
	 * possible that non-existing link in one variant
	 * actually exists in another variant. This function
	 * tries to find it. See e.g. LanguageZh.php
	 *
	 * @param $link String: the name of the link
	 * @param $nt Mixed: the title object of the link
	 * @param $ignoreOtherCond Boolean: to disable other conditions when
	 *		we need to transclude a template or update a category's link
	 * @return Null, the input parameters may be modified upon return
	 */
	public function findVariantLink( &$link, &$nt, $ignoreOtherCond = false ) {
		# If the article has already existed, there is no need to
		# check it again, otherwise it may cause a fault.
		if ( is_object( $nt ) && $nt->exists() ) {
			return;
		}

		global $wgDisableLangConversion, $wgDisableTitleConversion, $wgRequest,
			$wgUser;
		$isredir = $wgRequest->getText( 'redirect', 'yes' );
		$action = $wgRequest->getText( 'action' );
		$linkconvert = $wgRequest->getText( 'linkconvert', 'yes' );
		$disableLinkConversion = $wgDisableLangConversion
			|| $wgDisableTitleConversion;
		$linkBatch = new LinkBatch();

		$ns = NS_MAIN;

		if ( $disableLinkConversion ||
			 ( !$ignoreOtherCond &&
			   ( $isredir == 'no'
				 || $action == 'edit'
				 || $action == 'submit'
				 || $linkconvert == 'no'
				 || $wgUser->getOption( 'noconvertlink' ) == 1 ) ) ) {
			return;
		}

		if ( is_object( $nt ) ) {
			$ns = $nt->getNamespace();
		}

		$variants = $this->autoConvertToAllVariants( $link );
		if ( !$variants ) { // give up
			return;
		}

		$titles = array();

		foreach ( $variants as $v ) {
			if ( $v != $link ) {
				$varnt = Title::newFromText( $v, $ns );
				if ( !is_null( $varnt ) ) {
					$linkBatch->addObj( $varnt );
					$titles[] = $varnt;
				}
			}
		}

		// fetch all variants in single query
		$linkBatch->execute();

		foreach ( $titles as $varnt ) {
			if ( $varnt->getArticleID() > 0 ) {
				$nt = $varnt;
				$link = $varnt->getText();
				break;
			}
		}
	}

	/**
	 * Returns language specific hash options.
	 */
	public function getExtraHashOptions() {
		$variant = $this->getPreferredVariant();
		return '!' . $variant ;
	}

	/**
	 * Load default conversion tables.
	 * This method must be implemented in derived class.
	 *
	 * @private
	 */
	function loadDefaultTables() {
		$name = get_class( $this );
		wfDie( "Must implement loadDefaultTables() method in class $name" );
	}

	/**
	 * Load conversion tables either from the cache or the disk.
	 * @private
	 */
	function loadTables( $fromcache = true ) {
		if ( $this->mTablesLoaded ) {
			return;
		}
		global $wgMemc;
		wfProfileIn( __METHOD__ );
		$this->mTablesLoaded = true;
		$this->mTables = false;
		if ( $fromcache ) {
			wfProfileIn( __METHOD__ . '-cache' );
			$this->mTables = $wgMemc->get( $this->mCacheKey );
			wfProfileOut( __METHOD__ . '-cache' );
		}
		if ( !$this->mTables
			 || !array_key_exists( self::CACHE_VERSION_KEY, $this->mTables ) ) {
			wfProfileIn( __METHOD__ . '-recache' );
			// not in cache, or we need a fresh reload.
			// we will first load the default tables
			// then update them using things in MediaWiki:Zhconversiontable/*
			$this->loadDefaultTables();
			foreach ( $this->mVariants as $var ) {
				$cached = $this->parseCachedTable( $var );
				$this->mTables[$var]->mergeArray( $cached );
			}

			$this->postLoadTables();
			$this->mTables[self::CACHE_VERSION_KEY] = true;

			$wgMemc->set( $this->mCacheKey, $this->mTables, 43200 );
			wfProfileOut( __METHOD__ . '-recache' );
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Hook for post processig after conversion tables are loaded.
	 *
	 */
	function postLoadTables() { }

	/**
	 * Reload the conversion tables.
	 *
	 * @private
	 */
	function reloadTables() {
		if ( $this->mTables ) {
			unset( $this->mTables );
		}
		$this->mTablesLoaded = false;
		$this->loadTables( false );
	}


	/**
	 * Parse the conversion table stored in the cache.
	 *
	 * The tables should be in blocks of the following form:
	 *		-{
	 *			word => word ;
	 *			word => word ;
	 *			...
	 *		}-
	 *
	 *	To make the tables more manageable, subpages are allowed
	 *	and will be parsed recursively if $recursive == true.
	 *
	 */
	function parseCachedTable( $code, $subpage = '', $recursive = true ) {
		static $parsed = array();

		$key = 'Conversiontable/' . $code;
		if ( $subpage ) {
			$key .= '/' . $subpage;
		}
		if ( array_key_exists( $key, $parsed ) ) {
			return array();
		}

		if ( strpos( $code, '/' ) === false ) {
			$txt = MessageCache::singleton()->get( 'Conversiontable', true, $code );
			if ( $txt === false ) {
				# FIXME: this method doesn't seem to be expecting
				# this possible outcome...
				$txt = '&lt;Conversiontable&gt;';
			}
		} else {
			$title = Title::makeTitleSafe( NS_MEDIAWIKI,
										   "Conversiontable/$code" );
			if ( $title && $title->exists() ) {
				$article = new Article( $title );
				$txt = $article->getContents();
			} else {
				$txt = '';
			}
		}

		// get all subpage links of the form
		// [[MediaWiki:conversiontable/zh-xx/...|...]]
		$linkhead = $this->mLangObj->getNsText( NS_MEDIAWIKI ) .
			':Conversiontable';
		$subs = StringUtils::explode( '[[', $txt );
		$sublinks = array();
		foreach ( $subs as $sub ) {
			$link = explode( ']]', $sub, 2 );
			if ( count( $link ) != 2 ) {
				continue;
			}
			$b = explode( '|', $link[0], 2 );
			$b = explode( '/', trim( $b[0] ), 3 );
			if ( count( $b ) == 3 ) {
				$sublink = $b[2];
			} else {
				$sublink = '';
			}

			if ( $b[0] == $linkhead && $b[1] == $code ) {
				$sublinks[] = $sublink;
			}
		}

		// parse the mappings in this page
		$blocks = StringUtils::explode( '-{', $txt );
		$ret = array();
		$first = true;
		foreach ( $blocks as $block ) {
			if ( $first ) {
				// Skip the part before the first -{
				$first = false;
				continue;
			}
			$mappings = explode( '}-', $block, 2 );
			$stripped = str_replace( array( "'", '"', '*', '#' ), '',
									 $mappings[0] );
			$table = StringUtils::explode( ';', $stripped );
			foreach ( $table as $t ) {
				$m = explode( '=>', $t, 3 );
				if ( count( $m ) != 2 )
					continue;
				// trim any trailling comments starting with '//'
				$tt = explode( '//', $m[1], 2 );
				$ret[trim( $m[0] )] = trim( $tt[0] );
			}
		}
		$parsed[$key] = true;

		// recursively parse the subpages
		if ( $recursive ) {
			foreach ( $sublinks as $link ) {
				$s = $this->parseCachedTable( $code, $link, $recursive );
				$ret = array_merge( $ret, $s );
			}
		}

		if ( $this->mUcfirst ) {
			foreach ( $ret as $k => $v ) {
				$ret[$this->mLangObj->ucfirst( $k )] = $this->mLangObj->ucfirst( $v );
			}
		}
		return $ret;
	}

	/**
	 * Enclose a string with the "no conversion" tag. This is used by
	 * various functions in the Parser.
	 *
	 * @param $text String: text to be tagged for no conversion
	 * @param $noParse Unused (?)
	 * @return String: the tagged text
	 */
	public function markNoConversion( $text, $noParse = false ) {
		# don't mark if already marked
		if ( strpos( $text, '-{' ) || strpos( $text, '}-' ) ) {
			return $text;
		}

		$ret = "-{R|$text}-";
		return $ret;
	}

	/**
	 * Convert the sorting key for category links. This should make different
	 * keys that are variants of each other map to the same key.
	 */
	function convertCategoryKey( $key ) {
		return $key;
	}

	/**
	 * Hook to refresh the cache of conversion tables when
	 * MediaWiki:conversiontable* is updated.
	 * @private
	 */
	function OnArticleSaveComplete( $article, $user, $text, $summary, $isminor,
			$iswatch, $section, $flags, $revision ) {
		$titleobj = $article->getTitle();
		if ( $titleobj->getNamespace() == NS_MEDIAWIKI ) {
			$title = $titleobj->getDBkey();
			$t = explode( '/', $title, 3 );
			$c = count( $t );
			if ( $c > 1 && $t[0] == 'Conversiontable' ) {
				if ( $this->validateVariant( $t[1] ) ) {
					$this->reloadTables();
				}
			}
		}
		return true;
	}

	/**
	 * Armour rendered math against conversion.
	 * Escape special chars in parsed math text.(in most cases are img elements)
	 */
	public function armourMath( $text ) {
		// convert '-{' and '}-' to '-&#123;' and '&#125;-' to prevent
		// any unwanted markup appearing in the math image tag.
		$text = strtr( $text, array( '-{' => '-&#123;', '}-' => '&#125;-' ) );
		return $text;
	}

	/**
	 * Get the cached separator pattern for ConverterRule::parseRules()
	 */
	function getVarSeparatorPattern() {
		if ( is_null( $this->mVarSeparatorPattern ) ) {
			// varsep_pattern for preg_split:
			// text should be splited by ";" only if a valid variant
			// name exist after the markup, for example:
			//  -{zh-hans:<span style="font-size:120%;">xxx</span>;zh-hant:\
			//	<span style="font-size:120%;">yyy</span>;}-
			// we should split it as:
			//  array(
			//	  [0] => 'zh-hans:<span style="font-size:120%;">xxx</span>'
			//	  [1] => 'zh-hant:<span style="font-size:120%;">yyy</span>'
			//	  [2] => ''
			//	 )
			$pat = '/;\s*(?=';
			foreach ( $this->mVariants as $variant ) {
				// zh-hans:xxx;zh-hant:yyy
				$pat .= $variant . '\s*:|';
				// xxx=>zh-hans:yyy; xxx=>zh-hant:zzz
				$pat .= '[^;]*?=>\s*' . $variant . '\s*:|';
			}
			$pat .= '\s*$)/';
			$this->mVarSeparatorPattern = $pat;
		}
		return $this->mVarSeparatorPattern;
	}
}

/**
 * Parser for rules of language conversion , parse rules in -{ }- tag.
 * @ingroup Language
 * @author fdcn <fdcn64@gmail.com>, PhiLiP <philip.npc@gmail.com>
 */
class ConverterRule {
	var $mText; // original text in -{text}-
	var $mConverter; // LanguageConverter object
	var $mManualCodeError = '<strong class="error">code error!</strong>';
	var $mRuleDisplay = '';
	var $mRuleTitle = false;
	var $mRules = '';// string : the text of the rules
	var $mRulesAction = 'none';
	var $mFlags = array();
	var $mVariantFlags = array();
	var $mConvTable = array();
	var $mBidtable = array();// array of the translation in each variant
	var $mUnidtable = array();// array of the translation in each variant

	/**
	 * Constructor
	 *
	 * @param $text String: the text between -{ and }-
	 * @param $converter LanguageConverter object
	 */
	public function __construct( $text, $converter ) {
		$this->mText = $text;
		$this->mConverter = $converter;
	}

	/**
	 * Check if variants array in convert array.
	 *
	 * @param $variants Array or string: variant language code
	 * @return String: translated text
	 */
	public function getTextInBidtable( $variants ) {
		$variants = (array)$variants;
		if ( !$variants ) {
			return false;
		}
		foreach ( $variants as $variant ) {
			if ( isset( $this->mBidtable[$variant] ) ) {
				return $this->mBidtable[$variant];
			}
		}
		return false;
	}

	/**
	 * Parse flags with syntax -{FLAG| ... }-
	 * @private
	 */
	function parseFlags() {
		$text = $this->mText;
		$flags = array();
		$variantFlags = array();

		$sepPos = strpos( $text, '|' );
		if ( $sepPos !== false ) {
			$validFlags = $this->mConverter->mFlags;
			$f = StringUtils::explode( ';', substr( $text, 0, $sepPos ) );
			foreach ( $f as $ff ) {
				$ff = trim( $ff );
				if ( isset( $validFlags[$ff] ) ) {
					$flags[$validFlags[$ff]] = true;
				}
			}
			$text = strval( substr( $text, $sepPos + 1 ) );
		}

		if ( !$flags ) {
			$flags['S'] = true;
		} elseif ( isset( $flags['R'] ) ) {
			$flags = array( 'R' => true );// remove other flags
		} elseif ( isset( $flags['N'] ) ) {
			$flags = array( 'N' => true );// remove other flags
		} elseif ( isset( $flags['-'] ) ) {
			$flags = array( '-' => true );// remove other flags
		} elseif ( count( $flags ) == 1 && isset( $flags['T'] ) ) {
			$flags['H'] = true;
		} elseif ( isset( $flags['H'] ) ) {
			// replace A flag, and remove other flags except T
			$temp = array( '+' => true, 'H' => true );
			if ( isset( $flags['T'] ) ) {
				$temp['T'] = true;
			}
			if ( isset( $flags['D'] ) ) {
				$temp['D'] = true;
			}
			$flags = $temp;
		} else {
			if ( isset( $flags['A'] ) ) {
				$flags['+'] = true;
				$flags['S'] = true;
			}
			if ( isset( $flags['D'] ) ) {
				unset( $flags['S'] );
			}
			// try to find flags like "zh-hans", "zh-hant"
			// allow syntaxes like "-{zh-hans;zh-hant|XXXX}-"
			$variantFlags = array_intersect( array_keys( $flags ), $this->mConverter->mVariants );
			if ( $variantFlags ) {
				$variantFlags = array_flip( $variantFlags );
				$flags = array();
			}
		}
		$this->mVariantFlags = $variantFlags;
		$this->mRules = $text;
		$this->mFlags = $flags;
	}

	/**
	 * Generate conversion table.
	 * @private
	 */
	function parseRules() {
		$rules = $this->mRules;
		$bidtable = array();
		$unidtable = array();
		$variants = $this->mConverter->mVariants;
		$varsep_pattern = $this->mConverter->getVarSeparatorPattern();

		$choice = preg_split( $varsep_pattern, $rules );

		foreach ( $choice as $c ) {
			$v  = explode( ':', $c, 2 );
			if ( count( $v ) != 2 ) {
				// syntax error, skip
				continue;
			}
			$to = trim( $v[1] );
			$v  = trim( $v[0] );
			$u  = explode( '=>', $v, 2 );
			// if $to is empty, strtr() could return a wrong result
			if ( count( $u ) == 1 && $to && in_array( $v, $variants ) ) {
				$bidtable[$v] = $to;
			} elseif ( count( $u ) == 2 ) {
				$from = trim( $u[0] );
				$v	= trim( $u[1] );
				if ( array_key_exists( $v, $unidtable )
					 && !is_array( $unidtable[$v] )
					 && $to
					 && in_array( $v, $variants ) ) {
					$unidtable[$v] = array( $from => $to );
				} elseif ( $to && in_array( $v, $variants ) ) {
					$unidtable[$v][$from] = $to;
				}
			}
			// syntax error, pass
			if ( !isset( $this->mConverter->mVariantNames[$v] ) ) {
				$bidtable = array();
				$unidtable = array();
				break;
			}
		}
		$this->mBidtable = $bidtable;
		$this->mUnidtable = $unidtable;
	}

	/**
	 * @private
	 */
	function getRulesDesc() {
		$codesep = $this->mConverter->mDescCodeSep;
		$varsep = $this->mConverter->mDescVarSep;
		$text = '';
		foreach ( $this->mBidtable as $k => $v ) {
			$text .= $this->mConverter->mVariantNames[$k] . "$codesep$v$varsep";
		}
		foreach ( $this->mUnidtable as $k => $a ) {
			foreach ( $a as $from => $to ) {
				$text .= $from . '⇒' . $this->mConverter->mVariantNames[$k] .
					"$codesep$to$varsep";
			}
		}
		return $text;
	}

	/**
	 * Parse rules conversion.
	 * @private
	 */
	function getRuleConvertedStr( $variant ) {
		$bidtable = $this->mBidtable;
		$unidtable = $this->mUnidtable;

		if ( count( $bidtable ) + count( $unidtable ) == 0 ) {
			return $this->mRules;
		} else {
			// display current variant in bidirectional array
			$disp = $this->getTextInBidtable( $variant );
			// or display current variant in fallbacks
			if ( !$disp ) {
				$disp = $this->getTextInBidtable(
						$this->mConverter->getVariantFallbacks( $variant ) );
			}
			// or display current variant in unidirectional array
			if ( !$disp && array_key_exists( $variant, $unidtable ) ) {
				$disp = array_values( $unidtable[$variant] );
				$disp = $disp[0];
			}
			// or display frist text under disable manual convert
			if ( !$disp
				 && $this->mConverter->mManualLevel[$variant] == 'disable' ) {
				if ( count( $bidtable ) > 0 ) {
					$disp = array_values( $bidtable );
					$disp = $disp[0];
				} else {
					$disp = array_values( $unidtable );
					$disp = array_values( $disp[0] );
					$disp = $disp[0];
				}
			}
			return $disp;
		}
	}

	/**
	 * Generate conversion table for all text.
	 * @private
	 */
	function generateConvTable() {
		// Special case optimisation
		if ( !$this->mBidtable && !$this->mUnidtable ) {
			$this->mConvTable = array();
			return;
		}

		$bidtable = $this->mBidtable;
		$unidtable = $this->mUnidtable;
		$manLevel = $this->mConverter->mManualLevel;

		$vmarked = array();
		foreach ( $this->mConverter->mVariants as $v ) {
			/* for bidirectional array
				fill in the missing variants, if any,
				with fallbacks */
			if ( !isset( $bidtable[$v] ) ) {
				$variantFallbacks =
					$this->mConverter->getVariantFallbacks( $v );
				$vf = $this->getTextInBidtable( $variantFallbacks );
				if ( $vf ) {
					$bidtable[$v] = $vf;
				}
			}

			if ( isset( $bidtable[$v] ) ) {
				foreach ( $vmarked as $vo ) {
					// use syntax: -{A|zh:WordZh;zh-tw:WordTw}-
					// or -{H|zh:WordZh;zh-tw:WordTw}-
					// or -{-|zh:WordZh;zh-tw:WordTw}-
					// to introduce a custom mapping between
					// words WordZh and WordTw in the whole text
					if ( $manLevel[$v] == 'bidirectional' ) {
						$this->mConvTable[$v][$bidtable[$vo]] = $bidtable[$v];
					}
					if ( $manLevel[$vo] == 'bidirectional' ) {
						$this->mConvTable[$vo][$bidtable[$v]] = $bidtable[$vo];
					}
				}
				$vmarked[] = $v;
			}
			/*for unidirectional array fill to convert tables */
			if ( ( $manLevel[$v] == 'bidirectional' || $manLevel[$v] == 'unidirectional' )
				&& isset( $unidtable[$v] ) )
			{
				if ( isset( $this->mConvTable[$v] ) ) {
					$this->mConvTable[$v] = array_merge( $this->mConvTable[$v], $unidtable[$v] );
				} else {
					$this->mConvTable[$v] = $unidtable[$v];
				}
			}
		}
	}

	/**
	 * Parse rules and flags.
	 * @public
	 */
	function parse( $variant = NULL ) {
		if ( !$variant ) {
			$variant = $this->mConverter->getPreferredVariant();
		}

		$this->parseFlags();
		$flags = $this->mFlags;

		// convert to specified variant
		// syntax: -{zh-hans;zh-hant[;...]|<text to convert>}-
		if ( $this->mVariantFlags ) {
			// check if current variant in flags
			if ( isset( $this->mVariantFlags[$variant] ) ) {
				// then convert <text to convert> to current language
				$this->mRules = $this->mConverter->autoConvert( $this->mRules,
																$variant );
			} else { // if current variant no in flags,
				   // then we check its fallback variants.
				$variantFallbacks =
					$this->mConverter->getVariantFallbacks( $variant );
				foreach ( $variantFallbacks as $variantFallback ) {
					// if current variant's fallback exist in flags
					if ( isset( $this->mVariantFlags[$variantFallback] ) ) {
						// then convert <text to convert> to fallback language
						$this->mRules =
							$this->mConverter->autoConvert( $this->mRules,
															$variantFallback );
						break;
					}
				}
			}
			$this->mFlags = $flags = array( 'R' => true );
		}

		if ( !isset( $flags['R'] ) && !isset( $flags['N'] ) ) {
			// decode => HTML entities modified by Sanitizer::removeHTMLtags
			$this->mRules = str_replace( '=&gt;', '=>', $this->mRules );
			$this->parseRules();
		}
		$rules = $this->mRules;

		if ( !$this->mBidtable && !$this->mUnidtable ) {
			if ( isset( $flags['+'] ) || isset( $flags['-'] ) ) {
				// fill all variants if text in -{A/H/-|text} without rules
				foreach ( $this->mConverter->mVariants as $v ) {
					$this->mBidtable[$v] = $rules;
				}
			} elseif ( !isset( $flags['N'] ) && !isset( $flags['T'] ) ) {
				$this->mFlags = $flags = array( 'R' => true );
			}
		}

		$this->mRuleDisplay = false;
		foreach ( $flags as $flag => $unused ) {
			switch ( $flag ) {
				case 'R':
					// if we don't do content convert, still strip the -{}- tags
					$this->mRuleDisplay = $rules;
					break;
				case 'N':
					// process N flag: output current variant name
					$ruleVar = trim( $rules );
					if ( isset( $this->mConverter->mVariantNames[$ruleVar] ) ) {
						$this->mRuleDisplay = $this->mConverter->mVariantNames[$ruleVar];
					} else {
						$this->mRuleDisplay = '';
					}
					break;
				case 'D':
					// process D flag: output rules description
					$this->mRuleDisplay = $this->getRulesDesc();
					break;
				case 'H':
					// process H,- flag or T only: output nothing
					$this->mRuleDisplay = '';
					break;
				case '-':
					$this->mRulesAction = 'remove';
					$this->mRuleDisplay = '';
					break;
				case '+':
					$this->mRulesAction = 'add';
					$this->mRuleDisplay = '';
					break;
				case 'S':
					$this->mRuleDisplay = $this->getRuleConvertedStr( $variant );
					break;
				case 'T':
					$this->mRuleTitle = $this->getRuleConvertedStr( $variant );
					$this->mRuleDisplay = '';
					break;
				default:
					// ignore unknown flags (but see error case below)
			}
		}
		if ( $this->mRuleDisplay === false ) {
			$this->mRuleDisplay = $this->mManualCodeError;
		}

		$this->generateConvTable();
	}

	/**
	 * @public
	 */
	function hasRules() {
		// TODO:
	}

	/**
	 * Get display text on markup -{...}-
	 * @public
	 */
	function getDisplay() {
		return $this->mRuleDisplay;
	}

	/**
	 * Get converted title.
	 * @public
	 */
	function getTitle() {
		return $this->mRuleTitle;
	}

	/**
	 * Return how deal with conversion rules.
	 * @public
	 */
	function getRulesAction() {
		return $this->mRulesAction;
	}

	/**
	 * Get conversion table. ( bidirectional and unidirectional
	 * conversion table )
	 * @public
	 */
	function getConvTable() {
		return $this->mConvTable;
	}

	/**
	 * Get conversion rules string.
	 * @public
	 */
	function getRules() {
		return $this->mRules;
	}

	/**
	 * Get conversion flags.
	 * @public
	 */
	function getFlags() {
		return $this->mFlags;
	}
}

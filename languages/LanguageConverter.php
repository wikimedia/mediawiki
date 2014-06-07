<?php
/**
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

	/**
	 * languages supporting variants
	 * @since 1.20
	 * @var array
	 */
	static public $languagesWithVariants = array(
		'gan',
		'iu',
		'kk',
		'ku',
		'shi',
		'sr',
		'tg',
		'uz',
		'zh',
	);

	public $mMainLanguageCode;
	public $mVariants, $mVariantFallbacks, $mVariantNames;
	public $mTablesLoaded = false;
	public $mTables;
	// 'bidirectional' 'unidirectional' 'disable' for each variant
	public $mManualLevel;

	/**
	 * @var string memcached key name
	 */
	public $mCacheKey;

	public $mLangObj;
	public $mFlags;
	public $mDescCodeSep = ':', $mDescVarSep = ';';
	public $mUcfirst = false;
	public $mConvRuleTitle = false;
	public $mURLVariant;
	public $mUserVariant;
	public $mHeaderVariant;
	public $mMaxDepth = 10;
	public $mVarSeparatorPattern;

	const CACHE_VERSION_KEY = 'VERSION 7';

	/**
	 * Constructor
	 *
	 * @param Language $langobj
	 * @param string $maincode The main language code of this language
	 * @param array $variants The supported variants of this language
	 * @param array $variantfallbacks The fallback language of each variant
	 * @param array $flags Defining the custom strings that maps to the flags
	 * @param array $manualLevel Limit for supported variants
	 */
	public function __construct( $langobj, $maincode, $variants = array(),
								$variantfallbacks = array(), $flags = array(),
								$manualLevel = array() ) {
		global $wgDisabledVariants;
		$this->mLangObj = $langobj;
		$this->mMainLanguageCode = $maincode;
		$this->mVariants = array_diff( $variants, $wgDisabledVariants );
		$this->mVariantFallbacks = $variantfallbacks;
		$this->mVariantNames = Language::fetchLanguageNames();
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
			'H' => 'H',	  // add rule for convert code (but no display in placed code)
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
	 * @return array Contains all valid variants
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
	 * @param string $variant The language code of the variant
	 * @return string|array The code of the fallback language or the
	 *   main code if there is no fallback
	 */
	public function getVariantFallbacks( $variant ) {
		if ( isset( $this->mVariantFallbacks[$variant] ) ) {
			return $this->mVariantFallbacks[$variant];
		}
		return $this->mMainLanguageCode;
	}

	/**
	 * Get the title produced by the conversion rule.
	 * @return string The converted title text
	 */
	public function getConvRuleTitle() {
		return $this->mConvRuleTitle;
	}

	/**
	 * Get preferred language variant.
	 * @return string The preferred language code
	 */
	public function getPreferredVariant() {
		global $wgDefaultLanguageVariant, $wgUser;

		$req = $this->getURLVariant();

		if ( $wgUser->isLoggedIn() && !$req ) {
			$req = $this->getUserVariant();
		} elseif ( !$req ) {
			$req = $this->getHeaderVariant();
		}

		if ( $wgDefaultLanguageVariant && !$req ) {
			$req = $this->validateVariant( $wgDefaultLanguageVariant );
		}

		// This function, unlike the other get*Variant functions, is
		// not memoized (i.e. there return value is not cached) since
		// new information might appear during processing after this
		// is first called.
		if ( $this->validateVariant( $req ) ) {
			return $req;
		}
		return $this->mMainLanguageCode;
	}

	/**
	 * Get default variant.
	 * This function would not be affected by user's settings
	 * @return string The default variant code
	 */
	public function getDefaultVariant() {
		global $wgDefaultLanguageVariant;

		$req = $this->getURLVariant();

		if ( !$req ) {
			$req = $this->getHeaderVariant();
		}

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
	 * @param string $variant The variant to validate
	 * @return mixed Returns the variant if it is valid, null otherwise
	 */
	public function validateVariant( $variant = null ) {
		if ( $variant !== null && in_array( $variant, $this->mVariants ) ) {
			return $variant;
		}
		return null;
	}

	/**
	 * Get the variant specified in the URL
	 *
	 * @return mixed Variant if one found, false otherwise.
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

		$this->mURLVariant = $this->validateVariant( $ret );
		return $this->mURLVariant;
	}

	/**
	 * Determine if the user has a variant set.
	 *
	 * @return mixed Variant if one found, false otherwise.
	 */
	protected function getUserVariant() {
		global $wgUser, $wgContLang;

		// memoizing this function wreaks havoc on parserTest.php
		/*
		if ( $this->mUserVariant ) {
			return $this->mUserVariant;
		}
		*/

		// Get language variant preference from logged in users
		// Don't call this on stub objects because that causes infinite
		// recursion during initialisation
		if ( $wgUser->isLoggedIn() ) {
			if ( $this->mMainLanguageCode == $wgContLang->getCode() ) {
				$ret = $wgUser->getOption( 'variant' );
			} else {
				$ret = $wgUser->getOption( 'variant-' . $this->mMainLanguageCode );
			}
		} else {
			// figure out user lang without constructing wgLang to avoid
			// infinite recursion
			$ret = $wgUser->getOption( 'language' );
		}

		$this->mUserVariant = $this->validateVariant( $ret );
		return $this->mUserVariant;
	}

	/**
	 * Determine the language variant from the Accept-Language header.
	 *
	 * @return mixed Variant if one found, false otherwise.
	 */
	protected function getHeaderVariant() {
		global $wgRequest;

		if ( $this->mHeaderVariant ) {
			return $this->mHeaderVariant;
		}

		// see if some supported language variant is set in the
		// HTTP header.
		$languages = array_keys( $wgRequest->getAcceptLang() );
		if ( empty( $languages ) ) {
			return null;
		}

		$fallbackLanguages = array();
		foreach ( $languages as $language ) {
			$this->mHeaderVariant = $this->validateVariant( $language );
			if ( $this->mHeaderVariant ) {
				break;
			}

			// To see if there are fallbacks of current language.
			// We record these fallback variants, and process
			// them later.
			$fallbacks = $this->getVariantFallbacks( $language );
			if ( is_string( $fallbacks ) && $fallbacks !== $this->mMainLanguageCode ) {
				$fallbackLanguages[] = $fallbacks;
			} elseif ( is_array( $fallbacks ) ) {
				$fallbackLanguages =
					array_merge( $fallbackLanguages, $fallbacks );
			}
		}

		if ( !$this->mHeaderVariant ) {
			// process fallback languages now
			$fallback_languages = array_unique( $fallbackLanguages );
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
	 * Dictionary-based conversion.
	 * This function would not parse the conversion rules.
	 * If you want to parse rules, try to use convert() or
	 * convertTo().
	 *
	 * @param $text String the text to be converted
	 * @param $toVariant bool|string the target language code
	 * @return String the converted text
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

		if ( $this->guessVariant( $text, $toVariant ) ) {
			wfProfileOut( __METHOD__ );
			return $text;
		}

		/* we convert everything except:
		   1. HTML markups (anything between < and >)
		   2. HTML entities
		   3. placeholders created by the parser
		*/
		global $wgParser;
		if ( isset( $wgParser ) && $wgParser->UniqPrefix() != '' ) {
			$marker = '|' . $wgParser->UniqPrefix() . '[\-a-zA-Z0-9]+';
		} else {
			$marker = '';
		}

		// this one is needed when the text is inside an HTML markup
		$htmlfix = '|<[^>]+$|^[^<>]*>';

		// disable convert to variants between <code> tags
		$codefix = '<code>.+?<\/code>|';
		// disable conversion of <script> tags
		$scriptfix = '<script.*?>.*?<\/script>|';
		// disable conversion of <pre> tags
		$prefix = '<pre.*?>.*?<\/pre>|';

		$reg = '/' . $codefix . $scriptfix . $prefix .
			'<[^>]+>|&[a-zA-Z#][a-z0-9]+;' . $marker . $htmlfix . '/s';
		$startPos = 0;
		$sourceBlob = '';
		$literalBlob = '';

		// Guard against delimiter nulls in the input
		$text = str_replace( "\000", '', $text );

		$markupMatches = null;
		$elementMatches = null;
		while ( $startPos < strlen( $text ) ) {
			if ( preg_match( $reg, $text, $markupMatches, PREG_OFFSET_CAPTURE, $startPos ) ) {
				$elementPos = $markupMatches[0][1];
				$element = $markupMatches[0][0];
			} else {
				$elementPos = strlen( $text );
				$element = '';
			}

			// Queue the part before the markup for translation in a batch
			$sourceBlob .= substr( $text, $startPos, $elementPos - $startPos ) . "\000";

			// Advance to the next position
			$startPos = $elementPos + strlen( $element );

			// Translate any alt or title attributes inside the matched element
			if ( $element !== ''
				&& preg_match( '/^(<[^>\s]*)\s([^>]*)(.*)$/', $element, $elementMatches )
			) {
				$attrs = Sanitizer::decodeTagAttributes( $elementMatches[2] );
				$changed = false;
				foreach ( array( 'title', 'alt' ) as $attrName ) {
					if ( !isset( $attrs[$attrName] ) ) {
						continue;
					}
					$attr = $attrs[$attrName];
					// Don't convert URLs
					if ( !strpos( $attr, '://' ) ) {
						$attr = $this->recursiveConvertTopLevel( $attr, $toVariant );
					}

					// Remove HTML tags to avoid disrupting the layout
					$attr = preg_replace( '/<[^>]+>/', '', $attr );
					if ( $attr !== $attrs[$attrName] ) {
						$attrs[$attrName] = $attr;
						$changed = true;
					}
				}
				if ( $changed ) {
					$element = $elementMatches[1] . Html::expandAttributes( $attrs ) .
						$elementMatches[3];
				}
			}
			$literalBlob .= $element . "\000";
		}

		// Do the main translation batch
		$translatedBlob = $this->translate( $sourceBlob, $toVariant );

		// Put the output back together
		$translatedIter = StringUtils::explode( "\000", $translatedBlob );
		$literalIter = StringUtils::explode( "\000", $literalBlob );
		$output = '';
		while ( $translatedIter->valid() && $literalIter->valid() ) {
			$output .= $translatedIter->current();
			$output .= $literalIter->current();
			$translatedIter->next();
			$literalIter->next();
		}

		wfProfileOut( __METHOD__ );
		return $output;
	}

	/**
	 * Translate a string to a variant.
	 * Doesn't parse rules or do any of that other stuff, for that use
	 * convert() or convertTo().
	 *
	 * @param string $text Text to convert
	 * @param string $variant Variant language code
	 * @return string Translated text
	 */
	public function translate( $text, $variant ) {
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
	 * @param string $text The text to be converted
	 * @return array Variant => converted text
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
	 * Apply manual conversion rules.
	 *
	 * @param $convRule ConverterRule Object of ConverterRule
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
	 * @param Title $title A object of Title
	 * @return string Converted title text
	 */
	public function convertTitle( $title ) {
		$variant = $this->getPreferredVariant();
		$index = $title->getNamespace();
		if ( $index !== NS_MAIN ) {
			$text = $this->convertNamespace( $index, $variant ) . ':';
		} else {
			$text = '';
		}
		$text .= $this->translate( $title->getText(), $variant );
		return $text;
	}

	/**
	 * Get the namespace display name in the preferred variant.
	 *
	 * @param int $index Namespace id
	 * @param string|null $variant Variant code or null for preferred variant
	 * @return string Namespace name for display
	 */
	public function convertNamespace( $index, $variant = null ) {
		if ( $variant === null ) {
			$variant = $this->getPreferredVariant();
		}
		if ( $index === NS_MAIN ) {
			return '';
		} else {
			// First check if a message gives a converted name in the target variant.
			$nsConvMsg = wfMessage( 'conversion-ns' . $index )->inLanguage( $variant );
			if ( $nsConvMsg->exists() ) {
				return $nsConvMsg->plain();
			}
			// Then check if a message gives a converted name in content language
			// which needs extra translation to the target variant.
			$nsConvMsg = wfMessage( 'conversion-ns' . $index )->inContentLanguage();
			if ( $nsConvMsg->exists() ) {
				return $this->translate( $nsConvMsg->plain(), $variant );
			}
			// No message exists, retrieve it from the target variant's namespace names.
			$langObj = $this->mLangObj->factory( $variant );
			return $langObj->getFormattedNsText( $index );
		}
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
	 * @param string $text Text to be converted
	 * @return string Converted text
	 */
	public function convert( $text ) {
		$variant = $this->getPreferredVariant();
		return $this->convertTo( $text, $variant );
	}

	/**
	 * Same as convert() except a extra parameter to custom variant.
	 *
	 * @param string $text Text to be converted
	 * @param string $variant The target variant code
	 * @return string Converted text
	 */
	public function convertTo( $text, $variant ) {
		global $wgDisableLangConversion;
		if ( $wgDisableLangConversion ) {
			return $text;
		}
		// Reset converter state for a new converter run.
		$this->mConvRuleTitle = false;
		return $this->recursiveConvertTopLevel( $text, $variant );
	}

	/**
	 * Recursively convert text on the outside. Allow to use nested
	 * markups to custom rules.
	 *
	 * @param string $text Text to be converted
	 * @param string $variant The target variant code
	 * @param int $depth Depth of recursion
	 * @return string Converted text
	 */
	protected function recursiveConvertTopLevel( $text, $variant, $depth = 0 ) {
		$startPos = 0;
		$out = '';
		$length = strlen( $text );
		$shouldConvert = !$this->guessVariant( $text, $variant );

		while ( $startPos < $length ) {
			$pos = strpos( $text, '-{', $startPos );

			if ( $pos === false ) {
				// No more markup, append final segment
				$fragment = substr( $text, $startPos );
				$out .= $shouldConvert ? $this->autoConvert( $fragment, $variant ) : $fragment;
				return $out;
			}

			// Markup found
			// Append initial segment
			$fragment = substr( $text, $startPos, $pos - $startPos );
			$out .= $shouldConvert ? $this->autoConvert( $fragment, $variant ) : $fragment;

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
	 * @param string $text Text to be converted
	 * @param string $variant The target variant code
	 * @param int $startPos
	 * @param int $depth Depth of recursion
	 *
	 * @throws MWException
	 * @return string Converted text
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
			preg_match( '/-\{|\}-/', $text, $m, PREG_OFFSET_CAPTURE, $startPos );
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
								wfMessage( 'language-converter-depth-warning' )
									->numParams( $this->mMaxDepth )->inContentLanguage()->text() .
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
	 * If a language supports multiple variants, it is possible that
	 * non-existing link in one variant actually exists in another variant.
	 * This function tries to find it. See e.g. LanguageZh.php
	 *
	 * @param string $link The name of the link
	 * @param mixed $nt The title object of the link
	 * @param bool $ignoreOtherCond To disable other conditions when
	 *   we need to transclude a template or update a category's link
	 * @return void Null, the input parameters may be modified upon return
	 */
	public function findVariantLink( &$link, &$nt, $ignoreOtherCond = false ) {
		# If the article has already existed, there is no need to
		# check it again, otherwise it may cause a fault.
		if ( is_object( $nt ) && $nt->exists() ) {
			return;
		}

		global $wgDisableLangConversion, $wgDisableTitleConversion, $wgRequest;
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
					|| $linkconvert == 'no' ) ) ) {
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
	 *
	 * @return string
	 */
	public function getExtraHashOptions() {
		$variant = $this->getPreferredVariant();
		return '!' . $variant;
	}

	/**
	 * Guess if a text is written in a variant. This should be implemented in subclasses.
	 *
	 * @param string $text the text to be checked
	 * @param string $variant language code of the variant to be checked for
	 * @return bool true if $text appears to be written in $variant, false if not
	 *
	 * @author Nikola Smolenski <smolensk@eunet.rs>
	 * @since 1.19
	 */
	public function guessVariant( $text, $variant ) {
		return false;
	}

	/**
	 * Load default conversion tables.
	 * This method must be implemented in derived class.
	 *
	 * @private
	 * @throws MWException
	 */
	function loadDefaultTables() {
		$name = get_class( $this );
		throw new MWException( "Must implement loadDefaultTables() method in class $name" );
	}

	/**
	 * Load conversion tables either from the cache or the disk.
	 * @private
	 * @param bool $fromCache Load from memcached? Defaults to true.
	 */
	function loadTables( $fromCache = true ) {
		global $wgLangConvMemc;

		if ( $this->mTablesLoaded ) {
			return;
		}

		wfProfileIn( __METHOD__ );
		$this->mTablesLoaded = true;
		$this->mTables = false;
		if ( $fromCache ) {
			wfProfileIn( __METHOD__ . '-cache' );
			$this->mTables = $wgLangConvMemc->get( $this->mCacheKey );
			wfProfileOut( __METHOD__ . '-cache' );
		}
		if ( !$this->mTables || !array_key_exists( self::CACHE_VERSION_KEY, $this->mTables ) ) {
			wfProfileIn( __METHOD__ . '-recache' );
			// not in cache, or we need a fresh reload.
			// We will first load the default tables
			// then update them using things in MediaWiki:Conversiontable/*
			$this->loadDefaultTables();
			foreach ( $this->mVariants as $var ) {
				$cached = $this->parseCachedTable( $var );
				$this->mTables[$var]->mergeArray( $cached );
			}

			$this->postLoadTables();
			$this->mTables[self::CACHE_VERSION_KEY] = true;

			$wgLangConvMemc->set( $this->mCacheKey, $this->mTables, 43200 );
			wfProfileOut( __METHOD__ . '-recache' );
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Hook for post processing after conversion tables are loaded.
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
	 * To make the tables more manageable, subpages are allowed
	 * and will be parsed recursively if $recursive == true.
	 *
	 * @param string $code Language code
	 * @param string $subpage Subpage name
	 * @param bool $recursive Parse subpages recursively? Defaults to true.
	 *
	 * @return array
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

		$parsed[$key] = true;

		if ( $subpage === '' ) {
			$txt = MessageCache::singleton()->getMsgFromNamespace( $key, $code );
		} else {
			$txt = false;
			$title = Title::makeTitleSafe( NS_MEDIAWIKI, $key );
			if ( $title && $title->exists() ) {
				$revision = Revision::newFromTitle( $title );
				if ( $revision ) {
					if ( $revision->getContentModel() == CONTENT_MODEL_WIKITEXT ) {
						$txt = $revision->getContent( Revision::RAW )->getNativeData();
					}

					// @todo in the future, use a specialized content model, perhaps based on json!
				}
			}
		}

		# Nothing to parse if there's no text
		if ( $txt === false || $txt === null || $txt === '' ) {
			return array();
		}

		// get all subpage links of the form
		// [[MediaWiki:Conversiontable/zh-xx/...|...]]
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
			$stripped = str_replace( array( "'", '"', '*', '#' ), '', $mappings[0] );
			$table = StringUtils::explode( ';', $stripped );
			foreach ( $table as $t ) {
				$m = explode( '=>', $t, 3 );
				if ( count( $m ) != 2 ) {
					continue;
				}
				// trim any trailling comments starting with '//'
				$tt = explode( '//', $m[1], 2 );
				$ret[trim( $m[0] )] = trim( $tt[0] );
			}
		}

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
	 * @param string $text Text to be tagged for no conversion
	 * @param bool $noParse Unused
	 * @return string The tagged text
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
	 *
	 * @param $key string
	 *
	 * @return string
	 */
	function convertCategoryKey( $key ) {
		return $key;
	}

	/**
	 * Hook to refresh the cache of conversion tables when
	 * MediaWiki:Conversiontable* is updated.
	 * @private
	 *
	 * @param WikiPage $page
	 * @param User $user User object for the current user
	 * @param Content $content new page content
	 * @param string $summary edit summary of the edit
	 * @param bool $isMinor was the edit marked as minor?
	 * @param bool $isWatch did the user watch this page or not?
	 * @param string|int $section
	 * @param int $flags Bitfield
	 * @param Revision $revision new Revision object or null
	 * @return bool true
	 */
	function OnPageContentSaveComplete( $page, $user, $content, $summary, $isMinor,
			$isWatch, $section, $flags, $revision ) {
		$titleobj = $page->getTitle();
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
	 * Escape special chars in parsed math text. (in most cases are img elements)
	 *
	 * @param string $text Text to armour against conversion
	 * @return string Armoured text where { and } have been converted to
	 *   &#123; and &#125;
	 * @deprecated since 1.22 is no longer used
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

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
 * @author Zhengzhu Feng <zhengzhu@gmail.com>
 * @author fdcn <fdcn64@gmail.com>
 * @author shinjiman <shinjiman@gmail.com>
 * @author PhiLiP <philip.npc@gmail.com>
 */

namespace MediaWiki\Language;

use InvalidArgumentException;
use MediaWiki\Context\RequestContext;
use MediaWiki\Debug\DeprecationHelper;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\StubObject\StubUserLang;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use RuntimeException;
use UnexpectedValueException;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\StringUtils\StringUtils;

/**
 * Base class for multi-variant language conversion.
 *
 * @ingroup Language
 */
abstract class LanguageConverter implements ILanguageConverter {
	use DeprecationHelper;

	/**
	 * languages supporting variants
	 * @since 1.20
	 * @var string[]
	 * @phpcs-require-sorted-array
	 */
	public static $languagesWithVariants = [
		'ban',
		'crh',
		'en',
		'gan',
		'iu',
		'ku',
		'mni',
		'sh',
		'shi',
		'sr',
		'tg',
		'tly',
		'uz',
		'wuu',
		'zgh',
		'zh',
	];

	/**
	 * static default variant of languages supporting variants
	 * for use with DefaultOptionsLookup.php
	 * @since 1.40
	 * @var array<string,string>
	 * @phpcs-require-sorted-array
	 */
	public static $languagesWithStaticDefaultVariant = [
		'ban' => 'ban',
		'crh' => 'crh',
		'en' => 'en',
		'gan' => 'gan',
		'iu' => 'iu',
		'ku' => 'ku',
		'mni' => 'mni',
		'sh' => 'sh-latn',
		'shi' => 'shi',
		'sr' => 'sr',
		'tg' => 'tg',
		'tly' => 'tly',
		'uz' => 'uz',
		'wuu' => 'wuu',
		'zgh' => 'zgh',
		'zh' => 'zh',
	];

	/** @var bool */
	private $mTablesLoaded = false;
	/** @var ReplacementArray[] */
	protected $mTables = [];
	/** @var Language|StubUserLang */
	private $mLangObj;
	/** @var string|false */
	private $mConvRuleTitle = false;
	/** @var string|null */
	private $mURLVariant;
	/** @var string|null */
	private $mUserVariant;
	/** @var string|null */
	private $mHeaderVariant;
	/** @var int */
	private $mMaxDepth = 10;
	/** @var string|null */
	private $mVarSeparatorPattern;

	private const CACHE_VERSION_KEY = 'VERSION 7';

	/**
	 * @param Language|StubUserLang $langobj
	 */
	public function __construct( $langobj ) {
		$this->mLangObj = $langobj;
	}

	/**
	 * Get the language code with converter (the "main" language code).
	 * Page language code would be the same of the language code with converter.
	 * Note that this code might not be included as one of the variant languages.
	 * @since 1.36
	 *
	 * @return string
	 */
	abstract public function getMainCode(): string;

	/**
	 * Get static default variant.
	 * For use of specify the default variant form when it different from the
	 *  default "unconverted/mixed-variant form".
	 * @since 1.40
	 *
	 * @return string
	 */
	protected function getStaticDefaultVariant(): string {
		$code = $this->getMainCode();
		return self::$languagesWithStaticDefaultVariant[$code] ?? $code;
	}

	/**
	 * Get supported variants of the language.
	 * @since 1.36
	 *
	 * @return array
	 */
	abstract protected function getLanguageVariants(): array;

	/**
	 * Get language variants fallbacks.
	 * @since 1.36
	 *
	 * @return array
	 */
	abstract public function getVariantsFallbacks(): array;

	/**
	 * Get the strings that map to the flags.
	 * @since 1.36
	 *
	 * @return array
	 */
	final public function getFlags(): array {
		$defaultflags = [
			// 'S' show the converted text
			// '+' add rules for alltext
			// 'E' the flags have an error
			// these flags above are reserved for program
			'A' => 'A', // add rule for convert code (all text converted)
			'T' => 'T', // title convert
			'R' => 'R', // raw content
			'D' => 'D', // convert description (subclass implement)
			'-' => '-', // remove convert (not implement)
			'H' => 'H', // add rule for convert code (but no display in placed code)
			'N' => 'N', // current variant name
		];
		$flags = array_merge( $defaultflags, $this->getAdditionalFlags() );
		foreach ( $this->getVariants() as $v ) {
			$flags[$v] = $v;
		}
		return $flags;
	}

	/**
	 * Provides additional flags for converter. By default, it returns empty array and
	 * typically should be overridden by implementation of converter.
	 */
	protected function getAdditionalFlags(): array {
		return [];
	}

	/**
	 * Get manual level limit for supported variants.
	 * @since 1.36
	 *
	 * @return array
	 */
	final public function getManualLevel() {
		$manualLevel  = $this->getAdditionalManualLevel();
		$result = [];
		foreach ( $this->getVariants() as $v ) {
			if ( array_key_exists( $v, $manualLevel ) ) {
				$result[$v] = $manualLevel[$v];
			} else {
				$result[$v] = 'bidirectional';
			}
		}
		return $result;
	}

	/**
	 * Provides additional flags for converter. By default, this function returns an empty array and
	 * typically should be overridden by the implementation of converter.
	 * @since 1.36
	 *
	 * @return array
	 */
	protected function getAdditionalManualLevel(): array {
		return [];
	}

	/**
	 * Get desc code separator. By default returns ":", can be overridden by
	 * implementation of converter.
	 * @since 1.36
	 *
	 * @return string
	 */
	public function getDescCodeSeparator(): string {
		return ':';
	}

	/**
	 * Get desc var separator. By default returns ";", can be overridden by
	 * implementation of converter.
	 * @since 1.36
	 *
	 * @return string
	 */
	public function getDescVarSeparator(): string {
		return ';';
	}

	public function getVariantNames(): array {
		return MediaWikiServices::getInstance()
			->getLanguageNameUtils()
			->getLanguageNames();
	}

	/** @inheritDoc */
	final public function getVariants() {
		$disabledVariants = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::DisabledVariants );
		return array_diff( $this->getLanguageVariants(), $disabledVariants );
	}

	/** @inheritDoc */
	public function getVariantFallbacks( $variant ) {
		return $this->getVariantsFallbacks()[$variant] ?? $this->getStaticDefaultVariant();
	}

	/** @inheritDoc */
	public function getConvRuleTitle() {
		return $this->mConvRuleTitle;
	}

	/** @inheritDoc */
	public function getPreferredVariant() {
		$req = $this->getURLVariant();

		$services = MediaWikiServices::getInstance();
		( new HookRunner( $services->getHookContainer() ) )->onGetLangPreferredVariant( $req );

		if ( !$req ) {
			$user = RequestContext::getMain()->getUser();
			// NOTE: For some calls there may not be a context user or session that is safe
			// to use, see (T235360)
			// Use case: During user autocreation, UserNameUtils::isUsable is called which uses interface
			// messages for reserved usernames.
			if ( $user->isSafeToLoad() && $user->isRegistered() ) {
				$req = $this->getUserVariant( $user );
			} else {
				$req = $this->getHeaderVariant();
			}
		}

		$defaultLanguageVariant = $services->getMainConfig()
			->get( MainConfigNames::DefaultLanguageVariant );
		if ( !$req && $defaultLanguageVariant ) {
			$req = $this->validateVariant( $defaultLanguageVariant );
		}

		$req = $this->validateVariant( $req );

		// This function, unlike the other get*Variant functions, is
		// not memoized (i.e., there return value is not cached) since
		// new information might appear during processing after this
		// is first called.
		return $req ?? $this->getStaticDefaultVariant();
	}

	/** @inheritDoc */
	public function getDefaultVariant() {
		$defaultLanguageVariant = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::DefaultLanguageVariant );

		$req = $this->getURLVariant() ?? $this->getHeaderVariant();

		if ( !$req && $defaultLanguageVariant ) {
			$req = $this->validateVariant( $defaultLanguageVariant );
		}

		return $req ?? $this->getStaticDefaultVariant();
	}

	/** @inheritDoc */
	public function validateVariant( $variant = null ) {
		if ( $variant === null ) {
			return null;
		}
		// Our internal variants are always lower-case; the variant we
		// are validating may have mixed cases.
		$variant = LanguageCode::replaceDeprecatedCodes( strtolower( $variant ) );
		if ( in_array( $variant, $this->getVariants() ) ) {
			return $variant;
		}
		// Browsers are supposed to use BCP 47 standard in the
		// Accept-Language header, but not all of our internal
		// mediawiki variant codes are BCP 47.  Map BCP 47 code
		// to our internal code.
		foreach ( $this->getVariants() as $v ) {
			// Case-insensitive match (BCP 47 is mixed-case)
			if ( strtolower( LanguageCode::bcp47( $v ) ) === $variant ) {
				return $v;
			}
		}
		return null;
	}

	/** @inheritDoc */
	public function getURLVariant() {
		if ( $this->mURLVariant ) {
			return $this->mURLVariant;
		}

		$request = RequestContext::getMain()->getRequest();
		// see if the preference is set in the request
		$ret = $request->getText( 'variant' );

		if ( !$ret ) {
			$ret = $request->getVal( 'uselang' );
		}

		$this->mURLVariant = $this->validateVariant( $ret );
		return $this->mURLVariant;
	}

	/**
	 * Determine if the user has a variant set.
	 *
	 * @param User $user
	 * @return string|null Variant if one found, null otherwise
	 */
	protected function getUserVariant( User $user ) {
		// This should only be called within the class after the user is known to be
		// safe to load and logged in, but check just in case.
		if ( !$user->isSafeToLoad() ) {
			return null;
		}

		if ( !$this->mUserVariant ) {
			$services = MediaWikiServices::getInstance();
			if ( $user->isRegistered() ) {
				// Get language variant preference from logged in users
				if (
					$this->getMainCode() ===
					$services->getContentLanguageCode()->toString()
				) {
					$optionName = 'variant';
				} else {
					$optionName = 'variant-' . $this->getMainCode();
				}
			} else {
				// figure out user lang without constructing wgLang to avoid
				// infinite recursion
				$optionName = 'language';
			}
			$ret = $services->getUserOptionsLookup()->getOption( $user, $optionName );

			$this->mUserVariant = $this->validateVariant( $ret );
		}

		return $this->mUserVariant;
	}

	/**
	 * Determine the language variant from the Accept-Language header.
	 *
	 * @return string|null Variant if one found, null otherwise
	 */
	protected function getHeaderVariant() {
		if ( $this->mHeaderVariant ) {
			return $this->mHeaderVariant;
		}

		$request = RequestContext::getMain()->getRequest();
		// See if some supported language variant is set in the
		// HTTP header.
		$languages = array_keys( $request->getAcceptLang() );
		if ( !$languages ) {
			return null;
		}

		$fallbackLanguages = [];
		foreach ( $languages as $language ) {
			$this->mHeaderVariant = $this->validateVariant( $language );
			if ( $this->mHeaderVariant ) {
				break;
			}

			// To see if there are fallbacks of current language.
			// We record these fallback variants, and process
			// them later.
			$fallbacks = $this->getVariantFallbacks( $language );
			if (
				is_string( $fallbacks ) &&
				$fallbacks !== $this->getStaticDefaultVariant()
			) {
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

	/** @inheritDoc */
	public function autoConvert( $text, $toVariant = false ) {
		$this->loadTables();

		if ( !$toVariant ) {
			$toVariant = $this->getPreferredVariant();
			if ( !$toVariant ) {
				return $text;
			}
		}

		if ( $this->guessVariant( $text, $toVariant ) ) {
			return $text;
		}
		/**
		 * We convert everything except:
		 * 1. HTML markups (anything between < and >)
		 * 2. HTML entities
		 * 3. placeholders created by the parser
		 * IMPORTANT: Beware of failure from pcre.backtrack_limit (T124404).
		 * Minimize the use of backtracking where possible.
		 */
		static $reg;
		if ( $reg === null ) {
			$marker = '|' . Parser::MARKER_PREFIX . '[^\x7f]++\x7f';

			// this one is needed when the text is inside an HTML markup
			$htmlfix = '|<[^>\004]++(?=\004$)|^[^<>]*+>';

			// Optimize for the common case where these tags have
			// few or no children. Thus try and possessively get as much as
			// possible, and only engage in backtracking when we hit a '<'.

			// disable convert to variants between <code> tags
			$codefix = '<code>[^<]*+(?:(?:(?!<\/code>).)[^<]*+)*+<\/code>|';
			// disable conversion of <script> tags
			$scriptfix = '<script[^>]*+>[^<]*+(?:(?:(?!<\/script>).)[^<]*+)*+<\/script>|';
			// disable conversion of <pre> tags
			$prefix = '<pre[^>]*+>[^<]*+(?:(?:(?!<\/pre>).)[^<]*+)*+<\/pre>|';
			// disable conversion of <math> tags
			$mathfix = '<math[^>]*+>[^<]*+(?:(?:(?!<\/math>).)[^<]*+)*+<\/math>|';
			// disable conversion of <svg> tags
			$svgfix = '<svg[^>]*+>[^<]*+(?:(?:(?!<\/svg>).)[^<]*+)*+<\/svg>|';
			// The "|.*+)" at the end, is in case we missed some part of html syntax,
			// we will fail securely (hopefully) by matching the rest of the string.
			$htmlFullTag = '<(?:[^>=]*+(?>[^>=]*+=\s*+(?:"[^"]*"|\'[^\']*\'|[^\'">\s]*+))*+[^>=]*+>|.*+)|';

			$reg = '/' . $codefix . $scriptfix . $prefix . $mathfix . $svgfix .
				$htmlFullTag .
				'&[a-zA-Z#][a-z0-9]++;' . $marker . $htmlfix . '|\004$/s';
		}
		$startPos = 0;
		$sourceBlob = '';
		$literalBlob = '';

		// Guard against delimiter nulls in the input
		// (should never happen: see T159174)
		$text = str_replace( "\000", '', $text );
		$text = str_replace( "\004", '', $text );

		$markupMatches = null;
		$elementMatches = null;

		// We add a marker (\004) at the end of text, to ensure we always match the
		// entire text (Otherwise, pcre.backtrack_limit might cause silent failure)
		$textWithMarker = $text . "\004";
		while ( $startPos < strlen( $text ) ) {
			if ( preg_match( $reg, $textWithMarker, $markupMatches, PREG_OFFSET_CAPTURE, $startPos ) ) {
				$elementPos = $markupMatches[0][1];
				$element = $markupMatches[0][0];
				if ( $element === "\004" ) {
					// We hit the end.
					$elementPos = strlen( $text );
					$element = '';
				} elseif ( substr( $element, -1 ) === "\004" ) {
					// This can sometimes happen if we have
					// unclosed html tags. For example,
					// when converting a title attribute
					// during a recursive call that contains
					// a &lt; e.g. <div title="&lt;">.
					$element = substr( $element, 0, -1 );
				}
			} else {
				// If we hit here, then Language Converter could be tricked
				// into doing an XSS, so we refuse to translate.
				// If expected input manages to reach this code path,
				// we should consider it a bug.
				$log = LoggerFactory::getInstance( 'languageconverter' );
				$log->error( "Hit pcre.backtrack_limit in " . __METHOD__
					. ". Disabling language conversion for this page.",
					[
						"method" => __METHOD__,
						"variant" => $toVariant,
						"startOfText" => substr( $text, 0, 500 )
					]
				);
				return $text;
			}
			// Queue the part before the markup for translation in a batch
			$sourceBlob .= substr( $text, $startPos, $elementPos - $startPos ) . "\000";

			// Advance to the next position
			$startPos = $elementPos + strlen( $element );

			// Translate any alt or title attributes inside the matched element
			if ( $element !== ''
				&& preg_match( '/^(<[^>\s]*+)\s([^>]*+)(.*+)$/', $element, $elementMatches )
			) {
				// FIXME, this decodes entities, so if you have something
				// like <div title="foo&lt;bar"> the bar won't get
				// translated since after entity decoding it looks like
				// unclosed html and we call this method recursively
				// on attributes.
				$attrs = Sanitizer::decodeTagAttributes( $elementMatches[2] );
				// Ensure self-closing tags stay self-closing.
				$close = substr( $elementMatches[2], -1 ) === '/' ? ' /' : '';
				$changed = false;
				foreach ( [ 'title', 'alt' ] as $attrName ) {
					if ( !isset( $attrs[$attrName] ) ) {
						continue;
					}
					$attr = $attrs[$attrName];
					// Don't convert URLs
					if ( !str_contains( $attr, '://' ) ) {
						$attr = $this->recursiveConvertTopLevel( $attr, $toVariant );
					}

					if ( $attr !== $attrs[$attrName] ) {
						$attrs[$attrName] = $attr;
						$changed = true;
					}
				}
				if ( $changed ) {
					// @phan-suppress-next-line SecurityCheck-DoubleEscaped Explained above with decodeTagAttributes
					$element = $elementMatches[1] . Html::expandAttributes( $attrs ) .
						$close . $elementMatches[3];
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

		return $output;
	}

	/** @inheritDoc */
	public function translate( $text, $variant ) {
		// If $text is empty or only includes spaces, do nothing
		// Otherwise translate it
		if ( trim( $text ) ) {
			$this->loadTables();
			$text = $this->mTables[$variant]->replace( $text );
		}
		return $text;
	}

	/**
	 * @param string $text Text to convert
	 * @param string $variant Variant language code
	 * @return string Translated text
	 */
	protected function translateWithoutRomanNumbers( $text, $variant ) {
		$breaks = '[^\w\x80-\xff]';

		// regexp for roman numbers
		// Lookahead assertion ensures $roman doesn't match the empty string
		$roman = '(?=[MDCLXVI])M{0,4}(C[DM]|D?C{0,3})(X[LC]|L?X{0,3})(I[VX]|V?I{0,3})';

		$reg = '/^' . $roman . '$|^' . $roman . $breaks . '|' . $breaks
			. $roman . '$|' . $breaks . $roman . $breaks . '/';

		$matches = preg_split( $reg, $text, -1, PREG_SPLIT_OFFSET_CAPTURE );

		$m = array_shift( $matches );
		$this->loadTables();
		if ( !isset( $this->mTables[$variant] ) ) {
			throw new RuntimeException( "Broken variant table: "
				. implode( ',', array_keys( $this->mTables ) ) );
		}
		$ret = $this->mTables[$variant]->replace( $m[0] );
		$mstart = (int)$m[1] + strlen( $m[0] );
		foreach ( $matches as $m ) {
			$ret .= substr( $text, $mstart, (int)$m[1] - $mstart );
			$ret .= $this->translate( $m[0], $variant );
			$mstart = (int)$m[1] + strlen( $m[0] );
		}

		return $ret;
	}

	/** @inheritDoc */
	public function autoConvertToAllVariants( $text ) {
		$this->loadTables();

		$ret = [];
		foreach ( $this->getVariants() as $variant ) {
			$ret[$variant] = $this->translate( $text, $variant );
		}

		return $ret;
	}

	/**
	 * Apply manual conversion rules.
	 */
	protected function applyManualConv( ConverterRule $convRule ) {
		// Use syntax -{T|zh-cn:TitleCN; zh-tw:TitleTw}- to custom
		// title conversion.
		// T26072: $mConvRuleTitle was overwritten by other manual
		// rule(s) not for title, this breaks the title conversion.
		$newConvRuleTitle = $convRule->getTitle();
		if ( $newConvRuleTitle !== false ) {
			// So I add an empty check for getTitle()
			$this->mConvRuleTitle = $newConvRuleTitle;
		}

		// merge/remove manual conversion rules to/from global table
		$convTable = $convRule->getConvTable();
		$action = $convRule->getRulesAction();
		foreach ( $convTable as $variant => $pair ) {
			$v = $this->validateVariant( $variant );
			if ( !$v ) {
				continue;
			}

			if ( $action == 'add' ) {
				// More efficient than array_merge(), about 2.5 times.
				foreach ( $pair as $from => $to ) {
					$this->mTables[$v]->setPair( $from, $to );
				}
			} elseif ( $action == 'remove' ) {
				$this->mTables[$v]->removeArray( $pair );
			}
		}
	}

	/** @inheritDoc */
	public function convertSplitTitle( $title ) {
		$variant = $this->getPreferredVariant();

		$index = $title->getNamespace();
		$nsText = $this->convertNamespace( $index, $variant );

		$name = str_replace( '_', ' ', $title->getDBKey() );
		$mainText = $this->translate( $name, $variant );

		return [ $nsText, ':', $mainText ];
	}

	/** @inheritDoc */
	public function convertTitle( $title ) {
		[ $nsText, $nsSeparator, $mainText ] = $this->convertSplitTitle( $title );
		return $nsText !== '' ?
			$nsText . $nsSeparator . $mainText :
			$mainText;
	}

	/** @inheritDoc */
	public function convertNamespace( $index, $variant = null ) {
		if ( $index === NS_MAIN ) {
			return '';
		}

		$variant ??= $this->getPreferredVariant();

		$cache = MediaWikiServices::getInstance()->getLocalServerObjectCache();
		$key = $cache->makeKey( 'languageconverter', 'namespace-text', $index, $variant );
		return $cache->getWithSetCallback(
			$key,
			BagOStuff::TTL_MINUTE,
			function () use ( $index, $variant ) {
				return $this->computeNsVariantText( $index, $variant );
			}
		);
	}

	/**
	 * @param int $index
	 * @param string|null $variant
	 * @return string
	 */
	private function computeNsVariantText( int $index, ?string $variant ): string {
		$nsVariantText = false;

		// First check if a message gives a converted name in the target variant.
		$nsConvMsg = wfMessage( 'conversion-ns' . $index )->inLanguage( $variant );
		if ( $nsConvMsg->exists() ) {
			$nsVariantText = $nsConvMsg->plain();
		}

		// Then check if a message gives a converted name in content language
		// which needs extra translation to the target variant.
		if ( $nsVariantText === false ) {
			$nsConvMsg = wfMessage( 'conversion-ns' . $index )->inContentLanguage();
			if ( $nsConvMsg->exists() ) {
				$nsVariantText = $this->translate( $nsConvMsg->plain(), $variant );
			}
		}

		if ( $nsVariantText === false ) {
			// No message exists, retrieve it from the target variant's namespace names.
			$mLangObj = MediaWikiServices::getInstance()
				->getLanguageFactory()
				->getLanguage( $variant );
			$nsVariantText = $mLangObj->getFormattedNsText( $index );
		}
		return $nsVariantText;
	}

	/** @inheritDoc */
	public function convert( $text ) {
		$variant = $this->getPreferredVariant();
		return $this->convertTo( $text, $variant );
	}

	/** @inheritDoc */
	public function convertTo( $text, $variant, bool $clearState = true ) {
		$languageConverterFactory = MediaWikiServices::getInstance()->getLanguageConverterFactory();
		if ( $languageConverterFactory->isConversionDisabled() ) {
			return $text;
		}
		// Reset converter state for a new converter run.
		if ( $clearState ) {
			$this->mConvRuleTitle = false;
		}
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
		$continue = true;

		$noScript = '<script.*?>.*?<\/script>(*SKIP)(*FAIL)';
		$noStyle = '<style.*?>.*?<\/style>(*SKIP)(*FAIL)';
		$noMath = '<math.*?>.*?<\/math>(*SKIP)(*FAIL)';
		$noSvg = '<svg.*?>.*?<\/svg>(*SKIP)(*FAIL)';
		// phpcs:ignore Generic.Files.LineLength
		$noHtml = '<(?:[^>=]*+(?>[^>=]*+=\s*+(?:"[^"]*"|\'[^\']*\'|[^\'">\s]*+))*+[^>=]*+>|.*+)(*SKIP)(*FAIL)';
		while ( $startPos < $length && $continue ) {
			$continue = preg_match(
				// Only match "-{" outside the html.
				"/$noScript|$noStyle|$noMath|$noSvg|$noHtml|-\{/",
				$text,
				$m,
				PREG_OFFSET_CAPTURE,
				$startPos
			);

			if ( !$continue ) {
				// No more markup, append final segment
				$fragment = substr( $text, $startPos );
				$out .= $shouldConvert ? $this->autoConvert( $fragment, $variant ) : $fragment;
				return $out;
			}

			// Offset of the match of the regex pattern.
			$pos = $m[0][1];

			// Append initial segment
			$fragment = substr( $text, $startPos, $pos - $startPos );
			$out .= $shouldConvert ? $this->autoConvert( $fragment, $variant ) : $fragment;
			// -{ marker found, not in attribute
			// Advance position up to -{ marker.
			$startPos = $pos;
			// Do recursive conversion
			// Note: This passes $startPos by reference, and advances it.
			$out .= $this->recursiveConvertRule( $text, $variant, $startPos, $depth + 1 );
		}
		return $out;
	}

	/**
	 * Recursively convert text on the inside.
	 *
	 * @param string $text Text to be converted
	 * @param string $variant The target variant code
	 * @param int &$startPos
	 * @param int $depth Depth of recursion
	 * @return string Converted text
	 */
	protected function recursiveConvertRule( $text, $variant, &$startPos, $depth = 0 ) {
		// Quick check (no function calls)
		if ( $text[$startPos] !== '-' || $text[$startPos + 1] !== '{' ) {
			throw new InvalidArgumentException( __METHOD__ . ': invalid input string' );
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
						break;
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
					throw new UnexpectedValueException( __METHOD__ . ': invalid regex match' );
			}
		}

		// Unclosed rule
		if ( $startPos < $length ) {
			$inner .= substr( $text, $startPos );
		}
		$startPos = $length;
		return '-{' . $this->autoConvert( $inner, $variant );
	}

	/** @inheritDoc */
	public function findVariantLink( &$link, &$nt, $ignoreOtherCond = false ) {
		# If the article has already existed, there is no need to
		# check it again. Otherwise it may cause a fault.
		if ( $nt instanceof LinkTarget ) {
			$nt = Title::castFromLinkTarget( $nt );
			if ( $nt->exists() ) {
				return;
			}
		}

		if ( $nt instanceof PageIdentity && $nt->exists() ) {
			return;
		}

		$request = RequestContext::getMain()->getRequest();

		$isredir = $request->getText( 'redirect', 'yes' );
		$action = $request->getText( 'action' );
		if ( $action == 'edit' && $request->getBool( 'redlink' ) ) {
			$action = 'view';
		}
		$linkconvert = $request->getText( 'linkconvert', 'yes' );
		$disableLinkConversion =
			MediaWikiServices::getInstance()->getLanguageConverterFactory()
			->isLinkConversionDisabled();
		$linkBatchFactory = MediaWikiServices::getInstance()->getLinkBatchFactory();
		$linkBatch = $linkBatchFactory->newLinkBatch();

		$ns = NS_MAIN;

		if ( $disableLinkConversion ||
			( !$ignoreOtherCond &&
				( $isredir == 'no'
					|| $action == 'edit'
					|| $action == 'submit'
					|| $linkconvert == 'no' )
			)
		) {
			return;
		}

		if ( is_object( $nt ) ) {
			$ns = $nt->getNamespace();
		}

		$variants = $this->autoConvertToAllVariants( $link );
		if ( !$variants ) { // give up
			return;
		}

		$titles = [];

		foreach ( $variants as $v ) {
			if ( $v != $link ) {
				$varnt = Title::newFromText( $v, $ns );
				if ( $varnt !== null ) {
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

	/** @inheritDoc */
	public function getExtraHashOptions() {
		$variant = $this->getPreferredVariant();

		return '!' . $variant;
	}

	/** @inheritDoc */
	public function guessVariant( $text, $variant ) {
		return false;
	}

	/**
	 * Load default conversion tables.
	 *
	 * @return array
	 */
	abstract protected function loadDefaultTables(): array;

	/**
	 * Load conversion tables either from the cache or the disk.
	 * @private
	 * @param bool $fromCache Whether to load from cache. Defaults to true.
	 */
	protected function loadTables( $fromCache = true ) {
		$services = MediaWikiServices::getInstance();
		$languageConverterCacheType = $services
			->getMainConfig()->get( MainConfigNames::LanguageConverterCacheType );

		if ( $this->mTablesLoaded ) {
			return;
		}

		$cache = $services->getObjectCacheFactory()->getInstance( $languageConverterCacheType );
		$cacheKey = $cache->makeKey(
			'conversiontables', $this->getMainCode(),
			md5( implode( ',', $this->getVariants() ) ), self::CACHE_VERSION_KEY
		);
		if ( !$fromCache ) {
			$cache->delete( $cacheKey );
		}
		$this->mTables = $cache->getWithSetCallback( $cacheKey, $cache::TTL_HOUR * 12, function () {
			// We will first load the default tables
			// then update them using things in MediaWiki:Conversiontable/*
			$tables = $this->loadDefaultTables();
			foreach ( $this->getVariants() as $var ) {
				$cached = $this->parseCachedTable( $var );
				$tables[$var]->mergeArray( $cached );
			}

			$this->postLoadTables( $tables );
			return $tables;
		} );
		$this->mTablesLoaded = true;
	}

	/**
	 * Hook for post-processing after conversion tables are loaded.
	 *
	 * @param ReplacementArray[] &$tables
	 */
	protected function postLoadTables( &$tables ) {
	}

	/**
	 * Reload the conversion tables.
	 *
	 * Also used by test suites which need to reset the converter state.
	 *
	 * Called by ParserTestRunner with the help of TestingAccessWrapper
	 */
	private function reloadTables() {
		if ( $this->mTables ) {
			$this->mTables = [];
		}

		$this->mTablesLoaded = false;
		$this->loadTables( false );
	}

	/**
	 * Parse the conversion table stored in the cache.
	 *
	 * The tables should be in blocks of the following form:
	 * 		-{
	 * 			word => word ;
	 * 			word => word ;
	 * 			...
	 * 		}-
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
	private function parseCachedTable( $code, $subpage = '', $recursive = true ) {
		static $parsed = [];

		$key = 'Conversiontable/' . $code;
		if ( $subpage ) {
			$key .= '/' . $subpage;
		}
		if ( array_key_exists( $key, $parsed ) ) {
			return [];
		}

		$parsed[$key] = true;

		if ( $subpage === '' ) {
			$messageCache = MediaWikiServices::getInstance()->getMessageCache();
			$txt = $messageCache->getMsgFromNamespace( $key, $code );
		} else {
			$txt = false;
			$title = Title::makeTitleSafe( NS_MEDIAWIKI, $key );
			if ( $title && $title->exists() ) {
				$revision = MediaWikiServices::getInstance()
					->getRevisionLookup()
					->getRevisionByTitle( $title );
				if ( $revision ) {
					$model = $revision->getSlot(
						SlotRecord::MAIN,
						RevisionRecord::RAW
					)->getModel();
					if ( $model == CONTENT_MODEL_WIKITEXT ) {
						// @phan-suppress-next-line PhanUndeclaredMethod
						$txt = $revision->getContent(
							SlotRecord::MAIN,
							RevisionRecord::RAW
						)->getText();
					}

					// @todo in the future, use a specialized content model, perhaps based on json!
				}
			}
		}

		# Nothing to parse if there's no text
		if ( $txt === false || $txt === null || $txt === '' ) {
			return [];
		}

		// get all subpage links of the form
		// [[MediaWiki:Conversiontable/zh-xx/...|...]]
		$linkhead = $this->mLangObj->getNsText( NS_MEDIAWIKI ) .
			':Conversiontable';
		$subs = StringUtils::explode( '[[', $txt );
		$sublinks = [];
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
		$ret = [];
		$first = true;
		foreach ( $blocks as $block ) {
			if ( $first ) {
				// Skip the part before the first -{
				$first = false;
				continue;
			}
			$mappings = explode( '}-', $block, 2 )[0];
			$stripped = str_replace( [ "'", '"', '*', '#' ], '', $mappings );
			$table = StringUtils::explode( ';', $stripped );
			foreach ( $table as $t ) {
				$m = explode( '=>', $t, 3 );
				if ( count( $m ) != 2 ) {
					continue;
				}
				// trim any trailing comments starting with '//'
				$tt = explode( '//', $m[1], 2 );
				$ret[trim( $m[0] )] = trim( $tt[0] );
			}
		}

		// recursively parse the subpages
		if ( $recursive ) {
			foreach ( $sublinks as $link ) {
				$s = $this->parseCachedTable( $code, $link, $recursive );
				$ret = $s + $ret;
			}
		}
		return $ret;
	}

	/** @inheritDoc */
	public function markNoConversion( $text, $noParse = false ) {
		# don't mark if already marked
		if ( str_contains( $text, '-{' ) || str_contains( $text, '}-' ) ) {
			return $text;
		}

		return "-{R|$text}-";
	}

	/** @inheritDoc */
	public function convertCategoryKey( $key ) {
		return $key;
	}

	/**
	 * @param PageIdentity $page Message page
	 *
	 * @return void
	 */
	public function updateConversionTable( PageIdentity $page ) {
		if ( $page->getNamespace() === NS_MEDIAWIKI ) {
			$t = explode( '/', $page->getDBkey(), 3 );
			$c = count( $t );
			if ( $c > 1 && $t[0] == 'Conversiontable' && $this->validateVariant( $t[1] ) ) {
				$this->reloadTables();
			}
		}
	}

	/**
	 * Get the cached separator pattern for ConverterRule::parseRules()
	 * @return string
	 */
	public function getVarSeparatorPattern() {
		if ( $this->mVarSeparatorPattern === null ) {
			// varsep_pattern for preg_split:
			// The text should be split by ";" only if a valid variant
			// name exists after the markup.
			// For example
			//  -{zh-hans:<span style="font-size:120%;">xxx</span>;zh-hant:\
			//  <span style="font-size:120%;">yyy</span>;}-
			// we should split it as:
			//  [
			//    [0] => 'zh-hans:<span style="font-size:120%;">xxx</span>'
			//    [1] => 'zh-hant:<span style="font-size:120%;">yyy</span>'
			//    [2] => ''
			//  ]
			$expandedVariants = [];
			foreach ( $this->getVariants() as $variant ) {
				$expandedVariants[ $variant ] = 1;
				// Accept standard BCP 47 names for variants as well.
				$expandedVariants[ LanguageCode::bcp47( $variant ) ] = 1;
			}
			// Accept old deprecated names for variants
			foreach ( LanguageCode::getDeprecatedCodeMapping() as $old => $new ) {
				if ( isset( $expandedVariants[ $new ] ) ) {
					$expandedVariants[ $old ] = 1;
				}
			}
			$expandedVariants = implode( '|', array_keys( $expandedVariants ) );

			$pat = '/;\s*(?=';
			// zh-hans:xxx;zh-hant:yyy
			$pat .= '(?:' . $expandedVariants . ')\s*:';
			// xxx=>zh-hans:yyy; xxx=>zh-hant:zzz
			$pat .= '|[^;]*?=>\s*(?:' . $expandedVariants . ')\s*:';
			$pat .= '|\s*$)/';
			$this->mVarSeparatorPattern = $pat;
		}
		return $this->mVarSeparatorPattern;
	}

	/** @inheritDoc */
	public function hasVariants() {
		return count( $this->getVariants() ) > 1;
	}

	/** @inheritDoc */
	public function hasVariant( $variant ) {
		return $variant && ( $variant === $this->validateVariant( $variant ) );
	}

	/** @inheritDoc */
	public function convertHtml( $text ) {
		// @phan-suppress-next-line SecurityCheck-DoubleEscaped convert() is documented to return html
		return htmlspecialchars( $this->convert( $text ) );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( LanguageConverter::class, 'LanguageConverter' );

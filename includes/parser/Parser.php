<?php
/**
 * PHP parser that converts wiki markup to HTML.
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

namespace MediaWiki\Parser;

use BadMethodCallException;
use Exception;
use ImageGalleryBase;
use ImageGalleryClassNotFoundException;
use InvalidArgumentException;
use LogicException;
use MediaHandler;
use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Category\TrackingCategories;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\TextContent;
use MediaWiki\Context\RequestContext;
use MediaWiki\Debug\DeprecationHelper;
use MediaWiki\FileRepo\File\File;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Html\HtmlHelper;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Language\Language;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Language\RawMessage;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\File\BadFileLookup;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Preferences\SignatureValidatorFactory;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Tidy\TidyDriverBase;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Utils\MWTimestamp;
use MediaWiki\Utils\UrlUtils;
use MediaWiki\Xml\Xml;
use Psr\Log\LoggerInterface;
use RuntimeException;
use SectionProfiler;
use UnexpectedValueException;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\IPUtils;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Parsoid\Core\LinkTarget;
use Wikimedia\Parsoid\Core\SectionMetadata;
use Wikimedia\Parsoid\Core\TOCData;
use Wikimedia\Parsoid\DOM\Comment;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\DOM\Node;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;
use Wikimedia\RemexHtml\Serializer\SerializerNode;
use Wikimedia\ScopedCallback;
use Wikimedia\StringUtils\StringUtils;

/**
 * @defgroup Parser Parser
 */

/**
 * PHP Parser - Processes wiki markup (which uses a more user-friendly
 * syntax, such as "[[link]]" for making links), and provides a one-way
 * transformation of that wiki markup it into (X)HTML output / markup
 * (which in turn the browser understands, and can display).
 *
 * There are seven main entry points into the Parser class:
 *
 * - Parser::parse()
 *     produces HTML output
 * - Parser::preSaveTransform()
 *     produces altered wiki markup
 * - Parser::preprocess()
 *     removes HTML comments and expands templates
 * - Parser::cleanSig() and Parser::cleanSigInSig()
 *     cleans a signature before saving it to preferences
 * - Parser::getSection()
 *     return the content of a section from an article for section editing
 * - Parser::replaceSection()
 *     replaces a section by number inside an article
 * - Parser::getPreloadText()
 *     removes <noinclude> sections and <includeonly> tags
 *
 * @warning $wgUser or $wgTitle or $wgRequest or $wgLang. Keep them away!
 *
 * @par Settings:
 * $wgNamespacesWithSubpages
 *
 * @par Settings only within ParserOptions:
 * $wgAllowExternalImages
 * $wgAllowSpecialInclusion
 * $wgInterwikiMagic
 * $wgMaxArticleSize
 *
 * @ingroup Parser
 */
#[\AllowDynamicProperties]
class Parser {
	use DeprecationHelper;

	# Flags for Parser::setFunctionHook
	public const SFH_NO_HASH = 1;
	public const SFH_OBJECT_ARGS = 2;

	# Constants needed for external link processing
	/**
	 * Everything except bracket, space, or control characters.
	 * \p{Zs} is unicode 'separator, space' category. It covers the space 0x20
	 * as well as U+3000 is IDEOGRAPHIC SPACE for T21052.
	 * \x{FFFD} is the Unicode replacement character, which the HTML5 spec
	 * uses to replace invalid HTML characters.
	 */
	public const EXT_LINK_URL_CLASS = '[^][<>"\\x00-\\x20\\x7F\p{Zs}\x{FFFD}]';
	/**
	 * Simplified expression to match an IPv4 or IPv6 address, or
	 * at least one character of a host name (embeds Parser::EXT_LINK_URL_CLASS)
	 */
	// phpcs:ignore Generic.Files.LineLength
	private const EXT_LINK_ADDR = '(?:[0-9.]+|\\[(?i:[0-9a-f:.]+)\\]|[^][<>"\\x00-\\x20\\x7F\p{Zs}\x{FFFD}])';
	/** RegExp to make image URLs (embeds IPv6 part of Parser::EXT_LINK_ADDR) */
	// phpcs:ignore Generic.Files.LineLength
	private const EXT_IMAGE_REGEX = '/^(http:\/\/|https:\/\/)((?:\\[(?i:[0-9a-f:.]+)\\])?[^][<>"\\x00-\\x20\\x7F\p{Zs}\x{FFFD}]+)
		\\/([A-Za-z0-9_.,~%\\-+&;#*?!=()@\\x80-\\xFF]+)\\.((?i)avif|gif|jpg|jpeg|png|svg|webp)$/Sxu';

	/** Regular expression for a non-newline space */
	private const SPACE_NOT_NL = '(?:\t|&nbsp;|&\#0*160;|&\#[Xx]0*[Aa]0;|\p{Zs})';

	/**
	 * @var int Preprocess wikitext in transclusion mode
	 * @deprecated Since 1.36
	 */
	public const PTD_FOR_INCLUSION = Preprocessor::DOM_FOR_INCLUSION;

	# Allowed values for $this->mOutputType
	/** Output type: like Parser::parse() */
	public const OT_HTML = 1;
	/** Output type: like Parser::preSaveTransform() */
	public const OT_WIKI = 2;
	/** Output type: like Parser::preprocess() */
	public const OT_PREPROCESS = 3;
	/**
	 * Output type: like Parser::extractSections() - portions of the
	 * original are returned unchanged.
	 */
	public const OT_PLAIN = 4;

	/**
	 * @var string Prefix and suffix for temporary replacement strings
	 * for the multipass parser.
	 *
	 * \x7f should never appear in input as it's disallowed in XML.
	 * Using it at the front also gives us a little extra robustness
	 * since it shouldn't match when butted up against identifier-like
	 * string constructs.
	 *
	 * Must not consist of all title characters, or else it will change
	 * the behavior of <nowiki> in a link.
	 *
	 * Must have a character that needs escaping in attributes, otherwise
	 * someone could put a strip marker in an attribute, to get around
	 * escaping quote marks, and break out of the attribute. Thus we add
	 * `'".
	 */
	public const MARKER_SUFFIX = "-QINU`\"'\x7f";
	public const MARKER_PREFIX = "\x7f'\"`UNIQ-";
	private const HEADLINE_MARKER_REGEX = '/^' . self::MARKER_PREFIX . '-h-(\d+)-' . self::MARKER_SUFFIX . '/';

	/**
	 * Internal marker used by parser to track where the table of
	 * contents should be. Various magic words can change the position
	 * during the parse.  The table of contents is generated during
	 * the parse, however skins have the final decision on whether the
	 * table of contents is injected.  This placeholder element
	 * identifies where in the page the table of contents should be
	 * injected, if at all.
	 * @var string
	 * @see Keep this in sync with BlockLevelPass::execute() and
	 *  RemexCompatMunger::isTableOfContentsMarker()
	 * @internal Skins should *not* directly reference TOC_PLACEHOLDER
	 * but instead use Parser::replaceTableOfContentsMarker().
	 */
	public const TOC_PLACEHOLDER = '<meta property="mw:PageProp/toc" />';

	# Persistent:
	/** @var array<string,callable> */
	private array $mTagHooks = [];
	/** @var array<string,array{0:callable,1:int}> */
	private array $mFunctionHooks = [];
	/** @var array{0:array<string,string>,1:array<string,string>} */
	private array $mFunctionSynonyms = [ 0 => [], 1 => [] ];
	/** @var string[] */
	private array $mStripList = [];
	/** @var array<string,string> */
	private array $mVarCache = [];
	/** @var array<string,array<string,string[]>> */
	private array $mImageParams = [];
	/** @var array<string,MagicWordArray> */
	private array $mImageParamsMagicArray = [];
	/** @deprecated since 1.35 */
	public $mMarkerIndex = 0;

	// Initialised by initializeVariables()
	/** @var MagicWordArray */
	private MagicWordArray $mVariables;
	private MagicWordArray $mSubstWords;

	// Initialised in constructor
	/** @var string */
	private string $mExtLinkBracketedRegex;
	private HookRunner $hookRunner;
	private Preprocessor $mPreprocessor;

	// Cleared with clearState():
	/** @var ParserOutput */
	private ParserOutput $mOutput;
	private int $mAutonumber = 0;
	private StripState $mStripState;
	private LinkHolderArray $mLinkHolders;
	private int $mLinkID = 0;
	private array $mIncludeSizes;
	/**
	 * @internal
	 * @var int
	 */
	public $mPPNodeCount;
	/**
	 * @internal
	 * @var int
	 */
	public $mHighestExpansionDepth;
	private array $mTplRedirCache;
	/** @internal */
	public array $mHeadings;
	/** @var array<string,false> */
	private array $mDoubleUnderscores;
	/**
	 * Number of expensive parser function calls
	 * @deprecated since 1.35
	 */
	public $mExpensiveFunctionCount;
	private bool $mShowToc;
	private bool $mForceTocPosition;
	private array $mTplDomCache;
	private ?UserIdentity $mUser;

	# Temporary
	# These are variables reset at least once per parse regardless of $clearState

	/**
	 * @var ParserOptions|null
	 * @deprecated since 1.35, use Parser::getOptions()
	 */
	private $mOptions;

	# Deprecated "dynamic" properties
	# These used to be dynamic properties added to the parser, but these
	# have been deprecated since 1.42.
	/** @deprecated since 1.42: T343229 */
	public $scribunto_engine;
	/** @deprecated since 1.42: T343230 */
	public $extCite;
	/** @deprecated since 1.42: T343226 */
	public $extTemplateStylesCache;
	/** @deprecated since 1.42: T357838 */
	public $static_tag_buf;
	/** @deprecated since 1.42: T203531 */
	public $mExtVariables;
	/** @deprecated since 1.42: T203532 */
	public $mExtArrays;
	/** @deprecated since 1.42: T359887 */
	public $mExtHashTables;
	/** @deprecated since 1.42: T203563 */
	public $mExtLoopsCounter;
	/** @deprecated since 1.42: T362664 */
	public $proofreadRenderingPages;
	/** @deprecated since 1.42: T362693 */
	public $mTemplatePath;

	/**
	 * Title context, used for self-link rendering and similar things
	 *
	 * @deprecated since 1.35, use Parser::getPage()
	 */
	private Title $mTitle;
	/** Output type, one of the OT_xxx constants */
	private int $mOutputType;
	/**
	 * When true (default), extension tags are put in the general strip state
	 * for OT_PREPROCESS.
	 * When false, extension tags are skipped during OT_PREPROCESS (parsoid
	 * fragment v1) or put into the `exttag` strip state (parsoid fragment
	 * v2+)
	 */
	private bool $mStripExtTags = true;
	/**
	 * Shortcut alias, see Parser::setOutputType()
	 * @deprecated since 1.35
	 */
	private array $ot;
	/** ID to display in {{REVISIONID}} tags */
	private ?int $mRevisionId = null;
	/** The timestamp of the specified revision ID */
	private ?string $mRevisionTimestamp = null;
	/** User to display in {{REVISIONUSER}} tag */
	private ?string $mRevisionUser = null;
	/** Size to display in {{REVISIONSIZE}} variable */
	private ?int $mRevisionSize = null;
	/** @var int|false For {{PAGESIZE}} on current page */
	private $mInputSize = false;

	private ?RevisionRecord $mRevisionRecordObject = null;

	/**
	 * A cache of the current revisions of titles. Keys are $title->getPrefixedDbKey()
	 *
	 * @since 1.24
	 */
	private ?MapCacheLRU $currentRevisionCache = null;

	/**
	 * @var bool|string Recursive call protection.
	 * @internal
	 */
	private $mInParse = false;

	private SectionProfiler $mProfiler;
	private ?LinkRenderer $mLinkRenderer = null;

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		// See documentation for the corresponding config options
		// Many of these are only used in (eg) CoreMagicVariables
		MainConfigNames::AllowDisplayTitle,
		MainConfigNames::AllowSlowParserFunctions,
		MainConfigNames::ArticlePath,
		MainConfigNames::EnableScaryTranscluding,
		MainConfigNames::ExtraInterlanguageLinkPrefixes,
		MainConfigNames::FragmentMode,
		MainConfigNames::Localtimezone,
		MainConfigNames::MaxArticleSize,
		MainConfigNames::MaxSigChars,
		MainConfigNames::MaxTocLevel,
		MainConfigNames::MiserMode,
		MainConfigNames::RawHtml,
		MainConfigNames::ScriptPath,
		MainConfigNames::Server,
		MainConfigNames::ServerName,
		MainConfigNames::ShowHostnames,
		MainConfigNames::SignatureValidation,
		MainConfigNames::Sitename,
		MainConfigNames::StylePath,
		MainConfigNames::TranscludeCacheExpiry,
		MainConfigNames::PreprocessorCacheThreshold,
		MainConfigNames::EnableParserLimitReporting,
		MainConfigNames::ParserEnableUserLanguage,
	];

	/**
	 * Constructing parsers directly is not allowed! Use a ParserFactory.
	 * @internal
	 */
	public function __construct(
		// This is called $svcOptions instead of $options like elsewhere to avoid confusion with
		// $mOptions, which is public and widely used, and also with the local variable $options used
		// for ParserOptions throughout this file.
		private ServiceOptions $svcOptions,
		private MagicWordFactory $magicWordFactory,
		private Language $contLang,
		private UrlUtils $urlUtils,
		private SpecialPageFactory $specialPageFactory,
		private LinkRendererFactory $linkRendererFactory,
		private NamespaceInfo $nsInfo,
		private LoggerInterface $logger,
		private BadFileLookup $badFileLookup,
		private LanguageConverterFactory $languageConverterFactory,
		private LanguageNameUtils $languageNameUtils,
		private HookContainer $hookContainer,
		private TidyDriverBase $tidy,
		private WANObjectCache $wanCache,
		private UserOptionsLookup $userOptionsLookup,
		private UserFactory $userFactory,
		private TitleFormatter $titleFormatter,
		private HttpRequestFactory $httpRequestFactory,
		private TrackingCategories $trackingCategories,
		private SignatureValidatorFactory $signatureValidatorFactory,
		private UserNameUtils $userNameUtils,
	) {
		$this->deprecateDynamicPropertiesAccess( '1.42', __CLASS__ );
		$this->deprecatePublicProperty( 'ot', '1.35', __CLASS__ );
		$this->deprecatePublicProperty( 'mTitle', '1.35', __CLASS__ );
		$this->deprecatePublicProperty( 'mOptions', '1.35', __CLASS__ );

		if ( ParserFactory::$inParserFactory === 0 ) {
			// Direct construction of Parser was deprecated in 1.34 and
			// removed in 1.36; use a ParserFactory instead.
			throw new BadMethodCallException( 'Direct construction of Parser not allowed' );
		}
		$svcOptions->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->mExtLinkBracketedRegex = '/\[(((?i)' . $this->urlUtils->validProtocols() . ')' .
			self::EXT_LINK_ADDR .
			self::EXT_LINK_URL_CLASS . '*)\p{Zs}*([^\]\\x00-\\x08\\x0a-\\x1F\\x{FFFD}]*)\]/Su';

		$this->hookRunner = new HookRunner( $hookContainer );

		$this->mPreprocessor = new Preprocessor_Hash(
			$this,
			$this->wanCache,
			[
				'cacheThreshold' => $svcOptions->get( MainConfigNames::PreprocessorCacheThreshold ),
				'disableLangConversion' => $languageConverterFactory->isConversionDisabled(),
			]
		);

		// These steps used to be done in "::firstCallInit()"
		// (if you're chasing a reference from some old code)
		CoreParserFunctions::register(
			$this,
			new ServiceOptions( CoreParserFunctions::REGISTER_OPTIONS, $svcOptions )
		);
		CoreTagHooks::register(
			$this,
			new ServiceOptions( CoreTagHooks::REGISTER_OPTIONS, $svcOptions )
		);
		$this->initializeVariables();

		$this->hookRunner->onParserFirstCallInit( $this );
		$this->mTitle = Title::makeTitle( NS_SPECIAL, 'Badtitle/Missing' );
	}

	/**
	 * Reduce memory usage to reduce the impact of circular references
	 */
	public function __destruct() {
		// @phan-suppress-next-line PhanRedundantCondition Typed property not set in constructor, may be uninitialized
		if ( isset( $this->mLinkHolders ) ) {
			// @phan-suppress-next-line PhanTypeObjectUnsetDeclaredProperty
			unset( $this->mLinkHolders );
		}
		// @phan-suppress-next-line PhanTypeSuspiciousNonTraversableForeach
		foreach ( $this as $name => $value ) {
			unset( $this->$name );
		}
	}

	/**
	 * Allow extensions to clean up when the parser is cloned
	 */
	public function __clone() {
		$this->mInParse = false;

		$this->mPreprocessor = clone $this->mPreprocessor;
		$this->mPreprocessor->resetParser( $this );

		$this->hookRunner->onParserCloned( $this );
	}

	/**
	 * Used to do various kinds of initialisation on the first call of the
	 * parser.
	 * @deprecated since 1.35, this initialization is done in the constructor
	 *  and manual calls to ::firstCallInit() have no effect.
	 * @since 1.7
	 */
	public function firstCallInit() {
		/*
		 * This method should be hard-deprecated once remaining calls are
		 * removed; it no longer does anything.
		 */
	}

	/**
	 * Clear Parser state
	 *
	 * @internal
	 */
	public function clearState() {
		$this->resetOutput();
		$this->mAutonumber = 0;
		$this->mLinkHolders = new LinkHolderArray(
			$this,
			$this->getContentLanguageConverter(),
			$this->getHookContainer()
		);
		$this->mLinkID = 0;
		$this->mRevisionTimestamp = null;
		$this->mRevisionId = null;
		$this->mRevisionUser = null;
		$this->mRevisionSize = null;
		$this->mRevisionRecordObject = null;
		$this->mVarCache = [];
		$this->mUser = null;
		$this->currentRevisionCache = null;

		$this->mStripState = new StripState( $this );

		# Clear these on every parse, T6549
		$this->mTplRedirCache = [];
		$this->mTplDomCache = [];

		$this->mShowToc = true;
		$this->mForceTocPosition = false;
		$this->mIncludeSizes = [
			'post-expand' => 0,
			'arg' => 0,
		];
		$this->mPPNodeCount = 0;
		$this->mHighestExpansionDepth = 0;
		$this->mHeadings = [];
		$this->mDoubleUnderscores = [];
		$this->mExpensiveFunctionCount = 0;

		$this->mProfiler = new SectionProfiler();

		$this->hookRunner->onParserClearState( $this );
	}

	/**
	 * Reset the ParserOutput
	 * @since 1.34
	 */
	public function resetOutput() {
		$this->mOutput = new ParserOutput;
		$this->mOptions->registerWatcher( [ $this->mOutput, 'recordOption' ] );
	}

	/**
	 * Convert wikitext to HTML
	 * Do not call this function recursively.
	 *
	 * @param string $text Text we want to parse
	 * @param-taint $text escapes_htmlnoent
	 * @param PageReference $page
	 * @param ParserOptions $options
	 * @param bool $linestart
	 * @param bool $clearState
	 * @param int|null $revid ID of the revision being rendered. This is used to render
	 *  REVISION* magic words. 0 means that any current revision will be used. Null means
	 *  that {{REVISIONID}}/{{REVISIONUSER}} will be empty and {{REVISIONTIMESTAMP}} will
	 *  use the current timestamp.
	 * @return ParserOutput
	 * @return-taint escaped
	 * @since 1.10 method is public
	 */
	public function parse(
		$text, PageReference $page, ParserOptions $options,
		$linestart = true, $clearState = true, $revid = null
	) {
		if ( $clearState ) {
			// We use U+007F DELETE to construct strip markers, so we have to make
			// sure that this character does not occur in the input text.
			$text = strtr( $text, "\x7f", "?" );
			$magicScopeVariable = $this->lock();
		}
		// Strip U+0000 NULL (T159174)
		$text = str_replace( "\000", '', $text );

		$this->startParse( $page, $options, self::OT_HTML, $clearState );

		$this->currentRevisionCache = null;
		$this->mInputSize = strlen( $text );
		$this->mOutput->resetParseStartTime();

		$oldRevisionId = $this->mRevisionId;
		$oldRevisionRecordObject = $this->mRevisionRecordObject;
		$oldRevisionTimestamp = $this->mRevisionTimestamp;
		$oldRevisionUser = $this->mRevisionUser;
		$oldRevisionSize = $this->mRevisionSize;
		if ( $revid !== null ) {
			$this->mRevisionId = $revid;
			$this->mRevisionRecordObject = null;
			$this->mRevisionTimestamp = null;
			$this->mRevisionUser = null;
			$this->mRevisionSize = null;
		}

		$text = $this->internalParse( $text );
		$this->hookRunner->onParserAfterParse( $this, $text, $this->mStripState );

		$text = $this->internalParseHalfParsed( $text, true, $linestart );

		/**
		 * A converted title will be provided in the output object if title and
		 * content conversion are enabled, the article text does not contain
		 * a conversion-suppressing double-underscore tag, and no
		 * {{DISPLAYTITLE:...}} is present. DISPLAYTITLE takes precedence over
		 * automatic link conversion.
		 */
		if ( !$options->getDisableTitleConversion()
			&& !isset( $this->mDoubleUnderscores['nocontentconvert'] )
			&& !isset( $this->mDoubleUnderscores['notitleconvert'] )
			&& $this->mOutput->getDisplayTitle() === false
		) {
			$titleText = $this->getTargetLanguageConverter()->getConvRuleTitle();
			if ( $titleText !== false ) {
				$titleText = Sanitizer::removeSomeTags( $titleText );
			} else {
				[ $nsText, $nsSeparator, $mainText ] = $this->getTargetLanguageConverter()->convertSplitTitle( $page );
				// In the future, those three pieces could be stored separately rather than joined into $titleText,
				// and OutputPage would format them and join them together, to resolve T314399.
				$titleText = self::formatPageTitle( $nsText, $nsSeparator, $mainText );
			}
			$this->mOutput->setTitleText( $titleText );
		}

		# Recording timing info. Must be called before finalizeAdaptiveCacheExpiry() and
		# makeLimitReport(), which make use of the timing info.
		$this->mOutput->recordTimeProfile();

		# Compute runtime adaptive expiry if set
		$this->mOutput->finalizeAdaptiveCacheExpiry();

		# Warn if too many heavyweight parser functions were used
		if ( $this->mExpensiveFunctionCount > $options->getExpensiveParserFunctionLimit() ) {
			$this->limitationWarn( 'expensive-parserfunction',
				$this->mExpensiveFunctionCount,
				$options->getExpensiveParserFunctionLimit()
			);
		}

		# Information on limits, for the benefit of users who try to skirt them
		$this->makeLimitReport( $this->mOptions, $this->mOutput );

		$this->mOutput->setFromParserOptions( $options );

		$this->mOutput->setRawText( $text );

		$this->mRevisionId = $oldRevisionId;
		$this->mRevisionRecordObject = $oldRevisionRecordObject;
		$this->mRevisionTimestamp = $oldRevisionTimestamp;
		$this->mRevisionUser = $oldRevisionUser;
		$this->mRevisionSize = $oldRevisionSize;
		$this->mInputSize = false;
		$this->currentRevisionCache = null;

		return $this->mOutput;
	}

	/**
	 * Set the limit report data in the current ParserOutput.
	 * @internal
	 */
	public function makeLimitReport(
		ParserOptions $parserOptions, ParserOutput $parserOutput
	) {
		if ( !$this->svcOptions->get( MainConfigNames::EnableParserLimitReporting ) ) {
			return;
		}
		if ( $parserOptions->isMessage() ) {
			// No need to include limit report information in
			// user interface messages.
			return;
		}

		$maxIncludeSize = $parserOptions->getMaxIncludeSize();

		$cpuTime = $parserOutput->getTimeProfile( 'cpu' );
		if ( $cpuTime !== null ) {
			$parserOutput->setLimitReportData( 'limitreport-cputime',
				sprintf( "%.3f", $cpuTime )
			);
		}

		$wallTime = $parserOutput->getTimeProfile( 'wall' );
		$parserOutput->setLimitReportData( 'limitreport-walltime',
			sprintf( "%.3f", $wallTime )
		);

		$parserOutput->setLimitReportData( 'limitreport-ppvisitednodes',
			[ $this->mPPNodeCount, $parserOptions->getMaxPPNodeCount() ]
		);
		$revisionSize = $this->mInputSize !== false ? $this->mInputSize :
			$this->getRevisionSize();
		$parserOutput->setLimitReportData( 'limitreport-revisionsize',
			[ $revisionSize ?? -1, $this->svcOptions->get( MainConfigNames::MaxArticleSize ) * 1024 ]
		);
		$parserOutput->setLimitReportData( 'limitreport-postexpandincludesize',
			[ $this->mIncludeSizes['post-expand'], $maxIncludeSize ]
		);
		$parserOutput->setLimitReportData( 'limitreport-templateargumentsize',
			[ $this->mIncludeSizes['arg'], $maxIncludeSize ]
		);
		$parserOutput->setLimitReportData( 'limitreport-expansiondepth',
			[ $this->mHighestExpansionDepth, $parserOptions->getMaxPPExpandDepth() ]
		);
		$parserOutput->setLimitReportData( 'limitreport-expensivefunctioncount',
			[ $this->mExpensiveFunctionCount, $parserOptions->getExpensiveParserFunctionLimit() ]
		);

		foreach ( $this->mStripState->getLimitReport() as [ $key, $value ] ) {
			$parserOutput->setLimitReportData( $key, $value );
		}

		$this->hookRunner->onParserLimitReportPrepare( $this, $parserOutput );

		// Add on template profiling data in human/machine readable way
		$dataByFunc = $this->mProfiler->getFunctionStats();
		uasort( $dataByFunc, static function ( $a, $b ) {
			return $b['real'] <=> $a['real']; // descending order
		} );
		$profileReport = [];
		foreach ( array_slice( $dataByFunc, 0, 10 ) as $item ) {
			$profileReport[] = sprintf( "%6.2f%% %8.3f %6d %s",
				$item['%real'], $item['real'], $item['calls'],
				htmlspecialchars( $item['name'] ) );
		}

		$parserOutput->setLimitReportData( 'limitreport-timingprofile', $profileReport );

		// Add other cache related metadata
		if ( $this->svcOptions->get( MainConfigNames::ShowHostnames ) ) {
			$parserOutput->setLimitReportData( 'cachereport-origin', wfHostname() );
		}
		$parserOutput->setLimitReportData( 'cachereport-timestamp',
			$parserOutput->getCacheTime() );
		$parserOutput->setLimitReportData( 'cachereport-ttl',
			$parserOutput->getCacheExpiry() );
		$parserOutput->setLimitReportData( 'cachereport-transientcontent',
			$parserOutput->hasReducedExpiry() );
	}

	/**
	 * Half-parse wikitext to half-parsed HTML. This recursive parser entry point
	 * can be called from an extension tag hook.
	 *
	 * The output of this function IS NOT SAFE PARSED HTML; it is "half-parsed"
	 * instead, which means that lists and links have not been fully parsed yet,
	 * and strip markers are still present.
	 *
	 * Use recursiveTagParseFully() to fully parse wikitext to output-safe HTML.
	 *
	 * Use this function if you're a parser tag hook and you want to parse
	 * wikitext before or after applying additional transformations, and you
	 * intend to *return the result as hook output*, which will cause it to go
	 * through the rest of parsing process automatically.
	 *
	 * If $frame is not provided, then template variables (e.g., {{{1}}}) within
	 * $text are not expanded
	 *
	 * @param string $text Text extension wants to have parsed
	 * @param-taint $text escapes_htmlnoent
	 * @param PPFrame|false $frame The frame to use for expanding any template variables
	 * @return string UNSAFE half-parsed HTML
	 * @return-taint escaped
	 * @since 1.8
	 */
	public function recursiveTagParse( $text, $frame = false ) {
		$text = $this->internalParse( $text, false, $frame );
		return $text;
	}

	/**
	 * Fully parse wikitext to fully parsed HTML. This recursive parser entry
	 * point can be called from an extension tag hook.
	 *
	 * The output of this function is fully-parsed HTML that is safe for output.
	 * If you're a parser tag hook, you might want to use recursiveTagParse()
	 * instead.
	 *
	 * If $frame is not provided, then template variables (e.g., {{{1}}}) within
	 * $text are not expanded
	 *
	 * @since 1.25
	 *
	 * @param string $text Text extension wants to have parsed
	 * @param-taint $text escapes_htmlnoent
	 * @param PPFrame|false $frame The frame to use for expanding any template variables
	 * @return string Fully parsed HTML
	 * @return-taint escaped
	 */
	public function recursiveTagParseFully( $text, $frame = false ) {
		$text = $this->recursiveTagParse( $text, $frame );
		$text = $this->internalParseHalfParsed( $text, false );
		return $text;
	}

	/**
	 * Needed by Parsoid/PHP to ensure all the hooks for extensions
	 * are run in the right order. The primary differences between this
	 * and recursiveTagParseFully are:
	 * (a) absence of $frame
	 * (b) passing true to internalParseHalfParse so all hooks are run
	 * (c) running 'ParserAfterParse' hook at the same point in the parsing
	 *     pipeline when parse() does it. This kinda mimics Parsoid/JS behavior
	 *     where exttags are processed by the M/w API.
	 *
	 * This is a temporary convenience method and will go away as we proceed
	 * further with Parsoid <-> Parser.php integration.
	 *
	 * @internal
	 * @deprecated
	 * @param string $text Wikitext source of the extension
	 * @return string
	 * @return-taint escaped
	 */
	public function parseExtensionTagAsTopLevelDoc( string $text ): string {
		$text = $this->recursiveTagParse( $text );
		$this->hookRunner->onParserAfterParse( $this, $text, $this->mStripState );
		$text = $this->internalParseHalfParsed( $text, true );
		return $text;
	}

	/**
	 * Expand templates and variables in the text, producing valid, static wikitext.
	 * Also removes comments.
	 * Do not call this function recursively.
	 * @param string $text
	 * @param ?PageReference $page
	 * @param ParserOptions $options
	 * @param int|null $revid
	 * @param PPFrame|false $frame
	 * @return mixed|string
	 * @since 1.8
	 */
	public function preprocess(
		$text,
		?PageReference $page,
		ParserOptions $options,
		$revid = null,
		$frame = false
	) {
		$magicScopeVariable = $this->lock();
		$this->startParse( $page, $options, self::OT_PREPROCESS, true );
		if ( $revid !== null ) {
			$this->mRevisionId = $revid;
		}
		$this->hookRunner->onParserBeforePreprocess( $this, $text, $this->mStripState );
		$text = $this->replaceVariables( $text, $frame );
		$text = $this->mStripState->unstripBoth( $text );
		return $text;
	}

	/**
	 * Recursive parser entry point that can be called from an extension tag
	 * hook.
	 *
	 * @param string $text Text to be expanded
	 * @param PPFrame|false $frame The frame to use for expanding any template variables
	 * @return string
	 * @since 1.19
	 */
	public function recursivePreprocess( $text, $frame = false ) {
		$text = $this->replaceVariables( $text, $frame );
		$text = $this->mStripState->unstripBoth( $text );
		return $text;
	}

	/**
	 * Process the wikitext for the "?preload=" feature. (T7210)
	 *
	 * "<noinclude>", "<includeonly>" etc. are parsed as for template
	 * transclusion, comments, templates, arguments, tags hooks and parser
	 * functions are untouched.
	 *
	 * @param string $text
	 * @param PageReference $page
	 * @param ParserOptions $options
	 * @param array $params
	 * @return string
	 * @since 1.17
	 */
	public function getPreloadText( $text, PageReference $page, ParserOptions $options, $params = [] ) {
		$msg = new RawMessage( $text );
		$text = $msg->params( $params )->plain();

		# Parser (re)initialisation
		$magicScopeVariable = $this->lock();
		$this->startParse( $page, $options, self::OT_PLAIN, true );

		$flags = PPFrame::NO_ARGS | PPFrame::NO_TEMPLATES;
		$dom = $this->preprocessToDom( $text, Preprocessor::DOM_FOR_INCLUSION );
		$text = $this->getPreprocessor()->newFrame()->expand( $dom, $flags );
		$text = $this->mStripState->unstripBoth( $text );
		return $text;
	}

	/**
	 * Set the current user.
	 * Should only be used when doing pre-save transform.
	 *
	 * @param UserIdentity|null $user user identity or null (to reset)
	 * @since 1.17
	 */
	public function setUser( ?UserIdentity $user ) {
		$this->mUser = $user;
	}

	/**
	 * Set the context title
	 *
	 * @deprecated since 1.37, use setPage() instead.
	 * @param Title|null $t
	 * @since 1.12
	 */
	public function setTitle( ?Title $t = null ) {
		$this->setPage( $t );
	}

	/**
	 * @since 1.6
	 * @deprecated since 1.37, use getPage instead.
	 * @return Title
	 */
	public function getTitle(): Title {
		return $this->mTitle;
	}

	/**
	 * Set the page used as context for parsing, e.g. when resolving relative subpage links.
	 *
	 * @since 1.37
	 * @param ?PageReference $t
	 */
	public function setPage( ?PageReference $t = null ) {
		if ( !$t ) {
			$t = Title::makeTitle( NS_SPECIAL, 'Badtitle/Parser' );
		} else {
			// For now (early 1.37 alpha), always convert to Title, so we don't have to do it over
			// and over again in other methods. Eventually, we will no longer need to have a Title
			// instance internally.
			$t = Title::newFromPageReference( $t );
		}

		if ( $t->hasFragment() ) {
			# Strip the fragment to avoid various odd effects
			$this->mTitle = $t->createFragmentTarget( '' );
		} else {
			$this->mTitle = $t;
		}
	}

	/**
	 * Returns the page used as context for parsing, e.g. when resolving relative subpage links.
	 * @since 1.37
	 * @return ?PageReference Null if no page is set (deprecated since 1.34)
	 */
	public function getPage(): ?PageReference {
		if ( $this->mTitle->isSpecial( 'Badtitle' ) ) {
			[ , $subPage ] = $this->specialPageFactory->resolveAlias( $this->mTitle->getDBkey() );

			if ( $subPage === 'Missing' ) {
				wfDeprecated( __METHOD__ . ' without a Title set', '1.34' );
				return null;
			}
		}

		return $this->mTitle;
	}

	/**
	 * Accessor for the output type.
	 * @return int One of the Parser::OT_... constants
	 * @since 1.35
	 */
	public function getOutputType(): int {
		return $this->mOutputType;
	}

	/**
	 * Mutator for the output type.
	 * @param int $ot One of the Parser::OT_… constants
	 * @since 1.8
	 */
	public function setOutputType( $ot ): void {
		$this->mOutputType = $ot;
		# Shortcut alias
		$this->ot = [
			'html' => $ot == self::OT_HTML,
			'wiki' => $ot == self::OT_WIKI,
			'pre' => $ot == self::OT_PREPROCESS,
			'plain' => $ot == self::OT_PLAIN,
		];
	}

	/**
	 * Accessor/mutator for the output type
	 *
	 * @param int|null $x New value or null to just get the current one
	 * @return int
	 * @deprecated since 1.35, use getOutputType()/setOutputType()
	 */
	public function OutputType( $x = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		return wfSetVar( $this->mOutputType, $x );
	}

	/**
	 * @return ParserOutput
	 * @since 1.14
	 */
	public function getOutput() {
		// @phan-suppress-next-line PhanRedundantCondition False positive, see https://github.com/phan/phan/issues/4720
		if ( !isset( $this->mOutput ) ) {
			wfDeprecated( __METHOD__ . ' before initialization', '1.42' );
			// @phan-suppress-next-line PhanTypeMismatchReturnProbablyReal We don’t want to tell anyone we’re doing this
			return null;
		}
		return $this->mOutput;
	}

	/**
	 * @return ParserOptions|null
	 * @since 1.6
	 */
	public function getOptions() {
		return $this->mOptions;
	}

	/**
	 * Mutator for the ParserOptions object
	 * @param ParserOptions $options The new parser options
	 * @since 1.35
	 */
	public function setOptions( ParserOptions $options ): void {
		$this->mOptions = $options;
	}

	/**
	 * Accessor/mutator for the ParserOptions object
	 *
	 * @param ParserOptions|null $x New value or null to just get the current one
	 * @return ParserOptions Current ParserOptions object
	 * @deprecated since 1.35, use getOptions() / setOptions()
	 */
	public function Options( $x = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		return wfSetVar( $this->mOptions, $x );
	}

	/**
	 * @return int
	 * @since 1.14
	 */
	public function nextLinkID() {
		return $this->mLinkID++;
	}

	/**
	 * @param int $id
	 * @since 1.8
	 */
	public function setLinkID( $id ) {
		$this->mLinkID = $id;
	}

	/**
	 * Get a language object for use in parser functions such as {{FORMATNUM:}}
	 * @return Language
	 * @since 1.7
	 * @deprecated since 1.40; use ::getTargetLanguage() instead.
	 */
	public function getFunctionLang() {
		wfDeprecated( __METHOD__, '1.40' );
		return $this->getTargetLanguage();
	}

	/**
	 * Get the target language for the content being parsed. This is usually the
	 * language that the content is in.
	 *
	 * @since 1.19
	 *
	 * @return Language
	 */
	public function getTargetLanguage() {
		$target = $this->mOptions->getTargetLanguage();

		if ( $target !== null ) {
			return $target;
		} elseif ( $this->mOptions->getInterfaceMessage() ) {
			return $this->mOptions->getUserLangObj();
		}

		return $this->getTitle()->getPageLanguage();
	}

	/**
	 * Get a user either from the user set on Parser if it's set,
	 * or from the ParserOptions object otherwise.
	 *
	 * @since 1.36
	 * @return UserIdentity
	 */
	public function getUserIdentity(): UserIdentity {
		return $this->mUser ?? $this->getOptions()->getUserIdentity();
	}

	/**
	 * Get a preprocessor object
	 *
	 * @return Preprocessor
	 * @since 1.12.0
	 */
	public function getPreprocessor() {
		return $this->mPreprocessor;
	}

	/**
	 * Get a LinkRenderer instance to make links with
	 *
	 * @since 1.28
	 * @return LinkRenderer
	 */
	public function getLinkRenderer() {
		// XXX We make the LinkRenderer with current options and then cache it forever
		if ( !$this->mLinkRenderer ) {
			$this->mLinkRenderer = $this->linkRendererFactory->create();
		}

		return $this->mLinkRenderer;
	}

	/**
	 * Get the MagicWordFactory that this Parser is using
	 *
	 * @since 1.32
	 * @return MagicWordFactory
	 */
	public function getMagicWordFactory() {
		return $this->magicWordFactory;
	}

	/**
	 * Get the content language that this Parser is using
	 *
	 * @since 1.32
	 * @return Language
	 */
	public function getContentLanguage() {
		return $this->contLang;
	}

	/**
	 * Get the BadFileLookup instance that this Parser is using
	 *
	 * @since 1.35
	 * @return BadFileLookup
	 */
	public function getBadFileLookup() {
		return $this->badFileLookup;
	}

	/**
	 * Replaces all occurrences of HTML-style comments and the given tags
	 * in the text with a random marker and returns the next text. The output
	 * parameter $matches will be an associative array filled with data in
	 * the form:
	 *
	 * @code
	 *   'UNIQ-xxxxx' => [
	 *     'element',
	 *     'tag content',
	 *     [ 'param' => 'x' ],
	 *     '<element param="x">tag content</element>' ]
	 * @endcode
	 *
	 * @param string[] $elements List of element names. Comments are always extracted.
	 * @param string $text Source text string.
	 * @param array[] &$matches Out parameter, Array: extracted tags
	 * @return string Stripped text
	 */
	public static function extractTagsAndParams( array $elements, $text, &$matches ) {
		static $n = 1;
		$stripped = '';
		$matches = [];

		$taglist = implode( '|', $elements );
		$start = "/<($taglist)(\\s+[^>]*?|\\s*?)(\/?>)|<(!--)/i";

		while ( $text != '' ) {
			$p = preg_split( $start, $text, 2, PREG_SPLIT_DELIM_CAPTURE );
			$stripped .= $p[0];
			if ( count( $p ) < 5 ) {
				break;
			}
			if ( count( $p ) > 5 ) {
				# comment
				$element = $p[4];
				$attributes = '';
				$close = '';
				$inside = $p[5];
			} else {
				# tag
				[ , $element, $attributes, $close, $inside ] = $p;
			}

			$marker = self::MARKER_PREFIX . "-$element-" . sprintf( '%08X', $n++ ) . self::MARKER_SUFFIX;
			$stripped .= $marker;

			if ( $close === '/>' ) {
				# Empty element tag, <tag />
				$content = null;
				$text = $inside;
				$tail = null;
			} else {
				if ( $element === '!--' ) {
					$end = '/(-->)/';
				} else {
					$end = "/(<\\/$element\\s*>)/i";
				}
				$q = preg_split( $end, $inside, 2, PREG_SPLIT_DELIM_CAPTURE );
				$content = $q[0];
				if ( count( $q ) < 3 ) {
					# No end tag -- let it run out to the end of the text.
					$tail = '';
					$text = '';
				} else {
					[ , $tail, $text ] = $q;
				}
			}

			$matches[$marker] = [ $element,
				$content,
				Sanitizer::decodeTagAttributes( $attributes ),
				"<$element$attributes$close$content$tail" ];
		}
		return $stripped;
	}

	/**
	 * Get a list of strippable XML-like elements
	 *
	 * @return array
	 */
	public function getStripList() {
		return $this->mStripList;
	}

	/**
	 * @return StripState
	 * @since 1.34
	 */
	public function getStripState() {
		return $this->mStripState;
	}

	/**
	 * Add an item to the strip state
	 * Returns the unique tag which must be inserted into the stripped text
	 * The tag will be replaced with the original text in unstrip()
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function insertStripItem( $text ) {
		$marker = self::MARKER_PREFIX . "-item-{$this->mMarkerIndex}-" . self::MARKER_SUFFIX;
		$this->mMarkerIndex++;
		$this->mStripState->addGeneral( $marker, $text );
		return $marker;
	}

	/**
	 * Parse the wiki syntax used to render tables.
	 *
	 * @param string $text
	 * @return string
	 */
	private function handleTables( $text ) {
		$lines = StringUtils::explode( "\n", $text );
		$out = '';
		$td_history = []; # Is currently a td tag open?
		$last_tag_history = []; # Save history of last lag activated (td, th or caption)
		$tr_history = []; # Is currently a tr tag open?
		$tr_attributes = []; # history of tr attributes
		$has_opened_tr = []; # Did this table open a <tr> element?
		$indent_level = 0; # indent level of the table

		foreach ( $lines as $outLine ) {
			$line = trim( $outLine );

			if ( $line === '' ) { # empty line, go to next line
				$out .= $outLine . "\n";
				continue;
			}

			$first_character = $line[0];
			$first_two = substr( $line, 0, 2 );
			$matches = [];

			if ( preg_match( '/^(:*)\s*\{\|(.*)$/', $line, $matches ) ) {
				# First check if we are starting a new table
				$indent_level = strlen( $matches[1] );

				$attributes = $this->mStripState->unstripBoth( $matches[2] );
				$attributes = Sanitizer::fixTagAttributes( $attributes, 'table' );

				$outLine = str_repeat( '<dl><dd>', $indent_level ) . "<table{$attributes}>";
				$td_history[] = false;
				$last_tag_history[] = '';
				$tr_history[] = false;
				$tr_attributes[] = '';
				$has_opened_tr[] = false;
			} elseif ( count( $td_history ) == 0 ) {
				# Don't do any of the following
				$out .= $outLine . "\n";
				continue;
			} elseif ( $first_two === '|}' ) {
				# We are ending a table
				$line = '</table>' . substr( $line, 2 );
				$last_tag = array_pop( $last_tag_history );

				if ( !array_pop( $has_opened_tr ) ) {
					$line = "<tr><td></td></tr>{$line}";
				}

				if ( array_pop( $tr_history ) ) {
					$line = "</tr>{$line}";
				}

				if ( array_pop( $td_history ) ) {
					$line = "</{$last_tag}>{$line}";
				}
				array_pop( $tr_attributes );
				if ( $indent_level > 0 ) {
					$outLine = rtrim( $line ) . str_repeat( '</dd></dl>', $indent_level );
				} else {
					$outLine = $line;
				}
			} elseif ( $first_two === '|-' ) {
				# Now we have a table row
				$line = preg_replace( '#^\|-+#', '', $line );

				# Whats after the tag is now only attributes
				$attributes = $this->mStripState->unstripBoth( $line );
				$attributes = Sanitizer::fixTagAttributes( $attributes, 'tr' );
				array_pop( $tr_attributes );
				$tr_attributes[] = $attributes;

				$line = '';
				$last_tag = array_pop( $last_tag_history );
				array_pop( $has_opened_tr );
				$has_opened_tr[] = true;

				if ( array_pop( $tr_history ) ) {
					$line = '</tr>';
				}

				if ( array_pop( $td_history ) ) {
					$line = "</{$last_tag}>{$line}";
				}

				$outLine = $line;
				$tr_history[] = false;
				$td_history[] = false;
				$last_tag_history[] = '';
			} elseif ( $first_character === '|'
				|| $first_character === '!'
				|| $first_two === '|+'
			) {
				# This might be cell elements, td, th or captions
				if ( $first_two === '|+' ) {
					$first_character = '+';
					$line = substr( $line, 2 );
				} else {
					$line = substr( $line, 1 );
				}

				// Implies both are valid for table headings.
				if ( $first_character === '!' ) {
					$line = StringUtils::replaceMarkup( '!!', '||', $line );
				}

				# Split up multiple cells on the same line.
				# FIXME : This can result in improper nesting of tags processed
				# by earlier parser steps.
				$cells = explode( '||', $line );

				$outLine = '';

				# Loop through each table cell
				foreach ( $cells as $cell ) {
					$previous = '';
					if ( $first_character !== '+' ) {
						$tr_after = array_pop( $tr_attributes );
						if ( !array_pop( $tr_history ) ) {
							$previous = "<tr{$tr_after}>\n";
						}
						$tr_history[] = true;
						$tr_attributes[] = '';
						array_pop( $has_opened_tr );
						$has_opened_tr[] = true;
					}

					$last_tag = array_pop( $last_tag_history );

					if ( array_pop( $td_history ) ) {
						$previous = "</{$last_tag}>\n{$previous}";
					}

					if ( $first_character === '|' ) {
						$last_tag = 'td';
					} elseif ( $first_character === '!' ) {
						$last_tag = 'th';
					} elseif ( $first_character === '+' ) {
						$last_tag = 'caption';
					} else {
						$last_tag = '';
					}

					$last_tag_history[] = $last_tag;

					# A cell could contain both parameters and data
					$cell_data = explode( '|', $cell, 2 );

					# T2553: Note that a '|' inside an invalid link should not
					# be mistaken as delimiting cell parameters
					# Bug T153140: Neither should language converter markup.
					if ( preg_match( '/\[\[|-\{/', $cell_data[0] ) === 1 ) {
						$cell = "{$previous}<{$last_tag}>" . trim( $cell );
					} elseif ( count( $cell_data ) == 1 ) {
						// Whitespace in cells is trimmed
						$cell = "{$previous}<{$last_tag}>" . trim( $cell_data[0] );
					} else {
						$attributes = $this->mStripState->unstripBoth( $cell_data[0] );
						$attributes = Sanitizer::fixTagAttributes( $attributes, $last_tag );
						// Whitespace in cells is trimmed
						$cell = "{$previous}<{$last_tag}{$attributes}>" . trim( $cell_data[1] );
					}

					$outLine .= $cell;
					$td_history[] = true;
				}
			}
			$out .= $outLine . "\n";
		}

		# Closing open td, tr && table
		while ( count( $td_history ) > 0 ) {
			if ( array_pop( $td_history ) ) {
				$out .= "</td>\n";
			}
			if ( array_pop( $tr_history ) ) {
				$out .= "</tr>\n";
			}
			if ( !array_pop( $has_opened_tr ) ) {
				$out .= "<tr><td></td></tr>\n";
			}

			$out .= "</table>\n";
		}

		# Remove trailing line-ending (b/c)
		if ( substr( $out, -1 ) === "\n" ) {
			$out = substr( $out, 0, -1 );
		}

		# special case: don't return empty table
		if ( $out === "<table>\n<tr><td></td></tr>\n</table>" ) {
			$out = '';
		}

		return $out;
	}

	/**
	 * Helper function for parse() that transforms wiki markup into half-parsed
	 * HTML. Only called for $mOutputType == self::OT_HTML.
	 *
	 * @internal
	 *
	 * @param string $text The text to parse
	 * @param-taint $text escapes_html
	 * @param bool $isMain Whether this is being called from the main parse() function
	 * @param PPFrame|false $frame A pre-processor frame
	 *
	 * @return string
	 */
	public function internalParse( $text, $isMain = true, $frame = false ) {
		$origText = $text;

		# Hook to suspend the parser in this state
		if ( !$this->hookRunner->onParserBeforeInternalParse( $this, $text, $this->mStripState ) ) {
			return $text;
		}

		# if $frame is provided, then use $frame for replacing any variables
		if ( $frame ) {
			# use frame depth to infer how include/noinclude tags should be handled
			# depth=0 means this is the top-level document; otherwise it's an included document
			if ( !$frame->depth ) {
				$flag = 0;
			} else {
				$flag = Preprocessor::DOM_FOR_INCLUSION;
			}
			$dom = $this->preprocessToDom( $text, $flag );
			$text = $frame->expand( $dom );
		} else {
			# if $frame is not provided, then use old-style replaceVariables
			$text = $this->replaceVariables( $text );
		}

		$text = Sanitizer::internalRemoveHtmlTags(
			$text,
			// Callback from the Sanitizer for expanding items found in
			// HTML attribute values, so they can be safely tested and escaped.
			function ( &$text, $frame = false ) {
				$text = $this->replaceVariables( $text, $frame );
				$text = $this->mStripState->unstripBoth( $text );
			},
			false,
			[],
			[]
		);
		$this->hookRunner->onInternalParseBeforeLinks( $this, $text, $this->mStripState );

		# Tables need to come after variable replacement for things to work
		# properly; putting them before other transformations should keep
		# exciting things like link expansions from showing up in surprising
		# places.
		$text = $this->handleTables( $text );

		$text = preg_replace( '/(^|\n)-----*/', '\\1<hr />', $text );

		$text = $this->handleDoubleUnderscore( $text );

		$text = $this->handleHeadings( $text );
		$text = $this->handleInternalLinks( $text );
		$text = $this->handleAllQuotes( $text );
		$text = $this->handleExternalLinks( $text );

		# handleInternalLinks may sometimes leave behind
		# absolute URLs, which have to be masked to hide them from handleExternalLinks
		$text = str_replace( self::MARKER_PREFIX . 'NOPARSE', '', $text );

		$text = $this->handleMagicLinks( $text );
		$text = $this->finalizeHeadings( $text, $origText, $isMain );

		return $text;
	}

	/**
	 * Shorthand for getting a Language Converter for Target language
	 *
	 * @since public since 1.38
	 * @return ILanguageConverter
	 */
	public function getTargetLanguageConverter(): ILanguageConverter {
		return $this->languageConverterFactory->getLanguageConverter(
			$this->getTargetLanguage()
		);
	}

	/**
	 * Shorthand for getting a Language Converter for Content language
	 */
	private function getContentLanguageConverter(): ILanguageConverter {
		return $this->languageConverterFactory->getLanguageConverter(
			$this->getContentLanguage()
		);
	}

	/**
	 * Get a HookContainer capable of returning metadata about hooks or running
	 * extension hooks.
	 *
	 * @since 1.35
	 * @return HookContainer
	 */
	protected function getHookContainer() {
		return $this->hookContainer;
	}

	/**
	 * Get a HookRunner for calling core hooks
	 *
	 * @internal This is for use by core only. Hook interfaces may be removed
	 *   without notice.
	 * @since 1.35
	 * @return HookRunner
	 */
	protected function getHookRunner() {
		return $this->hookRunner;
	}

	/**
	 * Helper function for parse() that transforms half-parsed HTML into fully
	 * parsed HTML.
	 *
	 * @param string $text
	 * @param bool $isMain
	 * @param bool $linestart
	 * @return string
	 */
	private function internalParseHalfParsed( $text, $isMain = true, $linestart = true ) {
		$text = $this->mStripState->unstripGeneral( $text );

		$text = BlockLevelPass::doBlockLevels( $text, $linestart );

		$this->replaceLinkHoldersPrivate( $text );

		/**
		 * The input doesn't get language converted if
		 * a) It's disabled
		 * b) Content isn't converted
		 * c) It's a conversion table
		 * d) it is an interface message (which is in the user language)
		 */
		$converter = null;
		if ( !( $this->mOptions->getDisableContentConversion()
			|| isset( $this->mDoubleUnderscores['nocontentconvert'] )
			|| $this->mOptions->getInterfaceMessage() )
		) {
			# The position of the convert() call should not be changed. it
			# assumes that the links are all replaced and the only thing left
			# is the <nowiki> mark.
			$converter = $this->getTargetLanguageConverter();
			$text = $converter->convert( $text );
			// TOC will be converted below.
		}
		// Convert the TOC.   This is done *after* the main text
		// so that all the editor-defined conversion rules (by convention
		// defined at the start of the article) are applied to the TOC
		self::localizeTOC(
			$this->mOutput->getTOCData(),
			$this->getTargetLanguage(),
			$converter // null if conversion is to be suppressed.
		);
		if ( $converter ) {
			$this->mOutput->setLanguage( new Bcp47CodeValue(
				LanguageCode::bcp47( $converter->getPreferredVariant() )
			) );
		} else {
			$this->mOutput->setLanguage( $this->getTargetLanguage() );
		}

		$text = $this->mStripState->unstripNoWiki( $text );

		$text = $this->mStripState->unstripGeneral( $text );

		$text = $this->tidy->tidy( $text, [ Sanitizer::class, 'armorFrenchSpaces' ] );

		if ( $isMain ) {
			$this->hookRunner->onParserAfterTidy( $this, $text );
		}

		return $text;
	}

	/**
	 * Replace special strings like "ISBN xxx" and "RFC xxx" with
	 * magic external links.
	 *
	 * DML
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	private function handleMagicLinks( $text ) {
		$prots = $this->urlUtils->validAbsoluteProtocols();
		$urlChar = self::EXT_LINK_URL_CLASS;
		$addr = self::EXT_LINK_ADDR;
		$space = self::SPACE_NOT_NL; #  non-newline space
		$spdash = "(?:-|$space)"; # a dash or a non-newline space
		$spaces = "$space++"; # possessive match of 1 or more spaces
		$text = preg_replace_callback(
			'!(?:                        # Start cases
				(<a[ \t\r\n>].*?</a>) |    # m[1]: Skip link text
				(<.*?>) |                  # m[2]: Skip stuff inside HTML elements' . "
				(\b                        # m[3]: Free external links
					(?i:$prots)
					($addr$urlChar*)         # m[4]: Post-protocol path
				) |
				\b(?:RFC|PMID) $spaces     # m[5]: RFC or PMID, capture number
					([0-9]+)\b |
				\bISBN $spaces (           # m[6]: ISBN, capture number
					(?: 97[89] $spdash? )?   #  optional 13-digit ISBN prefix
					(?: [0-9]  $spdash? ){9} #  9 digits with opt. delimiters
					[0-9Xx]                  #  check digit
				)\b
			)!xu",
			$this->magicLinkCallback( ... ),
			$text
		);
		return $text;
	}

	/**
	 * @param array $m
	 * @return string HTML
	 */
	private function magicLinkCallback( array $m ) {
		if ( isset( $m[1] ) && $m[1] !== '' ) {
			# Skip anchor
			return $m[0];
		} elseif ( isset( $m[2] ) && $m[2] !== '' ) {
			# Skip HTML element
			return $m[0];
		} elseif ( isset( $m[3] ) && $m[3] !== '' ) {
			# Free external link
			return $this->makeFreeExternalLink( $m[0], strlen( $m[4] ) );
		} elseif ( isset( $m[5] ) && $m[5] !== '' ) {
			# RFC or PMID
			if ( str_starts_with( $m[0], 'RFC' ) ) {
				if ( !$this->mOptions->getMagicRFCLinks() ) {
					return $m[0];
				}
				$keyword = 'RFC';
				$urlmsg = 'rfcurl';
				$cssClass = 'mw-magiclink-rfc';
				$trackingCat = 'magiclink-tracking-rfc';
				$id = $m[5];
			} elseif ( str_starts_with( $m[0], 'PMID' ) ) {
				if ( !$this->mOptions->getMagicPMIDLinks() ) {
					return $m[0];
				}
				$keyword = 'PMID';
				$urlmsg = 'pubmedurl';
				$cssClass = 'mw-magiclink-pmid';
				$trackingCat = 'magiclink-tracking-pmid';
				$id = $m[5];
			} else {
				// Should never happen
				throw new UnexpectedValueException( __METHOD__ . ': unrecognised match type "' .
					substr( $m[0], 0, 20 ) . '"' );
			}
			$url = wfMessage( $urlmsg, $id )->inContentLanguage()->text();
			$this->addTrackingCategory( $trackingCat );
			return $this->getLinkRenderer()->makeExternalLink(
				$url,
				"{$keyword} {$id}",
				$this->getTitle(),
				$cssClass,
				[]
			);
		} elseif ( isset( $m[6] ) && $m[6] !== ''
			&& $this->mOptions->getMagicISBNLinks()
		) {
			# ISBN
			$isbn = $m[6];
			$space = self::SPACE_NOT_NL; #  non-newline space
			$isbn = preg_replace( "/$space/", ' ', $isbn );
			$num = strtr( $isbn, [
				'-' => '',
				' ' => '',
				'x' => 'X',
			] );
			$this->addTrackingCategory( 'magiclink-tracking-isbn' );
			return $this->getLinkRenderer()->makeKnownLink(
				SpecialPage::getTitleFor( 'Booksources', $num ),
				"ISBN $isbn",
				[
					'class' => 'internal mw-magiclink-isbn',
					'title' => false // suppress title attribute
				]
			);
		} else {
			return $m[0];
		}
	}

	/**
	 * Make a free external link, given a user-supplied URL
	 *
	 * @param string $url
	 * @param int $numPostProto
	 *   The number of characters after the protocol.
	 * @return string HTML
	 * @internal
	 */
	private function makeFreeExternalLink( $url, $numPostProto ) {
		$trail = '';

		# The characters '<' and '>' (which were escaped by
		# internalRemoveHtmlTags()) should not be included in
		# URLs, per RFC 2396.
		# Make &nbsp; terminate a URL as well (bug T84937)
		$m2 = [];
		if ( preg_match(
			'/&(lt|gt|nbsp|#x0*(3[CcEe]|[Aa]0)|#0*(60|62|160));/',
			$url,
			$m2,
			PREG_OFFSET_CAPTURE
		) ) {
			$trail = substr( $url, $m2[0][1] ) . $trail;
			$url = substr( $url, 0, $m2[0][1] );
		}

		# Move trailing punctuation to $trail
		$sep = ',;\.:!?';
		# If there is no left bracket, then consider right brackets fair game too
		if ( strpos( $url, '(' ) === false ) {
			$sep .= ')';
		}

		$urlRev = strrev( $url );
		$numSepChars = strspn( $urlRev, $sep );
		# Don't break a trailing HTML entity by moving the ; into $trail
		# This is in hot code, so use substr_compare to avoid having to
		# create a new string object for the comparison
		if ( $numSepChars && substr_compare( $url, ";", -$numSepChars, 1 ) === 0 ) {
			# more optimization: instead of running preg_match with a $
			# anchor, which can be slow, do the match on the reversed
			# string starting at the desired offset.
			# un-reversed regexp is: /&([a-z]+|#x[\da-f]+|#\d+)$/i
			if ( preg_match( '/\G([a-z]+|[\da-f]+x#|\d+#)&/i', $urlRev, $m2, 0, $numSepChars ) ) {
				$numSepChars--;
			}
		}
		if ( $numSepChars ) {
			$trail = substr( $url, -$numSepChars ) . $trail;
			$url = substr( $url, 0, -$numSepChars );
		}

		# Verify that we still have a real URL after trail removal, and
		# not just lone protocol
		if ( strlen( $trail ) >= $numPostProto ) {
			return $url . $trail;
		}

		$url = Sanitizer::cleanUrl( $url );

		# Is this an external image?
		$text = $this->maybeMakeExternalImage( $url );
		if ( $text === false ) {
			# Not an image, make a link
			$text = $this->getLinkRenderer()->makeExternalLink(
				$url,
				$this->getTargetLanguageConverter()->markNoConversion( $url ),
				$this->getTitle(),
				'free',
				$this->getExternalLinkAttribs( $url )
			);
			# Register it in the output object...
			$this->mOutput->addExternalLink( $url );
		}
		return $text . $trail;
	}

	/**
	 * Parse headers and return html
	 *
	 * @param string $text
	 * @return string
	 */
	private function handleHeadings( $text ) {
		for ( $i = 6; $i >= 1; --$i ) {
			$h = str_repeat( '=', $i );
			// Trim non-newline whitespace from headings
			// Using \s* will break for: "==\n===\n" and parse as <h2>=</h2>
			$text = preg_replace( "/^(?:$h)[ \\t]*(.+?)[ \\t]*(?:$h)\\s*$/m", "<h$i>\\1</h$i>", $text );
		}
		return $text;
	}

	/**
	 * Replace single quotes with HTML markup
	 *
	 * @param string $text
	 *
	 * @return string The altered text
	 */
	private function handleAllQuotes( $text ) {
		$outtext = '';
		$lines = StringUtils::explode( "\n", $text );
		foreach ( $lines as $line ) {
			$outtext .= $this->doQuotes( $line ) . "\n";
		}
		$outtext = substr( $outtext, 0, -1 );
		return $outtext;
	}

	/**
	 * Helper function for handleAllQuotes()
	 *
	 * @param string $text
	 *
	 * @return string
	 * @internal
	 */
	public function doQuotes( $text ) {
		$arr = preg_split( "/(''+)/", $text, -1, PREG_SPLIT_DELIM_CAPTURE );
		$countarr = count( $arr );
		if ( $countarr == 1 ) {
			return $text;
		}

		// First, do some preliminary work. This may shift some apostrophes from
		// being mark-up to being text. It also counts the number of occurrences
		// of bold and italics mark-ups.
		$numbold = 0;
		$numitalics = 0;
		for ( $i = 1; $i < $countarr; $i += 2 ) {
			$thislen = strlen( $arr[$i] );
			// If there are ever four apostrophes, assume the first is supposed to
			// be text, and the remaining three constitute mark-up for bold text.
			// (T15227: ''''foo'''' turns into ' ''' foo ' ''')
			if ( $thislen == 4 ) {
				$arr[$i - 1] .= "'";
				$arr[$i] = "'''";
				$thislen = 3;
			} elseif ( $thislen > 5 ) {
				// If there are more than 5 apostrophes in a row, assume they're all
				// text except for the last 5.
				// (T15227: ''''''foo'''''' turns into ' ''''' foo ' ''''')
				$arr[$i - 1] .= str_repeat( "'", $thislen - 5 );
				$arr[$i] = "'''''";
				$thislen = 5;
			}
			// Count the number of occurrences of bold and italics mark-ups.
			if ( $thislen == 2 ) {
				$numitalics++;
			} elseif ( $thislen == 3 ) {
				$numbold++;
			} elseif ( $thislen == 5 ) {
				$numitalics++;
				$numbold++;
			}
		}

		// If there is an odd number of both bold and italics, it is likely
		// that one of the bold ones was meant to be an apostrophe followed
		// by italics. Which one we cannot know for certain, but it is more
		// likely to be one that has a single-letter word before it.
		if ( ( $numbold % 2 == 1 ) && ( $numitalics % 2 == 1 ) ) {
			$firstsingleletterword = -1;
			$firstmultiletterword = -1;
			$firstspace = -1;
			for ( $i = 1; $i < $countarr; $i += 2 ) {
				if ( strlen( $arr[$i] ) == 3 ) {
					$x1 = substr( $arr[$i - 1], -1 );
					$x2 = substr( $arr[$i - 1], -2, 1 );
					if ( $x1 === ' ' ) {
						if ( $firstspace == -1 ) {
							$firstspace = $i;
						}
					} elseif ( $x2 === ' ' ) {
						$firstsingleletterword = $i;
						// if $firstsingleletterword is set, we don't
						// look at the other options, so we can bail early.
						break;
					} elseif ( $firstmultiletterword == -1 ) {
						$firstmultiletterword = $i;
					}
				}
			}

			// If there is a single-letter word, use it!
			if ( $firstsingleletterword > -1 ) {
				$arr[$firstsingleletterword] = "''";
				$arr[$firstsingleletterword - 1] .= "'";
			} elseif ( $firstmultiletterword > -1 ) {
				// If not, but there's a multi-letter word, use that one.
				$arr[$firstmultiletterword] = "''";
				$arr[$firstmultiletterword - 1] .= "'";
			} elseif ( $firstspace > -1 ) {
				// ... otherwise use the first one that has neither.
				// (notice that it is possible for all three to be -1 if, for example,
				// there is only one pentuple-apostrophe in the line)
				$arr[$firstspace] = "''";
				$arr[$firstspace - 1] .= "'";
			}
		}

		// Now let's actually convert our apostrophic mush to HTML!
		$output = '';
		$buffer = '';
		$state = '';
		$i = 0;
		foreach ( $arr as $r ) {
			if ( ( $i % 2 ) == 0 ) {
				if ( $state === 'both' ) {
					$buffer .= $r;
				} else {
					$output .= $r;
				}
			} else {
				$thislen = strlen( $r );
				if ( $thislen == 2 ) {
					// two quotes - open or close italics
					if ( $state === 'i' ) {
						$output .= '</i>';
						$state = '';
					} elseif ( $state === 'bi' ) {
						$output .= '</i>';
						$state = 'b';
					} elseif ( $state === 'ib' ) {
						$output .= '</b></i><b>';
						$state = 'b';
					} elseif ( $state === 'both' ) {
						$output .= '<b><i>' . $buffer . '</i>';
						$state = 'b';
					} else { // $state can be 'b' or ''
						$output .= '<i>';
						$state .= 'i';
					}
				} elseif ( $thislen == 3 ) {
					// three quotes - open or close bold
					if ( $state === 'b' ) {
						$output .= '</b>';
						$state = '';
					} elseif ( $state === 'bi' ) {
						$output .= '</i></b><i>';
						$state = 'i';
					} elseif ( $state === 'ib' ) {
						$output .= '</b>';
						$state = 'i';
					} elseif ( $state === 'both' ) {
						$output .= '<i><b>' . $buffer . '</b>';
						$state = 'i';
					} else { // $state can be 'i' or ''
						$output .= '<b>';
						$state .= 'b';
					}
				} elseif ( $thislen == 5 ) {
					// five quotes - open or close both separately
					if ( $state === 'b' ) {
						$output .= '</b><i>';
						$state = 'i';
					} elseif ( $state === 'i' ) {
						$output .= '</i><b>';
						$state = 'b';
					} elseif ( $state === 'bi' ) {
						$output .= '</i></b>';
						$state = '';
					} elseif ( $state === 'ib' ) {
						$output .= '</b></i>';
						$state = '';
					} elseif ( $state === 'both' ) {
						$output .= '<i><b>' . $buffer . '</b></i>';
						$state = '';
					} else { // ($state == '')
						$buffer = '';
						$state = 'both';
					}
				}
			}
			$i++;
		}
		// Now close all remaining tags.  Notice that the order is important.
		if ( $state === 'b' || $state === 'ib' ) {
			$output .= '</b>';
		}
		if ( $state === 'i' || $state === 'bi' || $state === 'ib' ) {
			$output .= '</i>';
		}
		if ( $state === 'bi' ) {
			$output .= '</b>';
		}
		// There might be lonely ''''', so make sure we have a buffer
		if ( $state === 'both' && $buffer ) {
			$output .= '<b><i>' . $buffer . '</i></b>';
		}
		return $output;
	}

	/**
	 * Replace external links (REL)
	 *
	 * Note: this is all very hackish and the order of execution matters a lot.
	 * Make sure to run tests/parser/parserTests.php if you change this code.
	 *
	 * @param string $text
	 * @return string
	 */
	private function handleExternalLinks( $text ) {
		$bits = preg_split( $this->mExtLinkBracketedRegex, $text, -1, PREG_SPLIT_DELIM_CAPTURE );
		if ( $bits === false ) {
			throw new RuntimeException( "PCRE failure" );
		}
		$s = array_shift( $bits );

		$i = 0;
		while ( $i < count( $bits ) ) {
			$url = $bits[$i++];
			$i++; // protocol
			$text = $bits[$i++];
			$trail = $bits[$i++];

			# The characters '<' and '>' (which were escaped by
			# internalRemoveHtmlTags()) should not be included in
			# URLs, per RFC 2396.
			$m2 = [];
			if ( preg_match( '/&(lt|gt);/', $url, $m2, PREG_OFFSET_CAPTURE ) ) {
				$text = substr( $url, $m2[0][1] ) . ' ' . $text;
				$url = substr( $url, 0, $m2[0][1] );
			}

			# If the link text is an image URL, replace it with an <img> tag
			# This happened by accident in the original parser, but some people used it extensively
			$img = $this->maybeMakeExternalImage( $text );
			if ( $img !== false ) {
				$text = $img;
			}

			$dtrail = '';

			# Set linktype for CSS
			$linktype = 'text';

			# No link text, e.g. [http://domain.tld/some.link]
			if ( $text == '' ) {
				# Autonumber
				$langObj = $this->getTargetLanguage();
				$text = '[' . $langObj->formatNum( ++$this->mAutonumber ) . ']';
				$linktype = 'autonumber';
			} else {
				# Have link text, e.g. [http://domain.tld/some.link text]s
				# Check for trail
				[ $dtrail, $trail ] = Linker::splitTrail( $trail );
			}

			// Excluding protocol-relative URLs may avoid many false positives.
			if ( preg_match( '/^(?:' . $this->urlUtils->validAbsoluteProtocols() . ')/', $text ) ) {
				$text = $this->getTargetLanguageConverter()->markNoConversion( $text );
			}

			$url = Sanitizer::cleanUrl( $url );

			# Use the encoded URL
			# This means that users can paste URLs directly into the text
			# Funny characters like ö aren't valid in URLs anyway
			# This was changed in August 2004
			$s .= $this->getLinkRenderer()->makeExternalLink(
				$url,
				// @phan-suppress-next-line SecurityCheck-XSS
				new HtmlArmor( $text ),
				$this->getTitle(),
				$linktype,
				$this->getExternalLinkAttribs( $url )
			) . $dtrail . $trail;

			# Register link in the output object.
			$this->mOutput->addExternalLink( $url );
		}

		// @phan-suppress-next-line PhanTypeMismatchReturnNullable False positive from array_shift
		return $s;
	}

	/**
	 * Get the rel attribute for a particular external link.
	 *
	 * @since 1.21
	 * @internal
	 * @param string|false $url Optional URL, to extract the domain from for rel =>
	 *   nofollow if appropriate
	 * @param LinkTarget|PageReference|null $title Optional page, for wgNoFollowNsExceptions lookups
	 * @return string|null Rel attribute for $url
	 */
	public static function getExternalLinkRel( $url = false, $title = null ) {
		$mainConfig = MediaWikiServices::getInstance()->getMainConfig();
		$noFollowLinks = $mainConfig->get( MainConfigNames::NoFollowLinks );
		$noFollowNsExceptions = $mainConfig->get( MainConfigNames::NoFollowNsExceptions );
		$noFollowDomainExceptions = $mainConfig->get( MainConfigNames::NoFollowDomainExceptions );
		$ns = $title ? $title->getNamespace() : false;
		if (
			$noFollowLinks && !in_array( $ns, $noFollowNsExceptions )
			&& !wfGetUrlUtils()->matchesDomainList( (string)$url, $noFollowDomainExceptions )
		) {
			return 'nofollow';
		}
		return null;
	}

	/**
	 * Get an associative array of additional HTML attributes appropriate for a
	 * particular external link.  This currently may include rel => nofollow
	 * (depending on configuration, namespace, and the URL's domain) and/or a
	 * target attribute (depending on configuration).
	 *
	 * @internal
	 * @param string $url URL to extract the domain from for rel =>
	 *   nofollow if appropriate
	 * @return array Associative array of HTML attributes
	 */
	public function getExternalLinkAttribs( $url ) {
		$attribs = [];
		$rel = self::getExternalLinkRel( $url, $this->getTitle() ) ?? '';

		$target = $this->mOptions->getExternalLinkTarget();
		if ( $target ) {
			$attribs['target'] = $target;
			if ( !in_array( $target, [ '_self', '_parent', '_top' ] ) ) {
				// T133507. New windows can navigate parent cross-origin.
				// Including noreferrer due to lacking browser
				// support of noopener. Eventually noreferrer should be removed.
				if ( $rel !== '' ) {
					$rel .= ' ';
				}
				$rel .= 'noreferrer noopener';
			}
		}
		if ( $rel !== '' ) {
			$attribs['rel'] = $rel;
		}
		return $attribs;
	}

	/**
	 * Replace unusual escape codes in a URL with their equivalent characters
	 *
	 * This generally follows the syntax defined in RFC 3986, with special
	 * consideration for HTTP query strings.
	 *
	 * @internal
	 * @param string $url
	 * @return string
	 */
	public static function normalizeLinkUrl( $url ) {
		# Test for RFC 3986 IPv6 syntax
		$scheme = '[a-z][a-z0-9+.-]*:';
		$userinfo = '(?:[a-z0-9\-._~!$&\'()*+,;=:]|%[0-9a-f]{2})*';
		$ipv6Host = '\\[((?:[0-9a-f:]|%3[0-A]|%[46][1-6])+)\\]';
		if ( preg_match( "<^(?:{$scheme})?//(?:{$userinfo}@)?{$ipv6Host}(?:[:/?#].*|)$>i", $url, $m ) &&
			IPUtils::isValid( rawurldecode( $m[1] ) )
		) {
			$isIPv6 = rawurldecode( $m[1] );
		} else {
			$isIPv6 = false;
		}

		# Make sure unsafe characters are encoded
		$url = preg_replace_callback(
			'/[\x00-\x20"<>\[\\\\\]^`{|}\x7F-\xFF]+/',
			static fn ( $m ) => rawurlencode( $m[0] ),
			$url
		);

		$ret = '';
		$end = strlen( $url );

		# Fragment part - 'fragment'
		$start = strpos( $url, '#' );
		if ( $start !== false && $start < $end ) {
			$ret = self::normalizeUrlComponent(
				substr( $url, $start, $end - $start ), '"#%<>[\]^`{|}' ) . $ret;
			$end = $start;
		}

		# Query part - 'query' minus &=+;
		$start = strpos( $url, '?' );
		if ( $start !== false && $start < $end ) {
			$ret = self::normalizeUrlComponent(
				substr( $url, $start, $end - $start ), '"#%<>[\]^`{|}&=+;' ) . $ret;
			$end = $start;
		}

		# Path part - 'pchar', remove dot segments
		# (find first '/' after the optional '//' after the scheme)
		$start = strpos( $url, '//' );
		$start = strpos( $url, '/', $start === false ? 0 : $start + 2 );
		if ( $start !== false && $start < $end ) {
			$ret = UrlUtils::removeDotSegments( self::normalizeUrlComponent(
				substr( $url, $start, $end - $start ), '"#%<>[\]^`{|}/?' ) ) . $ret;
			$end = $start;
		}

		# Scheme and host part - 'pchar'
		# (we assume no userinfo or encoded colons in the host)
		$ret = self::normalizeUrlComponent(
			substr( $url, 0, $end ), '"#%<>[\]^`{|}/?' ) . $ret;

		# Fix IPv6 syntax
		if ( $isIPv6 !== false ) {
			$ipv6Host = "%5B({$isIPv6})%5D";
			$ret = preg_replace(
				"<^((?:{$scheme})?//(?:{$userinfo}@)?){$ipv6Host}(?=[:/?#]|$)>i",
				"$1[$2]",
				$ret
			);
		}

		return $ret;
	}

	private static function normalizeUrlComponent( string $component, string $unsafe ): string {
		$callback = static function ( $matches ) use ( $unsafe ) {
			$char = urldecode( $matches[0] );
			$ord = ord( $char );
			if ( $ord > 32 && $ord < 127 && strpos( $unsafe, $char ) === false ) {
				# Unescape it
				return $char;
			} else {
				# Leave it escaped, but use uppercase for a-f
				return strtoupper( $matches[0] );
			}
		};
		return preg_replace_callback( '/%[0-9A-Fa-f]{2}/', $callback, $component );
	}

	/**
	 * make an image if it's allowed, either through the global
	 * option, through the exception, or through the on-wiki whitelist
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	private function maybeMakeExternalImage( $url ) {
		$imagesfrom = $this->mOptions->getAllowExternalImagesFrom();
		$imagesexception = (bool)$imagesfrom;
		$text = false;
		# $imagesfrom could be either a single string or an array of strings, parse out the latter
		if ( $imagesexception && is_array( $imagesfrom ) ) {
			$imagematch = false;
			foreach ( $imagesfrom as $match ) {
				if ( str_starts_with( $url, $match ) ) {
					$imagematch = true;
					break;
				}
			}
		} elseif ( $imagesexception ) {
			$imagematch = str_starts_with( $url, $imagesfrom );
		} else {
			$imagematch = false;
		}

		if ( $this->mOptions->getAllowExternalImages()
			|| ( $imagesexception && $imagematch )
		) {
			if ( preg_match( self::EXT_IMAGE_REGEX, $url ) ) {
				# Image found
				$text = Linker::makeExternalImage( $url );
			}
		}
		if ( !$text && $this->mOptions->getEnableImageWhitelist()
			&& preg_match( self::EXT_IMAGE_REGEX, $url )
		) {
			$whitelist = explode(
				"\n",
				wfMessage( 'external_image_whitelist' )->inContentLanguage()->text()
			);

			foreach ( $whitelist as $entry ) {
				# Sanitize the regex fragment, make it case-insensitive, ignore blank entries/comments
				if ( $entry === '' || str_starts_with( $entry, '#' ) ) {
					continue;
				}
				// @phan-suppress-next-line SecurityCheck-ReDoS preg_quote is not wanted here
				if ( preg_match( '/' . str_replace( '/', '\\/', $entry ) . '/i', $url ) ) {
					# Image matches a whitelist entry
					$text = Linker::makeExternalImage( $url );
					break;
				}
			}
		}
		return $text;
	}

	/**
	 * Process [[ ]] wikilinks
	 *
	 * @param string $text
	 *
	 * @return string Processed text
	 */
	private function handleInternalLinks( $text ) {
		$this->mLinkHolders->merge( $this->handleInternalLinks2( $text ) );
		return $text;
	}

	/**
	 * Process [[ ]] wikilinks (RIL)
	 * @param string &$s
	 * @return LinkHolderArray
	 */
	private function handleInternalLinks2( &$s ) {
		static $tc = false, $e1, $e1_img;
		# the % is needed to support urlencoded titles as well
		if ( !$tc ) {
			$tc = Title::legalChars() . '#%';
			# Match a link having the form [[namespace:link|alternate]]trail
			$e1 = "/^([{$tc}]+)(?:\\|(.+?))?]](.*)\$/sD";
			# Match cases where there is no "]]", which might still be images
			$e1_img = "/^([{$tc}]+)\\|(.*)\$/sD";
		}

		$holders = new LinkHolderArray(
			$this,
			$this->getContentLanguageConverter(),
			$this->getHookContainer() );

		# split the entire text string on occurrences of [[
		$a = StringUtils::explode( '[[', ' ' . $s );
		# get the first element (all text up to first [[), and remove the space we added
		$s = $a->current();
		$a->next();
		$line = $a->current(); # Workaround for broken ArrayIterator::next() that returns "void"
		$s = substr( $s, 1 );

		$nottalk = !$this->getTitle()->isTalkPage();

		$useLinkPrefixExtension = $this->getTargetLanguage()->linkPrefixExtension();
		$e2 = null;
		if ( $useLinkPrefixExtension ) {
			# Match the end of a line for a word that's not followed by whitespace,
			# e.g. in the case of 'The Arab al[[Razi]]', 'al' will be matched
			$charset = $this->contLang->linkPrefixCharset();
			$e2 = "/^((?>.*[^$charset]|))(.+)$/sDu";
			$m = [];
			if ( preg_match( $e2, $s, $m ) ) {
				$first_prefix = $m[2];
			} else {
				$first_prefix = false;
			}
			$prefix = false;
		} else {
			$first_prefix = false;
			$prefix = '';
		}

		# Some namespaces don't allow subpages
		$useSubpages = $this->nsInfo->hasSubpages(
			$this->getTitle()->getNamespace()
		);

		# Loop for each link
		for ( ; $line !== false && $line !== null; $a->next(), $line = $a->current() ) {
			# Check for excessive memory usage
			if ( $holders->isBig() ) {
				# Too big
				# Do the existence check, replace the link holders and clear the array
				$holders->replace( $s );
				$holders->clear();
			}

			if ( $useLinkPrefixExtension ) {
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullableInternal $e2 is set under this condition
				if ( preg_match( $e2, $s, $m ) ) {
					[ , $s, $prefix ] = $m;
				} else {
					$prefix = '';
				}
				# first link
				if ( $first_prefix ) {
					$prefix = $first_prefix;
					$first_prefix = false;
				}
			}

			$might_be_img = false;

			if ( preg_match( $e1, $line, $m ) ) { # page with normal text or alt
				$text = $m[2];
				# If we get a ] at the beginning of $m[3] that means we have a link that's something like:
				# [[Image:Foo.jpg|[http://example.com desc]]] <- having three ] in a row fucks up,
				# the real problem is with the $e1 regex
				# See T1500.
				# Still some problems for cases where the ] is meant to be outside punctuation,
				# and no image is in sight. See T4095.
				if ( $text !== ''
					&& substr( $m[3], 0, 1 ) === ']'
					&& strpos( $text, '[' ) !== false
				) {
					$text .= ']'; # so that handleExternalLinks($text) works later
					$m[3] = substr( $m[3], 1 );
				}
				# fix up urlencoded title texts
				if ( strpos( $m[1], '%' ) !== false ) {
					# Should anchors '#' also be rejected?
					$m[1] = str_replace( [ '<', '>' ], [ '&lt;', '&gt;' ], rawurldecode( $m[1] ) );
				}
				$trail = $m[3];
			} elseif ( preg_match( $e1_img, $line, $m ) ) {
				# Invalid, but might be an image with a link in its caption
				$might_be_img = true;
				$text = $m[2];
				if ( strpos( $m[1], '%' ) !== false ) {
					$m[1] = str_replace( [ '<', '>' ], [ '&lt;', '&gt;' ], rawurldecode( $m[1] ) );
				}
				$trail = "";
			} else { # Invalid form; output directly
				$s .= $prefix . '[[' . $line;
				continue;
			}

			// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset preg_match success when reached here
			$origLink = ltrim( $m[1], ' ' );

			# Don't allow internal links to pages containing
			# PROTO: where PROTO is a valid URL protocol; these
			# should be external links.
			if ( preg_match( '/^(?i:' . $this->urlUtils->validProtocols() . ')/', $origLink ) ) {
				$s .= $prefix . '[[' . $line;
				continue;
			}

			# Make subpage if necessary
			if ( $useSubpages ) {
				$link = Linker::normalizeSubpageLink(
					$this->getTitle(), $origLink, $text
				);
			} else {
				$link = $origLink;
			}

			// \x7f isn't a default legal title char, so most likely strip
			// markers will force us into the "invalid form" path above.  But,
			// just in case, let's assert that xmlish tags aren't valid in
			// the title position.
			$unstrip = $this->mStripState->killMarkers( $link );
			$noMarkers = ( $unstrip === $link );

			$nt = $noMarkers ? Title::newFromText( $link ) : null;
			if ( $nt === null ) {
				$s .= $prefix . '[[' . $line;
				continue;
			}

			$ns = $nt->getNamespace();
			$iw = $nt->getInterwiki();

			$noforce = !str_starts_with( $origLink, ':' );

			if ( $might_be_img ) { # if this is actually an invalid link
				if ( $ns === NS_FILE && $noforce ) { # but might be an image
					$found = false;
					while ( true ) {
						# look at the next 'line' to see if we can close it there
						$a->next();
						$next_line = $a->current();
						if ( $next_line === false || $next_line === null ) {
							break;
						}
						$m = explode( ']]', $next_line, 3 );
						if ( count( $m ) == 3 ) {
							# the first ]] closes the inner link, the second the image
							$found = true;
							$text .= "[[{$m[0]}]]{$m[1]}";
							$trail = $m[2];
							break;
						} elseif ( count( $m ) == 2 ) {
							# if there's exactly one ]] that's fine, we'll keep looking
							$text .= "[[{$m[0]}]]{$m[1]}";
						} else {
							# if $next_line is invalid too, we need look no further
							$text .= '[[' . $next_line;
							break;
						}
					}
					if ( !$found ) {
						# we couldn't find the end of this imageLink, so output it raw
						# but don't ignore what might be perfectly normal links in the text we've examined
						$holders->merge( $this->handleInternalLinks2( $text ) );
						$s .= "{$prefix}[[$link|$text";
						# note: no $trail, because without an end, there *is* no trail
						continue;
					}
				} else { # it's not an image, so output it raw
					$s .= "{$prefix}[[$link|$text";
					# note: no $trail, because without an end, there *is* no trail
					continue;
				}
			}

			$wasblank = ( $text == '' );
			if ( $wasblank ) {
				$text = $link;
				if ( !$noforce ) {
					# Strip off leading ':'
					$text = substr( $text, 1 );
				}
			} else {
				# T6598 madness. Handle the quotes only if they come from the alternate part
				# [[Lista d''e paise d''o munno]] -> <a href="...">Lista d''e paise d''o munno</a>
				# [[Criticism of Harry Potter|Criticism of ''Harry Potter'']]
				#    -> <a href="Criticism of Harry Potter">Criticism of <i>Harry Potter</i></a>
				$text = $this->doQuotes( $text );
			}

			# Link not escaped by : , create the various objects
			if ( $noforce && !$nt->wasLocalInterwiki() ) {
				# Interwikis
				if (
					$iw && $this->mOptions->getInterwikiMagic() && $nottalk && (
						$this->languageNameUtils->getLanguageName(
							$iw,
							LanguageNameUtils::AUTONYMS,
							LanguageNameUtils::DEFINED
						)
						|| in_array( $iw, $this->svcOptions->get( MainConfigNames::ExtraInterlanguageLinkPrefixes ) )
					)
				) {
					# T26502: duplicates are resolved in ParserOutput
					$this->mOutput->addLanguageLink( $nt );

					/**
					 * Strip the whitespace interlanguage links produce, see
					 * T10897, T175416, and T359886.
					 */
					$s = preg_replace( '/\n\s*$/', '', $s . $prefix ) . $trail;
					continue;
				}

				if ( $ns === NS_FILE ) {
					if ( $wasblank ) {
						# if no parameters were passed, $text
						# becomes something like "File:Foo.png",
						# which we don't want to pass on to the
						# image generator
						$text = '';
					} else {
						# recursively parse links inside the image caption
						# actually, this will parse them in any other parameters, too,
						# but it might be hard to fix that, and it doesn't matter ATM
						$text = $this->handleExternalLinks( $text );
						$holders->merge( $this->handleInternalLinks2( $text ) );
					}
					# cloak any absolute URLs inside the image markup, so handleExternalLinks() won't touch them
					$s .= $prefix . $this->armorLinks(
						$this->makeImage( $nt, $text, $holders ) ) . $trail;
					continue;
				} elseif ( $ns === NS_CATEGORY ) {
					# Strip newlines from the left hand context of Category
					# links.
					# See T2087, T87753, T174639, T359886
					$s = preg_replace( '/\n\s*$/', '', $s . $prefix ) . $trail;

					$sortkey = ''; // filled in by CategoryLinksTable
					if ( !$wasblank ) {
						$sortkey = $text;
					}
					$this->mOutput->addCategory( $nt, $sortkey );

					continue;
				}
			}

			# Self-link checking. For some languages, variants of the title are checked in
			# LinkHolderArray::doVariants() to allow batching the existence checks necessary
			# for linking to a different variant.
			if ( $ns !== NS_SPECIAL && $nt->equals( $this->getTitle() ) ) {
				$s .= $prefix . Linker::makeSelfLinkObj( $nt, $text, '', $trail, '',
					Sanitizer::escapeIdForLink( $nt->getFragment() ) );
				continue;
			}

			# NS_MEDIA is a pseudo-namespace for linking directly to a file
			# @todo FIXME: Should do batch file existence checks, see comment below
			if ( $ns === NS_MEDIA ) {
				# Give extensions a chance to select the file revision for us
				$options = [];
				$descQuery = false;
				$this->hookRunner->onBeforeParserFetchFileAndTitle(
					// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
					$this, $nt, $options, $descQuery
				);
				# Fetch and register the file (file title may be different via hooks)
				[ $file, $nt ] = $this->fetchFileAndTitle( $nt, $options );
				# Cloak with NOPARSE to avoid replacement in handleExternalLinks
				$s .= $prefix . $this->armorLinks(
					Linker::makeMediaLinkFile( $nt, $file, $text ) ) . $trail;
				continue;
			}

			# Some titles, such as valid special pages or files in foreign repos, should
			# be shown as bluelinks even though they're not included in the page table
			# @todo FIXME: isAlwaysKnown() can be expensive for file links; we should really do
			# batch file existence checks for NS_FILE and NS_MEDIA
			if ( $iw == '' && $nt->isAlwaysKnown() ) {
				$this->mOutput->addLink( $nt );
				$s .= $this->makeKnownLinkHolder( $nt, $text, $trail, $prefix );
			} else {
				# Links will be added to the output link list after checking
				$s .= $holders->makeHolder( $nt, $text, $trail, $prefix );
			}
		}
		return $holders;
	}

	/**
	 * Render a forced-blue link inline; protect against double expansion of
	 * URLs if we're in a mode that prepends full URL prefixes to internal links.
	 * Since this little disaster has to split off the trail text to avoid
	 * breaking URLs in the following text without breaking trails on the
	 * wiki links, it's been made into a horrible function.
	 *
	 * @param LinkTarget $nt
	 * @param string $text
	 * @param string $trail
	 * @param string $prefix
	 * @return string HTML-wikitext mix oh yuck
	 */
	private function makeKnownLinkHolder( LinkTarget $nt, $text = '', $trail = '', $prefix = '' ) {
		[ $inside, $trail ] = Linker::splitTrail( $trail );

		if ( $text == '' ) {
			$text = htmlspecialchars( $this->titleFormatter->getPrefixedText( $nt ) );
		}

		$link = $this->getLinkRenderer()->makeKnownLink(
			$nt, new HtmlArmor( "$prefix$text$inside" )
		);

		return $this->armorLinks( $link ) . $trail;
	}

	/**
	 * Insert a NOPARSE hacky thing into any inline links in a chunk that's
	 * going to go through further parsing steps before inline URL expansion.
	 *
	 * Not needed quite as much as it used to be since free links are a bit
	 * more sensible these days. But bracketed links are still an issue.
	 *
	 * @param string $text More-or-less HTML
	 * @return string Less-or-more HTML with NOPARSE bits
	 */
	private function armorLinks( $text ) {
		return preg_replace( '/\b((?i)' . $this->urlUtils->validProtocols() . ')/',
			self::MARKER_PREFIX . "NOPARSE$1", $text );
	}

	/**
	 * Make lists from lines starting with ':', '*', '#', etc. (DBL)
	 *
	 * @param string $text
	 * @param bool $linestart Whether or not this is at the start of a line.
	 * @internal
	 * @return string The lists rendered as HTML
	 * @deprecated since 1.35, will not be supported in future parsers
	 */
	public function doBlockLevels( $text, $linestart ) {
		wfDeprecated( __METHOD__, '1.35' );
		return BlockLevelPass::doBlockLevels( $text, $linestart );
	}

	/**
	 * Return value of a magic variable (like PAGENAME)
	 *
	 * @param string $index Magic variable identifier as mapped in MagicWordFactory::$mVariableIDs
	 * @param PPFrame|false $frame
	 *
	 * @return string
	 */
	private function expandMagicVariable( $index, $frame = false ) {
		/**
		 * Some of these require message or data lookups and can be
		 * expensive to check many times.
		 */
		if ( isset( $this->mVarCache[$index] ) ) {
			return $this->mVarCache[$index];
		}

		$ts = new MWTimestamp( $this->mOptions->getTimestamp() /* TS_MW */ );
		if ( $this->hookContainer->isRegistered( 'ParserGetVariableValueTs' ) ) {
			$s = $ts->getTimestamp( TS_UNIX );
			$this->hookRunner->onParserGetVariableValueTs( $this, $s );
			$ts = new MWTimestamp( $s );
		}

		$value = CoreMagicVariables::expand(
			$this, $index, $ts, $this->svcOptions, $this->logger
		);

		if ( $value === null ) {
			// Not a defined core magic word
			// Don't give this hook unrestricted access to mVarCache
			$fakeCache = [];
			$this->hookRunner->onParserGetVariableValueSwitch(
				// @phan-suppress-next-line PhanTypeMismatchArgument $value is passed as null but returned as string
				$this, $fakeCache, $index, $value, $frame
			);
			// Cache the value returned by the hook by falling through here.
			// Assert the the hook returned a non-null value for this MV
			'@phan-var string $value';
		}

		$this->mVarCache[$index] = $value;

		return $value;
	}

	/**
	 * Initialize the magic variables (like CURRENTMONTHNAME) and
	 * substitution modifiers.
	 */
	private function initializeVariables() {
		$variableIDs = $this->magicWordFactory->getVariableIDs();

		$this->mVariables = $this->magicWordFactory->newArray( $variableIDs );
		$this->mSubstWords = $this->magicWordFactory->getSubstArray();
	}

	/**
	 * Get the document object model for the given wikitext
	 *
	 * @see Preprocessor::preprocessToObj()
	 *
	 * The generated DOM tree must depend only on the input text and the flags.
	 * The DOM tree must be the same in OT_HTML and OT_WIKI mode, to avoid a
	 * regression of T6899.
	 *
	 * Any flag added to the $flags parameter here, or any other parameter liable to cause a
	 * change in the DOM tree for a given text, must be passed through the section identifier
	 * in the section edit link and thus back to extractSections().
	 *
	 * @param string $text Wikitext
	 * @param int $flags Bit field of Preprocessor::DOM_* constants
	 * @return PPNode
	 * @since 1.23 method is public
	 */
	public function preprocessToDom( $text, $flags = 0 ) {
		return $this->getPreprocessor()->preprocessToObj( $text, $flags );
	}

	/**
	 * Replace magic variables, templates, and template arguments
	 * with the appropriate text. Templates are substituted recursively,
	 * taking care to avoid infinite loops.
	 *
	 * Note that the substitution depends on value of $mOutputType:
	 *  self::OT_WIKI: only {{subst:}} templates
	 *  self::OT_PREPROCESS: templates but not extension tags
	 *  self::OT_HTML: all templates and extension tags
	 *
	 * @param string $text The text to transform
	 * @param false|PPFrame|array $frame Object describing the arguments passed to the
	 *   template. Arguments may also be provided as an associative array, as
	 *   was the usual case before MW1.12. Providing arguments this way may be
	 *   useful for extensions wishing to perform variable replacement
	 *   explicitly.
	 * @param bool $argsOnly Only do argument (triple-brace) expansion, not
	 *   double-brace expansion.
	 * @param array $options Various options used by Parsoid:
	 *  - 'parsoidTopLevelCall' Is this coming from Parsoid for top-level templates?
	 *   This is used to set start-of-line flag to true for template expansions since that
	 *   is how Parsoid models templates.
	 *  - 'processNowiki' expands <nowiki> and stores in strip state
	 *
	 * @return string
	 * @since 1.24 method is public
	 */
	public function replaceVariables(
		$text, $frame = false, $argsOnly = false, array $options = []
	) {
		# Is there any text? Also, Prevent too big inclusions!
		$textSize = strlen( $text );
		if ( $textSize < 1 || $textSize > $this->mOptions->getMaxIncludeSize() ) {
			return $text;
		}

		if ( $frame === false ) {
			$frame = $this->getPreprocessor()->newFrame();
		} elseif ( !( $frame instanceof PPFrame ) ) {
			wfDeprecated(
				__METHOD__ . " called using plain parameters instead of " .
				"a PPFrame instance. Creating custom frame.",
				'1.43'
			);
			$frame = $this->getPreprocessor()->newCustomFrame( $frame );
		}

		$ppFlags = 0;
		if ( $options['parsoidTopLevelCall'] ?? false ) {
			$ppFlags |= Preprocessor::START_IN_SOL_STATE;
		}
		$dom = $this->preprocessToDom( $text, $ppFlags );
		$flags = $argsOnly ? PPFrame::NO_TEMPLATES : 0;
		if ( $options['processNowiki'] ?? false ) {
			$flags |= PPFrame::PROCESS_NOWIKI;
		}
		$text = $frame->expand( $dom, $flags );

		return $text;
	}

	/** @internal */
	public function setStripExtTags( bool $val ) {
		$this->mStripExtTags = $val;
	}

	/**
	 * Warn the user when a parser limitation is reached
	 * Will warn at most once the user per limitation type
	 *
	 * The results are shown during preview and run through the Parser (See EditPage.php)
	 *
	 * @param string $limitationType Should be one of:
	 *   'expensive-parserfunction' (corresponding messages:
	 *       'expensive-parserfunction-warning',
	 *       'expensive-parserfunction-category')
	 *   'post-expand-template-argument' (corresponding messages:
	 *       'post-expand-template-argument-warning',
	 *       'post-expand-template-argument-category')
	 *   'post-expand-template-inclusion' (corresponding messages:
	 *       'post-expand-template-inclusion-warning',
	 *       'post-expand-template-inclusion-category')
	 *   'node-count-exceeded' (corresponding messages:
	 *       'node-count-exceeded-warning',
	 *       'node-count-exceeded-category')
	 *   'expansion-depth-exceeded' (corresponding messages:
	 *       'expansion-depth-exceeded-warning',
	 *       'expansion-depth-exceeded-category')
	 * @param string|int|null $current Current value
	 * @param string|int|null $max Maximum allowed, when an explicit limit has been
	 * 	 exceeded, provide the values (optional)
	 * @internal
	 */
	public function limitationWarn( $limitationType, $current = '', $max = '' ) {
		# does no harm if $current and $max are present but are unnecessary for the message
		# Not doing ->inLanguage( $this->mOptions->getUserLangObj() ), since this is shown
		# only during preview, and that would split the parser cache unnecessarily.
		$this->mOutput->addWarningMsg(
			"$limitationType-warning",
			Message::numParam( $current ),
			Message::numParam( $max )
		);
		$this->addTrackingCategory( "$limitationType-category" );
	}

	/**
	 * Return the text of a template, after recursively
	 * replacing any variables or templates within the template.
	 *
	 * @param array $piece The parts of the template
	 *   $piece['title']: the title, i.e. the part before the |
	 *   $piece['parts']: the parameter array
	 *   $piece['lineStart']: whether the brace was at the start of a line
	 * @param PPFrame $frame The current frame, contains template arguments
	 * @throws Exception
	 * @return string|array The text of the template
	 * @internal
	 */
	public function braceSubstitution( array $piece, PPFrame $frame ) {
		// Flags

		// $text has been filled
		$found = false;
		$text = '';
		// wiki markup in $text should be escaped
		$nowiki = false;
		// $text is HTML, armour it against most wikitext transformation
		// (it still participates in doBlockLevels, language conversion,
		// and the other steps at the start of ::internalParseHalfParsed)
		$isHTML = false;
		// $text is raw HTML, armour it against all wikitext transformation
		$isRawHTML = false;
		// Force interwiki transclusion to be done in raw mode not rendered
		$forceRawInterwiki = false;
		// $text is a DOM node needing expansion in a child frame
		$isChildObj = false;
		// $text is a DOM node needing expansion in the current frame
		$isLocalObj = false;

		# Title object, where $text came from
		$title = false;

		# $part1 is the bit before the first |, and must contain only title characters.
		# Various prefixes will be stripped from it later.
		$titleWithSpaces = $frame->expand( $piece['title'] );
		$part1 = trim( $titleWithSpaces );
		$titleText = false;

		# Original title text preserved for various purposes
		$originalTitle = $part1;

		# $args is a list of argument nodes, starting from index 0, not including $part1
		$args = $piece['parts'];

		$profileSection = null; // profile templates

		$sawDeprecatedTemplateEquals = false; // T91154

		$isParsoid = $this->mOptions->getUseParsoid();

		# SUBST
		// @phan-suppress-next-line PhanImpossibleCondition
		if ( !$found ) {
			$substMatch = $this->mSubstWords->matchStartAndRemove( $part1 );
			$part1 = trim( $part1 );

			# Possibilities for substMatch: "subst", "safesubst" or FALSE
			# Decide whether to expand template or keep wikitext as-is.
			if ( $this->ot['wiki'] ) {
				if ( $substMatch === false ) {
					$literal = true;  # literal when in PST with no prefix
				} else {
					$literal = false; # expand when in PST with subst: or safesubst:
				}
			} else {
				if ( $substMatch == 'subst' ) {
					$literal = true;  # literal when not in PST with plain subst:
				} else {
					$literal = false; # expand when not in PST with safesubst: or no prefix
				}
			}
			if ( $literal ) {
				$text = $frame->virtualBracketedImplode( '{{', '|', '}}', $titleWithSpaces, $args );
				$isLocalObj = true;
				$found = true;
			}
		}

		# Variables
		if ( !$found && $args->getLength() == 0 ) {
			$id = $this->mVariables->matchStartToEnd( $part1 );
			if ( $id !== false ) {
				if ( strpos( $part1, ':' ) !== false ) {
					wfDeprecatedMsg(
						'Registering a magic variable with a name including a colon',
						'1.39', false, false
					);
				}
				$text = $this->expandMagicVariable( $id, $frame );
				$found = true;
			}
		}

		# MSG, MSGNW and RAW
		if ( !$found ) {
			# Check for MSGNW:
			$mwMsgnw = $this->magicWordFactory->get( 'msgnw' );
			if ( $mwMsgnw->matchStartAndRemove( $part1 ) ) {
				$nowiki = true;
			} else {
				# Remove obsolete MSG:
				$mwMsg = $this->magicWordFactory->get( 'msg' );
				$mwMsg->matchStartAndRemove( $part1 );
			}

			# Check for RAW:
			$mwRaw = $this->magicWordFactory->get( 'raw' );
			if ( $mwRaw->matchStartAndRemove( $part1 ) ) {
				$forceRawInterwiki = true;
			}
		}

		# Parser functions
		if ( !$found ) {
			$colonPos = strpos( $part1, ':' );
			if ( $colonPos !== false ) {
				$func = substr( $part1, 0, $colonPos );
				$funcArgs = [ trim( substr( $part1, $colonPos + 1 ) ) ];
				$argsLength = $args->getLength();
				for ( $i = 0; $i < $argsLength; $i++ ) {
					$funcArgs[] = $args->item( $i );
				}

				$result = $this->callParserFunction(
					$frame, $func, $funcArgs, $isParsoid && $piece['lineStart']
				);

				// Extract any forwarded flags
				if ( isset( $result['title'] ) ) {
					$title = $result['title'];
				}
				if ( isset( $result['found'] ) ) {
					$found = $result['found'];
				}
				if ( array_key_exists( 'text', $result ) ) {
					// a string or null
					$text = $result['text'];
				}
				if ( isset( $result['nowiki'] ) ) {
					$nowiki = $result['nowiki'];
				}
				if ( isset( $result['isHTML'] ) ) {
					$isHTML = $result['isHTML'];
				}
				if ( isset( $result['isRawHTML'] ) ) {
					$isRawHTML = $result['isRawHTML'];
				}
				if ( isset( $result['forceRawInterwiki'] ) ) {
					$forceRawInterwiki = $result['forceRawInterwiki'];
				}
				if ( isset( $result['isChildObj'] ) ) {
					$isChildObj = $result['isChildObj'];
				}
				if ( isset( $result['isLocalObj'] ) ) {
					$isLocalObj = $result['isLocalObj'];
				}
			}
		}

		# Finish mangling title and then check for loops.
		# Set $title to a Title object and $titleText to the PDBK
		if ( !$found ) {
			$ns = NS_TEMPLATE;
			# Split the title into page and subpage
			$subpage = '';
			$relative = Linker::normalizeSubpageLink(
				$this->getTitle(), $part1, $subpage
			);
			if ( $part1 !== $relative ) {
				$part1 = $relative;
				$ns = $this->getTitle()->getNamespace();
			}
			$title = Title::newFromText( $part1, $ns );
			if ( $title ) {
				$titleText = $title->getPrefixedText();
				# Check for language variants if the template is not found
				if ( $this->getTargetLanguageConverter()->hasVariants() && $title->getArticleID() == 0 ) {
					$this->getTargetLanguageConverter()->findVariantLink( $part1, $title, true );
				}
				# Do recursion depth check
				$limit = $this->mOptions->getMaxTemplateDepth();
				if ( $frame->depth >= $limit ) {
					$found = true;
					$text = '<span class="error">'
						. wfMessage( 'parser-template-recursion-depth-warning' )
							->numParams( $limit )->inContentLanguage()->text()
						. '</span>';
				}
			}
		}

		# Load from database
		if ( !$found && $title ) {
			$profileSection = $this->mProfiler->scopedProfileIn( $title->getPrefixedDBkey() );
			if ( !$title->isExternal() ) {
				if ( $title->isSpecialPage()
					&& $this->mOptions->getAllowSpecialInclusion()
					&& ( $this->ot['html'] ||
						// PFragment for Parsoid
						( !$this->mStripExtTags && $this->ot['pre'] ) )
				) {
					$specialPage = $this->specialPageFactory->getPage( $title->getDBkey() );
					// Pass the template arguments as URL parameters.
					// "uselang" will have no effect since the Language object
					// is forced to the one defined in ParserOptions.
					$pageArgs = [];
					$argsLength = $args->getLength();
					for ( $i = 0; $i < $argsLength; $i++ ) {
						$bits = $args->item( $i )->splitArg();
						if ( strval( $bits['index'] ) === '' ) {
							$name = trim( $frame->expand( $bits['name'], PPFrame::STRIP_COMMENTS ) );
							$value = trim( $frame->expand( $bits['value'] ) );
							$pageArgs[$name] = $value;
						}
					}

					// Create a new context to execute the special page, that is expensive
					if ( $this->incrementExpensiveFunctionCount() ) {
						$context = new RequestContext;
						$context->setTitle( $title );
						$context->setRequest( new FauxRequest( $pageArgs ) );
						if ( $specialPage && $specialPage->maxIncludeCacheTime() === 0 ) {
							$context->setUser( $this->userFactory->newFromUserIdentity( $this->getUserIdentity() ) );
						} else {
							// If this page is cached, then we better not be per user.
							$context->setUser( User::newFromName( '127.0.0.1', false ) );
						}
						$context->setLanguage( $this->mOptions->getUserLangObj() );
						$ret = $this->specialPageFactory->capturePath( $title, $context, $this->getLinkRenderer() );
						if ( $ret ) {
							$text = $context->getOutput()->getHTML();
							$this->mOutput->addOutputPageMetadata( $context->getOutput() );
							$found = true;
							$isHTML = true;
							if ( $specialPage && $specialPage->maxIncludeCacheTime() !== false ) {
								$this->mOutput->updateRuntimeAdaptiveExpiry(
									$specialPage->maxIncludeCacheTime()
								);
							}
						}
					}
				} elseif ( $this->nsInfo->isNonincludable( $title->getNamespace() ) ) {
					$found = false; # access denied
					$this->logger->debug(
						__METHOD__ .
						": template inclusion denied for " . $title->getPrefixedDBkey()
					);
				} else {
					[ $text, $title ] = $this->getTemplateDom( $title, $isParsoid && $piece['lineStart'] );
					if ( $text !== false ) {
						$found = true;
						$isChildObj = true;
						if (
							$title->getNamespace() === NS_TEMPLATE &&
							$title->getDBkey() === '=' &&
							$originalTitle === '='
						) {
							// Note that we won't get here if `=` is evaluated
							// (in the future) as a parser function, nor if
							// the Template namespace is given explicitly,
							// ie `{{Template:=}}`.  Only `{{=}}` triggers.
							$sawDeprecatedTemplateEquals = true; // T91154
						}
					}
				}

				# If the title is valid but undisplayable, make a link to it
				if ( !$found && ( $this->ot['html'] || $this->ot['pre'] ) ) {
					$text = "[[:$titleText]]";
					$found = true;
				}
			} elseif ( $title->isTrans() ) {
				# Interwiki transclusion
				if ( $this->ot['html'] && !$forceRawInterwiki ) {
					$text = $this->interwikiTransclude( $title, 'render' );
					$isHTML = true;
				} else {
					$text = $this->interwikiTransclude( $title, 'raw' );
					# Preprocess it like a template
					$sol = ( $isParsoid && $piece['lineStart'] ) ? Preprocessor::START_IN_SOL_STATE : 0;
					$text = $this->preprocessToDom( $text, Preprocessor::DOM_FOR_INCLUSION | $sol );
					$isChildObj = true;
				}
				$found = true;
			}

			# Do infinite loop check
			# This has to be done after redirect resolution to avoid infinite loops via redirects
			if ( !$frame->loopCheck( $title ) ) {
				$found = true;
				$text = '<span class="error">'
					. wfMessage( 'parser-template-loop-warning', $titleText )->inContentLanguage()->text()
					. '</span>';
				$this->addTrackingCategory( 'template-loop-category' );
				$this->mOutput->addWarningMsg(
					'template-loop-warning',
					Message::plaintextParam( $titleText )
				);
				$this->logger->debug( __METHOD__ . ": template loop broken at '$titleText'" );
			}
		}

		# If we haven't found text to substitute by now, we're done
		# Recover the source wikitext and return it
		if ( !$found ) {
			$text = $frame->virtualBracketedImplode( '{{', '|', '}}', $titleWithSpaces, $args );
			if ( $profileSection ) {
				$this->mProfiler->scopedProfileOut( $profileSection );
			}
			return [ 'object' => $text ];
		}

		# Expand DOM-style return values in a child frame
		if ( $isChildObj ) {
			# Clean up argument array
			$newFrame = $frame->newChild( $args, $title );

			if ( $nowiki ) {
				$text = $newFrame->expand( $text, PPFrame::RECOVER_ORIG );
			} elseif ( $titleText !== false && $newFrame->isEmpty() ) {
				# Expansion is eligible for the empty-frame cache
				$text = $newFrame->cachedExpand( $titleText, $text );
			} else {
				# Uncached expansion
				$text = $newFrame->expand( $text );
			}
		}
		if ( $isLocalObj && $nowiki ) {
			$text = $frame->expand( $text, PPFrame::RECOVER_ORIG );
			$isLocalObj = false;
		}

		if ( $profileSection ) {
			$this->mProfiler->scopedProfileOut( $profileSection );
		}
		if (
			$sawDeprecatedTemplateEquals &&
			$this->mStripState->unstripBoth( $text ) !== '='
		) {
			// T91154: {{=}} is deprecated when it doesn't expand to `=`;
			// use {{Template:=}} if you must.
			$this->addTrackingCategory( 'template-equals-category' );
			$this->mOutput->addWarningMsg( 'template-equals-warning' );
		}

		# Replace raw HTML by a placeholder
		if ( $isHTML ) {
			// @phan-suppress-next-line SecurityCheck-XSS
			$text = $this->insertStripItem( $text );
		} elseif ( $isRawHTML ) {
			$marker = self::MARKER_PREFIX . "-pf-"
				. sprintf( '%08X', $this->mMarkerIndex++ ) . self::MARKER_SUFFIX;
			// use 'nowiki' type to protect this from doBlockLevels,
			// language conversion, etc.
			// @phan-suppress-next-line SecurityCheck-XSS
			$this->mStripState->addNoWiki( $marker, $text );
			$text = $marker;
		} elseif ( $nowiki && ( $this->ot['html'] || $this->ot['pre'] ) ) {
			# Escape nowiki-style return values
			// @phan-suppress-next-line SecurityCheck-DoubleEscaped
			$text = wfEscapeWikiText( $text );
		} elseif ( is_string( $text )
			&& !$piece['lineStart']
			&& preg_match( '/^(?:{\\||:|;|#|\*)/', $text )
		) {
			// T2529: if the template begins with a table or block-level
			// element, it should be treated as beginning a new line.
			// This behavior is somewhat controversial.
			//
			// T382464: Parsoid sets $piece['lineStart'] at top-level when
			// expanding templates, so this hack is restricted to nested expansions.
			$text = "\n" . $text;
		}

		if ( is_string( $text ) && !$this->incrementIncludeSize( 'post-expand', strlen( $text ) ) ) {
			# Error, oversize inclusion
			if ( $titleText !== false ) {
				# Make a working, properly escaped link if possible (T25588)
				$text = "[[:$titleText]]";
			} else {
				# This will probably not be a working link, but at least it may
				# provide some hint of where the problem is
				$originalTitle = preg_replace( '/^:/', '', $originalTitle );
				$text = "[[:$originalTitle]]";
			}
			$text .= $this->insertStripItem( '<!-- WARNING: template omitted, '
				. 'post-expand include size too large -->' );
			$this->limitationWarn( 'post-expand-template-inclusion' );
		}

		if ( $isLocalObj ) {
			$ret = [ 'object' => $text ];
		} else {
			$ret = [ 'text' => $text ];
		}

		return $ret;
	}

	/**
	 * Call a parser function and return an array with text and flags.
	 *
	 * The returned array will always contain a boolean 'found', indicating
	 * whether the parser function was found or not. It may also contain the
	 * following:
	 *  text: string|object, resulting wikitext or PP DOM object
	 *  isHTML: bool, $text is HTML, armour it against most wikitext transformation
	 *  isRawHTML: bool, $text is raw HTML, armour it against all wikitext transformation
	 *  isChildObj: bool, $text is a DOM node needing expansion in a child frame
	 *  isLocalObj: bool, $text is a DOM node needing expansion in the current frame
	 *  nowiki: bool, wiki markup in $text should be escaped
	 *
	 * @since 1.21
	 * @param PPFrame $frame The current frame, contains template arguments
	 * @param string $function Function name
	 * @param array $args Arguments to the function
	 * @param bool $inSolState Is the template processing starting in Start-Of-Line (SOL) position?
	 *  Prepreprocessing (on behalf of Parsoid) uses this flag to set lineStart property on
	 *  processor DOM tree nodes. Since the preprocessor tree doesn't rely on expanded templates,
	 *  this flag is a best guess since {{expands-to-empty-string}} can blind it to SOL context.
	 *  This flag is always false for legacy parser template expansions.
	 *
	 * @return array
	 */
	public function callParserFunction( PPFrame $frame, $function, array $args = [], bool $inSolState = false ) {
		# Case sensitive functions
		if ( isset( $this->mFunctionSynonyms[1][$function] ) ) {
			$function = $this->mFunctionSynonyms[1][$function];
		} else {
			# Case insensitive functions
			$function = $this->contLang->lc( $function );
			if ( isset( $this->mFunctionSynonyms[0][$function] ) ) {
				$function = $this->mFunctionSynonyms[0][$function];
			} else {
				return [ 'found' => false ];
			}
		}

		[ $callback, $flags ] = $this->mFunctionHooks[$function];

		$allArgs = [ $this ];
		if ( $flags & self::SFH_OBJECT_ARGS ) {
			# Convert arguments to PPNodes and collect for appending to $allArgs
			$funcArgs = [];
			foreach ( $args as $k => $v ) {
				if ( $v instanceof PPNode || $k === 0 ) {
					$funcArgs[] = $v;
				} else {
					$funcArgs[] = $this->mPreprocessor->newPartNodeArray( [ $k => $v ] )->item( 0 );
				}
			}

			# Add a frame parameter, and pass the arguments as an array
			$allArgs[] = $frame;
			$allArgs[] = $funcArgs;
		} else {
			# Convert arguments to plain text and append to $allArgs
			foreach ( $args as $k => $v ) {
				if ( $v instanceof PPNode ) {
					$allArgs[] = trim( $frame->expand( $v ) );
				} elseif ( is_int( $k ) && $k >= 0 ) {
					$allArgs[] = trim( $v );
				} else {
					$allArgs[] = trim( "$k=$v" );
				}
			}
		}

		$result = $callback( ...$allArgs );

		# The interface for function hooks allows them to return a wikitext
		# string or an array containing the string and any flags. This mungs
		# things around to match what this method should return.
		if ( !is_array( $result ) ) {
			$result = [
				'found' => true,
				'text' => $result,
			];
		} else {
			if ( isset( $result[0] ) && !isset( $result['text'] ) ) {
				$result['text'] = $result[0];
			}
			unset( $result[0] );
			$result += [
				'found' => true,
			];
		}

		$noparse = $result['noparse'] ?? true;
		if ( !$noparse ) {
			$preprocessFlags = $result['preprocessFlags'] ?? 0;
			if ( $inSolState ) {
				$preprocessFlags |= Preprocessor::START_IN_SOL_STATE;
			}
			$result['text'] = $this->preprocessToDom( $result['text'], $preprocessFlags );
			$result['isChildObj'] = true;
		}

		return $result;
	}

	/**
	 * Get the semi-parsed DOM representation of a template with a given title,
	 * and its redirect destination title. Cached.
	 *
	 * The second element of the returned array may be a Title object, or may be
	 * the passed `$title` parameter, so if you need a `Title`, pass it a `Title`
	 * rather than a TitleValue.
	 *
	 * @param LinkTarget $title
	 * @param bool $inSolState Is the template processing starting in Start-Of-Line (SOL) position?
	 *  Prepreprocessing (on behalf of Parsoid) uses this flag to set lineStart property on
	 *  processor DOM tree nodes. Since the preprocessor tree doesn't rely on expanded templates,
	 *  this flag is a best guess since {{expands-to-empty-string}} can blind it to SOL context.
	 *  This flag is always false for legacy parser template expansions.
	 *
	 * @return array{0:PPNode|false,1:LinkTarget} [Template DOM, final template title]
	 * @since 1.12
	 */
	public function getTemplateDom( LinkTarget $title, bool $inSolState = false ) {
		$cacheTitle = $title;
		$titleKey = CacheKeyHelper::getKeyForPage( $title );

		if ( isset( $this->mTplRedirCache[$titleKey] ) ) {
			[ $ns, $dbk ] = $this->mTplRedirCache[$titleKey];
			$title = Title::makeTitle( $ns, $dbk );
			$titleKey = CacheKeyHelper::getKeyForPage( $title );
		}

		// Factor in sol-state in the cache key
		$titleKey = "$titleKey:sol=" . ( $inSolState ? "0" : "1" );
		if ( isset( $this->mTplDomCache[$titleKey] ) ) {
			return [ $this->mTplDomCache[$titleKey], $title ];
		}

		# Cache miss, go to the database
		// FIXME T383919: if $title is changed by this call, caching below
		// will be ineffective.
		[ $text, $title ] = $this->fetchTemplateAndTitle( $title );

		if ( $text === false ) {
			$this->mTplDomCache[$titleKey] = false;
			return [ false, $title ];
		}

		$flags = Preprocessor::DOM_FOR_INCLUSION | ( $inSolState ? Preprocessor::START_IN_SOL_STATE : 0 );
		$dom = $this->preprocessToDom( $text, $flags );
		$this->mTplDomCache[$titleKey] = $dom;

		if ( !$title->isSameLinkAs( $cacheTitle ) ) {
			$this->mTplRedirCache[ CacheKeyHelper::getKeyForPage( $cacheTitle ) ] =
				[ $title->getNamespace(), $title->getDBkey() ];
		}

		return [ $dom, $title ];
	}

	/**
	 * Fetch the current revision of a given title as a RevisionRecord.
	 * Note that the revision (and even the title) may not exist in the database,
	 * so everything contributing to the output of the parser should use this method
	 * where possible, rather than getting the revisions themselves. This
	 * method also caches its results, so using it benefits performance.
	 *
	 * This can return null if the callback returns false
	 *
	 * @since 1.35
	 * @param LinkTarget $link
	 * @return RevisionRecord|null
	 */
	public function fetchCurrentRevisionRecordOfTitle( LinkTarget $link ) {
		$cacheKey = CacheKeyHelper::getKeyForPage( $link );
		if ( !$this->currentRevisionCache ) {
			$this->currentRevisionCache = new MapCacheLRU( 100 );
		}
		if ( !$this->currentRevisionCache->has( $cacheKey ) ) {
			$title = Title::newFromLinkTarget( $link ); // hook signature compat
			$revisionRecord =
				// Defaults to Parser::statelessFetchRevisionRecord()
				$this->mOptions->getCurrentRevisionRecordCallback()(
					$title,
					$this
				);
			if ( $revisionRecord === false ) {
				// Parser::statelessFetchRevisionRecord() can return false;
				// normalize it to null.
				$revisionRecord = null;
			}
			$this->currentRevisionCache->set( $cacheKey, $revisionRecord );
		}
		return $this->currentRevisionCache->get( $cacheKey );
	}

	/**
	 * @param LinkTarget $link
	 * @return bool
	 * @since 1.34
	 * @internal
	 */
	public function isCurrentRevisionOfTitleCached( LinkTarget $link ) {
		$key = CacheKeyHelper::getKeyForPage( $link );
		return (
			$this->currentRevisionCache &&
			$this->currentRevisionCache->has( $key )
		);
	}

	/**
	 * Wrapper around RevisionLookup::getKnownCurrentRevision
	 *
	 * @since 1.34
	 * @param LinkTarget $link
	 * @param Parser|null $parser
	 * @return RevisionRecord|false False if missing
	 */
	public static function statelessFetchRevisionRecord( LinkTarget $link, $parser = null ) {
		if ( $link instanceof PageIdentity ) {
			// probably a Title, just use it.
			$page = $link;
		} else {
			// XXX: use RevisionStore::getPageForLink()!
			//      ...but get the info for the current revision at the same time?
			//      Should RevisionStore::getKnownCurrentRevision accept a LinkTarget?
			$page = Title::newFromLinkTarget( $link );
		}

		$revRecord = MediaWikiServices::getInstance()
			->getRevisionLookup()
			->getKnownCurrentRevision( $page );
		return $revRecord;
	}

	/**
	 * Fetch the unparsed text of a template and register a reference to it.
	 * @param LinkTarget $link
	 * @return array{0:string|false,1:Title}
	 * @since 1.11
	 */
	public function fetchTemplateAndTitle( LinkTarget $link ) {
		// Use Title for compatibility with callbacks and return type
		$title = Title::newFromLinkTarget( $link );

		// Defaults to Parser::statelessFetchTemplate()
		$templateCb = $this->mOptions->getTemplateCallback();
		$stuff = $templateCb( $title, $this );
		$revRecord = $stuff['revision-record'] ?? null;

		$text = $stuff['text'];
		if ( is_string( $stuff['text'] ) ) {
			// We use U+007F DELETE to distinguish strip markers from regular text
			$text = strtr( $text, "\x7f", "?" );
		}
		$finalTitle = $stuff['finalTitle'] ?? $title;
		foreach ( ( $stuff['deps'] ?? [] ) as $dep ) {
			$this->mOutput->addTemplate( $dep['title'], $dep['page_id'], $dep['rev_id'] );
			if ( $dep['title']->equals( $this->getTitle() ) && $revRecord instanceof RevisionRecord ) {
				// Self-transclusion; final result may change based on the new page version
				try {
					$sha1 = $revRecord->getSha1();
				} catch ( RevisionAccessException ) {
					$sha1 = null;
				}
				$this->setOutputFlag( ParserOutputFlags::VARY_REVISION_SHA1, 'Self transclusion' );
				$this->getOutput()->setRevisionUsedSha1Base36( $sha1 );
			}
		}

		return [ $text, $finalTitle ];
	}

	/**
	 * Static function to get a template
	 * Can be overridden via ParserOptions::setTemplateCallback().
	 *
	 * @param LinkTarget $page
	 * @param Parser|false $parser
	 *
	 * @return array
	 * @since 1.12
	 */
	public static function statelessFetchTemplate( $page, $parser = false ) {
		$title = Title::castFromLinkTarget( $page ); // for compatibility with return type
		$text = $skip = false;
		$finalTitle = $title;
		$deps = [];
		$revRecord = null;
		$contextTitle = $parser ? $parser->getTitle() : null;

		# Loop to fetch the article, with up to 2 redirects

		# Note that $title (including redirect targets) could be
		# external; we do allow hooks a chance to redirect the
		# external title to a local one (which might be useful), but
		# are careful not to add external titles to the dependency
		# list. (T362221)

		$services = MediaWikiServices::getInstance();
		$revLookup = $services->getRevisionLookup();
		$hookRunner = new HookRunner( $services->getHookContainer() );
		for ( $i = 0; $i < 3 && is_object( $title ); $i++ ) {
			# Give extensions a chance to select the revision instead
			$revRecord = null; # Assume no hook
			$origTitle = $title;
			$titleChanged = false;
			$hookRunner->onBeforeParserFetchTemplateRevisionRecord(
				# The $title is a not a PageIdentity, as it may
				# contain fragments or even represent an attempt to transclude
				# a broken or otherwise-missing Title, which the hook may
				# fix up.  Similarly, the $contextTitle may represent a special
				# page or other page which "exists" as a parsing context but
				# is not in the DB.
				$contextTitle, $title,
				$skip, $revRecord
			);

			if ( $skip ) {
				$text = false;
				if ( !$title->isExternal() ) {
					$deps[] = [
						'title' => $title,
						'page_id' => $title->getArticleID(),
						'rev_id' => null
					];
				}
				break;
			}
			# Get the revision
			if ( !$revRecord ) {
				if ( $parser ) {
					$revRecord = $parser->fetchCurrentRevisionRecordOfTitle( $title );
				} else {
					$revRecord = $revLookup->getRevisionByTitle( $title );
				}
			}
			if ( $revRecord ) {
				# Update title, as $revRecord may have been changed by hook
				$title = Title::newFromPageIdentity( $revRecord->getPage() );
				// Assuming title is not external if we've got a $revRecord
				$deps[] = [
					'title' => $title,
					'page_id' => $revRecord->getPageId(),
					'rev_id' => $revRecord->getId(),
				];
			} elseif ( !$title->isExternal() ) {
				$deps[] = [
					'title' => $title,
					'page_id' => $title->getArticleID(),
					'rev_id' => null,
				];
			}
			if ( !$title->equals( $origTitle ) ) {
				# If we fetched a rev from a different title, register
				# the original title too...
				if ( !$origTitle->isExternal() ) {
					$deps[] = [
						'title' => $origTitle,
						'page_id' => $origTitle->getArticleID(),
						'rev_id' => null,
					];
				}
				$titleChanged = true;
			}
			# If there is no current revision, there is no page
			if ( $revRecord === null || $revRecord->getId() === null ) {
				$linkCache = $services->getLinkCache();
				$linkCache->addBadLinkObj( $title );
			}
			if ( $revRecord ) {
				if ( $titleChanged && !$revRecord->hasSlot( SlotRecord::MAIN ) ) {
					// We've added this (missing) title to the dependencies;
					// give the hook another chance to redirect it to an
					// actual page.
					$text = false;
					$finalTitle = $title;
					continue;
				}
				if ( $revRecord->hasSlot( SlotRecord::MAIN ) ) { // T276476
					$content = $revRecord->getContent( SlotRecord::MAIN );
					$text = $content ? $content->getWikitextForTransclusion() : null;
				} else {
					$text = false;
				}

				if ( $text === false || $text === null ) {
					$text = false;
					break;
				}
			} elseif ( $title->getNamespace() === NS_MEDIAWIKI ) {
				$message = wfMessage( $services->getContentLanguage()->
					lcfirst( $title->getText() ) )->inContentLanguage();
				if ( !$message->exists() ) {
					$text = false;
					break;
				}
				$text = $message->plain();
				break;
			} else {
				break;
			}
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable Only reached when content is set
			if ( !$content ) {
				break;
			}
			# Redirect?
			$finalTitle = $title;
			$title = $content->getRedirectTarget();
		}

		$retValues = [
			// previously, when this also returned a Revision object, we set
			// 'revision-record' to false instead of null if it was unavailable,
			// so that callers to use isset and then rely on the revision-record
			// key instead of the revision key, even if there was no corresponding
			// object - we continue to set to false here for backwards compatability
			'revision-record' => $revRecord ?: false,
			'text' => $text,
			'finalTitle' => $finalTitle,
			'deps' => $deps
		];
		return $retValues;
	}

	/**
	 * Fetch a file and its title and register a reference to it.
	 * If 'broken' is a key in $options then the file will appear as a broken thumbnail.
	 * @param LinkTarget $link
	 * @param array $options Array of options to RepoGroup::findFile
	 * @return array ( File or false, Title of file )
	 * @since 1.18
	 */
	public function fetchFileAndTitle( LinkTarget $link, array $options = [] ) {
		$file = $this->fetchFileNoRegister( $link, $options );

		$time = $file ? $file->getTimestamp() : false;
		$sha1 = $file ? $file->getSha1() : false;
		# Register the file as a dependency...
		$this->mOutput->addImage( $link, $time, $sha1 );
		if ( $file && !$link->isSameLinkAs( $file->getTitle() ) ) {
			# Update fetched file title after resolving redirects, etc.
			$link = $file->getTitle();
			$this->mOutput->addImage( $link, $time, $sha1 );
		}

		$title = Title::newFromLinkTarget( $link ); // for return type compat
		return [ $file, $title ];
	}

	/**
	 * Helper function for fetchFileAndTitle.
	 *
	 * Also useful if you need to fetch a file but not use it yet,
	 * for example to get the file's handler.
	 *
	 * @param LinkTarget $link
	 * @param array $options Array of options to RepoGroup::findFile
	 * @return File|false
	 */
	protected function fetchFileNoRegister( LinkTarget $link, array $options = [] ) {
		if ( isset( $options['broken'] ) ) {
			$file = false; // broken thumbnail forced by hook
		} else {
			$repoGroup = MediaWikiServices::getInstance()->getRepoGroup();
			if ( isset( $options['sha1'] ) ) { // get by (sha1,timestamp)
				$file = $repoGroup->findFileFromKey( $options['sha1'], $options );
			} else { // get by (name,timestamp)
				$link = TitleValue::newFromLinkTarget( $link );
				$file = $repoGroup->findFile( $link, $options );
			}
		}
		return $file;
	}

	/**
	 * Transclude an interwiki link.
	 *
	 * @param LinkTarget $link
	 * @param string $action Usually one of (raw, render)
	 *
	 * @return string
	 * @internal
	 */
	public function interwikiTransclude( LinkTarget $link, $action ) {
		if ( !$this->svcOptions->get( MainConfigNames::EnableScaryTranscluding ) ) {
			return wfMessage( 'scarytranscludedisabled' )->inContentLanguage()->text();
		}

		// TODO: extract relevant functionality from Title
		$title = Title::newFromLinkTarget( $link );

		$url = $title->getFullURL( [ 'action' => $action ] );
		if ( strlen( $url ) > 1024 ) {
			return wfMessage( 'scarytranscludetoolong' )->inContentLanguage()->text();
		}

		$wikiId = $title->getTransWikiID(); // remote wiki ID or false

		$fname = __METHOD__;

		$cache = $this->wanCache;
		$data = $cache->getWithSetCallback(
			$cache->makeGlobalKey(
				'interwiki-transclude',
				( $wikiId !== false ) ? $wikiId : 'external',
				sha1( $url )
			),
			$this->svcOptions->get( MainConfigNames::TranscludeCacheExpiry ),
			function ( $oldValue, &$ttl ) use ( $url, $fname, $cache ) {
				$req = $this->httpRequestFactory->create( $url, [], $fname );

				$status = $req->execute(); // Status object
				if ( !$status->isOK() ) {
					$ttl = $cache::TTL_UNCACHEABLE;
				} elseif ( $req->getResponseHeader( 'X-Database-Lagged' ) !== null ) {
					$ttl = min( $cache::TTL_LAGGED, $ttl );
				}

				return [
					'text' => $status->isOK() ? $req->getContent() : null,
					'code' => $req->getStatus()
				];
			},
			[
				'checkKeys' => ( $wikiId !== false )
					? [ $cache->makeGlobalKey( 'interwiki-page', $wikiId, $title->getDBkey() ) ]
					: [],
				'pcGroup' => 'interwiki-transclude:5',
				'pcTTL' => $cache::TTL_PROC_LONG
			]
		);

		if ( is_string( $data['text'] ) ) {
			$text = $data['text'];
		} elseif ( $data['code'] != 200 ) {
			// Though we failed to fetch the content, this status is useless.
			$text = wfMessage( 'scarytranscludefailed-httpstatus' )
				->params( $url, $data['code'] )->inContentLanguage()->text();
		} else {
			$text = wfMessage( 'scarytranscludefailed', $url )->inContentLanguage()->text();
		}

		return $text;
	}

	/**
	 * Triple brace replacement -- used for template arguments
	 *
	 * @param array $piece
	 * @param PPFrame $frame
	 *
	 * @return array
	 * @internal
	 */
	public function argSubstitution( array $piece, PPFrame $frame ) {
		$error = false;
		$parts = $piece['parts'];
		$nameWithSpaces = $frame->expand( $piece['title'] );
		$argName = trim( $nameWithSpaces );
		$object = false;
		$text = $frame->getArgument( $argName );
		if ( $text === false && $parts->getLength() > 0
			&& ( $this->ot['html']
				|| $this->ot['pre']
				|| ( $this->ot['wiki'] && $frame->isTemplate() )
			)
		) {
			# No match in frame, use the supplied default
			$object = $parts->item( 0 )->getChildren();
		}
		if ( !$this->incrementIncludeSize( 'arg', strlen( $text ) ) ) {
			$error = '<!-- WARNING: argument omitted, expansion size too large -->';
			$this->limitationWarn( 'post-expand-template-argument' );
		}

		if ( $text === false && $object === false ) {
			# No match anywhere
			$object = $frame->virtualBracketedImplode( '{{{', '|', '}}}', $nameWithSpaces, $parts );
		}
		if ( $error !== false ) {
			$text .= $error;
		}
		if ( $object !== false ) {
			$ret = [ 'object' => $object ];
		} else {
			$ret = [ 'text' => $text ];
		}

		return $ret;
	}

	public function tagNeedsNowikiStrippedInTagPF( string $lowerTagName ): bool {
		$parsoidSiteConfig = MediaWikiServices::getInstance()->getParsoidSiteConfig();
		return $parsoidSiteConfig->tagNeedsNowikiStrippedInTagPF( $lowerTagName );
	}

	/**
	 * Return the text to be used for a given extension tag.
	 * This is the ghost of strip().
	 *
	 * @param array $params Associative array of parameters:
	 *     name       PPNode for the tag name
	 *     attr       PPNode for unparsed text where tag attributes are thought to be
	 *     attributes Optional associative array of parsed attributes
	 *     inner      Contents of extension element
	 *     noClose    Original text did not have a close tag
	 * @param PPFrame $frame
	 * @param bool $processNowiki Process nowiki tags by running the nowiki tag handler
	 *     Normally, nowikis are only processed for the HTML output type. With this
	 *     arg set to true, they are processed (and converted to a nowiki strip marker)
	 *     for all output types.
	 * @return string
	 * @internal
	 * @since 1.12
	 */
	public function extensionSubstitution( array $params, PPFrame $frame, bool $processNowiki = false ) {
		static $errorStr = '<span class="error">';

		$name = $frame->expand( $params['name'] );
		if ( str_starts_with( $name, $errorStr ) ) {
			// Probably expansion depth or node count exceeded. Just punt the
			// error up.
			return $name;
		}

		// Parse attributes from XML-like wikitext syntax
		$attrText = !isset( $params['attr'] ) ? '' : $frame->expand( $params['attr'] );
		if ( str_starts_with( $attrText, $errorStr ) ) {
			// See above
			return $attrText;
		}

		// We can't safely check if the expansion for $content resulted in an
		// error, because the content could happen to be the error string
		// (T149622).
		$content = !isset( $params['inner'] ) ? null : $frame->expand( $params['inner'] );

		$marker = self::MARKER_PREFIX . "-$name-"
			. sprintf( '%08X', $this->mMarkerIndex++ ) . self::MARKER_SUFFIX;

		$normalizedName = strtolower( $name );
		$isNowiki = $normalizedName === 'nowiki';
		$markerType = $isNowiki ? 'nowiki' : 'general';
		$extra = $isNowiki ? 'nowiki' : null;
		if ( !$this->mStripExtTags ) {
			$processNowiki = true;
		}
		if ( $this->ot['html'] || ( $processNowiki && $isNowiki ) ) {
			$attributes = Sanitizer::decodeTagAttributes( $attrText );
			// Merge in attributes passed via {{#tag:}} parser function
			if ( isset( $params['attributes'] ) ) {
				$attributes += $params['attributes'];
			}

			if ( isset( $this->mTagHooks[$normalizedName] ) ) {
				// Note that $content may be null here, for example if the
				// tag is self-closed.
				$output = $this->mTagHooks[$normalizedName]( $content, $attributes, $this, $frame );
			} else {
				$output = '<span class="error">Invalid tag extension name: ' .
					htmlspecialchars( $normalizedName ) . '</span>';
			}

			if ( is_array( $output ) ) {
				// Extract flags
				$flags = $output;
				$output = $flags[0];
				if ( isset( $flags['isRawHTML'] ) ) {
					$markerType = 'nowiki';
				}
				if ( isset( $flags['markerType'] ) ) {
					$markerType = $flags['markerType'];
				}
			}
		} else {
			// We're substituting a {{subst:#tag:}} parser function.
			// Convert the attributes it passed into the XML-like string.
			if ( isset( $params['attributes'] ) ) {
				foreach ( $params['attributes'] as $attrName => $attrValue ) {
					$attrText .= ' ' . htmlspecialchars( $attrName ) . '="' .
						htmlspecialchars( $this->getStripState()->unstripBoth( $attrValue ), ENT_COMPAT ) . '"';
				}
			}
			if ( $content === null ) {
				$output = "<$name$attrText/>";
			} else {
				$close = $params['close'] === null ? '' : $frame->expand( $params['close'] );
				if ( str_starts_with( $close, $errorStr ) ) {
					// See above
					return $close;
				}
				$output = "<$name$attrText>$content$close";
			}
			if ( !$this->mStripExtTags ) {
				$markerType = 'exttag';
			}
		}

		if ( $markerType === 'none' ) {
			return $output;
		} elseif ( $markerType === 'nowiki' ) {
			$this->mStripState->addNoWiki( $marker, $output, $extra );
		} elseif ( $markerType === 'general' ) {
			$this->mStripState->addGeneral( $marker, $output );
		} elseif ( $markerType === 'exttag' ) {
			$this->mStripState->addExtTag( $marker, $output );
		} else {
			throw new UnexpectedValueException( __METHOD__ . ': invalid marker type' );
		}
		return $marker;
	}

	/**
	 * Increment an include size counter
	 *
	 * @param string $type The type of expansion
	 * @param int $size The size of the text
	 * @return bool False if this inclusion would take it over the maximum, true otherwise
	 */
	private function incrementIncludeSize( $type, $size ) {
		if ( $this->mIncludeSizes[$type] + $size > $this->mOptions->getMaxIncludeSize() ) {
			return false;
		} else {
			$this->mIncludeSizes[$type] += $size;
			return true;
		}
	}

	/**
	 * @return bool False if the limit has been exceeded
	 * @since 1.13
	 */
	public function incrementExpensiveFunctionCount() {
		$this->mExpensiveFunctionCount++;
		return $this->mExpensiveFunctionCount <= $this->mOptions->getExpensiveParserFunctionLimit();
	}

	/**
	 * Strip double-underscore items like __NOGALLERY__ and __NOTOC__
	 * Fills $this->mDoubleUnderscores, returns the modified text
	 *
	 * @param string $text
	 * @return string
	 */
	private function handleDoubleUnderscore( $text ) {
		# The position of __TOC__ needs to be recorded
		$mw = $this->magicWordFactory->get( 'toc' );
		if ( $mw->match( $text ) ) {
			$this->mShowToc = true;
			$this->mForceTocPosition = true;

			# Set a placeholder. At the end we'll fill it in with the TOC.
			$text = $mw->replace( self::TOC_PLACEHOLDER, $text, 1 );

			# Only keep the first one.
			$text = $mw->replace( '', $text );
			# For consistency with all other double-underscores
			# (see below)
			$this->mOutput->setUnsortedPageProperty( 'toc' );
		}

		# Now match and remove the rest of them
		$mwa = $this->magicWordFactory->getDoubleUnderscoreArray();
		$this->mDoubleUnderscores = $mwa->matchAndRemove( $text );

		if ( isset( $this->mDoubleUnderscores['nogallery'] ) ) {
			$this->mOutput->setNoGallery( true );
		}
		if ( isset( $this->mDoubleUnderscores['notoc'] ) && !$this->mForceTocPosition ) {
			$this->mShowToc = false;
		}
		if ( isset( $this->mDoubleUnderscores['hiddencat'] )
			&& $this->getTitle()->getNamespace() === NS_CATEGORY
		) {
			$this->addTrackingCategory( 'hidden-category-category' );
		}
		# (T10068) Allow control over whether robots index a page.
		# __INDEX__ always overrides __NOINDEX__, see T16899
		if ( isset( $this->mDoubleUnderscores['noindex'] ) && $this->getTitle()->canUseNoindex() ) {
			$this->mOutput->setIndexPolicy( 'noindex' );
			$this->addTrackingCategory( 'noindex-category' );
		}
		if ( isset( $this->mDoubleUnderscores['index'] ) && $this->getTitle()->canUseNoindex() ) {
			$this->mOutput->setIndexPolicy( 'index' );
			$this->addTrackingCategory( 'index-category' );
		}

		# Cache all double underscores in the database
		foreach ( $this->mDoubleUnderscores as $key => $val ) {
			$this->mOutput->setUnsortedPageProperty( $key );
		}

		return $text;
	}

	/**
	 * @see TrackingCategories::addTrackingCategory()
	 * @param string $msg Message key
	 * @return bool Whether the addition was successful
	 * @since 1.19 method is public
	 */
	public function addTrackingCategory( $msg ) {
		return $this->trackingCategories->addTrackingCategory(
			$this->mOutput, $msg, $this->getPage()
		);
	}

	/**
	 * Helper function to correctly set the target language and title of
	 * a message based on the parser context. Most uses of system messages
	 * inside extensions or parser functions should use this method (instead
	 * of directly using `wfMessage`) to ensure that the cache is not
	 * polluted.
	 *
	 * @param string $msg The localization message key
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
	 *   See Message::params()
	 * @return Message
	 * @since 1.40
	 * @see https://phabricator.wikimedia.org/T202481
	 */
	public function msg( string $msg, ...$params ): Message {
		return wfMessage( $msg, ...$params )
			->inLanguage( $this->getTargetLanguage() )
			->page( $this->getPage() );
	}

	private function cleanUpTocLine( Node $container ) {
		'@phan-var Element|DocumentFragment $container';  // @var Element|DocumentFragment $container
		# Strip out HTML
		# Allowed tags are:
		# * <sup> and <sub> (T10393)
		# * <i> (T28375)
		# * <b> (r105284)
		# * <bdi> (T74884)
		# * <span dir="rtl"> and <span dir="ltr"> (T37167)
		# * <s> and <strike> (T35715)
		# * <q> (T251672)
		# We strip any parameter from accepted tags, except dir="rtl|ltr" from <span>,
		# to allow setting directionality in toc items.
		$allowedTags = [ 'span', 'sup', 'sub', 'bdi', 'i', 'b', 's', 'strike', 'q' ];
		$node = $container->firstChild;
		while ( $node !== null ) {
			$next = $node->nextSibling;
			if ( $node instanceof Element ) {
				$nodeName = DOMCompat::nodeName( $node );
				if ( in_array( $nodeName, [ 'style', 'script' ], true ) ) {
					# Remove any <style> or <script> tags (T198618)
					DOMCompat::remove( $node );
				} elseif ( in_array( $nodeName, $allowedTags, true ) ) {
					// Keep tag, remove attributes
					$removeAttrs = [];
					foreach ( $node->attributes as $attr ) {
						if (
							$nodeName === 'span' && $attr->name === 'dir'
							&& ( $attr->value === 'rtl' || $attr->value === 'ltr' )
						) {
							// Keep <span dir="rtl"> and <span dir="ltr">
							continue;
						}
						$removeAttrs[] = $attr;
					}
					foreach ( $removeAttrs as $attr ) {
						$node->removeAttributeNode( $attr );
					}
					$this->cleanUpTocLine( $node );
					# Strip '<span></span>', which is the result from the above if
					# <span id="foo"></span> is used to produce an additional anchor
					# for a section.
					if ( $nodeName === 'span' && !$node->hasChildNodes() ) {
						DOMCompat::remove( $node );
					}
				} else {
					// Strip tag
					$next = $node->firstChild;
					// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
					while ( $childNode = $node->firstChild ) {
						$node->parentNode->insertBefore( $childNode, $node );
					}
					DOMCompat::remove( $node );
				}
			} elseif ( $node instanceof Comment ) {
				// Extensions may add comments to headings;
				// these shouldn't appear in the ToC either.
				DOMCompat::remove( $node );
			}
			$node = $next;
		}
	}

	/**
	 * This function accomplishes several tasks:
	 * 1) Auto-number headings if that option is enabled
	 * 2) Add an [edit] link to sections for users who have enabled the option and can edit the page
	 * 3) Add a Table of contents on the top for users who have enabled the option
	 * 4) Auto-anchor headings
	 *
	 * It loops through all headlines, collects the necessary data, then splits up the
	 * string and re-inserts the newly formatted headlines.
	 *
	 * @param string $text
	 * @param string $origText Original, untouched wikitext
	 * @param bool $isMain
	 * @return string
	 */
	private function finalizeHeadings( $text, $origText, $isMain = true ) {
		# Inhibit editsection links if requested in the page
		if ( isset( $this->mDoubleUnderscores['noeditsection'] ) ) {
			$maybeShowEditLink = false;
		} else {
			$maybeShowEditLink = true; /* Actual presence will depend on post-cache transforms */
		}

		# Get all headlines for numbering them and adding funky stuff like [edit]
		# links - this is for later, but we need the number of headlines right now
		# NOTE: white space in headings have been trimmed in handleHeadings. They shouldn't
		# be trimmed here since whitespace in HTML headings is significant.
		$matches = [];
		$numMatches = preg_match_all(
			'/<H(?P<level>[1-6])(?P<attrib>.*?>)(?P<header>[\s\S]*?)<\/H[1-6] *>/i',
			$text,
			$matches
		);

		# if there are fewer than 4 headlines in the article, do not show TOC
		# unless it's been explicitly enabled.
		$enoughToc = $this->mShowToc &&
			( ( $numMatches >= 4 ) || $this->mForceTocPosition );

		# Allow user to stipulate that a page should have a "new section"
		# link added via __NEWSECTIONLINK__
		if ( isset( $this->mDoubleUnderscores['newsectionlink'] ) ) {
			$this->mOutput->setNewSection( true );
		}

		# Allow user to remove the "new section"
		# link via __NONEWSECTIONLINK__
		if ( isset( $this->mDoubleUnderscores['nonewsectionlink'] ) ) {
			$this->mOutput->setHideNewSection( true );
		}

		# if the string __FORCETOC__ (not case-sensitive) occurs in the HTML,
		# override above conditions and always show TOC above first header
		if ( isset( $this->mDoubleUnderscores['forcetoc'] ) ) {
			$this->mShowToc = true;
			$enoughToc = true;
		}

		if ( !$numMatches ) {
			return $text;
		}

		# headline counter
		$headlineCount = 0;
		$haveTocEntries = false;

		# Ugh .. the TOC should have neat indentation levels which can be
		# passed to the skin functions. These are determined here
		$head = [];
		$level = 0;
		$tocData = new TOCData();
		$baseTitleText = $this->getTitle()->getPrefixedDBkey();
		$oldType = $this->mOutputType;
		$this->setOutputType( self::OT_WIKI );
		$frame = $this->getPreprocessor()->newFrame();
		$root = $this->preprocessToDom( $origText );
		$node = $root->getFirstChild();
		$cpOffset = 0;
		$refers = [];

		$maxTocLevel = $this->svcOptions->get( MainConfigNames::MaxTocLevel );
		$domDocument = DOMUtils::parseHTML( '' );
		foreach ( $matches[3] as $headline ) {
			// $headline is half-parsed HTML
			$isTemplate = false;
			$titleText = false;
			$sectionIndex = false;
			if ( preg_match( self::HEADLINE_MARKER_REGEX, $headline, $markerMatches ) ) {
				$serial = (int)$markerMatches[1];
				[ $titleText, $sectionIndex ] = $this->mHeadings[$serial];
				$isTemplate = ( $titleText != $baseTitleText );
				$headline = ltrim( substr( $headline, strlen( $markerMatches[0] ) ) );
			}

			$sectionMetadata = SectionMetadata::fromLegacy( [
				"fromtitle" => $titleText ?: null,
				"index" => $sectionIndex === false
					? '' : ( ( $isTemplate ? 'T-' : '' ) . $sectionIndex )
			] );
			$tocData->addSection( $sectionMetadata );

			$oldLevel = $level;
			$level = (int)$matches[1][$headlineCount];
			$tocData->processHeading( $oldLevel, $level, $sectionMetadata );

			if ( $tocData->getCurrentTOCLevel() < $maxTocLevel ) {
				$haveTocEntries = true;
			}

			# Remove link placeholders by the link text.
			#     <!--LINK number-->
			# turns into
			#     link text with suffix
			# Do this before unstrip since link text can contain strip markers
			$fullyParsedHeadline = $this->replaceLinkHoldersText( $headline );

			# Avoid insertion of weird stuff like <math> by expanding the relevant sections
			$fullyParsedHeadline = $this->mStripState->unstripBoth( $fullyParsedHeadline );

			// Run Tidy to convert wikitext entities to HTML entities (T355386),
			// conveniently also giving us a way to handle French spaces (T324763)
			$fullyParsedHeadline = $this->tidy->tidy( $fullyParsedHeadline, [ Sanitizer::class, 'armorFrenchSpaces' ] );

			// Wrap the safe headline to parse the heading attributes
			// Literal HTML tags should be sanitized at this point
			// cleanUpTocLine will strip the headline tag
			$wrappedHeadline = "<h$level" . $matches['attrib'][$headlineCount] . $fullyParsedHeadline . "</h$level>";

			// Parse the heading contents as HTML. This makes it easier to strip out some HTML tags,
			// and ensures that we generate balanced HTML at the end (T218330).
			$headlineDom = DOMUtils::parseHTMLToFragment( $domDocument, $wrappedHeadline );

			// Extract a user defined id on the heading
			// A heading is expected as the first child and could be asserted
			$h = $headlineDom->firstChild;
			$headingId = ( $h instanceof Element && DOMUtils::isHeading( $h ) ) ?
				DOMCompat::getAttribute( $h, 'id' ) : null;

			$this->cleanUpTocLine( $headlineDom );

			// Serialize back to HTML
			// $tocline is for the TOC display, fully-parsed HTML with some tags removed
			$tocline = trim( DOMUtils::getFragmentInnerHTML( $headlineDom ) );

			// $headlineText is for the "Edit section: $1" tooltip, plain text
			$headlineText = trim( $headlineDom->textContent );

			if ( $headingId === null || $headingId === '' ) {
				$headingId = Sanitizer::normalizeSectionNameWhitespace( $headlineText );
				$headingId = self::normalizeSectionName( $headingId );
			}

			# Create the anchor for linking from the TOC to the section
			$fallbackAnchor = Sanitizer::escapeIdForAttribute( $headingId, Sanitizer::ID_FALLBACK );
			$linkAnchor = Sanitizer::escapeIdForLink( $headingId );
			$anchor = Sanitizer::escapeIdForAttribute( $headingId, Sanitizer::ID_PRIMARY );
			if ( $fallbackAnchor === $anchor ) {
				# No reason to have both (in fact, we can't)
				$fallbackAnchor = false;
			}

			# HTML IDs must be case-insensitively unique for IE compatibility (T12721).
			$arrayKey = strtolower( $anchor );
			if ( $fallbackAnchor === false ) {
				$fallbackArrayKey = false;
			} else {
				$fallbackArrayKey = strtolower( $fallbackAnchor );
			}

			if ( isset( $refers[$arrayKey] ) ) {
				for ( $i = 2; isset( $refers["{$arrayKey}_$i"] ); ++$i );
				$anchor .= "_$i";
				$linkAnchor .= "_$i";
				$refers["{$arrayKey}_$i"] = true;
			} else {
				$refers[$arrayKey] = true;
			}
			if ( $fallbackAnchor !== false && isset( $refers[$fallbackArrayKey] ) ) {
				for ( $i = 2; isset( $refers["{$fallbackArrayKey}_$i"] ); ++$i );
				$fallbackAnchor .= "_$i";
				$refers["{$fallbackArrayKey}_$i"] = true;
			} else {
				$refers[$fallbackArrayKey] = true;
			}

			# Add the section to the section tree
			# Find the DOM node for this header
			$noOffset = ( $isTemplate || $sectionIndex === false );
			while ( $node && !$noOffset ) {
				if ( $node->getName() === 'h' ) {
					$bits = $node->splitHeading();
					if ( $bits['i'] == $sectionIndex ) {
						break;
					}
				}
				$cpOffset += mb_strlen(
					$this->mStripState->unstripBoth(
						$frame->expand( $node, PPFrame::RECOVER_ORIG )
					)
				);
				$node = $node->getNextSibling();
			}
			$sectionMetadata->line = $tocline;
			$sectionMetadata->codepointOffset = ( $noOffset ? null : $cpOffset );
			$sectionMetadata->anchor = $anchor;
			$sectionMetadata->linkAnchor = $linkAnchor;

			if ( $maybeShowEditLink && $sectionIndex !== false ) {
				// Output edit section links as markers with styles that can be customized by skins
				if ( $isTemplate ) {
					# Put a T flag in the section identifier, to indicate to extractSections()
					# that sections inside <includeonly> should be counted.
					$editsectionPage = $titleText;
					$editsectionSection = "T-$sectionIndex";
				} else {
					$editsectionPage = $this->getTitle()->getPrefixedText();
					$editsectionSection = $sectionIndex;
				}
				// Construct a pseudo-HTML tag as a placeholder for the section edit link. It is replaced in
				// MediaWiki\OutputTransform\Stages\HandleSectionLinks with the real link.
				//
				// Any HTML markup in the input has already been escaped,
				// so we don't have to worry about a user trying to input one of these markers directly.
				//
				// We put the page and section in attributes to stop the language converter from
				// converting them, but put the headline hint in tag content
				// because it is supposed to be able to convert that.
				$editlink = '<mw:editsection page="' . htmlspecialchars( $editsectionPage, ENT_COMPAT );
				$editlink .= '" section="' . htmlspecialchars( $editsectionSection, ENT_COMPAT ) . '"';
				$editlink .= '>' . htmlspecialchars( $headlineText ) . '</mw:editsection>';
			} else {
				$editlink = '';
			}
			// Reconstruct the original <h#> tag with added attributes. It is replaced in
			// MediaWiki\OutputTransform\Stages\HandleSectionLinks to add anchors and stuff.
			//
			// data-mw-... attributes are forbidden in Sanitizer::isReservedDataAttribute(),
			// so we don't have to worry about a user trying to input one of these markers directly.
			//
			// We put the anchors in attributes to stop the language converter from converting them.
			$head[$headlineCount] = "<h$level" . Html::expandAttributes( [
				'data-mw-anchor' => $anchor,
				'data-mw-fallback-anchor' => $fallbackAnchor,
			] ) . $matches['attrib'][$headlineCount] . $headline . $editlink . "</h$level>";

			$headlineCount++;
		}

		$this->setOutputType( $oldType );

		# Never ever show TOC if no headers (or suppressed)
		$suppressToc = $this->mOptions->getSuppressTOC();
		if ( !$haveTocEntries ) {
			$enoughToc = false;
		}
		$addTOCPlaceholder = false;

		if ( $isMain && !$suppressToc ) {
			// We generally output the section information via the API
			// even if there isn't "enough" of a ToC to merit showing
			// it -- but the "suppress TOC" parser option is set when
			// any sections that might be found aren't "really there"
			// (ie, JavaScript content that might have spurious === or
			// <h2>: T307691) so we will *not* set section information
			// in that case.
			$this->mOutput->setTOCData( $tocData );

			// T294950: Record a suggestion that the TOC should be shown.
			// Skins are free to ignore this suggestion and implement their
			// own criteria for showing/suppressing TOC (T318186).
			if ( $enoughToc ) {
				$this->mOutput->setOutputFlag( ParserOutputFlags::SHOW_TOC );
				if ( !$this->mForceTocPosition ) {
					$addTOCPlaceholder = true;
				}
			}

			// If __NOTOC__ is used on the page (and not overridden by
			// __TOC__ or __FORCETOC__) set the NO_TOC flag to tell
			// the skin that although the section information is
			// valid, it should perhaps not be presented as a Table Of
			// Contents.
			if ( !$this->mShowToc ) {
				$this->mOutput->setOutputFlag( ParserOutputFlags::NO_TOC );
			}
		}

		# split up and insert constructed headlines
		$blocks = preg_split( '/<h[1-6]\b[^>]*>.*?<\/h[1-6]>/is', $text );
		$i = 0;

		// build an array of document sections
		$sections = [];
		foreach ( $blocks as $block ) {
			// $head is zero-based, sections aren't.
			if ( empty( $head[$i - 1] ) ) {
				$sections[$i] = $block;
			} else {
				$sections[$i] = $head[$i - 1] . $block;
			}

			$i++;
		}

		if ( $addTOCPlaceholder ) {
			// append the TOC at the beginning
			// Top anchor now in skin
			// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset At least one element when enoughToc is true
			$sections[0] .= self::TOC_PLACEHOLDER . "\n";
		}

		return implode( '', $sections );
	}

	/**
	 * Localize the TOC into the given target language; this includes
	 * invoking the language converter on the headings.
	 * @param ?TOCData $tocData The Table of Contents
	 * @param Language $lang The target language
	 * @param ?ILanguageConverter $converter The target language converter, or
	 *   null if language conversion is to be suppressed.
	 * @internal
	 */
	private static function localizeTOC(
		?TOCData $tocData, Language $lang, ?ILanguageConverter $converter
	) {
		if ( $tocData === null ) {
			return; // Nothing to do
		}
		foreach ( $tocData->getSections() as $s ) {
			// Localize heading
			if ( $converter ) {
				// T331316: don't use 'convert' or 'convertTo' as these reset
				// the language converter state.
				$s->line = $converter->convertTo(
					$s->line, $converter->getPreferredVariant(), false
				);
			}
			// Localize numbering
			$dot = '.';
			$pieces = explode( $dot, $s->number );
			$numbering = '';
			foreach ( $pieces as $i => $p ) {
				if ( $i > 0 ) {
					$numbering .= $dot;
				}
				$numbering .= $lang->formatNum( $p );
			}
			$s->number = $numbering;
		}
	}

	/**
	 * Transform wiki markup when saving a page by doing "\r\n" -> "\n"
	 * conversion, substituting signatures, {{subst:}} templates, etc.
	 *
	 * @param string $text The text to transform
	 * @param PageReference $page the current article
	 * @param UserIdentity $user the current user
	 * @param ParserOptions $options Parsing options
	 * @param bool $clearState Whether to clear the parser state first
	 * @return string The altered wiki markup
	 * @since 1.3
	 */
	public function preSaveTransform(
		$text,
		PageReference $page,
		UserIdentity $user,
		ParserOptions $options,
		$clearState = true
	) {
		if ( $clearState ) {
			$magicScopeVariable = $this->lock();
		}
		$this->startParse( $page, $options, self::OT_WIKI, $clearState );
		$this->setUser( $user );

		// Strip U+0000 NULL (T159174)
		$text = str_replace( "\000", '', $text );

		// We still normalize line endings (including trimming trailing whitespace) for
		// backwards-compatibility with other code that just calls PST, but this should already
		// be handled in TextContent subclasses
		$text = TextContent::normalizeLineEndings( $text );

		if ( $options->getPreSaveTransform() ) {
			$text = $this->pstPass2( $text, $user );
		}
		$text = $this->mStripState->unstripBoth( $text );

		// Trim trailing whitespace again, because the previous steps can introduce it.
		$text = rtrim( $text );

		$this->hookRunner->onParserPreSaveTransformComplete( $this, $text );

		$this->setUser( null ); # Reset

		return $text;
	}

	/**
	 * Pre-save transform helper function
	 *
	 * @param string $text
	 * @param UserIdentity $user
	 *
	 * @return string
	 */
	private function pstPass2( $text, UserIdentity $user ) {
		# Note: This is the timestamp saved as hardcoded wikitext to the database, we use
		# $this->contLang here in order to give everyone the same signature and use the default one
		# rather than the one selected in each user's preferences.  (see also T14815)
		$ts = $this->mOptions->getTimestamp();
		$timestamp = MWTimestamp::getLocalInstance( $ts );
		$ts = $timestamp->format( 'YmdHis' );
		$tzMsg = $timestamp->getTimezoneMessage()->inContentLanguage()->text();

		$d = $this->contLang->timeanddate( $ts, false, false ) . " ($tzMsg)";

		# Variable replacement
		# Because mOutputType is OT_WIKI, this will only process {{subst:xxx}} type tags
		$text = $this->replaceVariables( $text );

		# This works almost by chance, as the replaceVariables are done before the getUserSig(),
		# which may corrupt this parser instance via its wfMessage()->text() call-

		# Signatures
		if ( strpos( $text, '~~~' ) !== false ) {
			$sigText = $this->getUserSig( $user );
			$text = strtr( $text, [
				'~~~~~' => $d,
				'~~~~' => "$sigText $d",
				'~~~' => $sigText
			] );
			# The main two signature forms used above are time-sensitive
			$this->setOutputFlag( ParserOutputFlags::USER_SIGNATURE, 'User signature detected' );
		}

		# Context links ("pipe tricks"): [[|name]] and [[name (context)|]]
		$tc = '[' . Title::legalChars() . ']';
		$nc = '[ _0-9A-Za-z\x80-\xff-]'; # Namespaces can use non-ascii!

		// [[ns:page (context)|]]
		$p1 = "/\[\[(:?$nc+:|:|)($tc+?)( ?\\($tc+\\))\\|]]/";
		// [[ns:page（context）|]] (double-width brackets, added in r40257)
		$p4 = "/\[\[(:?$nc+:|:|)($tc+?)( ?（$tc+）)\\|]]/";
		// [[ns:page (context), context|]] (using single, double-width or Arabic comma)
		$p3 = "/\[\[(:?$nc+:|:|)($tc+?)( ?\\($tc+\\)|)((?:, |，|، )$tc+|)\\|]]/";
		// [[|page]] (reverse pipe trick: add context from page title)
		$p2 = "/\[\[\\|($tc+)]]/";

		# try $p1 first, to turn "[[A, B (C)|]]" into "[[A, B (C)|A, B]]"
		$text = preg_replace( $p1, '[[\\1\\2\\3|\\2]]', $text );
		$text = preg_replace( $p4, '[[\\1\\2\\3|\\2]]', $text );
		$text = preg_replace( $p3, '[[\\1\\2\\3\\4|\\2]]', $text );

		$t = $this->getTitle()->getText();
		$m = [];
		if ( preg_match( "/^($nc+:|)$tc+?( \\($tc+\\))$/", $t, $m ) ) {
			$text = preg_replace( $p2, "[[$m[1]\\1$m[2]|\\1]]", $text );
		} elseif ( preg_match( "/^($nc+:|)$tc+?(, $tc+|)$/", $t, $m ) && "$m[1]$m[2]" != '' ) {
			$text = preg_replace( $p2, "[[$m[1]\\1$m[2]|\\1]]", $text );
		} else {
			# if there's no context, don't bother duplicating the title
			$text = preg_replace( $p2, '[[\\1]]', $text );
		}

		return $text;
	}

	/**
	 * Fetch the user's signature text, if any, and normalize to
	 * validated, ready-to-insert wikitext.
	 * If you have pre-fetched the nickname or the fancySig option, you can
	 * specify them here to save a database query.
	 * Do not reuse this parser instance after calling getUserSig(),
	 * as it may have changed.
	 *
	 * @param UserIdentity $user
	 * @param string|false $nickname Nickname to use or false to use user's default nickname
	 * @param bool|null $fancySig whether the nicknname is the complete signature
	 *    or null to use default value
	 * @return string
	 * @since 1.6
	 */
	public function getUserSig( UserIdentity $user, $nickname = false, $fancySig = null ) {
		$username = $user->getName();

		# If not given, retrieve from the user object.
		if ( $nickname === false ) {
			$nickname = $this->userOptionsLookup->getOption( $user, 'nickname' );
		}

		$fancySig ??= $this->userOptionsLookup->getBoolOption( $user, 'fancysig' );

		if ( $nickname === null || $nickname === '' ) {
			// Empty value results in the default signature (even when fancysig is enabled)
			$nickname = $username;
		} elseif ( mb_strlen( $nickname ) > $this->svcOptions->get( MainConfigNames::MaxSigChars ) ) {
			$nickname = $username;
			$this->logger->debug( __METHOD__ . ": $username has overlong signature." );
		} elseif ( $fancySig !== false ) {
			# Sig. might contain markup; validate this
			$isValid = $this->validateSig( $nickname ) !== false;

			# New validator
			$sigValidation = $this->svcOptions->get( MainConfigNames::SignatureValidation );
			if ( $isValid && $sigValidation === 'disallow' ) {
				$parserOpts = new ParserOptions(
					$this->mOptions->getUserIdentity(),
					$this->contLang
				);
				$validator = $this->signatureValidatorFactory
					->newSignatureValidator( $user, null, $parserOpts );
				$isValid = !$validator->validateSignature( $nickname );
			}

			if ( $isValid ) {
				# Validated; clean up (if needed) and return it
				return $this->cleanSig( $nickname, true );
			} else {
				# Failed to validate; fall back to the default
				$nickname = $username;
				$this->logger->debug( __METHOD__ . ": $username has invalid signature." );
			}
		}

		# Make sure nickname doesnt get a sig in a sig
		$nickname = self::cleanSigInSig( $nickname );

		# If we're still here, make it a link to the user page
		$userText = wfEscapeWikiText( $username );
		$nickText = wfEscapeWikiText( $nickname );
		if ( $this->userNameUtils->isTemp( $username ) ) {
			$msgName = 'signature-temp';
		} elseif ( $user->isRegistered() ) {
			$msgName = 'signature';
		} else {
			$msgName = 'signature-anon';
		}

		return wfMessage( $msgName, $userText, $nickText )->inContentLanguage()
			->page( $this->getPage() )->text();
	}

	/**
	 * Check that the user's signature contains no bad XML
	 *
	 * @param string $text
	 * @return string|false An expanded string, or false if invalid.
	 * @since 1.6
	 */
	public function validateSig( $text ) {
		return Xml::isWellFormedXmlFragment( $text ) ? $text : false;
	}

	/**
	 * Clean up signature text
	 *
	 * 1) Strip 3, 4 or 5 tildes out of signatures, see {@link cleanSigInSig}
	 * 2) Substitute all transclusions
	 *
	 * @param string $text
	 * @param bool $parsing Whether we're cleaning (preferences save) or parsing
	 * @return string Signature text
	 * @since 1.6
	 */
	public function cleanSig( $text, $parsing = false ) {
		if ( !$parsing ) {
			$magicScopeVariable = $this->lock();
			$this->startParse(
				$this->mTitle,
				ParserOptions::newFromUser( RequestContext::getMain()->getUser() ),
				self::OT_PREPROCESS,
				true
			);
		}

		# Option to disable this feature
		if ( !$this->mOptions->getCleanSignatures() ) {
			return $text;
		}

		# @todo FIXME: Regex doesn't respect extension tags or nowiki
		#  => Move this logic to braceSubstitution()
		$substWord = $this->magicWordFactory->get( 'subst' );
		$substRegex = '/\{\{(?!(?:' . $substWord->getBaseRegex() . '))/x' . $substWord->getRegexCase();
		$substText = '{{' . $substWord->getSynonym( 0 );

		$text = preg_replace( $substRegex, $substText, $text );
		$text = self::cleanSigInSig( $text );
		$dom = $this->preprocessToDom( $text );
		$frame = $this->getPreprocessor()->newFrame();
		$text = $frame->expand( $dom );

		if ( !$parsing ) {
			$text = $this->mStripState->unstripBoth( $text );
		}

		return $text;
	}

	/**
	 * Strip 3, 4 or 5 tildes out of signatures.
	 *
	 * @param string $text
	 * @return string Signature text with /~{3,5}/ removed
	 * @since 1.7
	 */
	public static function cleanSigInSig( $text ) {
		$text = preg_replace( '/~{3,5}/', '', $text );
		return $text;
	}

	/**
	 * Replace table of contents marker in parsed HTML.
	 *
	 * Used to remove or replace the marker.  This method should be
	 * used instead of direct access to Parser::TOC_PLACEHOLDER, since
	 * in the future the placeholder might have additional attributes
	 * attached which should be ignored when the replacement is made.
	 *
	 * @since 1.38
	 * @stable
	 *
	 * @param string $text Parsed HTML
	 * @param string $toc HTML table of contents string, or else an empty
	 *   string to remove the marker.
	 * @return string Result HTML
	 */
	public static function replaceTableOfContentsMarker( $text, $toc ) {
		// Optimization: Avoid a potentially expensive Remex tokenization and reserialization
		// if the content does not contain a TOC placeholder, such as during message parsing,
		// which may occur hundreds of times per request (T394059).
		if ( !str_contains( $text, 'mw:PageProp/toc' ) ) {
			return $text;
		}

		$replaced = false;
		return HtmlHelper::modifyElements(
			$text,
			static function ( SerializerNode $node ): bool {
				$prop = $node->attrs['property'] ?? '';
				return $node->name === 'meta' && $prop === 'mw:PageProp/toc';
			},
			static function ( SerializerNode $node ) use ( &$replaced, $toc ) {
				if ( $replaced ) {
					// Remove the additional metas. While not strictly
					// necessary, this also ensures idempotence if we
					// run the pass more than once on a given content.
					return '';
				}
				$replaced = true;
				return $toc; // outerHTML replacement.
			},
			false /* use legacy-compatible serialization */
		);
	}

	/**
	 * Set up some variables which are usually set up in parse()
	 * so that an external function can call some class members with confidence
	 *
	 * @param ?PageReference $page
	 * @param ParserOptions $options
	 * @param int $outputType One of the Parser::OT_… constants
	 * @param bool $clearState
	 * @param int|null $revId
	 * @since 1.3
	 */
	public function startExternalParse( ?PageReference $page, ParserOptions $options,
		$outputType, $clearState = true, $revId = null
	) {
		$this->startParse( $page, $options, $outputType, $clearState );
		if ( $revId !== null ) {
			$this->mRevisionId = $revId;
		}
	}

	/**
	 * @param ?PageReference $page
	 * @param ParserOptions $options
	 * @param int $outputType
	 * @param bool $clearState
	 */
	private function startParse( ?PageReference $page, ParserOptions $options,
		$outputType, $clearState = true
	) {
		$this->setPage( $page );
		$this->mOptions = $options;
		$this->setOutputType( $outputType );
		if ( $clearState ) {
			$this->clearState();
		}
	}

	/**
	 * Wrapper for preprocess()
	 *
	 * @param string $text The text to preprocess
	 * @param ParserOptions $options
	 * @param ?PageReference $page The context page
	 * @return string
	 * @since 1.3
	 */
	public function transformMsg( $text, ParserOptions $options, ?PageReference $page = null ) {
		static $executing = false;

		# Guard against infinite recursion
		if ( $executing ) {
			return $text;
		}
		$executing = true;

		$text = $this->preprocess( $text, $page ?? $this->mTitle, $options );

		$executing = false;
		return $text;
	}

	/**
	 * Create an HTML-style tag, e.g. "<yourtag>special text</yourtag>"
	 * The callback should have the following form:
	 *    function myParserHook( $text, array $params, Parser $parser, PPFrame $frame ) { ... }
	 *
	 * Transform and return $text. Use $parser for any required context, e.g. use
	 * $parser->getTitle() and $parser->getOptions() not $wgTitle or $wgOut->mParserOptions
	 *
	 * Hooks may return extended information by returning an array, of which the
	 * first numbered element (index 0) must be the return string. The following other
	 * keys are used:
	 *  - 'markerType': used by some core tag hooks to override which strip
	 *    array their results are placed in, 'general' or 'nowiki'.
	 *
	 * @param string $tag The tag to use, e.g. 'hook' for "<hook>"
	 * @param callable $callback The callback to use for the tag
	 * @return callable|null The old value of the mTagHooks array associated with the hook
	 * @since 1.3
	 */
	public function setHook( $tag, callable $callback ) {
		$tag = strtolower( $tag );
		if ( preg_match( '/[<>\r\n]/', $tag, $m ) ) {
			throw new InvalidArgumentException( "Invalid character {$m[0]} in setHook('$tag', ...) call" );
		}
		$oldVal = $this->mTagHooks[$tag] ?? null;
		$this->mTagHooks[$tag] = $callback;
		if ( !in_array( $tag, $this->mStripList ) ) {
			$this->mStripList[] = $tag;
		}

		return $oldVal;
	}

	/**
	 * Remove all tag hooks
	 * @since 1.12
	 */
	public function clearTagHooks() {
		$this->mTagHooks = [];
		$this->mStripList = [];
	}

	/**
	 * Create a function, e.g. {{sum:1|2|3}}
	 * The callback function should have the form:
	 *    function myParserFunction( &$parser, $arg1, $arg2, $arg3 ) { ... }
	 *
	 * Or with Parser::SFH_OBJECT_ARGS:
	 *    function myParserFunction( $parser, $frame, $args ) { ... }
	 *
	 * The callback may either return the text result of the function, or an array with the text
	 * in element 0, and a number of flags in the other elements. The names of the flags are
	 * specified in the keys. Valid flags are:
	 *   found                     The text returned is valid, stop processing the template. This
	 *                             is on by default.
	 *   nowiki                    Wiki markup in the return value should be escaped
	 *   isHTML                    The returned text is HTML, armour it
	 *                             against most wikitext transformation, but
	 *                             perform language conversion and some other
	 *                             postprocessing
	 *   isRawHTML                 The returned text is raw HTML, include it
	 *                             verbatim in the output.
	 *
	 * @param string $id The magic word ID
	 * @param callable $callback The callback function (and object) to use
	 * @param int $flags A combination of the following flags:
	 *     Parser::SFH_NO_HASH      No leading hash, i.e. {{plural:...}} instead of {{#if:...}}
	 *
	 *     Parser::SFH_OBJECT_ARGS  Pass the template arguments as PPNode objects instead of text.
	 *     This allows for conditional expansion of the parse tree, allowing you to eliminate dead
	 *     branches and thus speed up parsing. It is also possible to analyse the parse tree of
	 *     the arguments, and to control the way they are expanded.
	 *
	 *     The $frame parameter is a PPFrame. This can be used to produce expanded text from the
	 *     arguments, for instance:
	 *         $text = isset( $args[0] ) ? $frame->expand( $args[0] ) : '';
	 *
	 *     For technical reasons, $args[0] is pre-expanded and will be a string. This may change in
	 *     future versions. Please call $frame->expand() on it anyway so that your code keeps
	 *     working if/when this is changed.
	 *
	 *     If you want whitespace to be trimmed from $args, you need to do it yourself, post-
	 *     expansion.
	 *
	 *     Please read the documentation in includes/parser/Preprocessor.php for more information
	 *     about the methods available in PPFrame and PPNode.
	 *
	 * @return string|callable|null The old callback function for this name, if any
	 * @since 1.6
	 */
	public function setFunctionHook( $id, callable $callback, $flags = 0 ) {
		$oldVal = $this->mFunctionHooks[$id][0] ?? null;
		$this->mFunctionHooks[$id] = [ $callback, $flags ];

		# Add to function cache
		$mw = $this->magicWordFactory->get( $id );

		$synonyms = $mw->getSynonyms();
		$sensitive = intval( $mw->isCaseSensitive() );

		foreach ( $synonyms as $syn ) {
			# Case
			if ( !$sensitive ) {
				$syn = $this->contLang->lc( $syn );
			}
			# Add leading hash
			if ( !( $flags & self::SFH_NO_HASH ) ) {
				$syn = '#' . $syn;
			}
			# Remove trailing colon
			if ( substr( $syn, -1, 1 ) === ':' ) {
				$syn = substr( $syn, 0, -1 );
			}
			$this->mFunctionSynonyms[$sensitive][$syn] = $id;
		}
		return $oldVal;
	}

	/**
	 * Get all registered function hook identifiers
	 *
	 * @return array
	 * @since 1.8
	 */
	public function getFunctionHooks() {
		return array_keys( $this->mFunctionHooks );
	}

	/**
	 * Replace "<!--LINK-->" link placeholders with actual links, in the buffer
	 * Placeholders created in Linker::link()
	 *
	 * @param string &$text
	 * @deprecated since 1.34; should not be used outside parser class.
	 */
	public function replaceLinkHolders( &$text ) {
		$this->replaceLinkHoldersPrivate( $text );
	}

	/**
	 * Replace "<!--LINK-->" link placeholders with actual links, in the buffer
	 * Placeholders created in Linker::link()
	 *
	 * @param string &$text
	 */
	private function replaceLinkHoldersPrivate( &$text ) {
		$this->mLinkHolders->replace( $text );
	}

	/**
	 * Replace "<!--LINK-->" link placeholders with plain text of links
	 * (not HTML-formatted).
	 *
	 * @param string $text
	 * @return string
	 */
	private function replaceLinkHoldersText( $text ) {
		return $this->mLinkHolders->replaceText( $text );
	}

	/**
	 * Renders an image gallery from a text with one line per image.
	 * text labels may be given by using |-style alternative text. E.g.
	 *   Image:one.jpg|The number "1"
	 *   Image:tree.jpg|A tree
	 * given as text will return the HTML of a gallery with two images,
	 * labeled 'The number "1"' and
	 * 'A tree'.
	 *
	 * @param string $text
	 * @param array $params
	 * @return string HTML
	 * @internal
	 */
	public function renderImageGallery( $text, array $params ) {
		$mode = $params['mode'] ?? false;

		try {
			$ig = ImageGalleryBase::factory( $mode );
		} catch ( ImageGalleryClassNotFoundException ) {
			// If invalid type set, fallback to default.
			$ig = ImageGalleryBase::factory();
		}

		$ig->setContextTitle( $this->getTitle() );
		$ig->setShowBytes( false );
		$ig->setShowDimensions( false );
		$ig->setParser( $this );
		$ig->setHideBadImages();
		$ig->setAttributes( Sanitizer::validateTagAttributes( $params, 'ul' ) );

		$ig->setShowFilename( isset( $params['showfilename'] ) );
		if ( isset( $params['caption'] ) ) {
			// NOTE: We aren't passing a frame here or below.  Frame info
			// is currently opaque to Parsoid, which acts on OT_PREPROCESS.
			// See T107332#4030581
			$caption = $this->recursiveTagParse( $params['caption'] );
			$ig->setCaptionHtml( $caption );
		}
		if ( isset( $params['perrow'] ) ) {
			$ig->setPerRow( $params['perrow'] );
		}
		if ( isset( $params['widths'] ) ) {
			$ig->setWidths( $params['widths'] );
		}
		if ( isset( $params['heights'] ) ) {
			$ig->setHeights( $params['heights'] );
		}
		$ig->setAdditionalOptions( $params );

		$lines = StringUtils::explode( "\n", $text );
		foreach ( $lines as $line ) {
			# match lines like these:
			# Image:someimage.jpg|This is some image
			$matches = [];
			preg_match( "/^([^|]+)(\\|(.*))?$/", $line, $matches );
			# Skip empty lines
			if ( count( $matches ) == 0 ) {
				continue;
			}

			if ( strpos( $matches[0], '%' ) !== false ) {
				$matches[1] = rawurldecode( $matches[1] );
			}
			$title = Title::newFromText( $matches[1], NS_FILE );
			if ( $title === null ) {
				# Bogus title. Ignore these so we don't bomb out later.
				continue;
			}

			# We need to get what handler the file uses, to figure out parameters.
			# Note, a hook can override the file name, and chose an entirely different
			# file (which potentially could be of a different type and have different handler).
			$options = [];
			$descQuery = false;
			$this->hookRunner->onBeforeParserFetchFileAndTitle(
				// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
				$this, $title, $options, $descQuery
			);
			# Don't register it now, as TraditionalImageGallery does that later.
			$file = $this->fetchFileNoRegister( $title, $options );
			$handler = $file ? $file->getHandler() : false;

			$paramMap = [
				'img_alt' => 'gallery-internal-alt',
				'img_link' => 'gallery-internal-link',
			];
			if ( $handler ) {
				$paramMap += $handler->getParamMap();
				// We don't want people to specify per-image widths.
				// Additionally the width parameter would need special casing anyhow.
				unset( $paramMap['img_width'] );
			}

			$mwArray = $this->magicWordFactory->newArray( array_keys( $paramMap ) );

			$label = '';
			$alt = null;
			$handlerOptions = [];
			$imageOptions = [];
			$hasAlt = false;

			if ( isset( $matches[3] ) ) {
				// look for an |alt= definition while trying not to break existing
				// captions with multiple pipes (|) in it, until a more sensible grammar
				// is defined for images in galleries

				// FIXME: Doing recursiveTagParse at this stage is a bit odd,
				// and different from makeImage.
				$matches[3] = $this->recursiveTagParse( $matches[3] );
				// Protect LanguageConverter markup
				$parameterMatches = StringUtils::delimiterExplode(
					'-{', '}-',
					'|',
					$matches[3],
					true /* nested */
				);

				foreach ( $parameterMatches as $parameterMatch ) {
					[ $magicName, $match ] = $mwArray->matchVariableStartToEnd( trim( $parameterMatch ) );
					if ( !$magicName ) {
						// Last pipe wins.
						$label = $parameterMatch;
						continue;
					}

					$paramName = $paramMap[$magicName];
					switch ( $paramName ) {
						case 'gallery-internal-alt':
							$hasAlt = true;
							$alt = $this->stripAltText( $match, false );
							break;
						case 'gallery-internal-link':
							$linkValue = $this->stripAltText( $match, false );
							if ( preg_match( '/^-{R\|(.*)}-$/', $linkValue ) ) {
								// Result of LanguageConverter::markNoConversion
								// invoked on an external link.
								$linkValue = substr( $linkValue, 4, -2 );
							}
							[ $type, $target ] = $this->parseLinkParameter( $linkValue );
							if ( $type ) {
								if ( $type === 'no-link' ) {
									$target = true;
								}
								$imageOptions[$type] = $target;
							}
							break;
						default:
							// Must be a handler specific parameter.
							if ( $handler->validateParam( $paramName, $match ) ) {
								$handlerOptions[$paramName] = $match;
							} else {
								// Guess not, consider it as caption.
								$this->logger->debug(
									"$parameterMatch failed parameter validation" );
								$label = $parameterMatch;
							}
					}
				}
			}

			// Match makeImage when !$hasVisibleCaption
			if ( !$hasAlt && $label !== '' ) {
				$alt = $this->stripAltText( $label, false );
			}
			$imageOptions['title'] = $this->stripAltText( $label, false );

			// Match makeImage which sets this unconditionally
			$handlerOptions['targetlang'] = $this->getTargetLanguage()->getCode();

			$ig->add(
				$title, $label, $alt, '', $handlerOptions,
				ImageGalleryBase::LOADING_DEFAULT, $imageOptions
			);
		}
		$html = $ig->toHTML();
		$this->hookRunner->onAfterParserFetchFileAndTitle( $this, $ig, $html );
		return $html;
	}

	/**
	 * @param MediaHandler|false $handler
	 * @return array
	 */
	private function getImageParams( $handler ) {
		$handlerClass = $handler ? get_class( $handler ) : '';
		if ( !isset( $this->mImageParams[$handlerClass] ) ) {
			# Initialise static lists
			static $internalParamNames = [
				'horizAlign' => [ 'left', 'right', 'center', 'none' ],
				'vertAlign' => [ 'baseline', 'sub', 'super', 'top', 'text-top', 'middle',
					'bottom', 'text-bottom' ],
				'frame' => [ 'thumbnail', 'framed', 'frameless', 'border',
					// These parameters take arguments, so to ensure literals
					// have precedence, keep them listed last (T372935):
					'manualthumb', 'upright', 'link', 'alt', 'class' ],
			];
			static $internalParamMap;
			if ( !$internalParamMap ) {
				$internalParamMap = [];
				foreach ( $internalParamNames as $type => $names ) {
					foreach ( $names as $name ) {
						// For grep: img_left, img_right, img_center, img_none,
						// img_baseline, img_sub, img_super, img_top, img_text_top, img_middle,
						// img_bottom, img_text_bottom,
						// img_thumbnail, img_manualthumb, img_framed, img_frameless, img_upright,
						// img_border, img_link, img_alt, img_class
						$magicName = str_replace( '-', '_', "img_$name" );
						$internalParamMap[$magicName] = [ $type, $name ];
					}
				}
			}

			# Add handler params
			# Since img_width is one of these, it is important it is listed
			# *after* the literal parameter names above (T372935).
			$paramMap = $internalParamMap;
			if ( $handler ) {
				$handlerParamMap = $handler->getParamMap();
				foreach ( $handlerParamMap as $magic => $paramName ) {
					$paramMap[$magic] = [ 'handler', $paramName ];
				}
			} else {
				// Parse the size for non-existent files.  See T273013
				$paramMap[ 'img_width' ] = [ 'handler', 'width' ];
			}
			$this->mImageParams[$handlerClass] = $paramMap;
			$this->mImageParamsMagicArray[$handlerClass] =
				$this->magicWordFactory->newArray( array_keys( $paramMap ) );
		}
		return [ $this->mImageParams[$handlerClass], $this->mImageParamsMagicArray[$handlerClass] ];
	}

	/**
	 * Parse image options text and use it to make an image
	 *
	 * @param LinkTarget $link
	 * @param string $options
	 * @param LinkHolderArray|false $holders
	 * @return string HTML
	 * @since 1.5
	 */
	public function makeImage( LinkTarget $link, $options, $holders = false ) {
		# Check if the options text is of the form "options|alt text"
		# Options are:
		#  * thumbnail  make a thumbnail with enlarge-icon and caption, alignment depends on lang
		#  * left       no resizing, just left align. label is used for alt= only
		#  * right      same, but right aligned
		#  * none       same, but not aligned
		#  * ___px      scale to ___ pixels width, no aligning. e.g. use in taxobox
		#  * center     center the image
		#  * framed     Keep original image size, no magnify-button.
		#  * frameless  like 'thumb' but without a frame. Keeps user preferences for width
		#  * upright    reduce width for upright images, rounded to full __0 px
		#  * border     draw a 1px border around the image
		#  * alt        Text for HTML alt attribute (defaults to empty)
		#  * class      Set a class for img node
		#  * link       Set the target of the image link. Can be external, interwiki, or local
		# vertical-align values (no % or length right now):
		#  * baseline
		#  * sub
		#  * super
		#  * top
		#  * text-top
		#  * middle
		#  * bottom
		#  * text-bottom

		# Protect LanguageConverter markup when splitting into parts
		$parts = StringUtils::delimiterExplode(
			'-{', '}-', '|', $options, true /* allow nesting */
		);

		# Give extensions a chance to select the file revision for us
		$options = [];
		$descQuery = false;
		$title = Title::castFromLinkTarget( $link ); // hook signature compat
		$this->hookRunner->onBeforeParserFetchFileAndTitle(
			// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
			$this, $title, $options, $descQuery
		);
		# Fetch and register the file (file title may be different via hooks)
		[ $file, $link ] = $this->fetchFileAndTitle( $link, $options );

		# Get parameter map
		$handler = $file ? $file->getHandler() : false;

		[ $paramMap, $mwArray ] = $this->getImageParams( $handler );

		if ( !$file ) {
			$this->addTrackingCategory( 'broken-file-category' );
		}

		# Process the input parameters
		$caption = '';
		$params = [ 'frame' => [], 'handler' => [],
			'horizAlign' => [], 'vertAlign' => [] ];
		$seenformat = false;
		foreach ( $parts as $part ) {
			[ $magicName, $value ] = $mwArray->matchVariableStartToEnd( trim( $part ) );
			$validated = false;
			if ( isset( $paramMap[$magicName] ) ) {
				[ $type, $paramName ] = $paramMap[$magicName];

				# Special case; width and height come in one variable together
				if ( $type === 'handler' && $paramName === 'width' ) {
					// The 'px' suffix has already been localized by img_width
					$parsedWidthParam = $this->parseWidthParam( $value, true, true );
					// Parsoid applies data-(width|height) attributes to broken
					// media spans, for client use.  See T273013
					$validateFunc = static function ( $name, $value ) use ( $handler ) {
						return $handler
							? $handler->validateParam( $name, $value )
							: $value > 0;
					};
					if ( isset( $parsedWidthParam['width'] ) ) {
						$width = $parsedWidthParam['width'];
						if ( $validateFunc( 'width', $width ) ) {
							$params[$type]['width'] = $width;
							$validated = true;
						}
					}
					if ( isset( $parsedWidthParam['height'] ) ) {
						$height = $parsedWidthParam['height'];
						if ( $validateFunc( 'height', $height ) ) {
							$params[$type]['height'] = $height;
							$validated = true;
						}
					}
					# else no validation -- T15436
				} else {
					if ( $type === 'handler' ) {
						# Validate handler parameter
						$validated = $handler->validateParam( $paramName, $value );
					} else {
						# Validate internal parameters
						switch ( $paramName ) {
							case 'alt':
							case 'class':
								$validated = true;
								$value = $this->stripAltText( $value, $holders );
								break;
							case 'link':
								[ $paramName, $value ] =
									$this->parseLinkParameter(
										$this->stripAltText( $value, $holders )
									);
								if ( $paramName ) {
									$validated = true;
									if ( $paramName === 'no-link' ) {
										$value = true;
									}
								}
								break;
							case 'manualthumb':
								# @todo FIXME: Possibly check validity here for
								# manualthumb? downstream behavior seems odd with
								# missing manual thumbs.
								$value = $this->stripAltText( $value, $holders );
								// fall through
							case 'frameless':
							case 'framed':
							case 'thumbnail':
								// use first appearing option, discard others.
								$validated = !$seenformat;
								$seenformat = true;
								break;
							default:
								# Most other things appear to be empty or numeric...
								$validated = ( $value === false || is_numeric( trim( $value ) ) );
						}
					}

					if ( $validated ) {
						$params[$type][$paramName] = $value;
					}
				}
			}
			if ( !$validated ) {
				$caption = $part;
			}
		}

		# Process alignment parameters
		if ( $params['horizAlign'] !== [] ) {
			$params['frame']['align'] = array_key_first( $params['horizAlign'] );
		}
		if ( $params['vertAlign'] !== [] ) {
			$params['frame']['valign'] = array_key_first( $params['vertAlign'] );
		}

		$params['frame']['caption'] = $caption;

		# Will the image be presented in a frame, with the caption below?
		// @phan-suppress-next-line PhanImpossibleCondition
		$hasVisibleCaption = isset( $params['frame']['framed'] )
			|| isset( $params['frame']['thumbnail'] )
			|| isset( $params['frame']['manualthumb'] );

		# In the old days, [[Image:Foo|text...]] would set alt text.  Later it
		# came to also set the caption, ordinary text after the image -- which
		# makes no sense, because that just repeats the text multiple times in
		# screen readers.  It *also* came to set the title attribute.
		# Now that we have an alt attribute, we should not set the alt text to
		# equal the caption: that's worse than useless, it just repeats the
		# text.  This is the framed/thumbnail case.  If there's no caption, we
		# use the unnamed parameter for alt text as well, just for the time be-
		# ing, if the unnamed param is set and the alt param is not.
		# For the future, we need to figure out if we want to tweak this more,
		# e.g., introducing a title= parameter for the title; ignoring the un-
		# named parameter entirely for images without a caption; adding an ex-
		# plicit caption= parameter and preserving the old magic unnamed para-
		# meter for BC; ...

		if ( !$hasVisibleCaption ) {
			// @phan-suppress-next-line PhanImpossibleCondition
			if ( !isset( $params['frame']['alt'] ) && $caption !== '' ) {
				# No alt text, use the "caption" for the alt text
				$params['frame']['alt'] = $this->stripAltText( $caption, $holders );
			}
			# Use the "caption" for the tooltip text
			$params['frame']['title'] = $this->stripAltText( $caption, $holders );
		}
		$params['handler']['targetlang'] = $this->getTargetLanguage()->getCode();

		// hook signature compat again, $link may have changed
		$title = Title::castFromLinkTarget( $link );
		$this->hookRunner->onParserMakeImageParams( $title, $file, $params, $this );

		# Linker does the rest
		$time = $options['time'] ?? false;
		$ret = Linker::makeImageLink( $this, $link, $file, $params['frame'], $params['handler'],
			$time, $descQuery, $this->mOptions->getThumbSize() );

		# Give the handler a chance to modify the parser object
		if ( $handler ) {
			$handler->parserTransformHook( $this, $file );
		}
		if ( $file ) {
			$this->modifyImageHtml( $file, $params, $ret );
		}

		return $ret;
	}

	/**
	 * Parse the value of 'link' parameter in image syntax (`[[File:Foo.jpg|link=<value>]]`).
	 *
	 * Adds an entry to appropriate link tables.
	 *
	 * @since 1.32
	 * @param string $value
	 * @return array of `[ type, target ]`, where:
	 *   - `type` is one of:
	 *     - `null`: Given value is not a valid link target, use default
	 *     - `'no-link'`: Given value is empty, do not generate a link
	 *     - `'link-url'`: Given value is a valid external link
	 *     - `'link-title'`: Given value is a valid internal link
	 *   - `target` is:
	 *     - When `type` is `null` or `'no-link'`: `false`
	 *     - When `type` is `'link-url'`: URL string corresponding to given value
	 *     - When `type` is `'link-title'`: Title object corresponding to given value
	 */
	private function parseLinkParameter( $value ) {
		$chars = self::EXT_LINK_URL_CLASS;
		$addr = self::EXT_LINK_ADDR;
		$prots = $this->urlUtils->validProtocols();
		$type = null;
		$target = false;
		if ( $value === '' ) {
			$type = 'no-link';
		} elseif ( preg_match( "/^((?i)$prots)/", $value ) ) {
			if ( preg_match( "/^((?i)$prots)$addr$chars*$/u", $value ) ) {
				$this->mOutput->addExternalLink( $value );
				$type = 'link-url';
				$target = $value;
			}
		} else {
			// Percent-decode link arguments for consistency with wikilink
			// handling (T216003#7836261).
			//
			// There's slight concern here though.  The |link= option supports
			// two formats, link=Test%22test vs link=[[Test%22test]], both of
			// which are about to be decoded.
			//
			// In the former case, the decoding here is straightforward and
			// desirable.
			//
			// In the latter case, there's a potential for double decoding,
			// because the wikilink syntax has a higher precedence and has
			// already been parsed as a link before we get here.  $value
			// has had stripAltText() called on it, which in turn calls
			// replaceLinkHoldersText() on the link.  So, the text we're
			// getting at this point has already been percent decoded.
			//
			// The problematic case is if %25 is in the title, since that
			// decodes to %, which could combine with trailing characters.
			// However, % is not a valid link title character, so it would
			// not parse as a link and the string we received here would
			// still contain the encoded %25.
			//
			// Hence, double decoded is not an issue.  See the test,
			// "Should not double decode the link option"
			if ( strpos( $value, '%' ) !== false ) {
				$value = rawurldecode( $value );
			}
			$linkTitle = Title::newFromText( $value );
			if ( $linkTitle ) {
				$this->mOutput->addLink( $linkTitle );
				$type = 'link-title';
				$target = $linkTitle;
			}
		}
		return [ $type, $target ];
	}

	/**
	 * Give hooks a chance to modify image thumbnail HTML
	 *
	 * @param File $file
	 * @param array $params
	 * @param string &$html
	 */
	public function modifyImageHtml( File $file, array $params, string &$html ) {
		$this->hookRunner->onParserModifyImageHTML( $this, $file, $params, $html );
	}

	/**
	 * @param string $caption
	 * @param LinkHolderArray|false $holders
	 * @return string
	 */
	private function stripAltText( $caption, $holders ) {
		# Strip bad stuff out of the title (tooltip).  We can't just use
		# replaceLinkHoldersText() here, because if this function is called
		# from handleInternalLinks2(), mLinkHolders won't be up-to-date.
		if ( $holders ) {
			$tooltip = $holders->replaceText( $caption );
		} else {
			$tooltip = $this->replaceLinkHoldersText( $caption );
		}

		# make sure there are no placeholders in thumbnail attributes
		# that are later expanded to html- so expand them now and
		# remove the tags
		$tooltip = $this->mStripState->unstripBoth( $tooltip );
		# Compatibility hack!  In HTML certain entity references not terminated
		# by a semicolon are decoded (but not if we're in an attribute; that's
		# how link URLs get away without properly escaping & in queries).
		# But wikitext has always required semicolon-termination of entities,
		# so encode & where needed to avoid decode of semicolon-less entities.
		# See T209236 and
		# https://www.w3.org/TR/html5/syntax.html#named-character-references
		# T210437 discusses moving this workaround to Sanitizer::stripAllTags.
		$tooltip = preg_replace( "/
			&			# 1. entity prefix
			(?=			# 2. followed by:
			(?:			#  a. one of the legacy semicolon-less named entities
				A(?:Elig|MP|acute|circ|grave|ring|tilde|uml)|
				C(?:OPY|cedil)|E(?:TH|acute|circ|grave|uml)|
				GT|I(?:acute|circ|grave|uml)|LT|Ntilde|
				O(?:acute|circ|grave|slash|tilde|uml)|QUOT|REG|THORN|
				U(?:acute|circ|grave|uml)|Yacute|
				a(?:acute|c(?:irc|ute)|elig|grave|mp|ring|tilde|uml)|brvbar|
				c(?:cedil|edil|urren)|cent(?!erdot;)|copy(?!sr;)|deg|
				divide(?!ontimes;)|e(?:acute|circ|grave|th|uml)|
				frac(?:1(?:2|4)|34)|
				gt(?!c(?:c|ir)|dot|lPar|quest|r(?:a(?:pprox|rr)|dot|eq(?:less|qless)|less|sim);)|
				i(?:acute|circ|excl|grave|quest|uml)|laquo|
				lt(?!c(?:c|ir)|dot|hree|imes|larr|quest|r(?:Par|i(?:e|f|));)|
				m(?:acr|i(?:cro|ddot))|n(?:bsp|tilde)|
				not(?!in(?:E|dot|v(?:a|b|c)|)|ni(?:v(?:a|b|c)|);)|
				o(?:acute|circ|grave|rd(?:f|m)|slash|tilde|uml)|
				p(?:lusmn|ound)|para(?!llel;)|quot|r(?:aquo|eg)|
				s(?:ect|hy|up(?:1|2|3)|zlig)|thorn|times(?!b(?:ar|)|d;)|
				u(?:acute|circ|grave|ml|uml)|y(?:acute|en|uml)
			)
			(?:[^;]|$))	#  b. and not followed by a semicolon
			# S = study, for efficiency
			/Sx", '&amp;', $tooltip );
		$tooltip = Sanitizer::stripAllTags( $tooltip );

		return $tooltip;
	}

	/**
	 * Callback from the Sanitizer for expanding items found in HTML attribute
	 * values, so they can be safely tested and escaped.
	 *
	 * @param string &$text
	 * @param PPFrame|false $frame
	 * @return string
	 * @deprecated since 1.35, internal callback should not have been public
	 */
	public function attributeStripCallback( &$text, $frame = false ) {
		wfDeprecated( __METHOD__, '1.35' );
		$text = $this->replaceVariables( $text, $frame );
		$text = $this->mStripState->unstripBoth( $text );
		return $text;
	}

	/**
	 * Accessor
	 *
	 * @return array
	 * @since 1.6
	 */
	public function getTags(): array {
		return array_keys( $this->mTagHooks );
	}

	/**
	 * @since 1.32
	 * @return array{0:array<string,string>,1:array<string,string>}
	 */
	public function getFunctionSynonyms() {
		return $this->mFunctionSynonyms;
	}

	/**
	 * @since 1.32
	 * @return string
	 */
	public function getUrlProtocols() {
		return $this->urlUtils->validProtocols();
	}

	/**
	 * Break wikitext input into sections, and either pull or replace
	 * some particular section's text.
	 *
	 * External callers should use the getSection and replaceSection methods.
	 *
	 * @param string $text Page wikitext
	 * @param string|int $sectionId A section identifier string of the form:
	 *   "<flag1> - <flag2> - ... - <section number>"
	 *
	 * Currently the only recognised flag is "T", which means the target section number
	 * was derived during a template inclusion parse, in other words this is a template
	 * section edit link. If no flags are given, it was an ordinary section edit link.
	 * This flag is required to avoid a section numbering mismatch when a section is
	 * enclosed by "<includeonly>" (T8563).
	 *
	 * The section number 0 pulls the text before the first heading; other numbers will
	 * pull the given section along with its lower-level subsections. If the section is
	 * not found, $mode=get will return $newtext, and $mode=replace will return $text.
	 *
	 * Section 0 is always considered to exist, even if it only contains the empty
	 * string. If $text is the empty string and section 0 is replaced, $newText is
	 * returned.
	 *
	 * @param string $mode One of "get" or "replace"
	 * @param string|false $newText Replacement text for section data.
	 * @param PageReference|null $page
	 * @return string For "get", the extracted section text.
	 *   for "replace", the whole page with the section replaced.
	 */
	private function extractSections( $text, $sectionId, $mode, $newText, ?PageReference $page = null ) {
		$magicScopeVariable = $this->lock();
		$this->startParse(
			$page,
			ParserOptions::newFromUser( RequestContext::getMain()->getUser() ),
			self::OT_PLAIN,
			true
		);
		$outText = '';
		$frame = $this->getPreprocessor()->newFrame();

		# Process section extraction flags
		$flags = 0;
		$sectionParts = explode( '-', $sectionId );
		// The section ID may either be a magic string such as 'new' (which should be treated as 0),
		// or a numbered section ID in the format of "T-<section index>".
		// Explicitly coerce the section index into a number accordingly. (T323373)
		$sectionIndex = (int)array_pop( $sectionParts );
		foreach ( $sectionParts as $part ) {
			if ( $part === 'T' ) {
				$flags |= Preprocessor::DOM_FOR_INCLUSION;
			}
		}

		# Check for empty input
		if ( strval( $text ) === '' ) {
			# Only sections 0 and T-0 exist in an empty document
			if ( $sectionIndex === 0 ) {
				return $mode === 'get' ? '' : $newText;
			} else {
				return $mode === 'get' ? $newText : $text;
			}
		}

		# Preprocess the text
		$root = $this->preprocessToDom( $text, $flags );

		# <h> nodes indicate section breaks
		# They can only occur at the top level, so we can find them by iterating the root's children
		$node = $root->getFirstChild();

		# Find the target section
		if ( $sectionIndex === 0 ) {
			# Section zero doesn't nest, level=big
			$targetLevel = 1000;
		} else {
			while ( $node ) {
				if ( $node->getName() === 'h' ) {
					$bits = $node->splitHeading();
					if ( $bits['i'] == $sectionIndex ) {
						$targetLevel = $bits['level'];
						break;
					}
				}
				if ( $mode === 'replace' ) {
					$outText .= $frame->expand( $node, PPFrame::RECOVER_ORIG );
				}
				$node = $node->getNextSibling();
			}
		}

		if ( !$node ) {
			# Not found
			return $mode === 'get' ? $newText : $text;
		}

		# Find the end of the section, including nested sections
		do {
			if ( $node->getName() === 'h' ) {
				$bits = $node->splitHeading();
				$curLevel = $bits['level'];
				// @phan-suppress-next-line PhanPossiblyUndeclaredVariable False positive
				if ( $bits['i'] != $sectionIndex && $curLevel <= $targetLevel ) {
					break;
				}
			}
			if ( $mode === 'get' ) {
				$outText .= $frame->expand( $node, PPFrame::RECOVER_ORIG );
			}
			$node = $node->getNextSibling();
		} while ( $node );

		# Write out the remainder (in replace mode only)
		if ( $mode === 'replace' ) {
			# Output the replacement text
			# Add two newlines on -- trailing whitespace in $newText is conventionally
			# stripped by the editor, so we need both newlines to restore the paragraph gap
			# Only add trailing whitespace if there is newText
			if ( $newText != "" ) {
				$outText .= $newText . "\n\n";
			}

			while ( $node ) {
				$outText .= $frame->expand( $node, PPFrame::RECOVER_ORIG );
				$node = $node->getNextSibling();
			}
		}

		# Re-insert stripped tags
		$outText = rtrim( $this->mStripState->unstripBoth( $outText ) );

		return $outText;
	}

	/**
	 * This function returns the text of a section, specified by a number ($section).
	 * A section is text under a heading like == Heading == or \<h1\>Heading\</h1\>, or
	 * the first section before any such heading (section 0).
	 *
	 * If a section contains subsections, these are also returned.
	 *
	 * @param string $text Text to look in
	 * @param string|int $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1').
	 * @param string|false $defaultText Default to return if section is not found
	 *
	 * @return string Text of the requested section
	 * @since 1.7
	 */
	public function getSection( $text, $sectionId, $defaultText = '' ) {
		return $this->extractSections( $text, $sectionId, 'get', $defaultText );
	}

	/**
	 * This function returns $oldtext after the content of the section
	 * specified by $section has been replaced with $text. If the target
	 * section does not exist, $oldtext is returned unchanged.
	 *
	 * @param string $oldText Former text of the article
	 * @param string|int $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1').
	 * @param string|false $newText Replacing text
	 *
	 * @return string Modified text
	 * @since 1.7
	 */
	public function replaceSection( $oldText, $sectionId, $newText ) {
		return $this->extractSections( $oldText, $sectionId, 'replace', $newText );
	}

	/**
	 * Get an array of preprocessor section information.
	 *
	 * Preprocessor sections are those identified by wikitext-style syntax, not
	 * HTML-style syntax. Templates are not expanded, so these sections do not
	 * include sections created by templates or parser functions. This is the
	 * same definition of a section as used by section editing, but not the
	 * same as TOC generation.
	 *
	 * These sections are typically smaller than those acted on by getSection() and
	 * replaceSection() since they are not nested. Section nesting could be
	 * reconstructed from the heading levels.
	 *
	 * The return value is an array of associative array info structures. Each
	 * associative array contains the following keys, describing a section:
	 *
	 *  - index: An integer identifying the section.
	 *  - level: The heading level, e.g. 1 for <h1>. For the section before the
	 *    the first heading, this will be 0.
	 *  - offset: The byte offset within the wikitext at which the section starts
	 *  - heading: The wikitext for the header which introduces the section,
	 *    including equals signs. For the section before the first heading, this
	 *    will be an empty string.
	 *  - text: The complete text of the section.
	 *
	 * @param string $text
	 * @return array[]
	 * @internal
	 */
	public function getFlatSectionInfo( $text ) {
		$magicScopeVariable = $this->lock();
		$this->startParse(
			null,
			ParserOptions::newFromUser( RequestContext::getMain()->getUser() ),
			self::OT_PLAIN,
			true
		);
		$frame = $this->getPreprocessor()->newFrame();
		$root = $this->preprocessToDom( $text, 0 );
		$node = $root->getFirstChild();
		$offset = 0;
		$currentSection = [
			'index' => 0,
			'level' => 0,
			'offset' => 0,
			'heading' => '',
			'text' => ''
		];
		$sections = [];

		while ( $node ) {
			$nodeText = $frame->expand( $node, PPFrame::RECOVER_ORIG );
			if ( $node->getName() === 'h' ) {
				$bits = $node->splitHeading();
				$sections[] = $currentSection;
				$currentSection = [
					'index' => $bits['i'],
					'level' => $bits['level'],
					'offset' => $offset,
					'heading' => $nodeText,
					'text' => $nodeText
				];
			} else {
				$currentSection['text'] .= $nodeText;
			}
			$offset += strlen( $nodeText );
			$node = $node->getNextSibling();
		}
		$sections[] = $currentSection;
		return $sections;
	}

	/**
	 * Get the ID of the revision we are parsing
	 *
	 * The return value will be either:
	 *   - a) Positive, indicating a specific revision ID (current or old)
	 *   - b) Zero, meaning the revision ID is specified by getCurrentRevisionRecordCallback()
	 *   - c) Null, meaning the parse is for preview mode and there is no revision
	 *
	 * @return int|null
	 * @since 1.13
	 */
	public function getRevisionId() {
		return $this->mRevisionId;
	}

	/**
	 * Get the revision record object for $this->mRevisionId
	 *
	 * @return RevisionRecord|null Either a RevisionRecord object or null
	 * @since 1.35
	 */
	public function getRevisionRecordObject() {
		if ( $this->mRevisionRecordObject ) {
			return $this->mRevisionRecordObject;
		}
		if ( $this->mOptions->isMessage() ) {
			return null;
		}

		// NOTE: try to get the RevisionRecord object even if mRevisionId is null.
		// This is useful when parsing a revision that has not yet been saved.
		// However, if we get back a saved revision even though we are in
		// preview mode, we'll have to ignore it, see below.
		// NOTE: This callback may be used to inject an OLD revision that was
		// already loaded, so "current" is a bit of a misnomer. We can't just
		// skip it if mRevisionId is set.
		$rev = $this->mOptions->getCurrentRevisionRecordCallback()(
			$this->getTitle(),
			$this
		);

		if ( !$rev ) {
			// The revision record callback returns `false` (not null) to
			// indicate that the revision is missing.  (See for example
			// Parser::statelessFetchRevisionRecord(), the default callback.)
			// This API expects `null` instead. (T251952)
			return null;
		}

		if ( $this->mRevisionId === null && $rev->getId() ) {
			// We are in preview mode (mRevisionId is null), and the current revision callback
			// returned an existing revision. Ignore it and return null, it's probably the page's
			// current revision, which is not what we want here. Note that we do want to call the
			// callback to allow the unsaved revision to be injected here, e.g. for
			// self-transclusion previews.
			return null;
		}

		// If the parse is for a new revision, then the callback should have
		// already been set to force the object and should match mRevisionId.
		// If not, try to fetch by mRevisionId instead.
		if ( $this->mRevisionId && $rev->getId() != $this->mRevisionId ) {
			$rev = MediaWikiServices::getInstance()
				->getRevisionLookup()
				->getRevisionById( $this->mRevisionId );
		}

		$this->mRevisionRecordObject = $rev;

		return $this->mRevisionRecordObject;
	}

	/**
	 * Get the timestamp associated with the current revision, adjusted for
	 * the default server-local timestamp
	 * @return string TS_MW timestamp
	 * @since 1.9
	 */
	public function getRevisionTimestamp() {
		if ( $this->mRevisionTimestamp !== null ) {
			return $this->mRevisionTimestamp;
		}

		# Use specified revision timestamp, falling back to the current timestamp
		$revObject = $this->getRevisionRecordObject();
		$timestamp = $revObject && $revObject->getTimestamp()
			? $revObject->getTimestamp()
			: $this->mOptions->getTimestamp();
		$this->mOutput->setRevisionTimestampUsed( $timestamp ); // unadjusted time zone

		# The cryptic '' timezone parameter tells to use the site-default
		# timezone offset instead of the user settings.
		# Since this value will be saved into the parser cache, served
		# to other users, and potentially even used inside links and such,
		# it needs to be consistent for all visitors.
		$this->mRevisionTimestamp = $this->contLang->userAdjust( $timestamp, '' );

		return $this->mRevisionTimestamp;
	}

	/**
	 * Get the name of the user that edited the last revision
	 *
	 * @return string|null User name
	 * @since 1.15
	 */
	public function getRevisionUser(): ?string {
		if ( $this->mRevisionUser === null ) {
			$revObject = $this->getRevisionRecordObject();

			# if this template is subst: the revision id will be blank,
			# so just use the current user's name
			if ( $revObject && $revObject->getUser() ) {
				$this->mRevisionUser = $revObject->getUser()->getName();
			} elseif ( $this->ot['wiki'] || $this->mOptions->getIsPreview() ) {
				$this->mRevisionUser = $this->getUserIdentity()->getName();
			} else {
				# Note that we fall through here with
				# $this->mRevisionUser still null
			}
		}
		return $this->mRevisionUser;
	}

	/**
	 * Get the size of the revision
	 *
	 * @return int|null Revision size
	 * @since 1.22
	 */
	public function getRevisionSize() {
		if ( $this->mRevisionSize === null ) {
			$revObject = $this->getRevisionRecordObject();

			# if this variable is subst: the revision id will be blank,
			# so just use the parser input size, because the own substitution
			# will change the size.
			$this->mRevisionSize = $revObject ? $revObject->getSize() : $this->mInputSize;
		}
		return $this->mRevisionSize;
	}

	/**
	 * Accessor for the 'defaultsort' page property.
	 * Will use the empty string if none is set.
	 *
	 * This value is treated as a prefix, so the
	 * empty string is equivalent to sorting by
	 * page name.
	 *
	 * @return string
	 * @since 1.9
	 * @deprecated since 1.38, use
	 * $parser->getOutput()->getPageProperty('defaultsort') ?? ''
	 */
	public function getDefaultSort() {
		wfDeprecated( __METHOD__, '1.38' );
		return $this->mOutput->getPageProperty( 'defaultsort' ) ?? '';
	}

	private static function getSectionNameFromStrippedText( string $text ): string {
		$text = Sanitizer::normalizeSectionNameWhitespace( $text );
		$text = Sanitizer::decodeCharReferences( $text );
		$text = self::normalizeSectionName( $text );
		return $text;
	}

	private static function makeAnchor( string $sectionName ): string {
		return '#' . Sanitizer::escapeIdForLink( $sectionName );
	}

	private function makeLegacyAnchor( string $sectionName ): string {
		$fragmentMode = $this->svcOptions->get( MainConfigNames::FragmentMode );
		if ( isset( $fragmentMode[1] ) && $fragmentMode[1] === 'legacy' ) {
			// ForAttribute() and ForLink() are the same for legacy encoding
			$id = Sanitizer::escapeIdForAttribute( $sectionName, Sanitizer::ID_FALLBACK );
		} else {
			$id = Sanitizer::escapeIdForLink( $sectionName );
		}

		return "#$id";
	}

	/**
	 * Try to guess the section anchor name based on a wikitext fragment
	 * presumably extracted from a heading, for example "Header" from
	 * "== Header ==".
	 *
	 * @param string $text
	 * @return string Anchor (starting with '#')
	 * @since 1.12
	 */
	public function guessSectionNameFromWikiText( $text ) {
		# Strip out wikitext links(they break the anchor)
		$text = $this->stripSectionName( $text );
		$sectionName = self::getSectionNameFromStrippedText( $text );
		return self::makeAnchor( $sectionName );
	}

	/**
	 * Same as guessSectionNameFromWikiText(), but produces legacy anchors
	 * instead, if possible. For use in redirects, since various versions
	 * of Microsoft browsers interpret Location: headers as something other
	 * than UTF-8, resulting in breakage.
	 *
	 * @param string $text The section name
	 * @return string Anchor (starting with '#')
	 * @since 1.17
	 */
	public function guessLegacySectionNameFromWikiText( $text ) {
		# Strip out wikitext links(they break the anchor)
		$text = $this->stripSectionName( $text );
		$sectionName = self::getSectionNameFromStrippedText( $text );
		return $this->makeLegacyAnchor( $sectionName );
	}

	/**
	 * Like guessSectionNameFromWikiText(), but takes already-stripped text as input.
	 * @param string $text Section name (plain text)
	 * @return string Anchor (starting with '#')
	 * @since 1.31
	 */
	public static function guessSectionNameFromStrippedText( $text ) {
		$sectionName = self::getSectionNameFromStrippedText( $text );
		return self::makeAnchor( $sectionName );
	}

	/**
	 * Apply the same normalization as code making links to this section would
	 *
	 * @param string $text
	 * @return string
	 */
	private static function normalizeSectionName( $text ) {
		# T90902: ensure the same normalization is applied for IDs as to links
		$titleParser = MediaWikiServices::getInstance()->getTitleParser();
		try {
			$parts = $titleParser->splitTitleString( "#$text" );
		} catch ( MalformedTitleException ) {
			return $text;
		}
		return $parts['fragment'];
	}

	/**
	 * Strips a text string of wikitext for use in a section anchor
	 *
	 * Accepts a text string and then removes all wikitext from the
	 * string and leaves only the resultant text (i.e. the result of
	 * [[User:WikiSysop|Sysop]] would be "Sysop" and the result of
	 * [[User:WikiSysop]] would be "User:WikiSysop") - this is intended
	 * to create valid section anchors by mimicking the output of the
	 * parser when headings are parsed.
	 *
	 * @param string $text Text string to be stripped of wikitext
	 * for use in a Section anchor
	 * @return string Filtered text string
	 * @since 1.12
	 */
	public function stripSectionName( $text ) {
		# Strip internal link markup
		$text = preg_replace( '/\[\[:?([^[|]+)\|([^[]+)\]\]/', '$2', $text );
		$text = preg_replace( '/\[\[:?([^[]+)\|?\]\]/', '$1', $text );

		# Strip external link markup
		# @todo FIXME: Not tolerant to blank link text
		# I.E. [https://www.mediawiki.org] will render as [1] or something depending
		# on how many empty links there are on the page - need to figure that out.
		$text = preg_replace(
			'/\[(?i:' . $this->urlUtils->validProtocols() . ')([^ ]+?) ([^[]+)\]/', '$2', $text );

		# Parse wikitext quotes (italics & bold)
		$text = $this->doQuotes( $text );

		# Strip HTML tags
		$text = StringUtils::delimiterReplace( '<', '>', '', $text );
		return $text;
	}

	/**
	 * Call a callback function on all regions of the given text that are not
	 * inside strip markers, and replace those regions with the return value
	 * of the callback. For example, with input:
	 *
	 *  aaa<MARKER>bbb
	 *
	 * This will call the callback function twice, with 'aaa' and 'bbb'. Those
	 * two strings will be replaced with the value returned by the callback in
	 * each case.
	 *
	 * @param string $s
	 * @param callable $callback
	 *
	 * @return string
	 * @internal
	 * @since 1.12
	 */
	public function markerSkipCallback( $s, callable $callback ) {
		$i = 0;
		$out = '';
		while ( $i < strlen( $s ) ) {
			$markerStart = strpos( $s, self::MARKER_PREFIX, $i );
			if ( $markerStart === false ) {
				$out .= $callback( substr( $s, $i ) );
				break;
			} else {
				$out .= $callback( substr( $s, $i, $markerStart - $i ) );
				$markerEnd = strpos( $s, self::MARKER_SUFFIX, $markerStart );
				if ( $markerEnd === false ) {
					$out .= substr( $s, $markerStart );
					break;
				} else {
					$markerEnd += strlen( self::MARKER_SUFFIX );
					$out .= substr( $s, $markerStart, $markerEnd - $markerStart );
					$i = $markerEnd;
				}
			}
		}
		return $out;
	}

	/**
	 * Remove any strip markers found in the given text.
	 *
	 * @param string $text
	 * @return string
	 * @since 1.19
	 */
	public function killMarkers( $text ) {
		return $this->mStripState->killMarkers( $text );
	}

	/**
	 * Parsed a width param of imagelink like 300px or 200x300px
	 *
	 * @param string $value
	 * @param bool $parseHeight
	 * @param bool $localized Defaults to false; set to true if the $value
	 *   has already been matched against `img_width` to localize the `px`
	 *   suffix.
	 *
	 * @return array
	 * @since 1.20
	 * @internal
	 */
	public function parseWidthParam( $value, $parseHeight = true, bool $localized = false ) {
		$parsedWidthParam = [];
		if ( $value === '' ) {
			return $parsedWidthParam;
		}
		$m = [];
		if ( !$localized ) {
			// Strip a localized 'px' suffix (T374311)
			$mwArray = $this->magicWordFactory->newArray( [ 'img_width' ] );
			[ $magicWord, $newValue ] = $mwArray->matchVariableStartToEnd( $value );
			$value = $magicWord ? $newValue : $value;
		}

		# (T15500) In both cases (width/height and width only),
		# permit trailing "px" for backward compatibility.
		if ( $parseHeight && preg_match( '/^([0-9]*)x([0-9]*)\s*(px)?\s*$/', $value, $m ) ) {
			$parsedWidthParam['width'] = intval( $m[1] );
			$parsedWidthParam['height'] = intval( $m[2] );
			if ( $m[3] ?? false ) {
				$this->addTrackingCategory( 'double-px-category' );
			}
		} elseif ( preg_match( '/^([0-9]*)\s*(px)?\s*$/', $value, $m ) ) {
			$parsedWidthParam['width'] = intval( $m[1] );
			if ( $m[2] ?? false ) {
				$this->addTrackingCategory( 'double-px-category' );
			}
		}
		return $parsedWidthParam;
	}

	/**
	 * Lock the current instance of the parser.
	 *
	 * This is meant to stop someone from calling the parser
	 * recursively and messing up all the strip state.
	 *
	 * @return ScopedCallback The lock will be released once the return value goes out of scope.
	 */
	protected function lock() {
		if ( $this->mInParse ) {
			throw new LogicException( "Parser state cleared while parsing. "
				. "Did you call Parser::parse recursively? Lock is held by: " . $this->mInParse );
		}

		// Save the backtrace when locking, so that if some code tries locking again,
		// we can print the lock owner's backtrace for easier debugging
		$e = new RuntimeException;
		$this->mInParse = $e->getTraceAsString();

		$recursiveCheck = new ScopedCallback( function () {
			$this->mInParse = false;
		} );

		return $recursiveCheck;
	}

	/**
	 * Will entry points such as parse() throw an exception due to the parser
	 * already being active?
	 *
	 * @since 1.39
	 * @return bool
	 */
	public function isLocked() {
		return (bool)$this->mInParse;
	}

	/**
	 * Strip outer <p></p> tag from the HTML source of a single paragraph.
	 *
	 * Returns original HTML if the <p/> tag has any attributes, if there's no wrapping <p/> tag,
	 * or if there is more than one <p/> tag in the input HTML.
	 *
	 * @param string $html
	 * @return string
	 * @since 1.24
	 */
	public static function stripOuterParagraph( $html ) {
		$m = [];
		if ( preg_match( '/^<p>(.*)\n?<\/p>\n?$/sU', $html, $m ) && strpos( $m[1], '</p>' ) === false ) {
			$html = $m[1];
		}

		return $html;
	}

	/**
	 * Add HTML tags marking the parts of a page title, to be displayed in the first heading of the page.
	 *
	 * @internal
	 * @since 1.39
	 * @param string|HtmlArmor $nsText
	 * @param string|HtmlArmor $nsSeparator
	 * @param string|HtmlArmor $mainText
	 * @return string HTML
	 */
	public static function formatPageTitle( $nsText, $nsSeparator, $mainText ): string {
		$html = '';
		if ( $nsText !== '' ) {
			$html .= '<span class="mw-page-title-namespace">' . HtmlArmor::getHtml( $nsText ) . '</span>';
			$html .= '<span class="mw-page-title-separator">' . HtmlArmor::getHtml( $nsSeparator ) . '</span>';
		}
		$html .= '<span class="mw-page-title-main">' . HtmlArmor::getHtml( $mainText ) . '</span>';
		return $html;
	}

	/**
	 * Strip everything but the <body> from the provided string
	 * @param string $text
	 * @return string
	 * @unstable
	 */
	public static function extractBody( string $text ): string {
		$posStart = strpos( $text, '<body' );
		if ( $posStart === false ) {
			return $text;
		}
		$posStart = strpos( $text, '>', $posStart );
		if ( $posStart === false ) {
			return $text;
		}
		// Skip past the > character
		$posStart += 1;
		$posEnd = strrpos( $text, '</body>', $posStart );
		if ( $posEnd === false ) {
			// Strip <body> wrapper even if input was truncated (i.e. missing close tag)
			return substr( $text, $posStart );
		} else {
			return substr( $text, $posStart, $posEnd - $posStart );
		}
	}

	/**
	 * Set's up the PHP implementation of OOUI for use in this request
	 * and instructs OutputPage to enable OOUI for itself.
	 *
	 * @since 1.26
	 * @deprecated since 1.35, use $parser->getOutput()->setEnableOOUI() instead.
	 */
	public function enableOOUI() {
		wfDeprecated( __METHOD__, '1.35' );
		OutputPage::setupOOUI();
		$this->mOutput->setEnableOOUI( true );
	}

	/**
	 * Sets the flag on the parser output but also does some debug logging.
	 * Note that there is a copy of this method in CoreMagicVariables as well.
	 * @param ParserOutputFlags|string $flag
	 * @param string $reason
	 */
	private function setOutputFlag( ParserOutputFlags|string $flag, string $reason ): void {
		$this->mOutput->setOutputFlag( $flag );
		if ( $flag instanceof ParserOutputFlags ) {
			// Convert enumeration to string for logging.
			$flag = $flag->value;
		}
		$name = $this->getTitle()->getPrefixedText();
		$this->logger->debug( __METHOD__ . ": set $flag flag on '$name'; $reason" );
	}
}

/** @deprecated class alias since 1.42 */
class_alias( Parser::class, 'Parser' );

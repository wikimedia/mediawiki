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
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Special\SpecialPageFactory;
use Wikimedia\ScopedCallback;

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
class Parser {
	/**
	 * Update this version number when the ParserOutput format
	 * changes in an incompatible way, so the parser cache
	 * can automatically discard old data.
	 */
	const VERSION = '1.6.4';

	/**
	 * Update this version number when the output of serialiseHalfParsedText()
	 * changes in an incompatible way
	 */
	const HALF_PARSED_VERSION = 2;

	# Flags for Parser::setFunctionHook
	const SFH_NO_HASH = 1;
	const SFH_OBJECT_ARGS = 2;

	# Constants needed for external link processing
	# Everything except bracket, space, or control characters
	# \p{Zs} is unicode 'separator, space' category. It covers the space 0x20
	# as well as U+3000 is IDEOGRAPHIC SPACE for T21052
	# \x{FFFD} is the Unicode replacement character, which Preprocessor_DOM
	# uses to replace invalid HTML characters.
	const EXT_LINK_URL_CLASS = '[^][<>"\\x00-\\x20\\x7F\p{Zs}\x{FFFD}]';
	# Simplified expression to match an IPv4 or IPv6 address, or
	# at least one character of a host name (embeds EXT_LINK_URL_CLASS)
	const EXT_LINK_ADDR = '(?:[0-9.]+|\\[(?i:[0-9a-f:.]+)\\]|[^][<>"\\x00-\\x20\\x7F\p{Zs}\x{FFFD}])';
	# RegExp to make image URLs (embeds IPv6 part of EXT_LINK_ADDR)
	// phpcs:ignore Generic.Files.LineLength
	const EXT_IMAGE_REGEX = '/^(http:\/\/|https:\/\/)((?:\\[(?i:[0-9a-f:.]+)\\])?[^][<>"\\x00-\\x20\\x7F\p{Zs}\x{FFFD}]+)
		\\/([A-Za-z0-9_.,~%\\-+&;#*?!=()@\\x80-\\xFF]+)\\.((?i)gif|png|jpg|jpeg)$/Sxu';

	# Regular expression for a non-newline space
	const SPACE_NOT_NL = '(?:\t|&nbsp;|&\#0*160;|&\#[Xx]0*[Aa]0;|\p{Zs})';

	# Flags for preprocessToDom
	const PTD_FOR_INCLUSION = 1;

	# Allowed values for $this->mOutputType
	# Parameter to startExternalParse().
	const OT_HTML = 1; # like parse()
	const OT_WIKI = 2; # like preSaveTransform()
	const OT_PREPROCESS = 3; # like preprocess()
	const OT_MSG = 3;
	const OT_PLAIN = 4; # like extractSections() - portions of the original are returned unchanged.

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
	const MARKER_SUFFIX = "-QINU`\"'\x7f";
	const MARKER_PREFIX = "\x7f'\"`UNIQ-";

	# Markers used for wrapping the table of contents
	const TOC_START = '<mw:toc>';
	const TOC_END = '</mw:toc>';

	/** @var int Assume that no output will later be saved this many seconds after parsing */
	const MAX_TTS = 900;

	# Persistent:
	public $mTagHooks = [];
	public $mTransparentTagHooks = [];
	public $mFunctionHooks = [];
	public $mFunctionSynonyms = [ 0 => [], 1 => [] ];
	public $mFunctionTagHooks = [];
	public $mStripList = [];
	public $mDefaultStripList = [];
	public $mVarCache = [];
	public $mImageParams = [];
	public $mImageParamsMagicArray = [];
	public $mMarkerIndex = 0;
	/**
	 * @var bool Whether firstCallInit still needs to be called
	 */
	public $mFirstCall = true;

	# Initialised by initialiseVariables()

	/**
	 * @var MagicWordArray
	 */
	public $mVariables;

	/**
	 * @var MagicWordArray
	 */
	public $mSubstWords;
	# Initialised in constructor
	public $mConf, $mExtLinkBracketedRegex, $mUrlProtocols;

	# Initialized in getPreprocessor()
	/** @var Preprocessor */
	public $mPreprocessor;

	# Cleared with clearState():
	/**
	 * @var ParserOutput
	 */
	public $mOutput;
	public $mAutonumber;

	/**
	 * @var StripState
	 */
	public $mStripState;

	public $mIncludeCount;
	/**
	 * @var LinkHolderArray
	 */
	public $mLinkHolders;

	public $mLinkID;
	public $mIncludeSizes, $mPPNodeCount, $mGeneratedPPNodeCount, $mHighestExpansionDepth;
	public $mDefaultSort;
	public $mTplRedirCache, $mTplDomCache, $mHeadings, $mDoubleUnderscores;
	public $mExpensiveFunctionCount; # number of expensive parser function calls
	public $mShowToc, $mForceTocPosition;

	/**
	 * @var User
	 */
	public $mUser; # User object; only used when doing pre-save transform

	# Temporary
	# These are variables reset at least once per parse regardless of $clearState

	/**
	 * @var ParserOptions
	 */
	public $mOptions;

	/**
	 * @var Title
	 */
	public $mTitle;        # Title context, used for self-link rendering and similar things
	public $mOutputType;   # Output type, one of the OT_xxx constants
	public $ot;            # Shortcut alias, see setOutputType()
	public $mRevisionObject; # The revision object of the specified revision ID
	public $mRevisionId;   # ID to display in {{REVISIONID}} tags
	public $mRevisionTimestamp; # The timestamp of the specified revision ID
	public $mRevisionUser; # User to display in {{REVISIONUSER}} tag
	public $mRevisionSize; # Size to display in {{REVISIONSIZE}} variable
	public $mRevIdForTs;   # The revision ID which was used to fetch the timestamp
	public $mInputSize = false; # For {{PAGESIZE}} on current page.

	/**
	 * @var string Deprecated accessor for the strip marker prefix.
	 * @deprecated since 1.26; use Parser::MARKER_PREFIX instead.
	 */
	public $mUniqPrefix = self::MARKER_PREFIX;

	/**
	 * @var array Array with the language name of each language link (i.e. the
	 * interwiki prefix) in the key, value arbitrary. Used to avoid sending
	 * duplicate language links to the ParserOutput.
	 */
	public $mLangLinkLanguages;

	/**
	 * @var MapCacheLRU|null
	 * @since 1.24
	 *
	 * A cache of the current revisions of titles. Keys are $title->getPrefixedDbKey()
	 */
	public $currentRevisionCache;

	/**
	 * @var bool|string Recursive call protection.
	 * This variable should be treated as if it were private.
	 */
	public $mInParse = false;

	/** @var SectionProfiler */
	protected $mProfiler;

	/**
	 * @var LinkRenderer
	 */
	protected $mLinkRenderer;

	/** @var MagicWordFactory */
	private $magicWordFactory;

	/** @var Language */
	private $contLang;

	/** @var ParserFactory */
	private $factory;

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	/** @var Config */
	private $siteConfig;

	/** @var LinkRendererFactory */
	private $linkRendererFactory;

	/**
	 * @param array $parserConf See $wgParserConf documentation
	 * @param MagicWordFactory|null $magicWordFactory
	 * @param Language|null $contLang Content language
	 * @param ParserFactory|null $factory
	 * @param string|null $urlProtocols As returned from wfUrlProtocols()
	 * @param SpecialPageFactory|null $spFactory
	 * @param Config|null $siteConfig
	 * @param LinkRendererFactory|null $linkRendererFactory
	 */
	public function __construct(
		array $parserConf = [], MagicWordFactory $magicWordFactory = null,
		Language $contLang = null, ParserFactory $factory = null, $urlProtocols = null,
		SpecialPageFactory $spFactory = null, Config $siteConfig = null,
		LinkRendererFactory $linkRendererFactory = null
	) {
		$this->mConf = $parserConf;
		$this->mUrlProtocols = $urlProtocols ?? wfUrlProtocols();
		$this->mExtLinkBracketedRegex = '/\[(((?i)' . $this->mUrlProtocols . ')' .
			self::EXT_LINK_ADDR .
			self::EXT_LINK_URL_CLASS . '*)\p{Zs}*([^\]\\x00-\\x08\\x0a-\\x1F\\x{FFFD}]*?)\]/Su';
		if ( isset( $parserConf['preprocessorClass'] ) ) {
			$this->mPreprocessorClass = $parserConf['preprocessorClass'];
		} elseif ( wfIsHHVM() ) {
			# Under HHVM Preprocessor_Hash is much faster than Preprocessor_DOM
			$this->mPreprocessorClass = Preprocessor_Hash::class;
		} elseif ( extension_loaded( 'domxml' ) ) {
			# PECL extension that conflicts with the core DOM extension (T15770)
			wfDebug( "Warning: you have the obsolete domxml extension for PHP. Please remove it!\n" );
			$this->mPreprocessorClass = Preprocessor_Hash::class;
		} elseif ( extension_loaded( 'dom' ) ) {
			$this->mPreprocessorClass = Preprocessor_DOM::class;
		} else {
			$this->mPreprocessorClass = Preprocessor_Hash::class;
		}
		wfDebug( __CLASS__ . ": using preprocessor: {$this->mPreprocessorClass}\n" );

		$services = MediaWikiServices::getInstance();
		$this->magicWordFactory = $magicWordFactory ??
			$services->getMagicWordFactory();

		$this->contLang = $contLang ?? $services->getContentLanguage();

		$this->factory = $factory ?? $services->getParserFactory();
		$this->specialPageFactory = $spFactory ?? $services->getSpecialPageFactory();
		$this->siteConfig = $siteConfig ?? MediaWikiServices::getInstance()->getMainConfig();

		$this->linkRendererFactory =
			$linkRendererFactory ?? MediaWikiServices::getInstance()->getLinkRendererFactory();
	}

	/**
	 * Reduce memory usage to reduce the impact of circular references
	 */
	public function __destruct() {
		if ( isset( $this->mLinkHolders ) ) {
			unset( $this->mLinkHolders );
		}
		foreach ( $this as $name => $value ) {
			unset( $this->$name );
		}
	}

	/**
	 * Allow extensions to clean up when the parser is cloned
	 */
	public function __clone() {
		$this->mInParse = false;

		// T58226: When you create a reference "to" an object field, that
		// makes the object field itself be a reference too (until the other
		// reference goes out of scope). When cloning, any field that's a
		// reference is copied as a reference in the new object. Both of these
		// are defined PHP5 behaviors, as inconvenient as it is for us when old
		// hooks from PHP4 days are passing fields by reference.
		foreach ( [ 'mStripState', 'mVarCache' ] as $k ) {
			// Make a non-reference copy of the field, then rebind the field to
			// reference the new copy.
			$tmp = $this->$k;
			$this->$k =& $tmp;
			unset( $tmp );
		}

		Hooks::run( 'ParserCloned', [ $this ] );
	}

	/**
	 * Do various kinds of initialisation on the first call of the parser
	 */
	public function firstCallInit() {
		if ( !$this->mFirstCall ) {
			return;
		}
		$this->mFirstCall = false;

		CoreParserFunctions::register( $this );
		CoreTagHooks::register( $this );
		$this->initialiseVariables();

		// Avoid PHP 7.1 warning from passing $this by reference
		$parser = $this;
		Hooks::run( 'ParserFirstCallInit', [ &$parser ] );
	}

	/**
	 * Clear Parser state
	 *
	 * @private
	 */
	public function clearState() {
		$this->firstCallInit();
		$this->mOutput = new ParserOutput;
		$this->mOptions->registerWatcher( [ $this->mOutput, 'recordOption' ] );
		$this->mAutonumber = 0;
		$this->mIncludeCount = [];
		$this->mLinkHolders = new LinkHolderArray( $this );
		$this->mLinkID = 0;
		$this->mRevisionObject = $this->mRevisionTimestamp =
			$this->mRevisionId = $this->mRevisionUser = $this->mRevisionSize = null;
		$this->mVarCache = [];
		$this->mUser = null;
		$this->mLangLinkLanguages = [];
		$this->currentRevisionCache = null;

		$this->mStripState = new StripState( $this );

		# Clear these on every parse, T6549
		$this->mTplRedirCache = $this->mTplDomCache = [];

		$this->mShowToc = true;
		$this->mForceTocPosition = false;
		$this->mIncludeSizes = [
			'post-expand' => 0,
			'arg' => 0,
		];
		$this->mPPNodeCount = 0;
		$this->mGeneratedPPNodeCount = 0;
		$this->mHighestExpansionDepth = 0;
		$this->mDefaultSort = false;
		$this->mHeadings = [];
		$this->mDoubleUnderscores = [];
		$this->mExpensiveFunctionCount = 0;

		# Fix cloning
		if ( isset( $this->mPreprocessor ) && $this->mPreprocessor->parser !== $this ) {
			$this->mPreprocessor = null;
		}

		$this->mProfiler = new SectionProfiler();

		// Avoid PHP 7.1 warning from passing $this by reference
		$parser = $this;
		Hooks::run( 'ParserClearState', [ &$parser ] );
	}

	/**
	 * Convert wikitext to HTML
	 * Do not call this function recursively.
	 *
	 * @param string $text Text we want to parse
	 * @param-taint $text escapes_htmlnoent
	 * @param Title $title
	 * @param ParserOptions $options
	 * @param bool $linestart
	 * @param bool $clearState
	 * @param int|null $revid Number to pass in {{REVISIONID}}
	 * @return ParserOutput A ParserOutput
	 * @return-taint escaped
	 */
	public function parse(
		$text, Title $title, ParserOptions $options,
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

		$this->startParse( $title, $options, self::OT_HTML, $clearState );

		$this->currentRevisionCache = null;
		$this->mInputSize = strlen( $text );
		if ( $this->mOptions->getEnableLimitReport() ) {
			$this->mOutput->resetParseStartTime();
		}

		$oldRevisionId = $this->mRevisionId;
		$oldRevisionObject = $this->mRevisionObject;
		$oldRevisionTimestamp = $this->mRevisionTimestamp;
		$oldRevisionUser = $this->mRevisionUser;
		$oldRevisionSize = $this->mRevisionSize;
		if ( $revid !== null ) {
			$this->mRevisionId = $revid;
			$this->mRevisionObject = null;
			$this->mRevisionTimestamp = null;
			$this->mRevisionUser = null;
			$this->mRevisionSize = null;
		}

		// Avoid PHP 7.1 warning from passing $this by reference
		$parser = $this;
		Hooks::run( 'ParserBeforeStrip', [ &$parser, &$text, &$this->mStripState ] );
		# No more strip!
		Hooks::run( 'ParserAfterStrip', [ &$parser, &$text, &$this->mStripState ] );
		$text = $this->internalParse( $text );
		Hooks::run( 'ParserAfterParse', [ &$parser, &$text, &$this->mStripState ] );

		$text = $this->internalParseHalfParsed( $text, true, $linestart );

		/**
		 * A converted title will be provided in the output object if title and
		 * content conversion are enabled, the article text does not contain
		 * a conversion-suppressing double-underscore tag, and no
		 * {{DISPLAYTITLE:...}} is present. DISPLAYTITLE takes precedence over
		 * automatic link conversion.
		 */
		if ( !( $options->getDisableTitleConversion()
			|| isset( $this->mDoubleUnderscores['nocontentconvert'] )
			|| isset( $this->mDoubleUnderscores['notitleconvert'] )
			|| $this->mOutput->getDisplayTitle() !== false )
		) {
			$convruletitle = $this->getTargetLanguage()->getConvRuleTitle();
			if ( $convruletitle ) {
				$this->mOutput->setTitleText( $convruletitle );
			} else {
				$titleText = $this->getTargetLanguage()->convertTitle( $title );
				$this->mOutput->setTitleText( $titleText );
			}
		}

		# Compute runtime adaptive expiry if set
		$this->mOutput->finalizeAdaptiveCacheExpiry();

		# Warn if too many heavyweight parser functions were used
		if ( $this->mExpensiveFunctionCount > $this->mOptions->getExpensiveParserFunctionLimit() ) {
			$this->limitationWarn( 'expensive-parserfunction',
				$this->mExpensiveFunctionCount,
				$this->mOptions->getExpensiveParserFunctionLimit()
			);
		}

		# Information on limits, for the benefit of users who try to skirt them
		if ( $this->mOptions->getEnableLimitReport() ) {
			$text .= $this->makeLimitReport();
		}

		# Wrap non-interface parser output in a <div> so it can be targeted
		# with CSS (T37247)
		$class = $this->mOptions->getWrapOutputClass();
		if ( $class !== false && !$this->mOptions->getInterfaceMessage() ) {
			$this->mOutput->addWrapperDivClass( $class );
		}

		$this->mOutput->setText( $text );

		$this->mRevisionId = $oldRevisionId;
		$this->mRevisionObject = $oldRevisionObject;
		$this->mRevisionTimestamp = $oldRevisionTimestamp;
		$this->mRevisionUser = $oldRevisionUser;
		$this->mRevisionSize = $oldRevisionSize;
		$this->mInputSize = false;
		$this->currentRevisionCache = null;

		return $this->mOutput;
	}

	/**
	 * Set the limit report data in the current ParserOutput, and return the
	 * limit report HTML comment.
	 *
	 * @return string
	 */
	protected function makeLimitReport() {
		$maxIncludeSize = $this->mOptions->getMaxIncludeSize();

		$cpuTime = $this->mOutput->getTimeSinceStart( 'cpu' );
		if ( $cpuTime !== null ) {
			$this->mOutput->setLimitReportData( 'limitreport-cputime',
				sprintf( "%.3f", $cpuTime )
			);
		}

		$wallTime = $this->mOutput->getTimeSinceStart( 'wall' );
		$this->mOutput->setLimitReportData( 'limitreport-walltime',
			sprintf( "%.3f", $wallTime )
		);

		$this->mOutput->setLimitReportData( 'limitreport-ppvisitednodes',
			[ $this->mPPNodeCount, $this->mOptions->getMaxPPNodeCount() ]
		);
		$this->mOutput->setLimitReportData( 'limitreport-ppgeneratednodes',
			[ $this->mGeneratedPPNodeCount, $this->mOptions->getMaxGeneratedPPNodeCount() ]
		);
		$this->mOutput->setLimitReportData( 'limitreport-postexpandincludesize',
			[ $this->mIncludeSizes['post-expand'], $maxIncludeSize ]
		);
		$this->mOutput->setLimitReportData( 'limitreport-templateargumentsize',
			[ $this->mIncludeSizes['arg'], $maxIncludeSize ]
		);
		$this->mOutput->setLimitReportData( 'limitreport-expansiondepth',
			[ $this->mHighestExpansionDepth, $this->mOptions->getMaxPPExpandDepth() ]
		);
		$this->mOutput->setLimitReportData( 'limitreport-expensivefunctioncount',
			[ $this->mExpensiveFunctionCount, $this->mOptions->getExpensiveParserFunctionLimit() ]
		);

		foreach ( $this->mStripState->getLimitReport() as list( $key, $value ) ) {
			$this->mOutput->setLimitReportData( $key, $value );
		}

		Hooks::run( 'ParserLimitReportPrepare', [ $this, $this->mOutput ] );

		$limitReport = "NewPP limit report\n";
		if ( $this->siteConfig->get( 'ShowHostnames' ) ) {
			$limitReport .= 'Parsed by ' . wfHostname() . "\n";
		}
		$limitReport .= 'Cached time: ' . $this->mOutput->getCacheTime() . "\n";
		$limitReport .= 'Cache expiry: ' . $this->mOutput->getCacheExpiry() . "\n";
		$limitReport .= 'Dynamic content: ' .
			( $this->mOutput->hasDynamicContent() ? 'true' : 'false' ) .
			"\n";

		foreach ( $this->mOutput->getLimitReportData() as $key => $value ) {
			if ( Hooks::run( 'ParserLimitReportFormat',
				[ $key, &$value, &$limitReport, false, false ]
			) ) {
				$keyMsg = wfMessage( $key )->inLanguage( 'en' )->useDatabase( false );
				$valueMsg = wfMessage( [ "$key-value-text", "$key-value" ] )
					->inLanguage( 'en' )->useDatabase( false );
				if ( !$valueMsg->exists() ) {
					$valueMsg = new RawMessage( '$1' );
				}
				if ( !$keyMsg->isDisabled() && !$valueMsg->isDisabled() ) {
					$valueMsg->params( $value );
					$limitReport .= "{$keyMsg->text()}: {$valueMsg->text()}\n";
				}
			}
		}
		// Since we're not really outputting HTML, decode the entities and
		// then re-encode the things that need hiding inside HTML comments.
		$limitReport = htmlspecialchars_decode( $limitReport );

		// Sanitize for comment. Note '‐' in the replacement is U+2010,
		// which looks much like the problematic '-'.
		$limitReport = str_replace( [ '-', '&' ], [ '‐', '&amp;' ], $limitReport );
		$text = "\n<!-- \n$limitReport-->\n";

		// Add on template profiling data in human/machine readable way
		$dataByFunc = $this->mProfiler->getFunctionStats();
		uasort( $dataByFunc, function ( $a, $b ) {
			return $b['real'] <=> $a['real']; // descending order
		} );
		$profileReport = [];
		foreach ( array_slice( $dataByFunc, 0, 10 ) as $item ) {
			$profileReport[] = sprintf( "%6.2f%% %8.3f %6d %s",
				$item['%real'], $item['real'], $item['calls'],
				htmlspecialchars( $item['name'] ) );
		}
		$text .= "<!--\nTransclusion expansion time report (%,ms,calls,template)\n";
		$text .= implode( "\n", $profileReport ) . "\n-->\n";

		$this->mOutput->setLimitReportData( 'limitreport-timingprofile', $profileReport );

		// Add other cache related metadata
		if ( $this->siteConfig->get( 'ShowHostnames' ) ) {
			$this->mOutput->setLimitReportData( 'cachereport-origin', wfHostname() );
		}
		$this->mOutput->setLimitReportData( 'cachereport-timestamp',
			$this->mOutput->getCacheTime() );
		$this->mOutput->setLimitReportData( 'cachereport-ttl',
			$this->mOutput->getCacheExpiry() );
		$this->mOutput->setLimitReportData( 'cachereport-transientcontent',
			$this->mOutput->hasDynamicContent() );

		if ( $this->mGeneratedPPNodeCount > $this->mOptions->getMaxGeneratedPPNodeCount() / 10 ) {
			wfDebugLog( 'generated-pp-node-count', $this->mGeneratedPPNodeCount . ' ' .
				$this->mTitle->getPrefixedDBkey() );
		}
		return $text;
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
	 * @param bool|PPFrame $frame The frame to use for expanding any template variables
	 * @return string UNSAFE half-parsed HTML
	 * @return-taint escaped
	 */
	public function recursiveTagParse( $text, $frame = false ) {
		// Avoid PHP 7.1 warning from passing $this by reference
		$parser = $this;
		Hooks::run( 'ParserBeforeStrip', [ &$parser, &$text, &$this->mStripState ] );
		Hooks::run( 'ParserAfterStrip', [ &$parser, &$text, &$this->mStripState ] );
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
	 * @param bool|PPFrame $frame The frame to use for expanding any template variables
	 * @return string Fully parsed HTML
	 * @return-taint escaped
	 */
	public function recursiveTagParseFully( $text, $frame = false ) {
		$text = $this->recursiveTagParse( $text, $frame );
		$text = $this->internalParseHalfParsed( $text, false );
		return $text;
	}

	/**
	 * Expand templates and variables in the text, producing valid, static wikitext.
	 * Also removes comments.
	 * Do not call this function recursively.
	 * @param string $text
	 * @param Title|null $title
	 * @param ParserOptions $options
	 * @param int|null $revid
	 * @param bool|PPFrame $frame
	 * @return mixed|string
	 */
	public function preprocess( $text, Title $title = null,
		ParserOptions $options, $revid = null, $frame = false
	) {
		$magicScopeVariable = $this->lock();
		$this->startParse( $title, $options, self::OT_PREPROCESS, true );
		if ( $revid !== null ) {
			$this->mRevisionId = $revid;
		}
		// Avoid PHP 7.1 warning from passing $this by reference
		$parser = $this;
		Hooks::run( 'ParserBeforeStrip', [ &$parser, &$text, &$this->mStripState ] );
		Hooks::run( 'ParserAfterStrip', [ &$parser, &$text, &$this->mStripState ] );
		$text = $this->replaceVariables( $text, $frame );
		$text = $this->mStripState->unstripBoth( $text );
		return $text;
	}

	/**
	 * Recursive parser entry point that can be called from an extension tag
	 * hook.
	 *
	 * @param string $text Text to be expanded
	 * @param bool|PPFrame $frame The frame to use for expanding any template variables
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
	 * @param Title $title
	 * @param ParserOptions $options
	 * @param array $params
	 * @return string
	 */
	public function getPreloadText( $text, Title $title, ParserOptions $options, $params = [] ) {
		$msg = new RawMessage( $text );
		$text = $msg->params( $params )->plain();

		# Parser (re)initialisation
		$magicScopeVariable = $this->lock();
		$this->startParse( $title, $options, self::OT_PLAIN, true );

		$flags = PPFrame::NO_ARGS | PPFrame::NO_TEMPLATES;
		$dom = $this->preprocessToDom( $text, self::PTD_FOR_INCLUSION );
		$text = $this->getPreprocessor()->newFrame()->expand( $dom, $flags );
		$text = $this->mStripState->unstripBoth( $text );
		return $text;
	}

	/**
	 * Set the current user.
	 * Should only be used when doing pre-save transform.
	 *
	 * @param User|null $user User object or null (to reset)
	 */
	public function setUser( $user ) {
		$this->mUser = $user;
	}

	/**
	 * Set the context title
	 *
	 * @param Title $t
	 */
	public function setTitle( $t ) {
		if ( !$t ) {
			$t = Title::newFromText( 'NO TITLE' );
		}

		if ( $t->hasFragment() ) {
			# Strip the fragment to avoid various odd effects
			$this->mTitle = $t->createFragmentTarget( '' );
		} else {
			$this->mTitle = $t;
		}
	}

	/**
	 * Accessor for the Title object
	 *
	 * @return Title
	 */
	public function getTitle() {
		return $this->mTitle;
	}

	/**
	 * Accessor/mutator for the Title object
	 *
	 * @param Title|null $x Title object or null to just get the current one
	 * @return Title
	 */
	public function Title( $x = null ) {
		return wfSetVar( $this->mTitle, $x );
	}

	/**
	 * Set the output type
	 *
	 * @param int $ot New value
	 */
	public function setOutputType( $ot ) {
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
	 */
	public function OutputType( $x = null ) {
		return wfSetVar( $this->mOutputType, $x );
	}

	/**
	 * Get the ParserOutput object
	 *
	 * @return ParserOutput
	 */
	public function getOutput() {
		return $this->mOutput;
	}

	/**
	 * Get the ParserOptions object
	 *
	 * @return ParserOptions
	 */
	public function getOptions() {
		return $this->mOptions;
	}

	/**
	 * Accessor/mutator for the ParserOptions object
	 *
	 * @param ParserOptions|null $x New value or null to just get the current one
	 * @return ParserOptions Current ParserOptions object
	 */
	public function Options( $x = null ) {
		return wfSetVar( $this->mOptions, $x );
	}

	/**
	 * @return int
	 */
	public function nextLinkID() {
		return $this->mLinkID++;
	}

	/**
	 * @param int $id
	 */
	public function setLinkID( $id ) {
		$this->mLinkID = $id;
	}

	/**
	 * Get a language object for use in parser functions such as {{FORMATNUM:}}
	 * @return Language
	 */
	public function getFunctionLang() {
		return $this->getTargetLanguage();
	}

	/**
	 * Get the target language for the content being parsed. This is usually the
	 * language that the content is in.
	 *
	 * @since 1.19
	 *
	 * @throws MWException
	 * @return Language
	 */
	public function getTargetLanguage() {
		$target = $this->mOptions->getTargetLanguage();

		if ( $target !== null ) {
			return $target;
		} elseif ( $this->mOptions->getInterfaceMessage() ) {
			return $this->mOptions->getUserLangObj();
		} elseif ( is_null( $this->mTitle ) ) {
			throw new MWException( __METHOD__ . ': $this->mTitle is null' );
		}

		return $this->mTitle->getPageLanguage();
	}

	/**
	 * Get the language object for language conversion
	 * @deprecated since 1.32, just use getTargetLanguage()
	 * @return Language|null
	 */
	public function getConverterLanguage() {
		return $this->getTargetLanguage();
	}

	/**
	 * Get a User object either from $this->mUser, if set, or from the
	 * ParserOptions object otherwise
	 *
	 * @return User
	 */
	public function getUser() {
		if ( !is_null( $this->mUser ) ) {
			return $this->mUser;
		}
		return $this->mOptions->getUser();
	}

	/**
	 * Get a preprocessor object
	 *
	 * @return Preprocessor
	 */
	public function getPreprocessor() {
		if ( !isset( $this->mPreprocessor ) ) {
			$class = $this->mPreprocessorClass;
			$this->mPreprocessor = new $class( $this );
		}
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
			$this->mLinkRenderer->setStubThreshold(
				$this->getOptions()->getStubThreshold()
			);
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
	 * @param array $elements List of element names. Comments are always extracted.
	 * @param string $text Source text string.
	 * @param array &$matches Out parameter, Array: extracted tags
	 * @return string Stripped text
	 */
	public static function extractTagsAndParams( $elements, $text, &$matches ) {
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
				list( , $element, $attributes, $close, $inside ) = $p;
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
					list( , $tail, $text ) = $q;
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
	 * parse the wiki syntax used to render tables
	 *
	 * @private
	 * @param string $text
	 * @return string
	 */
	public function doTableStuff( $text ) {
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
				array_push( $td_history, false );
				array_push( $last_tag_history, '' );
				array_push( $tr_history, false );
				array_push( $tr_attributes, '' );
				array_push( $has_opened_tr, false );
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
				array_push( $tr_attributes, $attributes );

				$line = '';
				$last_tag = array_pop( $last_tag_history );
				array_pop( $has_opened_tr );
				array_push( $has_opened_tr, true );

				if ( array_pop( $tr_history ) ) {
					$line = '</tr>';
				}

				if ( array_pop( $td_history ) ) {
					$line = "</{$last_tag}>{$line}";
				}

				$outLine = $line;
				array_push( $tr_history, false );
				array_push( $td_history, false );
				array_push( $last_tag_history, '' );
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
						array_push( $tr_history, true );
						array_push( $tr_attributes, '' );
						array_pop( $has_opened_tr );
						array_push( $has_opened_tr, true );
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

					array_push( $last_tag_history, $last_tag );

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
					array_push( $td_history, true );
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
	 * @private
	 *
	 * @param string $text The text to parse
	 * @param-taint $text escapes_html
	 * @param bool $isMain Whether this is being called from the main parse() function
	 * @param PPFrame|bool $frame A pre-processor frame
	 *
	 * @return string
	 */
	public function internalParse( $text, $isMain = true, $frame = false ) {
		$origText = $text;

		// Avoid PHP 7.1 warning from passing $this by reference
		$parser = $this;

		# Hook to suspend the parser in this state
		if ( !Hooks::run( 'ParserBeforeInternalParse', [ &$parser, &$text, &$this->mStripState ] ) ) {
			return $text;
		}

		# if $frame is provided, then use $frame for replacing any variables
		if ( $frame ) {
			# use frame depth to infer how include/noinclude tags should be handled
			# depth=0 means this is the top-level document; otherwise it's an included document
			if ( !$frame->depth ) {
				$flag = 0;
			} else {
				$flag = self::PTD_FOR_INCLUSION;
			}
			$dom = $this->preprocessToDom( $text, $flag );
			$text = $frame->expand( $dom );
		} else {
			# if $frame is not provided, then use old-style replaceVariables
			$text = $this->replaceVariables( $text );
		}

		Hooks::run( 'InternalParseBeforeSanitize', [ &$parser, &$text, &$this->mStripState ] );
		$text = Sanitizer::removeHTMLtags(
			$text,
			[ $this, 'attributeStripCallback' ],
			false,
			array_keys( $this->mTransparentTagHooks ),
			[],
			[ $this, 'addTrackingCategory' ]
		);
		Hooks::run( 'InternalParseBeforeLinks', [ &$parser, &$text, &$this->mStripState ] );

		# Tables need to come after variable replacement for things to work
		# properly; putting them before other transformations should keep
		# exciting things like link expansions from showing up in surprising
		# places.
		$text = $this->doTableStuff( $text );

		$text = preg_replace( '/(^|\n)-----*/', '\\1<hr />', $text );

		$text = $this->doDoubleUnderscore( $text );

		$text = $this->doHeadings( $text );
		$text = $this->replaceInternalLinks( $text );
		$text = $this->doAllQuotes( $text );
		$text = $this->replaceExternalLinks( $text );

		# replaceInternalLinks may sometimes leave behind
		# absolute URLs, which have to be masked to hide them from replaceExternalLinks
		$text = str_replace( self::MARKER_PREFIX . 'NOPARSE', '', $text );

		$text = $this->doMagicLinks( $text );
		$text = $this->formatHeadings( $text, $origText, $isMain );

		return $text;
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

		// Avoid PHP 7.1 warning from passing $this by reference
		$parser = $this;

		if ( $isMain ) {
			Hooks::run( 'ParserAfterUnstrip', [ &$parser, &$text ] );
		}

		# Clean up special characters, only run once, next-to-last before doBlockLevels
		$text = Sanitizer::armorFrenchSpaces( $text );

		$text = $this->doBlockLevels( $text, $linestart );

		$this->replaceLinkHolders( $text );

		/**
		 * The input doesn't get language converted if
		 * a) It's disabled
		 * b) Content isn't converted
		 * c) It's a conversion table
		 * d) it is an interface message (which is in the user language)
		 */
		if ( !( $this->mOptions->getDisableContentConversion()
			|| isset( $this->mDoubleUnderscores['nocontentconvert'] ) )
			&& !$this->mOptions->getInterfaceMessage()
		) {
			# The position of the convert() call should not be changed. it
			# assumes that the links are all replaced and the only thing left
			# is the <nowiki> mark.
			$text = $this->getTargetLanguage()->convert( $text );
		}

		$text = $this->mStripState->unstripNoWiki( $text );

		if ( $isMain ) {
			Hooks::run( 'ParserBeforeTidy', [ &$parser, &$text ] );
		}

		$text = $this->replaceTransparentTags( $text );
		$text = $this->mStripState->unstripGeneral( $text );

		$text = Sanitizer::normalizeCharReferences( $text );

		if ( MWTidy::isEnabled() ) {
			if ( $this->mOptions->getTidy() ) {
				$text = MWTidy::tidy( $text );
			}
		} else {
			# attempt to sanitize at least some nesting problems
			# (T4702 and quite a few others)
			# This code path is buggy and deprecated!
			wfDeprecated( 'disabling tidy', '1.33' );
			$tidyregs = [
				# ''Something [http://www.cool.com cool''] -->
				# <i>Something</i><a href="http://www.cool.com"..><i>cool></i></a>
				'/(<([bi])>)(<([bi])>)?([^<]*)(<\/?a[^<]*>)([^<]*)(<\/\\4>)?(<\/\\2>)/' =>
				'\\1\\3\\5\\8\\9\\6\\1\\3\\7\\8\\9',
				# fix up an anchor inside another anchor, only
				# at least for a single single nested link (T5695)
				'/(<a[^>]+>)([^<]*)(<a[^>]+>[^<]*)<\/a>(.*)<\/a>/' =>
				'\\1\\2</a>\\3</a>\\1\\4</a>',
				# fix div inside inline elements- doBlockLevels won't wrap a line which
				# contains a div, so fix it up here; replace
				# div with escaped text
				'/(<([aib]) [^>]+>)([^<]*)(<div([^>]*)>)(.*)(<\/div>)([^<]*)(<\/\\2>)/' =>
				'\\1\\3&lt;div\\5&gt;\\6&lt;/div&gt;\\8\\9',
				# remove empty italic or bold tag pairs, some
				# introduced by rules above
				'/<([bi])><\/\\1>/' => '',
			];

			$text = preg_replace(
				array_keys( $tidyregs ),
				array_values( $tidyregs ),
				$text );
		}

		if ( $isMain ) {
			Hooks::run( 'ParserAfterTidy', [ &$parser, &$text ] );
		}

		return $text;
	}

	/**
	 * Replace special strings like "ISBN xxx" and "RFC xxx" with
	 * magic external links.
	 *
	 * DML
	 * @private
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function doMagicLinks( $text ) {
		$prots = wfUrlProtocolsWithoutProtRel();
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
			)!xu", [ $this, 'magicLinkCallback' ], $text );
		return $text;
	}

	/**
	 * @throws MWException
	 * @param array $m
	 * @return string HTML
	 */
	public function magicLinkCallback( $m ) {
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
			if ( substr( $m[0], 0, 3 ) === 'RFC' ) {
				if ( !$this->mOptions->getMagicRFCLinks() ) {
					return $m[0];
				}
				$keyword = 'RFC';
				$urlmsg = 'rfcurl';
				$cssClass = 'mw-magiclink-rfc';
				$trackingCat = 'magiclink-tracking-rfc';
				$id = $m[5];
			} elseif ( substr( $m[0], 0, 4 ) === 'PMID' ) {
				if ( !$this->mOptions->getMagicPMIDLinks() ) {
					return $m[0];
				}
				$keyword = 'PMID';
				$urlmsg = 'pubmedurl';
				$cssClass = 'mw-magiclink-pmid';
				$trackingCat = 'magiclink-tracking-pmid';
				$id = $m[5];
			} else {
				throw new MWException( __METHOD__ . ': unrecognised match type "' .
					substr( $m[0], 0, 20 ) . '"' );
			}
			$url = wfMessage( $urlmsg, $id )->inContentLanguage()->text();
			$this->addTrackingCategory( $trackingCat );
			return Linker::makeExternalLink( $url, "{$keyword} {$id}", true, $cssClass, [], $this->mTitle );
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
	 * @private
	 */
	public function makeFreeExternalLink( $url, $numPostProto ) {
		$trail = '';

		# The characters '<' and '>' (which were escaped by
		# removeHTMLtags()) should not be included in
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
			$text = Linker::makeExternalLink( $url,
				$this->getTargetLanguage()->getConverter()->markNoConversion( $url ),
				true, 'free',
				$this->getExternalLinkAttribs( $url ), $this->mTitle );
			# Register it in the output object...
			$this->mOutput->addExternalLink( $url );
		}
		return $text . $trail;
	}

	/**
	 * Parse headers and return html
	 *
	 * @private
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function doHeadings( $text ) {
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
	 * @private
	 *
	 * @param string $text
	 *
	 * @return string The altered text
	 */
	public function doAllQuotes( $text ) {
		$outtext = '';
		$lines = StringUtils::explode( "\n", $text );
		foreach ( $lines as $line ) {
			$outtext .= $this->doQuotes( $line ) . "\n";
		}
		$outtext = substr( $outtext, 0, -1 );
		return $outtext;
	}

	/**
	 * Helper function for doAllQuotes()
	 *
	 * @param string $text
	 *
	 * @return string
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
	 * @private
	 *
	 * @param string $text
	 *
	 * @throws MWException
	 * @return string
	 */
	public function replaceExternalLinks( $text ) {
		$bits = preg_split( $this->mExtLinkBracketedRegex, $text, -1, PREG_SPLIT_DELIM_CAPTURE );
		if ( $bits === false ) {
			throw new MWException( "PCRE needs to be compiled with "
				. "--enable-unicode-properties in order for MediaWiki to function" );
		}
		$s = array_shift( $bits );

		$i = 0;
		while ( $i < count( $bits ) ) {
			$url = $bits[$i++];
			$i++; // protocol
			$text = $bits[$i++];
			$trail = $bits[$i++];

			# The characters '<' and '>' (which were escaped by
			# removeHTMLtags()) should not be included in
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
				list( $dtrail, $trail ) = Linker::splitTrail( $trail );
			}

			// Excluding protocol-relative URLs may avoid many false positives.
			if ( preg_match( '/^(?:' . wfUrlProtocolsWithoutProtRel() . ')/', $text ) ) {
				$text = $this->getTargetLanguage()->getConverter()->markNoConversion( $text );
			}

			$url = Sanitizer::cleanUrl( $url );

			# Use the encoded URL
			# This means that users can paste URLs directly into the text
			# Funny characters like ö aren't valid in URLs anyway
			# This was changed in August 2004
			$s .= Linker::makeExternalLink( $url, $text, false, $linktype,
				$this->getExternalLinkAttribs( $url ), $this->mTitle ) . $dtrail . $trail;

			# Register link in the output object.
			$this->mOutput->addExternalLink( $url );
		}

		return $s;
	}

	/**
	 * Get the rel attribute for a particular external link.
	 *
	 * @since 1.21
	 * @param string|bool $url Optional URL, to extract the domain from for rel =>
	 *   nofollow if appropriate
	 * @param Title|null $title Optional Title, for wgNoFollowNsExceptions lookups
	 * @return string|null Rel attribute for $url
	 */
	public static function getExternalLinkRel( $url = false, $title = null ) {
		global $wgNoFollowLinks, $wgNoFollowNsExceptions, $wgNoFollowDomainExceptions;
		$ns = $title ? $title->getNamespace() : false;
		if ( $wgNoFollowLinks && !in_array( $ns, $wgNoFollowNsExceptions )
			&& !wfMatchesDomainList( $url, $wgNoFollowDomainExceptions )
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
	 * @param string $url URL to extract the domain from for rel =>
	 *   nofollow if appropriate
	 * @return array Associative array of HTML attributes
	 */
	public function getExternalLinkAttribs( $url ) {
		$attribs = [];
		$rel = self::getExternalLinkRel( $url, $this->mTitle );

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
		$attribs['rel'] = $rel;
		return $attribs;
	}

	/**
	 * Replace unusual escape codes in a URL with their equivalent characters
	 *
	 * This generally follows the syntax defined in RFC 3986, with special
	 * consideration for HTTP query strings.
	 *
	 * @param string $url
	 * @return string
	 */
	public static function normalizeLinkUrl( $url ) {
		# Test for RFC 3986 IPv6 syntax
		$scheme = '[a-z][a-z0-9+.-]*:';
		$userinfo = '(?:[a-z0-9\-._~!$&\'()*+,;=:]|%[0-9a-f]{2})*';
		$ipv6Host = '\\[((?:[0-9a-f:]|%3[0-A]|%[46][1-6])+)\\]';
		if ( preg_match( "<^(?:{$scheme})?//(?:{$userinfo}@)?{$ipv6Host}(?:[:/?#].*|)$>i", $url, $m ) &&
			IP::isValid( rawurldecode( $m[1] ) )
		) {
			$isIPv6 = rawurldecode( $m[1] );
		} else {
			$isIPv6 = false;
		}

		# Make sure unsafe characters are encoded
		$url = preg_replace_callback( '/[\x00-\x20"<>\[\\\\\]^`{|}\x7F-\xFF]/',
			function ( $m ) {
				return rawurlencode( $m[0] );
			},
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

		# Scheme and path part - 'pchar'
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

	private static function normalizeUrlComponent( $component, $unsafe ) {
		$callback = function ( $matches ) use ( $unsafe ) {
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
		$imagesexception = !empty( $imagesfrom );
		$text = false;
		# $imagesfrom could be either a single string or an array of strings, parse out the latter
		if ( $imagesexception && is_array( $imagesfrom ) ) {
			$imagematch = false;
			foreach ( $imagesfrom as $match ) {
				if ( strpos( $url, $match ) === 0 ) {
					$imagematch = true;
					break;
				}
			}
		} elseif ( $imagesexception ) {
			$imagematch = ( strpos( $url, $imagesfrom ) === 0 );
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
				if ( strpos( $entry, '#' ) === 0 || $entry === '' ) {
					continue;
				}
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
	 * @param string $s
	 *
	 * @return string Processed text
	 *
	 * @private
	 */
	public function replaceInternalLinks( $s ) {
		$this->mLinkHolders->merge( $this->replaceInternalLinks2( $s ) );
		return $s;
	}

	/**
	 * Process [[ ]] wikilinks (RIL)
	 * @param string &$s
	 * @throws MWException
	 * @return LinkHolderArray
	 *
	 * @private
	 */
	public function replaceInternalLinks2( &$s ) {
		static $tc = false, $e1, $e1_img;
		# the % is needed to support urlencoded titles as well
		if ( !$tc ) {
			$tc = Title::legalChars() . '#%';
			# Match a link having the form [[namespace:link|alternate]]trail
			$e1 = "/^([{$tc}]+)(?:\\|(.+?))?]](.*)\$/sD";
			# Match cases where there is no "]]", which might still be images
			$e1_img = "/^([{$tc}]+)\\|(.*)\$/sD";
		}

		$holders = new LinkHolderArray( $this );

		# split the entire text string on occurrences of [[
		$a = StringUtils::explode( '[[', ' ' . $s );
		# get the first element (all text up to first [[), and remove the space we added
		$s = $a->current();
		$a->next();
		$line = $a->current(); # Workaround for broken ArrayIterator::next() that returns "void"
		$s = substr( $s, 1 );

		$useLinkPrefixExtension = $this->getTargetLanguage()->linkPrefixExtension();
		$e2 = null;
		if ( $useLinkPrefixExtension ) {
			# Match the end of a line for a word that's not followed by whitespace,
			# e.g. in the case of 'The Arab al[[Razi]]', 'al' will be matched
			$charset = $this->contLang->linkPrefixCharset();
			$e2 = "/^((?>.*[^$charset]|))(.+)$/sDu";
		}

		if ( is_null( $this->mTitle ) ) {
			throw new MWException( __METHOD__ . ": \$this->mTitle is null\n" );
		}
		$nottalk = !$this->mTitle->isTalkPage();

		if ( $useLinkPrefixExtension ) {
			$m = [];
			if ( preg_match( $e2, $s, $m ) ) {
				$first_prefix = $m[2];
			} else {
				$first_prefix = false;
			}
		} else {
			$prefix = '';
		}

		$useSubpages = $this->areSubpagesAllowed();

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
				if ( preg_match( $e2, $s, $m ) ) {
					list( , $s, $prefix ) = $m;
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
					$text .= ']'; # so that replaceExternalLinks($text) works later
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

			$origLink = ltrim( $m[1], ' ' );

			# Don't allow internal links to pages containing
			# PROTO: where PROTO is a valid URL protocol; these
			# should be external links.
			if ( preg_match( '/^(?i:' . $this->mUrlProtocols . ')/', $origLink ) ) {
				$s .= $prefix . '[[' . $line;
				continue;
			}

			# Make subpage if necessary
			if ( $useSubpages ) {
				$link = $this->maybeDoSubpageLink( $origLink, $text );
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

			$noforce = ( substr( $origLink, 0, 1 ) !== ':' );

			if ( $might_be_img ) { # if this is actually an invalid link
				if ( $ns == NS_FILE && $noforce ) { # but might be an image
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
						$holders->merge( $this->replaceInternalLinks2( $text ) );
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
						Language::fetchLanguageName( $iw, null, 'mw' ) ||
						in_array( $iw, $this->siteConfig->get( 'ExtraInterlanguageLinkPrefixes' ) )
					)
				) {
					# T26502: filter duplicates
					if ( !isset( $this->mLangLinkLanguages[$iw] ) ) {
						$this->mLangLinkLanguages[$iw] = true;
						$this->mOutput->addLanguageLink( $nt->getFullText() );
					}

					/**
					 * Strip the whitespace interwiki links produce, see T10897
					 */
					$s = rtrim( $s . $prefix ) . $trail; # T175416
					continue;
				}

				if ( $ns == NS_FILE ) {
					if ( !wfIsBadImage( $nt->getDBkey(), $this->mTitle ) ) {
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
							$text = $this->replaceExternalLinks( $text );
							$holders->merge( $this->replaceInternalLinks2( $text ) );
						}
						# cloak any absolute URLs inside the image markup, so replaceExternalLinks() won't touch them
						$s .= $prefix . $this->armorLinks(
							$this->makeImage( $nt, $text, $holders ) ) . $trail;
						continue;
					}
				} elseif ( $ns == NS_CATEGORY ) {
					/**
					 * Strip the whitespace Category links produce, see T2087
					 */
					$s = rtrim( $s . $prefix ) . $trail; # T2087, T87753

					if ( $wasblank ) {
						$sortkey = $this->getDefaultSort();
					} else {
						$sortkey = $text;
					}
					$sortkey = Sanitizer::decodeCharReferences( $sortkey );
					$sortkey = str_replace( "\n", '', $sortkey );
					$sortkey = $this->getTargetLanguage()->convertCategoryKey( $sortkey );
					$this->mOutput->addCategory( $nt->getDBkey(), $sortkey );

					continue;
				}
			}

			# Self-link checking. For some languages, variants of the title are checked in
			# LinkHolderArray::doVariants() to allow batching the existence checks necessary
			# for linking to a different variant.
			if ( $ns != NS_SPECIAL && $nt->equals( $this->mTitle ) && !$nt->hasFragment() ) {
				$s .= $prefix . Linker::makeSelfLinkObj( $nt, $text, '', $trail );
				continue;
			}

			# NS_MEDIA is a pseudo-namespace for linking directly to a file
			# @todo FIXME: Should do batch file existence checks, see comment below
			if ( $ns == NS_MEDIA ) {
				# Give extensions a chance to select the file revision for us
				$options = [];
				$descQuery = false;
				Hooks::run( 'BeforeParserFetchFileAndTitle',
					[ $this, $nt, &$options, &$descQuery ] );
				# Fetch and register the file (file title may be different via hooks)
				list( $file, $nt ) = $this->fetchFileAndTitle( $nt, $options );
				# Cloak with NOPARSE to avoid replacement in replaceExternalLinks
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
				$s .= $holders->makeHolder( $nt, $text, [], $trail, $prefix );
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
	 * @param Title $nt
	 * @param string $text
	 * @param string $trail
	 * @param string $prefix
	 * @return string HTML-wikitext mix oh yuck
	 */
	protected function makeKnownLinkHolder( $nt, $text = '', $trail = '', $prefix = '' ) {
		list( $inside, $trail ) = Linker::splitTrail( $trail );

		if ( $text == '' ) {
			$text = htmlspecialchars( $nt->getPrefixedText() );
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
	public function armorLinks( $text ) {
		return preg_replace( '/\b((?i)' . $this->mUrlProtocols . ')/',
			self::MARKER_PREFIX . "NOPARSE$1", $text );
	}

	/**
	 * Return true if subpage links should be expanded on this page.
	 * @return bool
	 */
	public function areSubpagesAllowed() {
		# Some namespaces don't allow subpages
		return MWNamespace::hasSubpages( $this->mTitle->getNamespace() );
	}

	/**
	 * Handle link to subpage if necessary
	 *
	 * @param string $target The source of the link
	 * @param string &$text The link text, modified as necessary
	 * @return string The full name of the link
	 * @private
	 */
	public function maybeDoSubpageLink( $target, &$text ) {
		return Linker::normalizeSubpageLink( $this->mTitle, $target, $text );
	}

	/**
	 * Make lists from lines starting with ':', '*', '#', etc. (DBL)
	 *
	 * @param string $text
	 * @param bool $linestart Whether or not this is at the start of a line.
	 * @private
	 * @return string The lists rendered as HTML
	 */
	public function doBlockLevels( $text, $linestart ) {
		return BlockLevelPass::doBlockLevels( $text, $linestart );
	}

	/**
	 * Return value of a magic variable (like PAGENAME)
	 *
	 * @private
	 *
	 * @param string $index Magic variable identifier as mapped in MagicWordFactory::$mVariableIDs
	 * @param bool|PPFrame $frame
	 *
	 * @throws MWException
	 * @return string
	 */
	public function getVariableValue( $index, $frame = false ) {
		if ( is_null( $this->mTitle ) ) {
			// If no title set, bad things are going to happen
			// later. Title should always be set since this
			// should only be called in the middle of a parse
			// operation (but the unit-tests do funky stuff)
			throw new MWException( __METHOD__ . ' Should only be '
				. ' called while parsing (no title set)' );
		}

		// Avoid PHP 7.1 warning from passing $this by reference
		$parser = $this;

		/**
		 * Some of these require message or data lookups and can be
		 * expensive to check many times.
		 */
		if ( Hooks::run( 'ParserGetVariableValueVarCache', [ &$parser, &$this->mVarCache ] )
			&& isset( $this->mVarCache[$index] )
		) {
			return $this->mVarCache[$index];
		}

		$ts = wfTimestamp( TS_UNIX, $this->mOptions->getTimestamp() );
		Hooks::run( 'ParserGetVariableValueTs', [ &$parser, &$ts ] );

		// In miser mode, disable words that always cause double-parses on page save (T137900)
		static $slowRevWords = [ 'revisionid' => true ]; // @TODO: 'revisiontimestamp'
		if (
			isset( $slowRevWords[$index] ) &&
			$this->siteConfig->get( 'MiserMode' ) &&
			!$this->mOptions->getInterfaceMessage() &&
			// @TODO: disallow this word on all namespaces
			MWNamespace::isContent( $this->mTitle->getNamespace() )
		) {
			return $this->mRevisionId ? '-' : '';
		};

		$pageLang = $this->getFunctionLang();

		switch ( $index ) {
			case '!':
				$value = '|';
				break;
			case 'currentmonth':
				$value = $pageLang->formatNum( MWTimestamp::getInstance( $ts )->format( 'm' ), true );
				break;
			case 'currentmonth1':
				$value = $pageLang->formatNum( MWTimestamp::getInstance( $ts )->format( 'n' ), true );
				break;
			case 'currentmonthname':
				$value = $pageLang->getMonthName( MWTimestamp::getInstance( $ts )->format( 'n' ) );
				break;
			case 'currentmonthnamegen':
				$value = $pageLang->getMonthNameGen( MWTimestamp::getInstance( $ts )->format( 'n' ) );
				break;
			case 'currentmonthabbrev':
				$value = $pageLang->getMonthAbbreviation( MWTimestamp::getInstance( $ts )->format( 'n' ) );
				break;
			case 'currentday':
				$value = $pageLang->formatNum( MWTimestamp::getInstance( $ts )->format( 'j' ), true );
				break;
			case 'currentday2':
				$value = $pageLang->formatNum( MWTimestamp::getInstance( $ts )->format( 'd' ), true );
				break;
			case 'localmonth':
				$value = $pageLang->formatNum( MWTimestamp::getLocalInstance( $ts )->format( 'm' ), true );
				break;
			case 'localmonth1':
				$value = $pageLang->formatNum( MWTimestamp::getLocalInstance( $ts )->format( 'n' ), true );
				break;
			case 'localmonthname':
				$value = $pageLang->getMonthName( MWTimestamp::getLocalInstance( $ts )->format( 'n' ) );
				break;
			case 'localmonthnamegen':
				$value = $pageLang->getMonthNameGen( MWTimestamp::getLocalInstance( $ts )->format( 'n' ) );
				break;
			case 'localmonthabbrev':
				$value = $pageLang->getMonthAbbreviation( MWTimestamp::getLocalInstance( $ts )->format( 'n' ) );
				break;
			case 'localday':
				$value = $pageLang->formatNum( MWTimestamp::getLocalInstance( $ts )->format( 'j' ), true );
				break;
			case 'localday2':
				$value = $pageLang->formatNum( MWTimestamp::getLocalInstance( $ts )->format( 'd' ), true );
				break;
			case 'pagename':
				$value = wfEscapeWikiText( $this->mTitle->getText() );
				break;
			case 'pagenamee':
				$value = wfEscapeWikiText( $this->mTitle->getPartialURL() );
				break;
			case 'fullpagename':
				$value = wfEscapeWikiText( $this->mTitle->getPrefixedText() );
				break;
			case 'fullpagenamee':
				$value = wfEscapeWikiText( $this->mTitle->getPrefixedURL() );
				break;
			case 'subpagename':
				$value = wfEscapeWikiText( $this->mTitle->getSubpageText() );
				break;
			case 'subpagenamee':
				$value = wfEscapeWikiText( $this->mTitle->getSubpageUrlForm() );
				break;
			case 'rootpagename':
				$value = wfEscapeWikiText( $this->mTitle->getRootText() );
				break;
			case 'rootpagenamee':
				$value = wfEscapeWikiText( wfUrlencode( str_replace(
					' ',
					'_',
					$this->mTitle->getRootText()
				) ) );
				break;
			case 'basepagename':
				$value = wfEscapeWikiText( $this->mTitle->getBaseText() );
				break;
			case 'basepagenamee':
				$value = wfEscapeWikiText( wfUrlencode( str_replace(
					' ',
					'_',
					$this->mTitle->getBaseText()
				) ) );
				break;
			case 'talkpagename':
				if ( $this->mTitle->canHaveTalkPage() ) {
					$talkPage = $this->mTitle->getTalkPage();
					$value = wfEscapeWikiText( $talkPage->getPrefixedText() );
				} else {
					$value = '';
				}
				break;
			case 'talkpagenamee':
				if ( $this->mTitle->canHaveTalkPage() ) {
					$talkPage = $this->mTitle->getTalkPage();
					$value = wfEscapeWikiText( $talkPage->getPrefixedURL() );
				} else {
					$value = '';
				}
				break;
			case 'subjectpagename':
				$subjPage = $this->mTitle->getSubjectPage();
				$value = wfEscapeWikiText( $subjPage->getPrefixedText() );
				break;
			case 'subjectpagenamee':
				$subjPage = $this->mTitle->getSubjectPage();
				$value = wfEscapeWikiText( $subjPage->getPrefixedURL() );
				break;
			case 'pageid': // requested in T25427
				$pageid = $this->getTitle()->getArticleID();
				if ( $pageid == 0 ) {
					# 0 means the page doesn't exist in the database,
					# which means the user is previewing a new page.
					# The vary-revision flag must be set, because the magic word
					# will have a different value once the page is saved.
					$this->mOutput->setFlag( 'vary-revision' );
					wfDebug( __METHOD__ . ": {{PAGEID}} used in a new page, setting vary-revision...\n" );
				}
				$value = $pageid ?: null;
				break;
			case 'revisionid':
				# Let the edit saving system know we should parse the page
				# *after* a revision ID has been assigned.
				$this->mOutput->setFlag( 'vary-revision-id' );
				wfDebug( __METHOD__ . ": {{REVISIONID}} used, setting vary-revision-id...\n" );
				$value = $this->mRevisionId;

				if ( !$value ) {
					$rev = $this->getRevisionObject();
					if ( $rev ) {
						$value = $rev->getId();
					}
				}

				if ( !$value ) {
					$value = $this->mOptions->getSpeculativeRevId();
					if ( $value ) {
						$this->mOutput->setSpeculativeRevIdUsed( $value );
					}
				}
				break;
			case 'revisionday':
				$value = (int)$this->getRevisionTimestampSubstring( 6, 2, self::MAX_TTS, $index );
				break;
			case 'revisionday2':
				$value = $this->getRevisionTimestampSubstring( 6, 2, self::MAX_TTS, $index );
				break;
			case 'revisionmonth':
				$value = $this->getRevisionTimestampSubstring( 4, 2, self::MAX_TTS, $index );
				break;
			case 'revisionmonth1':
				$value = (int)$this->getRevisionTimestampSubstring( 4, 2, self::MAX_TTS, $index );
				break;
			case 'revisionyear':
				$value = $this->getRevisionTimestampSubstring( 0, 4, self::MAX_TTS, $index );
				break;
			case 'revisiontimestamp':
				# Let the edit saving system know we should parse the page
				# *after* a revision ID has been assigned. This is for null edits.
				$this->mOutput->setFlag( 'vary-revision' );
				wfDebug( __METHOD__ . ": {{REVISIONTIMESTAMP}} used, setting vary-revision...\n" );
				$value = $this->getRevisionTimestamp();
				break;
			case 'revisionuser':
				# Let the edit saving system know we should parse the page
				# *after* a revision ID has been assigned for null edits.
				$this->mOutput->setFlag( 'vary-user' );
				wfDebug( __METHOD__ . ": {{REVISIONUSER}} used, setting vary-user...\n" );
				$value = $this->getRevisionUser();
				break;
			case 'revisionsize':
				$value = $this->getRevisionSize();
				break;
			case 'namespace':
				$value = str_replace( '_', ' ',
					$this->contLang->getNsText( $this->mTitle->getNamespace() ) );
				break;
			case 'namespacee':
				$value = wfUrlencode( $this->contLang->getNsText( $this->mTitle->getNamespace() ) );
				break;
			case 'namespacenumber':
				$value = $this->mTitle->getNamespace();
				break;
			case 'talkspace':
				$value = $this->mTitle->canHaveTalkPage()
					? str_replace( '_', ' ', $this->mTitle->getTalkNsText() )
					: '';
				break;
			case 'talkspacee':
				$value = $this->mTitle->canHaveTalkPage() ? wfUrlencode( $this->mTitle->getTalkNsText() ) : '';
				break;
			case 'subjectspace':
				$value = str_replace( '_', ' ', $this->mTitle->getSubjectNsText() );
				break;
			case 'subjectspacee':
				$value = ( wfUrlencode( $this->mTitle->getSubjectNsText() ) );
				break;
			case 'currentdayname':
				$value = $pageLang->getWeekdayName( (int)MWTimestamp::getInstance( $ts )->format( 'w' ) + 1 );
				break;
			case 'currentyear':
				$value = $pageLang->formatNum( MWTimestamp::getInstance( $ts )->format( 'Y' ), true );
				break;
			case 'currenttime':
				$value = $pageLang->time( wfTimestamp( TS_MW, $ts ), false, false );
				break;
			case 'currenthour':
				$value = $pageLang->formatNum( MWTimestamp::getInstance( $ts )->format( 'H' ), true );
				break;
			case 'currentweek':
				# @bug T6594 PHP5 has it zero padded, PHP4 does not, cast to
				# int to remove the padding
				$value = $pageLang->formatNum( (int)MWTimestamp::getInstance( $ts )->format( 'W' ) );
				break;
			case 'currentdow':
				$value = $pageLang->formatNum( MWTimestamp::getInstance( $ts )->format( 'w' ) );
				break;
			case 'localdayname':
				$value = $pageLang->getWeekdayName(
					(int)MWTimestamp::getLocalInstance( $ts )->format( 'w' ) + 1
				);
				break;
			case 'localyear':
				$value = $pageLang->formatNum( MWTimestamp::getLocalInstance( $ts )->format( 'Y' ), true );
				break;
			case 'localtime':
				$value = $pageLang->time(
					MWTimestamp::getLocalInstance( $ts )->format( 'YmdHis' ),
					false,
					false
				);
				break;
			case 'localhour':
				$value = $pageLang->formatNum( MWTimestamp::getLocalInstance( $ts )->format( 'H' ), true );
				break;
			case 'localweek':
				# @bug T6594 PHP5 has it zero padded, PHP4 does not, cast to
				# int to remove the padding
				$value = $pageLang->formatNum( (int)MWTimestamp::getLocalInstance( $ts )->format( 'W' ) );
				break;
			case 'localdow':
				$value = $pageLang->formatNum( MWTimestamp::getLocalInstance( $ts )->format( 'w' ) );
				break;
			case 'numberofarticles':
				$value = $pageLang->formatNum( SiteStats::articles() );
				break;
			case 'numberoffiles':
				$value = $pageLang->formatNum( SiteStats::images() );
				break;
			case 'numberofusers':
				$value = $pageLang->formatNum( SiteStats::users() );
				break;
			case 'numberofactiveusers':
				$value = $pageLang->formatNum( SiteStats::activeUsers() );
				break;
			case 'numberofpages':
				$value = $pageLang->formatNum( SiteStats::pages() );
				break;
			case 'numberofadmins':
				$value = $pageLang->formatNum( SiteStats::numberingroup( 'sysop' ) );
				break;
			case 'numberofedits':
				$value = $pageLang->formatNum( SiteStats::edits() );
				break;
			case 'currenttimestamp':
				$value = wfTimestamp( TS_MW, $ts );
				break;
			case 'localtimestamp':
				$value = MWTimestamp::getLocalInstance( $ts )->format( 'YmdHis' );
				break;
			case 'currentversion':
				$value = SpecialVersion::getVersion();
				break;
			case 'articlepath':
				return $this->siteConfig->get( 'ArticlePath' );
			case 'sitename':
				return $this->siteConfig->get( 'Sitename' );
			case 'server':
				return $this->siteConfig->get( 'Server' );
			case 'servername':
				return $this->siteConfig->get( 'ServerName' );
			case 'scriptpath':
				return $this->siteConfig->get( 'ScriptPath' );
			case 'stylepath':
				return $this->siteConfig->get( 'StylePath' );
			case 'directionmark':
				return $pageLang->getDirMark();
			case 'contentlanguage':
				return $this->siteConfig->get( 'LanguageCode' );
			case 'pagelanguage':
				$value = $pageLang->getCode();
				break;
			case 'cascadingsources':
				$value = CoreParserFunctions::cascadingsources( $this );
				break;
			default:
				$ret = null;
				Hooks::run(
					'ParserGetVariableValueSwitch',
					[ &$parser, &$this->mVarCache, &$index, &$ret, &$frame ]
				);

				return $ret;
		}

		if ( $index ) {
			$this->mVarCache[$index] = $value;
		}

		return $value;
	}

	/**
	 * @param int $start
	 * @param int $len
	 * @param int $mtts Max time-till-save; sets vary-revision if result might change by then
	 * @param string $variable Parser variable name
	 * @return string
	 */
	private function getRevisionTimestampSubstring( $start, $len, $mtts, $variable ) {
		# Get the timezone-adjusted timestamp to be used for this revision
		$resNow = substr( $this->getRevisionTimestamp(), $start, $len );
		# Possibly set vary-revision if there is not yet an associated revision
		if ( !$this->getRevisionObject() ) {
			# Get the timezone-adjusted timestamp $mtts seconds in the future
			$resThen = substr(
				$this->contLang->userAdjust( wfTimestamp( TS_MW, time() + $mtts ), '' ),
				$start,
				$len
			);

			if ( $resNow !== $resThen ) {
				# Let the edit saving system know we should parse the page
				# *after* a revision ID has been assigned. This is for null edits.
				$this->mOutput->setFlag( 'vary-revision' );
				wfDebug( __METHOD__ . ": $variable used, setting vary-revision...\n" );
			}
		}

		return $resNow;
	}

	/**
	 * initialise the magic variables (like CURRENTMONTHNAME) and substitution modifiers
	 *
	 * @private
	 */
	public function initialiseVariables() {
		$variableIDs = $this->magicWordFactory->getVariableIDs();
		$substIDs = $this->magicWordFactory->getSubstIDs();

		$this->mVariables = $this->magicWordFactory->newArray( $variableIDs );
		$this->mSubstWords = $this->magicWordFactory->newArray( $substIDs );
	}

	/**
	 * Preprocess some wikitext and return the document tree.
	 * This is the ghost of replace_variables().
	 *
	 * @param string $text The text to parse
	 * @param int $flags Bitwise combination of:
	 *   - self::PTD_FOR_INCLUSION: Handle "<noinclude>" and "<includeonly>" as if the text is being
	 *     included. Default is to assume a direct page view.
	 *
	 * The generated DOM tree must depend only on the input text and the flags.
	 * The DOM tree must be the same in OT_HTML and OT_WIKI mode, to avoid a regression of T6899.
	 *
	 * Any flag added to the $flags parameter here, or any other parameter liable to cause a
	 * change in the DOM tree for a given text, must be passed through the section identifier
	 * in the section edit link and thus back to extractSections().
	 *
	 * The output of this function is currently only cached in process memory, but a persistent
	 * cache may be implemented at a later date which takes further advantage of these strict
	 * dependency requirements.
	 *
	 * @return PPNode
	 */
	public function preprocessToDom( $text, $flags = 0 ) {
		$dom = $this->getPreprocessor()->preprocessToObj( $text, $flags );
		return $dom;
	}

	/**
	 * Return a three-element array: leading whitespace, string contents, trailing whitespace
	 *
	 * @param string $s
	 *
	 * @return array
	 */
	public static function splitWhitespace( $s ) {
		$ltrimmed = ltrim( $s );
		$w1 = substr( $s, 0, strlen( $s ) - strlen( $ltrimmed ) );
		$trimmed = rtrim( $ltrimmed );
		$diff = strlen( $ltrimmed ) - strlen( $trimmed );
		if ( $diff > 0 ) {
			$w2 = substr( $ltrimmed, -$diff );
		} else {
			$w2 = '';
		}
		return [ $w1, $trimmed, $w2 ];
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
	 * @param bool|PPFrame $frame Object describing the arguments passed to the
	 *   template. Arguments may also be provided as an associative array, as
	 *   was the usual case before MW1.12. Providing arguments this way may be
	 *   useful for extensions wishing to perform variable replacement
	 *   explicitly.
	 * @param bool $argsOnly Only do argument (triple-brace) expansion, not
	 *   double-brace expansion.
	 * @return string
	 */
	public function replaceVariables( $text, $frame = false, $argsOnly = false ) {
		# Is there any text? Also, Prevent too big inclusions!
		$textSize = strlen( $text );
		if ( $textSize < 1 || $textSize > $this->mOptions->getMaxIncludeSize() ) {
			return $text;
		}

		if ( $frame === false ) {
			$frame = $this->getPreprocessor()->newFrame();
		} elseif ( !( $frame instanceof PPFrame ) ) {
			wfDebug( __METHOD__ . " called using plain parameters instead of "
				. "a PPFrame instance. Creating custom frame.\n" );
			$frame = $this->getPreprocessor()->newCustomFrame( $frame );
		}

		$dom = $this->preprocessToDom( $text );
		$flags = $argsOnly ? PPFrame::NO_TEMPLATES : 0;
		$text = $frame->expand( $dom, $flags );

		return $text;
	}

	/**
	 * Clean up argument array - refactored in 1.9 so parserfunctions can use it, too.
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function createAssocArgs( $args ) {
		$assocArgs = [];
		$index = 1;
		foreach ( $args as $arg ) {
			$eqpos = strpos( $arg, '=' );
			if ( $eqpos === false ) {
				$assocArgs[$index++] = $arg;
			} else {
				$name = trim( substr( $arg, 0, $eqpos ) );
				$value = trim( substr( $arg, $eqpos + 1 ) );
				if ( $value === false ) {
					$value = '';
				}
				if ( $name !== false ) {
					$assocArgs[$name] = $value;
				}
			}
		}

		return $assocArgs;
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
	 */
	public function limitationWarn( $limitationType, $current = '', $max = '' ) {
		# does no harm if $current and $max are present but are unnecessary for the message
		# Not doing ->inLanguage( $this->mOptions->getUserLangObj() ), since this is shown
		# only during preview, and that would split the parser cache unnecessarily.
		$warning = wfMessage( "$limitationType-warning" )->numParams( $current, $max )
			->text();
		$this->mOutput->addWarning( $warning );
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
	 * @return string The text of the template
	 */
	public function braceSubstitution( $piece, $frame ) {
		// Flags

		// $text has been filled
		$found = false;
		// wiki markup in $text should be escaped
		$nowiki = false;
		// $text is HTML, armour it against wikitext transformation
		$isHTML = false;
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
		# @todo FIXME: If piece['parts'] is null then the call to getLength()
		# below won't work b/c this $args isn't an object
		$args = ( $piece['parts'] == null ) ? [] : $piece['parts'];

		$profileSection = null; // profile templates

		# SUBST
		if ( !$found ) {
			$substMatch = $this->mSubstWords->matchStartAndRemove( $part1 );

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
				$text = $this->getVariableValue( $id, $frame );
				if ( $this->magicWordFactory->getCacheTTL( $id ) > -1 ) {
					$this->mOutput->updateCacheExpiry(
						$this->magicWordFactory->getCacheTTL( $id ) );
				}
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

				$result = $this->callParserFunction( $frame, $func, $funcArgs );

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
			$relative = $this->maybeDoSubpageLink( $part1, $subpage );
			if ( $part1 !== $relative ) {
				$part1 = $relative;
				$ns = $this->mTitle->getNamespace();
			}
			$title = Title::newFromText( $part1, $ns );
			if ( $title ) {
				$titleText = $title->getPrefixedText();
				# Check for language variants if the template is not found
				if ( $this->getTargetLanguage()->hasVariants() && $title->getArticleID() == 0 ) {
					$this->getTargetLanguage()->findVariantLink( $part1, $title, true );
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
					&& $this->ot['html']
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

					// Create a new context to execute the special page
					$context = new RequestContext;
					$context->setTitle( $title );
					$context->setRequest( new FauxRequest( $pageArgs ) );
					if ( $specialPage && $specialPage->maxIncludeCacheTime() === 0 ) {
						$context->setUser( $this->getUser() );
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
				} elseif ( MWNamespace::isNonincludable( $title->getNamespace() ) ) {
					$found = false; # access denied
					wfDebug( __METHOD__ . ": template inclusion denied for " .
						$title->getPrefixedDBkey() . "\n" );
				} else {
					list( $text, $title ) = $this->getTemplateDom( $title );
					if ( $text !== false ) {
						$found = true;
						$isChildObj = true;
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
					$text = $this->preprocessToDom( $text, self::PTD_FOR_INCLUSION );
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
				$this->mOutput->addWarning( wfMessage( 'template-loop-warning',
					wfEscapeWikiText( $titleText ) )->text() );
				wfDebug( __METHOD__ . ": template loop broken at '$titleText'\n" );
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

		# Replace raw HTML by a placeholder
		if ( $isHTML ) {
			$text = $this->insertStripItem( $text );
		} elseif ( $nowiki && ( $this->ot['html'] || $this->ot['pre'] ) ) {
			# Escape nowiki-style return values
			$text = wfEscapeWikiText( $text );
		} elseif ( is_string( $text )
			&& !$piece['lineStart']
			&& preg_match( '/^(?:{\\||:|;|#|\*)/', $text )
		) {
			# T2529: if the template begins with a table or block-level
			# element, it should be treated as beginning a new line.
			# This behavior is somewhat controversial.
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
				preg_replace( '/^:/', '', $originalTitle );
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
	 *  isHTML: bool, $text is HTML, armour it against wikitext transformation
	 *  isChildObj: bool, $text is a DOM node needing expansion in a child frame
	 *  isLocalObj: bool, $text is a DOM node needing expansion in the current frame
	 *  nowiki: bool, wiki markup in $text should be escaped
	 *
	 * @since 1.21
	 * @param PPFrame $frame The current frame, contains template arguments
	 * @param string $function Function name
	 * @param array $args Arguments to the function
	 * @throws MWException
	 * @return array
	 */
	public function callParserFunction( $frame, $function, array $args = [] ) {
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

		list( $callback, $flags ) = $this->mFunctionHooks[$function];

		// Avoid PHP 7.1 warning from passing $this by reference
		$parser = $this;

		$allArgs = [ &$parser ];
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

		$noparse = true;
		$preprocessFlags = 0;
		if ( isset( $result['noparse'] ) ) {
			$noparse = $result['noparse'];
		}
		if ( isset( $result['preprocessFlags'] ) ) {
			$preprocessFlags = $result['preprocessFlags'];
		}

		if ( !$noparse ) {
			$result['text'] = $this->preprocessToDom( $result['text'], $preprocessFlags );
			$result['isChildObj'] = true;
		}

		return $result;
	}

	/**
	 * Get the semi-parsed DOM representation of a template with a given title,
	 * and its redirect destination title. Cached.
	 *
	 * @param Title $title
	 *
	 * @return array
	 */
	public function getTemplateDom( $title ) {
		$cacheTitle = $title;
		$titleText = $title->getPrefixedDBkey();

		if ( isset( $this->mTplRedirCache[$titleText] ) ) {
			list( $ns, $dbk ) = $this->mTplRedirCache[$titleText];
			$title = Title::makeTitle( $ns, $dbk );
			$titleText = $title->getPrefixedDBkey();
		}
		if ( isset( $this->mTplDomCache[$titleText] ) ) {
			return [ $this->mTplDomCache[$titleText], $title ];
		}

		# Cache miss, go to the database
		list( $text, $title ) = $this->fetchTemplateAndTitle( $title );

		if ( $text === false ) {
			$this->mTplDomCache[$titleText] = false;
			return [ false, $title ];
		}

		$dom = $this->preprocessToDom( $text, self::PTD_FOR_INCLUSION );
		$this->mTplDomCache[$titleText] = $dom;

		if ( !$title->equals( $cacheTitle ) ) {
			$this->mTplRedirCache[$cacheTitle->getPrefixedDBkey()] =
				[ $title->getNamespace(), $title->getDBkey() ];
		}

		return [ $dom, $title ];
	}

	/**
	 * Fetch the current revision of a given title. Note that the revision
	 * (and even the title) may not exist in the database, so everything
	 * contributing to the output of the parser should use this method
	 * where possible, rather than getting the revisions themselves. This
	 * method also caches its results, so using it benefits performance.
	 *
	 * @since 1.24
	 * @param Title $title
	 * @return Revision
	 */
	public function fetchCurrentRevisionOfTitle( $title ) {
		$cacheKey = $title->getPrefixedDBkey();
		if ( !$this->currentRevisionCache ) {
			$this->currentRevisionCache = new MapCacheLRU( 100 );
		}
		if ( !$this->currentRevisionCache->has( $cacheKey ) ) {
			$this->currentRevisionCache->set( $cacheKey,
				// Defaults to Parser::statelessFetchRevision()
				call_user_func( $this->mOptions->getCurrentRevisionCallback(), $title, $this )
			);
		}
		return $this->currentRevisionCache->get( $cacheKey );
	}

	/**
	 * Wrapper around Revision::newFromTitle to allow passing additional parameters
	 * without passing them on to it.
	 *
	 * @since 1.24
	 * @param Title $title
	 * @param Parser|bool $parser
	 * @return Revision|bool False if missing
	 */
	public static function statelessFetchRevision( Title $title, $parser = false ) {
		$rev = Revision::newKnownCurrent( wfGetDB( DB_REPLICA ), $title );

		return $rev;
	}

	/**
	 * Fetch the unparsed text of a template and register a reference to it.
	 * @param Title $title
	 * @return array ( string or false, Title )
	 */
	public function fetchTemplateAndTitle( $title ) {
		// Defaults to Parser::statelessFetchTemplate()
		$templateCb = $this->mOptions->getTemplateCallback();
		$stuff = call_user_func( $templateCb, $title, $this );
		// We use U+007F DELETE to distinguish strip markers from regular text.
		$text = $stuff['text'];
		if ( is_string( $stuff['text'] ) ) {
			$text = strtr( $text, "\x7f", "?" );
		}
		$finalTitle = $stuff['finalTitle'] ?? $title;
		if ( isset( $stuff['deps'] ) ) {
			foreach ( $stuff['deps'] as $dep ) {
				$this->mOutput->addTemplate( $dep['title'], $dep['page_id'], $dep['rev_id'] );
				if ( $dep['title']->equals( $this->getTitle() ) ) {
					// If we transclude ourselves, the final result
					// will change based on the new version of the page
					$this->mOutput->setFlag( 'vary-revision' );
				}
			}
		}
		return [ $text, $finalTitle ];
	}

	/**
	 * Fetch the unparsed text of a template and register a reference to it.
	 * @param Title $title
	 * @return string|bool
	 */
	public function fetchTemplate( $title ) {
		return $this->fetchTemplateAndTitle( $title )[0];
	}

	/**
	 * Static function to get a template
	 * Can be overridden via ParserOptions::setTemplateCallback().
	 *
	 * @param Title $title
	 * @param bool|Parser $parser
	 *
	 * @return array
	 */
	public static function statelessFetchTemplate( $title, $parser = false ) {
		$text = $skip = false;
		$finalTitle = $title;
		$deps = [];

		# Loop to fetch the article, with up to 1 redirect
		for ( $i = 0; $i < 2 && is_object( $title ); $i++ ) {
			# Give extensions a chance to select the revision instead
			$id = false; # Assume current
			Hooks::run( 'BeforeParserFetchTemplateAndtitle',
				[ $parser, $title, &$skip, &$id ] );

			if ( $skip ) {
				$text = false;
				$deps[] = [
					'title' => $title,
					'page_id' => $title->getArticleID(),
					'rev_id' => null
				];
				break;
			}
			# Get the revision
			if ( $id ) {
				$rev = Revision::newFromId( $id );
			} elseif ( $parser ) {
				$rev = $parser->fetchCurrentRevisionOfTitle( $title );
			} else {
				$rev = Revision::newFromTitle( $title );
			}
			$rev_id = $rev ? $rev->getId() : 0;
			# If there is no current revision, there is no page
			if ( $id === false && !$rev ) {
				$linkCache = MediaWikiServices::getInstance()->getLinkCache();
				$linkCache->addBadLinkObj( $title );
			}

			$deps[] = [
				'title' => $title,
				'page_id' => $title->getArticleID(),
				'rev_id' => $rev_id ];
			if ( $rev && !$title->equals( $rev->getTitle() ) ) {
				# We fetched a rev from a different title; register it too...
				$deps[] = [
					'title' => $rev->getTitle(),
					'page_id' => $rev->getPage(),
					'rev_id' => $rev_id ];
			}

			if ( $rev ) {
				$content = $rev->getContent();
				$text = $content ? $content->getWikitextForTransclusion() : null;

				Hooks::run( 'ParserFetchTemplate',
					[ $parser, $title, $rev, &$text, &$deps ] );

				if ( $text === false || $text === null ) {
					$text = false;
					break;
				}
			} elseif ( $title->getNamespace() == NS_MEDIAWIKI ) {
				$message = wfMessage( MediaWikiServices::getInstance()->getContentLanguage()->
					lcfirst( $title->getText() ) )->inContentLanguage();
				if ( !$message->exists() ) {
					$text = false;
					break;
				}
				$content = $message->content();
				$text = $message->plain();
			} else {
				break;
			}
			if ( !$content ) {
				break;
			}
			# Redirect?
			$finalTitle = $title;
			$title = $content->getRedirectTarget();
		}
		return [
			'text' => $text,
			'finalTitle' => $finalTitle,
			'deps' => $deps ];
	}

	/**
	 * Fetch a file and its title and register a reference to it.
	 * If 'broken' is a key in $options then the file will appear as a broken thumbnail.
	 * @param Title $title
	 * @param array $options Array of options to RepoGroup::findFile
	 * @return File|bool
	 * @deprecated since 1.32, use fetchFileAndTitle instead
	 */
	public function fetchFile( $title, $options = [] ) {
		wfDeprecated( __METHOD__, '1.32' );
		return $this->fetchFileAndTitle( $title, $options )[0];
	}

	/**
	 * Fetch a file and its title and register a reference to it.
	 * If 'broken' is a key in $options then the file will appear as a broken thumbnail.
	 * @param Title $title
	 * @param array $options Array of options to RepoGroup::findFile
	 * @return array ( File or false, Title of file )
	 */
	public function fetchFileAndTitle( $title, $options = [] ) {
		$file = $this->fetchFileNoRegister( $title, $options );

		$time = $file ? $file->getTimestamp() : false;
		$sha1 = $file ? $file->getSha1() : false;
		# Register the file as a dependency...
		$this->mOutput->addImage( $title->getDBkey(), $time, $sha1 );
		if ( $file && !$title->equals( $file->getTitle() ) ) {
			# Update fetched file title
			$title = $file->getTitle();
			$this->mOutput->addImage( $title->getDBkey(), $time, $sha1 );
		}
		return [ $file, $title ];
	}

	/**
	 * Helper function for fetchFileAndTitle.
	 *
	 * Also useful if you need to fetch a file but not use it yet,
	 * for example to get the file's handler.
	 *
	 * @param Title $title
	 * @param array $options Array of options to RepoGroup::findFile
	 * @return File|bool
	 */
	protected function fetchFileNoRegister( $title, $options = [] ) {
		if ( isset( $options['broken'] ) ) {
			$file = false; // broken thumbnail forced by hook
		} elseif ( isset( $options['sha1'] ) ) { // get by (sha1,timestamp)
			$file = RepoGroup::singleton()->findFileFromKey( $options['sha1'], $options );
		} else { // get by (name,timestamp)
			$file = wfFindFile( $title, $options );
		}
		return $file;
	}

	/**
	 * Transclude an interwiki link.
	 *
	 * @param Title $title
	 * @param string $action Usually one of (raw, render)
	 *
	 * @return string
	 */
	public function interwikiTransclude( $title, $action ) {
		if ( !$this->siteConfig->get( 'EnableScaryTranscluding' ) ) {
			return wfMessage( 'scarytranscludedisabled' )->inContentLanguage()->text();
		}

		$url = $title->getFullURL( [ 'action' => $action ] );
		if ( strlen( $url ) > 1024 ) {
			return wfMessage( 'scarytranscludetoolong' )->inContentLanguage()->text();
		}

		$wikiId = $title->getTransWikiID(); // remote wiki ID or false

		$fname = __METHOD__;
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();

		$data = $cache->getWithSetCallback(
			$cache->makeGlobalKey(
				'interwiki-transclude',
				( $wikiId !== false ) ? $wikiId : 'external',
				sha1( $url )
			),
			$this->siteConfig->get( 'TranscludeCacheExpiry' ),
			function ( $oldValue, &$ttl ) use ( $url, $fname, $cache ) {
				$req = MWHttpRequest::factory( $url, [], $fname );

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
	 * @private
	 *
	 * @param array $piece
	 * @param PPFrame $frame
	 *
	 * @return array
	 */
	public function argSubstitution( $piece, $frame ) {
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
	 *
	 * @throws MWException
	 * @return string
	 */
	public function extensionSubstitution( $params, $frame ) {
		static $errorStr = '<span class="error">';
		static $errorLen = 20;

		$name = $frame->expand( $params['name'] );
		if ( substr( $name, 0, $errorLen ) === $errorStr ) {
			// Probably expansion depth or node count exceeded. Just punt the
			// error up.
			return $name;
		}

		$attrText = !isset( $params['attr'] ) ? null : $frame->expand( $params['attr'] );
		if ( substr( $attrText, 0, $errorLen ) === $errorStr ) {
			// See above
			return $attrText;
		}

		// We can't safely check if the expansion for $content resulted in an
		// error, because the content could happen to be the error string
		// (T149622).
		$content = !isset( $params['inner'] ) ? null : $frame->expand( $params['inner'] );

		$marker = self::MARKER_PREFIX . "-$name-"
			. sprintf( '%08X', $this->mMarkerIndex++ ) . self::MARKER_SUFFIX;

		$isFunctionTag = isset( $this->mFunctionTagHooks[strtolower( $name )] ) &&
			( $this->ot['html'] || $this->ot['pre'] );
		if ( $isFunctionTag ) {
			$markerType = 'none';
		} else {
			$markerType = 'general';
		}
		if ( $this->ot['html'] || $isFunctionTag ) {
			$name = strtolower( $name );
			$attributes = Sanitizer::decodeTagAttributes( $attrText );
			if ( isset( $params['attributes'] ) ) {
				$attributes += $params['attributes'];
			}

			if ( isset( $this->mTagHooks[$name] ) ) {
				$output = call_user_func_array( $this->mTagHooks[$name],
					[ $content, $attributes, $this, $frame ] );
			} elseif ( isset( $this->mFunctionTagHooks[$name] ) ) {
				list( $callback, ) = $this->mFunctionTagHooks[$name];

				// Avoid PHP 7.1 warning from passing $this by reference
				$parser = $this;
				$output = call_user_func_array( $callback, [ &$parser, $frame, $content, $attributes ] );
			} else {
				$output = '<span class="error">Invalid tag extension name: ' .
					htmlspecialchars( $name ) . '</span>';
			}

			if ( is_array( $output ) ) {
				// Extract flags
				$flags = $output;
				$output = $flags[0];
				if ( isset( $flags['markerType'] ) ) {
					$markerType = $flags['markerType'];
				}
			}
		} else {
			if ( is_null( $attrText ) ) {
				$attrText = '';
			}
			if ( isset( $params['attributes'] ) ) {
				foreach ( $params['attributes'] as $attrName => $attrValue ) {
					$attrText .= ' ' . htmlspecialchars( $attrName ) . '="' .
						htmlspecialchars( $attrValue ) . '"';
				}
			}
			if ( $content === null ) {
				$output = "<$name$attrText/>";
			} else {
				$close = is_null( $params['close'] ) ? '' : $frame->expand( $params['close'] );
				if ( substr( $close, 0, $errorLen ) === $errorStr ) {
					// See above
					return $close;
				}
				$output = "<$name$attrText>$content$close";
			}
		}

		if ( $markerType === 'none' ) {
			return $output;
		} elseif ( $markerType === 'nowiki' ) {
			$this->mStripState->addNoWiki( $marker, $output );
		} elseif ( $markerType === 'general' ) {
			$this->mStripState->addGeneral( $marker, $output );
		} else {
			throw new MWException( __METHOD__ . ': invalid marker type' );
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
	public function incrementIncludeSize( $type, $size ) {
		if ( $this->mIncludeSizes[$type] + $size > $this->mOptions->getMaxIncludeSize() ) {
			return false;
		} else {
			$this->mIncludeSizes[$type] += $size;
			return true;
		}
	}

	/**
	 * Increment the expensive function count
	 *
	 * @return bool False if the limit has been exceeded
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
	 *
	 * @return string
	 */
	public function doDoubleUnderscore( $text ) {
		# The position of __TOC__ needs to be recorded
		$mw = $this->magicWordFactory->get( 'toc' );
		if ( $mw->match( $text ) ) {
			$this->mShowToc = true;
			$this->mForceTocPosition = true;

			# Set a placeholder. At the end we'll fill it in with the TOC.
			$text = $mw->replace( '<!--MWTOC\'"-->', $text, 1 );

			# Only keep the first one.
			$text = $mw->replace( '', $text );
		}

		# Now match and remove the rest of them
		$mwa = $this->magicWordFactory->getDoubleUnderscoreArray();
		$this->mDoubleUnderscores = $mwa->matchAndRemove( $text );

		if ( isset( $this->mDoubleUnderscores['nogallery'] ) ) {
			$this->mOutput->mNoGallery = true;
		}
		if ( isset( $this->mDoubleUnderscores['notoc'] ) && !$this->mForceTocPosition ) {
			$this->mShowToc = false;
		}
		if ( isset( $this->mDoubleUnderscores['hiddencat'] )
			&& $this->mTitle->getNamespace() == NS_CATEGORY
		) {
			$this->addTrackingCategory( 'hidden-category-category' );
		}
		# (T10068) Allow control over whether robots index a page.
		# __INDEX__ always overrides __NOINDEX__, see T16899
		if ( isset( $this->mDoubleUnderscores['noindex'] ) && $this->mTitle->canUseNoindex() ) {
			$this->mOutput->setIndexPolicy( 'noindex' );
			$this->addTrackingCategory( 'noindex-category' );
		}
		if ( isset( $this->mDoubleUnderscores['index'] ) && $this->mTitle->canUseNoindex() ) {
			$this->mOutput->setIndexPolicy( 'index' );
			$this->addTrackingCategory( 'index-category' );
		}

		# Cache all double underscores in the database
		foreach ( $this->mDoubleUnderscores as $key => $val ) {
			$this->mOutput->setProperty( $key, '' );
		}

		return $text;
	}

	/**
	 * @see ParserOutput::addTrackingCategory()
	 * @param string $msg Message key
	 * @return bool Whether the addition was successful
	 */
	public function addTrackingCategory( $msg ) {
		return $this->mOutput->addTrackingCategory( $msg, $this->mTitle );
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
	 * @return mixed|string
	 * @private
	 */
	public function formatHeadings( $text, $origText, $isMain = true ) {
		# Inhibit editsection links if requested in the page
		if ( isset( $this->mDoubleUnderscores['noeditsection'] ) ) {
			$maybeShowEditLink = false;
		} else {
			$maybeShowEditLink = true; /* Actual presence will depend on post-cache transforms */
		}

		# Get all headlines for numbering them and adding funky stuff like [edit]
		# links - this is for later, but we need the number of headlines right now
		# NOTE: white space in headings have been trimmed in doHeadings. They shouldn't
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
			$this->mOutput->hideNewSection( true );
		}

		# if the string __FORCETOC__ (not case-sensitive) occurs in the HTML,
		# override above conditions and always show TOC above first header
		if ( isset( $this->mDoubleUnderscores['forcetoc'] ) ) {
			$this->mShowToc = true;
			$enoughToc = true;
		}

		# headline counter
		$headlineCount = 0;
		$numVisible = 0;

		# Ugh .. the TOC should have neat indentation levels which can be
		# passed to the skin functions. These are determined here
		$toc = '';
		$full = '';
		$head = [];
		$sublevelCount = [];
		$levelCount = [];
		$level = 0;
		$prevlevel = 0;
		$toclevel = 0;
		$prevtoclevel = 0;
		$markerRegex = self::MARKER_PREFIX . "-h-(\d+)-" . self::MARKER_SUFFIX;
		$baseTitleText = $this->mTitle->getPrefixedDBkey();
		$oldType = $this->mOutputType;
		$this->setOutputType( self::OT_WIKI );
		$frame = $this->getPreprocessor()->newFrame();
		$root = $this->preprocessToDom( $origText );
		$node = $root->getFirstChild();
		$byteOffset = 0;
		$tocraw = [];
		$refers = [];

		$headlines = $numMatches !== false ? $matches[3] : [];

		$maxTocLevel = $this->siteConfig->get( 'MaxTocLevel' );
		foreach ( $headlines as $headline ) {
			$isTemplate = false;
			$titleText = false;
			$sectionIndex = false;
			$numbering = '';
			$markerMatches = [];
			if ( preg_match( "/^$markerRegex/", $headline, $markerMatches ) ) {
				$serial = $markerMatches[1];
				list( $titleText, $sectionIndex ) = $this->mHeadings[$serial];
				$isTemplate = ( $titleText != $baseTitleText );
				$headline = preg_replace( "/^$markerRegex\\s*/", "", $headline );
			}

			if ( $toclevel ) {
				$prevlevel = $level;
			}
			$level = $matches[1][$headlineCount];

			if ( $level > $prevlevel ) {
				# Increase TOC level
				$toclevel++;
				$sublevelCount[$toclevel] = 0;
				if ( $toclevel < $maxTocLevel ) {
					$prevtoclevel = $toclevel;
					$toc .= Linker::tocIndent();
					$numVisible++;
				}
			} elseif ( $level < $prevlevel && $toclevel > 1 ) {
				# Decrease TOC level, find level to jump to

				for ( $i = $toclevel; $i > 0; $i-- ) {
					if ( $levelCount[$i] == $level ) {
						# Found last matching level
						$toclevel = $i;
						break;
					} elseif ( $levelCount[$i] < $level ) {
						# Found first matching level below current level
						$toclevel = $i + 1;
						break;
					}
				}
				if ( $i == 0 ) {
					$toclevel = 1;
				}
				if ( $toclevel < $maxTocLevel ) {
					if ( $prevtoclevel < $maxTocLevel ) {
						# Unindent only if the previous toc level was shown :p
						$toc .= Linker::tocUnindent( $prevtoclevel - $toclevel );
						$prevtoclevel = $toclevel;
					} else {
						$toc .= Linker::tocLineEnd();
					}
				}
			} else {
				# No change in level, end TOC line
				if ( $toclevel < $maxTocLevel ) {
					$toc .= Linker::tocLineEnd();
				}
			}

			$levelCount[$toclevel] = $level;

			# count number of headlines for each level
			$sublevelCount[$toclevel]++;
			$dot = 0;
			for ( $i = 1; $i <= $toclevel; $i++ ) {
				if ( !empty( $sublevelCount[$i] ) ) {
					if ( $dot ) {
						$numbering .= '.';
					}
					$numbering .= $this->getTargetLanguage()->formatNum( $sublevelCount[$i] );
					$dot = 1;
				}
			}

			# The safe header is a version of the header text safe to use for links

			# Remove link placeholders by the link text.
			#     <!--LINK number-->
			# turns into
			#     link text with suffix
			# Do this before unstrip since link text can contain strip markers
			$safeHeadline = $this->replaceLinkHoldersText( $headline );

			# Avoid insertion of weird stuff like <math> by expanding the relevant sections
			$safeHeadline = $this->mStripState->unstripBoth( $safeHeadline );

			# Remove any <style> or <script> tags (T198618)
			$safeHeadline = preg_replace(
				'#<(style|script)(?: [^>]*[^>/])?>.*?</\1>#is',
				'',
				$safeHeadline
			);

			# Strip out HTML (first regex removes any tag not allowed)
			# Allowed tags are:
			# * <sup> and <sub> (T10393)
			# * <i> (T28375)
			# * <b> (r105284)
			# * <bdi> (T74884)
			# * <span dir="rtl"> and <span dir="ltr"> (T37167)
			# * <s> and <strike> (T35715)
			# We strip any parameter from accepted tags (second regex), except dir="rtl|ltr" from <span>,
			# to allow setting directionality in toc items.
			$tocline = preg_replace(
				[
					'#<(?!/?(span|sup|sub|bdi|i|b|s|strike)(?: [^>]*)?>).*?>#',
					'#<(/?(?:span(?: dir="(?:rtl|ltr)")?|sup|sub|bdi|i|b|s|strike))(?: .*?)?>#'
				],
				[ '', '<$1>' ],
				$safeHeadline
			);

			# Strip '<span></span>', which is the result from the above if
			# <span id="foo"></span> is used to produce an additional anchor
			# for a section.
			$tocline = str_replace( '<span></span>', '', $tocline );

			$tocline = trim( $tocline );

			# For the anchor, strip out HTML-y stuff period
			$safeHeadline = preg_replace( '/<.*?>/', '', $safeHeadline );
			$safeHeadline = Sanitizer::normalizeSectionNameWhitespace( $safeHeadline );

			# Save headline for section edit hint before it's escaped
			$headlineHint = $safeHeadline;

			# Decode HTML entities
			$safeHeadline = Sanitizer::decodeCharReferences( $safeHeadline );

			$safeHeadline = self::normalizeSectionName( $safeHeadline );

			$fallbackHeadline = Sanitizer::escapeIdForAttribute( $safeHeadline, Sanitizer::ID_FALLBACK );
			$linkAnchor = Sanitizer::escapeIdForLink( $safeHeadline );
			$safeHeadline = Sanitizer::escapeIdForAttribute( $safeHeadline, Sanitizer::ID_PRIMARY );
			if ( $fallbackHeadline === $safeHeadline ) {
				# No reason to have both (in fact, we can't)
				$fallbackHeadline = false;
			}

			# HTML IDs must be case-insensitively unique for IE compatibility (T12721).
			# @todo FIXME: We may be changing them depending on the current locale.
			$arrayKey = strtolower( $safeHeadline );
			if ( $fallbackHeadline === false ) {
				$fallbackArrayKey = false;
			} else {
				$fallbackArrayKey = strtolower( $fallbackHeadline );
			}

			# Create the anchor for linking from the TOC to the section
			$anchor = $safeHeadline;
			$fallbackAnchor = $fallbackHeadline;
			if ( isset( $refers[$arrayKey] ) ) {
				// phpcs:ignore Generic.Formatting.DisallowMultipleStatements
				for ( $i = 2; isset( $refers["${arrayKey}_$i"] ); ++$i );
				$anchor .= "_$i";
				$linkAnchor .= "_$i";
				$refers["${arrayKey}_$i"] = true;
			} else {
				$refers[$arrayKey] = true;
			}
			if ( $fallbackHeadline !== false && isset( $refers[$fallbackArrayKey] ) ) {
				// phpcs:ignore Generic.Formatting.DisallowMultipleStatements
				for ( $i = 2; isset( $refers["${fallbackArrayKey}_$i"] ); ++$i );
				$fallbackAnchor .= "_$i";
				$refers["${fallbackArrayKey}_$i"] = true;
			} else {
				$refers[$fallbackArrayKey] = true;
			}

			# Don't number the heading if it is the only one (looks silly)
			if ( count( $matches[3] ) > 1 && $this->mOptions->getNumberHeadings() ) {
				# the two are different if the line contains a link
				$headline = Html::element(
					'span',
					[ 'class' => 'mw-headline-number' ],
					$numbering
				) . ' ' . $headline;
			}

			if ( $enoughToc && ( !isset( $maxTocLevel ) || $toclevel < $maxTocLevel ) ) {
				$toc .= Linker::tocLine( $linkAnchor, $tocline,
					$numbering, $toclevel, ( $isTemplate ? false : $sectionIndex ) );
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
				$byteOffset += mb_strlen( $this->mStripState->unstripBoth(
					$frame->expand( $node, PPFrame::RECOVER_ORIG ) ) );
				$node = $node->getNextSibling();
			}
			$tocraw[] = [
				'toclevel' => $toclevel,
				'level' => $level,
				'line' => $tocline,
				'number' => $numbering,
				'index' => ( $isTemplate ? 'T-' : '' ) . $sectionIndex,
				'fromtitle' => $titleText,
				'byteoffset' => ( $noOffset ? null : $byteOffset ),
				'anchor' => $anchor,
			];

			# give headline the correct <h#> tag
			if ( $maybeShowEditLink && $sectionIndex !== false ) {
				// Output edit section links as markers with styles that can be customized by skins
				if ( $isTemplate ) {
					# Put a T flag in the section identifier, to indicate to extractSections()
					# that sections inside <includeonly> should be counted.
					$editsectionPage = $titleText;
					$editsectionSection = "T-$sectionIndex";
					$editsectionContent = null;
				} else {
					$editsectionPage = $this->mTitle->getPrefixedText();
					$editsectionSection = $sectionIndex;
					$editsectionContent = $headlineHint;
				}
				// We use a bit of pesudo-xml for editsection markers. The
				// language converter is run later on. Using a UNIQ style marker
				// leads to the converter screwing up the tokens when it
				// converts stuff. And trying to insert strip tags fails too. At
				// this point all real inputted tags have already been escaped,
				// so we don't have to worry about a user trying to input one of
				// these markers directly. We use a page and section attribute
				// to stop the language converter from converting these
				// important bits of data, but put the headline hint inside a
				// content block because the language converter is supposed to
				// be able to convert that piece of data.
				// Gets replaced with html in ParserOutput::getText
				$editlink = '<mw:editsection page="' . htmlspecialchars( $editsectionPage );
				$editlink .= '" section="' . htmlspecialchars( $editsectionSection ) . '"';
				if ( $editsectionContent !== null ) {
					$editlink .= '>' . $editsectionContent . '</mw:editsection>';
				} else {
					$editlink .= '/>';
				}
			} else {
				$editlink = '';
			}
			$head[$headlineCount] = Linker::makeHeadline( $level,
				$matches['attrib'][$headlineCount], $anchor, $headline,
				$editlink, $fallbackAnchor );

			$headlineCount++;
		}

		$this->setOutputType( $oldType );

		# Never ever show TOC if no headers
		if ( $numVisible < 1 ) {
			$enoughToc = false;
		}

		if ( $enoughToc ) {
			if ( $prevtoclevel > 0 && $prevtoclevel < $maxTocLevel ) {
				$toc .= Linker::tocUnindent( $prevtoclevel - 1 );
			}
			$toc = Linker::tocList( $toc, $this->mOptions->getUserLangObj() );
			$this->mOutput->setTOCHTML( $toc );
			$toc = self::TOC_START . $toc . self::TOC_END;
		}

		if ( $isMain ) {
			$this->mOutput->setSections( $tocraw );
		}

		# split up and insert constructed headlines
		$blocks = preg_split( '/<H[1-6].*?>[\s\S]*?<\/H[1-6]>/i', $text );
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

			/**
			 * Send a hook, one per section.
			 * The idea here is to be able to make section-level DIVs, but to do so in a
			 * lower-impact, more correct way than r50769
			 *
			 * $this : caller
			 * $section : the section number
			 * &$sectionContent : ref to the content of the section
			 * $maybeShowEditLinks : boolean describing whether this section has an edit link
			 */
			Hooks::run( 'ParserSectionCreate', [ $this, $i, &$sections[$i], $maybeShowEditLink ] );

			$i++;
		}

		if ( $enoughToc && $isMain && !$this->mForceTocPosition ) {
			// append the TOC at the beginning
			// Top anchor now in skin
			$sections[0] .= $toc . "\n";
		}

		$full .= implode( '', $sections );

		if ( $this->mForceTocPosition ) {
			return str_replace( '<!--MWTOC\'"-->', $toc, $full );
		} else {
			return $full;
		}
	}

	/**
	 * Transform wiki markup when saving a page by doing "\r\n" -> "\n"
	 * conversion, substituting signatures, {{subst:}} templates, etc.
	 *
	 * @param string $text The text to transform
	 * @param Title $title The Title object for the current article
	 * @param User $user The User object describing the current user
	 * @param ParserOptions $options Parsing options
	 * @param bool $clearState Whether to clear the parser state first
	 * @return string The altered wiki markup
	 */
	public function preSaveTransform( $text, Title $title, User $user,
		ParserOptions $options, $clearState = true
	) {
		if ( $clearState ) {
			$magicScopeVariable = $this->lock();
		}
		$this->startParse( $title, $options, self::OT_WIKI, $clearState );
		$this->setUser( $user );

		// Strip U+0000 NULL (T159174)
		$text = str_replace( "\000", '', $text );

		// We still normalize line endings for backwards-compatibility
		// with other code that just calls PST, but this should already
		// be handled in TextContent subclasses
		$text = TextContent::normalizeLineEndings( $text );

		if ( $options->getPreSaveTransform() ) {
			$text = $this->pstPass2( $text, $user );
		}
		$text = $this->mStripState->unstripBoth( $text );

		$this->setUser( null ); # Reset

		return $text;
	}

	/**
	 * Pre-save transform helper function
	 *
	 * @param string $text
	 * @param User $user
	 *
	 * @return string
	 */
	private function pstPass2( $text, $user ) {
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
			$this->mOutput->setFlag( 'user-signature' );
		}

		# Context links ("pipe tricks"): [[|name]] and [[name (context)|]]
		$tc = '[' . Title::legalChars() . ']';
		$nc = '[ _0-9A-Za-z\x80-\xff-]'; # Namespaces can use non-ascii!

		// [[ns:page (context)|]]
		$p1 = "/\[\[(:?$nc+:|:|)($tc+?)( ?\\($tc+\\))\\|]]/";
		// [[ns:page（context）|]] (double-width brackets, added in r40257)
		$p4 = "/\[\[(:?$nc+:|:|)($tc+?)( ?（$tc+）)\\|]]/";
		// [[ns:page (context), context|]] (using either single or double-width comma)
		$p3 = "/\[\[(:?$nc+:|:|)($tc+?)( ?\\($tc+\\)|)((?:, |，)$tc+|)\\|]]/";
		// [[|page]] (reverse pipe trick: add context from page title)
		$p2 = "/\[\[\\|($tc+)]]/";

		# try $p1 first, to turn "[[A, B (C)|]]" into "[[A, B (C)|A, B]]"
		$text = preg_replace( $p1, '[[\\1\\2\\3|\\2]]', $text );
		$text = preg_replace( $p4, '[[\\1\\2\\3|\\2]]', $text );
		$text = preg_replace( $p3, '[[\\1\\2\\3\\4|\\2]]', $text );

		$t = $this->mTitle->getText();
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
	 * as it may have changed if it's the $wgParser.
	 *
	 * @param User &$user
	 * @param string|bool $nickname Nickname to use or false to use user's default nickname
	 * @param bool|null $fancySig whether the nicknname is the complete signature
	 *    or null to use default value
	 * @return string
	 */
	public function getUserSig( &$user, $nickname = false, $fancySig = null ) {
		$username = $user->getName();

		# If not given, retrieve from the user object.
		if ( $nickname === false ) {
			$nickname = $user->getOption( 'nickname' );
		}

		if ( is_null( $fancySig ) ) {
			$fancySig = $user->getBoolOption( 'fancysig' );
		}

		$nickname = $nickname == null ? $username : $nickname;

		if ( mb_strlen( $nickname ) > $this->siteConfig->get( 'MaxSigChars' ) ) {
			$nickname = $username;
			wfDebug( __METHOD__ . ": $username has overlong signature.\n" );
		} elseif ( $fancySig !== false ) {
			# Sig. might contain markup; validate this
			if ( $this->validateSig( $nickname ) !== false ) {
				# Validated; clean up (if needed) and return it
				return $this->cleanSig( $nickname, true );
			} else {
				# Failed to validate; fall back to the default
				$nickname = $username;
				wfDebug( __METHOD__ . ": $username has bad XML tags in signature.\n" );
			}
		}

		# Make sure nickname doesnt get a sig in a sig
		$nickname = self::cleanSigInSig( $nickname );

		# If we're still here, make it a link to the user page
		$userText = wfEscapeWikiText( $username );
		$nickText = wfEscapeWikiText( $nickname );
		$msgName = $user->isAnon() ? 'signature-anon' : 'signature';

		return wfMessage( $msgName, $userText, $nickText )->inContentLanguage()
			->title( $this->getTitle() )->text();
	}

	/**
	 * Check that the user's signature contains no bad XML
	 *
	 * @param string $text
	 * @return string|bool An expanded string, or false if invalid.
	 */
	public function validateSig( $text ) {
		return Xml::isWellFormedXmlFragment( $text ) ? $text : false;
	}

	/**
	 * Clean up signature text
	 *
	 * 1) Strip 3, 4 or 5 tildes out of signatures @see cleanSigInSig
	 * 2) Substitute all transclusions
	 *
	 * @param string $text
	 * @param bool $parsing Whether we're cleaning (preferences save) or parsing
	 * @return string Signature text
	 */
	public function cleanSig( $text, $parsing = false ) {
		if ( !$parsing ) {
			global $wgTitle;
			$magicScopeVariable = $this->lock();
			$this->startParse( $wgTitle, new ParserOptions, self::OT_PREPROCESS, true );
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
	 */
	public static function cleanSigInSig( $text ) {
		$text = preg_replace( '/~{3,5}/', '', $text );
		return $text;
	}

	/**
	 * Set up some variables which are usually set up in parse()
	 * so that an external function can call some class members with confidence
	 *
	 * @param Title|null $title
	 * @param ParserOptions $options
	 * @param int $outputType
	 * @param bool $clearState
	 */
	public function startExternalParse( Title $title = null, ParserOptions $options,
		$outputType, $clearState = true
	) {
		$this->startParse( $title, $options, $outputType, $clearState );
	}

	/**
	 * @param Title|null $title
	 * @param ParserOptions $options
	 * @param int $outputType
	 * @param bool $clearState
	 */
	private function startParse( Title $title = null, ParserOptions $options,
		$outputType, $clearState = true
	) {
		$this->setTitle( $title );
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
	 * @param Title|null $title Title object or null to use $wgTitle
	 * @return string
	 */
	public function transformMsg( $text, $options, $title = null ) {
		static $executing = false;

		# Guard against infinite recursion
		if ( $executing ) {
			return $text;
		}
		$executing = true;

		if ( !$title ) {
			global $wgTitle;
			$title = $wgTitle;
		}

		$text = $this->preprocess( $text, $title, $options );

		$executing = false;
		return $text;
	}

	/**
	 * Create an HTML-style tag, e.g. "<yourtag>special text</yourtag>"
	 * The callback should have the following form:
	 *    function myParserHook( $text, $params, $parser, $frame ) { ... }
	 *
	 * Transform and return $text. Use $parser for any required context, e.g. use
	 * $parser->getTitle() and $parser->getOptions() not $wgTitle or $wgOut->mParserOptions
	 *
	 * Hooks may return extended information by returning an array, of which the
	 * first numbered element (index 0) must be the return string, and all other
	 * entries are extracted into local variables within an internal function
	 * in the Parser class.
	 *
	 * This interface (introduced r61913) appears to be undocumented, but
	 * 'markerType' is used by some core tag hooks to override which strip
	 * array their results are placed in. **Use great caution if attempting
	 * this interface, as it is not documented and injudicious use could smash
	 * private variables.**
	 *
	 * @param string $tag The tag to use, e.g. 'hook' for "<hook>"
	 * @param callable $callback The callback function (and object) to use for the tag
	 * @throws MWException
	 * @return callable|null The old value of the mTagHooks array associated with the hook
	 */
	public function setHook( $tag, callable $callback ) {
		$tag = strtolower( $tag );
		if ( preg_match( '/[<>\r\n]/', $tag, $m ) ) {
			throw new MWException( "Invalid character {$m[0]} in setHook('$tag', ...) call" );
		}
		$oldVal = $this->mTagHooks[$tag] ?? null;
		$this->mTagHooks[$tag] = $callback;
		if ( !in_array( $tag, $this->mStripList ) ) {
			$this->mStripList[] = $tag;
		}

		return $oldVal;
	}

	/**
	 * As setHook(), but letting the contents be parsed.
	 *
	 * Transparent tag hooks are like regular XML-style tag hooks, except they
	 * operate late in the transformation sequence, on HTML instead of wikitext.
	 *
	 * This is probably obsoleted by things dealing with parser frames?
	 * The only extension currently using it is geoserver.
	 *
	 * @since 1.10
	 * @todo better document or deprecate this
	 *
	 * @param string $tag The tag to use, e.g. 'hook' for "<hook>"
	 * @param callable $callback The callback function (and object) to use for the tag
	 * @throws MWException
	 * @return callable|null The old value of the mTagHooks array associated with the hook
	 */
	public function setTransparentTagHook( $tag, callable $callback ) {
		$tag = strtolower( $tag );
		if ( preg_match( '/[<>\r\n]/', $tag, $m ) ) {
			throw new MWException( "Invalid character {$m[0]} in setTransparentHook('$tag', ...) call" );
		}
		$oldVal = $this->mTransparentTagHooks[$tag] ?? null;
		$this->mTransparentTagHooks[$tag] = $callback;

		return $oldVal;
	}

	/**
	 * Remove all tag hooks
	 */
	public function clearTagHooks() {
		$this->mTagHooks = [];
		$this->mFunctionTagHooks = [];
		$this->mStripList = $this->mDefaultStripList;
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
	 *   isHTML                    The returned text is HTML, armour it against wikitext transformation
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
	 * @throws MWException
	 * @return string|callable The old callback function for this name, if any
	 */
	public function setFunctionHook( $id, callable $callback, $flags = 0 ) {
		$oldVal = isset( $this->mFunctionHooks[$id] ) ? $this->mFunctionHooks[$id][0] : null;
		$this->mFunctionHooks[$id] = [ $callback, $flags ];

		# Add to function cache
		$mw = $this->magicWordFactory->get( $id );
		if ( !$mw ) {
			throw new MWException( __METHOD__ . '() expecting a magic word identifier.' );
		}

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
	 */
	public function getFunctionHooks() {
		$this->firstCallInit();
		return array_keys( $this->mFunctionHooks );
	}

	/**
	 * Create a tag function, e.g. "<test>some stuff</test>".
	 * Unlike tag hooks, tag functions are parsed at preprocessor level.
	 * Unlike parser functions, their content is not preprocessed.
	 * @param string $tag
	 * @param callable $callback
	 * @param int $flags
	 * @throws MWException
	 * @return null
	 */
	public function setFunctionTagHook( $tag, callable $callback, $flags ) {
		$tag = strtolower( $tag );
		if ( preg_match( '/[<>\r\n]/', $tag, $m ) ) {
			throw new MWException( "Invalid character {$m[0]} in setFunctionTagHook('$tag', ...) call" );
		}
		$old = $this->mFunctionTagHooks[$tag] ?? null;
		$this->mFunctionTagHooks[$tag] = [ $callback, $flags ];

		if ( !in_array( $tag, $this->mStripList ) ) {
			$this->mStripList[] = $tag;
		}

		return $old;
	}

	/**
	 * Replace "<!--LINK-->" link placeholders with actual links, in the buffer
	 * Placeholders created in Linker::link()
	 *
	 * @param string &$text
	 * @param int $options
	 */
	public function replaceLinkHolders( &$text, $options = 0 ) {
		$this->mLinkHolders->replace( $text );
	}

	/**
	 * Replace "<!--LINK-->" link placeholders with plain text of links
	 * (not HTML-formatted).
	 *
	 * @param string $text
	 * @return string
	 */
	public function replaceLinkHoldersText( $text ) {
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
	 */
	public function renderImageGallery( $text, $params ) {
		$mode = false;
		if ( isset( $params['mode'] ) ) {
			$mode = $params['mode'];
		}

		try {
			$ig = ImageGalleryBase::factory( $mode );
		} catch ( Exception $e ) {
			// If invalid type set, fallback to default.
			$ig = ImageGalleryBase::factory( false );
		}

		$ig->setContextTitle( $this->mTitle );
		$ig->setShowBytes( false );
		$ig->setShowDimensions( false );
		$ig->setShowFilename( false );
		$ig->setParser( $this );
		$ig->setHideBadImages();
		$ig->setAttributes( Sanitizer::validateTagAttributes( $params, 'ul' ) );

		if ( isset( $params['showfilename'] ) ) {
			$ig->setShowFilename( true );
		} else {
			$ig->setShowFilename( false );
		}
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

		// Avoid PHP 7.1 warning from passing $this by reference
		$parser = $this;
		Hooks::run( 'BeforeParserrenderImageGallery', [ &$parser, &$ig ] );

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
			if ( is_null( $title ) ) {
				# Bogus title. Ignore these so we don't bomb out later.
				continue;
			}

			# We need to get what handler the file uses, to figure out parameters.
			# Note, a hook can overide the file name, and chose an entirely different
			# file (which potentially could be of a different type and have different handler).
			$options = [];
			$descQuery = false;
			Hooks::run( 'BeforeParserFetchFileAndTitle',
				[ $this, $title, &$options, &$descQuery ] );
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
			$alt = '';
			$link = '';
			$handlerOptions = [];
			if ( isset( $matches[3] ) ) {
				// look for an |alt= definition while trying not to break existing
				// captions with multiple pipes (|) in it, until a more sensible grammar
				// is defined for images in galleries

				// FIXME: Doing recursiveTagParse at this stage, and the trim before
				// splitting on '|' is a bit odd, and different from makeImage.
				$matches[3] = $this->recursiveTagParse( trim( $matches[3] ) );
				// Protect LanguageConverter markup
				$parameterMatches = StringUtils::delimiterExplode(
					'-{', '}-', '|', $matches[3], true /* nested */
				);

				foreach ( $parameterMatches as $parameterMatch ) {
					list( $magicName, $match ) = $mwArray->matchVariableStartToEnd( $parameterMatch );
					if ( $magicName ) {
						$paramName = $paramMap[$magicName];

						switch ( $paramName ) {
							case 'gallery-internal-alt':
								$alt = $this->stripAltText( $match, false );
								break;
							case 'gallery-internal-link':
								$linkValue = $this->stripAltText( $match, false );
								if ( preg_match( '/^-{R|(.*)}-$/', $linkValue ) ) {
									// Result of LanguageConverter::markNoConversion
									// invoked on an external link.
									$linkValue = substr( $linkValue, 4, -2 );
								}
								list( $type, $target ) = $this->parseLinkParameter( $linkValue );
								if ( $type === 'link-url' ) {
									$link = $target;
									$this->mOutput->addExternalLink( $target );
								} elseif ( $type === 'link-title' ) {
									$link = $target->getLinkURL();
									$this->mOutput->addLink( $target );
								}
								break;
							default:
								// Must be a handler specific parameter.
								if ( $handler->validateParam( $paramName, $match ) ) {
									$handlerOptions[$paramName] = $match;
								} else {
									// Guess not, consider it as caption.
									wfDebug( "$parameterMatch failed parameter validation\n" );
									$label = $parameterMatch;
								}
						}

					} else {
						// Last pipe wins.
						$label = $parameterMatch;
					}
				}
			}

			$ig->add( $title, $label, $alt, $link, $handlerOptions );
		}
		$html = $ig->toHTML();
		Hooks::run( 'AfterParserFetchFileAndTitle', [ $this, $ig, &$html ] );
		return $html;
	}

	/**
	 * @param MediaHandler $handler
	 * @return array
	 */
	public function getImageParams( $handler ) {
		if ( $handler ) {
			$handlerClass = get_class( $handler );
		} else {
			$handlerClass = '';
		}
		if ( !isset( $this->mImageParams[$handlerClass] ) ) {
			# Initialise static lists
			static $internalParamNames = [
				'horizAlign' => [ 'left', 'right', 'center', 'none' ],
				'vertAlign' => [ 'baseline', 'sub', 'super', 'top', 'text-top', 'middle',
					'bottom', 'text-bottom' ],
				'frame' => [ 'thumbnail', 'manualthumb', 'framed', 'frameless',
					'upright', 'border', 'link', 'alt', 'class' ],
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
			$paramMap = $internalParamMap;
			if ( $handler ) {
				$handlerParamMap = $handler->getParamMap();
				foreach ( $handlerParamMap as $magic => $paramName ) {
					$paramMap[$magic] = [ 'handler', $paramName ];
				}
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
	 * @param Title $title
	 * @param string $options
	 * @param LinkHolderArray|bool $holders
	 * @return string HTML
	 */
	public function makeImage( $title, $options, $holders = false ) {
		# Check if the options text is of the form "options|alt text"
		# Options are:
		#  * thumbnail  make a thumbnail with enlarge-icon and caption, alignment depends on lang
		#  * left       no resizing, just left align. label is used for alt= only
		#  * right      same, but right aligned
		#  * none       same, but not aligned
		#  * ___px      scale to ___ pixels width, no aligning. e.g. use in taxobox
		#  * center     center the image
		#  * frame      Keep original image size, no magnify-button.
		#  * framed     Same as "frame"
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
		Hooks::run( 'BeforeParserFetchFileAndTitle',
			[ $this, $title, &$options, &$descQuery ] );
		# Fetch and register the file (file title may be different via hooks)
		list( $file, $title ) = $this->fetchFileAndTitle( $title, $options );

		# Get parameter map
		$handler = $file ? $file->getHandler() : false;

		list( $paramMap, $mwArray ) = $this->getImageParams( $handler );

		if ( !$file ) {
			$this->addTrackingCategory( 'broken-file-category' );
		}

		# Process the input parameters
		$caption = '';
		$params = [ 'frame' => [], 'handler' => [],
			'horizAlign' => [], 'vertAlign' => [] ];
		$seenformat = false;
		foreach ( $parts as $part ) {
			$part = trim( $part );
			list( $magicName, $value ) = $mwArray->matchVariableStartToEnd( $part );
			$validated = false;
			if ( isset( $paramMap[$magicName] ) ) {
				list( $type, $paramName ) = $paramMap[$magicName];

				# Special case; width and height come in one variable together
				if ( $type === 'handler' && $paramName === 'width' ) {
					$parsedWidthParam = self::parseWidthParam( $value );
					if ( isset( $parsedWidthParam['width'] ) ) {
						$width = $parsedWidthParam['width'];
						if ( $handler->validateParam( 'width', $width ) ) {
							$params[$type]['width'] = $width;
							$validated = true;
						}
					}
					if ( isset( $parsedWidthParam['height'] ) ) {
						$height = $parsedWidthParam['height'];
						if ( $handler->validateParam( 'height', $height ) ) {
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
							case 'manualthumb':
							case 'alt':
							case 'class':
								# @todo FIXME: Possibly check validity here for
								# manualthumb? downstream behavior seems odd with
								# missing manual thumbs.
								$validated = true;
								$value = $this->stripAltText( $value, $holders );
								break;
							case 'link':
								list( $paramName, $value ) =
									$this->parseLinkParameter(
										$this->stripAltText( $value, $holders )
									);
								if ( $paramName ) {
									$validated = true;
									if ( $paramName === 'no-link' ) {
										$value = true;
									}
									if ( ( $paramName === 'link-url' ) && $this->mOptions->getExternalLinkTarget() ) {
										$params[$type]['link-target'] = $this->mOptions->getExternalLinkTarget();
									}
								}
								break;
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
		if ( $params['horizAlign'] ) {
			$params['frame']['align'] = key( $params['horizAlign'] );
		}
		if ( $params['vertAlign'] ) {
			$params['frame']['valign'] = key( $params['vertAlign'] );
		}

		$params['frame']['caption'] = $caption;

		# Will the image be presented in a frame, with the caption below?
		$imageIsFramed = isset( $params['frame']['frame'] )
			|| isset( $params['frame']['framed'] )
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
		if ( $imageIsFramed ) { # Framed image
			if ( $caption === '' && !isset( $params['frame']['alt'] ) ) {
				# No caption or alt text, add the filename as the alt text so
				# that screen readers at least get some description of the image
				$params['frame']['alt'] = $title->getText();
			}
			# Do not set $params['frame']['title'] because tooltips don't make sense
			# for framed images
		} else { # Inline image
			if ( !isset( $params['frame']['alt'] ) ) {
				# No alt text, use the "caption" for the alt text
				if ( $caption !== '' ) {
					$params['frame']['alt'] = $this->stripAltText( $caption, $holders );
				} else {
					# No caption, fall back to using the filename for the
					# alt text
					$params['frame']['alt'] = $title->getText();
				}
			}
			# Use the "caption" for the tooltip text
			$params['frame']['title'] = $this->stripAltText( $caption, $holders );
		}
		$params['handler']['targetlang'] = $this->getTargetLanguage()->getCode();

		Hooks::run( 'ParserMakeImageParams', [ $title, $file, &$params, $this ] );

		# Linker does the rest
		$time = $options['time'] ?? false;
		$ret = Linker::makeImageLink( $this, $title, $file, $params['frame'], $params['handler'],
			$time, $descQuery, $this->mOptions->getThumbSize() );

		# Give the handler a chance to modify the parser object
		if ( $handler ) {
			$handler->parserTransformHook( $this, $file );
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
	public function parseLinkParameter( $value ) {
		$chars = self::EXT_LINK_URL_CLASS;
		$addr = self::EXT_LINK_ADDR;
		$prots = $this->mUrlProtocols;
		$type = null;
		$target = false;
		if ( $value === '' ) {
			$type = 'no-link';
		} elseif ( preg_match( "/^((?i)$prots)/", $value ) ) {
			if ( preg_match( "/^((?i)$prots)$addr$chars*$/u", $value, $m ) ) {
				$this->mOutput->addExternalLink( $value );
				$type = 'link-url';
				$target = $value;
			}
		} else {
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
	 * @param string $caption
	 * @param LinkHolderArray|bool $holders
	 * @return mixed|string
	 */
	protected function stripAltText( $caption, $holders ) {
		# Strip bad stuff out of the title (tooltip).  We can't just use
		# replaceLinkHoldersText() here, because if this function is called
		# from replaceInternalLinks2(), mLinkHolders won't be up-to-date.
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
	 * Set a flag in the output object indicating that the content is dynamic and
	 * shouldn't be cached.
	 * @deprecated since 1.28; use getOutput()->updateCacheExpiry()
	 */
	public function disableCache() {
		wfDebug( "Parser output marked as uncacheable.\n" );
		if ( !$this->mOutput ) {
			throw new MWException( __METHOD__ .
				" can only be called when actually parsing something" );
		}
		$this->mOutput->updateCacheExpiry( 0 ); // new style, for consistency
	}

	/**
	 * Callback from the Sanitizer for expanding items found in HTML attribute
	 * values, so they can be safely tested and escaped.
	 *
	 * @param string &$text
	 * @param bool|PPFrame $frame
	 * @return string
	 */
	public function attributeStripCallback( &$text, $frame = false ) {
		$text = $this->replaceVariables( $text, $frame );
		$text = $this->mStripState->unstripBoth( $text );
		return $text;
	}

	/**
	 * Accessor
	 *
	 * @return array
	 */
	public function getTags() {
		$this->firstCallInit();
		return array_merge(
			array_keys( $this->mTransparentTagHooks ),
			array_keys( $this->mTagHooks ),
			array_keys( $this->mFunctionTagHooks )
		);
	}

	/**
	 * @since 1.32
	 * @return array
	 */
	public function getFunctionSynonyms() {
		$this->firstCallInit();
		return $this->mFunctionSynonyms;
	}

	/**
	 * @since 1.32
	 * @return string
	 */
	public function getUrlProtocols() {
		return $this->mUrlProtocols;
	}

	/**
	 * Replace transparent tags in $text with the values given by the callbacks.
	 *
	 * Transparent tag hooks are like regular XML-style tag hooks, except they
	 * operate late in the transformation sequence, on HTML instead of wikitext.
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function replaceTransparentTags( $text ) {
		$matches = [];
		$elements = array_keys( $this->mTransparentTagHooks );
		$text = self::extractTagsAndParams( $elements, $text, $matches );
		$replacements = [];

		foreach ( $matches as $marker => $data ) {
			list( $element, $content, $params, $tag ) = $data;
			$tagName = strtolower( $element );
			if ( isset( $this->mTransparentTagHooks[$tagName] ) ) {
				$output = call_user_func_array(
					$this->mTransparentTagHooks[$tagName],
					[ $content, $params, $this ]
				);
			} else {
				$output = $tag;
			}
			$replacements[$marker] = $output;
		}
		return strtr( $text, $replacements );
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
	 * @param string $newText Replacement text for section data.
	 * @return string For "get", the extracted section text.
	 *   for "replace", the whole page with the section replaced.
	 */
	private function extractSections( $text, $sectionId, $mode, $newText = '' ) {
		global $wgTitle; # not generally used but removes an ugly failure mode

		$magicScopeVariable = $this->lock();
		$this->startParse( $wgTitle, new ParserOptions, self::OT_PLAIN, true );
		$outText = '';
		$frame = $this->getPreprocessor()->newFrame();

		# Process section extraction flags
		$flags = 0;
		$sectionParts = explode( '-', $sectionId );
		$sectionIndex = array_pop( $sectionParts );
		foreach ( $sectionParts as $part ) {
			if ( $part === 'T' ) {
				$flags |= self::PTD_FOR_INCLUSION;
			}
		}

		# Check for empty input
		if ( strval( $text ) === '' ) {
			# Only sections 0 and T-0 exist in an empty document
			if ( $sectionIndex == 0 ) {
				if ( $mode === 'get' ) {
					return '';
				}

				return $newText;
			} else {
				if ( $mode === 'get' ) {
					return $newText;
				}

				return $text;
			}
		}

		# Preprocess the text
		$root = $this->preprocessToDom( $text, $flags );

		# <h> nodes indicate section breaks
		# They can only occur at the top level, so we can find them by iterating the root's children
		$node = $root->getFirstChild();

		# Find the target section
		if ( $sectionIndex == 0 ) {
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
			if ( $mode === 'get' ) {
				return $newText;
			} else {
				return $text;
			}
		}

		# Find the end of the section, including nested sections
		do {
			if ( $node->getName() === 'h' ) {
				$bits = $node->splitHeading();
				$curLevel = $bits['level'];
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

		if ( is_string( $outText ) ) {
			# Re-insert stripped tags
			$outText = rtrim( $this->mStripState->unstripBoth( $outText ) );
		}

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
	 * @param string $defaultText Default to return if section is not found
	 *
	 * @return string Text of the requested section
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
	 * @param string $newText Replacing text
	 *
	 * @return string Modified text
	 */
	public function replaceSection( $oldText, $sectionId, $newText ) {
		return $this->extractSections( $oldText, $sectionId, 'replace', $newText );
	}

	/**
	 * Get the ID of the revision we are parsing
	 *
	 * @return int|null
	 */
	public function getRevisionId() {
		return $this->mRevisionId;
	}

	/**
	 * Get the revision object for $this->mRevisionId
	 *
	 * @return Revision|null Either a Revision object or null
	 * @since 1.23 (public since 1.23)
	 */
	public function getRevisionObject() {
		if ( !is_null( $this->mRevisionObject ) ) {
			return $this->mRevisionObject;
		}

		// NOTE: try to get the RevisionObject even if mRevisionId is null.
		// This is useful when parsing revision that has not yet been saved.
		// However, if we get back a saved revision even though we are in
		// preview mode, we'll have to ignore it, see below.
		// NOTE: This callback may be used to inject an OLD revision that was
		// already loaded, so "current" is a bit of a misnomer. We can't just
		// skip it if mRevisionId is set.
		$rev = call_user_func(
			$this->mOptions->getCurrentRevisionCallback(), $this->getTitle(), $this
		);

		if ( $this->mRevisionId === null && $rev && $rev->getId() ) {
			// We are in preview mode (mRevisionId is null), and the current revision callback
			// returned an existing revision. Ignore it and return null, it's probably the page's
			// current revision, which is not what we want here. Note that we do want to call the
			// callback to allow the unsaved revision to be injected here, e.g. for
			// self-transclusion previews.
			return null;
		}

		// If the parse is for a new revision, then the callback should have
		// already been set to force the object and should match mRevisionId.
		// If not, try to fetch by mRevisionId for sanity.
		if ( $this->mRevisionId && $rev && $rev->getId() != $this->mRevisionId ) {
			$rev = Revision::newFromId( $this->mRevisionId );
		}

		$this->mRevisionObject = $rev;

		return $this->mRevisionObject;
	}

	/**
	 * Get the timestamp associated with the current revision, adjusted for
	 * the default server-local timestamp
	 * @return string
	 */
	public function getRevisionTimestamp() {
		if ( is_null( $this->mRevisionTimestamp ) ) {
			$revObject = $this->getRevisionObject();
			$timestamp = $revObject ? $revObject->getTimestamp() : wfTimestampNow();

			# The cryptic '' timezone parameter tells to use the site-default
			# timezone offset instead of the user settings.
			# Since this value will be saved into the parser cache, served
			# to other users, and potentially even used inside links and such,
			# it needs to be consistent for all visitors.
			$this->mRevisionTimestamp = $this->contLang->userAdjust( $timestamp, '' );
		}
		return $this->mRevisionTimestamp;
	}

	/**
	 * Get the name of the user that edited the last revision
	 *
	 * @return string User name
	 */
	public function getRevisionUser() {
		if ( is_null( $this->mRevisionUser ) ) {
			$revObject = $this->getRevisionObject();

			# if this template is subst: the revision id will be blank,
			# so just use the current user's name
			if ( $revObject ) {
				$this->mRevisionUser = $revObject->getUserText();
			} elseif ( $this->ot['wiki'] || $this->mOptions->getIsPreview() ) {
				$this->mRevisionUser = $this->getUser()->getName();
			}
		}
		return $this->mRevisionUser;
	}

	/**
	 * Get the size of the revision
	 *
	 * @return int|null Revision size
	 */
	public function getRevisionSize() {
		if ( is_null( $this->mRevisionSize ) ) {
			$revObject = $this->getRevisionObject();

			# if this variable is subst: the revision id will be blank,
			# so just use the parser input size, because the own substituation
			# will change the size.
			if ( $revObject ) {
				$this->mRevisionSize = $revObject->getSize();
			} else {
				$this->mRevisionSize = $this->mInputSize;
			}
		}
		return $this->mRevisionSize;
	}

	/**
	 * Mutator for $mDefaultSort
	 *
	 * @param string $sort New value
	 */
	public function setDefaultSort( $sort ) {
		$this->mDefaultSort = $sort;
		$this->mOutput->setProperty( 'defaultsort', $sort );
	}

	/**
	 * Accessor for $mDefaultSort
	 * Will use the empty string if none is set.
	 *
	 * This value is treated as a prefix, so the
	 * empty string is equivalent to sorting by
	 * page name.
	 *
	 * @return string
	 */
	public function getDefaultSort() {
		if ( $this->mDefaultSort !== false ) {
			return $this->mDefaultSort;
		} else {
			return '';
		}
	}

	/**
	 * Accessor for $mDefaultSort
	 * Unlike getDefaultSort(), will return false if none is set
	 *
	 * @return string|bool
	 */
	public function getCustomDefaultSort() {
		return $this->mDefaultSort;
	}

	private static function getSectionNameFromStrippedText( $text ) {
		$text = Sanitizer::normalizeSectionNameWhitespace( $text );
		$text = Sanitizer::decodeCharReferences( $text );
		$text = self::normalizeSectionName( $text );
		return $text;
	}

	private static function makeAnchor( $sectionName ) {
		return '#' . Sanitizer::escapeIdForLink( $sectionName );
	}

	private function makeLegacyAnchor( $sectionName ) {
		$fragmentMode = $this->siteConfig->get( 'FragmentMode' );
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
		} catch ( MalformedTitleException $ex ) {
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
	 * to create valid section anchors by mimicing the output of the
	 * parser when headings are parsed.
	 *
	 * @param string $text Text string to be stripped of wikitext
	 * for use in a Section anchor
	 * @return string Filtered text string
	 */
	public function stripSectionName( $text ) {
		# Strip internal link markup
		$text = preg_replace( '/\[\[:?([^[|]+)\|([^[]+)\]\]/', '$2', $text );
		$text = preg_replace( '/\[\[:?([^[]+)\|?\]\]/', '$1', $text );

		# Strip external link markup
		# @todo FIXME: Not tolerant to blank link text
		# I.E. [https://www.mediawiki.org] will render as [1] or something depending
		# on how many empty links there are on the page - need to figure that out.
		$text = preg_replace( '/\[(?i:' . $this->mUrlProtocols . ')([^ ]+?) ([^[]+)\]/', '$2', $text );

		# Parse wikitext quotes (italics & bold)
		$text = $this->doQuotes( $text );

		# Strip HTML tags
		$text = StringUtils::delimiterReplace( '<', '>', '', $text );
		return $text;
	}

	/**
	 * strip/replaceVariables/unstrip for preprocessor regression testing
	 *
	 * @param string $text
	 * @param Title $title
	 * @param ParserOptions $options
	 * @param int $outputType
	 *
	 * @return string
	 */
	public function testSrvus( $text, Title $title, ParserOptions $options,
		$outputType = self::OT_HTML
	) {
		$magicScopeVariable = $this->lock();
		$this->startParse( $title, $options, $outputType, true );

		$text = $this->replaceVariables( $text );
		$text = $this->mStripState->unstripBoth( $text );
		$text = Sanitizer::removeHTMLtags( $text );
		return $text;
	}

	/**
	 * @param string $text
	 * @param Title $title
	 * @param ParserOptions $options
	 * @return string
	 */
	public function testPst( $text, Title $title, ParserOptions $options ) {
		return $this->preSaveTransform( $text, $title, $options->getUser(), $options );
	}

	/**
	 * @param string $text
	 * @param Title $title
	 * @param ParserOptions $options
	 * @return string
	 */
	public function testPreprocess( $text, Title $title, ParserOptions $options ) {
		return $this->testSrvus( $text, $title, $options, self::OT_PREPROCESS );
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
	 */
	public function markerSkipCallback( $s, $callback ) {
		$i = 0;
		$out = '';
		while ( $i < strlen( $s ) ) {
			$markerStart = strpos( $s, self::MARKER_PREFIX, $i );
			if ( $markerStart === false ) {
				$out .= call_user_func( $callback, substr( $s, $i ) );
				break;
			} else {
				$out .= call_user_func( $callback, substr( $s, $i, $markerStart - $i ) );
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
	 */
	public function killMarkers( $text ) {
		return $this->mStripState->killMarkers( $text );
	}

	/**
	 * Save the parser state required to convert the given half-parsed text to
	 * HTML. "Half-parsed" in this context means the output of
	 * recursiveTagParse() or internalParse(). This output has strip markers
	 * from replaceVariables (extensionSubstitution() etc.), and link
	 * placeholders from replaceLinkHolders().
	 *
	 * Returns an array which can be serialized and stored persistently. This
	 * array can later be loaded into another parser instance with
	 * unserializeHalfParsedText(). The text can then be safely incorporated into
	 * the return value of a parser hook.
	 *
	 * @deprecated since 1.31
	 * @param string $text
	 *
	 * @return array
	 */
	public function serializeHalfParsedText( $text ) {
		wfDeprecated( __METHOD__, '1.31' );
		$data = [
			'text' => $text,
			'version' => self::HALF_PARSED_VERSION,
			'stripState' => $this->mStripState->getSubState( $text ),
			'linkHolders' => $this->mLinkHolders->getSubArray( $text )
		];
		return $data;
	}

	/**
	 * Load the parser state given in the $data array, which is assumed to
	 * have been generated by serializeHalfParsedText(). The text contents is
	 * extracted from the array, and its markers are transformed into markers
	 * appropriate for the current Parser instance. This transformed text is
	 * returned, and can be safely included in the return value of a parser
	 * hook.
	 *
	 * If the $data array has been stored persistently, the caller should first
	 * check whether it is still valid, by calling isValidHalfParsedText().
	 *
	 * @deprecated since 1.31
	 * @param array $data Serialized data
	 * @throws MWException
	 * @return string
	 */
	public function unserializeHalfParsedText( $data ) {
		wfDeprecated( __METHOD__, '1.31' );
		if ( !isset( $data['version'] ) || $data['version'] != self::HALF_PARSED_VERSION ) {
			throw new MWException( __METHOD__ . ': invalid version' );
		}

		# First, extract the strip state.
		$texts = [ $data['text'] ];
		$texts = $this->mStripState->merge( $data['stripState'], $texts );

		# Now renumber links
		$texts = $this->mLinkHolders->mergeForeign( $data['linkHolders'], $texts );

		# Should be good to go.
		return $texts[0];
	}

	/**
	 * Returns true if the given array, presumed to be generated by
	 * serializeHalfParsedText(), is compatible with the current version of the
	 * parser.
	 *
	 * @deprecated since 1.31
	 * @param array $data
	 *
	 * @return bool
	 */
	public function isValidHalfParsedText( $data ) {
		wfDeprecated( __METHOD__, '1.31' );
		return isset( $data['version'] ) && $data['version'] == self::HALF_PARSED_VERSION;
	}

	/**
	 * Parsed a width param of imagelink like 300px or 200x300px
	 *
	 * @param string $value
	 * @param bool $parseHeight
	 *
	 * @return array
	 * @since 1.20
	 */
	public static function parseWidthParam( $value, $parseHeight = true ) {
		$parsedWidthParam = [];
		if ( $value === '' ) {
			return $parsedWidthParam;
		}
		$m = [];
		# (T15500) In both cases (width/height and width only),
		# permit trailing "px" for backward compatibility.
		if ( $parseHeight && preg_match( '/^([0-9]*)x([0-9]*)\s*(?:px)?\s*$/', $value, $m ) ) {
			$width = intval( $m[1] );
			$height = intval( $m[2] );
			$parsedWidthParam['width'] = $width;
			$parsedWidthParam['height'] = $height;
		} elseif ( preg_match( '/^[0-9]*\s*(?:px)?\s*$/', $value ) ) {
			$width = intval( $value );
			$parsedWidthParam['width'] = $width;
		}
		return $parsedWidthParam;
	}

	/**
	 * Lock the current instance of the parser.
	 *
	 * This is meant to stop someone from calling the parser
	 * recursively and messing up all the strip state.
	 *
	 * @throws MWException If parser is in a parse
	 * @return ScopedCallback The lock will be released once the return value goes out of scope.
	 */
	protected function lock() {
		if ( $this->mInParse ) {
			throw new MWException( "Parser state cleared while parsing. "
				. "Did you call Parser::parse recursively? Lock is held by: " . $this->mInParse );
		}

		// Save the backtrace when locking, so that if some code tries locking again,
		// we can print the lock owner's backtrace for easier debugging
		$e = new Exception;
		$this->mInParse = $e->getTraceAsString();

		$recursiveCheck = new ScopedCallback( function () {
			$this->mInParse = false;
		} );

		return $recursiveCheck;
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
	 * Return this parser if it is not doing anything, otherwise
	 * get a fresh parser. You can use this method by doing
	 * $myParser = $wgParser->getFreshParser(), or more simply
	 * $wgParser->getFreshParser()->parse( ... );
	 * if you're unsure if $wgParser is safe to use.
	 *
	 * @since 1.24
	 * @return Parser A parser object that is not parsing anything
	 */
	public function getFreshParser() {
		if ( $this->mInParse ) {
			return $this->factory->create();
		} else {
			return $this;
		}
	}

	/**
	 * Set's up the PHP implementation of OOUI for use in this request
	 * and instructs OutputPage to enable OOUI for itself.
	 *
	 * @since 1.26
	 */
	public function enableOOUI() {
		OutputPage::setupOOUI();
		$this->mOutput->setEnableOOUI( true );
	}
}

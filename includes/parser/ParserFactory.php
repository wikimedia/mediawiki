<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

use MediaWiki\Category\TrackingCategories;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\Page\File\BadFileLookup;
use MediaWiki\Preferences\SignatureValidatorFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Tidy\TidyDriverBase;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Utils\UrlUtils;
use Psr\Log\LoggerInterface;
use Wikimedia\ObjectCache\WANObjectCache;

/**
 * @since 1.32
 */
class ParserFactory {
	/**
	 * Track calls to Parser constructor to aid in deprecation of direct
	 * Parser invocation.  This is temporary: it will be removed once the
	 * deprecation notice period is over and the underlying method calls
	 * are refactored.
	 * @internal
	 * @var int
	 */
	public static $inParserFactory = 0;

	/** @var Parser|null */
	private $mainInstance;

	/**
	 * @since 1.32
	 * @internal
	 */
	public function __construct(
		private readonly ServiceOptions $svcOptions,
		private readonly MagicWordFactory $magicWordFactory,
		private readonly Language $contLang,
		private readonly UrlUtils $urlUtils,
		private readonly SpecialPageFactory $specialPageFactory,
		private readonly LinkRendererFactory $linkRendererFactory,
		private readonly NamespaceInfo $nsInfo,
		private readonly LoggerInterface $logger,
		private readonly BadFileLookup $badFileLookup,
		private readonly LanguageConverterFactory $languageConverterFactory,
		private readonly LanguageNameUtils $languageNameUtils,
		private readonly HookContainer $hookContainer,
		private readonly TidyDriverBase $tidy,
		private readonly WANObjectCache $wanCache,
		private readonly UserOptionsLookup $userOptionsLookup,
		private readonly UserFactory $userFactory,
		private readonly TitleFormatter $titleFormatter,
		private readonly HttpRequestFactory $httpRequestFactory,
		private readonly TrackingCategories $trackingCategories,
		private readonly SignatureValidatorFactory $signatureValidatorFactory,
		private readonly UserNameUtils $userNameUtils,
	) {
		$svcOptions->assertRequiredOptions( Parser::CONSTRUCTOR_OPTIONS );

		wfDebug( __CLASS__ . ": using default preprocessor" );
	}

	/**
	 * Creates a new parser
	 *
	 * @note Use this function to get a new Parser instance to store
	 * in a local class property.  Where possible use lazy creation and
	 * create the Parser only when needed, not directly in service wiring.
	 *
	 * @return Parser
	 * @since 1.32
	 */
	public function create(): Parser {
		self::$inParserFactory++;
		try {
			return new Parser(
				$this->svcOptions,
				$this->magicWordFactory,
				$this->contLang,
				$this->urlUtils,
				$this->specialPageFactory,
				$this->linkRendererFactory,
				$this->nsInfo,
				$this->logger,
				$this->badFileLookup,
				$this->languageConverterFactory,
				$this->languageNameUtils,
				$this->hookContainer,
				$this->tidy,
				$this->wanCache,
				$this->userOptionsLookup,
				$this->userFactory,
				$this->titleFormatter,
				$this->httpRequestFactory,
				$this->trackingCategories,
				$this->signatureValidatorFactory,
				$this->userNameUtils
			);
		} finally {
			self::$inParserFactory--;
		}
	}

	/**
	 * Get the main shared instance. This is unsafe when the caller is not in
	 * a top-level context, because re-entering the parser will throw an
	 * exception.
	 *
	 * @note This function is used to get metadata from the parser. Avoid
	 * using this function to parse wikitext.  (Generally avoid using this
	 * function at all in new code.)
	 *
	 * @since 1.39
	 * @return Parser
	 */
	public function getMainInstance() {
		if ( $this->mainInstance === null ) {
			$this->mainInstance = $this->create();
		}
		return $this->mainInstance;
	}

	/**
	 * Get the main shared instance, or if it is locked, get a new instance
	 *
	 * @note This function was used to parse wikitext. The instance it
	 * returned should be used only in local scope.  Do not hold a
	 * reference to this parser in class properties.  In general,
	 * avoid using this method and use ::create() instead.
	 *
	 * @since 1.39
	 * @return Parser
	 */
	public function getInstance() {
		$instance = $this->getMainInstance();
		if ( $instance->isLocked() ) {
			$instance = $this->create();
		}
		return $instance;
	}

}

/** @deprecated class alias since 1.43 */
class_alias( ParserFactory::class, 'ParserFactory' );

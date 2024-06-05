<?php

namespace MediaWiki\Rest\Handler\Helper;

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageLookup;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Parser\Parsoid\Config\SiteConfig as ParsoidSiteConfig;
use MediaWiki\Parser\Parsoid\HtmlTransformFactory;
use MediaWiki\Parser\Parsoid\ParsoidParserFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\TitleFormatter;
use Wikimedia\Bcp47Code\Bcp47Code;

/**
 * @since 1.40 Factory for helper objects designed for sharing logic between REST handlers that deal with page content.
 * @unstable during Parsoid migration
 */
class PageRestHelperFactory {

	/**
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = PageContentHelper::CONSTRUCTOR_OPTIONS;

	private ServiceOptions $options;
	private RevisionLookup $revisionLookup;
	private TitleFormatter $titleFormatter;
	private PageLookup $pageLookup;
	private ParsoidOutputStash $parsoidOutputStash;
	private StatsdDataFactoryInterface $stats;
	private ParserOutputAccess $parserOutputAccess;
	private ParsoidSiteConfig $parsoidSiteConfig;
	private ParsoidParserFactory $parsoidParserFactory;
	private HtmlTransformFactory $htmlTransformFactory;
	private IContentHandlerFactory $contentHandlerFactory;
	private LanguageFactory $languageFactory;
	private RedirectStore $redirectStore;
	private LanguageConverterFactory $languageConverterFactory;

	/**
	 * @param ServiceOptions $options
	 * @param RevisionLookup $revisionLookup
	 * @param TitleFormatter $titleFormatter
	 * @param PageLookup $pageLookup
	 * @param ParsoidOutputStash $parsoidOutputStash
	 * @param StatsdDataFactoryInterface $statsDataFactory
	 * @param ParserOutputAccess $parserOutputAccess
	 * @param ParsoidSiteConfig $parsoidSiteConfig
	 * @param ParsoidParserFactory $parsoidParserFactory
	 * @param HtmlTransformFactory $htmlTransformFactory
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param LanguageFactory $languageFactory
	 * @param RedirectStore $redirectStore
	 * @param LanguageConverterFactory $languageConverterFactory
	 */
	public function __construct(
		ServiceOptions $options,
		RevisionLookup $revisionLookup,
		TitleFormatter $titleFormatter,
		PageLookup $pageLookup,
		ParsoidOutputStash $parsoidOutputStash,
		StatsdDataFactoryInterface $statsDataFactory,
		ParserOutputAccess $parserOutputAccess,
		ParsoidSiteConfig $parsoidSiteConfig,
		ParsoidParserFactory $parsoidParserFactory,
		HtmlTransformFactory $htmlTransformFactory,
		IContentHandlerFactory $contentHandlerFactory,
		LanguageFactory $languageFactory,
		RedirectStore $redirectStore,
		LanguageConverterFactory $languageConverterFactory
	) {
		$this->options = $options;
		$this->revisionLookup = $revisionLookup;
		$this->titleFormatter = $titleFormatter;
		$this->pageLookup = $pageLookup;
		$this->parsoidOutputStash = $parsoidOutputStash;
		$this->stats = $statsDataFactory;
		$this->parserOutputAccess = $parserOutputAccess;
		$this->parsoidSiteConfig = $parsoidSiteConfig;
		$this->parsoidParserFactory = $parsoidParserFactory;
		$this->htmlTransformFactory = $htmlTransformFactory;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->languageFactory = $languageFactory;
		$this->redirectStore = $redirectStore;
		$this->languageConverterFactory = $languageConverterFactory;
	}

	public function newRevisionContentHelper(): RevisionContentHelper {
		return new RevisionContentHelper(
			$this->options,
			$this->revisionLookup,
			$this->titleFormatter,
			$this->pageLookup
		);
	}

	public function newPageContentHelper(): PageContentHelper {
		return new PageContentHelper(
			$this->options,
			$this->revisionLookup,
			$this->titleFormatter,
			$this->pageLookup
		);
	}

	/**
	 * Should we ignore page id mismatches between page and revision objects
	 * in HTML/pagebundle requests? Mismatches arise because of page moves.
	 * This is recommended only for handling calls to internal APIs.
	 * @note Since 1.43, passing 'null' for $page has been deprecated.
	 * @note Since 1.43, passing 'null' for $authority has been deprecated.
	 * @note Since 1.43, passing $lenientRevHandling as the first parameter
	 *  has been deprecated.
	 * @param bool|PageIdentity|null $page
	 *  If `false`, this argument is used as the value for $lenientRevHandling,
	 *  for backward-compatibility.
	 * @param array $parameters
	 * @param ?Authority $authority
	 * @param int|RevisionRecord|null $revision
	 * @param bool $lenientRevHandling
	 */
	public function newHtmlOutputRendererHelper(
		$page = null,
		array $parameters = [],
		?Authority $authority = null,
		$revision = null,
		bool $lenientRevHandling = false
	): HtmlOutputRendererHelper {
		if ( is_bool( $page ) ) {
			// Backward compatibility w/ pre-1.43 (deprecated)
			$lenientRevHandling = $page;
			$page = null;
			wfDeprecated( __METHOD__ . ' with boolean first parameter', '1.43' );
		}
		if ( $page === null ) {
			wfDeprecated( __METHOD__ . ' with null $page', '1.43' );
		}
		if ( $authority === null ) {
			wfDeprecated( __METHOD__ . ' with null $authority', '1.43' );
		}
		return new HtmlOutputRendererHelper(
			$this->parsoidOutputStash,
			$this->stats,
			$this->parserOutputAccess,
			$this->pageLookup,
			$this->revisionLookup,
			$this->parsoidSiteConfig,
			$this->parsoidParserFactory,
			$this->htmlTransformFactory,
			$this->contentHandlerFactory,
			$this->languageFactory,
			$page,
			$parameters,
			$authority,
			$revision,
			$lenientRevHandling
		);
	}

	/**
	 * @note Since 1.43, passing a null $page is deprecated.
	 */
	public function newHtmlMessageOutputHelper( ?PageIdentity $page = null ): HtmlMessageOutputHelper {
		if ( $page === null ) {
			wfDeprecated( __METHOD__ . ' with null $page', '1.43' );
		}
		return new HtmlMessageOutputHelper( $page );
	}

	public function newHtmlInputTransformHelper(
		$envOptions = [],
		?PageIdentity $page = null,
		$body = null,
		array $parameters = [],
		?RevisionRecord $originalRevision = null,
		?Bcp47Code $pageLanguage = null
	): HtmlInputTransformHelper {
		if ( $page === null || $body === null ) {
			wfDeprecated( __METHOD__ . ' without $page or $body' );
		}
		return new HtmlInputTransformHelper(
			$this->stats,
			$this->htmlTransformFactory,
			$this->parsoidOutputStash,
			$this->parserOutputAccess,
			$this->pageLookup,
			$this->revisionLookup,
			$envOptions,
			$page,
			$body ?? '',
			$parameters,
			$originalRevision,
			$pageLanguage
		);
	}

	/**
	 * @since 1.41
	 */
	public function newPageRedirectHelper(
		ResponseFactory $responseFactory,
		Router $router,
		string $route,
		RequestInterface $request
	): PageRedirectHelper {
		return new PageRedirectHelper(
			$this->redirectStore,
			$this->titleFormatter,
			$responseFactory,
			$router,
			$route,
			$request,
			$this->languageConverterFactory
		);
	}

}

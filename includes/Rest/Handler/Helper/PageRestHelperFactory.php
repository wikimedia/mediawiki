<?php

namespace MediaWiki\Rest\Handler\Helper;

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Page\PageLookup;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Parser\Parsoid\Config\SiteConfig as ParsoidSiteConfig;
use MediaWiki\Parser\Parsoid\HtmlTransformFactory;
use MediaWiki\Parser\Parsoid\ParsoidParserFactory;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Title\TitleFormatter;

/**
 * @since 1.40 Factory for helper objects designed for sharing logic between REST handlers that deal with page content.
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
	 */
	public function newHtmlOutputRendererHelper(
		bool $lenientRevHandling = false
	): HtmlOutputRendererHelper {
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
			$lenientRevHandling
		);
	}

	public function newHtmlMessageOutputHelper(): HtmlMessageOutputHelper {
		return new HtmlMessageOutputHelper();
	}

	public function newHtmlInputTransformHelper( $envOptions = [] ): HtmlInputTransformHelper {
		return new HtmlInputTransformHelper(
			$this->stats,
			$this->htmlTransformFactory,
			$this->parsoidOutputStash,
			$this->parserOutputAccess,
			$this->pageLookup,
			$this->revisionLookup,
			$envOptions
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

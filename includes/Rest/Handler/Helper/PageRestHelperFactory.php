<?php

namespace MediaWiki\Rest\Handler\Helper;

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Page\PageLookup;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Parser\Parsoid\HtmlTransformFactory;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Revision\RevisionLookup;
use TitleFormatter;

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
	private ParsoidOutputAccess $parsoidOutputAccess;
	private HtmlTransformFactory $htmlTransformFactory;
	private IContentHandlerFactory $contentHandlerFactory;
	private LanguageFactory $languageFactory;
	private RedirectStore $redirectStore;

	/**
	 * @param ServiceOptions $options
	 * @param RevisionLookup $revisionLookup
	 * @param TitleFormatter $titleFormatter
	 * @param PageLookup $pageLookup
	 * @param ParsoidOutputStash $parsoidOutputStash
	 * @param StatsdDataFactoryInterface $statsDataFactory
	 * @param ParsoidOutputAccess $parsoidOutputAccess
	 * @param HtmlTransformFactory $htmlTransformFactory
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param LanguageFactory $languageFactory
	 * @param RedirectStore $redirectStore
	 */
	public function __construct(
		ServiceOptions $options,
		RevisionLookup $revisionLookup,
		TitleFormatter $titleFormatter,
		PageLookup $pageLookup,
		ParsoidOutputStash $parsoidOutputStash,
		StatsdDataFactoryInterface $statsDataFactory,
		ParsoidOutputAccess $parsoidOutputAccess,
		HtmlTransformFactory $htmlTransformFactory,
		IContentHandlerFactory $contentHandlerFactory,
		LanguageFactory $languageFactory,
		RedirectStore $redirectStore
	) {
		$this->options = $options;
		$this->revisionLookup = $revisionLookup;
		$this->titleFormatter = $titleFormatter;
		$this->pageLookup = $pageLookup;
		$this->parsoidOutputStash = $parsoidOutputStash;
		$this->stats = $statsDataFactory;
		$this->parsoidOutputAccess = $parsoidOutputAccess;
		$this->htmlTransformFactory = $htmlTransformFactory;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->languageFactory = $languageFactory;
		$this->redirectStore = $redirectStore;
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

	public function newHtmlOutputRendererHelper(): HtmlOutputRendererHelper {
		return new HtmlOutputRendererHelper(
			$this->parsoidOutputStash,
			$this->stats,
			$this->parsoidOutputAccess,
			$this->htmlTransformFactory,
			$this->contentHandlerFactory,
			$this->languageFactory
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
			$this->parsoidOutputAccess,
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
			$request
		);
	}

}

/** @deprecated since 1.40, remove in 1.41 */
class_alias( PageRestHelperFactory::class, "MediaWiki\\Rest\\Handler\\PageRestHelperFactory" );

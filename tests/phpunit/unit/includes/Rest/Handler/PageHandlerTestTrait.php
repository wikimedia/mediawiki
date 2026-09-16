<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\FileRepo;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\Parsoid\LintErrorChecker;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Parser\Parsoid\ParsoidParserFactory;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Rest\Handler\Helper\HtmlOutputRendererHelper;
use MediaWiki\Rest\Handler\Helper\HtmlShadowOutputHelper;
use MediaWiki\Rest\Handler\Helper\PageContentHelper;
use MediaWiki\Rest\Handler\Helper\PageRedirectHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\Handler\Helper\RevisionContentHelper;
use MediaWiki\Rest\Handler\LanguageLinksHandler;
use MediaWiki\Rest\Handler\PageHandler;
use MediaWiki\Rest\Handler\PageHistoryCountHandler;
use MediaWiki\Rest\Handler\PageHistoryHandler;
use MediaWiki\Rest\Handler\PageHTMLHandler;
use MediaWiki\Rest\Handler\PageLintHandler;
use MediaWiki\Rest\Handler\RevisionLintHandler;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Stats\StatsFactory;

/**
 * A trait providing utility functions for testing Page Handler classes.
 * This trait is intended to be used on subclasses of MediaWikiUnitTestCase
 * or MediaWikiIntegrationTestCase.
 *
 * @stable to use
 */
trait PageHandlerTestTrait {

	private function newRouterForPageHandler( string $baseUrl, string $rootPath = '' ): Router {
		$router = $this->createNoOpMock( Router::class, [ 'getRoutePath', 'getRouteUrl' ] );
		$router->method( 'getRoutePath' )
			->willReturnCallback( static function (
				string $route,
				array $pathParams = [],
				array $queryParams = []
			) use ( $rootPath ) {
				foreach ( $pathParams as $param => $value ) {
					// NOTE: we use rawurlencode here, since execute() uses rawurldecode().
					// Spaces in path params must be encoded to %20 (not +).
					// Slashes must be encoded as %2F.
					$route = str_replace( '{' . $param . '}', rawurlencode( (string)$value ), $route );
				}

				$url = $rootPath . $route;
				return wfAppendQuery( $url, $queryParams );
			} );
		$router->method( 'getRouteUrl' )
			->willReturnCallback( static function (
				string $route,
				array $pathParams = [],
				array $queryParams = []
			) use ( $baseUrl, $router ) {
				return $baseUrl . $router->getRoutePath( $route, $pathParams, $queryParams );
			} );

		return $router;
	}

	/**
	 * Stubs PageRestHelperFactory::newPageRedirectHelper() so that the helper is built
	 * from the arguments the handler passes in, the way the real factory does.
	 *
	 * This matters because the route path and the request are what determine the
	 * redirect target URL: the handler passes its own getRoutePath(), so a redirect
	 * stays on the endpoint the request came in on, and the request supplies the
	 * query parameters that get carried over to the target. A helper pre-built with
	 * a fixed path and an empty request would make both unobservable.
	 *
	 * @param PageRestHelperFactory|MockObject $helperFactory
	 */
	private function mockPageRedirectHelper( $helperFactory ): void {
		$services = $this->getServiceContainer();
		$helperFactory->method( 'newPageRedirectHelper' )
			->willReturnCallback( static fn (
				ResponseFactory $responseFactory,
				Router $router,
				string $pathWithModulePrefix,
				RequestInterface $request
			) => new PageRedirectHelper(
				$services->getRedirectStore(),
				$services->getTitleFormatter(),
				$responseFactory,
				$router,
				$pathWithModulePrefix,
				$request,
				$services->getLanguageConverterFactory()
			) );
	}

	/**
	 * @param Parsoid|MockObject $mockParsoid
	 */
	public function resetServicesWithMockedParsoid( $mockParsoid ): void {
		$services = $this->getServiceContainer();
		$parsoidParser = new ParsoidParser(
			$mockParsoid,
			$services->getParsoidPageConfigFactory(),
			$services->getLanguageConverterFactory(),
			$services->getParsoidSiteConfig(),
			$services->getParsoidDataAccess(),
			$services->getNamespaceInfo(),
			$services->getTrackingCategories(),
		);

		// Create a mock Parsoid factory that returns the ParsoidParser object
		// with the mocked Parsoid object.
		$mockParsoidParserFactory = $this->createNoOpMock( ParsoidParserFactory::class, [ 'create' ] );
		$mockParsoidParserFactory->method( 'create' )->willReturn( $parsoidParser );

		$this->setService( 'ParsoidParserFactory', $mockParsoidParserFactory );
	}

	/**
	 * @return PageHTMLHandler
	 */
	public function newPageHtmlHandler() {
		$services = $this->getServiceContainer();
		$config = [
			MainConfigNames::RightsUrl => 'https://example.com/rights',
			MainConfigNames::RightsText => 'some rights',
			MainConfigNames::ParsoidCacheConfig =>
				MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidCacheConfig )
		];

		$helperFactory = $this->createNoOpMock(
			PageRestHelperFactory::class,
			[ 'newPageContentHelper', 'newHtmlOutputRendererHelper', 'newHtmlShadowOutputHelper', 'newPageRedirectHelper' ]
		);

		$helperFactory->method( 'newPageContentHelper' )
			->willReturnCallback( static fn () => new PageContentHelper(
				new ServiceOptions( PageContentHelper::CONSTRUCTOR_OPTIONS, $config ),
				$services->getRevisionLookup(),
				$services->getTitleFormatter(),
				$services->getPageStore(),
				$services->getTitleFactory(),
				$services->getConnectionProvider(),
				$services->getChangeTagsStore(),
				$services->getShadowPageLoader(),
			) );

		$parsoidOutputStash = $this->getParsoidOutputStash();
		$helperFactory->method( 'newHtmlOutputRendererHelper' )
			->willReturnCallback( static function ( $page, $parameters, $authority, $revision, $lenientRevHandling ) use ( $services, $parsoidOutputStash ) {
				return new HtmlOutputRendererHelper(
					$parsoidOutputStash,
					StatsFactory::newNull(),
					$services->getParserOutputAccess(),
					$services->getPageStore(),
					$services->getRevisionLookup(),
					$services->getRevisionRenderer(),
					$services->getParsoidSiteConfig(),
					$services->getHtmlTransformFactory(),
					$services->getContentHandlerFactory(),
					$services->getLanguageFactory(),
					$page,
					$parameters,
					$authority,
					$revision,
					$lenientRevHandling
				);
			} );
		$helperFactory->method( 'newHtmlShadowOutputHelper' )
			->willReturnCallback( static function ( $page ) use ( $services ) {
				return new HtmlShadowOutputHelper(
					$services->getShadowPageLoader(),
					$services->getTitleFormatter(),
					$services->getParsoidSiteConfig(),
					ParserOptions::newFromAnon(),
					$page
				);
			} );

		$this->mockPageRedirectHelper( $helperFactory );

		return new PageHTMLHandler(
			$helperFactory
		);
	}

	/**
	 * @return PageHandler
	 */
	public function newPageHandler() {
		$services = $this->getServiceContainer();
		$config = [
			MainConfigNames::RightsUrl => 'https://example.com/rights',
			MainConfigNames::RightsText => 'some rights',
			MainConfigNames::ParsoidCacheConfig =>
				MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidCacheConfig )
		];

		$helperFactory = $this->createNoOpMock(
			PageRestHelperFactory::class,
			[ 'newPageContentHelper', 'newHtmlOutputRendererHelper', 'newHtmlShadowOutputHelper', 'newPageRedirectHelper' ]
		);

		$helperFactory->method( 'newPageContentHelper' )
			->willReturnCallback( static fn () => new PageContentHelper(
				new ServiceOptions( PageContentHelper::CONSTRUCTOR_OPTIONS, $config ),
				$services->getRevisionLookup(),
				$services->getTitleFormatter(),
				$services->getPageStore(),
				$services->getTitleFactory(),
				$services->getConnectionProvider(),
				$services->getChangeTagsStore(),
				$services->getShadowPageLoader(),
			) );

		$parsoidOutputStash = $this->getParsoidOutputStash();
		$helperFactory->method( 'newHtmlOutputRendererHelper' )
			->willReturnCallback( static function ( $page, $parameters, $authority, $revision, $lenientRevHandling ) use ( $services, $parsoidOutputStash ) {
				return new HtmlOutputRendererHelper(
					$parsoidOutputStash,
					StatsFactory::newNull(),
					$services->getParserOutputAccess(),
					$services->getPageStore(),
					$services->getRevisionLookup(),
					$services->getRevisionRenderer(),
					$services->getParsoidSiteConfig(),
					$services->getHtmlTransformFactory(),
					$services->getContentHandlerFactory(),
					$services->getLanguageFactory(),
					$page,
					$parameters,
					$authority,
					$revision,
					$lenientRevHandling
				);
			} );
		$helperFactory->method( 'newHtmlShadowOutputHelper' )
			->willReturnCallback( static function ( $page ) use ( $services ) {
				return new HtmlShadowOutputHelper(
					$services->getShadowPageLoader(),
					$services->getTitleFormatter(),
					$services->getParsoidSiteConfig(),
					ParserOptions::newFromAnon(),
					$page
				);
			} );

		$this->mockPageRedirectHelper( $helperFactory );

		return new PageHandler(
			$services->getTitleFormatter(),
			$helperFactory
		);
	}

	/**
	 * @param Parsoid|MockObject|null $parsoid
	 * @return PageLintHandler
	 */
	public function newPageLintHandler( ?Parsoid $parsoid = null ) {
		$services = $this->getServiceContainer();
		$config = [
			MainConfigNames::RightsUrl => 'https://example.com/rights',
			MainConfigNames::RightsText => 'some rights',
			MainConfigNames::ParsoidCacheConfig =>
				MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidCacheConfig )
		];

		$helperFactory = $this->createNoOpMock(
			PageRestHelperFactory::class,
			[ 'newPageContentHelper', 'newPageRedirectHelper' ]
		);

		$helperFactory->method( 'newPageContentHelper' )
			->willReturnCallback( static fn () => new PageContentHelper(
				new ServiceOptions( PageContentHelper::CONSTRUCTOR_OPTIONS, $config ),
				$services->getRevisionLookup(),
				$services->getTitleFormatter(),
				$services->getPageStore(),
				$services->getTitleFactory(),
				$services->getConnectionProvider(),
				$services->getChangeTagsStore(),
				$services->getShadowPageLoader(),
			) );

		$this->mockPageRedirectHelper( $helperFactory );

		$lintErrorChecker = new LintErrorChecker(
			$parsoid ?: $services->get( '_Parsoid' ),
			$services->getParsoidPageConfigFactory(),
			$services->getTitleFactory(),
			ExtensionRegistry::getInstance(),
			$services->getMainConfig(),
		);

		return new PageLintHandler( $helperFactory, $lintErrorChecker );
	}

	/**
	 * @param Parsoid|MockObject|null $parsoid
	 * @return RevisionLintHandler
	 */
	public function newRevisionLintHandler( ?Parsoid $parsoid = null ) {
		$services = $this->getServiceContainer();
		$config = [
			MainConfigNames::RightsUrl => 'https://example.com/rights',
			MainConfigNames::RightsText => 'some rights',
			MainConfigNames::ParsoidCacheConfig =>
				MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidCacheConfig )
		];

		$helperFactory = $this->createNoOpMock(
			PageRestHelperFactory::class,
			[ 'newRevisionContentHelper', 'newPageRedirectHelper' ]
		);

		$helperFactory->method( 'newRevisionContentHelper' )
			->willReturnCallback( static fn () => new RevisionContentHelper(
				new ServiceOptions( PageContentHelper::CONSTRUCTOR_OPTIONS, $config ),
				$services->getRevisionLookup(),
				$services->getTitleFormatter(),
				$services->getPageStore(),
				$services->getTitleFactory(),
				$services->getConnectionProvider(),
				$services->getChangeTagsStore(),
				$services->getShadowPageLoader(),
			) );

		$this->mockPageRedirectHelper( $helperFactory );

		$lintErrorChecker = new LintErrorChecker(
			$parsoid ?: $services->get( '_Parsoid' ),
			$services->getParsoidPageConfigFactory(),
			$services->getTitleFactory(),
			ExtensionRegistry::getInstance(),
			$services->getMainConfig(),
		);

		return new RevisionLintHandler( $helperFactory, $lintErrorChecker );
	}

	public function newPageHistoryHandler(): PageHistoryHandler {
		$services = $this->getServiceContainer();
		return new PageHistoryHandler(
			$services->getRevisionStore(),
			$services->getNameTableStoreFactory(),
			$services->getGroupPermissionsLookup(),
			$services->getConnectionProvider(),
			$services->getPageStore(),
			$services->getTitleFormatter(),
			$services->getPageRestHelperFactory()
		);
	}

	public function newPageHistoryCountHandler(): PageHistoryCountHandler {
		$services = $this->getServiceContainer();
		return new PageHistoryCountHandler(
			$services->getRevisionStore(),
			$services->getNameTableStoreFactory(),
			$services->getGroupPermissionsLookup(),
			$services->getConnectionProvider(),
			new WANObjectCache( [ 'cache' => $this->parserCacheBagOStuff, ] ),
			$services->getPageStore(),
			$services->getPageRestHelperFactory(),
			$services->getTempUserConfig()
		);
	}

	public function newLanguageLinksHandler(): LanguageLinksHandler {
		$services = $this->getServiceContainer();
		return new LanguageLinksHandler(
			$services->getConnectionProvider(),
			$services->getLanguageNameUtils(),
			$services->getTitleFormatter(),
			$services->getTitleParser(),
			$services->getPageStore(),
			$services->getPageRestHelperFactory()
		);
	}

	private function installMockFileRepo(
		string $fileName,
		?string $redirectedFrom = null,
		bool $expectFindFile = true
	): void {
		$repo = $this->createNoOpMock(
			FileRepo::class,
			[]
		);
		$file = $this->createNoOpMock(
			File::class,
			[
				'isLocal',
				'exists',
				'getRepo',
				'getRedirected',
				'getName',
			]
		);
		$file->method( 'isLocal' )->willReturn( false );
		$file->method( 'exists' )->willReturn( true );
		$file->method( 'getRepo' )->willReturn( $repo );
		$file->method( 'getRedirected' )->willReturn( $redirectedFrom );
		$file->method( 'getName' )->willReturn( $fileName );

		$repoGroup = $this->createNoOpMock(
			RepoGroup::class,
			[ 'findFile' ]
		);
		$repoGroup->expects( $expectFindFile ? $this->atLeastOnce() : $this->any() )
			->method( 'findFile' )
			->willReturn( $file );

		$this->setService(
			'RepoGroup',
			$repoGroup
		);
	}

}

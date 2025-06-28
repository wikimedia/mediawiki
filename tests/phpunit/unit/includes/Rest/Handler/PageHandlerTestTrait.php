<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\FileRepo;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Parser\Parsoid\ParsoidParserFactory;
use MediaWiki\Rest\Handler\Helper\HtmlMessageOutputHelper;
use MediaWiki\Rest\Handler\Helper\HtmlOutputRendererHelper;
use MediaWiki\Rest\Handler\Helper\PageContentHelper;
use MediaWiki\Rest\Handler\Helper\PageRedirectHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\Handler\LanguageLinksHandler;
use MediaWiki\Rest\Handler\PageHistoryCountHandler;
use MediaWiki\Rest\Handler\PageHistoryHandler;
use MediaWiki\Rest\Handler\PageHTMLHandler;
use MediaWiki\Rest\Handler\PageSourceHandler;
use MediaWiki\Rest\RequestData;
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
	 * @param Parsoid|MockObject $mockParsoid
	 */
	public function resetServicesWithMockedParsoid( $mockParsoid ): void {
		$services = $this->getServiceContainer();
		$parsoidParser = new ParsoidParser(
			$mockParsoid,
			$services->getParsoidPageConfigFactory(),
			$services->getLanguageConverterFactory(),
			$services->getParsoidDataAccess()
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
	public function newPageHtmlHandler( ?RequestInterface $request = null ) {
		$services = $this->getServiceContainer();
		$config = [
			MainConfigNames::RightsUrl => 'https://example.com/rights',
			MainConfigNames::RightsText => 'some rights',
			MainConfigNames::ParsoidCacheConfig =>
				MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidCacheConfig )
		];

		$helperFactory = $this->createNoOpMock(
			PageRestHelperFactory::class,
			[ 'newPageContentHelper', 'newHtmlOutputRendererHelper', 'newHtmlMessageOutputHelper', 'newPageRedirectHelper' ]
		);

		$helperFactory->method( 'newPageContentHelper' )
			->willReturn( new PageContentHelper(
				new ServiceOptions( PageContentHelper::CONSTRUCTOR_OPTIONS, $config ),
				$services->getRevisionLookup(),
				$services->getTitleFormatter(),
				$services->getPageStore(),
				$services->getTitleFactory(),
				$services->getConnectionProvider(),
				$services->getChangeTagsStore()
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
		$helperFactory->method( 'newHtmlMessageOutputHelper' )
			->willReturnCallback( static function ( $page ) {
				return new HtmlMessageOutputHelper( $page );
			} );

		$request ??= new RequestData( [] );
		$responseFactory = new ResponseFactory( [] );
		$helperFactory->method( 'newPageRedirectHelper' )
			->willReturn(
				new PageRedirectHelper(
					$services->getRedirectStore(),
					$services->getTitleFormatter(),
					$responseFactory,
					$this->newRouterForPageHandler( 'https://example.test/api' ),
					'/test/{title}',
					$request,
					$services->getLanguageConverterFactory()
				)
			);

		return new PageHTMLHandler(
			$helperFactory
		);
	}

	/**
	 * @return PageSourceHandler
	 */
	public function newPageSourceHandler() {
		$services = $this->getServiceContainer();
		return new PageSourceHandler(
			$services->getTitleFormatter(),
			$services->getPageRestHelperFactory()
		);
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

	private function installMockFileRepo( string $fileName, ?string $redirectedFrom = null ): void {
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
		$repoGroup->expects( $this->atLeastOnce() )->method( 'findFile' )
			->willReturn( $file );

		$this->setService(
			'RepoGroup',
			$repoGroup
		);
	}

}

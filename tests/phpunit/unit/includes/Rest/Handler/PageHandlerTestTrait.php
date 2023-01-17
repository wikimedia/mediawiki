<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Json\JsonCodec;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Rest\Handler\Helper\HtmlMessageOutputHelper;
use MediaWiki\Rest\Handler\Helper\HtmlOutputRendererHelper;
use MediaWiki\Rest\Handler\Helper\PageContentHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\Handler\LanguageLinksHandler;
use MediaWiki\Rest\Handler\PageHistoryCountHandler;
use MediaWiki\Rest\Handler\PageHistoryHandler;
use MediaWiki\Rest\Handler\PageHTMLHandler;
use MediaWiki\Rest\Handler\PageSourceHandler;
use NullStatsdDataFactory;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;
use WANObjectCache;
use Wikimedia\Parsoid\Parsoid;

/**
 * A trait providing utility functions for testing Page Handler classes.
 * This trait is intended to be used on subclasses of MediaWikiUnitTestCase
 * or MediaWikiIntegrationTestCase.
 *
 * @stable to use
 * @package MediaWiki\Tests\Rest\Handler
 */
trait PageHandlerTestTrait {
	/**
	 * @param Parsoid|MockObject|null $parsoid
	 *
	 * @return PageHTMLHandler
	 */
	public function newPageHtmlHandler( ?Parsoid $parsoid = null ) {
		$parserCacheFactoryOptions = new ServiceOptions( ParserCacheFactory::CONSTRUCTOR_OPTIONS, [
			'CacheEpoch' => '20200202112233',
			'OldRevisionParserCacheExpireTime' => 60 * 60,
		] );

		$services = $this->getServiceContainer();
		$parserCacheFactory = new ParserCacheFactory(
			$this->parserCacheBagOStuff,
			new WANObjectCache( [ 'cache' => $this->parserCacheBagOStuff, ] ),
			$this->createHookContainer(),
			new JsonCodec(),
			new NullStatsdDataFactory(),
			new NullLogger(),
			$parserCacheFactoryOptions,
			$services->getTitleFactory(),
			$services->getWikiPageFactory()
		);

		$config = [
			'RightsUrl' => 'https://example.com/rights',
			'RightsText' => 'some rights',
			'ParsoidCacheConfig' =>
				MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidCacheConfig )
		];

		$parsoidOutputAccess = new ParsoidOutputAccess(
			new ServiceOptions(
				ParsoidOutputAccess::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig(),
				[ 'ParsoidWikiID' => 'MyWiki' ]
			),
			$parserCacheFactory,
			$services->getPageStore(),
			$services->getRevisionLookup(),
			$services->getGlobalIdGenerator(),
			$services->getStatsdDataFactory(),
			$parsoid ?? new Parsoid(
				$services->get( 'ParsoidSiteConfig' ),
				$services->get( 'ParsoidDataAccess' )
			),
			$services->getParsoidSiteConfig(),
			$services->getParsoidPageConfigFactory(),
			$services->getContentHandlerFactory()
		);

		$helperFactory = $this->createNoOpMock(
			PageRestHelperFactory::class,
			[ 'newPageContentHelper', 'newHtmlOutputRendererHelper', 'newHtmlMessageOutputHelper' ]
		);

		$helperFactory->method( 'newPageContentHelper' )
			->willReturn( new PageContentHelper(
				new ServiceOptions( PageContentHelper::CONSTRUCTOR_OPTIONS, $config ),
				$services->getRevisionLookup(),
				$services->getTitleFormatter(),
				$services->getPageStore()
			) );

		$parsoidOutputStash = $this->getParsoidOutputStash();
		$helperFactory->method( 'newHtmlOutputRendererHelper' )
			->willReturn(
				new HtmlOutputRendererHelper(
					$parsoidOutputStash,
					$services->getStatsdDataFactory(),
					$parsoidOutputAccess,
					$services->getHtmlTransformFactory(),
					$services->getContentHandlerFactory(),
					$services->getLanguageFactory()
				)
			);
		$helperFactory->method( 'newHtmlMessageOutputHelper' )
			->willReturn( new HtmlMessageOutputHelper() );

		return new PageHTMLHandler(
			$services->getTitleFormatter(),
			$services->getRedirectStore(),
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
			$services->getRedirectStore(),
			$services->getPageRestHelperFactory()
		);
	}

	public function newPageHistoryHandler() {
		$services = $this->getServiceContainer();
		return new PageHistoryHandler(
			$services->getRevisionStore(),
			$services->getNameTableStoreFactory(),
			$services->getGroupPermissionsLookup(),
			$services->getDBLoadBalancer(),
			$services->getPageStore(),
			$services->getTitleFormatter()
		);
	}

	public function newPageHistoryCountHandler() {
		$services = $this->getServiceContainer();
		return new PageHistoryCountHandler(
			$services->getRevisionStore(),
			$services->getNameTableStoreFactory(),
			$services->getGroupPermissionsLookup(),
			$services->getDBLoadBalancer(),
			new WANObjectCache( [ 'cache' => $this->parserCacheBagOStuff, ] ),
			$services->getPageStore(),
			$services->getActorMigration(),
			$services->getTitleFormatter()
		);
	}

	public function newLanguageLinksHandler() {
		$services = $this->getServiceContainer();
		return new LanguageLinksHandler(
			$services->getDBLoadBalancer(),
			$services->getLanguageNameUtils(),
			$services->getTitleFormatter(),
			$services->getTitleParser(),
			$services->getPageStore(),
		);
	}

}

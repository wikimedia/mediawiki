<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Json\JsonCodec;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Rest\Handler\HtmlOutputRendererHelper;
use MediaWiki\Rest\Handler\PageContentHelper;
use MediaWiki\Rest\Handler\PageHTMLHandler;
use MediaWiki\Rest\Handler\PageRestHelperFactory;
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
			$services->getParsoidPageConfigFactory()
		);

		$helperFactory = $this->createNoOpMock(
			PageRestHelperFactory::class,
			[ 'newPageContentHelper', 'newHtmlOutputRendererHelper' ]
		);

		$helperFactory->method( 'newPageContentHelper' )
			->willReturn( new PageContentHelper(
				new ServiceOptions( PageContentHelper::CONSTRUCTOR_OPTIONS, $config ),
				$services->getRevisionLookup(),
				$services->getTitleFormatter(),
				$services->getPageStore()
			) );

		$helperFactory->method( 'newHtmlOutputRendererHelper' )
			->willReturn( new HtmlOutputRendererHelper(
				$this->getParsoidOutputStash(),
				$services->getStatsdDataFactory(),
				$parsoidOutputAccess,
				$services->getHtmlTransformFactory(),
				$services->getContentHandlerFactory(),
				$services->getLanguageFactory()
			) );

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

}

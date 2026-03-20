<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Tests\Parser\ParserCacheTestBase;
use MediaWiki\Tests\Parser\TrackerWrapper;
use MediaWiki\Tests\Parser\TrackingParserCache;

/**
 * @group Database
 * @covers \MediaWiki\Page\ParserOutputAccess
 */
class ParserCacheIndependenceTest extends ParserCacheTestBase {
	private TrackerWrapper $trackerWrapper;

	public function setUp(): void {
		parent::setUp();
		$this->trackerWrapper = new TrackerWrapper();
		$this->overrideConfigValues( [
			// always hit the sample code
			MainConfigNames::UsePostprocCacheParsoid => true,
		] );
	}

	public function testPartialInvalidation(): void {
		$parserCacheFactory = $this->createMock( ParserCacheFactory::class );
		$bag = new HashBagOStuff();
		// defining the pcache and postproc-pcache makes it clear that we do not try to access them (otherwise they'd
		// show up in the accesses)
		$caches = [
			'pcache' => $this->getParserCache( 'pcache', $bag ),
			'parsoid-pcache' => $this->getParserCache( 'parsoid-pcache', $bag ),
			'postproc-pcache' => $this->getParserCache( 'postproc-pcache', $bag ),
			'postproc-parsoid-pcache' => $this->getParserCache( 'postproc-parsoid-pcache', $bag ),
		];
		$parserCacheFactory
			->method( 'getParserCache' )
			->willReturnCallback( static fn ( $cacheName ) => $caches[ $cacheName ] );

		$access = $this->getParserOutputAccess( $parserCacheFactory );
		$parserOptions = ParserOptions::newFromAnon();
		$page = $this->getExistingTestPage( __METHOD__ );

		$this->editPage( $page, "__TOC__\n== one ==" );
		$parserOptions->enablePostproc();
		$parserOptions->setUseParsoid();

		// First access: cache is cold
		$page = $this->getExistingTestPage( __METHOD__ );
		$access->getParserOutput( $page, $parserOptions, $page->getRevisionRecord() );
		$this->assertArrayEquals( [
			[ 'postproc-parsoid-pcache', false ],
			[ 'postproc-parsoid-pcache', false ], // selective update sample
			[ 'parsoid-pcache', false ],
			[ 'parsoid-pcache', false ] // selective update sample
		], $this->trackerWrapper->calls );

		// Second access: postproc cache hits
		$this->trackerWrapper->reset();
		$access->clearLocalCache();
		$page = $this->getExistingTestPage( __METHOD__ );
		$access->getParserOutput( $page, $parserOptions, $page->getRevisionRecord() );
		$this->assertArrayEquals( [
			[ 'postproc-parsoid-pcache', true ]
		], $this->trackerWrapper->calls );

		// other post-processing options: hit the primary cache
		$parserOptions->setOption( 'injectTOC', false );
		$this->trackerWrapper->reset();
		$access->clearLocalCache();
		$page = $this->getExistingTestPage( __METHOD__ );
		$access->getParserOutput( $page, $parserOptions, $page->getRevisionRecord() );
		$this->assertArrayEquals( [
			[ 'postproc-parsoid-pcache', false ], // miss on postproc
			[ 'postproc-parsoid-pcache', false ], // selective update check
			[ 'parsoid-pcache', true ] // found it in primary!
		], $this->trackerWrapper->calls );

		// remove from primary - postproc still hits
		$caches['parsoid-pcache']->deleteOptionsKey( $page );
		$this->trackerWrapper->reset();
		$access->clearLocalCache();
		$page = $this->getExistingTestPage( __METHOD__ );
		$access->getParserOutput( $page, $parserOptions, $page->getRevisionRecord() );
		$this->assertArrayEquals( [
			[ 'postproc-parsoid-pcache', true ] // hit on postproc
		], $this->trackerWrapper->calls );

		// new options: we have neither postproc nor primary (since primary hasn't been regenerated)
		$parserOptions = ParserOptions::newFromAnon();
		$parserOptions->setUseParsoid();
		$parserOptions->enablePostproc();
		$parserOptions->setOption( 'enableSectionEditLinks', false );
		$this->trackerWrapper->reset();
		$access->clearLocalCache();
		$page = $this->getExistingTestPage( __METHOD__ );
		$access->getParserOutput( $page, $parserOptions, $page->getRevisionRecord() );
		$this->assertArrayEquals( [
			[ 'postproc-parsoid-pcache', false ], // miss on postproc
			[ 'postproc-parsoid-pcache', false ], // selective update check
			[ 'parsoid-pcache', false ], // miss on primary
			[ 'parsoid-pcache', false ] // selective update check
		], $this->trackerWrapper->calls );

		// now primary has been regenerated
		$parserOptions = $parserOptions->clearPostproc();
		$access->clearLocalCache();
		self::assertNotNull( $access->getCachedParserOutput( $page, $parserOptions, $page->getRevisionRecord() ) );

		// let's drop postproc entries - we still get the primary pcache entries
		$caches['postproc-parsoid-pcache']->deleteOptionsKey( $page );
		$parserOptions = ParserOptions::newFromAnon();
		$parserOptions->setUseParsoid();
		$parserOptions->enablePostproc();
		$this->trackerWrapper->reset();
		$access->clearLocalCache();
		$page = $this->getExistingTestPage( __METHOD__ );
		$access->getParserOutput( $page, $parserOptions, $page->getRevisionRecord() );
		$this->assertArrayEquals( [
			[ 'postproc-parsoid-pcache', false ], // miss on postproc
			[ 'postproc-parsoid-pcache', false ], // selective update check
			[ 'parsoid-pcache', true ], // hit on primary
		], $this->trackerWrapper->calls );

		// still, when the page expires, both caches are invalidated
		// invalidate the cache by indicating the page has been touched one minute after it got cached
		$page = $this->getExistingTestPage( __METHOD__ );
		$cachedTime = $access->getCachedParserOutput( $page, $parserOptions )->getCacheTime();
		$page->getTitle()->invalidateCache(
			strval( ( new DateTime() )->setTimestamp( intval( $cachedTime ) )
				->modify( "+1 min" )
				->getTimestamp() ) );
		// ensure the DB is actually updated
		DeferredUpdates::doUpdates();

		$page = $this->getExistingTestPage( __METHOD__ );
		$access->clearLocalCache();
		$this->trackerWrapper->reset();
		$access->getParserOutput( $page, $parserOptions, $page->getRevisionRecord() );
		$this->assertArrayEquals( [
			[ 'postproc-parsoid-pcache', false ], // miss on postproc
			[ 'postproc-parsoid-pcache', true ], // selective update check
			[ 'parsoid-pcache', false ], // miss on primary
			[ 'parsoid-pcache', true ] // selective update check
		], $this->trackerWrapper->calls );
	}

	protected function createParserCache( ...$args ): ParserCache {
		return new TrackingParserCache( $this->trackerWrapper, ...$args );
	}

	private function getParserOutputAccess( ParserCacheFactory $parserCacheFactory ): ParserOutputAccess {
		return new ParserOutputAccess(
			$this->getServiceContainer()->getMainConfig(),
			$this->getServiceContainer()->getDefaultOutputPipeline(),
			$parserCacheFactory,
			$this->getServiceContainer()->getRevisionLookup(),
			$this->getServiceContainer()->getRevisionRenderer(),
			$this->statsHelper->getStatsFactory(),
			$this->getServiceContainer()->getChronologyProtector(),
			$this->getServiceContainer()->getWikiPageFactory(),
			$this->getServiceContainer()->getTitleFormatter(),
			$this->getServiceContainer()->getTracer(),
			$this->getServiceContainer()->getPoolCounterFactory()
		);
	}
}

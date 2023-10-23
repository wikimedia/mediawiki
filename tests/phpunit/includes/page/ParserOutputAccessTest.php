<?php
use MediaWiki\Json\JsonCodec;
use MediaWiki\Logger\Spi as LoggerSpi;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\Hook\OpportunisticLinksUpdateHook;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Parser\RevisionOutputCache;
use MediaWiki\PoolCounter\PoolCounterFactory;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use MediaWiki\Utils\MWTimestamp;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Page\ParserOutputAccess
 * @group Database
 */
class ParserOutputAccessTest extends MediaWikiIntegrationTestCase {

	private function getHtml( $value ) {
		if ( $value instanceof StatusValue ) {
			$value = $value->getValue();
		}

		if ( $value instanceof ParserOutput ) {
			$value = $value->getText();
		}

		$html = preg_replace( '/<!--.*?-->/s', '', $value );
		$html = trim( preg_replace( '/[\r\n]{2,}/', "\n", $html ) );
		$html = trim( preg_replace( '/\s{2,}/', ' ', $html ) );
		return $html;
	}

	private function assertContainsHtml( $needle, $actual, $msg = '' ) {
		$this->assertNotNull( $actual );

		if ( $actual instanceof StatusValue ) {
			$this->assertStatusOK( $actual, 'isOK' );
		}

		$this->assertStringContainsString( $needle, $this->getHtml( $actual ), $msg );
	}

	private function assertSameHtml( $expected, $actual, $msg = '' ) {
		$this->assertNotNull( $actual );

		if ( $actual instanceof StatusValue ) {
			$this->assertStatusOK( $actual, 'isOK' );
		}

		$this->assertSame( $this->getHtml( $expected ), $this->getHtml( $actual ), $msg );
	}

	private function assertNotSameHtml( $expected, $actual, $msg = '' ) {
		$this->assertNotNull( $actual );

		if ( $actual instanceof StatusValue ) {
			$this->assertStatusOK( $actual, 'isOK' );
		}

		$this->assertNotSame( $this->getHtml( $expected ), $this->getHtml( $actual ), $msg );
	}

	private function getParserCache( $bag = null ) {
		$parserCache = new ParserCache(
			'test',
			$bag ?: new HashBagOStuff(),
			'19900220000000',
			$this->getServiceContainer()->getHookContainer(),
			new JsonCodec(),
			new NullStatsdDataFactory(),
			new NullLogger(),
			$this->getServiceContainer()->getTitleFactory(),
			$this->getServiceContainer()->getWikiPageFactory()
		);

		return $parserCache;
	}

	private function getRevisionOutputCache( $bag = null, $expiry = 3600 ) {
		$wanCache = new WANObjectCache( [ 'cache' => $bag ?: new HashBagOStuff() ] );
		$revisionOutputCache = new RevisionOutputCache(
			'test',
			$wanCache,
			$expiry,
			'19900220000000',
			new JsonCodec(),
			new NullStatsdDataFactory(),
			new NullLogger()
		);

		return $revisionOutputCache;
	}

	/**
	 * @param LoggerInterface|null $logger
	 *
	 * @return LoggerSpi
	 */
	protected function getLoggerSpi( $logger = null ) {
		$logger = $logger ?: new NullLogger();
		$spi = $this->createNoOpMock( LoggerSpi::class, [ 'getLogger' ] );
		$spi->method( 'getLogger' )->willReturn( $logger );
		return $spi;
	}

	/**
	 * @param ParserCache|null $parserCache
	 * @param RevisionOutputCache|null $revisionOutputCache
	 * @param int|bool $maxRenderCalls
	 *
	 * @return ParserOutputAccess
	 * @throws Exception
	 */
	private function getParserOutputAccessWithCache(
		$parserCache = null,
		$revisionOutputCache = null,
		$maxRenderCalls = false
	) {
		if ( !$parserCache ) {
			$parserCache = $this->getParserCache( new HashBagOStuff() );
		}

		if ( !$revisionOutputCache ) {
			$revisionOutputCache = $this->getRevisionOutputCache( new HashBagOStuff() );
		}

		$parserCacheFactory = $this->createMock( ParserCacheFactory::class );
		$parserCacheFactory->method( 'getParserCache' )->willReturn( $parserCache );
		$parserCacheFactory->method( 'getRevisionOutputCache' )->willReturn( $revisionOutputCache );
		return $this->getParserOutputAccessWithCacheFactory(
			$parserCacheFactory,
			$maxRenderCalls
		);
	}

	/**
	 * @param ParserCacheFactory $parserCacheFactory
	 * @param int|bool $maxRenderCalls
	 *
	 * @return ParserOutputAccess
	 * @throws Exception
	 */
	private function getParserOutputAccessWithCacheFactory(
		$parserCacheFactory,
		$maxRenderCalls = false
	) {
		$revRenderer = $this->getServiceContainer()->getRevisionRenderer();

		if ( $maxRenderCalls ) {
			$realRevRenderer = $revRenderer;
			$revRenderer =
				$this->createNoOpMock( RevisionRenderer::class, [ 'getRenderedRevision' ] );

			$revRenderer->expects( $this->atMost( $maxRenderCalls ) )
				->method( 'getRenderedRevision' )
				->willReturnCallback( [ $realRevRenderer, 'getRenderedRevision' ] );
		}

		return new ParserOutputAccess(
			$parserCacheFactory,
			$this->getServiceContainer()->getRevisionLookup(),
			$revRenderer,
			new NullStatsdDataFactory(),
			$this->getServiceContainer()->getDBLoadBalancerFactory(),
			$this->getServiceContainer()->getChronologyProtector(),
			$this->getLoggerSpi(),
			$this->getServiceContainer()->getWikiPageFactory(),
			$this->getServiceContainer()->getTitleFormatter()
		);
	}

	/**
	 * @return ParserOutputAccess
	 */
	private function getParserOutputAccessNoCache() {
		return $this->getParserOutputAccessWithCache(
			$this->getParserCache( new EmptyBagOStuff() ),
			$this->getRevisionOutputCache( new EmptyBagOStuff() )
		);
	}

	/**
	 * @param WikiPage $page
	 * @param string $text
	 *
	 * @return RevisionRecord
	 */
	private function makeFakeRevision( WikiPage $page, $text ) {
		// construct fake revision with no ID
		$content = new WikitextContent( $text );
		$rev = new MutableRevisionRecord( $page->getTitle() );
		$rev->setPageId( $page->getId() );
		$rev->setContent( SlotRecord::MAIN, $content );

		return $rev;
	}

	/**
	 * @return ParserOptions
	 */
	private function getParserOptions() {
		return ParserOptions::newFromAnon();
	}

	/**
	 * Install OpportunisticLinksUpdateHook to inspect whether WikiPage::triggerOpportunisticLinksUpdate
	 * is called or not, the hook implementation will return false disabling the
	 * WikiPage::triggerOpportunisticLinksUpdate to proceed completely.
	 * @param bool $called whether WikiPage::triggerOpportunisticLinksUpdate is expected to be called or not
	 * @return void
	 */
	private function installOpportunisticUpdateHook( bool $called ): void {
		$opportunisticUpdateHook =
			$this->createMock( OpportunisticLinksUpdateHook::class );
		// WikiPage::triggerOpportunisticLinksUpdate is not called by default
		$opportunisticUpdateHook->expects( $this->exactly( $called ? 1 : 0 ) )
			->method( 'onOpportunisticLinksUpdate' )
			->willReturn( false );
		$this->setTemporaryHook( 'OpportunisticLinksUpdate', $opportunisticUpdateHook );
	}

	/**
	 * Tests that we can get rendered output for the latest revision.
	 */
	public function testOutputForLatestRevision() {
		$access = $this->getParserOutputAccessNoCache();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$parserOptions = $this->getParserOptions();
		// WikiPage::triggerOpportunisticLinksUpdate is not called by default
		$this->installOpportunisticUpdateHook( false );
		$status = $access->getParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( 'Hello <i>World</i>!', $status );
	}

	/**
	 * Tests that we can get rendered output for the latest revision.
	 */
	public function testOutputForLatestRevisionWithLinksUpdate() {
		$access = $this->getParserOutputAccessNoCache();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$parserOptions = $this->getParserOptions();
		// With ParserOutputAccess::OPT_LINKS_UPDATE WikiPage::triggerOpportunisticLinksUpdate can be called
		$this->installOpportunisticUpdateHook( true );
		$status = $access->getParserOutput( $page, $parserOptions, null, ParserOutputAccess::OPT_LINKS_UPDATE );
		$this->assertContainsHtml( 'Hello <i>World</i>!', $status );
	}

	/**
	 * Tests that cached output in the ParserCache will be used for the latest revision.
	 */
	public function testLatestRevisionUseCached() {
		// Allow only one render call, use default caches
		$access = $this->getParserOutputAccessWithCache( null, null, 1 );

		$parserOptions = $this->getParserOptions();
		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$access->getParserOutput( $page, $parserOptions );

		// The second call should use cached output
		$status = $access->getParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( 'Hello <i>World</i>!', $status );
	}

	/**
	 * Tests that cached output in the ParserCache will not be used
	 * for the latest revision if the FORCE_PARSE option is given.
	 */
	public function testLatestRevisionForceParse() {
		$parserCache = $this->getParserCache( new HashBagOStuff() );
		$access = $this->getParserOutputAccessWithCache( $parserCache );

		$parserOptions = ParserOptions::newFromAnon();
		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		// Put something else into the cache, so we'd notice if it got used
		$cachedOutput = new ParserOutput( 'Cached Text' );
		$parserCache->save( $cachedOutput, $page, $parserOptions );

		$status = $access->getParserOutput(
			$page,
			$parserOptions,
			null,
			ParserOutputAccess::OPT_FORCE_PARSE
		);
		$this->assertNotSameHtml( $cachedOutput, $status );
		$this->assertContainsHtml( 'Hello <i>World</i>!', $status );
	}

	/**
	 * Tests that an error is reported if the latest revision cannot be loaded.
	 */
	public function testLatestRevisionCantLoad() {
		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$revisionStore = $this->createNoOpMock(
			RevisionStore::class,
			[ 'getRevisionByTitle', 'getKnownCurrentRevision', 'getRevisionById' ]
		);
		$revisionStore->method( 'getRevisionById' )->willReturn( null );
		$revisionStore->method( 'getRevisionByTitle' )->willReturn( null );
		$revisionStore->method( 'getKnownCurrentRevision' )->willReturn( false );
		$this->setService( 'RevisionStore', $revisionStore );
		$this->setService( 'RevisionLookup', $revisionStore );

		$page->clear();

		$access = $this->getParserOutputAccessNoCache();

		$parserOptions = $this->getParserOptions();
		$status = $access->getParserOutput( $page, $parserOptions );
		$this->assertStatusNotOK( $status );
	}

	/**
	 * Tests that getCachedParserOutput() will return previously generated output.
	 */
	public function testGetCachedParserOutput() {
		$access = $this->getParserOutputAccessWithCache();
		$parserOptions = $this->getParserOptions();

		$page = $this->getNonexistingTestPage( __METHOD__ );

		$output = $access->getCachedParserOutput( $page, $parserOptions );
		$this->assertNull( $output );

		$this->editPage( $page, 'Hello \'\'World\'\'!' );
		$access->getParserOutput( $page, $parserOptions );

		$output = $access->getCachedParserOutput( $page, $parserOptions );
		$this->assertNotNull( $output );
		$this->assertContainsHtml( 'Hello <i>World</i>!', $output );
	}

	/**
	 * Tests that getCachedParserOutput() will not return output for current revision when
	 * a fake revision with no ID is supplied.
	 */
	public function testGetCachedParserOutputForFakeRevision() {
		$access = $this->getParserOutputAccessWithCache();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$parserOptions = $this->getParserOptions();
		$access->getParserOutput( $page, $parserOptions );

		$rev = $this->makeFakeRevision( $page, 'fake text' );

		$output = $access->getCachedParserOutput( $page, $parserOptions, $rev );
		$this->assertNull( $output );
	}

	/**
	 * Tests that getPageOutput() will place the generated output for the latest revision
	 * in the parser cache.
	 */
	public function testLatestRevisionIsCached() {
		$access = $this->getParserOutputAccessWithCache();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$parserOptions = $this->getParserOptions();
		$access->getParserOutput( $page, $parserOptions );

		$cachedOutput = $access->getCachedParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( 'World', $cachedOutput );
	}

	/**
	 * Tests that the cache for the current revision is split on parser options.
	 */
	public function testLatestRevisionCacheSplit() {
		$access = $this->getParserOutputAccessWithCache();

		$frenchOptions = ParserOptions::newFromAnon();
		$frenchOptions->setUserLang( 'fr' );

		$tongaOptions = ParserOptions::newFromAnon();
		$tongaOptions->setUserLang( 'to' );

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Test {{int:ok}}!' );

		$frenchResult = $access->getParserOutput( $page, $frenchOptions );
		$this->assertContainsHtml( 'Test', $frenchResult );

		// Check that French output was cached
		$cachedFrenchOutput =
			$access->getCachedParserOutput( $page, $frenchOptions );
		$this->assertNotNull( $cachedFrenchOutput, 'French output should be in the cache' );

		// check that we don't get the French output when asking for Tonga
		$cachedTongaOutput =
			$access->getCachedParserOutput( $page, $tongaOptions );
		$this->assertNull( $cachedTongaOutput, 'Tonga output should not be in the cache yet' );

		// check that we can generate the Tonga output, and it's different from French
		$tongaResult = $access->getParserOutput( $page, $tongaOptions );
		$this->assertContainsHtml( 'Test', $tongaResult );
		$this->assertNotSameHtml(
			$frenchResult,
			$tongaResult,
			'Tonga output should be different from French'
		);

		// check that the Tonga output is cached
		$cachedTongaOutput =
			$access->getCachedParserOutput( $page, $tongaOptions );
		$this->assertNotNull( $cachedTongaOutput, 'Tonga output should be in the cache' );
	}

	/**
	 * Tests that getPageOutput() will place the generated output in the parser cache if the
	 * latest revision is passed explicitly. In other words, thins ensures that the current
	 * revision won't get treated like an old revision.
	 */
	public function testLatestRevisionIsDetectedAndCached() {
		$access = $this->getParserOutputAccessWithCache();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$rev = $this->editPage( $page, 'Hello \'\'World\'\'!' )->getNewRevision();

		// When $rev is passed, it should be detected to be the latest revision.
		$parserOptions = $this->getParserOptions();
		$access->getParserOutput( $page, $parserOptions, $rev );

		$cachedOutput = $access->getCachedParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( 'World', $cachedOutput );
	}

	/**
	 * Tests that getPageOutput() will generate output for an old revision, and
	 * that we still have the output for the current revision cached afterwards.
	 */
	public function testOutputForOldRevision() {
		$access = $this->getParserOutputAccessWithCache();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$firstRev = $this->editPage( $page, 'First' )->getNewRevision();
		$secondRev = $this->editPage( $page, 'Second' )->getNewRevision();

		// output is for the second revision (write to ParserCache)
		$parserOptions = $this->getParserOptions();
		$status = $access->getParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( 'Second', $status );

		// output is for the first revision (not written to ParserCache)
		$status = $access->getParserOutput( $page, $parserOptions, $firstRev );
		$this->assertContainsHtml( 'First', $status );

		// Latest revision is still the one in the ParserCache
		$output = $access->getCachedParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( 'Second', $output );
	}

	/**
	 * Tests that trying to get output for a suppressed old revision is denied.
	 */
	public function testOldRevisionSuppressedDenied() {
		$access = $this->getParserOutputAccessNoCache();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$firstRev = $this->editPage( $page, 'First' )->getNewRevision();
		$secondRev = $this->editPage( $page, 'Second' )->getNewRevision();

		$this->revisionDelete( $firstRev );
		$firstRev =
			$this->getServiceContainer()->getRevisionStore()->getRevisionById( $firstRev->getId() );

		// output is for the first revision denied
		$parserOptions = $this->getParserOptions();
		$status = $access->getParserOutput( $page, $parserOptions, $firstRev );
		$this->assertStatusNotOK( $status );
		// TODO: Once PoolWorkArticleView properly reports errors, check that the correct error
		//       is propagated.
	}

	/**
	 * Tests that getting output for a suppressed old revision is possible when NO_AUDIENCE_CHECK
	 * is set.
	 */
	public function testOldRevisionSuppressedAllowed() {
		$access = $this->getParserOutputAccessNoCache();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$firstRev = $this->editPage( $page, 'First' )->getNewRevision();
		$secondRev = $this->editPage( $page, 'Second' )->getNewRevision();

		$this->revisionDelete( $firstRev );
		$firstRev =
			$this->getServiceContainer()->getRevisionStore()->getRevisionById( $firstRev->getId() );

		// output is for the first revision (even though it's suppressed)
		$parserOptions = $this->getParserOptions();
		$status = $access->getParserOutput(
			$page,
			$parserOptions,
			$firstRev,
			ParserOutputAccess::OPT_NO_AUDIENCE_CHECK
		);
		$this->assertContainsHtml( 'First', $status );

		// even though the output was generated, it wasn't cached, since it's not public
		$cachedOutput = $access->getCachedParserOutput( $page, $parserOptions, $firstRev );
		$this->assertNull( $cachedOutput );
	}

	/**
	 * Tests that output for an old revision is fetched from the secondary parser cache if possible.
	 */
	public function testOldRevisionUseCached() {
		// Allow only one render call, use default caches
		$access = $this->getParserOutputAccessWithCache( null, null, 1 );

		$parserOptions = $this->getParserOptions();
		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'First' );
		$oldRev = $page->getRevisionRecord();

		$this->editPage( $page, 'Second' );

		$firstStatus = $access->getParserOutput( $page, $parserOptions, $oldRev );

		// The second call should use cached output
		$secondStatus = $access->getParserOutput( $page, $parserOptions, $oldRev );
		$this->assertSameHtml( $firstStatus, $secondStatus );
	}

	/**
	 * Tests that output for an old revision is fetched from the secondary parser cache if possible.
	 */
	public function testOldRevisionDisableCached() {
		// Use default caches, but expiry 0 for the secondary cache
		$access = $this->getParserOutputAccessWithCache(
			null,
			$this->getRevisionOutputCache( null, 0 )
		);

		$parserOptions = $this->getParserOptions();
		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'First' );
		$oldRev = $page->getRevisionRecord();

		$this->editPage( $page, 'Second' );
		$access->getParserOutput( $page, $parserOptions, $oldRev );

		// Should not be cached!
		$cachedOutput = $access->getCachedParserOutput( $page, $parserOptions, $oldRev );
		$this->assertNull( $cachedOutput );
	}

	/**
	 * Tests that the secondary cache for output for old revisions is split on parser options.
	 */
	public function testOldRevisionCacheSplit() {
		$access = $this->getParserOutputAccessWithCache();

		$frenchOptions = ParserOptions::newFromAnon();
		$frenchOptions->setUserLang( 'fr' );

		$tongaOptions = ParserOptions::newFromAnon();
		$tongaOptions->setUserLang( 'to' );

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Test {{int:ok}}!' );
		$oldRev = $page->getRevisionRecord();

		$this->editPage( $page, 'Latest Test' );

		$frenchResult = $access->getParserOutput( $page, $frenchOptions, $oldRev );
		$this->assertContainsHtml( 'Test', $frenchResult );

		// Check that French output was cached
		$cachedFrenchOutput =
			$access->getCachedParserOutput( $page, $frenchOptions, $oldRev );
		$this->assertNotNull( $cachedFrenchOutput, 'French output should be in the cache' );

		// check that we don't get the French output when asking for Tonga
		$cachedTongaOutput =
			$access->getCachedParserOutput( $page, $tongaOptions, $oldRev );
		$this->assertNull( $cachedTongaOutput, 'Tonga output should not be in the cache yet' );

		// check that we can generate the Tonga output, and it's different from French
		$tongaResult = $access->getParserOutput( $page, $tongaOptions, $oldRev );
		$this->assertContainsHtml( 'Test', $tongaResult );
		$this->assertNotSameHtml(
			$frenchResult,
			$tongaResult,
			'Tonga output should be different from French'
		);

		// check that the Tonga output is cached
		$cachedTongaOutput =
			$access->getCachedParserOutput( $page, $tongaOptions, $oldRev );
		$this->assertNotNull( $cachedTongaOutput, 'Tonga output should be in the cache' );
	}

	/**
	 * Tests that a RevisionRecord with no ID can be rendered if OPT_NO_CACHE is set.
	 */
	public function testFakeRevisionNoCache() {
		$access = $this->getParserOutputAccessWithCache();

		$page = $this->getExistingTestPage( __METHOD__ );
		$rev = $this->makeFakeRevision( $page, 'fake text' );

		// Render fake
		$parserOptions = $this->getParserOptions();
		$fakeResult = $access->getParserOutput(
			$page,
			$parserOptions,
			$rev,
			ParserOutputAccess::OPT_NO_CACHE
		);
		$this->assertContainsHtml( 'fake text', $fakeResult );

		// check that fake output isn't cached
		$cachedOutput = $access->getCachedParserOutput( $page, $parserOptions );
		if ( $cachedOutput ) {
			// we may have a cache entry for original edit
			$this->assertNotSameHtml( $fakeResult, $cachedOutput );
		}
	}

	/**
	 * Tests that a RevisionRecord with no ID can not be rendered if OPT_NO_CACHE is not set.
	 */
	public function testFakeRevisionError() {
		$access = $this->getParserOutputAccessNoCache();
		$parserOptions = $this->getParserOptions();

		$page = $this->getExistingTestPage( __METHOD__ );
		$rev = $this->makeFakeRevision( $page, 'fake text' );

		// Render should fail
		$this->expectException( InvalidArgumentException::class );
		$access->getParserOutput( $page, $parserOptions, $rev );
	}

	/**
	 * Tests that trying to render a RevisionRecord for another page will throw an exception.
	 */
	public function testPageIdMismatchError() {
		$access = $this->getParserOutputAccessNoCache();
		$parserOptions = $this->getParserOptions();

		$page1 = $this->getExistingTestPage( __METHOD__ . '-1' );
		$page2 = $this->getExistingTestPage( __METHOD__ . '-2' );

		$this->expectException( InvalidArgumentException::class );
		$access->getParserOutput( $page1, $parserOptions, $page2->getRevisionRecord() );
	}

	/**
	 * Tests that trying to render a non-existing page will be reported as an error.
	 */
	public function testNonExistingPage() {
		$access = $this->getParserOutputAccessNoCache();

		$page = $this->getNonexistingTestPage( __METHOD__ );

		$parserOptions = $this->getParserOptions();
		$status = $access->getParserOutput( $page, $parserOptions );
		$this->assertStatusNotOK( $status );
	}

	/**
	 * @param Status $status
	 * @param bool $fastStale
	 * @return PoolCounterFactory
	 */
	private function makePoolCounterFactory( $status, $fastStale = false ) {
		/** @var MockObject|PoolCounter $poolCounter */
		$poolCounter = $this->getMockBuilder( PoolCounter::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'acquireForMe', 'acquireForAnyone', 'release', 'isFastStaleEnabled' ] )
			->getMock();

		$poolCounter->method( 'acquireForMe' )->willReturn( $status );
		$poolCounter->method( 'acquireForAnyone' )->willReturn( $status );
		$poolCounter->method( 'release' )->willReturn( Status::newGood( PoolCounter::RELEASED ) );
		$poolCounter->method( 'isFastStaleEnabled' )->willReturn( $fastStale );

		$pcFactory = $this->getMockBuilder( PoolCounterFactory::class )
			->disableOriginalConstructor()
			->getMock();
		$pcFactory->method( 'create' )->willReturn( $poolCounter );

		return $pcFactory;
	}

	public static function providePoolWorkDirty() {
		yield [ Status::newGood( PoolCounter::QUEUE_FULL ), false, 'view-pool-overload' ];
		yield [ Status::newGood( PoolCounter::TIMEOUT ), false, 'view-pool-overload' ];
		yield [ Status::newGood( PoolCounter::TIMEOUT ), true, 'view-pool-contention' ];
	}

	/**
	 * Tests that under some circumstances, stale cache entries will be returned, but get
	 * flagged as "dirty".
	 *
	 * @dataProvider providePoolWorkDirty
	 */
	public function testPoolWorkDirty( $status, $fastStale, $expectedMessage ) {
		MWTimestamp::setFakeTime( '2020-04-04T01:02:03' );

		$access = $this->getParserOutputAccessWithCache();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$parserOptions = $this->getParserOptions();
		$access->getParserOutput( $page, $parserOptions );
		$testingAccess = TestingAccessWrapper::newFromObject( $access );
		$testingAccess->localCache = [];

		// inject mock PoolCounter status
		$this->overrideConfigValues( [
			MainConfigNames::ParserCacheExpireTime => 60,
		] );
		$this->setService( 'PoolCounterFactory',
			$this->makePoolCounterFactory( $status, $fastStale ) );

		// expire parser cache
		MWTimestamp::setFakeTime( '2020-05-05T01:02:03' );

		$parserOptions = $this->getParserOptions();
		$cachedResult = $access->getParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( 'World', $cachedResult );

		$this->assertStatusWarning( $expectedMessage, $cachedResult );
		$this->assertStatusWarning( 'view-pool-dirty-output', $cachedResult );
	}

	/**
	 * Tests that a failure to acquire a work lock will be reported as an error if no
	 * stale output can be returned.
	 */
	public function testPoolWorkTimeout() {
		$this->overrideConfigValues( [
			MainConfigNames::ParserCacheExpireTime => 60,
		] );
		$this->setService( 'PoolCounterFactory',
			$this->makePoolCounterFactory( Status::newGood( PoolCounter::TIMEOUT ) ) );

		$access = $this->getParserOutputAccessNoCache();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$parserOptions = $this->getParserOptions();
		$result = $access->getParserOutput( $page, $parserOptions );
		$this->assertStatusNotOK( $result );
	}

	/**
	 * Tests that a PoolCounter error does not prevent output from being generated.
	 */
	public function testPoolWorkError() {
		$this->overrideConfigValues( [
			MainConfigNames::ParserCacheExpireTime => 60,
		] );
		$this->setService( 'PoolCounterFactory',
			$this->makePoolCounterFactory( Status::newFatal( 'some-error' ) ) );

		$access = $this->getParserOutputAccessNoCache();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$parserOptions = $this->getParserOptions();
		$result = $access->getParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( 'World', $result );
	}

	public function testParsoidCacheSplit() {
		$parserCacheFactory = $this->createMock( ParserCacheFactory::class );
		$revisionOutputCache = $this->getRevisionOutputCache( new HashBagOStuff() );
		$caches = [
			$this->getParserCache( new HashBagOStuff() ),
			$this->getParserCache( new HashBagOStuff() ),
		];
		$calls = [];
		$parserCacheFactory
			->method( 'getParserCache' )
			->willReturnCallback( static function ( $cacheName ) use ( &$calls, $caches ) {
				static $cacheList = [];
				$calls[] = $cacheName;
				$which = array_search( $cacheName, $cacheList );
				if ( $which === false ) {
					$which = count( $cacheList );
					$cacheList[] = $cacheName;
				}
				return $caches[$which];
			} );
		$parserCacheFactory
			->method( 'getRevisionOutputCache' )
			->willReturn( $revisionOutputCache );

		$access = $this->getParserOutputAccessWithCacheFactory( $parserCacheFactory );
		$parserOptions0 = $this->getParserOptions();
		$page = $this->getNonexistingTestPage( __METHOD__ );
		$output = $access->getCachedParserOutput( $page, $parserOptions0 );
		$this->assertNull( $output );
		// $calls[0] will remember what cache name we used.
		$this->assertCount( 1, $calls );

		$parserOptions1 = $this->getParserOptions();
		$parserOptions1->setUseParsoid();
		$output = $access->getCachedParserOutput( $page, $parserOptions1 );
		$this->assertNull( $output );
		$this->assertCount( 2, $calls );
		// Check that we used a different cache name this time.
		$this->assertNotEquals( $calls[1], $calls[0], "Should use different caches" );

		// Try this again, with actual content.
		$calls = [];
		$this->editPage( $page, "__NOTOC__" );
		$status0 = $access->getParserOutput( $page, $parserOptions0 );
		$this->assertContainsHtml( '<div class="mw-parser-output"></div>', $status0 );
		$status1 = $access->getParserOutput( $page, $parserOptions1 );
		$this->assertContainsHtml( '<meta property="mw:PageProp/notoc"', $status1 );
		$this->assertNotSameHtml( $status0, $status1 );
	}

	public function testParsoidRevisionCacheSplit() {
		$parserCacheFactory = $this->createMock( ParserCacheFactory::class );
		$parserCache = $this->getParserCache( new HashBagOStuff() );
		$caches = [
			$this->getRevisionOutputCache( new HashBagOStuff() ),
			$this->getRevisionOutputCache( new HashBagOStuff() ),
		];
		$calls = [];
		$parserCacheFactory
			->method( 'getParserCache' )
			->willReturn( $parserCache );
		$parserCacheFactory
			->method( 'getRevisionOutputCache' )
			->willReturnCallback( static function ( $cacheName ) use ( &$calls, $caches ) {
				static $cacheList = [];
				$calls[] = $cacheName;
				$which = array_search( $cacheName, $cacheList );
				if ( $which === false ) {
					$which = count( $cacheList );
					$cacheList[] = $cacheName;
				}
				return $caches[$which];
			} );

		$access = $this->getParserOutputAccessWithCacheFactory( $parserCacheFactory );
		$page = $this->getNonexistingTestPage( __METHOD__ );
		$firstRev = $this->editPage( $page, 'First __NOTOC__' )->getNewRevision();
		$secondRev = $this->editPage( $page, 'Second __NOTOC__' )->getNewRevision();

		$parserOptions0 = $this->getParserOptions();
		$status = $access->getParserOutput( $page, $parserOptions0, $firstRev );
		$this->assertContainsHtml( 'First', $status );
		// Check that we used the "not parsoid" revision cache
		$this->assertTrue( count( $calls ) > 0 );
		$notParsoid = $calls[0];
		$this->assertEquals( array_fill( 0, count( $calls ), $notParsoid ), $calls );

		$calls = [];
		$parserOptions1 = $this->getParserOptions();
		$parserOptions1->setUseParsoid();
		$status = $access->getParserOutput( $page, $parserOptions1, $firstRev );
		$this->assertContainsHtml( 'First', $status );
		$this->assertContainsHtml( '<meta property="mw:PageProp/notoc"', $status );
		$this->assertTrue( count( $calls ) > 0 );
		$parsoid = $calls[0];
		$this->assertNotEquals( $notParsoid, $parsoid, "Should use different caches" );
		$this->assertEquals( array_fill( 0, count( $calls ), $parsoid ), $calls );
	}
}

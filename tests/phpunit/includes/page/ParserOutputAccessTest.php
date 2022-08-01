<?php
use MediaWiki\Json\JsonCodec;
use MediaWiki\Logger\Spi as LoggerSpi;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Parser\RevisionOutputCache;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
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
		$html = trim( preg_replace( '/[\r\n]{2,}/s', "\n", $html ) );
		$html = trim( preg_replace( '/\s{2,}/s', ' ', $html ) );
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

		$revRenderer = $this->getServiceContainer()->getRevisionRenderer();
		$lbFactory = $this->getServiceContainer()->getDBLoadBalancerFactory();
		$stats = new NullStatsdDataFactory();

		if ( $maxRenderCalls ) {
			$realRevRenderer = $revRenderer;
			$revRenderer =
				$this->createNoOpMock( RevisionRenderer::class, [ 'getRenderedRevision' ] );

			$revRenderer->expects( $this->atMost( $maxRenderCalls ) )
				->method( 'getRenderedRevision' )
				->willReturnCallback( [ $realRevRenderer, 'getRenderedRevision' ] );
		}

		return new ParserOutputAccess(
			$parserCache,
			$revisionOutputCache,
			$this->getServiceContainer()->getRevisionLookup(),
			$revRenderer,
			$stats,
			$lbFactory,
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
	 * Tests that we can get rendered output for the latest revision.
	 */
	public function testOutputForLatestRevision() {
		$access = $this->getParserOutputAccessNoCache();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$parserOptions = $this->getParserOptions();
		$status = $access->getParserOutput( $page, $parserOptions );
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
		$rev = $this->editPage( $page, 'Hello \'\'World\'\'!' )->getValue()['revision-record'];

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
		$firstRev = $this->editPage( $page, 'First' )->getValue()['revision-record'];
		$secondRev = $this->editPage( $page, 'Second' )->getValue()['revision-record'];

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
		$firstRev = $this->editPage( $page, 'First' )->getValue()['revision-record'];
		$secondRev = $this->editPage( $page, 'Second' )->getValue()['revision-record'];

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
		$firstRev = $this->editPage( $page, 'First' )->getValue()['revision-record'];
		$secondRev = $this->editPage( $page, 'Second' )->getValue()['revision-record'];

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
	 * Tests that a RevisionRecord with no ID can be rendered if NO_CACHE is set.
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
	 * Tests that a RevisionRecord with no ID can not be rendered if NO_CACHE is not set.
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
	 *
	 * @return PoolCounter
	 */
	private function makePoolCounter( $status, $fastStale = false ) {
		/** @var MockObject|PoolCounter $poolCounter */
		$poolCounter = $this->getMockBuilder( PoolCounter::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'acquireForMe', 'acquireForAnyone', 'release', 'isFastStaleEnabled' ] )
			->getMock();

		$poolCounter->method( 'acquireForMe' )->willReturn( $status );
		$poolCounter->method( 'acquireForAnyone' )->willReturn( $status );
		$poolCounter->method( 'release' )->willReturn( Status::newGood( PoolCounter::RELEASED ) );
		$poolCounter->method( 'isFastStaleEnabled' )->willReturn( $fastStale );

		return $poolCounter;
	}

	public function providePoolWorkDirty() {
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
			MainConfigNames::PoolCounterConf => [
				'ArticleView' => [ 'factory' => function () use ( $status, $fastStale ) {
					return $this->makePoolCounter( $status, $fastStale );
				} ],
			]
		] );

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
			MainConfigNames::PoolCounterConf => [
				'ArticleView' => [ 'factory' => function () {
					return $this->makePoolCounter( Status::newGood( PoolCounter::TIMEOUT ) );
				} ],
			]
		] );

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
			MainConfigNames::PoolCounterConf => [
				'ArticleView' => [ 'factory' => function () {
					return $this->makePoolCounter( Status::newFatal( 'some-error' ) );
				} ],
			]
		] );

		$access = $this->getParserOutputAccessNoCache();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$parserOptions = $this->getParserOptions();
		$result = $access->getParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( 'World', $result );
	}

}

<?php

use MediaWiki\Content\WikitextContent;
use MediaWiki\Json\JsonCodec;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logger\Spi as LoggerSpi;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\Hook\OpportunisticLinksUpdateHook;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Page\WikiPage;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserCache;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\RevisionOutputCache;
use MediaWiki\PoolCounter\PoolCounter;
use MediaWiki\PoolCounter\PoolCounterWork;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Utils\MWTimestamp;
use Psr\Log\NullLogger;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Telemetry\TracerInterface;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Page\ParserOutputAccess
 * @group Database
 */
class ParserOutputAccessTest extends MediaWikiIntegrationTestCase {

	public int $actualCallsToPoolWorkArticleView = 0;
	public int $expectedCallsToPoolWorkArticleView = 0;

	public function assertPostConditions(): void {
		$this->assertSame(
			$this->expectedCallsToPoolWorkArticleView,
			$this->actualCallsToPoolWorkArticleView,
			'Calls to newPoolWorkArticleView'
		);

		parent::assertPostConditions();
	}

	private function getHtml( $value ) {
		if ( $value instanceof StatusValue ) {
			$value = $value->getValue();
		}

		if ( $value instanceof ParserOutput ) {
			$pipeline = MediaWikiServices::getInstance()->getDefaultOutputPipeline();
			$value = $pipeline->run( $value, $this->getParserOptions(), [] )->getContentHolderText();
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
			new JsonCodec( $this->getServiceContainer() ),
			StatsFactory::newNull(),
			new NullLogger(),
			$this->getServiceContainer()->getTitleFactory(),
			$this->getServiceContainer()->getWikiPageFactory(),
			$this->getServiceContainer()->getGlobalIdGenerator()
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
			new JsonCodec( $this->getServiceContainer() ),
			StatsFactory::newNull(),
			new NullLogger(),
			$this->getServiceContainer()->getGlobalIdGenerator()
		);

		return $revisionOutputCache;
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
	): ParserOutputAccess {
		return $this->getParserOutputAccess( [
			'parserCache' => $parserCache ?? new HashBagOStuff(),
			'revisionOutputCache' => $revisionOutputCache ?? new HashBagOStuff(),
			'maxRenderCalls' => $maxRenderCalls
		] );
	}

	/**
	 * @param array $options
	 *
	 * @return ParserOutputAccess
	 * @throws Exception
	 */
	private function getParserOutputAccess( array $options = [] ): ParserOutputAccess {
		$parserCacheFactory = $options['parserCacheFactory'] ?? null;
		$maxRenderCalls = $options['maxRenderCalls'] ?? null;
		$parserCache = $options['parserCache'] ?? null;
		$revisionOutputCache = $options['revisionOutputCache'] ?? null;
		$expectPoolCounterCalls = $options['expectPoolCounterCalls'] ?? 0;

		if ( !$parserCacheFactory ) {
			if ( !$parserCache instanceof ParserCache ) {
				$parserCache = $this->getParserCache(
					$parserCache ?? new EmptyBagOStuff()
				);
			}

			if ( !$revisionOutputCache instanceof RevisionOutputCache ) {
				$revisionOutputCache = $this->getRevisionOutputCache(
					$revisionOutputCache ?? new EmptyBagOStuff()
				);
			}

			$parserCacheFactory = $this->createMock( ParserCacheFactory::class );

			$parserCacheFactory->method( 'getParserCache' )
				->willReturn( $parserCache );

			$parserCacheFactory->method( 'getRevisionOutputCache' )
				->willReturn( $revisionOutputCache );
		}

		$revRenderer = $this->getServiceContainer()->getRevisionRenderer();
		if ( $maxRenderCalls ) {
			$realRevRenderer = $revRenderer;

			$revRenderer =
				$this->createNoOpMock( RevisionRenderer::class, [ 'getRenderedRevision' ] );

			$revRenderer->expects( $this->atMost( $maxRenderCalls ) )
				->method( 'getRenderedRevision' )
				->willReturnCallback( [ $realRevRenderer, 'getRenderedRevision' ] );
		}

		$mock = new class (
				$parserCacheFactory,
				$this->getServiceContainer()->getRevisionLookup(),
				$revRenderer,
				$this->getServiceContainer()->getStatsFactory(),
				$this->getServiceContainer()->getDBLoadBalancerFactory(),
				$this->getServiceContainer()->getChronologyProtector(),
				LoggerFactory::getProvider(),
				$this->getServiceContainer()->getWikiPageFactory(),
				$this->getServiceContainer()->getTitleFormatter(),
				$this->getServiceContainer()->getTracer(),
				$this
		) extends ParserOutputAccess {
			private ParserOutputAccessTest $test;

			public function __construct(
				ParserCacheFactory $parserCacheFactory,
				RevisionLookup $revisionLookup,
				RevisionRenderer $revisionRenderer,
				StatsFactory $statsFactory,
				ILBFactory $lbFactory,
				ChronologyProtector $chronologyProtector,
				LoggerSpi $loggerSpi,
				WikiPageFactory $wikiPageFactory,
				TitleFormatter $titleFormatter,
				TracerInterface $tracer,
				ParserOutputAccessTest $test
			) {
				parent::__construct(
					$parserCacheFactory,
					$revisionLookup,
					$revisionRenderer,
					$statsFactory,
					$lbFactory,
					$chronologyProtector,
					$loggerSpi,
					$wikiPageFactory,
					$titleFormatter,
					$tracer
				);

				$this->test = $test;
			}

			protected function newPoolWorkArticleView(
				PageRecord $page,
				ParserOptions $parserOptions,
				RevisionRecord $revision,
				int $options
			): PoolCounterWork {
				$this->test->actualCallsToPoolWorkArticleView++;
				return parent::newPoolWorkArticleView( $page, $parserOptions, $revision, $options );
			}
		};

		$this->expectedCallsToPoolWorkArticleView += $expectPoolCounterCalls;

		return $mock;
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
		$cache = new HashBagOStuff();
		$access = $this->getParserOutputAccess( [
			'parserCache' => $cache
		] );

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$parserOptions = $this->getParserOptions();
		// WikiPage::triggerOpportunisticLinksUpdate is not called by default
		$this->installOpportunisticUpdateHook( false );

		$status = $access->getParserOutput( $page, $parserOptions );
		$this->assertStatusOK( $status );

		/** @var ParserOutput $output */
		$output = $status->getValue();
		$this->assertContainsHtml( 'Hello <i>World</i>!', $output->getRawText() );

		// Check that the output was cached.
		// Create a new instance so we bypass the in-object cache.
		$access = $this->getParserOutputAccess( [
			'parserCache' => $cache
		] );

		$cachedOutput = $access->getCachedParserOutput( $page, $parserOptions );
		$this->assertNotNull( $cachedOutput );
		$this->assertSame( $output->getRawText(), $cachedOutput->getRawText() );
	}

	/**
	 * Tests that we can get rendered output of a redirect, the output is cached,
	 * and the redirect is not followed.
	 */
	public function testOutputForRedirect() {
		$cache = new HashBagOStuff();
		$access = $this->getParserOutputAccess( [
			'parserCache' => $cache
		] );

		$target = $this->getExistingTestPage( __METHOD__ . '_Target' )->getTitle();
		$link = $target->getPrefixedText();

		$page = $this->getNonexistingTestPage( __METHOD__ . '_Redirect' );
		$this->editPage( $page, "#REDIRECT [[$link]]\n\n(redirect footer)" );

		$parserOptions = $this->getParserOptions();

		/** @var ParserOutput $output */
		$output = $access->getParserOutput( $page, $parserOptions )->getValue();
		$this->assertNotNull( $output->getRedirectHeader() );
		$this->assertStringContainsString( 'footer', $output->getRawText() );

		// Check that the output was cached.
		// Create a new instance so we bypass the in-object cache.
		$access = $this->getParserOutputAccess( [
			'parserCache' => $cache
		] );

		$cachedOutput = $access->getCachedParserOutput( $page, $parserOptions );
		$this->assertNotNull( $cachedOutput );
		$this->assertSame( $output->getRedirectHeader(), $cachedOutput->getRedirectHeader() );
	}

	/**
	 * Tests that we can get rendered output for the latest revision.
	 */
	public function testOutputForLatestRevisionUsingPoolCounter() {
		$access = $this->getParserOutputAccess( [
			'expectPoolCounterCalls' => 1
		] );

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$parserOptions = $this->getParserOptions();

		// WikiPage::triggerOpportunisticLinksUpdate is not called by default
		$this->installOpportunisticUpdateHook( false );

		$status = $access->getParserOutput(
			$page,
			$parserOptions,
			null,
			ParserOutputAccess::OPT_FOR_ARTICLE_VIEW
		);
		$this->assertContainsHtml( 'Hello <i>World</i>!', $status );
	}

	/**
	 * Tests that we can get rendered output for the latest revision.
	 */
	public function testOutputForLatestRevisionWithLinksUpdate() {
		$access = $this->getParserOutputAccess();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$parserOptions = $this->getParserOptions();
		// With ParserOutputAccess::OPT_LINKS_UPDATE WikiPage::triggerOpportunisticLinksUpdate can be called
		$this->installOpportunisticUpdateHook( true );
		$status = $access->getParserOutput( $page, $parserOptions, null, ParserOutputAccess::OPT_LINKS_UPDATE );
		$this->assertContainsHtml( 'Hello <i>World</i>!', $status );
	}

	/**
	 * Tests that we can get rendered output for the latest revision.
	 */
	public function testOutputForLatestRevisionWithLinksUpdateWithPoolCounter() {
		$access = $this->getParserOutputAccess( [
			'expectPoolCounterCalls' => 1
		] );

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$parserOptions = $this->getParserOptions();
		// With ParserOutputAccess::OPT_LINKS_UPDATE WikiPage::triggerOpportunisticLinksUpdate can be called
		$this->installOpportunisticUpdateHook( true );

		$status = $access->getParserOutput(
			$page,
			$parserOptions,
			null,
			ParserOutputAccess::OPT_LINKS_UPDATE | ParserOutputAccess::OPT_FOR_ARTICLE_VIEW
		);
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

		$access = $this->getParserOutputAccess();

		$parserOptions = $this->getParserOptions();
		$status = $access->getParserOutput( $page, $parserOptions );
		$this->assertStatusError( 'missing-revision', $status );
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
	 * Tests that getPageOutput() will generate output for an old revision, and
	 * that we still have the output for the current revision cached afterwards.
	 */
	public function testOutputForOldRevisionUsingPoolCounter() {
		$access = $this->getParserOutputAccess( [
			'expectPoolCounterCalls' => 2,
			'parserCache' => new HashBagOStuff(),
			'revisionOutputCache' => new HashBagOStuff()
		] );

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$firstRev = $this->editPage( $page, 'First' )->getNewRevision();
		$secondRev = $this->editPage( $page, 'Second' )->getNewRevision();

		// output is for the second revision (write to ParserCache)
		$parserOptions = $this->getParserOptions();
		$status = $access->getParserOutput(
			$page,
			$parserOptions,
			null,
			ParserOutputAccess::OPT_FOR_ARTICLE_VIEW
		);
		$this->assertContainsHtml( 'Second', $status );

		// output is for the first revision (not written to ParserCache)
		$status = $access->getParserOutput(
			$page,
			$parserOptions,
			$firstRev,
			ParserOutputAccess::OPT_FOR_ARTICLE_VIEW
		);
		$this->assertContainsHtml( 'First', $status );

		// Latest revision is still the one in the ParserCache
		$output = $access->getCachedParserOutput( $page, $parserOptions );
		$this->assertContainsHtml( 'Second', $output );
	}

	/**
	 * Tests that trying to get output for a suppressed old revision is denied.
	 */
	public function testOldRevisionSuppressedDenied() {
		$access = $this->getParserOutputAccess();

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$firstRev = $this->editPage( $page, 'First' )->getNewRevision();
		$secondRev = $this->editPage( $page, 'Second' )->getNewRevision();

		$this->revisionDelete( $firstRev );
		$firstRev =
			$this->getServiceContainer()->getRevisionStore()->getRevisionById( $firstRev->getId() );

		// output is for the first revision denied
		$parserOptions = $this->getParserOptions();
		$status = $access->getParserOutput( $page, $parserOptions, $firstRev );
		$this->assertStatusError( 'missing-revision-permission', $status );
		// TODO: Once PoolWorkArticleView properly reports errors, check that the correct error
		//       is propagated.
	}

	/**
	 * Tests that getting output for a suppressed old revision is possible when NO_AUDIENCE_CHECK
	 * is set.
	 */
	public function testOldRevisionSuppressedAllowed() {
		$access = $this->getParserOutputAccess();

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
	 * Tests that a RevisionRecord with no ID cannot be rendered if OPT_NO_CACHE is not set.
	 */
	public function testFakeRevisionError() {
		$access = $this->getParserOutputAccess();
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
		$access = $this->getParserOutputAccess();
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
		$access = $this->getParserOutputAccess();

		$page = $this->getNonexistingTestPage( __METHOD__ );

		$parserOptions = $this->getParserOptions();
		$status = $access->getParserOutput( $page, $parserOptions );
		$this->assertStatusError( 'nopagetext', $status );
	}

	/**
	 * @param Status $status
	 * @param bool $fastStale
	 */
	private function setPoolCounterFactory( $status, $fastStale = false ) {
		$this->overrideConfigValue( MainConfigNames::PoolCounterConf, [
			'ArticleView' => [
				'class' => MockPoolCounterFailing::class,
				'fastStale' => $fastStale,
				'mockAcquire' => $status,
				'mockRelease' => Status::newGood( PoolCounter::RELEASED ),
			],
		] );
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
		$this->overrideConfigValues( [
			MainConfigNames::ParserCacheExpireTime => 60,
		] );
		$this->setPoolCounterFactory( Status::newGood( PoolCounter::LOCKED ), $fastStale );
		MWTimestamp::setFakeTime( '2020-04-04T01:02:03' );

		$access = $this->getParserOutputAccess( [
			'expectPoolCounterCalls' => 2,
			'parserCache' => new HashBagOStuff()
		] );

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$parserOptions = $this->getParserOptions();
		$result = $access->getParserOutput(
			$page,
			$parserOptions,
			null,
			ParserOutputAccess::OPT_FOR_ARTICLE_VIEW
		);
		$this->assertContainsHtml( 'World', $result, 'fresh result' );

		$testingAccess = TestingAccessWrapper::newFromObject( $access );
		$testingAccess->localCache->clear();

		$this->setPoolCounterFactory( $status, $fastStale );

		// expire parser cache
		MWTimestamp::setFakeTime( '2020-05-05T01:02:03' );

		$parserOptions = $this->getParserOptions();
		$cachedResult = $access->getParserOutput(
			$page,
			$parserOptions,
			null,
			ParserOutputAccess::OPT_FOR_ARTICLE_VIEW
		);
		$this->assertContainsHtml( 'World', $cachedResult, 'cached result' );

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
		$this->setPoolCounterFactory( Status::newGood( PoolCounter::TIMEOUT ) );

		$access = $this->getParserOutputAccess( [
			'expectPoolCounterCalls' => 1
		] );

		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, 'Hello \'\'World\'\'!' );

		$parserOptions = $this->getParserOptions();
		$result = $access->getParserOutput(
			$page,
			$parserOptions,
			null,
			ParserOutputAccess::OPT_FOR_ARTICLE_VIEW
		);
		$this->assertStatusError( 'pool-timeout', $result );
	}

	/**
	 * Tests that a PoolCounter error does not prevent output from being generated.
	 */
	public function testPoolWorkError() {
		$this->overrideConfigValues( [
			MainConfigNames::ParserCacheExpireTime => 60,
		] );
		$this->setPoolCounterFactory( Status::newFatal( 'some-error' ) );

		$access = $this->getParserOutputAccess();

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

		$access = $this->getParserOutputAccess( [
			'parserCacheFactory' => $parserCacheFactory
		] );
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
		$this->assertContainsHtml( '<div class="mw-content-ltr mw-parser-output" lang="en" dir="ltr"></div>', $status0 );
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

		$access = $this->getParserOutputAccess( [
			'parserCacheFactory' => $parserCacheFactory
		] );
		$page = $this->getNonexistingTestPage( __METHOD__ );
		$firstRev = $this->editPage( $page, 'First __NOTOC__' )->getNewRevision();
		$secondRev = $this->editPage( $page, 'Second __NOTOC__' )->getNewRevision();

		$parserOptions0 = $this->getParserOptions();
		$status = $access->getParserOutput( $page, $parserOptions0, $firstRev );
		$this->assertContainsHtml( 'First', $status );
		// Check that we used the "not parsoid" revision cache
		$this->assertNotEmpty( $calls );
		$notParsoid = $calls[0];
		$this->assertEquals( array_fill( 0, count( $calls ), $notParsoid ), $calls );

		$calls = [];
		$parserOptions1 = $this->getParserOptions();
		$parserOptions1->setUseParsoid();
		$status = $access->getParserOutput( $page, $parserOptions1, $firstRev );
		$this->assertContainsHtml( 'First', $status );
		$this->assertContainsHtml( '<meta property="mw:PageProp/notoc"', $status );
		$this->assertNotEmpty( $calls );
		$parsoid = $calls[0];
		$this->assertNotEquals( $notParsoid, $parsoid, "Should use different caches" );
		$this->assertEquals( array_fill( 0, count( $calls ), $parsoid ), $calls );
	}
}

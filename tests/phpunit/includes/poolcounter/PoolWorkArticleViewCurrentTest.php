<?php

use MediaWiki\Json\JsonCodec;
use MediaWiki\Revision\RevisionRecord;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers PoolWorkArticleViewCurrent
 * @group Database
 */
class PoolWorkArticleViewCurrentTest extends PoolWorkArticleViewTest {

	/** @var ParserCache */
	private $parserCache = null;

	/**
	 * @param WikiPage $page
	 * @param RevisionRecord|null $rev
	 * @param ParserOptions|null $options
	 *
	 * @return PoolWorkArticleViewCurrent
	 */
	protected function newPoolWorkArticleView(
		WikiPage $page,
		RevisionRecord $rev = null,
		$options = null
	) {
		if ( !$options ) {
			$options = ParserOptions::newFromAnon();
		}

		if ( !$rev ) {
			$rev = $page->getRevisionRecord();
		}

		$parserCache = $this->parserCache ?: $this->installParserCache();
		$lbFactory = $this->getServiceContainer()->getDBLoadBalancerFactory();
		$revisionRenderer = $this->getServiceContainer()->getRevisionRenderer();

		return new PoolWorkArticleViewCurrent(
			'test:' . $rev->getId(),
			$page,
			$rev,
			$options,
			$revisionRenderer,
			$parserCache,
			$lbFactory,
			$this->getLoggerSpi(),
			$this->getServiceContainer()->getWikiPageFactory()
		);
	}

	private function installParserCache( $bag = null ) {
		$this->parserCache = new ParserCache(
			'test',
			$bag ?: new HashBagOStuff(),
			'',
			$this->getServiceContainer()->getHookContainer(),
			new JsonCodec(),
			$this->getServiceContainer()->getStatsdDataFactory(),
			new NullLogger(),
			$this->getServiceContainer()->getTitleFactory(),
			$this->getServiceContainer()->getWikiPageFactory()
		);

		return $this->parserCache;
	}

	public function testUpdateCachedOutput() {
		$options = ParserOptions::newFromAnon();
		$page = $this->getExistingTestPage( __METHOD__ );

		$parserCache = $this->installParserCache();

		// rendering of a deleted revision should work, audience checks are bypassed
		$work = $this->newPoolWorkArticleView( $page, null, $options );
		/** @var Status $status */
		$status = $work->execute();
		$this->assertTrue( $status->isGood() );

		$cachedOutput = $parserCache->get( $page, $options );
		$this->assertNotEmpty( $cachedOutput );
		$this->assertSame( $status->getValue()->getText(), $cachedOutput->getText() );
	}

	/**
	 * Test that cache miss is not cached in-process, so pool work can fetch
	 * a parse cached by other pool work after waiting for a lock. See T277829
	 */
	public function testFetchAfterMissWithLock() {
		$bag = new HashBagOStuff();
		$options = ParserOptions::newFromAnon();
		$page = $this->getExistingTestPage( __METHOD__ );

		$this->installParserCache( $bag );
		$work1 = $this->newPoolWorkArticleView( $page, null, $options );
		$this->assertFalse( $work1->getCachedWork() );

		// Pretend we're in another process with another ParserCache,
		// but share the backend store
		$this->installParserCache( $bag );
		$work2 = $this->newPoolWorkArticleView( $page, null, $options );
		/** @var Status $status2 */
		$status2 = $work2->execute();
		$this->assertTrue( $status2->isGood() );

		// The parser output cached but $work2 should now be also visible to $work1
		$status1 = $work1->getCachedWork();
		$this->assertInstanceOf( ParserOutput::class, $status1->getValue() );
		$this->assertSame( $status2->getValue()->getText(), $status1->getValue()->getText() );
	}

	public function testFallbackFromOutdatedParserCache() {
		// Fake Unix timestamps
		$lastWrite = 10;
		$outdated = $lastWrite;

		$lbFactory = $this->createNoOpMock( LBFactory::class, [ 'getChronologyProtectorTouched' ] );
		$lbFactory->method( 'getChronologyProtectorTouched' )->willReturn( $lastWrite );

		$output = $this->createNoOpMock( ParserOutput::class, [ 'getCacheTime' ] );
		$output->method( 'getCacheTime' )->willReturn( $outdated );
		$this->parserCache = $this->createNoOpMock( ParserCache::class, [ 'getDirty' ] );
		$this->parserCache->method( 'getDirty' )->willReturn( $output );

		$work = $this->newPoolWorkArticleView(
			$this->createMock( WikiPage::class ),
			$this->createMock( RevisionRecord::class )
		);
		TestingAccessWrapper::newFromObject( $work )->lbFactory = $lbFactory;

		$this->assertFalse( $work->fallback( true ) );

		$status = $work->fallback( false );
		$this->assertTrue( $status->isOK() );
		$this->assertInstanceOf( ParserOutput::class, $status->getValue() );
		$this->assertTrue( $status->hasMessage( 'view-pool-overload' ) );
	}

	public function testFallbackFromMoreRecentParserCache() {
		// Fake Unix timestamps
		$lastWrite = 10;
		$moreRecent = $lastWrite + 1;

		$lbFactory = $this->createNoOpMock( LBFactory::class, [ 'getChronologyProtectorTouched' ] );
		$lbFactory->method( 'getChronologyProtectorTouched' )->willReturn( $lastWrite );

		$output = $this->createNoOpMock( ParserOutput::class, [ 'getCacheTime' ] );
		$output->method( 'getCacheTime' )->willReturn( $moreRecent );
		$this->parserCache = $this->createNoOpMock( ParserCache::class, [ 'getDirty' ] );
		$this->parserCache->method( 'getDirty' )->willReturn( $output );

		$work = $this->newPoolWorkArticleView(
			$this->createMock( WikiPage::class ),
			$this->createMock( RevisionRecord::class )
		);
		TestingAccessWrapper::newFromObject( $work )->lbFactory = $lbFactory;

		$status = $work->fallback( true );
		$this->assertTrue( $status->isOK() );
		$this->assertInstanceOf( ParserOutput::class, $status->getValue() );
		$this->assertTrue( $status->hasMessage( 'view-pool-contention' ) );

		$status = $work->fallback( false );
		$this->assertTrue( $status->isOK() );
		$this->assertInstanceOf( ParserOutput::class, $status->getValue() );
		$this->assertTrue( $status->hasMessage( 'view-pool-overload' ) );
	}

}

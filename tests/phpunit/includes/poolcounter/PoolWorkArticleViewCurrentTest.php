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
			$options = ParserOptions::newCanonical( 'canonical' );
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
		$options = ParserOptions::newCanonical( 'canonical' );
		$page = $this->getExistingTestPage( __METHOD__ );

		$parserCache = $this->installParserCache();

		// rendering of a deleted revision should work, audience checks are bypassed
		$work = $this->newPoolWorkArticleView( $page, null, $options );
		$this->assertTrue( $work->execute() );

		$cachedOutput = $parserCache->get( $page, $options );
		$this->assertNotEmpty( $cachedOutput );
		$this->assertSame( $work->getParserOutput()->getText(), $cachedOutput->getText() );
	}

	/**
	 * Test that cache miss is not cached in-process, so pool work can fetch
	 * a parse cached by other pool work after waiting for a lock. See T277829
	 */
	public function testFetchAfterMissWithLock() {
		$bag = new HashBagOStuff();
		$options = ParserOptions::newCanonical( 'canonical' );
		$page = $this->getExistingTestPage( __METHOD__ );

		$this->installParserCache( $bag );
		$work1 = $this->newPoolWorkArticleView( $page, null, $options );
		$this->assertFalse( $work1->getCachedWork() );

		// Pretend we're in another process with another ParserCache,
		// but share the backend store
		$this->installParserCache( $bag );
		$work2 = $this->newPoolWorkArticleView( $page, null, $options );
		$this->assertTrue( $work2->execute() );

		// The parser output cached but $work2 should now be also visible to $work1
		$work1->getCachedWork();
		$this->assertInstanceOf( ParserOutput::class, $work1->getParserOutput() );
		$this->assertSame( $work2->getParserOutput()->getText(), $work1->getParserOutput()->getText() );
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
		$this->assertFalse( $work->getParserOutput() );
		$this->assertFalse( $work->getIsDirty() );
		$this->assertFalse( $work->getIsFastStale() );

		$this->assertTrue( $work->fallback( false ) );
		$this->assertInstanceOf( ParserOutput::class, $work->getParserOutput() );
		$this->assertTrue( $work->getIsDirty() );
		$this->assertFalse( $work->getIsFastStale() );
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

		$this->assertTrue( $work->fallback( true ) );
		$this->assertInstanceOf( ParserOutput::class, $work->getParserOutput() );
		$this->assertTrue( $work->getIsDirty() );
		$this->assertTrue( $work->getIsFastStale() );

		$this->assertTrue( $work->fallback( false ) );
		$this->assertInstanceOf( ParserOutput::class, $work->getParserOutput() );
		$this->assertTrue( $work->getIsDirty() );
		// FIXME: No, this was not in fast mode (see the `false` above)
		$this->assertTrue( $work->getIsFastStale() );
	}

}

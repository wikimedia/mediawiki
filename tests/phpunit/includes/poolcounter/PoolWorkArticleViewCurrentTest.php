<?php

use MediaWiki\Json\JsonCodec;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserCache;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\PoolCounter\PoolWorkArticleViewCurrent;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Status\Status;
use Psr\Log\NullLogger;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\PoolCounter\PoolWorkArticleViewCurrent
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
		?RevisionRecord $rev = null,
		$options = null
	) {
		if ( !$options ) {
			$options = ParserOptions::newFromAnon();
		}

		if ( !$rev ) {
			$rev = $page->getRevisionRecord();
		}

		$parserCache = $this->parserCache ?: $this->installParserCache();

		$pool = $this->getServiceContainer()->getPoolCounterFactory()->create(
			'ArticleView',
			'test:' . $rev->getId()
		);
		return new PoolWorkArticleViewCurrent(
			$pool,
			$page,
			$rev,
			$options,
			$this->getServiceContainer()->getRevisionRenderer(),
			$parserCache,
			$this->getServiceContainer()->getDBLoadBalancerFactory(),
			$this->getServiceContainer()->getChronologyProtector(),
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
			StatsFactory::newNull(),
			new NullLogger(),
			$this->getServiceContainer()->getTitleFactory(),
			$this->getServiceContainer()->getWikiPageFactory(),
			$this->getServiceContainer()->getGlobalIdGenerator()
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
		$this->assertStatusGood( $status );

		$cachedOutput = $parserCache->get( $page, $options );
		$this->assertNotEmpty( $cachedOutput );
		$this->assertSame( $status->getValue()->getRawText(), $cachedOutput->getRawText() );
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
		$this->assertStatusGood( $status2 );

		// The parser output cached but $work2 should now be also visible to $work1
		$status1 = $work1->getCachedWork();
		$this->assertInstanceOf( ParserOutput::class, $status1->getValue() );
		$this->assertSame( $status2->getValue()->getRawText(), $status1->getValue()->getRawText() );
	}

	public function testFallbackFromOutdatedParserCache() {
		// Fake Unix timestamps
		$lastWrite = 10;
		$outdated = $lastWrite;

		$chronologyProtector = $this->createNoOpMock( ChronologyProtector::class, [ 'getTouched' ] );
		$chronologyProtector->method( 'getTouched' )->willReturn( $lastWrite );

		$output = $this->createNoOpMock( ParserOutput::class, [ 'getCacheTime' ] );
		$output->method( 'getCacheTime' )->willReturn( $outdated );
		$this->parserCache = $this->createNoOpMock( ParserCache::class, [ 'getDirty' ] );
		$this->parserCache->method( 'getDirty' )->willReturn( $output );

		$work = $this->newPoolWorkArticleView(
			$this->createMock( WikiPage::class ),
			$this->createMock( RevisionRecord::class )
		);
		TestingAccessWrapper::newFromObject( $work )->chronologyProtector = $chronologyProtector;

		$this->assertFalse( $work->fallback( true ) );

		$status = $work->fallback( false );
		$this->assertInstanceOf( ParserOutput::class, $status->getValue() );
		$this->assertStatusWarning( 'view-pool-overload', $status );
	}

	public function testFallbackFromMoreRecentParserCache() {
		// Fake Unix timestamps
		$lastWrite = 10;
		$moreRecent = $lastWrite + 1;

		$chronologyProtector = $this->createNoOpMock( ChronologyProtector::class, [ 'getTouched' ] );
		$chronologyProtector->method( 'getTouched' )->willReturn( $lastWrite );

		$output = $this->createNoOpMock( ParserOutput::class, [ 'getCacheTime' ] );
		$output->method( 'getCacheTime' )->willReturn( $moreRecent );
		$this->parserCache = $this->createNoOpMock( ParserCache::class, [ 'getDirty' ] );
		$this->parserCache->method( 'getDirty' )->willReturn( $output );

		$work = $this->newPoolWorkArticleView(
			$this->createMock( WikiPage::class ),
			$this->createMock( RevisionRecord::class )
		);
		TestingAccessWrapper::newFromObject( $work )->chronologyProtector = $chronologyProtector;

		$status = $work->fallback( true );
		$this->assertInstanceOf( ParserOutput::class, $status->getValue() );
		$this->assertStatusWarning( 'view-pool-contention', $status );

		$status = $work->fallback( false );
		$this->assertInstanceOf( ParserOutput::class, $status->getValue() );
		$this->assertStatusWarning( 'view-pool-overload', $status );
	}

}

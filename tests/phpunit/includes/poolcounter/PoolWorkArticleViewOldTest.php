<?php

use MediaWiki\Json\JsonCodec;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\RevisionOutputCache;
use MediaWiki\PoolCounter\PoolWorkArticleViewOld;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Status\Status;
use Psr\Log\NullLogger;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * @covers \MediaWiki\PoolCounter\PoolWorkArticleViewOld
 * @group Database
 */
class PoolWorkArticleViewOldTest extends PoolWorkArticleViewTest {

	/** @var RevisionOutputCache */
	private $cache = null;

	/**
	 * @param WikiPage $page
	 * @param RevisionRecord|null $rev
	 * @param ParserOptions|null $options
	 *
	 * @return PoolWorkArticleViewOld
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

		if ( !$this->cache ) {
			$this->installRevisionOutputCache();
		}

		$renderer = $this->getServiceContainer()->getRevisionRenderer();

		$pool = $this->getServiceContainer()->getPoolCounterFactory()->create(
			'ArticleView',
			'test:' . $rev->getId()
		);
		return new PoolWorkArticleViewOld(
			$pool,
			$this->cache,
			$rev,
			$options,
			$renderer,
			$this->getLoggerSpi()
		);
	}

	/**
	 * @param BagOStuff|null $bag
	 *
	 * @return RevisionOutputCache
	 */
	private function installRevisionOutputCache( $bag = null ) {
		$globalIdGenerator = $this->createMock( GlobalIdGenerator::class );
		$globalIdGenerator->method( 'newUUIDv1' )->willReturn( 'uuid-uuid' );
		$this->cache = new RevisionOutputCache(
			'test',
			new WANObjectCache( [ 'cache' => $bag ?: new HashBagOStuff() ] ),
			60 * 60,
			'20200101223344',
			new JsonCodec(),
			StatsFactory::newNull(),
			new NullLogger(),
			$globalIdGenerator
		);

		return $this->cache;
	}

	public function testUpdateCachedOutput() {
		$options = ParserOptions::newFromAnon();
		$page = $this->getExistingTestPage( __METHOD__ );

		$cache = $this->installRevisionOutputCache();

		$work = $this->newPoolWorkArticleView( $page, null, $options );
		/** @var Status $status */
		$status = $work->execute();
		$this->assertStatusGood( $status );

		$cachedOutput = $cache->get( $page->getRevisionRecord(), $options );
		$this->assertNotEmpty( $cachedOutput );
		$this->assertSame( $status->getValue()->getRawText(),
			$cachedOutput->getRawText() );
	}

	public function testDoesNotCacheNotSafe() {
		$page = $this->getExistingTestPage( __METHOD__ );

		$cache = $this->installRevisionOutputCache();

		$parserOptions = ParserOptions::newFromAnon();
		$parserOptions->setWrapOutputClass( 'wrapwrap' ); // Not safe to cache!

		$work = $this->newPoolWorkArticleView( $page, null, $parserOptions );
		/** @var Status $status */
		$status = $work->execute();
		$this->assertStatusGood( $status );

		$this->assertFalse( $cache->get( $page->getRevisionRecord(), $parserOptions ) );
	}

	public function testDoWorkWithFakeRevision() {
		// PoolWorkArticleViewOld caches the results, but things with null revid should
		// not be cached.
		$this->expectException( InvalidArgumentException::class );
		parent::testDoWorkWithFakeRevision();
	}
}

<?php

use MediaWiki\Json\JsonUnserializer;
use MediaWiki\Revision\RevisionRecord;

/**
 * @covers PoolWorkArticleViewOld
 * @group Database
 */
class PoolWorkArticleViewOldTest extends PoolWorkArticleViewTest {

	/** @var BagOStuff */
	private $cache = null;

	/**
	 * @param WikiPage $page
	 * @param RevisionRecord|null $rev
	 * @param ParserOptions|null $options
	 *
	 * @return PoolWorkArticleView
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

		if ( !$this->cache ) {
			$this->installCache();
		}

		$renderer = $this->getServiceContainer()->getRevisionRenderer();
		$unserializer = new JsonUnserializer();

		return new PoolWorkArticleViewOld(
			'test:' . $rev->getId(),
			60 * 60,
			$this->cache,
			$rev,
			$options,
			$renderer,
			$unserializer,
			$this->getLoggerSpi()
		);
	}

	/**
	 * @param BagOStuff $bag
	 *
	 * @return BagOStuff
	 */
	private function installCache( $bag = null ) {
		$this->cache = $bag ?: new HashBagOStuff();
		return $this->cache;
	}

	public function testUpdateCachedOutput() {
		$page = $this->getExistingTestPage( __METHOD__ );

		$cache = $this->installCache();

		$work = $this->newPoolWorkArticleView( $page );
		$this->assertTrue( $work->execute() );

		$cacheKey = 'test:' . $page->getLatest();

		$cachedJson = $cache->get( $cacheKey );
		$this->assertIsString( $cachedJson );

		$jsonUnserialiezr = new JsonUnserializer();
		$cachedOutput = $jsonUnserialiezr->unserialize( $cachedJson );
		$this->assertNotEmpty( $cachedOutput );

		$this->assertSame( $work->getParserOutput()->getText(), $cachedOutput->getText() );
	}

}

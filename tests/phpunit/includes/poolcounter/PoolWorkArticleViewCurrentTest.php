<?php

use MediaWiki\Json\JsonCodec;
use MediaWiki\Revision\RevisionRecord;
use Psr\Log\NullLogger;

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

}

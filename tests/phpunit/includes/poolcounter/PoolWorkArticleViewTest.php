<?php

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Logger\Spi as LoggerSpi;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\PoolCounter\PoolWorkArticleView;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\PoolCounter\PoolWorkArticleView
 * @group Database
 */
class PoolWorkArticleViewTest extends MediaWikiIntegrationTestCase {

	/**
	 * @param LoggerInterface|null $logger
	 *
	 * @return LoggerSpi
	 */
	protected function getLoggerSpi( $logger = null ) {
		$spi = $this->createNoOpMock( LoggerSpi::class, [ 'getLogger' ] );
		$spi->method( 'getLogger' )->willReturn( $logger ?? new NullLogger() );
		return $spi;
	}

	/**
	 * @param WikiPage $page
	 * @param RevisionRecord|null $rev
	 * @param ParserOptions|null $options
	 *
	 * @return PoolWorkArticleView
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

		$revisionRenderer = $this->getServiceContainer()->getRevisionRenderer();

		$pool = $this->getServiceContainer()->getPoolCounterFactory()->create(
			'ArticleView',
			'test:' . $rev->getId()
		);
		return new PoolWorkArticleView(
			$pool,
			$rev,
			$options,
			$revisionRenderer,
			$this->getLoggerSpi()
		);
	}

	private function makeRevision( WikiPage $page, $text ) {
		$user = $this->getTestUser()->getUser();
		$revision = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new WikitextContent( $text ) )
			->saveRevision( CommentStoreComment::newUnsavedComment( 'testing' ) );

		return $revision;
	}

	public function testDoWorkLoadRevision() {
		$options = ParserOptions::newFromAnon();
		$page = $this->getExistingTestPage( __METHOD__ );
		$rev1 = $this->makeRevision( $page, 'First!' );
		$rev2 = $this->makeRevision( $page, 'Second!' );

		$work = $this->newPoolWorkArticleView( $page, $rev1, $options );
		/** @var Status $status */
		$status = $work->execute();
		$this->assertStringContainsString( 'First', $status->getValue()->getText() );

		$work = $this->newPoolWorkArticleView( $page, $rev2, $options );
		/** @var Status $status */
		$status = $work->execute();
		$this->assertStringContainsString( 'Second', $status->getValue()->getText() );
	}

	public function testDoWorkParserCache() {
		$options = ParserOptions::newFromAnon();
		$page = $this->getExistingTestPage( __METHOD__ );
		$rev1 = $this->makeRevision( $page, 'First!' );

		$work = $this->newPoolWorkArticleView( $page, $rev1, $options );
		$work->execute();

		$cache = $this->getServiceContainer()->getParserCache();
		$out = $cache->get( $page, $options );

		$this->assertNotNull( $out );
		$this->assertNotFalse( $out );
		$this->assertStringContainsString( 'First', $out->getRawText() );
	}

	public function testDoWorkWithFakeRevision() {
		$options = ParserOptions::newFromAnon();
		$page = $this->getExistingTestPage( __METHOD__ );
		$rev = $this->makeRevision( $page, 'NOPE' );

		// Make a fake revision with different content and no revision ID or page ID,
		// and make sure the fake content is used.
		$fakeRev = new MutableRevisionRecord( $page->getTitle() );
		$fakeRev->setContent( SlotRecord::MAIN, new WikitextContent( 'YES!' ) );

		$work = $this->newPoolWorkArticleView( $page, $fakeRev, $options );
		/** @var Status $status */
		$status = $work->execute();

		$text = $status->getValue()->getText();
		$this->assertStringContainsString( 'YES!', $text );
		$this->assertStringNotContainsString( 'NOPE', $text );
	}

	public static function provideMagicWords() {
		yield 'PAGEID' => [
			'Test {{PAGEID}} Test',
			static function ( RevisionRecord $rev ) {
				return $rev->getPageId();
			}
		];
		yield 'REVISIONID' => [
			'Test {{REVISIONID}} Test',
			static function ( RevisionRecord $rev ) {
				return $rev->getId();
			}
		];
		yield 'REVISIONUSER' => [
			'Test {{REVISIONUSER}} Test',
			static function ( RevisionRecord $rev ) {
				return $rev->getUser()->getName();
			}
		];
		yield 'REVISIONTIMESTAMP' => [
			'Test {{REVISIONTIMESTAMP}} Test',
			static function ( RevisionRecord $rev ) {
				return $rev->getTimestamp();
			}
		];
	}

	/**
	 * @dataProvider provideMagicWords
	 */
	public function testMagicWords( $wikitext, $callback ) {
		static $counter = 1;

		$options = ParserOptions::newFromAnon();
		$page = $this->getNonexistingTestPage( __METHOD__ . $counter++ );
		$this->editPage( $page, $wikitext );
		$rev = $page->getRevisionRecord();

		// NOTE: provide the input as a string and let the PoolWorkArticleView create a fake
		// revision internally, to see if the magic words work with that fake. They should
		// work if the Parser causes the actual revision to be loaded when needed.
		$work = $this->newPoolWorkArticleView(
			$page,
			$page->getRevisionRecord(),
			$options,
			false
		);
		/** @var Status $status */
		$status = $work->execute();

		$expected = strval( $callback( $rev ) );
		$output = $status->getValue();

		$this->assertStringContainsString( $expected, $output->getText() );
	}

	public function testDoWorkDeletedContent() {
		$options = ParserOptions::newFromAnon();
		$page = $this->getExistingTestPage( __METHOD__ );
		$rev1 = $page->getRevisionRecord();

		// make another revision, since the latest revision cannot be deleted.
		$rev2 = $this->makeRevision( $page, 'Next' );

		// make a fake revision with deleted different content
		$fakeRev = new MutableRevisionRecord( $page->getTitle() );
		$fakeRev->setId( $rev1->getId() );
		$fakeRev->setPageId( $page->getId() );
		$fakeRev->setContent( SlotRecord::MAIN, new WikitextContent( 'SECRET' ) );
		$fakeRev->setVisibility( RevisionRecord::DELETED_TEXT );

		// rendering of a deleted revision should work, audience checks are bypassed
		$work = $this->newPoolWorkArticleView( $page, $fakeRev, $options );
		/** @var Status $status */
		$status = $work->execute();
		$this->assertStatusGood( $status );
	}

}

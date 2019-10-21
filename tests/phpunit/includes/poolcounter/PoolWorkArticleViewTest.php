<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;

/**
 * @covers PoolWorkArticleView
 * @group Database
 */
class PoolWorkArticleViewTest extends MediaWikiTestCase {

	private function makeRevision( WikiPage $page, $text ) {
		$user = $this->getTestUser()->getUser();
		$updater = $page->newPageUpdater( $user );

		$updater->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );
		return $updater->saveRevision( CommentStoreComment::newUnsavedComment( 'testing' ) );
	}

	public function testDoWorkLoadRevision() {
		$options = ParserOptions::newCanonical( 'canonical' );
		$page = $this->getExistingTestPage( __METHOD__ );
		$rev1 = $this->makeRevision( $page, 'First!' );
		$rev2 = $this->makeRevision( $page, 'Second!' );

		$work = new PoolWorkArticleView( $page, $options, $rev1->getId(), false );
		$work->execute();
		$this->assertContains( 'First', $work->getParserOutput()->getText() );

		$work = new PoolWorkArticleView( $page, $options, $rev2->getId(), false );
		$work->execute();
		$this->assertContains( 'Second', $work->getParserOutput()->getText() );
	}

	public function testDoWorkParserCache() {
		$options = ParserOptions::newCanonical( 'canonical' );
		$page = $this->getExistingTestPage( __METHOD__ );
		$rev1 = $this->makeRevision( $page, 'First!' );

		$work = new PoolWorkArticleView( $page, $options, $rev1->getId(), true );
		$work->execute();

		$cache = MediaWikiServices::getInstance()->getParserCache();
		$out = $cache->get( $page, $options );

		$this->assertNotNull( $out );
		$this->assertNotFalse( $out );
		$this->assertContains( 'First', $out->getText() );
	}

	public function testDoWorkWithExplicitRevision() {
		$options = ParserOptions::newCanonical( 'canonical' );
		$page = $this->getExistingTestPage( __METHOD__ );
		$rev = $this->makeRevision( $page, 'NOPE' );

		// make a fake revision with different content, so we know it's actually being used!
		$fakeRev = new MutableRevisionRecord( $page->getTitle() );
		$fakeRev->setId( $rev->getId() );
		$fakeRev->setPageId( $page->getId() );
		$fakeRev->setContent( SlotRecord::MAIN, new WikitextContent( 'YES!' ) );

		$work = new PoolWorkArticleView( $page, $options, $rev->getId(), false, $fakeRev );
		$work->execute();

		$text = $work->getParserOutput()->getText();
		$this->assertContains( 'YES!', $text );
		$this->assertNotContains( 'NOPE', $text );
	}

	public function testDoWorkWithContent() {
		$options = ParserOptions::newCanonical( 'canonical' );
		$page = $this->getExistingTestPage( __METHOD__ );

		$content = new WikitextContent( 'YES!' );

		$work = new PoolWorkArticleView( $page, $options, $page->getLatest(), false, $content );
		$work->execute();

		$text = $work->getParserOutput()->getText();
		$this->assertContains( 'YES!', $text );
	}

	public function testDoWorkWithString() {
		$options = ParserOptions::newCanonical( 'canonical' );
		$page = $this->getExistingTestPage( __METHOD__ );

		$work = new PoolWorkArticleView( $page, $options, $page->getLatest(), false, 'YES!' );
		$work->execute();

		$text = $work->getParserOutput()->getText();
		$this->assertContains( 'YES!', $text );
	}

	public function provideMagicWords() {
		yield 'PAGEID' => [
			'Test {{PAGEID}} Test',
			function ( RevisionRecord $rev ) {
				return $rev->getPageId();
			}
		];
		yield 'REVISIONID' => [
			'Test {{REVISIONID}} Test',
			function ( RevisionRecord $rev ) {
				return $rev->getId();
			}
		];
		yield 'REVISIONUSER' => [
			'Test {{REVISIONUSER}} Test',
			function ( RevisionRecord $rev ) {
				return $rev->getUser()->getName();
			}
		];
		yield 'REVISIONTIMESTAMP' => [
			'Test {{REVISIONTIMESTAMP}} Test',
			function ( RevisionRecord $rev ) {
				return $rev->getTimestamp();
			}
		];
	}

	/**
	 * @dataProvider provideMagicWords
	 */
	public function testMagicWords( $wikitext, $callback ) {
		$options = ParserOptions::newCanonical( 'canonical' );
		$page = $this->getExistingTestPage( __METHOD__ );
		$rev = $page->getRevision()->getRevisionRecord();

		// NOTE: provide the input as a string and let the PoolWorkArticleView create a fake
		// revision internally, to see if the magic words work with that fake. They should
		// work if the Parser causes the actual revision to be loaded when needed.
		$work = new PoolWorkArticleView( $page, $options, $page->getLatest(), false, $wikitext );
		$work->execute();

		$expected = strval( $callback( $rev ) );
		$output = $work->getParserOutput();

		$this->assertContains( $expected, $output->getText() );
	}

	public function testDoWorkMissingPage() {
		$options = ParserOptions::newCanonical( 'canonical' );
		$page = $this->getNonexistingTestPage();

		$work = new PoolWorkArticleView( $page, $options, '667788', false );
		$this->assertFalse( $work->execute() );
	}

	public function testDoWorkDeletedContent() {
		$options = ParserOptions::newCanonical( 'canonical' );
		$page = $this->getExistingTestPage( __METHOD__ );
		$rev1 = $page->getRevision()->getRevisionRecord();

		// make another revision, since the latest revision cannot be deleted.
		$rev2 = $this->makeRevision( $page, 'Next' );

		// make a fake revision with deleted different content
		$fakeRev = new MutableRevisionRecord( $page->getTitle() );
		$fakeRev->setId( $rev1->getId() );
		$fakeRev->setPageId( $page->getId() );
		$fakeRev->setContent( SlotRecord::MAIN, new WikitextContent( 'SECRET' ) );
		$fakeRev->setVisibility( RevisionRecord::DELETED_TEXT );

		$work = new PoolWorkArticleView( $page, $options, $rev1->getId(), false, $fakeRev );
		$this->assertFalse( $work->execute() );

		$work = new PoolWorkArticleView( $page, $options, $rev1->getId(), false, $fakeRev,
			RevisionRecord::RAW );
		$this->assertNotFalse( $work->execute() );

		// a deleted current revision should still be show
		$fakeRev->setId( $rev2->getId() );
		$work = new PoolWorkArticleView( $page, $options, $rev2->getId(), false, $fakeRev );
		$this->assertNotFalse( $work->execute() );
	}

}

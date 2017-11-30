<?php

abstract class WikiPageDbTestBase extends MediaWikiLangTestCase {

	private $pagesToDelete;

	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'page',
				'revision',
				'archive',
				'ip_changes',
				'text',

				'recentchanges',
				'logging',

				'page_props',
				'pagelinks',
				'categorylinks',
				'langlinks',
				'externallinks',
				'imagelinks',
				'templatelinks',
				'iwlinks' ] );
	}

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( 'wgContentHandlerUseDB', $this->getContentHandlerUseDB() );
		$this->pagesToDelete = [];
	}

	protected function tearDown() {
		foreach ( $this->pagesToDelete as $p ) {
			/* @var $p WikiPage */

			try {
				if ( $p->exists() ) {
					$p->doDeleteArticle( "testing done." );
				}
			} catch ( MWException $ex ) {
				// fail silently
			}
		}
		parent::tearDown();
	}

	abstract protected function getContentHandlerUseDB();

	/**
	 * @param Title|string $title
	 * @param string|null $model
	 * @return WikiPage
	 */
	private function newPage( $title, $model = null ) {
		if ( is_string( $title ) ) {
			$ns = $this->getDefaultWikitextNS();
			$title = Title::newFromText( $title, $ns );
		}

		$p = new WikiPage( $title );

		$this->pagesToDelete[] = $p;

		return $p;
	}

	/**
	 * @param string|Title|WikiPage $page
	 * @param string $text
	 * @param int $model
	 *
	 * @return WikiPage
	 */
	protected function createPage( $page, $text, $model = null ) {
		if ( is_string( $page ) || $page instanceof Title ) {
			$page = $this->newPage( $page, $model );
		}

		$content = ContentHandler::makeContent( $text, $page->getTitle(), $model );
		$page->doEditContent( $content, "testing", EDIT_NEW );

		return $page;
	}

	/**
	 * @covers WikiPage::doEditContent
	 * @covers WikiPage::doModify
	 * @covers WikiPage::doCreate
	 * @covers WikiPage::doEditUpdates
	 */
	public function testDoEditContent() {
		$page = $this->newPage( __METHOD__ );
		$title = $page->getTitle();

		$content = ContentHandler::makeContent(
			"[[Lorem ipsum]] dolor sit amet, consetetur sadipscing elitr, sed diam "
				. " nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.",
			$title,
			CONTENT_MODEL_WIKITEXT
		);

		$page->doEditContent( $content, "[[testing]] 1" );

		$this->assertTrue( $title->getArticleID() > 0, "Title object should have new page id" );
		$this->assertTrue( $page->getId() > 0, "WikiPage should have new page id" );
		$this->assertTrue( $title->exists(), "Title object should indicate that the page now exists" );
		$this->assertTrue( $page->exists(), "WikiPage object should indicate that the page now exists" );

		$id = $page->getId();

		# ------------------------
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'pagelinks', '*', [ 'pl_from' => $id ] );
		$n = $res->numRows();
		$res->free();

		$this->assertEquals( 1, $n, 'pagelinks should contain one link from the page' );

		# ------------------------
		$page = new WikiPage( $title );

		$retrieved = $page->getContent();
		$this->assertTrue( $content->equals( $retrieved ), 'retrieved content doesn\'t equal original' );

		# ------------------------
		$content = ContentHandler::makeContent(
			"At vero eos et accusam et justo duo [[dolores]] et ea rebum. "
				. "Stet clita kasd [[gubergren]], no sea takimata sanctus est.",
			$title,
			CONTENT_MODEL_WIKITEXT
		);

		$page->doEditContent( $content, "testing 2" );

		# ------------------------
		$page = new WikiPage( $title );

		$retrieved = $page->getContent();
		$this->assertTrue( $content->equals( $retrieved ), 'retrieved content doesn\'t equal original' );

		# ------------------------
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'pagelinks', '*', [ 'pl_from' => $id ] );
		$n = $res->numRows();
		$res->free();

		$this->assertEquals( 2, $n, 'pagelinks should contain two links from the page' );
	}

	/**
	 * @covers WikiPage::doDeleteArticle
	 * @covers WikiPage::doDeleteArticleReal
	 */
	public function testDoDeleteArticle() {
		$page = $this->createPage(
			__METHOD__,
			"[[original text]] foo",
			CONTENT_MODEL_WIKITEXT
		);
		$id = $page->getId();

		$page->doDeleteArticle( "testing deletion" );

		$this->assertFalse(
			$page->getTitle()->getArticleID() > 0,
			"Title object should now have page id 0"
		);
		$this->assertFalse( $page->getId() > 0, "WikiPage should now have page id 0" );
		$this->assertFalse(
			$page->exists(),
			"WikiPage::exists should return false after page was deleted"
		);
		$this->assertNull(
			$page->getContent(),
			"WikiPage::getContent should return null after page was deleted"
		);

		$t = Title::newFromText( $page->getTitle()->getPrefixedText() );
		$this->assertFalse(
			$t->exists(),
			"Title::exists should return false after page was deleted"
		);

		// Run the job queue
		JobQueueGroup::destroySingletons();
		$jobs = new RunJobs;
		$jobs->loadParamsAndArgs( null, [ 'quiet' => true ], null );
		$jobs->execute();

		# ------------------------
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'pagelinks', '*', [ 'pl_from' => $id ] );
		$n = $res->numRows();
		$res->free();

		$this->assertEquals( 0, $n, 'pagelinks should contain no more links from the page' );
	}

	/**
	 * @covers WikiPage::doDeleteUpdates
	 */
	public function testDoDeleteUpdates() {
		$page = $this->createPage(
			__METHOD__,
			"[[original text]] foo",
			CONTENT_MODEL_WIKITEXT
		);
		$id = $page->getId();

		// Similar to MovePage logic
		wfGetDB( DB_MASTER )->delete( 'page', [ 'page_id' => $id ], __METHOD__ );
		$page->doDeleteUpdates( $id );

		// Run the job queue
		JobQueueGroup::destroySingletons();
		$jobs = new RunJobs;
		$jobs->loadParamsAndArgs( null, [ 'quiet' => true ], null );
		$jobs->execute();

		# ------------------------
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'pagelinks', '*', [ 'pl_from' => $id ] );
		$n = $res->numRows();
		$res->free();

		$this->assertEquals( 0, $n, 'pagelinks should contain no more links from the page' );
	}

	/**
	 * @covers WikiPage::getRevision
	 */
	public function testGetRevision() {
		$page = $this->newPage( __METHOD__ );

		$rev = $page->getRevision();
		$this->assertNull( $rev );

		# -----------------
		$this->createPage( $page, "some text", CONTENT_MODEL_WIKITEXT );

		$rev = $page->getRevision();

		$this->assertEquals( $page->getLatest(), $rev->getId() );
		$this->assertEquals( "some text", $rev->getContent()->getNativeData() );
	}

	/**
	 * @covers WikiPage::getContent
	 */
	public function testGetContent() {
		$page = $this->newPage( __METHOD__ );

		$content = $page->getContent();
		$this->assertNull( $content );

		# -----------------
		$this->createPage( $page, "some text", CONTENT_MODEL_WIKITEXT );

		$content = $page->getContent();
		$this->assertEquals( "some text", $content->getNativeData() );
	}

	/**
	 * @covers WikiPage::exists
	 */
	public function testExists() {
		$page = $this->newPage( __METHOD__ );
		$this->assertFalse( $page->exists() );

		# -----------------
		$this->createPage( $page, "some text", CONTENT_MODEL_WIKITEXT );
		$this->assertTrue( $page->exists() );

		$page = new WikiPage( $page->getTitle() );
		$this->assertTrue( $page->exists() );

		# -----------------
		$page->doDeleteArticle( "done testing" );
		$this->assertFalse( $page->exists() );

		$page = new WikiPage( $page->getTitle() );
		$this->assertFalse( $page->exists() );
	}

	public function provideHasViewableContent() {
		return [
			[ 'WikiPageTest_testHasViewableContent', false, true ],
			[ 'Special:WikiPageTest_testHasViewableContent', false ],
			[ 'MediaWiki:WikiPageTest_testHasViewableContent', false ],
			[ 'Special:Userlogin', true ],
			[ 'MediaWiki:help', true ],
		];
	}

	/**
	 * @dataProvider provideHasViewableContent
	 * @covers WikiPage::hasViewableContent
	 */
	public function testHasViewableContent( $title, $viewable, $create = false ) {
		$page = $this->newPage( $title );
		$this->assertEquals( $viewable, $page->hasViewableContent() );

		if ( $create ) {
			$this->createPage( $page, "some text", CONTENT_MODEL_WIKITEXT );
			$this->assertTrue( $page->hasViewableContent() );

			$page = new WikiPage( $page->getTitle() );
			$this->assertTrue( $page->hasViewableContent() );
		}
	}

	public function provideGetRedirectTarget() {
		return [
			[ 'WikiPageTest_testGetRedirectTarget_1', CONTENT_MODEL_WIKITEXT, "hello world", null ],
			[
				'WikiPageTest_testGetRedirectTarget_2',
				CONTENT_MODEL_WIKITEXT,
				"#REDIRECT [[hello world]]",
				"Hello world"
			],
		];
	}

	/**
	 * @dataProvider provideGetRedirectTarget
	 * @covers WikiPage::getRedirectTarget
	 */
	public function testGetRedirectTarget( $title, $model, $text, $target ) {
		$this->setMwGlobals( [
			'wgCapitalLinks' => true,
		] );

		$page = $this->createPage( $title, $text, $model );

		# sanity check, because this test seems to fail for no reason for some people.
		$c = $page->getContent();
		$this->assertEquals( 'WikitextContent', get_class( $c ) );

		# now, test the actual redirect
		$t = $page->getRedirectTarget();
		$this->assertEquals( $target, is_null( $t ) ? null : $t->getPrefixedText() );
	}

	/**
	 * @dataProvider provideGetRedirectTarget
	 * @covers WikiPage::isRedirect
	 */
	public function testIsRedirect( $title, $model, $text, $target ) {
		$page = $this->createPage( $title, $text, $model );
		$this->assertEquals( !is_null( $target ), $page->isRedirect() );
	}

	public function provideIsCountable() {
		return [

			// any
			[ 'WikiPageTest_testIsCountable',
				CONTENT_MODEL_WIKITEXT,
				'',
				'any',
				true
			],
			[ 'WikiPageTest_testIsCountable',
				CONTENT_MODEL_WIKITEXT,
				'Foo',
				'any',
				true
			],

			// comma
			[ 'WikiPageTest_testIsCountable',
				CONTENT_MODEL_WIKITEXT,
				'Foo',
				'comma',
				false
			],
			[ 'WikiPageTest_testIsCountable',
				CONTENT_MODEL_WIKITEXT,
				'Foo, bar',
				'comma',
				true
			],

			// link
			[ 'WikiPageTest_testIsCountable',
				CONTENT_MODEL_WIKITEXT,
				'Foo',
				'link',
				false
			],
			[ 'WikiPageTest_testIsCountable',
				CONTENT_MODEL_WIKITEXT,
				'Foo [[bar]]',
				'link',
				true
			],

			// redirects
			[ 'WikiPageTest_testIsCountable',
				CONTENT_MODEL_WIKITEXT,
				'#REDIRECT [[bar]]',
				'any',
				false
			],
			[ 'WikiPageTest_testIsCountable',
				CONTENT_MODEL_WIKITEXT,
				'#REDIRECT [[bar]]',
				'comma',
				false
			],
			[ 'WikiPageTest_testIsCountable',
				CONTENT_MODEL_WIKITEXT,
				'#REDIRECT [[bar]]',
				'link',
				false
			],

			// not a content namespace
			[ 'Talk:WikiPageTest_testIsCountable',
				CONTENT_MODEL_WIKITEXT,
				'Foo',
				'any',
				false
			],
			[ 'Talk:WikiPageTest_testIsCountable',
				CONTENT_MODEL_WIKITEXT,
				'Foo, bar',
				'comma',
				false
			],
			[ 'Talk:WikiPageTest_testIsCountable',
				CONTENT_MODEL_WIKITEXT,
				'Foo [[bar]]',
				'link',
				false
			],

			// not a content namespace, different model
			[ 'MediaWiki:WikiPageTest_testIsCountable.js',
				null,
				'Foo',
				'any',
				false
			],
			[ 'MediaWiki:WikiPageTest_testIsCountable.js',
				null,
				'Foo, bar',
				'comma',
				false
			],
			[ 'MediaWiki:WikiPageTest_testIsCountable.js',
				null,
				'Foo [[bar]]',
				'link',
				false
			],
		];
	}

	/**
	 * @dataProvider provideIsCountable
	 * @covers WikiPage::isCountable
	 */
	public function testIsCountable( $title, $model, $text, $mode, $expected ) {
		global $wgContentHandlerUseDB;

		$this->setMwGlobals( 'wgArticleCountMethod', $mode );

		$title = Title::newFromText( $title );

		if ( !$wgContentHandlerUseDB
			&& $model
			&& ContentHandler::getDefaultModelFor( $title ) != $model
		) {
			$this->markTestSkipped( "Can not use non-default content model $model for "
				. $title->getPrefixedDBkey() . " with \$wgContentHandlerUseDB disabled." );
		}

		$page = $this->createPage( $title, $text, $model );

		$editInfo = $page->prepareContentForEdit( $page->getContent() );

		$v = $page->isCountable();
		$w = $page->isCountable( $editInfo );

		$this->assertEquals(
			$expected,
			$v,
			"isCountable( null ) returned unexpected value " . var_export( $v, true )
				. " instead of " . var_export( $expected, true )
			. " in mode `$mode` for text \"$text\""
		);

		$this->assertEquals(
			$expected,
			$w,
			"isCountable( \$editInfo ) returned unexpected value " . var_export( $v, true )
				. " instead of " . var_export( $expected, true )
			. " in mode `$mode` for text \"$text\""
		);
	}

	public function provideGetParserOutput() {
		return [
			[
				CONTENT_MODEL_WIKITEXT,
				"hello ''world''\n",
				"<div class=\"mw-parser-output\"><p>hello <i>world</i></p></div>"
			],
			// @todo more...?
		];
	}

	/**
	 * @dataProvider provideGetParserOutput
	 * @covers WikiPage::getParserOutput
	 */
	public function testGetParserOutput( $model, $text, $expectedHtml ) {
		$page = $this->createPage( __METHOD__, $text, $model );

		$opt = $page->makeParserOptions( 'canonical' );
		$po = $page->getParserOutput( $opt );
		$text = $po->getText();

		$text = trim( preg_replace( '/<!--.*?-->/sm', '', $text ) ); # strip injected comments
		$text = preg_replace( '!\s*(</p>|</div>)!sm', '\1', $text ); # don't let tidy confuse us

		$this->assertEquals( $expectedHtml, $text );

		return $po;
	}

	/**
	 * @covers WikiPage::getParserOutput
	 */
	public function testGetParserOutput_nonexisting() {
		$page = new WikiPage( Title::newFromText( __METHOD__ ) );

		$opt = new ParserOptions();
		$po = $page->getParserOutput( $opt );

		$this->assertFalse( $po, "getParserOutput() shall return false for non-existing pages." );
	}

	/**
	 * @covers WikiPage::getParserOutput
	 */
	public function testGetParserOutput_badrev() {
		$page = $this->createPage( __METHOD__, 'dummy', CONTENT_MODEL_WIKITEXT );

		$opt = new ParserOptions();
		$po = $page->getParserOutput( $opt, $page->getLatest() + 1234 );

		// @todo would be neat to also test deleted revision

		$this->assertFalse( $po, "getParserOutput() shall return false for non-existing revisions." );
	}

	public static $sections =

		"Intro

== stuff ==
hello world

== test ==
just a test

== foo ==
more stuff
";

	public function dataReplaceSection() {
		// NOTE: assume the Help namespace to contain wikitext
		return [
			[ 'Help:WikiPageTest_testReplaceSection',
				CONTENT_MODEL_WIKITEXT,
				self::$sections,
				"0",
				"No more",
				null,
				trim( preg_replace( '/^Intro/sm', 'No more', self::$sections ) )
			],
			[ 'Help:WikiPageTest_testReplaceSection',
				CONTENT_MODEL_WIKITEXT,
				self::$sections,
				"",
				"No more",
				null,
				"No more"
			],
			[ 'Help:WikiPageTest_testReplaceSection',
				CONTENT_MODEL_WIKITEXT,
				self::$sections,
				"2",
				"== TEST ==\nmore fun",
				null,
				trim( preg_replace( '/^== test ==.*== foo ==/sm',
					"== TEST ==\nmore fun\n\n== foo ==",
					self::$sections ) )
			],
			[ 'Help:WikiPageTest_testReplaceSection',
				CONTENT_MODEL_WIKITEXT,
				self::$sections,
				"8",
				"No more",
				null,
				trim( self::$sections )
			],
			[ 'Help:WikiPageTest_testReplaceSection',
				CONTENT_MODEL_WIKITEXT,
				self::$sections,
				"new",
				"No more",
				"New",
				trim( self::$sections ) . "\n\n== New ==\n\nNo more"
			],
		];
	}

	/**
	 * @dataProvider dataReplaceSection
	 * @covers WikiPage::replaceSectionContent
	 */
	public function testReplaceSectionContent( $title, $model, $text, $section,
		$with, $sectionTitle, $expected
	) {
		$page = $this->createPage( $title, $text, $model );

		$content = ContentHandler::makeContent( $with, $page->getTitle(), $page->getContentModel() );
		$c = $page->replaceSectionContent( $section, $content, $sectionTitle );

		$this->assertEquals( $expected, is_null( $c ) ? null : trim( $c->getNativeData() ) );
	}

	/**
	 * @dataProvider dataReplaceSection
	 * @covers WikiPage::replaceSectionAtRev
	 */
	public function testReplaceSectionAtRev( $title, $model, $text, $section,
		$with, $sectionTitle, $expected
	) {
		$page = $this->createPage( $title, $text, $model );
		$baseRevId = $page->getLatest();

		$content = ContentHandler::makeContent( $with, $page->getTitle(), $page->getContentModel() );
		$c = $page->replaceSectionAtRev( $section, $content, $sectionTitle, $baseRevId );

		$this->assertEquals( $expected, is_null( $c ) ? null : trim( $c->getNativeData() ) );
	}

	/**
	 * @covers WikiPage::getOldestRevision
	 */
	public function testGetOldestRevision() {
		$page = $this->newPage( __METHOD__ );
		$page->doEditContent(
			new WikitextContent( 'one' ),
			"first edit",
			EDIT_NEW
		);
		$rev1 = $page->getRevision();

		$page = new WikiPage( $page->getTitle() );
		$page->doEditContent(
			new WikitextContent( 'two' ),
			"second edit",
			EDIT_UPDATE
		);

		$page = new WikiPage( $page->getTitle() );
		$page->doEditContent(
			new WikitextContent( 'three' ),
			"third edit",
			EDIT_UPDATE
		);

		// sanity check
		$this->assertNotEquals(
			$rev1->getId(),
			$page->getRevision()->getId(),
			'$page->getRevision()->getId()'
		);

		// actual test
		$this->assertEquals(
			$rev1->getId(),
			$page->getOldestRevision()->getId(),
			'$page->getOldestRevision()->getId()'
		);
	}

	/**
	 * @covers WikiPage::doRollback
	 * @covers WikiPage::commitRollback
	 */
	public function testDoRollback() {
		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser()->getUser();
		// Use the confirmed group for user2 to make sure the user is different
		$user2 = $this->getTestUser( [ 'confirmed' ] )->getUser();

		$page = $this->newPage( __METHOD__ );

		// Make some edits
		$text = "one";
		$status1 = $page->doEditContent( ContentHandler::makeContent( $text, $page->getTitle() ),
			"section one", EDIT_NEW, false, $admin );

		$text .= "\n\ntwo";
		$status2 = $page->doEditContent( ContentHandler::makeContent( $text, $page->getTitle() ),
			"adding section two", 0, false, $user1 );

		$text .= "\n\nthree";
		$status3 = $page->doEditContent( ContentHandler::makeContent( $text, $page->getTitle() ),
			"adding section three", 0, false, $user2 );

		/** @var Revision $rev1 */
		/** @var Revision $rev2 */
		/** @var Revision $rev3 */
		$rev1 = $status1->getValue()['revision'];
		$rev2 = $status2->getValue()['revision'];
		$rev3 = $status3->getValue()['revision'];

		/**
		 * We are having issues with doRollback spuriously failing. Apparently
		 * the last revision somehow goes missing or not committed under some
		 * circumstances. So, make sure the revisions have the correct usernames.
		 */
		$this->assertEquals( 3, Revision::countByPageId( wfGetDB( DB_REPLICA ), $page->getId() ) );
		$this->assertEquals( $admin->getName(), $rev1->getUserText() );
		$this->assertEquals( $user1->getName(), $rev2->getUserText() );
		$this->assertEquals( $user2->getName(), $rev3->getUserText() );

		// Now, try the actual rollback
		$token = $admin->getEditToken( 'rollback' );
		$rollbackErrors = $page->doRollback(
			$user2->getName(),
			"testing rollback",
			$token,
			false,
			$resultDetails,
			$admin
		);

		if ( $rollbackErrors ) {
			$this->fail(
				"Rollback failed:\n" .
				print_r( $rollbackErrors, true ) . ";\n" .
				print_r( $resultDetails, true )
			);
		}

		$page = new WikiPage( $page->getTitle() );
		$this->assertEquals( $rev2->getSha1(), $page->getRevision()->getSha1(),
			"rollback did not revert to the correct revision" );
		$this->assertEquals( "one\n\ntwo", $page->getContent()->getNativeData() );
	}

	/**
	 * @covers WikiPage::doRollback
	 * @covers WikiPage::commitRollback
	 */
	public function testDoRollback_simple() {
		$admin = $this->getTestSysop()->getUser();

		$text = "one";
		$page = $this->newPage( __METHOD__ );
		$page->doEditContent(
			ContentHandler::makeContent( $text, $page->getTitle(), CONTENT_MODEL_WIKITEXT ),
			"section one",
			EDIT_NEW,
			false,
			$admin
		);
		$rev1 = $page->getRevision();

		$user1 = $this->getTestUser()->getUser();
		$text .= "\n\ntwo";
		$page = new WikiPage( $page->getTitle() );
		$page->doEditContent(
			ContentHandler::makeContent( $text, $page->getTitle(), CONTENT_MODEL_WIKITEXT ),
			"adding section two",
			0,
			false,
			$user1
		);

		# now, try the rollback
		$token = $admin->getEditToken( 'rollback' );
		$errors = $page->doRollback(
			$user1->getName(),
			"testing revert",
			$token,
			false,
			$details,
			$admin
		);

		if ( $errors ) {
			$this->fail( "Rollback failed:\n" . print_r( $errors, true )
				. ";\n" . print_r( $details, true ) );
		}

		$page = new WikiPage( $page->getTitle() );
		$this->assertEquals( $rev1->getSha1(), $page->getRevision()->getSha1(),
			"rollback did not revert to the correct revision" );
		$this->assertEquals( "one", $page->getContent()->getNativeData() );
	}

	/**
	 * @covers WikiPage::doRollback
	 * @covers WikiPage::commitRollback
	 */
	public function testDoRollbackFailureSameContent() {
		$admin = $this->getTestSysop()->getUser();

		$text = "one";
		$page = $this->newPage( __METHOD__ );
		$page->doEditContent(
			ContentHandler::makeContent( $text, $page->getTitle(), CONTENT_MODEL_WIKITEXT ),
			"section one",
			EDIT_NEW,
			false,
			$admin
		);
		$rev1 = $page->getRevision();

		$user1 = $this->getTestUser( [ 'sysop' ] )->getUser();
		$text .= "\n\ntwo";
		$page = new WikiPage( $page->getTitle() );
		$page->doEditContent(
			ContentHandler::makeContent( $text, $page->getTitle(), CONTENT_MODEL_WIKITEXT ),
			"adding section two",
			0,
			false,
			$user1
		);

		# now, do a the rollback from the same user was doing the edit before
		$resultDetails = [];
		$token = $user1->getEditToken( 'rollback' );
		$errors = $page->doRollback(
			$user1->getName(),
			"testing revert same user",
			$token,
			false,
			$resultDetails,
			$admin
		);

		$this->assertEquals( [], $errors, "Rollback failed same user" );

		# now, try the rollback
		$resultDetails = [];
		$token = $admin->getEditToken( 'rollback' );
		$errors = $page->doRollback(
			$user1->getName(),
			"testing revert",
			$token,
			false,
			$resultDetails,
			$admin
		);

		$this->assertEquals(
			[
				[
					'alreadyrolled',
					__METHOD__,
					$user1->getName(),
					$admin->getName(),
				],
			],
			$errors,
			"Rollback not failed"
		);

		$page = new WikiPage( $page->getTitle() );
		$this->assertEquals( $rev1->getSha1(), $page->getRevision()->getSha1(),
			"rollback did not revert to the correct revision" );
		$this->assertEquals( "one", $page->getContent()->getNativeData() );
	}

	/**
	 * Tests tagging for edits that do rollback action
	 * @covers WikiPage::doRollback
	 */
	public function testDoRollbackTagging() {
		if ( !in_array( 'mw-rollback', ChangeTags::getSoftwareTags() ) ) {
			$this->markTestSkipped( 'Rollback tag deactivated, skipped the test.' );
		}

		$admin = new User();
		$admin->setName( 'Administrator' );
		$admin->addToDatabase();

		$text = 'First line';
		$page = $this->newPage( 'WikiPageTest_testDoRollbackTagging' );
		$page->doEditContent(
			ContentHandler::makeContent( $text, $page->getTitle(), CONTENT_MODEL_WIKITEXT ),
			'Added first line',
			EDIT_NEW,
			false,
			$admin
		);

		$secondUser = new User();
		$secondUser->setName( '92.65.217.32' );
		$text .= '\n\nSecond line';
		$page = new WikiPage( $page->getTitle() );
		$page->doEditContent(
			ContentHandler::makeContent( $text, $page->getTitle(), CONTENT_MODEL_WIKITEXT ),
			'Adding second line',
			0,
			false,
			$secondUser
		);

		// Now, try the rollback
		$admin->addGroup( 'sysop' ); // Make the test user a sysop
		$token = $admin->getEditToken( 'rollback' );
		$errors = $page->doRollback(
			$secondUser->getName(),
			'testing rollback',
			$token,
			false,
			$resultDetails,
			$admin
		);

		// If doRollback completed without errors
		if ( $errors === [] ) {
			$tags = $resultDetails[ 'tags' ];
			$this->assertContains( 'mw-rollback', $tags );
		}
	}

	public function provideGetAutoDeleteReason() {
		return [
			[
				[],
				false,
				false
			],

			[
				[
					[ "first edit", null ],
				],
				"/first edit.*only contributor/",
				false
			],

			[
				[
					[ "first edit", null ],
					[ "second edit", null ],
				],
				"/second edit.*only contributor/",
				true
			],

			[
				[
					[ "first edit", "127.0.2.22" ],
					[ "second edit", "127.0.3.33" ],
				],
				"/second edit/",
				true
			],

			[
				[
					[
						"first edit: "
							. "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam "
							. " nonumy eirmod tempor invidunt ut labore et dolore magna "
							. "aliquyam erat, sed diam voluptua. At vero eos et accusam "
							. "et justo duo dolores et ea rebum. Stet clita kasd gubergren, "
							. "no sea  takimata sanctus est Lorem ipsum dolor sit amet.'",
						null
					],
				],
				'/first edit:.*\.\.\."/',
				false
			],

			[
				[
					[ "first edit", "127.0.2.22" ],
					[ "", "127.0.3.33" ],
				],
				"/before blanking.*first edit/",
				true
			],

		];
	}

	/**
	 * @dataProvider provideGetAutoDeleteReason
	 * @covers WikiPage::getAutoDeleteReason
	 */
	public function testGetAutoDeleteReason( $edits, $expectedResult, $expectedHistory ) {
		global $wgUser;

		// NOTE: assume Help namespace to contain wikitext
		$page = $this->newPage( "Help:WikiPageTest_testGetAutoDeleteReason" );

		$c = 1;

		foreach ( $edits as $edit ) {
			$user = new User();

			if ( !empty( $edit[1] ) ) {
				$user->setName( $edit[1] );
			} else {
				$user = $wgUser;
			}

			$content = ContentHandler::makeContent( $edit[0], $page->getTitle(), $page->getContentModel() );

			$page->doEditContent( $content, "test edit $c", $c < 2 ? EDIT_NEW : 0, false, $user );

			$c += 1;
		}

		$reason = $page->getAutoDeleteReason( $hasHistory );

		if ( is_bool( $expectedResult ) || is_null( $expectedResult ) ) {
			$this->assertEquals( $expectedResult, $reason );
		} else {
			$this->assertTrue( (bool)preg_match( $expectedResult, $reason ),
				"Autosummary didn't match expected pattern $expectedResult: $reason" );
		}

		$this->assertEquals( $expectedHistory, $hasHistory,
			"expected \$hasHistory to be " . var_export( $expectedHistory, true ) );

		$page->doDeleteArticle( "done" );
	}

	public function providePreSaveTransform() {
		return [
			[ 'hello this is ~~~',
				"hello this is [[Special:Contributions/127.0.0.1|127.0.0.1]]",
			],
			[ 'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
				'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
			],
		];
	}

	/**
	 * @covers WikiPage::factory
	 */
	public function testWikiPageFactory() {
		$title = Title::makeTitle( NS_FILE, 'Someimage.png' );
		$page = WikiPage::factory( $title );
		$this->assertEquals( 'WikiFilePage', get_class( $page ) );

		$title = Title::makeTitle( NS_CATEGORY, 'SomeCategory' );
		$page = WikiPage::factory( $title );
		$this->assertEquals( 'WikiCategoryPage', get_class( $page ) );

		$title = Title::makeTitle( NS_MAIN, 'SomePage' );
		$page = WikiPage::factory( $title );
		$this->assertEquals( 'WikiPage', get_class( $page ) );
	}

	/**
	 * @dataProvider provideCommentMigrationOnDeletion
	 *
	 * @param int $writeStage
	 * @param int $readStage
	 */
	public function testCommentMigrationOnDeletion( $writeStage, $readStage ) {
		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', $writeStage );
		$dbr = wfGetDB( DB_REPLICA );

		$page = $this->createPage(
			__METHOD__,
			"foo",
			CONTENT_MODEL_WIKITEXT
		);
		$revid = $page->getLatest();
		if ( $writeStage > MIGRATION_OLD ) {
			$comment_id = $dbr->selectField(
				'revision_comment_temp',
				'revcomment_comment_id',
				[ 'revcomment_rev' => $revid ],
				__METHOD__
			);
		}

		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', $readStage );

		$page->doDeleteArticle( "testing deletion" );

		if ( $readStage > MIGRATION_OLD ) {
			// Didn't leave behind any 'revision_comment_temp' rows
			$n = $dbr->selectField(
				'revision_comment_temp', 'COUNT(*)', [ 'revcomment_rev' => $revid ], __METHOD__
			);
			$this->assertEquals( 0, $n, 'no entry in revision_comment_temp after deletion' );

			// Copied or upgraded the comment_id, as applicable
			$ar_comment_id = $dbr->selectField(
				'archive',
				'ar_comment_id',
				[ 'ar_rev_id' => $revid ],
				__METHOD__
			);
			if ( $writeStage > MIGRATION_OLD ) {
				$this->assertSame( $comment_id, $ar_comment_id );
			} else {
				$this->assertNotEquals( 0, $ar_comment_id );
			}
		}

		// Copied rev_comment, if applicable
		if ( $readStage <= MIGRATION_WRITE_BOTH && $writeStage <= MIGRATION_WRITE_BOTH ) {
			$ar_comment = $dbr->selectField(
				'archive',
				'ar_comment',
				[ 'ar_rev_id' => $revid ],
				__METHOD__
			);
			$this->assertSame( 'testing', $ar_comment );
		}
	}

	public function provideCommentMigrationOnDeletion() {
		return [
			[ MIGRATION_OLD, MIGRATION_OLD ],
			[ MIGRATION_OLD, MIGRATION_WRITE_BOTH ],
			[ MIGRATION_OLD, MIGRATION_WRITE_NEW ],
			[ MIGRATION_WRITE_BOTH, MIGRATION_OLD ],
			[ MIGRATION_WRITE_BOTH, MIGRATION_WRITE_BOTH ],
			[ MIGRATION_WRITE_BOTH, MIGRATION_WRITE_NEW ],
			[ MIGRATION_WRITE_BOTH, MIGRATION_NEW ],
			[ MIGRATION_WRITE_NEW, MIGRATION_WRITE_BOTH ],
			[ MIGRATION_WRITE_NEW, MIGRATION_WRITE_NEW ],
			[ MIGRATION_WRITE_NEW, MIGRATION_NEW ],
			[ MIGRATION_NEW, MIGRATION_WRITE_BOTH ],
			[ MIGRATION_NEW, MIGRATION_WRITE_NEW ],
			[ MIGRATION_NEW, MIGRATION_NEW ],
		];
	}

}

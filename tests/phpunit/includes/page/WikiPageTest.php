<?php

/**
 * @group ContentHandler
 * @group Database
 * ^--- important, causes temporary tables to be used instead of the real database
 * @group medium
 **/
class WikiPageTest extends MediaWikiLangTestCase {

	protected $pages_to_delete;

	function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'page',
				'revision',
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
		$this->pages_to_delete = [];

		LinkCache::singleton()->clear(); # avoid cached redirect status, etc
	}

	protected function tearDown() {
		foreach ( $this->pages_to_delete as $p ) {
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

	/**
	 * @param Title|string $title
	 * @param string|null $model
	 * @return WikiPage
	 */
	protected function newPage( $title, $model = null ) {
		if ( is_string( $title ) ) {
			$ns = $this->getDefaultWikitextNS();
			$title = Title::newFromText( $title, $ns );
		}

		$p = new WikiPage( $title );

		$this->pages_to_delete[] = $p;

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
		$page = $this->newPage( "WikiPageTest_testDoEditContent" );
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
		$dbr = wfGetDB( DB_SLAVE );
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
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'pagelinks', '*', [ 'pl_from' => $id ] );
		$n = $res->numRows();
		$res->free();

		$this->assertEquals( 2, $n, 'pagelinks should contain two links from the page' );
	}

	/**
	 * @covers WikiPage::doEdit
	 * @deprecated since 1.21. Should be removed when WikiPage::doEdit() gets removed
	 */
	public function testDoEdit() {
		$this->hideDeprecated( "WikiPage::doEdit" );
		$this->hideDeprecated( "WikiPage::getText" );
		$this->hideDeprecated( "Revision::getText" );

		// NOTE: assume help namespace will default to wikitext
		$title = Title::newFromText( "Help:WikiPageTest_testDoEdit" );

		$page = $this->newPage( $title );

		$text = "[[Lorem ipsum]] dolor sit amet, consetetur sadipscing elitr, sed diam "
			. " nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.";

		$page->doEdit( $text, "[[testing]] 1" );

		$this->assertTrue( $title->getArticleID() > 0, "Title object should have new page id" );
		$this->assertTrue( $page->getId() > 0, "WikiPage should have new page id" );
		$this->assertTrue( $title->exists(), "Title object should indicate that the page now exists" );
		$this->assertTrue( $page->exists(), "WikiPage object should indicate that the page now exists" );

		$id = $page->getId();

		# ------------------------
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'pagelinks', '*', [ 'pl_from' => $id ] );
		$n = $res->numRows();
		$res->free();

		$this->assertEquals( 1, $n, 'pagelinks should contain one link from the page' );

		# ------------------------
		$page = new WikiPage( $title );

		$retrieved = $page->getText();
		$this->assertEquals( $text, $retrieved, 'retrieved text doesn\'t equal original' );

		# ------------------------
		$text = "At vero eos et accusam et justo duo [[dolores]] et ea rebum. "
			. "Stet clita kasd [[gubergren]], no sea takimata sanctus est.";

		$page->doEdit( $text, "testing 2" );

		# ------------------------
		$page = new WikiPage( $title );

		$retrieved = $page->getText();
		$this->assertEquals( $text, $retrieved, 'retrieved text doesn\'t equal original' );

		# ------------------------
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'pagelinks', '*', [ 'pl_from' => $id ] );
		$n = $res->numRows();
		$res->free();

		$this->assertEquals( 2, $n, 'pagelinks should contain two links from the page' );
	}

	/**
	 * @covers WikiPage::doDeleteArticle
	 */
	public function testDoDeleteArticle() {
		$page = $this->createPage(
			"WikiPageTest_testDoDeleteArticle",
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
		$this->assertFalse(
			$page->getText(),
			"WikiPage::getText should return false after page was deleted"
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
		$dbr = wfGetDB( DB_SLAVE );
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
			"WikiPageTest_testDoDeleteArticle",
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
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'pagelinks', '*', [ 'pl_from' => $id ] );
		$n = $res->numRows();
		$res->free();

		$this->assertEquals( 0, $n, 'pagelinks should contain no more links from the page' );
	}

	/**
	 * @covers WikiPage::getRevision
	 */
	public function testGetRevision() {
		$page = $this->newPage( "WikiPageTest_testGetRevision" );

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
		$page = $this->newPage( "WikiPageTest_testGetContent" );

		$content = $page->getContent();
		$this->assertNull( $content );

		# -----------------
		$this->createPage( $page, "some text", CONTENT_MODEL_WIKITEXT );

		$content = $page->getContent();
		$this->assertEquals( "some text", $content->getNativeData() );
	}

	/**
	 * @covers WikiPage::getText
	 */
	public function testGetText() {
		$this->hideDeprecated( "WikiPage::getText" );

		$page = $this->newPage( "WikiPageTest_testGetText" );

		$text = $page->getText();
		$this->assertFalse( $text );

		# -----------------
		$this->createPage( $page, "some text", CONTENT_MODEL_WIKITEXT );

		$text = $page->getText();
		$this->assertEquals( "some text", $text );
	}

	/**
	 * @covers WikiPage::getContentModel
	 */
	public function testGetContentModel() {
		global $wgContentHandlerUseDB;

		if ( !$wgContentHandlerUseDB ) {
			$this->markTestSkipped( '$wgContentHandlerUseDB is disabled' );
		}

		$page = $this->createPage(
			"WikiPageTest_testGetContentModel",
			"some text",
			CONTENT_MODEL_JAVASCRIPT
		);

		$page = new WikiPage( $page->getTitle() );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $page->getContentModel() );
	}

	/**
	 * @covers WikiPage::getContentHandler
	 */
	public function testGetContentHandler() {
		global $wgContentHandlerUseDB;

		if ( !$wgContentHandlerUseDB ) {
			$this->markTestSkipped( '$wgContentHandlerUseDB is disabled' );
		}

		$page = $this->createPage(
			"WikiPageTest_testGetContentHandler",
			"some text",
			CONTENT_MODEL_JAVASCRIPT
		);

		$page = new WikiPage( $page->getTitle() );
		$this->assertEquals( 'JavaScriptContentHandler', get_class( $page->getContentHandler() ) );
	}

	/**
	 * @covers WikiPage::exists
	 */
	public function testExists() {
		$page = $this->newPage( "WikiPageTest_testExists" );
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

	public static function provideHasViewableContent() {
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

	public static function provideGetRedirectTarget() {
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

	public static function provideIsCountable() {
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

	public static function provideGetParserOutput() {
		return [
			[ CONTENT_MODEL_WIKITEXT, "hello ''world''\n", "<p>hello <i>world</i></p>" ],
			// @todo more...?
		];
	}

	/**
	 * @dataProvider provideGetParserOutput
	 * @covers WikiPage::getParserOutput
	 */
	public function testGetParserOutput( $model, $text, $expectedHtml ) {
		$page = $this->createPage( 'WikiPageTest_testGetParserOutput', $text, $model );

		$opt = $page->makeParserOptions( 'canonical' );
		$po = $page->getParserOutput( $opt );
		$text = $po->getText();

		$text = trim( preg_replace( '/<!--.*?-->/sm', '', $text ) ); # strip injected comments
		$text = preg_replace( '!\s*(</p>)!sm', '\1', $text ); # don't let tidy confuse us

		$this->assertEquals( $expectedHtml, $text );

		return $po;
	}

	/**
	 * @covers WikiPage::getParserOutput
	 */
	public function testGetParserOutput_nonexisting() {
		static $count = 0;
		$count++;

		$page = new WikiPage( new Title( "WikiPageTest_testGetParserOutput_nonexisting_$count" ) );

		$opt = new ParserOptions();
		$po = $page->getParserOutput( $opt );

		$this->assertFalse( $po, "getParserOutput() shall return false for non-existing pages." );
	}

	/**
	 * @covers WikiPage::getParserOutput
	 */
	public function testGetParserOutput_badrev() {
		$page = $this->createPage( 'WikiPageTest_testGetParserOutput', "dummy", CONTENT_MODEL_WIKITEXT );

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
				WikiPageTest::$sections,
				"0",
				"No more",
				null,
				trim( preg_replace( '/^Intro/sm', 'No more', WikiPageTest::$sections ) )
			],
			[ 'Help:WikiPageTest_testReplaceSection',
				CONTENT_MODEL_WIKITEXT,
				WikiPageTest::$sections,
				"",
				"No more",
				null,
				"No more"
			],
			[ 'Help:WikiPageTest_testReplaceSection',
				CONTENT_MODEL_WIKITEXT,
				WikiPageTest::$sections,
				"2",
				"== TEST ==\nmore fun",
				null,
				trim( preg_replace( '/^== test ==.*== foo ==/sm',
					"== TEST ==\nmore fun\n\n== foo ==",
					WikiPageTest::$sections ) )
			],
			[ 'Help:WikiPageTest_testReplaceSection',
				CONTENT_MODEL_WIKITEXT,
				WikiPageTest::$sections,
				"8",
				"No more",
				null,
				trim( WikiPageTest::$sections )
			],
			[ 'Help:WikiPageTest_testReplaceSection',
				CONTENT_MODEL_WIKITEXT,
				WikiPageTest::$sections,
				"new",
				"No more",
				"New",
				trim( WikiPageTest::$sections ) . "\n\n== New ==\n\nNo more"
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

	/* @todo FIXME: fix this!
	public function testGetUndoText() {
	$this->markTestSkippedIfNoDiff3();

	$text = "one";
	$page = $this->createPage( "WikiPageTest_testGetUndoText", $text );
	$rev1 = $page->getRevision();

	$text .= "\n\ntwo";
	$page->doEditContent(
		ContentHandler::makeContent( $text, $page->getTitle() ),
		"adding section two"
	);
	$rev2 = $page->getRevision();

	$text .= "\n\nthree";
	$page->doEditContent(
		ContentHandler::makeContent( $text, $page->getTitle() ),
		"adding section three"
	);
	$rev3 = $page->getRevision();

	$text .= "\n\nfour";
	$page->doEditContent(
		ContentHandler::makeContent( $text, $page->getTitle() ),
		"adding section four"
	);
	$rev4 = $page->getRevision();

	$text .= "\n\nfive";
	$page->doEditContent(
		ContentHandler::makeContent( $text, $page->getTitle() ),
		"adding section five"
	);
	$rev5 = $page->getRevision();

	$text .= "\n\nsix";
	$page->doEditContent(
		ContentHandler::makeContent( $text, $page->getTitle() ),
		"adding section six"
	);
	$rev6 = $page->getRevision();

	$undo6 = $page->getUndoText( $rev6 );
	if ( $undo6 === false ) $this->fail( "getUndoText failed for rev6" );
	$this->assertEquals( "one\n\ntwo\n\nthree\n\nfour\n\nfive", $undo6 );

	$undo3 = $page->getUndoText( $rev4, $rev2 );
	if ( $undo3 === false ) $this->fail( "getUndoText failed for rev4..rev2" );
	$this->assertEquals( "one\n\ntwo\n\nfive", $undo3 );

	$undo2 = $page->getUndoText( $rev2 );
	if ( $undo2 === false ) $this->fail( "getUndoText failed for rev2" );
	$this->assertEquals( "one\n\nfive", $undo2 );
	}
	 */

	/**
	 * @todo FIXME: this is a better rollback test than the one below, but it
	 * keeps failing in jenkins for some reason.
	 */
	public function broken_testDoRollback() {
		$admin = new User();
		$admin->setName( "Admin" );

		$text = "one";
		$page = $this->newPage( "WikiPageTest_testDoRollback" );
		$page->doEditContent( ContentHandler::makeContent( $text, $page->getTitle() ),
			"section one", EDIT_NEW, false, $admin );

		$user1 = new User();
		$user1->setName( "127.0.1.11" );
		$text .= "\n\ntwo";
		$page = new WikiPage( $page->getTitle() );
		$page->doEditContent( ContentHandler::makeContent( $text, $page->getTitle() ),
			"adding section two", 0, false, $user1 );

		$user2 = new User();
		$user2->setName( "127.0.2.13" );
		$text .= "\n\nthree";
		$page = new WikiPage( $page->getTitle() );
		$page->doEditContent( ContentHandler::makeContent( $text, $page->getTitle() ),
			"adding section three", 0, false, $user2 );

		# we are having issues with doRollback spuriously failing. Apparently
		# the last revision somehow goes missing or not committed under some
		# circumstances. So, make sure the last revision has the right user name.
		$dbr = wfGetDB( DB_SLAVE );
		$this->assertEquals( 3, Revision::countByPageId( $dbr, $page->getId() ) );

		$page = new WikiPage( $page->getTitle() );
		$rev3 = $page->getRevision();
		$this->assertEquals( '127.0.2.13', $rev3->getUserText() );

		$rev2 = $rev3->getPrevious();
		$this->assertEquals( '127.0.1.11', $rev2->getUserText() );

		$rev1 = $rev2->getPrevious();
		$this->assertEquals( 'Admin', $rev1->getUserText() );

		# now, try the actual rollback
		$admin->addGroup( "sysop" ); # XXX: make the test user a sysop...
		$token = $admin->getEditToken(
			[ $page->getTitle()->getPrefixedText(), $user2->getName() ],
			null
		);
		$errors = $page->doRollback(
			$user2->getName(),
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
		$this->assertEquals( $rev2->getSha1(), $page->getRevision()->getSha1(),
			"rollback did not revert to the correct revision" );
		$this->assertEquals( "one\n\ntwo", $page->getContent()->getNativeData() );
	}

	/**
	 * @todo FIXME: the above rollback test is better, but it keeps failing in jenkins for some reason.
	 * @covers WikiPage::doRollback
	 */
	public function testDoRollback() {
		$admin = new User();
		$admin->setName( "Admin" );

		$text = "one";
		$page = $this->newPage( "WikiPageTest_testDoRollback" );
		$page->doEditContent(
			ContentHandler::makeContent( $text, $page->getTitle(), CONTENT_MODEL_WIKITEXT ),
			"section one",
			EDIT_NEW,
			false,
			$admin
		);
		$rev1 = $page->getRevision();

		$user1 = new User();
		$user1->setName( "127.0.1.11" );
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
		$admin->addGroup( "sysop" ); # XXX: make the test user a sysop...
		$token = $admin->getEditToken(
			[ $page->getTitle()->getPrefixedText(), $user1->getName() ],
			null
		);
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
	 */
	public function testDoRollbackFailureSameContent() {
		$admin = new User();
		$admin->setName( "Admin" );
		$admin->addGroup( "sysop" ); # XXX: make the test user a sysop...

		$text = "one";
		$page = $this->newPage( "WikiPageTest_testDoRollback" );
		$page->doEditContent(
			ContentHandler::makeContent( $text, $page->getTitle(), CONTENT_MODEL_WIKITEXT ),
			"section one",
			EDIT_NEW,
			false,
			$admin
		);
		$rev1 = $page->getRevision();

		$user1 = new User();
		$user1->setName( "127.0.1.11" );
		$user1->addGroup( "sysop" ); # XXX: make the test user a sysop...
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
		$token = $user1->getEditToken(
			[ $page->getTitle()->getPrefixedText(), $user1->getName() ],
			null
		);
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
		$token = $admin->getEditToken(
			[ $page->getTitle()->getPrefixedText(), $user1->getName() ],
			null
		);
		$errors = $page->doRollback(
			$user1->getName(),
			"testing revert",
			$token,
			false,
			$resultDetails,
			$admin
		);

		$this->assertEquals( [ [ 'alreadyrolled', 'WikiPageTest testDoRollback',
			'127.0.1.11', 'Admin' ] ], $errors, "Rollback not failed" );

		$page = new WikiPage( $page->getTitle() );
		$this->assertEquals( $rev1->getSha1(), $page->getRevision()->getSha1(),
			"rollback did not revert to the correct revision" );
		$this->assertEquals( "one", $page->getContent()->getNativeData() );
	}

	public static function provideGetAutosummary() {
		return [
			[
				'Hello there, world!',
				'#REDIRECT [[Foo]]',
				0,
				'/^Redirected page .*Foo/'
			],

			[
				null,
				'Hello world!',
				EDIT_NEW,
				'/^Created page .*Hello/'
			],

			[
				'Hello there, world!',
				'',
				0,
				'/^Blanked/'
			],

			[
				'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy
				eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam
				voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet
				clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',
				'Hello world!',
				0,
				'/^Replaced .*Hello/'
			],

			[
				'foo',
				'bar',
				0,
				'/^$/'
			],
		];
	}

	/**
	 * @dataProvider provideGetAutoSummary
	 * @covers WikiPage::getAutosummary
	 */
	public function testGetAutosummary( $old, $new, $flags, $expected ) {
		$this->hideDeprecated( "WikiPage::getAutosummary" );

		$page = $this->newPage( "WikiPageTest_testGetAutosummary" );

		$summary = $page->getAutosummary( $old, $new, $flags );

		$this->assertTrue( (bool)preg_match( $expected, $summary ),
			"Autosummary didn't match expected pattern $expected: $summary" );
	}

	public static function provideGetAutoDeleteReason() {
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

	public static function providePreSaveTransform() {
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
}

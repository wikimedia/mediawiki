<?php
/**
* @group ContentHandler
* @group Database
* ^--- important, causes temporary tables to be used instead of the real database
**/

class WikiPageTest extends MediaWikiLangTestCase {

	var $pages_to_delete;

	function  __construct( $name = null, array $data = array(), $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed = array_merge (
			$this->tablesUsed,
			array( 'page',
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
					'iwlinks' ) );
	}

	protected function setUp() {
		parent::setUp();
		$this->pages_to_delete = array();

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
	 * @param Title $title
	 * @param String $model
	 * @return WikiPage
	 */
	protected function newPage( $title, $model = null ) {
		if ( is_string( $title ) ) {
			$title = Title::newFromText( $title );
		}

		$p = new WikiPage( $title );

		$this->pages_to_delete[] = $p;

		return $p;
	}


	/**
	 * @param String|Title|WikiPage $page
	 * @param String $text
	 * @param int $model
	 *
	 * @return WikiPage
	 */
	protected function createPage( $page, $text, $model = null ) {
		if ( is_string( $page ) ) {
			$page = Title::newFromText( $page );
		}

		if ( $page instanceof Title ) {
			$page = $this->newPage( $page, $model );
		}

		$content = ContentHandler::makeContent( $text, $page->getTitle(), $model );
		$page->doEditContent( $content, "testing", EDIT_NEW );

		return $page;
	}

	public function testDoEditContent() {
		$title = Title::newFromText( "WikiPageTest_testDoEditContent" );

		$page = $this->newPage( $title );

		$content = ContentHandler::makeContent( "[[Lorem ipsum]] dolor sit amet, consetetur sadipscing elitr, sed diam "
						. " nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.",
						$title, CONTENT_MODEL_WIKITEXT );

		$page->doEditContent( $content, "[[testing]] 1" );

		$this->assertTrue( $title->getArticleID() > 0, "Title object should have new page id" );
		$this->assertTrue( $page->getId() > 0, "WikiPage should have new page id" );
		$this->assertTrue( $title->exists(), "Title object should indicate that the page now exists" );
		$this->assertTrue( $page->exists(), "WikiPage object should indicate that the page now exists" );

		$id = $page->getId();

		# ------------------------
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'pagelinks', '*', array( 'pl_from' => $id ) );
		$n = $res->numRows();
		$res->free();

		$this->assertEquals( 1, $n, 'pagelinks should contain one link from the page' );

		# ------------------------
		$page = new WikiPage( $title );

		$retrieved = $page->getContent();
		$this->assertTrue( $content->equals( $retrieved ), 'retrieved content doesn\'t equal original' );

		# ------------------------
		$content = ContentHandler::makeContent( "At vero eos et accusam et justo duo [[dolores]] et ea rebum. "
												. "Stet clita kasd [[gubergren]], no sea takimata sanctus est.",
												$title, CONTENT_MODEL_WIKITEXT );

		$page->doEditContent( $content, "testing 2" );

		# ------------------------
		$page = new WikiPage( $title );

		$retrieved = $page->getContent();
		$this->assertTrue( $content->equals( $retrieved ), 'retrieved content doesn\'t equal original' );

		# ------------------------
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'pagelinks', '*', array( 'pl_from' => $id ) );
		$n = $res->numRows();
		$res->free();

		$this->assertEquals( 2, $n, 'pagelinks should contain two links from the page' );
	}

	public function testDoEdit() {
		$this->hideDeprecated( "WikiPage::doEdit" );
		$this->hideDeprecated( "WikiPage::getText" );
		$this->hideDeprecated( "Revision::getText" );

		//NOTE: assume help namespace will default to wikitext
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
		$res = $dbr->select( 'pagelinks', '*', array( 'pl_from' => $id ) );
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
		$res = $dbr->select( 'pagelinks', '*', array( 'pl_from' => $id ) );
		$n = $res->numRows();
		$res->free();

		$this->assertEquals( 2, $n, 'pagelinks should contain two links from the page' );
	}

	public function testDoQuickEdit() {
		global $wgUser;

		$this->hideDeprecated( "WikiPage::doQuickEdit" );

		//NOTE: assume help namespace will default to wikitext
		$page = $this->createPage( "Help:WikiPageTest_testDoQuickEdit", "original text" );

		$text = "quick text";
		$page->doQuickEdit( $text, $wgUser, "testing q" );

		# ---------------------
		$page = new WikiPage( $page->getTitle() );
		$this->assertEquals( $text, $page->getText() );
	}

	public function testDoQuickEditContent() {
		global $wgUser;

		$page = $this->createPage( "WikiPageTest_testDoQuickEditContent", "original text", CONTENT_MODEL_WIKITEXT );

		$content = ContentHandler::makeContent( "quick text", $page->getTitle(), CONTENT_MODEL_WIKITEXT );
		$page->doQuickEditContent( $content, $wgUser, "testing q" );

		# ---------------------
		$page = new WikiPage( $page->getTitle() );
		$this->assertTrue( $content->equals( $page->getContent() ) );
	}

	public function testDoDeleteArticle() {
		$page = $this->createPage( "WikiPageTest_testDoDeleteArticle", "[[original text]] foo", CONTENT_MODEL_WIKITEXT );
		$id = $page->getId();

		$page->doDeleteArticle( "testing deletion" );

		$this->assertFalse( $page->getTitle()->getArticleID() > 0, "Title object should now have page id 0" );
		$this->assertFalse( $page->getId() > 0, "WikiPage should now have page id 0" );
		$this->assertFalse( $page->exists(), "WikiPage::exists should return false after page was deleted" );
		$this->assertNull( $page->getContent(), "WikiPage::getContent should return null after page was deleted" );
		$this->assertFalse( $page->getText(), "WikiPage::getText should return false after page was deleted" );

		$t = Title::newFromText( $page->getTitle()->getPrefixedText() );
		$this->assertFalse( $t->exists(), "Title::exists should return false after page was deleted" );

		# ------------------------
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'pagelinks', '*', array( 'pl_from' => $id ) );
		$n = $res->numRows();
		$res->free();

		$this->assertEquals( 0, $n, 'pagelinks should contain no more links from the page' );
	}

	public function testDoDeleteUpdates() {
		$page = $this->createPage( "WikiPageTest_testDoDeleteArticle", "[[original text]] foo", CONTENT_MODEL_WIKITEXT );
		$id = $page->getId();

		$page->doDeleteUpdates( $id );

		# ------------------------
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'pagelinks', '*', array( 'pl_from' => $id ) );
		$n = $res->numRows();
		$res->free();

		$this->assertEquals( 0, $n, 'pagelinks should contain no more links from the page' );
	}

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

	public function testGetContent() {
		$page = $this->newPage( "WikiPageTest_testGetContent" );

		$content = $page->getContent();
		$this->assertNull( $content );

		# -----------------
		$this->createPage( $page, "some text", CONTENT_MODEL_WIKITEXT );

		$content = $page->getContent();
		$this->assertEquals( "some text", $content->getNativeData() );
	}

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

	public function testGetRawText() {
		$this->hideDeprecated( "WikiPage::getRawText" );

		$page = $this->newPage( "WikiPageTest_testGetRawText" );

		$text = $page->getRawText();
		$this->assertFalse( $text );

		# -----------------
		$this->createPage( $page, "some text", CONTENT_MODEL_WIKITEXT );

		$text = $page->getRawText();
		$this->assertEquals( "some text", $text );
	}

	public function testGetContentModel() {
		global $wgContentHandlerUseDB;

		if ( !$wgContentHandlerUseDB ) {
			$this->markTestSkipped( '$wgContentHandlerUseDB is disabled' );
		}

		$page = $this->createPage( "WikiPageTest_testGetContentModel", "some text", CONTENT_MODEL_JAVASCRIPT );

		$page = new WikiPage( $page->getTitle() );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $page->getContentModel() );
	}

	public function testGetContentHandler() {
		global $wgContentHandlerUseDB;

		if ( !$wgContentHandlerUseDB ) {
			$this->markTestSkipped( '$wgContentHandlerUseDB is disabled' );
		}

		$page = $this->createPage( "WikiPageTest_testGetContentHandler", "some text", CONTENT_MODEL_JAVASCRIPT );

		$page = new WikiPage( $page->getTitle() );
		$this->assertEquals( 'JavaScriptContentHandler', get_class( $page->getContentHandler() ) );
	}

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
		return array(
			array( 'WikiPageTest_testHasViewableContent', false, true ),
			array( 'Special:WikiPageTest_testHasViewableContent', false ),
			array( 'MediaWiki:WikiPageTest_testHasViewableContent', false ),
			array( 'Special:Userlogin', true ),
			array( 'MediaWiki:help', true ),
		);
	}

	/**
	 * @dataProvider provideHasViewableContent
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
		return array(
			array( 'WikiPageTest_testGetRedirectTarget_1', CONTENT_MODEL_WIKITEXT, "hello world", null ),
			array( 'WikiPageTest_testGetRedirectTarget_2', CONTENT_MODEL_WIKITEXT, "#REDIRECT [[hello world]]", "Hello world" ),
		);
	}

	/**
	 * @dataProvider provideGetRedirectTarget
	 */
	public function testGetRedirectTarget( $title, $model, $text, $target ) {
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
	 */
	public function testIsRedirect( $title, $model, $text, $target ) {
		$page = $this->createPage( $title, $text, $model );
		$this->assertEquals( !is_null( $target ), $page->isRedirect() );
	}

	public static function provideIsCountable() {
		return array(

			// any
			array( 'WikiPageTest_testIsCountable',
					CONTENT_MODEL_WIKITEXT,
					'',
					'any',
					true
			),
			array( 'WikiPageTest_testIsCountable',
					CONTENT_MODEL_WIKITEXT,
					'Foo',
					'any',
					true
			),

			// comma
			array( 'WikiPageTest_testIsCountable',
					CONTENT_MODEL_WIKITEXT,
					'Foo',
					'comma',
					false
			),
			array( 'WikiPageTest_testIsCountable',
					CONTENT_MODEL_WIKITEXT,
					'Foo, bar',
					'comma',
					true
			),

			// link
			array( 'WikiPageTest_testIsCountable',
					CONTENT_MODEL_WIKITEXT,
					'Foo',
					'link',
					false
			),
			array( 'WikiPageTest_testIsCountable',
					CONTENT_MODEL_WIKITEXT,
					'Foo [[bar]]',
					'link',
					true
			),

			// redirects
			array( 'WikiPageTest_testIsCountable',
					CONTENT_MODEL_WIKITEXT,
					'#REDIRECT [[bar]]',
					'any',
					false
			),
			array( 'WikiPageTest_testIsCountable',
					CONTENT_MODEL_WIKITEXT,
					'#REDIRECT [[bar]]',
					'comma',
					false
			),
			array( 'WikiPageTest_testIsCountable',
					CONTENT_MODEL_WIKITEXT,
					'#REDIRECT [[bar]]',
					'link',
					false
			),

			// not a content namespace
			array( 'Talk:WikiPageTest_testIsCountable',
					CONTENT_MODEL_WIKITEXT,
					'Foo',
					'any',
					false
			),
			array( 'Talk:WikiPageTest_testIsCountable',
					CONTENT_MODEL_WIKITEXT,
					'Foo, bar',
					'comma',
					false
			),
			array( 'Talk:WikiPageTest_testIsCountable',
					CONTENT_MODEL_WIKITEXT,
					'Foo [[bar]]',
					'link',
					false
			),

			// not a content namespace, different model
			array( 'MediaWiki:WikiPageTest_testIsCountable.js',
					null,
					'Foo',
					'any',
					false
			),
			array( 'MediaWiki:WikiPageTest_testIsCountable.js',
					null,
					'Foo, bar',
					'comma',
					false
			),
			array( 'MediaWiki:WikiPageTest_testIsCountable.js',
					null,
					'Foo [[bar]]',
					'link',
					false
			),
		);
	}


	/**
	 * @dataProvider provideIsCountable
	 */
	public function testIsCountable( $title, $model, $text, $mode, $expected ) {
		global $wgArticleCountMethod;

		$oldArticleCountMethod = $wgArticleCountMethod;
		$wgArticleCountMethod = $mode;

		$page = $this->createPage( $title, $text, $model );
		$hasLinks = wfGetDB( DB_SLAVE )->selectField( 'pagelinks', 1,
					array( 'pl_from' => $page->getId() ), __METHOD__ );

		$editInfo = $page->prepareContentForEdit( $page->getContent() );

		$v = $page->isCountable();
		$w = $page->isCountable( $editInfo );

		$wgArticleCountMethod = $oldArticleCountMethod;

		$this->assertEquals( $expected, $v, "isCountable( null ) returned unexpected value " . var_export( $v, true )
											. " instead of " . var_export( $expected, true ) . " in mode `$mode` for text \"$text\"" );

		$this->assertEquals( $expected, $w, "isCountable( \$editInfo ) returned unexpected value " . var_export( $v, true )
											. " instead of " . var_export( $expected, true ) . " in mode `$mode` for text \"$text\"" );
	}

	public static function provideGetParserOutput() {
		return array(
			array( CONTENT_MODEL_WIKITEXT, "hello ''world''\n", "<p>hello <i>world</i></p>"),
			// @todo: more...?
		);
	}

	/**
	 * @dataProvider provideGetParserOutput
	 */
	public function testGetParserOutput( $model, $text, $expectedHtml ) {
		$page = $this->createPage( 'WikiPageTest_testGetParserOutput', $text, $model );

		$opt = new ParserOptions();
		$po = $page->getParserOutput( $opt );
		$text = $po->getText();

		$text = trim( preg_replace( '/<!--.*?-->/sm', '', $text ) ); # strip injected comments
		$text = preg_replace( '!\s*(</p>)!sm', '\1', $text ); # don't let tidy confuse us

		$this->assertEquals( $expectedHtml, $text );
		return $po;
	}

	public function testGetParserOutput_nonexisting( ) {
		static $count = 0;
		$count ++;

		$page = new WikiPage( new Title( "WikiPageTest_testGetParserOutput_nonexisting_$count" ) );

		$opt = new ParserOptions();
		$po = $page->getParserOutput( $opt );

		$this->assertFalse( $po, "getParserOutput() shall return false for non-existing pages." );
	}

	public function testGetParserOutput_badrev( ) {
		$page = $this->createPage( 'WikiPageTest_testGetParserOutput', "dummy", CONTENT_MODEL_WIKITEXT );

		$opt = new ParserOptions();
		$po = $page->getParserOutput( $opt, $page->getLatest() + 1234 );

		//@todo: would be neat to also test deleted revision

		$this->assertFalse( $po, "getParserOutput() shall return false for non-existing revisions." );
	}

	static $sections =

		"Intro

== stuff ==
hello world

== test ==
just a test

== foo ==
more stuff
";


	public function dataReplaceSection() {
		//NOTE: assume the Help namespace to contain wikitext
		return array(
			array( 'Help:WikiPageTest_testReplaceSection',
					CONTENT_MODEL_WIKITEXT,
					WikiPageTest::$sections,
					"0",
					"No more",
					null,
					trim( preg_replace( '/^Intro/sm', 'No more', WikiPageTest::$sections ) )
			),
			array( 'Help:WikiPageTest_testReplaceSection',
					CONTENT_MODEL_WIKITEXT,
					WikiPageTest::$sections,
					"",
					"No more",
					null,
					"No more"
			),
			array( 'Help:WikiPageTest_testReplaceSection',
					CONTENT_MODEL_WIKITEXT,
					WikiPageTest::$sections,
					"2",
					"== TEST ==\nmore fun",
					null,
					trim( preg_replace( '/^== test ==.*== foo ==/sm',
										"== TEST ==\nmore fun\n\n== foo ==",
										WikiPageTest::$sections ) )
			),
			array( 'Help:WikiPageTest_testReplaceSection',
					CONTENT_MODEL_WIKITEXT,
					WikiPageTest::$sections,
					"8",
					"No more",
					null,
					trim( WikiPageTest::$sections )
			),
			array( 'Help:WikiPageTest_testReplaceSection',
					CONTENT_MODEL_WIKITEXT,
					WikiPageTest::$sections,
					"new",
					"No more",
					"New",
					trim( WikiPageTest::$sections ) . "\n\n== New ==\n\nNo more"
			),
		);
	}

	/**
	 * @dataProvider dataReplaceSection
	 */
	public function testReplaceSection( $title, $model, $text, $section, $with, $sectionTitle, $expected ) {
		$this->hideDeprecated( "WikiPage::replaceSection" );

		$page = $this->createPage( $title, $text, $model );
		$text = $page->replaceSection( $section, $with, $sectionTitle );
		$text = trim( $text );

		$this->assertEquals( $expected, $text );
	}

	/**
	 * @dataProvider dataReplaceSection
	 */
	public function testReplaceSectionContent( $title, $model, $text, $section, $with, $sectionTitle, $expected ) {
		$page = $this->createPage( $title, $text, $model );

		$content = ContentHandler::makeContent( $with, $page->getTitle(), $page->getContentModel() );
		$c = $page->replaceSectionContent( $section, $content, $sectionTitle );

		$this->assertEquals( $expected, is_null( $c ) ? null : trim( $c->getNativeData() ) );
	}

	/* @todo FIXME: fix this!
	public function testGetUndoText() {
		global $wgDiff3;

		wfSuppressWarnings();
		$haveDiff3 = $wgDiff3 && file_exists( $wgDiff3 );
		wfRestoreWarnings();

		if( !$haveDiff3 ) {
			$this->markTestSkipped( "diff3 not installed or not found" );
			return;
		}

		$text = "one";
		$page = $this->createPage( "WikiPageTest_testGetUndoText", $text );
		$rev1 = $page->getRevision();

		$text .= "\n\ntwo";
		$page->doEditContent( ContentHandler::makeContent( $text, $page->getTitle() ), "adding section two");
		$rev2 = $page->getRevision();

		$text .= "\n\nthree";
		$page->doEditContent( ContentHandler::makeContent( $text, $page->getTitle() ), "adding section three");
		$rev3 = $page->getRevision();

		$text .= "\n\nfour";
		$page->doEditContent( ContentHandler::makeContent( $text, $page->getTitle() ), "adding section four");
		$rev4 = $page->getRevision();

		$text .= "\n\nfive";
		$page->doEditContent( ContentHandler::makeContent( $text, $page->getTitle() ), "adding section five");
		$rev5 = $page->getRevision();

		$text .= "\n\nsix";
		$page->doEditContent( ContentHandler::makeContent( $text, $page->getTitle() ), "adding section six");
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
	 * @todo FIXME: this is a better rollback test than the one below, but it keeps failing in jenkins for some reason.
	 */
	public function broken_testDoRollback() {
		$admin = new User();
		$admin->setName("Admin");

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

		# we are having issues with doRollback spuriously failing. apparently the last revision somehow goes missing
		# or not committed under some circumstances. so, make sure the last revision has the right user name.
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
		$admin->addGroup( "sysop" ); #XXX: make the test user a sysop...
		$token = $admin->getEditToken( array( $page->getTitle()->getPrefixedText(), $user2->getName() ), null );
		$errors = $page->doRollback( $user2->getName(), "testing revert", $token, false, $details, $admin );

		if ( $errors ) {
			$this->fail( "Rollback failed:\n" . print_r( $errors, true ) . ";\n" . print_r( $details, true ) );
		}

		$page = new WikiPage( $page->getTitle() );
		$this->assertEquals( $rev2->getSha1(), $page->getRevision()->getSha1(),
								"rollback did not revert to the correct revision" );
		$this->assertEquals( "one\n\ntwo", $page->getContent()->getNativeData() );
	}

	/**
	 * @todo FIXME: the above rollback test is better, but it keeps failing in jenkins for some reason.
	 */
	public function testDoRollback() {
		$admin = new User();
		$admin->setName("Admin");

		$text = "one";
		$page = $this->newPage( "WikiPageTest_testDoRollback" );
		$page->doEditContent( ContentHandler::makeContent( $text, $page->getTitle(), CONTENT_MODEL_WIKITEXT ),
								"section one", EDIT_NEW, false, $admin );
		$rev1 = $page->getRevision();

		$user1 = new User();
		$user1->setName( "127.0.1.11" );
		$text .= "\n\ntwo";
		$page = new WikiPage( $page->getTitle() );
		$page->doEditContent( ContentHandler::makeContent( $text, $page->getTitle(), CONTENT_MODEL_WIKITEXT ),
								"adding section two", 0, false, $user1 );

		# now, try the rollback
		$admin->addGroup( "sysop" ); #XXX: make the test user a sysop...
		$token = $admin->getEditToken( array( $page->getTitle()->getPrefixedText(), $user1->getName() ), null );
		$errors = $page->doRollback( $user1->getName(), "testing revert", $token, false, $details, $admin );

		if ( $errors ) {
			$this->fail( "Rollback failed:\n" . print_r( $errors, true ) . ";\n" . print_r( $details, true ) );
		}

		$page = new WikiPage( $page->getTitle() );
		$this->assertEquals( $rev1->getSha1(), $page->getRevision()->getSha1(),
							"rollback did not revert to the correct revision" );
		$this->assertEquals( "one", $page->getContent()->getNativeData() );
	}

	public static function provideGetAutosummary( ) {
		return array(
			array(
				'Hello there, world!',
				'#REDIRECT [[Foo]]',
				0,
				'/^Redirected page .*Foo/'
			),

			array(
				null,
				'Hello world!',
				EDIT_NEW,
				'/^Created page .*Hello/'
			),

			array(
				'Hello there, world!',
				'',
				0,
				'/^Blanked/'
			),

			array(
				'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
				labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et
				ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',
				'Hello world!',
				0,
				'/^Replaced .*Hello/'
			),

			array(
				'foo',
				'bar',
				0,
				'/^$/'
			),
		);
	}

	/**
	 * @dataProvider provideGetAutoSummary
	 */
	public function testGetAutosummary( $old, $new, $flags, $expected ) {
		$this->hideDeprecated( "WikiPage::getAutosummary" );

		$page = $this->newPage( "WikiPageTest_testGetAutosummary" );

		$summary = $page->getAutosummary( $old, $new, $flags );

		$this->assertTrue( (bool)preg_match( $expected, $summary ),
							"Autosummary didn't match expected pattern $expected: $summary" );
	}

	public static function provideGetAutoDeleteReason( ) {
		return array(
			array(
				array(),
				false,
				false
			),

			array(
				array(
					array( "first edit", null ),
				),
				"/first edit.*only contributor/",
				false
			),

			array(
				array(
					array( "first edit", null ),
					array( "second edit", null ),
				),
				"/second edit.*only contributor/",
				true
			),

			array(
				array(
					array( "first edit", "127.0.2.22" ),
					array( "second edit", "127.0.3.33" ),
				),
				"/second edit/",
				true
			),

			array(
				array(
					array( "first edit: "
						 . "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam "
						 . " nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. "
						 . "At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea "
						 . "takimata sanctus est Lorem ipsum dolor sit amet.'", null ),
				),
				'/first edit:.*\.\.\."/',
				false
			),

			array(
				array(
					array( "first edit", "127.0.2.22" ),
					array( "", "127.0.3.33" ),
				),
				"/before blanking.*first edit/",
				true
			),

		);
	}

	/**
	 * @dataProvider provideGetAutoDeleteReason
	 */
	public function testGetAutoDeleteReason( $edits, $expectedResult, $expectedHistory ) {
		global $wgUser;

		//NOTE: assume Help namespace to contain wikitext
		$page = $this->newPage( "Help:WikiPageTest_testGetAutoDeleteReason" );

		$c = 1;

		foreach ( $edits as $edit ) {
			$user = new User();

			if ( !empty( $edit[1] ) ) $user->setName( $edit[1] );
			else $user = $wgUser;

			$content = ContentHandler::makeContent( $edit[0], $page->getTitle(), $page->getContentModel() );

			$page->doEditContent( $content, "test edit $c", $c < 2 ? EDIT_NEW : 0, false, $user );

			$c += 1;
		}

		$reason = $page->getAutoDeleteReason( $hasHistory );

		if ( is_bool( $expectedResult ) || is_null( $expectedResult ) ) $this->assertEquals( $expectedResult, $reason );
		else $this->assertTrue( (bool)preg_match( $expectedResult, $reason ),
								"Autosummary didn't match expected pattern $expectedResult: $reason" );

		$this->assertEquals( $expectedHistory, $hasHistory,
							"expected \$hasHistory to be " . var_export( $expectedHistory, true ) );

		$page->doDeleteArticle( "done" );
	}

	public static function providePreSaveTransform() {
		return array(
			array( 'hello this is ~~~',
					"hello this is [[Special:Contributions/127.0.0.1|127.0.0.1]]",
			),
			array( 'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
					'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
			),
		);
	}

	/**
	 * @dataProvider providePreSaveTransform
	 */
	public function testPreSaveTransform( $text, $expected ) {
		$this->hideDeprecated( 'WikiPage::preSaveTransform' );
		$user = new User();
		$user->setName("127.0.0.1");

		//NOTE: assume Help namespace to contain wikitext
		$page = $this->newPage( "Help:WikiPageTest_testPreloadTransform" );
		$text = $page->preSaveTransform( $text, $user );

		$this->assertEquals( $expected, $text );
	}

}


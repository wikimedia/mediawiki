<?php

use MediaWiki\Edit\PreparedEdit;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\RevisionSlotsUpdate;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers WikiPage
 */
abstract class WikiPageDbTestBase extends MediaWikiLangTestCase {

	private $pagesToDelete;

	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'page',
				'revision',
				'redirect',
				'archive',
				'category',
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

	protected function addCoreDBData() {
		// Blank out. This would fail with a modified schema, and we don't need it.
	}

	/**
	 * @return int
	 */
	abstract protected function getMcrMigrationStage();

	/**
	 * @return string[]
	 */
	abstract protected function getMcrTablesToReset();

	protected function setUp() {
		parent::setUp();

		$this->tablesUsed += $this->getMcrTablesToReset();

		$this->setMwGlobals( 'wgContentHandlerUseDB', $this->getContentHandlerUseDB() );
		$this->setMwGlobals(
			'wgMultiContentRevisionSchemaMigrationStage',
			$this->getMcrMigrationStage()
		);
		$this->pagesToDelete = [];

		$this->overrideMwServices();
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
	 * @param string|Content|Content[] $content
	 * @param int|null $model
	 *
	 * @return WikiPage
	 */
	protected function createPage( $page, $content, $model = null, $user = null ) {
		if ( is_string( $page ) || $page instanceof Title ) {
			$page = $this->newPage( $page, $model );
		}

		if ( !$user ) {
			$user = $this->getTestUser()->getUser();
		}

		if ( is_string( $content ) ) {
			$content = ContentHandler::makeContent( $content, $page->getTitle(), $model );
		}

		if ( !is_array( $content ) ) {
			$content = [ 'main' => $content ];
		}

		$updater = $page->newPageUpdater( $user );

		foreach ( $content as $role => $cnt ) {
			$updater->setContent( $role, $cnt );
		}

		$updater->saveRevision( CommentStoreComment::newUnsavedComment( "testing" ) );

		return $page;
	}

	/**
	 * @covers WikiPage::prepareContentForEdit
	 */
	public function testPrepareContentForEdit() {
		$user = $this->getTestUser()->getUser();
		$sysop = $this->getTestUser( [ 'sysop' ] )->getUser();

		$page = $this->createPage( __METHOD__, __METHOD__, null, $user );
		$title = $page->getTitle();

		$content = ContentHandler::makeContent(
			"[[Lorem ipsum]] dolor sit amet, consetetur sadipscing elitr, sed diam "
			. " nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.",
			$title,
			CONTENT_MODEL_WIKITEXT
		);
		$content2 = ContentHandler::makeContent(
			"At vero eos et accusam et justo duo [[dolores]] et ea rebum. "
			. "Stet clita kasd [[gubergren]], no sea takimata sanctus est. ~~~~",
			$title,
			CONTENT_MODEL_WIKITEXT
		);

		$edit = $page->prepareContentForEdit( $content, null, $user, null, false );

		$this->assertInstanceOf(
			ParserOptions::class,
			$edit->popts,
			"pops"
		);
		$this->assertContains( '</a>', $edit->output->getText(), "output" );
		$this->assertContains(
			'consetetur sadipscing elitr',
			$edit->output->getText(),
			"output"
		);

		$this->assertTrue( $content->equals( $edit->newContent ), "newContent field" );
		$this->assertTrue( $content->equals( $edit->pstContent ), "pstContent field" );
		$this->assertSame( $edit->output, $edit->output, "output field" );
		$this->assertSame( $edit->popts, $edit->popts, "popts field" );
		$this->assertSame( null, $edit->revid, "revid field" );

		// Re-using the prepared info if possible
		$sameEdit = $page->prepareContentForEdit( $content, null, $user, null, false );
		$this->assertPreparedEditEquals( $edit, $sameEdit, 'equivalent PreparedEdit' );
		$this->assertSame( $edit->pstContent, $sameEdit->pstContent, 're-use output' );
		$this->assertSame( $edit->output, $sameEdit->output, 're-use output' );

		// Not re-using the same PreparedEdit if not possible
		$rev = $page->getRevision();
		$edit2 = $page->prepareContentForEdit( $content2, null, $user, null, false );
		$this->assertPreparedEditNotEquals( $edit, $edit2 );
		$this->assertContains( 'At vero eos', $edit2->pstContent->serialize(), "content" );

		// Check pre-safe transform
		$this->assertContains( '[[gubergren]]', $edit2->pstContent->serialize() );
		$this->assertNotContains( '~~~~', $edit2->pstContent->serialize() );

		$edit3 = $page->prepareContentForEdit( $content2, null, $sysop, null, false );
		$this->assertPreparedEditNotEquals( $edit2, $edit3 );

		// TODO: test with passing revision, then same without revision.
	}

	/**
	 * @covers WikiPage::doEditUpdates
	 */
	public function testDoEditUpdates() {
		$user = $this->getTestUser()->getUser();

		// NOTE: if site stats get out of whack and drop below 0,
		// that causes a DB error during tear-down. So bump the
		// numbers high enough to not drop below 0.
		$siteStatsUpdate = SiteStatsUpdate::factory(
			[ 'edits' => 1000, 'articles' => 1000, 'pages' => 1000 ]
		);
		$siteStatsUpdate->doUpdate();

		$page = $this->createPage( __METHOD__, __METHOD__ );

		$revision = new Revision(
			[
				'id' => 9989,
				'page' => $page->getId(),
				'title' => $page->getTitle(),
				'comment' => __METHOD__,
				'minor_edit' => true,
				'text' => __METHOD__ . ' [[|foo]][[bar]]', // PST turns [[|foo]] into [[foo]]
				'user' => $user->getId(),
				'user_text' => $user->getName(),
				'timestamp' => '20170707040404',
				'content_model' => CONTENT_MODEL_WIKITEXT,
				'content_format' => CONTENT_FORMAT_WIKITEXT,
			]
		);

		$page->doEditUpdates( $revision, $user );

		// TODO: test various options; needs temporary hooks

		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'pagelinks', '*', [ 'pl_from' => $page->getId() ] );
		$n = $res->numRows();
		$res->free();

		$this->assertEquals( 1, $n, 'pagelinks should contain only one link if PST was not applied' );
	}

	/**
	 * @covers WikiPage::doEditContent
	 * @covers WikiPage::prepareContentForEdit
	 */
	public function testDoEditContent() {
		$this->setMwGlobals( 'wgPageCreationLog', true );

		$page = $this->newPage( __METHOD__ );
		$title = $page->getTitle();

		$user1 = $this->getTestUser()->getUser();
		// Use the confirmed group for user2 to make sure the user is different
		$user2 = $this->getTestUser( [ 'confirmed' ] )->getUser();

		$content = ContentHandler::makeContent(
			"[[Lorem ipsum]] dolor sit amet, consetetur sadipscing elitr, sed diam "
				. " nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.",
			$title,
			CONTENT_MODEL_WIKITEXT
		);

		$preparedEditBefore = $page->prepareContentForEdit( $content, null, $user1 );

		$status = $page->doEditContent( $content, "[[testing]] 1", EDIT_NEW, false, $user1 );

		$this->assertTrue( $status->isOK(), 'OK' );
		$this->assertTrue( $status->value['new'], 'new' );
		$this->assertNotNull( $status->value['revision'], 'revision' );
		$this->assertSame( $status->value['revision']->getId(), $page->getRevision()->getId() );
		$this->assertSame( $status->value['revision']->getSha1(), $page->getRevision()->getSha1() );
		$this->assertTrue( $status->value['revision']->getContent()->equals( $content ), 'equals' );

		$rev = $page->getRevision();
		$preparedEditAfter = $page->prepareContentForEdit( $content, $rev, $user1 );

		$this->assertNotNull( $rev->getRecentChange() );
		$this->assertSame( $rev->getId(), (int)$rev->getRecentChange()->getAttribute( 'rc_this_oldid' ) );

		// make sure that cached ParserOutput gets re-used throughout
		$this->assertSame( $preparedEditBefore->output, $preparedEditAfter->output );

		$id = $page->getId();

		// Test page creation logging
		$this->assertSelect(
			'logging',
			[ 'log_type', 'log_action' ],
			[ 'log_page' => $id ],
			[ [ 'create', 'create' ] ]
		);

		$this->assertTrue( $title->getArticleID() > 0, "Title object should have new page id" );
		$this->assertTrue( $id > 0, "WikiPage should have new page id" );
		$this->assertTrue( $title->exists(), "Title object should indicate that the page now exists" );
		$this->assertTrue( $page->exists(), "WikiPage object should indicate that the page now exists" );

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
		$page = new WikiPage( $title );

		// try null edit, with a different user
		$status = $page->doEditContent( $content, 'This changes nothing', EDIT_UPDATE, false, $user2 );
		$this->assertTrue( $status->isOK(), 'OK' );
		$this->assertFalse( $status->value['new'], 'new' );
		$this->assertNull( $status->value['revision'], 'revision' );
		$this->assertNotNull( $page->getRevision() );
		$this->assertTrue( $page->getRevision()->getContent()->equals( $content ), 'equals' );

		# ------------------------
		$content = ContentHandler::makeContent(
			"At vero eos et accusam et justo duo [[dolores]] et ea rebum. "
				. "Stet clita kasd [[gubergren]], no sea takimata sanctus est. ~~~~",
			$title,
			CONTENT_MODEL_WIKITEXT
		);

		$status = $page->doEditContent( $content, "testing 2", EDIT_UPDATE );
		$this->assertTrue( $status->isOK(), 'OK' );
		$this->assertFalse( $status->value['new'], 'new' );
		$this->assertNotNull( $status->value['revision'], 'revision' );
		$this->assertSame( $status->value['revision']->getId(), $page->getRevision()->getId() );
		$this->assertSame( $status->value['revision']->getSha1(), $page->getRevision()->getSha1() );
		$this->assertFalse(
			$status->value['revision']->getContent()->equals( $content ),
			'not equals (PST must substitute signature)'
		);

		$rev = $page->getRevision();
		$this->assertNotNull( $rev->getRecentChange() );
		$this->assertSame( $rev->getId(), (int)$rev->getRecentChange()->getAttribute( 'rc_this_oldid' ) );

		# ------------------------
		$page = new WikiPage( $title );

		$retrieved = $page->getContent();
		$newText = $retrieved->serialize();
		$this->assertContains( '[[gubergren]]', $newText, 'New text must replace old text.' );
		$this->assertNotContains( '~~~~', $newText, 'PST must substitute signature.' );

		# ------------------------
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'pagelinks', '*', [ 'pl_from' => $id ] );
		$n = $res->numRows();
		$res->free();

		$this->assertEquals( 2, $n, 'pagelinks should contain two links from the page' );
	}

	/**
	 * @covers WikiPage::doEditContent
	 */
	public function testDoEditContent_twice() {
		$title = Title::newFromText( __METHOD__ );
		$page = WikiPage::factory( $title );
		$content = ContentHandler::makeContent( '$1 van $2', $title );

		// Make sure we can do the exact same save twice.
		// This tests checks that internal caches are reset as appropriate.
		$status1 = $page->doEditContent( $content, __METHOD__ );
		$status2 = $page->doEditContent( $content, __METHOD__ );

		$this->assertTrue( $status1->isOK(), 'OK' );
		$this->assertTrue( $status2->isOK(), 'OK' );

		$this->assertTrue( isset( $status1->value['revision'] ), 'OK' );
		$this->assertFalse( isset( $status2->value['revision'] ), 'OK' );
	}

	/**
	 * Undeletion is covered in PageArchiveTest::testUndeleteRevisions()
	 * TODO: Revision deletion
	 *
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
	 * @covers WikiPage::doDeleteArticleReal
	 */
	public function testDoDeleteArticleReal_user0() {
		$page = $this->createPage(
			__METHOD__,
			"[[original text]] foo",
			CONTENT_MODEL_WIKITEXT
		);
		$id = $page->getId();

		$errorStack = '';
		$status = $page->doDeleteArticleReal(
			/* reason */ "testing user 0 deletion",
			/* suppress */ false,
			/* unused 1 */ null,
			/* unused 2 */ null,
			/* errorStack */ $errorStack,
			null
		);
		$logId = $status->getValue();
		$actorQuery = ActorMigration::newMigration()->getJoin( 'log_user' );
		$this->assertSelect(
			[ 'logging' ] + $actorQuery['tables'], /* table */
			[
				'log_type',
				'log_action',
				'log_comment',
				'log_user' => $actorQuery['fields']['log_user'],
				'log_user_text' => $actorQuery['fields']['log_user_text'],
				'log_namespace',
				'log_title',
			],
			[ 'log_id' => $logId ],
			[ [
				'delete',
				'delete',
				'testing user 0 deletion',
				'0',
				'127.0.0.1',
				(string)$page->getTitle()->getNamespace(),
				$page->getTitle()->getDBkey(),
			] ],
			[],
			$actorQuery['joins']
		);
	}

	/**
	 * @covers WikiPage::doDeleteArticleReal
	 */
	public function testDoDeleteArticleReal_userSysop() {
		$page = $this->createPage(
			__METHOD__,
			"[[original text]] foo",
			CONTENT_MODEL_WIKITEXT
		);
		$id = $page->getId();

		$user = $this->getTestSysop()->getUser();
		$errorStack = '';
		$status = $page->doDeleteArticleReal(
			/* reason */ "testing sysop deletion",
			/* suppress */ false,
			/* unused 1 */ null,
			/* unused 2 */ null,
			/* errorStack */ $errorStack,
			$user
		);
		$logId = $status->getValue();
		$actorQuery = ActorMigration::newMigration()->getJoin( 'log_user' );
		$this->assertSelect(
			[ 'logging' ] + $actorQuery['tables'], /* table */
			[
				'log_type',
				'log_action',
				'log_comment',
				'log_user' => $actorQuery['fields']['log_user'],
				'log_user_text' => $actorQuery['fields']['log_user_text'],
				'log_namespace',
				'log_title',
			],
			[ 'log_id' => $logId ],
			[ [
				'delete',
				'delete',
				'testing sysop deletion',
				(string)$user->getId(),
				$user->getName(),
				(string)$page->getTitle()->getNamespace(),
				$page->getTitle()->getDBkey(),
			] ],
			[],
			$actorQuery['joins']
		);
	}

	/**
	 * TODO: Test more stuff about suppression.
	 *
	 * @covers WikiPage::doDeleteArticleReal
	 */
	public function testDoDeleteArticleReal_suppress() {
		$page = $this->createPage(
			__METHOD__,
			"[[original text]] foo",
			CONTENT_MODEL_WIKITEXT
		);
		$id = $page->getId();

		$user = $this->getTestSysop()->getUser();
		$errorStack = '';
		$status = $page->doDeleteArticleReal(
			/* reason */ "testing deletion",
			/* suppress */ true,
			/* unused 1 */ null,
			/* unused 2 */ null,
			/* errorStack */ $errorStack,
			$user
		);
		$logId = $status->getValue();
		$actorQuery = ActorMigration::newMigration()->getJoin( 'log_user' );
		$this->assertSelect(
			[ 'logging' ] + $actorQuery['tables'], /* table */
			[
				'log_type',
				'log_action',
				'log_comment',
				'log_user' => $actorQuery['fields']['log_user'],
				'log_user_text' => $actorQuery['fields']['log_user_text'],
				'log_namespace',
				'log_title',
			],
			[ 'log_id' => $logId ],
			[ [
				'suppress',
				'delete',
				'testing deletion',
				(string)$user->getId(),
				$user->getName(),
				(string)$page->getTitle()->getNamespace(),
				$page->getTitle()->getDBkey(),
			] ],
			[],
			$actorQuery['joins']
		);

		$this->assertNull(
			$page->getContent( Revision::FOR_PUBLIC ),
			"WikiPage::getContent should return null after the page was suppressed for general users"
		);

		$this->assertNull(
			$page->getContent( Revision::FOR_THIS_USER, null ),
			"WikiPage::getContent should return null after the page was suppressed for user zero"
		);

		$this->assertNull(
			$page->getContent( Revision::FOR_THIS_USER, $user ),
			"WikiPage::getContent should return null after the page was suppressed even for a sysop"
		);
	}

	/**
	 * @covers WikiPage::doDeleteUpdates
	 */
	public function testDoDeleteUpdates() {
		$user = $this->getTestUser()->getUser();
		$page = $this->createPage(
			__METHOD__,
			"[[original text]] foo",
			CONTENT_MODEL_WIKITEXT
		);
		$id = $page->getId();
		$page->loadPageData(); // make sure the current revision is cached.

		// Similar to MovePage logic
		wfGetDB( DB_MASTER )->delete( 'page', [ 'page_id' => $id ], __METHOD__ );
		$page->doDeleteUpdates( $page->getId(), $page->getContent(), $page->getRevision(), $user );

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
	 * @param string $name
	 *
	 * @return ContentHandler
	 */
	protected function defineMockContentModelForUpdateTesting( $name ) {
		/** @var ContentHandler|MockObject $handler */
		$handler = $this->getMockBuilder( TextContentHandler::class )
			->setConstructorArgs( [ $name ] )
			->setMethods(
				[ 'getSecondaryDataUpdates', 'getDeletionUpdates', 'unserializeContent' ]
			)
			->getMock();

		$dataUpdate = new MWCallableUpdate( 'time' );
		$dataUpdate->_name = "$name data update";

		$deletionUpdate = new MWCallableUpdate( 'time' );
		$deletionUpdate->_name = "$name deletion update";

		$handler->method( 'getSecondaryDataUpdates' )->willReturn( [ $dataUpdate ] );
		$handler->method( 'getDeletionUpdates' )->willReturn( [ $deletionUpdate ] );
		$handler->method( 'unserializeContent' )->willReturnCallback(
			function ( $text ) use ( $handler ) {
				return $this->createMockContent( $handler, $text );
			}
		);

		$this->mergeMwGlobalArrayValue(
			'wgContentHandlers', [
				$name => function () use ( $handler ){
					return $handler;
				}
			]
		);

		return $handler;
	}

	/**
	 * @param ContentHandler $handler
	 * @param string $text
	 *
	 * @return Content
	 */
	protected function createMockContent( ContentHandler $handler, $text ) {
		/** @var Content|MockObject $content */
		$content = $this->getMockBuilder( TextContent::class )
			->setConstructorArgs( [ $text ] )
			->setMethods( [ 'getModel', 'getContentHandler' ] )
			->getMock();

		$content->method( 'getModel' )->willReturn( $handler->getModelID() );
		$content->method( 'getContentHandler' )->willReturn( $handler );

		return $content;
	}

	public function testGetDeletionUpdates() {
		$m1 = $this->defineMockContentModelForUpdateTesting( 'M1' );

		$mainContent1 = $this->createMockContent( $m1, 'main 1' );

		$page = new WikiPage( Title::newFromText( __METHOD__ ) );
		$page = $this->createPage(
			$page,
			[ 'main' => $mainContent1 ]
		);

		$dataUpdates = $page->getDeletionUpdates( $page->getRevisionRecord() );
		$this->assertNotEmpty( $dataUpdates );

		$updateNames = array_map( function ( $du ) {
			return isset( $du->_name ) ? $du->_name : get_class( $du );
		}, $dataUpdates );

		$this->assertContains( LinksDeletionUpdate::class, $updateNames );
		$this->assertContains( 'M1 deletion update', $updateNames );
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
			// The below added to protect against Media namespace
			// redirects which throw a fatal: (T203942)
			[
				'WikiPageTest_testGetRedirectTarget_3',
				CONTENT_MODEL_WIKITEXT,
				"#REDIRECT [[Media:hello_world]]",
				"File:Hello world"
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
		$this->assertEquals( WikitextContent::class, get_class( $c ) );

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
		// FIXME: fails under postgres
		$this->markTestSkippedIfDbType( 'postgres' );

		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser()->getUser();
		// Use the confirmed group for user2 to make sure the user is different
		$user2 = $this->getTestUser( [ 'confirmed' ] )->getUser();

		// make sure we can test autopatrolling
		$this->setMwGlobals( 'wgUseRCPatrol', true );

		// TODO: MCR: test rollback of multiple slots!
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

		$rc = MediaWikiServices::getInstance()->getRevisionStore()->getRecentChange(
			$page->getRevision()->getRevisionRecord()
		);

		$this->assertNotNull( $rc, 'RecentChanges entry' );
		$this->assertEquals(
			RecentChange::PRC_AUTOPATROLLED,
			$rc->getAttribute( 'rc_patrolled' ),
			'rc_patrolled'
		);

		// TODO: MCR: assert origin once we write slot data
		// $mainSlot = $page->getRevision()->getRevisionRecord()->getSlot( SlotRecord::MAIN );
		// $this->assertTrue( $mainSlot->isInherited(), 'isInherited' );
		// $this->assertSame( $rev2->getId(), $mainSlot->getOrigin(), 'getOrigin' );
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
		$this->assertEquals( WikiFilePage::class, get_class( $page ) );

		$title = Title::makeTitle( NS_CATEGORY, 'SomeCategory' );
		$page = WikiPage::factory( $title );
		$this->assertEquals( WikiCategoryPage::class, get_class( $page ) );

		$title = Title::makeTitle( NS_MAIN, 'SomePage' );
		$page = WikiPage::factory( $title );
		$this->assertEquals( WikiPage::class, get_class( $page ) );
	}

	/**
	 * @covers WikiPage::loadPageData
	 * @covers WikiPage::wasLoadedFrom
	 */
	public function testLoadPageData() {
		$title = Title::makeTitle( NS_MAIN, 'SomePage' );
		$page = WikiPage::factory( $title );

		$this->assertFalse( $page->wasLoadedFrom( IDBAccessObject::READ_NORMAL ) );
		$this->assertFalse( $page->wasLoadedFrom( IDBAccessObject::READ_LATEST ) );
		$this->assertFalse( $page->wasLoadedFrom( IDBAccessObject::READ_LOCKING ) );
		$this->assertFalse( $page->wasLoadedFrom( IDBAccessObject::READ_EXCLUSIVE ) );

		$page->loadPageData( IDBAccessObject::READ_NORMAL );
		$this->assertTrue( $page->wasLoadedFrom( IDBAccessObject::READ_NORMAL ) );
		$this->assertFalse( $page->wasLoadedFrom( IDBAccessObject::READ_LATEST ) );
		$this->assertFalse( $page->wasLoadedFrom( IDBAccessObject::READ_LOCKING ) );
		$this->assertFalse( $page->wasLoadedFrom( IDBAccessObject::READ_EXCLUSIVE ) );

		$page->loadPageData( IDBAccessObject::READ_LATEST );
		$this->assertTrue( $page->wasLoadedFrom( IDBAccessObject::READ_NORMAL ) );
		$this->assertTrue( $page->wasLoadedFrom( IDBAccessObject::READ_LATEST ) );
		$this->assertFalse( $page->wasLoadedFrom( IDBAccessObject::READ_LOCKING ) );
		$this->assertFalse( $page->wasLoadedFrom( IDBAccessObject::READ_EXCLUSIVE ) );

		$page->loadPageData( IDBAccessObject::READ_LOCKING );
		$this->assertTrue( $page->wasLoadedFrom( IDBAccessObject::READ_NORMAL ) );
		$this->assertTrue( $page->wasLoadedFrom( IDBAccessObject::READ_LATEST ) );
		$this->assertTrue( $page->wasLoadedFrom( IDBAccessObject::READ_LOCKING ) );
		$this->assertFalse( $page->wasLoadedFrom( IDBAccessObject::READ_EXCLUSIVE ) );

		$page->loadPageData( IDBAccessObject::READ_EXCLUSIVE );
		$this->assertTrue( $page->wasLoadedFrom( IDBAccessObject::READ_NORMAL ) );
		$this->assertTrue( $page->wasLoadedFrom( IDBAccessObject::READ_LATEST ) );
		$this->assertTrue( $page->wasLoadedFrom( IDBAccessObject::READ_LOCKING ) );
		$this->assertTrue( $page->wasLoadedFrom( IDBAccessObject::READ_EXCLUSIVE ) );
	}

	/**
	 * @dataProvider provideCommentMigrationOnDeletion
	 *
	 * @param int $writeStage
	 * @param int $readStage
	 */
	public function testCommentMigrationOnDeletion( $writeStage, $readStage ) {
		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', $writeStage );
		$this->overrideMwServices();

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
		$this->overrideMwServices();

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

	/**
	 * @covers WikiPage::updateCategoryCounts
	 */
	public function testUpdateCategoryCounts() {
		$page = new WikiPage( Title::newFromText( __METHOD__ ) );

		// Add an initial category
		$page->updateCategoryCounts( [ 'A' ], [], 0 );

		$this->assertEquals( 1, Category::newFromName( 'A' )->getPageCount() );
		$this->assertEquals( 0, Category::newFromName( 'B' )->getPageCount() );
		$this->assertEquals( 0, Category::newFromName( 'C' )->getPageCount() );

		// Add a new category
		$page->updateCategoryCounts( [ 'B' ], [], 0 );

		$this->assertEquals( 1, Category::newFromName( 'A' )->getPageCount() );
		$this->assertEquals( 1, Category::newFromName( 'B' )->getPageCount() );
		$this->assertEquals( 0, Category::newFromName( 'C' )->getPageCount() );

		// Add and remove a category
		$page->updateCategoryCounts( [ 'C' ], [ 'A' ], 0 );

		$this->assertEquals( 0, Category::newFromName( 'A' )->getPageCount() );
		$this->assertEquals( 1, Category::newFromName( 'B' )->getPageCount() );
		$this->assertEquals( 1, Category::newFromName( 'C' )->getPageCount() );
	}

	public function provideUpdateRedirectOn() {
		yield [ '#REDIRECT [[Foo]]', true, null, true, true, 0 ];
		yield [ '#REDIRECT [[Foo]]', true, 'Foo', true, false, 1 ];
		yield [ 'SomeText', false, null, false, true, 0 ];
		yield [ 'SomeText', false, 'Foo', false, false, 1 ];
	}

	/**
	 * @dataProvider provideUpdateRedirectOn
	 * @covers WikiPage::updateRedirectOn
	 *
	 * @param string $initialText
	 * @param bool $initialRedirectState
	 * @param string|null $redirectTitle
	 * @param bool|null $lastRevIsRedirect
	 * @param bool $expectedSuccess
	 * @param int $expectedRowCount
	 */
	public function testUpdateRedirectOn(
		$initialText,
		$initialRedirectState,
		$redirectTitle,
		$lastRevIsRedirect,
		$expectedSuccess,
		$expectedRowCount
	) {
		// FIXME: fails under sqlite and postgres
		$this->markTestSkippedIfDbType( 'sqlite' );
		$this->markTestSkippedIfDbType( 'postgres' );
		static $pageCounter = 0;
		$pageCounter++;

		$page = $this->createPage( Title::newFromText( __METHOD__ . $pageCounter ), $initialText );
		$this->assertSame( $initialRedirectState, $page->isRedirect() );

		$redirectTitle = is_string( $redirectTitle )
			? Title::newFromText( $redirectTitle )
			: $redirectTitle;

		$success = $page->updateRedirectOn( $this->db, $redirectTitle, $lastRevIsRedirect );
		$this->assertSame( $expectedSuccess, $success, 'Success assertion' );
		/**
		 * updateRedirectOn explicitly updates the redirect table (and not the page table).
		 * Most of core checks the page table for redirect status, so we have to be ugly and
		 * assert a select from the table here.
		 */
		$this->assertRedirectTableCountForPageId( $page->getId(), $expectedRowCount );
	}

	private function assertRedirectTableCountForPageId( $pageId, $expected ) {
		$this->assertSelect(
			'redirect',
			'COUNT(*)',
			[ 'rd_from' => $pageId ],
			[ [ strval( $expected ) ] ]
		);
	}

	/**
	 * @covers WikiPage::insertRedirectEntry
	 */
	public function testInsertRedirectEntry_insertsRedirectEntry() {
		$page = $this->createPage( Title::newFromText( __METHOD__ ), 'A' );
		$this->assertRedirectTableCountForPageId( $page->getId(), 0 );

		$targetTitle = Title::newFromText( 'SomeTarget#Frag' );
		$targetTitle->mInterwiki = 'eninter';
		$page->insertRedirectEntry( $targetTitle, null );

		$this->assertSelect(
			'redirect',
			[ 'rd_from', 'rd_namespace', 'rd_title', 'rd_fragment', 'rd_interwiki' ],
			[ 'rd_from' => $page->getId() ],
			[ [
				strval( $page->getId() ),
				strval( $targetTitle->getNamespace() ),
				strval( $targetTitle->getDBkey() ),
				strval( $targetTitle->getFragment() ),
				strval( $targetTitle->getInterwiki() ),
			] ]
		);
	}

	/**
	 * @covers WikiPage::insertRedirectEntry
	 */
	public function testInsertRedirectEntry_insertsRedirectEntryWithPageLatest() {
		$page = $this->createPage( Title::newFromText( __METHOD__ ), 'A' );
		$this->assertRedirectTableCountForPageId( $page->getId(), 0 );

		$targetTitle = Title::newFromText( 'SomeTarget#Frag' );
		$targetTitle->mInterwiki = 'eninter';
		$page->insertRedirectEntry( $targetTitle, $page->getLatest() );

		$this->assertSelect(
			'redirect',
			[ 'rd_from', 'rd_namespace', 'rd_title', 'rd_fragment', 'rd_interwiki' ],
			[ 'rd_from' => $page->getId() ],
			[ [
				strval( $page->getId() ),
				strval( $targetTitle->getNamespace() ),
				strval( $targetTitle->getDBkey() ),
				strval( $targetTitle->getFragment() ),
				strval( $targetTitle->getInterwiki() ),
			] ]
		);
	}

	/**
	 * @covers WikiPage::insertRedirectEntry
	 */
	public function testInsertRedirectEntry_doesNotInsertIfPageLatestIncorrect() {
		$page = $this->createPage( Title::newFromText( __METHOD__ ), 'A' );
		$this->assertRedirectTableCountForPageId( $page->getId(), 0 );

		$targetTitle = Title::newFromText( 'SomeTarget#Frag' );
		$targetTitle->mInterwiki = 'eninter';
		$page->insertRedirectEntry( $targetTitle, 215251 );

		$this->assertRedirectTableCountForPageId( $page->getId(), 0 );
	}

	private function getRow( array $overrides = [] ) {
		$row = [
			'page_id' => '44',
			'page_len' => '76',
			'page_is_redirect' => '1',
			'page_latest' => '99',
			'page_namespace' => '3',
			'page_title' => 'JaJaTitle',
			'page_restrictions' => 'edit=autoconfirmed,sysop:move=sysop',
			'page_touched' => '20120101020202',
			'page_links_updated' => '20140101020202',
		];
		foreach ( $overrides as $key => $value ) {
			$row[$key] = $value;
		}
		return (object)$row;
	}

	public function provideNewFromRowSuccess() {
		yield 'basic row' => [
			$this->getRow(),
			function ( WikiPage $wikiPage, self $test ) {
				$test->assertSame( 44, $wikiPage->getId() );
				$test->assertSame( 76, $wikiPage->getTitle()->getLength() );
				$test->assertTrue( $wikiPage->isRedirect() );
				$test->assertSame( 99, $wikiPage->getLatest() );
				$test->assertSame( 3, $wikiPage->getTitle()->getNamespace() );
				$test->assertSame( 'JaJaTitle', $wikiPage->getTitle()->getDBkey() );
				$test->assertSame(
					[
						'edit' => [ 'autoconfirmed', 'sysop' ],
						'move' => [ 'sysop' ],
					],
					$wikiPage->getTitle()->getAllRestrictions()
				);
				$test->assertSame( '20120101020202', $wikiPage->getTouched() );
				$test->assertSame( '20140101020202', $wikiPage->getLinksTimestamp() );
			}
		];
		yield 'different timestamp formats' => [
			$this->getRow( [
				'page_touched' => '2012-01-01 02:02:02',
				'page_links_updated' => '2014-01-01 02:02:02',
			] ),
			function ( WikiPage $wikiPage, self $test ) {
				$test->assertSame( '20120101020202', $wikiPage->getTouched() );
				$test->assertSame( '20140101020202', $wikiPage->getLinksTimestamp() );
			}
		];
		yield 'no restrictions' => [
			$this->getRow( [
				'page_restrictions' => '',
			] ),
			function ( WikiPage $wikiPage, self $test ) {
			$test->assertSame(
				[
					'edit' => [],
					'move' => [],
				],
				$wikiPage->getTitle()->getAllRestrictions()
			);
			}
		];
		yield 'not redirect' => [
			$this->getRow( [
				'page_is_redirect' => '0',
			] ),
			function ( WikiPage $wikiPage, self $test ) {
				$test->assertFalse( $wikiPage->isRedirect() );
			}
		];
	}

	/**
	 * @covers WikiPage::newFromRow
	 * @covers WikiPage::loadFromRow
	 * @dataProvider provideNewFromRowSuccess
	 *
	 * @param object $row
	 * @param callable $assertions
	 */
	public function testNewFromRow( $row, $assertions ) {
		$page = WikiPage::newFromRow( $row, 'fromdb' );
		$assertions( $page, $this );
	}

	public function provideTestNewFromId_returnsNullOnBadPageId() {
		yield[ 0 ];
		yield[ -11 ];
	}

	/**
	 * @covers WikiPage::newFromID
	 * @dataProvider provideTestNewFromId_returnsNullOnBadPageId
	 */
	public function testNewFromId_returnsNullOnBadPageId( $pageId ) {
		$this->assertNull( WikiPage::newFromID( $pageId ) );
	}

	/**
	 * @covers WikiPage::newFromID
	 */
	public function testNewFromId_appearsToFetchCorrectRow() {
		$createdPage = $this->createPage( __METHOD__, 'Xsfaij09' );
		$fetchedPage = WikiPage::newFromID( $createdPage->getId() );
		$this->assertSame( $createdPage->getId(), $fetchedPage->getId() );
		$this->assertEquals(
			$createdPage->getContent()->getNativeData(),
			$fetchedPage->getContent()->getNativeData()
		);
	}

	/**
	 * @covers WikiPage::newFromID
	 */
	public function testNewFromId_returnsNullOnNonExistingId() {
		$this->assertNull( WikiPage::newFromID( 2147483647 ) );
	}

	public function provideTestInsertProtectNullRevision() {
		// phpcs:disable Generic.Files.LineLength
		yield [
			'goat-message-key',
			[ 'edit' => 'sysop' ],
			[ 'edit' => '20200101040404' ],
			false,
			'Goat Reason',
			true,
			'(goat-message-key: WikiPageDbTestBase::testInsertProtectNullRevision, UTSysop)(colon-separator)Goat Reason(word-separator)(parentheses: (protect-summary-desc: (restriction-edit), (protect-level-sysop), (protect-expiring: 04:04, 1 (january) 2020, 1 (january) 2020, 04:04)))'
		];
		yield [
			'goat-key',
			[ 'edit' => 'sysop', 'move' => 'something' ],
			[ 'edit' => '20200101040404', 'move' => '20210101050505' ],
			false,
			'Goat Goat',
			true,
			'(goat-key: WikiPageDbTestBase::testInsertProtectNullRevision, UTSysop)(colon-separator)Goat Goat(word-separator)(parentheses: (protect-summary-desc: (restriction-edit), (protect-level-sysop), (protect-expiring: 04:04, 1 (january) 2020, 1 (january) 2020, 04:04))(word-separator)(protect-summary-desc: (restriction-move), (protect-level-something), (protect-expiring: 05:05, 1 (january) 2021, 1 (january) 2021, 05:05)))'
		];
		// phpcs:enable
	}

	/**
	 * @dataProvider provideTestInsertProtectNullRevision
	 * @covers WikiPage::insertProtectNullRevision
	 * @covers WikiPage::protectDescription
	 *
	 * @param string $revCommentMsg
	 * @param array $limit
	 * @param array $expiry
	 * @param bool $cascade
	 * @param string $reason
	 * @param bool|null $user true if the test sysop should be used, or null
	 * @param string $expectedComment
	 */
	public function testInsertProtectNullRevision(
		$revCommentMsg,
		array $limit,
		array $expiry,
		$cascade,
		$reason,
		$user,
		$expectedComment
	) {
		$this->setContentLang( 'qqx' );

		$page = $this->createPage( __METHOD__, 'Goat' );

		$user = $user === null ? $user : $this->getTestSysop()->getUser();

		$result = $page->insertProtectNullRevision(
			$revCommentMsg,
			$limit,
			$expiry,
			$cascade,
			$reason,
			$user
		);

		$this->assertTrue( $result instanceof Revision );
		$this->assertSame( $expectedComment, $result->getComment( Revision::RAW ) );
	}

	/**
	 * @covers WikiPage::updateRevisionOn
	 */
	public function testUpdateRevisionOn_existingPage() {
		$user = $this->getTestSysop()->getUser();
		$page = $this->createPage( __METHOD__, 'StartText' );

		$revision = new Revision(
			[
				'id' => 9989,
				'page' => $page->getId(),
				'title' => $page->getTitle(),
				'comment' => __METHOD__,
				'minor_edit' => true,
				'text' => __METHOD__ . '-text',
				'len' => strlen( __METHOD__ . '-text' ),
				'user' => $user->getId(),
				'user_text' => $user->getName(),
				'timestamp' => '20170707040404',
				'content_model' => CONTENT_MODEL_WIKITEXT,
				'content_format' => CONTENT_FORMAT_WIKITEXT,
			]
		);

		$result = $page->updateRevisionOn( $this->db, $revision );
		$this->assertTrue( $result );
		$this->assertSame( 9989, $page->getLatest() );
		$this->assertEquals( $revision, $page->getRevision() );
	}

	/**
	 * @covers WikiPage::updateRevisionOn
	 */
	public function testUpdateRevisionOn_NonExistingPage() {
		$user = $this->getTestSysop()->getUser();
		$page = $this->createPage( __METHOD__, 'StartText' );
		$page->doDeleteArticle( 'reason' );

		$revision = new Revision(
			[
				'id' => 9989,
				'page' => $page->getId(),
				'title' => $page->getTitle(),
				'comment' => __METHOD__,
				'minor_edit' => true,
				'text' => __METHOD__ . '-text',
				'len' => strlen( __METHOD__ . '-text' ),
				'user' => $user->getId(),
				'user_text' => $user->getName(),
				'timestamp' => '20170707040404',
				'content_model' => CONTENT_MODEL_WIKITEXT,
				'content_format' => CONTENT_FORMAT_WIKITEXT,
			]
		);

		$result = $page->updateRevisionOn( $this->db, $revision );
		$this->assertFalse( $result );
	}

	/**
	 * @covers WikiPage::updateIfNewerOn
	 */
	public function testUpdateIfNewerOn_olderRevision() {
		$user = $this->getTestSysop()->getUser();
		$page = $this->createPage( __METHOD__, 'StartText' );
		$initialRevision = $page->getRevision();

		$olderTimeStamp = wfTimestamp(
			TS_MW,
			wfTimestamp( TS_UNIX, $initialRevision->getTimestamp() ) - 1
		);

		$olderRevison = new Revision(
			[
				'id' => 9989,
				'page' => $page->getId(),
				'title' => $page->getTitle(),
				'comment' => __METHOD__,
				'minor_edit' => true,
				'text' => __METHOD__ . '-text',
				'len' => strlen( __METHOD__ . '-text' ),
				'user' => $user->getId(),
				'user_text' => $user->getName(),
				'timestamp' => $olderTimeStamp,
				'content_model' => CONTENT_MODEL_WIKITEXT,
				'content_format' => CONTENT_FORMAT_WIKITEXT,
			]
		);

		$result = $page->updateIfNewerOn( $this->db, $olderRevison );
		$this->assertFalse( $result );
	}

	/**
	 * @covers WikiPage::updateIfNewerOn
	 */
	public function testUpdateIfNewerOn_newerRevision() {
		$user = $this->getTestSysop()->getUser();
		$page = $this->createPage( __METHOD__, 'StartText' );
		$initialRevision = $page->getRevision();

		$newerTimeStamp = wfTimestamp(
			TS_MW,
			wfTimestamp( TS_UNIX, $initialRevision->getTimestamp() ) + 1
		);

		$newerRevision = new Revision(
			[
				'id' => 9989,
				'page' => $page->getId(),
				'title' => $page->getTitle(),
				'comment' => __METHOD__,
				'minor_edit' => true,
				'text' => __METHOD__ . '-text',
				'len' => strlen( __METHOD__ . '-text' ),
				'user' => $user->getId(),
				'user_text' => $user->getName(),
				'timestamp' => $newerTimeStamp,
				'content_model' => CONTENT_MODEL_WIKITEXT,
				'content_format' => CONTENT_FORMAT_WIKITEXT,
			]
		);
		$result = $page->updateIfNewerOn( $this->db, $newerRevision );
		$this->assertTrue( $result );
	}

	/**
	 * @covers WikiPage::insertOn
	 */
	public function testInsertOn() {
		$title = Title::newFromText( __METHOD__ );
		$page = new WikiPage( $title );

		$startTimeStamp = wfTimestampNow();
		$result = $page->insertOn( $this->db );
		$endTimeStamp = wfTimestampNow();

		$this->assertInternalType( 'int', $result );
		$this->assertTrue( $result > 0 );

		$condition = [ 'page_id' => $result ];

		// Check the default fields have been filled
		$this->assertSelect(
			'page',
			[
				'page_namespace',
				'page_title',
				'page_restrictions',
				'page_is_redirect',
				'page_is_new',
				'page_latest',
				'page_len',
			],
			$condition,
			[ [
				'0',
				__METHOD__,
				'',
				'0',
				'1',
				'0',
				'0',
			] ]
		);

		// Check the page_random field has been filled
		$pageRandom = $this->db->selectField( 'page', 'page_random', $condition );
		$this->assertTrue( (float)$pageRandom < 1 && (float)$pageRandom > 0 );

		// Assert the touched timestamp in the DB is roughly when we inserted the page
		$pageTouched = $this->db->selectField( 'page', 'page_touched', $condition );
		$this->assertTrue(
			wfTimestamp( TS_UNIX, $startTimeStamp )
			<= wfTimestamp( TS_UNIX, $pageTouched )
		);
		$this->assertTrue(
			wfTimestamp( TS_UNIX, $endTimeStamp )
			>= wfTimestamp( TS_UNIX, $pageTouched )
		);

		// Try inserting the same page again and checking the result is false (no change)
		$result = $page->insertOn( $this->db );
		$this->assertFalse( $result );
	}

	/**
	 * @covers WikiPage::insertOn
	 */
	public function testInsertOn_idSpecified() {
		$title = Title::newFromText( __METHOD__ );
		$page = new WikiPage( $title );
		$id = 1478952189;

		$result = $page->insertOn( $this->db, $id );

		$this->assertSame( $id, $result );

		$condition = [ 'page_id' => $result ];

		// Check there is actually a row in the db
		$this->assertSelect(
			'page',
			[ 'page_title' ],
			$condition,
			[ [ __METHOD__ ] ]
		);
	}

	public function provideTestDoUpdateRestrictions_setBasicRestrictions() {
		// Note: Once the current dates passes the date in these tests they will fail.
		yield 'move something' => [
			true,
			[ 'move' => 'something' ],
			[],
			[ 'edit' => [], 'move' => [ 'something' ] ],
			[],
		];
		yield 'move something, edit blank' => [
			true,
			[ 'move' => 'something', 'edit' => '' ],
			[],
			[ 'edit' => [], 'move' => [ 'something' ] ],
			[],
		];
		yield 'edit sysop, with expiry' => [
			true,
			[ 'edit' => 'sysop' ],
			[ 'edit' => '21330101020202' ],
			[ 'edit' => [ 'sysop' ], 'move' => [] ],
			[ 'edit' => '21330101020202' ],
		];
		yield 'move and edit, move with expiry' => [
			true,
			[ 'move' => 'something', 'edit' => 'another' ],
			[ 'move' => '22220202010101' ],
			[ 'edit' => [ 'another' ], 'move' => [ 'something' ] ],
			[ 'move' => '22220202010101' ],
		];
		yield 'move and edit, edit with infinity expiry' => [
			true,
			[ 'move' => 'something', 'edit' => 'another' ],
			[ 'edit' => 'infinity' ],
			[ 'edit' => [ 'another' ], 'move' => [ 'something' ] ],
			[ 'edit' => 'infinity' ],
		];
		yield 'non existing, create something' => [
			false,
			[ 'create' => 'something' ],
			[],
			[ 'create' => [ 'something' ] ],
			[],
		];
		yield 'non existing, create something with expiry' => [
			false,
			[ 'create' => 'something' ],
			[ 'create' => '23451212112233' ],
			[ 'create' => [ 'something' ] ],
			[ 'create' => '23451212112233' ],
		];
	}

	/**
	 * @dataProvider provideTestDoUpdateRestrictions_setBasicRestrictions
	 * @covers WikiPage::doUpdateRestrictions
	 */
	public function testDoUpdateRestrictions_setBasicRestrictions(
		$pageExists,
		array $limit,
		array $expiry,
		array $expectedRestrictions,
		array $expectedRestrictionExpiries
	) {
		if ( $pageExists ) {
			$page = $this->createPage( __METHOD__, 'ABC' );
		} else {
			$page = new WikiPage( Title::newFromText( __METHOD__ . '-nonexist' ) );
		}
		$user = $this->getTestSysop()->getUser();
		$cascade = false;

		$status = $page->doUpdateRestrictions( $limit, $expiry, $cascade, 'aReason', $user, [] );

		$logId = $status->getValue();
		$allRestrictions = $page->getTitle()->getAllRestrictions();

		$this->assertTrue( $status->isGood() );
		$this->assertInternalType( 'int', $logId );
		$this->assertSame( $expectedRestrictions, $allRestrictions );
		foreach ( $expectedRestrictionExpiries as $key => $value ) {
			$this->assertSame( $value, $page->getTitle()->getRestrictionExpiry( $key ) );
		}

		// Make sure the log entry looks good
		// log_params is not checked here
		$actorQuery = ActorMigration::newMigration()->getJoin( 'log_user' );
		$this->assertSelect(
			[ 'logging' ] + $actorQuery['tables'],
			[
				'log_comment',
				'log_user' => $actorQuery['fields']['log_user'],
				'log_user_text' => $actorQuery['fields']['log_user_text'],
				'log_namespace',
				'log_title',
			],
			[ 'log_id' => $logId ],
			[ [
				'aReason',
				(string)$user->getId(),
				$user->getName(),
				(string)$page->getTitle()->getNamespace(),
				$page->getTitle()->getDBkey(),
			] ],
			[],
			$actorQuery['joins']
		);
	}

	/**
	 * @covers WikiPage::doUpdateRestrictions
	 */
	public function testDoUpdateRestrictions_failsOnReadOnly() {
		$page = $this->createPage( __METHOD__, 'ABC' );
		$user = $this->getTestSysop()->getUser();
		$cascade = false;

		// Set read only
		$readOnly = $this->getMockBuilder( ReadOnlyMode::class )
			->disableOriginalConstructor()
			->setMethods( [ 'isReadOnly', 'getReason' ] )
			->getMock();
		$readOnly->expects( $this->once() )
			->method( 'isReadOnly' )
			->will( $this->returnValue( true ) );
		$readOnly->expects( $this->once() )
			->method( 'getReason' )
			->will( $this->returnValue( 'Some Read Only Reason' ) );
		$this->setService( 'ReadOnlyMode', $readOnly );

		$status = $page->doUpdateRestrictions( [], [], $cascade, 'aReason', $user, [] );
		$this->assertFalse( $status->isOK() );
		$this->assertSame( 'readonlytext', $status->getMessage()->getKey() );
	}

	/**
	 * @covers WikiPage::doUpdateRestrictions
	 */
	public function testDoUpdateRestrictions_returnsGoodIfNothingChanged() {
		$page = $this->createPage( __METHOD__, 'ABC' );
		$user = $this->getTestSysop()->getUser();
		$cascade = false;
		$limit = [ 'edit' => 'sysop' ];

		$status = $page->doUpdateRestrictions(
			$limit,
			[],
			$cascade,
			'aReason',
			$user,
			[]
		);

		// The first entry should have a logId as it did something
		$this->assertTrue( $status->isGood() );
		$this->assertInternalType( 'int', $status->getValue() );

		$status = $page->doUpdateRestrictions(
			$limit,
			[],
			$cascade,
			'aReason',
			$user,
			[]
		);

		// The second entry should not have a logId as nothing changed
		$this->assertTrue( $status->isGood() );
		$this->assertNull( $status->getValue() );
	}

	/**
	 * @covers WikiPage::doUpdateRestrictions
	 */
	public function testDoUpdateRestrictions_logEntryTypeAndAction() {
		$page = $this->createPage( __METHOD__, 'ABC' );
		$user = $this->getTestSysop()->getUser();
		$cascade = false;

		// Protect the page
		$status = $page->doUpdateRestrictions(
			[ 'edit' => 'sysop' ],
			[],
			$cascade,
			'aReason',
			$user,
			[]
		);
		$this->assertTrue( $status->isGood() );
		$this->assertInternalType( 'int', $status->getValue() );
		$this->assertSelect(
			'logging',
			[ 'log_type', 'log_action' ],
			[ 'log_id' => $status->getValue() ],
			[ [ 'protect', 'protect' ] ]
		);

		// Modify the protection
		$status = $page->doUpdateRestrictions(
			[ 'edit' => 'somethingElse' ],
			[],
			$cascade,
			'aReason',
			$user,
			[]
		);
		$this->assertTrue( $status->isGood() );
		$this->assertInternalType( 'int', $status->getValue() );
		$this->assertSelect(
			'logging',
			[ 'log_type', 'log_action' ],
			[ 'log_id' => $status->getValue() ],
			[ [ 'protect', 'modify' ] ]
		);

		// Remove the protection
		$status = $page->doUpdateRestrictions(
			[],
			[],
			$cascade,
			'aReason',
			$user,
			[]
		);
		$this->assertTrue( $status->isGood() );
		$this->assertInternalType( 'int', $status->getValue() );
		$this->assertSelect(
			'logging',
			[ 'log_type', 'log_action' ],
			[ 'log_id' => $status->getValue() ],
			[ [ 'protect', 'unprotect' ] ]
		);
	}

	/**
	 * @covers WikiPage::newPageUpdater
	 * @covers WikiPage::getDerivedDataUpdater
	 */
	public function testNewPageUpdater() {
		$user = $this->getTestUser()->getUser();
		$page = $this->newPage( __METHOD__, __METHOD__ );

		/** @var Content $content */
		$content = $this->getMockBuilder( WikitextContent::class )
			->setConstructorArgs( [ 'Hello World' ] )
			->setMethods( [ 'getParserOutput' ] )
			->getMock();
		$content->expects( $this->once() )
			->method( 'getParserOutput' )
			->willReturn( new ParserOutput( 'HTML' ) );

		$preparedEditBefore = $page->prepareContentForEdit( $content, null, $user );

		// provide context, so the cache can be kept in place
		$slotsUpdate = new revisionSlotsUpdate();
		$slotsUpdate->modifyContent( SlotRecord::MAIN, $content );

		$updater = $page->newPageUpdater( $user, $slotsUpdate );
		$updater->setContent( SlotRecord::MAIN, $content );
		$revision = $updater->saveRevision(
			CommentStoreComment::newUnsavedComment( 'test' ),
			EDIT_NEW
		);

		$preparedEditAfter = $page->prepareContentForEdit( $content, $revision, $user );

		$this->assertSame( $revision->getId(), $page->getLatest() );

		// Parsed output must remain cached throughout.
		$this->assertSame( $preparedEditBefore->output, $preparedEditAfter->output );
	}

	/**
	 * @covers WikiPage::newPageUpdater
	 * @covers WikiPage::getDerivedDataUpdater
	 */
	public function testGetDerivedDataUpdater() {
		$admin = $this->getTestSysop()->getUser();

		/** @var object $page */
		$page = $this->createPage( __METHOD__, __METHOD__ );
		$page = TestingAccessWrapper::newFromObject( $page );

		$revision = $page->getRevision()->getRevisionRecord();
		$user = $revision->getUser();

		$slotsUpdate = new RevisionSlotsUpdate();
		$slotsUpdate->modifyContent( SlotRecord::MAIN, new WikitextContent( 'Hello World' ) );

		// get a virgin updater
		$updater1 = $page->getDerivedDataUpdater( $user );
		$this->assertFalse( $updater1->isUpdatePrepared() );

		$updater1->prepareUpdate( $revision );

		// Re-use updater with same revision or content, even if base changed
		$this->assertSame( $updater1, $page->getDerivedDataUpdater( $user, $revision ) );

		$slotsUpdate = RevisionSlotsUpdate::newFromContent(
			[ SlotRecord::MAIN => $revision->getContent( SlotRecord::MAIN ) ]
		);
		$this->assertSame( $updater1, $page->getDerivedDataUpdater( $user, null, $slotsUpdate ) );

		// Don't re-use for edit if base revision ID changed
		$this->assertNotSame(
			$updater1,
			$page->getDerivedDataUpdater( $user, null, $slotsUpdate, true )
		);

		// Don't re-use with different user
		$updater2a = $page->getDerivedDataUpdater( $admin, null, $slotsUpdate );
		$updater2a->prepareContent( $admin, $slotsUpdate, false );

		$updater2b = $page->getDerivedDataUpdater( $user, null, $slotsUpdate );
		$updater2b->prepareContent( $user, $slotsUpdate, false );
		$this->assertNotSame( $updater2a, $updater2b );

		// Don't re-use with different content
		$updater3 = $page->getDerivedDataUpdater( $admin, null, $slotsUpdate );
		$updater3->prepareUpdate( $revision );
		$this->assertNotSame( $updater2b, $updater3 );

		// Don't re-use if no context given
		$updater4 = $page->getDerivedDataUpdater( $admin );
		$updater4->prepareUpdate( $revision );
		$this->assertNotSame( $updater3, $updater4 );

		// Don't re-use if AGAIN no context given
		$updater5 = $page->getDerivedDataUpdater( $admin );
		$this->assertNotSame( $updater4, $updater5 );

		// Don't re-use cached "virgin" unprepared updater
		$updater6 = $page->getDerivedDataUpdater( $admin, $revision );
		$this->assertNotSame( $updater5, $updater6 );
	}

	protected function assertPreparedEditEquals(
		PreparedEdit $edit, PreparedEdit $edit2, $message = ''
	) {
		// suppress differences caused by a clock tick between generating the two PreparedEdits
		if ( abs( $edit->timestamp - $edit2->timestamp ) < 3 ) {
			$edit2 = clone $edit2;
			$edit2->timestamp = $edit->timestamp;
		}
		$this->assertEquals( $edit, $edit2, $message );
	}

	protected function assertPreparedEditNotEquals(
		PreparedEdit $edit, PreparedEdit $edit2, $message = ''
	) {
		if ( abs( $edit->timestamp - $edit2->timestamp ) < 3 ) {
			$edit2 = clone $edit2;
			$edit2->timestamp = $edit->timestamp;
		}
		$this->assertNotEquals( $edit, $edit2, $message );
	}

}

<?php

use MediaWiki\Category\Category;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\Content;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\JavaScriptContent;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Content\TextContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Deferred\SiteStatsUpdate;
use MediaWiki\Edit\PreparedEdit;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\Event\PageProtectionChangedEvent;
use MediaWiki\Page\Event\PageRevisionUpdatedEvent;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\RevisionSlotsUpdate;
use MediaWiki\Tests\ExpectCallbackTrait;
use MediaWiki\Tests\Language\LocalizationUpdateSpyTrait;
use MediaWiki\Tests\recentchanges\ChangeTrackingUpdateSpyTrait;
use MediaWiki\Tests\ResourceLoader\ResourceLoaderUpdateSpyTrait;
use MediaWiki\Tests\Search\SearchUpdateSpyTrait;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\Utils\MWTimestamp;
use PHPUnit\Framework\Assert;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Page\WikiPage
 * @group Database
 */
class WikiPageDbTest extends MediaWikiLangTestCase {
	use DummyServicesTrait;
	use MockAuthorityTrait;
	use TempUserTestTrait;
	use ChangeTrackingUpdateSpyTrait;
	use SearchUpdateSpyTrait;
	use LocalizationUpdateSpyTrait;
	use ResourceLoaderUpdateSpyTrait;
	use ExpectCallbackTrait;

	protected function tearDown(): void {
		ParserOptions::clearStaticCache();
		parent::tearDown();
	}

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

		return new WikiPage( $title );
	}

	/**
	 * @param string|Title|WikiPage $page
	 * @param string|Content|Content[] $content
	 * @param int|null $model
	 * @param Authority|null $performer
	 *
	 * @return WikiPage
	 */
	protected function createPage( $page, $content, $model = null, ?Authority $performer = null ) {
		if ( !$page instanceof WikiPage ) {
			$page = $this->newPage( $page, $model );
		}

		$performer ??= $this->getTestUser()->getUser();

		if ( is_string( $content ) ) {
			$content = ContentHandler::makeContent( $content, $page->getTitle(), $model );
		}

		if ( !is_array( $content ) ) {
			$content = [ SlotRecord::MAIN => $content ];
		}

		$updater = $page->newPageUpdater( $performer );

		foreach ( $content as $role => $cnt ) {
			$updater->setContent( $role, $cnt );
		}

		$updater->saveRevision( CommentStoreComment::newUnsavedComment( "testing" ) );
		if ( !$updater->wasSuccessful() ) {
			$this->fail( $updater->getStatus()->getWikiText() );
		}

		return $page;
	}

	public function testSerialization_fails() {
		$this->expectException( LogicException::class );
		$page = $this->createPage( __METHOD__, __METHOD__ );
		serialize( $page );
	}

	public static function provideTitlesThatCannotExist() {
		yield 'Special' => [ NS_SPECIAL, 'Recentchanges' ]; // existing special page
		yield 'Invalid character' => [ NS_MAIN, '#' ]; // bad character
	}

	/**
	 * @dataProvider provideTitlesThatCannotExist
	 */
	public function testConstructionWithPageThatCannotExist( $ns, $text ) {
		$title = Title::makeTitle( $ns, $text );
		$this->expectException( InvalidArgumentException::class );
		new WikiPage( $title );
	}

	public function testPrepareContentForEdit() {
		$performer = $this->mockUserAuthorityWithPermissions(
			$this->getTestUser()->getUserIdentity(),
			[ 'edit' ]
		);
		$sysop = $this->getTestUser( [ 'sysop' ] )->getUserIdentity();

		$page = $this->createPage( __METHOD__, __METHOD__, null, $performer );
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

		$edit = $page->prepareContentForEdit( $content, null, $performer->getUser(), null, false );

		$this->assertInstanceOf(
			ParserOptions::class,
			$edit->popts,
			"pops"
		);
		$this->assertStringContainsString( '</a>', $edit->output->getRawText(), "output" );
		$this->assertStringContainsString(
			'consetetur sadipscing elitr',
			$edit->output->getRawText(), "output"
		);

		$this->assertTrue( $content->equals( $edit->newContent ), "newContent field" );
		$this->assertTrue( $content->equals( $edit->pstContent ), "pstContent field" );
		$this->assertSame( $edit->output, $edit->output, "output field" );
		$this->assertSame( $edit->popts, $edit->popts, "popts field" );
		$this->assertSame( null, $edit->revid, "revid field" );

		// PreparedUpdate matches PreparedEdit
		$update = $page->getCurrentUpdate();
		$this->assertSame( $edit->output, $update->getCanonicalParserOutput() );

		// Re-using the prepared info if possible
		$sameEdit = $page->prepareContentForEdit( $content, null, $performer->getUser(), null, false );
		$this->assertPreparedEditEquals( $edit, $sameEdit, 'equivalent PreparedEdit' );
		$this->assertSame( $edit->pstContent, $sameEdit->pstContent, 're-use output' );
		$this->assertSame( $edit->output, $sameEdit->output, 're-use output' );

		// re-using the same PreparedUpdate
		$this->assertSame( $update, $page->getCurrentUpdate() );

		// Not re-using the same PreparedEdit if not possible
		$edit2 = $page->prepareContentForEdit( $content2, null, $performer->getUser(), null, false );
		$this->assertPreparedEditNotEquals( $edit, $edit2 );
		$this->assertStringContainsString( 'At vero eos', $edit2->pstContent->serialize(), "content" );

		// Not re-using the same PreparedUpdate
		$this->assertNotSame( $update, $page->getCurrentUpdate() );

		// Check pre-safe transform
		$this->assertStringContainsString( '[[gubergren]]', $edit2->pstContent->serialize() );
		$this->assertStringNotContainsString( '~~~~', $edit2->pstContent->serialize() );

		$edit3 = $page->prepareContentForEdit( $content2, null, $sysop, null, false );
		$this->assertPreparedEditNotEquals( $edit2, $edit3 );

		// TODO: test with passing revision, then same without revision.
	}

	public function testDoEditUpdates() {
		$this->hideDeprecated( WikiPage::class . '::doEditUpdates' );
		$user = $this->getTestUser()->getUserIdentity();

		// NOTE: if site stats get out of whack and drop below 0,
		// that causes a DB error during tear-down. So bump the
		// numbers high enough to not drop below 0.
		$siteStatsUpdate = SiteStatsUpdate::factory(
			[ 'edits' => 1000, 'articles' => 1000, 'pages' => 1000 ]
		);
		$siteStatsUpdate->doUpdate();

		$page = $this->createPage( __METHOD__, __METHOD__ );

		$comment = CommentStoreComment::newUnsavedComment( __METHOD__ );

		// PST turns [[|foo]] into [[foo]]
		$content = $this->getServiceContainer()
			->getContentHandlerFactory()
			->getContentHandler( CONTENT_MODEL_WIKITEXT )
			->unserializeContent( __METHOD__ . ' [[|foo]][[bar]]' );

		$revRecord = new MutableRevisionRecord( $page->getTitle() );
		$revRecord->setContent( SlotRecord::MAIN, $content );
		$revRecord->setUser( $user );
		$revRecord->setTimestamp( '20170707040404' );
		$revRecord->setPageId( $page->getId() );
		$revRecord->setId( 9989 );
		$revRecord->setMinorEdit( true );
		$revRecord->setComment( $comment );

		$page->doEditUpdates( $revRecord, $user );

		// TODO: test various options; needs temporary hooks

		$res = $this->getDb()->newSelectQueryBuilder()
			->select( '*' )
			->from( 'pagelinks' )
			->where( [ 'pl_from' => $page->getId() ] )
			->fetchResultSet();
		$n = $res->numRows();
		$res->free();

		$this->assertSame( 1, $n, 'pagelinks should contain only one link if PST was not applied' );
	}

	public function testDoUserEditContent() {
		$this->overrideConfigValue( MainConfigNames::PageCreationLog, true );

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

		$status = $page->doUserEditContent( $content, $user1, "[[testing]] 1", EDIT_NEW );

		$this->assertStatusGood( $status );
		$this->assertTrue( $status->value['new'], 'new' );
		$this->assertNotNull( $status->getNewRevision(), 'revision-record' );

		$statusRevRecord = $status->getNewRevision();
		$this->assertSame( $statusRevRecord->getId(), $page->getRevisionRecord()->getId() );
		$this->assertSame( $statusRevRecord->getSha1(), $page->getRevisionRecord()->getSha1() );
		$this->assertTrue(
			$statusRevRecord->getContent( SlotRecord::MAIN )->equals( $content ),
			'equals'
		);

		$revRecord = $page->getRevisionRecord();
		$recentChange = $this->getServiceContainer()
			->getRevisionStore()
			->getRecentChange( $revRecord );
		$preparedEditAfter = $page->prepareContentForEdit( $content, $revRecord, $user1 );

		$this->assertNotNull( $recentChange );
		$this->assertSame( $revRecord->getId(), (int)$recentChange->getAttribute( 'rc_this_oldid' ) );

		// make sure that cached ParserOutput gets re-used throughout
		$this->assertSame( $preparedEditBefore->output, $preparedEditAfter->output );

		$id = $page->getId();

		// Test page creation logging
		$this->newSelectQueryBuilder()
			->select( [ 'log_type', 'log_action' ] )
			->from( 'logging' )
			->where( [ 'log_page' => $id ] )
			->assertResultSet( [ [ 'create', 'create' ] ] );

		$this->assertGreaterThan( 0, $title->getArticleID(), "Title object should have new page id" );
		$this->assertGreaterThan( 0, $id, "WikiPage should have new page id" );
		$this->assertTrue( $title->exists(), "Title object should indicate that the page now exists" );
		$this->assertTrue( $page->exists(), "WikiPage object should indicate that the page now exists" );

		# ------------------------
		$res = $this->getDb()->newSelectQueryBuilder()
			->select( '*' )
			->from( 'pagelinks' )
			->where( [ 'pl_from' => $id ] )
			->fetchResultSet();
		$n = $res->numRows();
		$res->free();

		$this->assertSame( 1, $n, 'pagelinks should contain one link from the page' );

		# ------------------------
		$page = new WikiPage( $title );

		$retrieved = $page->getContent();
		$this->assertTrue( $content->equals( $retrieved ), 'retrieved content doesn\'t equal original' );

		# ------------------------
		$page = new WikiPage( $title );

		// try null edit, with a different user
		$status = $page->doUserEditContent( $content, $user2, 'This changes nothing', EDIT_UPDATE, false );
		$this->assertStatusWarning( 'edit-no-change', $status );
		$this->assertFalse( $status->value['new'], 'new' );
		$this->assertNull( $status->getNewRevision(), 'revision-record' );
		$this->assertNotNull( $page->getRevisionRecord() );
		$this->assertTrue(
			$page->getRevisionRecord()->getContent( SlotRecord::MAIN )->equals( $content ),
			'equals'
		);

		# ------------------------
		$content = ContentHandler::makeContent(
			"At vero eos et accusam et justo duo [[dolores]] et ea rebum. "
				. "Stet clita kasd [[gubergren]], no sea takimata sanctus est. ~~~~",
			$title,
			CONTENT_MODEL_WIKITEXT
		);

		$status = $page->doUserEditContent( $content, $user1, "testing 2", EDIT_UPDATE );
		$this->assertStatusGood( $status );
		$this->assertFalse( $status->value['new'], 'new' );
		$this->assertNotNull( $status->getNewRevision(), 'revision-record' );
		$statusRevRecord = $status->getNewRevision();
		$this->assertSame( $statusRevRecord->getId(), $page->getRevisionRecord()->getId() );
		$this->assertSame( $statusRevRecord->getSha1(), $page->getRevisionRecord()->getSha1() );
		$this->assertFalse(
			$statusRevRecord->getContent( SlotRecord::MAIN )->equals( $content ),
			'not equals (PST must substitute signature)'
		);

		$revRecord = $page->getRevisionRecord();
		$recentChange = $this->getServiceContainer()
			->getRevisionStore()
			->getRecentChange( $revRecord );
		$this->assertNotNull( $recentChange );
		$this->assertSame( $revRecord->getId(), (int)$recentChange->getAttribute( 'rc_this_oldid' ) );

		# ------------------------
		$page = new WikiPage( $title );

		$retrieved = $page->getContent();
		$newText = $retrieved->serialize();
		$this->assertStringContainsString( '[[gubergren]]', $newText, 'New text must replace old text.' );
		$this->assertStringNotContainsString( '~~~~', $newText, 'PST must substitute signature.' );

		# ------------------------
		$res = $this->getDb()->newSelectQueryBuilder()
			->select( '*' )
			->from( 'pagelinks' )
			->where( [ 'pl_from' => $id ] )
			->fetchResultSet();
		$n = $res->numRows();
		$res->free();

		// two in page text and two in signature
		$this->assertEquals( 4, $n, 'pagelinks should contain four links from the page' );
	}

	public static function provideNonPageTitle() {
		yield 'bad case and char' => [ Title::makeTitle( NS_MAIN, 'lower case and bad # char' ) ];
		yield 'empty' => [ Title::makeTitle( NS_MAIN, '' ) ];
		yield 'special' => [ Title::makeTitle( NS_SPECIAL, 'Dummy' ) ];
		yield 'relative' => [ Title::makeTitle( NS_MAIN, '', '#section' ) ];
		yield 'interwiki' => [ Title::makeTitle( NS_MAIN, 'Foo', '', 'acme' ) ];
	}

	/**
	 * @dataProvider provideNonPageTitle
	 */
	public function testDoUserEditContent_bad_page( $title ) {
		$user1 = $this->getTestUser()->getUser();

		$content = ContentHandler::makeContent(
			"Yadda yadda",
			$title,
			CONTENT_MODEL_WIKITEXT
		);

		$this->filterDeprecated( '/WikiPage constructed on a Title that cannot exist as a page/' );
		try {
			$page = $this->newPage( $title );
			$page->doUserEditContent( $content, $user1, "[[testing]] 1", EDIT_NEW );
		} catch ( Exception ) {
			// Throwing is an acceptable way to react to an invalid title,
			// as long as no garbage is written to the database.
		}

		$row = $this->getDb()->newSelectQueryBuilder()
			->select( '*' )
			->from( 'page' )
			->where( [ 'page_namespace' => $title->getNamespace(), 'page_title' => $title->getDBkey() ] )
			->fetchRow();

		$this->assertFalse( $row );
	}

	public function testDoUserEditContent_twice() {
		$title = Title::newFromText( __METHOD__ );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$content = ContentHandler::makeContent( '$1 van $2', $title );

		$user = $this->getTestUser()->getUser();

		// Make sure we can do the exact same save twice.
		// This tests checks that internal caches are reset as appropriate.
		$status1 = $page->doUserEditContent( $content, $user, __METHOD__ );
		$status2 = $page->doUserEditContent( $content, $user, __METHOD__ );

		$this->assertStatusGood( $status1 );
		$this->assertStatusWarning( 'edit-no-change', $status2 );

		$this->assertNotNull( $status1->getNewRevision(), 'OK' );
		$this->assertNull( $status2->getNewRevision(), 'OK' );
	}

	/**
	 * Undeletion is covered in PageArchiveTest::testUndeleteRevisions()
	 *
	 * TODO: Revision deletion
	 */
	public function testDoDeleteArticleReal() {
		$this->overrideConfigValues( [
			MainConfigNames::RCWatchCategoryMembership => false,
		] );

		$page = $this->createPage(
			__METHOD__,
			"[[original text]] foo",
			CONTENT_MODEL_WIKITEXT
		);
		$id = $page->getId();
		$user = $this->getTestSysop()->getUser();

		$reason = "testing deletion";
		$status = $page->doDeleteArticleReal( $reason, $user );

		$this->assertFalse(
			$page->getTitle()->getArticleID() > 0,
			"Title object should now have page id 0"
		);
		$this->assertSame( 0, $page->getId(), "WikiPage should now have page id 0" );
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
		$this->runJobs();

		# ------------------------
		$res = $this->getDb()->newSelectQueryBuilder()
			->select( '*' )
			->from( 'pagelinks' )
			->where( [ 'pl_from' => $id ] )
			->fetchResultSet();
		$n = $res->numRows();
		$res->free();

		$this->assertSame( 0, $n, 'pagelinks should contain no more links from the page' );

		// Test deletion logging
		$logId = $status->getValue();
		$commentQuery = $this->getServiceContainer()->getCommentStore()->getJoin( 'log_comment' );
		$this->newSelectQueryBuilder()
			->select( [
				'log_type',
				'log_action',
				'log_comment' => $commentQuery['fields']['log_comment_text'],
				'log_actor',
				'log_namespace',
				'log_title',
			] )
			->from( 'logging' )
			->tables( $commentQuery['tables'] )
			->where( [ 'log_id' => $logId ] )
			->joinConds( $commentQuery['joins'] )
			->assertRowValue( [
				'delete',
				'delete',
				$reason,
				(string)$user->getActorId(),
				(string)$page->getTitle()->getNamespace(),
				$page->getTitle()->getDBkey(),
			] );
	}

	/**
	 * TODO: Test more stuff about suppression.
	 */
	public function testDoDeleteArticleReal_suppress() {
		$page = $this->createPage(
			__METHOD__,
			"[[original text]] foo",
			CONTENT_MODEL_WIKITEXT
		);

		$user = $this->getTestSysop()->getUser();
		$status = $page->doDeleteArticleReal(
			/* reason */ "testing deletion",
			$user,
			/* suppress */ true
		);

		// Test suppression logging
		$logId = $status->getValue();
		$commentQuery = $this->getServiceContainer()->getCommentStore()->getJoin( 'log_comment' );
		$this->newSelectQueryBuilder()
			->select( [
				'log_type',
				'log_action',
				'log_comment' => $commentQuery['fields']['log_comment_text'],
				'log_actor',
				'log_namespace',
				'log_title',
			] )
			->from( 'logging' )
			->tables( $commentQuery['tables'] )
			->where( [ 'log_id' => $logId ] )
			->joinConds( $commentQuery['joins'] )
			->assertRowValue( [
				'suppress',
				'delete',
				'testing deletion',
				(string)$user->getActorId(),
				(string)$page->getTitle()->getNamespace(),
				$page->getTitle()->getDBkey(),
			] );

		$lookup = $this->getServiceContainer()->getArchivedRevisionLookup();
		$archivedRevs = $lookup->listRevisions( $page->getTitle() );
		if ( !$archivedRevs || $archivedRevs->numRows() !== 1 ) {
			$this->fail( 'Unexpected number of archived revisions' );
		}
		$archivedRev = $this->getServiceContainer()->getRevisionStore()
			->newRevisionFromArchiveRow( $archivedRevs->current() );

		$this->assertNull(
			$archivedRev->getContent( SlotRecord::MAIN, RevisionRecord::FOR_PUBLIC ),
			"Archived content should be null after the page was suppressed for general users"
		);

		$this->assertNull(
			$archivedRev->getContent(
				SlotRecord::MAIN,
				RevisionRecord::FOR_THIS_USER,
				$this->getTestUser()->getUser()
			),
			"Archived content should be null after the page was suppressed for individual users"
		);

		$this->hideDeprecated( 'ContentHandler::getSlotDiffRendererInternal' );
		$this->assertNull(
			$archivedRev->getContent( SlotRecord::MAIN, RevisionRecord::FOR_THIS_USER, $user ),
			"Archived content should be null after the page was suppressed even for a sysop"
		);
	}

	public function testGetContent() {
		$page = $this->newPage( __METHOD__ );

		$content = $page->getContent();
		$this->assertNull( $content );

		$this->createPage( $page, "some text", CONTENT_MODEL_WIKITEXT );

		$content = $page->getContent();
		$this->assertEquals( "some text", $content->getText() );
	}

	public function testExists() {
		$page = $this->newPage( __METHOD__ );
		$this->assertFalse( $page->exists() );

		$this->createPage( $page, "some text", CONTENT_MODEL_WIKITEXT );
		$this->assertTrue( $page->exists() );

		$page = new WikiPage( $page->getTitle() );
		$this->assertTrue( $page->exists() );

		$this->deletePage( $page, "done testing" );
		$this->assertFalse( $page->exists() );

		$page = new WikiPage( $page->getTitle() );
		$this->assertFalse( $page->exists() );
	}

	public static function provideHasViewableContent() {
		return [
			[ 'WikiPageTest_testHasViewableContent', false, true ],
			[ 'MediaWiki:WikiPageTest_testHasViewableContent', false ],
			[ 'MediaWiki:help', true ],
		];
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
			// Test fragments longer than 255 bytes (T207876)
			[
				'WikiPageTest_testGetRedirectTarget_4',
				CONTENT_MODEL_WIKITEXT,
				'#REDIRECT [[Foobar#🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿]]',
				'Foobar#🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬󠁦󠁲󠁿🏴󠁮󠁬'
			]
		];
	}

	/**
	 * @dataProvider provideGetRedirectTarget
	 * @covers \MediaWiki\Page\WikiPage
	 * @covers \MediaWiki\Page\RedirectStore
	 */
	public function testGetRedirectTarget( $title, $model, $text, $target ) {
		$this->overrideConfigValues( [
			MainConfigNames::CapitalLinks => true,
			// The file redirect can trigger http request with UseInstantCommons = true
			MainConfigNames::ForeignFileRepos => [],
		] );

		$titleFormatter = $this->getServiceContainer()->getTitleFormatter();

		$page = $this->createPage( $title, $text, $model );

		# double check, because this test seems to fail for no reason for some people.
		$c = $page->getContent();
		$this->assertEquals( WikitextContent::class, get_class( $c ) );

		# now, test the actual redirect
		$redirectStore = $this->getServiceContainer()->getRedirectStore();
		$t = $redirectStore->getRedirectTarget( $page );

		$this->assertEquals( $target, $t ? $titleFormatter->getFullText( $t ) : null );
	}

	/**
	 * @dataProvider provideGetRedirectTarget
	 */
	public function testIsRedirect( $title, $model, $text, $target ) {
		// The file redirect can trigger http request with UseInstantCommons = true
		$this->overrideConfigValue( MainConfigNames::ForeignFileRepos, [] );

		$page = $this->createPage( $title, $text, $model );
		$this->assertEquals( $target !== null, $page->isRedirect() );
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
	 */
	public function testIsCountable( $title, $model, $text, $mode, $expected ) {
		$this->overrideConfigValue( MainConfigNames::ArticleCountMethod, $mode );

		$title = Title::newFromText( $title );

		$page = $this->createPage( $title, $text, $model );

		$editInfo = $page->prepareContentForEdit(
			$page->getContent(),
			null,
			$this->getTestUser()->getUser()
		);

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

	/**
	 * @dataProvider provideMakeParserOptions
	 */
	public function testMakeParserOptions( int $ns, string $title, string $model, $context, callable $expectation ) {
		// Ensure we're working with the default values during this test.
		$this->overrideConfigValues( [
			MainConfigNames::TextModelsToParse => [
				CONTENT_MODEL_WIKITEXT,
				CONTENT_MODEL_JAVASCRIPT,
				CONTENT_MODEL_CSS,
			],
			MainConfigNames::DisableLangConversion => false,
		] );
		// Call the context function first, which lets us setup the
		// overall wiki context before invoking the function-under-test
		if ( is_callable( $context ) ) {
			$context = $context( $this );
		}
		$page = $this->createPage(
			Title::makeTitle( $ns, $title ), __METHOD__, $model
		);
		$parserOptions = $page->makeParserOptions( $context );
		$expected = $expectation();
		$this->assertTrue( $expected->matches( $parserOptions ) );
	}

	/**
	 * @dataProvider provideMakeParserOptions
	 */
	public function testMakeParserOptionsFromTitleAndModel( int $ns, string $title, string $model, $context, callable $expectation ) {
		// Ensure we're working with the default values during this test.
		$this->overrideConfigValues( [
			MainConfigNames::TextModelsToParse => [
				CONTENT_MODEL_WIKITEXT,
				CONTENT_MODEL_JAVASCRIPT,
				CONTENT_MODEL_CSS,
			],
			MainConfigNames::DisableLangConversion => false,
		] );
		// Call the context function first, which lets us setup the
		// overall wiki context before invoking the function-under-test
		if ( is_callable( $context ) ) {
			$context = $context( $this );
		}
		$parserOptions = WikiPage::makeParserOptionsFromTitleAndModel(
			Title::makeTitle( $ns, $title ), $model, $context
		);
		$expected = $expectation();
		$this->assertTrue( $expected->matches( $parserOptions ) );
	}

	public static function provideMakeParserOptions() {
		// Default canonical parser options for a normal wikitext page
		yield [
			NS_MAIN, 'Main Page', CONTENT_MODEL_WIKITEXT, 'canonical',
			static function () {
				return ParserOptions::newFromAnon();
			},
		];
		// JavaScript should have Table Of Contents suppressed
		yield [
			NS_MAIN, 'JavaScript Test', CONTENT_MODEL_JAVASCRIPT, 'canonical',
			static function () {
				return ParserOptions::newFromAnon();
			},
		];
		// CSS should have Table Of Contents suppressed
		yield [
			NS_MAIN, 'CSS Test', CONTENT_MODEL_CSS, 'canonical',
			static function () {
				return ParserOptions::newFromAnon();
			},
		];
		// Language Conversion tables have content conversion disabled
		yield [
			NS_MEDIAWIKI, 'Conversiontable/Test', CONTENT_MODEL_WIKITEXT,
			static function ( $test ) {
				// Switch wiki to a language where LanguageConverter is enabled
				$test->setContentLang( 'zh' );
				$test->setUserLang( 'en' );
				return 'canonical';
			},
			static function () {
				$po = ParserOptions::newFromAnon();
				$po->disableContentConversion();
				// "Canonical" PO should use content language not user language
				Assert::assertSame( 'zh', $po->getUserLang() );
				return $po;
			},
		];
		// Test "non-canonical" options: parser option should use user
		// language here, not content language
		$user = null;
		yield [
			NS_MAIN, 'Main Page', CONTENT_MODEL_WIKITEXT,
			static function ( $test ) use ( &$user ) {
				$test->setContentLang( 'qqx' );
				$test->setUserLang( 'fr' );
				$user = $test->getTestUser()->getUser();
				return $user;
			},
			static function () use ( &$user ) {
				$po = ParserOptions::newFromUser( $user );
				Assert::assertSame( 'fr', $po->getUserLang() );
				return $po;
			},
		];
	}

	public static function provideGetParserOutput() {
		return [
			[
				CONTENT_MODEL_WIKITEXT,
				"hello ''world''\n",
				'<div class="mw-content-ltr mw-parser-output" lang="en" dir="ltr"><p>hello <i>world</i></p></div>'
			],
			[
				CONTENT_MODEL_JAVASCRIPT,
				"var test='<h2>not really a heading</h2>';",
				"<pre class=\"mw-code mw-js\" dir=\"ltr\">\nvar test='&lt;h2>not really a heading&lt;/h2>';\n</pre>",
			],
			[
				CONTENT_MODEL_CSS,
				"/* Not ''wikitext'' */",
				"<pre class=\"mw-code mw-css\" dir=\"ltr\">\n/* Not ''wikitext'' */\n</pre>",
			],
			// @todo more...?
		];
	}

	/**
	 * @dataProvider provideGetParserOutput
	 */
	public function testGetParserOutput( $model, $text, $expectedHtml ) {
		$page = $this->createPage( __METHOD__, $text, $model );

		$opt = $page->makeParserOptions( 'canonical' );
		$po = $page->getParserOutput( $opt );
		$pipeline = MediaWikiServices::getInstance()->getDefaultOutputPipeline();
		$text = $pipeline->run( $po, $opt, [] )->getContentHolderText();

		$text = trim( preg_replace( '/<!--.*?-->/sm', '', $text ) ); # strip injected comments
		$text = preg_replace( '!\s*(</p>|</div>)!m', '\1', $text ); # don't let tidy confuse us

		$this->assertEquals( $expectedHtml, $text );
	}

	public function testGetParserOutput_nonexisting() {
		$page = new WikiPage( Title::newFromText( __METHOD__ ) );

		$opt = ParserOptions::newFromAnon();
		$po = $page->getParserOutput( $opt );

		$this->assertFalse( $po, "getParserOutput() shall return false for non-existing pages." );
	}

	public function testGetParserOutput_badrev() {
		$page = $this->createPage( __METHOD__, 'dummy', CONTENT_MODEL_WIKITEXT );

		$opt = ParserOptions::newFromAnon();
		$po = $page->getParserOutput( $opt, $page->getLatest() + 1234 );

		// @todo would be neat to also test deleted revision

		$this->assertFalse( $po, "getParserOutput() shall return false for non-existing revisions." );
	}

	public const SECTIONS =

		"Intro

== stuff ==
hello world

== test ==
just a test

== foo ==
more stuff
";

	public static function provideReplaceSection() {
		// NOTE: assume the Help namespace to contain wikitext
		return [
			[ 'Help:WikiPageTest_testReplaceSection',
				CONTENT_MODEL_WIKITEXT,
				self::SECTIONS,
				"0",
				"No more",
				null,
				trim( preg_replace( '/^Intro/m', 'No more', self::SECTIONS ) )
			],
			[ 'Help:WikiPageTest_testReplaceSection',
				CONTENT_MODEL_WIKITEXT,
				self::SECTIONS,
				"",
				"No more",
				null,
				"No more"
			],
			[ 'Help:WikiPageTest_testReplaceSection',
				CONTENT_MODEL_WIKITEXT,
				self::SECTIONS,
				"2",
				"== TEST ==\nmore fun",
				null,
				trim( preg_replace( '/^== test ==.*== foo ==/sm',
					"== TEST ==\nmore fun\n\n== foo ==",
					self::SECTIONS ) )
			],
			[ 'Help:WikiPageTest_testReplaceSection',
				CONTENT_MODEL_WIKITEXT,
				self::SECTIONS,
				"8",
				"No more",
				null,
				trim( self::SECTIONS )
			],
			[ 'Help:WikiPageTest_testReplaceSection',
				CONTENT_MODEL_WIKITEXT,
				self::SECTIONS,
				"new",
				"No more",
				"New",
				trim( self::SECTIONS ) . "\n\n== New ==\n\nNo more"
			],
		];
	}

	/**
	 * @dataProvider provideReplaceSection
	 */
	public function testReplaceSectionContent( $title, $model, $text, $section,
		$with, $sectionTitle, $expected
	) {
		$page = $this->createPage( $title, $text, $model );

		$content = ContentHandler::makeContent( $with, $page->getTitle(), $page->getContentModel() );
		/** @var TextContent $c */
		$c = $page->replaceSectionContent( $section, $content, $sectionTitle );

		$this->assertEquals( $expected, $c ? trim( $c->getText() ) : null );
	}

	/**
	 * @dataProvider provideReplaceSection
	 */
	public function testReplaceSectionAtRev( $title, $model, $text, $section,
		$with, $sectionTitle, $expected
	) {
		$page = $this->createPage( $title, $text, $model );
		$baseRevId = $page->getLatest();

		$content = ContentHandler::makeContent( $with, $page->getTitle(), $page->getContentModel() );
		/** @var TextContent $c */
		$c = $page->replaceSectionAtRev( $section, $content, $sectionTitle, $baseRevId );

		$this->assertEquals( $expected, $c ? trim( $c->getText() ) : null );
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
							. "no sea  takimata sanctus est Lorem ipsum dolor sit amet. "
							. " this here is some more filler content added to try and "
							. "reach the maximum automatic summary length so that this is"
							. " truncated ipot sodit colrad ut ad olve amit basul dat"
							. "Dorbet romt crobit trop bri. DannyS712 put me here lor pe"
							. " ode quob zot bozro see also T22281 for background pol sup"
							. "Lorem ipsum dolor sit amet'",
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
	 */
	public function testGetAutoDeleteReason( $edits, $expectedResult, $expectedHistory ) {
		$this->disableAutoCreateTempUser();

		// NOTE: assume Help namespace to contain wikitext
		$page = $this->newPage( "Help:WikiPageTest_testGetAutoDeleteReason" );

		$c = 1;

		foreach ( $edits as $edit ) {
			$user = new User();

			if ( !empty( $edit[1] ) ) {
				$user->setName( $edit[1] );
			} else {
				$user = new User;
			}

			$content = ContentHandler::makeContent( $edit[0], $page->getTitle(), $page->getContentModel() );

			$page->doUserEditContent( $content, $user, "test edit $c", $c < 2 ? EDIT_NEW : 0 );

			$c++;
		}

		$this->hideDeprecated( WikiPage::class . '::getAutoDeleteReason:' );
		$this->hideDeprecated( ContentHandler::class . '::getAutoDeleteReason:' );
		$reason = $page->getAutoDeleteReason( $hasHistory );

		if ( is_bool( $expectedResult ) || $expectedResult === null ) {
			$this->assertEquals( $expectedResult, $reason );
		} else {
			$this->assertTrue( (bool)preg_match( $expectedResult, $reason ),
				"Autosummary didn't match expected pattern $expectedResult: $reason" );
		}

		$this->assertEquals( $expectedHistory, $hasHistory,
			"expected \$hasHistory to be " . var_export( $expectedHistory, true ) );
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

	public function testLoadPageData() {
		$title = Title::makeTitle( NS_MAIN, 'SomePage' );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

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

	public function testUpdateCategoryCounts() {
		$page = new WikiPage( Title::newFromText( __METHOD__ ) );

		// Add an initial category
		$page->updateCategoryCounts( [ 'A' ], [], 0 );

		$this->assertSame( 1, Category::newFromName( 'A' )->getMemberCount() );
		$this->assertSame( 0, Category::newFromName( 'B' )->getMemberCount() );
		$this->assertSame( 0, Category::newFromName( 'C' )->getMemberCount() );

		// Add a new category
		$page->updateCategoryCounts( [ 'B' ], [], 0 );

		$this->assertSame( 1, Category::newFromName( 'A' )->getMemberCount() );
		$this->assertSame( 1, Category::newFromName( 'B' )->getMemberCount() );
		$this->assertSame( 0, Category::newFromName( 'C' )->getMemberCount() );

		// Add and remove a category
		$page->updateCategoryCounts( [ 'C' ], [ 'A' ], 0 );

		$this->assertSame( 0, Category::newFromName( 'A' )->getMemberCount() );
		$this->assertSame( 1, Category::newFromName( 'B' )->getMemberCount() );
		$this->assertSame( 1, Category::newFromName( 'C' )->getMemberCount() );
	}

	public static function provideUpdateRedirectOn() {
		yield [ '#REDIRECT [[Foo]]', true, null, true, true, [] ];
		yield [ '#REDIRECT [[Foo]]', true, 'Foo', true, true, [ [ NS_MAIN, 'Foo' ] ] ];
		yield [ 'SomeText', false, null, false, true, [] ];
		yield [ 'SomeText', false, 'Foo', false, true, [ [ NS_MAIN, 'Foo' ] ] ];
	}

	/**
	 * @dataProvider provideUpdateRedirectOn
	 *
	 * @param string $initialText
	 * @param bool $initialRedirectState
	 * @param string|null $redirectTitle
	 * @param bool|null $lastRevIsRedirect
	 * @param bool $expectedSuccess
	 * @param array $expectedRows
	 */
	public function testUpdateRedirectOn(
		$initialText,
		$initialRedirectState,
		$redirectTitle,
		$lastRevIsRedirect,
		$expectedSuccess,
		$expectedRows
	) {
		static $pageCounter = 0;
		$pageCounter++;

		$page = $this->createPage( Title::newFromText( __METHOD__ . $pageCounter ), $initialText );
		$this->assertSame( $initialRedirectState, $page->isRedirect() );

		$redirectTitle = is_string( $redirectTitle )
			? Title::newFromText( $redirectTitle )
			: $redirectTitle;

		$success = $this->getServiceContainer()->getRedirectStore()
			->updateRedirectTarget( $page, $redirectTitle, $lastRevIsRedirect );
		$this->assertSame( $expectedSuccess, $success, 'Success assertion' );
		/**
		 * updateRedirectTarget explicitly updates the redirect table (and not the page table).
		 * Most of core checks the page table for redirect status, so we have to be ugly and
		 * assert a select from the table here.
		 */
		$this->assertRedirectTableCountForPageId( $page->getId(), $expectedRows );
	}

	private function assertRedirectTableCountForPageId( $pageId, $expectedRows ) {
		$this->newSelectQueryBuilder()
			->select( [ 'rd_namespace', 'rd_title' ] )
			->from( 'redirect' )
			->where( [ 'rd_from' => $pageId ] )
			->assertResultSet( $expectedRows );
	}

	public function testInsertRedirectEntry_insertsRedirectEntry() {
		$page = $this->createPage( Title::newFromText( __METHOD__ ), 'A' );
		$this->assertRedirectTableCountForPageId( $page->getId(), [] );

		$targetTitle = Title::newFromText( 'SomeTarget#Frag' );
		$reflectedTitle = TestingAccessWrapper::newFromObject( $targetTitle );
		$reflectedTitle->mInterwiki = 'eninter';
		$this->getServiceContainer()->getRedirectStore()
			->updateRedirectTarget( $page, $targetTitle );

		$this->newSelectQueryBuilder()
			->select( [ 'rd_from', 'rd_namespace', 'rd_title', 'rd_fragment', 'rd_interwiki' ] )
			->from( 'redirect' )
			->where( [ 'rd_from' => $page->getId() ] )
			->assertResultSet( [ [
				strval( $page->getId() ),
				strval( $targetTitle->getNamespace() ),
				strval( $targetTitle->getDBkey() ),
				strval( $targetTitle->getFragment() ),
				strval( $targetTitle->getInterwiki() ),
			] ] );
	}

	public function testInsertRedirectEntry_T278367() {
		$page = $this->createPage( Title::newFromText( __METHOD__ ), 'A' );
		$this->assertRedirectTableCountForPageId( $page->getId(), [] );

		$targetTitle = Title::newFromText( '#Frag' );
		$ok = $this->getServiceContainer()->getRedirectStore()
			->updateRedirectTarget( $page, $targetTitle );

		$this->assertFalse( $ok );
		$this->assertRedirectTableCountForPageId( $page->getId(), [] );
	}

	public function testUpdateRevisionOn_existingPage() {
		$user = $this->getTestSysop()->getUser();
		$page = $this->createPage( __METHOD__, 'StartText' );

		$revisionRecord = new MutableRevisionRecord( $page );
		$revisionRecord->setContent(
			SlotRecord::MAIN,
			new WikitextContent( __METHOD__ . '-text' )
		);
		$revisionRecord->setUser( $user );
		$revisionRecord->setTimestamp( '20170707040404' );
		$revisionRecord->setPageId( $page->getId() );
		$revisionRecord->setId( 9989 );
		$revisionRecord->setSize( strlen( __METHOD__ . '-text' ) );
		$revisionRecord->setMinorEdit( true );
		$revisionRecord->setComment( CommentStoreComment::newUnsavedComment( __METHOD__ ) );

		$result = $page->updateRevisionOn( $this->getDb(), $revisionRecord );
		$this->assertTrue( $result );
		$this->assertSame( 9989, $page->getLatest() );
		$this->assertEquals( $revisionRecord, $page->getRevisionRecord() );
	}

	public function testUpdateRevisionOn_NonExistingPage() {
		$user = $this->getTestSysop()->getUser();
		$page = $this->createPage( __METHOD__, 'StartText' );
		$this->deletePage( $page, '', $user );

		$revisionRecord = new MutableRevisionRecord( $page );
		$revisionRecord->setContent(
			SlotRecord::MAIN,
			new WikitextContent( __METHOD__ . '-text' )
		);
		$revisionRecord->setUser( $user );
		$revisionRecord->setTimestamp( '20170707040404' );
		$revisionRecord->setPageId( $page->getId() );
		$revisionRecord->setId( 9989 );
		$revisionRecord->setSize( strlen( __METHOD__ . '-text' ) );
		$revisionRecord->setMinorEdit( true );
		$revisionRecord->setComment( CommentStoreComment::newUnsavedComment( __METHOD__ ) );

		$result = $page->updateRevisionOn( $this->getDb(), $revisionRecord );
		$this->assertFalse( $result );
	}

	public function testInsertOn() {
		$title = Title::newFromText( __METHOD__ );
		$page = new WikiPage( $title );

		$startTimeStamp = wfTimestampNow();
		$result = $page->insertOn( $this->getDb() );
		$endTimeStamp = wfTimestampNow();

		$this->assertIsInt( $result );
		$this->assertGreaterThan( 0, $result );

		$condition = [ 'page_id' => $result ];

		// Check the default fields have been filled
		$this->newSelectQueryBuilder()
			->select( [
				'page_namespace',
				'page_title',
				'page_is_redirect',
				'page_is_new',
				'page_latest',
				'page_len',
			] )
			->from( 'page' )
			->where( $condition )
			->assertResultSet( [ [
				'0',
				__METHOD__,
				'0',
				'1',
				'0',
				'0',
			] ] );

		// Check the page_random field has been filled
		$pageRandom = $this->getDb()->newSelectQueryBuilder()
			->select( 'page_random' )
			->from( 'page' )
			->where( $condition )
			->fetchField();
		$this->assertTrue( (float)$pageRandom < 1 && (float)$pageRandom > 0 );

		// Assert the touched timestamp in the DB is roughly when we inserted the page
		$pageTouched = $this->getDb()->newSelectQueryBuilder()
			->select( 'page_touched' )
			->from( 'page' )
			->where( $condition )
			->fetchField();
		$this->assertTrue(
			wfTimestamp( TS_UNIX, $startTimeStamp )
			<= wfTimestamp( TS_UNIX, $pageTouched )
		);
		$this->assertTrue(
			wfTimestamp( TS_UNIX, $endTimeStamp )
			>= wfTimestamp( TS_UNIX, $pageTouched )
		);

		// Try inserting the same page again and checking the result is false (no change)
		$result = $page->insertOn( $this->getDb() );
		$this->assertFalse( $result );
	}

	public function testInsertOn_idSpecified() {
		$title = Title::newFromText( __METHOD__ );
		$page = new WikiPage( $title );
		$id = 1478952189;

		$result = $page->insertOn( $this->getDb(), $id );

		$this->assertSame( $id, $result );

		$condition = [ 'page_id' => $result ];

		// Check there is actually a row in the db
		$this->newSelectQueryBuilder()
			->select( 'page_title' )
			->from( 'page' )
			->where( $condition )
			->assertResultSet( [ [ __METHOD__ ] ] );
	}

	public static function provideTestDoUpdateRestrictions_setBasicRestrictions() {
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
			$page = $this->getNonexistingTestPage( Title::newFromText( __METHOD__ . '-nonexist' ) );
		}
		$user = $this->getTestSysop()->getUser();
		$userIdentity = $this->getTestSysop()->getUserIdentity();

		$cascade = false;

		// Expect that the onArticleProtect and onArticleProtectComplete hooks.
		$this->expectHook( 'ArticleProtect' );
		$this->expectHook( 'ArticleProtectComplete' );

		// Expect PageProtectionChangedEvent
		$this->expectDomainEvent(
			PageProtectionChangedEvent::TYPE, 1,
			static function ( PageProtectionChangedEvent $event ) use (
				$page,
				$user,
				$cascade,
				$expectedRestrictions,
				$expectedRestrictionExpiries
			) {
				Assert::assertTrue( $page->isSamePageAs( $event->getPage() ), 'getPage' );
				Assert::assertSame( $page->getId(), $event->getPageId() );

				Assert::assertSame(
					$user->getName(),
					$event->getPerformer()->getName(),
					'getPerformer'
				);
				Assert::assertSame(
					$cascade,
					$event->isCascadingAfter(),
					'isCascadingAfter'
				);

				$defaultRestrictions = array_fill_keys(
					array_keys( $expectedRestrictions ),
					[]
				);

				$defaultExpiry = array_fill_keys(
					array_keys( $expectedRestrictions ),
					'infinity'
				);

				Assert::assertSame( $defaultRestrictions,
					$event->getRestrictionMapBefore(),
					'getRestrictionMapBefore' );

				Assert::assertSame( $expectedRestrictions,
					$event->getRestrictionMapAfter(),
					'getRestrictionMapAfter' );

				Assert::assertSame( $expectedRestrictionExpiries + $defaultExpiry,
					$event->getExpiryAfter(),
					'getExpiryAfter' );
			}
		);

		$status = $page->doUpdateRestrictions( $limit, $expiry, $cascade, 'aReason', $userIdentity, [] );

		$logId = $status->getValue();
		$restrictionStore = $this->getServiceContainer()->getRestrictionStore();
		$allRestrictions = $restrictionStore->getAllRestrictions( $page->getTitle() );

		$this->assertStatusGood( $status );
		$this->assertIsInt( $logId );
		$this->assertSame( $expectedRestrictions, $allRestrictions );
		foreach ( $expectedRestrictionExpiries as $key => $value ) {
			$this->assertSame( $value, $restrictionStore->getRestrictionExpiry( $page->getTitle(), $key ) );
		}

		// Make sure the log entry looks good
		// log_params is not checked here
		$commentQuery = $this->getServiceContainer()->getCommentStore()->getJoin( 'log_comment' );
		$this->newSelectQueryBuilder()
			->select( [
				'log_comment' => $commentQuery['fields']['log_comment_text'],
				'log_actor',
				'log_namespace',
				'log_title',
			] )
			->from( 'logging' )
			->tables( $commentQuery['tables'] )
			->where( [ 'log_id' => $logId ] )
			->joinConds( $commentQuery['joins'] )
			->assertRowValue( [
				'aReason',
				(string)$user->getActorId(),
				(string)$page->getTitle()->getNamespace(),
				$page->getTitle()->getDBkey(),
			] );
	}

	public function testDoUpdateRestrictions_failsOnReadOnly() {
		$page = $this->createPage( __METHOD__, 'ABC' );
		$user = $this->getTestSysop()->getUser();
		$cascade = false;

		// Set read only
		$readOnly = $this->getDummyReadOnlyMode( true );
		$this->setService( 'ReadOnlyMode', $readOnly );

		$status = $page->doUpdateRestrictions( [], [], $cascade, 'aReason', $user, [] );
		$this->assertStatusError( 'readonlytext', $status );
	}

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
		$this->assertStatusGood( $status );
		$this->assertIsInt( $status->getValue() );

		// Expect no hooks and events (flush pending events first)
		$this->runDeferredUpdates();
		$this->expectHook( 'ArticleProtect', 0 );
		$this->expectHook( 'ArticleProtectComplete', 0 );
		$this->expectDomainEvent( PageProtectionChangedEvent::TYPE, 0 );

		$status = $page->doUpdateRestrictions(
			$limit,
			[],
			$cascade,
			'aReason',
			$user,
			[]
		);

		// The second entry should not have a logId as nothing changed
		$this->assertStatusGood( $status );
		$this->assertNull( $status->getValue() );
	}

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
		$this->assertStatusGood( $status );
		$this->assertIsInt( $status->getValue() );
		$this->newSelectQueryBuilder()
			->select( [ 'log_type', 'log_action' ] )
			->from( 'logging' )
			->where( [ 'log_id' => $status->getValue() ] )
			->assertResultSet( [ [ 'protect', 'protect' ] ] );

		// Modify the protection
		$status = $page->doUpdateRestrictions(
			[ 'edit' => 'somethingElse' ],
			[],
			$cascade,
			'aReason',
			$user,
			[]
		);
		$this->assertStatusGood( $status );
		$this->assertIsInt( $status->getValue() );
		$this->newSelectQueryBuilder()
			->select( [ 'log_type', 'log_action' ] )
			->from( 'logging' )
			->where( [ 'log_id' => $status->getValue() ] )
			->assertResultSet( [ [ 'protect', 'modify' ] ] );

		// Remove the protection
		$status = $page->doUpdateRestrictions(
			[],
			[],
			$cascade,
			'aReason',
			$user,
			[]
		);
		$this->assertStatusGood( $status );
		$this->assertIsInt( $status->getValue() );
		$this->newSelectQueryBuilder()
			->select( [ 'log_type', 'log_action' ] )
			->from( 'logging' )
			->where( [ 'log_id' => $status->getValue() ] )
			->assertResultSet( [ [ 'protect', 'unprotect' ] ] );
	}

	public function testNewPageUpdater() {
		$user = $this->getTestUser()->getUser();
		$page = $this->newPage( __METHOD__, __METHOD__ );
		$content = new WikitextContent( 'Hello World' );

		/** @var ContentRenderer $contentRenderer */
		$contentRenderer = $this->getMockBuilder( ContentRenderer::class )
			->onlyMethods( [ 'getParserOutput' ] )
			->disableOriginalConstructor()
			->getMock();
		$contentRenderer->expects( $this->once() )
			->method( 'getParserOutput' )
			->willReturn( new ParserOutput( 'HTML' ) );

		$this->setService( 'ContentRenderer', $contentRenderer );

		$preparedEditBefore = $page->prepareContentForEdit( $content, null, $user );
		$preparedUpdateBefore = $page->getCurrentUpdate();

		// provide context, so the cache can be kept in place
		$slotsUpdate = new revisionSlotsUpdate();
		$slotsUpdate->modifyContent( SlotRecord::MAIN, $content );

		$revision = $page->newPageUpdater( $user, $slotsUpdate )
			->setContent( SlotRecord::MAIN, $content )
			->saveRevision( CommentStoreComment::newUnsavedComment( 'test' ), EDIT_NEW );

		$preparedEditAfter = $page->prepareContentForEdit( $content, $revision, $user );
		$preparedUpdateAfter = $page->getCurrentUpdate();

		$this->assertSame( $revision->getId(), $page->getLatest() );

		// Parsed output must remain cached throughout.
		$this->assertSame(
			$preparedEditBefore->output,
			$preparedEditAfter->output
		);
		$this->assertSame(
			$preparedEditBefore->output,
			$preparedUpdateBefore->getCanonicalParserOutput()
		);
		$this->assertSame(
			$preparedEditBefore->output,
			$preparedUpdateAfter->getCanonicalParserOutput()
		);
	}

	public static function provideDoUpdateRestrictions_updatePropagation() {
		static $counter = 1;
		$name = strtr( __METHOD__, '\\:', '--' ) . $counter++;

		yield 'article' => [ PageIdentityValue::localIdentity( 0, NS_MAIN, $name ) ];
		yield 'message' => [ PageIdentityValue::localIdentity( 0, NS_MEDIAWIKI, $name ) ];
		yield 'script' => [
			PageIdentityValue::localIdentity( 0, NS_USER, "$name/common.js" ),
			new JavaScriptContent( 'console.log("hi")' ),
		];
	}

	/**
	 * @dataProvider provideDoUpdateRestrictions_updatePropagation
	 */
	public function testDoUpdateRestrictions_updatePropagation(
		PageIdentity $title,
		?Content $content = null
	) {
		// Clear some extension hook handlers that may interfere with mock object expectations.
		$this->clearHooks( [
			'PageSaveComplete',
			'RevisionRecordInserted',
			'ArticleProtectComplete',
		] );

		$content ??= new TextContent( 'Lorem Ipsum' );
		$page = $this->createPage( $title, $content );

		// clear the queue
		$this->runJobs();

		// Expect only non-edit recent changes entry
		$this->expectChangeTrackingUpdates( 0, 1, 0, 0, 0 );

		// Expect no resource module purges on protection
		$this->expectResourceLoaderUpdates( 0 );

		// Expect no additional updates, since content didn't change
		$this->expectSearchUpdates( 0 );
		$this->expectLocalizationUpdate( 0 );

		// now apply restrictions
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

		$this->assertStatusGood( $status );
		$this->runDeferredUpdates();
	}

	public function testDoUpdateRestrictions_eventEmission() {
		$page = $this->getExistingTestPage();

		// flush the queue
		$this->runDeferredUpdates();

		$this->expectDomainEvent(
			PageRevisionUpdatedEvent::TYPE, 1,
			static function ( PageRevisionUpdatedEvent $event ) use ( &$calls, $page ) {
				Assert::assertFalse( $event->isCreation(), 'isCreation' );
				Assert::assertTrue( $event->changedLatestRevisionId(), 'changedLatestRevisionId' );
				Assert::assertFalse( $event->isEffectiveContentChange(), 'isEffectiveContentChange' );
				Assert::assertSame( $page->getId(), $event->getPage()->getId() );

				Assert::assertTrue(
					$event->hasCause( PageRevisionUpdatedEvent::CAUSE_PROTECTION_CHANGE ),
					PageRevisionUpdatedEvent::CAUSE_PROTECTION_CHANGE
				);

				Assert::assertTrue( $event->isSilent(), 'isSilent' );
				Assert::assertTrue( $event->isImplicit(), 'isAutomated' );

				Assert::assertTrue(
					$event->getLatestRevisionAfter()->isMinor(),
					'isMinor'
				);
			}
		);

		// Hooks fired by PageUpdater
		$this->expectHook( 'RevisionFromEditComplete', 1 );
		$this->expectHook( 'PageSaveComplete', 1 );

		// now apply restrictions
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

		$this->assertStatusGood( $status );
	}

	public function testGetDerivedDataUpdater() {
		$admin = $this->getTestSysop()->getUser();

		/** @var object $page */
		$page = $this->createPage( __METHOD__, __METHOD__ );
		$page = TestingAccessWrapper::newFromObject( $page );

		$revision = $page->getRevisionRecord();
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
		$timestamp1 = $edit->getOutput()->getCacheTime();
		$timestamp2 = $edit2->getOutput()->getCacheTime();
		$this->assertEquals( $edit, $edit2, $message );
		$this->assertLessThan( 3, abs( $timestamp1 - $timestamp2 ), $message );
	}

	protected function assertPreparedEditNotEquals(
		PreparedEdit $edit, PreparedEdit $edit2, $message = ''
	) {
		$this->assertNotEquals( $edit, $edit2, $message );
	}

	/**
	 * This is just to confirm that WikiPage::updateRevisionOn() updates the
	 * Title and LinkCache with the correct redirect value. Failing to do so
	 * causes subtle test failures in extensions, such as Cognate (T283654)
	 * and Echo (no task, but see code review of I12542fc899).
	 */
	public function testUpdateSetsTitleRedirectCache() {
		// Get a title object without using the title cache
		$title = Title::makeTitleSafe( NS_MAIN, 'A new redirect' );
		$this->assertFalse( $title->isRedirect() );

		$dbw = $this->getDb();
		$store = $this->getServiceContainer()->getRevisionStore();
		$page = $this->newPage( $title );
		$page->insertOn( $dbw );

		$revision = new MutableRevisionRecord( $page );
		$revision->setContent(
			SlotRecord::MAIN,
			new WikitextContent( '#REDIRECT [[Target]]' )
		);
		$revision->setTimestamp( wfTimestampNow() );
		$revision->setComment( CommentStoreComment::newUnsavedComment( '' ) );
		$revision->setUser( $this->getTestUser()->getUser() );

		$revision = $store->insertRevisionOn( $revision, $dbw );

		$page->updateRevisionOn( $dbw, $revision );
		// check the title cache
		$this->assertTrue( $title->isRedirect() );
		// check the link cache with a fresh title
		$title = Title::makeTitleSafe( NS_MAIN, 'A new redirect' );
		$this->assertTrue( $title->isRedirect() );
	}

	public function testGetTitle() {
		$page = $this->createPage( __METHOD__, 'whatever' );

		$title = $page->getTitle();
		$this->assertSame( __METHOD__, $title->getText() );

		$this->assertSame( $page->getId(), $title->getId() );
		$this->assertSame( $page->getNamespace(), $title->getNamespace() );
		$this->assertSame( $page->getDBkey(), $title->getDBkey() );
		$this->assertSame( $page->getWikiId(), $title->getWikiId() );
		$this->assertSame( $page->canExist(), $title->canExist() );
	}

	public function testToPageRecord() {
		$page = $this->createPage( __METHOD__, 'whatever' );
		$record = $page->toPageRecord();

		$this->assertSame( $page->getId(), $record->getId() );
		$this->assertSame( $page->getNamespace(), $record->getNamespace() );
		$this->assertSame( $page->getDBkey(), $record->getDBkey() );
		$this->assertSame( $page->getWikiId(), $record->getWikiId() );
		$this->assertSame( $page->canExist(), $record->canExist() );

		$this->assertSame( $page->getLatest(), $record->getLatest() );
		$this->assertSame( $page->getTouched(), $record->getTouched() );
		$this->assertSame( $page->isNew(), $record->isNew() );
		$this->assertSame( $page->isRedirect(), $record->isRedirect() );
	}

	public function testGetTouched() {
		$page = $this->createPage( __METHOD__, 'whatever' );

		$touched = $this->getDb()->newSelectQueryBuilder()
			->select( 'page_touched' )
			->from( 'page' )
			->where( [ 'page_id' => $page->getId() ] )
			->fetchField();
		$touched = MWTimestamp::convert( TS_MW, $touched );

		// Internal cache of the touched time was set after the page was created
		$this->assertSame( $touched, $page->getTouched() );

		$touched = MWTimestamp::convert( TS_MW, MWTimestamp::convert( TS_UNIX, $touched ) + 100 );
		$page->getTitle()->invalidateCache( $touched );

		// Re-load touched time
		$page = $this->newPage( $page->getTitle() );
		$this->assertSame( $touched, $page->getTouched() );

		// Cause the latest revision to be loaded
		$page->getRevisionRecord();

		// Make sure the internal cache of the touched time was not overwritten
		$this->assertSame( $touched, $page->getTouched() );
	}

}

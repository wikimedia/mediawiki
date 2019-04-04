<?php
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\IncompleteRevisionException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;

/**
 * RevisionDbTestBase contains test cases for the Revision class that have Database interactions.
 *
 * @group Database
 * @group medium
 */
abstract class RevisionDbTestBase extends MediaWikiTestCase {

	/**
	 * @var WikiPage $testPage
	 */
	private $testPage;

	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed = array_merge( $this->tablesUsed,
			[
				'page',
				'revision',
				'comment',
				'ip_changes',
				'text',
				'archive',

				'recentchanges',
				'logging',

				'page_props',
				'pagelinks',
				'categorylinks',
				'langlinks',
				'externallinks',
				'imagelinks',
				'templatelinks',
				'iwlinks'
			]
		);
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
		$this->tablesUsed += $this->getMcrTablesToReset();

		parent::setUp();

		$this->mergeMwGlobalArrayValue(
			'wgExtraNamespaces',
			[
				12312 => 'Dummy',
				12313 => 'Dummy_talk',
			]
		);

		$this->mergeMwGlobalArrayValue(
			'wgNamespaceContentModels',
			[
				12312 => DummyContentForTesting::MODEL_ID,
			]
		);

		$this->mergeMwGlobalArrayValue(
			'wgContentHandlers',
			[
				DummyContentForTesting::MODEL_ID => 'DummyContentHandlerForTesting',
				RevisionTestModifyableContent::MODEL_ID => 'RevisionTestModifyableContentHandler',
			]
		);

		$this->setMwGlobals( [
			'wgMultiContentRevisionSchemaMigrationStage' => $this->getMcrMigrationStage(),
			'wgContentHandlerUseDB' => $this->getContentHandlerUseDB(),
			'wgActorTableSchemaMigrationStage' => SCHEMA_COMPAT_NEW,
		] );

		$this->overrideMwServices();

		if ( !$this->testPage ) {
			/**
			 * We have to create a new page for each subclass as the page creation may result
			 * in different DB fields being filled based on configuration.
			 */
			$this->testPage = $this->createPage( __CLASS__, __CLASS__ );
		}
	}

	/**
	 * @param string $model
	 * @return Title
	 */
	protected function getMockTitle() {
		$mock = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->any() )
			->method( 'getNamespace' )
			->will( $this->returnValue( $this->getDefaultWikitextNS() ) );
		$mock->expects( $this->any() )
			->method( 'getPrefixedText' )
			->will( $this->returnValue( __CLASS__ ) );
		$mock->expects( $this->any() )
			->method( 'getDBkey' )
			->will( $this->returnValue( __CLASS__ ) );
		$mock->expects( $this->any() )
			->method( 'getArticleID' )
			->will( $this->returnValue( 23 ) );

		return $mock;
	}

	abstract protected function getContentHandlerUseDB();

	private function makeRevisionWithProps( $props = null ) {
		if ( $props === null ) {
			$props = [];
		}

		if ( !isset( $props['content'] ) && !isset( $props['text'] ) ) {
			$props['text'] = 'Lorem Ipsum';
		}

		if ( !isset( $props['user_text'] ) ) {
			$user = $this->getTestUser()->getUser();
			$props['user_text'] = $user->getName();
			$props['user'] = $user->getId();
		}

		if ( !isset( $props['user'] ) ) {
			$props['user'] = 0;
		}

		if ( !isset( $props['comment'] ) ) {
			$props['comment'] = 'just a test';
		}

		if ( !isset( $props['page'] ) ) {
			$props['page'] = $this->testPage->getId();
		}

		if ( !isset( $props['content_model'] ) ) {
			$props['content_model'] = CONTENT_MODEL_WIKITEXT;
		}

		$rev = new Revision( $props );

		$dbw = wfGetDB( DB_MASTER );
		$rev->insertOn( $dbw );

		return $rev;
	}

	/**
	 * @param string $titleString
	 * @param string $text
	 * @param string|null $model
	 *
	 * @return WikiPage
	 */
	private function createPage( $titleString, $text, $model = null ) {
		if ( !preg_match( '/:/', $titleString ) &&
			( $model === null || $model === CONTENT_MODEL_WIKITEXT )
		) {
			$ns = $this->getDefaultWikitextNS();
			$titleString = MWNamespace::getCanonicalName( $ns ) . ':' . $titleString;
		}

		$title = Title::newFromText( $titleString );
		$wikipage = new WikiPage( $title );

		// Delete the article if it already exists
		if ( $wikipage->exists() ) {
			$wikipage->doDeleteArticle( "done" );
		}

		$content = ContentHandler::makeContent( $text, $title, $model );
		$wikipage->doEditContent( $content, __METHOD__, EDIT_NEW );

		return $wikipage;
	}

	private function assertRevEquals( Revision $orig, Revision $rev = null ) {
		$this->assertNotNull( $rev, 'missing revision' );

		$this->assertEquals( $orig->getId(), $rev->getId() );
		$this->assertEquals( $orig->getPage(), $rev->getPage() );
		$this->assertEquals( $orig->getTimestamp(), $rev->getTimestamp() );
		$this->assertEquals( $orig->getUser(), $rev->getUser() );
		$this->assertEquals( $orig->getContentModel(), $rev->getContentModel() );
		$this->assertEquals( $orig->getContentFormat(), $rev->getContentFormat() );
		$this->assertEquals( $orig->getSha1(), $rev->getSha1() );
	}

	/**
	 * @covers Revision::getRecentChange
	 */
	public function testGetRecentChange() {
		$rev = $this->testPage->getRevision();
		$recentChange = $rev->getRecentChange();

		// Make sure various attributes look right / the correct entry has been retrieved.
		$this->assertEquals( $rev->getTimestamp(), $recentChange->getAttribute( 'rc_timestamp' ) );
		$this->assertEquals(
			$rev->getTitle()->getNamespace(),
			$recentChange->getAttribute( 'rc_namespace' )
		);
		$this->assertEquals(
			$rev->getTitle()->getDBkey(),
			$recentChange->getAttribute( 'rc_title' )
		);
		$this->assertEquals( $rev->getUser(), $recentChange->getAttribute( 'rc_user' ) );
		$this->assertEquals( $rev->getUserText(), $recentChange->getAttribute( 'rc_user_text' ) );
		$this->assertEquals( $rev->getComment(), $recentChange->getAttribute( 'rc_comment' ) );
		$this->assertEquals( $rev->getPage(), $recentChange->getAttribute( 'rc_cur_id' ) );
		$this->assertEquals( $rev->getId(), $recentChange->getAttribute( 'rc_this_oldid' ) );
	}

	/**
	 * @covers Revision::insertOn
	 */
	public function testInsertOn_success() {
		$parentId = $this->testPage->getLatest();

		// If an ExternalStore is set don't use it.
		$this->setMwGlobals( 'wgDefaultExternalStore', false );

		$rev = new Revision( [
			'page' => $this->testPage->getId(),
			'title' => $this->testPage->getTitle(),
			'text' => 'Revision Text',
			'comment' => 'Revision comment',
		] );

		$revId = $rev->insertOn( wfGetDB( DB_MASTER ) );

		$this->assertInternalType( 'integer', $revId );
		$this->assertSame( $revId, $rev->getId() );

		// getTextId() must be an int!
		$this->assertInternalType( 'integer', $rev->getTextId() );

		$mainSlot = $rev->getRevisionRecord()->getSlot( SlotRecord::MAIN, RevisionRecord::RAW );

		// we currently only support storage in the text table
		$textId = MediaWikiServices::getInstance()
			->getBlobStore()
			->getTextIdFromAddress( $mainSlot->getAddress() );

		$this->assertSelect(
			'text',
			[ 'old_id', 'old_text' ],
			"old_id = $textId",
			[ [ strval( $textId ), 'Revision Text' ] ]
		);
		$this->assertSelect(
			'revision',
			[
				'rev_id',
				'rev_page',
				'rev_minor_edit',
				'rev_deleted',
				'rev_len',
				'rev_parent_id',
				'rev_sha1',
			],
			"rev_id = {$rev->getId()}",
			[ [
				strval( $rev->getId() ),
				strval( $this->testPage->getId() ),
				'0',
				'0',
				'13',
				strval( $parentId ),
				's0ngbdoxagreuf2vjtuxzwdz64n29xm',
			] ]
		);
	}

	public function provideInsertOn_exceptionOnIncomplete() {
		$content = new TextContent( '' );
		$user = User::newFromName( 'Foo' );

		yield 'no parent' => [
			[
				'content' => $content,
				'comment' => 'test',
				'user' => $user,
			],
			IncompleteRevisionException::class,
			"rev_page field must not be 0!"
		];

		yield 'no comment' => [
			[
				'content' => $content,
				'page' => 7,
				'user' => $user,
			],
			IncompleteRevisionException::class,
			"comment must not be NULL!"
		];

		yield 'no content' => [
			[
				'comment' => 'test',
				'page' => 7,
				'user' => $user,
			],
			IncompleteRevisionException::class,
			"Uninitialized field: content_address" // XXX: message may change
		];
	}

	/**
	 * @dataProvider provideInsertOn_exceptionOnIncomplete
	 * @covers Revision::insertOn
	 */
	public function testInsertOn_exceptionOnIncomplete( $array, $expException, $expMessage ) {
		// If an ExternalStore is set don't use it.
		$this->setMwGlobals( 'wgDefaultExternalStore', false );
		$this->setExpectedException( $expException, $expMessage );

		$title = Title::newFromText( 'Nonexistant-' . __METHOD__ );
		$rev = new Revision( $array, 0, $title );

		$rev->insertOn( wfGetDB( DB_MASTER ) );
	}

	/**
	 * @covers Revision::newFromTitle
	 */
	public function testNewFromTitle_withoutId() {
		$latestRevId = $this->testPage->getLatest();

		$rev = Revision::newFromTitle( $this->testPage->getTitle() );

		$this->assertTrue( $this->testPage->getTitle()->equals( $rev->getTitle() ) );
		$this->assertEquals( $latestRevId, $rev->getId() );
	}

	/**
	 * @covers Revision::newFromTitle
	 */
	public function testNewFromTitle_withId() {
		$latestRevId = $this->testPage->getLatest();

		$rev = Revision::newFromTitle( $this->testPage->getTitle(), $latestRevId );

		$this->assertTrue( $this->testPage->getTitle()->equals( $rev->getTitle() ) );
		$this->assertEquals( $latestRevId, $rev->getId() );
	}

	/**
	 * @covers Revision::newFromTitle
	 */
	public function testNewFromTitle_withBadId() {
		$latestRevId = $this->testPage->getLatest();

		$rev = Revision::newFromTitle( $this->testPage->getTitle(), $latestRevId + 1 );

		$this->assertNull( $rev );
	}

	/**
	 * @covers Revision::newFromRow
	 */
	public function testNewFromRow() {
		$orig = $this->makeRevisionWithProps();

		$dbr = wfGetDB( DB_REPLICA );
		$revQuery = Revision::getQueryInfo();
		$res = $dbr->select( $revQuery['tables'], $revQuery['fields'], [ 'rev_id' => $orig->getId() ],
		   __METHOD__, [], $revQuery['joins'] );
		$this->assertTrue( is_object( $res ), 'query failed' );

		$row = $res->fetchObject();
		$res->free();

		$rev = Revision::newFromRow( $row );

		$this->assertRevEquals( $orig, $rev );
	}

	public function provideNewFromArchiveRow() {
		yield [
			function ( $f ) {
				return $f;
			},
		];
		yield [
			function ( $f ) {
				return $f + [ 'ar_namespace', 'ar_title' ];
			},
		];
		yield [
			function ( $f ) {
				unset( $f['ar_text_id'] );
				return $f;
			},
		];
		yield [
			function ( $f ) {
				unset( $f['ar_page_id'] );
				return $f;
			},
		];
		yield [
			function ( $f ) {
				unset( $f['ar_parent_id'] );
				return $f;
			},
		];
		yield [
			function ( $f ) {
				unset( $f['ar_rev_id'] );
				return $f;
			},
		];
		yield [
			function ( $f ) {
				unset( $f['ar_sha1'] );
				return $f;
			},
		];
	}

	/**
	 * @dataProvider provideNewFromArchiveRow
	 * @covers Revision::newFromArchiveRow
	 */
	public function testNewFromArchiveRow( $selectModifier ) {
		$services = MediaWikiServices::getInstance();

		$store = new RevisionStore(
			$services->getDBLoadBalancer(),
			$services->getService( '_SqlBlobStore' ),
			$services->getMainWANObjectCache(),
			$services->getCommentStore(),
			$services->getContentModelStore(),
			$services->getSlotRoleStore(),
			$services->getSlotRoleRegistry(),
			$this->getMcrMigrationStage(),
			$services->getActorMigration()
		);

		$store->setContentHandlerUseDB( $this->getContentHandlerUseDB() );
		$this->setService( 'RevisionStore', $store );

		$page = $this->createPage(
			'RevisionStorageTest_testNewFromArchiveRow',
			'Lorem Ipsum',
			CONTENT_MODEL_WIKITEXT
		);
		$orig = $page->getRevision();
		$page->doDeleteArticle( 'test Revision::newFromArchiveRow' );

		$dbr = wfGetDB( DB_REPLICA );
		$arQuery = Revision::getArchiveQueryInfo();
		$arQuery['fields'] = $selectModifier( $arQuery['fields'] );
		$res = $dbr->select(
			$arQuery['tables'], $arQuery['fields'], [ 'ar_rev_id' => $orig->getId() ],
			__METHOD__, [], $arQuery['joins']
		);
		$this->assertTrue( is_object( $res ), 'query failed' );

		$row = $res->fetchObject();
		$res->free();

		// MCR migration note: $row is now required to contain ar_title and ar_namespace.
		// Alternatively, a Title object can be passed to RevisionStore::newRevisionFromArchiveRow
		$rev = Revision::newFromArchiveRow( $row );

		$this->assertRevEquals( $orig, $rev );
	}

	/**
	 * @covers Revision::newFromArchiveRow
	 */
	public function testNewFromArchiveRowOverrides() {
		$page = $this->createPage(
			'RevisionStorageTest_testNewFromArchiveRow',
			'Lorem Ipsum',
			CONTENT_MODEL_WIKITEXT
		);
		$orig = $page->getRevision();
		$page->doDeleteArticle( 'test Revision::newFromArchiveRow' );

		$dbr = wfGetDB( DB_REPLICA );
		$arQuery = Revision::getArchiveQueryInfo();
		$res = $dbr->select(
			$arQuery['tables'], $arQuery['fields'], [ 'ar_rev_id' => $orig->getId() ],
			__METHOD__, [], $arQuery['joins']
		);
		$this->assertTrue( is_object( $res ), 'query failed' );

		$row = $res->fetchObject();
		$res->free();

		$rev = Revision::newFromArchiveRow( $row, [ 'comment_text' => 'SOMEOVERRIDE' ] );

		$this->assertNotEquals( $orig->getComment(), $rev->getComment() );
		$this->assertEquals( 'SOMEOVERRIDE', $rev->getComment() );
	}

	/**
	 * @covers Revision::newFromId
	 */
	public function testNewFromId() {
		$orig = $this->testPage->getRevision();
		$rev = Revision::newFromId( $orig->getId() );
		$this->assertRevEquals( $orig, $rev );
	}

	/**
	 * @covers Revision::newFromPageId
	 */
	public function testNewFromPageId() {
		$rev = Revision::newFromPageId( $this->testPage->getId() );
		$this->assertRevEquals(
			$this->testPage->getRevision(),
			$rev
		);
	}

	/**
	 * @covers Revision::newFromPageId
	 */
	public function testNewFromPageIdWithLatestId() {
		$rev = Revision::newFromPageId(
			$this->testPage->getId(),
			$this->testPage->getLatest()
		);
		$this->assertRevEquals(
			$this->testPage->getRevision(),
			$rev
		);
	}

	/**
	 * @covers Revision::newFromPageId
	 */
	public function testNewFromPageIdWithNotLatestId() {
		$content = new WikitextContent( __METHOD__ );
		$this->testPage->doEditContent( $content, __METHOD__ );
		$rev = Revision::newFromPageId(
			$this->testPage->getId(),
			$this->testPage->getRevision()->getPrevious()->getId()
		);
		$this->assertRevEquals(
			$this->testPage->getRevision()->getPrevious(),
			$rev
		);
	}

	/**
	 * @covers Revision::getPage
	 */
	public function testGetPage() {
		$page = $this->testPage;

		$orig = $this->makeRevisionWithProps( [ 'page' => $page->getId() ] );
		$rev = Revision::newFromId( $orig->getId() );

		$this->assertEquals( $page->getId(), $rev->getPage() );
	}

	/**
	 * @covers Revision::isCurrent
	 */
	public function testIsCurrent() {
		$rev1 = $this->testPage->getRevision();

		# @todo find out if this should be true
		# $this->assertTrue( $rev1->isCurrent() );

		$rev1x = Revision::newFromId( $rev1->getId() );
		$this->assertTrue( $rev1x->isCurrent() );

		$this->testPage->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );
		$rev2 = $this->testPage->getRevision();

		# @todo find out if this should be true
		# $this->assertTrue( $rev2->isCurrent() );

		$rev1x = Revision::newFromId( $rev1->getId() );
		$this->assertFalse( $rev1x->isCurrent() );

		$rev2x = Revision::newFromId( $rev2->getId() );
		$this->assertTrue( $rev2x->isCurrent() );
	}

	/**
	 * @covers Revision::getPrevious
	 */
	public function testGetPrevious() {
		$oldestRevision = $this->testPage->getOldestRevision();
		$latestRevision = $this->testPage->getLatest();

		$this->assertNull( $oldestRevision->getPrevious() );

		$this->testPage->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );
		$newRevision = $this->testPage->getRevision();

		$this->assertNotNull( $newRevision->getPrevious() );
		$this->assertEquals( $latestRevision, $newRevision->getPrevious()->getId() );
	}

	/**
	 * @covers Revision::getNext
	 */
	public function testGetNext() {
		$rev1 = $this->testPage->getRevision();

		$this->assertNull( $rev1->getNext() );

		$this->testPage->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );
		$rev2 = $this->testPage->getRevision();

		$this->assertNotNull( $rev1->getNext() );
		$this->assertEquals( $rev2->getId(), $rev1->getNext()->getId() );
	}

	/**
	 * @covers Revision::newNullRevision
	 */
	public function testNewNullRevision() {
		$this->testPage->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );
		$orig = $this->testPage->getRevision();

		$dbw = wfGetDB( DB_MASTER );
		$rev = Revision::newNullRevision( $dbw, $this->testPage->getId(), 'a null revision', false );

		$this->assertNotEquals( $orig->getId(), $rev->getId(),
			'new null revision should have a different id from the original revision' );
		$this->assertEquals( $orig->getTextId(), $rev->getTextId(),
			'new null revision should have the same text id as the original revision' );
		$this->assertEquals( $orig->getSha1(), $rev->getSha1(),
			'new null revision should have the same SHA1 as the original revision' );
		$this->assertTrue( $orig->getRevisionRecord()->hasSameContent( $rev->getRevisionRecord() ),
			'new null revision should have the same content as the original revision' );
		$this->assertEquals( __METHOD__, $rev->getContent()->getText() );
	}

	/**
	 * @covers Revision::newNullRevision
	 */
	public function testNewNullRevision_badPage() {
		$dbw = wfGetDB( DB_MASTER );
		$rev = Revision::newNullRevision( $dbw, -1, 'a null revision', false );

		$this->assertNull( $rev );
	}

	/**
	 * @covers Revision::insertOn
	 */
	public function testInsertOn() {
		$ip = '2600:387:ed7:947e:8c16:a1ad:dd34:1dd7';

		$orig = $this->makeRevisionWithProps( [
			'user_text' => $ip
		] );

		// Make sure the revision was copied to ip_changes
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'ip_changes', '*', [ 'ipc_rev_id' => $orig->getId() ] );
		$row = $res->fetchObject();

		$this->assertEquals( IP::toHex( $ip ), $row->ipc_hex );
		$this->assertEquals(
			$orig->getTimestamp(),
			wfTimestamp( TS_MW, $row->ipc_rev_timestamp )
		);
	}

	public static function provideUserWasLastToEdit() {
		yield 'actually the last edit' => [ 3, true ];
		yield 'not the current edit, but still by this user' => [ 2, true ];
		yield 'edit by another user' => [ 1, false ];
		yield 'first edit, by this user, but another user edited in the mean time' => [ 0, false ];
	}

	/**
	 * @covers Revision::userWasLastToEdit
	 * @dataProvider provideUserWasLastToEdit
	 */
	public function testUserWasLastToEdit( $sinceIdx, $expectedLast ) {
		$userA = User::newFromName( "RevisionStorageTest_userA" );
		$userB = User::newFromName( "RevisionStorageTest_userB" );

		if ( $userA->getId() === 0 ) {
			$userA = User::createNew( $userA->getName() );
		}

		if ( $userB->getId() === 0 ) {
			$userB = User::createNew( $userB->getName() );
		}

		$ns = $this->getDefaultWikitextNS();

		$dbw = wfGetDB( DB_MASTER );
		$revisions = [];

		// create revisions -----------------------------
		$page = WikiPage::factory( Title::newFromText(
			'RevisionStorageTest_testUserWasLastToEdit', $ns ) );
		$page->insertOn( $dbw );

		$revisions[0] = new Revision( [
			'page' => $page->getId(),
			// we need the title to determine the page's default content model
			'title' => $page->getTitle(),
			'timestamp' => '20120101000000',
			'user' => $userA->getId(),
			'text' => 'zero',
			'content_model' => CONTENT_MODEL_WIKITEXT,
			'comment' => 'edit zero'
		] );
		$revisions[0]->insertOn( $dbw );

		$revisions[1] = new Revision( [
			'page' => $page->getId(),
			// still need the title, because $page->getId() is 0 (there's no entry in the page table)
			'title' => $page->getTitle(),
			'timestamp' => '20120101000100',
			'user' => $userA->getId(),
			'text' => 'one',
			'content_model' => CONTENT_MODEL_WIKITEXT,
			'comment' => 'edit one'
		] );
		$revisions[1]->insertOn( $dbw );

		$revisions[2] = new Revision( [
			'page' => $page->getId(),
			'title' => $page->getTitle(),
			'timestamp' => '20120101000200',
			'user' => $userB->getId(),
			'text' => 'two',
			'content_model' => CONTENT_MODEL_WIKITEXT,
			'comment' => 'edit two'
		] );
		$revisions[2]->insertOn( $dbw );

		$revisions[3] = new Revision( [
			'page' => $page->getId(),
			'title' => $page->getTitle(),
			'timestamp' => '20120101000300',
			'user' => $userA->getId(),
			'text' => 'three',
			'content_model' => CONTENT_MODEL_WIKITEXT,
			'comment' => 'edit three'
		] );
		$revisions[3]->insertOn( $dbw );

		$revisions[4] = new Revision( [
			'page' => $page->getId(),
			'title' => $page->getTitle(),
			'timestamp' => '20120101000200',
			'user' => $userA->getId(),
			'text' => 'zero',
			'content_model' => CONTENT_MODEL_WIKITEXT,
			'comment' => 'edit four'
		] );
		$revisions[4]->insertOn( $dbw );

		// test it ---------------------------------
		$since = $revisions[$sinceIdx]->getTimestamp();

		$revQuery = Revision::getQueryInfo();
		$allRows = iterator_to_array( $dbw->select(
			$revQuery['tables'],
			[ 'rev_id', 'rev_timestamp', 'rev_user' => $revQuery['fields']['rev_user'] ],
			[
				'rev_page' => $page->getId(),
				//'rev_timestamp > ' . $dbw->addQuotes( $dbw->timestamp( $since ) )
			],
			__METHOD__,
			[ 'ORDER BY' => 'rev_timestamp ASC', 'LIMIT' => 50 ],
			$revQuery['joins']
		) );

		$wasLast = Revision::userWasLastToEdit( $dbw, $page->getId(), $userA->getId(), $since );

		$this->assertEquals( $expectedLast, $wasLast );
	}

	/**
	 * @param string $text
	 * @param string $title
	 * @param string $model
	 * @param string $format
	 *
	 * @return Revision
	 */
	private function newTestRevision( $text, $title = "Test",
		$model = CONTENT_MODEL_WIKITEXT, $format = null
	) {
		if ( is_string( $title ) ) {
			$title = Title::newFromText( $title );
		}

		$content = ContentHandler::makeContent( $text, $title, $model, $format );

		$rev = new Revision(
			[
				'id' => 42,
				'page' => 23,
				'title' => $title,

				'content' => $content,
				'length' => $content->getSize(),
				'comment' => "testing",
				'minor_edit' => false,

				'content_format' => $format,
			]
		);

		return $rev;
	}

	public function provideGetContentModel() {
		// NOTE: we expect the help namespace to always contain wikitext
		return [
			[ 'hello world', 'Help:Hello', null, null, CONTENT_MODEL_WIKITEXT ],
			[ 'hello world', 'User:hello/there.css', null, null, CONTENT_MODEL_CSS ],
			[ serialize( 'hello world' ), 'Dummy:Hello', null, null, DummyContentForTesting::MODEL_ID ],
		];
	}

	/**
	 * @dataProvider provideGetContentModel
	 * @covers Revision::getContentModel
	 */
	public function testGetContentModel( $text, $title, $model, $format, $expectedModel ) {
		$rev = $this->newTestRevision( $text, $title, $model, $format );

		$this->assertEquals( $expectedModel, $rev->getContentModel() );
	}

	public function provideGetContentFormat() {
		// NOTE: we expect the help namespace to always contain wikitext
		return [
			[ 'hello world', 'Help:Hello', null, null, CONTENT_FORMAT_WIKITEXT ],
			[ 'hello world', 'Help:Hello', CONTENT_MODEL_CSS, null, CONTENT_FORMAT_CSS ],
			[ 'hello world', 'User:hello/there.css', null, null, CONTENT_FORMAT_CSS ],
			[ serialize( 'hello world' ), 'Dummy:Hello', null, null, DummyContentForTesting::MODEL_ID ],
		];
	}

	/**
	 * @dataProvider provideGetContentFormat
	 * @covers Revision::getContentFormat
	 */
	public function testGetContentFormat( $text, $title, $model, $format, $expectedFormat ) {
		$rev = $this->newTestRevision( $text, $title, $model, $format );

		$this->assertEquals( $expectedFormat, $rev->getContentFormat() );
	}

	public function provideGetContentHandler() {
		// NOTE: we expect the help namespace to always contain wikitext
		return [
			[ 'hello world', 'Help:Hello', null, null, WikitextContentHandler::class ],
			[ 'hello world', 'User:hello/there.css', null, null, CssContentHandler::class ],
			[ serialize( 'hello world' ), 'Dummy:Hello', null, null, DummyContentHandlerForTesting::class ],
		];
	}

	/**
	 * @dataProvider provideGetContentHandler
	 * @covers Revision::getContentHandler
	 */
	public function testGetContentHandler( $text, $title, $model, $format, $expectedClass ) {
		$rev = $this->newTestRevision( $text, $title, $model, $format );

		$this->assertEquals( $expectedClass, get_class( $rev->getContentHandler() ) );
	}

	public function provideGetContent() {
		// NOTE: we expect the help namespace to always contain wikitext
		return [
			[ 'hello world', 'Help:Hello', null, null, Revision::FOR_PUBLIC, 'hello world' ],
			[
				serialize( 'hello world' ),
				'Hello',
				DummyContentForTesting::MODEL_ID,
				null,
				Revision::FOR_PUBLIC,
				serialize( 'hello world' )
			],
			[
				serialize( 'hello world' ),
				'Dummy:Hello',
				null,
				null,
				Revision::FOR_PUBLIC,
				serialize( 'hello world' )
			],
		];
	}

	/**
	 * @dataProvider provideGetContent
	 * @covers Revision::getContent
	 */
	public function testGetContent( $text, $title, $model, $format,
		$audience, $expectedSerialization
	) {
		$rev = $this->newTestRevision( $text, $title, $model, $format );
		$content = $rev->getContent( $audience );

		$this->assertEquals(
			$expectedSerialization,
			is_null( $content ) ? null : $content->serialize( $format )
		);
	}

	/**
	 * @covers Revision::getContent
	 */
	public function testGetContent_failure() {
		$rev = new Revision( [
			'page' => $this->testPage->getId(),
			'content_model' => $this->testPage->getContentModel(),
			'id' => 123456789, // not in the test DB
		] );

		Wikimedia\suppressWarnings(); // bad text_id will trigger a warning.

		$this->assertNull( $rev->getContent(),
			"getContent() should return null if the revision's text blob could not be loaded." );

		// NOTE: check this twice, once for lazy initialization, and once with the cached value.
		$this->assertNull( $rev->getContent(),
			"getContent() should return null if the revision's text blob could not be loaded." );

		Wikimedia\restoreWarnings();
	}

	public function provideGetSize() {
		return [
			[ "hello world.", CONTENT_MODEL_WIKITEXT, 12 ],
			[ serialize( "hello world." ), DummyContentForTesting::MODEL_ID, 12 ],
		];
	}

	/**
	 * @covers Revision::getSize
	 * @dataProvider provideGetSize
	 */
	public function testGetSize( $text, $model, $expected_size ) {
		$rev = $this->newTestRevision( $text, 'RevisionTest_testGetSize', $model );
		$this->assertEquals( $expected_size, $rev->getSize() );
	}

	public function provideGetSha1() {
		return [
			[ "hello world.", CONTENT_MODEL_WIKITEXT, Revision::base36Sha1( "hello world." ) ],
			[
				serialize( "hello world." ),
				DummyContentForTesting::MODEL_ID,
				Revision::base36Sha1( serialize( "hello world." ) )
			],
		];
	}

	/**
	 * @covers Revision::getSha1
	 * @dataProvider provideGetSha1
	 */
	public function testGetSha1( $text, $model, $expected_hash ) {
		$rev = $this->newTestRevision( $text, 'RevisionTest_testGetSha1', $model );
		$this->assertEquals( $expected_hash, $rev->getSha1() );
	}

	/**
	 * Tests whether $rev->getContent() returns a clone when needed.
	 *
	 * @covers Revision::getContent
	 */
	public function testGetContentClone() {
		$content = new RevisionTestModifyableContent( "foo" );

		$rev = new Revision(
			[
				'id' => 42,
				'page' => 23,
				'title' => Title::newFromText( "testGetContentClone_dummy" ),

				'content' => $content,
				'length' => $content->getSize(),
				'comment' => "testing",
				'minor_edit' => false,
			]
		);

		/** @var RevisionTestModifyableContent $content */
		$content = $rev->getContent( Revision::RAW );
		$content->setText( "bar" );

		/** @var RevisionTestModifyableContent $content2 */
		$content2 = $rev->getContent( Revision::RAW );
		// content is mutable, expect clone
		$this->assertNotSame( $content, $content2, "expected a clone" );
		// clone should contain the original text
		$this->assertEquals( "foo", $content2->getText() );

		$content2->setText( "bla bla" );
		// clones should be independent
		$this->assertEquals( "bar", $content->getText() );
	}

	/**
	 * Tests whether $rev->getContent() returns the same object repeatedly if appropriate.
	 * @covers Revision::getContent
	 */
	public function testGetContentUncloned() {
		$rev = $this->newTestRevision( "hello", "testGetContentUncloned_dummy", CONTENT_MODEL_WIKITEXT );
		$content = $rev->getContent( Revision::RAW );
		$content2 = $rev->getContent( Revision::RAW );

		// for immutable content like wikitext, this should be the same object
		$this->assertSame( $content, $content2 );
	}

	/**
	 * @covers Revision::loadFromId
	 */
	public function testLoadFromId() {
		$rev = $this->testPage->getRevision();
		$this->hideDeprecated( 'Revision::loadFromId' );
		$this->assertRevEquals(
			$rev,
			Revision::loadFromId( wfGetDB( DB_MASTER ), $rev->getId() )
		);
	}

	/**
	 * @covers Revision::loadFromPageId
	 */
	public function testLoadFromPageId() {
		$this->assertRevEquals(
			$this->testPage->getRevision(),
			Revision::loadFromPageId( wfGetDB( DB_MASTER ), $this->testPage->getId() )
		);
	}

	/**
	 * @covers Revision::loadFromPageId
	 */
	public function testLoadFromPageIdWithLatestRevId() {
		$this->assertRevEquals(
			$this->testPage->getRevision(),
			Revision::loadFromPageId(
				wfGetDB( DB_MASTER ),
				$this->testPage->getId(),
				$this->testPage->getLatest()
			)
		);
	}

	/**
	 * @covers Revision::loadFromPageId
	 */
	public function testLoadFromPageIdWithNotLatestRevId() {
		$this->testPage->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );
		$this->assertRevEquals(
			$this->testPage->getRevision()->getPrevious(),
			Revision::loadFromPageId(
				wfGetDB( DB_MASTER ),
				$this->testPage->getId(),
				$this->testPage->getRevision()->getPrevious()->getId()
			)
		);
	}

	/**
	 * @covers Revision::loadFromTitle
	 */
	public function testLoadFromTitle() {
		$this->assertRevEquals(
			$this->testPage->getRevision(),
			Revision::loadFromTitle( wfGetDB( DB_MASTER ), $this->testPage->getTitle() )
		);
	}

	/**
	 * @covers Revision::loadFromTitle
	 */
	public function testLoadFromTitleWithLatestRevId() {
		$this->assertRevEquals(
			$this->testPage->getRevision(),
			Revision::loadFromTitle(
				wfGetDB( DB_MASTER ),
				$this->testPage->getTitle(),
				$this->testPage->getLatest()
			)
		);
	}

	/**
	 * @covers Revision::loadFromTitle
	 */
	public function testLoadFromTitleWithNotLatestRevId() {
		$this->testPage->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );
		$this->assertRevEquals(
			$this->testPage->getRevision()->getPrevious(),
			Revision::loadFromTitle(
				wfGetDB( DB_MASTER ),
				$this->testPage->getTitle(),
				$this->testPage->getRevision()->getPrevious()->getId()
			)
		);
	}

	/**
	 * @covers Revision::loadFromTimestamp()
	 */
	public function testLoadFromTimestamp() {
		$this->assertRevEquals(
			$this->testPage->getRevision(),
			Revision::loadFromTimestamp(
				wfGetDB( DB_MASTER ),
				$this->testPage->getTitle(),
				$this->testPage->getRevision()->getTimestamp()
			)
		);
	}

	/**
	 * @covers Revision::getParentLengths
	 */
	public function testGetParentLengths_noRevIds() {
		$this->assertSame(
			[],
			Revision::getParentLengths(
				wfGetDB( DB_MASTER ),
				[]
			)
		);
	}

	/**
	 * @covers Revision::getParentLengths
	 */
	public function testGetParentLengths_oneRevId() {
		$text = '831jr091jr0921kr21kr0921kjr0921j09rj1';
		$textLength = strlen( $text );

		$this->testPage->doEditContent( new WikitextContent( $text ), __METHOD__ );
		$rev[1] = $this->testPage->getLatest();

		$this->assertSame(
			[ $rev[1] => $textLength ],
			Revision::getParentLengths(
				wfGetDB( DB_MASTER ),
				[ $rev[1] ]
			)
		);
	}

	/**
	 * @covers Revision::getParentLengths
	 */
	public function testGetParentLengths_multipleRevIds() {
		$textOne = '831jr091jr0921kr21kr0921kjr0921j09rj1';
		$textOneLength = strlen( $textOne );
		$textTwo = '831jr091jr092121j09rj1';
		$textTwoLength = strlen( $textTwo );

		$this->testPage->doEditContent( new WikitextContent( $textOne ), __METHOD__ );
		$rev[1] = $this->testPage->getLatest();
		$this->testPage->doEditContent( new WikitextContent( $textTwo ), __METHOD__ );
		$rev[2] = $this->testPage->getLatest();

		$this->assertSame(
			[ $rev[1] => $textOneLength, $rev[2] => $textTwoLength ],
			Revision::getParentLengths(
				wfGetDB( DB_MASTER ),
				[ $rev[1], $rev[2] ]
			)
		);
	}

	/**
	 * @covers Revision::getTitle
	 */
	public function testGetTitle_fromExistingRevision() {
		$this->assertTrue(
			$this->testPage->getTitle()->equals(
				$this->testPage->getRevision()->getTitle()
			)
		);
	}

	/**
	 * @covers Revision::getTitle
	 */
	public function testGetTitle_fromRevisionWhichWillLoadTheTitle() {
		$rev = new Revision( [ 'id' => $this->testPage->getLatest() ] );
		$this->assertTrue(
			$this->testPage->getTitle()->equals(
				$rev->getTitle()
			)
		);
	}

	/**
	 * @covers Revision::isMinor
	 */
	public function testIsMinor_true() {
		// Use a sysop to ensure we can mark edits as minor
		$sysop = $this->getTestSysop()->getUser();

		$this->testPage->doEditContent(
			new WikitextContent( __METHOD__ ),
			__METHOD__,
			EDIT_MINOR,
			false,
			$sysop
		);
		$rev = $this->testPage->getRevision();

		$this->assertSame( true, $rev->isMinor() );
	}

	/**
	 * @covers Revision::isMinor
	 */
	public function testIsMinor_false() {
		$this->testPage->doEditContent(
			new WikitextContent( __METHOD__ ),
			__METHOD__,
			0
		);
		$rev = $this->testPage->getRevision();

		$this->assertSame( false, $rev->isMinor() );
	}

	/**
	 * @covers Revision::getTimestamp
	 */
	public function testGetTimestamp() {
		$testTimestamp = wfTimestampNow();

		$this->testPage->doEditContent(
			new WikitextContent( __METHOD__ ),
			__METHOD__
		);
		$rev = $this->testPage->getRevision();

		$this->assertInternalType( 'string', $rev->getTimestamp() );
		$this->assertTrue( strlen( $rev->getTimestamp() ) == strlen( 'YYYYMMDDHHMMSS' ) );
		$this->assertContains( substr( $testTimestamp, 0, 10 ), $rev->getTimestamp() );
	}

	/**
	 * @covers Revision::getUser
	 * @covers Revision::getUserText
	 */
	public function testGetUserAndText() {
		$sysop = $this->getTestSysop()->getUser();

		$this->testPage->doEditContent(
			new WikitextContent( __METHOD__ ),
			__METHOD__,
			0,
			false,
			$sysop
		);
		$rev = $this->testPage->getRevision();

		$this->assertSame( $sysop->getId(), $rev->getUser() );
		$this->assertSame( $sysop->getName(), $rev->getUserText() );
	}

	/**
	 * @covers Revision::isDeleted
	 */
	public function testIsDeleted_nothingDeleted() {
		$rev = $this->testPage->getRevision();

		$this->assertSame( false, $rev->isDeleted( Revision::DELETED_TEXT ) );
		$this->assertSame( false, $rev->isDeleted( Revision::DELETED_COMMENT ) );
		$this->assertSame( false, $rev->isDeleted( Revision::DELETED_RESTRICTED ) );
		$this->assertSame( false, $rev->isDeleted( Revision::DELETED_USER ) );
	}

	/**
	 * @covers Revision::getVisibility
	 */
	public function testGetVisibility_nothingDeleted() {
		$rev = $this->testPage->getRevision();

		$this->assertSame( 0, $rev->getVisibility() );
	}

	/**
	 * @covers Revision::getComment
	 */
	public function testGetComment_notDeleted() {
		$expectedSummary = 'goatlicious summary';

		$this->testPage->doEditContent(
			new WikitextContent( __METHOD__ ),
			$expectedSummary
		);
		$rev = $this->testPage->getRevision();

		$this->assertSame( $expectedSummary, $rev->getComment() );
	}

	/**
	 * @covers Revision::isUnpatrolled
	 */
	public function testIsUnpatrolled_returnsRecentChangesId() {
		$this->testPage->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );
		$rev = $this->testPage->getRevision();

		$this->assertGreaterThan( 0, $rev->isUnpatrolled() );
		$this->assertSame( $rev->getRecentChange()->getAttribute( 'rc_id' ), $rev->isUnpatrolled() );
	}

	/**
	 * @covers Revision::isUnpatrolled
	 */
	public function testIsUnpatrolled_returnsZeroIfPatrolled() {
		// This assumes that sysops are auto patrolled
		$sysop = $this->getTestSysop()->getUser();
		$this->testPage->doEditContent(
			new WikitextContent( __METHOD__ ),
			__METHOD__,
			0,
			false,
			$sysop
		);
		$rev = $this->testPage->getRevision();

		$this->assertSame( 0, $rev->isUnpatrolled() );
	}

	/**
	 * This is a simple blanket test for all simple content getters and is methods to provide some
	 * coverage before the split of Revision into multiple classes for MCR work.
	 * @covers Revision::getContent
	 * @covers Revision::getSerializedData
	 * @covers Revision::getContentModel
	 * @covers Revision::getContentFormat
	 * @covers Revision::getContentHandler
	 */
	public function testSimpleContentGetters() {
		$expectedText = 'testSimpleContentGetters in Revision. Goats love MCR...';
		$expectedSummary = 'goatlicious testSimpleContentGetters summary';

		$this->testPage->doEditContent(
			new WikitextContent( $expectedText ),
			$expectedSummary
		);
		$rev = $this->testPage->getRevision();

		$this->assertSame( $expectedText, $rev->getContent()->getText() );
		$this->assertSame( $expectedText, $rev->getSerializedData() );
		$this->assertSame( $this->testPage->getContentModel(), $rev->getContentModel() );
		$this->assertSame( $this->testPage->getContent()->getDefaultFormat(), $rev->getContentFormat() );
		$this->assertSame( $this->testPage->getContentHandler(), $rev->getContentHandler() );
	}

	/**
	 * @covers Revision::newKnownCurrent
	 */
	public function testNewKnownCurrent() {
		// Setup the services
		$this->overrideMwServices();
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$this->setService( 'MainWANObjectCache', $cache );
		$db = wfGetDB( DB_MASTER );

		$now = 1553893742;
		$cache->setMockTime( $now );

		// Get a fresh revision to use during testing
		$this->testPage->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );
		$rev = $this->testPage->getRevision();

		// Clear any previous cache for the revision during creation
		$key = $cache->makeGlobalKey(
			RevisionStore::ROW_CACHE_KEY,
			$db->getDomainID(),
			$rev->getPage(),
			$rev->getId()
		);
		$cache->delete( $key, WANObjectCache::HOLDOFF_NONE );
		$this->assertFalse( $cache->get( $key ) );

		++$now;

		// Get the new revision and make sure it is in the cache and correct
		$newRev = Revision::newKnownCurrent( $db, $rev->getPage(), $rev->getId() );
		$this->assertRevEquals( $rev, $newRev );

		$cachedRow = $cache->get( $key );
		$this->assertNotFalse( $cachedRow );
		$this->assertEquals( $rev->getId(), $cachedRow->rev_id );
	}

	public function testNewKnownCurrent_withPageId() {
		$db = wfGetDB( DB_MASTER );

		$this->testPage->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );
		$rev = $this->testPage->getRevision();

		$pageId = $this->testPage->getId();

		$newRev = Revision::newKnownCurrent( $db, $pageId, $rev->getId() );
		$this->assertRevEquals( $rev, $newRev );
	}

	public function testNewKnownCurrent_returnsFalseWhenTitleDoesntExist() {
		$db = wfGetDB( DB_MASTER );

		$this->assertFalse( Revision::newKnownCurrent( $db, 0 ) );
	}

	public function provideUserCanBitfield() {
		yield [ 0, 0, [], null, true ];
		// Bitfields match, user has no permissions
		yield [ Revision::DELETED_TEXT, Revision::DELETED_TEXT, [], null, false ];
		yield [ Revision::DELETED_COMMENT, Revision::DELETED_COMMENT, [], null, false ];
		yield [ Revision::DELETED_USER, Revision::DELETED_USER, [], null, false ];
		yield [ Revision::DELETED_RESTRICTED, Revision::DELETED_RESTRICTED, [], null, false ];
		// Bitfields match, user (admin) does have permissions
		yield [ Revision::DELETED_TEXT, Revision::DELETED_TEXT, [ 'sysop' ], null, true ];
		yield [ Revision::DELETED_COMMENT, Revision::DELETED_COMMENT, [ 'sysop' ], null, true ];
		yield [ Revision::DELETED_USER, Revision::DELETED_USER, [ 'sysop' ], null, true ];
		// Bitfields match, user (admin) does not have permissions
		yield [ Revision::DELETED_RESTRICTED, Revision::DELETED_RESTRICTED, [ 'sysop' ], null, false ];
		// Bitfields match, user (oversight) does have permissions
		yield [ Revision::DELETED_RESTRICTED, Revision::DELETED_RESTRICTED, [ 'oversight' ], null, true ];
		// Check permissions using the title
		yield [
			Revision::DELETED_TEXT,
			Revision::DELETED_TEXT,
			[ 'sysop' ],
			__METHOD__,
			true,
		];
		yield [
			Revision::DELETED_TEXT,
			Revision::DELETED_TEXT,
			[],
			__METHOD__,
			false,
		];
	}

	/**
	 * @dataProvider provideUserCanBitfield
	 * @covers Revision::userCanBitfield
	 */
	public function testUserCanBitfield( $bitField, $field, $userGroups, $title, $expected ) {
		$title = Title::newFromText( $title );

		$this->setMwGlobals(
			'wgGroupPermissions',
			[
				'sysop' => [
					'deletedtext' => true,
					'deletedhistory' => true,
				],
				'oversight' => [
					'viewsuppressed' => true,
					'suppressrevision' => true,
				],
			]
		);
		$user = $this->getTestUser( $userGroups )->getUser();

		$this->assertSame(
			$expected,
			Revision::userCanBitfield( $bitField, $field, $user, $title )
		);

		// Fallback to $wgUser
		$this->setMwGlobals(
			'wgUser',
			$user
		);
		$this->assertSame(
			$expected,
			Revision::userCanBitfield( $bitField, $field, null, $title )
		);
	}

	public function provideUserCan() {
		yield [ 0, 0, [], true ];
		// Bitfields match, user has no permissions
		yield [ Revision::DELETED_TEXT, Revision::DELETED_TEXT, [], false ];
		yield [ Revision::DELETED_COMMENT, Revision::DELETED_COMMENT, [], false ];
		yield [ Revision::DELETED_USER, Revision::DELETED_USER, [], false ];
		yield [ Revision::DELETED_RESTRICTED, Revision::DELETED_RESTRICTED, [], false ];
		// Bitfields match, user (admin) does have permissions
		yield [ Revision::DELETED_TEXT, Revision::DELETED_TEXT, [ 'sysop' ], true ];
		yield [ Revision::DELETED_COMMENT, Revision::DELETED_COMMENT, [ 'sysop' ], true ];
		yield [ Revision::DELETED_USER, Revision::DELETED_USER, [ 'sysop' ], true ];
		// Bitfields match, user (admin) does not have permissions
		yield [ Revision::DELETED_RESTRICTED, Revision::DELETED_RESTRICTED, [ 'sysop' ], false ];
		// Bitfields match, user (oversight) does have permissions
		yield [ Revision::DELETED_RESTRICTED, Revision::DELETED_RESTRICTED, [ 'oversight' ], true ];
	}

	/**
	 * @dataProvider provideUserCan
	 * @covers Revision::userCan
	 */
	public function testUserCan( $bitField, $field, $userGroups, $expected ) {
		$this->setMwGlobals(
			'wgGroupPermissions',
			[
				'sysop' => [
					'deletedtext' => true,
					'deletedhistory' => true,
				],
				'oversight' => [
					'viewsuppressed' => true,
					'suppressrevision' => true,
				],
			]
		);
		$user = $this->getTestUser( $userGroups )->getUser();
		$revision = new Revision( [ 'deleted' => $bitField ], 0, $this->testPage->getTitle() );

		$this->assertSame(
			$expected,
			$revision->userCan( $field, $user )
		);
	}

	public function provideGetTextId() {
		yield [ [], null ];

		$slot = new SlotRecord( (object)[
			'slot_revision_id' => 42,
			'slot_content_id' => 1,
			'content_address' => 'tt:789',
			'model_name' => CONTENT_MODEL_WIKITEXT,
			'role_name' => SlotRecord::MAIN,
			'slot_origin' => 1,
		], new WikitextContent( 'Test' ) );

		$rec = new MutableRevisionRecord( $this->testPage->getTitle() );
		$rec->setId( 42 );
		$rec->setSlot( $slot );

		yield [ $rec, 789 ];
	}

	/**
	 * @dataProvider provideGetTextId
	 * @covers Revision::getTextId()
	 */
	public function testGetTextId( $spec, $expected ) {
		$rev = new Revision( $spec, 0, $this->testPage->getTitle() );
		$this->assertSame( $expected, $rev->getTextId() );
	}

	public function provideGetRevisionText() {
		yield [
			[ 'text' ]
		];
	}

	/**
	 * @dataProvider provideGetRevisionText
	 * @covers Revision::getRevisionText
	 */
	public function testGetRevisionText( array $queryInfoOptions, array $queryInfoExtra = [] ) {
		$rev = $this->testPage->getRevisionRecord();

		$queryInfo = Revision::getQueryInfo( $queryInfoOptions );
		$queryInfo['tables'] = array_merge( $queryInfo['tables'], $queryInfoExtra['tables'] ?? [] );
		$queryInfo['fields'] = array_merge( $queryInfo['fields'], $queryInfoExtra['fields'] ?? [] );
		$queryInfo['joins'] = array_merge( $queryInfo['joins'], $queryInfoExtra['joins'] ?? [] );

		$conds = [ 'rev_id' => $rev->getId() ];
		$row = $this->db->selectRow(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$conds,
			__METHOD__,
			[],
			$queryInfo['joins']
		);

		$expected = $rev->getContent( SlotRecord::MAIN )->serialize();

		$this->hideDeprecated( 'Revision::getRevisionText (MCR without SCHEMA_COMPAT_WRITE_OLD)' );
		$this->assertSame( $expected, Revision::getRevisionText( $row ) );
	}

}

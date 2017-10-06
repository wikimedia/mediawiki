<?php

/**
 * @group ContentHandler
 * @group Database
 *
 * @group medium
 */
class RevisionIntegrationTest extends MediaWikiTestCase {

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

	protected function setUp() {
		global $wgContLang;

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

		MWNamespace::clearCaches();
		// Reset namespace cache
		$wgContLang->resetNamespaces();
		if ( !$this->testPage ) {
			$this->testPage = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		}
	}

	protected function tearDown() {
		global $wgContLang;

		parent::tearDown();

		MWNamespace::clearCaches();
		// Reset namespace cache
		$wgContLang->resetNamespaces();
	}

	private function makeRevisionWithProps( $props = null ) {
		if ( $props === null ) {
			$props = [];
		}

		if ( !isset( $props['content'] ) && !isset( $props['text'] ) ) {
			$props['text'] = 'Lorem Ipsum';
		}

		if ( !isset( $props['comment'] ) ) {
			$props['comment'] = 'just a test';
		}

		if ( !isset( $props['page'] ) ) {
			$props['page'] = $this->testPage->getId();
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
		$this->assertInternalType( 'integer', $rev->getTextId() );
		$this->assertSame( $revId, $rev->getId() );

		$this->assertSelect(
			'text',
			[ 'old_id', 'old_text' ],
			"old_id = {$rev->getTextId()}",
			[ [ strval( $rev->getTextId() ), 'Revision Text' ] ]
		);
		$this->assertSelect(
			'revision',
			[
				'rev_id',
				'rev_page',
				'rev_text_id',
				'rev_user',
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
				strval( $rev->getTextId() ),
				'0',
				'0',
				'0',
				'13',
				strval( $parentId ),
				's0ngbdoxagreuf2vjtuxzwdz64n29xm',
			] ]
		);
	}

	/**
	 * @covers Revision::insertOn
	 */
	public function testInsertOn_exceptionOnNoPage() {
		// If an ExternalStore is set don't use it.
		$this->setMwGlobals( 'wgDefaultExternalStore', false );
		$this->setExpectedException(
			MWException::class,
			"Cannot insert revision: page ID must be nonzero"
		);

		$rev = new Revision( [] );

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
			true,
			function ( $f ) {
				return $f;
			},
		];
		yield [
			false,
			function ( $f ) {
				return $f;
			},
		];
		yield [
			true,
			function ( $f ) {
				return $f + [ 'ar_namespace', 'ar_title' ];
			},
		];
		yield [
			false,
			function ( $f ) {
				return $f + [ 'ar_namespace', 'ar_title' ];
			},
		];
		yield [
			true,
			function ( $f ) {
				unset( $f['ar_text_id'] );
				return $f;
			},
		];
		yield [
			false,
			function ( $f ) {
				unset( $f['ar_text_id'] );
				return $f;
			},
		];
	}

	/**
	 * @dataProvider provideNewFromArchiveRow
	 * @covers Revision::newFromArchiveRow
	 */
	public function testNewFromArchiveRow( $contentHandlerUseDB, $selectModifier ) {
		$this->setMwGlobals( 'wgContentHandlerUseDB', $contentHandlerUseDB );

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

		$rev = Revision::newFromArchiveRow( $row, [ 'comment' => 'SOMEOVERRIDE' ] );

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
		$this->testPage->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );
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
	 * @covers Revision::fetchRevision
	 */
	public function testFetchRevision() {
		// Hidden process cache assertion below
		$this->testPage->getRevision()->getId();

		$this->testPage->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );
		$id = $this->testPage->getRevision()->getId();

		$res = Revision::fetchRevision( $this->testPage->getTitle() );

		# note: order is unspecified
		$rows = [];
		while ( ( $row = $res->fetchObject() ) ) {
			$rows[$row->rev_id] = $row;
		}

		$this->assertEquals( 1, count( $rows ), 'expected exactly one revision' );
		$this->assertArrayHasKey( $id, $rows, 'missing revision with id ' . $id );
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
		$this->assertEquals( __METHOD__, $rev->getContent()->getNativeData() );
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
		$this->assertEquals( $orig->getTimestamp(), $row->ipc_rev_timestamp );
	}

	public static function provideUserWasLastToEdit() {
		yield 'actually the last edit' => [ 3, true ];
		yield 'not the current edit, but still by this user' => [ 2, true ];
		yield 'edit by another user' => [ 1, false ];
		yield 'first edit, by this user, but another user edited in the mean time' => [ 0, false ];
	}

	/**
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
			'summary' => 'edit zero'
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
			'summary' => 'edit one'
		] );
		$revisions[1]->insertOn( $dbw );

		$revisions[2] = new Revision( [
			'page' => $page->getId(),
			'title' => $page->getTitle(),
			'timestamp' => '20120101000200',
			'user' => $userB->getId(),
			'text' => 'two',
			'content_model' => CONTENT_MODEL_WIKITEXT,
			'summary' => 'edit two'
		] );
		$revisions[2]->insertOn( $dbw );

		$revisions[3] = new Revision( [
			'page' => $page->getId(),
			'title' => $page->getTitle(),
			'timestamp' => '20120101000300',
			'user' => $userA->getId(),
			'text' => 'three',
			'content_model' => CONTENT_MODEL_WIKITEXT,
			'summary' => 'edit three'
		] );
		$revisions[3]->insertOn( $dbw );

		$revisions[4] = new Revision( [
			'page' => $page->getId(),
			'title' => $page->getTitle(),
			'timestamp' => '20120101000200',
			'user' => $userA->getId(),
			'text' => 'zero',
			'content_model' => CONTENT_MODEL_WIKITEXT,
			'summary' => 'edit four'
		] );
		$revisions[4]->insertOn( $dbw );

		// test it ---------------------------------
		$since = $revisions[$sinceIdx]->getTimestamp();

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
			[ 'hello world', 'Help:Hello', null, null, 'WikitextContentHandler' ],
			[ 'hello world', 'User:hello/there.css', null, null, 'CssContentHandler' ],
			[ serialize( 'hello world' ), 'Dummy:Hello', null, null, 'DummyContentHandlerForTesting' ],
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
			'text_id' => 123456789, // not in the test DB
		] );

		$this->assertNull( $rev->getContent(),
			"getContent() should return null if the revision's text blob could not be loaded." );

		// NOTE: check this twice, once for lazy initialization, and once with the cached value.
		$this->assertNull( $rev->getContent(),
			"getContent() should return null if the revision's text blob could not be loaded." );
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

}

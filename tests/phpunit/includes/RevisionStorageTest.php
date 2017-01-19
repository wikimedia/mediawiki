<?php

/**
 * Test class for Revision storage.
 *
 * @group ContentHandler
 * @group Database
 * ^--- important, causes temporary tables to be used instead of the real database
 *
 * @group medium
 * ^--- important, causes tests not to fail with timeout
 */
class RevisionStorageTest extends MediaWikiTestCase {
	/**
	 * @var WikiPage $the_page
	 */
	private $the_page;

	function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed = array_merge( $this->tablesUsed,
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
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		parent::setUp();

		$wgExtraNamespaces[12312] = 'Dummy';
		$wgExtraNamespaces[12313] = 'Dummy_talk';

		$wgNamespaceContentModels[12312] = 'DUMMY';
		$wgContentHandlers['DUMMY'] = 'DummyContentHandlerForTesting';

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache
		if ( !$this->the_page ) {
			$this->the_page = $this->createPage(
				'RevisionStorageTest_the_page',
				"just a dummy page",
				CONTENT_MODEL_WIKITEXT
			);
		}

		$this->tablesUsed[] = 'archive';
	}

	protected function tearDown() {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		parent::tearDown();

		unset( $wgExtraNamespaces[12312] );
		unset( $wgExtraNamespaces[12313] );

		unset( $wgNamespaceContentModels[12312] );
		unset( $wgContentHandlers['DUMMY'] );

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache
	}

	protected function makeRevision( $props = null ) {
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
			$props['page'] = $this->the_page->getId();
		}

		$rev = new Revision( $props );

		$dbw = wfGetDB( DB_MASTER );
		$rev->insertOn( $dbw );

		return $rev;
	}

	protected function createPage( $page, $text, $model = null ) {
		if ( is_string( $page ) ) {
			if ( !preg_match( '/:/', $page ) &&
				( $model === null || $model === CONTENT_MODEL_WIKITEXT )
			) {
				$ns = $this->getDefaultWikitextNS();
				$page = MWNamespace::getCanonicalName( $ns ) . ':' . $page;
			}

			$page = Title::newFromText( $page );
		}

		if ( $page instanceof Title ) {
			$page = new WikiPage( $page );
		}

		if ( $page->exists() ) {
			$page->doDeleteArticle( "done" );
		}

		$content = ContentHandler::makeContent( $text, $page->getTitle(), $model );
		$page->doEditContent( $content, "testing", EDIT_NEW );

		return $page;
	}

	protected function assertRevEquals( Revision $orig, Revision $rev = null ) {
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
	 * @covers Revision::__construct
	 */
	public function testConstructFromRow() {
		$orig = $this->makeRevision();

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'revision', '*', [ 'rev_id' => $orig->getId() ] );
		$this->assertTrue( is_object( $res ), 'query failed' );

		$row = $res->fetchObject();
		$res->free();

		$rev = new Revision( $row );

		$this->assertRevEquals( $orig, $rev );
	}

	/**
	 * @covers Revision::newFromRow
	 */
	public function testNewFromRow() {
		$orig = $this->makeRevision();

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'revision', '*', [ 'rev_id' => $orig->getId() ] );
		$this->assertTrue( is_object( $res ), 'query failed' );

		$row = $res->fetchObject();
		$res->free();

		$rev = Revision::newFromRow( $row );

		$this->assertRevEquals( $orig, $rev );
	}

	/**
	 * @covers Revision::newFromArchiveRow
	 */
	public function testNewFromArchiveRow() {
		$page = $this->createPage(
			'RevisionStorageTest_testNewFromArchiveRow',
			'Lorem Ipsum',
			CONTENT_MODEL_WIKITEXT
		);
		$orig = $page->getRevision();
		$page->doDeleteArticle( 'test Revision::newFromArchiveRow' );

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'archive', '*', [ 'ar_rev_id' => $orig->getId() ] );
		$this->assertTrue( is_object( $res ), 'query failed' );

		$row = $res->fetchObject();
		$res->free();

		$rev = Revision::newFromArchiveRow( $row );

		$this->assertRevEquals( $orig, $rev );
	}

	/**
	 * @covers Revision::newFromId
	 */
	public function testNewFromId() {
		$orig = $this->makeRevision();

		$rev = Revision::newFromId( $orig->getId() );

		$this->assertRevEquals( $orig, $rev );
	}

	/**
	 * @covers Revision::fetchRevision
	 */
	public function testFetchRevision() {
		$page = $this->createPage(
			'RevisionStorageTest_testFetchRevision',
			'one',
			CONTENT_MODEL_WIKITEXT
		);

		// Hidden process cache assertion below
		$page->getRevision()->getId();

		$page->doEditContent( new WikitextContent( 'two' ), 'second rev' );
		$id = $page->getRevision()->getId();

		$res = Revision::fetchRevision( $page->getTitle() );

		# note: order is unspecified
		$rows = [];
		while ( ( $row = $res->fetchObject() ) ) {
			$rows[$row->rev_id] = $row;
		}

		$this->assertEquals( 1, count( $rows ), 'expected exactly one revision' );
		$this->assertArrayHasKey( $id, $rows, 'missing revision with id ' . $id );
	}

	/**
	 * @covers Revision::selectFields
	 */
	public function testSelectFields() {
		global $wgContentHandlerUseDB;

		$fields = Revision::selectFields();

		$this->assertTrue( in_array( 'rev_id', $fields ), 'missing rev_id in list of fields' );
		$this->assertTrue( in_array( 'rev_page', $fields ), 'missing rev_page in list of fields' );
		$this->assertTrue(
			in_array( 'rev_timestamp', $fields ),
			'missing rev_timestamp in list of fields'
		);
		$this->assertTrue( in_array( 'rev_user', $fields ), 'missing rev_user in list of fields' );

		if ( $wgContentHandlerUseDB ) {
			$this->assertTrue( in_array( 'rev_content_model', $fields ),
				'missing rev_content_model in list of fields' );
			$this->assertTrue( in_array( 'rev_content_format', $fields ),
				'missing rev_content_format in list of fields' );
		}
	}

	/**
	 * @covers Revision::getPage
	 */
	public function testGetPage() {
		$page = $this->the_page;

		$orig = $this->makeRevision( [ 'page' => $page->getId() ] );
		$rev = Revision::newFromId( $orig->getId() );

		$this->assertEquals( $page->getId(), $rev->getPage() );
	}

	/**
	 * @covers Revision::getContent
	 */
	public function testGetContent_failure() {
		$rev = new Revision( [
			'page' => $this->the_page->getId(),
			'content_model' => $this->the_page->getContentModel(),
			'text_id' => 123456789, // not in the test DB
		] );

		$this->assertNull( $rev->getContent(),
			"getContent() should return null if the revision's text blob could not be loaded." );

		// NOTE: check this twice, once for lazy initialization, and once with the cached value.
		$this->assertNull( $rev->getContent(),
			"getContent() should return null if the revision's text blob could not be loaded." );
	}

	/**
	 * @covers Revision::getContent
	 */
	public function testGetContent() {
		$orig = $this->makeRevision( [ 'text' => 'hello hello.' ] );
		$rev = Revision::newFromId( $orig->getId() );

		$this->assertEquals( 'hello hello.', $rev->getContent()->getNativeData() );
	}

	/**
	 * @covers Revision::getContentModel
	 */
	public function testGetContentModel() {
		global $wgContentHandlerUseDB;

		if ( !$wgContentHandlerUseDB ) {
			$this->markTestSkipped( '$wgContentHandlerUseDB is disabled' );
		}

		$orig = $this->makeRevision( [ 'text' => 'hello hello.',
			'content_model' => CONTENT_MODEL_JAVASCRIPT ] );
		$rev = Revision::newFromId( $orig->getId() );

		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $rev->getContentModel() );
	}

	/**
	 * @covers Revision::getContentFormat
	 */
	public function testGetContentFormat() {
		global $wgContentHandlerUseDB;

		if ( !$wgContentHandlerUseDB ) {
			$this->markTestSkipped( '$wgContentHandlerUseDB is disabled' );
		}

		$orig = $this->makeRevision( [
			'text' => 'hello hello.',
			'content_model' => CONTENT_MODEL_JAVASCRIPT,
			'content_format' => CONTENT_FORMAT_JAVASCRIPT
		] );
		$rev = Revision::newFromId( $orig->getId() );

		$this->assertEquals( CONTENT_FORMAT_JAVASCRIPT, $rev->getContentFormat() );
	}

	/**
	 * @covers Revision::isCurrent
	 */
	public function testIsCurrent() {
		$page = $this->createPage(
			'RevisionStorageTest_testIsCurrent',
			'Lorem Ipsum',
			CONTENT_MODEL_WIKITEXT
		);
		$rev1 = $page->getRevision();

		# @todo find out if this should be true
		# $this->assertTrue( $rev1->isCurrent() );

		$rev1x = Revision::newFromId( $rev1->getId() );
		$this->assertTrue( $rev1x->isCurrent() );

		$page->doEditContent(
			ContentHandler::makeContent( 'Bla bla', $page->getTitle(), CONTENT_MODEL_WIKITEXT ),
			'second rev'
		);
		$rev2 = $page->getRevision();

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
		$page = $this->createPage(
			'RevisionStorageTest_testGetPrevious',
			'Lorem Ipsum testGetPrevious',
			CONTENT_MODEL_WIKITEXT
		);
		$rev1 = $page->getRevision();

		$this->assertNull( $rev1->getPrevious() );

		$page->doEditContent(
			ContentHandler::makeContent( 'Bla bla', $page->getTitle(), CONTENT_MODEL_WIKITEXT ),
			'second rev testGetPrevious' );
		$rev2 = $page->getRevision();

		$this->assertNotNull( $rev2->getPrevious() );
		$this->assertEquals( $rev1->getId(), $rev2->getPrevious()->getId() );
	}

	/**
	 * @covers Revision::getNext
	 */
	public function testGetNext() {
		$page = $this->createPage(
			'RevisionStorageTest_testGetNext',
			'Lorem Ipsum testGetNext',
			CONTENT_MODEL_WIKITEXT
		);
		$rev1 = $page->getRevision();

		$this->assertNull( $rev1->getNext() );

		$page->doEditContent(
			ContentHandler::makeContent( 'Bla bla', $page->getTitle(), CONTENT_MODEL_WIKITEXT ),
			'second rev testGetNext'
		);
		$rev2 = $page->getRevision();

		$this->assertNotNull( $rev1->getNext() );
		$this->assertEquals( $rev2->getId(), $rev1->getNext()->getId() );
	}

	/**
	 * @covers Revision::newNullRevision
	 */
	public function testNewNullRevision() {
		$page = $this->createPage(
			'RevisionStorageTest_testNewNullRevision',
			'some testing text',
			CONTENT_MODEL_WIKITEXT
		);
		$orig = $page->getRevision();

		$dbw = wfGetDB( DB_MASTER );
		$rev = Revision::newNullRevision( $dbw, $page->getId(), 'a null revision', false );

		$this->assertNotEquals( $orig->getId(), $rev->getId(),
			'new null revision shold have a different id from the original revision' );
		$this->assertEquals( $orig->getTextId(), $rev->getTextId(),
			'new null revision shold have the same text id as the original revision' );
		$this->assertEquals( 'some testing text', $rev->getContent()->getNativeData() );
	}

	public static function provideUserWasLastToEdit() {
		return [
			[ # 0
				3, true, # actually the last edit
			],
			[ # 1
				2, true, # not the current edit, but still by this user
			],
			[ # 2
				1, false, # edit by another user
			],
			[ # 3
				0, false, # first edit, by this user, but another user edited in the mean time
			],
		];
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

		# zero
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

		# one
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

		# two
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

		# three
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

		# four
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
}

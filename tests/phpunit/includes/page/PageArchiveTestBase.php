<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;

/**
 * Base class for tests of PageArchive against different database schemas.
 */
abstract class PageArchiveTestBase extends MediaWikiTestCase {

	/**
	 * @var int
	 */
	protected $pageId;

	/**
	 * @var PageArchive $archivedPage
	 */
	protected $archivedPage;

	/**
	 * A logged out user who edited the page before it was archived.
	 * @var string $ipEditor
	 */
	protected $ipEditor;

	/**
	 * Revision of the first (initial) edit
	 * @var RevisionRecord
	 */
	protected $firstRev;

	/**
	 * Revision of the IP edit (the second edit)
	 * @var RevisionRecord
	 */
	protected $ipRev;

	function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[
				'page',
				'revision',
				'revision_comment_temp',
				'ip_changes',
				'text',
				'archive',
				'recentchanges',
				'logging',
				'page_props',
				'comment',
			]
		);
	}

	protected function addCoreDBData() {
		// Blank out to avoid failures when schema overrides imposed by subclasses
		// affect revision storage.
	}

	/**
	 * @return int
	 */
	abstract protected function getMcrMigrationStage();

	/**
	 * @return string[]
	 */
	abstract protected function getMcrTablesToReset();

	/**
	 * @return bool
	 */
	protected function getContentHandlerUseDB() {
		return true;
	}

	protected function setUp() {
		parent::setUp();

		$this->tablesUsed += $this->getMcrTablesToReset();

		$this->setMwGlobals( [
			'wgContentHandlerUseDB' => $this->getContentHandlerUseDB(),
			'wgMultiContentRevisionSchemaMigrationStage' => $this->getMcrMigrationStage(),
		] );

		// First create our dummy page
		$page = Title::newFromText( 'PageArchiveTest_thePage' );
		$page = new WikiPage( $page );
		$content = ContentHandler::makeContent(
			'testing',
			$page->getTitle(),
			CONTENT_MODEL_WIKITEXT
		);

		$user = $this->getTestUser()->getUser();
		$page->doEditContent( $content, 'testing', EDIT_NEW, false, $user );

		$this->pageId = $page->getId();
		$this->firstRev = $page->getRevision()->getRevisionRecord();

		// Insert IP revision
		$this->ipEditor = '2001:db8::1';

		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();

		$ipTimestamp = wfTimestamp(
			TS_MW,
			wfTimestamp( TS_UNIX, $this->firstRev->getTimestamp() ) + 1
		);

		$rev = $revisionStore->newMutableRevisionFromArray( [
			'text' => 'Lorem Ipsum',
			'comment' => 'just a test',
			'page' => $page->getId(),
			'user_text' => $this->ipEditor,
			'timestamp' => $ipTimestamp,
		] );

		$dbw = wfGetDB( DB_MASTER );
		$this->ipRev = $revisionStore->insertRevisionOn( $rev, $dbw );

		// Delete the page
		$page->doDeleteArticleReal( 'Just a test deletion' );

		$this->archivedPage = new PageArchive( $page->getTitle() );
	}

	/**
	 * @covers PageArchive::undelete
	 * @covers PageArchive::undeleteRevisions
	 */
	public function testUndeleteRevisions() {
		// TODO: MCR: Test undeletion with multiple slots. Check that slots remain untouched.

		// First make sure old revisions are archived
		$dbr = wfGetDB( DB_REPLICA );
		$arQuery = Revision::getArchiveQueryInfo();
		$row = $dbr->selectRow(
			$arQuery['tables'],
			$arQuery['fields'],
			[ 'ar_rev_id' => $this->ipRev->getId() ],
			__METHOD__,
			[],
			$arQuery['joins']
		);
		$this->assertEquals( $this->ipEditor, $row->ar_user_text );

		// Should not be in revision
		$row = $dbr->selectRow( 'revision', '1', [ 'rev_id' => $this->ipRev->getId() ] );
		$this->assertFalse( $row );

		// Should not be in ip_changes
		$row = $dbr->selectRow( 'ip_changes', '1', [ 'ipc_rev_id' => $this->ipRev->getId() ] );
		$this->assertFalse( $row );

		// Restore the page
		$this->archivedPage->undelete( [] );

		// Should be back in revision
		$revQuery = Revision::getQueryInfo();
		$row = $dbr->selectRow(
			$revQuery['tables'],
			$revQuery['fields'],
			[ 'rev_id' => $this->ipRev->getId() ],
			__METHOD__,
			[],
			$revQuery['joins']
		);
		$this->assertNotFalse( $row, 'row exists in revision table' );
		$this->assertEquals( $this->ipEditor, $row->rev_user_text );

		// Should be back in ip_changes
		$row = $dbr->selectRow( 'ip_changes', [ 'ipc_hex' ], [ 'ipc_rev_id' => $this->ipRev->getId() ] );
		$this->assertNotFalse( $row, 'row exists in ip_changes table' );
		$this->assertEquals( IP::toHex( $this->ipEditor ), $row->ipc_hex );
	}

	abstract protected function getExpectedArchiveRows();

	/**
	 * @covers PageArchive::listRevisions
	 */
	public function testListRevisions() {
		$revisions = $this->archivedPage->listRevisions();
		$this->assertEquals( 2, $revisions->numRows() );

		// Get the rows as arrays
		$row0 = (array)$revisions->current();
		$row1 = (array)$revisions->next();

		$expectedRows = $this->getExpectedArchiveRows();

		$this->assertEquals(
			$expectedRows[0],
			$row0
		);
		$this->assertEquals(
			$expectedRows[1],
			$row1
		);
	}

	/**
	 * @covers PageArchive::listPagesBySearch
	 */
	public function testListPagesBySearch() {
		$pages = PageArchive::listPagesBySearch( 'PageArchiveTest_thePage' );
		$this->assertSame( 1, $pages->numRows() );

		$page = (array)$pages->current();

		$this->assertSame(
			[
				'ar_namespace' => '0',
				'ar_title' => 'PageArchiveTest_thePage',
				'count' => '2',
			],
			$page
		);
	}

	/**
	 * @covers PageArchive::listPagesBySearch
	 */
	public function testListPagesByPrefix() {
		$pages = PageArchive::listPagesByPrefix( 'PageArchiveTest' );
		$this->assertSame( 1, $pages->numRows() );

		$page = (array)$pages->current();

		$this->assertSame(
			[
				'ar_namespace' => '0',
				'ar_title' => 'PageArchiveTest_thePage',
				'count' => '2',
			],
			$page
		);
	}

	public function provideGetTextFromRowThrowsInvalidArgumentException() {
		yield 'missing ar_text_id field' => [ [] ];
		yield 'ar_text_id is null' => [ [ 'ar_text_id' => null ] ];
		yield 'ar_text_id is zero' => [ [ 'ar_text_id' => 0 ] ];
		yield 'ar_text_id is "0"' => [ [ 'ar_text_id' => '0' ] ];
	}

	/**
	 * @covers PageArchive::getLastRevisionId
	 */
	public function testGetLastRevisionId() {
		$id = $this->archivedPage->getLastRevisionId();
		$this->assertSame( $this->ipRev->getId(), $id );
	}

	/**
	 * @covers PageArchive::isDeleted
	 */
	public function testIsDeleted() {
		$this->assertTrue( $this->archivedPage->isDeleted() );
	}

	/**
	 * @covers PageArchive::getRevision
	 */
	public function testGetRevision() {
		$rev = $this->archivedPage->getRevision( $this->ipRev->getTimestamp() );
		$this->assertNotNull( $rev );
		$this->assertSame( $this->pageId, $rev->getPage() );

		$rev = $this->archivedPage->getRevision( '22991212115555' );
		$this->assertNull( $rev );
	}

	/**
	 * @covers PageArchive::getRevision
	 */
	public function testGetArchivedRevision() {
		$rev = $this->archivedPage->getArchivedRevision( $this->ipRev->getId() );
		$this->assertNotNull( $rev );
		$this->assertSame( $this->ipRev->getTimestamp(), $rev->getTimestamp() );
		$this->assertSame( $this->pageId, $rev->getPage() );

		$rev = $this->archivedPage->getArchivedRevision( 632546 );
		$this->assertNull( $rev );
	}

	/**
	 * @covers PageArchive::getPreviousRevision
	 */
	public function testGetPreviousRevision() {
		$rev = $this->archivedPage->getPreviousRevision( $this->ipRev->getTimestamp() );
		$this->assertNotNull( $rev );
		$this->assertSame( $this->firstRev->getId(), $rev->getId() );

		$rev = $this->archivedPage->getPreviousRevision( $this->firstRev->getTimestamp() );
		$this->assertNull( $rev );

		// Re-create our dummy page
		$title = Title::newFromText( 'PageArchiveTest_thePage' );
		$page = new WikiPage( $title );
		$content = ContentHandler::makeContent(
			'testing again',
			$page->getTitle(),
			CONTENT_MODEL_WIKITEXT
		);

		$user = $this->getTestUser()->getUser();
		$status = $page->doEditContent( $content, 'testing', EDIT_NEW, false, $user );

		/** @var Revision $newRev */
		$newRev = $status->value['revision'];

		// force the revision timestamp
		$newTimestamp = wfTimestamp(
			TS_MW,
			wfTimestamp( TS_UNIX, $this->ipRev->getTimestamp() ) + 1
		);

		$this->db->update(
			'revision',
			[ 'rev_timestamp' => $this->db->timestamp( $newTimestamp ) ],
			[ 'rev_id' => $newRev->getId() ]
		);

		// check that we don't get the existing revision too soon.
		$rev = $this->archivedPage->getPreviousRevision( $newTimestamp );
		$this->assertNotNull( $rev );
		$this->assertSame( $this->ipRev->getId(), $rev->getId() );

		// check that we do get the existing revision when appropriate.
		$afterNewTimestamp = wfTimestamp(
			TS_MW,
			wfTimestamp( TS_UNIX, $newTimestamp ) + 1
		);

		$rev = $this->archivedPage->getPreviousRevision( $afterNewTimestamp );
		$this->assertNotNull( $rev );
		$this->assertSame( $newRev->getId(), $rev->getId() );
	}

}

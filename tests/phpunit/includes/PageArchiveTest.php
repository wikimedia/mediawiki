<?php

/**
 * Test class for page archiving.
 *
 * @group ContentHandler
 * @group Database
 * ^--- important, causes temporary tables to be used instead of the real database
 *
 * @group medium
 * ^--- important, causes tests not to fail with timeout
 */
class PageArchiveTest extends MediaWikiTestCase {

	/**
	 * @var PageArchive $archivedPage
	 */
	private $archivedPage;

	/**
	 * A logged out user who edited the page before it was archived.
	 * @var string $ipEditor
	 */
	private $ipEditor;

	/**
	 * Revision ID of the IP edit
	 * @var int $ipRevId
	 */
	private $ipRevId;

	function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[
				'page',
				'revision',
				'ip_changes',
				'text',
				'archive',
				'recentchanges',
				'logging',
				'page_props',
			]
		);
	}

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', MIGRATION_OLD );
		$this->setMwGlobals( 'wgActorTableSchemaMigrationStage', MIGRATION_OLD );
		$this->overrideMwServices();

		// First create our dummy page
		$page = Title::newFromText( 'PageArchiveTest_thePage' );
		$page = new WikiPage( $page );
		$content = ContentHandler::makeContent(
			'testing',
			$page->getTitle(),
			CONTENT_MODEL_WIKITEXT
		);
		$page->doEditContent( $content, 'testing', EDIT_NEW );

		// Insert IP revision
		$this->ipEditor = '2600:387:ed7:947e:8c16:a1ad:dd34:1dd7';
		$rev = new Revision( [
			'text' => 'Lorem Ipsum',
			'comment' => 'just a test',
			'page' => $page->getId(),
			'user_text' => $this->ipEditor,
		] );
		$dbw = wfGetDB( DB_MASTER );
		$this->ipRevId = $rev->insertOn( $dbw );

		// Delete the page
		$page->doDeleteArticleReal( 'Just a test deletion' );

		$this->archivedPage = new PageArchive( $page->getTitle() );
	}

	/**
	 * @covers PageArchive::undelete
	 * @covers PageArchive::undeleteRevisions
	 */
	public function testUndeleteRevisions() {
		// First make sure old revisions are archived
		$dbr = wfGetDB( DB_REPLICA );
		$arQuery = Revision::getArchiveQueryInfo();
		$res = $dbr->select(
			$arQuery['tables'],
			$arQuery['fields'],
			[ 'ar_rev_id' => $this->ipRevId ],
			__METHOD__,
			[],
			$arQuery['joins']
		);
		$row = $res->fetchObject();
		$this->assertEquals( $this->ipEditor, $row->ar_user_text );

		// Should not be in revision
		$res = $dbr->select( 'revision', '1', [ 'rev_id' => $this->ipRevId ] );
		$this->assertFalse( $res->fetchObject() );

		// Should not be in ip_changes
		$res = $dbr->select( 'ip_changes', '1', [ 'ipc_rev_id' => $this->ipRevId ] );
		$this->assertFalse( $res->fetchObject() );

		// Restore the page
		$this->archivedPage->undelete( [] );

		// Should be back in revision
		$revQuery = Revision::getQueryInfo();
		$res = $dbr->select(
			$revQuery['tables'],
			$revQuery['fields'],
			[ 'rev_id' => $this->ipRevId ],
			__METHOD__,
			[],
			$revQuery['joins']
		);
		$row = $res->fetchObject();
		$this->assertEquals( $this->ipEditor, $row->rev_user_text );

		// Should be back in ip_changes
		$res = $dbr->select( 'ip_changes', [ 'ipc_hex' ], [ 'ipc_rev_id' => $this->ipRevId ] );
		$row = $res->fetchObject();
		$this->assertEquals( IP::toHex( $this->ipEditor ), $row->ipc_hex );
	}

	/**
	 * @covers PageArchive::listRevisions
	 */
	public function testListRevisions() {
		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', MIGRATION_OLD );
		$this->overrideMwServices();

		$revisions = $this->archivedPage->listRevisions();
		$this->assertEquals( 2, $revisions->numRows() );

		// Get the rows as arrays
		$row1 = (array)$revisions->current();
		$row2 = (array)$revisions->next();
		// Unset the timestamps (we assume they will be right...
		$this->assertInternalType( 'string', $row1['ar_timestamp'] );
		$this->assertInternalType( 'string', $row2['ar_timestamp'] );
		unset( $row1['ar_timestamp'] );
		unset( $row2['ar_timestamp'] );

		$this->assertEquals(
			[
				'ar_minor_edit' => '0',
				'ar_user' => '0',
				'ar_user_text' => '2600:387:ed7:947e:8c16:a1ad:dd34:1dd7',
				'ar_actor' => null,
				'ar_len' => '11',
				'ar_deleted' => '0',
				'ar_rev_id' => '3',
				'ar_sha1' => '0qdrpxl537ivfnx4gcpnzz0285yxryy',
				'ar_page_id' => '2',
				'ar_comment_text' => 'just a test',
				'ar_comment_data' => null,
				'ar_comment_cid' => null,
				'ar_content_format' => null,
				'ar_content_model' => null,
				'ts_tags' => null,
				'ar_id' => '2',
				'ar_namespace' => '0',
				'ar_title' => 'PageArchiveTest_thePage',
				'ar_text_id' => '3',
				'ar_parent_id' => '2',
			],
			$row1
		);
		$this->assertEquals(
			[
				'ar_minor_edit' => '0',
				'ar_user' => '0',
				'ar_user_text' => '127.0.0.1',
				'ar_actor' => null,
				'ar_len' => '7',
				'ar_deleted' => '0',
				'ar_rev_id' => '2',
				'ar_sha1' => 'pr0s8e18148pxhgjfa0gjrvpy8fiyxc',
				'ar_page_id' => '2',
				'ar_comment_text' => 'testing',
				'ar_comment_data' => null,
				'ar_comment_cid' => null,
				'ar_content_format' => null,
				'ar_content_model' => null,
				'ts_tags' => null,
				'ar_id' => '1',
				'ar_namespace' => '0',
				'ar_title' => 'PageArchiveTest_thePage',
				'ar_text_id' => '2',
				'ar_parent_id' => '0',
			],
			$row2
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

	/**
	 * @covers PageArchive::getTextFromRow
	 */
	public function testGetTextFromRow() {
		$row = (object)[ 'ar_text_id' => 2 ];
		$text = $this->archivedPage->getTextFromRow( $row );
		$this->assertSame( 'testing', $text );
	}

	/**
	 * @covers PageArchive::getLastRevisionText
	 */
	public function testGetLastRevisionText() {
		$text = $this->archivedPage->getLastRevisionText();
		$this->assertSame( 'Lorem Ipsum', $text );
	}

	/**
	 * @covers PageArchive::isDeleted
	 */
	public function testIsDeleted() {
		$this->assertTrue( $this->archivedPage->isDeleted() );
	}
}

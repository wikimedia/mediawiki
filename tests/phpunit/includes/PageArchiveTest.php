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
	 * @var WikiPage $archivedPage
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
	 */
	public function testUndeleteRevisions() {
		// First make sure old revisions are archived
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'archive', '*', [ 'ar_rev_id' => $this->ipRevId ] );
		$row = $res->fetchObject();
		$this->assertEquals( $this->ipEditor, $row->ar_user_text );

		// Should not be in revision
		$res = $dbr->select( 'revision', '*', [ 'rev_id' => $this->ipRevId ] );
		$this->assertFalse( $res->fetchObject() );

		// Should not be in ip_changes
		$res = $dbr->select( 'ip_changes', '*', [ 'ipc_rev_id' => $this->ipRevId ] );
		$this->assertFalse( $res->fetchObject() );

		// Restore the page
		$this->archivedPage->undelete( [] );

		// Should be back in revision
		$res = $dbr->select( 'revision', '*', [ 'rev_id' => $this->ipRevId ] );
		$row = $res->fetchObject();
		$this->assertEquals( $this->ipEditor, $row->rev_user_text );

		// Should be back in ip_changes
		$res = $dbr->select( 'ip_changes', '*', [ 'ipc_rev_id' => $this->ipRevId ] );
		$row = $res->fetchObject();
		$this->assertEquals( IP::toHex( $this->ipEditor ), $row->ipc_hex );
	}
}

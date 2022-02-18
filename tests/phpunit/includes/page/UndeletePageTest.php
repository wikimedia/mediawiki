<?php

use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\SlotRecord;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\IPUtils;

/**
 * @group Database
 * @coversDefaultClass \MediaWiki\Page\UndeletePage
 */
class UndeletePageTest extends MediaWikiIntegrationTestCase {
	/**
	 * @var WikiPage
	 */
	private $page;

	/**
	 * A logged out user who edited the page before it was archived.
	 * @var string
	 */
	private $ipEditor;

	/**
	 * Revision of the IP edit (the second edit)
	 * @var RevisionRecord
	 */
	private $ipRev;

	protected function addCoreDBData() {
		// Blanked out to keep auto-increment values stable.
	}

	protected function setUp(): void {
		parent::setUp();

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
				'slots',
				'content',
				'content_models',
				'slot_roles',
			]
		);

		// First create our dummy page
		$page = Title::newFromText( 'UndeletePageTest_thePage' );
		$page = new WikiPage( $page );
		$content = ContentHandler::makeContent(
			'testing',
			$page->getTitle(),
			CONTENT_MODEL_WIKITEXT
		);

		$user = $this->getTestUser()->getUser();
		$page->doUserEditContent( $content, $user, 'testing', EDIT_NEW );

		// Insert IP revision
		$this->ipEditor = '2001:DB8:0:0:0:0:0:1';

		$revisionStore = $this->getServiceContainer()->getRevisionStore();

		$firstRev = $page->getRevisionRecord();
		$ipTimestamp = wfTimestamp(
			TS_MW,
			wfTimestamp( TS_UNIX, $firstRev->getTimestamp() ) + 1
		);
		$rev = new MutableRevisionRecord( $page );
		$rev->setUser( UserIdentityValue::newAnonymous( $this->ipEditor ) );
		$rev->setTimestamp( $ipTimestamp );
		$rev->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );
		$rev->setComment( CommentStoreComment::newUnsavedComment( 'just a test' ) );

		$dbw = wfGetDB( DB_PRIMARY );
		$this->ipRev = $revisionStore->insertRevisionOn( $rev, $dbw );

		$this->deletePage( $page, '', $user );

		$this->page = $page;
	}

	/**
	 * @covers ::undeleteUnsafe
	 * @covers ::undeleteRevisions
	 */
	public function testUndeleteRevisions() {
		// TODO: MCR: Test undeletion with multiple slots. Check that slots remain untouched.
		$revisionStore = $this->getServiceContainer()->getRevisionStore();

		// First make sure old revisions are archived
		$dbr = wfGetDB( DB_REPLICA );
		$arQuery = $revisionStore->getArchiveQueryInfo();
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
		$undeletePage = $this->getServiceContainer()->getUndeletePageFactory()->newUndeletePage(
			$this->page,
			$this->getTestSysop()->getUser()
		);
		$undeletePage->undeleteUnsafe( '' );

		// Should be back in revision
		$revQuery = $revisionStore->getQueryInfo();
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
		$this->assertEquals( IPUtils::toHex( $this->ipEditor ), $row->ipc_hex );
	}
}

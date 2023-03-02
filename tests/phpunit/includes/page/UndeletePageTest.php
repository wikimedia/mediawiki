<?php

use MediaWiki\Page\UndeletePage;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\IPUtils;

/**
 * @group Database
 * @coversDefaultClass \MediaWiki\Page\UndeletePage
 */
class UndeletePageTest extends MediaWikiIntegrationTestCase {
	/**
	 * @var array
	 */
	private $pages = [];

	/**
	 * A logged out user who edited the page before it was archived.
	 * @var string
	 */
	private $ipEditor;

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

		$this->ipEditor = '2001:DB8:0:0:0:0:0:1';
		$this->setupPage( 'UndeletePageTest_thePage', NS_MAIN, ' ' );
		$this->setupPage( 'UndeletePageTest_thePage', NS_TALK, ' ' );
	}

	/**
	 * @param string $titleText
	 * @param int $ns
	 * @param string $content
	 */
	private function setupPage( string $titleText, int $ns, string $content ): void {
		$title = Title::makeTitle( $ns, $titleText );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$performer = static::getTestUser()->getUser();
		$content = ContentHandler::makeContent( $content, $page->getTitle(), CONTENT_MODEL_WIKITEXT );
		$updater = $page->newPageUpdater( UserIdentityValue::newAnonymous( $this->ipEditor ) )
			->setContent( SlotRecord::MAIN, $content );

		$revisionRecord = $updater->saveRevision( CommentStoreComment::newUnsavedComment( "testing" ) );
		if ( !$updater->wasSuccessful() ) {
			$this->fail( $updater->getStatus()->getWikiText() );
		}

		$this->pages[] = [ 'page' => $page, 'revId' => $revisionRecord->getId() ];
		$this->deletePage( $page, '', $performer );
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

		foreach ( [ 0, 1 ] as $key ) {
			$row = $dbr->selectRow(
				$arQuery['tables'],
				$arQuery['fields'],
				[ 'ar_rev_id' => $this->pages[$key]['revId'] ],
				__METHOD__,
				[],
				$arQuery['joins']
			);
			$this->assertEquals( $this->ipEditor, $row->ar_user_text );

			// Should not be in revision
			$row = $dbr->selectRow( 'revision', '1', [ 'rev_id' => $this->pages[$key]['revId'] ] );
			$this->assertFalse( $row );

			// Should not be in ip_changes
			$row = $dbr->selectRow( 'ip_changes', '1', [ 'ipc_rev_id' => $this->pages[$key]['revId'] ] );
			$this->assertFalse( $row );
		}

		// Restore the page
		$undeletePage = $this->getServiceContainer()->getUndeletePageFactory()->newUndeletePage(
			$this->pages[0]['page'],
			$this->getTestSysop()->getUser()
		);

		$status = $undeletePage->setUndeleteAssociatedTalk( true )->undeleteUnsafe( '' );
		$this->assertEquals( 2, $status->value[UndeletePage::REVISIONS_RESTORED] );

		$revQuery = $revisionStore->getQueryInfo();
		// check subject page and talk page are both back in the revision table
		foreach ( [ 0, 1 ] as $key ) {
			$row = $dbr->selectRow(
				$revQuery['tables'],
				$revQuery['fields'],
				[ 'rev_id' => $this->pages[$key]['revId'] ],
				__METHOD__,
				[],
				$revQuery['joins']
			);
			$this->assertNotFalse( $row, 'row exists in revision table' );
			$this->assertEquals( $this->ipEditor, $row->rev_user_text );

			// Should be back in ip_changes
			$row = $dbr->selectRow( 'ip_changes', [ 'ipc_hex' ], [ 'ipc_rev_id' => $this->pages[$key]['revId'] ] );
			$this->assertNotFalse( $row, 'row exists in ip_changes table' );
			$this->assertEquals( IPUtils::toHex( $this->ipEditor ), $row->ipc_hex );
		}
	}
}

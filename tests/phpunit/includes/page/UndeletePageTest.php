<?php

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Page\UndeletePage;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\IPUtils;

/**
 * @group Database
 * @coversDefaultClass \MediaWiki\Page\UndeletePage
 */
class UndeletePageTest extends MediaWikiIntegrationTestCase {

	use TempUserTestTrait;

	/**
	 * @var array
	 */
	private $pages = [];

	/**
	 * A logged out user who edited the page before it was archived.
	 * @var string
	 */
	private $ipEditor;

	protected function setUp(): void {
		parent::setUp();

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
		$this->disableAutoCreateTempUser();
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
	 * @covers \MediaWiki\Revision\RevisionStoreFactory::getRevisionStoreForUndelete
	 * @covers \MediaWiki\User\ActorStoreFactory::getActorStoreForUndelete
	 */
	public function testUndeleteRevisions() {
		// TODO: MCR: Test undeletion with multiple slots. Check that slots remain untouched.
		$revisionStore = $this->getServiceContainer()->getRevisionStore();

		// First make sure old revisions are archived
		$dbr = $this->getDb();

		foreach ( [ 0, 1 ] as $key ) {
			$row = $revisionStore->newArchiveSelectQueryBuilder( $dbr )
				->joinComment()
				->where( [ 'ar_rev_id' => $this->pages[$key]['revId'] ] )
				->caller( __METHOD__ )->fetchRow();
			$this->assertEquals( $this->ipEditor, $row->ar_user_text );

			// Should not be in revision
			$row = $dbr->newSelectQueryBuilder()
				->select( '1' )
				->from( 'revision' )
				->where( [ 'rev_id' => $this->pages[$key]['revId'] ] )
				->fetchRow();
			$this->assertFalse( $row );

			// Should not be in ip_changes
			$row = $dbr->newSelectQueryBuilder()
				->select( '1' )
				->from( 'ip_changes' )
				->where( [ 'ipc_rev_id' => $this->pages[$key]['revId'] ] )
				->fetchRow();
			$this->assertFalse( $row );
		}

		// Enable autocreation of temporary users to test that undeletion of revisions performed by IP addresses works
		// when temporary accounts are enabled.
		$this->enableAutoCreateTempUser();
		// Restore the page
		$undeletePage = $this->getServiceContainer()->getUndeletePageFactory()->newUndeletePage(
			$this->pages[0]['page'],
			$this->getTestSysop()->getUser()
		);

		$status = $undeletePage->setUndeleteAssociatedTalk( true )->undeleteUnsafe( '' );
		$this->assertEquals( 2, $status->value[UndeletePage::REVISIONS_RESTORED] );

		// check subject page and talk page are both back in the revision table
		foreach ( [ 0, 1 ] as $key ) {
			$row = $revisionStore->newSelectQueryBuilder( $dbr )
				->where( [ 'rev_id' => $this->pages[$key]['revId'] ] )
				->caller( __METHOD__ )->fetchRow();

			$this->assertNotFalse( $row, 'row exists in revision table' );
			$this->assertEquals( $this->ipEditor, $row->rev_user_text );

			// Should be back in ip_changes
			$row = $dbr->newSelectQueryBuilder()
				->select( [ 'ipc_hex' ] )
				->from( 'ip_changes' )
				->where( [ 'ipc_rev_id' => $this->pages[$key]['revId'] ] )
				->fetchRow();
			$this->assertNotFalse( $row, 'row exists in ip_changes table' );
			$this->assertEquals( IPUtils::toHex( $this->ipEditor ), $row->ipc_hex );
		}
	}
}

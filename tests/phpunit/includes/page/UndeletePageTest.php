<?php

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Page\Event\PageUpdatedEvent;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Page\UndeletePage;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Language\LocalizationUpdateSpyTrait;
use MediaWiki\Tests\recentchanges\ChangeTrackingUpdateSpyTrait;
use MediaWiki\Tests\ResourceLoader\ResourceLoaderUpdateSpyTrait;
use MediaWiki\Tests\Search\SearchUpdateSpyTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentityValue;
use PHPUnit\Framework\Assert;
use Wikimedia\IPUtils;

/**
 * @group Database
 * @coversDefaultClass \MediaWiki\Page\UndeletePage
 */
class UndeletePageTest extends MediaWikiIntegrationTestCase {

	use TempUserTestTrait;
	use ChangeTrackingUpdateSpyTrait;
	use SearchUpdateSpyTrait;
	use LocalizationUpdateSpyTrait;
	use ResourceLoaderUpdateSpyTrait;

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
	 * Test update propagation.
	 * Includes regression test for T381225
	 * @param string $titleText
	 * @param int $ns
	 * @param string|Content $content
	 */
	private function setupPage( string $titleText, int $ns, $content ): void {
		if ( is_string( $content ) ) {
			$content = new WikitextContent( $content );
		}

		$this->disableAutoCreateTempUser();
		$title = Title::makeTitle( $ns, $titleText );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$performer = static::getTestUser()->getUser();
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

	/**
	 * Regression test for T381225
	 * @covers \MediaWiki\Page\UndeletePage::undeleteUnsafe
	 */
	public function testEventEmission() {
		$calls = 0;

		$page = PageIdentityValue::localIdentity( 0, NS_MEDIAWIKI, __METHOD__ );
		$this->setupPage( $page->getDBkey(), $page->getNamespace(), 'Lorem Ipsum' );

		$sysop = $this->getTestSysop()->getUser();

		// clear the queue
		$this->runJobs();

		$this->getServiceContainer()->getDomainEventSource()->registerListener(
			'PageUpdated',
			static function ( PageUpdatedEvent $event ) use ( &$calls, $sysop ) {
				Assert::assertTrue(
					$event->hasCause( PageUpdatedEvent::CAUSE_UNDELETE ),
					PageUpdatedEvent::CAUSE_UNDELETE
				);

				Assert::assertTrue( $event->isSilent(), 'isSilent' );
				Assert::assertTrue( $event->isAutomated(), 'isAutomated' );
				Assert::assertTrue( $event->isContentChange(), 'isContentChange' );
				Assert::assertFalse( $event->getAuthor()->isRegistered(), 'getAuthor' );
				Assert::assertSame( $event->getPerformer(), $sysop, 'getPerformer' );

				$calls++;
			}
		);

		// Now undelete the page
		$undeletePage = $this->getServiceContainer()->getUndeletePageFactory()->newUndeletePage(
			$page,
			$sysop
		);

		$undeletePage->undeleteUnsafe( 'just a test' );

		$this->runDeferredUpdates();
		$this->assertSame( 1, $calls );
	}

	public static function provideUpdatePropagation() {
		static $counter = 1;
		$name = __METHOD__ . $counter++;

		yield 'article' => [ PageIdentityValue::localIdentity( 0, NS_MAIN, $name ) ];
		yield 'user talk' => [ PageIdentityValue::localIdentity( 0, NS_USER_TALK, $name ) ];
		yield 'message' => [ PageIdentityValue::localIdentity( 0, NS_MEDIAWIKI, $name ) ];
		yield 'script' => [
			PageIdentityValue::localIdentity( 0, NS_USER, "$name/common.js" ),
			new JavaScriptContent( 'console.log("hi")' ),
		];
	}

	/**
	 * Regression test for T381225
	 *
	 * @dataProvider provideUpdatePropagation
	 * @covers \MediaWiki\Page\UndeletePage::undeleteUnsafe
	 */
	public function testUpdatePropagation( ProperPageIdentity $page, ?Content $content = null ) {
		$content ??= new WikitextContent( 'hi' );
		$this->setupPage( $page->getDBkey(), $page->getNamespace(), $content );
		$this->runJobs();

		$performer = $this->getTestSysop()->getUser();

		// Should generate an RC entry for undeletion,
		// but not a regular page edit.
		$this->expectChangeTrackingUpdates( 0, 1, 0, 0 );

		$this->expectSearchUpdates( 1 );
		$this->expectLocalizationUpdate( $page->getNamespace() === NS_MEDIAWIKI ? 1 : 0 );
		$this->expectResourceLoaderUpdates(
			$content->getModel() === CONTENT_MODEL_JAVASCRIPT ? 1 : 0
		);

		// Now undelete the page
		$undeletePage = $this->getServiceContainer()->getUndeletePageFactory()->newUndeletePage(
			$page,
			$performer
		);

		$undeletePage->undeleteUnsafe( 'just a test' );

		// NOTE: assertions are applied by the spies installed earlier.
		$this->runDeferredUpdates();
	}

}

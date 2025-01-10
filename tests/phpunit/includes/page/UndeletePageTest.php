<?php

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\Content;
use MediaWiki\Content\JavaScriptContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Page\Event\PageCreatedEvent;
use MediaWiki\Page\Event\PageRevisionUpdatedEvent;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Page\UndeletePage;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\PageUpdateCauses;
use MediaWiki\Tests\ExpectCallbackTrait;
use MediaWiki\Tests\Language\LocalizationUpdateSpyTrait;
use MediaWiki\Tests\recentchanges\ChangeTrackingUpdateSpyTrait;
use MediaWiki\Tests\ResourceLoader\ResourceLoaderUpdateSpyTrait;
use MediaWiki\Tests\Search\SearchUpdateSpyTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Utils\MWTimestamp;
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
	use ExpectCallbackTrait;

	/**
	 * A logged out user who edited the page before it was archived.
	 * @var string
	 */
	private $ipEditor;

	protected function setUp(): void {
		parent::setUp();

		$this->ipEditor = '2001:DB8:0:0:0:0:0:1';
	}

	/**
	 * Test update propagation.
	 * Includes regression test for T381225
	 * @param string $titleText
	 * @param int $ns
	 * @param string|Content $content
	 *
	 * @return array { page: WikiPage, revId: int }
	 */
	private function setupPage( string $titleText, int $ns, $content ): array {
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

		// Run jobs that were enqueued by page creation now, since they might expect the page to exist.
		$this->runJobs( [ 'minJobs' => 0 ] );

		$this->deletePage( $page, '', $performer );
		return [ 'page' => $page, 'revId' => $revisionRecord->getId() ];
	}

	/**
	 * @covers ::undeleteUnsafe
	 * @covers ::undeleteRevisions
	 * @covers \MediaWiki\Revision\RevisionStoreFactory::getRevisionStoreForUndelete
	 * @covers \MediaWiki\User\ActorStoreFactory::getActorStoreForUndelete
	 */
	public function testUndeleteRevisions() {
		$pages[] = $this->setupPage( 'UndeletePageTest_thePage', NS_MAIN, ' ' );
		$pages[] = $this->setupPage( 'UndeletePageTest_thePage', NS_TALK, ' ' );

		// TODO: MCR: Test undeletion with multiple slots. Check that slots remain untouched.
		$revisionStore = $this->getServiceContainer()->getRevisionStore();

		// First make sure old revisions are archived
		$dbr = $this->getDb();

		foreach ( $pages as $pg ) {
			$row = $revisionStore->newArchiveSelectQueryBuilder( $dbr )
				->joinComment()
				->where( [ 'ar_rev_id' => $pg['revId'] ] )
				->caller( __METHOD__ )->fetchRow();
			$this->assertEquals( $this->ipEditor, $row->ar_user_text );

			// Should not be in revision
			$row = $dbr->newSelectQueryBuilder()
				->select( '1' )
				->from( 'revision' )
				->where( [ 'rev_id' => $pg['revId'] ] )
				->fetchRow();
			$this->assertFalse( $row );

			// Should not be in ip_changes
			$row = $dbr->newSelectQueryBuilder()
				->select( '1' )
				->from( 'ip_changes' )
				->where( [ 'ipc_rev_id' => $pg['revId'] ] )
				->fetchRow();
			$this->assertFalse( $row );
		}

		// Enable autocreation of temporary users to test that undeletion of revisions performed by IP addresses works
		// when temporary accounts are enabled.
		$this->enableAutoCreateTempUser();
		// Restore the page
		$undeletePage = $this->getServiceContainer()->getUndeletePageFactory()->newUndeletePage(
			$pages[0]['page'],
			$this->getTestSysop()->getUser()
		);

		$status = $undeletePage->setUndeleteAssociatedTalk( true )->undeleteUnsafe( '' );
		$this->assertEquals( 2, $status->value[UndeletePage::REVISIONS_RESTORED] );

		// check subject page and talk page are both back in the revision table
		foreach ( $pages as $pg ) {
			$row = $revisionStore->newSelectQueryBuilder( $dbr )
				->where( [ 'rev_id' => $pg['revId'] ] )
				->caller( __METHOD__ )->fetchRow();

			$this->assertNotFalse( $row, 'row exists in revision table' );
			$this->assertEquals( $this->ipEditor, $row->rev_user_text );

			// Should be back in ip_changes
			$row = $dbr->newSelectQueryBuilder()
				->select( [ 'ipc_hex' ] )
				->from( 'ip_changes' )
				->where( [ 'ipc_rev_id' => $pg['revId'] ] )
				->fetchRow();

			$this->assertNotFalse( $row, 'row exists in ip_changes table' );
			$this->assertEquals( IPUtils::toHex( $this->ipEditor ), $row->ipc_hex );
		}
	}

	/**
	 * Regression test for T381225
	 * @covers \MediaWiki\Page\UndeletePage::undeleteUnsafe
	 */
	public function testEventEmission_new() {
		$page = PageIdentityValue::localIdentity( 0, NS_MEDIAWIKI, __METHOD__ );
		$this->setupPage( $page->getDBkey(), $page->getNamespace(), 'Lorem Ipsum' );

		$sysop = $this->getTestSysop()->getUser();

		// clear the queue
		$this->runJobs();

		$this->expectDomainEvent(
			PageRevisionUpdatedEvent::TYPE, 1,
			static function ( PageRevisionUpdatedEvent $event ) use ( $sysop ) {
				Assert::assertTrue(
					$event->hasCause( PageRevisionUpdatedEvent::CAUSE_UNDELETE ),
					PageRevisionUpdatedEvent::CAUSE_UNDELETE
				);

				Assert::assertTrue( $event->isSilent(), 'isSilent' );
				Assert::assertTrue( $event->isImplicit(), 'isImplicit' );
				Assert::assertTrue( $event->isCreation(), 'isCreation' );
				Assert::assertTrue(
					$event->isEffectiveContentChange(),
					'isEffectiveContentChange'
				);
				Assert::assertTrue(
					$event->isNominalContentChange(),
					'isNominalContentChange'
				);
				Assert::assertTrue(
					$event->changedLatestRevisionId(),
					'changedLatestRevisionId'
				);
				Assert::assertFalse(
					$event->isReconciliationRequest(),
					'isReconciliationRequest'
				);
				Assert::assertFalse( $event->getAuthor()->isRegistered(), 'getAuthor' );
				Assert::assertSame( $event->getPerformer(), $sysop, 'getPerformer' );
			}
		);

		$this->expectDomainEvent(
			PageCreatedEvent::TYPE, 1,
			static function ( PageCreatedEvent $event ) use ( $sysop ) {
				Assert::assertTrue(
					$event->hasCause( PageUpdateCauses::CAUSE_UNDELETE ),
					PageUpdateCauses::CAUSE_UNDELETE
				);

				Assert::assertSame( $sysop, $event->getPerformer(), 'getPerformer' );
				Assert::assertSame( 'just some reason', $event->getReason(), 'getReason' );

				Assert::assertSame(
					'Lorem Ipsum',
					$event->getLatestRevisionAfter()->getMainContentRaw()->getText()
				);
			}
		);

		// Now undelete the page
		$undeletePage = $this->getServiceContainer()->getUndeletePageFactory()->newUndeletePage(
			$page,
			$sysop
		);

		$undeletePage->undeleteUnsafe( 'just some reason' );
	}

	public static function provideEventEmission_existing() {
		yield 'undelete historical revision' => [ -60, 10, false ];
		yield 'undelete newer revision (different page ID)' => [ +60, 10, true ];
		yield 'undelete newer revision (same page ID)' => [ +60, 0, true ];
	}

	/**
	 * @dataProvider provideEventEmission_existing
	 *
	 * Regression test for T391739
	 * @covers \MediaWiki\Page\UndeletePage::undeleteUnsafe
	 */
	public function testEventEmission_existing(
		$archiveTimestampOffset,
		$pageIdOffset,
		$expectEvent,
		$expectCreation = false
	) {
		$fakeTime = wfTimestamp( TS_UNIX, '2020-01-01T11:22:33' );

		// create a page and delete it.
		MWTimestamp::setFakeTime( $fakeTime + $archiveTimestampOffset );
		$page = PageIdentityValue::localIdentity( 0, NS_MEDIAWIKI, __METHOD__ );
		[ 'revId' => $archivedRev ] = $this->setupPage(
			$page->getDBkey(),
			$page->getNamespace(),
			'Lorem Ipsum'
		);

		// create another page with the same name
		MWTimestamp::setFakeTime( $fakeTime );
		$status = $this->editPage( $page, 'Lorem Ipsum' );
		$revId = $status->getNewRevision()->getId();
		$pageId = $status->getNewRevision()->getPageId();

		// Force the page ID of the archived revision(s).
		$this->getDb()->newUpdateQueryBuilder()->update( 'archive' )
			->where( [ 'ar_rev_id' => $archivedRev ] )
			->set( [
				'ar_page_id' => $pageId + $pageIdOffset
			] )
			->caller( __METHOD__ )
			->execute();

		$this->assertSame( 1, $this->getDb()->affectedRows() );

		// clear the queue
		$this->runJobs();

		$sysop = $this->getTestSysop()->getUser();

		$this->expectDomainEvent(
			PageRevisionUpdatedEvent::TYPE, $expectEvent ? 1 : 0,
			static function ( PageRevisionUpdatedEvent $event ) use ( $expectCreation, $revId ) {
				Assert::assertTrue(
					$event->hasCause( PageRevisionUpdatedEvent::CAUSE_UNDELETE ),
					PageRevisionUpdatedEvent::CAUSE_UNDELETE
				);

				Assert::assertTrue( $event->isSilent(), 'isSilent' );
				Assert::assertTrue( $event->isImplicit(), 'isImplicit' );
				Assert::assertSame( $expectCreation, $event->isCreation(), 'isCreation' );
				Assert::assertSame( $revId, $event->getLatestRevisionBefore()->getId() );
				Assert::assertSame( $revId, $event->getPageRecordBefore()->getLatest() );
			}
		);

		// Now undelete the page
		$undeletePage = $this->getServiceContainer()->getUndeletePageFactory()->newUndeletePage(
			$page,
			$sysop
		);
		$status = $undeletePage->undeleteUnsafe( 'just a test' );

		$this->assertStatusGood( $status );
		$this->assertSame( 1, $status->value['revs'] );
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
		// Clear some extension hook handlers that may interfere with mock object expectations.
		$this->clearHooks( [
			'LinksUpdateComplete',
			'PageUndeleteComplete',
		] );

		$content ??= new WikitextContent( 'hi' );
		$this->setupPage( $page->getDBkey(), $page->getNamespace(), $content );
		$this->runJobs();

		$performer = $this->getTestSysop()->getUser();

		// Should generate an RC entry for undeletion,
		// but not a regular page edit.
		$this->expectChangeTrackingUpdates( 0, 1, 0, 0, 0 );

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

<?php

namespace MediaWiki\Tests\Page;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\Content;
use MediaWiki\Content\JavaScriptContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\DeletePage;
use MediaWiki\Page\Event\PageDeletedEvent;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Page\WikiPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Language\LocalizationUpdateSpyTrait;
use MediaWiki\Tests\recentchanges\ChangeTrackingUpdateSpyTrait;
use MediaWiki\Tests\ResourceLoader\ResourceLoaderUpdateSpyTrait;
use MediaWiki\Tests\Search\SearchUpdateSpyTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\Assert;
use Wikimedia\ScopedCallback;

/**
 * @covers \MediaWiki\Page\DeletePage
 * @group Database
 * @note Permission-related tests are in \MediaWiki\Tests\Unit\Page\DeletePageTest
 */
class DeletePageTest extends MediaWikiIntegrationTestCase {
	use ChangeTrackingUpdateSpyTrait;
	use SearchUpdateSpyTrait;
	use LocalizationUpdateSpyTrait;
	use ResourceLoaderUpdateSpyTrait;

	private const PAGE_TEXT = "[[Stuart Little]]\n" .
		"{{Multiple issues}}\n" .
		"https://www.example.com/\n" .
		"[[Category:Felis catus]]";

	private function getDeletePage( ProperPageIdentity $page, Authority $deleter ): DeletePage {
		return $this->getServiceContainer()->getDeletePageFactory()->newDeletePage(
			$page,
			$deleter
		);
	}

	/**
	 * @param string $titleText
	 * @param string|Content $content
	 * @return WikiPage
	 */
	private function createPage( string $titleText, $content ): WikiPage {
		if ( is_string( $content ) ) {
			$content = new WikitextContent( $content );
		}

		$ns = $this->getDefaultWikitextNS();
		$title = Title::newFromText( $titleText, $ns );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$performer = $this->getTestUser()->getAuthority();

		$updater = $page->newPageUpdater( $performer )
			->setContent( SlotRecord::MAIN, $content );

		$updater->saveRevision( CommentStoreComment::newUnsavedComment( "testing" ) );
		if ( !$updater->wasSuccessful() ) {
			$this->fail( $updater->getStatus()->getWikiText() );
		}
		DeferredUpdates::doUpdates();

		return $page;
	}

	private function assertDeletionLogged(
		ProperPageIdentity $title,
		int $pageID,
		User $deleter,
		string $reason,
		bool $suppress,
		string $logSubtype,
		int $logID
	): void {
		$commentQuery = $this->getServiceContainer()->getCommentStore()->getJoin( 'log_comment' );
		$this->newSelectQueryBuilder()
			->select( [
				'log_type',
				'log_action',
				'log_comment' => $commentQuery['fields']['log_comment_text'],
				'log_actor',
				'log_namespace',
				'log_title',
				'log_page',
			] )
			->from( 'logging' )
			->tables( $commentQuery['tables'] )
			->where( [ 'log_id' => $logID ] )
			->joinConds( $commentQuery['joins'] )
			->assertRowValue( [
				$suppress ? 'suppress' : 'delete',
				$logSubtype,
				$reason,
				(string)$deleter->getActorId(),
				(string)$title->getNamespace(),
				$title->getDBkey(),
				$pageID,
			] );
	}

	private function assertArchiveVisibility( Title $title, bool $suppression ): void {
		if ( !$suppression ) {
			// Archived revisions are considered "deleted" only when suppressed, so we'd always get a content
			// in case of normal deletion.
			return;
		}
		$lookup = $this->getServiceContainer()->getArchivedRevisionLookup();
		$archivedRevs = $lookup->listRevisions( $title );
		if ( !$archivedRevs || $archivedRevs->numRows() !== 1 ) {
			$this->fail( 'Unexpected number of archived revisions' );
		}
		$archivedRev = $this->getServiceContainer()->getRevisionStore()
			->newRevisionFromArchiveRow( $archivedRevs->current() );

		$this->assertNotNull(
			$archivedRev->getContent( SlotRecord::MAIN, RevisionRecord::RAW ),
			"Archived content should be there"
		);

		$this->assertNull(
			$archivedRev->getContent( SlotRecord::MAIN, RevisionRecord::FOR_PUBLIC ),
			"Archived content should be null after the page was suppressed for general users"
		);

		$getContentForUser = static function ( Authority $user ) use ( $archivedRev ): ?Content {
			return $archivedRev->getContent(
				SlotRecord::MAIN,
				RevisionRecord::FOR_THIS_USER,
				$user
			);
		};

		$this->assertNull(
			$getContentForUser( static::getTestUser()->getUser() ),
			"Archived content should be null after the page was suppressed for individual users"
		);

		$this->assertNull(
			$getContentForUser( static::getTestSysop()->getUser() ),
			"Archived content should be null after the page was suppressed even for a sysop"
		);

		$this->assertNotNull(
			$getContentForUser( static::getTestUser( [ 'suppress', 'sysop' ] )->getUser() ),
			"Archived content should be visible after the page was suppressed for an oversighter"
		);
	}

	private function assertPageObjectsConsistency( WikiPage $page ): void {
		$this->assertSame(
			0,
			$page->getTitle()->getArticleID(),
			"Title object should now have page id 0"
		);
		$this->assertSame( 0, $page->getId(), "WikiPage should now have page id 0" );
		$this->assertFalse(
			$page->exists(),
			"WikiPage::exists should return false after page was deleted"
		);
		$this->assertNull(
			$page->getContent(),
			"WikiPage::getContent should return null after page was deleted"
		);

		// NOTE: Title objects returned by Title::newFromText may come from an
		//       instance cache!
		$t = Title::newFromText( $page->getTitle()->getPrefixedText() );
		$this->assertFalse(
			$t->exists(),
			"Title::exists should return false after page was deleted"
		);

		$w = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $t );
		$this->assertFalse(
			$w->exists(),
			"WikiPage::exists should return false after page was deleted"
		);
	}

	private function assertLinksUpdateSetup( int $pageID ): void {
		$linkTarget = MediaWikiServices::getInstance()->getLinkTargetLookup()->getLinkTargetId(
			Title::makeTitle( NS_TEMPLATE, 'Multiple_issues' )
		);
		$this->newSelectQueryBuilder()
			->select( [ 'lt_namespace', 'lt_title' ] )
			->from( 'pagelinks' )
			->join( 'linktarget', null, 'pl_target_id=lt_id' )
			->where( [ 'pl_from' => $pageID ] )
			->assertResultSet( [ [ 0, 'Stuart_Little' ], [ NS_TEMPLATE, 'Multiple_issues' ] ] );
		$this->newSelectQueryBuilder()
			->select( 'tl_target_id' )
			->from( 'templatelinks' )
			->where( [ 'tl_from' => $pageID ] )
			->assertFieldValue( $linkTarget );
		$this->newSelectQueryBuilder()
			->select( 'cl_to' )
			->from( 'categorylinks' )
			->where( [ 'cl_from' => $pageID ] )
			->assertFieldValue( 'Felis_catus' );
		$this->newSelectQueryBuilder()
			->select( 'cat_pages' )
			->from( 'category' )
			->where( [ 'cat_title' => 'Felis_catus' ] )
			->assertFieldValue( 1 );
	}

	private function assertPageLinksUpdate( int $pageID, bool $shouldRunJobs ): void {
		if ( $shouldRunJobs ) {
			$this->runJobs();
		}

		$this->newSelectQueryBuilder()
			->select( [ 'lt_namespace', 'lt_title' ] )
			->from( 'pagelinks' )
			->join( 'linktarget', null, 'pl_target_id=lt_id' )
			->where( [ 'pl_from' => $pageID ] )
			->assertEmptyResult();
		$this->newSelectQueryBuilder()
			->select( 'tl_target_id' )
			->from( 'templatelinks' )
			->where( [ 'tl_from' => $pageID ] )
			->assertEmptyResult();
		$this->newSelectQueryBuilder()
			->select( 'cl_to' )
			->from( 'categorylinks' )
			->where( [ 'cl_from' => $pageID ] )
			->assertEmptyResult();
		$this->newSelectQueryBuilder()
			->select( 'cat_pages' )
			->from( 'category' )
			->where( [ 'cat_title' => 'Felis_catus' ] )
			->assertEmptyResult();
	}

	private function assertDeletionTags( int $logId, array $tags ): void {
		if ( !$tags ) {
			return;
		}
		$actualTags = $this->getDb()->newSelectQueryBuilder()
			->select( 'ct_tag_id' )
			->from( 'change_tag' )
			->where( [ 'ct_log_id' => $logId ] )
			->fetchFieldValues();
		$changeTagDefStore = $this->getServiceContainer()->getChangeTagDefStore();
		$expectedTags = array_map( [ $changeTagDefStore, 'acquireId' ], $tags );
		$this->assertArrayEquals( $expectedTags, array_map( 'intval', $actualTags ) );
	}

	/**
	 * @dataProvider provideDeleteUnsafe
	 */
	public function testDeleteUnsafe( bool $suppress, array $tags, bool $immediate, string $logSubtype ) {
		$teardownScope = DeferredUpdates::preventOpportunisticUpdates();
		$deleterUser = static::getTestSysop()->getUser();
		$deleter = new UltimateAuthority( $deleterUser );

		$page = $this->createPage( __METHOD__, self::PAGE_TEXT );
		$id = $page->getId();

		// Create a Title object from text, so it will end up in the instance
		// cache. In assertPageObjectsConsistency() we'll check that we are not
		// getting this stale object from Title::newFromText().
		$titleFromText = Title::newFromText( __METHOD__ );
		$titleFromText->getId(); // make sure the ID is initialized and cached.

		$this->assertLinksUpdateSetup( $id );

		if ( !$immediate ) {
			// Ensure that the job queue can be used
			$this->overrideConfigValue( MainConfigNames::DeleteRevisionsBatchSize, 1 );
			$this->editPage( $page, "second revision" );
		}

		$reason = "testing deletion";
		$deletePage = $this->getDeletePage( $page, $deleter );
		$status = $deletePage
			->setSuppress( $suppress )
			->setTags( $tags )
			->forceImmediate( $immediate )
			->setLogSubtype( $logSubtype )
			->deleteUnsafe( $reason );

		$this->assertStatusGood( $status, 'Deletion should succeed' );

		DeferredUpdates::doUpdates();

		if ( $immediate ) {
			$this->assertFalse( $deletePage->deletionsWereScheduled()[DeletePage::PAGE_BASE] );
			$logIDs = $deletePage->getSuccessfulDeletionsIDs();
			$this->assertCount( 1, $logIDs );
			$logID = $logIDs[DeletePage::PAGE_BASE];
			$this->assertIsInt( $logID );
		} else {
			$this->assertTrue( $deletePage->deletionsWereScheduled()[DeletePage::PAGE_BASE] );
			$this->assertNull( $deletePage->getSuccessfulDeletionsIDs()[DeletePage::PAGE_BASE] );
			$this->runJobs();
			$logID = $this->getDb()->newSelectQueryBuilder()
				->select( 'log_id' )
				->from( 'logging' )
				->where( [ 'log_type' => $suppress ? 'suppress' : 'delete', 'log_namespace' => $page->getNamespace(), 'log_title' => $page->getDBkey() ] )
				->fetchField();
			$this->assertNotFalse( $logID, 'Should have a log ID now' );
			$logID = (int)$logID;
			// Clear caches.
			$page->getTitle()->resetArticleID( false );
			$page->clear();
		}

		$this->assertPageObjectsConsistency( $page );
		$this->assertArchiveVisibility( $page->getTitle(), $suppress );
		$this->assertDeletionLogged( $page, $id, $deleterUser, $reason, $suppress, $logSubtype, $logID );
		$this->assertDeletionTags( $logID, $tags );
		$this->assertPageLinksUpdate( $id, $immediate );

		ScopedCallback::consume( $teardownScope );
	}

	public static function provideDeleteUnsafe(): iterable {
		// Note that we're using immediate deletion as default
		yield 'standard deletion' => [ false, [], true, 'delete' ];
		yield 'suppression' => [ true, [], true, 'delete' ];
		yield 'deletion with tags' => [ false, [ 'tag-foo', 'tag-bar' ], true, 'delete' ];
		yield 'custom deletion log' => [ false, [], true, 'custom-del-log' ];
		yield 'queued deletion' => [ false, [], false, 'delete' ];
	}

	public function testDeletionHooks() {
		$deleterUser = static::getTestSysop()->getUser();
		$deleter = new UltimateAuthority( $deleterUser );

		$status = $this->editPage( __METHOD__, '#REDIRECT[[Foo]]' );
		$id = $status->getNewRevision()->getPageId();
		$wikiPage = $this->getServiceContainer()->getWikiPageFactory()->newFromID( $id );

		$this->assertTrue( $wikiPage->exists(), 'WikiPage exists before deletion' );
		$this->assertTrue( $wikiPage->isRedirect(), 'WikiPage is redirect before deletion' );
		// Clear internal WikiPage state, to ensure that DeletePage loads it if it's missing
		$wikiPage->clear();

		// Set up hook handlers for testing
		$oldHookCalled = 0;
		$newHookCalled = 0;

		$this->setTemporaryHook( 'ArticleDeleteComplete', function (
			WikiPage $wikiPage, ...$unused
		) use ( &$oldHookCalled ) {
			$this->assertTrue( $wikiPage->exists(), 'WikiPage exists in ArticleDeleteComplete hook' );
			$this->assertTrue( $wikiPage->isRedirect(), 'WikiPage is redirect in ArticleDeleteComplete hook' );

			$oldHookCalled++;
		} );

		$this->setTemporaryHook( 'PageDeleteComplete', function (
			ProperPageIdentity $page, ...$unused
		) use ( &$newHookCalled ) {
			$this->assertTrue( $page->exists(), 'ProperPageIdentity exists in PageDeleteComplete hook' );

			// When accessing the corresponding WikiPage, it no longer exists.
			$wikiPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $page );
			$this->assertFalse(
				$wikiPage->exists(),
				'WikiPage no longer exists in PageDeleteComplete hook'
			);

			$newHookCalled++;
		} );

		// Do the deletion
		$reason = "testing deletion";
		$deletePage = $this->getDeletePage( $wikiPage, $deleter );
		$status = $deletePage
			->forceImmediate( true )
			->deleteUnsafe( $reason );

		$this->assertStatusGood( $status, 'Deletion should succeed' );
		$this->assertSame( 1, $oldHookCalled, 'Old hook was called' );
		$this->assertSame( 1, $newHookCalled, 'New hook was called' );

		$this->assertFalse( $wikiPage->exists(), 'WikiPage does not exist after deletion' );
		$this->assertFalse( $wikiPage->isRedirect(), 'WikiPage is not a redirect after deletion' );
	}

	public static function provideEventEmission() {
		yield [ false, [] ];
		yield [ true, [ 'suppressed' ] ];
		yield [ true, [], new WikitextContent( "#REDIRECT [[Foobar]]" ) ];
	}

	/**
	 * @dataProvider provideEventEmission
	 * @covers \MediaWiki\Page\DeletePage::deleteUnsafe
	 */
	public function testEventEmission( $suppress, $tags, $content = null ) {
		$calls = 0;

		if ( $content === null ) {
			$content = new WikitextContent( self::PAGE_TEXT );
		}

		$deleterUser = static::getTestSysop()->getUser();
		$deleter = new UltimateAuthority( $deleterUser );
		$page = $this->createPage( 'MediaWiki:' . __METHOD__, $content );
		$id = $page->getId();

		// clear the queue
		$this->runJobs();

		$this->getServiceContainer()->getDomainEventSource()->registerListener(
			PageDeletedEvent::TYPE,
			static function ( PageDeletedEvent $event ) use ( &$calls, $suppress, $tags, $id, $content
			) {
				Assert::assertNull(
					$event->getPageRecordAfter(),
					'Expected getPageStateAfter() to be null.'
				);
				Assert::assertTrue(
					$event->getPageRecordBefore()->getId() === $event->getPageId(),
					'Expected getPageId() and getPageStateBefore()->getId() to be the same'
				);
				Assert::assertTrue(
					$event->getPageRecordBefore()->isSamePageAs( $event->getDeletedPage() ),
					'Expected getDeletedPage() and getPageStateBefore() to be the same'
				);
				Assert::assertSame(
					$id,
					$event->getPageId(),
					'Expected getPageId() to return the correct ID'
				);
				Assert::assertGreaterThan(
					0,
					$event->getArchivedRevisionCount()
				);

				Assert::assertSame( $tags, $event->getTags() );
				Assert::assertSame( $suppress, $event->isSuppressed() );

				Assert::assertSame( $content->isRedirect(), $event->wasRedirect(), "isRedirect()" );
				Assert::assertSame( $content->getRedirectTarget() === null,
					$event->getRedirectTargetBefore() === null, "getPageRedirectTargetBefore()" );

				if ( $content->getRedirectTarget() !== null ) {
					Assert::assertSame( $content->getRedirectTarget()->getDBkey(),
						$event->getRedirectTargetBefore()->getDBkey(), "getPageRedirectTargetBefore()" );

				}

				$calls++;
			}
		);

		// Now delete the page
		$this->getDeletePage( $page, $deleter )
			->setSuppress( $suppress )
			->setTags( $tags )
			->deleteUnsafe( 'testing' );

		$this->runDeferredUpdates();
		$this->assertSame( 1, $calls );
	}

	public static function provideUpdatePropagation() {
		static $counter = 1;
		$name = __METHOD__ . $counter++;

		yield 'article' => [ "$name" ];
		yield 'user talk' => [ "User_talk:$name" ];
		yield 'message' => [ "MediaWiki:$name" ];
		yield 'script' => [
			"User:$name/common.js",
			new JavaScriptContent( 'console.log("hi")' ),
		];
	}

	/**
	 * Test update propagation.
	 * Includes regression test for T381225
	 *
	 * @dataProvider provideUpdatePropagation
	 * @covers \MediaWiki\Page\UndeletePage::undeleteUnsafe
	 */
	public function testUpdatePropagation( $name, ?Content $content = null ) {
		// Clear some extension hook handlers that may interfere with mock object expectations.
		$this->clearHooks( [
			'PageDeleteComplete',
		] );

		$content ??= new WikitextContent( self::PAGE_TEXT );
		$deleterUser = static::getTestSysop()->getUser();
		$deleter = new UltimateAuthority( $deleterUser );
		$page = $this->createPage( $name, $content );

		$this->runJobs();

		// Should generate an RC entry for deletion,
		// but not a regular page edit.
		$this->expectChangeTrackingUpdates(
			0, 1, 0,
			$page->getNamespace() === NS_USER_TALK ? -1 : 0,
			0
		);

		// TODO: Assert that the search index is updated after deletion.
		//       This appears to be broken at the moment.
		$this->expectSearchUpdates( 1 );

		$this->expectLocalizationUpdate( $page->getNamespace() === NS_MEDIAWIKI ? 1 : 0 );
		$this->expectResourceLoaderUpdates(
			$content->getModel() === CONTENT_MODEL_JAVASCRIPT ? 1 : 0
		);

		// Now delete the page
		$this->getDeletePage( $page, $deleter )
			->deleteUnsafe( 'testing' );

		// NOTE: assertions are applied by the spies installed earlier.
		$this->runDeferredUpdates();
	}
}

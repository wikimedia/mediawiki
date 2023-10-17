<?php

namespace MediaWiki\Tests\Page;

use CommentStoreComment;
use Content;
use ContentHandler;
use DeferredUpdates;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\DeletePage;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use Wikimedia\ScopedCallback;
use WikiPage;

/**
 * @covers \MediaWiki\Page\DeletePage
 * @group Database
 * @note Permission-related tests are in \MediaWiki\Tests\Unit\Page\DeletePageTest
 */
class DeletePageTest extends MediaWikiIntegrationTestCase {
	protected $tablesUsed = [
		'page',
		'revision',
		'redirect',
		'archive',
		'text',
		'slots',
		'content',
		'slot_roles',
		'content_models',
		'recentchanges',
		'logging',
		'pagelinks',
		'change_tag',
		'change_tag_def',
	];

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
	 * @param string $content
	 * @return WikiPage
	 */
	private function createPage( string $titleText, string $content ): WikiPage {
		$ns = $this->getDefaultWikitextNS();
		$title = Title::newFromText( $titleText, $ns );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$performer = $this->getTestUser()->getAuthority();

		$content = ContentHandler::makeContent( $content, $page->getTitle(), CONTENT_MODEL_WIKITEXT );

		$updater = $page->newPageUpdater( $performer )
			->setContent( SlotRecord::MAIN, $content );

		$updater->saveRevision( CommentStoreComment::newUnsavedComment( "testing" ) );
		if ( !$updater->wasSuccessful() ) {
			$this->fail( $updater->getStatus()->getWikiText() );
		}
		DeferredUpdates::doUpdates();
		$this->assertLinksUpdateSetup( $page->getId() );

		return $page;
	}

	private function assertDeletionLogged(
		ProperPageIdentity $title,
		User $deleter,
		string $reason,
		bool $suppress,
		string $logSubtype,
		int $logID
	): void {
		$commentQuery = $this->getServiceContainer()->getCommentStore()->getJoin( 'log_comment' );
		$this->assertSelect(
			[ 'logging' ] + $commentQuery['tables'],
			[
				'log_type',
				'log_action',
				'log_comment' => $commentQuery['fields']['log_comment_text'],
				'log_actor',
				'log_namespace',
				'log_title',
			],
			[ 'log_id' => $logID ],
			[ [
				$suppress ? 'suppress' : 'delete',
				$logSubtype,
				$reason,
				(string)$deleter->getActorId(),
				(string)$title->getNamespace(),
				$title->getDBkey(),
			] ],
			[],
			$commentQuery['joins']
		);
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

		$t = Title::newFromText( $page->getTitle()->getPrefixedText() );
		$this->assertFalse(
			$t->exists(),
			"Title::exists should return false after page was deleted"
		);
	}

	private function assertLinksUpdateSetup( int $pageID ): void {
		$linkTarget = MediaWikiServices::getInstance()->getLinkTargetLookup()->getLinkTargetId(
			Title::makeTitle( NS_TEMPLATE, 'Multiple_issues' )
		);
		$this->newSelectQueryBuilder()
			->select( [ 'pl_namespace', 'pl_title' ] )
			->from( 'pagelinks' )
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
			->select( [ 'pl_namespace', 'pl_title' ] )
			->from( 'pagelinks' )
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
		$this->assertDeletionLogged( $page, $deleterUser, $reason, $suppress, $logSubtype, $logID );
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

			// This works because $page is actually a WikiPage, and WikiPageFactory::newFromTitle() returns
			// the same object. Shouldn't have done that, some extension probably depends on this now…
			$wikiPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $page );
			$this->assertTrue( $wikiPage->exists(), 'WikiPage exists in PageDeleteComplete hook' );
			$this->assertTrue( $wikiPage->isRedirect(), 'WikiPage is redirect in PageDeleteComplete hook' );

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
}

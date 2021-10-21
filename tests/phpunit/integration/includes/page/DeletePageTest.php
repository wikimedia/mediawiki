<?php

namespace MediaWiki\Tests\Page;

use CommentStoreComment;
use Content;
use ContentHandler;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\DeletePage;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWikiIntegrationTestCase;
use PageArchive;
use Title;
use User;
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

	private function getDeletePage( ProperPageIdentity $page, Authority $deleter ): DeletePage {
		return MediaWikiServices::getInstance()->getDeletePageFactory()->newDeletePage(
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
		$page = MediaWikiServices::getInstance()->getWikiPageFactory()->newFromTitle( $title );

		$performer = static::getTestUser()->getUser();

		$content = ContentHandler::makeContent( $content, $page->getTitle(), CONTENT_MODEL_WIKITEXT );

		$updater = $page->newPageUpdater( $performer );
		$updater->setContent( 'main', $content );

		$updater->saveRevision( CommentStoreComment::newUnsavedComment( "testing" ) );
		if ( !$updater->wasSuccessful() ) {
			$this->fail( $updater->getStatus()->getWikiText() );
		}

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
		$commentQuery = MediaWikiServices::getInstance()->getCommentStore()->getJoin( 'log_comment' );
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
		$archive = new PageArchive( $title, MediaWikiServices::getInstance()->getMainConfig() );
		$archivedRevs = $archive->listRevisions();
		if ( !$archivedRevs || $archivedRevs->numRows() !== 1 ) {
			$this->fail( 'Unexpected number of archived revisions' );
		}
		$archivedRev = MediaWikiServices::getInstance()->getRevisionStore()
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
			$getContentForUser( static::getTestUser( [ 'suppress' ] )->getUser() ),
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

	private function assertPageLinksUpdate( int $pageID, bool $shouldRunJobs ): void {
		if ( $shouldRunJobs ) {
			$this->runJobs();
		}

		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'pagelinks', '*', [ 'pl_from' => $pageID ] );

		$this->assertSame( 0, $res->numRows(), 'pagelinks should contain no more links from the page' );
	}

	private function assertDeletionTags( int $logId, array $tags ): void {
		if ( !$tags ) {
			return;
		}
		$actualTags = wfGetDB( DB_REPLICA )->selectFieldValues(
			'change_tag',
			'ct_tag_id',
			[ 'ct_log_id' => $logId ]
		);
		$changeTagDefStore = MediaWikiServices::getInstance()->getChangeTagDefStore();
		$expectedTags = array_map( [ $changeTagDefStore, 'acquireId' ], $tags );
		$this->assertArrayEquals( $expectedTags, array_map( 'intval', $actualTags ) );
	}

	/**
	 * @dataProvider provideDeleteUnsafe
	 */
	public function testDeleteUnsafe( bool $suppress, array $tags, bool $immediate, string $logSubtype ) {
		$deleterUser = static::getTestSysop()->getUser();
		$deleter = new UltimateAuthority( $deleterUser );
		$page = $this->createPage( __METHOD__, "[[original text]] foo" );
		$id = $page->getId();

		if ( !$immediate ) {
			// Ensure that the job queue can be used
			$this->setMwGlobals( [
				'wgDeleteRevisionsBatchSize' => 1
			] );
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

		$this->assertTrue( $status->isGood(), 'Deletion should succeed' );

		if ( $immediate ) {
			$this->assertFalse( $deletePage->deletionWasScheduled() );
			$logIDs = $deletePage->getSuccessfulDeletionsIDs();
			$this->assertCount( 1, $logIDs );
			$logID = $logIDs[0];
			$this->assertIsInt( $logID );
		} else {
			$this->assertTrue( $deletePage->deletionWasScheduled() );
			$this->runJobs();
			$logID = wfGetDB( DB_REPLICA )->selectField(
				'logging',
				'log_id',
				[
					'log_type' => $suppress ? 'suppress' : 'delete',
					'log_namespace' => $page->getNamespace(),
					'log_title' => $page->getDBkey()
				]
			);
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
	}

	public function provideDeleteUnsafe(): iterable {
		// Note that we're using immediate deletion as default
		yield 'standard deletion' => [ false, [], true, 'delete' ];
		yield 'suppression' => [ true, [], true, 'delete' ];
		yield 'deletion with tags' => [ false, [ 'tag-foo', 'tag-bar' ], true, 'delete' ];
		yield 'custom deletion log' => [ false, [], true, 'custom-del-log' ];
		yield 'queued deletion' => [ false, [], false, 'delete' ];
	}

	/**
	 * @todo This test should go away if we don't want doDeleteUpdates to be public
	 */
	public function testDoDeleteUpdates() {
		$user = static::getTestUser()->getUser();
		$page = $this->createPage( __METHOD__, "[[original text]] foo" );
		$id = $page->getId();
		// make sure the current revision is cached.
		$page->loadPageData();

		// Similar to MovePage logic
		wfGetDB( DB_PRIMARY )->delete( 'page', [ 'page_id' => $id ], __METHOD__ );
		$this->getDeletePage( $page, $user )->doDeleteUpdates( $page->getRevisionRecord() );
		$this->assertPageLinksUpdate( $id, true );
	}
}

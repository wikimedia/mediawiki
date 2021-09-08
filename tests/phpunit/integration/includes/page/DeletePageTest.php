<?php

namespace MediaWiki\Tests\Page;

use CommentStoreComment;
use ContentHandler;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\DeletePage;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Revision\RevisionRecord;
use MediaWikiIntegrationTestCase;
use Title;
use WikiPage;

/**
 * @covers \MediaWiki\Page\DeletePage
 * @group Database
 * @note Permission-related tests are in \MediaWiki\Tests\Unit\Page\DeletePageTest
 * @todo Test all possible mutations
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

	public function testDeleteUnsafe() {
		$deleterUser = static::getTestSysop()->getUser();
		$deleter = new UltimateAuthority( $deleterUser );
		$page = $this->createPage( __METHOD__, "[[original text]] foo" );
		$id = $page->getId();

		$reason = "testing deletion";
		$status = $this->getDeletePage( $page, $deleter )->deleteUnsafe( $reason );

		$this->assertFalse(
			$page->getTitle()->getArticleID() > 0,
			"Title object should now have page id 0"
		);
		$this->assertFalse( $page->getId() > 0, "WikiPage should now have page id 0" );
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

		$this->runJobs();

		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'pagelinks', '*', [ 'pl_from' => $id ] );

		$this->assertSame( 0, $res->numRows(), 'pagelinks should contain no more links from the page' );

		// Test deletion logging
		$logId = $status->getValue();
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
			[ 'log_id' => $logId ],
			[ [
				'delete',
				'delete',
				$reason,
				(string)$deleterUser->getActorId(),
				(string)$page->getTitle()->getNamespace(),
				$page->getTitle()->getDBkey(),
			] ],
			[],
			$commentQuery['joins']
		);
	}

	/**
	 * @todo Merge in testDeleteUnsafe
	 */
	public function testDeleteUnsafe__suppress() {
		$deleterUser = static::getTestSysop()->getUser();
		$deleter = new UltimateAuthority( $deleterUser );
		$page = $this->createPage( __METHOD__, "[[original text]] foo" );

		$reason = "testing deletion";
		$status = $this->getDeletePage( $page, $deleter )->setSuppress( true )->deleteUnsafe( $reason );

		// Test suppression logging
		$logId = $status->getValue();
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
			[ 'log_id' => $logId ],
			[ [
				'suppress',
				'delete',
				$reason,
				(string)$deleterUser->getActorId(),
				(string)$page->getTitle()->getNamespace(),
				$page->getTitle()->getDBkey(),
			] ],
			[],
			$commentQuery['joins']
		);

		$this->assertNull(
			$page->getContent( RevisionRecord::FOR_PUBLIC ),
			"WikiPage::getContent should return null after the page was suppressed for general users"
		);

		$this->assertNull(
			$page->getContent( RevisionRecord::FOR_THIS_USER, static::getTestUser()->getUser() ),
			"WikiPage::getContent should return null after the page was suppressed for individual users"
		);

		$this->assertNull(
			$page->getContent( RevisionRecord::FOR_THIS_USER, $deleterUser ),
			"WikiPage::getContent should return null after the page was suppressed even for a sysop"
		);
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
		$this->getDeletePage( $page, $user )->doDeleteUpdates( $id, $page->getRevisionRecord() );

		$this->runJobs();

		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'pagelinks', '*', [ 'pl_from' => $id ] );
		$this->assertSame( 0, $res->numRows(), 'pagelinks should contain no more links from the page' );
	}
}

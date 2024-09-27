<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Tests for MediaWiki api.php?action=delete.
 *
 * @author Yifei He
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiDelete
 */
class ApiDeleteTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
	}

	public function testDelete() {
		$title = Title::makeTitle( NS_HELP, 'TestDelete' );

		// create new page
		$this->editPage( $title, 'Some text' );

		// test deletion
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $title->getPrefixedText(),
		] )[0];

		$this->assertArrayHasKey( 'delete', $apiResult );
		$this->assertArrayHasKey( 'title', $apiResult['delete'] );
		$this->assertSame( $title->getPrefixedText(), $apiResult['delete']['title'] );
		$this->assertArrayHasKey( 'logid', $apiResult['delete'] );

		$this->assertFalse( $title->exists( IDBAccessObject::READ_LATEST ) );
	}

	public function testBatchedDelete() {
		$this->overrideConfigValue(
			MainConfigNames::DeleteRevisionsBatchSize, 1
		);

		$title = Title::makeTitle( NS_HELP, 'TestBatchedDelete' );
		for ( $i = 1; $i <= 3; $i++ ) {
			$this->editPage( $title, "Revision $i" );
		}

		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $title->getPrefixedText(),
		] )[0];

		$this->assertArrayHasKey( 'delete', $apiResult );
		$this->assertArrayHasKey( 'title', $apiResult['delete'] );
		$this->assertSame( $title->getPrefixedText(), $apiResult['delete']['title'] );
		$this->assertArrayHasKey( 'scheduled', $apiResult['delete'] );
		$this->assertTrue( $apiResult['delete']['scheduled'] );
		$this->assertArrayNotHasKey( 'logid', $apiResult['delete'] );

		// Run the jobs
		$this->runJobs();

		$this->assertFalse( $title->exists( IDBAccessObject::READ_LATEST ) );
	}

	public function testDeleteNonexistent() {
		$this->expectApiErrorCode( 'missingtitle' );

		$this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => 'This page deliberately left nonexistent',
		] );
	}

	public function testDeleteAssociatedTalkPage() {
		$title = Title::makeTitle( NS_HELP, 'TestDeleteAssociatedTalkPage' );
		$talkPage = $title->getTalkPageIfDefined();
		$this->editPage( $title, 'Some text' );
		$this->editPage( $talkPage, 'Some text' );
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $title->getPrefixedText(),
			'deletetalk' => true,
		] )[0];

		$this->assertSame( $title->getPrefixedText(), $apiResult['delete']['title'] );
		$this->assertFalse( $talkPage->exists( IDBAccessObject::READ_LATEST ) );
	}

	public function testDeleteAssociatedTalkPageNonexistent() {
		$title = Title::makeTitle( NS_HELP, 'TestDeleteAssociatedTalkPageNonexistent' );
		$this->editPage( $title, 'Some text' );
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $title->getPrefixedText(),
			'deletetalk' => true,
		] )[0];

		$this->assertSame( $title->getPrefixedText(), $apiResult['delete']['title'] );
		$this->assertArrayHasKey( 'warnings', $apiResult );
	}

	public function testDeletionWithoutPermission() {
		$this->expectApiErrorCode( 'permissiondenied' );

		$title = Title::makeTitle( NS_HELP, 'TestDeletionWithoutPermission' );

		// create new page
		$this->editPage( $title, 'Some text' );

		// test deletion without permission
		try {
			$user = new User();
			$this->doApiRequest( [
				'action' => 'delete',
				'title' => $title->getPrefixedText(),
				'token' => $user->getEditToken(),
			], null, null, $user );
		} finally {
			$this->assertTrue( $title->exists( IDBAccessObject::READ_LATEST ) );
		}
	}

	public function testDeleteWithTag() {
		$title = Title::makeTitle( NS_HELP, 'TestDeleteWithTag' );

		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'custom tag' );

		$this->editPage( $title, 'Some text' );

		$this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $title->getPrefixedText(),
			'tags' => 'custom tag',
		] );

		$this->assertFalse( $title->exists( IDBAccessObject::READ_LATEST ) );

		$this->assertSame( 'custom tag', $this->getDb()->newSelectQueryBuilder()
			->select( 'ctd_name' )
			->from( 'logging' )
			->join( 'change_tag', null, 'ct_log_id = log_id' )
			->join( 'change_tag_def', null, 'ctd_id = ct_tag_id' )
			->where( [ 'log_namespace' => $title->getNamespace(), 'log_title' => $title->getDBkey(), ] )
			->caller( __METHOD__ )->fetchField() );
	}

	public function testDeleteWithoutTagPermission() {
		$this->expectApiErrorCode( 'tags-apply-no-permission' );

		$title = Title::makeTitle( NS_HELP, 'TestDeleteWithoutTagPermission' );

		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'custom tag' );
		$this->overrideConfigValue(
			MainConfigNames::RevokePermissions,
			[ 'user' => [ 'applychangetags' => true ] ]
		);

		$this->editPage( $title, 'Some text' );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'delete',
				'title' => $title->getPrefixedText(),
				'tags' => 'custom tag',
			] );
		} finally {
			$this->assertTrue( $title->exists( IDBAccessObject::READ_LATEST ) );
		}
	}

	public function testDeleteAbortedByHook() {
		$this->expectApiErrorCode( 'delete-hook-aborted' );

		$title = Title::makeTitle( NS_HELP, 'TestDeleteAbortedByHook' );

		$this->editPage( $title, 'Some text' );

		$this->setTemporaryHook( 'ArticleDelete',
			static function () {
				return false;
			}
		);

		try {
			$this->doApiRequestWithToken( [ 'action' => 'delete', 'title' => $title->getPrefixedText() ] );
		} finally {
			$this->assertTrue( $title->exists( IDBAccessObject::READ_LATEST ) );
		}
	}

	public function testDeleteWatch() {
		$title = Title::makeTitle( NS_HELP, 'TestDeleteWatch' );
		$page = $this->getExistingTestPage( $title );
		$performer = $this->getTestSysop()->getUser();
		$watchlistManager = $this->getServiceContainer()->getWatchlistManager();

		$this->assertFalse( $watchlistManager->isWatched( $performer, $page ) );

		$res = $this->doApiRequestWithToken(
			[
				'action' => 'delete',
				'title' => $title->getPrefixedText(),
				'watch' => '',
				'watchlistexpiry' => '99990123000000',
			],
			null,
			$performer
		);
		$this->assertArrayHasKey( 'delete', $res[0] );
		$page->clear();

		$this->assertFalse( $page->exists() );
		$this->assertTrue( $watchlistManager->isWatched( $performer, $page ) );
		$this->assertTrue( $watchlistManager->isTempWatched( $performer, $page ) );
	}

	public function testDeleteUnwatch() {
		$title = Title::makeTitle( NS_HELP, 'TestDeleteUnwatch' );
		$user = $this->getTestSysop()->getUser();

		$this->editPage( $title, 'Some text' );
		$this->assertTrue( $title->exists( IDBAccessObject::READ_LATEST ) );
		$watchlistManager = $this->getServiceContainer()->getWatchlistManager();
		$watchlistManager->addWatch( $user, $title );
		$this->assertTrue( $watchlistManager->isWatched( $user, $title ) );

		$this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $title->getPrefixedText(),
			'watchlist' => 'unwatch',
		] );

		$this->assertFalse( $title->exists( IDBAccessObject::READ_LATEST ) );
		$this->assertFalse( $watchlistManager->isWatched( $user, $title ) );
	}
}

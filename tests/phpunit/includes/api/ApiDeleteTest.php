<?php

/**
 * Tests for MediaWiki api.php?action=delete.
 *
 * @author Yifei He
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiDelete
 */
class ApiDeleteTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'change_tag', 'change_tag_def', 'logging', 'watchlist', 'watchlist_expiry' ]
		);

		$this->setMwGlobals( [
			'wgWatchlistExpiry' => true,
		] );
	}

	public function testDelete() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		// create new page
		$this->editPage( $name, 'Some text' );

		// test deletion
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $name,
		] )[0];

		$this->assertArrayHasKey( 'delete', $apiResult );
		$this->assertArrayHasKey( 'title', $apiResult['delete'] );
		$this->assertSame( $name, $apiResult['delete']['title'] );
		$this->assertArrayHasKey( 'logid', $apiResult['delete'] );

		$this->assertFalse( Title::newFromText( $name )->exists() );
	}

	public function testBatchedDelete() {
		$this->setMwGlobals( 'wgDeleteRevisionsBatchSize', 1 );

		$name = 'Help:' . ucfirst( __FUNCTION__ );
		for ( $i = 1; $i <= 3; $i++ ) {
			$this->editPage( $name, "Revision $i" );
		}

		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $name,
		] )[0];

		$this->assertArrayHasKey( 'delete', $apiResult );
		$this->assertArrayHasKey( 'title', $apiResult['delete'] );
		$this->assertSame( $name, $apiResult['delete']['title'] );
		$this->assertArrayHasKey( 'scheduled', $apiResult['delete'] );
		$this->assertTrue( $apiResult['delete']['scheduled'] );
		$this->assertArrayNotHasKey( 'logid', $apiResult['delete'] );

		// Run the jobs
		$this->runJobs();

		$this->assertFalse( Title::newFromText( $name )->exists( Title::READ_LATEST ) );
	}

	public function testDeleteNonexistent() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( "The page you specified doesn't exist." );

		$this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => 'This page deliberately left nonexistent',
		] );
	}

	public function testDeleteAssociatedTalkPage() {
		$title = 'Help:' . ucfirst( __FUNCTION__ );
		$talkPage = 'Help_talk:' . ucfirst( __FUNCTION__ );
		$this->editPage( $title, 'Some text' );
		$this->editPage( $talkPage, 'Some text' );
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $title,
			'deletetalk' => true,
		] )[0];

		$this->assertSame( $title, $apiResult['delete']['title'] );
		$this->assertFalse( Title::newFromText( $talkPage )->exists( Title::READ_LATEST ) );
	}

	public function testDeleteAssociatedTalkPageNonexistent() {
		$title = 'Help:' . ucfirst( __FUNCTION__ );
		$this->editPage( $title, 'Some text' );
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $title,
			'deletetalk' => true,
		] )[0];

		$this->assertSame( $title, $apiResult['delete']['title'] );
		$this->assertArrayHasKey( 'warnings', $apiResult );
	}

	public function testDeletionWithoutPermission() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage(
			// Two error messages are possible depending on the number of groups in the wiki with deletion rights:
			// - The action you have requested is limited to users in the group:
			// - The action you have requested is limited to users in one of the groups:
			'The action you have requested is limited to users in'
		);

		$name = 'Help:' . ucfirst( __FUNCTION__ );

		// create new page
		$this->editPage( $name, 'Some text' );

		// test deletion without permission
		try {
			$user = new User();
			$apiResult = $this->doApiRequest( [
				'action' => 'delete',
				'title' => $name,
				'token' => $user->getEditToken(),
			], null, null, $user );
		} finally {
			$this->assertTrue( Title::newFromText( $name )->exists() );
		}
	}

	public function testDeleteWithTag() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		ChangeTags::defineTag( 'custom tag' );

		$this->editPage( $name, 'Some text' );

		$this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $name,
			'tags' => 'custom tag',
		] );

		$this->assertFalse( Title::newFromText( $name )->exists() );

		$dbw = wfGetDB( DB_PRIMARY );
		$this->assertSame( 'custom tag', $dbw->selectField(
			[ 'change_tag', 'logging', 'change_tag_def' ],
			'ctd_name',
			[
				'log_namespace' => NS_HELP,
				'log_title' => ucfirst( __FUNCTION__ ),
			],
			__METHOD__,
			[],
			[
				'change_tag' => [ 'JOIN', 'ct_log_id = log_id' ],
				'change_tag_def' => [ 'JOIN', 'ctd_id = ct_tag_id' ]
			]
		) );
	}

	public function testDeleteWithoutTagPermission() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage(
			'You do not have permission to apply change tags along with your changes.'
		);

		$name = 'Help:' . ucfirst( __FUNCTION__ );

		ChangeTags::defineTag( 'custom tag' );
		$this->setMwGlobals( 'wgRevokePermissions',
			[ 'user' => [ 'applychangetags' => true ] ] );

		$this->editPage( $name, 'Some text' );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'delete',
				'title' => $name,
				'tags' => 'custom tag',
			] );
		} finally {
			$this->assertTrue( Title::newFromText( $name )->exists() );
		}
	}

	public function testDeleteAbortedByHook() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( 'Deletion aborted by hook. It gave no explanation.' );

		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->editPage( $name, 'Some text' );

		$this->setTemporaryHook( 'ArticleDelete',
			static function () {
				return false;
			}
		);

		try {
			$this->doApiRequestWithToken( [ 'action' => 'delete', 'title' => $name ] );
		} finally {
			$this->assertTrue( Title::newFromText( $name )->exists() );
		}
	}

	public function testDeleteWatch() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );
		$user = self::$users['sysop']->getUser();
		$watchlistManager = $this->getServiceContainer()->getWatchlistManager();

		$this->editPage( $name, 'Some text' );
		$this->assertTrue( Title::newFromText( $name )->exists() );
		$this->assertFalse( $watchlistManager->isWatched( $user, Title::newFromText( $name ) ) );

		$this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $name,
			'watch' => '',
			'watchlistexpiry' => '99990123000000',
		] );

		$title = Title::newFromText( $name );
		$this->assertFalse( $title->exists() );
		$this->assertTrue( $watchlistManager->isWatched( $user, $title ) );
		$this->assertTrue( $watchlistManager->isTempWatched( $user, $title ) );
	}

	public function testDeleteUnwatch() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );
		$user = $this->getTestSysop()->getUser();

		$this->editPage( $name, 'Some text' );
		$this->assertTrue( Title::newFromText( $name )->exists() );
		$watchlistManager = $this->getServiceContainer()->getWatchlistManager();
		$watchlistManager->addWatch( $user,  Title::newFromText( $name ) );
		$this->assertTrue( $watchlistManager->isWatched( $user, Title::newFromText( $name ) ) );

		$this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $name,
			'watchlist' => 'unwatch',
		] );

		$this->assertFalse( Title::newFromText( $name )->exists() );
		$this->assertFalse( $watchlistManager->isWatched( $user, Title::newFromText( $name ) ) );
	}
}

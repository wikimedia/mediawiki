<?php

/**
 * Tests for Rollback API.
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiRollback
 */
class ApiRollbackTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'watchlist', 'watchlist_expiry' ]
		);

		$this->setMwGlobals( [
			'wgWatchlistExpiry' => true,
		] );
	}

	public function testProtectWithWatch(): void {
		$name = ucfirst( __FUNCTION__ );
		$title = Title::newFromText( $name );
		$revisionStore = $this->getServiceContainer()->getRevisionStore();

		$user = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		// Create page as sysop.
		$this->editPage( $name, 'Some text', '', NS_MAIN, $sysop );

		// Edit as non-sysop.
		$this->editPage( $name, 'Vandalism', '', NS_MAIN, $user );

		// Rollback as sysop.
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'rollback',
			'title' => $name,
			'user' => $user->getName(),
			'watchlist' => 'watch',
			'watchlistexpiry' => '99990123000000',
		] )[0];

		// Content of latest revision should match the initial.
		$latestRev = $revisionStore->getRevisionByTitle( $title );
		$initialRev = $revisionStore->getFirstRevision( $title );
		$this->assertTrue( $latestRev->hasSameContent( $initialRev ) );
		// ...but have different rev IDs.
		$this->assertNotSame( $latestRev->getId(), $initialRev->getId() );

		// Make sure the API response looks good.
		$this->assertArrayHasKey( 'rollback', $apiResult );
		$this->assertSame( $name, $apiResult['rollback']['title'] );

		// And that the page was temporarily watched.
		$this->assertTrue( $this->getServiceContainer()->getWatchlistManager()->isTempWatched( $sysop, $title ) );

		$recentChange = $revisionStore->getRecentChange( $latestRev );
		$this->assertSame( '0', $recentChange->getAttribute( 'rc_bot' ) );
		$this->assertSame( $sysop->getName(), $recentChange->getAttribute( 'rc_user_text' ) );
	}

	public function testRollbackMarkAsBot() {
		$revisionStore = $this->getServiceContainer()->getRevisionStore();
		$title = Title::newFromText( __METHOD__ );

		$user = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		// Create page as sysop.
		$this->editPage( __METHOD__, 'Some text', '', NS_MAIN, $sysop );

		// Edit as non-sysop.
		$this->editPage( __METHOD__, 'Vandalism', '', NS_MAIN, $user );

		// Rollback as sysop.
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'rollback',
			'title' => __METHOD__,
			'user' => $user->getName(),
			'markbot' => true
		] )[0];
		// Make sure the API response looks good.
		$this->assertArrayHasKey( 'rollback', $apiResult );
		$this->assertSame( __METHOD__, $apiResult['rollback']['title'] );

		$recentChange = $revisionStore->getRecentChange( $revisionStore->getRevisionByTitle( $title ) );
		$this->assertSame( '1', $recentChange->getAttribute( 'rc_bot' ) );
	}

	public function testRollbackNoToken() {
		$user = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		// Create page as sysop.
		$this->editPage( __METHOD__, 'Some text', '', NS_MAIN, $sysop );

		// Edit as non-sysop.
		$this->editPage( __METHOD__, 'Vandalism', '', NS_MAIN, $user );

		$this->expectException( ApiUsageException::class );
		$this->doApiRequest( [
			'action' => 'rollback',
			'title' => __METHOD__,
			'user' => $user->getName(),
		] )[0];
	}
}

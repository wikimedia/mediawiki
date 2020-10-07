<?php

use MediaWiki\MediaWikiServices;

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

	/**
	 * @covers ApiRollback::execute()
	 */
	public function testProtectWithWatch(): void {
		$name = ucfirst( __FUNCTION__ );
		$title = Title::newFromText( $name );
		$revLookup = MediaWikiServices::getInstance()
			->getRevisionLookup();

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
		$latestRev = $revLookup->getRevisionByTitle( $title );
		$initialRev = $revLookup->getFirstRevision( $title );
		$this->assertTrue( $latestRev->hasSameContent( $initialRev ) );
		// ...but have different rev IDs.
		$this->assertNotSame( $latestRev->getId(), $initialRev->getId() );

		// Make sure the API response looks good.
		$this->assertArrayHasKey( 'rollback', $apiResult );
		$this->assertSame( $name, $apiResult['rollback']['title'] );

		// And that the page was temporarily watched.
		$this->assertTrue( $sysop->isTempWatched( $title ) );
	}
}

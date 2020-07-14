<?php

/**
 * Tests for Undelete API.
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiUndelete
 */
class ApiUndeleteTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'logging', 'watchlist', 'watchlist_expiry' ]
		);

		$this->setMwGlobals( [
			'wgWatchlistExpiry' => true,
		] );
	}

	/**
	 * @covers ApiUndelete::execute()
	 */
	public function testUndeleteWithWatch(): void {
		$name = ucfirst( __FUNCTION__ );
		$title = Title::newFromText( $name );
		$sysop = $this->getTestSysop()->getUser();

		// Create page.
		$this->editPage( $name, 'Test' );

		// Delete page.
		$this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $name,
		] );

		// For good measure.
		$this->assertFalse( $title->exists() );
		$this->assertFalse( $sysop->isWatched( $title ) );

		// Restore page, and watch with expiry.
		$this->doApiRequestWithToken( [
			'action' => 'undelete',
			'title' => $name,
			'watchlist' => 'watch',
			'watchlistexpiry' => '99990123000000',
		] );

		$this->assertTrue( $title->exists() );
		$this->assertTrue( $sysop->isTempWatched( $title ) );
	}
}

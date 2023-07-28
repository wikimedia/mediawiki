<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;

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

		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
	}

	/**
	 * @covers ApiUndelete::execute()
	 */
	public function testUndeleteWithWatch(): void {
		$title = Title::makeTitle( NS_MAIN, 'TestUndeleteWithWatch' );
		$sysop = $this->getTestSysop()->getUser();
		$watchlistManager = $this->getServiceContainer()->getWatchlistManager();

		// Create page.
		$this->editPage( $title, 'Test', '', NS_MAIN, $sysop );

		// Delete page.
		$this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $title->getPrefixedText(),
		] );

		// For good measure.
		$this->assertFalse( $title->exists( Title::READ_LATEST ) );
		$this->assertFalse( $watchlistManager->isWatched( $sysop, $title ) );

		// Restore page, and watch with expiry.
		$this->doApiRequestWithToken( [
			'action' => 'undelete',
			'title' => $title->getPrefixedText(),
			'watchlist' => 'watch',
			'watchlistexpiry' => '99990123000000',
		] );

		$this->assertTrue( $title->exists( Title::READ_LATEST ) );
		$this->assertTrue( $watchlistManager->isTempWatched( $sysop, $title ) );
	}
}

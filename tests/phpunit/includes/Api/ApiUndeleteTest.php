<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Tests for Undelete API.
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiUndelete
 */
class ApiUndeleteTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
	}

	/**
	 * @covers \MediaWiki\Api\ApiUndelete::execute()
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
		$this->assertFalse( $title->exists( IDBAccessObject::READ_LATEST ) );
		$this->assertFalse( $watchlistManager->isWatched( $sysop, $title ) );

		// Restore page, and watch with expiry.
		$this->doApiRequestWithToken( [
			'action' => 'undelete',
			'title' => $title->getPrefixedText(),
			'watchlist' => 'watch',
			'watchlistexpiry' => '99990123000000',
		] );

		$this->assertTrue( $title->exists( IDBAccessObject::READ_LATEST ) );
		$this->assertTrue( $watchlistManager->isTempWatched( $sysop, $title ) );
	}
}

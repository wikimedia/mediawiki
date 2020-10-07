<?php

/**
 * Tests for protect API.
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiProtect
 */
class ApiProtectTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'page_restrictions', 'logging', 'watchlist', 'watchlist_expiry' ]
		);

		$this->setMwGlobals( [
			'wgWatchlistExpiry' => true,
		] );
	}

	/**
	 * @covers ApiProtect::execute()
	 */
	public function testProtectWithWatch(): void {
		$name = ucfirst( __FUNCTION__ );
		$title = Title::newFromText( $name );

		$this->editPage( $name, 'Some text' );

		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'protect',
			'title' => $name,
			'protections' => 'edit=sysop',
			'expiry' => '55550123000000',
			'watchlist' => 'watch',
			'watchlistexpiry' => '99990123000000',
		] )[0];

		$this->assertArrayHasKey( 'protect', $apiResult );
		$this->assertSame( $name, $apiResult['protect']['title'] );
		$this->assertTrue( $title->isProtected( 'edit' ) );
		$this->assertTrue( $this->getTestSysop()->getUser()->isTempWatched( $title ) );
	}
}

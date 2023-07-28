<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;

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

		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
	}

	/**
	 * @covers ApiProtect::execute()
	 */
	public function testProtectWithWatch(): void {
		$title = Title::makeTitle( NS_MAIN, 'TestProtectWithWatch' );

		$this->editPage( $title, 'Some text' );

		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'protect',
			'title' => $title->getPrefixedText(),
			'protections' => 'edit=sysop',
			'expiry' => '55550123000000',
			'watchlist' => 'watch',
			'watchlistexpiry' => '99990123000000',
		] )[0];

		$this->assertArrayHasKey( 'protect', $apiResult );
		$this->assertSame( $title->getPrefixedText(), $apiResult['protect']['title'] );
		$this->assertTrue( $this->getServiceContainer()->getRestrictionStore()->isProtected( $title, 'edit' ) );
		$this->assertTrue( $this->getServiceContainer()->getWatchlistManager()->isTempWatched(
			$this->getTestSysop()->getUser(),
			$title
		) );
	}
}

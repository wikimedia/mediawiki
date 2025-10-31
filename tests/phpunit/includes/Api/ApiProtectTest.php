<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;

/**
 * Tests for protect API.
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiProtect
 */
class ApiProtectTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
	}

	/**
	 * @covers \MediaWiki\Api\ApiProtect::execute()
	 */
	public function testWithInvalidExpiry(): void {
		$title = Title::makeTitle( NS_MAIN, 'TestProtectWithInvalidExpiry' );
		$this->editPage( $title, 'Some text' );
		$this->expectApiErrorCode( 'pastexpiry' );
		$this->doApiRequestWithToken( [
			'action' => 'protect',
			'title' => $title->getPrefixedText(),
			'protections' => 'edit=sysop',
			'expiry' => '11110123000000',
		] );
	}

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

<?php

/**
 * @covers MediaWikiServices
 *
 * @group MediaWiki
 */
class MediaWikiServicesTest extends MediaWikiTestCase {

	/**
	 * @param string $expectedType expected class
	 * @param string $getter method name
	 */
	private function assertGetterReturnType( $expectedType, $getter ) {
		$locator = MediaWikiServices::getInstance();
		$service = $locator->$getter();
		$this->assertInstanceOf( $expectedType, $service );
	}

	public function testGetInstance() {
		$locator = MediaWikiServices::getInstance();
		$this->assertInstanceOf( 'MediaWikiServices', $locator );
	}

	public function testGetConfig() {
		$this->assertGetterReturnType( 'Config', 'getConfig' );
	}

	public function testGetSiteStore() {
		$this->assertGetterReturnType( 'SiteStore', 'getSiteStore' );
	}

	public function testGetSiteLookup() {
		$this->assertGetterReturnType( 'SiteLookup', 'getSiteLookup' );
	}

	public function testGetGenderCache() {
		$this->assertGetterReturnType( 'GenderCache', 'getGenderCache' );
	}

	public function testGetTitleFormatter() {
		$this->assertGetterReturnType( 'TitleFormatter', 'getTitleFormatter' );
	}

	/**
	 * Test that the TitleFormatter instance gets automatically reset when affected by
	 * config changes, but is re-used otherwise.
	 */
	public function testGetTitleFormatter_reset() {
		global $wgContLang;
		$this->stashMwGlobals( 'wgContLang' );

		$locator = MediaWikiServices::getInstance();
		$originalFormatter = $locator->getTitleFormatter();

		$this->assertSame(
			$originalFormatter,
			$locator->getTitleFormatter(),
			'service instance should be cached'
		);

		$wgContLang = Language::factory( 'qqxyz' );

		$this->assertNotSame(
			$originalFormatter,
			$locator->getTitleFormatter(),
			'service instance should be reset when $wgLanguageCode changes'
		);
	}

	public function testGetTitleParser() {
		$this->assertGetterReturnType( 'TitleParser', 'getTitleParser' );
	}

	public function testGetPageLinkRenderer() {
		$this->assertGetterReturnType( 'PageLinkRenderer', 'getPageLinkRenderer' );
	}

}

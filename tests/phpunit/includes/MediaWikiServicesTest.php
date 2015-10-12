<?php

/**
 * @covers MediaWikiServices
 *
 * @group MediaWiki
 */
class MediaWikiServicesTest extends PHPUnit_Framework_TestCase {

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

}

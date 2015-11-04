<?php
use MediaWiki\MediaWikiServices;

/**
 * @covers MediaWiki\MediaWikiServices
 *
 * @group MediaWiki
 */
class MediaWikiServicesTest extends PHPUnit_Framework_TestCase {

	private function getMediaWikiServices() {
		return new MediaWikiServices( new HashConfig() );
	}

	public function testGetInstance() {
		$locator = $this->getMediaWikiServices();
		$this->assertInstanceOf( 'MediaWiki\\MediaWikiServices', $locator );
	}

	public function testGetConfig() {
		$locator = $this->getMediaWikiServices();
		$service = $locator->getConfig();
		$this->assertInstanceOf( 'Config', $service );
	}

	public function provideGetters() {
		return array(
			'Config' => array( 'Config', 'Config' ),
			'SiteStore' => array( 'SiteStore', 'SiteStore' ),
			'SiteLookup' => array( 'SiteLookup', 'SiteLookup' ),
		);
	}

	/**
	 * @dataProvider provideGetters
	 */
	public function testGetters( $name, $type ) {
		$getter = "get$name";

		$locator = $this->getMediaWikiServices();
		$service = $locator->$getter();
		$this->assertInstanceOf( $type, $service );
	}

	public function provideGetService() {
		return array(
			'SiteStore' => array( 'SiteStore', 'SiteStore' ),
		);
	}

	/**
	 * @dataProvider provideGetService
	 */
	public function testGetService( $name, $type ) {
		$locator = $this->getMediaWikiServices();
		$service = $locator->getService( $name );
		$this->assertInstanceOf( $type, $service );
	}

	public function testDefineService() {
		$locator = $this->getMediaWikiServices();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$locator->defineService( $name, function( $actualLocator ) use ( $locator, $theService ) {
			PHPUnit_Framework_Assert::assertSame( $locator, $actualLocator );
			return $theService;
		} );

		$this->assertSame( $theService, $locator->getService( $name ) );
	}

	public function testDefineService_fail_duplicate() {
		$locator = $this->getMediaWikiServices();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$locator->defineService( $name, function() use ( $theService ) {
			return $theService;
		} );

		$this->setExpectedException( 'RuntimeException' );

		$locator->defineService( $name, function() use ( $theService ) {
			return $theService;
		} );
	}

	public function testDefineService_fail_builtin() {
		$locator = $this->getMediaWikiServices();

		$theService = new stdClass();

		$this->setExpectedException( 'RuntimeException' );

		$locator->defineService( 'SiteStore', function() use ( $theService ) {
			return $theService;
		} );
	}

	public function testReplaceService() {
		$locator = $this->getMediaWikiServices();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$locator->defineService( $name, function() {
			return new stdClass();
		} );

		$locator->replaceService( $name, function( $actualLocator ) use ( $locator, $theService ) {
			PHPUnit_Framework_Assert::assertSame( $locator, $actualLocator );
			return $theService;
		} );

		$this->assertSame( $theService, $locator->getService( $name ) );
	}

	public function testReplaceService_builtin() {
		$locator = $this->getMediaWikiServices();

		$theService = new stdClass();

		$locator->replaceService( 'SiteStore', function() use ( $theService ) {
			return $theService;
		} );

		$this->assertSame( $theService, $locator->getService( 'SiteStore' ) );
	}

	public function testReplaceService_fail_undefined() {
		$locator = $this->getMediaWikiServices();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$this->setExpectedException( 'RuntimeException' );

		$locator->replaceService( $name, function() use ( $theService ) {
			return $theService;
		} );
	}

	public function testModifyService() {
		$locator = $this->getMediaWikiServices();

		$serviceOne = new stdClass();
		$serviceTwo = new stdClass();
		$serviceThree = new stdClass();
		$name = 'TestService92834576';

		$locator->defineService( $name, function() use ( $serviceOne ) {
			return $serviceOne;
		} );

		$locator->modifyService( $name, function( $actualService, $actualLocator ) use ( $serviceOne, $serviceTwo, $locator ) {
			PHPUnit_Framework_Assert::assertSame( $locator, $actualLocator );
			PHPUnit_Framework_Assert::assertSame( $serviceOne, $actualService );
			return $serviceTwo;
		} );

		$this->assertSame( $serviceTwo, $locator->getService( $name ) );

		$locator->modifyService( $name, function( $actualService, $actualLocator ) use ( $serviceTwo, $serviceThree, $locator ) {
			PHPUnit_Framework_Assert::assertSame( $locator, $actualLocator );
			PHPUnit_Framework_Assert::assertSame( $serviceTwo, $actualService );
			return $serviceThree;
		} );

		$this->assertSame( $serviceThree, $locator->getService( $name ) );
	}

	public function testModifyService_fail_undefined() {
		$locator = $this->getMediaWikiServices();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$this->setExpectedException( 'RuntimeException' );

		$locator->modifyService( $name, function() use ( $theService ) {
			return $theService;
		} );
	}

	public function testModifyService_builtin() {
		$locator = $this->getMediaWikiServices();

		$serviceTwo = new stdClass();

		$locator->modifyService( 'SiteStore', function( $actualService, $actualLocator ) use ( $serviceTwo, $locator ) {
			PHPUnit_Framework_Assert::assertSame( $locator, $actualLocator );
			PHPUnit_Framework_Assert::assertInternalType( 'object', $actualService );
			return $serviceTwo;
		} );

		$this->assertSame( $serviceTwo, $locator->getService( 'SiteStore'  ) );
	}

}

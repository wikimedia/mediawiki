<?php
use MediaWiki\MediaWikiServices;

/**
 * @covers MediaWiki\MediaWikiServices
 *
 * @group MediaWiki
 */
class MediaWikiServicesTest extends PHPUnit_Framework_TestCase {

	private function newMediaWikiServices() {
		return new MediaWikiServices( new HashConfig() );
	}

	public function testGetInstance() {
		$services = MediaWikiServices::getInstance();
		$this->assertInstanceOf( 'MediaWiki\\MediaWikiServices', $services );
	}

	public function provideGetters() {
		// NOTE: This should list all service getters defined in MediaWikiServices.
		// NOTE: For every test case defined here there should be a corresponding
		// test case defined in provideGetService().
		return array(
			'BootstrapConfig' => array( 'getBootstrapConfig', 'Config' ),
			'ConfigFactory' => array( 'getConfigFactory', 'ConfigFactory' ),
			'MainConfig' => array( 'getMainConfig', 'Config' ),
			'SiteStore' => array( 'getSiteStore', 'SiteStore' ),
			'SiteLookup' => array( 'getSiteLookup', 'SiteLookup' ),
			'DBLoadBalancerFactory' => array( 'getDBLoadBalancerFactory', 'LBFactory' ),
			'DBLoadBalancer' => array( 'getDBLoadBalancer', 'LoadBalancer' ),
		);
	}

	/**
	 * @dataProvider provideGetters
	 */
	public function testGetters( $getter, $type ) {
		// Test against the default instance, since the dummy will not know the default services.
		$services = MediaWikiServices::getInstance();
		$service = $services->$getter();
		$this->assertInstanceOf( $type, $service );
	}

	public function testGetServiceNames() {
		$services = $this->newMediaWikiServices();
		$names = $services->getServiceNames();

		$this->assertInternalType( 'array', $names );
		$this->assertContains( 'BootstrapConfig', $names );
	}

	public function testHasService() {
		$services = $this->newMediaWikiServices();

		$name = 'TestService92834576';
		$this->assertFalse( $services->hasService( $name ) );

		$services->defineService( $name, function() {
			return null;
		} );

		$this->assertTrue( $services->hasService( $name ) );
	}

	public function provideGetService() {
		// NOTE: This should list all service getters defined in ServiceWiring.php.
		// NOTE: For every test case defined here there should be a corresponding
		// test case defined in provideGetters().
		return array(
			'BootstrapConfig' => array( 'BootstrapConfig', 'Config' ),
			'ConfigFactory' => array( 'ConfigFactory', 'ConfigFactory' ),
			'MainConfig' => array( 'MainConfig', 'Config' ),
			'SiteStore' => array( 'SiteStore', 'SiteStore' ),
			'SiteLookup' => array( 'SiteLookup', 'SiteLookup' ),
			'DBLoadBalancerFactory' => array( 'DBLoadBalancerFactory', 'LBFactory' ),
			'DBLoadBalancer' => array( 'DBLoadBalancer', 'LoadBalancer' ),
		);
	}

	/**
	 * @dataProvider provideGetService
	 */
	public function testGetService( $name, $type ) {
		// Test against the default instance, since the dummy will not know the default services.
		$services = MediaWikiServices::getInstance();

		$service = $services->getService( $name );
		$this->assertInstanceOf( $type, $service );
	}

	public function testDefineService() {
		$services = $this->newMediaWikiServices();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$services->defineService( $name, function( $actualLocator ) use ( $services, $theService ) {
			PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
			return $theService;
		} );

		$this->assertTrue( $services->hasService( $name ) );
		$this->assertSame( $theService, $services->getService( $name ) );
	}

	public function testDefineService_fail_duplicate() {
		$services = $this->newMediaWikiServices();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$services->defineService( $name, function() use ( $theService ) {
			return $theService;
		} );

		$this->setExpectedException( 'RuntimeException' );

		$services->defineService( $name, function() use ( $theService ) {
			return $theService;
		} );
	}

	public function testReplaceService() {
		$services = $this->newMediaWikiServices();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$services->defineService( $name, function() {
			return new stdClass();
		} );

		$services->replaceService( $name, function( $actualLocator ) use ( $services, $theService ) {
			PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
			return $theService;
		} );

		$this->assertSame( $theService, $services->getService( $name ) );
	}

	public function testReplaceService_fail_undefined() {
		$services = $this->newMediaWikiServices();

		$theService = new stdClass();
		$name = 'TestService92834576';

		$this->setExpectedException( 'RuntimeException' );

		$services->replaceService( $name, function() use ( $theService ) {
			return $theService;
		} );
	}

	public function testDefaultServiceInstantiation() {
		// Check all services in the default instance, not a dummy instance!
		// Note that we instantiate all services here, including any that
		// were registered by extensions.
		$services = MediaWikiServices::getInstance();
		$names = $services->getServiceNames();

		foreach ( $names as $name ) {
			$this->assertTrue( $services->hasService( $name ) );
			$service = $services->getService( $name );
			$this->assertInternalType( 'object', $service );
		}
	}

}

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

		$theService1 = new stdClass();
		$theService2 = new stdClass();
		$name = 'TestService92834576';

		$services->defineService( $name, function() {
			PHPUnit_Framework_Assert::fail( 'The original constructor callback should not get called' );
		} );

		// replace before instantiation
		$services->replaceService(
			$name,
			function( $actualLocator ) use ( $services, $theService1 ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				return $theService1;
			},
			function ( $instance ) {
				PHPUnit_Framework_Assert::fail( 'Cleanup callback should not be called if the services wasn\'t yet instantiated' );
			}
		);

		// force instantiation, check result
		$this->assertSame( $theService1, $services->getService( $name ) );

		// replace after instantiation
		$cleanedUpInstance = null;
		$services->replaceService(
			$name,
			function( $actualLocator ) use ( $services, $theService2 ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				return $theService2;
			},
			function ( $instance, $actualLocator ) use ( $services, &$cleanedUpInstance ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				$cleanedUpInstance = $instance;
			}
		);

		// check cleanup
		$this->assertSame(
			$theService1,
			$cleanedUpInstance,
			'Cleanup callback should have been called for previous service instance'
		);

		// check instantiation
		$this->assertSame( $theService2, $services->getService( $name ) );

		// replace without cleanup
		$services->replaceService(
			$name,
			function( $actualLocator ) use ( $services, $theService1 ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				return $theService1;
			}
		);

		// check instantiation
		$this->assertSame( $theService1, $services->getService( $name ) );
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

	public function testResetService() {
		$services = $this->newMediaWikiServices();

		$name = 'TestService92834576';
		$counter = 0;

		$services->defineService( $name, function() use ( &$counter ) {
			$obj = new stdClass();
			$obj->number = ++$counter;
			return $obj;
		} );

		// reset before instantiation
		$services->resetService(
			$name,
			function ( $instance ) {
				PHPUnit_Framework_Assert::fail( 'Cleanup callback should not be called if the services wasn\'t yet instantiated' );
			}
		);

		// force instantiation, check result
		$service1 = $services->getService( $name );
		$this->assertSame( 1, $service1->number, 'First service instance' );

		// reset after instantiation
		$cleanedUpInstance = null;
		$services->resetService(
			$name,
			function ( $instance, $actualLocator ) use ( $services, &$cleanedUpInstance ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				$cleanedUpInstance = $instance;
			}
		);

		// check cleanup
		$this->assertSame(
			$service1,
			$cleanedUpInstance,
			'Cleanup callback should have been called for previous service instance'
		);

		// check instantiation
		$service2 = $services->getService( $name );
		$this->assertNotSame( $service1, $service2, 'New service instance expected after reset' );
		$this->assertSame( 2, $service2->number, 'New service instance expected after reset' );

		// reset without cleanup
		$services->resetService(
			$name
		);

		// check instantiation
		$service3 = $services->getService( $name );
		$this->assertNotSame( $service2, $service3, 'New service instance expected after reset' );
		$this->assertSame( 3, $service3->number, 'New service instance expected after reset' );
	}

	public function testResetService_fail_undefined() {
		$services = $this->newMediaWikiServices();

		$name = 'TestService92834576';

		$this->setExpectedException( 'RuntimeException' );

		$services->resetService( $name );
	}

	public function testModifyService() {
		$services = $this->newMediaWikiServices();

		$serviceOne = new stdClass();
		$serviceTwo = new stdClass();
		$serviceThree = new stdClass();
		$name = 'TestService92834576';
 
		$services->defineService( $name, function() use ( $serviceOne ) {
			return $serviceOne;
		} );

		$services->wrapService( $name,
			function( $actualService, $actualLocator )
			use ( $serviceOne, $serviceTwo, $services ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				PHPUnit_Framework_Assert::assertSame( $serviceOne, $actualService );

				return $serviceTwo;
			}
		);

		$this->assertSame( $serviceTwo, $services->getService( $name ) );

		$services->wrapService( $name,
			function( $actualService, $actualLocator )
			use ( $serviceTwo, $serviceThree, $services ) {
				PHPUnit_Framework_Assert::assertSame( $services, $actualLocator );
				PHPUnit_Framework_Assert::assertSame( $serviceTwo, $actualService );
				return $serviceThree;
			}
		);

		$this->assertSame( $serviceThree, $services->getService( $name ) );
	}

	public function testModifyService_fail_undefined() {
		$services = $this->newMediaWikiServices();
		
		$theService = new stdClass();
		$name = 'TestService92834576';

		$this->setExpectedException( 'RuntimeException' );

		$services->wrapService( $name, function() use ( $theService ) {
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

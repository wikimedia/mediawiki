<?php
use Liuggio\StatsdClient\Factory\StatsdDataFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Services\ServiceDisabledException;

/**
 * @covers MediaWiki\MediaWikiServices
 *
 * @group MediaWiki
 */
class MediaWikiServicesTest extends PHPUnit_Framework_TestCase {

	/**
	 * @return Config
	 */
	private function newTestConfig() {
		$globalConfig = new GlobalVarConfig();

		$testConfig = new HashConfig();
		$testConfig->set( 'ServiceWiringFiles', $globalConfig->get( 'ServiceWiringFiles' ) );
		$testConfig->set( 'ConfigRegistry', $globalConfig->get( 'ConfigRegistry' ) );

		return $testConfig;
	}

	/**
	 * @return MediaWikiServices
	 */
	private function newMediaWikiServices( Config $config = null ) {
		if ( $config === null ) {
			$config = $this->newTestConfig();
		}

		$instance = new MediaWikiServices( $config );

		// Load the default wiring from the specified files.
		$wiringFiles = $config->get( 'ServiceWiringFiles' );
		$instance->loadWiringFiles( $wiringFiles );

		return $instance;
	}

	public function testGetInstance() {
		$services = MediaWikiServices::getInstance();
		$this->assertInstanceOf( 'MediaWiki\\MediaWikiServices', $services );
	}

	public function testForceGlobalInstance() {
		$newServices = $this->newMediaWikiServices();
		$oldServices = MediaWikiServices::forceGlobalInstance( $newServices );

		$this->assertInstanceOf( 'MediaWiki\\MediaWikiServices', $oldServices );
		$this->assertNotSame( $oldServices, $newServices );

		$theServices = MediaWikiServices::getInstance();
		$this->assertSame( $theServices, $newServices );

		MediaWikiServices::forceGlobalInstance( $oldServices );

		$theServices = MediaWikiServices::getInstance();
		$this->assertSame( $theServices, $oldServices );
	}

	public function testResetGlobalInstance() {
		$newServices = $this->newMediaWikiServices();
		$oldServices = MediaWikiServices::forceGlobalInstance( $newServices );

		MediaWikiServices::resetGlobalInstance( $this->newTestConfig() );
		$theServices = MediaWikiServices::getInstance();

		$this->assertNotSame( $theServices, $newServices );
		$this->assertNotSame( $theServices, $oldServices );

		MediaWikiServices::forceGlobalInstance( $oldServices );
	}

	public function testDisableStorageBackend() {
		$newServices = $this->newMediaWikiServices();
		$oldServices = MediaWikiServices::forceGlobalInstance( $newServices );

		$lbFactory = $this->getMockBuilder( 'LBFactorySimple' )
			->disableOriginalConstructor()
			->getMock();

		$lbFactory->expects( $this->once() )
			->method( 'destroy' );

		$newServices->redefineService(
			'DBLoadBalancerFactory',
			function() use ( $lbFactory ) {
				return $lbFactory;
			}
		);

		// force the service to become active, so we can check that it does get destroyed
		$newServices->getService( 'DBLoadBalancerFactory' );

		MediaWikiServices::disableStorageBackend(); // should destroy DBLoadBalancerFactory

		try {
			MediaWikiServices::getInstance()->getService( 'DBLoadBalancerFactory' );
			$this->fail( 'DBLoadBalancerFactory shoudl have been disabled' );
		}
		catch ( ServiceDisabledException $ex ) {
			// ok, as expected
		}
		catch ( Throwable $ex ) {
			$this->fail( 'ServiceDisabledException expected, caught ' . get_class( $ex ) );
		}

		MediaWikiServices::forceGlobalInstance( $oldServices );
	}

	public function testResetChildProcessServices() {
		$newServices = $this->newMediaWikiServices();
		$oldServices = MediaWikiServices::forceGlobalInstance( $newServices );

		$lbFactory = $this->getMockBuilder( 'LBFactorySimple' )
			->disableOriginalConstructor()
			->getMock();

		$lbFactory->expects( $this->once() )
			->method( 'destroy' );

		$newServices->redefineService(
			'DBLoadBalancerFactory',
			function() use ( $lbFactory ) {
				return $lbFactory;
			}
		);

		// force the service to become active, so we can check that it does get destroyed
		$oldLBFactory = $newServices->getService( 'DBLoadBalancerFactory' );

		MediaWikiServices::resetChildProcessServices();
		$finalServices = MediaWikiServices::getInstance();

		$newLBFactory = $finalServices->getService( 'DBLoadBalancerFactory' );

		$this->assertNotSame( $oldLBFactory, $newLBFactory );

		MediaWikiServices::forceGlobalInstance( $oldServices );
	}

	public function testResetServiceForTesting() {
		$services = $this->newMediaWikiServices();
		$serviceCounter = 0;

		$services->defineService(
			'Test',
			function() use ( &$serviceCounter ) {
				$serviceCounter++;
				$service = $this->getMock( 'MediaWiki\Services\DestructibleService' );
				$service->expects( $this->once() )->method( 'destroy' );
				return $service;
			}
		);

		// This should do nothing. In particular, it should not create a service instance.
		$services->resetServiceForTesting( 'Test' );
		$this->assertEquals( 0, $serviceCounter, 'No service instance should be created yet.' );

		$oldInstance = $services->getService( 'Test' );
		$this->assertEquals( 1, $serviceCounter, 'A service instance should exit now.' );

		// The old instance should be detached, and destroy() called.
		$services->resetServiceForTesting( 'Test' );
		$newInstance = $services->getService( 'Test' );

		$this->assertNotSame( $oldInstance, $newInstance );

		// Satisfy the expectation that destroy() is called also for the second service instance.
		$newInstance->destroy();
	}

	public function testResetServiceForTesting_noDestroy() {
		$services = $this->newMediaWikiServices();

		$services->defineService(
			'Test',
			function() {
				$service = $this->getMock( 'MediaWiki\Services\DestructibleService' );
				$service->expects( $this->never() )->method( 'destroy' );
				return $service;
			}
		);

		$oldInstance = $services->getService( 'Test' );

		// The old instance should be detached, but destroy() not called.
		$services->resetServiceForTesting( 'Test', false );
		$newInstance = $services->getService( 'Test' );

		$this->assertNotSame( $oldInstance, $newInstance );
	}

	public function provideGetters() {
		$getServiceCases = $this->provideGetService();
		$getterCases = [];

		// All getters should be named just like the service, with "get" added.
		foreach ( $getServiceCases as $name => $case ) {
			if ( $name[0] === '_' ) {
				// Internal service, no getter
				continue;
			}
			list( $service, $class ) = $case;
			$getterCases[$name] = [
				'get' . $service,
				$class,
			];
		}

		return $getterCases;
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

	public function provideGetService() {
		// NOTE: This should list all service getters defined in ServiceWiring.php.
		// NOTE: For every test case defined here there should be a corresponding
		// test case defined in provideGetters().
		return [
			'BootstrapConfig' => [ 'BootstrapConfig', Config::class ],
			'ConfigFactory' => [ 'ConfigFactory', ConfigFactory::class ],
			'MainConfig' => [ 'MainConfig', Config::class ],
			'SiteStore' => [ 'SiteStore', SiteStore::class ],
			'SiteLookup' => [ 'SiteLookup', SiteLookup::class ],
			'StatsdDataFactory' => [ 'StatsdDataFactory', StatsdDataFactory::class ],
			'EventRelayerGroup' => [ 'EventRelayerGroup', EventRelayerGroup::class ],
			'SearchEngineFactory' => [ 'SearchEngineFactory', SearchEngineFactory::class ],
			'SearchEngineConfig' => [ 'SearchEngineConfig', SearchEngineConfig::class ],
			'SkinFactory' => [ 'SkinFactory', SkinFactory::class ],
			'DBLoadBalancerFactory' => [ 'DBLoadBalancerFactory', 'LBFactory' ],
			'DBLoadBalancer' => [ 'DBLoadBalancer', 'LoadBalancer' ],
			'WatchedItemStore' => [ 'WatchedItemStore', WatchedItemStore::class ],
			'_MediaWikiTitleCodec' => [ '_MediaWikiTitleCodec', MediaWikiTitleCodec::class ],
			'TitleFormatter' => [ 'TitleFormatter', TitleFormatter::class ],
			'TitleParser' => [ 'TitleParser', TitleParser::class ],
		];
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

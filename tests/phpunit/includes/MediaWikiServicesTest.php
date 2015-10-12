<?php
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

	public function testResetBetweenTests() {
		// We don't know what resetBetweenTest() actually does. So we just check
		// that we still can access the main config via the service locator.
		MediaWikiServices::resetBetweenTest();

		$services = MediaWikiServices::getInstance();
		$this->assertInstanceOf( 'MediaWiki\\MediaWikiServices', $services );

		$services->getMainConfig();
	}

	public function provideGetters() {
		$getServiceCases = $this->provideGetService();
		$getterCases = array();

		// All getters should be named just like the service, with "get" added.
		foreach ( $getServiceCases as $name => $case ) {
			$getterCases[$name] = array(
				'get' . $case[0],
				$case[1]
			);
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
		return array(
			'BootstrapConfig' => array( 'BootstrapConfig', 'Config' ),
			'ConfigFactory' => array( 'ConfigFactory', 'ConfigFactory' ),
			'MainConfig' => array( 'MainConfig', 'Config' ),
			'SiteStore' => array( 'SiteStore', 'SiteStore' ),
			'SiteLookup' => array( 'SiteLookup', 'SiteLookup' ),
			'DBLoadBalancerFactory' => array( 'DBLoadBalancerFactory', 'LBFactory' ),
			'DBLoadBalancer' => array( 'DBLoadBalancer', 'LoadBalancer' ),
			'ObjectCacheManager' => array( 'ObjectCacheManager', 'ObjectCacheManager' ),
			'Profiler' => array( 'Profiler', 'Profiler' ),
			'LoggerFactory' => array( 'LoggerFactory', 'MediaWiki\Logger\Spi' ),
			'FileBackendGroup' => array( 'FileBackendGroup', 'FileBackendGroup' ),
			'RedisConnectionPoolPool' => array( 'RedisConnectionPoolPool', 'MediaWiki\Services\ServicePool' ),
			'JobQueueGroupPool' => array( 'JobQueueGroupPool', 'MediaWiki\Services\ServicePool' ),
			'LockManagerGroupPool' => array( 'LockManagerGroupPool', 'MediaWiki\Services\ServicePool' ),
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

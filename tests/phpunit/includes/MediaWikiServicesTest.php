<?php

use Mediawiki\Http\HttpRequestFactory;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Services\DestructibleService;
use MediaWiki\Services\SalvageableService;
use MediaWiki\Services\ServiceDisabledException;
use MediaWiki\Shell\CommandFactory;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\RevisionLookup;
use MediaWiki\Storage\RevisionStore;
use MediaWiki\Storage\SqlBlobStore;

/**
 * @covers MediaWiki\MediaWikiServices
 *
 * @group MediaWiki
 */
class MediaWikiServicesTest extends MediaWikiTestCase {

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
		$this->assertInstanceOf( MediaWikiServices::class, $services );
	}

	public function testForceGlobalInstance() {
		$newServices = $this->newMediaWikiServices();
		$oldServices = MediaWikiServices::forceGlobalInstance( $newServices );

		$this->assertInstanceOf( MediaWikiServices::class, $oldServices );
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

		$service1 = $this->createMock( SalvageableService::class );
		$service1->expects( $this->never() )
			->method( 'salvage' );

		$newServices->defineService(
			'Test',
			function () use ( $service1 ) {
				return $service1;
			}
		);

		// force instantiation
		$newServices->getService( 'Test' );

		MediaWikiServices::resetGlobalInstance( $this->newTestConfig() );
		$theServices = MediaWikiServices::getInstance();

		$this->assertSame(
			$service1,
			$theServices->getService( 'Test' ),
			'service definition should survive reset'
		);

		$this->assertNotSame( $theServices, $newServices );
		$this->assertNotSame( $theServices, $oldServices );

		MediaWikiServices::forceGlobalInstance( $oldServices );
	}

	public function testResetGlobalInstance_quick() {
		$newServices = $this->newMediaWikiServices();
		$oldServices = MediaWikiServices::forceGlobalInstance( $newServices );

		$service1 = $this->createMock( SalvageableService::class );
		$service1->expects( $this->never() )
			->method( 'salvage' );

		$service2 = $this->createMock( SalvageableService::class );
		$service2->expects( $this->once() )
			->method( 'salvage' )
			->with( $service1 );

		// sequence of values the instantiator will return
		$instantiatorReturnValues = [
			$service1,
			$service2,
		];

		$newServices->defineService(
			'Test',
			function () use ( &$instantiatorReturnValues ) {
				return array_shift( $instantiatorReturnValues );
			}
		);

		// force instantiation
		$newServices->getService( 'Test' );

		MediaWikiServices::resetGlobalInstance( $this->newTestConfig(), 'quick' );
		$theServices = MediaWikiServices::getInstance();

		$this->assertSame( $service2, $theServices->getService( 'Test' ) );

		$this->assertNotSame( $theServices, $newServices );
		$this->assertNotSame( $theServices, $oldServices );

		MediaWikiServices::forceGlobalInstance( $oldServices );
	}

	public function testDisableStorageBackend() {
		$newServices = $this->newMediaWikiServices();
		$oldServices = MediaWikiServices::forceGlobalInstance( $newServices );

		$lbFactory = $this->getMockBuilder( \Wikimedia\Rdbms\LBFactorySimple::class )
			->disableOriginalConstructor()
			->getMock();

		$newServices->redefineService(
			'DBLoadBalancerFactory',
			function () use ( $lbFactory ) {
				return $lbFactory;
			}
		);

		// force the service to become active, so we can check that it does get destroyed
		$newServices->getService( 'DBLoadBalancerFactory' );

		MediaWikiServices::disableStorageBackend(); // should destroy DBLoadBalancerFactory

		try {
			MediaWikiServices::getInstance()->getService( 'DBLoadBalancerFactory' );
			$this->fail( 'DBLoadBalancerFactory should have been disabled' );
		}
		catch ( ServiceDisabledException $ex ) {
			// ok, as expected
		} catch ( Throwable $ex ) {
			$this->fail( 'ServiceDisabledException expected, caught ' . get_class( $ex ) );
		}

		MediaWikiServices::forceGlobalInstance( $oldServices );
		$newServices->destroy();

		// No exception was thrown, avoid being risky
		$this->assertTrue( true );
	}

	public function testResetChildProcessServices() {
		$newServices = $this->newMediaWikiServices();
		$oldServices = MediaWikiServices::forceGlobalInstance( $newServices );

		$service1 = $this->createMock( DestructibleService::class );
		$service1->expects( $this->once() )
			->method( 'destroy' );

		$service2 = $this->createMock( DestructibleService::class );
		$service2->expects( $this->never() )
			->method( 'destroy' );

		// sequence of values the instantiator will return
		$instantiatorReturnValues = [
			$service1,
			$service2,
		];

		$newServices->defineService(
			'Test',
			function () use ( &$instantiatorReturnValues ) {
				return array_shift( $instantiatorReturnValues );
			}
		);

		// force the service to become active, so we can check that it does get destroyed
		$oldTestService = $newServices->getService( 'Test' );

		MediaWikiServices::resetChildProcessServices();
		$finalServices = MediaWikiServices::getInstance();

		$newTestService = $finalServices->getService( 'Test' );
		$this->assertNotSame( $oldTestService, $newTestService );

		MediaWikiServices::forceGlobalInstance( $oldServices );
	}

	public function testResetServiceForTesting() {
		$services = $this->newMediaWikiServices();
		$serviceCounter = 0;

		$services->defineService(
			'Test',
			function () use ( &$serviceCounter ) {
				$serviceCounter++;
				$service = $this->createMock( MediaWiki\Services\DestructibleService::class );
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
			function () {
				$service = $this->createMock( MediaWiki\Services\DestructibleService::class );
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
			'StatsdDataFactory' => [ 'StatsdDataFactory', IBufferingStatsdDataFactory::class ],
			'InterwikiLookup' => [ 'InterwikiLookup', InterwikiLookup::class ],
			'EventRelayerGroup' => [ 'EventRelayerGroup', EventRelayerGroup::class ],
			'SearchEngineFactory' => [ 'SearchEngineFactory', SearchEngineFactory::class ],
			'SearchEngineConfig' => [ 'SearchEngineConfig', SearchEngineConfig::class ],
			'SkinFactory' => [ 'SkinFactory', SkinFactory::class ],
			'DBLoadBalancerFactory' => [ 'DBLoadBalancerFactory', Wikimedia\Rdbms\LBFactory::class ],
			'DBLoadBalancer' => [ 'DBLoadBalancer', Wikimedia\Rdbms\LoadBalancer::class ],
			'WatchedItemStore' => [ 'WatchedItemStore', WatchedItemStore::class ],
			'WatchedItemQueryService' => [ 'WatchedItemQueryService', WatchedItemQueryService::class ],
			'CryptRand' => [ 'CryptRand', CryptRand::class ],
			'CryptHKDF' => [ 'CryptHKDF', CryptHKDF::class ],
			'MediaHandlerFactory' => [ 'MediaHandlerFactory', MediaHandlerFactory::class ],
			'Parser' => [ 'Parser', Parser::class ],
			'ParserCache' => [ 'ParserCache', ParserCache::class ],
			'GenderCache' => [ 'GenderCache', GenderCache::class ],
			'LinkCache' => [ 'LinkCache', LinkCache::class ],
			'LinkRenderer' => [ 'LinkRenderer', LinkRenderer::class ],
			'LinkRendererFactory' => [ 'LinkRendererFactory', LinkRendererFactory::class ],
			'_MediaWikiTitleCodec' => [ '_MediaWikiTitleCodec', MediaWikiTitleCodec::class ],
			'MimeAnalyzer' => [ 'MimeAnalyzer', MimeAnalyzer::class ],
			'TitleFormatter' => [ 'TitleFormatter', TitleFormatter::class ],
			'TitleParser' => [ 'TitleParser', TitleParser::class ],
			'ProxyLookup' => [ 'ProxyLookup', ProxyLookup::class ],
			'MainObjectStash' => [ 'MainObjectStash', BagOStuff::class ],
			'MainWANObjectCache' => [ 'MainWANObjectCache', WANObjectCache::class ],
			'LocalServerObjectCache' => [ 'LocalServerObjectCache', BagOStuff::class ],
			'VirtualRESTServiceClient' => [ 'VirtualRESTServiceClient', VirtualRESTServiceClient::class ],
			'ShellCommandFactory' => [ 'ShellCommandFactory', CommandFactory::class ],
			'BlobStoreFactory' => [ 'BlobStoreFactory', BlobStoreFactory::class ],
			'BlobStore' => [ 'BlobStore', BlobStore::class ],
			'_SqlBlobStore' => [ '_SqlBlobStore', SqlBlobStore::class ],
			'RevisionStore' => [ 'RevisionStore', RevisionStore::class ],
			'RevisionLookup' => [ 'RevisionLookup', RevisionLookup::class ],
			'HttpRequestFactory' => [ 'HttpRequestFactory', HttpRequestFactory::class ],
			'CommentStore' => [ 'CommentStore', CommentStore::class ],
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

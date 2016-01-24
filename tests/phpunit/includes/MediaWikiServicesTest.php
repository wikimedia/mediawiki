<?php
use MediaWiki\MediaWikiServices;

/**
 * @covers MediaWiki\MediaWikiServices
 *
 * @group MediaWiki
 */
class MediaWikiServicesTest extends PHPUnit_Framework_TestCase {

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
			'DBLoadBalancerFactoryContainer' => array( 'getDBLoadBalancerFactoryContainer', 'MediaWiki\Services\ServiceContainer' ),
			'RequestContext' => array( 'getRequestContext', 'RequestContext' ),
			'ObjectCacheManager' => array( 'getObjectCacheManager', 'ObjectCacheManager' ),
			'ChronologyProtector' => array( 'getChronologyProtector', 'ChronologyProtector' ),
			'Profiler' => array( 'getProfiler', 'Profiler' ),
			'LoggerFactory' => array( 'getLoggerFactory', 'MediaWiki\Logger\Spi' ),
			'StatsdDataFactory' => array( 'getStatsdDataFactory', 'Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface' ),
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
			'DBLoadBalancerFactoryContainer' => array( 'DBLoadBalancerFactoryContainer', 'MediaWiki\Services\ServiceContainer' ),
			'RequestContext' => array( 'RequestContext', 'RequestContext' ),
			'ObjectCacheManager' => array( 'ObjectCacheManager', 'ObjectCacheManager' ),
			'ChronologyProtector' => array( 'ChronologyProtector', 'ChronologyProtector' ),
			'Profiler' => array( 'Profiler', 'Profiler' ),
			'LoggerFactory' => array( 'LoggerFactory', 'MediaWiki\Logger\Spi' ),
			'StatsdDataFactory' => array( 'StatsdDataFactory', 'Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface' ),
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

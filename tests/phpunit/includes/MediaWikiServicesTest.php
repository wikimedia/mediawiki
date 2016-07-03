<?php
use Liuggio\StatsdClient\Factory\StatsdDataFactory;
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
		return [
			'BootstrapConfig' => [ 'getBootstrapConfig', Config::class ],
			'ConfigFactory' => [ 'getConfigFactory', ConfigFactory::class ],
			'MainConfig' => [ 'getMainConfig', Config::class ],
			'SiteStore' => [ 'getSiteStore', SiteStore::class ],
			'SiteLookup' => [ 'getSiteLookup', SiteLookup::class ],
			'StatsdDataFactory' => [ 'getStatsdDataFactory', StatsdDataFactory::class ],
			'EventRelayerGroup' => [ 'getEventRelayerGroup', EventRelayerGroup::class ],
			'SearchEngine' => [ 'newSearchEngine', SearchEngine::class ],
			'SearchEngineFactory' => [ 'getSearchEngineFactory', SearchEngineFactory::class ],
			'SearchEngineConfig' => [ 'getSearchEngineConfig', SearchEngineConfig::class ],
			'SkinFactory' => [ 'getSkinFactory', SkinFactory::class ],
		];
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

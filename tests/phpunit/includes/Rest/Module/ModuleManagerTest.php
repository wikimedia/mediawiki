<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Module\ModuleManager;
use MediaWiki\Rest\ResponseFactory;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Rest\Module\ModuleManager
 */
class ModuleManagerTest extends MediaWikiIntegrationTestCase {
	use RestTestTrait;

	public function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::RestPath, '/rest' );
	}

	/**
	 * @return ModuleManager
	 */
	private function getModuleManager(): ModuleManager {
		$services = $this->getServiceContainer();
		$conf = $services->getMainConfig();

		return new ModuleManager(
			new ServiceOptions( ModuleManager::CONSTRUCTOR_OPTIONS, $conf ),
			$services->getLocalServerObjectCache(),
			new ResponseFactory( [] ),
		);
	}

	public static function provideCoreRouteFiles() {
		yield 'coreRoutes' => [ 'includes/Rest/coreRoutes.json' ];
		yield 'site.v1' => [ 'includes/Rest/site.v1.json' ];
	}

	/**
	 * @dataProvider provideCoreRouteFiles
	 *
	 * Ensure ModuleManager automatically loads core routes
	 */
	public function testRouteFiles( string $needle ) {
		$moduleManager = $this->getModuleManager();
		$routeFiles = $moduleManager->getRouteFiles();

		$found = false;
		foreach ( $routeFiles as $file ) {
			// ModuleManager prepends the install path, so an exact string comparison would fail
			if ( str_contains( $file, $needle ) ) {
				$found = true;
				break;
			}
		}

		$this->assertTrue( $found, 'Core route ' . $needle . ' not found' );
	}

	public static function provideCoreSpecs() {
		// TODO: When we add more modules to core whose specs are visible by default,
		//  add them to this data provider
		yield 'mw-extra' => [ 'mw-extra' ];
	}

	/**
	 * @dataProvider provideCoreSpecs
	 *
	 * Ensure ModuleManager automatically loads core specs
	 */
	public function testSpecs( string $needle ) {
		$moduleManager = $this->getModuleManager();
		$specs = $moduleManager->getApiSpecs();

		$found = false;
		foreach ( $specs as $key => $spec ) {
			if ( $key === $needle ) {
				$found = true;
				break;
			}
		}

		$this->assertTrue( $found, 'Spec ' . $needle . ' not found' );
	}
}

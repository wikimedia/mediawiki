<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Module\ModuleManager;
use MediaWiki\Rest\Module\ModuleMode;
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

		$conf = $this->getServiceContainer()->getMainConfig();

		$rf = $conf->get( MainConfigNames::RestAPIAdditionalRouteFiles );
		$rf[] = __DIR__ . '/mockTwo.v1.json';
		$this->overrideConfigValue( MainConfigNames::RestAPIAdditionalRouteFiles, $rf );

		$overrides = [
			'mockTwo/v1' => [ 'mode' => 'hidden' ],
			'mockThree/v1' => [ 'mode' => 'disabled' ],
			'mockNonexistent/v1' => [ 'mode' => 'gibberish' ],
		];
		$this->overrideConfigValue( MainConfigNames::RestModuleOverrides, $overrides );

		$this->overrideConfigValue( MainConfigNames::RestExternalModules, [
			'mockExternal/v1' => [
				'info' => [
					'title' => 'Mock External Module',
					'version' => '1.0.0',
					'description' => 'This is a mock external module.'
				],
				'base' => 'https://example.com/mockExternal/v1',
				'spec' => 'https://example.com/mockExternal/v1/spec.json',
			],
		] );

		$rss = $conf->get( MainConfigNames::RestSandboxSpecs );
		$rss['mock.v1-invalid.json'] = [ 'file' => __DIR__ . '/mock.v1-invalid.json' ];
		$this->overrideConfigValue( MainConfigNames::RestSandboxSpecs, $rss );
	}

	/**
	 * @param array $extensionModuleFiles
	 *
	 * @return ModuleManager
	 */
	private function getModuleManager( $extensionModuleFiles = [] ): ModuleManager {
		$services = $this->getServiceContainer();
		$conf = $services->getMainConfig();

		return new ModuleManager(
			new ServiceOptions( ModuleManager::CONSTRUCTOR_OPTIONS, $conf ),
			$extensionModuleFiles,
			$services->getLocalServerObjectCache(),
			new ResponseFactory( [] ),
		);
	}

	public static function provideRouteFiles() {
		yield 'coreRoutes' => [ [], 'includes/Rest/coreRoutes.json' ];
		yield 'site.v1.json' => [ [], 'includes/Rest/site.v1.json' ];
		yield 'specs.v0.json' => [ [], 'includes/Rest/specs.v0.json' ];
		yield 'mockTwo.v1.json' => [ [ __DIR__ . '/mockFour.v1.json' ], 'mockFour.v1.json' ];
	}

	/**
	 * @dataProvider provideRouteFiles
	 *
	 * Ensure ModuleManager automatically loads routes (flat and modules) via core, config,
	 * and extension.
	 */
	public function testRouteFiles( array $extensionRouteFiles, string $needle ) {
		$moduleManager = $this->getModuleManager( $extensionRouteFiles );
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

	public static function provideDisabledRouteFiles() {
		yield 'invalid' => [ [ __DIR__ . '/mock.v1-invalid.json' ], 'mock.v1-invalid.json' ];
		yield 'overridden' => [ [ __DIR__ . '/mockThree.v1.json' ], 'mockThree.v1.json' ];
	}

	/**
	 * @dataProvider provideDisabledRouteFiles
	 *
	 * Ensure ModuleManager tracks disabled route files.
	 */
	public function testDisabledRouteFiles( array $extensionRouteFiles, string $needle ) {
		$moduleManager = $this->getModuleManager( $extensionRouteFiles );
		$disabledRouteFiles = $moduleManager->getDisabledRouteFiles();

		$found = false;
		foreach ( $disabledRouteFiles as $file ) {
			// ModuleManager prepends the install path, so an exact string comparison would fail
			if ( str_contains( $file, $needle ) ) {
				$found = true;
				break;
			}
		}

		$this->assertTrue( $found, 'Disabled route ' . $needle . ' not found' );
	}

	/**
	 * Ensure disabled modules are treated as disabled.
	 */
	public function testDisabledModules() {
		$moduleManager = $this->getModuleManager();
		$routeFiles = $moduleManager->getRouteFiles();

		$needle = 'mock.v1-invalid.json';
		$found = false;
		foreach ( $routeFiles as $file ) {
			// ModuleManager prepends the install path, so an exact string comparison would fail
			if ( str_contains( $file, $needle ) ) {
				$found = true;
				break;
			}
		}

		$this->assertFalse( $found, 'Disabled module ' . $needle . ' found' );
	}

	public static function provideSpecs() {
		// This comes from the hard-coded list in core
		yield 'mw-extra' => [
			'mw-extra',
			[
				'group' => '',
				'url' => '/rest.php/specs/v0/module/-',
				'name' => 'MediaWiki REST API (routes not in modules)',
			]
		];

		yield 'mockExternal/v1' => [
			'mockExternal/v1',
			[
				'group' => '',
				'url' => 'https://example.com/mockExternal/v1/spec.json',
				'name' => 'Mock External Module',
			]
		];

		// This comes from RestSandboxSpecs, which should override audience designations.
		yield 'mock.v1-invalid.json' => [
			'mock.v1-invalid.json',
			[
				'group' => '',
				'url' => '/rest.php/specs/v0/module/mock/v1-invalid',
				'name' => 'Mock Module (Invalid)',
			]
		];
	}

	/**
	 * @dataProvider provideSpecs
	 */
	public function testSpecs( string $needle, array $expected ) {
		$moduleManager = $this->getModuleManager();
		$specs = $moduleManager->getApiSpecs();

		$this->assertArrayHasKey( $needle, $specs, 'Spec ' . $needle . ' not found' );
		$spec = $specs[$needle];
		foreach ( $expected as $key => $expectedValue ) {
			$this->assertSame( $expectedValue, $spec[$key] ?? null, "Unexpected value for $needle:$key" );
		}
	}

	public function testHasApiSpecs(): void {
		$moduleManager = $this->getModuleManager();
		$this->assertTrue( $moduleManager->hasApiSpecs() );
	}

	public static function provideGetModuleModeCases() {
		yield from [
			[ 'example/v1', ModuleMode::PUBLISHED ],

			// We don't expect people to actually use 'published', but if they do, it should work.
			[ 'example/v1-published', ModuleMode::PUBLISHED ],

			[ 'example/v1-internal', ModuleMode::PUBLISHED ],

			[ 'example/v1-beta', ModuleMode::PUBLISHED ],

			// We don't expect people to actually use 'invalid', but if they do, it should work.
			[ 'example/v1-invalid', ModuleMode::DISABLED ],

			// Completely unrecognized and unsupported audience designation
			[ 'example/v1-unrecognized', ModuleMode::DISABLED ],

			// Malformed module id (no version)
			[ 'malformed', ModuleMode::DISABLED ],

			// Overrides work
			[ 'mockTwo/v1', ModuleMode::HIDDEN ],

			// Fallback to DISABLED on unrecognized mode works
			[ 'mockNonexistent/v1', ModuleMode::DISABLED ],
		];
	}

	/**
	 * @dataProvider provideGetModuleModeCases
	 */
	public function testGetModuleMode( string $moduleId, ModuleMode $expected ): void {
		$moduleManager = $this->getModuleManager();
		$mode = $moduleManager->getModuleMode( $moduleId );

		// The default message is unhelpful in identifying which test case failed.
		$msg = "Failure for module id $moduleId and expected mode {$mode->name}";
		$this->assertSame( $expected, $mode, $msg );
	}

	public function testGetApiSpecsSortOrder(): void {
		$conf = $this->getServiceContainer()->getMainConfig();
		$rss = $conf->get( MainConfigNames::RestSandboxSpecs );

		$unsorted = [
			'c.json' => [ 'name' => 'C Spec', 'url' => '/c' ],
			'a.json' => [ 'name' => 'A Spec', 'url' => '/a' ],
			'b.json' => [ 'name' => 'b Spec', 'url' => '/b' ],
		];
		$this->overrideConfigValue( MainConfigNames::RestSandboxSpecs, $unsorted );

		$moduleManager = $this->getModuleManager();
		$specs = $moduleManager->getApiSpecs();

		$names = array_column( $specs, 'name' );

		// mw-extra is always first
		$this->assertSame( 'MediaWiki REST API (routes not in modules)', $names[0] );

		// The rest are sorted alphabetically by name
		$this->assertSame( 'A Spec', $names[1] );
		$this->assertSame( 'b Spec', $names[2] );
		$this->assertSame( 'C Spec', $names[3] );

		$this->overrideConfigValue( MainConfigNames::RestSandboxSpecs, $rss );
	}
}

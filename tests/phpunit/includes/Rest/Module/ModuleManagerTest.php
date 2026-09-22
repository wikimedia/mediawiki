<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\JsonLocalizer;
use MediaWiki\Rest\Module\ModuleInfo;
use MediaWiki\Rest\Module\ModuleManager;
use MediaWiki\Rest\Module\ModuleMode;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWikiIntegrationTestCase;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Rest\Module\ModuleManager
 */
class ModuleManagerTest extends MediaWikiIntegrationTestCase {
	use RestTestTrait;
	use DummyServicesTrait;

	public function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::RestPath, '/rest' );

		$conf = $this->getServiceContainer()->getMainConfig();

		$rf = $conf->get( MainConfigNames::RestAPIAdditionalRouteFiles );
		$rf[] = __DIR__ . '/mockTwo.v1.json';
		$this->overrideConfigValue( MainConfigNames::RestAPIAdditionalRouteFiles, $rf );

		$overrides = [
			'mockTwo/v1' => [ 'availability' => 'hidden' ],
			'mockThree/v1' => [ 'availability' => 'disabled' ],
			'mockNonexistent/v1' => [ 'availability' => 'gibberish' ],
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
	}

	/**
	 * @param array $extensionModuleFiles
	 * @param ITextFormatter|null $formatter
	 *
	 * @return ModuleManager
	 */
	private function getModuleManager(
		array $extensionModuleFiles = [],
		?ITextFormatter $formatter = null
	): ModuleManager {
		$services = $this->getServiceContainer();
		$conf = $services->getMainConfig();

		$formatter ??= $this->getDummyTextFormatter();

		return new ModuleManager(
			new ServiceOptions( ModuleManager::CONSTRUCTOR_OPTIONS, $conf ),
			$extensionModuleFiles,
			$services->getLocalServerObjectCache(),
			new JsonLocalizer( $formatter ),
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
				'groups' => [],
				'url' => '/rest/specs/v0/module/-',
				'name' => 'MediaWiki REST API (routes not in modules)',
			]
		];

		yield 'mockExternal/v1' => [
			'mockExternal/v1',
			[
				'groups' => [],
				'url' => 'https://example.com/mockExternal/v1/spec.json',
				'name' => 'Mock External Module',
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

			// We don't expect people to actually use 'public', but if they do, it should work.
			[ 'example/v1-public', ModuleMode::PUBLISHED ],

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
		$rem = $conf->get( MainConfigNames::RestExternalModules );

		$unsorted = [
			'C/v1' => [
				'info' => [
					'title' => 'C Spec',
					'version' => '1.0.0',
				],
				'base' => '/api/rest_v1/c',
				'spec' => '/api/rest_v1/c/openapi.json',
			],
			'A/v1' => [
				'info' => [
					'title' => 'A Spec',
					'version' => '1.0.0',
				],
				'base' => '/api/rest_v1/a',
				'spec' => '/api/rest_v1/a/openapi.json',
			],
			'b/v1' => [
				'info' => [
					'title' => 'b Spec',
					'version' => '1.0.0',
				],
				'base' => '/api/rest_v1/b',
				'spec' => '/api/rest_v1/b/openapi.json',
			]
		];

		$this->overrideConfigValue( MainConfigNames::RestExternalModules, $unsorted );

		$moduleManager = $this->getModuleManager();
		$specs = $moduleManager->getApiSpecs();

		$names = array_column( $specs, 'name' );

		// mw-extra is always first
		$this->assertSame( 'MediaWiki REST API (routes not in modules)', $names[0] );

		// The rest are sorted alphabetically by name
		$this->assertSame( 'A Spec', $names[1] );
		$this->assertSame( 'b Spec', $names[2] );
		$this->assertSame( 'C Spec', $names[3] );

		$this->overrideConfigValue( MainConfigNames::RestExternalModules, $rem );
	}

	public static function provideGetModuleInfoGroupsCases() {
		yield from [
			'local module without groups' => [ 'site/v1', [] ],
			'local module with audience designation suffix' => [ 'fragments/v0-internal', [ 'internal' ] ],
			'external module without groups' => [ 'mockExternal/v1', [] ],
			'module with override' => [
				'site/v1',
				[ 'preferred' ],
				[ 'site/v1' => [ 'availability' => 'published', 'groups' => [ 'preferred' ] ] ]
			],
			'module with multiple override groups' => [
				'site/v1',
				[ 'preferred', 'beta' ],
				[ 'site/v1' => [ 'availability' => 'published', 'groups' => [ 'preferred', 'beta' ] ] ]
			],
		];
	}

	/**
	 * @dataProvider provideGetModuleInfoGroupsCases
	 * @covers \MediaWiki\Rest\Module\ModuleManager::getModuleInfo
	 * @covers \MediaWiki\Rest\Module\ModuleManager::getModuleInfos
	 */
	public function testGetModuleInfoGroups( string $moduleId, array $expected, array $overrides = [] ): void {
		if ( $overrides ) {
			$this->overrideConfigValue( MainConfigNames::RestModuleOverrides, $overrides );
		}
		$moduleManager = $this->getModuleManager();
		$this->assertSame( $expected, $moduleManager->getModuleInfo( $moduleId )->getGroups() );
	}

	/**
	 * Test unified aggregation of local, external, hidden, and disabled modules.
	 *
	 * @covers \MediaWiki\Rest\Module\ModuleManager::getModuleInfos
	 */
	public function testGetModuleInfos(): void {
		$overrides = [
			'mockTwo/v1' => [ 'availability' => 'hidden' ],
			'mockThree/v1' => [ 'availability' => 'disabled' ],
			'site/v1' => [ 'availability' => 'published', 'groups' => [ 'site-group' ] ],
		];
		$this->overrideConfigValue( MainConfigNames::RestModuleOverrides, $overrides );

		$formatter = $this->getServiceContainer()
			->getMessageFormatterFactory()
			->getTextFormatter( 'en' );
		$moduleManager = $this->getModuleManager( [ __DIR__ . '/mockThree.v1.json' ], $formatter );
		$infos = $moduleManager->getModuleInfos();

		// Prefix-less module should be first.
		$this->assertSame( '', array_key_first( $infos ) );
		$prefixless = $infos[''];
		$this->assertInstanceOf( ModuleInfo::class, $prefixless );
		$this->assertSame( '', $prefixless->getId() );
		$this->assertFalse( $prefixless->isExternal() );
		$this->assertSame( ModuleMode::PUBLISHED, $prefixless->getAvailability() );
		$this->assertSame( 'MediaWiki REST API (routes not in modules)', $prefixless->getTitle() );
		$this->assertSame(
			wfMessage( 'rest-module-extra-routes-desc' )->inLanguage( 'en' )->text(),
			$prefixless->getDescription()
		);
		$this->assertSame( '0.1.0', $prefixless->getVersion() );
		$this->assertTrue( wfMessage( 'rest-module-extra-routes-desc' )->exists() );

		// Check local module site/v1 with groups.
		$this->assertArrayHasKey( 'site/v1', $infos );
		$site = $infos['site/v1'];
		$this->assertInstanceOf( ModuleInfo::class, $site );
		$this->assertSame( 'site/v1', $site->getId() );
		$this->assertFalse( $site->isExternal() );
		$this->assertSame(
			wfMessage( 'rest-module-site.v1-title' )->inLanguage( 'en' )->text(),
			$site->getTitle()
		);
		$this->assertSame( '1.0.0', $site->getVersion() );
		$this->assertSame( ModuleMode::PUBLISHED, $site->getAvailability() );
		$this->assertSame( [ 'site-group' ], $site->getGroups() );

		// Check hidden local module mockTwo/v1.
		$this->assertArrayHasKey( 'mockTwo/v1', $infos );
		$mockTwo = $infos['mockTwo/v1'];
		$this->assertInstanceOf( ModuleInfo::class, $mockTwo );
		$this->assertSame( ModuleMode::HIDDEN, $mockTwo->getAvailability() );

		// Check disabled local module mockThree/v1.
		$this->assertArrayHasKey( 'mockThree/v1', $infos );
		$mockThree = $infos['mockThree/v1'];
		$this->assertInstanceOf( ModuleInfo::class, $mockThree );
		$this->assertSame( ModuleMode::DISABLED, $mockThree->getAvailability() );

		// Check external module mockExternal/v1.
		$this->assertArrayHasKey( 'mockExternal/v1', $infos );
		$external = $infos['mockExternal/v1'];
		$this->assertInstanceOf( ModuleInfo::class, $external );
		$this->assertSame( 'mockExternal/v1', $external->getId() );
		$this->assertTrue( $external->isExternal() );
		$this->assertSame( 'Mock External Module', $external->getTitle() );
		$this->assertSame( 'This is a mock external module.', $external->getDescription() );
		$this->assertSame( '1.0.0', $external->getVersion() );
		$this->assertSame( ModuleMode::PUBLISHED, $external->getAvailability() );
	}

	/**
	 * Test that a disabled prefix-less module is still included with DISABLED availability.
	 *
	 * Note: Preserve disabled modules to maintain separation of concerns between the module
	 * configuration provider and downstream consumers with differing requirements, such as
	 * the following:
	 * - DiscoveryHandler (/discovery) inspects availability to omit disabled modules.
	 * - ModuleSpecHandler (/specs/v0/module/{id}) inspects availability to return HTTP 403
	 *   Forbidden with a clear error message, instead of misclassifying the module as
	 *   nonexistent with HTTP 404 Not Found.
	 *
	 * @covers \MediaWiki\Rest\Module\ModuleManager::getModuleInfos
	 */
	public function testGetModuleInfosDisabledPrefixless(): void {
		$overrides = [
			'' => [ 'availability' => 'disabled' ],
		];
		$this->overrideConfigValue( MainConfigNames::RestModuleOverrides, $overrides );

		$moduleManager = $this->getModuleManager();
		$infos = $moduleManager->getModuleInfos();

		$this->assertArrayHasKey( '', $infos );
		$this->assertSame( ModuleMode::DISABLED, $infos['']->getAvailability() );
	}

	/**
	 * Test that `getModuleInfos` sorts the prefix-less module first, followed by
	 * case-insensitive natural order sorting of all remaining module IDs.
	 *
	 * @covers \MediaWiki\Rest\Module\ModuleManager::getModuleInfos
	 */
	public function testGetModuleInfosSortOrder(): void {
		$this->overrideConfigValue( MainConfigNames::RestExternalModules, [
			'C/v1' => [ 'info' => [ 'version' => '1' ], 'spec' => 'https://example.com/c' ],
			'a/v1' => [ 'info' => [ 'version' => '1' ], 'spec' => 'https://example.com/a' ],
			'B/v1' => [ 'info' => [ 'version' => '1' ], 'spec' => 'https://example.com/b' ],
		] );

		$moduleManager = $this->getModuleManager();
		$infos = $moduleManager->getModuleInfos();
		$keys = array_keys( $infos );

		// Prefix-less should be first
		$this->assertSame( '', $keys[0] );

		// The rest must be sorted in natural case-insensitive order
		$externalKeys = array_values( array_intersect( $keys, [ 'a/v1', 'B/v1', 'C/v1' ] ) );
		$this->assertSame( [ 'a/v1', 'B/v1', 'C/v1' ], $externalKeys );
	}

	/**
	 * Test single-module lookup by module ID, verifying that valid IDs return a `ModuleInfo`
	 * instance and non-existent IDs return `null`.
	 *
	 * @covers \MediaWiki\Rest\Module\ModuleManager::getModuleInfo
	 */
	public function testGetModuleInfo(): void {
		$moduleManager = $this->getModuleManager();

		$site = $moduleManager->getModuleInfo( 'site/v1' );
		$this->assertInstanceOf( ModuleInfo::class, $site );
		$this->assertSame( 'site/v1', $site->getId() );

		$external = $moduleManager->getModuleInfo( 'mockExternal/v1' );
		$this->assertInstanceOf( ModuleInfo::class, $external );
		$this->assertSame( 'mockExternal/v1', $external->getId() );
		$this->assertTrue( $external->isExternal() );

		$prefixless = $moduleManager->getModuleInfo( '' );
		$this->assertInstanceOf( ModuleInfo::class, $prefixless );
		$this->assertSame( '', $prefixless->getId() );

		$nonexistent = $moduleManager->getModuleInfo( 'nonexistent/v99' );
		$this->assertNull( $nonexistent );
	}

	/**
	 * Test that when module definition files declare groups,
	 * `getModuleDefinitionInfo`, `getModuleInfos`, and `getApiSpecs`
	 * reflect those groups.
	 *
	 * @covers \MediaWiki\Rest\Module\ModuleManager::getModuleDefinitionInfo
	 * @covers \MediaWiki\Rest\Module\ModuleManager::getModuleInfos
	 * @covers \MediaWiki\Rest\Module\ModuleManager::getApiSpecs
	 * @covers \MediaWiki\Rest\Module\ModuleManager::populateFromFile
	 */
	public function testFileDefinedGroups(): void {
		$file = __DIR__ . '/mockWithGroups.v1.json';
		$moduleManager = $this->getModuleManager( [ $file ] );

		// 1. Verify `getModuleDefinitionInfo` extracts 'groups'.
		$wrapper = TestingAccessWrapper::newFromObject( $moduleManager );
		$defInfo = $wrapper->getModuleDefinitionInfo( $file );
		$this->assertArrayHasKey( 'groups', $defInfo );
		$this->assertSame( [ 'file-defined-group' ], $defInfo['groups'] );

		// 2. Verify `getModuleInfos` reflects file-defined groups without override.
		$infos = $moduleManager->getModuleInfos();
		$this->assertArrayHasKey( 'mockWithGroups/v1', $infos );
		$this->assertSame( [ 'file-defined-group' ], $infos['mockWithGroups/v1']->getGroups() );

		// 3. Verify `getApiSpecs` reflects file-defined groups when loaded as an extension module.
		$specs = $moduleManager->getApiSpecs();
		$this->assertArrayHasKey( 'mockWithGroups.v1', $specs );
		$this->assertSame( [ 'file-defined-group' ], $specs['mockWithGroups.v1']['groups'] );
	}

	/**
	 * Test that `$wgRestModuleOverrides` takes precedence over groups declared in a
	 * module definition file.
	 *
	 * @covers \MediaWiki\Rest\Module\ModuleManager::getModuleInfos
	 */
	public function testFileDefinedGroupsWithOverride(): void {
		$file = __DIR__ . '/mockWithGroups.v1.json';
		$this->overrideConfigValue( MainConfigNames::RestModuleOverrides, [
			'mockWithGroups/v1' => [ 'groups' => [ 'override-group' ] ],
		] );

		$moduleManager = $this->getModuleManager( [ $file ] );
		$infos = $moduleManager->getModuleInfos();

		$this->assertArrayHasKey( 'mockWithGroups/v1', $infos );
		$this->assertSame( [ 'override-group' ], $infos['mockWithGroups/v1']->getGroups() );
	}
}

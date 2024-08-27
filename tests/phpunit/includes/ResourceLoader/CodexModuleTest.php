<?php

namespace MediaWiki\Tests\ResourceLoader;

use InvalidArgumentException;
use MediaWiki\MainConfigNames;
use MediaWiki\ResourceLoader\CodexModule;
use RuntimeException;
use Wikimedia\TestingAccessWrapper;

/**
 * @group ResourceLoader
 * @covers \MediaWiki\ResourceLoader\CodexModule
 */
class CodexModuleTest extends ResourceLoaderTestCase {

	public const FIXTURE_PATH = 'tests/phpunit/data/resourceloader/codex';
	public const DEVMODE_FIXTURE_PATH = 'tests/phpunit/data/resourceloader/codex-devmode';

	public static function provideModuleConfig() {
		yield 'Codex subset' => [
				[
					'codexComponents' => [ 'CdxButton', 'CdxMessage', 'useModelWrapper' ],
					'codexStyleOnly' => false,
					'codexScriptOnly' => false
				],
				[
					'packageFiles' => [
						'codex.js',
						'_codex/constants.js',
						'_codex/useSlotContents2.js',
						'_codex/useWarnOnce.js',
						'_codex/useIconOnlyButton.js',
						'_codex/_plugin-vue_export-helper.js',
						'_codex/CdxButton.js',
						'_codex/useComputedDirection.js',
						'_codex/useComputedLanguage.js',
						'_codex/Icon.js',
						'_codex/CdxMessage.js',
						'_codex/useModelWrapper.js'
					],
					'styles' => [ 'modules/CdxButton.css', 'modules/CdxIcon.css', 'modules/CdxMessage.css' ]
				]
		];
		yield 'Codex subset, style only' => [
			[
				'codexComponents' => [ 'CdxButton', 'CdxMessage' ],
				'codexStyleOnly' => true,
				'codexScriptOnly' => false
			],
			[
				'packageFiles' => [],
				'styles' => [ 'modules/CdxButton.css', 'modules/CdxIcon.css', 'modules/CdxMessage.css' ]
			]
		];
		yield 'Codex subset, script only' => [
			[
				'codexComponents' => [ 'CdxButton', 'CdxMessage', 'useModelWrapper' ],
				'codexStyleOnly' => false,
				'codexScriptOnly' => true
			],
			[
				'packageFiles' => [
					'codex.js',
					'_codex/constants.js',
					'_codex/useSlotContents2.js',
					'_codex/useWarnOnce.js',
					'_codex/useIconOnlyButton.js',
					'_codex/_plugin-vue_export-helper.js',
					'_codex/CdxButton.js',
					'_codex/useComputedDirection.js',
					'_codex/useComputedLanguage.js',
					'_codex/Icon.js',
					'_codex/CdxMessage.js',
					'_codex/useModelWrapper.js'
				],
				'styles' => []
			]
		];
		yield 'Exception thrown when a chunk is requested' => [
			[
				'codexComponents' => [ 'CdxButton', 'buttonHelpers' ],
			],
			[
				'exception' => [
					'class' => InvalidArgumentException::class,
					'message' => '"buttonHelpers" is not an export of Codex and cannot be included in the "codexComponents" array.'
				]
			]
		];
		yield 'Exception thrown when a nonexistent file is requested' => [
			[
				'codexComponents' => [ 'CdxButton', 'blahblahidontexistblah' ],
			],
			[
				'exception' => [
					'class' => InvalidArgumentException::class,
					'message' => '"blahblahidontexistblah" is not an export of Codex and cannot be included in the "codexComponents" array.'
				]
			]
		];
		yield 'Exception thrown when codexComponents is empty in the module definition' => [
			[
				'codexComponents' => []
			],
			[
				'exception' => [
					'class' => InvalidArgumentException::class,
					'message' => "All 'codexComponents' properties in your module definition file " .
					'must either be omitted or be an array with at least one component name'
				]
			]
		];
		yield 'Exception thrown when codexComponents is not an array in the module definition' => [
			[
				'codexComponents' => ''
			],
			[
				'exception' => [
					'class' => InvalidArgumentException::class,
					'message' => "All 'codexComponents' properties in your module definition file " .
					'must either be omitted or be an array with at least one component name'
				]
			]
		];

		yield 'Exception thrown when the @wikimedia/codex module is required' => [
			[
				'codexComponents' => [ 'CdxButton', 'buttonHelpers' ],
				'dependencies' => [ '@wikimedia/codex' ]
			],
			[
				'exception' => [
					'class' => InvalidArgumentException::class,
					'message' => 'ResourceLoader modules using the CodexModule class cannot ' .
						"list the '@wikimedia/codex' module as a dependency. " .
						"Instead, use 'codexComponents' to require a subset of components."
				]
			]
		];

		yield 'Full library' => [
			[
				'codexFullLibrary' => true
			],
			[
				'packageFiles' => [
					'codex.js'
				],
				'styles' => [
					'codex.style.css'
				]
			]
		];

		yield 'Full library, script only' => [
			[
				'codexFullLibrary' => true,
				'codexScriptOnly' => true
			],
			[
				'packageFiles' => [
					'codex.js'
				],
				'styles' => []
			]
		];

		yield 'Full library, style only' => [
			[
				'codexFullLibrary' => true,
				'codexStyleOnly' => true
			],
			[
				'packageFiles' => [],
				'styles' => [
					'codex.style.css'
				]
			]
		];
	}

	/**
	 * @dataProvider provideModuleConfig
	 */
	public function testCodexSubset( $moduleDefinition, $expected ) {
		if ( isset( $expected['exception'] ) ) {
			$this->expectException( $expected['exception']['class'] );
			$this->expectExceptionMessage( $expected['exception']['message'] );
		}

		$testModule = new class( $moduleDefinition ) extends CodexModule {
			public const CODEX_DEFAULT_LIBRARY_DIR = CodexModuleTest::FIXTURE_PATH;
		};

		$context = $this->getResourceLoaderContext();
		$config = $context->getResourceLoader()->getConfig();
		$testModule->setConfig( $config );

		$packageFiles = $testModule->getPackageFiles( $context );
		$styleFiles = $testModule->getStyleFiles( $context );

		// Style-only module will not have any packageFiles.
		$packageFilenames = isset( $packageFiles ) ? array_keys( $packageFiles[ 'files' ] ) : [];
		$this->assertEquals( $expected[ 'packageFiles' ] ?? [], $packageFilenames, 'Correct packageFiles added' );

		// Script-only module will not have any styleFiles.
		$styleFilenames = [];
		if ( count( $styleFiles ) > 0 ) {
			$styleFilenames = array_map( static function ( $filepath ) use ( $testModule ) {
				return str_replace( $testModule::CODEX_DEFAULT_LIBRARY_DIR . '/', '', $filepath->getPath() );
			}, $styleFiles[ 'all' ] );
		}
		$this->assertEquals( $expected[ 'styles' ] ?? [], $styleFilenames, 'Correct styleFiles added' );
	}

	public function testMissingCodexComponentsDefinition() {
		$moduleDefinition = [
			'codexComponents' => [ 'CdxButton', 'CdxMessage' ]
		];

		$testModule = new class( $moduleDefinition ) extends CodexModule {
			public const CODEX_DEFAULT_LIBRARY_DIR = CodexModuleTest::FIXTURE_PATH;
		};

		$context = $this->getResourceLoaderContext();
		$config = $context->getResourceLoader()->getConfig();
		$testModule->setConfig( $config );

		$packageFiles = $testModule->getPackageFiles( $context );

		$codexPackageFileContent = $packageFiles[ 'files' ][ 'codex.js' ][ 'content' ];
		$expectedProxiedExports = '{"CdxButton":require( "./_codex/CdxButton.js" ),'
			. '"CdxMessage":require( "./_codex/CdxMessage.js" )}';

		// Components defined in the 'codexComponents' array should be proxied in the codex.js
		// package file so that missing components will throw a custom error when required.
		// By asserting what components are proxied, we are indirectly asserting that missing
		// components would throw an error when required.
		$this->assertStringContainsString( $expectedProxiedExports, $codexPackageFileContent );
	}

	public function testGetManifestFile() {
		$moduleDefinition = [ 'codexComponents' => [ 'CdxButton', 'CdxMessage' ] ];
		$testModule = new class( $moduleDefinition ) extends CodexModule {
			public const CODEX_DEFAULT_LIBRARY_DIR = CodexModuleTest::FIXTURE_PATH;
		};

		$context = $this->getResourceLoaderContext();
		$config = $context->getResourceLoader()->getConfig();
		$testModule->setConfig( $config );
		$testWrapper = TestingAccessWrapper::newFromObject( $testModule );

		// By default, look for a manifest file called "manifest.json"
		$this->assertEquals(
			MW_INSTALL_PATH . '/' . self::FIXTURE_PATH . '/modules/manifest.json',
			$testWrapper->getManifestFilePath( $context )
		);
	}

	public function testGetMessages() {
		$messageKeysFromFile = json_decode( file_get_contents(
			MW_INSTALL_PATH . '/' . self::FIXTURE_PATH . '/messageKeys.json'
		) );

		$moduleDefinition = [ 'codexComponents' => [ 'CdxButton', 'CdxMessage' ] ];
		$testModule = new class( $moduleDefinition ) extends CodexModule {
			public const CODEX_DEFAULT_LIBRARY_DIR = CodexModuleTest::FIXTURE_PATH;
		};
		$context = $this->getResourceLoaderContext();
		$config = $context->getResourceLoader()->getConfig();
		$testModule->setConfig( $config );
		$this->assertEquals(
			$messageKeysFromFile,
			$testModule->getMessages(),
			'i18n messages from messageKeys.json are added'
		);

		$moduleDefinition = [
			'codexComponents' => [ 'CdxButton', 'CdxMessage' ],
			'messages' => [ 'monday', 'tuesday' ]
		];
		$testModule = new class( $moduleDefinition ) extends CodexModule {
			public const CODEX_DEFAULT_LIBRARY_DIR = CodexModuleTest::FIXTURE_PATH;
		};
		$context = $this->getResourceLoaderContext();
		$config = $context->getResourceLoader()->getConfig();
		$testModule->setConfig( $config );
		$this->assertEquals(
			array_merge( [ 'monday', 'tuesday' ], $messageKeysFromFile ),
			$testModule->getMessages(),
			'i18n messages from messageKeys.json are in addition to messages in module definition'
		);

		$moduleDefinition = [
			'codexFullLibrary' => true
		];
		$testModule = new class( $moduleDefinition ) extends CodexModule {
			public const CODEX_DEFAULT_LIBRARY_DIR = CodexModuleTest::FIXTURE_PATH;
		};
		$context = $this->getResourceLoaderContext();
		$config = $context->getResourceLoader()->getConfig();
		$testModule->setConfig( $config );
		$this->assertEquals(
			$messageKeysFromFile,
			$testModule->getMessages(),
			'i18n messages are added for full library modules'
		);

		$moduleDefinition = [
			'codexComponents' => [ 'CdxButton', 'CdxMessage' ],
			'codexStyleOnly' => true
		];
		$testModule = new class( $moduleDefinition ) extends CodexModule {
			public const CODEX_DEFAULT_LIBRARY_DIR = CodexModuleTest::FIXTURE_PATH;
		};
		$context = $this->getResourceLoaderContext();
		$config = $context->getResourceLoader()->getConfig();
		$testModule->setConfig( $config );
		$this->assertEquals(
			[],
			$testModule->getMessages(),
			'i18n messages are not added for style-only modules'
		);
	}

	public function testDevMode() {
		$devDir = MW_INSTALL_PATH . '/' . self::DEVMODE_FIXTURE_PATH;
		$this->overrideConfigValues( [
			MainConfigNames::CodexDevelopmentDir => $devDir
		] );

		$moduleDefinition = [ 'codexComponents' => [ 'CdxButton', 'CdxMessage' ] ];
		$testModule = new class( $moduleDefinition ) extends CodexModule {
			public const CODEX_DEFAULT_LIBRARY_DIR = CodexModuleTest::FIXTURE_PATH;
		};

		$context = $this->getResourceLoaderContext();
		$config = $context->getResourceLoader()->getConfig();
		$testModule->setConfig( $config );
		$testWrapper = TestingAccessWrapper::newFromObject( $testModule );

		$this->assertEquals(
			$devDir . '/packages/codex/dist/modules/manifest.json',
			$testWrapper->getManifestFilePath( $context ),
			'Manifest path is based on dev mode path'
		);

		$packageFiles = $testModule->getPackageFiles( $context );
		$this->assertEquals(
			$devDir . '/packages/codex/dist/modules/CdxButton.js',
			$packageFiles[ 'files' ][ '_codex/CdxButton.js' ][ 'filePath' ]->getLocalPath(),
			'Package file paths are based on dev mode path'
		);

		$styleFiles = $testModule->getStyleFiles( $context );
		$this->assertEquals(
			$devDir . '/packages/codex/dist/modules/CdxButton.css',
			$styleFiles[ 'all' ][ 0 ]->getLocalPath(),
			'Style file paths are based on dev mode path'
		);

		$this->assertEquals(
			[ 'cdx-test-message-1', 'cdx-test-message-2' ],
			$testModule->getMessages(),
			'i18n message keys come from messages file in dev mode path'
		);

		$fullLibraryModuleDefinition = [ 'codexFullLibrary' => true ];
		$fullLibraryModule = new class( $fullLibraryModuleDefinition ) extends CodexModule {
			public const CODEX_DEFAULT_LIBRARY_DIR = CodexModuleTest::FIXTURE_PATH;
		};
		$fullLibraryModule->setConfig( $config );

		$packageFiles = $fullLibraryModule->getPackageFiles( $context );
		$this->assertEquals(
			$devDir . '/packages/codex/dist/codex.umd.cjs',
			$packageFiles[ 'files' ][ 'codex.js' ][ 'versionFilePath' ]->getLocalPath(),
			'Full library module script path is based on dev mode path'
		);

		$styleFiles = $fullLibraryModule->getStyleFiles( $context );
		$this->assertEquals(
			$devDir . '/packages/codex/dist/codex.style.css',
			$styleFiles[ 'all' ][ 0 ]->getLocalPath(),
			'Full library module style path is based on dev mode path'
		);
	}

	public function testDevModeException() {
		$badDir = MW_INSTALL_PATH . '/' . self::DEVMODE_FIXTURE_PATH . '/path/that/does/not/exist';
		$this->overrideConfigValues( [
			MainConfigNames::CodexDevelopmentDir => $badDir
		] );

		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'Could not find Codex development build' );

		$moduleDefinition = [ 'codexComponents' => [ 'CdxButton', 'CdxMessage' ] ];
		$testModule = new class( $moduleDefinition ) extends CodexModule {
			public const CODEX_DEFAULT_LIBRARY_DIR = CodexModuleTest::FIXTURE_PATH;
		};
		$context = $this->getResourceLoaderContext();
		$config = $context->getResourceLoader()->getConfig();
		$testModule->setConfig( $config );

		$testModule->getPackageFiles( $context );
	}

	/**
	 * Test that the manifest data structure is transformed correctly.
	 * This test relies on the fixture manifest data that lives in
	 * tests/phpunit/data/resourceloader/codexModules
	 */
	public function testGetCodexFiles() {
		$moduleDefinition = [ 'codexComponents' => [ 'CdxButton', 'CdxMessage' ] ];
		$testModule = new class( $moduleDefinition ) extends CodexModule {
			public const CODEX_DEFAULT_LIBRARY_DIR = CodexModuleTest::FIXTURE_PATH;
		};

		$context = $this->getResourceLoaderContext();
		$config = $context->getResourceLoader()->getConfig();
		$testModule->setConfig( $config );
		$testWrapper = TestingAccessWrapper::newFromObject( $testModule );
		$codexFiles = $testWrapper->getCodexFiles( $context );

		// The transformed data structure should have a "files" and a "components" array.
		$this->assertIsArray( $codexFiles );
		$this->assertArrayHasKey( 'files', $codexFiles );
		$this->assertArrayHasKey( 'components', $codexFiles );

		// The "components" array should contain keys like "CdxButton"
		// with values like "CdxButton.js" (matching the names in the manifest)
		$this->assertArrayHasKey( 'CdxButton', $codexFiles[ 'components' ] );
		$this->assertEquals( 'CdxButton.js', $codexFiles[ 'components' ][ 'CdxButton' ] );

		// The "files" array should contains keys like "CdxButton.js"
		// Items in this array are themselves arrays with "styles" and "dependencies" keys.
		$this->assertArrayHasKey( 'CdxButton.js', $codexFiles[ 'files' ] );
		$this->assertArrayHasKey( 'styles', $codexFiles[ 'files' ][ 'CdxButton.js' ] );
		$this->assertArrayHasKey( 'dependencies', $codexFiles[ 'files' ][ 'CdxButton.js' ] );
	}

	public function testGetIcons() {
		$context = $this->getResourceLoaderContext();
		$config = $context->getResourceLoader()->getConfig();

		$icons = CodexModule::getIcons( $context, $config, [ 'cdxIconAdd', 'cdxIconNext' ] );
		$this->assertArrayHasKey( 'cdxIconAdd', $icons );
		$this->assertArrayHasKey( 'cdxIconNext', $icons );
		$this->assertArrayNotHasKey( 'cdxIconPrevious', $icons );
	}

	public function testGetIconsInDevMode() {
		$devDir = MW_INSTALL_PATH . '/' . self::DEVMODE_FIXTURE_PATH;
		$this->overrideConfigValues( [
			MainConfigNames::CodexDevelopmentDir => $devDir
		] );

		$context = $this->getResourceLoaderContext();
		$config = $context->getResourceLoader()->getConfig();
		$icons = CodexModule::getIcons( $context, $config, [ 'cdxIconAdd', 'cdxIconNext' ] );
		$this->assertArrayHasKey( 'cdxIconAdd', $icons );
		$this->assertArrayHasKey( 'cdxIconNext', $icons );
		$this->assertArrayNotHasKey( 'cdxIconPrevious', $icons );

		$this->assertEquals( 'test add icon', $icons['cdxIconAdd'] );
		$this->assertEquals( 'test next icon', $icons['cdxIconNext'] );
	}
}

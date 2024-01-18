<?php

namespace MediaWiki\Tests\ResourceLoader;

use InvalidArgumentException;
use MediaWiki\ResourceLoader\CodexModule;
use ResourceLoaderTestCase;

/**
 * @group ResourceLoader
 * @covers \MediaWiki\ResourceLoader\CodexModule
 */
class CodexModuleTest extends ResourceLoaderTestCase {

	public static function provideModuleConfig() {
		return [
			[ 'Codex subset',
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
					'styles' => [ 'CdxButton.css', 'CdxIcon.css', 'CdxMessage.css' ]
				]
			],
			[ 'Codex subset, style only',
				[
					'codexComponents' => [ 'CdxButton', 'CdxMessage' ],
					'codexStyleOnly' => true,
					'codexScriptOnly' => false
				],
				[
					'packageFiles' => [],
					'styles' => [ 'CdxButton.css', 'CdxIcon.css', 'CdxMessage.css' ]
				]
			],
			[ 'Codex subset, script only',
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
			],
			[ 'Exception thrown when a chunk is requested',
				[
					'codexComponents' => [ 'CdxButton', 'buttonHelpers' ],
				],
				[
					'exception' => [
						'class' => InvalidArgumentException::class,
						'message' => '"buttonHelpers" is not an export of Codex and cannot be included in the "codexComponents" array.'
					]
				]

			]
		];
	}

	/**
	 * @dataProvider provideModuleConfig
	 */
	public function testCodexSubset( $testCase, $moduleDefinition, $expected ) {
		if ( isset( $expected['exception'] ) ) {
			$this->expectException( $expected['exception']['class'] );
			$this->expectExceptionMessage( $expected['exception']['message'] );
		}

		$testModule = new class( $moduleDefinition ) extends CodexModule {
			public const CODEX_MODULE_DIR = 'tests/phpunit/data/resourceloader/codexModules/';
		};

		$context = $this->getResourceLoaderContext();
		$config = $context->getResourceLoader()->getConfig();
		$testModule->setConfig( $config );

		$packageFiles = $testModule->getPackageFiles( $context );
		$styleFiles = $testModule->getStyleFiles( $context );

		// Style-only module will not have any packageFiles.
		$packageFilenames = isset( $packageFiles ) ? array_keys( $packageFiles[ 'files' ] ) : [];
		$this->assertEquals( $expected[ 'packageFiles' ], $packageFilenames, 'Correct packageFiles added for ' . $testCase );

		// Script-only module will not have any styleFiles.
		$styleFilenames = [];
		if ( count( $styleFiles ) > 0 ) {
			$styleFilenames = array_map( static function ( $filepath ) use ( $testModule ) {
				return str_replace( $testModule::CODEX_MODULE_DIR, '', $filepath->getPath() );
			}, $styleFiles[ 'all' ] );
		}
		$this->assertEquals( $expected[ 'styles' ], $styleFilenames, 'Correct styleFiles added for ' . $testCase );
	}

	public function testMissingCodexComponentsDefinition() {
		$moduleDefinition = [
			'codexComponents' => [ 'CdxButton', 'CdxMessage' ]
		];

		$testModule = new class( $moduleDefinition ) extends CodexModule {
			public const CODEX_MODULE_DIR = 'tests/phpunit/data/resourceloader/codexModules/';
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
}

<?php

namespace MediaWiki\Tests\ResourceLoader;

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
						'_codex/useModelWrapper.js',
						'_codex/constants.js',
						'_codex/useSlotContents.js',
						'_codex/useWarnOnce.js',
						'_codex/useIconOnlyButton.js',
						'_codex/_plugin-vue_export-helper.js',
						'_codex/CdxButton.js',
						'_codex/Icon.js',
						'_codex/CdxMessage.js'
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
						'_codex/useModelWrapper.js',
						'_codex/constants.js',
						'_codex/useSlotContents.js',
						'_codex/useWarnOnce.js',
						'_codex/useIconOnlyButton.js',
						'_codex/_plugin-vue_export-helper.js',
						'_codex/CdxButton.js',
						'_codex/Icon.js',
						'_codex/CdxMessage.js'
					],
					'styles' => []
				]
			]
		];
	}

	/**
	 * @dataProvider provideModuleConfig
	 */
	public function testCodexSubset( $testCase, $moduleDefinition, $expected ) {
		$testModule = new class( $moduleDefinition ) extends CodexModule {
			public const CODEX_MODULE_DIR = 'tests/phpunit/data/resourceloader/codexModules/';
		};

		$context = $this->getResourceLoaderContext();
		$config = $context->getResourceLoader()->getConfig();
		$testModule->setConfig( $config );

		$packageFiles = $testModule->getPackageFiles( $context );
		// Style-only module will not have any packageFiles.
		$packageFilenames = isset( $packageFiles ) ? array_keys( $packageFiles[ 'files' ] ) : [];
		$this->assertEquals( $expected[ 'packageFiles' ], $packageFilenames, 'Correct packageFiles added for ' . $testCase );

		$styleFiles = $testModule->getStyleFiles( $context );
		// Script-only module will not have any styleFiles.
		$styleFilenames = [];
		if ( count( $styleFiles ) > 0 ) {
			$styleFilenames = array_map( static function ( $filepath ) use ( $testModule ) {
				return str_replace( $testModule::CODEX_MODULE_DIR, '', $filepath->getPath() );
			}, $styleFiles[ 'all' ] );
		}
		$this->assertEquals( $expected[ 'styles' ], $styleFilenames, 'Correct styleFiles added for ' . $testCase );
	}
}

<?php

namespace MediaWiki\Tests\Structure;

use ExtensionRegistry;
use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Settings\Source\FileSource;
use MediaWiki\Settings\Source\PhpSettingsSource;
use MediaWiki\Settings\Source\SettingsSource;
use MediaWiki\Shell\Shell;
use MediaWikiIntegrationTestCase;

/**
 * @coversNothing
 */
class SettingsTest extends MediaWikiIntegrationTestCase {

	public function testConfigSchemaIsLoadable() {
		$configBuilder = new ArrayConfigBuilder();
		$settingsBuilder = new SettingsBuilder(
			__DIR__ . '/../../..',
			$this->createNoOpMock( ExtensionRegistry::class ),
			$configBuilder,
			$this->createNoOpMock( PhpIniSink::class )
		);
		$settingsBuilder->loadFile( 'includes/config-schema.yaml' );
		$settingsBuilder->apply();

		// Assert we've read some random config value
		$this->assertTrue( $configBuilder->build()->has( 'Server' ) );
	}

	/**
	 * Check that core default settings validate against the schema
	 */
	public function testConfigSchemaDefaultsValidate() {
		$settingsBuilder = new SettingsBuilder(
			__DIR__ . '/../../..',
			$this->createNoOpMock( ExtensionRegistry::class ),
			new ArrayConfigBuilder(),
			$this->createNoOpMock( PhpIniSink::class )
		);
		$validationResult = $settingsBuilder->loadFile( 'includes/config-schema.yaml' )
			->apply()
			->validate();
		$this->assertArrayEquals( [], $validationResult->getErrors() );
	}

	/**
	 * Check that currently loaded settings validate against the schema.
	 */
	public function testCurrentSettingsValidate() {
		global $wgSettings;
		$result = $wgSettings->validate();
		$this->assertTrue( $result->isGood(), $result->__toString() );
	}

	public function provideConfigGeneration() {
		yield 'docs/Configuration.md' => [
			'script' => __DIR__ . '/../../../maintenance/generateConfigDoc.php',
			'expectedFile' => __DIR__ . '/../../../docs/Configuration.md',
		];
		yield 'incl/Configuration.md' => [
			'script' => __DIR__ . '/../../../maintenance/generateConfigSchema.php',
			'expectedFile' => __DIR__ . '/../../../includes/config-schema.php',
		];
	}

	/**
	 * @dataProvider provideConfigGeneration
	 */
	public function testConfigGeneration( string $script, string $expectedFile ) {
		$schemaGenerator = Shell::makeScriptCommand( $script, [ '--output', 'php://stdout' ] );

		$result = $schemaGenerator->execute();
		$this->assertSame( 0, $result->getExitCode(), 'Config generation must finish successfully' );
		$this->assertSame( '', $result->getStderr(), 'Config generation must not have errors' );

		$oldGeneratedSchema = file_get_contents( $expectedFile );

		$this->assertEquals(
			$oldGeneratedSchema,
			$result->getStdout(),
			'Configuration schema was changed. Rerun maintenance/generateConfigSchema.php script!'
		);
	}

	public function provideDefaultSettingsConsistency() {
		yield 'YAML' => [ new FileSource( 'includes/config-schema.yaml' ) ];
		yield 'PHP' => [ new PhpSettingsSource( 'includes/config-schema.php' ) ];
	}

	/**
	 * Check that the result of loading config-schema.yaml is the same as DefaultSettings.php
	 * This test can be removed when DefaultSettings.php is removed.
	 * @dataProvider provideDefaultSettingsConsistency
	 */
	public function testDefaultSettingsConsistency( SettingsSource $source ) {
		$defaultSettingsProps = ( static function () {
			require __DIR__ . '/../../../includes/DefaultSettings.php';
			$vars = get_defined_vars();
			unset( $vars['input'] );
			$result = [];
			foreach ( $vars as $key => $value ) {
				$result[substr( $key, 2 )] = $value;
			}
			return $result;
		} )();

		$configBuilder = new ArrayConfigBuilder();
		$settingsBuilder = new SettingsBuilder(
			__DIR__ . '/../../..',
			$this->createNoOpMock( ExtensionRegistry::class ),
			$configBuilder,
			$this->createNoOpMock( PhpIniSink::class )
		);
		$settingsBuilder->load( $source );
		$settingsBuilder->apply();

		foreach ( $defaultSettingsProps as $key => $value ) {
			if ( in_array( $key, [
				'Version', // deprecated alias to MW_VERSION
				'Conf', // instance of SiteConfiguration
				'AutoloadClasses', // conditionally initialized
				'StyleSheetPath', // Alias to another global
			] ) ) {
				continue;
			}
			$this->assertTrue( $configBuilder->build()->has( $key ), "Missing $key" );
			$this->assertEquals( $value, $configBuilder->build()->get( $key ), "Wrong value for $key\n" );
		}
	}
}

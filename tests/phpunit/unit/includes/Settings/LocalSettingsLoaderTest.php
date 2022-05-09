<?php

namespace MediaWiki\Tests\Unit\Settings;

use ExtensionRegistry;
use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\LocalSettingsLoader;
use MediaWiki\Settings\SettingsBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\LocalSettingsLoader
 */
class LocalSettingsLoaderTest extends TestCase {

	/**
	 * @param array $params
	 * @return SettingsBuilder
	 */
	private function newSettingsBuilder( $params = [] ): SettingsBuilder {
		return new SettingsBuilder(
			__DIR__,
			$params['extensionRegistry'] ?? $this->createMock( ExtensionRegistry::class ),
			$params['configBuilder'] ?? new ArrayConfigBuilder(),
			$params['phpIniSink'] ?? $this->createMock( PhpIniSink::class ),
			$params['cache'] ?? null
		);
	}

	public function provideLoadingFromFile() {
		$expected = [
			'SiteName' => 'TestSite',
			'HttpsPort' => 443,
			'Something' => 'TEST',
			'StyleDirectory' => '/test/skins',
			'ExtensionDirectory' => '/test/extensions',
			'ForeignUploadTargets' => [ 'local', 'acme' ],
			'ExtraLanguageCodes' => [ 'no' => 'nb', 'simple' => 'en' ],
			'Extra' => 'extra',
		];

		yield 'JSON' => [
			'fixtures/settings.json',
			$expected
		];
		yield 'PHP with variables' => [
			'fixtures/settings-with-variables.php',
			$expected
		];
	}

	/**
	 * @dataProvider provideLoadingFromFile
	 *
	 * @param string $file
	 * @param array $expected
	 */
	public function testLoadingFromFile( $file, $expected ) {
		$settings = $this->newSettingsBuilder();
		$settings->loadFile( 'fixtures/default-schema.json' );

		$loader = new LocalSettingsLoader( $settings, __DIR__ );
		$loader->loadLocalSettingsFile( $file );

		$config = $settings->getConfig();

		$this->assertFalse( $config->has( 'SillyStuff' ) );

		foreach ( $expected as $key => $value ) {
			$actual = $config->get( $key );

			if ( is_array( $value ) ) {
				ksort( $value );
				ksort( $actual );
			}
			$this->assertSame( $value, $actual, 'Setting: ' . $key );
		}
	}

	public function testLoadingTheSamePhpFileTwice() {
		$settings = $this->newSettingsBuilder();
		$settings->loadFile( 'fixtures/default-schema.json' );

		$loader = new LocalSettingsLoader( $settings, __DIR__ );
		$loader->loadLocalSettingsFile( 'fixtures/settings-with-variables.php' );

		$config1 = $settings->getConfig();

		// Now, do it again.
		$settings = $this->newSettingsBuilder();
		$settings->loadFile( 'fixtures/default-schema.json' );

		$loader = new LocalSettingsLoader( $settings, __DIR__ );
		$loader->loadLocalSettingsFile( 'fixtures/settings-with-variables.php' );

		$config2 = $settings->getConfig();

		// This would fail if require_once was used inside.
		$this->assertEquals( $config1, $config2 );
	}

}

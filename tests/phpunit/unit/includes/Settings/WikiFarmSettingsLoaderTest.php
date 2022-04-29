<?php

namespace phpunit\unit\includes\Settings;

use ExtensionRegistry;
use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Settings\WikiFarmSettingsLoader;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\WikiFarmSettingsLoader
 */
class WikiFarmSettingsLoaderTest extends TestCase {

	private static $site = null;

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

	public function provideWikiFarmSettings() {
		yield [
			[
				'WikiFarmSettingsDirectory' => __DIR__ . '/fixtures/sites',
				'WikiFarmSettingsExtension' => 'yaml',
				'WikiFarmSiteDetector' => [ self::class, 'fakeSiteDetector' ],
			],
			'alpha',
			[ 'SiteName' => 'Alpha Wiki' ]
		];
		yield [
			[
				'WikiFarmSettingsDirectory' => __DIR__ . '/fixtures/sites',
				'WikiFarmSettingsExtension' => 'json',
				'WikiFarmSiteDetector' => [ self::class, 'fakeSiteDetector' ],
			],
			'beta',
			[ 'SiteName' => 'Beta Wiki' ]
		];
	}

	/**
	 * @param array $config
	 * @param string $detect
	 * @param array $expected
	 *
	 * @dataProvider provideWikiFarmSettings
	 */
	public function testLoadWikiFarmSettings( $config, $detect, $expected ) {
		$settings = $this->newSettingsBuilder();

		$settings->putConfigValues( $config );

		self::$site = $detect;

		$loader = new WikiFarmSettingsLoader( $settings );
		$loader->loadWikiFarmSettings();

		$config = $settings->getConfig();

		foreach ( $expected as $name => $value ) {
			$this->assertSame( $value, $config->get( $name ) );
		}
	}

	public static function fakeSiteDetector() {
		return self::$site;
	}

}

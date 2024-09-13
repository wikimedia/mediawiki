<?php

namespace MediaWiki\Tests\Unit\Settings;

use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Settings\WikiFarmSettingsLoader;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \MediaWiki\Settings\WikiFarmSettingsLoader
 */
class WikiFarmSettingsLoaderTest extends MediaWikiUnitTestCase {

	/** @var array|null */
	private $originalServerVars = null;

	/**
	 * @before
	 */
	public function serverVarsSetUp() {
		$this->originalServerVars = $_SERVER;
	}

	/**
	 * @after
	 */
	public function serverVarsTearDown() {
		$_SERVER = $this->originalServerVars;
	}

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

	/**
	 * @return SettingsBuilder|MockObject
	 */
	private function fakeSettingsBuilder(): SettingsBuilder {
		$config = [
			MainConfigNames::WikiFarmSettingsDirectory => 'test',
			MainConfigNames::WikiFarmSettingsExtension => 'yaml',
		];
		$mock = $this->createMock( SettingsBuilder::class );
		$mock->method( 'getConfig' )->willReturn( new HashConfig( $config ) );
		$mock->method( 'fileExists' )->willReturn( true );

		return $mock;
	}

	public static function provideWikiFarmSettings() {
		yield [
			[
				'WikiFarmSettingsDirectory' => __DIR__ . '/fixtures/sites',
				'WikiFarmSettingsExtension' => 'yaml',
			],
			'alpha',
			[ 'SiteName' => 'Alpha Wiki' ]
		];
		yield [
			[
				'WikiFarmSettingsDirectory' => __DIR__ . '/fixtures/sites',
				'WikiFarmSettingsExtension' => 'json',
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

		$_SERVER['MW_WIKI_NAME'] = $detect;

		$loader = $this->newLoader( $settings );
		$loader->loadWikiFarmSettings();

		$config = $settings->getConfig();

		foreach ( $expected as $name => $value ) {
			$this->assertSame( $value, $config->get( $name ) );
		}
	}

	public function testDetectSettingsFileFromConstant() {
		$settings = $this->fakeSettingsBuilder();

		$settings->expects( $this->once() )
			->method( 'loadFile' )
			->with( 'test/AcmeWiki123.yaml' );

		$loader = $this->newLoader( $settings, 'AcmeWiki123' );
		$loader->loadWikiFarmSettings();
	}

	public function testDetectSettingsFileFromLegacyVar() {
		$settings = $this->fakeSettingsBuilder();

		$_SERVER['WIKI_NAME'] = 'test/FooBarWiki.yaml';

		$this->expectPHPError(
			E_USER_NOTICE,
			function () use ( $settings ) {
				// TODO: Assert that the file is actually loaded after the notice is triggered
				$loader = $this->newLoader( $settings );
				$loader->loadWikiFarmSettings();
			},
			'The WIKI_NAME server variable has been deprecated'
		);
	}

	/**
	 * @param SettingsBuilder $settings
	 * @param ?string $wikiNameConstantValue
	 *
	 * @return WikiFarmSettingsLoader
	 */
	private function newLoader( SettingsBuilder $settings, ?string $wikiNameConstantValue = null ) {
		$loader = new class( $settings, $wikiNameConstantValue ) extends WikiFarmSettingsLoader {
			private $wikiNameConstantValue;

			public function __construct( $settings, $wikiNameConstantValue ) {
				parent::__construct( $settings );
				$this->wikiNameConstantValue = $wikiNameConstantValue;
			}

			protected function getWikiNameConstant() {
				return $this->wikiNameConstantValue;
			}
		};

		return $loader;
	}

}

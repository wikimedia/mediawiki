<?php

namespace MediaWiki\Tests\ResourceLoader;

use Generator;
use InvalidArgumentException;
use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\FilePath;
use MediaWiki\ResourceLoader\SkinModule;
use ReflectionClass;
use Wikimedia\TestingAccessWrapper;

/**
 * @group ResourceLoader
 * @covers \MediaWiki\ResourceLoader\SkinModule
 */
class SkinModuleTest extends ResourceLoaderTestCase {
	public static function provideApplyFeaturesCompatibility() {
		return [
			'Alias for unset target (content-thumbnails)' => [
				[
					'content-thumbnails' => true,
				],
				[
					'content-media' => true,
				],
				true
			],
			'Alias with conflict (content-thumbnails)' => [
				[
					'content-thumbnails' => true,
					'content-media' => false,
				],
				[
					'content-media' => false,
				],
				true
			],
			'Alias that no-ops (legacy)' => [
				[
					'toc' => true,
					'legacy' => true,
				],
				[
					'toc' => true,
				],
				true
			],
			'content-links enables content-links-external if unset' => [
				[
					'content-links' => true,
				],
				[
					'content-links-external' => true,
					'content-links' => true,
				],
				true
			],
			'elements enables content-links if unset' => [
				[
					'elements' => true,
				],
				[
					'elements' => true,
					'content-links' => true,
				],
				true
			],
			'content-links does not change content-links-external if set' => [
				[
					'content-links-external' => false,
					'content-links' => true,
				],
				[
					'content-links-external' => false,
					'content-links' => true,
				],
				true
			],
			'list-form does not add unwanted defaults (aliases)' => [
				[
					'content-links' => true,
					'content-thumbnails' => true,
				],
				[
					'content-links' => true,
					'content-media' => true,
				],
				false
			],
			'list-form does not add unwanted defaults (no aliases)' => [
				[
					'elements' => true,
				],
				[
					'elements' => true,
				],
				false
			],
		];
	}

	/**
	 * @dataProvider provideApplyFeaturesCompatibility
	 */
	public function testApplyFeaturesCompatibility( array $features, array $expected, bool $optInPolicy ) {
		// Test protected method
		$class = TestingAccessWrapper::newFromClass( SkinModule::class );
		$actual = $class->applyFeaturesCompatibility( $features, $optInPolicy );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetAvailableLogos() {
		return [
			[
				[
					MainConfigNames::Logos => [],
					MainConfigNames::Logo => '/logo.png',
				],
				[
					'1x' => '/logo.png',
				]
			],
			[
				[
					MainConfigNames::Logos => [
						'svg' => '/logo.svg',
						'2x' => 'logo-2x.png'
					],
					MainConfigNames::Logo => '/logo.png',
				],
				[
					'svg' => '/logo.svg',
					'2x' => 'logo-2x.png',
					'1x' => '/logo.png',
				]
			],
			[
				[
					MainConfigNames::Logos => [
						'wordmark' => [
							'src' => '/logo-wordmark.png',
							'width' => 100,
							'height' => 15,
						],
						'1x' => '/logo.png',
						'svg' => '/logo.svg',
						'2x' => 'logo-2x.png'
					],
				],
				[
					'wordmark' => [
						'src' => '/logo-wordmark.png',
						'width' => 100,
						'height' => 15,
						'style' => 'width: 6.25em; height: 0.9375em;',
					],
					'1x' => '/logo.png',
					'svg' => '/logo.svg',
					'2x' => 'logo-2x.png',
				]
			]
		];
	}

	public static function provideGetLogoStyles() {
		return [
			[
				'features' => [],
				'logo' => '/logo.png',
				'expected' => [
					'all' => [ '.mw-wiki-logo { background-image: url(/logo.png); }' ],
				],
			],
			[
				'features' => [
					'screen' => '.example {}',
				],
				'logo' => '/logo.png',
				'expected' => [
					'screen' => '.example {}',
					'all' => [ '.mw-wiki-logo { background-image: url(/logo.png); }' ],
				],
			],
			[
				'features' => [],
				'logo' => [
					'1x' => '/logo.png',
					'1.5x' => '/logo@1.5x.png',
					'2x' => '/logo@2x.png',
				],
				'expected' => [
					'all' => [
						'.mw-wiki-logo { background-image: url(/logo.png); }',
					],
					'(-webkit-min-device-pixel-ratio: 1.5), (min-resolution: 1.5dppx), (min-resolution: 144dpi)' => [
						'.mw-wiki-logo { background-image: url(/logo@1.5x.png);background-size: 135px auto; }',
					],
					'(-webkit-min-device-pixel-ratio: 2), (min-resolution: 2dppx), (min-resolution: 192dpi)' => [
						'.mw-wiki-logo { background-image: url(/logo@2x.png);background-size: 135px auto; }',
					],
				],
			],
			[
				'features' => [],
				'logo' => [
					'1x' => '/logo.png',
					'svg' => '/logo.svg',
				],
				'expected' => [
					'all' => [
						'.mw-wiki-logo { background-image: url(/logo.svg); }',
						'.mw-wiki-logo { background-size: 135px auto; }',
					],
				],
			],
		];
		// phpcs:enable
	}

	/**
	 * @dataProvider provideGetLogoStyles
	 */
	public function testGenerateAndAppendLogoStyles( $features, $logo, $expected ) {
		$module = $this->getMockBuilder( SkinModule::class )
			->onlyMethods( [ 'getLogoData' ] )
			->getMock();
		$module->expects( $this->atLeast( 1 ) )->method( 'getLogoData' )
			->willReturn( $logo );
		$module->setConfig( new HashConfig( self::getSettings() ) );

		$ctx = $this->createMock( Context::class );

		$this->assertEquals(
			$expected,
			$module->generateAndAppendLogoStyles( $features, $ctx )
		);
	}

	/**
	 * @dataProvider provideGetAvailableLogos
	 */
	public function testGetAvailableLogos( $config, $expected ) {
		$logos = SkinModule::getAvailableLogos( new HashConfig( $config ) );
		$this->assertSame( $expected, $logos );
	}

	public function testGetAvailableLogosRuntimeException() {
		$logos = SkinModule::getAvailableLogos( new HashConfig( [
			MainConfigNames::Logo => false,
			MainConfigNames::Logos => false,
		] ) );
		$this->assertSame( [], $logos );
	}

	public function testIsKnownEmpty() {
		$module = new SkinModule();
		$ctx = $this->createMock( Context::class );

		$this->assertFalse( $module->isKnownEmpty( $ctx ) );
	}

	/**
	 * @dataProvider provideGetLogoData
	 */
	public function testGetLogoData( $config, $expected ) {
		// Allow testing of protected method
		$module = TestingAccessWrapper::newFromObject( new SkinModule() );

		$this->assertEquals(
			$expected,
			$module->getLogoData( new HashConfig( $config ) )
		);
	}

	public static function provideGetLogoData() {
		return [
			'wordmark' => [
				'config' => [
					MainConfigNames::ResourceBasePath => '/w',
					MainConfigNames::Logos => [
						'1x' => '/img/default.png',
						'wordmark' => [
							'src' => '/img/wordmark.png',
							'width' => 120,
							'height' => 20,
						],
					],
				],
				'expected' => '/img/default.png',
			],
			'simple' => [
				'config' => [
					MainConfigNames::ResourceBasePath => '/w',
					MainConfigNames::Logos => [
						'1x' => '/img/default.png',
					],
				],
				'expected' => '/img/default.png',
			],
			'default and 2x' => [
				'config' => [
					MainConfigNames::ResourceBasePath => '/w',
					MainConfigNames::Logos => [
						'1x' => '/img/default.png',
						'2x' => '/img/two-x.png',
					],
				],
				'expected' => [
					'1x' => '/img/default.png',
					'2x' => '/img/two-x.png',
				],
			],
			'default and all HiDPIs' => [
				'config' => [
					MainConfigNames::ResourceBasePath => '/w',
					MainConfigNames::Logos => [
						'1x' => '/img/default.png',
						'1.5x' => '/img/one-point-five.png',
						'2x' => '/img/two-x.png',
					],
				],
				'expected' => [
					'1x' => '/img/default.png',
					'1.5x' => '/img/one-point-five.png',
					'2x' => '/img/two-x.png',
				],
			],
			'default and SVG' => [
				'config' => [
					MainConfigNames::ResourceBasePath => '/w',
					MainConfigNames::Logos => [
						'1x' => '/img/default.png',
						'svg' => '/img/vector.svg',
					],
				],
				'expected' => [
					'1x' => '/img/default.png',
					'svg' => '/img/vector.svg',
				],
			],
			'everything' => [
				'config' => [
					MainConfigNames::ResourceBasePath => '/w',
					MainConfigNames::Logos => [
						'1x' => '/img/default.png',
						'1.5x' => '/img/one-point-five.png',
						'2x' => '/img/two-x.png',
						'svg' => '/img/vector.svg',
					],
				],
				'expected' => [
					'1x' => '/img/default.png',
					'svg' => '/img/vector.svg',
				],
			],
			'versioned url' => [
				'config' => [
					MainConfigNames::ResourceBasePath => '/w',
					MainConfigNames::UploadPath => '/w/images',
					MainConfigNames::Logos => [
						'1x' => '/w/tests/phpunit/data/media/test.jpg',
					],
				],
				'expected' => '/w/tests/phpunit/data/media/test.jpg?edcf2',
			],
		];
	}

	/**
	 * @dataProvider providePreloadLinks
	 */
	public function testPreloadLinkHeaders( $config, $lang, $result ) {
		$ctx = $this->createMock( Context::class );
		$ctx->method( 'getLanguage' )->willReturn( $lang );
		$module = new SkinModule();
		$module->setConfig( new HashConfig( $config + [
			MainConfigNames::ResourceBasePath => '/w',
			MainConfigNames::Logo => false,
		] + self::getSettings() ) );

		$this->assertEquals( [ $result ], $module->getHeaders( $ctx ) );
	}

	public static function providePreloadLinks() {
		return [
			[
				[
					MainConfigNames::Logos => [
						'1x' => '/img/default.png',
						'1.5x' => '/img/one-point-five.png',
						'2x' => '/img/two-x.png',
					],
				],
				'en',
				'Link: </img/default.png>;rel=preload;as=image;media=' .
				'not all and (min-resolution: 1.5dppx),' .
				'</img/one-point-five.png>;rel=preload;as=image;media=' .
				'(min-resolution: 1.5dppx) and (max-resolution: 1.999999dppx),' .
				'</img/two-x.png>;rel=preload;as=image;media=(min-resolution: 2dppx)'
			],
			[
				[
					MainConfigNames::Logos => [ '1x' => '/img/default.png' ],
				],
				'en',
				'Link: </img/default.png>;rel=preload;as=image'
			],
			[
				[
					MainConfigNames::Logos => [
						'1x' => '/img/default.png',
						'2x' => '/img/two-x.png',
					],
				],
				'en',
				'Link: </img/default.png>;rel=preload;as=image;media=' .
				'not all and (min-resolution: 2dppx),' .
				'</img/two-x.png>;rel=preload;as=image;media=(min-resolution: 2dppx)'
			],
			[
				[
					MainConfigNames::Logos => [
						'1x' => '/img/default.png',
						'svg' => '/img/vector.svg',
					],
				],
				'en',
				'Link: </img/vector.svg>;rel=preload;as=image'

			],
			[
				[
					MainConfigNames::Logos => [ '1x' => '/w/tests/phpunit/data/media/test.jpg' ],
					MainConfigNames::UploadPath => '/w/images',
				],
				'en',
				'Link: </w/tests/phpunit/data/media/test.jpg?edcf2>;rel=preload;as=image',
			],
			[
				[
					MainConfigNames::Logos => [
						'1x' => '/img/default.png',
						'1.5x' => '/img/one-point-five.png',
						'2x' => '/img/two-x.png',
						'variants' => [
							'zh-hans' => [
								'1x' => '/img/default-zh-hans.png',
								'1.5x' => '/img/one-point-five-zh-hans.png',
							]
						]
					],
				],
				'zh-hans',
				'Link: </img/default-zh-hans.png>;rel=preload;as=image;media=' .
				'not all and (min-resolution: 1.5dppx),' .
				'</img/one-point-five-zh-hans.png>;rel=preload;as=image;media=' .
				'(min-resolution: 1.5dppx) and (max-resolution: 1.999999dppx),' .
				'</img/two-x.png>;rel=preload;as=image;media=(min-resolution: 2dppx)'
			],
		];
	}

	public function testNoPreloadLogos() {
		$module = new SkinModule( [ 'features' => [ 'logo' => false ] ] );
		$context =
			$this->createMock( Context::class );
		$preloadLinks = $module->getPreloadLinks( $context );
		$this->assertArrayEquals( [], $preloadLinks );
	}

	public function testPreloadLogos() {
		$module = new SkinModule();
		$module->setConfig( self::getMinimalConfig() );
		$context = $this->createMock( Context::class );

		$preloadLinks = $module->getPreloadLinks( $context );
		$this->assertNotSameSize( [], $preloadLinks );
	}

	/**
	 * Covers SkinModule::FEATURE_FILES, but not annotatable.
	 *
	 * @dataProvider provideFeatureFiles
	 * @param string $file
	 */
	public function testFeatureFilesExist( string $file ): void {
		$this->assertFileExists( $file );
	}

	public static function provideFeatureFiles(): Generator {
		global $IP;

		$featureFiles = ( new ReflectionClass( SkinModule::class ) )
			->getConstant( 'FEATURE_FILES' );

		foreach ( $featureFiles as $feature => $files ) {
			foreach ( $files as $media => $stylesheets ) {
				foreach ( $stylesheets as $stylesheet ) {
					yield "$feature: $media: $stylesheet" => [ "$IP/$stylesheet" ];
				}
			}
		}
	}

	public static function getSkinFeaturePath( $feature, $mediaType ) {
		global $IP;
		$featureFiles = ( new ReflectionClass( SkinModule::class ) )->getConstant( 'FEATURE_FILES' );
		return new FilePath( $featureFiles[ $feature ][ $mediaType ][ 0 ], $IP, '/w' );
	}

	public static function provideGetFeatureFilePathsOrder() {
		return [
			[
				'The "logo" skin-feature is loaded when the "features" key is absent',
				[],
				[
					'all' => [ self::getSkinFeaturePath( 'logo', 'all' ) ],
					'print' => [ self::getSkinFeaturePath( 'logo', 'print' ) ],
				],
			],
			[
				'The "normalize" skin-feature is always output first',
				[
					'features' => [ 'elements', 'normalize' ],
				],
				[
					'all' => [ self::getSkinFeaturePath( 'normalize', 'all' ) ],
					'screen' => [ self::getSkinFeaturePath( 'elements', 'screen' ) ],
					'print' => [ self::getSkinFeaturePath( 'elements', 'print' ) ],
				],
			],
			[
				'Empty media query blocks are not included in output',
				[
					'features' => [
						'accessibility' => false,
						'content-body' => false,
						'interface-core' => false,
						'toc' => false
					],
				],
				[],
			],
			[
				'Empty "features" key outputs default skin-features',
				[
					'features' => [],
				],
				[
					'all' => [
						self::getSkinFeaturePath( 'accessibility', 'all' ),
						self::getSkinFeaturePath( 'toc', 'all' )
					],
					'screen' => [
						self::getSkinFeaturePath( 'content-body', 'screen' ),
						self::getSkinFeaturePath( 'interface-core', 'screen' ),
						self::getSkinFeaturePath( 'toc', 'screen' ),
					],
					'print' => [
						self::getSkinFeaturePath( 'content-body', 'print' ),
						self::getSkinFeaturePath( 'interface-core', 'print' ),
						self::getSkinFeaturePath( 'toc', 'print' )
					]
				],
			],
			[
				'skin-features are output in the order defined in SkinModule.php',
				[
					'features' => [ 'interface-message-box', 'normalize', 'accessibility' ],
				],
				[
					'all' => [
						self::getSkinFeaturePath( 'accessibility', 'all' ),
						self::getSkinFeaturePath( 'normalize', 'all' ),
						self::getSkinFeaturePath( 'interface-message-box', 'all' )
					],
				]
			]
		];
	}

	/**
	 * @dataProvider provideGetFeatureFilePathsOrder
	 * @param string $msg to show for debugging
	 * @param array $skinModuleConfig
	 * @param array $expectedStyleOrder
	 */
	public function testGetFeatureFilePathsOrder(
		$msg, $skinModuleConfig, $expectedStyleOrder
	): void {
		$module = new SkinModule( $skinModuleConfig );
		$module->setConfig( self::getMinimalConfig() );

		$actual = $module->getFeatureFilePaths();

		$this->assertEquals(
			array_values( $expectedStyleOrder ),
			array_values( $actual )
		);
	}

	public static function provideInvalidFeatures() {
		yield 'listed unknown' => [
			[ 'logo', 'unknown' ],
		];

		yield 'enabled unknown' => [
			[
				'logo' => true,
				'toc' => false,
				'unknown' => true,
			],
		];

		yield 'disabled unknown' => [
			[
				'logo' => true,
				'toc' => false,
				'unknown' => false,
			],
		];
	}

	/**
	 * @dataProvider provideInvalidFeatures
	 */
	public function testConstructInvalidFeatures( array $features ) {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Feature 'unknown' is not recognised" );
		$module = new SkinModule( [
			'features' => $features,
		] );
	}
}

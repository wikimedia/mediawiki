<?php

namespace MediaWiki\Tests\ResourceLoader;

use Generator;
use HashConfig;
use InvalidArgumentException;
use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\FilePath;
use MediaWiki\ResourceLoader\SkinModule;
use ReflectionClass;
use ResourceLoaderTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @group ResourceLoader
 * @covers \MediaWiki\ResourceLoader\SkinModule
 */
class SkinModuleTest extends ResourceLoaderTestCase {
	public static function provideApplyFeaturesCompatibility() {
		return [
			[
				[
					'content-thumbnails' => true,
				],
				[
					'content-media' => true,
				],
				true,
				'The `content-thumbnails` feature is mapped to `content-media`.'
			],
			[
				[
					'content-parser-output' => true,
				],
				[
					'content-body' => true,
				],
				true,
				'The new `content-parser-output` module was renamed to `content-body`.'
			],
			[
				[
					'content' => true,
				],
				[
					'content-media' => true,
				],
				true,
				'The `content` feature is mapped to `content-media`.'
			],
			[
				[
					'content-links' => true,
				],
				[
					'content-links-external' => true,
					'content-links' => true,
				],
				true,
				'The `content-links` feature will also enable `content-links-external` if it not specified.'
			],
			[
				[
					'element' => true,
				],
				[
					'element' => true,
					'content-links' => true,
				],
				true,
				'The `element` feature will turn on `content-links` if not specified.'
			],
			[
				[
					'content-links-external' => false,
					'content-links' => true,
				],
				[
					'content-links-external' => false,
					'content-links' => true,
				],
				true,
				'The `content-links` feature has no impact on content-links-external value.'
			],
			[
				[
					'content-links' => true,
					'content-thumbnails' => true,
				],
				[
					'content-links' => true,
					'content-media' => true,
				],
				false,
				'applyFeaturesCompatibility should not opt the skin into things it does not want.' .
					'It should only rename features.'
			],
			[
				[
					'element' => true,
				],
				[
					'element' => true,
				],
				false,
				'applyFeaturesCompatibility should not opt the skin into things it does not want.'
			],
		];
	}

	/**
	 * @dataProvider provideApplyFeaturesCompatibility
	 */
	public function testApplyFeaturesCompatibility( array $features, array $expected, bool $optInPolicy, $msg ) {
		// Test protected method
		$class = TestingAccessWrapper::newFromClass( SkinModule::class );
		$actual = $class->applyFeaturesCompatibility( $features, $optInPolicy );
		$this->assertEquals( $expected, $actual, $msg );
	}

	public static function provideGetAvailableLogos() {
		return [
			[
				[
					'Logos' => [],
					'Logo' => '/logo.png',
				],
				[
					'1x' => '/logo.png',
				]
			],
			[
				[
					'Logos' => [
						'svg' => '/logo.svg',
						'2x' => 'logo-2x.png'
					],
					'Logo' => '/logo.png',
				],
				[
					'svg' => '/logo.svg',
					'2x' => 'logo-2x.png',
					'1x' => '/logo.png',
				]
			],
			[
				[
					'Logos' => [
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

	public static function provideGetStyles() {
		return [
			[
				'parent' => [],
				'logo' => '/logo.png',
				'expected' => [
					'all' => [ '.mw-wiki-logo { background-image: url(/logo.png); }' ],
				],
			],
			[
				'parent' => [
					'screen' => '.example {}',
				],
				'logo' => '/logo.png',
				'expected' => [
					'screen' => [ '.example {}' ],
					'all' => [ '.mw-wiki-logo { background-image: url(/logo.png); }' ],
				],
			],
			[
				'parent' => [],
				'logo' => [
					'1x' => '/logo.png',
					'1.5x' => '/logo@1.5x.png',
					'2x' => '/logo@2x.png',
				],
				'expected' => [
					'all' => [ <<<CSS
.mw-wiki-logo { background-image: url(/logo.png); }
CSS
					],
					'(-webkit-min-device-pixel-ratio: 1.5), (min-resolution: 1.5dppx), (min-resolution: 144dpi)' => [ <<<CSS
.mw-wiki-logo { background-image: url(/logo@1.5x.png);background-size: 135px auto; }
CSS
					],
					'(-webkit-min-device-pixel-ratio: 2), (min-resolution: 2dppx), (min-resolution: 192dpi)' => [ <<<CSS
.mw-wiki-logo { background-image: url(/logo@2x.png);background-size: 135px auto; }
CSS
					],
				],
			],
			[
				'parent' => [],
				'logo' => [
					'1x' => '/logo.png',
					'svg' => '/logo.svg',
				],
				'expected' => [
					'all' => [ <<<CSS
.mw-wiki-logo { background-image: url(/logo.png); }
CSS
					, <<<CSS
.mw-wiki-logo { background-image: linear-gradient(transparent, transparent), url(/logo.svg);background-size: 135px auto; }
CSS
					],
				],
			],
		];
		// phpcs:enable
	}

	/**
	 * @dataProvider provideGetStyles
	 */
	public function testGetStyles( $parent, $logo, $expected ) {
		$module = $this->getMockBuilder( SkinModule::class )
			->onlyMethods( [ 'readStyleFiles', 'getLogoData' ] )
			->getMock();
		$module->expects( $this->once() )->method( 'readStyleFiles' )
			->willReturn( $parent );
		$module->expects( $this->once() )->method( 'getLogoData' )
			->willReturn( $logo );
		$module->setConfig( new HashConfig( [
			'ParserEnableLegacyMediaDOM' => false,
		] + self::getSettings() ) );

		$ctx = $this->createMock( Context::class );

		$this->assertEquals(
			$expected,
			$module->getStyles( $ctx )
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
			'Logo' => false,
			'Logos' => false,
			'LogoHD' => false,
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

	public function provideGetLogoData() {
		return [
			'wordmark' => [
				'config' => [
					'BaseDirectory' => MW_INSTALL_PATH,
					'ResourceBasePath' => '/w',
					'Logos' => [
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
					'BaseDirectory' => MW_INSTALL_PATH,
					'ResourceBasePath' => '/w',
					'Logos' => [
						'1x' => '/img/default.png',
					],
				],
				'expected' => '/img/default.png',
			],
			'default and 2x' => [
				'config' => [
					'BaseDirectory' => MW_INSTALL_PATH,
					'ResourceBasePath' => '/w',
					'Logos' => [
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
					'BaseDirectory' => MW_INSTALL_PATH,
					'ResourceBasePath' => '/w',
					'Logos' => [
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
					'BaseDirectory' => MW_INSTALL_PATH,
					'ResourceBasePath' => '/w',
					'Logos' => [
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
					'BaseDirectory' => MW_INSTALL_PATH,
					'ResourceBasePath' => '/w',
					'Logos' => [
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
					'BaseDirectory' => dirname( dirname( __DIR__ ) ) . '/data/media',
					'ResourceBasePath' => '/w',
					'UploadPath' => '/w/images',
					'Logos' => [
						'1x' => '/w/test.jpg',
					],
				],
				'expected' => '/w/test.jpg?edcf2',
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
			'BaseDirectory' => '/dummy',
			'ResourceBasePath' => '/w',
			'Logo' => false,
			'LogoHD' => false,
		] + self::getSettings() ) );

		$this->assertEquals( [ $result ], $module->getHeaders( $ctx ) );
	}

	public function providePreloadLinks() {
		return [
			[
				[
					'Logos' => [
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
					'Logos' => [
						'1x' => '/img/default.png',
					],
				],
				'en',
				'Link: </img/default.png>;rel=preload;as=image'
			],
			[
				[
					'Logos' => [
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
					'Logos' => [
						'1x' => '/img/default.png',
						'svg' => '/img/vector.svg',
					],
				],
				'en',
				'Link: </img/vector.svg>;rel=preload;as=image'

			],
			[
				[
					'BaseDirectory' => dirname( dirname( __DIR__ ) ) . '/data/media',
					'Logos' => [
						'1x' => '/w/test.jpg',
					],
					'UploadPath' => '/w/images',
				],
				'en',
				'Link: </w/test.jpg?edcf2>;rel=preload;as=image',
			],
			[
				[
					'Logos' => [
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

	public function provideFeatureFiles(): Generator {
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

	public static function provideGetStyleFilesFeatureStylesOrder() {
		global $IP;
		$featureFiles = ( new ReflectionClass( SkinModule::class ) )
			->getConstant( 'FEATURE_FILES' );

		$normalizePath = new FilePath(
			$featureFiles['normalize']['all'][0],
			$IP,
			'/w'
		);
		$elementsPath = new FilePath(
			$featureFiles['elements']['screen'][0],
			$IP,
			'/w'
		);
		$cbPath = new FilePath(
			$featureFiles['content-body']['screen'][0],
			$IP,
			'/w'
		);

		return [
			[
				[ 'elements', 'normalize' ],
				[
					'test.styles/styles.css' => [
						'media' => 'screen'
					]
				],
				[ $normalizePath ],
				[ $elementsPath, 'test.styles/styles.css' ],
				'opt-out by default policy results in correct order'
			],
			[
				[
					'accessibility' => false,
					'content-body' => false,
					'elements' => true,
					'normalize' => true,
					'interface-core' => false,
					'toc' => false,
				],
				[
					'test.styles/styles.css' => [
						'media' => 'screen'
					]
				],
				[ $normalizePath ],
				[ $elementsPath, 'test.styles/styles.css' ],
				'opt-in by default policy results in correct order'
			],

			[
				[ 'content-parser-output' ],
				[ 'test.styles/all.css' ],
				[
					$cbPath
				],
				[
					'test.styles/all.css'
				],
				'content-parser-output mapped to content-body styles'
			],

			[
				[ 'normalize' ],
				[ 'test.styles/styles.css' => [ 'media' => 'screen' ] ],
				[ $normalizePath ],
				[ 'test.styles/styles.css' ],
				'module provided styles come after skin defined'
			],
		];
	}

	/**
	 * @dataProvider provideGetStyleFilesFeatureStylesOrder
	 * @param array $features
	 * @param array $styles
	 * @param array $expectedAllStyles array of styles
	 * @param array $expectedScreenStyles array of styles
	 * @param string $msg to show for debugging
	 */
	public function testGetStyleFilesFeatureStylesOrder(
		$features, $styles, $expectedAllStyles, $expectedScreenStyles, $msg
	): void {
		$ctx = $this->createMock( Context::class );
		$module = new SkinModule(
			[
				// The ordering should be controlled by ResourceLoaderSkinModule
				// `normalize` will be outputted before `elements` despite the ordering
				'features' => $features,
				'styles' => $styles,
			]
		);
		$module->setConfig( self::getMinimalConfig() );

		$expected = [
			'all' => $expectedAllStyles,
			'screen' => $expectedScreenStyles,
		];

		$actual = $module->getStyleFiles( $ctx );
		unset( $actual['print'] ); // not testing print for now
		$this->assertEquals(
			array_values( $expected ),
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

		yield 'disbled unknown' => [
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

<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group ResourceLoader
 */
class ResourceLoaderSkinModuleTest extends MediaWikiIntegrationTestCase {

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
						'wordmark' => '/logo-wordmark.png',
						'1x' => '/logo.png',
						'svg' => '/logo.svg',
						'2x' => 'logo-2x.png'
					],
				],
				[
					'wordmark' => '/logo-wordmark.png',
					'1x' => '/logo.png',
					'svg' => '/logo.svg',
					'2x' => 'logo-2x.png',
				]
			]
		];
	}

	public static function provideGetStyles() {
		// phpcs:disable Generic.Files.LineLength
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
					'(-webkit-min-device-pixel-ratio: 1.5), (min--moz-device-pixel-ratio: 1.5), (min-resolution: 1.5dppx), (min-resolution: 144dpi)' => [ <<<CSS
.mw-wiki-logo { background-image: url(/logo@1.5x.png);background-size: 135px auto; }
CSS
					],
					'(-webkit-min-device-pixel-ratio: 2), (min--moz-device-pixel-ratio: 2), (min-resolution: 2dppx), (min-resolution: 192dpi)' => [ <<<CSS
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
.mw-wiki-logo { background-image: -webkit-linear-gradient(transparent, transparent), url(/logo.svg); background-image: linear-gradient(transparent, transparent), url(/logo.svg);background-size: 135px auto; }
CSS
					],
				],
			],
		];
		// phpcs:enable
	}

	/**
	 * @dataProvider provideGetStyles
	 * @covers ResourceLoaderSkinModule
	 */
	public function testGetStyles( $parent, $logo, $expected ) {
		$module = $this->getMockBuilder( ResourceLoaderSkinModule::class )
			->setMethods( [ 'readStyleFiles', 'getConfig', 'getLogoData' ] )
			->getMock();
		$module->expects( $this->once() )->method( 'readStyleFiles' )
			->willReturn( $parent );
		$module->expects( $this->once() )->method( 'getConfig' )
			->willReturn( new HashConfig() );
		$module->expects( $this->once() )->method( 'getLogoData' )
			->willReturn( $logo );

		$ctx = $this->getMockBuilder( ResourceLoaderContext::class )
			->disableOriginalConstructor()->getMock();

		$this->assertEquals(
			$expected,
			$module->getStyles( $ctx )
		);
	}

	/**
	 * @dataProvider provideGetAvailableLogos
	 * @covers ResourceLoaderSkinModule::getAvailableLogos
	 */
	public function testGetAvailableLogos( $config, $expected ) {
		$logos = ResourceLoaderSkinModule::getAvailableLogos( new HashConfig( $config ) );
		$this->assertSame( $logos, $expected );
	}

	/**
	 * @covers ResourceLoaderSkinModule::getAvailableLogos
	 */
	public function testGetAvailableLogosRuntimeException() {
		$this->expectException( \RuntimeException::class );
		ResourceLoaderSkinModule::getAvailableLogos( new HashConfig( [
			'Logo' => false,
			'Logos' => false,
			'LogoHD' => false,
		] ) );
	}

	/**
	 * @covers ResourceLoaderSkinModule::isKnownEmpty
	 */
	public function testIsKnownEmpty() {
		$module = $this->getMockBuilder( ResourceLoaderSkinModule::class )
			->disableOriginalConstructor()->setMethods( null )->getMock();
		$ctx = $this->getMockBuilder( ResourceLoaderContext::class )
			->disableOriginalConstructor()->getMock();

		$this->assertFalse( $module->isKnownEmpty( $ctx ) );
	}

	/**
	 * @dataProvider provideGetLogoData
	 * @covers ResourceLoaderSkinModule::getLogoData
	 */
	public function testGetLogoData( $config, $expected, $baseDir = null ) {
		if ( $baseDir ) {
			$this->setMwGlobals( 'IP', $baseDir );
		}
		// Allow testing of protected method
		$module = TestingAccessWrapper::newFromObject( new ResourceLoaderSkinModule() );

		$this->assertEquals(
			$expected,
			$module->getLogoData( new HashConfig( $config ) )
		);
	}

	public function provideGetLogoData() {
		return [
			'wordmark' => [
				'config' => [
					'ResourceBasePath' => '/w',
					'Logos' => [
						'1x' => '/img/default.png',
						'wordmark' => '/img/wordmark.png',
					],
				],
				'expected' => '/img/default.png',
			],
			'simple' => [
				'config' => [
					'ResourceBasePath' => '/w',
					'Logos' => [
						'1x' => '/img/default.png',
					],
				],
				'expected' => '/img/default.png',
			],
			'default and 2x' => [
				'config' => [
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
					'ResourceBasePath' => '/w',
					'UploadPath' => '/w/images',
					'Logos' => [
						'1x' => '/w/test.jpg',
					],
				],
				'expected' => '/w/test.jpg?edcf2',
				'baseDir' => dirname( dirname( __DIR__ ) ) . '/data/media',
			],
		];
	}

	/**
	 * @dataProvider providePreloadLinks
	 * @covers ResourceLoaderSkinModule::getPreloadLinks
	 * @covers ResourceLoaderSkinModule::getLogoPreloadlinks
	 * @covers ResourceLoaderSkinModule::getLogoData
	 */
	public function testPreloadLinkHeaders( $config, $result ) {
		$this->setMwGlobals( $config );
		$ctx = $this->getMockBuilder( ResourceLoaderContext::class )
			->disableOriginalConstructor()->getMock();
		$module = new ResourceLoaderSkinModule();

		$this->assertEquals( [ $result ], $module->getHeaders( $ctx ) );
	}

	public function providePreloadLinks() {
		return [
			[
				[
					'wgResourceBasePath' => '/w',
					'wgLogo' => false,
					'wgLogoHD' => false,
					'wgLogos' => [
						'1x' => '/img/default.png',
						'1.5x' => '/img/one-point-five.png',
						'2x' => '/img/two-x.png',
					],
				],
				'Link: </img/default.png>;rel=preload;as=image;media=' .
				'not all and (min-resolution: 1.5dppx),' .
				'</img/one-point-five.png>;rel=preload;as=image;media=' .
				'(min-resolution: 1.5dppx) and (max-resolution: 1.999999dppx),' .
				'</img/two-x.png>;rel=preload;as=image;media=(min-resolution: 2dppx)'
			],
			[
				[
					'wgResourceBasePath' => '/w',
					'wgLogo' => false,
					'wgLogoHD' => false,
					'wgLogos' => [
						'1x' => '/img/default.png',
					],
				],
				'Link: </img/default.png>;rel=preload;as=image'
			],
			[
				[
					'wgResourceBasePath' => '/w',
					'wgLogo' => false,
					'wgLogoHD' => false,
					'wgLogos' => [
						'1x' => '/img/default.png',
						'2x' => '/img/two-x.png',
					],
				],
				'Link: </img/default.png>;rel=preload;as=image;media=' .
				'not all and (min-resolution: 2dppx),' .
				'</img/two-x.png>;rel=preload;as=image;media=(min-resolution: 2dppx)'
			],
			[
				[
					'wgResourceBasePath' => '/w',
					'wgLogo' => false,
					'wgLogoHD' => false,
					'wgLogos' => [
						'1x' => '/img/default.png',
						'svg' => '/img/vector.svg',
					],
				],
				'Link: </img/vector.svg>;rel=preload;as=image'

			],
			[
				[
					'wgResourceBasePath' => '/w',
					'wgLogo' => false,
					'wgLogoHD' => false,
					'wgLogos' => [
						'1x' => '/w/test.jpg',
					],
					'wgUploadPath' => '/w/images',
					'IP' => dirname( dirname( __DIR__ ) ) . '/data/media',
				],
				'Link: </w/test.jpg?edcf2>;rel=preload;as=image',
			],
		];
	}

	/**
	 * Covers ResourceLoaderSkinModule::FEATURE_FILES, but not annotatable.
	 *
	 * @dataProvider provideFeatureFiles
	 * @coversNothing
	 *
	 * @param string $file
	 */
	public function testFeatureFilesExist( string $file ) : void {
		$this->assertFileExists( $file );
	}

	public function provideFeatureFiles() : Generator {
		global $IP;

		$featureFiles = ( new ReflectionClass( ResourceLoaderSkinModule::class ) )
			->getConstant( 'FEATURE_FILES' );

		foreach ( $featureFiles as $feature => $files ) {
			foreach ( $files as $media => $stylesheets ) {
				foreach ( $stylesheets as $stylesheet ) {
					yield "$feature: $media: $stylesheet" => [ "$IP/$stylesheet" ];
				}
			}
		}
	}
}

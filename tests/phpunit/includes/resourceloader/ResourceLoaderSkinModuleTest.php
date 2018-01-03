<?php

/**
 * @group ResourceLoader
 */
class ResourceLoaderSkinModuleTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

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
	 * @covers ResourceLoaderSkinModule::normalizeStyles
	 * @covers ResourceLoaderSkinModule::getStyles
	 */
	public function testGetStyles( $parent, $logo, $expected ) {
		$module = $this->getMockBuilder( ResourceLoaderSkinModule::class )
			->disableOriginalConstructor()
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
	 * @dataProvider provideGetLogo
	 * @covers ResourceLoaderSkinModule::getLogo
	 */
	public function testGetLogo( $config, $expected, $baseDir = null ) {
		if ( $baseDir ) {
			$oldIP = $GLOBALS['IP'];
			$GLOBALS['IP'] = $baseDir;
			$teardown = new Wikimedia\ScopedCallback( function () use ( $oldIP ) {
				$GLOBALS['IP'] = $oldIP;
			} );
		}

		$this->assertEquals(
			$expected,
			ResourceLoaderSkinModule::getLogo( new HashConfig( $config ) )
		);
	}

	public function provideGetLogo() {
		return [
			'simple' => [
				'config' => [
					'ResourceBasePath' => '/w',
					'Logo' => '/img/default.png',
					'LogoHD' => false,
				],
				'expected' => '/img/default.png',
			],
			'default and 2x' => [
				'config' => [
					'ResourceBasePath' => '/w',
					'Logo' => '/img/default.png',
					'LogoHD' => [
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
					'Logo' => '/img/default.png',
					'LogoHD' => [
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
					'Logo' => '/img/default.png',
					'LogoHD' => [
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
					'Logo' => '/img/default.png',
					'LogoHD' => [
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
					'Logo' => '/w/test.jpg',
					'LogoHD' => false,
					'UploadPath' => '/w/images',
				],
				'expected' => '/w/test.jpg?edcf2',
				'baseDir' => dirname( dirname( __DIR__ ) ) . '/data/media',
			],
		];
	}
}

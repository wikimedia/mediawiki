<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group ResourceLoader
 */
class ResourceLoaderImageModuleTest extends ResourceLoaderTestCase {

	public static $commonImageData = [
		'abc' => 'abc.gif',
		'def' => [
			'file' => 'def.svg',
			'variants' => [ 'destructive' ],
		],
		'ghi' => [
			'file' => [
				'ltr' => 'ghi.svg',
				'rtl' => 'jkl.svg'
			],
		],
		'mno' => [
			'file' => [
				'ltr' => 'mno-ltr.svg',
				'rtl' => 'mno-rtl.svg',
				'lang' => [
					'he' => 'mno-ltr.svg',
				]
			],
		],
		'pqr' => [
			'file' => [
				'default' => 'pqr-a.svg',
				'lang' => [
					'en' => 'pqr-b.svg',
					'ar,de' => 'pqr-f.svg',
				]
			],
		]
	];

	public static $commonImageVariants = [
		'invert' => [
			'color' => '#FFFFFF',
			'global' => true,
		],
		'primary' => [
			'color' => '#598AD1',
		],
		'constructive' => [
			'color' => '#00C697',
		],
		'destructive' => [
			'color' => '#E81915',
		],
	];

	public static function providerGetModules() {
		return [
			[
				[
					'class' => ResourceLoaderImageModule::class,
					'prefix' => 'oo-ui-icon',
					'variants' => self::$commonImageVariants,
					'images' => self::$commonImageData,
				],
				'.oo-ui-icon-abc {
	...
}
.oo-ui-icon-abc-invert {
	...
}
.oo-ui-icon-def {
	...
}
.oo-ui-icon-def-invert {
	...
}
.oo-ui-icon-def-destructive {
	...
}
.oo-ui-icon-ghi {
	...
}
.oo-ui-icon-ghi-invert {
	...
}
.oo-ui-icon-mno {
	...
}
.oo-ui-icon-mno-invert {
	...
}
.oo-ui-icon-pqr {
	...
}
.oo-ui-icon-pqr-invert {
	...
}',
			],
			[
				[
					'class' => ResourceLoaderImageModule::class,
					'selectorWithoutVariant' => '.mw-ui-icon-{name}:after, .mw-ui-icon-{name}:before',
					'selectorWithVariant' =>
						'.mw-ui-icon-{name}-{variant}:after, .mw-ui-icon-{name}-{variant}:before',
					'variants' => self::$commonImageVariants,
					'images' => self::$commonImageData,
				],
				'.mw-ui-icon-abc:after, .mw-ui-icon-abc:before {
	...
}
.mw-ui-icon-abc-invert:after, .mw-ui-icon-abc-invert:before {
	...
}
.mw-ui-icon-def:after, .mw-ui-icon-def:before {
	...
}
.mw-ui-icon-def-invert:after, .mw-ui-icon-def-invert:before {
	...
}
.mw-ui-icon-def-destructive:after, .mw-ui-icon-def-destructive:before {
	...
}
.mw-ui-icon-ghi:after, .mw-ui-icon-ghi:before {
	...
}
.mw-ui-icon-ghi-invert:after, .mw-ui-icon-ghi-invert:before {
	...
}
.mw-ui-icon-mno:after, .mw-ui-icon-mno:before {
	...
}
.mw-ui-icon-mno-invert:after, .mw-ui-icon-mno-invert:before {
	...
}
.mw-ui-icon-pqr:after, .mw-ui-icon-pqr:before {
	...
}
.mw-ui-icon-pqr-invert:after, .mw-ui-icon-pqr-invert:before {
	...
}',
			],
		];
	}

	/**
	 * @dataProvider providerGetModules
	 * @covers ResourceLoaderImageModule::getStyles
	 */
	public function testGetStyles( $module, $expected ) {
		$module = new ResourceLoaderImageModuleTestable(
			$module,
			__DIR__ . '/../../data/resourceloader'
		);
		$styles = $module->getStyles( $this->getResourceLoaderContext() );
		$this->assertEquals( $expected, $styles['all'] );
	}

	/**
	 * @covers ResourceLoaderContext::getImageObj
	 */
	public function testContext() {
		$context = new ResourceLoaderContext( new EmptyResourceLoader(), new FauxRequest() );
		$this->assertFalse( $context->getImageObj(), 'Missing image parameter' );

		$context = new ResourceLoaderContext( new EmptyResourceLoader(), new FauxRequest( [
			'image' => 'example',
		] ) );
		$this->assertFalse( $context->getImageObj(), 'Missing module parameter' );

		$context = new ResourceLoaderContext( new EmptyResourceLoader(), new FauxRequest( [
			'modules' => 'unknown',
			'image' => 'example',
		] ) );
		$this->assertFalse( $context->getImageObj(), 'Not an image module' );

		$rl = new EmptyResourceLoader();
		$rl->register( 'test', [
			'class' => ResourceLoaderImageModule::class,
			'prefix' => 'test',
			'images' => [ 'example' => 'example.png' ],
		] );
		$context = new ResourceLoaderContext( $rl, new FauxRequest( [
			'modules' => 'test',
			'image' => 'unknown',
		] ) );
		$this->assertFalse( $context->getImageObj(), 'Unknown image' );

		$rl = new EmptyResourceLoader();
		$rl->register( 'test', [
			'class' => ResourceLoaderImageModule::class,
			'prefix' => 'test',
			'images' => [ 'example' => 'example.png' ],
		] );
		$context = new ResourceLoaderContext( $rl, new FauxRequest( [
			'modules' => 'test',
			'image' => 'example',
		] ) );
		$this->assertInstanceOf( ResourceLoaderImage::class, $context->getImageObj() );
	}

	public static function providerGetStyleDeclarations() {
		return [
			[
				false,
<<<TEXT
background-image: url(rasterized.png);
	background-image: linear-gradient(transparent, transparent), url(original.svg);
TEXT
			],
			[
				'data:image/svg+xml',
<<<TEXT
background-image: url(rasterized.png);
	background-image: linear-gradient(transparent, transparent), url(data:image/svg+xml);
TEXT
			],

		];
	}

	/**
	 * @dataProvider providerGetStyleDeclarations
	 * @covers ResourceLoaderImageModule::getStyleDeclarations
	 */
	public function testGetStyleDeclarations( $dataUriReturnValue, $expected ) {
		$module = TestingAccessWrapper::newFromObject( new ResourceLoaderImageModule() );
		$context = $this->getResourceLoaderContext();
		$image = $this->getImageMock( $context, $dataUriReturnValue );

		$styles = $module->getStyleDeclarations(
			$context,
			$image,
			'load.php'
		);

		$this->assertEquals( $expected, $styles );
	}

	private function getImageMock( ResourceLoaderContext $context, $dataUriReturnValue ) {
		$image = $this->getMockBuilder( ResourceLoaderImage::class )
			->disableOriginalConstructor()
			->getMock();
		$image->method( 'getDataUri' )
			->will( $this->returnValue( $dataUriReturnValue ) );
		$image->expects( $this->any() )
			->method( 'getUrl' )
			->will( $this->returnValueMap( [
				[ $context, 'load.php', null, 'original', 'original.svg' ],
				[ $context, 'load.php', null, 'rasterized', 'rasterized.png' ],
			] ) );

		return $image;
	}
}

class ResourceLoaderImageModuleTestable extends ResourceLoaderImageModule {
	/**
	 * Replace with a stub to make test cases easier to write.
	 */
	protected function getCssDeclarations( $primary, $fallback ) {
		return [ '...' ];
	}
}

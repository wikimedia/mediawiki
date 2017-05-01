<?php

/**
 * @group ResourceLoader
 */
class ResourceLoaderImageModuleTest extends ResourceLoaderTestCase {

	public static $commonImageData = [
		'add' => 'add.gif',
		'remove' => [
			'file' => 'remove.svg',
			'variants' => [ 'destructive' ],
		],
		'next' => [
			'file' => [
				'ltr' => 'next.svg',
				'rtl' => 'prev.svg'
			],
		],
		'help' => [
			'file' => [
				'ltr' => 'help-ltr.svg',
				'rtl' => 'help-rtl.svg',
				'lang' => [
					'he' => 'help-ltr.svg',
				]
			],
		],
		'bold' => [
			'file' => [
				'default' => 'bold-a.svg',
				'lang' => [
					'en' => 'bold-b.svg',
					'ar,de' => 'bold-f.svg',
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
					'class' => 'ResourceLoaderImageModule',
					'prefix' => 'oo-ui-icon',
					'variants' => self::$commonImageVariants,
					'images' => self::$commonImageData,
				],
				'.oo-ui-icon-add {
	...
}
.oo-ui-icon-add-invert {
	...
}
.oo-ui-icon-remove {
	...
}
.oo-ui-icon-remove-invert {
	...
}
.oo-ui-icon-remove-destructive {
	...
}
.oo-ui-icon-next {
	...
}
.oo-ui-icon-next-invert {
	...
}
.oo-ui-icon-help {
	...
}
.oo-ui-icon-help-invert {
	...
}
.oo-ui-icon-bold {
	...
}
.oo-ui-icon-bold-invert {
	...
}',
			],
			[
				[
					'class' => 'ResourceLoaderImageModule',
					'selectorWithoutVariant' => '.mw-ui-icon-{name}:after, .mw-ui-icon-{name}:before',
					'selectorWithVariant' =>
						'.mw-ui-icon-{name}-{variant}:after, .mw-ui-icon-{name}-{variant}:before',
					'variants' => self::$commonImageVariants,
					'images' => self::$commonImageData,
				],
				'.mw-ui-icon-add:after, .mw-ui-icon-add:before {
	...
}
.mw-ui-icon-add-invert:after, .mw-ui-icon-add-invert:before {
	...
}
.mw-ui-icon-remove:after, .mw-ui-icon-remove:before {
	...
}
.mw-ui-icon-remove-invert:after, .mw-ui-icon-remove-invert:before {
	...
}
.mw-ui-icon-remove-destructive:after, .mw-ui-icon-remove-destructive:before {
	...
}
.mw-ui-icon-next:after, .mw-ui-icon-next:before {
	...
}
.mw-ui-icon-next-invert:after, .mw-ui-icon-next-invert:before {
	...
}
.mw-ui-icon-help:after, .mw-ui-icon-help:before {
	...
}
.mw-ui-icon-help-invert:after, .mw-ui-icon-help-invert:before {
	...
}
.mw-ui-icon-bold:after, .mw-ui-icon-bold:before {
	...
}
.mw-ui-icon-bold-invert:after, .mw-ui-icon-bold-invert:before {
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
}

class ResourceLoaderImageModuleTestable extends ResourceLoaderImageModule {
	/**
	 * Replace with a stub to make test cases easier to write.
	 */
	protected function getCssDeclarations( $primary, $fallback ) {
		return [ '...' ];
	}
}

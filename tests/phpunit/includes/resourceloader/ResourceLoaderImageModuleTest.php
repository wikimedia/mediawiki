<?php

/**
 * @group ResourceLoader
 */
class ResourceLoaderImageModuleTest extends ResourceLoaderTestCase {

	public static function providerGetModules() {
		$commonVariants = array(
			'invert' => array(
				'color' => '#FFFFFF',
				'global' => true,
			),
			'primary' => array(
				'color' => '#598AD1',
			),
			'constructive' => array(
				'color' => '#00C697',
			),
			'destructive' => array(
				'color' => '#E81915',
			),
		);

		$commonImageData = array(
			'advanced' => 'advanced.svg',
			'remove' => array(
				'image' => 'remove.svg',
				'variants' => array( 'destructive' ),
			),
			'next' => array(
				'image' => array(
					'ltr' => 'next.svg',
					'rtl' => 'prev.svg'
				),
			),
			'help' => array(
				'image' => array(
					'ltr' => 'help-ltr.svg',
					'rtl' => 'help-rtl.svg',
					'lang' => array(
						'he' => 'help-ltr.svg',
					)
				),
			),
			'bold' => array(
				'image' => array(
					'default' => 'bold-a.svg',
					'lang' => array(
						'en' => 'bold-b.svg',
						'de' => 'bold-f.svg',
					)
				),
			)
		);

		return array(
			array(
				array(
					'class' => 'ResourceLoaderImageModule',
					'prefix' => 'oo-ui-icon',
					'variants' => $commonVariants,
					'images' => $commonImageData,
				),
				'.oo-ui-icon-advanced {
	...
}
.oo-ui-icon-advanced-invert {
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
			),
			array(
				array(
					'class' => 'ResourceLoaderImageModule',
					'selectorWithoutVariant' => '.mw-ui-icon-{name}:after, .mw-ui-icon-{name}:before',
					'selectorWithVariant' => '.mw-ui-icon-{name}-{variant}:after, .mw-ui-icon-{name}-{variant}:before',
					'variants' => $commonVariants,
					'images' => $commonImageData,
				),
				'.mw-ui-icon-advanced:after, .mw-ui-icon-advanced:before {
	...
}
.mw-ui-icon-advanced-invert:after, .mw-ui-icon-advanced-invert:before {
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
			),
		);
	}

	/**
	 * @dataProvider providerGetModules
	 * @covers ResourceLoaderImageModule::getStyles
	 */
	public function testGetStyles( $module, $expected ) {
		$module = new ResourceLoaderImageModuleTestable( $module );
		$styles = $module->getStyles( $this->getResourceLoaderContext() );
		$this->assertEquals( $expected, $styles['all'] );
	}
}

class ResourceLoaderImageModuleTestable extends ResourceLoaderImageModule {
	/**
	 * Replace with a stub to make test cases easier to write.
	 */
	protected function getCssDeclarations( $primary, $fallback ) {
		return array( '...' );
	}

	/**
	 * Return mock ResourceLoaderImages that don't call file_get_contents and such.
	 */
	public function getImages() {
		$images = parent::getImages();
		foreach ( $images as $name => &$image ) {
			$image = new ResourceLoaderImageWrapper( $image );
		}
		return $images;
	}
}

/**
 * Wraps a ResourceLoaderImage not to call file_get_contents and such.
 */
class ResourceLoaderImageWrapper extends ResourceLoaderImage {
	public function __construct( ResourceLoaderImage $image ) {
		$this->image = $image;
	}

	public function getUrl( ResourceLoaderContext $context, $script, $variant, $format ) {
		return null;
	}

	public function getDataUri( ResourceLoaderContext $context, $variant, $format ) {
		return null;
	}

	public function __call( $method, $arguments ) {
		return call_user_func_array( array( $this->image, $method ), $arguments );
	}

	public function __get( $name ) {
		return $this->image->$name;
	}
}

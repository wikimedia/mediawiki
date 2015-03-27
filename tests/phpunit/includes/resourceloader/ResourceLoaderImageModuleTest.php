<?php

/**
 * @group ResourceLoader
 *
 * TODO: Tests for ResourceLoaderImage
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
					'prefix' => 'oo-ui',
					'variants' => array(
						'icon' => $commonVariants,
					),
					'images' => array(
						'icon' => $commonImageData,
					),
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
 * Wraps a ResourceLoaderImages not to call file_get_contents and such.
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

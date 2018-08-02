<?php
/**
 * Tests related to auto rotation.
 *
 * @group Media
 * @group medium
 *
 * @covers BitmapHandler
 */
class ExifRotationTest extends MediaWikiMediaTestCase {

	/** @var BitmapHandler */
	private $handler;

	protected function setUp() {
		parent::setUp();
		$this->checkPHPExtension( 'exif' );

		$this->handler = new BitmapHandler();

		$this->setMwGlobals( [
			'wgShowEXIF' => true,
			'wgEnableAutoRotation' => true,
		] );
	}

	/**
	 * Mark this test as creating thumbnail files.
	 */
	protected function createsThumbnails() {
		return true;
	}

	/**
	 * @dataProvider provideFiles
	 */
	public function testMetadata( $name, $type, $info ) {
		if ( !$this->handler->canRotate() ) {
			$this->markTestSkipped( "This test needs a rasterizer that can auto-rotate." );
		}
		$file = $this->dataFile( $name, $type );
		$this->assertEquals( $info['width'], $file->getWidth(), "$name: width check" );
		$this->assertEquals( $info['height'], $file->getHeight(), "$name: height check" );
	}

	/**
	 * Same as before, but with auto-rotation set to auto.
	 *
	 * This sets scaler to image magick, which we should detect as
	 * supporting rotation.
	 * @dataProvider provideFiles
	 */
	public function testMetadataAutoRotate( $name, $type, $info ) {
		$this->setMwGlobals( 'wgEnableAutoRotation', null );
		$this->setMwGlobals( 'wgUseImageMagick', true );
		$this->setMwGlobals( 'wgUseImageResize', true );

		$file = $this->dataFile( $name, $type );
		$this->assertEquals( $info['width'], $file->getWidth(), "$name: width check" );
		$this->assertEquals( $info['height'], $file->getHeight(), "$name: height check" );
	}

	/**
	 *
	 * @dataProvider provideFiles
	 */
	public function testRotationRendering( $name, $type, $info, $thumbs ) {
		if ( !$this->handler->canRotate() ) {
			$this->markTestSkipped( "This test needs a rasterizer that can auto-rotate." );
		}
		foreach ( $thumbs as $size => $out ) {
			if ( preg_match( '/^(\d+)px$/', $size, $matches ) ) {
				$params = [
					'width' => $matches[1],
				];
			} elseif ( preg_match( '/^(\d+)x(\d+)px$/', $size, $matches ) ) {
				$params = [
					'width' => $matches[1],
					'height' => $matches[2]
				];
			} else {
				throw new MWException( 'bogus test data format ' . $size );
			}

			$file = $this->dataFile( $name, $type );
			$thumb = $file->transform( $params, File::RENDER_NOW | File::RENDER_FORCE );

			$this->assertEquals(
				$out[0],
				$thumb->getWidth(),
				"$name: thumb reported width check for $size"
			);
			$this->assertEquals(
				$out[1],
				$thumb->getHeight(),
				"$name: thumb reported height check for $size"
			);

			$gis = getimagesize( $thumb->getLocalCopyPath() );
			if ( $out[0] > $info['width'] ) {
				// Physical image won't be scaled bigger than the original.
				$this->assertEquals( $info['width'], $gis[0], "$name: thumb actual width check for $size" );
				$this->assertEquals( $info['height'], $gis[1], "$name: thumb actual height check for $size" );
			} else {
				$this->assertEquals( $out[0], $gis[0], "$name: thumb actual width check for $size" );
				$this->assertEquals( $out[1], $gis[1], "$name: thumb actual height check for $size" );
			}
		}
	}

	public static function provideFiles() {
		return [
			[
				'landscape-plain.jpg',
				'image/jpeg',
				[
					'width' => 1024,
					'height' => 768,
				],
				[
					'800x600px' => [ 800, 600 ],
					'9999x800px' => [ 1067, 800 ],
					'800px' => [ 800, 600 ],
					'600px' => [ 600, 450 ],
				]
			],
			[
				'portrait-rotated.jpg',
				'image/jpeg',
				[
					'width' => 768, // as rotated
					'height' => 1024, // as rotated
				],
				[
					'800x600px' => [ 450, 600 ],
					'9999x800px' => [ 600, 800 ],
					'800px' => [ 800, 1067 ],
					'600px' => [ 600, 800 ],
				]
			]
		];
	}

	/**
	 * Same as before, but with auto-rotation disabled.
	 * @dataProvider provideFilesNoAutoRotate
	 */
	public function testMetadataNoAutoRotate( $name, $type, $info ) {
		$this->setMwGlobals( 'wgEnableAutoRotation', false );

		$file = $this->dataFile( $name, $type );
		$this->assertEquals( $info['width'], $file->getWidth(), "$name: width check" );
		$this->assertEquals( $info['height'], $file->getHeight(), "$name: height check" );
	}

	/**
	 * Same as before, but with auto-rotation set to auto and an image scaler that doesn't support it.
	 * @dataProvider provideFilesNoAutoRotate
	 */
	public function testMetadataAutoRotateUnsupported( $name, $type, $info ) {
		$this->setMwGlobals( 'wgEnableAutoRotation', null );
		$this->setMwGlobals( 'wgUseImageResize', false );

		$file = $this->dataFile( $name, $type );
		$this->assertEquals( $info['width'], $file->getWidth(), "$name: width check" );
		$this->assertEquals( $info['height'], $file->getHeight(), "$name: height check" );
	}

	/**
	 *
	 * @dataProvider provideFilesNoAutoRotate
	 */
	public function testRotationRenderingNoAutoRotate( $name, $type, $info, $thumbs ) {
		$this->setMwGlobals( 'wgEnableAutoRotation', false );

		foreach ( $thumbs as $size => $out ) {
			if ( preg_match( '/^(\d+)px$/', $size, $matches ) ) {
				$params = [
					'width' => $matches[1],
				];
			} elseif ( preg_match( '/^(\d+)x(\d+)px$/', $size, $matches ) ) {
				$params = [
					'width' => $matches[1],
					'height' => $matches[2]
				];
			} else {
				throw new MWException( 'bogus test data format ' . $size );
			}

			$file = $this->dataFile( $name, $type );
			$thumb = $file->transform( $params, File::RENDER_NOW | File::RENDER_FORCE );

			if ( $thumb->isError() ) {
				/** @var MediaTransformError $thumb */
				$this->fail( $thumb->toText() );
			}

			$this->assertEquals(
				$out[0],
				$thumb->getWidth(),
				"$name: thumb reported width check for $size"
			);
			$this->assertEquals(
				$out[1],
				$thumb->getHeight(),
				"$name: thumb reported height check for $size"
			);

			$gis = getimagesize( $thumb->getLocalCopyPath() );
			if ( $out[0] > $info['width'] ) {
				// Physical image won't be scaled bigger than the original.
				$this->assertEquals( $info['width'], $gis[0], "$name: thumb actual width check for $size" );
				$this->assertEquals( $info['height'], $gis[1], "$name: thumb actual height check for $size" );
			} else {
				$this->assertEquals( $out[0], $gis[0], "$name: thumb actual width check for $size" );
				$this->assertEquals( $out[1], $gis[1], "$name: thumb actual height check for $size" );
			}
		}
	}

	public static function provideFilesNoAutoRotate() {
		return [
			[
				'landscape-plain.jpg',
				'image/jpeg',
				[
					'width' => 1024,
					'height' => 768,
				],
				[
					'800x600px' => [ 800, 600 ],
					'9999x800px' => [ 1067, 800 ],
					'800px' => [ 800, 600 ],
					'600px' => [ 600, 450 ],
				]
			],
			[
				'portrait-rotated.jpg',
				'image/jpeg',
				[
					'width' => 1024, // since not rotated
					'height' => 768, // since not rotated
				],
				[
					'800x600px' => [ 800, 600 ],
					'9999x800px' => [ 1067, 800 ],
					'800px' => [ 800, 600 ],
					'600px' => [ 600, 450 ],
				]
			]
		];
	}

	const TEST_WIDTH = 100;
	const TEST_HEIGHT = 200;

	/**
	 * @dataProvider provideBitmapExtractPreRotationDimensions
	 */
	public function testBitmapExtractPreRotationDimensions( $rotation, $expected ) {
		$result = $this->handler->extractPreRotationDimensions( [
			'physicalWidth' => self::TEST_WIDTH,
			'physicalHeight' => self::TEST_HEIGHT,
		], $rotation );
		$this->assertEquals( $expected, $result );
	}

	public static function provideBitmapExtractPreRotationDimensions() {
		return [
			[
				0,
				[ self::TEST_WIDTH, self::TEST_HEIGHT ]
			],
			[
				90,
				[ self::TEST_HEIGHT, self::TEST_WIDTH ]
			],
			[
				180,
				[ self::TEST_WIDTH, self::TEST_HEIGHT ]
			],
			[
				270,
				[ self::TEST_HEIGHT, self::TEST_WIDTH ]
			],
		];
	}
}

<?php

/**
 * @group Media
 */
class BitmapScalingTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgMaxImageArea' => 1.25e7, // 3500x3500
			'wgCustomConvertCommand' => 'dummy', // Set so that we don't get client side rendering
		] );
	}

	/**
	 * @dataProvider provideNormaliseParams
	 * @covers BitmapHandler::normaliseParams
	 */
	public function testNormaliseParams( $fileDimensions, $expectedParams, $params, $msg ) {
		$file = new FakeDimensionFile( $fileDimensions );
		$handler = new BitmapHandler;
		$valid = $handler->normaliseParams( $file, $params );
		$this->assertTrue( $valid );
		$this->assertEquals( $expectedParams, $params, $msg );
	}

	public static function provideNormaliseParams() {
		return [
			/* Regular resize operations */
			[
				[ 1024, 768 ],
				[
					'width' => 512, 'height' => 384,
					'physicalWidth' => 512, 'physicalHeight' => 384,
					'page' => 1, 'interlace' => false,
				],
				[ 'width' => 512 ],
				'Resizing with width set',
			],
			[
				[ 1024, 768 ],
				[
					'width' => 512, 'height' => 384,
					'physicalWidth' => 512, 'physicalHeight' => 384,
					'page' => 1, 'interlace' => false,
				],
				[ 'width' => 512, 'height' => 768 ],
				'Resizing with height set too high',
			],
			[
				[ 1024, 768 ],
				[
					'width' => 512, 'height' => 384,
					'physicalWidth' => 512, 'physicalHeight' => 384,
					'page' => 1, 'interlace' => false,
				],
				[ 'width' => 1024, 'height' => 384 ],
				'Resizing with height set',
			],

			/* Very tall images */
			[
				[ 1000, 100 ],
				[
					'width' => 5, 'height' => 1,
					'physicalWidth' => 5, 'physicalHeight' => 1,
					'page' => 1, 'interlace' => false,
				],
				[ 'width' => 5 ],
				'Very wide image',
			],

			[
				[ 100, 1000 ],
				[
					'width' => 1, 'height' => 10,
					'physicalWidth' => 1, 'physicalHeight' => 10,
					'page' => 1, 'interlace' => false,
				],
				[ 'width' => 1 ],
				'Very high image',
			],
			[
				[ 100, 1000 ],
				[
					'width' => 1, 'height' => 5,
					'physicalWidth' => 1, 'physicalHeight' => 10,
					'page' => 1, 'interlace' => false,
				],
				[ 'width' => 10, 'height' => 5 ],
				'Very high image with height set',
			],
			/* Max image area */
			[
				[ 4000, 4000 ],
				[
					'width' => 5000, 'height' => 5000,
					'physicalWidth' => 4000, 'physicalHeight' => 4000,
					'page' => 1, 'interlace' => false,
				],
				[ 'width' => 5000 ],
				'Bigger than max image size but doesn\'t need scaling',
			],
			/* Max interlace image area */
			[
				[ 4000, 4000 ],
				[
					'width' => 5000, 'height' => 5000,
					'physicalWidth' => 4000, 'physicalHeight' => 4000,
					'page' => 1, 'interlace' => false,
				],
				[ 'width' => 5000, 'interlace' => true ],
				'Interlace bigger than max interlace area',
			],
		];
	}

	/**
	 * @covers BitmapHandler::doTransform
	 */
	public function testTooBigImage() {
		$file = new FakeDimensionFile( [ 4000, 4000 ] );
		$handler = new BitmapHandler;
		$params = [ 'width' => '3700' ]; // Still bigger than max size.
		$this->assertEquals( TransformTooBigImageAreaError::class,
			get_class( $handler->doTransform( $file, 'dummy path', '', $params ) ) );
	}

	/**
	 * @covers BitmapHandler::doTransform
	 */
	public function testTooBigMustRenderImage() {
		$file = new FakeDimensionFile( [ 4000, 4000 ] );
		$file->mustRender = true;
		$handler = new BitmapHandler;
		$params = [ 'width' => '5000' ]; // Still bigger than max size.
		$this->assertEquals( TransformTooBigImageAreaError::class,
			get_class( $handler->doTransform( $file, 'dummy path', '', $params ) ) );
	}

	/**
	 * @covers BitmapHandler::getImageArea
	 */
	public function testImageArea() {
		$file = new FakeDimensionFile( [ 7, 9 ] );
		$handler = new BitmapHandler;
		$this->assertEquals( 63, $handler->getImageArea( $file ) );
	}
}

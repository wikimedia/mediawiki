<?php

/**
 * Tests related to auto rotation
 */
class ExifRotationTest extends MediaWikiTestCase {

	function setUp() {
		parent::setUp();
		$this->filePath = dirname( __FILE__ ) . '/../../data/media/';
		$this->handler = new BitmapHandler();
	}

	/**
	 *
	 * @dataProvider providerFiles
	 */
	function testMetadata( $name, $type, $info ) {
		# Force client side resizing
		$params = array( 'width' => 10000, 'height' => 10000 );
		$file = UnregisteredLocalFile::newFromPath( $this->filePath . $name, $type );
		
		# Normalize parameters
		$this->assertTrue( $this->handler->normaliseParams( $file, $params ) );
		$rotation = $this->handler->getRotation( $file );
		
		# Check if pre-rotation dimensions are still good
		list( $width, $height ) = $this->handler->extractPreRotationDimensions( $params, $rotation );
		$this->assertEquals( $file->getWidth(), $width, 
			"$name: pre-rotation width check, $rotation:$width" );
		$this->assertEquals( $file->getHeight(), $height, 
			"$name: pre-rotation height check, $rotation" );
		
		# Any further test require a scaler that can rotate
		if ( !BitmapHandler::canRotate() ) {
			$this->markTestIncomplete( 'Scaler does not support rotation' );
			return;
		}
		
		# Check post-rotation width
		$this->assertEquals( $params['physicalWidth'], $info['width'], 
			"$name: post-rotation width check" );
		$this->assertEquals( $params['physicalHeight'], $info['height'], 
			"$name: post-rotation height check" );
	}

	function providerFiles() {
		return array(
			array(
				'landscape-plain.jpg',
				'image/jpeg',
				array(
					'width' => 1024,
					'height' => 768,
				)
			),
			array(
				'portrait-rotated.jpg',
				'image/jpeg',
				array(
					'width' => 768, // as rotated
					'height' => 1024, // as rotated
				)
			)
		);
	}
	
	
	const TEST_WIDTH = 100;
	const TEST_HEIGHT = 200;
	
	/**
	 * @dataProvider provideBitmapExtractPreRotationDimensions
	 */
	function testBitmapExtractPreRotationDimensions( $rotation, $expected ) {
		$result = $this->handler->extractPreRotationDimensions( array(
				'physicalWidth' => self::TEST_WIDTH, 
				'physicalHeight' => self::TEST_HEIGHT,
			), $rotation );
		$this->assertEquals( $expected, $result );
	}
	
	function provideBitmapExtractPreRotationDimensions() {
		return array(
			array(
				0,
				array( self::TEST_WIDTH, self::TEST_HEIGHT ) 
			),
			array(
				90,
				array( self::TEST_HEIGHT, self::TEST_WIDTH ) 
			),
			array(
				180,
				array( self::TEST_WIDTH, self::TEST_HEIGHT ) 
			),
			array(
				270,
				array( self::TEST_HEIGHT, self::TEST_WIDTH ) 
			),			
		);
	}

	function testWidthFlipping() {
		$file = UnregisteredLocalFile::newFromPath( $this->filePath . 'portrait-rotated.jpg', 'image/jpeg' );
		$params = array( 'width' => '50' );
		$this->assertTrue( $this->handler->normaliseParams( $file, $params ) );

		$this->assertEquals( 50, $params['height'] );
		$this->assertEquals( round( (768/1024)*50 ), $params['width'], '', 0.1 );
	}
	function testWidthNotFlipping() {
		$file = UnregisteredLocalFile::newFromPath( $this->filePath . 'landscape-plain.jpg', 'image/jpeg' );
		$params = array( 'width' => '50' );
		$this->assertTrue( $this->handler->normaliseParams( $file, $params ) );

		$this->assertEquals( 50, $params['width'] );
		$this->assertEquals( round( (768/1024)*50 ), $params['height'], '', 0.1 );
	}
}


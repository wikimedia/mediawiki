<?php

/**
 * Tests related to auto rotation
 */
class ExifRotationTest extends MediaWikiTestCase {

	function setUp() {
		parent::setUp();
		$this->filePath = dirname( __FILE__ ) . '/../../data/media/';
		$this->handler = new BitmapHandler();
		if ( !wfDl( 'exif' ) ) {
			$this->markTestSkipped( "This test needs the exif extension." );
		}
		global $wgShowEXIF;
		$this->show = $wgShowEXIF;
		$wgShowEXIF = true;
	}
	public function tearDown() {
		global $wgShowEXIF;
		$wgShowEXIF = $this->show;
	}

	/**
	 *
	 * @dataProvider providerFiles
	 */
	function testMetadata( $name, $type, $info ) {
		$file = UnregisteredLocalFile::newFromPath( $this->filePath . $name, $type );
		$this->assertEquals( $file->getWidth(), $info['width'], "$name: width check" );
		$this->assertEquals( $file->getHeight(), $info['height'], "$name: height check" );
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
		# Any further test require a scaler that can rotate
		if ( !BitmapHandler::canRotate() ) {
			$this->markTestSkipped( 'Scaler does not support rotation' );
			return;
		}
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


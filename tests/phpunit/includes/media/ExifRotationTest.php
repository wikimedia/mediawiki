<?php

/**
 * Tests related to auto rotation
 */
class ExifRotationTest extends MediaWikiTestCase {

	function setUp() {
		parent::setUp();
		$this->filePath = dirname( __FILE__ ) . '/../../data/media/';
		$this->handler = new BitmapHandler();
		$this->repo = new FSRepo(array(
			'name' => 'temp',
			'directory' => wfTempDir() . '/exif-test-' . time(),
			'url' => 'http://localhost/thumbtest'
		));
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

	/**
	 *
	 * @dataProvider providerFiles
	 */
	function testRotationRendering( $name, $type, $info ) {
		$file = $this->localFile( $name, $type );
		$thumb = $file->transform( array(
			'width' => 800,
			'height' => 600,
		), File::RENDER_NOW );

		$this->assertEquals( $thumb->getWidth(), $info['thumbWidth'], "$name: thumb reported width check" );
		$this->assertEquals( $thumb->getHeight(), $info['thumbHeight'], "$name: thumb reported height check" );

		$gis = getimagesize( $thumb->getPath() );
		$this->assertEquals( $gis[0], $info['thumbWidth'], "$name: thumb actual width check");
		$this->assertEquals( $gis[0], $info['thumbWidth'], "$name: thumb actual height check");
	}

	private function localFile( $name, $type ) {
		return new UnregisteredLocalFile( false, $this->repo, $this->filePath . $name, $type );
	}

	function providerFiles() {
		return array(
			array(
				'landscape-plain.jpg',
				'image/jpeg',
				array(
					'width' => 1024,
					'height' => 768,
					'thumbWidth' => 800,
					'thumbHeight' => 600,
				)
			),
			array(
				'portrait-rotated.jpg',
				'image/jpeg',
				array(
					'width' => 768, // as rotated
					'height' => 1024, // as rotated
					'thumbWidth' => 450,
					'thumbHeight' => 600,
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


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
		$this->assertEquals( $info['width'], $file->getWidth(), "$name: width check" );
		$this->assertEquals( $info['height'], $file->getHeight(), "$name: height check" );
	}

	/**
	 *
	 * @dataProvider providerFiles
	 */
	function testRotationRendering( $name, $type, $info, $thumbs ) {
		foreach( $thumbs as $size => $out ) {
			if( preg_match('/^(\d+)px$/', $size, $matches ) ) {
				$params = array(
					'width' => $matches[1],
				);
			} elseif ( preg_match( '/^(\d+)x(\d+)px$/', $size, $matches ) ) {
				$params = array(
					'width' => $matches[1],
					'height' => $matches[2]
				);
			} else {
				throw new MWException('bogus test data format ' . $size);
			}

			$file = $this->localFile( $name, $type );
			$thumb = $file->transform( $params, File::RENDER_NOW );

			$this->assertEquals( $out[0], $thumb->getWidth(), "$name: thumb reported width check for $size" );
			$this->assertEquals( $out[1], $thumb->getHeight(), "$name: thumb reported height check for $size" );

			$gis = getimagesize( $thumb->getPath() );
			if ($out[0] > $info['width']) {
				// Physical image won't be scaled bigger than the original.
				$this->assertEquals( $info['width'], $gis[0], "$name: thumb actual width check for $size");
				$this->assertEquals( $info['height'], $gis[1], "$name: thumb actual height check for $size");
			} else {
				$this->assertEquals( $out[0], $gis[0], "$name: thumb actual width check for $size");
				$this->assertEquals( $out[1], $gis[1], "$name: thumb actual height check for $size");
			}
		}
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
				),
				array(
					'800x600px' => array( 800, 600 ),
					'9999x800px' => array( 1067, 800 ),
					'800px' => array( 800, 600 ),
					'600px' => array( 600, 450 ),
				)
			),
			array(
				'portrait-rotated.jpg',
				'image/jpeg',
				array(
					'width' => 768, // as rotated
					'height' => 1024, // as rotated
				),
				array(
					'800x600px' => array( 450, 600 ),
					'9999x800px' => array( 600, 800 ),
					'800px' => array( 800, 1067 ),
					'600px' => array( 600, 800 ),
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


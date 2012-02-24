<?php

/**
 * Tests related to auto rotation
 */
class ExifRotationTest extends MediaWikiTestCase {

	/** track directories creations. Content will be deleted. */
	private $createdDirs = array();

	function setUp() {
		parent::setUp();
		$this->handler = new BitmapHandler();
		$filePath = dirname( __FILE__ ) . '/../../data/media';

		$tmpDir = wfTempDir() . '/exif-test-' . time() . '-' . mt_rand();
		$this->createdDirs[] = $tmpDir;

		$this->repo = new FSRepo( array(
			'name'            => 'temp',
			'url'             => 'http://localhost/thumbtest',
			'backend'         => new FSFileBackend( array(
				'name'           => 'localtesting',
				'lockManager'    => 'nullLockManager',
				'containerPaths' => array( 'temp-thumb' => $tmpDir, 'data' => $filePath )
			) )
		) );
		if ( !wfDl( 'exif' ) ) {
			$this->markTestSkipped( "This test needs the exif extension." );
		}
		global $wgShowEXIF;
		$this->show = $wgShowEXIF;
		$wgShowEXIF = true;

		global $wgEnableAutoRotation;
		$this->oldAuto = $wgEnableAutoRotation;
		$wgEnableAutoRotation = true;
	}

	public function tearDown() {
		global $wgShowEXIF, $wgEnableAutoRotation;
		$wgShowEXIF = $this->show;
		$wgEnableAutoRotation = $this->oldAuto;

		$this->tearDownFiles();
	}

	private function tearDownFiles() {
		foreach( $this->createdDirs as $dir ) {
			wfRecursiveRemoveDir( $dir );
		}
	}

	function __destruct() {
		$this->tearDownFiles();
	}

	/**
	 *
	 * @dataProvider providerFiles
	 */
	function testMetadata( $name, $type, $info ) {
		if ( !BitmapHandler::canRotate() ) {
			$this->markTestSkipped( "This test needs a rasterizer that can auto-rotate." );
		}
		$file = $this->dataFile( $name, $type );
		$this->assertEquals( $info['width'], $file->getWidth(), "$name: width check" );
		$this->assertEquals( $info['height'], $file->getHeight(), "$name: height check" );
	}

	/**
	 *
	 * @dataProvider providerFiles
	 */
	function testRotationRendering( $name, $type, $info, $thumbs ) {
		if ( !BitmapHandler::canRotate() ) {
			$this->markTestSkipped( "This test needs a rasterizer that can auto-rotate." );
		}
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

			$file = $this->dataFile( $name, $type );
			$thumb = $file->transform( $params, File::RENDER_NOW | File::RENDER_FORCE );

			$this->assertEquals( $out[0], $thumb->getWidth(), "$name: thumb reported width check for $size" );
			$this->assertEquals( $out[1], $thumb->getHeight(), "$name: thumb reported height check for $size" );

			$gis = getimagesize( $thumb->getLocalCopyPath() );
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

	private function dataFile( $name, $type ) {
		return new UnregisteredLocalFile( false, $this->repo,
			"mwstore://localtesting/data/$name", $type );
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

	/**
	 * Same as before, but with auto-rotation disabled.
	 * @dataProvider providerFilesNoAutoRotate
	 */
	function testMetadataNoAutoRotate( $name, $type, $info ) {
		global $wgEnableAutoRotation;
		$wgEnableAutoRotation = false;

		$file = $this->dataFile( $name, $type );
		$this->assertEquals( $info['width'], $file->getWidth(), "$name: width check" );
		$this->assertEquals( $info['height'], $file->getHeight(), "$name: height check" );

		$wgEnableAutoRotation = true;
	}

	/**
	 *
	 * @dataProvider providerFilesNoAutoRotate
	 */
	function testRotationRenderingNoAutoRotate( $name, $type, $info, $thumbs ) {
		global $wgEnableAutoRotation;
		$wgEnableAutoRotation = false;

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

			$file = $this->dataFile( $name, $type );
			$thumb = $file->transform( $params, File::RENDER_NOW | File::RENDER_FORCE );

			$this->assertEquals( $out[0], $thumb->getWidth(), "$name: thumb reported width check for $size" );
			$this->assertEquals( $out[1], $thumb->getHeight(), "$name: thumb reported height check for $size" );

			$gis = getimagesize( $thumb->getLocalCopyPath() );
			if ($out[0] > $info['width']) {
				// Physical image won't be scaled bigger than the original.
				$this->assertEquals( $info['width'], $gis[0], "$name: thumb actual width check for $size");
				$this->assertEquals( $info['height'], $gis[1], "$name: thumb actual height check for $size");
			} else {
				$this->assertEquals( $out[0], $gis[0], "$name: thumb actual width check for $size");
				$this->assertEquals( $out[1], $gis[1], "$name: thumb actual height check for $size");
			}
		}
		$wgEnableAutoRotation = true;
	}

	function providerFilesNoAutoRotate() {
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
					'width' => 1024, // since not rotated
					'height' => 768, // since not rotated
				),
				array(
					'800x600px' => array( 800, 600 ),
					'9999x800px' => array( 1067, 800 ),
					'800px' => array( 800, 600 ),
					'600px' => array( 600, 450 ),
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
}


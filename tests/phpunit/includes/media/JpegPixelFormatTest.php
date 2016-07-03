<?php
/**
 * Tests related to JPEG chroma subsampling via $wgJpegPixelFormat setting.
 *
 * @group Media
 * @group medium
 *
 * @todo covers tags
 */
class JpegPixelFormatTest extends MediaWikiMediaTestCase {

	protected function setUp() {
		parent::setUp();
	}

	/**
	 * Mark this test as creating thumbnail files.
	 */
	protected function createsThumbnails() {
		return true;
	}

	/**
	 *
	 * @dataProvider providePixelFormats
	 */
	public function testPixelFormatRendering( $sourceFile, $pixelFormat, $samplingFactor ) {
		global $wgUseImageMagick, $wgUseImageResize;
		if ( !$wgUseImageMagick ) {
			$this->markTestSkipped( "This test is only applicable when using ImageMagick thumbnailing" );
		}
		if ( !$wgUseImageResize ) {
			$this->markTestSkipped( "This test is only applicable when using thumbnailing" );
		}

		$fmtStr = var_export( $pixelFormat, true );
		$this->setMwGlobals( 'wgJpegPixelFormat', $pixelFormat );

		$file = $this->dataFile( $sourceFile, 'image/jpeg' );

		$params = [
			'width' => 320,
		];
		$thumb = $file->transform( $params, File::RENDER_NOW | File::RENDER_FORCE );
		$this->assertTrue( !$thumb->isError(), "created JPEG thumbnail for pixel format $fmtStr" );

		$path = $thumb->getLocalCopyPath();
		$this->assertTrue( is_string( $path ), "path returned for JPEG thumbnail for $fmtStr" );

		$cmd = [
			'identify',
			'-format',
			'%[jpeg:sampling-factor]',
			$path
		];
		$retval = null;
		$output = wfShellExec( $cmd, $retval );
		$this->assertTrue( $retval === 0, "ImageMagick's identify command should return success" );

		$expected = $samplingFactor;
		$actual = trim( $output );
		$this->assertEquals(
			$expected,
			trim( $output ),
			"IM identify expects JPEG chroma subsampling \"$expected\" for $fmtStr"
		);
	}

	public static function providePixelFormats() {
		return [
			// From 4:4:4 source file
			[
				'yuv444.jpg',
				false,
				'1x1,1x1,1x1'
			],
			[
				'yuv444.jpg',
				'yuv444',
				'1x1,1x1,1x1'
			],
			[
				'yuv444.jpg',
				'yuv422',
				'2x1,1x1,1x1'
			],
			[
				'yuv444.jpg',
				'yuv420',
				'2x2,1x1,1x1'
			],
			// From 4:2:0 source file
			[
				'yuv420.jpg',
				false,
				'2x2,1x1,1x1'
			],
			[
				'yuv420.jpg',
				'yuv444',
				'1x1,1x1,1x1'
			],
			[
				'yuv420.jpg',
				'yuv422',
				'2x1,1x1,1x1'
			],
			[
				'yuv420.jpg',
				'yuv420',
				'2x2,1x1,1x1'
			]
		];
	}
}

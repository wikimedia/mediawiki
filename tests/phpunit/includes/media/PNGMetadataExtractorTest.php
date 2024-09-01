<?php

/**
 * @group Media
 * @covers \PNGMetadataExtractor
 */
class PNGMetadataExtractorTest extends MediaWikiIntegrationTestCase {
	private const FILE_PATH = __DIR__ . '/../../data/media/';

	/**
	 * Tests zTXt tag (compressed textual metadata)
	 * @requires extension zlib
	 */
	public function testPngNativetZtxt() {
		$meta = PNGMetadataExtractor::getMetadata( self::FILE_PATH .
			'Png-native-test.png' );
		$expected = "foo bar baz foo foo foo foof foo foo foo foo";
		$this->assertArrayHasKey( 'text', $meta );
		$meta = $meta['text'];
		$this->assertArrayHasKey( 'Make', $meta );
		$this->assertArrayHasKey( 'x-default', $meta['Make'] );

		$this->assertEquals( $expected, $meta['Make']['x-default'] );
	}

	/**
	 * Test tEXt tag (Uncompressed textual metadata)
	 */
	public function testPngNativeText() {
		$meta = PNGMetadataExtractor::getMetadata( self::FILE_PATH .
			'Png-native-test.png' );
		$expected = "Some long image desc";
		$this->assertArrayHasKey( 'text', $meta );
		$meta = $meta['text'];
		$this->assertArrayHasKey( 'ImageDescription', $meta );
		$this->assertArrayHasKey( 'x-default', $meta['ImageDescription'] );
		$this->assertArrayHasKey( '_type', $meta['ImageDescription'] );

		$this->assertEquals( $expected, $meta['ImageDescription']['x-default'] );
	}

	/**
	 * tEXt tags must be encoded iso-8859-1 (vs iTXt which are utf-8)
	 * Make sure non-ascii characters get converted properly
	 */
	public function testPngNativeTextNonAscii() {
		$meta = PNGMetadataExtractor::getMetadata( self::FILE_PATH .
			'Png-native-test.png' );

		// Note the Copyright symbol here is a utf-8 one
		// (aka \xC2\xA9) where in the file its iso-8859-1
		// encoded as just \xA9.
		$expected = "© 2010 Bawolff";

		$this->assertArrayHasKey( 'text', $meta );
		$meta = $meta['text'];
		$this->assertArrayHasKey( 'Copyright', $meta );
		$this->assertArrayHasKey( 'x-default', $meta['Copyright'] );

		$this->assertEquals( $expected, $meta['Copyright']['x-default'] );
	}

	/**
	 * Given a normal static PNG, check the animation metadata returned.
	 */
	public function testStaticPngAnimationMetadata() {
		$meta = PNGMetadataExtractor::getMetadata( self::FILE_PATH .
			'Png-native-test.png' );

		$this->assertSame( 0, $meta['frameCount'] );
		$this->assertSame( 1, $meta['loopCount'] );
		$this->assertSame( 0.0, $meta['duration'] );
	}

	/**
	 * Given an animated APNG image file
	 * check it gets animated metadata right.
	 */
	public function testApngAnimationMetadata() {
		$meta = PNGMetadataExtractor::getMetadata( self::FILE_PATH .
			'Animated_PNG_example_bouncing_beach_ball.png' );

		$this->assertEquals( 20, $meta['frameCount'] );
		// Note loop count of 0 = infinity
		$this->assertSame( 0, $meta['loopCount'] );
		$this->assertEqualsWithDelta( 1.5, $meta['duration'], 0.00001, '' );
	}

	public function testPngBitDepth8() {
		$meta = PNGMetadataExtractor::getMetadata( self::FILE_PATH .
			'Png-native-test.png' );

		$this->assertEquals( 8, $meta['bitDepth'] );
	}

	public function testPngBitDepth1() {
		$meta = PNGMetadataExtractor::getMetadata( self::FILE_PATH .
			'1bit-png.png' );
		$this->assertSame( 1, $meta['bitDepth'] );
	}

	public function testPngIndexColour() {
		$meta = PNGMetadataExtractor::getMetadata( self::FILE_PATH .
			'Png-native-test.png' );

		$this->assertEquals( 'index-coloured', $meta['colorType'] );
	}

	public function testPngRgbColour() {
		$meta = PNGMetadataExtractor::getMetadata( self::FILE_PATH .
			'rgb-png.png' );
		$this->assertEquals( 'truecolour-alpha', $meta['colorType'] );
	}

	public function testPngRgbNoAlphaColour() {
		$meta = PNGMetadataExtractor::getMetadata( self::FILE_PATH .
			'rgb-na-png.png' );
		$this->assertEquals( 'truecolour', $meta['colorType'] );
	}

	public function testPngGreyscaleColour() {
		$meta = PNGMetadataExtractor::getMetadata( self::FILE_PATH .
			'greyscale-png.png' );
		$this->assertEquals( 'greyscale-alpha', $meta['colorType'] );
	}

	public function testPngGreyscaleNoAlphaColour() {
		$meta = PNGMetadataExtractor::getMetadata( self::FILE_PATH .
			'greyscale-na-png.png' );
		$this->assertEquals( 'greyscale', $meta['colorType'] );
	}

	/**
	 * T286273 -- tEXt chunk replaced by null bytes
	 */
	public function testPngInvalidChunk() {
		$meta = PNGMetadataExtractor::getMetadata( self::FILE_PATH .
			'tEXt-invalid-masked.png' );
		$this->assertEquals( 10, $meta['width'] );
		$this->assertEquals( 10, $meta['height'] );
	}

	/**
	 * T286273 -- oversize chunk
	 */
	public function testPngOversizeChunk() {
		// Write a temporary file consisting of a normal PNG plus an extra tEXt chunk.
		// Try to hold the chunk in memory only once.
		$path = $this->getNewTempFile();
		copy( self::FILE_PATH . '1bit-png.png', $path );
		$chunkTypeAndData = "tEXtkey\0value" . str_repeat( '.', 10000000 );
		$crc = crc32( $chunkTypeAndData );
		$chunkLength = strlen( $chunkTypeAndData ) - 4;
		$file = fopen( $path, 'r+' );
		fseek( $file, -12, SEEK_END );
		$iend = fread( $file, 12 );
		fseek( $file, -12, SEEK_END );
		fwrite( $file, pack( 'N', $chunkLength ) );
		fwrite( $file, $chunkTypeAndData );
		fwrite( $file, pack( 'N', $crc ) );
		fwrite( $file, $iend );
		fclose( $file );

		// Extract the metadata
		$meta = PNGMetadataExtractor::getMetadata( $path );
		$this->assertEquals( 50, $meta['width'] );
		$this->assertEquals( 50, $meta['height'] );

		// Verify that the big chunk didn't end up in the metadata
		$this->assertLessThan( 100000, strlen( serialize( $meta ) ) );
	}

}

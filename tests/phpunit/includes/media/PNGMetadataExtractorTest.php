<?php
class PNGMetadataExtractorTest extends MediaWikiTestCase {

	function setUp() {
		$this->filePath = dirname( __FILE__ ) . '/../../data/media/';	
	}
	/**
	 * Tests zTXt tag (compressed textual metadata) 
	 */
	function testPngNativetZtxt() {
		$meta = PNGMetadataExtractor::getMetadata( $this->filePath .
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
	function testPngNativeText() {
		$meta = PNGMetadataExtractor::getMetadata( $this->filePath .
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
	function testPngNativeTextNonAscii() {
		$meta = PNGMetadataExtractor::getMetadata( $this->filePath .
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
	 * Test extraction of pHYs tags, which can tell what the
	 * actual resolution of the image is (aka in dots per meter).
	function testPngPhysTag () {
		$meta = PNGMetadataExtractor::getMetadata( $this->filePath .
			'Png-native-test.png' );

		$this->assertArrayHasKey( 'text', $meta );
		$meta = $meta['text'];

		$this->assertEquals( '2835/100', $meta['XResolution'] );
		$this->assertEquals( '2835/100', $meta['YResolution'] );
		$this->assertEquals( 3, $meta['ResolutionUnit'] ); // 3 = cm
	}

	/**
	 * Given a normal static PNG, check the animation metadata returned.
	 */
	function testStaticPngAnimationMetadata() {
		$meta = PNGMetadataExtractor::getMetadata( $this->filePath .
			'Png-native-test.png' );

		$this->assertEquals( 0, $meta['frameCount'] );
		$this->assertEquals( 1, $meta['loopCount'] );
		$this->assertEquals( 0, $meta['duration'] );
	}

	/**
	 * Given an animated APNG image file
	 * check it gets animated metadata right.
	 */
	function testApngAnimationMetadata() {
		$meta = PNGMetadataExtractor::getMetadata( $this->filePath .
			'Animated_PNG_example_bouncing_beach_ball.png' );

		$this->assertEquals( 20, $meta['frameCount'] );
		// Note loop count of 0 = infinity
		$this->assertEquals( 0, $meta['loopCount'] );
		$this->assertEquals( 1.5, $meta['duration'], '', 0.00001 );
	}

	function testPngBitDepth8() {
		$meta = PNGMetadataExtractor::getMetadata( $this->filePath .
			'Png-native-test.png' );

		$this->assertEquals( 8, $meta['bitDepth'] );
	}
	function testPngBitDepth1() {
		$meta = PNGMetadataExtractor::getMetadata( $this->filePath .
			'1bit-png.png' );
		$this->assertEquals( 1, $meta['bitDepth'] );
	}


	function testPngIndexColour() {
		$meta = PNGMetadataExtractor::getMetadata( $this->filePath .
			'Png-native-test.png' );

		$this->assertEquals( 'index-coloured', $meta['colorType'] );
	}
	function testPngRgbColour() {
		$meta = PNGMetadataExtractor::getMetadata( $this->filePath .
			'rgb-png.png' );
		$this->assertEquals( 'truecolour-alpha', $meta['colorType'] );
	}
	function testPngRgbNoAlphaColour() {
		$meta = PNGMetadataExtractor::getMetadata( $this->filePath .
			'rgb-na-png.png' );
		$this->assertEquals( 'truecolour', $meta['colorType'] );
	}
	function testPngGreyscaleColour() {
		$meta = PNGMetadataExtractor::getMetadata( $this->filePath .
			'greyscale-png.png' );
		$this->assertEquals( 'greyscale-alpha', $meta['colorType'] );
	}
	function testPngGreyscaleNoAlphaColour() {
		$meta = PNGMetadataExtractor::getMetadata( $this->filePath .
			'greyscale-na-png.png' );
		$this->assertEquals( 'greyscale', $meta['colorType'] );
	}

}

<?php
class FileTest extends MediaWikiMediaTestCase {

	function setUp() {
		$this->setMWGlobals( 'wgMaxAnimatedGifArea', 9000 );
		parent::setUp();
	}

	/**
	 * @param $filename String
	 * @param $expected boolean
	 * @dataProvider providerCanAnimate
	 */
	function testCanAnimateThumbIfAppropriate( $filename, $expected ) {
		$file = $this->dataFile( $filename );
		$this->assertEquals( $file->canAnimateThumbIfAppropriate(), $expected );
	}

	function providerCanAnimate() {
		return array(
			array( 'nonanimated.gif', true ),
			array( 'jpeg-comment-utf.jpg', true ),
			array( 'test.tiff', true ),
			array( 'Animated_PNG_example_bouncing_beach_ball.png', false ),
			array( 'greyscale-png.png', true ),
			array( 'Toll_Texas_1.svg', true ),
			array( 'LoremIpsum.djvu', true ),
			array( '80x60-2layers.xcf', true ),
			array( 'Soccer_ball_animated.svg', false ),
			array( 'Bishzilla_blink.gif', false ),
			array( 'animated.gif', true ),
		);
	}
}

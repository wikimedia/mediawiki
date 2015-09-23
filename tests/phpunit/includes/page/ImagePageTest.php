<?php
class ImagePageTest extends MediaWikiMediaTestCase {

	function setUp() {
		$this->setMwGlobals( 'wgImageLimits', array(
			array( 320, 240 ),
			array( 640, 480 ),
			array( 800, 600 ),
			array( 1024, 768 ),
			array( 1280, 1024 )
		) );
		parent::setUp();
	}

	function getImagePage( $filename ) {
		$title = Title::makeTitleSafe( NS_FILE, $filename );
		$file = $this->dataFile( $filename );
		$iPage = new ImagePage( $title );
		$iPage->setFile( $file );
		return $iPage;
	}

	/**
	 * @dataProvider providerGetDisplayWidthHeight
	 * @param array $dim Array [maxWidth, maxHeight, width, height]
	 * @param array $expected Array [width, height] The width and height we expect to display at
	 */
	function testGetDisplayWidthHeight( $dim, $expected ) {
		$iPage = $this->getImagePage( 'animated.gif' );
		$reflection = new ReflectionClass( $iPage );
		$reflMethod = $reflection->getMethod( 'getDisplayWidthHeight' );
		$reflMethod->setAccessible( true );

		$actual = $reflMethod->invoke( $iPage, $dim[0], $dim[1], $dim[2], $dim[3] );
		$this->assertEquals( $actual, $expected );
	}

	function providerGetDisplayWidthHeight() {
		return array(
			array(
				array( 1024.0, 768.0, 600.0, 600.0 ),
				array( 600.0, 600.0 )
			),
			array(
				array( 1024.0, 768.0, 1600.0, 600.0 ),
				array( 1024.0, 384.0 )
			),
			array(
				array( 1024.0, 768.0, 1024.0, 768.0 ),
				array( 1024.0, 768.0 )
			),
			array(
				array( 1024.0, 768.0, 800.0, 1000.0 ),
				array( 614.0, 768.0 )
			),
			array(
				array( 1024.0, 768.0, 0, 1000 ),
				array( 0, 0 )
			),
			array(
				array( 1024.0, 768.0, 2000, 0 ),
				array( 0, 0 )
			),
		);
	}

	/**
	 * @dataProvider providerGetThumbSizes
	 * @param string $filename
	 * @param int $expectedNumberThumbs How many thumbnails to show
	 */
	function testGetThumbSizes( $filename, $expectedNumberThumbs ) {
		$iPage = $this->getImagePage( $filename );
		$reflection = new ReflectionClass( $iPage );
		$reflMethod = $reflection->getMethod( 'getThumbSizes' );
		$reflMethod->setAccessible( true );

		$actual = $reflMethod->invoke( $iPage, 545, 700 );
		$this->assertEquals( count( $actual ), $expectedNumberThumbs );
	}

	function providerGetThumbSizes() {
		return array(
			array( 'animated.gif', 2 ),
			array( 'Toll_Texas_1.svg', 1 ),
			array( '80x60-Greyscale.xcf', 1 ),
			array( 'jpeg-comment-binary.jpg', 2 ),
		);
	}
}

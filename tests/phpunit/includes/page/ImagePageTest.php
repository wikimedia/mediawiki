<?php
class ImagePageTest extends MediaWikiMediaTestCase {

	function setUp() {
		$this->setMwGlobals( 'wgImageLimits', [
			[ 320, 240 ],
			[ 640, 480 ],
			[ 800, 600 ],
			[ 1024, 768 ],
			[ 1280, 1024 ]
		] );
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
	 * @covers ImagePage::getDisplayWidthHeight
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
		return [
			[
				[ 1024.0, 768.0, 600.0, 600.0 ],
				[ 600.0, 600.0 ]
			],
			[
				[ 1024.0, 768.0, 1600.0, 600.0 ],
				[ 1024.0, 384.0 ]
			],
			[
				[ 1024.0, 768.0, 1024.0, 768.0 ],
				[ 1024.0, 768.0 ]
			],
			[
				[ 1024.0, 768.0, 800.0, 1000.0 ],
				[ 614.0, 768.0 ]
			],
			[
				[ 1024.0, 768.0, 0, 1000 ],
				[ 0, 0 ]
			],
			[
				[ 1024.0, 768.0, 2000, 0 ],
				[ 0, 0 ]
			],
		];
	}

	/**
	 * @covers ImagePage::getThumbSizes
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
		return [
			[ 'animated.gif', 2 ],
			[ 'Toll_Texas_1.svg', 1 ],
			[ '80x60-Greyscale.xcf', 1 ],
			[ 'jpeg-comment-binary.jpg', 2 ],
		];
	}
}

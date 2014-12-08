<?php
/**
 * For doing Image Page tests that rely on 404 thumb handling
 */
class ImagePage404Test extends MediaWikiMediaTestCase {

	protected function getRepoOptions() {
		return parent::getRepoOptions() + array( 'transformVia404' => true );
	}

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
			array( 'animated.gif', 6 ),
			array( 'Toll_Texas_1.svg', 6 ),
			array( '80x60-Greyscale.xcf', 6 ),
			array( 'jpeg-comment-binary.jpg', 6 ),
		);
	}
}

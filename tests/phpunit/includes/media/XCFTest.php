<?php

/**
 * @group Media
 */
class XCFHandlerTest extends MediaWikiMediaTestCase {

	/** @var XCFHandler */
	protected $handler;

	protected function setUp() {
		parent::setUp();
		$this->handler = new XCFHandler();
	}


	/**
	 * @param string $filename
	 * @param int $expectedWidth Width
	 * @param int $expectedHeight Height
	 * @dataProvider provideGetImageSize
	 * @covers XCFHandler::getImageSize
	 */
	public function testGetImageSize( $filename, $expectedWidth, $expectedHeight ) {
		$file = $this->dataFile( $filename, 'image/x-xcf' );
		$actual = $this->handler->getImageSize( $file, $file->getLocalRefPath() );
		$this->assertEquals( $expectedWidth, $actual[0] );
		$this->assertEquals( $expectedHeight, $actual[1] );
	}

	public static function provideGetImageSize() {
		return array(
			array( '80x60-2layers.xcf', 80, 60 ),
			array( '80x60-RGB.xcf', 80, 60 ),
			array( '80x60-Greyscale.xcf', 80, 60 ),
		);
	}

	/**
	 * @param string $metadata Serialized metadata
	 * @param int $expected One of the class constants of XCFHandler
	 * @dataProvider provideIsMetadataValid
	 * @covers XCFHandler::isMetadataValid
	 */
	public function testIsMetadataValid( $metadata, $expected ) {
		$actual = $this->handler->isMetadataValid( null, $metadata );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideIsMetadataValid() {
		return array(
			array( '', XCFHandler::METADATA_BAD ),
			array( serialize( array( 'error' => true ) ), XCFHandler::METADATA_GOOD ),
			array( false, XCFHandler::METADATA_BAD ),
			array( serialize( array( 'colorType' => 'greyscale-alpha' ) ), XCFHandler::METADATA_GOOD ),
		);
	}

	/**
	 * @param string $filename
	 * @param string $expected Serialized array
	 * @dataProvider provideGetMetadata
	 * @covers XCFHandler::getMetadata
	 */
	public function testGetMetadata( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/png' );
		$actual = $this->handler->getMetadata( $file, "$this->filePath/$filename" );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetMetadata() {
		return array(
			array( '80x60-2layers.xcf', 'a:1:{s:9:"colorType";s:16:"truecolour-alpha";}' ),
			array( '80x60-RGB.xcf', 'a:1:{s:9:"colorType";s:16:"truecolour-alpha";}' ),
			array( '80x60-Greyscale.xcf', 'a:1:{s:9:"colorType";s:15:"greyscale-alpha";}' ),
		);
	}
}

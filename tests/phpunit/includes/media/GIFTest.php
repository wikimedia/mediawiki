<?php

/**
 * @group Media
 */
class GIFHandlerTest extends MediaWikiMediaTestCase {

	/** @var GIFHandler */
	protected $handler;

	protected function setUp() {
		parent::setUp();

		$this->handler = new GIFHandler();
	}

	/**
	 * @covers GIFHandler::getMetadata
	 */
	public function testInvalidFile() {
		$res = $this->handler->getMetadata( null, $this->filePath . '/README' );
		$this->assertEquals( GIFHandler::BROKEN_FILE, $res );
	}

	/**
	 * @param string $filename Basename of the file to check
	 * @param bool $expected Expected result.
	 * @dataProvider provideIsAnimated
	 * @covers GIFHandler::isAnimatedImage
	 */
	public function testIsAnimanted( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/gif' );
		$actual = $this->handler->isAnimatedImage( $file );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideIsAnimated() {
		return array(
			array( 'animated.gif', true ),
			array( 'nonanimated.gif', false ),
		);
	}

	/**
	 * @param string $filename
	 * @param int $expected Total image area
	 * @dataProvider provideGetImageArea
	 * @covers GIFHandler::getImageArea
	 */
	public function testGetImageArea( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/gif' );
		$actual = $this->handler->getImageArea( $file, $file->getWidth(), $file->getHeight() );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetImageArea() {
		return array(
			array( 'animated.gif', 5400 ),
			array( 'nonanimated.gif', 1350 ),
		);
	}

	/**
	 * @param string $metadata Serialized metadata
	 * @param int $expected One of the class constants of GIFHandler
	 * @dataProvider provideIsMetadataValid
	 * @covers GIFHandler::isMetadataValid
	 */
	public function testIsMetadataValid( $metadata, $expected ) {
		$actual = $this->handler->isMetadataValid( null, $metadata );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideIsMetadataValid() {
		return array(
			array( GIFHandler::BROKEN_FILE, GIFHandler::METADATA_GOOD ),
			array( '', GIFHandler::METADATA_BAD ),
			array( null, GIFHandler::METADATA_BAD ),
			array( 'Something invalid!', GIFHandler::METADATA_BAD ),
			// @codingStandardsIgnoreStart Ignore Generic.Files.LineLength.TooLong
			array( 'a:4:{s:10:"frameCount";i:1;s:6:"looped";b:0;s:8:"duration";d:0.1000000000000000055511151231257827021181583404541015625;s:8:"metadata";a:2:{s:14:"GIFFileComment";a:1:{i:0;s:35:"GIF test file ⁕ Created with GIMP";}s:15:"_MW_GIF_VERSION";i:1;}}', GIFHandler::METADATA_GOOD ),
			// @codingStandardsIgnoreEnd
		);
	}

	/**
	 * @param string $filename
	 * @param string $expected Serialized array
	 * @dataProvider provideGetMetadata
	 * @covers GIFHandler::getMetadata
	 */
	public function testGetMetadata( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/gif' );
		$actual = $this->handler->getMetadata( $file, "$this->filePath/$filename" );
		$this->assertEquals( unserialize( $expected ), unserialize( $actual ) );
	}

	public static function provideGetMetadata() {
		return array(
			// @codingStandardsIgnoreStart Ignore Generic.Files.LineLength.TooLong
			array( 'nonanimated.gif', 'a:4:{s:10:"frameCount";i:1;s:6:"looped";b:0;s:8:"duration";d:0.1000000000000000055511151231257827021181583404541015625;s:8:"metadata";a:2:{s:14:"GIFFileComment";a:1:{i:0;s:35:"GIF test file ⁕ Created with GIMP";}s:15:"_MW_GIF_VERSION";i:1;}}' ),
			array( 'animated-xmp.gif', 'a:4:{s:10:"frameCount";i:4;s:6:"looped";b:1;s:8:"duration";d:2.399999999999999911182158029987476766109466552734375;s:8:"metadata";a:5:{s:6:"Artist";s:7:"Bawolff";s:16:"ImageDescription";a:2:{s:9:"x-default";s:18:"A file to test GIF";s:5:"_type";s:4:"lang";}s:15:"SublocationDest";s:13:"The interwebs";s:14:"GIFFileComment";a:1:{i:0;s:16:"GIƒ·test·file";}s:15:"_MW_GIF_VERSION";i:1;}}' ),
			// @codingStandardsIgnoreEnd
		);
	}

	/**
	 * @param string $filename
	 * @param string $expected Serialized array
	 * @dataProvider provideGetIndependentMetaArray
	 * @covers GIFHandler::getCommonMetaArray
	 */
	public function testGetIndependentMetaArray( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/gif' );
		$actual = $this->handler->getCommonMetaArray( $file );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetIndependentMetaArray() {
		return array(
			array( 'nonanimated.gif', array(
				'GIFFileComment' => array(
					'GIF test file ⁕ Created with GIMP',
				),
			) ),
			array( 'animated-xmp.gif',
				array(
					'Artist' => 'Bawolff',
					'ImageDescription' => array(
						'x-default' => 'A file to test GIF',
						'_type' => 'lang',
					),
					'SublocationDest' => 'The interwebs',
					'GIFFileComment' =>
					array(
						'GIƒ·test·file',
					),
				)
			),
		);
	}
}

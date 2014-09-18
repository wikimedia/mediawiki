<?php

/**
 * @group Media
 */
class PNGHandlerTest extends MediaWikiMediaTestCase {

	/** @var PNGHandler */
	protected $handler;

	protected function setUp() {
		parent::setUp();
		$this->handler = new PNGHandler();
	}

	/**
	 * @covers PNGHandler::getMetadata
	 */
	public function testInvalidFile() {
		$res = $this->handler->getMetadata( null, $this->filePath . '/README' );
		$this->assertEquals( PNGHandler::BROKEN_FILE, $res );
	}

	/**
	 * @param string $filename Basename of the file to check
	 * @param bool $expected Expected result.
	 * @dataProvider provideIsAnimated
	 * @covers PNGHandler::isAnimatedImage
	 */
	public function testIsAnimanted( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/png' );
		$actual = $this->handler->isAnimatedImage( $file );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideIsAnimated() {
		return array(
			array( 'Animated_PNG_example_bouncing_beach_ball.png', true ),
			array( '1bit-png.png', false ),
		);
	}

	/**
	 * @param string $filename
	 * @param int $expected Total image area
	 * @dataProvider provideGetImageArea
	 * @covers PNGHandler::getImageArea
	 */
	public function testGetImageArea( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/png' );
		$actual = $this->handler->getImageArea( $file, $file->getWidth(), $file->getHeight() );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetImageArea() {
		return array(
			array( '1bit-png.png', 2500 ),
			array( 'greyscale-png.png', 2500 ),
			array( 'Png-native-test.png', 126000 ),
			array( 'Animated_PNG_example_bouncing_beach_ball.png', 10000 ),
		);
	}

	/**
	 * @param string $metadata Serialized metadata
	 * @param int $expected One of the class constants of PNGHandler
	 * @dataProvider provideIsMetadataValid
	 * @covers PNGHandler::isMetadataValid
	 */
	public function testIsMetadataValid( $metadata, $expected ) {
		$actual = $this->handler->isMetadataValid( null, $metadata );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideIsMetadataValid() {
		return array(
			array( PNGHandler::BROKEN_FILE, PNGHandler::METADATA_GOOD ),
			array( '', PNGHandler::METADATA_BAD ),
			array( null, PNGHandler::METADATA_BAD ),
			array( 'Something invalid!', PNGHandler::METADATA_BAD ),
			// @codingStandardsIgnoreStart Ignore Generic.Files.LineLength.TooLong
			array( 'a:6:{s:10:"frameCount";i:0;s:9:"loopCount";i:1;s:8:"duration";d:0;s:8:"bitDepth";i:8;s:9:"colorType";s:10:"truecolour";s:8:"metadata";a:1:{s:15:"_MW_PNG_VERSION";i:1;}}', PNGHandler::METADATA_GOOD ),
			// @codingStandardsIgnoreEnd
		);
	}

	/**
	 * @param string $filename
	 * @param string $expected Serialized array
	 * @dataProvider provideGetMetadata
	 * @covers PNGHandler::getMetadata
	 */
	public function testGetMetadata( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/png' );
		$actual = $this->handler->getMetadata( $file, "$this->filePath/$filename" );
//		$this->assertEquals( unserialize( $expected ), unserialize( $actual ) );
		$this->assertEquals( ( $expected ), ( $actual ) );
	}

	public static function provideGetMetadata() {
		return array(
			// @codingStandardsIgnoreStart Ignore Generic.Files.LineLength.TooLong
			array( 'rgb-na-png.png', 'a:6:{s:10:"frameCount";i:0;s:9:"loopCount";i:1;s:8:"duration";d:0;s:8:"bitDepth";i:8;s:9:"colorType";s:10:"truecolour";s:8:"metadata";a:1:{s:15:"_MW_PNG_VERSION";i:1;}}' ),
			array( 'xmp.png', 'a:6:{s:10:"frameCount";i:0;s:9:"loopCount";i:1;s:8:"duration";d:0;s:8:"bitDepth";i:1;s:9:"colorType";s:14:"index-coloured";s:8:"metadata";a:2:{s:12:"SerialNumber";s:9:"123456789";s:15:"_MW_PNG_VERSION";i:1;}}' ),
			// @codingStandardsIgnoreEnd
		);
	}

	/**
	 * @param string $filename
	 * @param array $expected Expected standard metadata
	 * @dataProvider provideGetIndependentMetaArray
	 * @covers PNGHandler::getCommonMetaArray
	 */
	public function testGetIndependentMetaArray( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/png' );
		$actual = $this->handler->getCommonMetaArray( $file );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetIndependentMetaArray() {
		return array(
			array( 'rgb-na-png.png', array() ),
			array( 'xmp.png',
				array(
					'SerialNumber' => '123456789',
				)
			),
		);
	}
}

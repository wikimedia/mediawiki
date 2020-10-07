<?php

/**
 * @group Media
 */
class PNGHandlerTest extends MediaWikiMediaTestCase {

	/** @var PNGHandler */
	protected $handler;

	protected function setUp() : void {
		parent::setUp();
		$this->handler = new PNGHandler();
	}

	/**
	 * @return string Value of PNGHandler::BROKEN_FILE
	 */
	private function brokenFile() : string {
		$const = new ReflectionClassConstant( PNGHandler::class, 'BROKEN_FILE' );
		return $const->getValue();
	}

	/**
	 * @covers PNGHandler::getMetadata
	 */
	public function testInvalidFile() {
		$res = $this->handler->getMetadata( null, $this->filePath . '/README' );
		$this->assertEquals( $this->brokenFile(), $res );
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
		return [
			[ 'Animated_PNG_example_bouncing_beach_ball.png', true ],
			[ '1bit-png.png', false ],
		];
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
		return [
			[ '1bit-png.png', 2500 ],
			[ 'greyscale-png.png', 2500 ],
			[ 'Png-native-test.png', 126000 ],
			[ 'Animated_PNG_example_bouncing_beach_ball.png', 10000 ],
		];
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

	public function provideIsMetadataValid() {
		// phpcs:disable Generic.Files.LineLength
		return [
			[ $this->brokenFile(), PNGHandler::METADATA_GOOD ],
			[ '', PNGHandler::METADATA_BAD ],
			[ null, PNGHandler::METADATA_BAD ],
			[ 'Something invalid!', PNGHandler::METADATA_BAD ],
			[
				'a:6:{s:10:"frameCount";i:0;s:9:"loopCount";i:1;s:8:"duration";d:0;s:8:"bitDepth";i:8;s:9:"colorType";s:10:"truecolour";s:8:"metadata";a:1:{s:15:"_MW_PNG_VERSION";i:1;}}',
				PNGHandler::METADATA_GOOD
			],
		];
		// phpcs:enable
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
		// $this->assertEquals( unserialize( $expected ), unserialize( $actual ) );
		$this->assertEquals( ( $expected ), ( $actual ) );
	}

	public static function provideGetMetadata() {
		// phpcs:disable Generic.Files.LineLength
		return [
			[
				'rgb-na-png.png',
				'a:6:{s:10:"frameCount";i:0;s:9:"loopCount";i:1;s:8:"duration";d:0;s:8:"bitDepth";i:8;s:9:"colorType";s:10:"truecolour";s:8:"metadata";a:1:{s:15:"_MW_PNG_VERSION";i:1;}}'
			],
			[
				'xmp.png',
				'a:6:{s:10:"frameCount";i:0;s:9:"loopCount";i:1;s:8:"duration";d:0;s:8:"bitDepth";i:1;s:9:"colorType";s:14:"index-coloured";s:8:"metadata";a:2:{s:12:"SerialNumber";s:9:"123456789";s:15:"_MW_PNG_VERSION";i:1;}}'
			],
		];
		// phpcs:enable
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
		return [
			[ 'rgb-na-png.png', [] ],
			[ 'xmp.png',
				[
					'SerialNumber' => '123456789',
				]
			],
		];
	}

	/**
	 * @param string $filename
	 * @param float $expectedLength
	 * @dataProvider provideGetLength
	 * @covers PNGHandler::getLength
	 */
	public function testGetLength( $filename, $expectedLength ) {
		$file = $this->dataFile( $filename, 'image/png' );
		$actualLength = $file->getLength();
		$this->assertEqualsWithDelta( $expectedLength, $actualLength, 0.00001 );
	}

	public function provideGetLength() {
		return [
			[ 'Animated_PNG_example_bouncing_beach_ball.png', 1.5 ],
			[ 'Png-native-test.png', 0.0 ],
			[ 'greyscale-png.png', 0.0 ],
			[ '1bit-png.png', 0.0 ],
		];
	}
}

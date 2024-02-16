<?php

/**
 * @group Media
 */
class GIFHandlerTest extends MediaWikiMediaTestCase {

	/** @var GIFHandler */
	protected $handler;

	protected function setUp(): void {
		parent::setUp();

		$this->handler = new GIFHandler();
	}

	/**
	 * @return array Unserialized metadata array for GIFHandler::BROKEN_FILE
	 */
	private function brokenFile(): array {
		return [ '_error' => 0 ];
	}

	/**
	 * @covers \GIFHandler::getSizeAndMetadata
	 */
	public function testInvalidFile() {
		$res = $this->handler->getSizeAndMetadata( null, $this->filePath . '/README' );
		$this->assertEquals( $this->brokenFile(), $res['metadata'] );
	}

	/**
	 * @param string $filename Basename of the file to check
	 * @param bool $expected Expected result.
	 * @dataProvider provideIsAnimated
	 * @covers \GIFHandler::isAnimatedImage
	 */
	public function testIsAnimated( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/gif' );
		$actual = $this->handler->isAnimatedImage( $file );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideIsAnimated() {
		return [
			[ 'animated.gif', true ],
			[ 'nonanimated.gif', false ],
		];
	}

	/**
	 * @param string $filename
	 * @param int $expected Total image area
	 * @dataProvider provideGetImageArea
	 * @covers \GIFHandler::getImageArea
	 */
	public function testGetImageArea( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/gif' );
		$actual = $this->handler->getImageArea( $file, $file->getWidth(), $file->getHeight() );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetImageArea() {
		return [
			[ 'animated.gif', 5400 ],
			[ 'nonanimated.gif', 1350 ],
		];
	}

	/**
	 * @param string $metadata Serialized metadata
	 * @param int $expected One of the class constants of GIFHandler
	 * @dataProvider provideIsFileMetadataValid
	 * @covers \GIFHandler::isFileMetadataValid
	 */
	public function testIsFileMetadataValid( $metadata, $expected ) {
		$file = $this->getMockFileWithMetadata( $metadata );
		$actual = $this->handler->isFileMetadataValid( $file );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideIsFileMetadataValid() {
		return [
			[ '0', GIFHandler::METADATA_GOOD ],
			[ '', GIFHandler::METADATA_BAD ],
			[ 'a:0:{}', GIFHandler::METADATA_BAD ],
			[ 'Something invalid!', GIFHandler::METADATA_BAD ],
			[
				'a:4:{s:10:"frameCount";i:1;s:6:"looped";b:0;s:8:"duration";d:0.1000000000000000055511151231257827021181583404541015625;s:8:"metadata";a:2:{s:14:"GIFFileComment";a:1:{i:0;s:35:"GIF test file ⁕ Created with GIMP";}s:15:"_MW_GIF_VERSION";i:1;}}',
				GIFHandler::METADATA_GOOD
			],
		];
		// phpcs:enable
	}

	/**
	 * @param string $filename
	 * @param array $expected Unserialized metadata
	 * @dataProvider provideGetSizeAndMetadata
	 * @covers \GIFHandler::getSizeAndMetadata
	 */
	public function testGetSizeAndMetadata( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/gif' );
		$actual = $this->handler->getSizeAndMetadata( $file, "$this->filePath/$filename" );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetSizeAndMetadata() {
		return [
			[
				'nonanimated.gif',
				[
					'width' => 45,
					'height' => 30,
					'bits' => 1,
					'metadata' => [
						'frameCount' => 1,
						'looped' => false,
						'duration' => 0.1,
						'metadata' => [
							'GIFFileComment' => [ 'GIF test file ⁕ Created with GIMP' ],
							'_MW_GIF_VERSION' => 1,
						],
					],
				]
			],
			[
				'animated-xmp.gif',
				[
					'width' => 45,
					'height' => 30,
					'bits' => 1,
					'metadata' => [
						'frameCount' => 4,
						'looped' => true,
						'duration' => 2.4,
						'metadata' => [
							'Artist' => 'Bawolff',
							'ImageDescription' => [
								'x-default' => 'A file to test GIF',
								'_type' => 'lang',
							],
							'SublocationDest' => 'The interwebs',
							'GIFFileComment' => [
								0 => 'GIƒ·test·file',
							],
							'_MW_GIF_VERSION' => 1,
						],
					],
				],

			],
		];
		// phpcs:enable
	}

	/**
	 * @param string $filename
	 * @param string $expected Serialized array
	 * @dataProvider provideGetIndependentMetaArray
	 * @covers \GIFHandler::getCommonMetaArray
	 */
	public function testGetIndependentMetaArray( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/gif' );
		$actual = $this->handler->getCommonMetaArray( $file );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideGetIndependentMetaArray() {
		return [
			[ 'nonanimated.gif', [
				'GIFFileComment' => [
					'GIF test file ⁕ Created with GIMP',
				],
			] ],
			[ 'animated-xmp.gif',
				[
					'Artist' => 'Bawolff',
					'ImageDescription' => [
						'x-default' => 'A file to test GIF',
						'_type' => 'lang',
					],
					'SublocationDest' => 'The interwebs',
					'GIFFileComment' =>
					[
						'GIƒ·test·file',
					],
				]
			],
		];
	}

	/**
	 * @param string $filename
	 * @param float $expectedLength
	 * @dataProvider provideGetLength
	 * @covers \GIFHandler::getLength
	 */
	public function testGetLength( $filename, $expectedLength ) {
		$file = $this->dataFile( $filename, 'image/gif' );
		$actualLength = $file->getLength();
		$this->assertEqualsWithDelta( $expectedLength, $actualLength, 0.00001 );
	}

	public static function provideGetLength() {
		return [
			[ 'animated.gif', 2.4 ],
			[ 'animated-xmp.gif', 2.4 ],
			[ 'nonanimated', 0.0 ],
			[ 'Bishzilla_blink.gif', 1.4 ],
		];
	}
}

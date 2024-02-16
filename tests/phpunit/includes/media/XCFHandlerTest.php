<?php

/**
 * @group Media
 */
class XCFHandlerTest extends MediaWikiMediaTestCase {

	/** @var XCFHandler */
	protected $handler;

	protected function setUp(): void {
		parent::setUp();
		$this->handler = new XCFHandler();
	}

	/**
	 * @param string $filename
	 * @param array $expected
	 * @dataProvider provideGetSizeAndMetadata
	 * @covers \XCFHandler::getSizeAndMetadata
	 */
	public function testGetSizeAndMetadata( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/x-xcf' );
		$actual = $this->handler->getSizeAndMetadata( $file, $file->getLocalRefPath() );
		$this->assertSame( $expected, $actual );
	}

	public static function provideGetSizeAndMetadata() {
		return [
			[
				'80x60-2layers.xcf',
				[
					'width' => 80,
					'height' => 60,
					'bits' => 8,
					'metadata' => [
						'colorType' => 'truecolour-alpha',
					]
				],
			],
			[
				'80x60-RGB.xcf',
				[
					'width' => 80,
					'height' => 60,
					'bits' => 8,
					'metadata' => [
						'colorType' => 'truecolour-alpha',
					]
				],
			],
			[
				'80x60-Greyscale.xcf',
				[
					'width' => 80,
					'height' => 60,
					'bits' => 8,
					'metadata' => [
						'colorType' => 'greyscale-alpha',
					]
				]
			],
		];
	}

	/**
	 * @param string $metadata Serialized metadata
	 * @param int $expected One of the class constants of XCFHandler
	 * @dataProvider provideIsFileMetadataValid
	 * @covers \XCFHandler::isFileMetadataValid
	 */
	public function testIsFileMetadataValid( $metadata, $expected ) {
		$actual = $this->handler->isFileMetadataValid( $this->getMockFileWithMetadata( $metadata ) );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideIsFileMetadataValid() {
		return [
			[ '', XCFHandler::METADATA_BAD ],
			[ serialize( [ 'error' => true ] ), XCFHandler::METADATA_GOOD ],
			[ false, XCFHandler::METADATA_BAD ],
			[ serialize( [ 'colorType' => 'greyscale-alpha' ] ), XCFHandler::METADATA_GOOD ],
		];
	}
}

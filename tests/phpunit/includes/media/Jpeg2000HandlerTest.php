<?php

/**
 * @covers Jpeg2000Handler
 */
class Jpeg2000HandlerTest extends MediaWikiIntegrationTestCase {

	/** @var string */
	private $tempFileName;

	protected function setUp(): void {
		parent::setUp();
		// Allocated file for testing
		$this->tempFileName = tempnam( wfTempDir(), 'JPEG2000' );
	}

	protected function tearDown(): void {
		unlink( $this->tempFileName );
		parent::tearDown();
	}

	/**
	 * @dataProvider provideTestGetSizeAndMetadata
	 */
	public function testGetSizeAndMetadata( $path, $expectedResult ) {
		$handler = new Jpeg2000Handler();
		$this->assertEquals( $expectedResult, $handler->getSizeAndMetadata(
			new TrivialMediaHandlerState, $path ) );
	}

	public function provideTestGetSizeAndMetadata() {
		return [
			[ __DIR__ . '/../../data/media/jpeg2000-lossless.jp2', [
				'width' => 100,
				'height' => 100,
				'bits' => 8,
			] ],
			[ __DIR__ . '/../../data/media/jpeg2000-lossy.jp2', [
				'width' => 100,
				'height' => 100,
				'bits' => 8,
			] ],
			[ __DIR__ . '/../../data/media/jpeg2000-alpha.jp2', [
				'width' => 100,
				'height' => 100,
				'bits' => 8,
			] ],
			[ __DIR__ . '/../../data/media/jpeg2000-profile.jpf', [
				'width' => 100,
				'height' => 100,
				'bits' => 8,
			] ],

			// Error cases
			[ __FILE__, [] ],
		];
	}
}

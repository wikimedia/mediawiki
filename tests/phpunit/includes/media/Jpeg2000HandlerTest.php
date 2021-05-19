<?php

/**
 * @covers Jpeg2000Handler
 */
class Jpeg2000HandlerTest extends MediaWikiIntegrationTestCase {
	protected function setUp() : void {
		parent::setUp();
		// Allocated file for testing
		$this->tempFileName = tempnam( wfTempDir(), 'JPEG2000' );
	}

	protected function tearDown() : void {
		unlink( $this->tempFileName );
		parent::tearDown();
	}

	/**
	 * @dataProvider provideTestGetImageSize
	 */
	public function testGetImageSize( $path, $expectedResult ) {
		$handler = new Jpeg2000Handler();
		$this->assertEquals( $expectedResult, $handler->getImageSize( null, $path ) );
	}

	public function provideTestGetImageSize() {
		return [
			[ __DIR__ . '/../../data/media/jpeg2000-lossless.jp2', [
				0 => 100,
				1 => 100,
				2 => 10,
				3 => 'width="100" height="100"',
				'bits' => 8,
				'channels' => 3,
				'mime' => 'image/jp2'
			] ],
			[ __DIR__ . '/../../data/media/jpeg2000-lossy.jp2', [
				0 => 100,
				1 => 100,
				2 => 10,
				3 => 'width="100" height="100"',
				'bits' => 8,
				'channels' => 3,
				'mime' => 'image/jp2'
			] ],
			[ __DIR__ . '/../../data/media/jpeg2000-alpha.jp2', [
				0 => 100,
				1 => 100,
				2 => 10,
				3 => 'width="100" height="100"',
				'bits' => 8,
				'channels' => 4,
				'mime' => 'image/jp2'
			] ],
			[ __DIR__ . '/../../data/media/jpeg2000-profile.jpf', [
				0 => 100,
				1 => 100,
				2 => 10,
				3 => 'width="100" height="100"',
				'bits' => 8,
				'channels' => 4,
				'mime' => 'image/jp2'
			] ],

			// Error cases
			[ __FILE__, false ],
		];
	}
}

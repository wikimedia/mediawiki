<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Media;

use BmpHandler;
use MediaHandlerState;
use MediaWiki\FileRepo\File\File;
use MediaWikiMediaTestCase;

/**
 * @covers \BmpHandler
 * @group Media
 */
class BmpHandlerTest extends MediaWikiMediaTestCase {

	protected BmpHandler $handler;
	protected File $tempFile;

	protected function setUp(): void {
		parent::setUp();
		$this->handler = new BmpHandler();
		$this->tempFile = $this->createMock( File::class );
	}

	/**
	 * @covers \BmpHandler::mustRender
	 */
	public function testMustRender() {
		$this->assertTrue( $this->handler->mustRender( $this->tempFile ) );
	}

	/**
	 * @covers \BmpHandler::getThumbType
	 */
	public function testGetThumbType() {
		$this->assertEquals( [ 'png', 'image/png' ], $this->handler->getThumbType( 'bmp', 'image/bmp' ) );
	}

	/**
	 * @covers \BmpHandler::getSizeAndMetadata
	 * @dataProvider provideGetSizeAndMetadata
	 */
	public function testGetSizeAndMetadata( string $filename, array $expected ) {
		$stateMock = $this->createMock( MediaHandlerState::class );
		$res = $this->handler->getSizeAndMetadata( $stateMock, $filename );
		$this->assertEquals( $expected, $res );
	}

	public static function provideGetSizeAndMetadata(): array {
		return [
			[ __DIR__ . '/../../data/media/bmp-100x100.bmp', [ 'width' => 100, 'height' => 100 ] ],
			[ __DIR__ . '/../../data/media/bmp-200x150.bmp', [ 'width' => 200, 'height' => 150 ] ],
			[ __DIR__ . '/../../data/media/bmp-300x200.bmp', [ 'width' => 300, 'height' => 200 ] ],
		];
	}
}

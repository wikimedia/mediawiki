<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @since 1.42
 */

namespace MediaWiki\Tests\Media;

use BmpHandler;
use File;
use MediaHandlerState;
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

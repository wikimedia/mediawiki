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
 */

namespace Wikimedia\Tests\Leximorph\Util;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use Wikimedia\Leximorph\Util\JsonLoader;

/**
 * JsonLoaderTest
 *
 * This test class verifies the functionality of the {@see JsonLoader} class.
 *
 * Covered tests include:
 *   - Loading and decoding valid JSON.
 *   - Handling invalid JSON.
 *   - Handling missing file scenarios with and without allowing missing files.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 *
 * @covers \Wikimedia\Leximorph\Util\JsonLoader
 */
class JsonLoaderTest extends TestCase {

	/**
	 * Creates a temporary file with the specified content.
	 *
	 * @param string $content
	 *
	 * @return string The temporary file path.
	 * @throws RuntimeException if tempnam fails.
	 */
	private function createTempFile( string $content ): string {
		$file = tempnam( sys_get_temp_dir(), 'json_test_' );
		if ( $file === false ) {
			throw new RuntimeException( 'Unable to create temporary file' );
		}
		file_put_contents( $file, $content );

		return $file;
	}

	/**
	 * Removes a temporary file.
	 *
	 * @param string $file
	 *
	 * @return void
	 */
	private function removeTempFile( string $file ): void {
		if ( file_exists( $file ) ) {
			unlink( $file );
		}
	}

	/**
	 * Tests that load() returns the expected array for valid JSON.
	 *
	 * @since 1.45
	 */
	public function testLoadValidJson(): void {
		$jsonContent = (string)json_encode(
			[
				'key' => 'value',
				'num' => 123,
			]
		);
		$tempFile = $this->createTempFile( $jsonContent );

		$loader = new JsonLoader( new NullLogger() );
		$result = $loader->load( $tempFile, 'valid test' );

		$this->assertSame(
			[
				'key' => 'value',
				'num' => 123,
			],
			$result
		);

		$this->removeTempFile( $tempFile );
	}

	/**
	 * Tests that load() returns an empty array when JSON is invalid,
	 * and that an error is logged.
	 *
	 * @since 1.45
	 */
	public function testLoadInvalidJson(): void {
		$invalidJson = "this is not json";
		$tempFile = $this->createTempFile( $invalidJson );

		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->once() )->method( 'error' )->with(
			$this->stringContains( 'Expected an array' ),
			$this->arrayHasKey( 'error' )
		);

		$loader = new JsonLoader( $logger );
		$result = $loader->load( $tempFile, 'invalid test' );

		$this->assertSame( [], $result );

		$this->removeTempFile( $tempFile );
	}

	/**
	 * Tests that load() returns an empty array and logs an error when the file is missing
	 * and $allowMissing is false.
	 *
	 * @since 1.45
	 */
	public function testLoadMissingFileWithoutAllowMissing(): void {
		$nonexistentFile = sys_get_temp_dir() . '/nonexistent_' . uniqid() . '.json';

		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->once() )->method( 'error' )->with(
			$this->stringContains( 'Failed to read file contents' ),
			$this->callback( static function ( $context ) use ( $nonexistentFile ) {
				if ( !is_array( $context ) ) {
					return false;
				}

				return isset( $context['filePath'] ) && $context['filePath'] === $nonexistentFile;
			} )
		);

		$loader = new JsonLoader( $logger );
		$result = $loader->load( $nonexistentFile, 'missing file test' );

		$this->assertSame( [], $result );
	}

	/**
	 * Tests that load() returns an empty array without logging an error when the file is missing
	 * and $allowMissing is true.
	 *
	 * @since 1.45
	 */
	public function testLoadMissingFileWithAllowMissing(): void {
		$nonexistentFile = sys_get_temp_dir() . '/nonexistent_' . uniqid() . '.json';

		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->never() )->method( 'error' );

		$loader = new JsonLoader( $logger );
		$result = $loader->load( $nonexistentFile, 'missing file test', true );

		$this->assertSame( [], $result );
	}
}

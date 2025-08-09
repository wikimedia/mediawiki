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

use DOMDocument;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use Wikimedia\Leximorph\Util\XmlLoader;

/**
 * XmlLoaderTest
 *
 * This test class verifies the functionality of the {@see XmlLoader} class.
 *
 * Covered tests include:
 *   - Loading and parsing valid XML.
 *   - Handling invalid XML.
 *   - Handling missing file scenarios with and without allowing missing files.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 *
 * @covers \Wikimedia\Leximorph\Util\XmlLoader
 */
class XmlLoaderTest extends TestCase {

	/**
	 * Creates a temporary file with the specified content.
	 *
	 * @param string $content
	 *
	 * @return string The temporary file path.
	 * @throws RuntimeException if tempnam fails.
	 */
	private function createTempFile( string $content ): string {
		$file = tempnam( sys_get_temp_dir(), 'xml_test_' );
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
	 * Tests that load() returns a valid DOMDocument for well-formed XML.
	 *
	 * @since 1.45
	 */
	public function testLoadValidXml(): void {
		$xmlContent = '<?xml version="1.0" encoding="UTF-8"?><root><key>value</key><num>123</num></root>';
		$tempFile = $this->createTempFile( $xmlContent );

		$loader = new XmlLoader( new NullLogger() );
		$doc = $loader->load( $tempFile, 'valid XML test' );

		$this->assertInstanceOf( DOMDocument::class, $doc );
		$this->assertNotNull( $doc->documentElement );
		$this->assertSame( 'root', $doc->documentElement->nodeName );
		$keyElements = $doc->getElementsByTagName( 'key' );
		$this->assertGreaterThan( 0, $keyElements->length );

		$keyElement = $keyElements->item( 0 );
		$this->assertNotNull( $keyElement, 'Expected to find a <key> element.' );
		$this->assertSame( 'value', $keyElement->textContent );

		$this->removeTempFile( $tempFile );
	}

	/**
	 * Tests that load() returns null when XML is invalid, and that an error is logged.
	 *
	 * @since 1.45
	 */
	public function testLoadInvalidXml(): void {
		$invalidXml = "this is not xml";
		$tempFile = $this->createTempFile( $invalidXml );

		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->once() )->method( 'error' )->with(
			$this->stringContains( 'Invalid XML format' ),
			$this->arrayHasKey( 'filePath' )
		);

		$loader = new XmlLoader( $logger );
		$doc = $loader->load( $tempFile, 'invalid XML test' );

		$this->assertNull( $doc );

		$this->removeTempFile( $tempFile );
	}

	/**
	 * Tests that load() returns null and logs an error when the file is missing and $allowMissing is false.
	 *
	 * @since 1.45
	 */
	public function testLoadMissingFileWithoutAllowMissing(): void {
		$nonexistentFile = sys_get_temp_dir() . '/nonexistent_' . uniqid() . '.xml';

		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->once() )->method( 'error' )->with(
			$this->stringContains( 'Unable to read XML file' ),
			$this->callback( static function ( $context ) use ( $nonexistentFile ) {
				return is_array( $context ) &&
					isset( $context['filePath'] ) &&
					$context['filePath'] === $nonexistentFile;
			} )
		);

		$loader = new XmlLoader( $logger );
		$doc = $loader->load( $nonexistentFile, 'missing file test' );

		$this->assertNull( $doc );
	}

	/**
	 * Tests that load() returns null without logging an error when the file is missing and $allowMissing is true.
	 *
	 * @since 1.45
	 */
	public function testLoadMissingFileWithAllowMissing(): void {
		$nonexistentFile = sys_get_temp_dir() . '/nonexistent_' . uniqid() . '.xml';

		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->never() )->method( 'error' );

		$loader = new XmlLoader( $logger );
		$doc = $loader->load( $nonexistentFile, 'missing file test', true );

		$this->assertNull( $doc );
	}
}

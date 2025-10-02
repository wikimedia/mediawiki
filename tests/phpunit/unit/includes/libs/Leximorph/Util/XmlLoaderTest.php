<?php
/**
 * @license GPL-2.0-or-later
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
 * This test class verifies the functionality of the {@see XmlLoader} class.
 *
 * Covered tests include:
 *   - Loading and parsing valid XML.
 *   - Handling invalid XML.
 *   - Handling missing file scenarios with and without allowing missing files.
 *
 * @covers \Wikimedia\Leximorph\Util\XmlLoader
 * @author Doğu Abaris (abaris@null.net)
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

<?php

use Wikimedia\FileBackend\FileBackend;
use Wikimedia\FileBackend\MemoryFileBackend;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Wikimedia\FileBackend\FileBackendStore
 */
class FileBackendStoreTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideGetContentType
	 */
	public function testGetContentType( $mimeFromString ) {
		global $IP;

		if ( $mimeFromString ) {
			$mimeCallback = [ $this->getServiceContainer()->getFileBackendGroup(), 'guessMimeInternal' ];
		} else {
			$mimeCallback = null;
		}

		$be = TestingAccessWrapper::newFromObject( new MemoryFileBackend( [
			'name' => 'testing',
			'class' => MemoryFileBackend::class,
			'wikiId' => 'meow',
			'mimeCallback' => $mimeCallback,
		] ) );

		$dst = 'mwstore://testing/container/path/to/file_no_ext';
		$src = "$IP/tests/phpunit/data/media/srgb.jpg";
		$this->assertEquals( 'image/jpeg', $be->getContentType( $dst, null, $src ) );
		$this->assertEquals( $mimeFromString ? 'image/jpeg' : 'unknown/unknown',
			$be->getContentType( $dst, file_get_contents( $src ), null ) );

		$src = "$IP/tests/phpunit/data/media/Png-native-test.png";
		$this->assertEquals( 'image/png', $be->getContentType( $dst, null, $src ) );
		$this->assertEquals( $mimeFromString ? 'image/png' : 'unknown/unknown',
			$be->getContentType( $dst, file_get_contents( $src ), null ) );
	}

	public static function provideGetContentType() {
		return [
			[ false ],
			[ true ],
		];
	}

	public function testSanitizeOpHeaders() {
		$be = TestingAccessWrapper::newFromObject( new MemoryFileBackend( [
			'name' => 'localtesting',
			'wikiId' => 'wikidb',
		] ) );

		$input = [
			'headers' => [
				'content-Disposition' => FileBackend::makeContentDisposition( 'inline', 'name' ),
				'Content-dUration' => 25.6,
				'X-LONG-VALUE' => str_pad( '0', 300 ),
				'CONTENT-LENGTH' => 855055,
			],
		];
		$expected = [
			'headers' => [
				'content-disposition' => FileBackend::makeContentDisposition( 'inline', 'name' ),
				'content-duration' => 25.6,
				'content-length' => 855055,
			],
		];

		$actual = @$be->sanitizeOpHeaders( $input );
		$this->assertEquals( $expected, $actual, "Header sanitized properly" );
	}

}

<?php

namespace Wikimedia\Tests\ParamValidator\Util;

require_once __DIR__ . '/UploadedFileTestBase.php';

use Error;
use RuntimeException;
use TypeError;
use Wikimedia\ParamValidator\Util\UploadedFileStream;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Wikimedia\ParamValidator\Util\UploadedFileStream
 */
class UploadedFileStreamTest extends UploadedFileTestBase {

	public function testConstruct_doesNotExist() {
		$filename = $this->makeTemp( __FUNCTION__ );
		unlink( $filename );

		$this->assertFileDoesNotExist( $filename, 'Non existence check' );
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( "Failed to open file:" );
		$stream = new UploadedFileStream( $filename );
	}

	public function testConstruct_notReadable() {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( "Skip test, since chmod does not work on windows" );
		}

		$filename = $this->makeTemp( __FUNCTION__ );

		chmod( $filename, 0000 );
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( "Failed to open file:" );
		$stream = new UploadedFileStream( $filename );
	}

	public function testCloseOnDestruct() {
		$filename = $this->makeTemp( __FUNCTION__ );
		$stream = new UploadedFileStream( $filename );
		$fp = TestingAccessWrapper::newFromObject( $stream )->fp;
		$this->assertSame( 'f', fread( $fp, 1 ), 'read check' );
		unset( $stream );
		try {
			// PHP 7 raises warnings
			$this->assertFalse( @fread( $fp, 1 ) );
		} catch ( TypeError $ex ) {
			// PHP 8 throws
		}
	}

	public function testToString() {
		$filename = $this->makeTemp( __FUNCTION__ );
		$stream = new UploadedFileStream( $filename );

		// Always starts at the start of the stream
		$stream->seek( 3 );
		$this->assertSame( 'foobar', (string)$stream );

		// No exception when closed
		$stream->close();
		$this->assertSame( '', (string)$stream );
	}

	public function testToString_Error() {
		if ( !class_exists( Error::class ) ) {
			$this->markTestSkipped( 'No PHP Error class' );
		}

		// ... Yeah
		$filename = $this->makeTemp( __FUNCTION__ );
		$stream = $this->getMockBuilder( UploadedFileStream::class )
			->setConstructorArgs( [ $filename ] )
			->onlyMethods( [ 'getContents' ] )
			->getMock();
		$stream->method( 'getContents' )->willReturnCallback( static function () {
			throw new Error( 'Bogus' );
		} );
		$this->assertSame( '', (string)$stream );
	}

	public function testClose() {
		$filename = $this->makeTemp( __FUNCTION__ );
		$stream = new UploadedFileStream( $filename );

		$stream->close();

		// Second call doesn't error
		$stream->close();
	}

	public function testDetach() {
		$filename = $this->makeTemp( __FUNCTION__ );
		$stream = new UploadedFileStream( $filename );

		// We got the file descriptor
		$fp = $stream->detach();
		$this->assertNotNull( $fp );
		$this->assertSame( 'f', fread( $fp, 1 ) );

		// Stream operations now fail.
		try {
			$stream->seek( 0 );
		} catch ( RuntimeException $ex ) {
		}

		// Stream close doesn't affect the file descriptor
		$stream->close();
		$this->assertSame( 'o', fread( $fp, 1 ) );

		// Stream destruction doesn't affect the file descriptor
		unset( $stream );
		$this->assertSame( 'o', fread( $fp, 1 ) );

		// On a closed stream, we don't get a file descriptor
		$stream = new UploadedFileStream( $filename );
		$stream->close();
		$this->assertNull( $stream->detach() );
	}

	public function testGetSize() {
		$filename = $this->makeTemp( __FUNCTION__ );
		$stream = new UploadedFileStream( $filename );
		file_put_contents( $filename, 'foobarbaz' );
		$this->assertSame( 9, $stream->getSize() );

		// Cached
		file_put_contents( $filename, 'baz' );
		clearstatcache();
		$this->assertSame( 3, stat( $filename )['size'], 'size check' );
		$this->assertSame( 9, $stream->getSize() );

		// No error if closed
		$stream = new UploadedFileStream( $filename );
		$stream->close();
		$this->assertSame( null, $stream->getSize() );

		// No error even if the fd goes bad
		$stream = new UploadedFileStream( $filename );
		fclose( TestingAccessWrapper::newFromObject( $stream )->fp );
		$this->assertSame( null, $stream->getSize() );
	}

	public function testSeekTell() {
		$filename = $this->makeTemp( __FUNCTION__ );
		$stream = new UploadedFileStream( $filename );

		$stream->seek( 2 );
		$this->assertSame( 2, $stream->tell() );
		$stream->seek( 2, SEEK_CUR );
		$this->assertSame( 4, $stream->tell() );
		$stream->seek( -5, SEEK_END );
		$this->assertSame( 1, $stream->tell() );
		$stream->read( 2 );
		$this->assertSame( 3, $stream->tell() );

		$stream->close();
		try {
			$stream->seek( 0 );
		} catch ( RuntimeException $ex ) {
		}
		try {
			$stream->tell();
		} catch ( RuntimeException $ex ) {
		}
	}

	public function testEof() {
		$filename = $this->makeTemp( __FUNCTION__ );
		$stream = new UploadedFileStream( $filename );

		$this->assertFalse( $stream->eof() );
		$stream->getContents();
		$this->assertTrue( $stream->eof() );
		$stream->seek( -1, SEEK_END );
		$this->assertFalse( $stream->eof() );

		// No error if closed
		$stream = new UploadedFileStream( $filename );
		$stream->close();
		$this->assertTrue( $stream->eof() );

		// No error even if the fd goes bad
		$stream = new UploadedFileStream( $filename );
		fclose( TestingAccessWrapper::newFromObject( $stream )->fp );
		$this->assertIsBool( $stream->eof() );
	}

	public function testIsFuncs() {
		$filename = $this->makeTemp( __FUNCTION__ );
		$stream = new UploadedFileStream( $filename );
		$this->assertTrue( $stream->isSeekable() );
		$this->assertTrue( $stream->isReadable() );
		$this->assertFalse( $stream->isWritable() );

		$stream->close();
		$this->assertFalse( $stream->isSeekable() );
		$this->assertFalse( $stream->isReadable() );
		$this->assertFalse( $stream->isWritable() );
	}

	public function testRewind() {
		$filename = $this->makeTemp( __FUNCTION__ );
		$stream = new UploadedFileStream( $filename );

		$stream->seek( 2 );
		$this->assertSame( 2, $stream->tell() );
		$stream->rewind();
		$this->assertSame( 0, $stream->tell() );

		$stream->close();
		try {
			$stream->rewind();
		} catch ( RuntimeException $ex ) {
		}
	}

	public function testWrite() {
		$filename = $this->makeTemp( __FUNCTION__ );
		$stream = new UploadedFileStream( $filename );

		try {
			$stream->write( 'foo' );
		} catch ( RuntimeException $ex ) {
		}
	}

	public function testRead() {
		$filename = $this->makeTemp( __FUNCTION__ );
		$stream = new UploadedFileStream( $filename );

		$this->assertSame( 'foo', $stream->read( 3 ) );
		$this->assertSame( 'bar', $stream->read( 10 ) );
		$this->assertSame( '', $stream->read( 10 ) );
		$stream->rewind();
		$this->assertSame( 'foobar', $stream->read( 10 ) );

		$stream->close();
		try {
			$stream->read( 1 );
		} catch ( RuntimeException $ex ) {
		}
	}

	public function testGetContents() {
		$filename = $this->makeTemp( __FUNCTION__ );
		$stream = new UploadedFileStream( $filename );

		$this->assertSame( 'foobar', $stream->getContents() );
		$this->assertSame( '', $stream->getContents() );
		$stream->seek( 3 );
		$this->assertSame( 'bar', $stream->getContents() );

		$stream->close();
		try {
			$stream->getContents();
		} catch ( RuntimeException $ex ) {
		}
	}

	public function testGetMetadata() {
		// Whatever
		$filename = $this->makeTemp( __FUNCTION__ );
		$fp = fopen( $filename, 'r' );
		$expect = stream_get_meta_data( $fp );
		fclose( $fp );

		$stream = new UploadedFileStream( $filename );
		$this->assertSame( $expect, $stream->getMetadata() );
		foreach ( $expect as $k => $v ) {
			$this->assertSame( $v, $stream->getMetadata( $k ) );
		}
		$this->assertNull( $stream->getMetadata( 'bogus' ) );

		$stream->close();
		try {
			$stream->getMetadata();
		} catch ( RuntimeException $ex ) {
		}
	}

}

<?php

namespace Wikimedia\ParamValidator\Util;

require_once __DIR__ . '/UploadedFileTestBase.php';

use Psr\Http\Message\StreamInterface;
use RuntimeException;

/**
 * @covers Wikimedia\ParamValidator\Util\UploadedFile
 */
class UploadedFileTest extends UploadedFileTestBase {

	public function testGetStream() {
		$filename = $this->makeTemp( __FUNCTION__ );

		$file = new UploadedFile( [ 'error' => UPLOAD_ERR_OK, 'tmp_name' => $filename ], false );

		// getStream() fails for non-OK uploads
		foreach ( [
			UPLOAD_ERR_INI_SIZE,
			UPLOAD_ERR_FORM_SIZE,
			UPLOAD_ERR_PARTIAL,
			UPLOAD_ERR_NO_FILE,
			UPLOAD_ERR_NO_TMP_DIR,
			UPLOAD_ERR_CANT_WRITE,
			UPLOAD_ERR_EXTENSION,
			-42
		] as $code ) {
			$file2 = new UploadedFile( [ 'error' => $code, 'tmp_name' => $filename ], false );
			try {
				$file2->getStream();
				$this->fail( 'Expected exception not thrown' );
			} catch ( \PHPUnit\Framework\AssertionFailedError $ex ) {
				throw $ex;
			} catch ( RuntimeException $ex ) {
			}
		}

		// getStream() works
		$stream = $file->getStream();
		$this->assertInstanceOf( StreamInterface::class, $stream );
		$stream->seek( 0 );
		$this->assertSame( 'foobar', $stream->getContents() );

		// Second call also works
		$this->assertInstanceOf( StreamInterface::class, $file->getStream() );

		// getStream() throws after move, and the stream is invalidated too
		$file->moveTo( $filename . '.xxx' );
		try {
			try {
				$file->getStream();
				$this->fail( 'Expected exception not thrown' );
			} catch ( \PHPUnit\Framework\AssertionFailedError $ex ) {
				throw $ex;
			} catch ( RuntimeException $ex ) {
				$this->assertSame( 'File has already been moved', $ex->getMessage() );
			}
			try {
				$stream->seek( 0 );
				$stream->getContents();
				$this->fail( 'Expected exception not thrown' );
			} catch ( \PHPUnit\Framework\AssertionFailedError $ex ) {
				throw $ex;
			} catch ( RuntimeException $ex ) {
			}
		} finally {
			unlink( $filename . '.xxx' ); // Clean up
		}

		// getStream() fails if the file is missing
		$file = new UploadedFile( [ 'error' => UPLOAD_ERR_OK, 'tmp_name' => $filename ], true );
		try {
			$file->getStream();
			$this->fail( 'Expected exception not thrown' );
		} catch ( \PHPUnit\Framework\AssertionFailedError $ex ) {
			throw $ex;
		} catch ( RuntimeException $ex ) {
			$this->assertSame( 'Uploaded file is missing', $ex->getMessage() );
		}
	}

	public function testMoveTo() {
		// Successful move
		$filename = $this->makeTemp( __FUNCTION__ );
		$this->assertFileExists( $filename, 'sanity check' );
		$this->assertFileNotExists( "$filename.xxx", 'sanity check' );
		$file = new UploadedFile( [ 'error' => UPLOAD_ERR_OK, 'tmp_name' => $filename ], false );
		$file->moveTo( $filename . '.xxx' );
		$this->assertFileNotExists( $filename );
		$this->assertFileExists( "$filename.xxx" );

		// Fails on a second move attempt
		$this->assertFileNotExists( "$filename.yyy", 'sanity check' );
		try {
			$file->moveTo( $filename . '.yyy' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \PHPUnit\Framework\AssertionFailedError $ex ) {
			throw $ex;
		} catch ( RuntimeException $ex ) {
			$this->assertSame( 'File has already been moved', $ex->getMessage() );
		}
		$this->assertFileNotExists( $filename );
		$this->assertFileExists( "$filename.xxx" );
		$this->assertFileNotExists( "$filename.yyy" );

		// Fails if the file is missing
		$file = new UploadedFile( [ 'error' => UPLOAD_ERR_OK, 'tmp_name' => "$filename.aaa" ], false );
		$this->assertFileNotExists( "$filename.aaa", 'sanity check' );
		$this->assertFileNotExists( "$filename.bbb", 'sanity check' );
		try {
			$file->moveTo( $filename . '.bbb' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \PHPUnit\Framework\AssertionFailedError $ex ) {
			throw $ex;
		} catch ( RuntimeException $ex ) {
			$this->assertSame( 'Uploaded file is missing', $ex->getMessage() );
		}
		$this->assertFileNotExists( "$filename.aaa" );
		$this->assertFileNotExists( "$filename.bbb" );

		// Fails for non-upload file (when not flagged to ignore that)
		$filename = $this->makeTemp( __FUNCTION__ );
		$this->assertFileExists( $filename, 'sanity check' );
		$this->assertFileNotExists( "$filename.xxx", 'sanity check' );
		$file = new UploadedFile( [ 'error' => UPLOAD_ERR_OK, 'tmp_name' => $filename ] );
		try {
			$file->moveTo( $filename . '.xxx' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \PHPUnit\Framework\AssertionFailedError $ex ) {
			throw $ex;
		} catch ( RuntimeException $ex ) {
			$this->assertSame( 'Specified file is not an uploaded file', $ex->getMessage() );
		}
		$this->assertFileExists( $filename );
		$this->assertFileNotExists( "$filename.xxx" );

		// Fails for error uploads
		$filename = $this->makeTemp( __FUNCTION__ );
		$this->assertFileExists( $filename, 'sanity check' );
		$this->assertFileNotExists( "$filename.xxx", 'sanity check' );
		foreach ( [
			UPLOAD_ERR_INI_SIZE,
			UPLOAD_ERR_FORM_SIZE,
			UPLOAD_ERR_PARTIAL,
			UPLOAD_ERR_NO_FILE,
			UPLOAD_ERR_NO_TMP_DIR,
			UPLOAD_ERR_CANT_WRITE,
			UPLOAD_ERR_EXTENSION,
			-42
		] as $code ) {
			$file = new UploadedFile( [ 'error' => $code, 'tmp_name' => $filename ], false );
			try {
				$file->moveTo( $filename . '.xxx' );
				$this->fail( 'Expected exception not thrown' );
			} catch ( \PHPUnit\Framework\AssertionFailedError $ex ) {
				throw $ex;
			} catch ( RuntimeException $ex ) {
			}
			$this->assertFileExists( $filename );
			$this->assertFileNotExists( "$filename.xxx" );
		}

		// Move failure triggers exception
		$filename = $this->makeTemp( __FUNCTION__, 'file1' );
		$filename2 = $this->makeTemp( __FUNCTION__, 'file2' );
		$this->assertFileExists( $filename, 'sanity check' );
		$file = new UploadedFile( [ 'error' => UPLOAD_ERR_OK, 'tmp_name' => $filename ], false );
		try {
			$file->moveTo( $filename2 . DIRECTORY_SEPARATOR . 'foobar' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \PHPUnit\Framework\AssertionFailedError $ex ) {
			throw $ex;
		} catch ( RuntimeException $ex ) {
		}
		$this->assertFileExists( $filename );
	}

	public function testInfoMethods() {
		$filename = $this->makeTemp( __FUNCTION__ );
		$file = new UploadedFile( [
			'name' => 'C:\\example.txt',
			'type' => 'text/plain',
			'size' => 1025,
			'error' => UPLOAD_ERR_OK,
			'tmp_name' => $filename,
		], false );
		$this->assertSame( 1025, $file->getSize() );
		$this->assertSame( UPLOAD_ERR_OK, $file->getError() );
		$this->assertSame( 'C:\\example.txt', $file->getClientFilename() );
		$this->assertSame( 'text/plain', $file->getClientMediaType() );

		// None of these are allowed to error
		$file = new UploadedFile( [], false );
		$this->assertSame( null, $file->getSize() );
		$this->assertSame( UPLOAD_ERR_NO_FILE, $file->getError() );
		$this->assertSame( null, $file->getClientFilename() );
		$this->assertSame( null, $file->getClientMediaType() );

		// "if none was provided" behavior, given that $_FILES often contains
		// the empty string.
		$file = new UploadedFile( [
			'name' => '',
			'type' => '',
			'size' => 100,
			'error' => UPLOAD_ERR_NO_FILE,
			'tmp_name' => $filename,
		], false );
		$this->assertSame( null, $file->getClientFilename() );
		$this->assertSame( null, $file->getClientMediaType() );
	}

}

<?php

namespace Wikimedia\ParamValidator\Util;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Wikimedia\AtEase\AtEase;

class UploadedFileTestBase extends \PHPUnit\Framework\TestCase {

	/** @var string|null */
	protected static $tmpdir;

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();

		// Create a temporary directory for this test's files.
		self::$tmpdir = null;
		$base = sys_get_temp_dir() . DIRECTORY_SEPARATOR .
			'phpunit-ParamValidator-UploadedFileTest-' . time() . '-' . getmypid() . '-';
		for ( $i = 0; $i < 10000; $i++ ) {
			$dir = $base . sprintf( '%04d', $i );
			if ( AtEase::quietCall( 'mkdir', $dir, 0700, false ) === true ) {
				self::$tmpdir = $dir;
				break;
			}
		}
		if ( self::$tmpdir === null ) {
			self::fail( "Could not create temporary directory '{$base}XXXX'" );
		}
	}

	public static function tearDownAfterClass() {
		parent::tearDownAfterClass();

		// Clean up temporary directory.
		if ( self::$tmpdir !== null ) {
			$iter = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( self::$tmpdir, RecursiveDirectoryIterator::SKIP_DOTS ),
				RecursiveIteratorIterator::CHILD_FIRST
			);
			foreach ( $iter as $file ) {
				if ( $file->isDir() ) {
					rmdir( $file->getRealPath() );
				} else {
					unlink( $file->getRealPath() );
				}
			}
			rmdir( self::$tmpdir );
			self::$tmpdir = null;
		}
	}

	protected static function assertTmpdir() {
		if ( self::$tmpdir === null || !is_dir( self::$tmpdir ) ) {
			self::fail( 'No temporary directory for ' . static::class );
		}
	}

	/**
	 * @param string $prefix For tempnam()
	 * @param string $content Contents of the file
	 * @return string Filename
	 */
	protected function makeTemp( $prefix, $content = 'foobar' ) {
		self::assertTmpdir();

		$filename = tempnam( self::$tmpdir, $prefix );
		if ( $filename === false ) {
			self::fail( 'Failed to create temporary file' );
		}

		self::assertSame(
			strlen( $content ),
			file_put_contents( $filename, $content ),
			'Writing test temporary file'
		);

		return $filename;
	}

}

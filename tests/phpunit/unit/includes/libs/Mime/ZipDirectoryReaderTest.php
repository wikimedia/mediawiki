<?php

namespace Wikimedia\Mime\Tests;

use MediaWikiUnitTestCase;
use Wikimedia\Mime\ZipDirectoryReader;

/**
 * @group Mime
 * @covers \Wikimedia\Mime\ZipDirectoryReader
 */
class ZipDirectoryReaderTest extends MediaWikiUnitTestCase {
	private const ZIP_DIR = MW_INSTALL_PATH . '/tests/phpunit/data/zip';

	/** @var array[] */
	protected $entries;

	public function zipCallback( $entry ) {
		$this->entries[] = $entry;
	}

	public function readZipAssertError( $file, $error, $assertMessage ) {
		$this->entries = [];
		$status = ZipDirectoryReader::read(
			self::ZIP_DIR . "/$file",
			[
				$this,
				'zipCallback',
			]
		);
		$this->assertStatusError(
			$error,
			$status,
			$assertMessage
		);
	}

	public function readZipAssertSuccess( $file, $assertMessage ) {
		$this->entries = [];
		$status = ZipDirectoryReader::read(
			self::ZIP_DIR . "/$file",
			[
				$this,
				'zipCallback',
			]
		);
		$this->assertStatusOK(
			$status,
			$assertMessage
		);
	}

	public function testEmpty() {
		$this->readZipAssertSuccess(
			'empty.zip',
			'Empty zip'
		);
	}

	public function testMultiDisk0() {
		$this->readZipAssertError(
			'split.zip',
			'zip-unsupported',
			'Split zip error'
		);
	}

	public function testNoSignature() {
		$this->readZipAssertError(
			'nosig.zip',
			'zip-wrong-format',
			'No signature should give "wrong format" error'
		);
	}

	public function testSimple() {
		$this->readZipAssertSuccess(
			'class.zip',
			'Simple ZIP'
		);
		$this->assertEquals( [
			[
				'name' => 'Class.class',
				'mtime' => '20010115000000',
				'size' => 1,
			],
		],
			$this->entries );
	}

	public function testBadCentralEntrySignature() {
		$this->readZipAssertError(
			'wrong-central-entry-sig.zip',
			'zip-bad',
			'Bad central entry error'
		);
	}

	public function testTrailingBytes() {
		// Due to T40432 this is now zip-wrong-format instead of zip-bad
		$this->readZipAssertError(
			'trail.zip',
			'zip-wrong-format',
			'Trailing bytes error'
		);
	}

	public function testWrongCDStart() {
		$this->readZipAssertError(
			'wrong-cd-start-disk.zip',
			'zip-unsupported',
			'Wrong CD start disk error'
		);
	}

	public function testCentralDirectoryGap() {
		$this->readZipAssertError(
			'cd-gap.zip',
			'zip-bad',
			'CD gap error'
		);
	}

	public function testCentralDirectoryTruncated() {
		$this->readZipAssertError(
			'cd-truncated.zip',
			'zip-bad',
			'CD truncated error (should hit unpack() overrun)'
		);
	}

	public function testLooksLikeZip64() {
		$this->readZipAssertError(
			'looks-like-zip64.zip',
			'zip-unsupported',
			'A file which looks like ZIP64 but isn\'t, should give error'
		);
	}
}

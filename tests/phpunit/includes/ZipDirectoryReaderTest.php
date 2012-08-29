<?php

class ZipDirectoryReaderTest extends MediaWikiTestCase {
	var $zipDir, $entries;

	function setUp() {
		$this->zipDir = dirname( __FILE__ ) . '/../data/zip';
	}

	function zipCallback( $entry ) {
		$this->entries[] = $entry;
	}

	function readZipAssertError( $file, $error, $assertMessage ) {
		$this->entries = array();
		$status = ZipDirectoryReader::read( "{$this->zipDir}/$file", array( $this, 'zipCallback' ) );
		$this->assertTrue( $status->hasMessage( $error ), $assertMessage );
	}

	function readZipAssertSuccess( $file, $assertMessage ) {
		$this->entries = array();
		$status = ZipDirectoryReader::read( "{$this->zipDir}/$file", array( $this, 'zipCallback' ) );
		$this->assertTrue( $status->isOK(), $assertMessage );
	}

	function testEmpty() {
		$this->readZipAssertSuccess( 'empty.zip', 'Empty zip' );
	}

	function testMultiDisk0() {
		$this->readZipAssertError( 'split.zip', 'zip-unsupported', 
			'Split zip error' );
	}

	function testNoSignature() {
		$this->readZipAssertError( 'nosig.zip', 'zip-wrong-format', 
			'No signature should give "wrong format" error' );
	}

	function testSimple() {
		$this->readZipAssertSuccess( 'class.zip', 'Simple ZIP' );
		$this->assertEquals( $this->entries, array( array(
			'name' => 'Class.class',
			'mtime' => '20010115000000',
			'size' => 1,
		) ) );
	}

	function testBadCentralEntrySignature() {
		$this->readZipAssertError( 'wrong-central-entry-sig.zip', 'zip-bad',
			'Bad central entry error' );
	}

	function testTrailingBytes() {
		$this->readZipAssertError( 'trail.zip', 'zip-bad',
			'Trailing bytes error' );
	}

	function testWrongCDStart() {
		$this->readZipAssertError( 'wrong-cd-start-disk.zip', 'zip-unsupported', 
			'Wrong CD start disk error' );
	}


	function testCentralDirectoryGap() {
		$this->readZipAssertError( 'cd-gap.zip', 'zip-bad',
			'CD gap error' );
	}

	function testCentralDirectoryTruncated() {
		$this->readZipAssertError( 'cd-truncated.zip', 'zip-bad',
			'CD truncated error (should hit unpack() overrun)' );
	}

	function testLooksLikeZip64() {
		$this->readZipAssertError( 'looks-like-zip64.zip', 'zip-unsupported',
			'A file which looks like ZIP64 but isn\'t, should give error' );
	}
}

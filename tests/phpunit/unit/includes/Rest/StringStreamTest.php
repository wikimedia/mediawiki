<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\StringStream;

/** @covers \MediaWiki\Rest\StringStream */
class StringStreamTest extends \MediaWikiUnitTestCase {
	public static function provideSeekGetContents() {
		return [
			[ 'abcde', 0, SEEK_SET, 'abcde' ],
			[ 'abcde', 1, SEEK_SET, 'bcde' ],
			[ 'abcde', 5, SEEK_SET, '' ],
			[ 'abcde', 1, SEEK_CUR, 'cde' ],
			[ 'abcde', 0, SEEK_END, '' ],
		];
	}

	/** @dataProvider provideSeekGetContents */
	public function testCopyToStream( $input, $offset, $whence, $expected ) {
		$ss = new StringStream;
		$ss->write( $input );
		$ss->seek( 1 );
		$ss->seek( $offset, $whence );
		$destStream = fopen( 'php://memory', 'w+' );
		$ss->copyToStream( $destStream );
		fseek( $destStream, 0 );
		$result = stream_get_contents( $destStream );
		$this->assertSame( $expected, $result );
	}

	public function testGetSize() {
		$ss = new StringStream;
		$this->assertSame( 0, $ss->getSize() );
		$ss->write( "hello" );
		$this->assertSame( 5, $ss->getSize() );
		$ss->rewind();
		$this->assertSame( 5, $ss->getSize() );
	}

	public function testTell() {
		$ss = new StringStream;
		$this->assertSame( $ss->tell(), 0 );
		$ss->write( "abc" );
		$this->assertSame( $ss->tell(), 3 );
		$ss->seek( 0 );
		$ss->read( 1 );
		$this->assertSame( $ss->tell(), 1 );
	}

	public function testEof() {
		$ss = new StringStream( 'abc' );
		$this->assertFalse( $ss->eof() );
		$ss->read( 1 );
		$this->assertFalse( $ss->eof() );
		$ss->read( 1 );
		$this->assertFalse( $ss->eof() );
		$ss->read( 1 );
		$this->assertTrue( $ss->eof() );
		$ss->rewind();
		$this->assertFalse( $ss->eof() );
	}

	public function testIsSeekable() {
		$ss = new StringStream;
		$this->assertTrue( $ss->isSeekable() );
	}

	public function testIsReadable() {
		$ss = new StringStream;
		$this->assertTrue( $ss->isReadable() );
	}

	public function testIsWritable() {
		$ss = new StringStream;
		$this->assertTrue( $ss->isWritable() );
	}

	public function testSeekWrite() {
		$ss = new StringStream;
		$this->assertSame( '', (string)$ss );
		$ss->write( 'a' );
		$this->assertSame( 'a', (string)$ss );
		$ss->write( 'b' );
		$this->assertSame( 'ab', (string)$ss );
		$ss->seek( 1 );
		$ss->write( 'c' );
		$this->assertSame( 'ac', (string)$ss );
	}

	/** @dataProvider provideSeekGetContents */
	public function testSeekGetContents( $input, $offset, $whence, $expected ) {
		$ss = new StringStream( $input );
		$ss->seek( 1 );
		$ss->seek( $offset, $whence );
		$this->assertSame( $expected, $ss->getContents() );
	}

	public static function provideSeekRead() {
		return [
			[ 'abcde', 0, SEEK_SET, 1, 'a' ],
			[ 'abcde', 0, SEEK_SET, 2, 'ab' ],
			[ 'abcde', 4, SEEK_SET, 2, 'e' ],
			[ 'abcde', 5, SEEK_SET, 1, '' ],
			[ 'abcde', 1, SEEK_CUR, 1, 'c' ],
			[ 'abcde', 0, SEEK_END, 1, '' ],
			[ 'abcde', -1, SEEK_END, 1, 'e' ],
		];
	}

	/** @dataProvider provideSeekRead */
	public function testSeekRead( $input, $offset, $whence, $length, $expected ) {
		$ss = new StringStream( $input );
		$ss->seek( 1 );
		$ss->seek( $offset, $whence );
		$this->assertSame( $expected, $ss->read( $length ) );
	}

	/** @expectedException \InvalidArgumentException */
	public function testReadBeyondEnd() {
		$ss = new StringStream( 'abc' );
		$ss->seek( 1, SEEK_END );
	}

	/** @expectedException \InvalidArgumentException */
	public function testReadBeforeStart() {
		$ss = new StringStream( 'abc' );
		$ss->seek( -1 );
	}
}

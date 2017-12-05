<?php

namespace MediaWiki\ExternalStore;

use Exception;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

class MultiContentBlobTest extends \MediaWikiTestCase {

	/**
	 * @dataProvider provideEncodeInt
	 * @covers MediaWiki\ExternalStore\MultiContentBlob::encodeInt
	 * @param int $n
	 * @param string|Exception $expect If a string, hex-encoded
	 */
	public function testEncodeInt( $n, $expect ) {
		$w = TestingAccessWrapper::newFromClass( MultiContentBlob::class );

		if ( $expect instanceof Exception ) {
			$this->setExpectedException( get_class( $expect ), $expect->getMessage() );
		}

		$str = $w->encodeInt( $n );
		if ( !$expect instanceof Exception ) {
			$this->assertSame( $expect, bin2hex( $str ) );
		}
	}

	public static function provideEncodeInt() {
		$ex = new \InvalidArgumentException( '$n must be a positive integer' );

		return [
			[ 0, '00' ],
			[ 1, '01' ],
			[ 127, '7f' ],
			[ 128, '8100' ],
			[ 16383, 'ff7f' ],
			[ 0x123456789, '929a95cf09' ],
			[ -1, $ex ],
			[ '0', $ex ],
			[ 1.5, $ex ],
		];
	}

	/**
	 * @dataProvider provideConsumeInt
	 * @covers MediaWiki\ExternalStore\MultiContentBlob::consumeInt
	 * @param string $str Hex-encoded
	 * @param int $pos Input position
	 * @param int|Exception $expect
	 * @param int $expectPos Output $pos
	 */
	public function testConsumeInt( $str, $pos, $expect, $expectPos ) {
		$w = TestingAccessWrapper::newFromClass( MultiContentBlob::class );

		if ( $expect instanceof Exception ) {
			$this->setExpectedException( get_class( $expect ), $expect->getMessage() );
		}

		$str = hex2bin( $str );
		$n = $w->__call( 'consumeInt', [ $str, &$pos ] );
		if ( !$expect instanceof Exception ) {
			$this->assertSame( $expect, $n );
			$this->assertSame( $expectPos, $pos );
		}
	}

	public static function provideConsumeInt() {
		$underflow = new \UnderflowException( 'Unexpected end of blob' );
		$overflow = new \OverflowException( 'Encoded integer is too large' );

		return [
			[ '00', 0, 0, 1 ],
			[ '00ff', 0, 0, 1 ],
			[ '01', 0, 1, 1 ],
			[ '7f', 0, 127, 1 ],
			[ '7fff', 0, 127, 1 ],
			[ '8100', 0, 128, 2 ],
			[ '810012', 0, 128, 2 ],
			[ 'ff7f', 0, 16383, 2 ],
			[ '929a95cf09', 0, 0x123456789, 5 ],
			[ '929a95cf09929a95cf09', 0, 0x123456789, 5 ],
			[ '', 0, $underflow, 0 ],
			[ '92', 0, $underflow, 0 ],
			[ 'ffffffffffffffffffffffffffffffffffff', 0, $overflow, 0 ],
			[ '929a95cf0901', 3, 0x2789, 5 ],
		];
	}

	/**
	 * @dataProvider provideConsumeStr
	 * @covers MediaWiki\ExternalStore\MultiContentBlob::encodeStr
	 * @covers MediaWiki\ExternalStore\MultiContentBlob::consumeStr
	 * @param string $str Hex-encoded
	 * @param int $pos Input position
	 * @param string|Exception $expect
	 * @param int $expectPos Output $pos
	 */
	public function testConsumeStr( $str, $pos, $expect, $expectPos ) {
		$w = TestingAccessWrapper::newFromClass( MultiContentBlob::class );

		if ( $expect instanceof Exception ) {
			$this->setExpectedException( get_class( $expect ), $expect->getMessage() );
		} else {
			// Test encoding here too
			$this->assertSame(
				substr( $str, $pos * 2, ( $expectPos - $pos ) * 2 ),
				bin2hex( $w->encodeStr( $expect ) )
			);
		}

		$str = hex2bin( $str );
		$ret = $w->__call( 'consumeStr', [ $str, &$pos ] );
		if ( !$expect instanceof Exception ) {
			$this->assertSame( $expect, $ret );
			$this->assertSame( $expectPos, $pos );
		}
	}

	public static function provideConsumeStr() {
		$underflow = new \UnderflowException( 'Unexpected end of blob' );
		$overflow = new \OverflowException( 'Encoded integer is too large' );

		return [
			[ '00', 0, '', 1 ],
			[ '00ff', 0, '', 1 ],
			[ '0141', 0, 'A', 2 ],
			[ '8101' . str_repeat( '58', 131 ), 0, str_repeat( 'X', 129 ), 131 ],
			[ '', 0, $underflow, 0 ],
			[ '01', 0, $underflow, 0 ],
			[ '034141', 0, $underflow, 0 ],
			[ 'ffffffffffffffffffffffffffffffffffff', 0, $overflow, 0 ],
			[ '0641014243', 2, 'B', 4 ],
		];
	}

	/**
	 * @dataProvider provideRoundTrip
	 * @covers MediaWiki\ExternalStore\MultiContentBlob::encode
	 * @covers MediaWiki\ExternalStore\MultiContentBlob::decode
	 * @param array $items
	 * @param array|null $metadata
	 */
	public function testRoundTrip( $items, $metadata ) {
		$w = TestingAccessWrapper::newFromClass( MultiContentBlob::class );
		$knownTypes = $w->knownTypes;
		$reset = new ScopedCallback( [ $w, '__set' ], [ 'knownTypes', $knownTypes ] );
		$w->knownTypes = [ 'test' => TestMultiContentBlob::class ] + $knownTypes;

		$obj = new TestMultiContentBlob;
		$obj->items = $items;
		$obj->metadata = $metadata;

		$blob = $obj->encode();
		$this->assertInternalType( 'string', $blob );
		$copy = $blob;
		$newObj = MultiContentBlob::decode( $blob );
		$this->assertTrue( $blob === $copy, '$blob wasn\'t modified' );
		$this->assertInstanceOf( TestMultiContentBlob::class, $newObj );
		$this->assertEquals( $items, $newObj->items );
		$this->assertSame( $metadata, $newObj->metadata );
	}

	public static function provideRoundTrip() {
		return [
			'empty' => [ [], null ],
			'empty with empty metadata' => [ [], [] ],
			'array' => [ [ 'foo', 'bar', 'baz', 'bar' ], null ],
			'assoc' => [ [ 'foo' => '1', 'bar' => '3', 'baz' => '42' ], null ],
			'array with metadata' => [ [ 'foo', 'bar', 'baz', 'bar' ], [ 'meta' => 'M', 'null' => null ] ],
			'assoc with metadata' => [ [ 'foo' => 'bar' ], [ 'meta' => 'M', 'null' => null ] ],
			'repetitive data (should be compressed)' => [ [ str_repeat( 'x', 10000 ) ], null ],
			'misordered array' => [ [ 1 => 'bar', 3 => 'bar', 2 => 'baz', 0 => 'foo' ], null ],
		];
	}

	/**
	 * @covers MediaWiki\ExternalStore\MultiContentBlob::encode
	 * @expectedException \OutOfBoundsException
	 */
	public function testEncodeBadClass() {
		$obj = new TestMultiContentBlob;
		$obj->encode();
	}

	/**
	 * @covers MediaWiki\ExternalStore\MultiContentBlob::encode
	 * @expectedException \OverflowException
	 */
	public function testEncodeTooBig() {
		$w = TestingAccessWrapper::newFromClass( MultiContentBlob::class );
		$knownTypes = $w->knownTypes;
		$reset = new ScopedCallback( function () use ( $w, $knownTypes ) {
			$w->knownTypes = $knownTypes;
			$w->useCompression = true;
			$w->maxSize = MultiContentBlob::MAX_SIZE;
		} );
		$w->knownTypes = [ 'test' => TestMultiContentBlob::class ] + $knownTypes;
		$w->useCompression = false;
		$w->maxSize = 1000;

		$obj = new TestMultiContentBlob;
		for ( $i = 0; $i < 1000; $i++ ) {
			$obj->items[] = 'foobar';
		}
		$obj->encode();
	}

	/**
	 * @dataProvider provideDecodeErrors
	 * @covers MediaWiki\ExternalStore\MultiContentBlob::decode
	 * @param string $blob
	 * @param Exception $ex
	 * @param bool $suppressWarnings
	 */
	public function testDecodeErrors( $blob, $ex, $suppressWarnings = false ) {
		$w = TestingAccessWrapper::newFromClass( MultiContentBlob::class );
		$knownTypes = $w->knownTypes;
		$reset = new ScopedCallback( [ $w, '__set' ], [ 'knownTypes', $knownTypes ] );
		$w->knownTypes = [ 'test' => TestMultiContentBlob::class ] + $knownTypes;

		if ( $suppressWarnings ) {
			\MediaWiki\suppressWarnings();
			$reset2 = new ScopedCallback( '\MediaWiki\restoreWarnings' );
		}

		$this->setExpectedException( get_class( $ex ), $ex->getMessage() );
		MultiContentBlob::decode( $blob );
	}

	public static function provideDecodeErrors() {
		return [
			[
				'ABC',
				new \UnderflowException( 'Unexpected end of blob' )
			],
			[
				"\x7FMCB\xFE\xFF\x04test\x00\x00\x00ABCD",
				new \UnexpectedValueException( 'Blob header is missing' ),
			],
			[
				"\x7FMCB\xFF\xFE\x04test\x00\x00\x00ABCD",
				new \UnexpectedValueException( 'Blob CRC check failed' ),
			],
			[
				"\x7FMCB\xFF\xFE\x04XXXX\x00\x00\x00\x93\x3C\x05d",
				new \OutOfBoundsException( 'Unknown blob type "XXXX"' )
			],
			[
				"\x7FMCB\xFF\xFE\x04test\x04\x00\x00x9\xE4\xB0",
				new \UnexpectedValueException( 'Unknown blob flags' ),
			],
			[
				"\x7FMCB\xFF\xFE\x04test\x01\x00\x00~\xF2\x26\x5B",
				new \UnexpectedValueException( 'gzinflate failed' ),
				true
			],
			[
				"\x7FMCB\xFF\xFE\x04test\x00\x02\x7B\x5D\x00\x0AC\x28\xFC",
				new \UnexpectedValueException( 'Blob metadata JSON decode failed: ' ),
			],
			[
				"\x7FMCB\xFF\xFE\x04test\x00\x00\x00\x00\x96y\xF2\xB2",
				new \UnexpectedValueException( 'Extra data found in blob' ),
			],
		];
	}

	/**
	 * @dataProvider provideCheckHeader
	 * @covers MediaWiki\ExternalStore\MultiContentBlob::checkHeader
	 * @param string $blob
	 * @param string|bool $expect
	 */
	public function testCheckHeader( $blob, $expect ) {
		$w = TestingAccessWrapper::newFromClass( MultiContentBlob::class );
		$knownTypes = $w->knownTypes;
		$reset = new ScopedCallback( [ $w, '__set' ], [ 'knownTypes', $knownTypes ] );
		$w->knownTypes = [ 'test' => TestMultiContentBlob::class ] + $knownTypes;

		$this->assertSame( $expect, MultiContentBlob::checkHeader( $blob ) );
	}

	public static function provideCheckHeader() {
		return [
			[
				'ABC',
				'Too short',
			],
			[
				"\x7FMCB\xFE\xFF\x04test\x00\x00\x00ABCD",
				'Blob header is missing',
			],
			[
				"\x7FMCB\xFF\xFE\x04test\x00\x00\x00ABCD",
				true,
			],
			[
				"\x7FMCB\xFF\xFE\x04tes",
				'Unexpected end of blob',
			],
			[
				"\x7FMCB\xFF\xFE\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF",
				'Encoded integer is too large',
			],
			[
				"\x7FMCB\xFF\xFE\x04XXXX\x00\x00\x00\x93\x3C\x05d",
				'Unknown blob type "XXXX"',
			],
			[
				"\x7FMCB\xFF\xFE\x04test\x04\x00\x00x9\xE4\xB0",
				true,
			],
			[
				"\x7FMCB\xFF\xFE\x04test\x01\x00\x00~\xF2\x26\x5B",
				true,
			],
			[
				"\x7FMCB\xFF\xFE\x04test\x00\x02\x7B\x5D\x00\x0AC\x28\xFC",
				true,
			],
			[
				"\x7FMCB\xFF\xFE\x04test\x00\x00\x00\x00\x96y\xF2\xB2",
				true,
			],
		];
	}

}

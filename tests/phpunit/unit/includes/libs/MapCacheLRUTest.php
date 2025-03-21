<?php

namespace Wikimedia\Tests;

use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;
use Wikimedia\MapCacheLRU\MapCacheLRU;

/**
 * @group Cache
 * @covers \Wikimedia\MapCacheLRU\MapCacheLRU
 */
class MapCacheLRUTest extends TestCase {

	use MediaWikiCoversValidator;

	public function testArrayConversion() {
		$raw = [ 'd' => 4, 'c' => 3, 'b' => 2, 'a' => 1 ];
		$cache = MapCacheLRU::newFromArray( $raw, 3 );

		$this->assertEquals( 3, $cache->getMaxSize() );
		$this->assertSame( true, $cache->has( 'a' ) );
		$this->assertSame( true, $cache->has( 'b' ) );
		$this->assertSame( true, $cache->has( 'c' ) );
		$this->assertSame( 1, $cache->get( 'a' ) );
		$this->assertSame( 2, $cache->get( 'b' ) );
		$this->assertSame( 3, $cache->get( 'c' ) );

		$this->assertSame(
			[ 'a' => 1, 'b' => 2, 'c' => 3 ],
			$cache->toArray()
		);
		$this->assertSame(
			[ 'a', 'b', 'c' ],
			$cache->getAllKeys()
		);

		$cache->clear( 'a' );
		$this->assertSame(
			[ 'b' => 2, 'c' => 3 ],
			$cache->toArray()
		);

		$cache->clear();
		$this->assertSame(
			[],
			$cache->toArray()
		);

		$cache = MapCacheLRU::newFromArray( [ 'd' => 4, 'c' => 3, 'b' => 2, 'a' => 1 ], 4 );
		$cache->setMaxSize( 3 );
		$this->assertSame(
			[ 'c' => 3, 'b' => 2, 'a' => 1 ],
			$cache->toArray()
		);
	}

	public function testSerialize() {
		$cache = new MapCacheLRU( 3 );
		$cache->set( 'a', 1 );
		$cache->set( 'b', 2 );
		$cache->set( 'c', 3 );

		$string = serialize( $cache );
		$cache = unserialize( $string );
		$this->assertSame(
			[ 'a' => 1, 'b' => 2, 'c' => 3 ],
			$cache->toArray(),
			'entries are preserved'
		);

		$cache->set( 'd', 4 );
		$this->assertSame(
			[ 'b' => 2, 'c' => 3, 'd' => 4 ],
			$cache->toArray(),
			'maxKeys is preserved'
		);
	}

	public function testGetWithSetCallback() {
		$cache = new MapCacheLRU( 3 );
		$i = 0;
		$fn = static function () use ( &$i ) {
			$i++;
			return $i;
		};

		$this->assertSame( null, $cache->get( 'a' ), 'initial' );
		$this->assertSame( 1, $cache->getWithSetCallback( 'a', $fn ), 'first gen' );
		$this->assertSame( 1, $cache->getWithSetCallback( 'a', $fn ), 'getWithSet hit' );
		$this->assertSame( 1, $cache->get( 'a' ), 'plain get hit' );
		$cache->clear( 'a' );
		$this->assertSame( 2, $cache->getWithSetCallback( 'a', $fn ), 'second gen' );
	}

	public function testMissing() {
		$raw = [ 'a' => 1, 'b' => 2, 'c' => 3 ];
		$cache = MapCacheLRU::newFromArray( $raw, 3 );

		$this->assertFalse( $cache->has( 'd' ) );
		$this->assertNull( $cache->get( 'd' ) );
		$this->assertNull( $cache->get( 'd', 0.0, null ) );
		$this->assertFalse( $cache->get( 'd', 0.0, false ) );
	}

	public function testLRU() {
		$raw = [ 'a' => 1, 'b' => 2, 'c' => 3 ];
		$cache = MapCacheLRU::newFromArray( $raw, 3 );

		$this->assertSame( true, $cache->has( 'c' ) );
		$this->assertSame(
			[ 'a' => 1, 'b' => 2, 'c' => 3 ],
			$cache->toArray()
		);

		$this->assertSame( 3, $cache->get( 'c' ) );
		$this->assertSame(
			[ 'a' => 1, 'b' => 2, 'c' => 3 ],
			$cache->toArray()
		);

		$this->assertSame( 1, $cache->get( 'a' ) );
		$this->assertSame(
			[ 'b' => 2, 'c' => 3, 'a' => 1 ],
			$cache->toArray()
		);

		$cache->set( 'a', 1 );
		$this->assertSame(
			[ 'b' => 2, 'c' => 3, 'a' => 1 ],
			$cache->toArray()
		);

		$cache->set( 'b', 22 );
		$this->assertSame(
			[ 'c' => 3, 'a' => 1, 'b' => 22 ],
			$cache->toArray()
		);

		$cache->set( 'd', 4 );
		$this->assertSame(
			[ 'a' => 1, 'b' => 22, 'd' => 4 ],
			$cache->toArray()
		);

		$cache->set( 'e', 5, 0.33 );
		$this->assertSame(
			[ 'e' => 5, 'b' => 22, 'd' => 4 ],
			$cache->toArray()
		);

		$cache->set( 'f', 6, 0.66 );
		$this->assertSame(
			[ 'b' => 22, 'f' => 6, 'd' => 4 ],
			$cache->toArray()
		);

		$cache->set( 'g', 7, 0.90 );
		$this->assertSame(
			[ 'f' => 6, 'g' => 7, 'd' => 4 ],
			$cache->toArray()
		);

		$cache->set( 'g', 7, 1.0 );
		$this->assertSame(
			[ 'f' => 6, 'd' => 4, 'g' => 7 ],
			$cache->toArray()
		);
	}

	public function testExpiry() {
		$raw = [ 'a' => 1, 'b' => 2, 'c' => 3 ];
		$cache = MapCacheLRU::newFromArray( $raw, 3 );

		$now = microtime( true );
		$cache->setMockTime( $now );

		$cache->set( 'd', 'xxx' );
		$this->assertTrue( $cache->has( 'd', 30 ) );
		$this->assertEquals( 'xxx', $cache->get( 'd' ) );

		$now += 29;
		$this->assertTrue( $cache->has( 'd', 30 ) );
		$this->assertEquals( 'xxx', $cache->get( 'd' ) );
		$this->assertEquals( 'xxx', $cache->get( 'd', 30 ) );

		$now += 1.5;
		$this->assertFalse( $cache->has( 'd', 30 ) );
		$this->assertEquals( 'xxx', $cache->get( 'd' ) );
		$this->assertNull( $cache->get( 'd', 30 ) );
	}

	public function testFields() {
		$raw = [ 'a' => 1, 'b' => 2, 'c' => 3 ];
		$cache = MapCacheLRU::newFromArray( $raw, 3 );

		$now = microtime( true );
		$cache->setMockTime( $now );

		$cache->setField( 'PMs', 'Tony Blair', 'Labour' );
		$cache->setField( 'PMs', 'Margaret Thatcher', 'Tory' );
		$this->assertTrue( $cache->hasField( 'PMs', 'Tony Blair', 30 ) );
		$this->assertEquals( 'Labour', $cache->getField( 'PMs', 'Tony Blair' ) );
		$this->assertTrue( $cache->hasField( 'PMs', 'Tony Blair', 30 ) );

		$now += 29;
		$this->assertTrue( $cache->hasField( 'PMs', 'Tony Blair', 30 ) );
		$this->assertEquals( 'Labour', $cache->getField( 'PMs', 'Tony Blair' ) );
		$this->assertEquals( 'Labour', $cache->getField( 'PMs', 'Tony Blair', 30 ) );

		$now += 1.5;
		$this->assertFalse( $cache->hasField( 'PMs', 'Tony Blair', 30 ) );
		$this->assertEquals( 'Labour', $cache->getField( 'PMs', 'Tony Blair' ) );
		$this->assertNull( $cache->getField( 'PMs', 'Tony Blair', 30 ) );

		$this->assertEquals(
			[ 'Tony Blair' => 'Labour', 'Margaret Thatcher' => 'Tory' ],
			$cache->get( 'PMs' )
		);

		$cache->set( 'MPs', [
			'Edwina Currie' => 1983,
			'Neil Kinnock' => 1970
		] );
		$this->assertEquals(
			[
				'Edwina Currie' => 1983,
				'Neil Kinnock' => 1970
			],
			$cache->get( 'MPs' )
		);

		$this->assertEquals( 1983, $cache->getField( 'MPs', 'Edwina Currie' ) );
		$this->assertEquals( 1970, $cache->getField( 'MPs', 'Neil Kinnock' ) );
	}

	public static function provideInvalidKeys() {
		yield [ 3.4 ];
		yield [ false ];
	}

	/**
	 * @dataProvider provideInvalidKeys
	 */
	public function testHasInvalidKey( $key ) {
		$cache = new MapCacheLRU( 3 );
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage( 'must be string or integer' );
		$cache->has( $key );
	}

	public static function provideMakeKey() {
		yield [ 'foo', 'foo' ];
		yield [ 'foo:bar:4:baz:qu%3Aux%2520', 'foo', 'bar', 4, 'baz', 'qu:ux%20' ];
		yield [ 'qu%3Aux%2520:4', 'qu:ux%20', 4 ];
	}

	/**
	 * @dataProvider provideMakeKey
	 */
	public function testMakeKey( $expected, ...$params ) {
		$cache = new MapCacheLRU( 3 );
		$this->assertSame( $expected, $cache->makeKey( ...$params ) );
	}

	/**
	 * @dataProvider provideInvalidKeys
	 */
	public function testGetInvalidKey( $key ) {
		$cache = new MapCacheLRU( 3 );
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage( 'must be string or integer' );
		$cache->get( $key );
	}

	/**
	 * @dataProvider provideInvalidKeys
	 */
	public function testSetInvalidKey( $key ) {
		$cache = new MapCacheLRU( 3 );
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage( 'must be string or integer' );
		$cache->set( $key, 'x' );
	}

	/**
	 * @dataProvider provideInvalidKeys
	 */
	public function testHasFieldInvalidKey( $field ) {
		$cache = MapCacheLRU::newFromArray( [ 'key' => [] ], 3 );
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage( 'must be string or integer' );
		$cache->hasField( 'key', $field );
	}

	/**
	 * @dataProvider provideInvalidKeys
	 */
	public function testGetFieldInvalidKey( $field ) {
		$cache = MapCacheLRU::newFromArray( [ 'key' => [] ], 3 );
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage( 'must be string or integer' );
		$cache->getField( 'key', $field );
	}

	/**
	 * @dataProvider provideInvalidKeys
	 */
	public function testSetFieldInvalidKey( $field ) {
		$cache = new MapCacheLRU( 3 );
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage( 'must be string or integer' );
		$cache->setField( 'key', $field, 'x' );
	}
}
